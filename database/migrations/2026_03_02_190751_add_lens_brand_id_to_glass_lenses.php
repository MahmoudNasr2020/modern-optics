<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\LensBrand;

class AddLensBrandIdToGlassLenses extends Migration
{
    public function up()
    {
        Schema::table('glass_lenses', function (Blueprint $table) {
            // Add FK column — nullable so existing rows don't break
            $table->unsignedBigInteger('lens_brand_id')->nullable()->after('brand');
            $table->foreign('lens_brand_id')
                ->references('id')
                ->on('lens_brands')
                ->onDelete('set null');
        });

        // ── Auto-migrate existing brand strings to lens_brands ──
        // Creates a brand record for each unique brand string found,
        // then links glass_lenses rows to the new brand id.
        $brands = DB::table('glass_lenses')
            ->select('brand')
            ->distinct()
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->pluck('brand');

        foreach ($brands as $brandName) {
            $slug = str_replace(' ', '-', strtolower(trim($brandName)));
            $brandId = DB::table('lens_brands')->insertGetId([
                'name'       => trim($brandName),
                'slug'       => $slug,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('glass_lenses')
                ->where('brand', $brandName)
                ->update(['lens_brand_id' => $brandId]);
        }
    }

    public function down()
    {
        Schema::table('glass_lenses', function (Blueprint $table) {
            $table->dropForeign(['lens_brand_id']);
            $table->dropColumn('lens_brand_id');
        });
    }
}
