<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*public function up()
    {*/
        // ===== INVOICE_ITEMS INDEXES =====
        /*Schema::table('invoice_items', function (Blueprint $table) {
            // Single indexes
            if (!$this->indexExists('invoice_items', 'invoice_items_invoice_id_index')) {
                $table->index('invoice_id', 'invoice_items_invoice_id_index');
            }

            if (!$this->indexExists('invoice_items', 'invoice_items_product_id_index')) {
                $table->index('product_id', 'invoice_items_product_id_index');
            }

            if (!$this->indexExists('invoice_items', 'invoice_items_status_index')) {
                $table->index('status', 'invoice_items_status_index');
            }

            // Composite index for better JOIN performance
            if (!$this->indexExists('invoice_items', 'invoice_items_invoice_product_index')) {
                $table->index(['invoice_id', 'product_id'], 'invoice_items_invoice_product_index');
            }
        });*/

        // ===== PRODUCTS INDEXES =====
      /*  Schema::table('products', function (Blueprint $table) {
            if (!$this->indexExists('products', 'products_category_id_index')) {
                $table->index('category_id', 'products_category_id_index');
            }

            if (!$this->indexExists('products', 'products_product_id_index')) {
                $table->index('product_id', 'products_product_id_index');
            }

            if (!$this->indexExists('products', 'products_branch_id_index')) {
                $table->index('branch_id', 'products_branch_id_index');
            }
        });

        // ===== INVOICES INDEXES =====
        Schema::table('invoices', function (Blueprint $table) {
            if (!$this->indexExists('invoices', 'invoices_branch_id_index')) {
                $table->index('branch_id', 'invoices_branch_id_index');
            }

            if (!$this->indexExists('invoices', 'invoices_customer_id_index')) {
                $table->index('customer_id', 'invoices_customer_id_index');
            }

            if (!$this->indexExists('invoices', 'invoices_status_index')) {
                $table->index('status', 'invoices_status_index');
            }

            if (!$this->indexExists('invoices', 'invoices_created_at_index')) {
                $table->index('created_at', 'invoices_created_at_index');
            }

            // Composite indexes for common queries
            if (!$this->indexExists('invoices', 'invoices_branch_created_index')) {
                $table->index(['branch_id', 'created_at'], 'invoices_branch_created_index');
            }

            if (!$this->indexExists('invoices', 'invoices_branch_status_index')) {
                $table->index(['branch_id', 'status'], 'invoices_branch_status_index');
            }
        });

        // ===== CATEGORIES INDEXES =====
        Schema::table('categories', function (Blueprint $table) {
            if (!$this->indexExists('categories', 'categories_id_index')) {
                $table->index('id', 'categories_id_index');
            }

            if (!$this->indexExists('categories', 'categories_category_name_index')) {
                $table->index('category_name', 'categories_category_name_index');
            }
        });

        // ===== CUSTOMERS INDEXES =====
        Schema::table('customers', function (Blueprint $table) {
            if (!$this->indexExists('customers', 'customers_branch_id_index')) {
                $table->index('branch_id', 'customers_branch_id_index');
            }

            if (!$this->indexExists('customers', 'customers_customer_id_index')) {
                $table->index('customer_id', 'customers_customer_id_index');
            }

            if (!$this->indexExists('customers', 'customers_created_at_index')) {
                $table->index('created_at', 'customers_created_at_index');
            }

            // Composite index
            if (!$this->indexExists('customers', 'customers_branch_created_index')) {
                $table->index(['branch_id', 'created_at'], 'customers_branch_created_index');
            }
        });

        // ===== CASHIER_TRANSACTIONS INDEXES =====
        Schema::table('cashier_transactions', function (Blueprint $table) {
            if (!$this->indexExists('cashier_transactions', 'cashier_transactions_invoice_id_index')) {
                $table->index('invoice_id', 'cashier_transactions_invoice_id_index');
            }

            if (!$this->indexExists('cashier_transactions', 'cashier_transactions_payment_type_index')) {
                $table->index('payment_type', 'cashier_transactions_payment_type_index');
            }
        });

        // ===== DOCTORS INDEXES =====
        Schema::table('doctors', function (Blueprint $table) {
            if (!$this->indexExists('doctors', 'doctors_doctor_code_index')) {
                $table->index('doctor_code', 'doctors_doctor_code_index');
            }

            if (!$this->indexExists('doctors', 'doctors_created_at_index')) {
                $table->index('created_at', 'doctors_created_at_index');
            }
        });
    }*/

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    /*public function down()
    {
        // Drop invoice_items indexes
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropIndex('invoice_items_invoice_id_index');
            $table->dropIndex('invoice_items_product_id_index');
            $table->dropIndex('invoice_items_status_index');
            $table->dropIndex('invoice_items_invoice_product_index');
        });

        // Drop products indexes
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_category_id_index');
            $table->dropIndex('products_product_id_index');
            $table->dropIndex('products_branch_id_index');
        });

        // Drop invoices indexes
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex('invoices_branch_id_index');
            $table->dropIndex('invoices_customer_id_index');
            $table->dropIndex('invoices_status_index');
            $table->dropIndex('invoices_created_at_index');
            $table->dropIndex('invoices_branch_created_index');
            $table->dropIndex('invoices_branch_status_index');
        });

        // Drop categories indexes
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_id_index');
            $table->dropIndex('categories_category_name_index');
        });

        // Drop customers indexes
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_branch_id_index');
            $table->dropIndex('customers_customer_id_index');
            $table->dropIndex('customers_created_at_index');
            $table->dropIndex('customers_branch_created_index');
        });

        // Drop cashier_transactions indexes
        Schema::table('cashier_transactions', function (Blueprint $table) {
            $table->dropIndex('cashier_transactions_invoice_id_index');
            $table->dropIndex('cashier_transactions_payment_type_index');
        });

        // Drop doctors indexes
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropIndex('doctors_doctor_code_index');
            $table->dropIndex('doctors_created_at_index');
        });
    }*/

    /**
     * Check if index exists
     *
     * @param string $table
     * @param string $index
     * @return bool
     */
    /*protected function indexExists($table, $index)
    {
        $conn = Schema::getConnection();
        $dbSchemaManager = $conn->getDoctrineSchemaManager();
        $doctrineTable = $dbSchemaManager->listTableDetails($table);
        return $doctrineTable->hasIndex($index);
    }*/
}
