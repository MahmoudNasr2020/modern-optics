<?php

namespace App\Http\Controllers\Dashboard;

use App\Customer;
use App\Doctor;
use App\Facades\File;
use App\glassLense;
use App\Http\Controllers\Controller;
use App\Lenses;
use Illuminate\Http\Request;

class EyeTestController extends Controller
{

    public function __construct()
    {
        // ========================================
        // EYE TESTS PERMISSIONS
        // ========================================
        $this->middleware('permission.spatie:view-eye-tests')
            ->only([
                'getLensesView',
                'showEyTest',
                'cusromerEyeTest',
                'printEyeTest'
            ]);

        $this->middleware('permission.spatie:create-eye-tests')
            ->only([
                'addNewEyTest',
                'StoreNewEyTest'
            ]);

        $this->middleware('permission.spatie:edit-eye-tests')
            ->only([
                // 'editEyeTest',
                // 'updateEyeTest'
            ]);

        $this->middleware('permission.spatie:delete-eye-tests')
            ->only([
                'deleteEyeTest'
            ]);
    }

    public function getLensesView(Request $request, $id)
    {
        $customer = Customer::find($id);
        $lenses = Lenses::where('customer_id', $customer->customer_id)->get();
        $glass_lenses = glassLense::all();

        return view('dashboard.pages.customers.eye-tests.allLenses', with(compact('customer', 'lenses', 'glass_lenses')));
    }

    public function addNewEyTest(Request $request, $id)
    {
        $customer = Customer::find($id);
        $doctors = Doctor::all();
        $customers = Customer::all();
        $visits = Lenses::where('customer_id', $customer->customer_id)->get()->count();
        return view('dashboard.pages.customers.eye-tests.new_eye_test', compact(['customer', 'doctors', 'customers', 'visits']));
    }

    /*public function StoreNewEyTest(Request $request,$id)
    {

        $customer_id = Customer::where('customer_id', $id)->first()->id;

        $rules = [
            'PDLeft' => 'required',
            'PDRight' => 'required',
            'attachment' => 'nullable|mimes:pdf,jpg,jpeg,png,svg|max:4048',
        ];
        $rules2 = [];
        if($request->CylRight != 0.00 || $request->CylLeft != 0.00 ) {
            $rules2 = ['AxisRight' => 'Required', 'AxisLeft' => 'required'];
        }
        $rules = array_merge($rules, $rules2);

        $messages = [
            'PDLeft.required' => 'Please Enter PD Left',
            'PDRight.required' => 'Please Enter PD Right',
        ];

        $request->validate($rules, $messages);

        try {

            $rightDiagnosis = json_encode($request->rightDiagnosis, true);
            $leftDiagnosis = json_encode($request->leftDiagnosis, true);
            $data['customer_id'] = $id;
            $data['doctor_id'] = $request->doctorId;
            $data['invoice_id'] = 1;
            $data['visit_date'] = $request->visitDate;
            $data['sph_right_sign'] = $request->SphRightSign;
            $data['sph_right_value'] = $request->SphRight;
            $data['cyl_right_sign'] = $request->CylRightSign;
            $data['cyl_right_value'] = $request->CylRight;
            $data['axis_right'] = $request->AxisRight;
            $data['addition_right'] = $request->AdditionRight;
            $data['pd_right'] = $request->PDRight;
            $data['sph_left_sign'] = $request->SphLeftSign;
            $data['sph_left_value'] = $request->SphLeft;
            $data['cyl_left_sign'] = $request->CylLeftSign;
            $data['cyl_left_value'] = $request->CylLeft;
            $data['axis_left'] = $request->AxisLeft;
            $data['addition_left'] = $request->AdditionLeft;
            $data['pd_left'] = $request->PDLeft;
            $data['right_diagnosis'] = $rightDiagnosis;
            $data['left_diagnosis'] = $leftDiagnosis;
            $data['glasses'] = $request->Glasses_Type;
            $data['attachment'] = File::upload($request->file('attachment'),'eyeTests');

            Lenses::create($data);

            session()->flash('success', 'Test has been created Successfully!');

        }
        catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return redirect()->route('dashboard.get-all-customers', ['search' => $id]);


    }*/

    public function StoreNewEyTest(Request $request,$id)
    {
        $customerObj = Customer::where('customer_id', $id)->first();
        if (!$customerObj) { abort(404, 'Customer not found.'); }
        $customer_id = $customerObj->id;

        $rules = [
            'PDLeft' => 'required',
            'PDRight' => 'required',
            'attachment' => 'nullable|mimes:pdf,jpg,jpeg,png,svg|max:4048',
        ];
        $rules2 = [];
        if($request->CylRight != 0.00 || $request->CylLeft != 0.00 ) {
            $rules2 = ['AxisRight' => 'Required', 'AxisLeft' => 'required'];
        }
        $rules = array_merge($rules, $rules2);

        $messages = [
            'PDLeft.required' => 'Please Enter PD Left',
            'PDRight.required' => 'Please Enter PD Right',
        ];

        $request->validate($rules, $messages);

        try {

            if($request->hasFile('attachment'))
            {
                $data['attachment'] = File::upload($request->file('attachment'),'eyeTests');
            }

            $rightDiagnosis = json_encode($request->rightDiagnosis, true);
            $leftDiagnosis = json_encode($request->leftDiagnosis, true);
            $data['customer_id'] = $id;
            $data['doctor_id'] = $request->doctorId;
            $data['invoice_id'] = 1;
            $data['visit_date'] = $request->visitDate;
            $data['sph_right_sign'] = $request->SphRightSign;
            $data['sph_right_value'] = $request->SphRight;
            $data['cyl_right_sign'] = $request->CylRightSign;
            $data['cyl_right_value'] = $request->CylRight;
            $data['axis_right'] = $request->AxisRight;
            $data['addition_right'] = $request->AdditionRight;
            $data['pd_right'] = $request->PDRight;
            $data['sph_left_sign'] = $request->SphLeftSign;
            $data['sph_left_value'] = $request->SphLeft;
            $data['cyl_left_sign'] = $request->CylLeftSign;
            $data['cyl_left_value'] = $request->CylLeft;
            $data['axis_left'] = $request->AxisLeft;
            $data['addition_left'] = $request->AdditionLeft;
            $data['pd_left'] = $request->PDLeft;
            $data['right_diagnosis'] = $rightDiagnosis;
            $data['left_diagnosis'] = $leftDiagnosis;
            $data['glasses'] = $request->Glasses_Type;
            // $data['attachment'] = File::upload($request->file('attachment'),'eyeTests');

            Lenses::create($data);

            session()->flash('success', 'Test has been created Successfully!');

        }
        catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        //return redirect()->route('dashboard.get-all-customers', ['search' => $id]);

        $draft = session('invoice_draft');

        if ($draft && isset($draft['customer_id'])) {
            return redirect()->route('dashboard.invoice.create', $draft['customer_id']);
        }

        return redirect()->route('dashboard.get-all-customers', ['search' => $id]);

    }

    public function showEyTest($id) {
        $eyeTest = Lenses::find($id);
        $customer = Customer::where('customer_id', $eyeTest->customer_id)->first();
        $doctor = Doctor::where('code', $eyeTest->doctor_id)->first();
        return view('dashboard.pages.customers.eye-tests.show_eye_test', with(compact('eyeTest', 'customer', 'doctor')) );
    }
    public function cusromerEyeTest(Request $request) {
        $cusId = $request->customer_id;
        $customer = Customer::where('customer_id', $cusId)->first();
        $eyeTests = Lenses::where('customer_id', $cusId)->get();
        return response()->json(['responsea' => $eyeTests, 'customer_id' => $customer->id]);
    }
    public function printEyeTest($id)
    {
        $eyeTest = Lenses::with('customer:id,customer_id,english_name')->findOrFail($id);

        return view('dashboard.pages.customers.eye-tests.print_eye_test')->with(compact('eyeTest'));
    }

    public function deleteEyeTest($id)
    {
        $eyeTest = Lenses::findOrfail($id);
        try {
            File::delete($eyeTest->attachment);
            $eyeTest->delete();
            session()->flash('success', 'Eye Test has been Deleted Successfully!');
            return redirect()->back();
        }
        catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();

        }

    }
}
