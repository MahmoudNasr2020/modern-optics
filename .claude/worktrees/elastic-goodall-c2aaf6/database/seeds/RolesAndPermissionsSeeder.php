<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        echo "🚀 Starting Roles and Permissions Seeder...\n\n";

        // ========================================
        // CUSTOMERS PERMISSIONS
        // ========================================
        echo "📦 Creating Customers Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-customers'], ['display_name' => 'View Customers', 'description' => 'Can view customers', 'module' => 'customers']);
        Permission::firstOrCreate(['name' => 'create-customers'], ['display_name' => 'Create Customer', 'description' => 'Can create new customers', 'module' => 'customers']);
        Permission::firstOrCreate(['name' => 'edit-customers'], ['display_name' => 'Edit Customer', 'description' => 'Can edit customer details', 'module' => 'customers']);
        Permission::firstOrCreate(['name' => 'delete-customers'], ['display_name' => 'Delete Customer', 'description' => 'Can delete customers', 'module' => 'customers']);

        // ========================================
        // PRODUCTS PERMISSIONS
        // ========================================
        echo "📦 Creating Products Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-products'], ['display_name' => 'View Products', 'description' => 'Can view products', 'module' => 'products']);
        Permission::firstOrCreate(['name' => 'create-products'], ['display_name' => 'Create Product', 'description' => 'Can create new products', 'module' => 'products']);
        Permission::firstOrCreate(['name' => 'edit-products'], ['display_name' => 'Edit Product', 'description' => 'Can edit product details', 'module' => 'products']);
        Permission::firstOrCreate(['name' => 'delete-products'], ['display_name' => 'Delete Product', 'description' => 'Can delete products', 'module' => 'products']);

        // ========================================
        // LENSES PERMISSIONS
        // ========================================
        echo "📦 Creating Lenses Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-lenses'], ['display_name' => 'View Lenses', 'description' => 'Can view lenses', 'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'create-lenses'], ['display_name' => 'Create Lens', 'description' => 'Can create new lenses', 'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'edit-lenses'], ['display_name' => 'Edit Lens', 'description' => 'Can edit lens details', 'module' => 'lenses']);
        Permission::firstOrCreate(['name' => 'delete-lenses'], ['display_name' => 'Delete Lens', 'description' => 'Can delete lenses', 'module' => 'lenses']);

        // ========================================
        // CATEGORIES PERMISSIONS
        // ========================================
        echo "📦 Creating Categories Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-categories'], ['display_name' => 'View Categories', 'description' => 'Can view categories', 'module' => 'categories']);
        Permission::firstOrCreate(['name' => 'create-categories'], ['display_name' => 'Create Category', 'description' => 'Can create new categories', 'module' => 'categories']);
        Permission::firstOrCreate(['name' => 'edit-categories'], ['display_name' => 'Edit Category', 'description' => 'Can edit category details', 'module' => 'categories']);
        Permission::firstOrCreate(['name' => 'delete-categories'], ['display_name' => 'Delete Category', 'description' => 'Can delete categories', 'module' => 'categories']);

        // ========================================
        // BRANDS PERMISSIONS
        // ========================================
        echo "📦 Creating Brands Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-brands'], ['display_name' => 'View Brands', 'description' => 'Can view brands', 'module' => 'brands']);
        Permission::firstOrCreate(['name' => 'create-brands'], ['display_name' => 'Create Brand', 'description' => 'Can create new brands', 'module' => 'brands']);
        Permission::firstOrCreate(['name' => 'edit-brands'], ['display_name' => 'Edit Brand', 'description' => 'Can edit brand details', 'module' => 'brands']);
        Permission::firstOrCreate(['name' => 'delete-brands'], ['display_name' => 'Delete Brand', 'description' => 'Can delete brands', 'module' => 'brands']);

        // ========================================
        // MODELS PERMISSIONS
        // ========================================
        echo "📦 Creating Models Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-models'], ['display_name' => 'View Models', 'description' => 'Can view models', 'module' => 'models']);
        Permission::firstOrCreate(['name' => 'create-models'], ['display_name' => 'Create Model', 'description' => 'Can create new models', 'module' => 'models']);
        Permission::firstOrCreate(['name' => 'edit-models'], ['display_name' => 'Edit Model', 'description' => 'Can edit model details', 'module' => 'models']);
        Permission::firstOrCreate(['name' => 'delete-models'], ['display_name' => 'Delete Model', 'description' => 'Can delete models', 'module' => 'models']);

        // ========================================
        // BRANCHES PERMISSIONS
        // ========================================
        echo "📦 Creating Branches Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-branches'], ['display_name' => 'View Branches', 'description' => 'Can view branches list', 'module' => 'branches']);
        Permission::firstOrCreate(['name' => 'create-branches'], ['display_name' => 'Create Branch', 'description' => 'Can create new branches', 'module' => 'branches']);
        Permission::firstOrCreate(['name' => 'edit-branches'], ['display_name' => 'Edit Branch', 'description' => 'Can edit branch details', 'module' => 'branches']);
        Permission::firstOrCreate(['name' => 'delete-branches'], ['display_name' => 'Delete Branch', 'description' => 'Can delete branches', 'module' => 'branches']);
        Permission::firstOrCreate(['name' => 'access-all-branches'], ['display_name' => 'Access All Branches', 'description' => 'Can access data from all branches', 'module' => 'branches']);

        // ========================================
        // STOCK PERMISSIONS
        // ========================================
        echo "📦 Creating Stock Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-stock'], ['display_name' => 'View Stock', 'description' => 'Can view stock levels', 'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'add-stock'], ['display_name' => 'Add Stock', 'description' => 'Can add stock quantities', 'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'edit-stock'], ['display_name' => 'Edit Stock', 'description' => 'Can edit stock details', 'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'delete-stock'], ['display_name' => 'Delete Stock', 'description' => 'Can delete stock entries', 'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'increase-stock'], ['display_name' => 'Increase Stock', 'description' => 'Can increase stock quantities', 'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'decrease-stock'], ['display_name' => 'Decrease Stock', 'description' => 'Can decrease stock quantities', 'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'adjust-stock'], ['display_name' => 'Adjust Stock', 'description' => 'Can manually adjust stock', 'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'view-stock-movements'], ['display_name' => 'View Stock Movements', 'description' => 'Can view stock movement history', 'module' => 'stock']);
        Permission::firstOrCreate(['name' => 'view-stock-reports'], ['display_name' => 'View Stock Reports', 'description' => 'Can view stock reports', 'module' => 'stock']);

        // ========================================
        // STOCK TRANSFERS PERMISSIONS
        // ========================================
        echo "📦 Creating Stock Transfers Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-transfers'], ['display_name' => 'View Transfers', 'description' => 'Can view transfer requests', 'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'create-transfers'], ['display_name' => 'Create Transfer', 'description' => 'Can create transfer requests', 'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'delete-transfers'], ['display_name' => 'Delete Transfer', 'description' => 'Can delete transfer requests', 'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'approve-transfers'], ['display_name' => 'Approve Transfer', 'description' => 'Can approve transfer requests', 'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'ship-transfers'], ['display_name' => 'Ship Transfer', 'description' => 'Can mark transfers as shipped', 'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'receive-transfers'], ['display_name' => 'Receive Transfer', 'description' => 'Can receive transfers', 'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'cancel-transfers'], ['display_name' => 'Cancel Transfer', 'description' => 'Can cancel transfer requests', 'module' => 'transfers']);
        Permission::firstOrCreate(['name' => 'view-transfer-reports'], ['display_name' => 'View Transfer Reports', 'description' => 'Can view transfer reports', 'module' => 'transfers']);

        // ========================================
        // INVOICES PERMISSIONS
        // ========================================
        echo "📦 Creating Invoices Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-invoices'], ['display_name' => 'View Invoices', 'description' => 'Can view invoices', 'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'view-pending-invoices'], ['display_name' => 'View Pending Invoices', 'description' => 'Can view pending invoices', 'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'view-returned-invoices'], ['display_name' => 'View Returned Invoices', 'description' => 'Can view returned invoices', 'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'create-invoices'], ['display_name' => 'Create Invoice', 'description' => 'Can create new invoices', 'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'edit-invoices'], ['display_name' => 'Edit Invoice', 'description' => 'Can edit invoices', 'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'delete-invoices'], ['display_name' => 'Delete Invoice', 'description' => 'Can delete invoices', 'module' => 'invoices']);
        Permission::firstOrCreate(['name' => 'print-invoices'], ['display_name' => 'Print Invoice', 'description' => 'Can print invoices', 'module' => 'invoices']);


        // ========================================
        // PAYMENTS PERMISSIONS
        // ========================================
        Permission::firstOrCreate(['name' => 'add-payments'], [
            'display_name' => 'Add Payment',
            'description' => 'Can add payments to invoices',
            'module' => 'invoices'
        ]);

        Permission::firstOrCreate(['name' => 'delete-payments'], [
            'display_name' => 'Delete Payment',
            'description' => 'Can delete invoice payments',
            'module' => 'invoices'
        ]);

        // ========================================
        // EXPENSES PERMISSIONS
        // ========================================
        echo "📦 Creating Expenses Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-expenses'], ['display_name' => 'View Expenses', 'description' => 'Can view expenses', 'module' => 'expenses']);
        Permission::firstOrCreate(['name' => 'create-expenses'], ['display_name' => 'Create Expense', 'description' => 'Can create new expenses', 'module' => 'expenses']);
        Permission::firstOrCreate(['name' => 'edit-expenses'], ['display_name' => 'Edit Expense', 'description' => 'Can edit expense details', 'module' => 'expenses']);
        Permission::firstOrCreate(['name' => 'delete-expenses'], ['display_name' => 'Delete Expense', 'description' => 'Can delete expenses', 'module' => 'expenses']);

        // ========================================
// EXPENSES CATEGORIES PERMISSIONS
// ========================================
        echo "📦 Creating Expense Categories Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-expense-categories'], [
            'display_name' => 'View Expense Categories',
            'description' => 'Can view expense categories',
            'module' => 'expenses'
        ]);
        Permission::firstOrCreate(['name' => 'create-expense-categories'], [
            'display_name' => 'Create Expense Category',
            'description' => 'Can create new expense categories',
            'module' => 'expenses'
        ]);
        Permission::firstOrCreate(['name' => 'edit-expense-categories'], [
            'display_name' => 'Edit Expense Category',
            'description' => 'Can edit expense categories',
            'module' => 'expenses'
        ]);
        Permission::firstOrCreate(['name' => 'delete-expense-categories'], [
            'display_name' => 'Delete Expense Category',
            'description' => 'Can delete expense categories',
            'module' => 'expenses'
        ]);

        // ========================================
// EXPENSES REPORTS PERMISSIONS
// ========================================
        echo "📦 Creating Expenses Reports Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-expenses-reports'], [
            'display_name' => 'View Expenses Reports',
            'description' => 'Can view expenses reports',
            'module' => 'expenses'
        ]);


// ========================================
// EYE TEST PERMISSIONS
// ========================================
        echo "📦 Creating Eye Tests Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-eye-tests'], [
            'display_name' => 'View Eye Tests',
            'description' => 'Can view eye tests',
            'module' => 'eye-tests'
        ]);
        Permission::firstOrCreate(['name' => 'create-eye-tests'], [
            'display_name' => 'Create Eye Test',
            'description' => 'Can create new eye tests',
            'module' => 'eye-tests'
        ]);
        Permission::firstOrCreate(['name' => 'edit-eye-tests'], [
            'display_name' => 'Edit Eye Test',
            'description' => 'Can edit eye tests',
            'module' => 'eye-tests'
        ]);
        Permission::firstOrCreate(['name' => 'delete-eye-tests'], [
            'display_name' => 'Delete Eye Test',
            'description' => 'Can delete eye tests',
            'module' => 'eye-tests'
        ]);
        // ========================================
        // DAILY CLOSING PERMISSIONS
        // ========================================
        echo "📦 Creating Daily Closing Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-daily-closing'], ['display_name' => 'View Daily Closing', 'description' => 'Can view daily closing records', 'module' => 'daily-closing']);
        Permission::firstOrCreate(['name' => 'perform-daily-closing'], ['display_name' => 'Perform Daily Closing', 'description' => 'Can perform daily closing', 'module' => 'daily-closing']);

        // ========================================
        // HISTORY PERMISSIONS
        // ========================================
        echo "📦 Creating History Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-history'], ['display_name' => 'View History', 'description' => 'Can view system history', 'module' => 'history']);

        // ========================================
        // CUSTOMER STATUS PERMISSIONS
        // ========================================
        echo "📦 Creating Customer Status Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-customer-status'], ['display_name' => 'View Customer Status', 'description' => 'Can view customer status', 'module' => 'customer-status']);

        // ========================================
        // REPORTS PERMISSIONS
        // ========================================
        echo "📦 Creating Reports Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-reports'], ['display_name' => 'View Reports', 'description' => 'Can view reports', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'export-reports'], ['display_name' => 'Export Reports', 'description' => 'Can export reports', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-items-to-arrive-report'], ['display_name' => 'View Items To Arrive Report', 'description' => 'Can view items to arrive report', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-items-delivered-report'], ['display_name' => 'View Items Delivered Report', 'description' => 'Can view items delivered report', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-pending-invoices-report'], ['display_name' => 'View Pending Invoices Report', 'description' => 'Can view pending invoices report', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-returned-invoices-report'], ['display_name' => 'View Returned Invoices Report', 'description' => 'Can view returned invoices report', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-customers-report'], ['display_name' => 'View Customers Report', 'description' => 'Can view customers report', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-sales-transactions-report'], ['display_name' => 'View Sales Transactions Report', 'description' => 'Can view sales transactions report', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-cashier-report'], ['display_name' => 'View Cashier Report', 'description' => 'Can view cashier report', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-sales-summary-report'], ['display_name' => 'View Sales Summary Report', 'description' => 'Can view sales summary report', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-sales-by-seller-report'], ['display_name' => 'View Sales By Seller Report', 'description' => 'Can view sales by seller report', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-seller-sales-summary-report'], ['display_name' => 'View Seller Sales Summary Report', 'description' => 'Can view seller sales summary report', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-employee-salaries-report'], ['display_name' => 'View Employee Salaries Report', 'description' => 'Can view employee salaries report', 'module' => 'reports']);
        Permission::firstOrCreate(['name' => 'view-sales-by-doctor-report'], ['display_name' => 'View Sales By Doctor Report', 'description' => 'Can view sales by doctor report', 'module' => 'reports']);

        // ========================================
        // DOCTORS PERMISSIONS
        // ========================================
        echo "📦 Creating Doctors Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-doctors'], ['display_name' => 'View Doctors', 'description' => 'Can view doctors', 'module' => 'doctors']);
        Permission::firstOrCreate(['name' => 'create-doctors'], ['display_name' => 'Create Doctor', 'description' => 'Can create new doctors', 'module' => 'doctors']);
        Permission::firstOrCreate(['name' => 'edit-doctors'], ['display_name' => 'Edit Doctor', 'description' => 'Can edit doctor details', 'module' => 'doctors']);
        Permission::firstOrCreate(['name' => 'delete-doctors'], ['display_name' => 'Delete Doctor', 'description' => 'Can delete doctors', 'module' => 'doctors']);

        // ========================================
        // INSURANCE COMPANIES PERMISSIONS
        // ========================================
        echo "📦 Creating Insurance Companies Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-insurance-companies'], ['display_name' => 'View Insurance Companies', 'description' => 'Can view insurance companies', 'module' => 'insurance-companies']);
        Permission::firstOrCreate(['name' => 'create-insurance-companies'], ['display_name' => 'Create Insurance Company', 'description' => 'Can create new insurance companies', 'module' => 'insurance-companies']);
        Permission::firstOrCreate(['name' => 'edit-insurance-companies'], ['display_name' => 'Edit Insurance Company', 'description' => 'Can edit insurance company details', 'module' => 'insurance-companies']);
        Permission::firstOrCreate(['name' => 'delete-insurance-companies'], ['display_name' => 'Delete Insurance Company', 'description' => 'Can delete insurance companies', 'module' => 'insurance-companies']);

        // ========================================
        // CARDHOLDERS PERMISSIONS
        // ========================================
        echo "📦 Creating Cardholders Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-cardholders'], ['display_name' => 'View Cardholders', 'description' => 'Can view cardholders', 'module' => 'cardholders']);
        Permission::firstOrCreate(['name' => 'create-cardholders'], ['display_name' => 'Create Cardholder', 'description' => 'Can create new cardholders', 'module' => 'cardholders']);
        Permission::firstOrCreate(['name' => 'edit-cardholders'], ['display_name' => 'Edit Cardholder', 'description' => 'Can edit cardholder details', 'module' => 'cardholders']);
        Permission::firstOrCreate(['name' => 'delete-cardholders'], ['display_name' => 'Delete Cardholder', 'description' => 'Can delete cardholders', 'module' => 'cardholders']);

        // ========================================
        // USERS MANAGEMENT PERMISSIONS
        // ========================================
        echo "📦 Creating Users Management Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-users'], ['display_name' => 'View Users', 'description' => 'Can view users list', 'module' => 'users']);
        Permission::firstOrCreate(['name' => 'create-users'], ['display_name' => 'Create User', 'description' => 'Can create new users', 'module' => 'users']);
        Permission::firstOrCreate(['name' => 'edit-users'], ['display_name' => 'Edit User', 'description' => 'Can edit user details', 'module' => 'users']);
        Permission::firstOrCreate(['name' => 'delete-users'], ['display_name' => 'Delete User', 'description' => 'Can delete users', 'module' => 'users']);
        Permission::firstOrCreate(['name' => 'assign-roles'], ['display_name' => 'Assign Roles', 'description' => 'Can assign roles to users', 'module' => 'users']);

        // ========================================
        // ROLES & PERMISSIONS MANAGEMENT
        // ========================================
        echo "📦 Creating Roles & Permissions Management...\n";
        Permission::firstOrCreate(['name' => 'view-roles'], ['display_name' => 'View Roles', 'description' => 'Can view roles', 'module' => 'roles']);
        Permission::firstOrCreate(['name' => 'create-roles'], ['display_name' => 'Create Role', 'description' => 'Can create new roles', 'module' => 'roles']);
        Permission::firstOrCreate(['name' => 'edit-roles'], ['display_name' => 'Edit Role', 'description' => 'Can edit role details', 'module' => 'roles']);
        Permission::firstOrCreate(['name' => 'delete-roles'], ['display_name' => 'Delete Role', 'description' => 'Can delete roles', 'module' => 'roles']);
        Permission::firstOrCreate(['name' => 'assign-permissions'], ['display_name' => 'Assign Permissions', 'description' => 'Can assign permissions to roles', 'module' => 'roles']);

        // ========================================
        // SETTINGS PERMISSIONS
        // ========================================
        echo "📦 Creating Settings Permissions...\n";
        Permission::firstOrCreate(['name' => 'view-settings'], ['display_name' => 'View Settings', 'description' => 'Can view system settings', 'module' => 'settings']);
        Permission::firstOrCreate(['name' => 'edit-settings'], ['display_name' => 'Edit Settings', 'description' => 'Can edit system settings', 'module' => 'settings']);

        echo "\n✅ All Permissions Created Successfully!\n\n";

        // ========================================
        // CREATE OR UPDATE ROLES
        // ========================================
        echo "🎭 Creating/Updating Roles...\n\n";

        // Super Admin Role - Full Access
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super-admin'],
            [
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with all permissions',
                'is_system' => true,
            ]
        );

        echo "👑 Assigning ALL permissions to Super Admin...\n";
        $superAdmin->syncPermissions(Permission::all());

        // Manager Role - Branch Management
        $manager = Role::firstOrCreate(
            ['name' => 'manager'],
            [
                'display_name' => 'Branch Manager',
                'description' => 'Manages branch operations',
                'is_system' => true,
            ]
        );

        echo "👔 Assigning permissions to Manager...\n";
        $manager->syncPermissions([
            'view-branches', 'view-stock', 'add-stock', 'increase-stock', 'decrease-stock', 'view-stock-movements', 'view-stock-reports',
            'view-transfers', 'create-transfers', 'approve-transfers', 'receive-transfers', 'view-transfer-reports',
            'view-invoices', 'view-pending-invoices', 'view-returned-invoices', 'create-invoices', 'print-invoices',
            'view-customers', 'create-customers', 'edit-customers', 'view-customer-status',
            'view-products', 'view-lenses', 'view-categories', 'view-brands', 'view-models',
            'view-doctors', 'view-insurance-companies', 'view-cardholders',
            'view-expenses', 'create-expenses', 'edit-expenses',
            'view-daily-closing', 'perform-daily-closing',
            'view-reports', 'export-reports', 'view-items-to-arrive-report', 'view-items-delivered-report',
            'view-pending-invoices-report', 'view-returned-invoices-report', 'view-customers-report',
            'view-sales-transactions-report', 'view-cashier-report', 'view-sales-summary-report',
            'view-sales-by-seller-report', 'view-seller-sales-summary-report', 'view-sales-by-doctor-report',
            'view-history',
        ]);

        // Cashier Role - Sales & Invoices
        $cashier = Role::firstOrCreate(
            ['name' => 'cashier'],
            [
                'display_name' => 'Cashier',
                'description' => 'Handles sales and invoices',
                'is_system' => true,
            ]
        );

        echo "💰 Assigning permissions to Cashier...\n";
        $cashier->syncPermissions([
            'view-stock', 'view-invoices', 'create-invoices', 'print-invoices',
            'view-customers', 'create-customers', 'view-customer-status',
            'view-products', 'view-lenses',
            'view-doctors', 'view-insurance-companies', 'view-cardholders',
            'view-cashier-report',
        ]);

        // Stock Keeper Role - Inventory Management
        $stockKeeper = Role::firstOrCreate(
            ['name' => 'stock-keeper'],
            [
                'display_name' => 'Stock Keeper',
                'description' => 'Manages stock and inventory',
            ]
        );

        echo "📦 Assigning permissions to Stock Keeper...\n";
        $stockKeeper->syncPermissions([
            'view-stock', 'add-stock', 'edit-stock', 'increase-stock', 'decrease-stock', 'adjust-stock',
            'view-stock-movements', 'view-stock-reports',
            'view-transfers', 'create-transfers', 'ship-transfers', 'receive-transfers', 'view-transfer-reports',
            'view-products', 'view-lenses', 'view-categories', 'view-brands', 'view-models',
            'view-items-to-arrive-report', 'view-items-delivered-report',
        ]);

        echo "\n✅ All Roles Created/Updated Successfully!\n\n";

        // ========================================
        // CREATE/UPDATE DEFAULT SUPER ADMIN USER
        // ========================================
        echo "👤 Creating/Updating Default Super Admin User...\n";

        $admin = User::firstOrCreate(
            ['email' => 'admin@system.com'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => bcrypt('admin123'),
                'is_active' => true,
            ]
        );

        // ========================================
// ADD NEW PERMISSIONS TO SUPER ADMIN
// ========================================
       /* echo "👑 Adding new permissions to Super Admin...\n";
        $superAdmin->givePermissionTo([
            'view-expense-categories', 'create-expense-categories', 'edit-expense-categories', 'delete-expense-categories',
            'view-eye-tests', 'create-eye-tests', 'edit-eye-tests', 'delete-eye-tests'
        ]);*/


// ========================================
// ADD NEW EXPENSES REPORTS PERMISSION TO SUPER ADMIN
// ========================================
        //$superAdmin->givePermissionTo('view-expenses-reports');

        if (!$admin->hasRole('super-admin')) {
            $admin->assignRole('super-admin');
            echo "✅ Super Admin role assigned to user!\n";
        } else {
            echo "ℹ️  User already has Super Admin role!\n";
        }

        echo "\n";
        echo "╔════════════════════════════════════════════╗\n";
        echo "║  🎉 SEEDER COMPLETED SUCCESSFULLY! 🎉     ║\n";
        echo "╠════════════════════════════════════════════╣\n";
        echo "║  📊 Total Permissions: " . Permission::count() . " permissions     ║\n";
        echo "║  🎭 Total Roles: " . Role::count() . " roles                  ║\n";
        echo "╠════════════════════════════════════════════╣\n";
        echo "║  👤 Super Admin Credentials:               ║\n";
        echo "║  📧 Email: admin@system.com                ║\n";
        echo "║  🔑 Password: admin123                     ║\n";
        echo "╚════════════════════════════════════════════╝\n";
    }
}
