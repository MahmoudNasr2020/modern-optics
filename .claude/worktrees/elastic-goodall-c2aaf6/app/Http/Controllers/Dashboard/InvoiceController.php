<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\CashierTransaction;
use App\Doctor;
use App\glassLense;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceItems;
use App\Payments;
use App\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    // Post
    public function saveInvoice(Request $request)
    {
        $invoiceItems = $request->only('datas');
        // if product out of stock
         $productIds = collect($invoiceItems['datas'])->pluck('product_id')->toArray();
         /*$zeroStock = Product::whereIn('product_id', $productIds)
            ->where('amount', 0)
            ->exists();
        if ($zeroStock) {
            return response()->json([
                'error' => true,
                'message' => '❌ Some product is out of stock.'
            ]);
        }*/

        $invoiceData = $request->only('customer_id','doctor_id','user_id','pickup_date','total','paied','discount_type','discount_value');

        $payedMin = (float)($invoiceData['total'] /2) ;

        $rules = [
            'pickup_date' => 'required',
            'paied' => "required|numeric|min:$payedMin",
            'invoice_code' => 'unique:invoices',
            'datas' => 'required',
            'doctor_id' => 'required|exists:doctors,code',
        ];

        $messages = [
            'pickup_date.required' => 'Please choose pickup date',
            'paied.required' => "Payed Amount can't be less than $payedMin QR",
            'invoice_code.unique' => 'Added Before',
            'datas.required' => 'Please choose Products',
        ];

        //$request->validate($rules, $messages);

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors()
            ]);
        }

        $invoice_code = mt_rand(10000000, 99999999);

        if($invoiceData['paied'] > $invoiceData['total']){
            $invoiceData['paied'] = $invoiceData['total'];
            $invoiceData['remaining'] = ($invoiceData['total'] - $invoiceData['paied']);
        } else {
            $invoiceData['remaining'] = ($invoiceData['total'] - $invoiceData['paied']);
        }

        $firstDoctorCode = Doctor::first();

        if (is_null($invoiceData['doctor_id']))
            $invoiceData['doctor_id'] = $firstDoctorCode ? $firstDoctorCode->code : 1;


        DB::beginTransaction();
        try {
            //create invoice
            $invoice = new Invoice();

            $invoice->invoice_code = $invoice_code;
            $invoice->customer_id = $invoiceData['customer_id'];
            $invoice->doctor_id = $request->doctor_id;
            $invoice->user_id = auth()->user()->id;
            $invoice->pickup_date = $request->pickup_date;
            $invoice->total = $request->total;
            $invoice->total_before_discount = $request->total_before_discount;
            $invoice->paied = $invoiceData['paied'];
            $invoice->discount_type = $request->discount_type;
            $invoice->discount_value = $request->discount_value;
            $invoice->payment_way = $request->type;
           // $invoice->status = 'delivered';
            $invoice->remaining = $invoiceData['remaining'];


           //if insurance
            if ($request->filled('insurance_cardholder_type')) {
                $invoice->insurance_cardholder_type = $request->insurance_cardholder_type;
                $invoice->insurance_cardholder_type_id = $request->insurance_cardholder_type_id;
                if ($request->insurance_cardholder_type == 'insurance')
                {
                    $invoice->insurance_approval_amount = $request->insurance_approval_amount;
                }
            }


            $invoice->save();

            // save invoice items data
            foreach ($invoiceItems['datas'] as $item) {
                    $invoiceItemsData = [
                        'invoice_id' => $invoice->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'net' => $item['net'],
                        'tax' => $item['tax'],
                        'total' => (float) $item['total'],
                        'type' => trim($item['type'], '"'),
                        'status' => 'under_process',
                        'insurance_cardholder_discount'=>$item['insurance_cardholder_discount'],
                    ];

                     InvoiceItems::create($invoiceItemsData);
                     $this->decreaseStock($item['product_id'], $item['type'], $item['quantity']);
            }

            // save payments data
            $payments = $request->only('payment_methods')['payment_methods'];

            foreach($payments as $payment){
                $paymentRecord  = Payments::create([
                    'invoice_id' => $invoice->id,
                    'bank' => $payment['bank'],
                    'type' => $payment['type'],
                    'card_number' => $payment['card_number'],
                    'expiration_date' => $payment['expiration_date'],
                    'currency' => 'QAR',
                    'payed_amount' => $payment['payed_amount'],
                    'exchange_rate' => 1,
                    'local_payment' => $request->local_payment,
                    'beneficiary' => auth()->user()->id,
                    'is_refund' => false,
                ]);

                CashierTransaction::create([
                    'transaction_type' => 'sale',
                    'payment_type' => $payment['type'],
                    'amount' => $payment['payed_amount'],
                    'currency' => 'QAR',
                    'exchange_rate' => 1,
                    'amount_in_sar' => $payment['payed_amount'],
                    'invoice_id' => $invoice->id,
                    'payment_id' => $paymentRecord->id,
                    'customer_id' => $invoice->customer_id,
                    'bank' => $payment['bank'] ?? null,
                    'card_number' => $payment['card_number'] ?? null,
                    'cashier_id' => auth()->user()->id,
                    'transaction_date' => now(),
                ]);
            }

            DB::commit();
            session_destroy();

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        session()->flash('success', 'Invoice is Created Successfully!');

        $route = route('dashboard.get-all-customers',['search'=> $invoice->customer_id]);
        return response()->json(['route' => $route]);

    }
    // Save Only
    public function saveOnlyInvoice(Request $request)
    {

        $invoiceData = $request->only('customer_id','doctor_id','user_id','pickup_date','total','paied','discount_type','discount_value');

        $payedMin = $invoiceData['total'] / 2;
        $rules = [
            'pickup_date' => 'required',
            'paied' => "required|numeric|min:$payedMin",
            'invoice_code' => 'unique:invoices'
        ];

        $messages = [
            'pickup_date.required' => 'Please choose pickup date',
            'paied.required' => "Payed Amount can\'t be less than $payedMin QR",
            'invoice_code.unique' => 'Added Before'
        ];

        //$request->validate($rules, $messages);
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'messages' => $validator->errors()
            ]);
        }

        $invoice_code = mt_rand(10000000, 99999999);

        if($invoiceData['paied'] > $invoiceData['total']){
            $invoiceData['paied'] = $invoiceData['total'];
            $invoiceData['remaining'] = ($invoiceData['total'] - $invoiceData['paied']);
        }else{
            $invoiceData['remaining'] = ($invoiceData['total'] - $invoiceData['paied']);
        }

        $firstDoctorCode = Doctor::first();

        if (is_null($invoiceData['doctor_id']))
            $invoiceData['doctor_id'] = $firstDoctorCode ? $firstDoctorCode->code : 1;

        $invoiceItems = $request->only('datas');

        try {
            //create invoice
            $invoice = new Invoice();

            $invoice->invoice_code = $invoice_code;
            $invoice->customer_id = $invoiceData['customer_id'];
            $invoice->doctor_id = $request->doctor_id;
            $invoice->user_id = auth()->user()->id;
            $invoice->pickup_date = $request->pickup_date;
            $invoice->total = $request->total;
            $invoice->paied = $invoiceData['paied'];
            $invoice->discount_type = $request->discount_type;
            $invoice->discount_value = $request->discount_value;
            $invoice->payment_way = $request->type;
            $invoice->status = 'pending';
            $invoice->remaining = $invoiceData['remaining'];

            $invoice->save();

           // save invoice items data
           foreach ($invoiceItems['datas'] as $item) {
                $invoiceItemsData = [
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'net' => $item['net'],
                    'tax' => $item['tax'],
                    'total' => (float) $item['total'],
                    'type' => trim($item['type'], '"'),
                ];

                InvoiceItems::create($invoiceItemsData);
            }

            $payments = $request->only('payment_methods')['payment_methods'];

            foreach($payments as $payment){
                Payments::create([
                    'invoice_id' => $invoice->id,
                    'bank' => $payment['bank'],
                    'type' => $payment['type'],
                    'card_number' => $payment['card_number'],
                    'expiration_date' => $payment['expiration_date'],
                    'currency' => 'QAR',
                    'payed_amount' => $payment['payed_amount'],
                    'exchange_rate' => 1,
                    'local_payment' => $request->local_payment,
                ]);
            }

            session_destroy();

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        $message = 'Invoice is Created Successfully!';
        return response()->json($message);

    }

    public function decreaseStock($id, $type, $quantity)
    {
        if ($type == 'product') {
            $product = Product::where('product_id', $id)->first();
            if ($product->amount == 0)
                $message = 'no items found for this product';
            else {
                $product_amount = $product->amount - $quantity;
                $product->update(['amount' => $product_amount]);
            }
        } else {
            $lens = glassLense::where('product_id', $id)->first();
            if ($lens->amount == 0)
                $message = 'no items found for this product';
            else {
                $lens_amount = $lens->amount - $quantity;
                $lens->update(['amount' => $lens_amount]);
            }
        }
    }


    public function deleteSessionInvoices(Request $request)
    {
        session_destroy();
    }

    public function getAllInvoices(Request $request)
    {
      //  return 0;
        $invoices = Invoice::where('status','!=','returned')
            ->where('status','!=','delivered')
            ->with([
            'customer' => function($query) {
                $query->select('customer_id', 'english_name');
            },
            'user' => function($query) {
                $query->select('id', 'first_name');
            },
            'doctor' => function($query) {
                $query->select('id', 'name','code');
            }
        ])
            ->when($request->invoice_code, function ($q, $value) {
                $q->where('invoice_code', 'like', "%{$value}%");
            })
            ->when($request->customer_code, function ($q, $value) {
                $q->where('customer_id', $value);
            })
            ->when($request->customer_name, function ($q, $value) {
                $q->join('customers', 'customers.customer_id', '=', 'invoices.customer_id')
                    ->where('customers.english_name', 'like', "%{$value}%")
                    ->select('invoices.*');
            })

            ->when($request->creation_date, function ($q, $value) {
                $q->whereDate('created_at', $value);
            })
            ->when($request->status, function ($q, $value) {
                $q->where('status', $value);
            })
           ->when($request->remaining_balance == '1', function ($q) {
                $q->where('remaining', '>', 0);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(20)->appends($request->except('page'));

        return view('dashboard.pages.invoices.all-invoice', compact('invoices'));
    }

    public function editInvoice($invoiceCode)
    {
        $invoice = Invoice::with(['customer', 'user', 'doctor', 'invoiceItems'])->where('invoice_code', $invoiceCode)->first();

        if (!$invoice) {
            return redirect()->route('dashboard.all-invoices')->with('error', 'Invoice not found!');
        }

        return view('dashboard.pages.invoices.edit-invoice', compact('invoice'));
    }

    /*public function updateInvoice(Request $request, $invoiceCode)
    {
        $invoice = Invoice::where('invoice_code', $invoiceCode)->first();

        $invoice->status = $request->status;
        $invoice->notes = $request->notes;
        $invoice->tray_number = $request->tray_number;
        $invoice->save();

        session()->flash('success', 'Invoice Updated Successfully!');
        return redirect()->route('dashboard.all-invoices');

    }*/


// في InvoiceController.php

    public function updateInvoice(Request $request, $invoiceCode)
    {
        $invoice = Invoice::where('invoice_code', $invoiceCode)->first();

        if (!$invoice) {
            session()->flash('error', 'Invoice not found!');
            return redirect()->route('dashboard.all-invoices');
        }

        DB::beginTransaction();
        try {
            // ✓ التحقق من الحالة والمتبقي
            if ($request->status == 'delivered' && $invoice->remaining > 0) {
                session()->flash('error', 'Cannot mark invoice as DELIVERED! Remaining amount: ' . number_format($invoice->remaining, 2) . ' QAR. Please collect the payment first.');
                return redirect()->back();
            }

            // ✓ تحديث بيانات الفاتورة الأساسية
            $invoice->update([
                'status' => $request->status,
                'notes' => $request->notes,
                'tray_number' => $request->tray_number,
            ]);

            // ✓ تحديث حالة العناصر (Ready/Delivered)
            if ($request->has('ready_items')) {
                // العناصر اللي اتعلمت Ready
                foreach ($request->ready_items as $itemId) {
                    InvoiceItems::where('id', $itemId)->update([
                        'status' => 'ready'
                    ]);
                }

                // العناصر اللي اتشال منها Ready
                InvoiceItems::where('invoice_id', $invoice->id)
                    ->whereNotIn('id', $request->ready_items)
                    ->whereIn('status', ['ready', null])
                    ->update(['status' => null]);
            } else {
                // لو مفيش حاجة متعلمة Ready → امسح الكل
                InvoiceItems::where('invoice_id', $invoice->id)
                    ->where('status', 'ready')
                    ->update(['status' => null]);
            }

            if ($request->has('delivery_items')) {
                // العناصر اللي اتعلمت Delivered
                foreach ($request->delivery_items as $itemId) {
                    InvoiceItems::where('id', $itemId)->update([
                        'status' => 'delivery',
                        'delivered_at' => now()
                    ]);
                }

                // العناصر اللي اتشال منها Delivery
                InvoiceItems::where('invoice_id', $invoice->id)
                    ->whereNotIn('id', $request->delivery_items)
                    ->where('status', 'delivery')
                    ->update([
                        'status' => null,
                        'delivered_at' => null
                    ]);
            } else {
                // لو مفيش حاجة متعلمة Delivered → امسح الكل
                InvoiceItems::where('invoice_id', $invoice->id)
                    ->where('status', 'delivery')
                    ->update([
                        'status' => null,
                        'delivered_at' => null
                    ]);
            }

            DB::commit();
            session()->flash('success', 'Invoice Updated Successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update invoice: ' . $e->getMessage());
        }

        return redirect()->route('dashboard.all-invoices');
    }

    public function getPendingInvoices(Request $request)
    {

        $invoices = Invoice::leftJoin('users', 'users.id', 'invoices.user_id')
            ->leftJoin('customers', 'customers.customer_id', 'invoices.customer_id')
            ->select(['invoices.*', 'users.first_name as user_name', 'customers.english_name as customer_name'])
            ->where('status', 'delivered')->orderBy('created_at', 'DESC')
            ->get();


        return view('dashboard.pages.invoices.pending', compact('invoices'));

    }

    public function getDeliveredInvoices(Request $request)
    {

        $invoices = Invoice::leftJoin('users', 'users.id', 'invoices.user_id')
            ->leftJoin('customers', 'customers.customer_id', 'invoices.customer_id')
            ->select(['invoices.*', 'users.first_name as user_name', 'customers.english_name as customer_name'])
            ->where('status', 'delivered')
            ->get();

        return view('dashboard.pages.invoices.delivered', compact('invoices'));
    }

    // Get Invoice Details
    public function getInvoiceDetails(Request $request)
    {
        $invoice_code = $request->InvoiceID;

        $invoice = Invoice::where('invoice_code', $invoice_code)->first();
        $invoiceItems =  InvoiceItems::where('invoice_id', $invoice->id)->get();

        $payments = Payments::where('invoice_id',$invoice->id)->with('getBenficiary')->get();

        foreach($invoiceItems as $item) {
            if($item->type == 'product') {
                $item['name'] = $item->getProductItem->description?? '-';
                $item['stock'] = $item->getProductItem->amount ?? '-';
            } else {
                $item['name'] = $item->getLenseItem->description ?? '-';
                $item['stock'] = $item->getLenseItem->amount ?? '-';
            }

        }

        return response()->json(['invoice' => $invoice, 'Invoice_items' => $invoiceItems, 'payments' => $payments]);
    }

    // Post Deliver Pending Invoice
    public function postDeliverInvoice(Request $request)
    {
        $invoice_code = $request->InvoiceID;
        $invoice = Invoice::where('invoice_code', $invoice_code)->first();

        $invoice->status = 'delivered';
        $invoice->notes = $request->invoice_notes;
        $invoice->tray_number = $request->invoice_trayn;
        $invoice->paied = $invoice->paied + $invoice->remaining;
        $invoice->remaining = 0;
        $invoice->save();

        session()->flash('success', 'Invoice Delivered Successfully!');
        return redirect()->route('dashboard.pending-invoices');
    }

    // Post return Pending Invoice
    /*public function postReturnInvoice(Request $request)
    {

        $invoice_code = $request->InvoiceID;
        $invoice = Invoice::where('invoice_code', $invoice_code)->first();

        $invoice->status = 'canceled';
        $invoice->notes = $request->invoice_notes;
        $invoice->return_reason = $request->return_reason;

        // return stock amount to product and lenses
        foreach ($invoice->invoiceItems as $item) {

            if ($item->type === 'product') {

                $product = Product::where('product_id', $item->product_id)->first();

                if ($product) {
                    $product->amount += $item->quantity;
                    $product->save();
                }

            } else {

                $lens = glassLense::where('product_id', $item->product_id)->first();

                if ($lens) {
                    $lens->amount += $item->quantity;
                    $lens->save();
                }
            }
        }


        $invoice->save();

        session()->flash('success', 'Invoice Returned Successfully!');
        return redirect()->route('dashboard.return-invoices');
    }*/

    public function postReturnInvoice(Request $request)
    {
        $invoice_code = $request->InvoiceID;
        $invoice = Invoice::where('invoice_code', $invoice_code)->first();

        DB::beginTransaction();
        try {
            // تحديث حالة الفاتورة
            $invoice->status = 'canceled';
            $invoice->notes = $request->invoice_notes;
            $invoice->return_reason = $request->return_reason;
            $invoice->save();

            // ✓✓✓ استرجاع المدفوعات ✓✓✓
            // جيب كل المدفوعات الأصلية (اللي مش استرجاع)
            $originalPayments = Payments::where('invoice_id', $invoice->id)
                ->where('is_refund', false) // أو where('is_refund', 0)
                ->get();

            foreach ($originalPayments as $payment) {
                // 1. سجل دفعة استرجاع في payments (بالسالب)
                $refundPayment = Payments::create([
                    'invoice_id' => $invoice->id,
                    'type' => $payment->type,
                    'bank' => $payment->bank,
                    'card_number' => $payment->card_number,
                    'expiration_date' => $payment->expiration_date,
                    'currency' => $payment->currency ?? 'QAR',
                    'payed_amount' => -$payment->payed_amount, // ✓ سالب
                    'exchange_rate' => $payment->exchange_rate ?? 1,
                    'local_payment' => -$payment->local_payment, // ✓ سالب
                    'beneficiary' => auth()->user()->id,
                    'is_refund' => true, // ✓ علامة استرجاع
                   // 'refund_of_payment_id' => $payment->id, // ✓ مرتبط بالدفعة الأصلية
                ]);

                // 2. سجل في cashier_transactions (بالسالب)
                CashierTransaction::create([
                    'transaction_type' => 'refund', // ✓ نوع استرجاع
                    'payment_type' => $payment->type,
                    'amount' => -$payment->payed_amount, // موجب في amount
                    'currency' => $payment->currency ?? 'QAR',
                    'exchange_rate' => $payment->exchange_rate ?? 1,
                    'amount_in_sar' => -$payment->payed_amount, // ✓ سالب في amount_in_sar
                    'invoice_id' => $invoice->id,
                    'payment_id' => $refundPayment->id,
                    'customer_id' => $invoice->customer_id,
                    'bank' => $payment->bank,
                    'card_number' => $payment->card_number,
                    'notes' => "استرجاع فاتورة #{$invoice->invoice_code} - {$request->return_reason}",
                    'cashier_id' => auth()->user()->id,
                    'transaction_date' => now(),
                ]);
            }

            // إرجاع المخزون
            foreach ($invoice->invoiceItems as $item) {
                if ($item->type === 'product') {
                    $product = Product::where('product_id', $item->product_id)->first();
                    if ($product) {
                        $product->amount += $item->quantity;
                        $product->save();
                    }
                } else {
                    $lens = glassLense::where('product_id', $item->product_id)->first();
                    if ($lens) {
                        $lens->amount += $item->quantity;
                        $lens->save();
                    }
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        session()->flash('success', 'Invoice Returned Successfully!');
        return redirect()->route('dashboard.return-invoices');
    }

   /* public function getReturnInvoices()
    {
        $invoices = Invoice::leftJoin('users', 'users.id', 'invoices.user_id')
            ->leftJoin('customers', 'customers.customer_id', 'invoices.customer_id')
            ->select(['invoices.*', 'users.first_name as user_name', 'customers.english_name as customer_name'])
            ->whereIn('status',['delivered','pending'])
            ->get();

        return view('dashboard.pages.invoices.return')->with(compact('invoices'));
    }*/

    /*public function getReturnInvoices(Request $request)
    {
        if (!$request->invoice_code && !$request->customer_code) {
            $invoices = collect();
            return view('dashboard.pages.invoices.return', compact('invoices'));
        }

        $query = Invoice::leftJoin('users', 'users.id', 'invoices.user_id')
            ->leftJoin('customers', 'customers.customer_id', 'invoices.customer_id')
            ->select([
                'invoices.*',
                'users.first_name as user_name',
                'customers.english_name as customer_name'
            ])
            ->whereIn('invoices.status', ['delivered', 'pending']);

        if ($request->invoice_code) {
            $query->where('invoices.invoice_code', 'like', '%' . $request->invoice_code . '%');
        }

        if ($request->customer_code) {
            $query->where('invoices.customer_id', $request->customer_code);
        }

        $invoices = $query->get();

        return view('dashboard.pages.invoices.return', compact('invoices'));
    }*/


    public function getReturnInvoices(Request $request)
    {
        if (
            !$request->invoice_code &&
            !$request->customer_code &&
            !$request->product_id
        ) {
            $invoices = collect();
            return view('dashboard.pages.invoices.return', compact('invoices'));
        }

        $query = Invoice::leftJoin('invoice_items', 'invoice_items.invoice_id', 'invoices.id')
            ->leftJoin('users', 'users.id', 'invoices.user_id')
            ->leftJoin('customers', 'customers.customer_id', 'invoices.customer_id')
            ->select([
                'invoices.*',
                'users.first_name as user_name',
                'customers.english_name as customer_name'
            ])
            ->whereIn('invoices.status', ['delivered', 'pending','Under Process','canceled']);
            //->whereNotIn('invoices.status', ['canceled']);


        if ($request->invoice_code) {
            $query->where('invoices.invoice_code', 'like', '%' . $request->invoice_code . '%');
        }

        if ($request->customer_code) {
            $query->where('invoices.customer_id', $request->customer_code);
        }

        if ($request->product_id) {
            $query->where('invoice_items.product_id', $request->product_id);
        }

        $invoices = $query
            ->distinct()
            ->get();

        return view('dashboard.pages.invoices.return', compact('invoices'));
    }



    public function printPendingInvoiceEn(Request $request, $id)
    {
        $invoice = Invoice::where('invoice_code', $id)->first();

        $invoiceItems = InvoiceItems::where('invoice_id', $invoice->id)->get();

        return view('dashboard.pages.invoices.print_invoice_en')->with(compact('invoice', 'invoiceItems', 'id'));
    }

    public function printPendingInvoiceAr(Request $request, $id)
    {
        $invoice = Invoice::where('invoice_code', $id)->first();

        $invoiceItems = InvoiceItems::where('invoice_id', $invoice->id)->get();

        return view('dashboard.pages.invoices.print_invoice_ar')->with(compact('invoice', 'invoiceItems', 'id'));
    }

    public function showInvoice($invoice_code) {
          $invoice = Invoice::where('invoice_code', $invoice_code)->first();
       //  return $invoice->invoiceItems;
        $payments = Payments::where('invoice_id',$invoice->id)->with('getBenficiary')->get();

        return view('dashboard.pages.invoices.show-invoice-details', ['invoice' => $invoice, 'payments' => $payments]);
    }

    // function delete invoice in pending invoices view
    public function deleteInvoice(Request $request,$invoiceCode)
    {
        $invoice = Invoice::where('invoice_code', $invoiceCode)->first();

        $invoice->delete();

        //delete invoice items
        foreach($invoice->invoiceItems as $invoice){
            $invoice->delete();
        }

        session()->flash('success', 'Invoice Deleted Successfully!');
        return redirect()->back();
    }

    // function change item from pending invoice to ready status
    public function changeItemStatusReady(Request $request,$id){
        $invoiceItem = InvoiceItems::find($id);

        if(isset($invoiceItem)){
            $invoiceItem->status =  $request->status;
            $invoiceItem->save();
        }
    }

    // function change item from pending invoice to deliver status
    public function changeItemStatusDeliver(Request $request,$id){
        $invoiceItem = InvoiceItems::find($id);

        if(isset($invoiceItem)){
            $invoiceItem->status =  $request->status;
            $invoiceItem->updated_at = Carbon::now();

            $invoiceItem->save();
        }
    }

    /*public function addPaymentToPendingInvoice(Request $request) {

        $invoice = Invoice::where('invoice_code',$request->InvoiceID)->first();
        $invoicePayment = new Payments();

        $invoicePayment->invoice_id = $invoice->id;
        $invoicePayment->type = $request->payment['type'];;
        $invoicePayment->bank = $request->payment['bank'];;
        $invoicePayment->card_number = $request->payment['card_number'];;
        $invoicePayment->expiration_date = $request->payment['expiration_date'];;
        $invoicePayment->payed_amount = $request->payment['payed_amount'];
        $invoicePayment->beneficiary = auth()->user()->id;

        $invoicePayment->save();

        if($invoice->remaining > 0) {
            $invoice->paied += $request->payment['payed_amount'];
            $invoice->remaining -= $request->payment['payed_amount'];
        }

        $invoice->save();
        $newInvoicePayment = Payments::where('id', $invoicePayment->id)->with('getBenficiary')->first();
        return response()->json($newInvoicePayment);
    }*/

    public function addPaymentToPendingInvoice(Request $request) {

        $invoice = Invoice::where('invoice_code', $request->InvoiceID)->first();

        DB::beginTransaction(); // ✓ جديد
        try {
            // حفظ في payments
            $invoicePayment = new Payments();
            $invoicePayment->invoice_id = $invoice->id;
            $invoicePayment->type = $request->payment['type'];
            $invoicePayment->bank = $request->payment['bank'];
            $invoicePayment->card_number = $request->payment['card_number'];
            $invoicePayment->expiration_date = $request->payment['expiration_date'];
            $invoicePayment->payed_amount = $request->payment['payed_amount'];
            $invoicePayment->currency = 'QAR'; // ✓ جديد
            $invoicePayment->exchange_rate = 1; // ✓ جديد
            $invoicePayment->local_payment = $request->payment['payed_amount']; // ✓ جديد
            $invoicePayment->beneficiary = auth()->user()->id;
            $invoicePayment->is_refund = false; // ✓ جديد
            $invoicePayment->save();

            // ✓✓✓ جديد: حفظ في cashier_transactions ✓✓✓
            CashierTransaction::create([
                'transaction_type' => 'sale',
                'payment_type' => $request->payment['type'],
                'amount' => $request->payment['payed_amount'],
                'currency' => 'QAR',
                'exchange_rate' => 1,
                'amount_in_sar' => $request->payment['payed_amount'],
                'invoice_id' => $invoice->id,
                'payment_id' => $invoicePayment->id,
                'customer_id' => $invoice->customer_id,
                'bank' => $request->payment['bank'] ?? null,
                'card_number' => $request->payment['card_number'] ?? null,
                'cashier_id' => auth()->user()->id,
                'transaction_date' => now(),
            ]);

            if($invoice->remaining > 0) {
                $invoice->paied += $request->payment['payed_amount'];
                $invoice->remaining -= $request->payment['payed_amount'];
            }
            $invoice->save();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $newInvoicePayment = Payments::where('id', $invoicePayment->id)->with('getBenficiary')->first();
        return response()->json($newInvoicePayment);
    }

    /*public function addPaymentToInvoice(Request $request,$invoice_code) {

        $invoice = Invoice::where('invoice_code',$invoice_code)->first();
        $invoicePayment = new Payments();

        $invoicePayment->invoice_id = $invoice->id;
        $invoicePayment->type = $request->payment_type;
        $invoicePayment->bank = $request->bank;
        $invoicePayment->card_number = $request->card_number;
        $invoicePayment->expiration_date = $request->expiration_date;
        $invoicePayment->payed_amount = $request->payed_amount;
        $invoicePayment->beneficiary = auth()->user()->id;

        $invoicePayment->save();

        if($invoice->remaining > 0) {
            $invoice->paied += $request->payed_amount;
            $invoice->remaining -= $request->payed_amount;
        }

        $invoice->save();
        $newInvoicePayment = Payments::where('id', $invoicePayment->id)->with('getBenficiary')->first();
        session()->flash('success', 'Payment Created Successfully!');
        return redirect()->route('dashboard.edit-invoice',$invoice_code);
    }*/

    public function addPaymentToInvoice(Request $request, $invoice_code) {

        $invoice = Invoice::where('invoice_code', $invoice_code)->first();

        DB::beginTransaction(); // ✓ جديد
        try {
            // حفظ في payments
            $invoicePayment = new Payments();
            $invoicePayment->invoice_id = $invoice->id;
            $invoicePayment->type = $request->payment_type;
            $invoicePayment->bank = $request->bank;
            $invoicePayment->card_number = $request->card_number;
            $invoicePayment->expiration_date = $request->expiration_date;
            $invoicePayment->payed_amount = $request->payed_amount;
            $invoicePayment->currency = 'QAR'; // ✓ جديد
            $invoicePayment->exchange_rate = 1; // ✓ جديد
            $invoicePayment->local_payment = $request->payed_amount; // ✓ جديد
            $invoicePayment->beneficiary = auth()->user()->id;
            $invoicePayment->is_refund = false; // ✓ جديد
            $invoicePayment->save();

            // ✓✓✓ جديد: حفظ في cashier_transactions ✓✓✓
            CashierTransaction::create([
                'transaction_type' => 'sale',
                'payment_type' => $request->payment_type,
                'amount' => $request->payed_amount,
                'currency' => 'QAR',
                'exchange_rate' => 1,
                'amount_in_sar' => $request->payed_amount,
                'invoice_id' => $invoice->id,
                'payment_id' => $invoicePayment->id,
                'customer_id' => $invoice->customer_id,
                'bank' => $request->bank ?? null,
                'card_number' => $request->card_number ?? null,
                'cashier_id' => auth()->user()->id,
                'transaction_date' => now(),
            ]);

            // تحديث الفاتورة
            if($invoice->remaining > 0) {
                $invoice->paied += $request->payed_amount;
                $invoice->remaining -= $request->payed_amount;
            }
            $invoice->save();

            DB::commit(); // ✓ جديد

        } catch (\Exception $e) {
            DB::rollBack(); // ✓ جديد
            throw $e;
        }

        $newInvoicePayment = Payments::where('id', $invoicePayment->id)->with('getBenficiary')->first();
        session()->flash('success', 'Payment Created Successfully!');
        return redirect()->route('dashboard.edit-invoice', $invoice_code);
    }

    public function updateStatus(Request $request,$id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update([
            'status'=>$request->status
        ]);
        session()->flash('success', 'The invoice status has been successfully changed!');
        return redirect()->back();
    }

 /*   public function deletePayment($id)
    {
        $payment = Payments::findOrFail($id);

        $invoice = $payment->invoice;

        if ($invoice) {
            $invoice->paied -= $payment->payed_amount;
            $invoice->remaining += $payment->payed_amount;

            $invoice->save();
        }

        $payment->delete();

        session()->flash('success', 'Payment Deleted Successfully!');

        return redirect()->back();
    }*/

    public function deletePayment($id)
    {
        DB::beginTransaction();
        try {
            $payment = Payments::findOrFail($id);
            $invoice = $payment->invoice;

            // ✓ حذف الـ cashier transaction المرتبطة بالـ payment ده
            CashierTransaction::where('payment_id', $payment->id)->delete();

            // تحديث الفاتورة
            if ($invoice) {
                $invoice->paied -= $payment->payed_amount;
                $invoice->remaining += $payment->payed_amount;
                $invoice->save();
            }

            // حذف الـ payment
            $payment->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        session()->flash('success', 'Payment Deleted Successfully!');
        return redirect()->back();
    }


    // app/Http/Controllers/InvoiceController.php

    public function cashierHistory(Request $request)
    {
        $user = auth()->user();

        // Get filter values
        $date = $request->date ?? now()->format('Y-m-d');
        $branchId = $request->branch_id;
        $cashierId = $request->cashier_id;
        $transactionType = $request->transaction_type;
        $paymentType = $request->payment_type;

        // ✅ Determine which branch to filter by
        $filterBranchId = $user->getFilterBranchId($branchId);

        // ✅ Get accessible branches for dropdown
        $branches = $user->getAccessibleBranches();

        // Date range
        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        // ✅ Build query with branch filtering
        $query = CashierTransaction::with(['invoice.branch', 'customer', 'cashier.branch', 'payment'])
            ->whereBetween('transaction_date', [$start, $end]);

        // ✅ Apply branch filter
        if ($filterBranchId) {
            $query->where(function($q) use ($filterBranchId) {
                // Filter by invoice's branch
                $q->whereHas('invoice', function($invoiceQuery) use ($filterBranchId) {
                    $invoiceQuery->where('branch_id', $filterBranchId);
                })
                    // OR filter by cashier's branch (for expenses without invoice)
                    ->orWhereHas('cashier', function($cashierQuery) use ($filterBranchId) {
                        $cashierQuery->where('branch_id', $filterBranchId);
                    });
            });
        } else {
            // Super Admin with "All Branches" - no branch filter
            // Shows all transactions
        }

        // Filter by cashier
        if ($cashierId) {
            // ✅ Check if user can access this cashier
            $cashier = User::find($cashierId);
            if ($cashier && $user->canAccessBranch($cashier->branch_id)) {
                $query->where('cashier_id', $cashierId);
            }
        }

        // Filter by transaction type
        if ($transactionType) {
            $query->where('transaction_type', $transactionType);
        }

        // Filter by payment type
        if ($paymentType) {
            $query->where('payment_type', $paymentType);
        }

        // Get transactions
        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        // ✅ Calculate statistics
        $stats = [
            'total_sales' => $transactions->where('transaction_type', 'sale')->sum('amount'),
            'total_refunds' => abs($transactions->where('transaction_type', 'refund')->sum('amount')),
            'total_expenses' => abs($transactions->where('transaction_type', 'expense')->sum('amount')),
            'net_total' => $transactions->sum('amount'),
            'transactions_count' => $transactions->count(),
            'sales_count' => $transactions->where('transaction_type', 'sale')->count(),
            'refunds_count' => $transactions->where('transaction_type', 'refund')->count(),
            'expenses_count' => $transactions->where('transaction_type', 'expense')->count(),
        ];

        // ✅ Payment type statistics
        $paymentStats = $transactions->groupBy('payment_type')->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount'),
            ];
        });

        // ✅ Get cashiers list (filtered by accessible branches)
        if ($user->canSeeAllBranches()) {
            // Super Admin sees all cashiers
            $cashiers = User::whereHas('cashierTransactions')
                ->orderBy('first_name')
                ->get();
        } else {
            // Regular user sees only their branch cashiers
            $cashiers = User::where('branch_id', $user->branch_id)
                ->whereHas('cashierTransactions')
                ->orderBy('first_name')
                ->get();
        }

        // ✅ Get selected branch info (if any)
        $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;

        return view('dashboard.pages.cashier.history', compact(
            'transactions',
            'stats',
            'paymentStats',
            'cashiers',
            'branches',
            'date',
            'branchId',
            'filterBranchId',
            'selectedBranch',
            'cashierId',
            'transactionType',
            'paymentType'
        ));
    }

    /**
     * ✅ NEW: Cashier Summary Report (by branch)
     */
    public function cashierSummary(Request $request)
    {
        $user = auth()->user();

        $date = $request->date ?? now()->format('Y-m-d');
        $branchId = $request->branch_id;

        $filterBranchId = $user->getFilterBranchId($branchId);
        $branches = $user->getAccessibleBranches();

        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        // Get transactions
        $query = CashierTransaction::with(['cashier', 'invoice.branch'])
            ->whereBetween('transaction_date', [$start, $end]);

        if ($filterBranchId) {
            $query->inBranch($filterBranchId);
        }

        $transactions = $query->get();

        // Group by cashier
        $cashierSummary = $transactions->groupBy('cashier_id')->map(function($cashierTransactions) {
            $cashier = $cashierTransactions->first()->cashier;

            return [
                'cashier' => $cashier,
                'total_sales' => $cashierTransactions->where('transaction_type', 'sale')->sum('amount'),
                'total_refunds' => abs($cashierTransactions->where('transaction_type', 'refund')->sum('amount')),
                'total_expenses' => abs($cashierTransactions->where('transaction_type', 'expense')->sum('amount')),
                'net_total' => $cashierTransactions->sum('amount'),
                'transactions_count' => $cashierTransactions->count(),
            ];
        });

        return view('dashboard.pages.cashier.summary', compact(
            'cashierSummary',
            'branches',
            'date',
            'branchId',
            'filterBranchId'
        ));
    }
}
