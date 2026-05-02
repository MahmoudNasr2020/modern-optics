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
        $products = Product::with(['category', 'brand', 'model'])
            ->when($request->search, function ($query) use ($request) {
                $query->where(function($q) use ($request){
                    $q->where('product_id', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        $totalItems = Product::count();

        return view('dashboard.pages.products.index', compact(
            'products',
            'totalItems'
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

        // Get last product ID for auto-generation
        $productID = Product::select('product_id')->latest()->first();

        // Get accessible branches
        //$branches = $user->getAccessibleBranches();

        // Get all categories, brands, and models
        $categories = Category::all();
        $brands = Brand::all();
        $models = glassModel::all();

        return view('dashboard.pages.products.create', compact([
            'productID',
           // 'branches',
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

        $rules = [
            'product_id' => 'unique:products',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'color' => 'required',
            'size' => 'required',
            'model' => 'required',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
           // 'branch' => 'required|exists:branches,id',
            'category' => 'required|exists:categories,id',
            'brand' => 'required|exists:brands,id',
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
        $product->product_id = $request->product_id;
        $product->description = $request->description;
        $product->category_id = $request->category;
        $product->brand_id = $request->brand;
        $product->model_id = $request->model;
        $product->color = $request->color;
        $product->size = $request->size;
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

        // 🔥 Note: Stock management is done through BranchStock table
        // No need to create BranchStock here - it's managed separately through Stock Management module

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
        //$user = auth()->user();
        $product = Product::findOrFail($id);

        // Check if user can access this product's branch
       /* if (!$user->canAccessBranch($product->branch_id)) {
            abort(403, 'You do not have permission to edit this product.');
        }*/

        // Validate that user can access the new branch (if changed)
        if ($request->branch != $product->branch_id && !$user->canAccessBranch($request->branch)) {
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

        $product->product_id = $request->product_id;
        $product->description = $request->description;
        $product->category_id = $request->category;
        $product->brand_id = $request->brand;
        $product->model_id = $request->model;
        $product->color = $request->color;
        $product->size = $request->size;
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
        $user = auth()->user();
        $product = Product::with('branchStocks.branch')->find($id);

        if (!$product) {
            session()->flash('error', 'Product not found.');
            return redirect()->back();
        }

        /*
        // لو عايز تفعل صلاحيات الفروع
        if (!$user->canAccessBranch($product->branch_id)) {
            abort(403, 'You do not have permission to delete this product.');
        }
        */

        $branchesWithStock = $product->branchStocks()
            ->where(function ($q) {
                $q->where('quantity', '>', 0)
                    ->orWhere('reserved_quantity', '>', 0);
            })
            ->with('branch')
            ->get();

        if ($branchesWithStock->count() > 0) {

            $branchNames = $branchesWithStock->map(function ($stock) {
                return $stock->branch->name . " (Qty: {$stock->quantity}, Reserved: {$stock->reserved_quantity})";
            })->implode(' , ');

            session()->flash('error',
                "Cannot delete product. Please zero stock in all branches first.Stock available: " . $branchNames
            );

            return redirect()->back();
        }


        $product->delete();

        session()->flash('success', 'Product deleted successfully!');
        return redirect()->back();
    }

}
