<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\BranchStock;
use App\glassLense;
use App\Http\Controllers\Controller;
use App\Product;
use App\Services\NotificationService;
use App\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class BranchStockController extends Controller
{

    public function __construct()
    {
        // عرض المخزون
        $this->middleware('permission.spatie:view-stock')->only([
            'index',
            'show',
            'lowStock',
            'outOfStock',
        ]);

        // إضافة صنف للمخزون
        $this->middleware('permission.spatie:add-stock')->only([
            'create',
            'store',
        ]);

        // تعديل بيانات الصنف (min / max / cost)
        $this->middleware('permission.spatie:edit-stock')->only([
            'edit',
            'update',
        ]);

        // زيادة الكمية
        $this->middleware('permission.spatie:increase-stock')->only([
            'addQuantity',
        ]);

        // تقليل الكمية
        $this->middleware('permission.spatie:decrease-stock')->only([
            'reduceQuantity',
        ]);

        // تسوية الجرد (Adjust)
        $this->middleware('permission.spatie:adjust-stock')->only([
            'adjust',
        ]);

        // عرض الحركات
        $this->middleware('permission.spatie:view-stock-movements')->only([
            'movements',
        ]);

        // التقارير و PDF
        $this->middleware('permission.spatie:view-stock-reports')->only([
            'report',
            'reportPdf',
        ]);
    }


    /**
     * Display stock index for a branch
     */
    public function index(Request $request, Branch $branch)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }
        // Respect the 'type' filter; default to products only
        $stockableType = ($request->type === 'lens') ? 'App\\glassLense' : 'App\\Product';

        $query = BranchStock::where('branch_id', $branch->id)
            ->where('stockable_type', $stockableType)
            ->with('stockable');

        // Search filter — avoid whereHas on morphTo (not supported reliably in 5.8)
        if ($request->search) {
            $search = $request->search;
            if ($stockableType === 'App\\Product') {
                $ids = Product::where('description', 'LIKE', "%{$search}%")
                    ->orWhere('product_id', 'LIKE', "%{$search}%")
                    ->pluck('id');
            } else {
                $ids = glassLense::where('description', 'LIKE', "%{$search}%")
                    ->orWhere('product_id', 'LIKE', "%{$search}%")
                    ->pluck('id');
            }
            $query->whereIn('stockable_id', $ids);
        }

        // Status filter
        if ($request->status === 'low') {
            $query->whereColumn('quantity', '<=', 'min_quantity')
                ->where('quantity', '>', 0);
        } elseif ($request->status === 'out') {
            $query->where('quantity', '<=', 0);
        } elseif ($request->status === 'normal') {
            $query->whereColumn('quantity', '>', 'min_quantity');
        }

        // Paginate
        $stocks = $query->latest()->paginate(20);

        return view('dashboard.pages.branches.stock.index', compact('branch', 'stocks'));
    }

    /**
     * Show create form
     */
    public function create(Branch $branch)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }
        $products = Product::all();
        // Lenses are managed via Lab Orders system — NOT added to branch stock
        $lenses = collect();

        return view('dashboard.pages.branches.stock.create', compact('branch', 'products', 'lenses'));
    }

    /**
     * Store new stock item
     */
   /* public function store(Request $request, Branch $branch)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        $request->validate([
            'item_type' => 'required|in:product,lens',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'max_quantity' => 'required|integer|min:0',
            'cost' => 'nullable|numeric|min:0',
        ]);


        // Validate item based on type
        if ($request->item_type === 'product') {
            $request->validate(['product_id' => 'required|exists:products,id']);
            $stockableType = 'App\\Product';
            $stockableId = $request->product_id;
            $productId = $request->product_id;
        } else {
            $request->validate(['lens_id' => 'required|exists:glass_lenses,id']);
            $stockableType = 'App\\glassLense';
            $stockableId = $request->lens_id;
            $productId = null;
        }


        // Check if stock already exists
        $existingStock = BranchStock::where('branch_id', $branch->id)
            ->where('stockable_type', $stockableType)
            ->where('stockable_id', $stockableId)
            ->first();

        if ($existingStock) {
            return back()->withErrors(['item' => 'This item already exists in branch stock. Please edit it instead.'])->withInput();
        }

        // Validate min/max
        if ($request->min_quantity >= $request->max_quantity) {
            return back()->withErrors(['max_quantity' => 'Maximum quantity must be greater than minimum quantity'])->withInput();
        }

        // Create stock record
        $stock = BranchStock::create([
            'branch_id' => $branch->id,
            'product_id' => $productId,
            'stockable_type' => $stockableType,
            'stockable_id' => $stockableId,
            'quantity' => $request->quantity,
            'min_quantity' => $request->min_quantity,
            'max_quantity' => $request->max_quantity,
            'last_cost' => $request->cost,
            'average_cost' => $request->cost,
            'total_in' => $request->quantity,
            'total_out' => 0,
        ]);


        // Create stock movement if quantity > 0 using helper method
        if ($request->quantity > 0) {
            if ($request->item_type === 'product') {
                StockMovement::createForProduct(
                    $branch->id,
                    $stockableId,
                    'in',
                    $request->quantity,
                    auth()->id(),
                    [
                        'cost' => $request->cost,
                        'notes' => $request->notes ?? 'Initial stock'
                    ]
                );
            } else {

                StockMovement::createForLens(
                    $branch->id,
                    $stockableId,
                    'in',
                    $request->quantity,
                    auth()->id(),
                    [
                        'cost' => $request->cost,
                        'notes' => $request->notes ?? 'Initial stock'
                    ]
                );
            }
        }

        return redirect()->route('dashboard.branches.stock.index', $branch)
            ->with('success', 'Stock item added successfully');
    }*/

    public function store(Request $request, Branch $branch)
    {
        // ── Auth ────────────────────────────────────────────────
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        // ── Validate the array ──────────────────────────────────
        $request->validate([
            'items'                    => 'required|array|min:1',
            'items.*.item_type'        => 'required|in:product,lens',
            'items.*.quantity'         => 'required|integer|min:0',
            'items.*.min_quantity'     => 'required|integer|min:0',
            'items.*.max_quantity'     => 'required|integer|min:0',
            'items.*.cost'             => 'nullable|numeric|min:0',
            'items.*.notes'            => 'nullable|string|max:500',
            // product_id / lens_id validated per-row below
        ]);

        // ── Per-row extra validation ─────────────────────────────
        $extraRules = [];
        foreach ($request->items as $i => $item) {
            if (($item['item_type'] ?? 'product') === 'product') {
                $extraRules["items.{$i}.product_id"] = 'required|exists:products,id';
            } else {
                $extraRules["items.{$i}.lens_id"] = 'required|exists:glass_lenses,id';
            }

            // min < max check
            $min = (int) ($item['min_quantity'] ?? 0);
            $max = (int) ($item['max_quantity'] ?? 0);
            if ($min >= $max) {
                return back()
                    ->withErrors(["items.{$i}.max_quantity" =>
                        "Row " . ($i + 1) . ": Maximum quantity must be greater than minimum quantity."])
                    ->withInput();
            }
        }

        $request->validate($extraRules);

        // ── Check duplicates WITHIN the same request ─────────────────────────
        $seenInRequest = [];
        foreach ($request->items as $i => $item) {
            $isProduct = $item['item_type'] === 'product';
            $key = ($isProduct ? 'product' : 'lens') . '_' . ($isProduct ? ($item['product_id'] ?? '') : ($item['lens_id'] ?? ''));

            if (in_array($key, $seenInRequest)) {
                return back()
                    ->withErrors(['items' => 'Row ' . ($i + 1) . ': This item is duplicated in another row. Please remove it.'])
                    ->withInput();
            }
            $seenInRequest[] = $key;
        }

        // ── Process each row ─────────────────────────────────────
        $added    = 0;
        $skipped  = [];

        foreach ($request->items as $i => $item) {

            $isProduct     = $item['item_type'] === 'product';
            $stockableType = $isProduct ? 'App\\Product' : 'App\\glassLense';
            $stockableId   = $isProduct ? $item['product_id'] : $item['lens_id'];
            $productId     = $isProduct ? $item['product_id'] : null;

            // Skip duplicates (already in stock)
            $exists = BranchStock::where('branch_id', $branch->id)
                ->where('stockable_type', $stockableType)
                ->where('stockable_id', $stockableId)
                ->exists();

            if ($exists) {
                $skipped[] = 'Row ' . ($i + 1) . ': Item already exists in stock — skipped.';
                continue;
            }

            // Create stock record
            BranchStock::create([
                'branch_id'      => $branch->id,
                'product_id'     => $productId,
                'stockable_type' => $stockableType,
                'stockable_id'   => $stockableId,
                'quantity'       => $item['quantity'],
                'min_quantity'   => $item['min_quantity'],
                'max_quantity'   => $item['max_quantity'],
                'last_cost'      => $item['cost'] ?? null,
                'average_cost'   => $item['cost'] ?? null,
                'total_in'       => $item['quantity'],
                'total_out'      => 0,
            ]);

            // Stock movement (only if quantity > 0)
            if ((int) $item['quantity'] > 0) {
                $movementOptions = [
                    'cost'           => $item['cost'] ?? null,
                    'notes'          => $item['notes'] ?? 'Initial stock',
                    'balance_before' => 0, // new stock — nothing was there before
                ];

                if ($isProduct) {
                    StockMovement::createForProduct(
                        $branch->id,
                        $stockableId,
                        'in',
                        $item['quantity'],
                        auth()->id(),
                        $movementOptions
                    );
                } else {
                    StockMovement::createForLens(
                        $branch->id,
                        $stockableId,
                        'in',
                        $item['quantity'],
                        auth()->id(),
                        $movementOptions
                    );
                }
            }

            $added++;
        }

        // ── Redirect with feedback ───────────────────────────────
        $message = "Successfully added {$added} item(s) to stock.";

        if (!empty($skipped)) {
            $message .= ' ' . count($skipped) . ' item(s) were skipped because they already exist.';
            session()->flash('warning_details', $skipped);
        }

        return redirect()
            ->route('dashboard.branches.stock.index', $branch)
            ->with('success', $message);
    }

    /**
     * Show stock details
     */
    public function show(Branch $branch, BranchStock $stock)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        $stock->load('stockable', 'branch');

        return view('dashboard.pages.branches.stock.show', compact('branch', 'stock'));
    }

    /**
     * Show edit form
     */
    public function edit(Branch $branch, BranchStock $stock)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        $stock->load('stockable');

        return view('dashboard.pages.branches.stock.edit', compact('branch', 'stock'));
    }

    /**
     * Update stock
     */
    public function update(Request $request, Branch $branch, BranchStock $stock)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        $request->validate([
            'min_quantity' => 'required|integer|min:0',
            'max_quantity' => 'required|integer|min:0',
            'last_cost' => 'nullable|numeric|min:0',
            'average_cost' => 'nullable|numeric|min:0',
        ]);

        // Validate min/max
        if ($request->min_quantity >= $request->max_quantity) {
            return back()->withErrors(['max_quantity' => 'Maximum quantity must be greater than minimum quantity'])->withInput();
        }

        // Update stock
        $stock->update([
            'min_quantity' => $request->min_quantity,
            'max_quantity' => $request->max_quantity,
            'last_cost' => $request->last_cost,
            'average_cost' => $request->average_cost,
        ]);

        return redirect()->route('dashboard.branches.stock.show', [$branch, $stock])
            ->with('success', 'Stock updated successfully');
    }

    /**
     * Delete stock item
     */
    public function destroy(Branch $branch, BranchStock $stock)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        // Check if stock has quantity
        if ($stock->quantity > 0) {
            return back()->with('error', 'Cannot delete stock with available quantity. Quantity must be zero.');
        }

        // Check if stock has reserved quantity
        if ($stock->reserved_quantity > 0) {
            return back()->with('error', 'Cannot delete stock with reserved quantity.');
        }

        $description = $stock->description;

        // Delete related movements
        StockMovement::where('branch_id', $branch->id)
            ->where('stockable_type', $stock->stockable_type)
            ->where('stockable_id', $stock->stockable_id)
            ->delete();

        // Delete stock
        $stock->delete();

        return redirect()->route('dashboard.branches.stock.index', $branch)
            ->with('success', "Stock item '{$description}' deleted successfully");
    }

    /**
     * Show stock movements/history
     */
    public function movements(Branch $branch, BranchStock $stock)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        $stock->load('stockable');

        // Get movements for this stock item
        $movements = StockMovement::where('branch_id', $branch->id)
            ->where('stockable_type', $stock->stockable_type)
            ->where('stockable_id', $stock->stockable_id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('dashboard.pages.branches.stock.movements', compact('branch', 'stock', 'movements'));
    }

    /**
     * Generate stock report (PDF)
     */


    /**
     * Dedicated clean print/PDF page — opens in new tab, all items, no layout
     */
    public function reportPrint(Branch $branch)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        $allStocks = BranchStock::where('branch_id', $branch->id)
            ->where('quantity', '>', 0)
            ->with('stockable')
            ->get();

        return view('dashboard.pages.branches.stock.print_stock_report', compact('branch', 'allStocks'));
    }

    /**
     * Show stock report with pagination (normal view)
     */
    public function report(Branch $branch)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        $perPage = request('per_page', 25); // Default 25 items per page

        $query = BranchStock::where('branch_id', $branch->id)
            ->with('stockable');

        // Apply filters if any
        if (request()->has('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('stockable', function($q2) use ($search) {
                    $q2->where('description', 'like', "%{$search}%")
                        ->orWhere('product_id', 'like', "%{$search}%");
                });
            });
        }

        // Filter by type
        if (request()->has('type') && request('type') != '') {
            if (request('type') == 'product') {
                $query->where('stockable_type', 'App\\Product');
            } elseif (request('type') == 'lens') {
                $query->where('stockable_type', 'App\\glassLense');
            }
        }

        // Filter by status
        if (request()->has('status') && request('status') != '') {
            $status = request('status');
            if ($status == 'low') {
                $query->whereColumn('quantity', '<=', 'min_quantity')
                    ->where('quantity', '>', 0);
            } elseif ($status == 'out') {
                $query->where('quantity', '<=', 0);
            } elseif ($status == 'over') {
                $query->whereColumn('quantity', '>=', 'max_quantity');
            } elseif ($status == 'normal') {
                $query->whereColumn('quantity', '>', 'min_quantity')
                    ->whereColumn('quantity', '<', 'max_quantity');
            }
        }

        $stocks = $query->paginate($perPage);

        // Get totals for all data (not just current page)
        $allStocks = BranchStock::where('branch_id', $branch->id)
            ->with('stockable')
            ->get();

        return view('dashboard.pages.branches.stock.report', compact('branch', 'stocks', 'allStocks'));
    }

    /**
     * Download stock report as Excel — all items, includes branch name
     */
    public function reportExcel(Branch $branch)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        // Only items that have actual stock (qty > 0)
        $stocks = BranchStock::where('branch_id', $branch->id)
            ->where('quantity', '>', 0)
            ->with('stockable')
            ->get();

        $totalQty = 0;
        $rows     = '';

        foreach ($stocks as $i => $stock) {
            $type      = $stock->stockable_type === 'App\\Product' ? 'Product' : 'Lens';
            $totalQty += $stock->quantity;

            $rows .= '<tr>'
                . '<td>' . ($i + 1) . '</td>'
                . '<td>' . htmlspecialchars($stock->item_code ?? '') . '</td>'
                . '<td>' . $type . '</td>'
                . '<td>' . htmlspecialchars($stock->description ?? '') . '</td>'
                . '<td style="text-align:center;">' . $stock->quantity . '</td>'
                . '</tr>';
        }

        $html = '<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta charset="UTF-8"/>
<!--[if gte mso 9]><xml>
  <x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>
    <x:Name>Stock Report</x:Name>
    <x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>
  </x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook>
</xml><![endif]-->
<style>
  body { font-family: Arial, sans-serif; font-size: 12px; }
  table { border-collapse: collapse; width: 100%; }
  td, th { border: 1px solid #ccc; padding: 6px 10px; font-size: 12px; font-family: Arial; }
  th { background: #1a237e; color: #fff; font-weight: bold; text-align: center; }
  tr:nth-child(even) td { background: #f5f5f5; }
  .branch-header td { background: #667eea; color: #fff; font-weight: bold; font-size: 15px; border: none; padding: 12px 10px; }
  .info-row td { background: #e8eaf6; color: #333; font-size: 11px; border: none; padding: 4px 10px; }
  .total-row td { background: #27ae60; color: #fff; font-weight: bold; border-color: #1e8449; }
</style>
</head>
<body>
<table>
  <tr class="branch-header">
    <td colspan="5">&#128201; Branch Stock Report &mdash; ' . htmlspecialchars($branch->name) . '</td>
  </tr>
  <tr class="info-row">
    <td colspan="5">Generated: ' . now()->format('Y-m-d H:i') . ' &nbsp;&nbsp; Items in Stock: ' . $stocks->count() . ' &nbsp;&nbsp; Total Units: ' . $totalQty . '</td>
  </tr>
  <tr><td colspan="5" style="border:none;padding:4px;"></td></tr>
  <tr>
    <th>#</th>
    <th>Code</th>
    <th>Type</th>
    <th>Description</th>
    <th>Qty</th>
  </tr>
  ' . $rows . '
  <tr class="total-row">
    <td colspan="4" style="text-align:right;"><strong>GRAND TOTAL</strong></td>
    <td style="text-align:center;"><strong>' . $totalQty . '</strong></td>
  </tr>
</table>
</body>
</html>';

        $filename = 'stock-' . $branch->name . '-' . now()->format('Ymd') . '.xls';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Download stock report as PDF
     */
    public function reportPdf(Branch $branch)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        $stocks = BranchStock::where('branch_id', $branch->id)
            ->with('stockable')
            ->get();

        $pdf = PDF::loadView('dashboard.pages.branches.stock.report_pdf', compact('branch', 'stocks'));

        // Set paper to landscape for better table display
        $pdf->setPaper('A4', 'landscape');

        // Set options
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ]);

        return $pdf->download('stock-report-' . $branch->code . '-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Adjust stock quantity (Admin only)
     */
    public function adjust(Request $request, Branch $branch, BranchStock $stock)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        $request->validate([
            'adjustment_type' => 'required|in:add,remove',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $quantity = $request->quantity;
            $itemType = $stock->stockable_type === 'App\\Product' ? 'product' : 'lens';

            // Capture balance BEFORE updating so movement history is correct
            $balanceBefore = $stock->quantity;

            if ($request->adjustment_type === 'add') {
                $stock->addQuantity($quantity);
                $movementType = 'in';
            } else {
                if ($stock->available_quantity < $quantity) {
                    return back()->withErrors(['quantity' => 'Insufficient stock to remove'])->withInput();
                }
                $stock->reduceQuantity($quantity);
                $movementType = 'out';
            }

            // Create movement using helper
            if ($itemType === 'product') {
                StockMovement::createForProduct(
                    $branch->id,
                    $stock->stockable_id,
                    $movementType,
                    $quantity,
                    auth()->id(),
                    [
                        'reason'         => $request->reason,
                        'notes'          => $request->notes,
                        'balance_before' => $balanceBefore,
                    ]
                );
            } else {
                StockMovement::createForLens(
                    $branch->id,
                    $stock->stockable_id,
                    $movementType,
                    $quantity,
                    auth()->id(),
                    [
                        'reason'         => $request->reason,
                        'notes'          => $request->notes,
                        'balance_before' => $balanceBefore,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('dashboard.branches.stock.show', [$branch, $stock])
                ->with('success', 'Stock adjusted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to adjust stock: ' . $e->getMessage());
        }
    }

    /**
     * Get low stock items (AJAX)
     */
    public function lowStock(Branch $branch)
    {
        $stocks = BranchStock::where('branch_id', $branch->id)
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->where('quantity', '>', 0)
            ->with('stockable')
            ->get();

        foreach ($stocks as $item) {
            // ✅ إشعار مخزون منخفض
            NotificationService::lowStock($item->stockable, $item->branch);
        }

        return response()->json([
            'success' => true,
            'count' => $stocks->count(),
            'items' => $stocks
        ]);
    }

    /**
     * Get out of stock items (AJAX)
     */
    public function outOfStock(Branch $branch)
    {
        $stocks = BranchStock::where('branch_id', $branch->id)
            ->where('quantity', '<=', 0)
            ->with('stockable')
            ->get();

        foreach ($stocks as $item) {
            // ✅ إشعار نفذ المخزون
            NotificationService::outOfStock($item->stockable, $item->branch);
        }
        return response()->json([
            'success' => true,
            'count' => $stocks->count(),
            'items' => $stocks
        ]);
    }

    /**
     * Add quantity to stock
     */
    public function addQuantity(Request $request, Branch $branch, BranchStock $stock)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Capture balance BEFORE updating so movement history is correct
            $balanceBefore = $stock->quantity;

            // Add quantity
            $stock->addQuantity($request->quantity, $request->cost);

            // Determine item type
            $itemType = $stock->stockable_type === 'App\\Product' ? 'product' : 'lens';

            // Create movement using helper method
            if ($itemType === 'product') {
                StockMovement::createForProduct(
                    $branch->id,
                    $stock->stockable_id,
                    'in',
                    $request->quantity,
                    auth()->id(),
                    [
                        'cost'           => $request->cost,
                        'notes'          => $request->notes ?? 'Stock added',
                        'balance_before' => $balanceBefore,
                    ]
                );
            } else {
                StockMovement::createForLens(
                    $branch->id,
                    $stock->stockable_id,
                    'in',
                    $request->quantity,
                    auth()->id(),
                    [
                        'cost'           => $request->cost,
                        'notes'          => $request->notes ?? 'Stock added',
                        'balance_before' => $balanceBefore,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('dashboard.branches.stock.index', $branch)
                ->with('success', "Added {$request->quantity} units successfully");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to add stock: ' . $e->getMessage());
        }
    }

    /**
     * Reduce quantity from stock
     */
    public function reduceQuantity(Request $request, Branch $branch, BranchStock $stock)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        // Validate quantity
        if ($request->quantity > $stock->available_quantity) {
            return back()->with('error', 'Cannot reduce more than available quantity (' . $stock->available_quantity . ')');
        }

        DB::beginTransaction();
        try {
            // Capture balance BEFORE updating so movement history is correct
            $balanceBefore = $stock->quantity;

            // Reduce quantity
           // $stock->reduceQuantity($request->quantity);
            $stock->reduceQuantityManually($request->quantity);


            // Determine item type
            $itemType = $stock->stockable_type === 'App\\Product' ? 'product' : 'lens';

            // Create movement using helper method
            if ($itemType === 'product') {
                StockMovement::createForProduct(
                    $branch->id,
                    $stock->stockable_id,
                    'out',
                    $request->quantity,
                    auth()->id(),
                    [
                        'reason'         => $request->reason,
                        'notes'          => $request->notes,
                        'balance_before' => $balanceBefore,
                    ]
                );
            } else {
                StockMovement::createForLens(
                    $branch->id,
                    $stock->stockable_id,
                    'out',
                    $request->quantity,
                    auth()->id(),
                    [
                        'reason'         => $request->reason,
                        'notes'          => $request->notes,
                        'balance_before' => $balanceBefore,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('dashboard.branches.stock.index', $branch)
                ->with('success', "Reduced {$request->quantity} units successfully");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reduce stock: ' . $e->getMessage());
        }
    }




    // ── Download blank template ───────────────────────────────────
    public function downloadTemplate(Branch $branch)
    {
        $path = public_path('templates/stock_import_template.xlsx');

        if (!file_exists($path)) {
            abort(404, 'Template file not found. Please contact the administrator.');
        }

        // Clear ALL output buffers to prevent any extra bytes corrupting the ZIP/xlsx
        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="stock_import_template.xlsx"');
        header('Content-Length: ' . filesize($path));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($path);
        exit;
    }

    public function downloadTestTemplate(Branch $branch)
    {
        $path = public_path('templates/test_stock_import.xlsx');

        if (!file_exists($path)) {
            abort(404, 'Test template not found.');
        }

        while (ob_get_level()) { ob_end_clean(); }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="test_stock_import.xlsx"');
        header('Content-Length: ' . filesize($path));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($path);
        exit;
    }



// ── Process Excel import ─────────────────────────────────────
    public function import(Request $request, Branch $branch)
    {
        if (!auth()->user()->canAccessBranch($branch->id)) {
            abort(403, 'You do not have permission to access this branch.');
        }

        $request->validate([
            'import_data' => 'required|string',
        ]);

        $rows = json_decode($request->import_data, true);

        if (!is_array($rows) || empty($rows)) {
            return back()->withErrors(['import_data' => 'No valid data found in the import.']);
        }

        $added   = 0;
        $skipped = [];
        $errors  = [];

        foreach ($rows as $i => $row) {
            $rowNum  = $i + 1;
            $type    = $row['type']    ?? '';
            $itemId  = trim($row['item_id'] ?? '');
            $qty     = (int)   ($row['qty']     ?? 0);
            $minQty  = (int)   ($row['min_qty'] ?? 0);
            $maxQty  = (int)   ($row['max_qty'] ?? 0);
            $cost    = !empty($row['cost']) ? (float) $row['cost'] : null;
            $notes   = $row['notes'] ?? 'Imported from Excel';

            // ── Basic validation ─────────────────────────────────
            if (!in_array($type, ['product', 'lens'])) {
                $errors[] = "Row {$rowNum}: Invalid type '{$type}'.";
                continue;
            }

            if (!$itemId) {
                $errors[] = "Row {$rowNum}: Missing Item ID.";
                continue;
            }

            if ($minQty >= $maxQty) {
                $errors[] = "Row {$rowNum}: Min quantity must be less than max quantity.";
                continue;
            }

            // ── Look up the item by product_id CODE ──────────────
            if ($type === 'product') {
                $item = \App\Product::where('product_id', $itemId)->first();

                if (!$item) {
                    $errors[] = "Row {$rowNum}: Product with ID '{$itemId}' not found.";
                    continue;
                }

                $stockableType = 'App\\Product';
                $stockableId   = $item->id;
                $productId     = $item->id;

            } else {
                $item = \App\glassLense::where('product_id', $itemId)->first();

                if (!$item) {
                    $errors[] = "Row {$rowNum}: Lens with ID '{$itemId}' not found.";
                    continue;
                }

                $stockableType = 'App\\glassLense';
                $stockableId   = $item->id;
                $productId     = null;
            }

            // ── Check already in stock ───────────────────────────
            $exists = BranchStock::where('branch_id', $branch->id)
                ->where('stockable_type', $stockableType)
                ->where('stockable_id', $stockableId)
                ->exists();

            if ($exists) {
                $skipped[] = "Row {$rowNum}: Item '{$itemId}' already exists in stock — skipped.";
                continue;
            }

            // ── Create stock record ──────────────────────────────
            BranchStock::create([
                'branch_id'      => $branch->id,
                'product_id'     => $productId,
                'stockable_type' => $stockableType,
                'stockable_id'   => $stockableId,
                'quantity'       => $qty,
                'min_quantity'   => $minQty,
                'max_quantity'   => $maxQty,
                'last_cost'      => $cost,
                'average_cost'   => $cost,
                'total_in'       => $qty,
                'total_out'      => 0,
            ]);

            // ── Stock movement ────────────────────────────────────
            if ($qty > 0) {
                $movementOptions = [
                    'cost'           => $cost,
                    'notes'          => $notes ?: 'Imported from Excel',
                    'balance_before' => 0, // new stock — nothing was there before
                ];

                if ($type === 'product') {
                    StockMovement::createForProduct(
                        $branch->id, $stockableId, 'in', $qty, auth()->id(), $movementOptions
                    );
                } else {
                    StockMovement::createForLens(
                        $branch->id, $stockableId, 'in', $qty, auth()->id(), $movementOptions
                    );
                }
            }

            $added++;
        }

        // ── Build flash messages ──────────────────────────────────
        $successMsg = "Successfully imported {$added} item(s).";

        if (!empty($skipped)) {
            session()->flash('import_skipped', $skipped);
            $successMsg .= ' ' . count($skipped) . ' skipped (already in stock).';
        }

        if (!empty($errors)) {
            session()->flash('import_errors', $errors);
            $successMsg .= ' ' . count($errors) . ' row(s) had errors.';
        }

        return redirect()
            ->route('dashboard.branches.stock.index', $branch)
            ->with('success', $successMsg);
    }

}
