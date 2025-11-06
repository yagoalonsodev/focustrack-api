<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'description',
        'sku',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación con categoría
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación con items de inventario
     */
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Items disponibles en stock
     */
    public function availableItems()
    {
        return $this->hasMany(InventoryItem::class)
            ->where('status', 'en_stock');
    }

    /**
     * Items vendidos
     */
    public function soldItems()
    {
        return $this->hasMany(InventoryItem::class)
            ->whereIn('status', ['vendido', 'enviado', 'entregado']);
    }

    /**
     * Scope para productos activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Total de items en inventario
     */
    public function getTotalInventoryAttribute()
    {
        return $this->inventoryItems()->count();
    }

    /**
     * Total de items vendidos
     */
    public function getTotalSoldAttribute()
    {
        return $this->soldItems()->count();
    }
}

