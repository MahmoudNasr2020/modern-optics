-- =====================================================================
-- fix-invoices-and-branch.sql
-- 1. تحويل الفواتير من delivered → pending
-- 2. إضافة فرع رئيسي (Store)
-- =====================================================================

-- ─── 1. تحويل حالة الفواتير لـ pending ───────────────────────────────
UPDATE `invoices`
SET
    `status`       = 'pending',
    `delivered_at` = NULL,
    `delivered_by` = NULL
WHERE `invoice_code` IN (
    89380699, 91222633, 63020942, 31582344, 33682378,
    51162252, 31841725, 82492453, 28841250, 19648038,
    33442048, 34723276, 55195287, 16468858, 81060630,
    38032674, 97013481, 54156242, 89307771, 15565560,
    72060469, 73430503
);

-- تحقق من النتيجة
SELECT
    invoice_code,
    status,
    delivered_at
FROM `invoices`
WHERE `invoice_code` IN (
    89380699, 91222633, 63020942, 31582344, 33682378,
    51162252, 31841725, 82492453, 28841250, 19648038,
    33442048, 34723276, 55195287, 16468858, 81060630,
    38032674, 97013481, 54156242, 89307771, 15565560,
    72060469, 73430503
)
ORDER BY invoice_code;


-- ─── 2. إضافة فرع Store الرئيسي ──────────────────────────────────────

-- تأكد إنه مفيش فرع رئيسي تاني (نعمل reset الأول)
UPDATE `branches` SET `is_main` = 0;

-- أضف الفرع الرئيسي
INSERT INTO `branches`
    (`name`, `name_ar`, `code`, `address`, `is_main`, `is_active`, `created_at`, `updated_at`)
VALUES
    ('Store', 'المخزن الرئيسي', 'STORE-MAIN', 'Main Store', 1, 1, NOW(), NOW());

-- تحقق من الفروع
SELECT id, name, name_ar, code, is_main, is_active
FROM `branches`
ORDER BY is_main DESC, id ASC;
