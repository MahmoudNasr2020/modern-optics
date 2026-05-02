<?php


namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\glassLense;
use App\InvoiceItems;
use App\StockMovement;
use DB;
use Illuminate\Http\Request;
use Validator;

class GlassLenseController extends Controller
{
    public function __construct()
    {
        // ========================================
        // VIEW LENSES
        // ========================================
        $this->middleware('permission.spatie:view-lenses')
            ->only([
                'index',
                'showLens',
                'filterLenses'
            ]);

        // ========================================
        // CREATE LENSES
        // ========================================
        $this->middleware('permission.spatie:create-lenses')
            ->only([
                'getAddLense',
                'addLense'
            ]);

        // ========================================
        // EDIT LENSES
        // ========================================
        $this->middleware('permission.spatie:edit-lenses')
            ->only([
                'getEditLense',
                'EditLense'
            ]);

        // ========================================
        // DELETE LENSES
        // ========================================
        $this->middleware('permission.spatie:delete-lenses')
            ->only([
                'deleteLense'
            ]);
    }

    public function index(Request $request) {
        $lense_count = glassLense::count();
        $lenses = glassLense::when($request->search, function ($query) use ($request) {
            return $query->where('product_id', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%')
                ->orWhere('price', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);
        return view('dashboard.pages.glasseslenses.index', compact('lenses', 'lense_count'));
    }


    public function showLens($id)
    {
        $user = auth()->user();
        $lens = glassLense::with(['branch', 'branchStocks.branch'])->findOrFail($id);

        // Check if user can access this lens (at least in one branch)
        /*$canAccess = false;
        foreach ($lens->branchStocks as $stock) {
            if ($user->canAccessBranch($stock->branch_id)) {
                $canAccess = true;
                break;
            }
        }

        if (!$canAccess && !$user->canSeeAllBranches()) {
            abort(403, 'You do not have permission to view this lens.');
        }*/

        // Get all branch stocks for this lens
        $branchStocks = $lens->branchStocks()
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
        $totalValue = $totalQuantity * $lens->retail_price;

        // Get sales count (from invoice items for lenses)
        $totalSold = InvoiceItems::where('product_id', $lens->product_id)->sum('quantity');
        $salesCount = InvoiceItems::where('product_id', $lens->product_id)->count();

        // Get recent movements (last 10)
        $recentMovements = StockMovement::where('stockable_type', 'App\\glassLense')
            ->where('stockable_id', $lens->id)
            ->with(['branch', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.pages.glasseslenses.show_lense', compact(
            'lens',
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

    public function getAddLense() {
        $lense_count = glassLense::count();
        // $lenseID = mt_rand(10000000, 99999999) . $lense_count;
        $lenseID = glassLense::select('product_id')->orderBy('product_id','desc')->first();

        return view('dashboard.pages.glasseslenses.add_lense', compact(['lenseID', 'lense_count']));

    }

    public function addLense(Request $request) {

        $glass_lense = new glassLense();

        $rules = [
            'product_id' => 'unique:glass_lenses,product_id',
            'price' => 'required',
            'description' => 'required',
            //'amount' => 'required',
        ];

        $messages = [
            'product_id.unique' => 'This Lense Id Added Before',
            'price.required' => 'Please enter  Lense Price',
            //'amount.required' => 'Please enter  Lense amount',
            'description.required' => 'Please enter lense description',
        ];


        $request->validate($rules, $messages);

        $glass_lense->index = $request->index;
        $glass_lense->frame_type = $request->frame_type;
        $glass_lense->lense_type = $request->lense_type;

        $glass_lense->life_style = $request->life_style;
        $glass_lense->customer_activity = $request->customer_activity;
        $glass_lense->lense_tech = $request->lense_tech;
        $glass_lense->price = $request->price;
        $glass_lense->retail_price = $request->retail_price;
        $glass_lense->product_id = $request->product_id;
        $glass_lense->description = $request->description;
        //$glass_lense->amount = $request->amount;

        $glass_lense->brand = $request->brand;
        $glass_lense->lense_production = $request->production;

        $glass_lense->save();

        session()->flash('success', 'Lense Added Successfully!');
        return redirect()->route('dashboard.get-glassess-lenses');
    }

    public function filterLenses(Request $request) {

        $frame_type = $request->frame_type;
        $lense_type = $request->lense_type;
        $life_style = $request->life_style;
        $customer_activity = $request->customer_activity;
        $lense_tech = $request->lense_tech;
        $glasses_brand = $request->glasses_brand;
        $glasses_production = $request->glasses_production;
        $index = $request->index;

        $query = 'SELECT * From glass_lenses WHERE('
        . ( $frame_type ? 'frame_type = :frame_type' : '' )
        . ( $frame_type ? ' AND ' : '' )
        . ( $index ? '`index` = :index' : '')
        . ( $index ? ' AND ' : '')
        . ( $lense_type ? 'lense_type = :lense_type' : '')
        . ( $lense_type ? ' AND ' : '')
        . ( $life_style ? 'life_style = :life_style' : '')
        . ( $life_style ? ' AND ' : '')
        . ( $lense_tech ? 'lense_tech = :lense_tech' : '')
        . ( $lense_tech ? ' AND ' : '')
        . ( $customer_activity ? 'customer_activity = :customer_activity' : '')
        . ( $customer_activity ? ' AND ' : '')
        . ( $glasses_brand ? 'brand = :glasses_brand' : '')
        . ( $glasses_brand ? ' AND ' : '')
        . ( $glasses_production ? 'lense_production = :glasses_production' : '') . ')';

        if(str_ends_with($query, ' AND )')) {
            $replaced_query = str_replace(' AND )', ')', $query);
        } else {
            $replaced_query = $query;
        }

        $parmetersArray = Array(
            (!$frame_type) ? NULL : 'frame_type' => $frame_type,
            (!$index) ? NULL : 'index' => $index,
            (!$lense_type) ? NULL : 'lense_type' => $lense_type,
            (!$life_style) ? NULL : 'life_style' => $life_style,
            (!$customer_activity) ? NULL : 'customer_activity' => $customer_activity,
            (!$lense_tech) ? NULL : 'lense_tech' => $lense_tech,
            (!$glasses_brand) ? NULL : 'glasses_brand' => $glasses_brand,
            (!$glasses_production) ? NULL : 'glasses_production' => $glasses_production,
        );

        $this->removeEmptyValues($parmetersArray);
        // dd($parmetersArray);

        $lenses = DB::select($replaced_query, $parmetersArray);
        return response()->json($lenses);
    }

    //get edit glass lenses view
    public function getEditLense($id) {
        $lense_count = glassLense::count();

        $glassLense = glassLense::where('id',$id)->first();

        return view('dashboard.pages.glasseslenses.edit_lense', compact(['lense_count','glassLense']));

    }

    public function EditLense(Request $request,$id) {

        $glass_lense = glassLense::where('id', $id)->first();

        $rules = [
            'price' => 'required',
            'description' => 'required',
            //'amount' => 'required',
        ];

        $messages = [
            'price.required' => 'Please enter  Lense Price',
            'amount.required' => 'Please enter  Lense amount',
            'description.required' => 'Please enter lense description',
        ];


        $request->validate($rules, $messages);

        $glass_lense->index = $request->index;
        $glass_lense->frame_type = $request->frame_type;
        $glass_lense->lense_type = $request->lense_type;

        $glass_lense->life_style = $request->life_style;
        $glass_lense->customer_activity = $request->customer_activity;
        $glass_lense->lense_tech = $request->lense_tech;

        $glass_lense->price = $request->price;
        $glass_lense->retail_price = $request->retail_price;
        $glass_lense->product_id = $request->product_id;
        $glass_lense->description = $request->description;
       // $glass_lense->amount = $request->amount;

        $glass_lense->brand = $request->brand;
        $glass_lense->lense_production = $request->production;


        $glass_lense->save();

        session()->flash('success', 'Lense Updated Successfully!');
        return redirect()->route('dashboard.get-glassess-lenses');
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
     }

   /* public function deletelense($id) {
        $lense = glassLense::find($id);
        if($lense) {
            $lense->delete();
            session()->flash('success', 'Lense Deleted Successfully!');
            return redirect()->back();
        }
    }*/

    public function deleteLense($id)
    {
        $lense = glassLense::with('branchStocks.branch')->find($id);

        if (!$lense) {
            session()->flash('error', 'Lense not found.');
            return redirect()->back();
        }

        $branchesWithStock = $lense->branchStocks()
            ->where(function ($q) {
                $q->where('quantity', '>', 0)
                    ->orWhere('reserved_quantity', '>', 0);
            })
            ->with('branch')
            ->get();

        if ($branchesWithStock->count() > 0) {

            $branchNames = $branchesWithStock->map(function ($stock) {
                return $stock->branch->name .
                    " (Qty: {$stock->quantity}, Reserved: {$stock->reserved_quantity})";
            })->implode(' , ');

            session()->flash(
                'error',
                "Cannot delete lense. Please zero stock in all branches first. Stock available: " . $branchNames
            );

            return redirect()->back();
        }

        $lense->delete();

        session()->flash('success', 'Lense deleted successfully!');
        return redirect()->back();
    }


}
