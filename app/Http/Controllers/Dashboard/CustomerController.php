<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\Brand;
use App\Cardholder;
use App\Category;
use App\Customer;
use App\Doctor;
use App\glassLense;
use App\glassModel;
use App\Http\Controllers\Controller;
use App\InsuranceCompany;
use App\Invoice;
use App\Lenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission.spatie:view-customers')->only(['index', 'showCustomer']);
        $this->middleware('permission.spatie:create-customers')->only(['getAddCustomer', 'postAddCustomer']);
        $this->middleware('permission.spatie:edit-customers')->only(['getUpdateCustomer']);
        $this->middleware('permission.spatie:delete-customers')->only(['deleteCustomer']);
    }
    public function index(Request $request)
    {
        if($request->search) {
            $customers = Customer::when($request->search, function ($query) use ($request) {
                return $query->where('english_name', 'like', '%' . $request->search . '%')
                    ->orWhere('local_name', 'like', '%' . $request->search . '%')
                    ->orWhere('national_id', 'like', '%' . $request->search . '%')
                    ->orWhere('mobile_number', 'like', '%' . $request->search . '%')
                    ->orWhere('customer_id', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })->with(['eyetests', 'customerinvoices'])->latest()->paginate(10);
        } else {
        $customers = null;
    }
        return view('dashboard.pages.customers.all-customers', compact('customers'));
    }

    public function getAddCustomer(Request $request)
    {
        $customer_count = Customer::count();
        $customerID = mt_rand(10000000, 99999999);
        return view('dashboard.pages.customers.create_customer', compact('customerID'));
    }

    public function postAddCustomer(Request $request)
    {
        $rules = [
            'english_name' => 'required',
            'birth_date' => 'required',
            'mobile_number' => 'required',
            'email' => 'required',

        ];

        $messages = [
            'english_name.required' => 'Please enter the last name',
            'birth_date.required' => 'Please enter the birth date',
            'mobile_number.required' => 'Please enter the mobile number',
            'email.required' => 'Please enter the email',
        ];


        $request->validate($rules, $messages);

     $customer = new Customer();
        $customer->customer_id = $request->customer_id;
        $customer->title = $request->title;
        $customer->english_name = $request->english_name;
        $customer->local_name = $request->local_name;
        $customer->gender = $request->gender;
        $customer->birth_date = $request->birth_date;
        $customer->prefered_language = $request->prefered_language;
        $customer->nationality = $request->nationality;
        $customer->national_id = $request->national_id;
        $customer->age = $request->age;
        $customer->country = $request->country;
        $customer->city = $request->city;
        $customer->address = $request->address;
        $customer->dial_code = $request->dial_code;
        $customer->email = $request->email;
        $customer->receive_notifications = $request->receive_nots;
        $customer->office_number = $request->office_number;
        $customer->notes = $request->notes;
        $customer->mobile_number = $request->mobile_number;
        if ($request->points) {
            $customer->moftah_club = 1;
        }
        $customer->save();

        session()->flash('success', 'Customer Added Successfully!');
        switch ($request->input('action')) {
                case 'save':
                    return redirect()->route('dashboard.get-all-customers');
                    break;

                case 'saveAndCreateInvoice':
                   return redirect()->route('dashboard.show-customer-invoice',['id' => $customer->id]);
                    break;

                case 'saveAndCreateEyeTest':
                    return redirect()->route('dashboard.add-eye-test',['id' => $customer->id]);
                    break;
            }
    }

    /*public function getUpdateCustomer(Request $request, $id)
    {
        $customer = Customer::find($id);
        return view('dashboard.pages.customers.update_customer', compact('customer'));
    }*/
    public function getUpdateCustomer(Request $request, $id)
    {
        $customer = Customer::find($id);
        return view('dashboard.pages.customers.update_customer', compact(
            'customer'
        ));
    }

    public function postUpdateCustomer(Request $request, $customer_id)
    {
        $rules = [
            'english_name' => 'required',
            'national_id' => 'required',
            'mobile_number' => 'required',
            'email' => 'required',
        ];

        $messages = [
            'english_name.required' => 'Please enter the last name',
            'birth_date.required' => 'Please enter the birth date',
            'mobile_number.required' => 'Please enter the mobile number',
            'email.required' => 'Please enter the email',
        ];

        $request->validate($rules, $messages);

        try {
            $customer = Customer::where('customer_id', $customer_id)->first();
            $data = $request->except('_token', '_method');
            $customer->update($data);

            session()->flash('success', 'Customer Updated Successfully!');

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return redirect()->route('dashboard.get-all-customers', [
            'search' => $customer_id
        ]);

        //return redirect()->route('dashboard.get-all-customers');
    }

    public function showCustomer($id)
    {
        $customer = Customer::find($id);
        $lenses = Lenses::where('customer_id', $customer->customer_id)->get();
        $glass_lenses = glassLense::all();

        $customerInvoices = Invoice::where('customer_id',$customer->customer_id)->orderBy('created_at')->get()->groupBy(function($item) {
            return $item->created_at->format('y-m-d');
        });

        return view('dashboard.pages.customers.show_customer')->with(compact('customerInvoices','customer','lenses','glass_lenses'));
    }

    public function deleteCustomer($id)
    {
        $customer = Customer::findOrFail($id);

        try {
            $customer->delete();
            session()->flash('success', 'Customer has been deleted successfully!');
            return redirect()->route('dashboard.get-all-customers');
        }
        catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();

        }
    }

   /* public function showCustomerInvoice(Request $request, $id)
    {
        $customer = Customer::find($id);

        $customers = Customer::select(['customer_id', 'english_name', 'local_name'])->get();

        $doctors = Doctor::select(['id', 'name', 'code'])->get();

        $categories = Category::all();
        $brands = Brand::all();
        $models = glassModel::all();

        $insurances = InsuranceCompany::where('status',1)->with('categories')->get();

        $cardholders = Cardholder::where('status',1)->with('categories')->get();
       // return $_SESSION['products'];

        return view('dashboard.pages.customers.invoice.show_customer_invoice')->with(compact('id', 'customer', 'customers', 'categories', 'brands', 'models', 'doctors','cardholders','insurances'));
    }*/

    public function showCustomerInvoice(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        // Get user's accessible branches
        $user = auth()->user();

        /*if ($user->hasRole('admin')) {
            // Admin sees all active branches
            $branches = Branch::where('is_active', true)->get();
            $defaultBranch = $user->branch_id
                ? Branch::find($user->branch_id)
                : Branch::where('is_main', true)->first();
        } else {
            // Regular user sees only their branch
            if (!$user->branch_id) {
                return redirect()->back()->with('error', 'You are not assigned to any branch. Please contact administrator.');
            }

            $branches = Branch::where('id', $user->branch_id)
                ->where('is_active', true)
                ->get();
            $defaultBranch = $user->branch;
        }*/

        $branches = Branch::where('is_active', true)->get();
        $defaultBranch = $user->branch_id
            ? Branch::find($user->branch_id)
            : Branch::where('is_main', true)->first();

        // Get other data
        $customers = Customer::select(['customer_id', 'english_name', 'local_name'])->get();
        $doctors = Doctor::select(['id', 'name', 'code'])->get();
        $categories = Category::all();
        $brands = Brand::all();
        $models = glassModel::all();
        $insurances = InsuranceCompany::where('status', 1)->with('categories')->get();
        $cardholders = Cardholder::where('status', 1)->with('categories')->get();

        return view('dashboard.pages.customers.invoice.show_customer_invoice')->with(compact(
            'id',
            'customer',
            'customers',
            'categories',
            'brands',
            'models',
            'doctors',
            'cardholders',
            'insurances',
            'branches',           // ✅ جديد
            'defaultBranch',      // ✅ جديد
            'user'                // ✅ جديد
        ));
    }

    public function getCustomerDetails(Request $request)
    {
        $customer_id = $request->customer_id;
        $customer = Customer::where('customer_id', $customer_id)->first();

        return response()->json(['customer' => $customer]);
    }

    public function storeManyDataInSession(Request $request)
    {
        $data = $request->all();
        return $_SESSION['products'][] = $data;
    }

    public function storeDataInSession(Request $request)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!$request->has('Product')) {
            return response()->json([
                'error' => 'Product data not found'
            ], 400);
        }

        $product = $request->Product;

        if (!isset($_SESSION['products']) || !is_array($_SESSION['products'])) {
            $_SESSION['products'] = [];
        }

        if (!isset($product['product_id'])) {
            return response()->json([
                'error' => 'product_id missing'
            ], 400);
        }

        $found = false;

        foreach ($_SESSION['products'] as $key => $item) {

            if (isset($item['Product'])) {
                $item = $item['Product'];
            }

            if (isset($item['product_id']) && $item['product_id'] == $product['product_id']) {

                /*$_SESSION['products'][$key]['Product']['product_quantity'] =
                    isset($_SESSION['products'][$key]['Product'])
                        ? $product['product_quantity']
                        : $product['product_quantity'];*/

                $_SESSION['products'][$key]['Product']['product_quantity'] = $product['product_quantity'];
                $_SESSION['products'][$key]['Product']['price'] = $product['price'];
                $_SESSION['products'][$key]['Product']['net'] = $product['net'];
                $_SESSION['products'][$key]['Product']['tax'] = $product['tax'];
                $_SESSION['products'][$key]['Product']['total'] = $product['total'];

                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['products'][] = [
                'Product' => $product
            ];
        }

        return response()->json($_SESSION['products']);
    }


    public function deleteDataFromSession(Request $request)
    {
        $id = $request->product_id;

        if (isset($_SESSION['products'])) {
            foreach ($_SESSION['products'] as $index => $product) {
                if (isset($product['Product']['product_id']) && $product['Product']['product_id'] == $id) {
                    unset($_SESSION['products'][$index]);
                }

            }
            $_SESSION['products'] = array_values($_SESSION['products']);
        }

        return response()->json(['success' => true]);
    }


    public function storeTotalInSession(Request $request)
    {
        $data = $request->all();
        $_SESSION['totals'] = $data;

    }

    public function cusromerHistory(Request $request) {
        $cusId = $request->customer_id;
        $history = Invoice::where('customer_id', $cusId)->orderBy('created_at', 'DESC')->get();
        return response()->json($history);
    }


    public function discountGetType(Request $request)
    {
        $type = $request->type;

        if (!in_array($type, ['insurance', 'cardholder'])) {
            return response()->json(['message' => 'Invalid type'], 422);
        }

        if ($type === 'insurance') {
            $items = InsuranceCompany::where('status', 1)
                ->with(['categories'])
                ->orderBy('company_name')
                ->get(['id', 'company_name as name']);
        } else {
            $items = Cardholder::where('status', 1)
                ->with(['categories'])
                ->orderBy('cardholder_name')
                ->get(['id', 'cardholder_name as name']);
        }

        return response()->json([
            'data' => $items
        ]);
    }

    public function discountGetSingleType(Request $request)
    {
        $payerType = $request->input('payer_type');
        $payerId = $request->input('payer_id');

        if ($payerType === 'insurance') {
            $items = InsuranceCompany::where('status', 1)
                ->with(['categories'])
                ->select('*', 'company_name as name')
                ->find($payerId);
        } else {
            $items = Cardholder::where('status', 1)
                ->with(['categories'])
                ->select('*', 'cardholder_name as name')
                ->find($payerId);
        }

        if ($items) {
            return response()->json(['success' => true, 'data' => $items]);
        } else {
            return response()->json(['success' => false, 'message' => 'Data not found']);
        }

    }


}
