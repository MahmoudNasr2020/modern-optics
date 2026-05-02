<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\BranchStock;
use App\glassLense;
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
        $this->middleware('permission.spatie:create-transfers')->only(['create', 'store']);
        $this->middleware('permission.spatie:approve-transfers')->only(['approve']);
        $this->middleware('permission.spatie:ship-transfers')->only(['ship']);
        $this->middleware('permission.spatie:receive-transfers')->only(['receive']);
        $this->middleware('permission.spatie:cancel-transfers')->only(['cancel']);
        $this->middleware('permission.spatie:view-transfer-reports')->only(['report', 'reportPdf']);
        // Optional: delete-transfers if you add delete() method
        // $this->middleware('permission.spatie:delete-transfers')->only(['destroy']);
    }
    /**
     * Index - List all transfers
     */
    public function index(Request $request)
    {
        $query = StockTransfer::with(['fromBranch', 'toBranch', 'stockable', 'creator']);

        // Search filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('transfer_number', 'LIKE', '%' . $request->search . '%')
                    ->orWhereHas('stockable', function($sq) use ($request) {
                        $sq->where('description', 'LIKE', '%' . $request->search . '%');
                    });
            });
        }

        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // From branch filter
        if ($request->from_branch) {
            $query->where('from_branch_id', $request->from_branch);
        }

        // Item type filter
        if ($request->item_type === 'product') {
            $query->where('stockable_type', 'App\\Product');
        } elseif ($request->item_type === 'lens') {
            $query->where('stockable_type', 'App\\glassLense');
        }

        $transfers = $query->latest()->paginate(20);
        $branches = Branch::all();

        // Calculate stats
        $stats = [
            'pending' => StockTransfer::where('status', 'pending')->count(),
            'approved' => StockTransfer::where('status', 'approved')->count(),
            'in_transit' => StockTransfer::where('status', 'in_transit')->count(),
            'received' => StockTransfer::where('status', 'received')->count(),
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
        $lenses = glassLense::all();

        return view('dashboard.pages.branches.stock.stock-transfers.create', compact('branches', 'products', 'lenses'));
    }

    /**
     * Check stock availability (AJAX)
     */
    public function checkStock(Request $request)
    {
        $itemType = $request->item_type; // 'product' or 'lens'
        $itemId = $request->item_id;
        $fromBranchId = $request->from_branch_id;
        $toBranchId = $request->to_branch_id;

        $stockableType = $itemType === 'product' ? 'App\\Product' : 'App\\glassLense';

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
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'item_type' => 'required|in:product,lens',
            'quantity' => 'required|integer|min:1',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        // Determine stockable type and ID
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
        NotificationService::transferCreated($transfer);

        return redirect()->route('dashboard.stock-transfers.show', $transfer)
            ->with('success', 'Transfer request created successfully');
    }

    /**
     * Show transfer details
     */
    public function show(StockTransfer $stockTransfer)
    {
        $stockTransfer->load(['fromBranch', 'toBranch', 'stockable', 'creator', 'approver', 'shipper', 'receiver']);

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
    public function approve(StockTransfer $stockTransfer)
    {
        if ($stockTransfer->status !== 'pending') {
            return back()->with('error', 'Only pending transfers can be approved');
        }

        try {
            // Call model method
            $stockTransfer->approve(auth()->id());

            // ✅ إشعار الموافقة
            NotificationService::transferApproved($stockTransfer);


            return back()->with('success', 'Transfer request approved successfully');

        } catch (\Exception $e) {
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
}
