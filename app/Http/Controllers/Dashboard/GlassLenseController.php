<?php


namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\glassLense;
use App\InvoiceItems;
use App\LensBrand;
use App\LensStockEntry;
use App\LensPurchaseOrder;
use App\Invoice;
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

        /*
         * LEFT JOIN with a stock subquery so every lens carries its own
         * stock totals (R / L / unknown-side) in a single DB round-trip.
         * Sorting: lenses that have any stock come first (DESC), then alpha.
         */
        $stockSub = \DB::raw("(
            SELECT
                glass_lense_id,
                GREATEST(0, SUM(CASE WHEN direction='in' THEN quantity ELSE -quantity END))               AS net_stock,
                GREATEST(0, SUM(CASE WHEN side='R' AND direction='in'  THEN quantity
                                     WHEN side='R' AND direction='out' THEN -quantity ELSE 0 END))        AS stock_r,
                GREATEST(0, SUM(CASE WHEN side='L' AND direction='in'  THEN quantity
                                     WHEN side='L' AND direction='out' THEN -quantity ELSE 0 END))        AS stock_l,
                GREATEST(0, SUM(CASE WHEN (side IS NULL OR side='') AND direction='in'  THEN quantity
                                     WHEN (side IS NULL OR side='') AND direction='out' THEN -quantity
                                     ELSE 0 END))                                                         AS stock_unk
            FROM lens_stock_entries
            GROUP BY glass_lense_id
        ) AS lse_summary");

        $lenses = glassLense::leftJoin($stockSub, 'lse_summary.glass_lense_id', '=', 'glass_lenses.id')
            ->select(
                'glass_lenses.*',
                \DB::raw('COALESCE(lse_summary.net_stock, 0) AS net_stock'),
                \DB::raw('COALESCE(lse_summary.stock_r,   0) AS stock_r'),
                \DB::raw('COALESCE(lse_summary.stock_l,   0) AS stock_l'),
                \DB::raw('COALESCE(lse_summary.stock_unk, 0) AS stock_unk')
            )
            ->when($request->search, function ($query) use ($request) {
                $s = $request->search;
                return $query->where(function ($q) use ($s) {
                    $q->where('glass_lenses.product_id',   'like', "%{$s}%")
                      ->orWhere('glass_lenses.description','like', "%{$s}%")
                      ->orWhere('glass_lenses.price',      'like', "%{$s}%");
                });
            })
            ->orderByDesc('net_stock')           // lenses with stock first
            ->orderBy('glass_lenses.product_id') // then alphabetical
            ->paginate(15);

        // Keep variable names compatible with the view (dummy collections not needed now –
        // stock is on the model – but keep the compact keys so the view still works).
        $stockMap  = collect(); // not used in view anymore (values are on $lense directly)
        $stockMapR = collect();
        $stockMapL = collect();

        return view('dashboard.pages.glasseslenses.index',
            compact('lenses', 'lense_count', 'stockMap', 'stockMapR', 'stockMapL'));
    }


    public function showLens($id)
    {
        $user = auth()->user();
        $lens = glassLense::findOrFail($id);

        // ── Branches the current user can access ──────────────────────────
        $accessibleBranches = $user->getAccessibleBranches();
        $branchIds          = $accessibleBranches->pluck('id')->toArray();

        // Branch filter (admins can pick a branch; regular users are fixed to theirs)
        $selectedBranchId = null;
        if ($user->canSeeAllBranches()) {
            $selectedBranchId = request('branch_id') ?: null; // null = all branches
        } else {
            $selectedBranchId = $user->branch_id;
        }

        // ── Build base entries query ────────────────────────────────────────
        $entriesQuery = LensStockEntry::where('glass_lense_id', $lens->id)
            ->whereIn('branch_id', $branchIds)
            ->with(['branch', 'user']);

        if ($selectedBranchId) {
            $entriesQuery->where('branch_id', $selectedBranchId);
        }

        // Timeline: all entries newest-first
        $timeline = $entriesQuery->latest()->get();

        // ── Bulk-load source references (filter() for Laravel 5.8 compat) ──
        $poIds  = $timeline->filter(function($e){ return $e->source_type === 'purchase_order'; })
                           ->pluck('source_id')->unique();
        $poMap  = $poIds->isNotEmpty()
                    ? LensPurchaseOrder::whereIn('id', $poIds->toArray())->pluck('po_number', 'id')
                    : collect();

        $invIds = $timeline->filter(function($e){ return $e->source_type === 'invoice_delivery'; })
                           ->pluck('source_id')->unique();
        $invMap = $invIds->isNotEmpty()
                    ? Invoice::whereIn('id', $invIds->toArray())->pluck('invoice_code', 'id')
                    : collect();

        // ── Per-branch stock summary ───────────────────────────────────────
        $branchSummaries = [];
        foreach ($accessibleBranches as $branch) {
            if ($selectedBranchId && $branch->id != $selectedBranchId) {
                continue;
            }

            $rows = LensStockEntry::where('glass_lense_id', $lens->id)
                ->where('branch_id', $branch->id)
                ->selectRaw("side, direction, SUM(quantity) as total")
                ->groupBy('side', 'direction')
                ->get();

            // Nothing for this branch at all → skip
            if ($rows->isEmpty()) {
                continue;
            }

            // Laravel 5.8: whereNull() does not exist on Collection — use filter()
            $inR    = $rows->filter(function($r){ return $r->side === 'R' && $r->direction === 'in';  })->sum('total');
            $outR   = $rows->filter(function($r){ return $r->side === 'R' && $r->direction === 'out'; })->sum('total');
            $inL    = $rows->filter(function($r){ return $r->side === 'L' && $r->direction === 'in';  })->sum('total');
            $outL   = $rows->filter(function($r){ return $r->side === 'L' && $r->direction === 'out'; })->sum('total');
            $inUnk  = $rows->filter(function($r){ return is_null($r->side) && $r->direction === 'in';  })->sum('total');
            $outUnk = $rows->filter(function($r){ return is_null($r->side) && $r->direction === 'out'; })->sum('total');

            $totalIn  = $inR  + $inL  + $inUnk;
            $totalOut = $outR + $outL + $outUnk;

            $branchSummaries[] = [
                'branch'        => $branch,
                'stock_R'       => max(0, $inR  - $outR),
                'stock_L'       => max(0, $inL  - $outL),
                'stock_unk'     => max(0, $inUnk - $outUnk),
                'total_in'      => $totalIn,
                'total_out'     => $totalOut,
                'current_stock' => max(0, $totalIn - $totalOut),
            ];
        }

        // ── Overall totals ─────────────────────────────────────────────────
        $overallIn    = array_sum(array_column($branchSummaries, 'total_in'));
        $overallOut   = array_sum(array_column($branchSummaries, 'total_out'));
        $overallStock = array_sum(array_column($branchSummaries, 'current_stock'));

        // ── Sales info (invoice items) ─────────────────────────────────────
        $totalSold  = InvoiceItems::where('product_id', $lens->product_id)->sum('quantity');
        $salesCount = InvoiceItems::where('product_id', $lens->product_id)->count();

        return view('dashboard.pages.glasseslenses.show_lense', compact(
            'lens',
            'timeline',
            'poMap',
            'invMap',
            'branchSummaries',
            'accessibleBranches',
            'selectedBranchId',
            'overallIn',
            'overallOut',
            'overallStock',
            'totalSold',
            'salesCount'
        ));
    }

    /*public function getAddLense() {
        $lense_count = glassLense::count();
        // $lenseID = mt_rand(10000000, 99999999) . $lense_count;
        $lenseID = glassLense::select('product_id')->orderBy('product_id','desc')->first();

        return view('dashboard.pages.glasseslenses.add_lense', compact(['lenseID', 'lense_count']));
    }*/

    public function getAddLense() {
        $lense_count = glassLense::count();
        $lenseID     = glassLense::select('product_id')->orderBy('product_id','desc')->first();
        $brands      = LensBrand::active()->orderBy('name')->get();
        $branches    = auth()->user()->getAccessibleBranches();
        return view('dashboard.pages.glasseslenses.add_lense', compact('lenseID', 'lense_count', 'brands', 'branches'));
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

        /*$glass_lense->brand = $request->brand;
        $glass_lense->lense_production = $request->production;

        $glass_lense->save();*/

        $brand = LensBrand::find($request->lens_brand_id);
        $glass_lense->lens_brand_id    = $request->lens_brand_id;
        $glass_lense->brand            = $brand ? $brand->name : '';
        $glass_lense->lense_production = $request->production;
        $glass_lense->save();

        // ===== OPTIONAL: Add to branch stock immediately =====
        if ($request->add_to_stock == '1' && auth()->user()->can('add-stock')) {
            $branchId = (int) $request->stock_branch_id;

            if ($branchId && auth()->user()->canAccessBranch($branchId)) {
                $alreadyExists = \App\BranchStock::where('branch_id', $branchId)
                    ->where('stockable_type', 'App\\glassLense')
                    ->where('stockable_id', $glass_lense->id)
                    ->exists();

                if (!$alreadyExists) {
                    $qty    = max(0, (int) $request->stock_quantity);
                    $minQty = max(0, (int) $request->stock_min_quantity);
                    $maxQty = max($minQty + 1, (int) $request->stock_max_quantity);

                    \App\BranchStock::create([
                        'branch_id'      => $branchId,
                        'product_id'     => null,
                        'stockable_type' => 'App\\glassLense',
                        'stockable_id'   => $glass_lense->id,
                        'quantity'       => $qty,
                        'min_quantity'   => $minQty,
                        'max_quantity'   => $maxQty,
                        'last_cost'      => $request->stock_cost ?: null,
                        'average_cost'   => $request->stock_cost ?: null,
                        'total_in'       => $qty,
                        'total_out'      => 0,
                    ]);

                    if ($qty > 0) {
                        StockMovement::createForLens(
                            $branchId,
                            $glass_lense->id,
                            'in',
                            $qty,
                            auth()->id(),
                            [
                                'cost'  => $request->stock_cost ?: null,
                                'notes' => $request->stock_notes ?: 'Added with lens creation',
                            ]
                        );
                    }
                }
            }
        }

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
    /*public function getEditLense($id) {
        $lense_count = glassLense::count();

        $glassLense = glassLense::where('id',$id)->first();

        return view('dashboard.pages.glasseslenses.edit_lense', compact(['lense_count','glassLense']));

    }*/
    public function getEditLense($id) {
        $lense_count = glassLense::count();
        $glassLense  = glassLense::where('id', $id)->firstOrFail();
        $brands      = LensBrand::active()->orderBy('name')->get(); // <-- add this
        return view('dashboard.pages.glasseslenses.edit_lense', compact('lense_count', 'glassLense', 'brands'));
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

        /*$glass_lense->brand = $request->brand;
        $glass_lense->lense_production = $request->production;*/
        $brand = LensBrand::find($request->lens_brand_id);
        $glass_lense->lens_brand_id    = $request->lens_brand_id;
        $glass_lense->brand            = $brand ? $brand->name : $request->brand; // keep string in sync
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
                return (optional($stock->branch)->name ?? 'Branch#' . $stock->branch_id) .
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
