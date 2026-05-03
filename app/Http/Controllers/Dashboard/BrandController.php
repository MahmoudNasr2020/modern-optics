<?php

namespace App\Http\Controllers\Dashboard;

use App\Brand;
use App\Category;
use App\glassModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class BrandController extends Controller
{
    public function __construct()
    {
        // عرض البراندات
        $this->middleware('permission.spatie:view-brands')->only([
            'index',
        ]);

        // إضافة براند
        $this->middleware('permission.spatie:create-brands')->only([
            'addBrand',
        ]);

        // تعديل براند
        $this->middleware('permission.spatie:edit-brands')->only([
            'updateBrand',
        ]);

        // حذف موديل (مرتبط بالبراند/المنتجات)
        $this->middleware('permission.spatie:delete-brands')->only([
            'deleteModel',
        ]);
    }

    public function index(Request $request) {
        $categories = Category::all();
        $brands = Brand::with('category')
            ->when($request->search, function($query) use ($request) {
                return $query->where('category_id', 'like', '%' . $request->search . '%')
                    ->orWhere('brand_name', 'like', '%' . $request->search . '%');
            })->latest()->paginate(10);
        return view('dashboard.pages.brands.index', compact(['brands', 'categories']));
    }

    public function addBrand(Request $request) {
        $brand = new Brand();

        $rules = [
            'brand_name' => 'required',
        ];

        $messages = [
            'brand_name.required' => 'Please enter brand name',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return response()->json(['message' => 'This Brand Added Before!']);
        }
        $exisitedBrand = Brand::where([
            ['brand_name', '=', $request->brand_name],
            ['category_id', '=', $request->category_id],
        ])->first();

        if($exisitedBrand) {
            return response()->json(['message' => 'This Brand Added Before Under This Category!']);
        }

        $brand->brand_name = $request->brand_name;
        $brand->category_id = $request->category_id;
        $brand->save();

    }

    public function updateBrand(Request $request) {
        $brand = Brand::find($request->id);

        $rules = [
            'brand_name' => 'required',
        ];

        $messages = [
            'brand_name.required' => 'Please enter Brand name',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return response()->json(['message' => 'This Brand Added Before!']);
        }

        $brand->brand_name = $request->brand_name;
        $brand->save();

    }




    public function deleteModel($id)
    {
        try {
            DB::beginTransaction();

            $model = GlassModel::with('products.branchStocks.branch')->find($id);

            if (!$model) {
                session()->flash('error', 'Cannot find this model!');
                return redirect()->back();
            }

            $products = $model->products;

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
                            return (optional($stock->branch)->name ?? 'Branch#'.$stock->branch_id) .
                                " (Qty: {$stock->quantity}, Reserved: {$stock->reserved_quantity})";
                        })->implode(' , ');

                    return $product->name . " => " . $stocks;
                })->implode(" | ");

                session()->flash('error', "Cannot delete model. Some products have stock: " . $productNames);
                DB::rollBack();
                return redirect()->back();
            }

            // حذف المنتجات المرتبطة (لأن مفيش مخزون)
            foreach ($products as $product) {
                $product->delete();
            }

            // حذف الموديل نفسه
            $model->delete();

            DB::commit();
            session()->flash('success', 'Model deleted successfully!');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error('Delete model failed: '.$e->getMessage());
            session()->flash('error', 'An unexpected error occurred: ' . $e->getMessage());
            return redirect()->back();
        }
    }



}
