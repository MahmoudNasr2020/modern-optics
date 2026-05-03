<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Brand;
use App\Category;
use App\glassModel;
use Illuminate\Support\Facades\DB;
use Validator;

class GlassModelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.spatie:view-models')
            ->only([
                'index'
            ]);

        $this->middleware('permission.spatie:create-models')
            ->only([
                'addModel'
            ]);

        $this->middleware('permission.spatie:edit-models')
            ->only([
                'updateModel'
            ]);

        $this->middleware('permission.spatie:delete-models')
            ->only([
                'deleteModel'
            ]);
    }


    public function index(Request $request) {
        $categories = Category::all();
        $brands = Brand::all();
        $models = glassModel::with(['category', 'brand'])
            ->when($request->search, function($query) use ($request) {
                return $query->where('model_id', 'like', '%' . $request->search . '%');
            })->latest()->paginate(10);
        return view('dashboard.pages.models.index', compact(['models', 'categories', 'brands']));
    }

    public function addModel(Request $request) {
        $model = new glassModel();

        $rules = [
            'model_id' => 'required|unique:glass_models',
        ];

        $messages = [
            'model_id.required' => 'Please enter model ID',
            'model_id.unique' => 'This model Added Before',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return response()->json(['message' => 'This Model Added Before!']);
        }

        $model->category_id = $request->category_id;
        $model->brand_id    = $request->brand_id;
        $model->model_id    = $request->model_id;

        $model->save();

    }

    public function updateModel(Request $request) {
        $model = glassModel::find($request->id);
        $rules = [
            'model_id' => 'required',
        ];

        $messages = [
            'model_id.required' => 'Please enter model ID',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return response()->json(['message' => 'Please enter model ID!']);
        }
        $model->model_id = $request->model_id;
        $model->save();

    }


    public function deleteModel($id)
    {
        try {

            DB::beginTransaction();

            $model = GlassModel::with('products')->findOrFail($id);

            // لو فيه منتجات مرتبطة بالموديل
            if ($model->products()->count() > 0) {

                // لو عندك شرط ستوك — احذفه أو عدل حسب نظامك
                foreach ($model->products as $product) {

                    // مثال: متحذفش منتج عليه ستوك
                    if ($product->stock()->sum('quantity') > 0) {
                        DB::rollBack();
                        session()->flash('error', 'Cannot delete model. Some products still have stock!');
                        return redirect()->back();
                    }

                    // احذف المنتج
                    $product->delete();
                }
            }

            // احذف الموديل نفسه
            $model->delete();

            DB::commit();

            session()->flash('success', 'Model deleted successfully!');
            return redirect()->back();

        } catch (\Exception $e) {

            DB::rollBack();

            session()->flash('error', 'Something went wrong while deleting the model.');
            return redirect()->back();
        }
    }

}
