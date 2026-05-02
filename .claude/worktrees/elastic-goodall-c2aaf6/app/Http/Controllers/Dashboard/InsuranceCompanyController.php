<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Doctor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\InsuranceCompanyRequest;
use App\InsuranceCompany;
use Illuminate\Http\Request;

class InsuranceCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.spatie:view-insurance-companies')
            ->only([
                'index'
            ]);

        $this->middleware('permission.spatie:create-insurance-companies')
            ->only([
                'create',
                'store'
            ]);

        $this->middleware('permission.spatie:edit-insurance-companies')
            ->only([
                'edit',
                'update'
            ]);

        $this->middleware('permission.spatie:delete-insurance-companies')
            ->only([
                'destroy'
            ]);
    }

    public function index(Request $request)
    {
         $insuranceCompanies = InsuranceCompany::when($request->search, function ($query) use ($request) {
            return $query->where('company_name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10)->appends($request->except('page'));;

        return view('dashboard.pages.insuranceCompany.index')->with(compact('insuranceCompanies'));
    }
    public function create(){
        $categories = Category::select('id','category_name')->get();
        return view('dashboard.pages.insuranceCompany.create',compact('categories'));
    }

    public function store(InsuranceCompanyRequest $request){
        try {
            $insuranceCompany = InsuranceCompany::create(['company_name'=>$request->company_name,'status'=>$request->status]);

            $syncData=[];
            foreach ($request->input('categories',[]) as $categoryId=>$discount){
                if($discount !== '' && $discount !== null){
                    $syncData[$categoryId] = ['discount_percent' => $discount];
                }
            }

            // 3. Sync pivot table

            $insuranceCompany->categories()->sync($syncData);

            session()->flash('success', 'insurance Company has been Created Successfully!');
            return redirect()->route('dashboard.get-all-insurance-companies');
        }
        catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();

        }

    }

    public function edit($id){
        $insuranceCompany = InsuranceCompany::with('categories')->findOrFail($id);
        $categories = Category::select('id','category_name')->get();
        return view('dashboard.pages.insuranceCompany.edit',compact('insuranceCompany','categories'));
    }

    public function update(InsuranceCompanyRequest $request,$id){

        $insuranceCompany = InsuranceCompany::findOrFail($id);
        try {
            $insuranceCompany->update(['company_name'=>$request->company_name,'status'=>$request->status]);

            $syncData=[];
            foreach ($request->input('categories',[]) as $categoryId=>$discount){
                if($discount !== '' && $discount !== null){
                    $syncData[$categoryId] = ['discount_percent' => $discount];
                }
            }
            $insuranceCompany->categories()->sync($syncData);
            session()->flash('success', 'insurance Company has been Updated Successfully!');
            return redirect()->route('dashboard.get-all-insurance-companies');
        }
        catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }

    }

    public function destroy($id){
        $insuranceCompany = InsuranceCompany::findOrFail($id);
        try {
            $insuranceCompany->categories()->detach();
            $insuranceCompany->delete();
            session()->flash('success', 'insurance Company has been Deleted Successfully!');
            return redirect()->route('dashboard.get-all-insurance-companies');
        }
        catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->route('dashboard.get-all-insurance-companies');
        }

    }

}
