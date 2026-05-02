<?php


namespace App\Http\Controllers\Dashboard;


use App\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission.spatie:view-doctors')->only(['index', 'showDoctor', 'getDoctorDetails']);
        $this->middleware('permission.spatie:create-doctors')->only(['getDoctor', 'addDoctor']);
        $this->middleware('permission.spatie:edit-doctors')->only(['getUpdateDoctor', 'postUpdateDoctor']);
        $this->middleware('permission.spatie:delete-doctors')->only(['deleteDoctor']);
    }


    public function index(Request $request)
    {
        $doctors = Doctor::latest()->paginate(10)->appends($request->all());
        if ($request->search) {
            $doctors = Doctor::when($request->search, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('code', 'like', '%' . $request->search . '%');
            })->latest()->paginate(5);

            return view('dashboard.pages.doctors.all-doctors')->with(compact('doctors'));
        }
        return view('dashboard.pages.doctors.all-doctors')->with(compact('doctors'));
    }

    public function getDoctor(Request $request)
    {
        $doctor_count = Doctor::count();
        $DoctorId = mt_rand(10000000, 99999999) . $doctor_count;

        return view('dashboard.pages.doctors.create_doctor')->with(compact('DoctorId'));
    }

    public function addDoctor(Request $request)
    {

        $rules = [
            'name' => 'required',
        ];

        $messages = [
            'name.required' => 'Please enter the Your name',
        ];


        $request->validate($rules, $messages);

        $data = $request->all();

        Doctor::create($data);

        session()->flash('success', 'Doctor Added Successfully!');
        return redirect()->route('dashboard.get-all-doctors');
    }

    public function showDoctor($id)
    {
        $doctor = Doctor::where('id', $id)->first();

        return view('dashboard.pages.doctors.show_doctor')->with(compact('doctor'));
    }


    public function getUpdateDoctor(Request $request, $id)
    {
        $doctor = Doctor::where('id', $id)->first();

        return view('dashboard.pages.doctors.update_doctor', compact('doctor'));
    }

    public function postUpdateDoctor(Request $request, $doctor_id)
    {
        $rules = [
            'name' => 'required',
        ];

        $messages = [
            'name.required' => 'Please enter the your name',
        ];

        $request->validate($rules, $messages);

        try {
            $doctor = Doctor::where('id', $doctor_id)->first();
            $data = $request->except('_token', '_method');
            $doctor->update($data);

            session()->flash('success', 'Doctor Updated Successfully!');

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return redirect()->route('dashboard.get-all-doctors');
    }


    public function getDoctorDetails(Request $request)
    {
        $doctor_id = $request->doctor_id;
        $doctor = Doctor::where('id', $doctor_id)->first();

        return response()->json(['doctor' => $doctor]);
    }


    public function setDoctorSession(Request $request)
    {
        $this->validate($request, [
            'doctor_id' => 'required|exists:doctors,code',
        ]);

        $doctor = Doctor::where('code', $request->doctor_id)->first();

        session([
            'doctor_id'   => $doctor->code,
            'doctor_name' => $doctor->name,
        ]);

        return response()->json([
            'status' => true
        ]);
    }

    public function deleteDoctor($id)
    {
        DB::beginTransaction();

        try {
            $doctor = Doctor::findOrFail($id);

            if ($doctor->invoices()->exists()) {
                session()->flash('error', 'Cannot delete this doctor because they are associated with existing invoices.');
                throw new \Exception(
                    'Cannot delete this doctor because they are associated with existing invoices.'
                );
            }

            $doctor->delete();

            DB::commit();

            session()->flash('success', 'Doctor Deleted Successfully!');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Failed to delete doctor', [
                'doctor_id' => $id,
                'error' => $e->getMessage()
            ]);


            return redirect()->back()->with('message', $e->getMessage());
        }
    }


}
