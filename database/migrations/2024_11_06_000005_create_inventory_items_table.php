<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Propietario
            
            // Detalles del item específico
            $table->string('color')->nullable();
            $table->string('size')->nullable(); // Talla: S, M, L, XL, etc.
            $table->string('condition')->default('nuevo'); // nuevo, usado, como nuevo
            $table->string('internal_code')->unique()->nullable(); // Código interno de seguimiento
            
            // Información de compra
            $table->decimal('purchase_price', 10, 2); // Precio de compra
            $table->date('purchase_date'); // Fecha de compra
            $table->string('purchase_location')->nullable(); // Dónde se compró
            $table->text('purchase_notes')->nullable();
            
            // Información de venta
            $table->decimal('sale_price', 10, 2)->nullable(); // Precio de venta
            $table->date('sale_date')->nullable(); // Fecha de venta
            $table->decimal('platform_fees', 10, 2)->default(0); // Comisiones de plataforma
            $table->decimal('shipping_cost', 10, 2)->default(0); // Costo de envío
            $table->text('sale_notes')->nullable();
            
            // Cálculos automáticos (pueden ser calculados pero los almacenamos)
            $table->decimal('net_profit', 10, 2)->nullable(); // Beneficio neto (sale_price - purchase_price - fees - shipping)
            $table->decimal('profit_percentage', 5, 2)->nullable(); // Porcentaje de beneficio
            
            // Estado del item
            $table->enum('status', [
                'en_stock',        // En inventario, sin publicar
                'publicado',       // Publicado en plataforma
                'vendido',         // Vendido, pendiente de envío
                'enviado',         // Enviado
                'entregado',       // Entregado al comprador
                'devuelto',        // Devuelto
                'retirado'         // Retirado de venta
            ])->default('en_stock');
            
            // Información de envío
            $table->string('shipping_method')->nullable(); // Método de envío
            $table->string('tracking_number')->nullable(); // Número de seguimiento
            $table->date('shipped_date')->nullable(); // Fecha de envío
            $table->date('delivered_date')->nullable(); // Fecha de entrega
            
            // Campos adicionales
            $table->text('images')->nullable(); // JSON con URLs de imágenes
            $table->text('notes')->nullable(); // Notas generales
            $table->boolean('is_featured')->default(false); // Destacado
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para mejorar consultas
            $table->index('status');
            $table->index('purchase_date');
            $table->index('sale_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};

