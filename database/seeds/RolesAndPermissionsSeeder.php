<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        echo "🚀 Starting Roles and Permissions Seeder...\n\n";

        // ════════════════════════════════════════════
        // 1. CUSTOMERS
        // ════════════════════════════════════════════
        echo "👤 Customers...\n";
        Permission::firstOrCreate(['name' => 'view-customers'],          ['display_name' => 'View Customers',          'description' => 'Can view customers list',              'module' => 'customers']);
        Permission::firstOrCreate(['name' => 'create-customers'],        ['display_name' => 'Create Customer',         'description' => 'Can create new customers',             'module' => 'customers']);
        Permission::firstOrCreate(['name' => 'edit-customers'],          ['display_name' => 'Edit Customer',           'description' => 'Can edit customer details',            'module' => 'customers']);
        Permission::firstOrCreate(['name' => 'delete-customers'],        ['display_name' => 'Delete Customer',         'description' => 'Can delete customers',                 'module' => 'customers']);
        Permission::firstOrCreate(['name' => 'view-customer-status'],    ['display_name' => 'View Customer Status',    'description' => 'Can view customer status page',        'module' => 'customers']);
        Permission::firstOrCreate(['name' => 'view-customer-statement'], ['display_name' => 'View Customer Statement', 'description' => 'Can view customer account statement',  'module' => 'customers']);

        // ════════════════════════════════════════════
        // 2. PRODUCTS
        // ════════════════════════════════════════════
        echo "📦 Products...\n";
        Permission::firstOrCreate(['name' => 'view-products'],        ['display_name' => 'View Products',        'description' => 'Can view products list',                   'module' => 'products']);
        Permission::firstOrCreate(['name' => 'create-products'],      ['display_name' => 'Create Product',       'description' => 'Can create new products',                  'module' => 'products']);
        Permission::firstOrCreate(['name' => 'edit-products'],        ['display_name' => 'Edit Product',         'description' => 'Can edit product details',                 'module' => 'products']);
        Permission::firstOrCreate(['name' => 'delete-products'],      ['display_name' => 'Delete Product',       'description' => 'Can delete products',                      'module' => 'products']);
        Permission::firstOrCreate(['name' => 'view-product-prices'],  ['display_name' => 'View Product Prices',  'description' => 'Can view purchase and retail prices',      'module' => 'products']);
        Permission::firstOrCreate(['name' => 'view-categories'],      ['display_name' => 'View Categories',      'description' => 'Can view product categories',              'module' => 'products']);
        Permission::firstOrCreate(['name' => 'create-categories'],    ['display_name' => 'Create Category',      'description' => 'Can create product categories',            'module' => 'products']);
        Permission::firstOrCreate(['name' => 'edit-categories'],      ['display_name' => 'Edit Category',        'description' => 'Can edit product categories',              'module' => 'products']);
        Permission::firstOrCreate(['name' => 'delete-categories'],    ['display_name' => 'Delete Category',      'description' => 'Can delete product categories',            'module' => 'products']);
        Permission::firstOrCreate(['name' => 'view-brands'],          ['display_name' => 'View Brands',          'description' => 'Can view brands list',                     'module' => 'products']);
        Permission::firstOrCreate(['name' => 'create-brands'],        ['display_name' => 'Create Brand',         'description' => 'Can create new brands',                    'module' => 'products']);
        Permission::firstOrCreate(['name' => 'edit-brands'],          ['display_name' => 'Edit Brand',           'description' => 'Can edit brand details',                   'module' => 'products']);
        Permission::firstOrCreate(['name' => 'delete-brands'],        ['display_name' => 'Delete Brand',         'description' => 'Can delete brands',                        'module' => 'products']);
        Permission::firstOrCreate(['name' => 'view-models'],          ['display_name' => 'View Models',          'description' => 'Can view product models',                  'module' => 'products']);
        Permission::firstOrCreate(['name' => 'create-models'],        ['display_name' => 'Create Model',         'description' => 'Can create product models',                'module' => 'products']);
        Permission::firstOrCreate(['name' => 'edit-models'],          ['display_name' => 'Edit Model',           'description' => 'Can edit product models',                  'module' => 'products']);
        Permission::firstOrCreate(['name' => 'delete-models'],        ['display_name' => 'Delete Model',         'description' => 'Can delete product models',                'module' => 'products']);

        // ════════════════════════════════════════════
        // 3. LENSES
        // ════════════════════════════════════════════
        echo "🔍 Lenses...\n";
        Permission::firstOrCreate(['name' => 'view-lenses'],                  ['display_name' => 'View Lenses',                  'description' => 'Can view lenses list',                     'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'create-lenses'],                ['display_name' => 'Create Lens',                  'description' => 'Can create new lenses',                    'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'edit-lenses'],                  ['display_name' => 'Edit Lens',                    'description' => 'Can edit lens details',                    'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'delete-lenses'],                ['display_name' => 'Delete Lens',                  'description' => 'Can delete lenses',                        'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'view-lens-purchase-orders'],    ['display_name' => 'View Lens Purchase Orders',    'description' => 'Can view lens purchase orders',            'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'create-lens-purchase-orders'],  ['display_name' => 'Create Lens Purchase Order',   'description' => 'Can create lens purchase orders',          'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'edit-lens-purchase-orders'],    ['display_name' => 'Edit Lens Purchase Order',     'description' => 'Can edit lens purchase orders',            'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'delete-lens-purchase-orders'],  ['display_name' => 'Delete Lens Purchase Order',   'description' => 'Can delete lens purchase orders',          'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'view-damaged-lenses'],          ['display_name' => 'View Damaged Lenses',          'description' => 'Can view and manage damaged lenses list',  'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'manage-damaged-lenses'],        ['display_name' => 'Manage Damaged Lenses',        'description' => 'Can mark/recover damaged lenses',          'module' => 'lenses']);

        // ════════════════════════════════════════════
        // 4. EYE TESTS
        // ════════════════════════════════════════════
        echo "👁️  Eye Tests...\n";
        Permission::firstOrCreate(['name' => 'view-eye-tests'],   ['display_name' => 'View Eye Tests',   'description' => 'Can view eye test records',  'module' => 'eye-tests']);
        Permission::firstOrCreate(['name' => 'create-eye-tests'], ['display_name' => 'Create Eye Test',  'description' => 'Can create eye tests',        'module' => 'eye-tests']);
        Permission::firstOrCreate(['name' => 'edit-eye-tests'],   ['display_name' => 'Edit Eye Test',    'description' => 'Can edit eye test records',   'module' => 'eye-tests']);
        Permission::firstOrCreate(['name' => 'delete-eye-tests'], ['display_name' => 'Delete Eye Test',  'description' => 'Can delete eye test records', 'module' => 'eye-tests']);

        // ════════════════════════════════════════════
        // 5. BRANCHES
        // ════════════════════════════════════════════
        echo "🏢 Branches...\n";
        Permission::firstOrCreate(['name' => 'view-branches'],        ['display_name' => 'View Branches',        'description' => 'Can view branches list',          'module' => 'branches']);
        Permission::firstOrCreate(['name' => 'create-branches'],      ['display_name' => 'Create Branch',        'description' => 'Can create new branches',         'module' => 'branches']);
        Permission::firstOrCreate(['name' => 'edit-branches'],        ['display_name' => 'Edit Branch',          'description' => 'Can edit branch details',         'module' => 'branches']);
        Permission::firstOrCreate(['name' => 'delete-branches'],      ['display_name' => 'Delete Branch',        'description' => 'Can delete branches',             'module' => 'branches']);
        Permission::firstOrCreate(['name' => 'access-all-branches'],  ['display_name' => 'Access All Branches',  'description' => 'Can access data from all branches','module' => 'branches']);

        // ════════════════════════════════════════════
        // 6. STOCK
        // ════════════════════════════════════════════
        echo "📦 Stock...\n";
        Permission::firstOrCreate(['name' => 'view-stock'],           ['display_name' => 'View Stock',           'description' => 'Can view stock levels',               'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'add-stock'],            ['display_name' => 'Add Stock',            'description' => 'Can add stock entries',               'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'edit-stock'],           ['display_name' => 'Edit Stock',           'description' => 'Can edit stock details',              'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'delete-stock'],         ['display_name' => 'Delete Stock',         'description' => 'Can delete stock entries',            'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'increase-stock'],       ['display_name' => 'Increase Stock',       'description' => 'Can increase stock quantities',       'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'decrease-stock'],       ['display_name' => 'Decrease Stock',       'description' => 'Can decrease stock quantities',       'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'adjust-stock'],         ['display_name' => 'Adjust Stock',         'description' => 'Can manually adjust stock',           'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'view-stock-movements'], ['display_name' => 'View Stock Movements', 'description' => 'Can view stock movement history',     'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'view-stock-reports'],   ['display_name' => 'View Stock Reports',   'description' => 'Can view and print stock reports',    'module' => 'stock']);

        // ════════════════════════════════════════════
        // 7. STOCK TRANSFERS
        // ════════════════════════════════════════════
        echo "🚛 Stock Transfers...\n";
        Permission::firstOrCreate(['name' => 'view-transfers'],        ['display_name' => 'View Transfers',        'description' => 'Can view transfer requests',           'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'create-transfers'],      ['display_name' => 'Create Transfer',       'description' => 'Can create transfer requests',         'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'delete-transfers'],      ['display_name' => 'Delete Transfer',       'description' => 'Can delete transfer requests',         'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'approve-transfers'],     ['display_name' => 'Approve Transfer',      'description' => 'Can approve transfer requests',        'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'ship-transfers'],        ['display_name' => 'Ship Transfer',         'description' => 'Can mark transfers as shipped',        'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'receive-transfers'],     ['display_name' => 'Receive Transfer',      'description' => 'Can receive transfers',                'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'cancel-transfers'],      ['display_name' => 'Cancel Transfer',       'description' => 'Can cancel transfer requests',         'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'view-transfer-reports'], ['display_name' => 'View Transfer Reports', 'description' => 'Can view and export transfer reports', 'module' => 'transfers']);

        // ════════════════════════════════════════════
        // 8. INVOICES
        // ════════════════════════════════════════════
        echo "🧾 Invoices...\n";
        Permission::firstOrCreate(['name' => 'view-invoices'],           ['display_name' => 'View Invoices',           'description' => 'Can view invoices list',              'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'view-pending-invoices'],   ['display_name' => 'View Pending Invoices',   'description' => 'Can view pending invoices',           'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'view-returned-invoices'],  ['display_name' => 'View Returned Invoices',  'description' => 'Can view returned invoices',          'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'create-invoices'],         ['display_name' => 'Create Invoice',          'description' => 'Can create new invoices',             'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'edit-invoices'],           ['display_name' => 'Edit Invoice',            'description' => 'Can edit invoices',                   'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'delete-invoices'],         ['display_name' => 'Delete Invoice',          'description' => 'Can delete invoices',                 'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'print-invoices'],          ['display_name' => 'Print Invoice',           'description' => 'Can print invoices',                  'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'add-payments'],            ['display_name' => 'Add Payment',             'description' => 'Can add payments to invoices',        'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'delete-payments'],         ['display_name' => 'Delete Payment',          'description' => 'Can delete invoice payments',         'module' => 'invoices']);

        // ════════════════════════════════════════════
        // 9. EXPENSES
        // ════════════════════════════════════════════
        echo "💸 Expenses...\n";
        Permission::firstOrCreate(['name' => 'view-expenses'],              ['display_name' => 'View Expenses',              'description' => 'Can view expenses list',                'module' => 'expenses']);
        Permission::firstOrCreate(['name' => 'create-expenses'],            ['display_name' => 'Create Expense',             'description' => 'Can create new expenses',               'module' => 'expenses']);
        Permission::firstOrCreate(['name' => 'edit-expenses'],              ['display_name' => 'Edit Expense',               'description' => 'Can edit expense details',              'module' => 'expenses']);
        Permission::firstOrCreate(['name' => 'delete-expenses'],            ['display_name' => 'Delete Expense',             'description' => 'Can delete expenses',                   'module' => 'expenses']);
        Permission::firstOrCreate(['name' => 'view-expense-categories'],    ['display_name' => 'View Expense Categories',    'description' => 'Can view expense categories',           'module' => 'expenses']);
        Permission::firstOrCreate(['name' => 'create-expense-categories'],  ['display_name' => 'Create Expense Category',   'description' => 'Can create expense categories',         'module' => 'expenses']);
        Permission::firstOrCreate(['name' => 'edit-expense-categories'],    ['display_name' => 'Edit Expense Category',     'description' => 'Can edit expense categories',           'module' => 'expenses']);
        Permission::firstOrCreate(['name' => 'delete-expense-categories'],  ['display_name' => 'Delete Expense Category',   'description' => 'Can delete expense categories',         'module' => 'expenses']);
        Permission::firstOrCreate(['name' => 'view-expenses-reports'],      ['display_name' => 'View Expenses Reports',     'description' => 'Can view expenses summary reports',     'module' => 'expenses']);

        // ════════════════════════════════════════════
        // 10. DOCTORS
        // ════════════════════════════════════════════
        echo "🩺 Doctors...\n";
        Permission::firstOrCreate(['name' => 'view-doctors'],   ['display_name' => 'View Doctors',   'description' => 'Can view doctors list',   'module' => 'doctors']);
        Permission::firstOrCreate(['name' => 'create-doctors'], ['display_name' => 'Create Doctor',  'description' => 'Can create new doctors',  'module' => 'doctors']);
        Permission::firstOrCreate(['name' => 'edit-doctors'],   ['display_name' => 'Edit Doctor',    'description' => 'Can edit doctor details', 'module' => 'doctors']);
        Permission::firstOrCreate(['name' => 'delete-doctors'], ['display_name' => 'Delete Doctor',  'description' => 'Can delete doctors',      'module' => 'doctors']);

        // ════════════════════════════════════════════
        // 11. INSURANCE COMPANIES
        // ════════════════════════════════════════════
        echo "🏥 Insurance Companies...\n";
        Permission::firstOrCreate(['name' => 'view-insurance-companies'],   ['display_name' => 'View Insurance Companies',  'description' => 'Can view insurance companies',   'module' => 'insurance-companies']);
        Permission::firstOrCreate(['name' => 'create-insurance-companies'], ['display_name' => 'Create Insurance Company',  'description' => 'Can create insurance companies', 'module' => 'insurance-companies']);
        Permission::firstOrCreate(['name' => 'edit-insurance-companies'],   ['display_name' => 'Edit Insurance Company',    'description' => 'Can edit insurance companies',   'module' => 'insurance-companies']);
        Permission::firstOrCreate(['name' => 'delete-insurance-companies'], ['display_name' => 'Delete Insurance Company',  'description' => 'Can delete insurance companies', 'module' => 'insurance-companies']);

        // ════════════════════════════════════════════
        // 12. CARDHOLDERS
        // ════════════════════════════════════════════
        echo "💳 Cardholders...\n";
        Permission::firstOrCreate(['name' => 'view-cardholders'],   ['display_name' => 'View Cardholders',   'description' => 'Can view cardholders list',   'module' => 'cardholders']);
        Permission::firstOrCreate(['name' => 'create-cardholders'], ['display_name' => 'Create Cardholder',  'description' => 'Can create new cardholders',  'module' => 'cardholders']);
        Permission::firstOrCreate(['name' => 'edit-cardholders'],   ['display_name' => 'Edit Cardholder',    'description' => 'Can edit cardholder details', 'module' => 'cardholders']);
        Permission::firstOrCreate(['name' => 'delete-cardholders'], ['display_name' => 'Delete Cardholder',  'description' => 'Can delete cardholders',      'module' => 'cardholders']);

        // ════════════════════════════════════════════
        // 13. SALESMAN
        // ════════════════════════════════════════════
        echo "🤝 Salesman...\n";
        Permission::firstOrCreate(['name' => 'view-salesman'],   ['display_name' => 'View Salesman',   'description' => 'Can view salesman list',          'module' => 'salesman']);
        Permission::firstOrCreate(['name' => 'create-salesman'], ['display_name' => 'Create Salesman', 'description' => 'Can create new salesman records', 'module' => 'salesman']);
        Permission::firstOrCreate(['name' => 'edit-salesman'],   ['display_name' => 'Edit Salesman',   'description' => 'Can edit salesman details',       'module' => 'salesman']);
        Permission::firstOrCreate(['name' => 'delete-salesman'], ['display_name' => 'Delete Salesman', 'description' => 'Can delete salesman records',     'module' => 'salesman']);

        // ════════════════════════════════════════════
        // 14. DAILY CLOSING
        // ════════════════════════════════════════════
        echo "🔒 Daily Closing...\n";
        Permission::firstOrCreate(['name' => 'view-daily-closing'],    ['display_name' => 'View Daily Closing',    'description' => 'Can view daily closing records', 'module' => 'daily-closing']);
        Permission::firstOrCreate(['name' => 'perform-daily-closing'], ['display_name' => 'Perform Daily Closing', 'description' => 'Can perform daily closing',      'module' => 'daily-closing']);

        // ════════════════════════════════════════════
        // 15. HISTORY
        // ════════════════════════════════════════════
        echo "📜 History...\n";
        Permission::firstOrCreate(['name' => 'view-history'], ['display_name' => 'View History', 'description' => 'Can view system history log', 'module' => 'history']);

        // ════════════════════════════════════════════
        // 16. REPORTS
        // ════════════════════════════════════════════
        echo "📊 Reports...\n";
        Permission::firstOrCreate(['name' => 'view-reports'],                      ['display_name' => 'View Reports',                      'description' => 'Can access the reports section',                    'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'export-reports'],                    ['display_name' => 'Export Reports',                    'description' => 'Can export reports to PDF/Excel',                   'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-items-to-arrive-report'],       ['display_name' => 'Items To Arrive Report',            'description' => 'Can view items to arrive report',                   'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-items-delivered-report'],       ['display_name' => 'Items Delivered Report',            'description' => 'Can view delivered items report',                   'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-damaged-lenses-report'],        ['display_name' => 'Damaged Lenses Report',             'description' => 'Can view damaged lenses report',                    'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-delivered-invoices-report'],    ['display_name' => 'Delivered Invoices Report',         'description' => 'Can view delivered invoices report',                'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-pending-invoices-report'],      ['display_name' => 'Pending Invoices Report',           'description' => 'Can view pending invoices report',                  'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-returned-invoices-report'],     ['display_name' => 'Returned Invoices Report',          'description' => 'Can view returned invoices report',                 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-customers-report'],             ['display_name' => 'Customers Report',                  'description' => 'Can view customers report',                         'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-sales-transactions-report'],    ['display_name' => 'Sales Transactions Report',         'description' => 'Can view sales transactions report',                'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-cashier-report'],               ['display_name' => 'Cashier Report',                    'description' => 'Can view cashier report',                           'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-expenses-report'],              ['display_name' => 'Expenses Report',                   'description' => 'Can view expenses report',                          'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-sales-summary-report'],         ['display_name' => 'Sales Summary Report',              'description' => 'Can view sales summary report',                     'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-sales-by-seller-report'],       ['display_name' => 'Sales By Seller Report',            'description' => 'Can view sales by seller report',                   'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-seller-sales-summary-report'],  ['display_name' => 'Seller Sales Summary Report',       'description' => 'Can view seller sales summary report',              'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-employee-salaries-report'],     ['display_name' => 'Employee Salaries Report',          'description' => 'Can view employee salaries report',                 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-sales-by-doctor-report'],       ['display_name' => 'Sales By Doctor Report',            'description' => 'Can view sales by doctor report',                   'module' => 'reports']);

        // ════════════════════════════════════════════
        // 17. USERS MANAGEMENT
        // ════════════════════════════════════════════
        echo "👥 Users...\n";
        Permission::firstOrCreate(['name' => 'view-users'],    ['display_name' => 'View Users',    'description' => 'Can view users list',            'module' => 'users']);
        Permission::firstOrCreate(['name' => 'create-users'],  ['display_name' => 'Create User',   'description' => 'Can create new users',           'module' => 'users']);
        Permission::firstOrCreate(['name' => 'edit-users'],    ['display_name' => 'Edit User',     'description' => 'Can edit user details',          'module' => 'users']);
        Permission::firstOrCreate(['name' => 'delete-users'],  ['display_name' => 'Delete User',   'description' => 'Can delete users',               'module' => 'users']);
        Permission::firstOrCreate(['name' => 'assign-roles'],  ['display_name' => 'Assign Roles',  'description' => 'Can assign roles to users',      'module' => 'users']);

        // ════════════════════════════════════════════
        // 18. ROLES & PERMISSIONS MANAGEMENT
        // ════════════════════════════════════════════
        echo "🔑 Roles & Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-roles'],          ['display_name' => 'View Roles',          'description' => 'Can view roles list',              'module' => 'roles']);
        Permission::firstOrCreate(['name' => 'create-roles'],        ['display_name' => 'Create Role',         'description' => 'Can create new roles',             'module' => 'roles']);
        Permission::firstOrCreate(['name' => 'edit-roles'],          ['display_name' => 'Edit Role',           'description' => 'Can edit role details',            'module' => 'roles']);
        Permission::firstOrCreate(['name' => 'delete-roles'],        ['display_name' => 'Delete Role',         'description' => 'Can delete roles',                 'module' => 'roles']);
        Permission::firstOrCreate(['name' => 'assign-permissions'],  ['display_name' => 'Assign Permissions',  'description' => 'Can assign permissions to roles',  'module' => 'roles']);

        // ════════════════════════════════════════════
        // 19. SETTINGS
        // ════════════════════════════════════════════
        echo "⚙️  Settings...\n";
        Permission::firstOrCreate(['name' => 'view-settings'], ['display_name' => 'View Settings', 'description' => 'Can view system settings', 'module' => 'settings']);
        Permission::firstOrCreate(['name' => 'edit-settings'], ['display_name' => 'Edit Settings', 'description' => 'Can edit system settings', 'module' => 'settings']);

        // ════════════════════════════════════════════
        // 20. AI ASSISTANT  ← جديد
        // ════════════════════════════════════════════
        echo "🤖 AI Assistant...\n";
        Permission::firstOrCreate(['name' => 'view-ai-assistant'], [
            'display_name' => 'Use AI Assistant',
            'description'  => 'Can access and use the AI assistant chat',
            'module'       => 'assistant',
        ]);

        echo "\n✅ All Permissions Created!\n\n";

        // ════════════════════════════════════════════════════════════════
        // ROLES
        // ════════════════════════════════════════════════════════════════
        echo "🎭 Creating / Updating Roles...\n\n";

        // ── SUPER ADMIN ── Full access ────────────────────────────────
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super-admin'],
            ['display_name' => 'Super Administrator', 'description' => 'Full system access with all permissions', 'is_system' => true]
        );
        echo "👑 Super Admin — ALL permissions\n";
        $superAdmin->syncPermissions(Permission::all());

        // ── MANAGER ── Branch manager ─────────────────────────────────
        $manager = Role::firstOrCreate(
            ['name' => 'manager'],
            ['display_name' => 'Branch Manager', 'description' => 'Manages branch operations', 'is_system' => true]
        );
        echo "👔 Manager\n";
        $manager->syncPermissions([
            // Customers
            'view-customers', 'create-customers', 'edit-customers',
            'view-customer-status', 'view-customer-statement',
            // Products
            'view-products', 'view-categories', 'view-brands', 'view-models',
            'view-product-prices',
            // Lenses
            'view-lenses', 'view-lens-purchase-orders',
            'view-damaged-lenses',
            // Eye Tests
            'view-eye-tests', 'create-eye-tests',
            // Branches & Stock
            'view-branches', 'access-all-branches',
            'view-stock', 'add-stock', 'increase-stock', 'decrease-stock',
            'view-stock-movements', 'view-stock-reports',
            // Transfers
            'view-transfers', 'create-transfers', 'approve-transfers',
            'receive-transfers', 'cancel-transfers', 'view-transfer-reports',
            // Invoices
            'view-invoices', 'view-pending-invoices', 'view-returned-invoices',
            'create-invoices', 'edit-invoices', 'print-invoices',
            'add-payments',
            // Expenses
            'view-expenses', 'create-expenses', 'edit-expenses',
            'view-expense-categories',
            // Doctors / Insurance / Cardholders
            'view-doctors', 'view-insurance-companies', 'view-cardholders',
            // Salesman
            'view-salesman',
            // Daily Closing
            'view-daily-closing', 'perform-daily-closing',
            // History
            'view-history',
            // Reports
            'view-reports', 'export-reports',
            'view-items-to-arrive-report', 'view-items-delivered-report',
            'view-delivered-invoices-report', 'view-damaged-lenses-report',
            'view-pending-invoices-report', 'view-returned-invoices-report',
            'view-customers-report', 'view-sales-transactions-report',
            'view-cashier-report', 'view-expenses-report',
            'view-sales-summary-report', 'view-sales-by-seller-report',
            'view-seller-sales-summary-report', 'view-sales-by-doctor-report',
            'view-employee-salaries-report',
            // AI Assistant
            'view-ai-assistant',
        ]);

        // ── CASHIER ── Sales & invoices ───────────────────────────────
        $cashier = Role::firstOrCreate(
            ['name' => 'cashier'],
            ['display_name' => 'Cashier', 'description' => 'Handles sales and invoices', 'is_system' => true]
        );
        echo "💰 Cashier\n";
        $cashier->syncPermissions([
            // Customers
            'view-customers', 'create-customers',
            'view-customer-status', 'view-customer-statement',
            // Products
            'view-products', 'view-lenses', 'view-categories',
            // Eye Tests
            'view-eye-tests', 'create-eye-tests',
            // Stock
            'view-stock',
            // Invoices
            'view-invoices', 'view-pending-invoices',
            'create-invoices', 'print-invoices', 'add-payments',
            // Doctors / Insurance / Cardholders
            'view-doctors', 'view-insurance-companies', 'view-cardholders',
            // Reports
            'view-cashier-report',
        ]);

        // ── STOCK KEEPER ── Inventory management ─────────────────────
        $stockKeeper = Role::firstOrCreate(
            ['name' => 'stock-keeper'],
            ['display_name' => 'Stock Keeper', 'description' => 'Manages stock and inventory']
        );
        echo "📦 Stock Keeper\n";
        $stockKeeper->syncPermissions([
            // Products & Lenses
            'view-products', 'view-lenses', 'view-categories', 'view-brands', 'view-models',
            // Lens POs & Damaged
            'view-lens-purchase-orders', 'create-lens-purchase-orders', 'edit-lens-purchase-orders',
            'view-damaged-lenses', 'manage-damaged-lenses',
            // Stock
            'view-stock', 'add-stock', 'edit-stock', 'increase-stock', 'decrease-stock', 'adjust-stock',
            'view-stock-movements', 'view-stock-reports',
            // Transfers
            'view-transfers', 'create-transfers', 'ship-transfers', 'receive-transfers', 'view-transfer-reports',
            // Reports
            'view-items-to-arrive-report', 'view-items-delivered-report',
            'view-damaged-lenses-report', 'view-transfer-reports',
        ]);

        // ── ACCOUNTANT ── Finance & reports ──────────────────────────
        $accountant = Role::firstOrCreate(
            ['name' => 'accountant'],
            ['display_name' => 'Accountant', 'description' => 'Handles financial records and reports']
        );
        echo "🧮 Accountant\n";
        $accountant->syncPermissions([
            // Customers
            'view-customers', 'view-customer-statement', 'view-customer-status',
            // Products
            'view-products', 'view-product-prices',
            // Invoices
            'view-invoices', 'view-pending-invoices', 'view-returned-invoices', 'print-invoices',
            'add-payments', 'delete-payments',
            // Expenses
            'view-expenses', 'create-expenses', 'edit-expenses',
            'view-expense-categories', 'view-expenses-reports',
            // Daily Closing
            'view-daily-closing', 'perform-daily-closing',
            // Doctors
            'view-doctors', 'view-insurance-companies', 'view-cardholders',
            // Reports
            'view-reports', 'export-reports',
            'view-pending-invoices-report', 'view-returned-invoices-report',
            'view-customers-report', 'view-sales-transactions-report',
            'view-cashier-report', 'view-expenses-report',
            'view-sales-summary-report', 'view-employee-salaries-report',
            'view-delivered-invoices-report',
        ]);

        echo "\n✅ All Roles Created/Updated!\n\n";

        // ════════════════════════════════════════════════════════════════
        // DEFAULT SUPER ADMIN USER
        // ════════════════════════════════════════════════════════════════
        echo "👤 Creating/Updating Super Admin user...\n";
        $admin = User::firstOrCreate(
            ['email' => 'admin@system.com'],
            [
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'password'   => bcrypt('admin123'),
                'is_active'  => true,
            ]
        );

        if (!$admin->hasRole('super-admin')) {
            $admin->assignRole('super-admin');
            echo "✅ Super Admin role assigned!\n";
        } else {
            echo "ℹ️  Already has Super Admin role.\n";
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $totalPerms = Permission::count();
        $totalRoles = Role::count();

        echo "\n";
        echo "╔══════════════════════════════════════════════╗\n";
        echo "║   🎉  SEEDER COMPLETED SUCCESSFULLY!  🎉    ║\n";
        echo "╠══════════════════════════════════════════════╣\n";
        printf("║   📊 Permissions : %-26s║\n", $totalPerms);
        printf("║   🎭 Roles       : %-26s║\n", $totalRoles);
        echo "╠══════════════════════════════════════════════╣\n";
        echo "║   👤 admin@system.com  /  admin123          ║\n";
        echo "╚══════════════════════════════════════════════╝\n";
    }
}
