<?php

namespace App\Http\Controllers\Dashboard;

use App\Cardholder;
use App\Category;
use App\Http\Requests\Dashboard\CardholderRequest;
use App\Http\Requests\Dashboard\InsuranceCompanyRequest;
use App\InsuranceCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CardholderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.spatie:view-cardholders')->only([
            'index'
        ]);

        $this->middleware('permission.spatie:create-cardholders')->only([
            'create',
            'store'
        ]);

        $this->middleware('permission.spatie:edit-cardholders')->only([
            'edit',
            'update'
        ]);


        $this->middleware('permission.spatie:delete-cardholders')->only([
            'destroy'
        ]);
    }

    public function index(Request $request)
    {
        $cardholders = Cardholder::when($request->search, function ($query) use ($request) {
            return $query->where('cardholder_name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10)->appends($request->except('page'));;

        return view('dashboard.pages.cardholders.index')->with(compact('cardholders'));
    }

    public function create(){
        $categories = Category::select('id','category_name')->get();
        return view('dashboard.pages.cardholders.create',compact('categories'));
    }

    public function store(CardholderRequest $request){

        try {
            $cardholder = Cardholder::create(['cardholder_name'=>$request->cardholder_name,'status'=>$request->status]);

            $syncData=[];
            foreach ($request->input('categories',[]) as $categoryId=>$discount){
                if($discount !== '' && $discount !== null){
                    $syncData[$categoryId] = ['discount_percent' => $discount];
                }
            }

            // 3. Sync pivot table

            $cardholder->categories()->sync($syncData);

            session()->flash('success', 'cardholder created successfully!');
            return redirect()->route('dashboard.get-all-cardholders');
        }
        catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();

        }

    }

    public function edit($id){
        $cardholder = Cardholder::with('categories')->findOrFail($id);
        $categories = Category::select('id','category_name')->get();
        return view('dashboard.pages.cardholders.edit',compact('cardholder','categories'));
    }

    public function update(CardholderRequest $request,$id){

        $cardholder = Cardholder::findOrFail($id);
        try {
            $cardholder->update(['cardholder_name'=>$request->cardholder_name,'status'=>$request->status]);

            $syncData=[];
            foreach ($request->input('categories',[]) as $categoryId=>$discount){
                if($discount !== '' && $discount !== null){
                    $syncData[$categoryId] = ['discount_percent' => $discount];
                }
            }
            $cardholder->categories()->sync($syncData);
            session()->flash('success', 'cardholder updated successfully!');
            return redirect()->route('dashboard.get-all-cardholders');
        }
        catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }

    }

    public function destroy($id){
        $cardholder = Cardholder::findOrFail($id);
        try {
            $cardholder->categories()->detach();
            $cardholder->delete();
            session()->flash('success', 'cardholder deleted successfully!');
            return redirect()->route('dashboard.get-all-cardholders');
        }
        catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->route('dashboard.get-all-cardholders');
        }

    }
}
