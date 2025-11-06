<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'account_id',
        'user_id',
        'color',
        'size',
        'condition',
        'internal_code',
        'purchase_price',
        'purchase_date',
        'purchase_location',
        'purchase_notes',
        'sale_price',
        'sale_date',
        'platform_fees',
        'shipping_cost',
        'sale_notes',
        'net_profit',
        'profit_percentage',
        'status',
        'shipping_method',
        'tracking_number',
        'shipped_date',
        'delivered_date',
        'images',
        'notes',
        'is_featured',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'platform_fees' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'net_profit' => 'decimal:2',
        'profit_percentage' => 'decimal:2',
        'purchase_date' => 'date',
        'sale_date' => 'date',
        'shipped_date' => 'date',
        'delivered_date' => 'date',
        'images' => 'array',
        'is_featured' => 'boolean',
    ];

    /**
     * Boot del modelo para calcular beneficios automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Calcular beneficio neto si hay precio de venta
            if ($item->sale_price) {
                $item->net_profit = $item->sale_price 
                    - $item->purchase_price 
                    - $item->platform_fees 
                    - $item->shipping_cost;
                
                // Calcular porcentaje de beneficio
                if ($item->purchase_price > 0) {
                    $item->profit_percentage = ($item->net_profit / $item->purchase_price) * 100;
                }
            }
        });
    }

    /**
     * Relación con producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relación con cuenta
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para items en stock
     */
    public function scopeInStock($query)
    {
        return $query->where('status', 'en_stock');
    }

    /**
     * Scope para items publicados
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'publicado');
    }

    /**
     * Scope para items vendidos
     */
    public function scopeSold($query)
    {
        return $query->whereIn('status', ['vendido', 'enviado', 'entregado']);
    }

    /**
     * Scope para items enviados
     */
    public function scopeShipped($query)
    {
        return $query->where('status', 'enviado');
    }

    /**
     * Scope para items por rango de fechas
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('purchase_date', [$startDate, $endDate]);
    }

    /**
     * Scope para items destacados
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Verificar si el item está vendido
     */
    public function getIsSoldAttribute()
    {
        return in_array($this->status, ['vendido', 'enviado', 'entregado']);
    }

    /**
     * Obtener el nombre completo del item
     */
    public function getFullNameAttribute()
    {
        $parts = [$this->product->name];
        
        if ($this->color) {
            $parts[] = $this->color;
        }
        
        if ($this->size) {
            $parts[] = "Talla {$this->size}";
        }
        
        return implode(' - ', $parts);
    }

    /**
     * Marcar como vendido
     */
    public function markAsSold($salePrice, $saleDate = null, $accountId = null)
    {
        $this->sale_price = $salePrice;
        $this->sale_date = $saleDate ?? now();
        $this->account_id = $accountId;
        $this->status = 'vendido';
        $this->save();
    }

    /**
     * Marcar como enviado
     */
    public function markAsShipped($trackingNumber = null, $shippingMethod = null)
    {
        $this->tracking_number = $trackingNumber;
        $this->shipping_method = $shippingMethod;
        $this->shipped_date = now();
        $this->status = 'enviado';
        $this->save();
    }

    /**
     * Marcar como entregado
     */
    public function markAsDelivered()
    {
        $this->delivered_date = now();
        $this->status = 'entregado';
        $this->save();
    }
}

