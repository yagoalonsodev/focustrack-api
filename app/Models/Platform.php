<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Platform extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'website_url',
        'commission_rate',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Boot del modelo para generar slug automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($platform) {
            if (empty($platform->slug)) {
                $platform->slug = Str::slug($platform->name);
            }
        });
    }

    /**
     * Relación con cuentas
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Scope para plataformas activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

