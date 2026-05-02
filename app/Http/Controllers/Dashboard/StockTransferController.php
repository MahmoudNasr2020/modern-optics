<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\BranchStock;
// use App\glassLense; // LENSES DISABLED - products only
use App\Http\Controllers\Controller;
use App\Product;
use App\Services\NotificationService;
use App\StockTransfer;
use Illuminate\Http\Request;
use PDF;

class StockTransferController extends Controller
{
    public function __construct()
    {
        // Permissions
        $this->middleware('permission.spatie:view-transfers')->only(['index', 'show', 'pending']);
        $this->middleware('permission.spatie:create-transfers')->only(['create', 'store', 'storeMulti', 'searchProducts']);
        $this->middleware('permission.spatie:approve-transfers')->only(['approve']);
        $this->middleware('permission.spatie:ship-transfers')->only(['ship']);
        $this->middleware('permission.spatie:receive-transfers')->only(['receive']);
        $this->middleware('permission.spatie:cancel-transfers')->only(['cancel']);
        $this->middleware('permission.spatie:view-transfer-reports')->only(['report', 'reportPdf', 'itemReport', 'itemReportPdf']);
        // Optional: delete-transfers if you add delete() method
        // $this->middleware('permission.spatie:delete-transfers')->only(['destroy']);
    }
    /**
     * Index - List all transfers
     */
    public function index(Request $request)
    {
        $query = StockTransfer::with(['fromBranch', 'toBranch', 'stockable', 'creator'])
            ->where('stockable_type', 'App\\Product');

        // Search: by transfer#, batch#, or product name
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transfer_number', 'LIKE', "%{$search}%")
                  ->orWhere('batch_number', 'LIKE', "%{$search}%")
                  ->orWhereHasMorph('stockable', [Product::class], function ($sq) use ($search) {
                      $sq->where('description', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Status filter — applied per-row before grouping
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // From branch filter
        if ($request->from_branch) {
            $query->where('from_branch_id', $request->from_branch);
        }

        // Get all matching rows ordered newest first
        $all = $query->latest()->get();

        // Group: batch transfers together, singles individually
        $groups = $all->groupBy(function ($t) {
            return $t->batch_number ?: ('__single__' . $t->id);
        })->values(); // collection of collections

        // Manual pagination over groups
        $perPage = 20;
        $page    = (int) $request->get('page', 1);
        $total   = $groups->count();
        $slice   = $groups->slice(($page - 1) * $perPage, $perPage)->values();

        $transfers = new \Illuminate\Pagination\LengthAwarePaginator(
            $slice, $total, $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $branches = Branch::all();

        $stats = [
            'pending'    => StockTransfer::where('status', 'pending')->count(),
            'approved'   => StockTransfer::where('status', 'approved')->count(),
            'in_transit' => StockTransfer::where('status', 'in_transit')->count(),
            'received'   => StockTransfer::where('status', 'received')->count(),
        ];

        return view('dashboard.pages.branches.stock.stock-transfers.index', compact('transfers', 'branches', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $branches = Branch::all();
        $products = Product::all();
        $store    = Branch::where('is_main', true)->where('is_active', true)->first();
        // $lenses = glassLense::all(); // LENSES DISABLED - products only

        return view('dashboard.pages.branches.stock.stock-transfers.create', compact('branches', 'products', 'store'));
    }

    /**
     * Check stock availability (AJAX)
     */
    public function checkStock(Request $request)
    {
        // $itemType = $request->item_type; // LENSES DISABLED - products only
        $itemId = $request->item_id;
        $fromBranchId = $request->from_branch_id;
        $toBranchId = $request->to_branch_id;

        $stockableType = 'App\\Product'; // always product

        // Get stock from source branch
        $fromStock = BranchStock::where('branch_id', $fromBranchId)
            ->where('stockable_type', $stockableType)
            ->where('stockable_id', $itemId)
            ->first();

        // Get stock from destination branch
        $toStock = BranchStock::where('branch_id', $toBranchId)
            ->where('stockable_type', $stockableType)
            ->where('stockable_id', $itemId)
            ->first();

        return response()->json([
            'success' => true,
            'from_stock' => $fromStock ? $fromStock->available_quantity : 0,
            'to_stock' => $toStock ? $toStock->quantity : 0,
        ]);
    }

    /**
     * Store transfer request
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id'   => 'required|exists:branches,id|different:from_branch_id',
            'item_type'      => 'required|in:product', // LENSES DISABLED: was in:product,lens
            'quantity'       => 'required|integer|min:1',
            'priority'       => 'nullable|in:low,medium,high,urgent',
        ]);

        // LENSES DISABLED - products only
        $request->validate(['product_id' => 'required|exists:products,id']);
        $stockableType = 'App\\Product';
        $stockableId   = $request->product_id;
        $productId     = $request->product_id;

        // Lens branch disabled:
        // if ($request->item_type === 'lens') {
        //     $request->validate(['lens_id' => 'required|exists:glass_lenses,id']);
        //     $stockableType = 'App\\glassLense';
        //     $stockableId   = $request->lens_id;
        //     $productId     = null;
        // }

        // Enforce: regular users must request FROM the main Store branch
        if (!auth()->user()->canSeeAllBranches()) {
            $store = Branch::where('is_main', true)->where('is_active', true)->first();
            if ($store && (int) $request->from_branch_id !== $store->id) {
                return back()->withErrors(['from_branch_id' => 'Transfer requests must come from the Store (Warehouse) branch.'])->withInput();
            }
        }

        // Check stock availability
        $stock = BranchStock::where('branch_id', $request->from_branch_id)
            ->where('stockable_type', $stockableType)
            ->where('stockable_id', $stockableId)
            ->first();

        if (!$stock || $stock->available_quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'Insufficient stock in source branch'])->withInput();
        }

        // Create transfer
        $transfer = StockTransfer::create([
            'from_branch_id' => $request->from_branch_id,
            'to_branch_id' => $request->to_branch_id,
            'product_id' => $productId,
            'stockable_type' => $stockableType,
            'stockable_id' => $stockableId,
            'quantity' => $request->quantity,
            'transfer_date' => now(),
            'status' => 'pending',
            'priority' => $request->priority ?? 'medium',
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);
        \Cache::forget('sidebar_pending_transfers');
        NotificationService::transferCreated($transfer);

        return redirect()->route('dashboard.stock-transfers.show', $transfer)
            ->with('success', 'Transfer request created successfully');
    }

    /**
     * Show transfer details.
     * If part of a batch → redirect to batch view automatically.
     */
    public function show(StockTransfer $stockTransfer)
    {
        $stockTransfer->load(['fromBranch', 'toBranch', 'stockable', 'creator', 'approver', 'shipper', 'receiver']);

        // If this transfer is part of a batch, redirect to the batch view
        if ($stockTransfer->batch_number) {
            return redirect()->route('dashboard.stock-transfers.batch', $stockTransfer->batch_number);
        }

        return view('dashboard.pages.branches.stock.stock-transfers.show', compact('stockTransfer'));
    }

    /**
     * Show pending transfers only
     */
    public function pending()
    {
        $transfers = StockTransfer::with(['fromBranch', 'toBranch', 'stockable', 'creator'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('dashboard.pages.branches.stock.stock-transfers.pending', compact('transfers'));
    }

    /**
     * Approve transfer
     */

    /**
     * Approve transfer
     */
    public function approve(Request $request, StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Only pending transfers can be approved.'], 422);
            }
            return back()->with('error', 'Only pending transfers can be approved');
        }

        try {
            $stockTransfer->approve(auth()->id());

            \Cache::forget('sidebar_pending_transfers');
            NotificationService::transferApproved($stockTransfer);

            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }
            return back()->with('success', 'Transfer request approved successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to approve transfer: ' . $e->getMessage());
        }
    }

    /**
     * Ship transfer
     */
    public function ship(StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status !== 'approved') {
            return back()->with('error', 'Only approved transfers can be shipped');
        }

        try {
            // Call model method
            $stockTransfer->ship(auth()->id());
            // ✅ إرسال إشعار
            NotificationService::transferInTransit($stockTransfer);

            return back()->with('success', 'Transfer marked as shipped');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to ship transfer: ' . $e->getMessage());
        }
    }

    /**
     * Receive transfer
     */
    public function receive(StockTransfer $stockTransfer)
    {
        if (!in_array($stockTransfer->status, ['approved', 'in_transit'])) {
            return back()->with('error', 'Invalid transfer status');
        }

        try {
            // Call model method
            $stockTransfer->receive(auth()->id());

            //notify
            NotificationService::transferReceived($stockTransfer);

            return back()->with('success', 'Transfer received and stock updated successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to receive transfer: ' . $e->getMessage());
        }
    }

    /**
     * Cancel transfer
     */
    public function cancel(Request $request, StockTransfer $stockTransfer)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if (in_array($stockTransfer->status, ['received', 'canceled'])) {
            return back()->with('error', 'This transfer cannot be canceled');
        }

        try {
            // Call model method
            $stockTransfer->cancel($request->rejection_reason);

            \Cache::forget('sidebar_pending_transfers');
            // ✅ إرسال إشعار الرفض
            NotificationService::transferRejected($stockTransfer, $request->rejection_reason);

            return back()->with('success', 'Transfer request canceled successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel transfer: ' . $e->getMessage());
        }
    }
    /**
     * Generate transfers report
     */
    public function report(Request $request)
    {
        $user = auth()->user();

        // Permission check
        if (!$user->can('view-transfer-reports')) {
            abort(403, 'Unauthorized: You do not have permission to view transfer reports.');
        }

        $query = StockTransfer::with(['fromBranch', 'toBranch', 'stockable', 'creator']);

        /**
         * 🔐 Branch access restriction
         * - Super Admin / access-all-branches → يشوف الكل
         * - User عادي → التحويلات اللي ليه طرف فيها (from أو to)
         */
        if (!$user->canSeeAllBranches()) {
            $query->where(function ($q) use ($user) {
                $q->where('from_branch_id', $user->branch_id)
                    ->orWhere('to_branch_id', $user->branch_id);
            });
        }

        // Date filters
        if ($request->filled('from_date')) {
            $query->whereDate('transfer_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('transfer_date', '<=', $request->to_date);
        }

        // Branch filter (from UI)
        if ($request->filled('branch_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('from_branch_id', $request->branch_id)
                    ->orWhere('to_branch_id', $request->branch_id);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transfers = $query->latest('transfer_date')->get();

        // Branches for filter dropdown
        $branches = $user->getAccessibleBranches();

        $statistics = $this->calculateStatistics($transfers);

        return view(
            'dashboard.pages.branches.stock.stock-transfers.report',
            compact('transfers', 'branches', 'statistics')
        );
    }



    /**
     * Calculate report statistics
     */
    private function calculateStatistics($transfers)
    {
        return [
            'total_transfers' => $transfers->count(),
            'total_quantity_transferred' => $transfers->where('status', 'received')->sum('quantity'),
            'pending_count' => $transfers->where('status', 'pending')->count(),
            'approved_count' => $transfers->where('status', 'approved')->count(),
            'in_transit_count' => $transfers->where('status', 'in_transit')->count(),
            'received_count' => $transfers->where('status', 'received')->count(),
            'canceled_count' => $transfers->where('status', 'canceled')->count(),
            'in_progress_count' => $transfers->whereIn('status', ['pending', 'approved', 'in_transit'])->count(),
        ];
    }

    /**
     * Generate PDF report
     */
    public function reportPdf(Request $request)
    {
        $query = StockTransfer::with(['fromBranch', 'toBranch', 'stockable', 'creator']);

        // Apply same filters as report
        if ($request->from_date) {
            $query->whereDate('transfer_date', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('transfer_date', '<=', $request->to_date);
        }

        if ($request->branch_id) {
            $query->where(function($q) use ($request) {
                $q->where('from_branch_id', $request->branch_id)
                    ->orWhere('to_branch_id', $request->branch_id);
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $transfers = $query->latest('transfer_date')->get();
        $branches = Branch::all();

        $pdf = PDF::loadview('dashboard.pages.branches.stock.stock-transfers.report_pdf', compact('transfers', 'branches', 'request'));

        return $pdf->download('stock-transfers-report-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Reject a pending transfer
     */
    public function reject(Request $request, StockTransfer $stockTransfer)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($stockTransfer->status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Only pending transfers can be rejected.'], 422);
            }
            return back()->with('error', 'Only pending transfers can be rejected.');
        }

        try {
            $stockTransfer->update([
                'status'           => 'canceled',
                'rejection_reason' => $request->rejection_reason,
            ]);

            \Cache::forget('sidebar_pending_transfers');
            NotificationService::transferRejected($stockTransfer, $request->rejection_reason);

            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }
            return back()->with('success', 'Transfer request rejected.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to reject transfer: ' . $e->getMessage());
        }
    }

    /**
     * Accept a pending transfer: approve + receive in one step (for branch employee)
     * This deducts stock from Store and immediately adds it to the destination branch.
     */
    public function accept(Request $request, StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Only pending transfers can be accepted.'], 422);
            }
            return back()->with('error', 'Only pending transfers can be accepted.');
        }

        try {
            // Step 1: approve (deducts from Store, sets status=approved)
            $stockTransfer->approve(auth()->id());

            // Step 2: receive (adds to destination branch, sets status=received)
            $stockTransfer->receive(auth()->id());

            \Cache::forget('sidebar_pending_transfers');
            NotificationService::transferReceived($stockTransfer);

            if ($request->expectsJson()) {
                return response()->json(['success' => true]);
            }
            return back()->with('success', "Transfer {$stockTransfer->transfer_number} accepted — stock updated.");

        } catch (\Exception $e) {
            // If approve succeeded but receive failed, try to rollback
            if ($stockTransfer->status === 'approved') {
                try { $stockTransfer->cancel('Accept failed — auto-rollback: ' . $e->getMessage()); } catch (\Exception $ex) {}
            }
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to accept transfer: ' . $e->getMessage());
        }
    }

    /**
     * Bulk transfer request form (Excel upload)
     */
    public function bulkRequestForm()
    {
        $user      = auth()->user();
        $store     = Branch::where('is_main', true)->where('is_active', true)->first();
        $toBranch  = $user->canSeeAllBranches() ? null : Branch::find($user->branch_id);
        $branches  = $user->getAccessibleBranches();

        return view('dashboard.pages.branches.stock.stock-transfers.bulk-request',
            compact('store', 'toBranch', 'branches'));
    }

    /**
     * Bulk transfer request store (process Excel)
     */
    public function bulkRequestStore(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
            'to_branch_id' => 'required|exists:branches,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $user     = auth()->user();
        $store    = Branch::where('is_main', true)->where('is_active', true)->first();

        if (!$store) {
            return back()->with('error', 'No main Store branch configured (is_main = true).');
        }

        $toBranchId = (int) $request->to_branch_id;

        // Enforce: regular users can only request to their own branch
        if (!$user->canSeeAllBranches() && $toBranchId !== (int) $user->branch_id) {
            return back()->with('error', 'You can only create transfer requests for your own branch.');
        }

        // Parse the Excel file
        $file = $request->file('excel_file');
        $rows = [];

        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext === 'csv') {
            // Parse CSV
            $handle = fopen($file->getPathname(), 'r');
            $header = null;
            while (($line = fgetcsv($handle)) !== false) {
                if (!$header) { $header = $line; continue; }
                if (count($line) >= 2) {
                    $rows[] = ['product_id' => trim($line[0]), 'quantity' => (int) trim($line[1])];
                }
            }
            fclose($handle);
        } else {
            // Parse XLSX using simple ZIP/XML approach
            try {
                $zip = new \ZipArchive();
                if ($zip->open($file->getPathname()) !== true) {
                    return back()->with('error', 'Could not open Excel file.');
                }
                $xml = $zip->getFromName('xl/worksheets/sheet1.xml');
                $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
                $zip->close();

                $shared = [];
                if ($sharedXml) {
                    $sx = simplexml_load_string($sharedXml);
                    foreach ($sx->si as $si) {
                        $shared[] = (string) $si->t;
                    }
                }

                $sx2 = simplexml_load_string($xml);
                $firstRow = true;
                foreach ($sx2->sheetData->row as $row) {
                    if ($firstRow) { $firstRow = false; continue; } // skip header
                    $cells = [];
                    foreach ($row->c as $cell) {
                        $t = (string) $cell['t'];
                        $v = (string) $cell->v;
                        $cells[] = ($t === 's') ? ($shared[(int)$v] ?? '') : $v;
                    }
                    if (count($cells) >= 2 && !empty($cells[0])) {
                        $rows[] = ['product_id' => trim($cells[0]), 'quantity' => max(1, (int) $cells[1])];
                    }
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to parse Excel file: ' . $e->getMessage());
            }
        }

        if (empty($rows)) {
            return back()->with('error', 'No valid rows found in the file.');
        }

        $created    = 0;
        $errors     = [];
        $batchNumber = 'BATCH-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        foreach ($rows as $i => $row) {
            $productId = $row['product_id'];
            $qty       = $row['quantity'];

            $product = Product::where('product_id', $productId)->first();
            if (!$product) {
                $errors[] = "Row " . ($i + 2) . ": Product '{$productId}' not found.";
                continue;
            }

            // Check stock exists in Store
            $storeStock = BranchStock::where('branch_id', $store->id)
                ->where('stockable_type', 'App\\Product')
                ->where('stockable_id', $product->id)
                ->first();

            if (!$storeStock) {
                $errors[] = "Row " . ($i + 2) . ": Product '{$productId}' not in Store stock.";
                continue;
            }

            StockTransfer::create([
                'from_branch_id' => $store->id,
                'to_branch_id'   => $toBranchId,
                'product_id'     => $product->id,
                'stockable_type' => 'App\\Product',
                'stockable_id'   => $product->id,
                'quantity'       => $qty,
                'transfer_date'  => now(),
                'status'         => 'pending',
                'priority'       => 'medium',
                'notes'          => $request->notes ?: 'Bulk transfer request via Excel',
                'created_by'     => auth()->id(),
                'batch_number'   => $batchNumber,
            ]);
            $created++;
        }

        if ($created === 0) {
            return back()->with('error', 'No transfers created. Errors: ' . implode(' | ', array_slice($errors, 0, 5)));
        }

        $msg = "Created {$created} transfer request(s) in batch <strong>{$batchNumber}</strong>.";
        if (!empty($errors)) {
            $msg .= ' Skipped rows: ' . implode(' | ', array_slice($errors, 0, 5));
        }

        return redirect()->route('dashboard.stock-transfers.batch', $batchNumber)
            ->with('success', $msg);
    }

    /**
     * Download Excel template for bulk transfer requests
     */
    public function bulkTemplate()
    {
        $path = public_path('templates/transfer_request_template.xlsx');

        // Build template if it doesn't exist
        if (!file_exists($path) || filesize($path) < 100) {
            $this->buildTransferTemplate($path);
        }

        while (ob_get_level()) { ob_end_clean(); }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="transfer_request_template.xlsx"');
        header('Content-Length: ' . filesize($path));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($path);
        exit;
    }

    private function buildTransferTemplate(string $path)
    {
        // Simplified: only product_id + quantity
        $xl_sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <sheetData>
    <row r="1">
      <c r="A1" t="inlineStr"><is><t>product_id</t></is></c>
      <c r="B1" t="inlineStr"><is><t>quantity</t></is></c>
    </row>
    <row r="2">
      <c r="A2" t="inlineStr"><is><t>ADD002</t></is></c>
      <c r="B2"><v>5</v></c>
    </row>
    <row r="3">
      <c r="A3" t="inlineStr"><is><t>PRD005</t></is></c>
      <c r="B3"><v>3</v></c>
    </row>
  </sheetData>
</worksheet>';

        $xl_workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"
          xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets><sheet name="Transfer Request" sheetId="1" r:id="rId1"/></sheets>
</workbook>';

        $xl_rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
</Relationships>';

        $content_types = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
</Types>';

        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>';

        if (!is_dir(dirname($path))) { mkdir(dirname($path), 0755, true); }

        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', $content_types);
        $zip->addFromString('_rels/.rels', $rels);
        $zip->addFromString('xl/workbook.xml', $xl_workbook);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $xl_rels);
        $zip->addFromString('xl/worksheets/sheet1.xml', $xl_sheet);
        $zip->close();
    }

    /**
     * Show all transfers in a batch (for receiving branch to approve/reject per item)
     */
    public function batchShow($batchNumber)
    {
        $transfers = StockTransfer::with(['fromBranch', 'toBranch', 'stockable', 'creator', 'approver'])
            ->where('batch_number', $batchNumber)
            ->get();

        if ($transfers->isEmpty()) {
            return redirect()->route('dashboard.stock-transfers.index')
                ->with('error', 'Batch not found.');
        }

        $firstTransfer = $transfers->first();

        return view('dashboard.pages.branches.stock.stock-transfers.batch',
            compact('transfers', 'batchNumber', 'firstTransfer'));
    }

    /**
     * Get stock available at a specific branch (AJAX helper for transfer create)
     */
    public function getStock(Request $request)
    {
        $branchId  = $request->branch_id;
        $productId = $request->product_id;

        $stock = BranchStock::where('branch_id', $branchId)
            ->where('stockable_type', 'App\\Product')
            ->where('stockable_id', $productId)
            ->first();

        return response()->json([
            'available' => $stock ? $stock->available_quantity : 0,
        ]);
    }

    /* ================================================================
       ITEMS TRANSFER REPORT — full movement history per product
       ================================================================ */
    public function itemReport(Request $request)
    {
        $products = Product::orderBy('description')->get(['id', 'product_id', 'description']);
        $transfers = collect();
        $selectedProduct = null;
        $currentTotalStock = 0;

        if ($request->filled('product_id')) {
            $selectedProduct = Product::find($request->product_id);

            $transfers = StockTransfer::with(['fromBranch', 'toBranch', 'creator'])
                ->where('stockable_type', 'App\\Product')
                ->where('stockable_id', $request->product_id)
                ->orderBy('transfer_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            // Current actual stock across all branches
            $currentTotalStock = BranchStock::where('stockable_type', 'App\\Product')
                ->where('stockable_id', $request->product_id)
                ->sum('quantity');
        }

        return view('dashboard.pages.branches.stock.stock-transfers.item-report',
            compact('products', 'transfers', 'selectedProduct', 'currentTotalStock'));
    }

    public function itemReportPdf(Request $request)
    {
        $selectedProduct = null;
        $transfers = collect();

        if ($request->filled('product_id')) {
            $selectedProduct = Product::find($request->product_id);
            $transfers = StockTransfer::with(['fromBranch', 'toBranch', 'creator'])
                ->where('stockable_type', 'App\\Product')
                ->where('stockable_id', $request->product_id)
                ->orderBy('transfer_date', 'desc')
                ->get();
        }

        $pdf = PDF::loadView('dashboard.pages.branches.stock.stock-transfers.item-report-pdf',
            compact('transfers', 'selectedProduct'))
            ->setPaper('a4', 'landscape');

        $filename = 'item-transfer-' . ($selectedProduct ? $selectedProduct->product_id : 'all') . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Clean standalone print/PDF page — opens in new tab, no Arabic encoding issues
     */
    public function itemReportPrint(Request $request)
    {
        $selectedProduct = null;
        $transfers = collect();

        if ($request->filled('product_id')) {
            $selectedProduct = Product::find($request->product_id);
            $transfers = StockTransfer::with(['fromBranch', 'toBranch', 'creator'])
                ->where('stockable_type', 'App\\Product')
                ->where('stockable_id', $request->product_id)
                ->orderBy('transfer_date', 'desc')
                ->get();
        }

        return view('dashboard.pages.branches.stock.stock-transfers.print_item_report',
            compact('transfers', 'selectedProduct'));
    }

    /* ================================================================
       SEARCH PRODUCTS — AJAX endpoint for multi-product transfer form
       GET /dashboard/stock-transfers/search-products?q=...&from_branch_id=...
       Returns JSON: [{id, product_id, description, stock}]
       ================================================================ */
    public function searchProducts(Request $request)
    {
        $q          = trim($request->q ?? '');
        $branchId   = $request->from_branch_id;

        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $products = Product::where(function ($query) use ($q) {
                $query->where('product_id', 'LIKE', '%' . $q . '%')
                      ->orWhere('description', 'LIKE', '%' . $q . '%');
            })
            ->orderBy('product_id')
            ->limit(20)
            ->get(['id', 'product_id', 'description']);

        $result = [];
        foreach ($products as $product) {
            $stock = 0;
            if ($branchId) {
                $bs = BranchStock::where('branch_id', $branchId)
                    ->where('stockable_type', 'App\\Product')
                    ->where('stockable_id', $product->id)
                    ->first();
                $stock = $bs ? (int) $bs->available_quantity : 0;
            }
            $result[] = [
                'id'          => $product->id,
                'product_id'  => $product->product_id,
                'description' => $product->description,
                'stock'       => $stock,
            ];
        }

        return response()->json($result);
    }

    /* ================================================================
       STORE MULTI — create multiple transfers from one form submission
       POST /dashboard/stock-transfers/store-multi
       items[{n}][product_id], items[{n}][quantity], items[{n}][notes]
       Creates a BATCH if more than one item.
       ================================================================ */
    public function storeMulti(Request $request)
    {
        $request->validate([
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id'   => 'required|exists:branches,id|different:from_branch_id',
            'items'          => 'required|array|min:1',
        ]);

        $user       = auth()->user();
        $fromBranch = $request->from_branch_id;
        $toBranch   = $request->to_branch_id;

        // Regular users must always request FROM the main Store branch
        if (!$user->canSeeAllBranches()) {
            $store = Branch::where('is_main', true)->where('is_active', true)->first();
            if ($store && (int) $fromBranch !== $store->id) {
                return back()->withErrors(['from_branch_id' => 'Transfer requests must come from the Store (Warehouse) branch.'])->withInput();
            }
        }

        // Filter out blank rows (no product_id or quantity < 1)
        $validItems = [];
        foreach ($request->items as $row) {
            if (!empty($row['product_id']) && isset($row['quantity']) && (int) $row['quantity'] > 0) {
                $validItems[] = $row;
            }
        }

        if (empty($validItems)) {
            return back()->withErrors(['items' => 'Please add at least one product with a valid quantity.'])->withInput();
        }

        $created     = 0;
        $errors      = [];
        $batchNumber = null;

        // Generate batch number only when there are multiple items
        if (count($validItems) > 1) {
            $batchNumber = 'BATCH-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        }

        $lastTransfer = null;

        foreach ($validItems as $i => $row) {
            $product = Product::find($row['product_id']);
            if (!$product) {
                $errors[] = 'Row ' . ($i + 1) . ': Product not found.';
                continue;
            }

            $qty = (int) $row['quantity'];

            // Check stock availability in source branch
            $stock = BranchStock::where('branch_id', $fromBranch)
                ->where('stockable_type', 'App\\Product')
                ->where('stockable_id', $product->id)
                ->first();

            if (!$stock || $stock->available_quantity < $qty) {
                $available = $stock ? $stock->available_quantity : 0;
                $errors[] = 'Row ' . ($i + 1) . ': Insufficient stock for "' . $product->product_id . '" (available: ' . $available . ', requested: ' . $qty . ').';
                continue;
            }

            $transfer = StockTransfer::create([
                'from_branch_id' => $fromBranch,
                'to_branch_id'   => $toBranch,
                'product_id'     => $product->id,
                'stockable_type' => 'App\\Product',
                'stockable_id'   => $product->id,
                'quantity'       => $qty,
                'transfer_date'  => now(),
                'status'         => 'pending',
                'priority'       => $request->priority ?? 'medium',
                'notes'          => isset($row['notes']) ? trim($row['notes']) : null,
                'created_by'     => auth()->id(),
                'batch_number'   => $batchNumber,
            ]);

            NotificationService::transferCreated($transfer);
            $created++;
            $lastTransfer = $transfer;
        }

        if ($created === 0) {
            $msg = 'No transfers created.';
            if (!empty($errors)) {
                $msg .= ' ' . implode(' | ', array_slice($errors, 0, 5));
            }
            return back()->with('error', $msg)->withInput();
        }

        // Build success message
        $successMsg = $created === 1
            ? 'Transfer request created successfully.'
            : "Created {$created} transfer requests in batch <strong>{$batchNumber}</strong>.";

        if (!empty($errors)) {
            $successMsg .= ' Skipped: ' . implode(' | ', array_slice($errors, 0, 3));
        }

        \Cache::forget('sidebar_pending_transfers');

        // Redirect: batch view if multiple, single show if one
        if ($batchNumber) {
            return redirect()->route('dashboard.stock-transfers.batch', $batchNumber)
                ->with('success', $successMsg);
        }

        return redirect()->route('dashboard.stock-transfers.show', $lastTransfer)
            ->with('success', $successMsg);
    }
}
