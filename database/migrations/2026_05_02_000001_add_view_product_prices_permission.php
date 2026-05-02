<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddViewProductPricesPermission extends Migration
{
    public function up()
    {
        // Insert permission if it doesn't already exist
        $exists = DB::table('permissions')
            ->where('name', 'view-product-prices')
            ->where('guard_name', 'web')
            ->exists();

        if (!$exists) {
            $permissionId = DB::table('permissions')->insertGetId([
                'name'         => 'view-product-prices',
                'guard_name'   => 'web',
                'display_name' => 'View Product Prices',
                'description'  => 'Can view product purchase price and retail price',
                'module'       => 'products',
                'is_active'    => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            // Assign to super-admin role automatically
            $superAdmin = DB::table('roles')
                ->where('name', 'super-admin')
                ->where('guard_name', 'web')
                ->first();

            if ($superAdmin) {
                $alreadyAssigned = DB::table('role_has_permissions')
                    ->where('permission_id', $permissionId)
                    ->where('role_id', $superAdmin->id)
                    ->exists();

                if (!$alreadyAssigned) {
                    DB::table('role_has_permissions')->insert([
                        'permission_id' => $permissionId,
                        'role_id'       => $superAdmin->id,
                    ]);
                }
            }
        }

        // Clear spatie permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down()
    {
        $permission = DB::table('permissions')
            ->where('name', 'view-product-prices')
            ->where('guard_name', 'web')
            ->first();

        if ($permission) {
            DB::table('role_has_permissions')->where('permission_id', $permission->id)->delete();
            DB::table('model_has_permissions')->where('permission_id', $permission->id)->delete();
            DB::table('permissions')->where('id', $permission->id)->delete();
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
