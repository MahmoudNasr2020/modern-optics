<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LensBrand extends Model
{
    protected $table    = 'lens_brands';
    protected $fillable = ['name', 'slug', 'logo', 'description', 'is_active'];

    // ── Auto-generate slug on create ─────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    // ── Relationships ─────────────────────────────────────────
    public function lenses()
    {
        return $this->hasMany(glassLense::class, 'lens_brand_id');
    }

    // ── Accessors ─────────────────────────────────────────────
    public function getLensCountAttribute()
    {
        return $this->lenses()->count();
    }

    // ── Scopes ────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
