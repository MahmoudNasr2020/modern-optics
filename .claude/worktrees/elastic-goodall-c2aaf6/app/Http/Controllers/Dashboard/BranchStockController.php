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
        // Base query
        $query = BranchStock::where('branch_id', $branch->id)
            ->with('stockable');

        // Search filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('stockable', function($sq) use ($request) {
                    $sq->where('description', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('product_id', 'LIKE', '%' . $request->search . '%');
                });
            });
        }

        // Type filter
        if ($request->type === 'product') {
            $query->where('stockable_type', 'App\\Product');
        } elseif ($request->type === 'lens') {
            $query->where('stockable_type', 'App\\glassLense');
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
        $lenses = glassLense::all();

        return view('dashboard.pages.branches.stock.create', compact('branch', 'products', 'lenses'));
    }

    /**
     * Store new stock item
     */
    public function store(Request $request, Branch $branch)
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
                        'reason' => $request->reason,
                        'notes' => $request->notes
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
                        'reason' => $request->reason,
                        'notes' => $request->notes
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
                        'cost' => $request->cost,
                        'notes' => $request->notes ?? 'Stock added'
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
                        'cost' => $request->cost,
                        'notes' => $request->notes ?? 'Stock added'
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
                        'reason' => $request->reason,
                        'notes' => $request->notes
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
                        'reason' => $request->reason,
                        'notes' => $request->notes
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


}
