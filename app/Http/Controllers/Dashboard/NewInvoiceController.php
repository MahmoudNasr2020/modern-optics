<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\BranchStock;
use App\Brand;
use App\Cardholder;
use App\CashierTransaction;
use App\Category;
use App\Customer;
use App\Doctor;
use App\Facades\Settings;
use App\glassLense;
use App\glassModel;
use App\Http\Controllers\Controller;
use App\InsuranceCompany;
use App\Invoice;
use App\InvoiceItems;
use App\LensBrand;
use App\Payments;
use App\Product;
use App\Services\InvoiceWhatsAppNotifier;
use App\Services\NotificationService;
use App\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NewInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.spatie:create-invoices')
            ->only(['create', 'saveInvoice']);
    }

    /**
     * ====================================================
     * CREATE INVOICE VIEW
     * ====================================================
     */
    public function create($customer_id)
    {
        $user = auth()->user();

        // Get customer
        $customer = Customer::where('customer_id', $customer_id)->firstOrFail();

        // Get accessible branches — exclude the main/store branch from invoice creation
        $branches = $user->getAccessibleBranches()->where('is_main', false);

        // Get default branch — if user's assigned branch is the main store, use first available instead
        $userBranch    = $user->hasBranch() ? $user->branch : null;
        $defaultBranch = ($userBranch && !$userBranch->is_main) ? $userBranch : $branches->first();

        // Get categories, brands, models for search
        $categories = Category::all();

        // Get doctors
        $doctors = Doctor::all();

        // Get insurance companies and cardholders for discounts
        $insurances = InsuranceCompany::with('categories')->get();
        $cardholders = Cardholder::with('categories')->get();

        // Get customers for search modal
        $customers = Customer::all();

        $brands     = Brand::orderBy('brand_name')->get();       // ✅ NEW
        $models     = glassModel::orderBy('model_id')->get();    // ✅ NEW
        $lensBrands = LensBrand::all();

        // Initialize session if not exists
        if (!session()->has('invoice_draft')) {
            session([
                'invoice_draft' => [
                    'customer_id' => $customer_id,
                    'branch_id' => $defaultBranch ? $defaultBranch->id : null,
                    'doctor_id' => null,
                    'items' => [],
                    'discount' => [
                        'type' => null,
                        'value' => 0,
                        'payer_type' => null,
                        'payer_id' => null,
                        'approval_amount' => 0
                    ],
                    'payments' => [],
                    'totals' => [
                        'subtotal' => 0,
                        'total_qty' => 0,
                        'total_net' => 0,
                        'total_tax' => 0,
                        'discount_amount' => 0,
                        'grand_total' => 0
                    ]
                ]
            ]);
        }

        $invoiceDesign = Settings::get('invoice_template', 'design1');

        $viewMap = [
            'design1' => 'dashboard.pages.invoice-new.create',
            'design2' => 'dashboard.pages.invoice-new.create_v2',
            'design3' => 'dashboard.pages.invoice-new.create_v3',
            'design4' => 'dashboard.pages.invoice-new.create_v4',
        ];

        $viewName = isset($viewMap[$invoiceDesign])
            ? $viewMap[$invoiceDesign]
            : 'dashboard.pages.invoice-new.create';

        return view($viewName, compact(
            'customer',
            'branches',
            'defaultBranch',
            'categories',
            'doctors',
            'insurances',
            'cardholders',
            'customers',
            'brands',
            'models',
            'lensBrands',
            'invoiceDesign'
        ));
    }

    /**
     * ====================================================
     * SESSION MANAGEMENT
     * ====================================================
     */

    /**
     * Store item in session
     */
    public function storeItemInSession(Request $request)
    {
        $product = Product::where('product_id', $request->product_id)
            ->where('branch_id', $request->branch_id)
            ->first();

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:product,lens',
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'branch_id' => 'required|exists:branches,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $branch_id = $request->branch_id;

        // Check branch access
        if (!$user->canAccessBranch($branch_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this branch'
            ], 403);
        }

        try {
            // Get product/lens details with stock
            if ($request->type === 'product') {
                $item = $this->getProductWithStock($request->product_id, $branch_id);
            } else {
                $item = $this->getLensWithStock($request->product_id, $branch_id);
            }

            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in this branch'
                ], 404);
            }

            // For products only: check stock availability
            // Lenses are lab-order items — stock can be 0, always allow
            if ($request->type === 'product' && $item['stock'] < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock. Only {$item['stock']} units available"
                ], 400);
            }

            // Get current draft
            $draft = session('invoice_draft');

            // Check if item already exists
            $existingIndex = $this->findItemInDraft($draft['items'], $request->product_id, $request->type);

            if ($existingIndex !== false) {
                // Update quantity
                $newQty = $draft['items'][$existingIndex]['quantity'] + $request->quantity;

                // For products only: enforce stock ceiling
                if ($request->type === 'product' && $newQty > $item['stock']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot add. Total quantity would exceed available stock ({$item['stock']} units)"
                    ], 400);
                }

                $draft['items'][$existingIndex]['quantity'] = $newQty;
                $draft['items'][$existingIndex]['total'] = $this->calculateItemTotal(
                    $draft['items'][$existingIndex]
                );
            } else {
                // Add new item
                $newItem = [
                    'id' => uniqid(),
                    'type' => $request->type,
                    'product_id' => $item['product_id'],
                    'description' => $item['description'],
                    'category_id' => $item['category_id'] ?? null,
                    'quantity' => $request->quantity,
                    'price' => $item['retail_price'],
                    'net' => $item['net_price'],
                    'tax' => $item['tax'],
                    'tax_amount' => ($item['retail_price'] * $item['tax'] / 100),
                    'total' => $item['retail_price'] * $request->quantity,
                    'stock' => $item['stock'],
                    'branch_id' => $branch_id,
                    'branch_name' => $item['branch_name'],
                    'discount_percent' => 0
                ];

                $draft['items'][] = $newItem;
            }

            // Update branch_id if changed
            $draft['branch_id'] = $branch_id;

            // Recalculate totals
            $draft['totals'] = $this->calculateDraftTotals($draft);

            // Save to session
            session(['invoice_draft' => $draft]);

            return response()->json([
                'success' => true,
                'message' => 'Item added successfully',
                'draft' => $draft
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update item in session
     */
    public function updateItemInSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $draft = session('invoice_draft');

        // Find item
        $itemIndex = $this->findItemById($draft['items'], $request->item_id);

        if ($itemIndex === false) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        $item = $draft['items'][$itemIndex];

        // Check stock — lenses are lab-order items, skip stock check for them
        $itemType = $item['type'] ?? 'product';
        if ($itemType !== 'lens' && $request->quantity > $item['stock']) {
            return response()->json([
                'success' => false,
                'message' => "Insufficient stock. Only {$item['stock']} units available"
            ], 400);
        }

        // Update quantity and total
        $draft['items'][$itemIndex]['quantity'] = $request->quantity;
        $draft['items'][$itemIndex]['total'] = $this->calculateItemTotal($draft['items'][$itemIndex]);

        // Recalculate totals
        $draft['totals'] = $this->calculateDraftTotals($draft);

        session(['invoice_draft' => $draft]);

        return response()->json([
            'success' => true,
            'message' => 'Item updated successfully',
            'draft' => $draft
        ]);
    }

    /**
     * Delete item from session
     */
    public function deleteItemFromSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $draft = session('invoice_draft');

        // Find and remove item
        $itemIndex = $this->findItemById($draft['items'], $request->item_id);

        if ($itemIndex === false) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        array_splice($draft['items'], $itemIndex, 1);

        // Recalculate totals
        $draft['totals'] = $this->calculateDraftTotals($draft);

        session(['invoice_draft' => $draft]);

        return response()->json([
            'success' => true,
            'message' => 'Item deleted successfully',
            'draft' => $draft
        ]);
    }

    /**
     * Clear session
     */
    public function clearSession(Request $request)
    {
        $customer_id = session('invoice_draft.customer_id');
        $branch_id = session('invoice_draft.branch_id');

        session([
            'invoice_draft' => [
                'customer_id' => $customer_id,
                'branch_id' => $branch_id,
                'doctor_id' => null,
                'items' => [],
                'discount' => [
                    'type' => null,
                    'value' => 0,
                    'payer_type' => null,
                    'payer_id' => null,
                    'approval_amount' => 0
                ],
                'payments' => [],
                'totals' => [
                    'subtotal' => 0,
                    'total_qty' => 0,
                    'total_net' => 0,
                    'total_tax' => 0,
                    'discount_amount' => 0,
                    'grand_total' => 0
                ]
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invoice cleared successfully'
        ]);
    }

    /**
     * ====================================================
     * HELPER METHODS
     * ====================================================
     */



    /**
     * Find item in draft by product_id and type
     */
    private function findItemInDraft($items, $product_id, $type)
    {
        foreach ($items as $index => $item) {
            if ($item['product_id'] === $product_id && $item['type'] === $type) {
                return $index;
            }
        }
        return false;
    }

    /**
     * Find item by ID
     */
    private function findItemById($items, $item_id)
    {
        foreach ($items as $index => $item) {
            if ($item['id'] === $item_id) {
                return $index;
            }
        }
        return false;
    }

    /**
     * Calculate item total
     */
    private function calculateItemTotal($item)
    {
        $subtotal = $item['price'] * $item['quantity'];

        // Apply discount if exists
        if (isset($item['discount_percent']) && $item['discount_percent'] > 0) {
            $subtotal = $subtotal - ($subtotal * $item['discount_percent'] / 100);
        }

        return round($subtotal, 2);
    }

    /**
     * Calculate draft totals
     */
    /*private function calculateDraftTotals($draft)
    {
        $subtotal = 0;
        $total_qty = 0;
        $total_net = 0;
        $total_tax = 0;

        foreach ($draft['items'] as $item) {
            $total_qty += $item['quantity'];
            $item_total = $item['total'];
            $subtotal += $item_total;

            // Calculate tax amount
            $tax_amount = ($item_total * $item['tax'] / (100 + $item['tax']));
            $total_tax += $tax_amount;
            $total_net += ($item_total - $tax_amount);
        }

        // Apply regular discount
        $discount_amount = 0;
        if (isset($draft['discount']['type']) && $draft['discount']['type']) {
            if ($draft['discount']['type'] === 'fixed') {
                $discount_amount = $draft['discount']['value'];
            } else {
                $discount_amount = ($subtotal * $draft['discount']['value'] / 100);
            }
        }

        // Apply approval amount (insurance)
        if (isset($draft['discount']['approval_amount'])) {
            $discount_amount += $draft['discount']['approval_amount'];
        }

        $grand_total = $subtotal - $discount_amount;

        return [
            'subtotal' => round($subtotal, 2),
            'total_qty' => $total_qty,
            'total_net' => round($total_net, 2),
            'total_tax' => round($total_tax, 2),
            'discount_amount' => round($discount_amount, 2),
            'grand_total' => round($grand_total, 2)
        ];
    }*/

    private function calculateDraftTotals($draft)
    {
        $subtotal = 0;              // ← بعد خصم الـ items
        $subtotal_before = 0;       // ✅ قبل خصم الـ items
        $total_qty = 0;
        $total_net = 0;
        $total_tax = 0;

        foreach ($draft['items'] as $item) {
            $total_qty += $item['quantity'];

            // ✅ احسب الـ total قبل الخصم
            $item_total_before = $item['price'] * $item['quantity'];
            $subtotal_before += $item_total_before;

            // احسب الـ total بعد الخصم
            $item_total = $item['total'];
            $subtotal += $item_total;

            // Calculate tax amount
            $tax_amount = ($item_total * $item['tax'] / (100 + $item['tax']));
            $total_tax += $tax_amount;
            $total_net += ($item_total - $tax_amount);
        }

        // Apply regular discount (على الـ subtotal بعد خصم الـ items)
        $discount_amount = 0;
        if (isset($draft['discount']['type']) && $draft['discount']['type']) {
            if ($draft['discount']['type'] === 'fixed') {
                $discount_amount = $draft['discount']['value'];
            } else {
                $discount_amount = ($subtotal * $draft['discount']['value'] / 100);
            }
        }

        // Apply approval amount (insurance)
        if (isset($draft['discount']['approval_amount'])) {
            $discount_amount += $draft['discount']['approval_amount'];
        }

        $grand_total = $subtotal - $discount_amount;

        return [
            'subtotal_before' => round($subtotal_before, 2),  // ✅ قبل كل الخصومات
            'subtotal' => round($subtotal, 2),                 // بعد خصم الـ items
            'total_qty' => $total_qty,
            'total_net' => round($total_net, 2),
            'total_tax' => round($total_tax, 2),
            'discount_amount' => round($discount_amount, 2),
            'grand_total' => round($grand_total, 2)
        ];
    }

    /**
     * ====================================================
     * PART 2: PRODUCT SEARCH & LENSES
     * ====================================================
     * Add these methods to InvoiceController
     */

    /**
     * ====================================================
     * PRODUCT SEARCH METHODS
     * ====================================================
     */

    /**
     * Search products with filters
     */
    public function searchProducts(Request $request)
    {

        $user = auth()->user();
        $branch_id = $request->branch_id;

        // Validate branch
        if (!$branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a branch first'
            ], 400);
        }

        // Check branch access
        if (!$user->canAccessBranch($branch_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this branch'
            ], 403);
        }

        try {

            $query = Product::query()
                ->with(['branchStocks' => function($q) use ($branch_id) {
                    $q->where('branch_id', $branch_id);
                }])
                ->whereHas('branchStocks', function($q) use ($branch_id) {
                    $q->where('branch_id', $branch_id)
                        ->where('quantity', '>', 0);
                });

            // Apply filters
            if ($request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->brand_id) {
                $query->where('brand_id', $request->brand_id);
            }

            if ($request->model_id) {
                $query->where('model_id', $request->model_id);
            }

            if ($request->size) {
                $query->where('size', $request->size);
            }

            if ($request->color) {
                $query->where('color', 'LIKE', "%{$request->color}%");
            }

            if ($request->brand_segment) {
                $query->where('brand_segment', $request->brand_segment);
            }

            if ($request->power) {
                $query->where('power', $request->power);
            }

            if ($request->sign) {
                $query->where('sign', $request->sign);
            }

            if ($request->type) {
                $query->where('type', $request->type);
            }

            // Search by product_id or description
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('product_id', 'LIKE', "%{$request->search}%")
                        ->orWhere('description', 'LIKE', "%{$request->search}%");
                });
            }

            $products = $query->orderBy('product_id', 'ASC')
                ->limit(50)
                ->get();

            $branch = Branch::find($branch_id);

            // Format response
            $productsArray = $products->map(function ($product) use ($branch) {
                $branchStock = $product->branchStocks->first();

                return [
                    'id' => $product->id,
                    'product_id' => $product->product_id,
                    'description' => $product->description,
                    'category_id' => $product->category_id,
                    'brand_id' => $product->brand_id,
                    'model_id' => $product->model_id,
                    'size' => $product->size,
                    'color' => $product->color,
                    'brand_segment' => $product->brand_segment,
                    'power' => $product->power,
                    'sign' => $product->sign,
                    'type' => $product->type,
                    'price' => (float) $product->price,
                    'retail_price' => (float) $product->retail_price,
                    'tax' => (float) $product->tax,
                    'stock' => $branchStock ? $branchStock->quantity : 0,
                    'available_quantity' => $branchStock ? $branchStock->available_quantity : 0,
                    'branch_id' => $branch->id,
                    'branch_name' => $branch->name,
                ];
            });

            return response()->json([
                'success' => true,
                'products' => $productsArray,
                'count' => $productsArray->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product by ID
     */
    public function getProductById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'branch_id' => 'required|exists:branches,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();

        if (!$user->canAccessBranch($request->branch_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied to this branch'
            ], 403);
        }

        $product = $this->getProductWithStock($request->product_id, $request->branch_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found or out of stock in this branch'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    /**
     * Get product by barcode (same as by ID for now)
     */
    public function getProductByBarcode(Request $request)
    {
        return $this->getProductById($request);
    }

    /**
     * ====================================================
     * LENSES METHODS
     * ====================================================
     */

    /**
     * Get customer eye tests
     */
    public function getEyeTests($customer_id)
    {
        $eyeTests = DB::table('lenses')
            ->where('customer_id', $customer_id)
            ->orderBy('visit_date', 'DESC')
            ->get();

        $formatted = $eyeTests->map(function($test) {
            $doctor = Doctor::where('code', $test->doctor_id)->first();

            return [
                'id' => $test->id,
                'doctor_id' => $test->doctor_id,
                'doctor_name' => $doctor ? $doctor->name : '-',
                'visit_date' => $test->visit_date,
                'sph_right_sign' => $test->sph_right_sign,
                'sph_right_value' => $test->sph_right_value,
                'cyl_right_sign' => $test->cyl_right_sign,
                'cyl_right_value' => $test->cyl_right_value,
                'axis_right' => $test->axis_right,
                'addition_right' => $test->addition_right,
                'sph_left_sign' => $test->sph_left_sign,
                'sph_left_value' => $test->sph_left_value,
                'cyl_left_sign' => $test->cyl_left_sign,
                'cyl_left_value' => $test->cyl_left_value,
                'axis_left' => $test->axis_left,
                'addition_left' => $test->addition_left,
            ];
        });

        return response()->json([
            'success' => true,
            'eye_tests' => $formatted
        ]);
    }

    /**
     * Filter lenses based on criteria
     */


    /**
     * Filter lenses based on criteria (UPDATED)
     */
    public function filterLenses(Request $request)
    {
        $branch_id = $request->branch_id;

        if (!$branch_id) {
            return response()->json([
                'success' => false,
                'message' => 'Branch ID required'
            ], 400);
        }

        // Lenses are NOT branch-specific — show ALL lenses regardless of branch
        $query = glassLense::query();

        // Apply filters
        if ($request->frame_type) {
            $query->where('frame_type', $request->frame_type);
        }
        if ($request->lense_type) {
            $query->where('lense_type', $request->lense_type);
        }
        if ($request->life_style) {
            $query->where('life_style', $request->life_style);
        }
        if ($request->customer_activity) {
            $query->where('customer_activity', $request->customer_activity);
        }
        if ($request->lense_tech) {
            $query->where('lense_tech', $request->lense_tech);
        }
        if ($request->brand) {
            $query->where('lens_brand_id', $request->brand);
        }
        if ($request->production) {
            $query->where('lense_production', $request->production);
        }
        if ($request->index) {
            $query->where('index', $request->index);
        }
        if ($request->description) {
            $query->where('description', 'LIKE', "%{$request->description}%");
        }

        $lenses = $query->orderBy('product_id', 'ASC')->get();

        $formatted = $lenses->map(function ($lens) use ($branch_id) {
            // Stock from LensStockEntry ledger (new system)
            $stockAvailable = \App\LensStockEntry::availableFor($lens->id, $branch_id);

            return [
                'id'           => $lens->id,
                'product_id'   => $lens->product_id,
                'description'  => $lens->description,
                'index'        => $lens->index,
                'frame_type'   => $lens->frame_type,
                'lense_type'   => $lens->lense_type,
                'brand'        => $lens->brand,
                'production'   => $lens->lense_production ?? null,
                'retail_price' => (float) $lens->retail_price,
                'stock'        => $stockAvailable,
            ];
        });

        return response()->json([
            'success' => true,
            'lenses'  => $formatted,
            'count'   => $formatted->count()
        ]);
    }


    /**
     * Add lenses to invoice (UPDATED - with direction check)
     */
    public function addLensesToInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lenses' => 'required|array',
            'lenses.*.product_id' => 'required',
            'lenses.*.direction' => 'required|in:L,R',
            'branch_id' => 'required|exists:branches,id',
            'eye_test_id' => 'nullable|exists:lenses,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();
        $branch_id = $request->branch_id;

        if (!$user->canAccessBranch($branch_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied to this branch'
            ], 403);
        }

        try {
            $draft = session('invoice_draft');
            $addedCount = 0;
            $skippedCount = 0;

            foreach ($request->lenses as $lensData) {
                $lens = $this->getLensWithStock($lensData['product_id'], $branch_id);

                if (!$lens) {
                    $skippedCount++;
                    continue;
                }

                // Check if already exists WITH SAME DIRECTION ✅
                $existingIndex = $this->findLensInDraftWithDirection(
                    $draft['items'],
                    $lensData['product_id'],
                    $lensData['direction']
                );

                if ($existingIndex !== false) {
                    $skippedCount++;
                    continue; // Skip if already added with same direction
                }

                // Add lens with direction
                $newItem = [
                    'id' => uniqid(),
                    'type' => 'lens',
                    'product_id' => $lens['product_id'],
                    'description' => $lens['description'] . ' (' . ($lensData['direction'] === 'R' ? 'Right' : 'Left') . ')',
                    'category_id' => null,
                    'quantity' => 1,
                    'price' => $lens['retail_price'],
                    'net' => $lens['net_price'],
                    'tax' => $lens['tax'],
                    'tax_amount' => 0,
                    'total' => $lens['retail_price'],
                    'stock' => $lens['stock'],
                    'branch_id' => $branch_id,
                    'branch_name' => $lens['branch_name'],
                    'discount_percent' => 0,
                    'direction' => $lensData['direction'],
                    'eye_test_id' => $request->eye_test_id
                ];

                $draft['items'][] = $newItem;
                $addedCount++;
            }

            // Recalculate totals
            $draft['totals'] = $this->calculateDraftTotals($draft);

            session(['invoice_draft' => $draft]);

            $message = "{$addedCount} lens(es) added successfully";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} skipped - already added with same direction)";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'draft' => $draft,
                'added_count' => $addedCount,
                'skipped_count' => $skippedCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding lenses: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Find lens in draft by product_id AND direction (NEW METHOD)
     */
    private function findLensInDraftWithDirection($items, $product_id, $direction)
    {
        foreach ($items as $index => $item) {
            if ($item['type'] === 'lens'
                && $item['product_id'] === $product_id
                && isset($item['direction'])
                && $item['direction'] === $direction) {
                return $index;
            }
        }
        return false;
    }

    /**
     * ====================================================
     * VALIDATION METHODS
     * ====================================================
     */

    /**
     * Validate stock availability for all items
     */
    public function validateStock(Request $request)
    {
        $draft = session('invoice_draft');

        if (empty($draft['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'No items in invoice'
            ], 400);
        }

        $errors = [];

        foreach ($draft['items'] as $item) {
            // Lenses are lab-order items — stock can be 0, skip stock check
            if ($item['type'] === 'lens') {
                continue;
            }

            $currentStock = $this->getProductWithStock(
                $item['product_id'],
                $item['branch_id']
            );

            if (!$currentStock || $currentStock['stock'] < $item['quantity']) {
                $errors[] = [
                    'product_id' => $item['product_id'],
                    'description' => $item['description'],
                    'requested'  => $item['quantity'],
                    'available'  => $currentStock ? $currentStock['stock'] : 0,
                ];
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Stock validation failed',
                'errors' => $errors
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'All items have sufficient stock'
        ]);
    }



    /**
     * ====================================================
     * PART 3: DISCOUNTS
     * ====================================================
     * Add these methods to InvoiceController
     */

    /**
     * ====================================================
     * REGULAR DISCOUNT METHODS
     * ====================================================
     */

    /**
     * Apply regular discount (fixed or percentage)
     */
    public function applyRegularDiscount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $draft = session('invoice_draft');

        if (empty($draft['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot apply discount to empty invoice'
            ], 400);
        }

        // Calculate subtotal
        $subtotal = $draft['totals']['subtotal'];

        // Validate discount value
        if ($request->discount_type === 'percentage') {
            if ($request->discount_value > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'Percentage discount cannot exceed 100%'
                ], 400);
            }
        } else {
            if ($request->discount_value > $subtotal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Discount amount cannot exceed invoice total'
                ], 400);
            }
        }

        // Check if payer discount already applied
        if ($draft['discount']['payer_type']) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot apply regular discount when insurance/cardholder discount is active'
            ], 400);
        }

        // Apply discount
        $draft['discount']['type'] = $request->discount_type;
        $draft['discount']['value'] = $request->discount_value;

        // Recalculate totals
        $draft['totals'] = $this->calculateDraftTotals($draft);

        session(['invoice_draft' => $draft]);

        return response()->json([
            'success' => true,
            'message' => 'Discount applied successfully',
            'draft' => $draft
        ]);
    }

    /**
     * Remove regular discount
     */
    public function removeRegularDiscount(Request $request)
    {
        $draft = session('invoice_draft');

        $draft['discount']['type'] = null;
        $draft['discount']['value'] = 0;

        // Recalculate totals
        $draft['totals'] = $this->calculateDraftTotals($draft);

        session(['invoice_draft' => $draft]);

        return response()->json([
            'success' => true,
            'message' => 'Discount removed successfully',
            'draft' => $draft
        ]);
    }

    /**
     * ====================================================
     * PAYER DISCOUNT (INSURANCE/CARDHOLDER)
     * ====================================================
     */

    /**
     * Get payer companies (insurance or cardholder)
     */
    public function getPayerCompanies($type)
    {
        if (!in_array($type, ['insurance', 'cardholder'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payer type'
            ], 400);
        }

        try {
            if ($type === 'insurance') {
                $companies = InsuranceCompany::with('categories')->get();
            } else {
                $companies = Cardholder::with('categories')->get();
            }

            $formatted = $companies->map(function ($company) use ($type) {
                $categories = $company->categories->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'name' => $cat->category_name,
                        'discount_percent' => (float)$cat->pivot->discount_percent
                    ];
                });

                return [
                    'id' => $company->id,
                    'name' => $type === 'insurance' ? $company->company_name : $company->cardholder_name,
                    'categories' => $categories
                ];
            });

            return response()->json([
                'success' => true,
                'companies' => $formatted
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading companies: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific payer company details
     */
    public function getPayerCompanyDetails($type, $id)
    {
        if (!in_array($type, ['insurance', 'cardholder'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payer type'
            ], 400);
        }

        try {
            if ($type === 'insurance') {
                $company = InsuranceCompany::with('categories')->findOrFail($id);
                $name = $company->company_name;
            } else {
                $company = Cardholder::with('categories')->findOrFail($id);
                $name = $company->cardholder_name;
            }

            $categories = $company->categories->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->category_name,
                    'discount_percent' => (float)$cat->pivot->discount_percent
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $company->id,
                    'name' => $name,
                    'type' => $type,
                    'categories' => $categories
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found'
            ], 404);
        }
    }

    /**
     * Apply payer discount (insurance or cardholder)
     */
    public function applyPayerDiscount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payer_type' => 'required|in:insurance,cardholder',
            'payer_id' => 'required|integer',
            'approval_amount' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $draft = session('invoice_draft');

        if (empty($draft['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot apply discount to empty invoice'
            ], 400);
        }

        // Check if regular discount already applied
        if ($draft['discount']['type']) {
            return response()->json([
                'success' => false,
                'message' => 'Please remove regular discount first'
            ], 400);
        }

        try {
            // Get company and categories
            if ($request->payer_type === 'insurance') {
                $company = InsuranceCompany::with('categories')->findOrFail($request->payer_id);
            } else {
                $company = Cardholder::with('categories')->findOrFail($request->payer_id);
            }

            // Build category discount map
            $categoryDiscounts = [];
            foreach ($company->categories as $cat) {
                $categoryDiscounts[$cat->id] = (float)$cat->pivot->discount_percent;
            }

            // Apply discount to each item based on category
            foreach ($draft['items'] as &$item) {
                if ($item['category_id'] && isset($categoryDiscounts[$item['category_id']])) {
                    $item['discount_percent'] = $categoryDiscounts[$item['category_id']];
                    $item['total'] = $this->calculateItemTotal($item);
                } else {
                    $item['discount_percent'] = 0;
                }
            }
            unset($item);

            // Store payer info
            $draft['discount']['payer_type'] = $request->payer_type;
            $draft['discount']['payer_id'] = $request->payer_id;
            $draft['discount']['type'] = null;
            $draft['discount']['value'] = 0;

            // Store approval amount (for insurance only)
            if ($request->payer_type === 'insurance' && $request->approval_amount) {
                $draft['discount']['approval_amount'] = $request->approval_amount;
            } else {
                $draft['discount']['approval_amount'] = 0;
            }

            // Recalculate totals
            $draft['totals'] = $this->calculateDraftTotals($draft);

            session(['invoice_draft' => $draft]);

            return response()->json([
                'success' => true,
                'message' => ucfirst($request->payer_type) . ' discount applied successfully',
                'draft' => $draft
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error applying discount: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove payer discount
     */
    public function removePayerDiscount(Request $request)
    {
        $draft = session('invoice_draft');

        // Remove discount from all items
        foreach ($draft['items'] as &$item) {
            $item['discount_percent'] = 0;
            $item['total'] = $this->calculateItemTotal($item);
        }
        unset($item);

        // Clear payer info
        $draft['discount']['payer_type'] = null;
        $draft['discount']['payer_id'] = null;
        $draft['discount']['approval_amount'] = 0;

        // Recalculate totals
        $draft['totals'] = $this->calculateDraftTotals($draft);

        session(['invoice_draft' => $draft]);

        return response()->json([
            'success' => true,
            'message' => 'Payer discount removed successfully',
            'draft' => $draft
        ]);
    }

    /**
     * ====================================================
     * HELPER: Calculate item total with discount
     * ====================================================
     */
    private function calculateItemTotalOLD($item)
    {
        $subtotal = $item['price'] * $item['quantity'];

        // Apply discount if exists
        if (isset($item['discount_percent']) && $item['discount_percent'] > 0) {
            $subtotal = $subtotal - ($subtotal * $item['discount_percent'] / 100);
        }

        return round($subtotal, 2);
    }





    /**
     * ====================================================
     * PART 4: PAYMENTS & SAVE INVOICE
     * ====================================================
     * Add these methods to InvoiceController
     */

    /**
     * ====================================================
     * PAYMENT METHODS
     * ====================================================
     */

    /**
     * Calculate and validate payments
     */
    public function calculatePayments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payments' => 'required|array|min:1',
            'payments.*.type' => 'required|in:Cash,Atm,visa,Master Card,Gift Voudire',
            'payments.*.amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $draft = session('invoice_draft');
        $total = $draft['totals']['grand_total'];

        // Calculate total paid
        $totalPaid = array_sum(array_column($request->payments, 'amount'));

        // Validate minimum payment (50%)
        $minPayment = $total / 2;
        if ($totalPaid < $minPayment) {
            return response()->json([
                'success' => false,
                'message' => "Minimum payment is {$minPayment} QAR (50% of total)"
            ], 400);
        }

        // Cap at total
        if ($totalPaid > $total) {
            $totalPaid = $total;
        }

        $remaining = $total - $totalPaid;

        return response()->json([
            'success' => true,
            'total' => round($total, 2),
            'paid' => round($totalPaid, 2),
            'remaining' => round($remaining, 2),
            'message' => 'Payment calculation successful'
        ]);
    }

    /**
     * ====================================================
     * SAVE INVOICE METHODS
     * ====================================================
     */


    /**
     * Save as draft
     */
    public function saveDraft(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pickup_date' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $draft = session('invoice_draft');
        $user = auth()->user();

        if (empty($draft['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot save empty invoice'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $invoice_code = $this->generateInvoiceCode();

            $invoice = new Invoice();
            $invoice->invoice_code = $invoice_code;
            $invoice->customer_id = $draft['customer_id'];
            $invoice->doctor_id = $request->doctor_id;
            $invoice->user_id = $user->id;
            $invoice->branch_id = $draft['branch_id'];
            $invoice->pickup_date = $request->pickup_date;
            $invoice->total = $draft['totals']['grand_total'];
            $invoice->total_before_discount = $draft['totals']['subtotal'];
            $invoice->paied = 0;
            $invoice->remaining = $draft['totals']['grand_total'];
            $invoice->status = 'pending';
            $invoice->save();

            // Create invoice items (no stock reduction)
            foreach ($draft['items'] as $item) {
                $invoiceItem = new InvoiceItems();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->product_id = $item['product_id'];
                $invoiceItem->quantity = $item['quantity'];
                $invoiceItem->price = $item['price'];
                $invoiceItem->net = $item['net'];
                $invoiceItem->tax = $item['tax'];
                $invoiceItem->total = $item['total'];
                $invoiceItem->type = $item['type'];
                $invoiceItem->save();
            }

            DB::commit();

            session()->forget('invoice_draft');

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully',
                'invoice_code' => $invoice_code
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error saving draft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ====================================================
     * HELPER METHODS
     * ====================================================
     */

    /**
     * Generate unique invoice code
     */
    private function generateInvoiceCode()
    {
        do {
            $code = mt_rand(10000000, 99999999);
        } while (Invoice::where('invoice_code', $code)->exists());

        return $code;
    }



    public function getDraft(Request $request)
    {
        $draft = session('invoice_draft');

        if ($draft) {
            return response()->json([
                'success' => true,
                'draft' => $draft
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No draft found'
        ]);
    }




    /* -----------------------------------------------  */

    private function getProductWithStock($product_id, $branch_id)
    {
        // Search by barcode first, fall back to product_id code
        $product = Product::with(['branchStocks' => function($q) use ($branch_id) {
            $q->where('branch_id', $branch_id);
        }])
            ->where(function($q) use ($product_id) {
                $q->where('barcode', $product_id)
                  ->orWhere('product_id', $product_id);
            })
            ->first();

        if (!$product) {
            return null;
        }

        $branchStock = $product->branchStocks->first();
        $branch = Branch::find($branch_id);

        if (!$branchStock || $branchStock->quantity <= 0) {
            return null;
        }

        return [
            'id'          => $product->id,
            'product_id'  => $product->product_id,
            'barcode'     => $product->barcode,
            'description' => $product->description,
            'category_id' => $product->category_id,
            'retail_price'=> (float) $product->retail_price,
            'tax'         => (float) $product->tax,
            'net_price'   => (float) $product->retail_price,
            'stock'       => $branchStock->available_quantity,
            'branch_id'   => $branch_id,
            'branch_name' => $branch->name ?? 'Unknown',
        ];
    }

    /**
     * Get lens with stock (UPDATED)
     */
    private function getLensWithStock($product_id, $branch_id)
    {
        // Lenses use LensStockEntry (not BranchStock). Stock can be 0 — always allow.
        $lens = glassLense::where('product_id', $product_id)->first();

        if (!$lens) {
            return null;
        }

        $branch = Branch::find($branch_id);

        // Stock from the new ledger — informational only, never blocks adding
        $stock = \App\LensStockEntry::availableFor($lens->id, $branch_id);

        return [
            'id'          => $lens->id,
            'product_id'  => $lens->product_id,
            'description' => $lens->description,
            'category_id' => null,
            'retail_price'=> (float) $lens->retail_price,
            'tax'         => 0,
            'net_price'   => (float) $lens->retail_price,
            'stock'       => $stock,   // informational — can be 0
            'branch_id'   => $branch_id,
            'branch_name' => $branch->name ?? 'Unknown',
        ];
    }

    /**
     * Validate stock before saving (UPDATED)
     */
    private function validateStockBeforeSave($draft)
    {
        $branchId = $draft['branch_id'];
        $errors   = [];

        foreach ($draft['items'] as $item) {
            // Lenses are ordered via Lab Orders — stock can legitimately be 0, skip check
            if ($item['type'] === 'lens') {
                continue;
            }

            $product = Product::where('product_id', $item['product_id'])->first();

            if (!$product) {
                $errors[] = "Product not found: {$item['description']}";
                continue;
            }

            $branchStock = BranchStock::where('branch_id', $branchId)
                ->where('stockable_type', 'App\\Product')
                ->where('stockable_id', $product->id)
                ->first();

            if (!$branchStock) {
                $errors[] = "Stock not found for: {$item['description']}";
                continue;
            }

            if ($branchStock->available_quantity < $item['quantity']) {
                $errors[] = "{$item['description']}: Insufficient stock (Available: {$branchStock->available_quantity}, Required: {$item['quantity']})";
            }
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => 'Some items have insufficient stock',
                'errors'  => $errors,
            ];
        }

        return ['success' => true];
    }

    /**
     * Reduce stock for item (UPDATED - with StockMovement tracking)
     */
    private function reduceStock($item, $invoiceId = null)
    {
        $user     = auth()->user();
        $branchId = $item['branch_id'];

        // ── LENSES: use LensStockEntry ledger (OUT entry) ──────────────────
        if ($item['type'] === 'lens') {
            $lens = glassLense::where('product_id', $item['product_id'])->first();

            if (!$lens) {
                // Lens not in catalog — skip stock movement silently
                return null;
            }

            \App\LensStockEntry::create([
                'branch_id'      => $branchId,
                'glass_lense_id' => $lens->id,
                'direction'      => 'out',
                'quantity'       => $item['quantity'],
                'source_type'    => 'invoice',
                'source_id'      => $invoiceId,
                'notes'          => 'Sold - ' . $item['description'],
                'user_id'        => $user->id,
            ]);

            return null; // no StockMovement record needed for lenses
        }

        // ── PRODUCTS: use BranchStock (existing behaviour) ──────────────────
        $product = Product::where('product_id', $item['product_id'])->first();

        if (!$product) {
            throw new \Exception("Product not found: {$item['description']}");
        }

        $branchStock = BranchStock::where('branch_id', $branchId)
            ->where('stockable_type', 'App\\Product')
            ->where('stockable_id', $product->id)
            ->lockForUpdate()
            ->first();

        if (!$branchStock) {
            throw new \Exception("Stock not found for: {$item['description']}");
        }

        if ($branchStock->available_quantity < $item['quantity']) {
            throw new \Exception("Insufficient stock for: {$item['description']}. Available: {$branchStock->available_quantity}, Required: {$item['quantity']}");
        }

        $balanceBefore = $branchStock->quantity;
        $branchStock->reduceQuantity($item['quantity']);
        $branchStock->refresh();

        return StockMovement::create([
            'branch_id'      => $branchId,
            'product_id'     => $item['product_id'],
            'stockable_type' => 'App\\Product',
            'stockable_id'   => $product->id,
            'type'           => 'sale',
            'quantity'       => $item['quantity'],
            'reference_type' => 'App\\Invoice',
            'reference_id'   => $invoiceId,
            'notes'          => "Sale - {$item['description']}",
            'balance_before' => $balanceBefore,
            'balance_after'  => $branchStock->quantity,
            'user_id'        => $user->id,
        ]);
    }

    /**
     * Reserve stock for invoice item (NEW)
     */
    private function reserveStock($item, $branchId, $invoiceId)
    {
        $user = auth()->user();

        try {
            // ── LENSES: NO stock reservation at invoice-save time ─────────────
            // Stock flow for lenses:
            //   IN  → when Lab Order is received (LensPurchaseOrderController::markReceived)
            //   OUT → when customer picks up the invoice (InvoiceItems::deliverItem)
            // Nothing should happen here — reserving at save time would cause a
            // double deduction once the item is delivered.
            if ($item['type'] === 'lens') {
                return;
            }

            // ── PRODUCTS: existing BranchStock reservation ────────────────────
            $product = Product::where('product_id', $item['product_id'])->first();

            if (!$product) {
                throw new \Exception("Product {$item['product_id']} not found");
            }

            $product->reserveInBranch($branchId, $item['quantity']);

            StockMovement::create([
                'branch_id'      => $branchId,
                'product_id'     => $item['product_id'],
                'stockable_type' => 'App\Product',
                'stockable_id'   => $product->id,
                'type'           => 'reserve',
                'quantity'       => $item['quantity'],
                'reference_type' => 'App\Invoice',
                'reference_id'   => $invoiceId,
                'notes'          => "Reserved for invoice #{$invoiceId} - {$item['description']}",
                'user_id'        => $user->id,
            ]);

        } catch (\Exception $e) {
            \Log::error("Failed to reserve stock: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Save invoice (UPDATED - only the stock reduction part)
     */
    /*public function saveInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,code',
            'pickup_date' => 'required|date',
            'payments' => 'required|array|min:1',
            'payments.*.type' => 'required',
            'payments.*.amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $draft = session('invoice_draft');
        $user = auth()->user();

        // Validate items exist
        if (empty($draft['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot save empty invoice'
            ], 400);
        }

        // Validate stock
        $stockValidation = $this->validateStockBeforeSave($draft);
        if (!$stockValidation['success']) {
            return response()->json($stockValidation, 400);
        }

        // Calculate payments
        $totalPaid = array_sum(array_column($request->payments, 'amount'));
        $total = $draft['totals']['grand_total'];

        if ($totalPaid < ($total / 2)) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum payment is 50% of total'
            ], 400);
        }

        if ($totalPaid > $total) {
            $totalPaid = $total;
        }

        $remaining = $total - $totalPaid;

        DB::beginTransaction();
        try {
            // Generate invoice code
            $invoice_code = $this->generateInvoiceCode();

            // Create invoice
            $invoice = new Invoice();
            $invoice->invoice_code = $invoice_code;
            $invoice->customer_id = $draft['customer_id'];
            $invoice->doctor_id = $request->doctor_id;
            $invoice->user_id = $user->id;
            $invoice->branch_id = $draft['branch_id'];
            $invoice->pickup_date = $request->pickup_date;
            $invoice->total = $total;
            $invoice->total_before_discount = $draft['totals']['subtotal_before'];
            $invoice->paied = $totalPaid;
            $invoice->remaining = $remaining;
            $invoice->status = 'pending';

            // Discount info
            if ($draft['discount']['type']) {
                $invoice->discount_type = $draft['discount']['type'];
                $invoice->discount_value = $draft['discount']['value'];
            }

            // Payer info
            if ($draft['discount']['payer_type']) {
                $invoice->insurance_cardholder_type = $draft['discount']['payer_type'];
                $invoice->insurance_cardholder_type_id = $draft['discount']['payer_id'];

                if ($draft['discount']['payer_type'] === 'insurance' && $draft['discount']['approval_amount'] > 0) {
                    $invoice->insurance_approval_amount = $draft['discount']['approval_amount'];
                }
            }

            $invoice->save();

            // Track stock movements for later update
            $stockMovements = [];

            // Create invoice items and reduce stock
            foreach ($draft['items'] as $item) {
                $invoiceItem = new InvoiceItems();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->product_id = $item['product_id'];
                $invoiceItem->quantity = $item['quantity'];
                $invoiceItem->price = $item['price'];
                $invoiceItem->net = $item['net'];
                $invoiceItem->tax = $item['tax'];
                $invoiceItem->total = $item['total'];
                $invoiceItem->type = $item['type'];
                $invoiceItem->direction = $item['direction'] ?? '';
                $invoiceItem->status = 'under_process';

                if (isset($item['discount_percent'])) {
                    $invoiceItem->insurance_cardholder_discount = $item['discount_percent'];
                }

                $invoiceItem->save();

                // Reduce stock and track movement (UPDATED)
                $movement = $this->reduceStock($item, $invoice->id);
                $stockMovements[] = $movement;
            }

            // Update stock movements with invoice reference (if needed)
            foreach ($stockMovements as $movement) {
                if (!$movement->reference_id) {
                    $movement->update(['reference_id' => $invoice->id]);
                }
            }

            // Create payments
            foreach ($request->payments as $paymentData) {
                $payment = new Payments();
                $payment->invoice_id = $invoice->id;
                $payment->type = $paymentData['type'];
                $payment->payed_amount = $paymentData['amount'];
                $payment->currency = 'QAR';
                $payment->exchange_rate = 1;
                $payment->local_payment = $paymentData['amount'];
                $payment->beneficiary = $user->id;
                $payment->is_refund = false;

                // Card details if applicable
                if (isset($paymentData['bank'])) {
                    $payment->bank = $paymentData['bank'];
                }
                if (isset($paymentData['card_number'])) {
                    $payment->card_number = $paymentData['card_number'];
                }
                if (isset($paymentData['expiration_date'])) {
                    $payment->expiration_date = $paymentData['expiration_date'];
                }

                $payment->save();

                // Create cashier transaction
                CashierTransaction::create([
                    'transaction_type' => 'sale',
                    'payment_type' => $paymentData['type'],
                    'amount' => $paymentData['amount'],
                    'currency' => 'QAR',
                    'exchange_rate' => 1,
                    'amount_in_sar' => $paymentData['amount'],
                    'invoice_id' => $invoice->id,
                    'payment_id' => $payment->id,
                    'customer_id' => $invoice->customer_id,
                    'bank' => $paymentData['bank'] ?? null,
                    'card_number' => $paymentData['card_number'] ?? null,
                    'cashier_id' => $user->id,
                    'transaction_date' => now(),
                ]);
            }

            DB::commit();

            // Clear session
            session()->forget('invoice_draft');

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'invoice_code' => $invoice_code,
                'invoice_id' => $invoice->id,
                'redirect' => route('dashboard.invoice.show', $invoice_code)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error saving invoice: ' . $e->getMessage()
            ], 500);
        }
    }*/

    // ====================================================
// REPLACE saveInvoice METHOD in NewInvoiceController
// ====================================================

    public function saveInvoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required|exists:doctors,code',
            'pickup_date' => 'required|date',
            'payments' => 'required|array|min:1',
            'payments.*.type' => 'required',
            'payments.*.amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $draft = session('invoice_draft');
        $user = auth()->user();

        // Validate items exist
        if (empty($draft['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot save empty invoice'
            ], 400);
        }

        // Validate stock
        $stockValidation = $this->validateStockBeforeSave($draft);
        if (!$stockValidation['success']) {
            return response()->json($stockValidation, 400);
        }

        // Calculate payments
        $totalPaid = array_sum(array_column($request->payments, 'amount'));
        $total = $draft['totals']['grand_total'];

        if ($totalPaid < ($total / 2)) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum payment is 50% of total'
            ], 400);
        }

        if ($totalPaid > $total) {
            $totalPaid = $total;
        }

        $remaining = $total - $totalPaid;

        DB::beginTransaction();
        try {
            // Generate invoice code
            $invoice_code = $this->generateInvoiceCode();

            // Create invoice
            $invoice = new Invoice();
            $invoice->invoice_code = $invoice_code;
            $invoice->customer_id = $draft['customer_id'];
            $invoice->doctor_id = $request->doctor_id;
            $invoice->user_id = $user->id;
            $invoice->branch_id = $draft['branch_id'];
            $invoice->pickup_date = $request->pickup_date;
            $invoice->total = $total;
            $invoice->total_before_discount = $draft['totals']['subtotal_before'];
            $invoice->paied = $totalPaid;
            $invoice->remaining = $remaining;
            $invoice->status = 'pending'; // ✅ Status: pending

            // Discount info
            if ($draft['discount']['type']) {
                $invoice->discount_type = $draft['discount']['type'];
                $invoice->discount_value = $draft['discount']['value'];
            }

            // Payer info
            if ($draft['discount']['payer_type']) {
                $invoice->insurance_cardholder_type = $draft['discount']['payer_type'];
                $invoice->insurance_cardholder_type_id = $draft['discount']['payer_id'];

                if ($draft['discount']['payer_type'] === 'insurance' && $draft['discount']['approval_amount'] > 0) {
                    $invoice->insurance_approval_amount = $draft['discount']['approval_amount'];
                }
            }

            $invoice->save();

            // ✅ UPDATED: Reserve stock instead of reducing
            foreach ($draft['items'] as $item) {
                $invoiceItem = new InvoiceItems();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->product_id = $item['product_id'];
                $invoiceItem->quantity = $item['quantity'];
                $invoiceItem->price = $item['price'];
                $invoiceItem->net = $item['net'];
                $invoiceItem->tax = $item['tax'];
                $invoiceItem->total = $item['total'];
                $invoiceItem->type = $item['type'];
                $invoiceItem->direction = $item['direction'] ?? '';
                $invoiceItem->status = 'under_process';

                if (isset($item['discount_percent'])) {
                    $invoiceItem->insurance_cardholder_discount = $item['discount_percent'];
                }

                $invoiceItem->save();

                // ✅ Reserve stock (NOT reduce)
                $this->reserveStock($item, $invoice->branch_id, $invoice->id);
            }

            // Create payments
            foreach ($request->payments as $paymentData) {
                $payment = new Payments();
                $payment->invoice_id = $invoice->id;
                $payment->type = $paymentData['type'];
                $payment->payed_amount = $paymentData['amount'];
                $payment->currency = 'QAR';
                $payment->exchange_rate = 1;
                $payment->local_payment = $paymentData['amount'];
                $payment->beneficiary = $user->id;
                $payment->is_refund = false;

                // Card details if applicable
                if (isset($paymentData['bank'])) {
                    $payment->bank = $paymentData['bank'];
                }
                if (isset($paymentData['card_number'])) {
                    $payment->card_number = $paymentData['card_number'];
                }
                if (isset($paymentData['expiration_date'])) {
                    $payment->expiration_date = $paymentData['expiration_date'];
                }

                $payment->save();

                // Create cashier transaction
                CashierTransaction::create([
                    'transaction_type' => 'sale',
                    'payment_type' => $paymentData['type'],
                    'amount' => $paymentData['amount'],
                    'currency' => 'QAR',
                    'exchange_rate' => 1,
                    'amount_in_sar' => $paymentData['amount'],
                    'invoice_id' => $invoice->id,
                    'payment_id' => $payment->id,
                    'customer_id' => $invoice->customer_id,
                    'bank' => $paymentData['bank'] ?? null,
                    'card_number' => $paymentData['card_number'] ?? null,
                    'cashier_id' => $user->id,
                    'transaction_date' => now(),
                ]);
            }

            //send notify
            NotificationService::invoiceCreated($invoice);

            DB::commit();

            try {
                if (Settings::get('send_whatsapp') == true)
                {
                    $whatsapp = app(InvoiceWhatsAppNotifier::class);
                    $whatsapp->sendInvoiceCreated($invoice);

                    // 2. رسالة لكل دفعة
                    foreach ($request->payments as $paymentData) {
                        $paymentMethod = $this->getPaymentMethodText($paymentData['type']);
                        $whatsapp->sendPaymentReceived($invoice, $paymentData['amount'], $paymentMethod);
                    }
                }

            } catch (\Exception $e) {
                \Log::warning("WhatsApp failed: " . $e->getMessage());
            }

            // Clear session
            session()->forget('invoice_draft');

            \Log::info("Invoice {$invoice_code} created with reservation by user {$user->id}");

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'invoice_code' => $invoice_code,
                'invoice_id' => $invoice->id,
                'redirect' => route('dashboard.invoice.show', $invoice_code)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error("Failed to create invoice: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error saving invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function getPaymentMethodText($type)
    {
        $methods = [
            'Cash' => 'نقدي',
            'visa' => 'فيزا',
            'Atm' => 'بطاقة',
            'Master Card' => 'ماستر كارد',
            'Bank' => 'تحويل بنكي',
            'other' => 'أخرى'
        ];
        return $methods[$type] ?? $type;
    }
}
