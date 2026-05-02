<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.spatie:view-categories')->only([
            'index'
        ]);

        $this->middleware('permission.spatie:create-categories')->only([
            'addCategory'
        ]);

        $this->middleware('permission.spatie:edit-categories')->only([
            'updateCategory'
        ]);

        $this->middleware('permission.spatie:delete-categories')->only([
            'deleteCategory'
        ]);
    }

    public function index(Request $request) {
        $categories = Category::when($request->search, function($query) use ($request) {
            return $query->where('category_name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);
        return view('dashboard.pages.categories.index', compact('categories'));
    }

    public function addCategory(Request $request) {
        $categorie = new Category();

        $rules = [
            'category_name' => 'required|unique:categories',
        ];

        $messages = [
            'category_name.required' => 'Please enter category name',
            'category_name.unique' => 'This Category Added Before',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return response()->json(['message' => 'This Categoery Added Before!']);
        }

        $categorie->category_name = $request->category_name;
        $categorie->save();

    }

    public function updateCategory(Request $request) {
        $categorie = Category::find($request->id);

        $rules = [
            'category_name' => 'required|unique:categories,id,'.$request->id,
        ];

        $messages = [
            'category_name.required' => 'Please enter category name',
            'category_name.unique' => 'This Category Added Before',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return response()->json(['message' => 'This Categoery Added Before!']);
        }

        $categorie->category_name = $request->category_name;
        $categorie->save();

    }


    public function deleteCategory($id) {
        try {
            DB::beginTransaction();

            $category = Category::find($id);

            if (!$category) {
                session()->flash('error', 'Cannot find this category!');
                return redirect()->back();
            }

            // جلب كل المنتجات المرتبطة بالـ category مع المخزون والفروع
            $products = $category->catProducts()->with('branchStocks.branch')->get();

            // التحقق من أي منتج يحتوي على مخزون
            $productsWithStock = $products->filter(function($product) {
                return $product->branchStocks()->where(function($q) {
                    $q->where('quantity', '>', 0)
                        ->orWhere('reserved_quantity', '>', 0);
                })->exists();
            });

            if ($productsWithStock->count() > 0) {
                $productNames = $productsWithStock->map(function($product) {
                    $stocks = $product->branchStocks()
                        ->where(function($q) {
                            $q->where('quantity', '>', 0)
                                ->orWhere('reserved_quantity', '>', 0);
                        })
                        ->get()
                        ->map(function($stock) {
                            return $stock->branch->name . " (Qty: {$stock->quantity}, Reserved: {$stock->reserved_quantity})";
                        })->implode(' , ');

                    return $product->name . " => " . $stocks;
                })->implode(" | ");

                session()->flash('error', "Cannot delete category. Some products have stock: " . $productNames);
                DB::rollBack();
                return redirect()->back();
            }

            // حذف المنتجات المرتبطة (لأن مفيش مخزون)
            foreach($products as $product) {
                $product->delete();
            }

            // حذف الـ brands المرتبطة
            if ($category->catBrands) {
                foreach($category->catBrands as $brand) {
                    $brand->delete();
                }
            }

            // حذف الـ category
            $category->delete();

            DB::commit();
            session()->flash('success', 'Category deleted successfully!');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            // ممكن كمان تعمل logging للخطأ
            // \Log::error('Delete category failed: '.$e->getMessage());
            session()->flash('error', 'An unexpected error occurred: ' . $e->getMessage());
            return redirect()->back();
        }
    }

}
