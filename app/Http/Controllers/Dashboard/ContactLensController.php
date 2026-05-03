<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\Brand;
use App\BranchStock;
use App\Category;
use App\glassModel;
use App\Http\Controllers\Controller;
use App\Product;
use App\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * ContactLensController
 *
 * Handles CRUD for Contact Lens products (category_id = 4).
 * Uses the same products table but always sets category_id = 4
 * and uses the extra columns: brand_segment, lense_use, sign, power.
 *
 * model_id  → "Product Name" (a glass_model entry with category_id = 4)
 * color     → '' (not used)
 * size      → '' (not used)
 */
class ContactLensController extends Controller
{
    /** Contact Lens category ID */
    const CATEGORY_ID = 4;

    // ──────────────────────────────────────────────────────────
    // INDEX — list all contact lens products
    // ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'model'])
            ->where('category_id', self::CATEGORY_ID);

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('description', 'like', "%{$s}%")
                  ->orWhere('product_id', 'like', "%{$s}%")
                  ->orWhere('power', 'like', "%{$s}%");
            });
        }

        // Filters
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
        if ($request->filled('brand_segment')) {
            $query->where('brand_segment', $request->brand_segment);
        }
        if ($request->filled('lense_use')) {
            $query->where('lense_use', $request->lense_use);
        }
        if ($request->filled('sign')) {
            $query->where('sign', $request->sign);
        }

        // Show archived only when ?show_archived=1
        $showArchived = $request->input('show_archived') == '1';
        if (!$showArchived) {
            $query->where('is_active', true);
        }

        $lenses        = $query->latest()->paginate(30);
        $allBrands     = Brand::where('category_id', self::CATEGORY_ID)->get();
        $totalLenses   = Product::where('category_id', self::CATEGORY_ID)->where('is_active', true)->count();
        $archivedCount = Product::where('category_id', self::CATEGORY_ID)->where('is_active', false)->count();

        return view('dashboard.pages.contact-lenses.index', compact(
            'lenses', 'allBrands', 'totalLenses', 'archivedCount', 'showArchived'
        ));
    }

    // ──────────────────────────────────────────────────────────
    // CREATE — show form
    // ──────────────────────────────────────────────────────────
    public function create()
    {
        // Auto-generate next product ID
        $lastProduct = Product::orderBy('id', 'desc')->first();
        $nextId      = $lastProduct ? ((int) $lastProduct->product_id + 1) : 100001;

        // Pre-generate a unique EAN-13 barcode for the form
        $nextBarcode = $this->generateUniqueBarcode();

        // Brands available for contact lenses (category_id = 4 only)
        $brands = Brand::where('category_id', self::CATEGORY_ID)->get();

        return view('dashboard.pages.contact-lenses.create', compact('nextId', 'nextBarcode', 'brands'));
    }

    // ──────────────────────────────────────────────────────────
    // GENERATE BARCODE — AJAX endpoint
    // ──────────────────────────────────────────────────────────
    public function generateBarcodeAjax()
    {
        return response()->json(['barcode' => $this->generateUniqueBarcode()]);
    }

    // ──────────────────────────────────────────────────────────
    // STORE — save new contact lens
    // ──────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'product_id'    => 'nullable|unique:products,product_id',
            'barcode'       => 'nullable|string|max:100|unique:products,barcode',
            'brand_id'      => 'required|exists:brands,id',
            'model_id'      => 'required|exists:glass_models,id',
            'brand_segment' => 'required|in:Clear,Color,Toric,Multifocal',
            'lense_use'     => 'required|in:Daily,Monthly',
            'sign'          => 'required|in:+,-',
            'power'         => 'required|numeric|min:0|max:12',
            'description'   => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'retail_price'  => 'required|numeric|min:0',
            'tax'           => 'nullable|numeric|min:0',
            'store_quantity'=> 'nullable|integer|min:0',
            'store_min_qty' => 'nullable|integer|min:0',
            'store_max_qty' => 'nullable|integer|min:1',
        ], [
            'brand_id.required'      => 'Please select a brand.',
            'model_id.required'      => 'Please select a product name.',
            'brand_segment.required' => 'Please select a brand segment.',
            'lense_use.required'     => 'Please select lens type (Daily / Monthly).',
            'sign.required'          => 'Please select sign (+ or -).',
            'power.required'         => 'Please enter the power.',
            'description.required'   => 'Please enter a description.',
            'price.required'         => 'Please enter cost price.',
            'retail_price.required'  => 'Please enter retail price.',
        ]);

        // Generate product_id if not provided
        $productId = $request->filled('product_id')
            ? trim($request->product_id)
            : ((int)(Product::max('product_id') ?? 100000) + 1);

        $product = Product::create([
            'product_id'    => $productId,
            'barcode'       => $request->filled('barcode') ? trim($request->barcode) : $this->generateUniqueBarcode(),
            'category_id'   => self::CATEGORY_ID,
            'brand_id'      => $request->brand_id,
            'model_id'      => $request->model_id,
            'description'   => $request->description,
            'brand_segment' => $request->brand_segment,
            'lense_use'     => $request->lense_use,
            'sign'          => $request->sign,
            'power'         => $request->power,
            'color'         => '',
            'size'          => '',
            'price'         => $request->price,
            'retail_price'  => $request->retail_price,
            'tax'           => $request->tax ?? 0,
            'discount_type' => 'fixed',
            'discount_value'=> 0,
            'total'         => $request->retail_price,
        ]);

        // Auto-register in Store branch
        $this->registerInStore($product, $request);

        session()->flash('success', 'Contact lens added successfully!');
        return redirect()->route('dashboard.contact-lenses.index');
    }

    // ──────────────────────────────────────────────────────────
    // EDIT — show edit form
    // ──────────────────────────────────────────────────────────
    public function edit($id)
    {
        $lens   = Product::where('category_id', self::CATEGORY_ID)->findOrFail($id);
        $brands = Brand::where('category_id', self::CATEGORY_ID)->get();
        $models = glassModel::where('category_id', self::CATEGORY_ID)->get();

        return view('dashboard.pages.contact-lenses.edit', compact('lens', 'brands', 'models'));
    }

    // ──────────────────────────────────────────────────────────
    // UPDATE — save changes
    // ──────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $lens = Product::where('category_id', self::CATEGORY_ID)->findOrFail($id);

        $request->validate([
            'brand_id'      => 'required|exists:brands,id',
            'model_id'      => 'required|exists:glass_models,id',
            'brand_segment' => 'required|in:Clear,Color,Toric,Multifocal',
            'lense_use'     => 'required|in:Daily,Monthly',
            'sign'          => 'required|in:+,-',
            'power'         => 'required|numeric|min:0|max:12',
            'description'   => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'retail_price'  => 'required|numeric|min:0',
            'tax'           => 'nullable|numeric|min:0',
        ]);

        $lens->update([
            'brand_id'      => $request->brand_id,
            'model_id'      => $request->model_id,
            'description'   => $request->description,
            'brand_segment' => $request->brand_segment,
            'lense_use'     => $request->lense_use,
            'sign'          => $request->sign,
            'power'         => $request->power,
            'price'         => $request->price,
            'retail_price'  => $request->retail_price,
            'tax'           => $request->tax ?? 0,
            'total'         => $request->retail_price,
        ]);

        session()->flash('success', 'Contact lens updated successfully!');
        return redirect()->route('dashboard.contact-lenses.index');
    }

    // ──────────────────────────────────────────────────────────
    // DESTROY — 3-tier safe deletion
    // ──────────────────────────────────────────────────────────
    public function destroy($id)
    {
        $lens = Product::where('category_id', self::CATEGORY_ID)->findOrFail($id);

        $result = $lens->safeDelete();

        switch ($result['action']) {
            case 'blocked':
                session()->flash('error', $result['message']);
                break;
            case 'archived':
                session()->flash('warning', $result['message']);
                break;
            case 'deleted':
                session()->flash('success', $result['message']);
                break;
        }

        return redirect()->route('dashboard.contact-lenses.index');
    }

    // ──────────────────────────────────────────────────────────
    // RESTORE — reactivate an archived contact lens
    // ──────────────────────────────────────────────────────────
    public function restore($id)
    {
        $lens = Product::where('category_id', self::CATEGORY_ID)->findOrFail($id);
        $lens->restore();

        session()->flash('success', 'تم استعادة العدسة وتفعيلها مجدداً.');
        return redirect()->route('dashboard.contact-lenses.index');
    }

    // ──────────────────────────────────────────────────────────
    // IMPORT (Excel) — bulk import contact lenses
    // ──────────────────────────────────────────────────────────
    public function import(Request $request)
    {
        $request->validate(['import_data' => 'required|string']);

        $rows   = json_decode($request->import_data, true);
        $added  = 0;
        $errors = [];

        foreach ($rows as $i => $row) {
            try {
                $brand = Brand::whereRaw('LOWER(brand_name) = ?', [strtolower($row['brand'] ?? '')])->first();
                if (!$brand) { $errors[] = "Row " . ($i + 1) . ": brand '{$row['brand']}' not found."; continue; }

                $model = glassModel::where('category_id', self::CATEGORY_ID)
                    ->whereRaw('LOWER(model_id) = ?', [strtolower($row['product'] ?? '')])->first();
                if (!$model) {
                    // Auto-create model under this brand for category 4
                    $model = glassModel::create([
                        'category_id' => self::CATEGORY_ID,
                        'brand_id'    => $brand->id,
                        'model_id'    => $row['product'],
                    ]);
                }

                $pid = !empty($row['product_id'])
                    ? $row['product_id']
                    : ((int)(Product::max('product_id') ?? 100000) + 1);

                if (Product::where('product_id', $pid)->exists()) {
                    $errors[] = "Row " . ($i + 1) . ": product_id '{$pid}' already exists — skipped.";
                    continue;
                }

                // Auto-generate barcode if not provided in the sheet
                $barcode = !empty(trim($row['barcode'] ?? ''))
                    ? trim($row['barcode'])
                    : $this->generateUniqueBarcode();

                $product = Product::create([
                    'product_id'    => $pid,
                    'barcode'       => $barcode,
                    'category_id'   => self::CATEGORY_ID,
                    'brand_id'      => $brand->id,
                    'model_id'      => $model->id,
                    'description'   => $row['description'] ?? '',
                    'brand_segment' => $row['brand_segment'] ?? '',
                    'lense_use'     => $row['lense_use'] ?? '',
                    'sign'          => $row['sign'] ?? '+',
                    'power'         => $row['power'] ?? 0,
                    'color'         => '',
                    'size'          => '',
                    'price'         => (float)($row['price'] ?? 0),
                    'retail_price'  => (float)($row['retail_price'] ?? 0),
                    'tax'           => (float)($row['tax'] ?? 0),
                    'discount_type' => 'fixed',
                    'discount_value'=> 0,
                    'total'         => (float)($row['retail_price'] ?? 0),
                ]);

                // Register in store
                $this->registerInStoreFromArray($product, $row);
                $added++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($i + 1) . ": " . $e->getMessage();
            }
        }

        session()->flash('import_added',  $added);
        session()->flash('import_errors', $errors);
        return redirect()->route('dashboard.contact-lenses.create');
    }

    // ──────────────────────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────────────────────

    /**
     * Register product in the main Store branch.
     */
    private function registerInStore(Product $product, Request $request): void
    {
        try {
            $storeBranch = Branch::where('is_main', true)->where('is_active', true)->first();
            if (!$storeBranch) return;

            $storeQty    = max(0, (int) $request->input('store_quantity', 0));
            $storeMinQty = max(0, (int) $request->input('store_min_qty',  0));
            $storeMaxQty = $request->filled('store_max_qty') && (int)$request->store_max_qty > 0
                            ? max(1, (int)$request->store_max_qty) : 999;

            $existing = BranchStock::where('branch_id', $storeBranch->id)
                ->where('stockable_type', 'App\\Product')
                ->where('stockable_id', $product->id)
                ->first();

            if ($existing) {
                $balanceBefore = $existing->quantity;
                $existing->quantity    += $storeQty;
                $existing->total_in    += $storeQty;
                $existing->min_quantity = $storeMinQty;
                $existing->max_quantity = $storeMaxQty;
                $existing->save();
            } else {
                BranchStock::create([
                    'branch_id'      => $storeBranch->id,
                    'product_id'     => $product->id,
                    'stockable_type' => 'App\\Product',
                    'stockable_id'   => $product->id,
                    'quantity'       => $storeQty,
                    'min_quantity'   => $storeMinQty,
                    'max_quantity'   => $storeMaxQty,
                    'total_in'       => $storeQty,
                    'total_out'      => 0,
                ]);
                $balanceBefore = 0;
            }

            if ($storeQty > 0) {
                StockMovement::createForProduct(
                    $storeBranch->id, $product->id, 'in', $storeQty, auth()->id(),
                    ['notes' => 'Initial store stock — contact lens creation', 'balance_before' => $balanceBefore]
                );
            }
        } catch (\Exception $e) {
            session()->flash('warning', 'Contact lens saved, but Store stock registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique EAN-13 barcode not already used in the products table.
     */
    private function generateUniqueBarcode(): string
    {
        do {
            $digits = '';
            for ($i = 0; $i < 12; $i++) {
                $digits .= random_int(0, 9);
            }
            // EAN-13 check digit
            $sum = 0;
            for ($i = 0; $i < 12; $i++) {
                $sum += (int)$digits[$i] * ($i % 2 === 0 ? 1 : 3);
            }
            $check   = (10 - ($sum % 10)) % 10;
            $barcode = $digits . $check;
        } while (Product::where('barcode', $barcode)->exists());

        return $barcode;
    }

    private function registerInStoreFromArray(Product $product, array $row): void
    {
        $storeBranch = Branch::where('is_main', true)->where('is_active', true)->first();
        if (!$storeBranch) return;

        $qty = max(0, (int)($row['quantity'] ?? 0));

        $existing = BranchStock::where('branch_id', $storeBranch->id)
            ->where('stockable_type', 'App\\Product')
            ->where('stockable_id', $product->id)
            ->first();

        if (!$existing) {
            BranchStock::create([
                'branch_id'      => $storeBranch->id,
                'product_id'     => $product->id,
                'stockable_type' => 'App\\Product',
                'stockable_id'   => $product->id,
                'quantity'       => $qty,
                'min_quantity'   => max(0, (int)($row['min_quantity'] ?? 0)),
                'max_quantity'   => max(1,  (int)($row['max_quantity'] ?? 999)),
                'total_in'       => $qty,
                'total_out'      => 0,
            ]);
        }
    }
}
