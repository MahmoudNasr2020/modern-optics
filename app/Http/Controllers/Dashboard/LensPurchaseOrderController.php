<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\glassLense;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceItems;
use App\LensLab;
use App\LensPurchaseOrder;
use App\LensPurchaseOrderItem;
use App\LensStockEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LensPurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.spatie:view-stock')->only(['index', 'show']);
        $this->middleware('permission.spatie:add-stock')->only(['create', 'store']);
        $this->middleware('permission.spatie:increase-stock')->only(['receiveForm', 'markReceived']);
        $this->middleware('permission.spatie:edit-stock')->only(['markSent', 'cancel']);
    }

    // ─── List all POs ────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = LensPurchaseOrder::with(['invoice.customer', 'branch', 'lab', 'orderedBy', 'items'])
            ->orderByDesc('created_at');

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $search = $request->search;
            $query->where('po_number', 'like', '%' . $search . '%')
                  ->orWhereHas('invoice', function ($q) use ($search) {
                      $q->where('invoice_code', 'like', '%' . $search . '%');
                  });
        }
        if (!auth()->user()->canSeeAllBranches()) {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        $orders = $query->paginate(20);
        $labs   = LensLab::active()->orderBy('name')->get();

        return view('dashboard.pages.lens-purchase-orders.index', compact('orders', 'labs'));
    }

    // ─── Search invoice by code then redirect to create ─────────
    public function searchInvoice(Request $request)
    {
        $code = trim($request->invoice_code);
        if (!$code) {
            return redirect()->route('dashboard.lens-purchase-orders.index')
                ->with('search_error', 'Please enter an invoice code.');
        }

        // First check if the invoice exists at all (any status)
        $invoiceAny = Invoice::where('invoice_code', $code)->first();

        if (!$invoiceAny) {
            return redirect()->route('dashboard.lens-purchase-orders.index')
                ->withInput()
                ->with('search_error', "Invoice code \"{$code}\" was not found. Please double-check the code.");
        }

        // Check status
        if ($invoiceAny->status !== 'pending') {
            $statusLabel = ucfirst($invoiceAny->status ?? 'unknown');
            return redirect()->route('dashboard.lens-purchase-orders.index')
                ->withInput()
                ->with('search_error', "Invoice {$code} has status \"{$statusLabel}\" — only Pending invoices can be sent to the lab.");
        }

        $invoice = $invoiceAny;

        $hasLens = \DB::table('invoice_items')
            ->where('invoice_id', $invoice->id)
            ->where('type', 'lens')
            ->exists();

        if (!$hasLens) {
            return redirect()->route('dashboard.lens-purchase-orders.index')
                ->withInput()
                ->with('search_error', "Invoice {$code} has no lens items. Only invoices with lens items can have lab orders.");
        }

        return redirect()->route('dashboard.lens-purchase-orders.create', $invoice->id);
    }

    // ─── Create PO from invoice ──────────────────────────────────
    public function create($invoiceId)
    {
        $invoice = Invoice::with(['customer', 'branch', 'invoiceItems'])->findOrFail($invoiceId);

        // Allow access if invoice has no branch (old data), or user can access the branch
        if ($invoice->branch_id && !auth()->user()->canAccessBranch($invoice->branch_id)) {
            abort(403);
        }

        // Get only lens items that don't already have an active PO
        $existingPoItemIds = LensPurchaseOrderItem::whereHas('purchaseOrder', function ($q) use ($invoiceId) {
            $q->where('invoice_id', $invoiceId)->whereNotIn('status', ['cancelled']);
        })->pluck('invoice_item_id')->toArray();

        $lensItems = $invoice->invoiceItems()
            ->where('type', 'lens')
            ->whereNotIn('id', $existingPoItemIds)
            ->get()
            ->map(function ($item) {
                $item->lensModel = glassLense::where('product_id', $item->product_id)->first();
                return $item;
            });

        // Even if glassLense not found, still show item so user can order
        // just flag it
        $lensItems = $lensItems->map(function ($item) {
            if (!$item->lensModel) {
                // Create a minimal stub so the form still works
                $item->lensModel = (object)[
                    'description' => $item->product_id,
                    'brand'       => '-',
                    'index'       => '-',
                    'lense_type'  => '-',
                ];
            }
            return $item;
        });

        if ($lensItems->isEmpty()) {
            return redirect()->route('dashboard.lens-purchase-orders.index')
                ->with('info', 'All lens items in this invoice already have active lab orders.');
        }

        $labs = LensLab::active()->orderBy('name')->get();
        $existingPos = LensPurchaseOrder::where('invoice_id', $invoiceId)
            ->whereNotIn('status', ['cancelled'])
            ->with('items')
            ->get();

        return view('dashboard.pages.lens-purchase-orders.create', compact(
            'invoice', 'lensItems', 'labs', 'existingPos'
        ));
    }

    // ─── Store new PO ────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id'      => 'required|exists:invoices,id',
            'lab_name'        => 'required|string|max:255',
            'notes'           => 'nullable|string|max:1000',
            'items'           => 'required|array|min:1',
            'items.*.invoice_item_id' => 'required|exists:invoice_items,id',
            'items.*.quantity'        => 'required|integer|min:1',
            'items.*.unit_cost'       => 'nullable|numeric|min:0',
            'items.*.notes'           => 'nullable|string|max:500',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);

        if ($invoice->branch_id && !auth()->user()->canAccessBranch($invoice->branch_id)) {
            abort(403);
        }

        DB::transaction(function () use ($request, $invoice) {
            $lab = null;
            if ($request->lab_id) {
                $lab = LensLab::find($request->lab_id);
            }

            // Use invoice branch, fallback to user's branch if invoice has no branch
            $branchId = $invoice->branch_id ?? auth()->user()->branch_id;

            $po = LensPurchaseOrder::create([
                'po_number'  => LensPurchaseOrder::generatePoNumber(),
                'invoice_id' => $invoice->id,
                'branch_id'  => $branchId,
                'lab_id'     => $lab ? $lab->id : null,
                'lab_name'   => $lab ? $lab->name : $request->lab_name,
                'status'     => 'pending',
                'ordered_by' => auth()->id(),
                'notes'      => $request->notes,
                'ordered_at' => now(),
            ]);

            foreach ($request->items as $itemData) {
                $invoiceItem = InvoiceItems::findOrFail($itemData['invoice_item_id']);
                $lens = glassLense::where('product_id', $invoiceItem->product_id)->first();

                LensPurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'invoice_item_id'   => $invoiceItem->id,
                    'glass_lense_id'    => $lens ? $lens->id : null,
                    'lens_product_id'   => $invoiceItem->product_id,
                    'quantity'          => $itemData['quantity'],
                    'unit_cost'         => $itemData['unit_cost'] ?? null,
                    'notes'             => $itemData['notes'] ?? null,
                ]);
            }
        });

        session()->flash('success', 'Lab order created successfully!');
        return redirect()->route('dashboard.lens-purchase-orders.index');
    }

    // ─── Show PO details ─────────────────────────────────────────
    public function show($id)
    {
        $po = LensPurchaseOrder::with([
            'invoice.customer', 'branch', 'lab', 'orderedBy', 'receivedBy',
            'items.lens', 'items.invoiceItem',
        ])->findOrFail($id);

        if ($po->branch_id && !auth()->user()->canAccessBranch($po->branch_id)) {
            abort(403);
        }

        // Stock available per lens item (R + L summary)
        foreach ($po->items as $item) {
            $lenseId = $item->glass_lense_id;
            if (!$lenseId && $item->lens_product_id) {
                $found = glassLense::where('product_id', $item->lens_product_id)->first();
                $lenseId = $found ? $found->id : null;
            }
            $item->available_stock = $lenseId
                ? LensStockEntry::availableFor($lenseId, $po->branch_id)
                : 0;
        }

        return view('dashboard.pages.lens-purchase-orders.show', compact('po'));
    }

    // ─── Receive form ────────────────────────────────────────────
    public function receiveForm($id)
    {
        $po = LensPurchaseOrder::with([
            'invoice.customer', 'branch', 'items.lens', 'items.invoiceItem',
        ])->findOrFail($id);

        if ($po->branch_id && !auth()->user()->canAccessBranch($po->branch_id)) {
            abort(403);
        }

        if ($po->isReceived() || $po->isCancelled()) {
            return redirect()->route('dashboard.lens-purchase-orders.show', $po->id)
                ->with('error', 'This order has already been ' . $po->status . '.');
        }

        return view('dashboard.pages.lens-purchase-orders.receive', compact('po'));
    }

    // ─── Mark as received ────────────────────────────────────────
    public function markReceived(Request $request, $id)
    {
        $po = LensPurchaseOrder::with('items')->findOrFail($id);

        if ($po->branch_id && !auth()->user()->canAccessBranch($po->branch_id)) {
            abort(403);
        }

        if ($po->isReceived() || $po->isCancelled()) {
            return redirect()->route('dashboard.lens-purchase-orders.show', $po->id)
                ->with('error', 'Cannot update this order.');
        }

        $request->validate([
            'received_quantities'   => 'required|array',
            'received_quantities.*' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $po) {
            foreach ($po->items as $item) {
                $receivedQty = (int) ($request->received_quantities[$item->id] ?? 0);

                if ($receivedQty <= 0) continue;

                $item->update(['received_quantity' => $receivedQty]);

                // ── Resolve glass_lense_id if it was NULL (fallback lookup) ──
                $glassLenseId = $item->glass_lense_id;
                if (!$glassLenseId && $item->lens_product_id) {
                    $found = glassLense::where('product_id', $item->lens_product_id)->first();
                    if ($found) {
                        $glassLenseId = $found->id;
                        $item->update(['glass_lense_id' => $glassLenseId]);
                    }
                }

                // ── Get side (R/L) from the linked invoice item ───────────
                $side = null;
                if ($item->invoice_item_id) {
                    $invoiceItem = InvoiceItems::find($item->invoice_item_id);
                    if ($invoiceItem) {
                        $rawDir = trim($invoiceItem->direction ?? '');
                        $side   = in_array($rawDir, ['R', 'L']) ? $rawDir : null;

                        // Mark invoice item as 'ready' (lenses have arrived)
                        if ($invoiceItem->status !== 'ready') {
                            $invoiceItem->update(['status' => 'ready']);
                        }
                    }
                }

                // ── Create stock-IN entry + update lens amount ───────────
                if ($glassLenseId) {
                    LensStockEntry::create([
                        'branch_id'      => $po->branch_id,
                        'glass_lense_id' => $glassLenseId,
                        'side'           => $side,
                        'direction'      => 'in',
                        'quantity'       => $receivedQty,
                        'source_type'    => 'purchase_order',
                        'source_id'      => $po->id,
                        'notes'          => "Received - PO# {$po->po_number} | Invoice #" . (optional($po->invoice)->invoice_code ?? '—'),
                        'user_id'        => auth()->id(),
                    ]);

                    // Keep glass_lenses.amount in sync (authoritative stock counter)
                    $lens = glassLense::find($glassLenseId);
                    if ($lens) {
                        $lens->increment('amount', $receivedQty);
                    }
                }
            }

            $po->update([
                'status'      => 'received',
                'received_by' => auth()->id(),
                'received_at' => now(),
            ]);
        });

        session()->flash('success', 'Lenses received successfully! Stock has been updated.');
        return redirect()->route('dashboard.lens-purchase-orders.show', $po->id);
    }

    // ─── Mark as sent to lab ─────────────────────────────────────
    public function markSent($id)
    {
        $po = LensPurchaseOrder::findOrFail($id);

        if ($po->branch_id && !auth()->user()->canAccessBranch($po->branch_id)) {
            abort(403);
        }

        if (!$po->isPending()) {
            return redirect()->back()->with('error', 'Only pending orders can be marked as sent.');
        }

        $po->update(['status' => 'sent']);

        session()->flash('success', 'Order marked as sent to lab.');
        return redirect()->route('dashboard.lens-purchase-orders.show', $po->id);
    }

    // ─── Cancel PO ───────────────────────────────────────────────
    public function cancel($id)
    {
        $po = LensPurchaseOrder::findOrFail($id);

        if ($po->branch_id && !auth()->user()->canAccessBranch($po->branch_id)) {
            abort(403);
        }

        if ($po->isReceived()) {
            return redirect()->back()->with('error', 'Cannot cancel a received order.');
        }

        $po->update(['status' => 'cancelled']);

        session()->flash('success', 'Lab order cancelled.');
        return redirect()->route('dashboard.lens-purchase-orders.index');
    }

    // ─── Search lenses JSON API (for Select2 in re-order form) ──
    public function searchLenses(Request $request)
    {
        $q = $request->q ?? '';

        $lenses = glassLense::query()
            ->when($q, function ($query) use ($q) {
                $query->where('product_id', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%")
                      ->orWhere('brand', 'like', "%{$q}%");
            })
            ->orderBy('product_id')
            ->limit(30)
            ->get(['id', 'product_id', 'description', 'brand', 'index', 'lense_type']);

        return response()->json([
            'results' => $lenses->map(function ($l) {
                return [
                    'id'   => $l->id,
                    'text' => "[{$l->product_id}] {$l->description} | {$l->brand} | Index: {$l->index}",
                    'product_id'  => $l->product_id,
                    'description' => $l->description,
                    'brand'       => $l->brand,
                    'index'       => $l->index,
                    'lense_type'  => $l->lense_type,
                ];
            }),
        ]);
    }

    // ─── Re-order form (defective lenses from a received PO) ────
    public function reorderForm($id)
    {
        $po = LensPurchaseOrder::with([
            'invoice.customer', 'branch', 'items.lens', 'items.invoiceItem',
        ])->findOrFail($id);

        if (!$po->isReceived()) {
            return redirect()->route('dashboard.lens-purchase-orders.show', $po->id)
                ->with('error', 'You can only re-order from a received lab order.');
        }

        if ($po->branch_id && !auth()->user()->canAccessBranch($po->branch_id)) {
            abort(403);
        }

        $labs = LensLab::active()->orderBy('name')->get();

        return view('dashboard.pages.lens-purchase-orders.reorder', compact('po', 'labs'));
    }

    // ─── Re-order store ─────────────────────────────────────────
    public function reorderStore(Request $request, $id)
    {
        $po = LensPurchaseOrder::with('items')->findOrFail($id);

        if (!$po->isReceived()) {
            return redirect()->back()->with('error', 'Only received orders can be re-ordered.');
        }

        $request->validate([
            'lab_name'    => 'required|string|max:255',
            'new_lenses'  => 'required|array|min:1',
            'new_lenses.*.glass_lense_id' => 'required|exists:glass_lenses,id',
            'new_lenses.*.quantity'       => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $po) {
            $lab      = $request->lab_id ? LensLab::find($request->lab_id) : null;
            $branchId = $po->branch_id ?? auth()->user()->branch_id;

            // ── Step 1: Mark selected old lenses as هالك ──────────────
            if ($request->defective) {
                foreach ($request->defective as $itemId => $data) {
                    if (empty($data['selected'])) continue;
                    $qty          = max(1, (int) ($data['qty'] ?? 1));
                    $glassLenseId = $data['glass_lense_id'] ?? null;
                    $invoiceItemId = $data['invoice_item_id'] ?? null;

                    // Resolve side from invoice item
                    $side = null;
                    if ($invoiceItemId) {
                        $ii = InvoiceItems::find($invoiceItemId);
                        $dir = trim($ii->direction ?? '');
                        $side = in_array($dir, ['R', 'L']) ? $dir : null;
                    }

                    if ($glassLenseId) {
                        // 1. Log the damage movement
                        LensStockEntry::create([
                            'branch_id'      => $branchId,
                            'glass_lense_id' => $glassLenseId,
                            'side'           => $side,
                            'direction'      => 'out',
                            'quantity'       => $qty,
                            'source_type'    => 'damaged',
                            'source_id'      => $po->id,
                            'notes'          => "Damaged/Defective — PO# {$po->po_number}",
                            'user_id'        => auth()->id(),
                        ]);

                        // 2. Reduce BranchStock quantity
                        $bsLens = \App\BranchStock::where('branch_id', $branchId)
                            ->where('stockable_type', 'App\\glassLense')
                            ->where('stockable_id', $glassLenseId)
                            ->first();
                        if ($bsLens && $bsLens->quantity >= $qty) {
                            $bsLens->decrement('quantity', $qty);
                            $bsLens->increment('total_out', $qty);
                        }

                        // 3. Reduce glass_lenses.amount directly
                        $lensRec = glassLense::find($glassLenseId);
                        if ($lensRec && $lensRec->amount >= $qty) {
                            $lensRec->decrement('amount', $qty);
                        }
                    }
                }
            }

            // ── Step 2: Create new PO with newly selected lenses ──────
            $newPo = LensPurchaseOrder::create([
                'po_number'      => LensPurchaseOrder::generatePoNumber(),
                'invoice_id'     => $po->invoice_id,
                'branch_id'      => $branchId,
                'lab_id'         => $lab ? $lab->id : null,
                'lab_name'       => $lab ? $lab->name : $request->lab_name,
                'status'         => 'pending',
                'ordered_by'     => auth()->id(),
                'notes'          => $request->notes ?? "Re-order — Original PO: {$po->po_number}",
                'ordered_at'     => now(),
                'is_reorder'     => 1,
                'original_po_id' => $po->id,
            ]);

            foreach ($request->new_lenses as $lensData) {
                $glassLenseId = (int) $lensData['glass_lense_id'];
                $lens         = glassLense::find($glassLenseId);

                LensPurchaseOrderItem::create([
                    'purchase_order_id' => $newPo->id,
                    'invoice_item_id'   => null,          // new lens, not tied to invoice item
                    'glass_lense_id'    => $glassLenseId,
                    'lens_product_id'   => $lens ? $lens->product_id : null,
                    'quantity'          => (int) $lensData['quantity'],
                    'unit_cost'         => isset($lensData['unit_cost']) && $lensData['unit_cost'] !== '' ? $lensData['unit_cost'] : null,
                    'notes'             => ($lensData['notes'] ?? '') ?: 'Re-order replacement',
                ]);
            }
        });

        session()->flash('success', 'Re-order created! Defective lenses moved to هالك. New PO created.');
        return redirect()->route('dashboard.lens-purchase-orders.index');
    }

    // ─── Damaged lenses list (هالك management) ──────────────────
    public function damagedLenses(Request $request)
    {
        $user     = auth()->user();
        $branchId = $user->canSeeAllBranches() ? $request->branch_id : $user->branch_id;
        $branches = $user->getAccessibleBranches();

        $query = LensStockEntry::with(['lens', 'branch', 'user', 'sourcePo.invoice'])
            ->where('source_type', 'damaged')
            ->where('direction', 'out');

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($request->lens_id) {
            $query->where('glass_lense_id', $request->lens_id);
        }

        $damaged = $query->latest()->paginate(25);

        // For each entry, calculate already-recovered quantity
        foreach ($damaged as $entry) {
            $entry->recovered_qty = LensStockEntry::where('source_type', 'recovered')
                ->where('source_id', $entry->id)
                ->where('direction', 'in')
                ->sum('quantity');
            $entry->remaining_damaged = max(0, $entry->quantity - $entry->recovered_qty);
        }

        // Summary stats
        $totalDamaged   = LensStockEntry::where('source_type', 'damaged')->where('direction', 'out')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->sum('quantity');
        $totalRecovered = LensStockEntry::where('source_type', 'recovered')->where('direction', 'in')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->sum('quantity');
        $netDamaged = max(0, $totalDamaged - $totalRecovered);

        return view('dashboard.pages.lens-purchase-orders.damaged-lenses',
            compact('damaged', 'branches', 'branchId', 'totalDamaged', 'totalRecovered', 'netDamaged'));
    }

    // ─── Recover a damaged lens entry back to stock ──────────────
    public function recoverDamaged(Request $request, $entryId)
    {
        $entry = LensStockEntry::findOrFail($entryId);

        if ($entry->source_type !== 'damaged') {
            return redirect()->back()->with('error', 'This entry is not a damaged lens entry.');
        }

        // Calculate how much has already been recovered for this damaged entry
        $alreadyRecovered = LensStockEntry::where('source_type', 'recovered')
            ->where('source_id', $entry->id)
            ->where('direction', 'in')
            ->sum('quantity');

        $remainingDamaged = max(0, $entry->quantity - $alreadyRecovered);

        if ($remainingDamaged <= 0) {
            return redirect()->back()->with('error', 'This damaged entry has already been fully recovered.');
        }

        $request->validate([
            'recover_qty' => 'required|integer|min:1|max:' . $remainingDamaged,
            'notes'       => 'nullable|string|max:500',
        ]);

        $qty = (int) $request->recover_qty;

        DB::transaction(function () use ($entry, $qty, $request) {
            // 1. Create LensStockEntry (movement log — used by lensStock page)
            LensStockEntry::create([
                'branch_id'      => $entry->branch_id,
                'glass_lense_id' => $entry->glass_lense_id,
                'side'           => $entry->side,
                'direction'      => 'in',
                'quantity'       => $qty,
                'source_type'    => 'recovered',
                'source_id'      => $entry->id,
                'notes'          => $request->notes ?: "Recovered from damaged — Original entry #{$entry->id}",
                'user_id'        => auth()->id(),
            ]);

            // 2. Update BranchStock quantity for lens (used by branch stock reports)
            if ($entry->glass_lense_id) {
                $branchStock = \App\BranchStock::where('branch_id', $entry->branch_id)
                    ->where('stockable_type', 'App\\glassLense')
                    ->where('stockable_id', $entry->glass_lense_id)
                    ->first();

                if ($branchStock) {
                    $branchStock->increment('quantity', $qty);
                    $branchStock->increment('total_in', $qty);
                } else {
                    // Create branch stock record if it doesn't exist
                    \App\BranchStock::create([
                        'branch_id'      => $entry->branch_id,
                        'stockable_type' => 'App\\glassLense',
                        'stockable_id'   => $entry->glass_lense_id,
                        'product_id'     => null,
                        'quantity'       => $qty,
                        'min_quantity'   => 0,
                        'max_quantity'   => 999,
                        'total_in'       => $qty,
                        'total_out'      => 0,
                    ]);
                }

                // 3. Also update glass_lenses.amount directly (used by lens catalog & invoices)
                $lens = glassLense::find($entry->glass_lense_id);
                if ($lens) {
                    $lens->increment('amount', $qty);
                }
            }
        });

        return redirect()->back()->with('success', "Recovered {$qty} lens(es) back to stock successfully.");
    }

    // ─── Lens stock view ─────────────────────────────────────────
    public function lensStock(Request $request)
    {
        $user     = auth()->user();
        $branchId = $user->canSeeAllBranches() ? $request->branch_id : $user->branch_id;
        $branches = $user->getAccessibleBranches();

        // ── 1. LensStockEntry movements ───────────────────────────────────────
        $stockQuery = LensStockEntry::selectRaw(
            "glass_lense_id, side, direction, SUM(quantity) as qty"
        );
        if ($branchId) {
            $stockQuery->where('branch_id', $branchId);
        }
        $rawStock = $stockQuery->groupBy('glass_lense_id', 'side', 'direction')->get();

        // Index: [glass_lense_id][side][direction] = qty
        $stockIndex = [];
        foreach ($rawStock as $row) {
            $lid  = $row->glass_lense_id;
            $side = $row->side ?? 'X';
            $dir  = $row->direction;
            $stockIndex[$lid][$side][$dir] = ($stockIndex[$lid][$side][$dir] ?? 0) + $row->qty;
        }

        // ── 2. Recovered entries (net-independent minimum stock) ─────────────
        // When damage & recovery quantities are equal the standard (in-out) formula
        // gives 0, even though the recovered lenses ARE physically back in stock.
        // We store their total as a guaranteed minimum for stock_available.
        $recQuery = LensStockEntry::selectRaw(
            "glass_lense_id, SUM(quantity) as total_recovered"
        )->where('source_type', 'recovered')->where('direction', 'in');
        if ($branchId) {
            $recQuery->where('branch_id', $branchId);
        }
        $recoveredMap = [];
        foreach ($recQuery->groupBy('glass_lense_id')->get() as $row) {
            $recoveredMap[$row->glass_lense_id] = (int) $row->total_recovered;
        }

        // ── 3. BranchStock quantities (most reliable, updated by damage/recovery) ─
        $bsQuery = \App\BranchStock::where('stockable_type', 'App\\glassLense')
            ->where('quantity', '>', 0);
        if ($branchId) {
            $bsQuery->where('branch_id', $branchId);
        }
        $bsMap = [];
        foreach ($bsQuery->get(['stockable_id', 'quantity']) as $bs) {
            $bsMap[$bs->stockable_id] = ($bsMap[$bs->stockable_id] ?? 0) + (int) $bs->quantity;
        }

        // ── 4. Merge all lens IDs ─────────────────────────────────────────────
        $allLensIds = array_unique(array_merge(
            array_keys($stockIndex),
            array_keys($bsMap),
            array_keys($recoveredMap)
        ));

        if (empty($allLensIds) && !$request->show_all) {
            $lenses = collect();
            return view('dashboard.pages.lens-purchase-orders.lens-stock',
                compact('lenses', 'branches', 'branchId'));
        }

        // ── 5. Fetch lens records and compute display fields ──────────────────
        $lensQuery = $request->show_all
            ? glassLense::query()
            : glassLense::whereIn('id', $allLensIds);

        $lenses = $lensQuery->orderBy('product_id')->get()
            ->map(function ($lens) use ($stockIndex, $bsMap, $recoveredMap) {
                $data = $stockIndex[$lens->id] ?? [];

                $calcNet = function ($sideKey) use ($data) {
                    $in  = $data[$sideKey]['in']  ?? 0;
                    $out = $data[$sideKey]['out'] ?? 0;
                    return max(0, $in - $out);
                };

                $lens->stock_R   = $calcNet('R');
                $lens->stock_L   = $calcNet('L');
                $lens->stock_unk = $calcNet('X');

                // Priority for stock_available:
                //   A) BranchStock.quantity — directly maintained by damage/recovery ops
                //   B) LensStockEntry net   — works when PO receive history exists
                //   C) Recovered total      — guaranteed minimum; handles the case where
                //                             damage & recovery cancel each other to 0
                //      (old recoveries done before BranchStock was tracked)
                $entryNet    = $lens->stock_R + $lens->stock_L + $lens->stock_unk;
                $bsQty       = isset($bsMap[$lens->id])       ? max(0, $bsMap[$lens->id])       : $entryNet;
                $recoveredQty = isset($recoveredMap[$lens->id]) ? max(0, $recoveredMap[$lens->id]) : 0;

                $lens->stock_available = max($bsQty, $recoveredQty);

                // Total in / out (for IN / OUT history columns)
                $lens->stock_in  = 0;
                $lens->stock_out = 0;
                foreach ($data as $sides) {
                    $lens->stock_in  += $sides['in']  ?? 0;
                    $lens->stock_out += $sides['out'] ?? 0;
                }

                return $lens;
            })
            ->filter(function ($l) use ($request) {
                return $l->stock_available > 0 || $l->stock_in > 0 || $request->show_all;
            });

        return view('dashboard.pages.lens-purchase-orders.lens-stock',
            compact('lenses', 'branches', 'branchId'));
    }
}
