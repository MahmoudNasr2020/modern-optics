<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LensStockEntry extends Model
{
    protected $table = 'lens_stock_entries';

    protected $fillable = [
        'branch_id', 'glass_lense_id', 'side',
        'direction', 'quantity', 'source_type', 'source_id', 'notes', 'user_id',
    ];

    public function lens()
    {
        return $this->belongsTo(glassLense::class, 'glass_lense_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    /**
     * The Lab Order (PO) that caused this damaged entry.
     * Only meaningful when source_type = 'damaged'.
     */
    public function sourcePo()
    {
        return $this->belongsTo(\App\LensPurchaseOrder::class, 'source_id');
    }

    /**
     * Get available stock for a lens in a specific branch (all sides combined).
     */
    public static function availableFor($glassLenseId, $branchId)
    {
        $in  = static::where('glass_lense_id', $glassLenseId)
                     ->where('branch_id', $branchId)
                     ->where('direction', 'in')
                     ->sum('quantity');

        $out = static::where('glass_lense_id', $glassLenseId)
                     ->where('branch_id', $branchId)
                     ->where('direction', 'out')
                     ->sum('quantity');

        return max(0, $in - $out);
    }

    /**
     * Get available stock for a specific side (R / L) in a branch.
     */
    public static function availableForSide($glassLenseId, $branchId, $side)
    {
        $base = static::where('glass_lense_id', $glassLenseId)
                      ->where('branch_id', $branchId)
                      ->where('side', $side);

        $in  = (clone $base)->where('direction', 'in')->sum('quantity');
        $out = (clone $base)->where('direction', 'out')->sum('quantity');

        return max(0, $in - $out);
    }

    /**
     * Efficient batch query: returns ['R' => n, 'L' => n, 'total' => n]
     * for a lens + branch combination.
     */
    public static function stockSummaryFor($glassLenseId, $branchId)
    {
        $rows = static::where('glass_lense_id', $glassLenseId)
            ->where('branch_id', $branchId)
            ->selectRaw("side, SUM(CASE WHEN direction='in' THEN quantity ELSE -quantity END) as net")
            ->groupBy('side')
            ->get()
            ->keyBy('side');

        $r      = max(0, (int) ($rows->get('R')->net ?? 0));
        $l      = max(0, (int) ($rows->get('L')->net ?? 0));
        $noSide = max(0, (int) ($rows->get(null)->net ?? 0));

        return ['R' => $r, 'L' => $l, 'unknown' => $noSide, 'total' => $r + $l + $noSide];
    }
}
