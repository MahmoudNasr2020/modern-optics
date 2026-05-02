<?php

use App\Facades\Settings;

return [


    // Your NotiFire username
     'username' => env('NOTIFIRE_USERNAME', ''),
    //'username' => Settings::get('whatsapp_username'),

    // Your device ID (from NotiFire dashboard)
    'device_id' => env('NOTIFIRE_DEVICE_ID', ''),
    //'device_id' => Settings::get('whatsapp_device_id'),

    // API Base URL
    'api_url' => env('NOTIFIRE_API_URL', 'https://www.noti-fire.com/api'),
    //'api_url' => Settings::get('whatsapp_url'),

    // Enable/Disable WhatsApp notifications
    'enabled' => env('NOTIFIRE_ENABLED', true),

    // Queue settings
    'queue' => [
        'enabled' => env('NOTIFIRE_QUEUE_ENABLED', true),
        'connection' => env('NOTIFIRE_QUEUE_CONNECTION', 'database'),
        'queue_name' => env('NOTIFIRE_QUEUE_NAME', 'whatsapp'),
    ],

    // Rate limiting
    'rate_limit' => [
        'requests_per_minute' => 60,
        'delay_between_messages' => 2, // seconds
    ],

    // Logging
    'log' => [
        'enabled' => env('NOTIFIRE_LOG_ENABLED', true),
        'channel' => env('NOTIFIRE_LOG_CHANNEL', 'daily'),
    ],

    // Message Templates
    'templates' => [

        // Invoice Created Template
        'invoice_created' => [
            'enabled' => true,
            'message' => "مرحباً *{customer_name}* 👋\n\n" .
                "تم إنشاء فاتورة جديدة لك 📄\n\n" .
                "📋 *تفاصيل الفاتورة:*\n" .
                "• رقم الفاتورة: *{invoice_number}*\n" .
                "• التاريخ: {date}\n" .
                "• الإجمالي: *{total}* ريال\n" .
                "• الحالة: {status}\n\n" .
                "يمكنك متابعة حالة الفاتورة من خلال النظام.\n\n" .
                "شكراً لتعاملك معنا 🙏",
        ],

        // Invoice Ready Template
        'invoice_ready' => [
            'enabled' => true,
            'message' => "مرحباً *{customer_name}* 👋\n\n" .
                "فاتورتك جاهزة! ✅\n\n" .
                "📋 *معلومات الفاتورة:*\n" .
                "• رقم الفاتورة: *{invoice_number}*\n" .
                "• الإجمالي: *{total}* ريال\n" .
                "• تاريخ الجاهزية: {ready_date}\n\n" .
                "يمكنك استلام طلبك الآن 🎉\n\n" .
                "شكراً لثقتكم بنا 💚",
        ],

        // Payment Received Template
        'payment_received' => [
            'enabled' => true,
            'message' => "مرحباً *{customer_name}* 👋\n\n" .
                "تم استلام دفعتك بنجاح! ✅\n\n" .
                "💰 *تفاصيل الدفعة:*\n" .
                "• رقم الفاتورة: *{invoice_number}*\n" .
                "• المبلغ المدفوع: *{amount}* ريال\n" .
                "• طريقة الدفع: {payment_method}\n" .
                "• التاريخ: {payment_date}\n\n" .
                "شكراً لك 🙏",
        ],

        // Invoice Cancelled Template
        'invoice_cancelled' => [
            'enabled' => true,
            'message' => "مرحباً *{customer_name}* 👋\n\n" .
                "تم إلغاء الفاتورة رقم *{invoice_number}*\n\n" .
                "📋 *التفاصيل:*\n" .
                "• السبب: {reason}\n" .
                "• التاريخ: {cancellation_date}\n\n" .
                "للاستفسار، يرجى التواصل معنا.\n\n" .
                "شكراً لتفهمك 🙏",
        ],

        // Reminder Template
        'payment_reminder' => [
            'enabled' => true,
            'message' => "مرحباً *{customer_name}* 👋\n\n" .
                "تذكير بفاتورة مستحقة 📌\n\n" .
                "📋 *التفاصيل:*\n" .
                "• رقم الفاتورة: *{invoice_number}*\n" .
                "• المبلغ المستحق: *{due_amount}* ريال\n" .
                "• تاريخ الاستحقاق: {due_date}\n\n" .
                "نرجو منك سداد المبلغ في أقرب وقت.\n\n" .
                "شكراً لتعاونك 🙏",
        ],

        // Custom Template
        'custom' => [
            'enabled' => true,
            'message' => "{message}",
        ],
    ],

    // Default country code for phone numbers
    'default_country_code' => env('NOTIFIRE_DEFAULT_COUNTRY_CODE', '+974'),

    // Phone number validation
    'phone_validation' => [
        'enabled' => true,
        'min_length' => 10,
        'max_length' => 15,
    ],

];
