<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'platform_id',
        'account_name',
        'account_username',
        'account_email',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con plataforma
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Relación con items de inventario
     */
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Scope para cuentas activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Total de ventas de esta cuenta
     */
    public function getTotalSalesAttribute()
    {
        return $this->inventoryItems()
            ->whereNotNull('sale_price')
            ->sum('sale_price');
    }

    /**
     * Total de beneficios de esta cuenta
     */
    public function getTotalProfitAttribute()
    {
        return $this->inventoryItems()
            ->whereNotNull('net_profit')
            ->sum('net_profit');
    }
}

