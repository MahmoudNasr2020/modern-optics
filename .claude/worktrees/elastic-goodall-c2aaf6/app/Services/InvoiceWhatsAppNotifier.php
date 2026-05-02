<?php

namespace App\Services;

use App\Facades\Settings;
use App\Invoice;
use App\Customer;
use Illuminate\Support\Facades\Log;

class InvoiceWhatsAppNotifier
{
    protected $whatsappService;

    public function __construct(NotiFireWhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Get system name from settings
     */
    protected function getSystemName()
    {
        return Settings::get('system_name', 'النظام');
    }

    /**
     * Send invoice created notification
     *
     * @param Invoice $invoice
     * @return array
     */
    public function sendInvoiceCreated(Invoice $invoice)
    {
        if (!config('notifire.templates.invoice_created.enabled', true)) {
            return ['success' => false, 'message' => 'Invoice created notifications are disabled'];
        }

        $customer = $invoice->customer;

        if (!$customer || !$customer->mobile_number) {
            Log::warning('Cannot send WhatsApp: Customer or phone missing', [
                'invoice_id' => $invoice->id
            ]);
            return ['success' => false, 'message' => 'Customer phone number not found'];
        }

        $systemName = $this->getSystemName();

        $message = "مرحباً *{$customer->english_name}* 👋\n\n" .
            "تم إنشاء فاتورة جديدة لك من *{$systemName}* 📄\n\n" .
            "📋 *تفاصيل الفاتورة:*\n" .
            "• رقم الفاتورة: *{$invoice->invoice_code}*\n" .
            "• التاريخ: " . $invoice->created_at->format('Y-m-d') . "\n" .
            "• الإجمالي: *" . number_format($invoice->total, 2) . "* ريال\n" .
            "• المدفوع: *" . number_format($invoice->paied, 2) . "* ريال\n" .
            "• المتبقي: *" . number_format($invoice->remaining, 2) . "* ريال\n" .
            "• الحالة: " . $this->getStatusText($invoice->status) . "\n" .
            "• موعد الاستلام: " . date('Y-m-d', strtotime($invoice->pickup_date)) . "\n\n" .
            "يمكنك متابعة حالة الفاتورة من خلال النظام.\n\n" .
            "شكراً لتعاملك معنا 🙏";

        return $this->whatsappService->sendMessage($customer->mobile_number, $message);
    }

    /**
     * Send invoice ready notification
     *
     * @param Invoice $invoice
     * @return array
     */
    public function sendInvoiceReady(Invoice $invoice)
    {
        if (!config('notifire.templates.invoice_ready.enabled', true)) {
            return ['success' => false, 'message' => 'Invoice ready notifications are disabled'];
        }

        $customer = $invoice->customer;

        if (!$customer || !$customer->mobile_number) {
            return ['success' => false, 'message' => 'Customer phone number not found'];
        }

        $systemName = $this->getSystemName();

        $message = "مرحباً *{$customer->english_name}* 👋\n\n" .
            "فاتورتك جاهزة من *{$systemName}*! ✅\n\n" .
            "📋 *معلومات الفاتورة:*\n" .
            "• رقم الفاتورة: *{$invoice->invoice_code}*\n" .
            "• الإجمالي: *" . number_format($invoice->total, 2) . "* ريال\n" .
            "• تاريخ الجاهزية: " . now()->format('Y-m-d H:i') . "\n\n" .
            "يمكنك استلام طلبك الآن 🎉\n\n" .
            "شكراً لثقتكم بنا 💚";

        return $this->whatsappService->sendMessage($customer->mobile_number, $message);
    }

    /**
     * Send payment received notification
     *
     * @param Invoice $invoice
     * @param float $amount
     * @param string $paymentMethod
     * @return array
     */
    public function sendPaymentReceived(Invoice $invoice, $amount, $paymentMethod = 'نقدي')
    {
        if (!config('notifire.templates.payment_received.enabled', true)) {
            return ['success' => false, 'message' => 'Payment notifications are disabled'];
        }

        $customer = $invoice->customer;

        if (!$customer || !$customer->mobile_number) {
            return ['success' => false, 'message' => 'Customer phone number not found'];
        }

        $systemName = $this->getSystemName();

        $message = "مرحباً *{$customer->english_name}* 👋\n\n" .
            "تم استلام دفعتك بنجاح في *{$systemName}*! ✅\n\n" .
            "💰 *تفاصيل الدفعة:*\n" .
            "• رقم الفاتورة: *{$invoice->invoice_code}*\n" .
            "• المبلغ المدفوع: *" . number_format($amount, 2) . "* ريال\n" .
            "• طريقة الدفع: {$paymentMethod}\n" .
            "• التاريخ: " . now()->format('Y-m-d H:i') . "\n" .
            "• المتبقي: *" . number_format($invoice->remaining, 2) . "* ريال\n\n" .
            "شكراً لك 🙏";

        return $this->whatsappService->sendMessage($customer->mobile_number, $message);
    }

    /**
     * Send invoice cancelled notification
     *
     * @param Invoice $invoice
     * @param string $reason
     * @return array
     */
    public function sendInvoiceCancelled(Invoice $invoice, $reason = 'غير محدد')
    {
        if (!config('notifire.templates.invoice_cancelled.enabled', true)) {
            return ['success' => false, 'message' => 'Cancellation notifications are disabled'];
        }

        $customer = $invoice->customer;

        if (!$customer || !$customer->mobile_number) {
            return ['success' => false, 'message' => 'Customer phone number not found'];
        }

        $systemName = $this->getSystemName();

        $message = "مرحباً *{$customer->name}* 👋\n\n" .
            "تم إلغاء الفاتورة رقم *{$invoice->invoice_code}* من *{$systemName}*\n\n" .
            "📋 *التفاصيل:*\n" .
            "• السبب: {$reason}\n" .
            "• التاريخ: " . now()->format('Y-m-d H:i') . "\n\n" .
            "للاستفسار، يرجى التواصل معنا.\n\n" .
            "شكراً لتفهمك 🙏";

        return $this->whatsappService->sendMessage($customer->mobile_number, $message);
    }

    /**
     * Send payment reminder
     *
     * @param Invoice $invoice
     * @return array
     */
    public function sendPaymentReminder(Invoice $invoice)
    {
        if (!config('notifire.templates.payment_reminder.enabled', true)) {
            return ['success' => false, 'message' => 'Payment reminder notifications are disabled'];
        }

        $customer = $invoice->customer;

        if (!$customer || !$customer->mobile_number) {
            return ['success' => false, 'message' => 'Customer phone number not found'];
        }

        $systemName = $this->getSystemName();
        $dueAmount = $invoice->remaining; // المبلغ المتبقي

        $message = "مرحباً *{$customer->name}* 👋\n\n" .
            "تذكير بفاتورة مستحقة من *{$systemName}* 📌\n\n" .
            "📋 *التفاصيل:*\n" .
            "• رقم الفاتورة: *{$invoice->invoice_code}*\n" .
            "• المبلغ المستحق: *" . number_format($dueAmount, 2) . "* ريال\n" .
            "• تاريخ الاستحقاق: " . ($invoice->pickup_date ? date('Y-m-d', strtotime($invoice->pickup_date)) : 'غير محدد') . "\n\n" .
            "نرجو منك سداد المبلغ في أقرب وقت.\n\n" .
            "شكراً لتعاونك 🙏";

        return $this->whatsappService->sendMessage($customer->mobile_number, $message);
    }

    /**
     * Send custom message to customer
     *
     * @param Customer $customer
     * @param string $message
     * @return array
     */
    public function sendCustomMessage(Customer $customer, $message)
    {
        if (!$customer->mobile_number) {
            return ['success' => false, 'message' => 'Customer phone number not found'];
        }

        $systemName = $this->getSystemName();

        // Add system name to custom message
        $fullMessage = "*{$systemName}* 📢\n\n" . $message;

        return $this->whatsappService->sendMessage($customer->mobile_number, $fullMessage);
    }

    /**
     * Send invoice PDF to customer
     *
     * @param Invoice $invoice
     * @param string $pdfUrl URL to the PDF file
     * @param string|null $caption
     * @return array
     */
    public function sendInvoicePDF(Invoice $invoice, $pdfUrl, $caption = null)
    {
        $customer = $invoice->customer;

        if (!$customer || !$customer->mobile_number) {
            return ['success' => false, 'message' => 'Customer phone number not found'];
        }

        $systemName = $this->getSystemName();

        $defaultCaption = "*{$systemName}*\n\n" .
            "فاتورة رقم *{$invoice->invoice_code}*\n" .
            "العميل: {$customer->name}\n" .
            "الإجمالي: *" . number_format($invoice->total, 2) . "* ريال";

        return $this->whatsappService->sendMedia(
            $customer->mobile_number,
            'pdf',
            $pdfUrl,
            $caption ?? $defaultCaption,
            ['fileName' => "invoice-{$invoice->invoice_code}.pdf"]
        );
    }

    /**
     * Send bulk notification to multiple customers
     *
     * @param array $customerIds Array of customer IDs
     * @param string $message Message content
     * @return array
     */
    public function sendBulkNotification(array $customerIds, $message)
    {
        $customers = Customer::whereIn('id', $customerIds)
            ->whereNotNull('mobile_number')
            ->get();

        $phoneNumbers = $customers->pluck('mobile_number')->toArray();

        if (empty($phoneNumbers)) {
            return ['success' => false, 'message' => 'No valid phone numbers found'];
        }

        $systemName = $this->getSystemName();
        $fullMessage = "*{$systemName}* 📢\n\n" . $message;

        return $this->whatsappService->sendBulkMessage($phoneNumbers, $fullMessage);
    }

    /**
     * Get status text in Arabic
     *
     * @param string $status
     * @return string
     */
    protected function getStatusText($status)
    {
        $statuses = [
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد التجهيز',
            'ready' => 'جاهز',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي',
            'paid' => 'مدفوع',
            'unpaid' => 'غير مدفوع',
            'partial' => 'دفع جزئي',
        ];

        return $statuses[$status] ?? $status;
    }

    /**
     * Check if customer can receive WhatsApp
     *
     * @param Customer $customer
     * @return bool
     */
    public function canSendToCustomer(Customer $customer)
    {
        if (!$customer->mobile_number) {
            return false;
        }

        return $this->whatsappService->validatePhoneNumber($customer->mobile_number);
    }
}
