<?php
// database/seeds/ExpenseCategoriesSeeder.php

use Illuminate\Database\Seeder;
use App\ExpenseCategory;

class ExpenseCategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Rent', 'name_ar' => 'إيجار', 'type' => 'fixed', 'description' => 'Monthly rent payment'],
            ['name' => 'Salaries', 'name_ar' => 'رواتب', 'type' => 'fixed', 'description' => 'Employee salaries'],
            ['name' => 'Utilities', 'name_ar' => 'كهرباء وماء', 'type' => 'fixed', 'description' => 'Electricity, water, internet'],
            ['name' => 'Maintenance', 'name_ar' => 'صيانة', 'type' => 'variable', 'description' => 'Repairs and maintenance'],
            ['name' => 'Cleaning Supplies', 'name_ar' => 'مواد تنظيف', 'type' => 'variable', 'description' => 'Cleaning materials'],
            ['name' => 'Marketing', 'name_ar' => 'تسويق', 'type' => 'variable', 'description' => 'Marketing and advertising'],
            ['name' => 'Transportation', 'name_ar' => 'مواصلات', 'type' => 'variable', 'description' => 'Fuel and transportation'],
            ['name' => 'Office Supplies', 'name_ar' => 'أدوات مكتبية', 'type' => 'variable', 'description' => 'Office supplies and stationery'],
            ['name' => 'Petty Cash', 'name_ar' => 'نثريات', 'type' => 'variable', 'description' => 'Small miscellaneous expenses'],
            ['name' => 'Other', 'name_ar' => 'أخرى', 'type' => 'variable', 'description' => 'Other expenses'],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }
    }
}
