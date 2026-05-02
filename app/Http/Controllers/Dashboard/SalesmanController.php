<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalesmanController extends Controller
{
    public function getSalesmanSalesView(Request $request)
    {
        $user     = auth()->user();
        $branches = $user->getAccessibleBranches();

        $date_from = $request->date_from;
        $date_to   = $request->date_to;
        $branchId  = $request->branch_id;

        $salesmenData  = collect();
        $selectedBranch = null;

        if ($date_from && $date_to) {

            $start = Carbon::parse($date_from)->startOfDay();
            $end   = Carbon::parse($date_to)->endOfDay();

            $filterBranchId = $user->getFilterBranchId($branchId);
            $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;

            // ✅ نفس الـ query بتاع reportSalesSummarySalesman بالظبط
            $query = Invoice::leftJoin('invoice_items',  'invoice_items.invoice_id', 'invoices.id')
                ->leftJoin('products',     'products.product_id',      'invoice_items.product_id')
                ->leftJoin('users',        'users.id',                  'invoices.user_id')
                ->leftJoin('glass_lenses', 'glass_lenses.product_id',  'invoice_items.product_id')
                ->leftJoin('categories',   'categories.id',             'products.category_id')
                ->whereDate('invoice_items.created_at', '>=', $start)
                ->whereDate('invoice_items.created_at', '<=', $end)
                ->where('invoices.status', 'delivered');

            if ($filterBranchId) {
                $query->where('users.branch_id', $filterBranchId);
            }

            $rawItems = $query->select([
                'invoice_items.*',
                'invoices.invoice_code',
                'invoices.total as invoice_total',
                'invoices.user_id',
                'categories.category_name',
                'users.first_name as user_first_name',
                'users.last_name  as user_last_name',
                'users.image      as user_image',
                'users.branch_id  as user_branch_id',
                'glass_lenses.description  as lens_description',
                'glass_lenses.price        as lens_price',
                'glass_lenses.retail_price as lens_retail_price',
                'products.description  as product_description',
                'products.total        as product_total',
                'products.price        as product_price',
                'products.retail_price as product_retail_price',
                \DB::raw('count(*) as item_count'),
            ])
                ->groupBy('invoice_items.product_id')
                ->get();

            // ✅ جروب بالموظف مع حساب الإجماليات
            $salesmenData = $rawItems
                ->groupBy(function ($e) {
                    return trim(($e->user_first_name ?? '') . ' ' . ($e->user_last_name ?? '')) ?: 'Unknown';
                })
                ->map(function ($items, $salesmanName) {

                    $totalAmount = $items->sum(function ($item) {
                        // استخدم net لو موجود، لو لا استخدم total
                        return $item->net ?? $item->total ?? 0;
                    });

                    $invoiceCodes = $items->pluck('invoice_code')->unique()->filter()->values();

                    return [
                        'name'          => $salesmanName,
                        'image'         => $items->first()->user_image ?? null,
                        'total_items'   => $items->sum('item_count'),
                        'total_invoices'=> $invoiceCodes->count(),
                        'total_amount'  => $totalAmount,
                        'invoice_codes' => $invoiceCodes,
                        'items'         => $items,   // للتفاصيل
                        'by_category'   => $items->groupBy('category_name')
                            ->map(fn($g) => [
                                'count'  => $g->sum('item_count'),
                                'amount' => $g->sum(fn($i) => $i->net ?? $i->total ?? 0),
                            ]),
                    ];
                })
                ->sortByDesc('total_amount')
                ->values();
        }

        return view('dashboard.pages.salesman.sales',
            compact('salesmenData', 'branches', 'date_from', 'date_to', 'selectedBranch')
        );
    }
}
