<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\Brand;
use App\Category;
use App\glassModel;
use App\Http\Controllers\Controller;
use App\InvoiceItems;
use App\Product;
use App\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ProductController extends Controller
{

    public function __construct()
    {
        // View
        $this->middleware('permission.spatie:view-products')
            ->only([
                'index',
                'showProduct',
                'getProductDetails',
                'getDiscountsView'
            ]);

        // Create
        $this->middleware('permission.spatie:create-products')
            ->only([
                'getAddProduct',
                'postAddProduct'
            ]);

        // Edit
        $this->middleware('permission.spatie:edit-products')
            ->only([
                'getUpdateProduct',
                'updateProduct',
                'saveDiscounts'
            ]);

        // Delete
        $this->middleware('permission.spatie:delete-products')
            ->only([
                'deleteProduct'
            ]);
    }

    /**
     * ====================================================
     * INDEX - عرض المنتجات التابعة للفرع بتاع اليوزر
     * ====================================================
     */
    /*public function index(Request $request)
    {
        $user = auth()->user();

        // Get accessible branches for the user
        $accessibleBranches = $user->getAccessibleBranches();

        // Get the branch to filter by
        $branchId = $user->getFilterBranchId($request->branch_id);

        // If no branch selected and user is not super admin, use their branch
        if (!$branchId && !$user->canSeeAllBranches()) {
            $branchId = $user->branch_id;
        }

        // 🔥 Get products with their BranchStock info - filtered by branch
        $products = Product::with(['category', 'brand', 'model'])
            ->with(['branchStocks' => function($q) use ($branchId) {
                if ($branchId) {
                    $q->where('branch_id', $branchId);
                }
            }])
            // 🔥 Only get products that have stock in the selected branch
            ->when($branchId, function($query) use ($branchId) {
                return $query->whereHas('branchStocks', function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            })
            ->when($request->search, function ($query) use ($request) {
                return $query->where('product_id', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10);

        // Get current branch info for display
        $currentBranch = $user->getCurrentBranch($request->branch_id);

        // 🔥 Calculate statistics from BranchStock
        $statsQuery = \App\BranchStock::where('stockable_type', 'App\\Product')
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            });

        // Total items count
        $totalItems = $products->total();

        // Total stock quantity from BranchStock
        $totalQuantity = $statsQuery->sum('quantity');

        // Total available quantity (after reservations)
        $totalAvailable = $statsQuery->get()->sum(function($stock) {
            return $stock->available_quantity;
        });

        // Total reserved quantity
        $totalReserved = $statsQuery->sum('reserved_quantity');

        // Calculate total inventory value (quantity × retail_price)
        $totalValue = $statsQuery->get()->sum(function($stock) {
            if ($stock->stockable) {
                return $stock->quantity * ($stock->stockable->retail_price ?? 0);
            }
            return 0;
        });

        // Low stock count
        $lowStockCount = \App\BranchStock::where('stockable_type', 'App\\Product')
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->lowStock()
            ->count();

        // Out of stock count
        $outOfStockCount = \App\BranchStock::where('stockable_type', 'App\\Product')
            ->when($branchId, function ($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            })
            ->outOfStock()
            ->count();

        return view('dashboard.pages.products.index', compact(
            'products',
            'accessibleBranches',
            'currentBranch',
            'branchId',
            'totalItems',
            'totalQuantity',
            'totalAvailable',
            'totalReserved',
            'totalValue',
            'lowStockCount',
            'outOfStockCount'
        ));
    }*/

    public function index(Request $request)
    {
        // By default hide archived (is_active=false) unless ?show_archived=1
        $showArchived = $request->input('show_archived') == '1';

        $products = Product::with(['category', 'brand', 'model'])
            ->when(!$showArchived, fn($q) => $q->where('is_active', true))
            ->when($request->search, function ($query) use ($request) {
                $query->where(function($q) use ($request){
                    $q->where('product_id', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        $totalItems    = Product::where('is_active', true)->count();
        $archivedCount = Product::where('is_active', false)->count();

        // Main (Store) branch stock — keyed by product id for O(1) lookup in view
        $mainBranch   = \App\Branch::where('is_main', true)->first();
        $storeStockMap = collect();
        if ($mainBranch) {
            $storeStockMap = \App\BranchStock::where('branch_id', $mainBranch->id)
                ->where('stockable_type', 'App\\Product')
                ->get()
                ->keyBy('stockable_id');
        }

        return view('dashboard.pages.products.index', compact(
            'products',
            'totalItems',
            'archivedCount',
            'showArchived',
            'mainBranch',
            'storeStockMap'
        ));
    }


    /**
     * ====================================================
     * GET ADD PRODUCT - صفحة إضافة منتج جديد
     * ====================================================
     */
    public function getAddProduct()
    {
        $user = auth()->user();

        $productID  = Product::select('product_id')->latest()->first();
        $branches   = $user->getAccessibleBranches();
        $categories = Category::all();
        $brands     = Brand::all();
        $models     = glassModel::all();

        return view('dashboard.pages.products.create', compact([
            'productID',
            'branches',
            'categories',
            'brands',
            'models'
        ]));
    }

    /**
     * ====================================================
     * POST ADD PRODUCT - حفظ منتج جديد
     * ====================================================
     */
    public function postAddProduct(Request $request)
    {
        /*$user = auth()->user();

        // Validate that user can access the selected branch
        if (!$user->canAccessBranch($request->branch)) {
            return redirect()->back()
                ->withErrors(['branch' => 'You do not have permission to add products to this branch.'])
                ->withInput();
        }*/

        // Categories that require model + color + size
        $catId = (int) $request->category;
        $needsModelColorSize = in_array($catId, [1, 2]);   // Sun Glasses, Frames
        $needsPowerType      = in_array($catId, [6]);       // Reading Glasses

        $rules = [
            'product_id'    => 'unique:products',
            'barcode'       => 'nullable|string|max:100|unique:products,barcode',
            'description'   => 'required',
            'price'         => 'required|numeric|min:0',
            'retail_price'  => 'required|numeric|min:0',
            'tax'           => 'required|numeric|min:0',
            'color'         => $needsModelColorSize ? 'nullable' : 'nullable',
            'size'          => $needsModelColorSize ? 'nullable' : 'nullable',
            'model'         => $needsModelColorSize ? 'required' : 'nullable',
            'discount_value'=> 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'category'      => 'required|exists:categories,id',
            'brand'         => 'required|exists:brands,id',
            'power'         => $needsPowerType ? 'required|numeric|min:0.25|max:12' : 'nullable|numeric',
        ];

        $messages = [
            'product_id.unique' => 'This product ID has been added before',
            'description.required' => 'Please enter product description',
            'price.required' => 'Please enter product price',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price must be greater than or equal to 0',
            'retail_price.required' => 'Please enter product retail price',
            'retail_price.numeric' => 'Retail price must be a number',
            'retail_price.min' => 'Retail price must be greater than or equal to 0',
            'tax.required' => 'Please enter product tax',
            'tax.numeric' => 'Tax must be a number',
            'tax.min' => 'Tax must be greater than or equal to 0',
            'color.required' => 'Please enter product color',
            'size.required' => 'Please enter product size',
            'discount_value.required' => 'Please enter product discount value',
            'discount_value.numeric' => 'Discount value must be a number',
            'discount_value.min' => 'Discount value must be greater than or equal to 0',
            'discount_type.required' => 'Please select discount type',
            'discount_type.in' => 'Discount type must be either fixed or percentage',
            'model.required' => 'Please select product model',
            /*'branch.required' => 'Please select a branch',
            'branch.exists' => 'Selected branch does not exist',*/
            'category.required' => 'Please select a category',
            'category.exists' => 'Selected category does not exist',
            'brand.required' => 'Please select a brand',
            'brand.exists' => 'Selected brand does not exist',
        ];

        $request->validate($rules, $messages);

        $product = new Product();
        $product->product_id  = $request->product_id;
        $product->barcode     = $request->filled('barcode') ? trim($request->barcode) : null;
        $product->description = $request->description;
        $product->category_id = $request->category;
        $product->brand_id    = $request->brand;
        $product->model_id    = $request->model ?: 0;   // 0 when no model (chains, services, etc.)
        $product->color       = $request->color ?? '';
        $product->size        = $request->size  ?? '';
       // $product->branch_id = $request->branch;
        $product->price = $request->price;
        $product->retail_price = $request->retail_price;
        $product->tax = $request->tax;
        $product->discount_type = $request->discount_type;
        $product->discount_value = $request->discount_value;
        $product->power = $request->power;
        $product->sign = $request->sign;
        $product->lense_use = $request->lense_use;
        $product->type = $request->type;

        // Calculate total based on discount type
        if ($product->discount_type == 'fixed') {
            $product->total = $request->tax + ($request->retail_price - $request->discount_value);
        } else {
            $product->total = $request->tax + (($request->retail_price) - (($request->retail_price) * ($request->discount_value / 100)));
        }

        $product->save();

        // ===== OPTIONAL: Add to branch stock immediately =====
        if ($request->add_to_stock == '1' && auth()->user()->can('add-stock')) {
            $branchId = (int) $request->stock_branch_id;

            if ($branchId && auth()->user()->canAccessBranch($branchId)) {
                $alreadyExists = \App\BranchStock::where('branch_id', $branchId)
                    ->where('stockable_type', 'App\\Product')
                    ->where('stockable_id', $product->id)
                    ->exists();

                if (!$alreadyExists) {
                    $qty    = max(0, (int) $request->stock_quantity);
                    $minQty = max(0, (int) $request->stock_min_quantity);
                    $maxQty = max($minQty + 1, (int) $request->stock_max_quantity);

                    \App\BranchStock::create([
                        'branch_id'      => $branchId,
                        'product_id'     => $product->id,
                        'stockable_type' => 'App\\Product',
                        'stockable_id'   => $product->id,
                        'quantity'       => $qty,
                        'min_quantity'   => $minQty,
                        'max_quantity'   => $maxQty,
                        'last_cost'      => $request->stock_cost ?: null,
                        'average_cost'   => $request->stock_cost ?: null,
                        'total_in'       => $qty,
                        'total_out'      => 0,
                    ]);

                    if ($qty > 0) {
                        \App\StockMovement::createForProduct(
                            $branchId,
                            $product->id,
                            'in',
                            $qty,
                            auth()->id(),
                            [
                                'cost'           => $request->stock_cost ?: null,
                                'notes'          => $request->stock_notes ?: 'Added with product creation',
                                'balance_before' => 0, // new stock entry
                            ]
                        );
                    }
                }
            }
        }

        // ── Auto-register product in the main Store branch (is_main=1) ──────
        try {
            $storeBranch = \App\Branch::where('is_main', true)->where('is_active', true)->first()
                        ?? \App\Branch::where('is_active', true)
                            ->where(function($q) {
                                $q->where('name', 'like', '%store%')
                                  ->orWhere('name_ar', 'like', '%ستور%')
                                  ->orWhere('name_ar', 'like', '%مستودع%');
                            })->first();

            if ($storeBranch) {
                $storeQty    = max(0, (int) $request->input('store_quantity', 0));
                $storeMinQty = max(0, (int) $request->input('store_min_qty', 0));
                $storeMaxQty = $request->filled('store_max_qty') && (int)$request->store_max_qty > 0
                                ? max(1, (int) $request->store_max_qty) : 999;

                $existingStock = \App\BranchStock::where('branch_id', $storeBranch->id)
                    ->where('stockable_type', 'App\\Product')
                    ->where('stockable_id', $product->id)
                    ->first();

                if ($existingStock) {
                    // Already registered — increment quantity
                    $balanceBefore = $existingStock->quantity;
                    $existingStock->quantity    += $storeQty;
                    $existingStock->total_in    += $storeQty;
                    $existingStock->min_quantity = $storeMinQty;
                    $existingStock->max_quantity = $storeMaxQty;
                    $existingStock->save();
                    if ($storeQty > 0) {
                        \App\StockMovement::createForProduct(
                            $storeBranch->id, $product->id, 'in', $storeQty, auth()->id(),
                            ['notes' => 'Store stock added with product creation', 'balance_before' => $balanceBefore]
                        );
                    }
                } else {
                    \App\BranchStock::create([
                        'branch_id'      => $storeBranch->id,
                        'product_id'     => $product->id,
                        'stockable_type' => 'App\\Product',
                        'stockable_id'   => $product->id,
                        'quantity'       => $storeQty,
                        'min_quantity'   => $storeMinQty,
                        'max_quantity'   => $storeMaxQty,
                        'total_in'       => $storeQty,
                        'total_out'      => 0,
                    ]);
                    if ($storeQty > 0) {
                        \App\StockMovement::createForProduct(
                            $storeBranch->id, $product->id, 'in', $storeQty, auth()->id(),
                            ['notes' => 'Initial store stock — added with product creation', 'balance_before' => 0]
                        );
                    }
                }
            } else {
                session()->flash('warning', 'Product added, but no active Store branch found. Please set a branch as main store.');
            }
        } catch (\Exception $e) {
            // Product was already saved — don't fail the whole request, just warn
            session()->flash('warning', 'Product added successfully, but Store stock registration failed: ' . $e->getMessage());
        }

        session()->flash('success', 'Product added successfully!');
        return redirect()->route('dashboard.get-all-products');
    }

    /**
     * ====================================================
     * GET UPDATE PRODUCT - صفحة تعديل منتج
     * ====================================================
     */
    public function getUpdateProduct(Request $request, $id)
    {
         $user = auth()->user();
         $product = Product::findOrFail($id);
         //$product->stockInBranch($)

      /*  // Check if user can access this product's branch
        if (!$user->canAccessBranch($product->branch_id)) {
            abort(403, 'You do not have permission to edit this product.');
        }*/

        // Get accessible branches
       // $branches = $user->getAccessibleBranches();

        // Get all categories, brands, and models
        $categories = Category::all();
        $brands = Brand::all();
        $models = glassModel::all();

        return view('dashboard.pages.products.update', compact([
            'product',
            //'branches',
            'categories',
            'brands',
            'models'
        ]));
    }

    /**
     * ====================================================
     * POST UPDATE PRODUCT - حفظ تعديلات المنتج
     * ====================================================
     */
    public function updateProduct(Request $request, $id)
    {
        $user    = auth()->user();
        $product = Product::findOrFail($id);

        // Validate that user can access the new branch (if branch field is submitted and changed)
        if ($request->filled('branch') && $request->branch != $product->branch_id && !$user->canAccessBranch($request->branch)) {
            return redirect()->back()
                ->withErrors(['branch' => 'You do not have permission to move products to this branch.'])
                ->withInput();
        }

        $rules = [
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'color' => 'required',
            'size' => 'required',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            //'branch' => 'required|exists:branches,id',
            'category' => 'required|exists:categories,id',
            'brand' => 'required|exists:brands,id',
            'barcode'    => 'nullable|string|max:100|unique:products,barcode,' . $id,
        ];

        $messages = [
            'description.required' => 'Please enter product description',
            'price.required' => 'Please enter product price',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price must be greater than or equal to 0',
            'retail_price.required' => 'Please enter product retail price',
            'retail_price.numeric' => 'Retail price must be a number',
            'retail_price.min' => 'Retail price must be greater than or equal to 0',
            'tax.required' => 'Please enter product tax',
            'tax.numeric' => 'Tax must be a number',
            'tax.min' => 'Tax must be greater than or equal to 0',
            'color.required' => 'Please enter product color',
            'size.required' => 'Please enter product size',
            'discount_value.required' => 'Please enter product discount value',
            'discount_value.numeric' => 'Discount value must be a number',
            'discount_value.min' => 'Discount value must be greater than or equal to 0',
            'discount_type.required' => 'Please select discount type',
            'discount_type.in' => 'Discount type must be either fixed or percentage',
            /*'branch.required' => 'Please select a branch',
            'branch.exists' => 'Selected branch does not exist',*/
            'category.required' => 'Please select a category',
            'category.exists' => 'Selected category does not exist',
            'brand.required' => 'Please select a brand',
            'brand.exists' => 'Selected brand does not exist',
        ];

        $request->validate($rules, $messages);

        $product->product_id  = $request->product_id;
        $product->barcode     = $request->filled('barcode') ? trim($request->barcode) : null;
        $product->description = $request->filled('description') ? trim($request->description) : $product->description;
        $product->category_id = $request->category;
        $product->brand_id    = $request->brand;
        $product->model_id    = $request->model;
        $product->color       = $request->color;
        $product->size        = $request->size;
       // $product->branch_id = $request->branch;
        $product->price = $request->price;
        $product->retail_price = $request->retail_price;
        $product->tax = $request->tax;
        $product->discount_type = $request->discount_type;
        $product->discount_value = $request->discount_value;
        $product->brand_segment = $request->brand_segment;
        $product->power = $request->power;
        $product->sign = $request->sign;
        $product->lense_use = $request->lense_use;
        $product->type = $request->type;

        // Calculate total based on discount type
        if ($product->discount_type == 'fixed') {
            $product->total = $request->tax + ($request->retail_price - $request->discount_value);
        } else {
            $product->total = $request->tax + (($request->retail_price) - (($request->retail_price) * ($request->discount_value / 100)));
        }

        $product->save();

        // 🔥 Note: Stock management is done through BranchStock table
        // Branch changes are managed through Stock Transfer module

        session()->flash('success', 'Product updated successfully!');
        return redirect()->route('dashboard.get-all-products');
    }

    /**
     * ====================================================
     * GET PRODUCT DETAILS - مع نظام الفروع والمخزون
     * ====================================================
     */
    public function getProductDetails(Request $request)
    {
        $user = auth()->user();

        $product_code = $request->product_id;
        $branch_id = $request->branch_id;

        if (!$product_code) {
            return response()->json([
                'success' => false,
                'message' => 'Product ID is required'
            ], 400);
        }

        if (!$branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'Branch ID is required'
            ], 400);
        }

        if (!$user->canAccessBranch($branch_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this branch'
            ], 403);
        }

        $product = Product::with(['branchStocks' => function ($q) use ($branch_id) {
            $q->where('branch_id', $branch_id);
        }])
            ->where('product_id', $product_code)
            ->whereHas('branchStocks', function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id)
                    ->where('quantity', '>', 0);
            })
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found in this branch or out of stock'
            ], 404);
        }

        $branchStock = $product->branchStocks->first();
        $branch = Branch::find($branch_id);

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'product_id' => $product->product_id,
                'description' => $product->description,
                'category_id' => $product->category_id,
                'brand_id' => $product->brand_id,
                'model_id' => $product->model_id,
                'size' => $product->size,
                'color' => $product->color,
                'brand_segment' => $product->brand_segment,
                'power' => $product->power,
                'sign' => $product->sign,
                'type' => $product->type,
                'price' => (float) $product->price,
                'retail_price' => (float) $product->retail_price,
                'tax' => (float) $product->tax,
                'net_price' => (float) $product->net_price, // accessor
                'price_with_tax' => (float) $product->price_with_tax, // accessor
                'stock' => $branchStock->quantity,
                'available_quantity' => $branchStock->available_quantity,
                'branch_id' => $branch->id,
                'branch_name' => $branch->name ?? $branch->branch_name,
                'branch_full_name' => $branch->full_name ?? $branch->name,
            ]
        ]);
    }

    /**
     * ====================================================
     * GET DISCOUNTS VIEW
     * ====================================================
     */
    public function getDiscountsView(Request $request)
    {
        $user = auth()->user();
        $branchId = $user->getFilterBranchId($request->branch_id);

        $products = Product::when($branchId, function ($query) use ($branchId) {
            return $query->where('branch_id', $branchId);
        })
            ->pluck('id', 'product_id');

        return view('dashboard.pages.products.discount')->with(compact('products'));
    }

    /**
     * ====================================================
     * SAVE DISCOUNTS - حفظ الخصومات لمنتجات متعددة
     * ====================================================
     */
    public function saveDiscounts(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'discount_value' => 'required|numeric|min:0',
            'discount_type' => 'required|in:fixed,percentage',
        ];

        $messages = [
            'products.required' => 'Please select products',
            'products.array' => 'Invalid products selection',
            'products.*.exists' => 'One or more selected products do not exist',
            'discount_value.required' => 'Please enter discount value',
            'discount_value.numeric' => 'Discount value must be a number',
            'discount_value.min' => 'Discount value must be greater than or equal to 0',
            'discount_type.required' => 'Please select discount type',
            'discount_type.in' => 'Discount type must be either fixed or percentage',
        ];

        $request->validate($rules, $messages);

        try {
            $products = $request->products;
            $discount_type = $request->discount_type;
            $discount_value = $request->discount_value;

            foreach ($products as $id) {
                $product = Product::find($id);

                // Check if user can access this product's branch
                if (!$user->canAccessBranch($product->branch_id)) {
                    continue; // Skip products user doesn't have access to
                }

                $product->update([
                    'discount_type' => $discount_type,
                    'discount_value' => $discount_value,
                ]);

                // Recalculate total
                if ($product->discount_type == 'fixed') {
                    $product->total = $product->tax + ($product->retail_price - $product->discount_value);
                } else {
                    $product->total = $product->tax + (($product->retail_price) - (($product->retail_price) * ($product->discount_value / 100)));
                }

                $product->save();
            }

            session()->flash('success', 'Discounts updated successfully!');
            return redirect()->route('dashboard.discounts-view');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating discounts: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * ====================================================
     * SHOW PRODUCT - عرض تفاصيل المنتج الكاملة
     * ====================================================
     */
    public function showProduct($id)
    {
        $user = auth()->user();
        $product = Product::with(['category', 'brand', 'model', 'branchStocks.branch'])->findOrFail($id);

        // Check if user can access this product (at least in one branch)
        /*$canAccess = false;
        foreach ($product->branchStocks as $stock) {
            if ($user->canAccessBranch($stock->branch_id)) {
                $canAccess = true;
                break;
            }
        }

        if (!$canAccess && !$user->canSeeAllBranches()) {
            abort(403, 'You do not have permission to view this product.');
        }*/

        // Get all branch stocks for this product
        $branchStocks = $product->branchStocks()
            ->with('branch')
            ->get()/*
            ->filter(function($stock) use ($user) {
                return $user->canAccessBranch($stock->branch_id);
            })*/;

        // Calculate statistics
        $totalQuantity = $branchStocks->sum('quantity');
        $totalAvailable = $branchStocks->sum(function($stock) {
            return $stock->available_quantity;
        });
        $totalReserved = $branchStocks->sum('reserved_quantity');
        $totalValue = $totalQuantity * $product->retail_price;

        // Get sales count (from invoice items)
        $totalSold = InvoiceItems::where('product_id', $product->product_id)->sum('quantity');
        $salesCount = InvoiceItems::where('product_id', $product->product_id)->count();

        // Get recent movements (last 10)
        $recentMovements = StockMovement::where('stockable_type', 'App\\Product')
            ->where('stockable_id', $product->id)
            ->with(['branch', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.pages.products.show', compact(
            'product',
            'branchStocks',
            'totalQuantity',
            'totalAvailable',
            'totalReserved',
            'totalValue',
            'totalSold',
            'salesCount',
            'recentMovements'
        ));
    }

    /**
     * ====================================================
     * DELETE PRODUCT - حذف منتج
     * ====================================================
     */

    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            session()->flash('error', 'المنتج غير موجود.');
            return redirect()->back();
        }

        $result = $product->safeDelete();

        switch ($result['action']) {
            case 'blocked':
                session()->flash('error', $result['message']);
                break;
            case 'archived':
                session()->flash('warning', $result['message']);
                break;
            case 'deleted':
                session()->flash('success', $result['message']);
                break;
        }

        return redirect()->back();
    }

    /**
     * Restore an archived (inactive) product.
     */
    public function restoreProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            session()->flash('error', 'المنتج غير موجود.');
            return redirect()->back();
        }

        $product->restore();
        session()->flash('success', 'تم استعادة المنتج وتفعيله مجدداً.');
        return redirect()->back();
    }

    public function importProducts(Request $request)
    {
        $request->validate(['import_data' => 'required|string']);

        $rows       = json_decode($request->input('import_data'), true);
        $added      = 0;
        $skipped    = [];
        $errors     = [];
        $seenInFile = [];

        if (empty($rows) || !is_array($rows)) {
            return redirect()->route('dashboard.get-add-product')
                ->with('import_added', 0)
                ->with('import_errors', ['No data received.']);
        }

        $norm = function($s) {
            $s = strtolower(trim((string)$s));
            $s = preg_replace('/^[a-z0-9]+;/', '', $s);
            $s = str_replace(['-', '_'], ' ', $s);
            $s = preg_replace('/\s+/', ' ', $s);
            return trim($s);
        };

        // ── Build lookup maps ─────────────────────────────────────
        $catMap   = [];
        $brandMap = [];
        $modelMap = [];

        foreach (Category::all() as $c) {
            $catMap[$norm($c->category_name)] = $c;
        }
        foreach (Brand::all() as $b) {
            $key = $norm($b->brand_name);
            if (!isset($brandMap[$key])) $brandMap[$key] = $b;
        }
        foreach (glassModel::all() as $m) {
            $key = $norm($m->model_id);
            if (!isset($modelMap[$key])) $modelMap[$key] = $m;
        }

        $lastId      = Product::orderByDesc('product_id')->value('product_id');
        $nextNum     = is_numeric($lastId) ? ((int)$lastId + 1) : 100001;

        // Find Store branch once — fallback to name search if is_main not set
        try {
            $storeBranch = \App\Branch::where('is_main', true)->where('is_active', true)->first()
                        ?? \App\Branch::where('is_active', true)
                            ->where(function($q) {
                                $q->where('name', 'like', '%store%')
                                  ->orWhere('name_ar', 'like', '%ستور%')
                                  ->orWhere('name_ar', 'like', '%مستودع%');
                            })->first();
        } catch (\Exception $e) {
            $storeBranch = null;
        }

        foreach ($rows as $i => $row) {
            $rowNum    = $i + 1;
            $rowErrors = [];

            $catKey   = $norm($row['category'] ?? '');
            $brandKey = $norm($row['brand']    ?? '');
            $modelKey = $norm($row['model']    ?? '');
            $desc     = trim($row['description'] ?? '');

            // ── 1. Validate وجود كل عنصر ─────────────────────────
            if (!$desc) $rowErrors[] = 'Missing description';

            $cat = null;
            if (!$catKey) {
                $rowErrors[] = 'Missing category';
            } elseif (!isset($catMap[$catKey])) {
                $rowErrors[] = 'Category "'.($row['category'] ?? '').'" not found in system';
            } else {
                $cat = $catMap[$catKey];
            }

            $brand = null;
            if (!$brandKey) {
                $rowErrors[] = 'Missing brand';
            } elseif (!isset($brandMap[$brandKey])) {
                $rowErrors[] = 'Brand "'.($row['brand'] ?? '').'" not found in system';
            } else {
                $brand = $brandMap[$brandKey];
            }

            // Categories that require a model (Frames & Sunglasses only)
            $catNeedsModel = $cat && in_array((int)$cat->id, [1, 2]);

            $model = null;
            if ($catNeedsModel) {
                if (!$modelKey) {
                    $rowErrors[] = 'Missing model (required for Frames / Sunglasses)';
                } elseif (!isset($modelMap[$modelKey])) {
                    $rowErrors[] = 'Model "'.($row['model'] ?? '').'" not found in system';
                } else {
                    $model = $modelMap[$modelKey];
                }
            } elseif ($modelKey && isset($modelMap[$modelKey])) {
                // Model provided but optional — use it anyway
                $model = $modelMap[$modelKey];
            }

            // ── 2. Validate relationships ──────────────────────────
            if ($cat && $brand) {
                // Brand must belong to the selected category
                if ((int)$brand->category_id !== (int)$cat->id) {
                    $rowErrors[] = 'Brand "'.($row['brand'] ?? '').'" does not belong to category "'.($row['category'] ?? '').'"';
                }
            }

            if ($model && $brand) {
                // Model must belong to the brand
                if ((int)$model->brand_id !== (int)$brand->id) {
                    $rowErrors[] = 'Model "'.($row['model'] ?? '').'" does not belong to brand "'.($row['brand'] ?? '').'"';
                }
            }

            // ── Skip if any error ─────────────────────────────────
            if (!empty($rowErrors)) {
                $pid = !empty($row['product_id']) ? $row['product_id'] : "Row {$rowNum}";
                $errors[] = "[{$pid}]: " . implode(' | ', $rowErrors);
                continue;
            }

            // ── Product ID ────────────────────────────────────────
            $productId = !empty(trim($row['product_id'] ?? ''))
                ? trim($row['product_id'])
                : (string)$nextNum;

            if (isset($seenInFile[$productId])) {
                $skipped[] = "Row {$rowNum}: ID '{$productId}' duplicated in file (first at row {$seenInFile[$productId]}) — skipped.";
                continue;
            }
            if (Product::where('product_id', $productId)->exists()) {
                $skipped[] = "Row {$rowNum}: ID '{$productId}' already exists in database — skipped.";
                continue;
            }

            $seenInFile[$productId] = $rowNum;
            if (empty(trim($row['product_id'] ?? ''))) $nextNum++;

            // ── Auto-generate unique EAN-13 barcode ───────────────
            $barcode = !empty(trim($row['barcode'] ?? ''))
                ? trim($row['barcode'])
                : $this->generateUniqueBarcode();

            // ── Create product ────────────────────────────────────────────
            $newProduct = null;
            try {
                $retailPrice    = (float)($row['retail_price']  ?? 0);
                $discountType   = in_array(($row['discount_type'] ?? ''), ['fixed','percentage'])
                    ? $row['discount_type'] : 'fixed';
                $discountValue  = (float)($row['discount_value'] ?? 0);
                $tax            = (float)($row['tax'] ?? 0);

                // Calculate total (same logic as manual add)
                if ($discountType === 'fixed') {
                    $total = $tax + ($retailPrice - $discountValue);
                } else {
                    $total = $tax + ($retailPrice - ($retailPrice * ($discountValue / 100)));
                }

                $newProduct = Product::create([
                    'product_id'     => $productId,
                    'barcode'        => $barcode,
                    'description'    => $desc,
                    'category_id'    => $cat->id,
                    'brand_id'       => $brand->id,
                    'model_id'       => $model ? $model->id : 0,
                    'color'          => trim($row['color']  ?? ''),
                    'size'           => trim($row['size']   ?? ''),
                    'price'          => (float)($row['price'] ?? 0),
                    'retail_price'   => $retailPrice,
                    'tax'            => $tax,
                    'total'          => $total,
                    'discount_type'  => $discountType,
                    'discount_value' => $discountValue,
                    'brand_segment'  => !empty($row['brand_segment']) ? $row['brand_segment'] : null,
                    'power'          => !empty($row['power'])         ? $row['power']         : null,
                    'sign'           => !empty($row['sign'])          ? $row['sign']          : null,
                    'lense_use'      => !empty($row['lense_use'])     ? $row['lense_use']     : null,
                    'type'           => !empty($row['type'])          ? $row['type']          : null,
                ]);
                $added++;
            } catch (\Exception $e) {
                $errors[] = "[{$productId}]: DB error — " . $e->getMessage();
                continue; // Skip to next row — product wasn't saved
            }

            // ── Add / increment stock in the main Store branch ──────────────
            if ($storeBranch && $newProduct) {
                try {
                    $qty    = max(0, (int)($row['quantity']     ?? 0));
                    $minQty = max(0, (int)($row['min_quantity'] ?? 0));
                    $maxQty = isset($row['max_quantity']) && (int)$row['max_quantity'] > 0
                                ? max(1, (int)$row['max_quantity']) : 999;

                    $existingStock = \App\BranchStock::where('branch_id', $storeBranch->id)
                        ->where('stockable_type', 'App\\Product')
                        ->where('stockable_id', $newProduct->id)
                        ->first();

                    if ($existingStock) {
                        $balanceBefore = $existingStock->quantity;
                        $existingStock->quantity    += $qty;
                        $existingStock->total_in    += $qty;
                        if ($minQty > 0) $existingStock->min_quantity = $minQty;
                        if ($maxQty < 999) $existingStock->max_quantity = $maxQty;
                        $existingStock->save();
                        if ($qty > 0) {
                            \App\StockMovement::createForProduct(
                                $storeBranch->id, $newProduct->id, 'in', $qty, auth()->id(),
                                ['notes' => 'Store stock added via product import', 'balance_before' => $balanceBefore]
                            );
                        }
                    } else {
                        \App\BranchStock::create([
                            'branch_id'      => $storeBranch->id,
                            'product_id'     => $newProduct->id,
                            'stockable_type' => 'App\\Product',
                            'stockable_id'   => $newProduct->id,
                            'quantity'       => $qty,
                            'min_quantity'   => $minQty,
                            'max_quantity'   => $maxQty,
                            'total_in'       => $qty,
                            'total_out'      => 0,
                        ]);
                        if ($qty > 0) {
                            \App\StockMovement::createForProduct(
                                $storeBranch->id, $newProduct->id, 'in', $qty, auth()->id(),
                                ['notes' => 'Initial store stock — imported from Excel', 'balance_before' => 0]
                            );
                        }
                    }
                } catch (\Exception $e) {
                    // Product was added — only stock registration failed
                    $skipped[] = "[{$productId}]: Added OK, but Store stock registration failed — " . $e->getMessage();
                }
            }
        }
        // ── Done — redirect once after processing ALL rows ────────
        return redirect()
            ->route('dashboard.get-add-product')
            ->with('import_added',   $added)
            ->with('import_skipped', $skipped)
            ->with('import_errors',  $errors);
    }


    public function downloadTestTemplate()
    {
        $path = public_path('templates/test_products_import.xlsx');

        if (!file_exists($path)) {
            abort(404, 'Test template not found.');
        }

        while (ob_get_level()) { ob_end_clean(); }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="test_products_import.xlsx"');
        header('Content-Length: ' . filesize($path));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($path);
        exit;
    }

    /**
     * Generate a unique EAN-13 barcode not already used in the DB.
     */
    private function generateUniqueBarcode(): string
    {
        do {
            // 12 random digits
            $digits = '';
            for ($i = 0; $i < 12; $i++) {
                $digits .= random_int(0, 9);
            }
            // EAN-13 check digit
            $sum = 0;
            for ($i = 0; $i < 12; $i++) {
                $sum += (int)$digits[$i] * ($i % 2 === 0 ? 1 : 3);
            }
            $check   = (10 - ($sum % 10)) % 10;
            $barcode = $digits . $check;
        } while (Product::where('barcode', $barcode)->exists());

        return $barcode;
    }

    public function downloadProductTemplate()
    {
        $path = public_path('templates/product_import_template.xlsx');

        if (!file_exists($path)) {
            abort(404, 'Template file not found. Please contact the administrator.');
        }

        // Clear ALL output buffers to prevent any extra bytes corrupting the ZIP/xlsx
        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="product_import_template.xlsx"');
        header('Content-Length: ' . filesize($path));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        readfile($path);
        exit;
    }

}
