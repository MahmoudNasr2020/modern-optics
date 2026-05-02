<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\Brand;
use App\Category;
use App\glassLense;
use App\glassModel;
use App\Http\Controllers\Controller;
use App\Product;
use DB;
use Illuminate\Http\Request;

class StockOverview extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $models = glassModel::all();

        return view('dashboard.pages.stock.index', compact(['categories', 'brands', 'models']));
    }

    public function getItemInquiry(Request $request) {
        $branches = Branch::all();
        return view('dashboard.pages.stock.item_overview', compact(['branches']));
    }

    public function searchItem(Request $request)
    {
        if(Product::where('product_id', $request->product_id)->first()) {
            $product = Product::where('product_id', $request->product_id)->first();
        } else {
            $product = glassLense::where('product_id', $request->product_id)->first();
        }
        if ($product) {
            $branch_name = Branch::select('branch_name')->where('id', $product->branch_id)->first();
            $product['branch_name'] = $branch_name;
            return response()->json($product);
        } else {
            return response()->json(['message' => 'No Item Found With this ID!']);
        }
    }


    public function searchItemInq(Request $request)
    {
        $product = Product::where('product_id', $request->product_id)->first();

        if ($product) {
            $branch_name = Branch::select('branch_name')->where('id', $product->branch_id)->first();
            $product['branch_name'] = $branch_name;
            return response()->json($product);
        } else {
            return response()->json(['message' => 'No Item Found With this ID!']);
        }
    }



    // Filter Products By Category ID
    public function filterByCatId(Request $request)
    {
        $products = Product::where('category_id', $request->category_id)->get();

        if ($products) {
            return response()->json($products);
        }
    }

    // Filter Products By Brand ID
    public function filterByBrandId(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();

        if ($products) {
            return response()->json($products);
        }
    }

    // Filter Products By Model ID
    public function filterByModelId(Request $request)
    {
        $products = Product::where('model_id', $request->model_id)->get();

        if ($products) {
            return response()->json($products);
        }
    }


    // Filter Products By Size
    public function filterBySize(Request $request)
    {
        $products = Product::where('size', $request->size)->get();

        if ($products) {
            return response()->json($products);
        }
    }

    // Filter Products By Color
    public function filterByColor(Request $request)
    {
        $products = Product::where('color', $request->color)->get();

        if ($products) {
            return response()->json($products);
        }
    }


    // Filter Products By Category And Brand
    // public function filterProductsByCategoryAndBrand(Request $request) {
    //     $products = Product::where(['category_id' => $request->category_id, 'brand_id' => $request->brand_id])->get();

    //     if($products) {
    //         return response()->json($products);
    //     }
    // }


    // Filter Brands by Category ID
    public function filterBrandsByCatId(Request $request)
    {
        $brands = Brand::where('category_id', $request->category_id)->get();

        if ($brands) {
            return response()->json($brands);
        }
    }

    // Filter Models  by Brand ID
    public function filterModelsByBrandId(Request $request)
    {
        $models = glassModel::where('brand_id', $request->brand_id)->get();

        if ($models) {
            return response()->json($models);
        }
    }

    // Filter Models By Category_id AND BrandId
    public function filterModelsByCategoryIdAndBrandId(Request $request)
    {
        if ($request->brand_id) {
            $models = glassModel::where([['category_id', '=', $request->category_id],
                ['brand_id', '=', $request->brand_id]])->get();
        } else {
            $models = glassModel::where('category_id', $request->category_id)->get();
        }

        if ($models) {
            return response()->json($models);
        }
    }

   /* public function fullSearch(Request $request)
    {

        $cat_id = $request->category_id;
        $brand_id = $request->brand_id;
        $model_id = $request->model_id;
        $size = $request->size;
        $color = $request->color;
        $segment = $request->brand_segment;
        $power = $request->power;
        $sign = $request->sign;
        $type = $request->type;

        $query = 'SELECT * From products WHERE('
        . ( $cat_id ? 'category_id = :category_id' : '' )
        . ( $brand_id ? ' AND brand_id = :brand_id' : '')
        . ( $model_id ? ' AND model_id = :model_id' : '')
        . ( $size ? ' AND size = :size' : '')
        . ( $segment ? ' AND brand_segment = :segment' : '')
        . ( $power ? ' AND power = :power' : '')
        . ( $sign ? ' AND sign = :sign' : '')
        . ( $type ? ' AND type = :type' : '')
        . ( $color ? ' AND color = :color' : '') . ')';
        // dd($query);
        $parmetersArray = Array(
            'category_id' => $cat_id,
            (!$brand_id) ? NULL : 'brand_id' => $brand_id,
            (!$model_id) ? NULL : 'model_id' => $model_id,
            (!$color) ? NULL : 'color' => $color,
            (!$size) ? NULL : 'size' => $size,
            (!$segment) ? NULL : 'segment' => $segment,
            (!$power) ? NULL : 'power' => $power,
            (!$sign) ? NULL : 'sign' => $sign,
            (!$type) ? NULL : 'type' => $type
        );

        $this->removeEmptyValues($parmetersArray);
        $products = DB::select($query, $parmetersArray);

        foreach ($products as $product) {
            $branch_name = Branch::select('branch_name')->where('id', $product->branch_id)->first();
            $product->branch_name = $branch_name;
        }

        return response()->json($products);
    }

    // To Remove ements with Empy Values from an array
    function removeEmptyValues(array &$array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = removeEmptyValues($value);
            }
            if (empty($value)) {
                unset($array[$key]);
            }
        }
        return $array;
    }*/

    /**
     * ====================================================
     * FULL SEARCH - مع نظام الفروع والمخزون
     * ====================================================
     */
    /**
     * ====================================================
     * FULL SEARCH - مظبوطة 100% مع العلاقات
     * ====================================================
     */
    public function fullSearch(Request $request)
    {
        // Get current user
        $user = auth()->user();

        // Get selected branch
        $branch_id = $request->branch_id;

        // Validate branch
        if (!$branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a branch first'
            ], 400);
        }

        // Check branch access
        if (!$user->canAccessBranch($branch_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this branch'
            ], 403);
        }

        // Get search parameters
        $cat_id = $request->category_id;
        $brand_id = $request->brand_id;
        $model_id = $request->model_id;
        $size = $request->size;
        $color = $request->color;
        $segment = $request->brand_segment;
        $power = $request->power;
        $sign = $request->sign;
        $type = $request->type;

        // ✅ Build query باستخدام Eloquent Relationships
        $query = Product::query()
            ->with(['branchStocks' => function($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            }])
            ->whereHas('branchStocks', function($q) use ($branch_id) {
                $q->where('branch_id', $branch_id)
                    ->where('quantity', '>', 0);
            });

        // Apply filters
        if ($cat_id) {
            $query->where('category_id', $cat_id);
        }

        if ($brand_id) {
            $query->where('brand_id', $brand_id);
        }

        if ($model_id) {
            $query->where('model_id', $model_id);
        }

        if ($size) {
            $query->where('size', $size);
        }

        if ($color) {
            $query->where('color', 'LIKE', "%{$color}%");
        }

        if ($segment) {
            $query->where('brand_segment', $segment);
        }

        if ($power) {
            $query->where('power', $power);
        }

        if ($sign) {
            $query->where('sign', $sign);
        }

        if ($type) {
            $query->where('type', $type);
        }

        // Execute query
        $products = $query->orderBy('product_id', 'ASC')->get();

        // Get branch info
        $branch = Branch::find($branch_id);

        // Format response
        $productsArray = $products->map(function ($product) use ($branch) {
            // Get stock for this branch
            $branchStock = $product->branchStocks->first();

            return [
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
                'stock' => $branchStock ? $branchStock->quantity : 0,
                'available_quantity' => $branchStock ? $branchStock->available_quantity : 0,
                'branch_id' => $branch->id,
                'branch_name' => $branch->name ?? $branch->branch_name,
                'branch_full_name' => $branch->full_name ?? $branch->name,
            ];
        });

        return response()->json([
            'success' => true,
            'products' => $productsArray,
            'count' => $productsArray->count(),
            'branch_id' => $branch_id,
            'branch_name' => $branch->name ?? $branch->branch_name
        ]);
    }
}
