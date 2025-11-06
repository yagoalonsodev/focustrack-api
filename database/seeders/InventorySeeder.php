<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Platform;
use App\Models\Account;
use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías
        $categorias = [
            ['name' => 'Bolsos', 'description' => 'Bolsos y carteras de diferentes marcas'],
            ['name' => 'Jerseys', 'description' => 'Jerseys y suéteres'],
            ['name' => 'Pantalones', 'description' => 'Pantalones de diferentes estilos'],
            ['name' => 'Calzado', 'description' => 'Zapatos, botas y zapatillas'],
        ];

        foreach ($categorias as $cat) {
            Category::create($cat);
        }

        // Crear plataformas de venta
        $plataformas = [
            [
                'name' => 'Vinted',
                'website_url' => 'https://www.vinted.es',
                'commission_rate' => 5.00,
            ],
            [
                'name' => 'Wallapop',
                'website_url' => 'https://www.wallapop.com',
                'commission_rate' => 0.00,
            ],
            [
                'name' => 'Amazon',
                'website_url' => 'https://www.amazon.es',
                'commission_rate' => 15.00,
            ],
            [
                'name' => 'eBay',
                'website_url' => 'https://www.ebay.es',
                'commission_rate' => 12.50,
            ],
        ];

        foreach ($plataformas as $plat) {
            Platform::create($plat);
        }

        // Obtener el primer usuario (asumiendo que ya existe)
        $user = User::first();
        
        if (!$user) {
            // Si no hay usuario, crear uno de ejemplo
            $user = User::create([
                'name' => 'Usuario Demo',
                'email' => 'demo@focustrack.com',
                'password' => bcrypt('password123'),
            ]);
        }

        // Crear cuentas en plataformas
        $vinted = Platform::where('slug', 'vinted')->first();
        $wallapop = Platform::where('slug', 'wallapop')->first();

        if ($vinted) {
            Account::create([
                'user_id' => $user->id,
                'platform_id' => $vinted->id,
                'account_name' => 'Mi cuenta Vinted',
                'account_username' => '@mitienda',
                'account_email' => 'vinted@example.com',
            ]);
        }

        if ($wallapop) {
            Account::create([
                'user_id' => $user->id,
                'platform_id' => $wallapop->id,
                'account_name' => 'Mi cuenta Wallapop',
                'account_username' => 'mitienda',
                'account_email' => 'wallapop@example.com',
            ]);
        }

        // Crear productos basados en los datos de la hoja de cálculo
        $categoriaBolsos = Category::where('slug', 'bolsos')->first();
        $categoriaJerseys = Category::where('slug', 'jerseys')->first();
        $categoriaPantalones = Category::where('slug', 'pantalones')->first();

        // Producto: Bolso Longchamp Le Pliage
        $bolsoLongchamp = Product::create([
            'category_id' => $categoriaBolsos->id,
            'name' => 'Bolso Longchamp Le Pliage',
            'brand' => 'Longchamp',
            'description' => 'Bolso icónico de Longchamp, modelo Le Pliage',
        ]);

        // Producto: Jersey Ami Paris Corazon
        $jerseyAmi = Product::create([
            'category_id' => $categoriaJerseys->id,
            'name' => 'Jersey Ami Paris Corazon Grande',
            'brand' => 'Ami Paris',
            'description' => 'Jersey con el icónico logo de corazón de Ami Paris',
        ]);

        // Producto: Jersey Ami Paris Corazon Pequeño
        $jerseyAmiPequeno = Product::create([
            'category_id' => $categoriaJerseys->id,
            'name' => 'Jersey Ami Paris Corazon Pequeño',
            'brand' => 'Ami Paris',
            'description' => 'Jersey con logo de corazón pequeño de Ami Paris',
        ]);

        // Producto: Pantalones One Karma
        $pantalones = Product::create([
            'category_id' => $categoriaPantalones->id,
            'name' => 'Pantalones One Karma',
            'brand' => 'One Karma',
            'description' => 'Pantalones de la marca One Karma',
        ]);

        // Obtener la primera cuenta para asignar ventas
        $account = Account::first();

        // Crear items de inventario basados en la hoja de cálculo
        $items = [
            // Bolsos Longchamp
            [
                'product_id' => $bolsoLongchamp->id,
                'color' => null,
                'size' => 'L',
                'purchase_price' => 40.00,
                'sale_price' => 11.47,
                'purchase_date' => '2025-10-14',
                'sale_date' => '2025-10-24',
                'status' => 'enviado',
            ],
            [
                'product_id' => $bolsoLongchamp->id,
                'color' => 'Negro',
                'size' => 'L',
                'purchase_price' => 40.50,
                'sale_price' => 11.47,
                'purchase_date' => '2025-10-14',
                'sale_date' => '2025-10-28',
                'status' => 'enviado',
            ],
            [
                'product_id' => $bolsoLongchamp->id,
                'color' => 'Negro',
                'size' => 'L',
                'purchase_price' => 40.50,
                'sale_price' => 11.47,
                'purchase_date' => '2025-10-14',
                'sale_date' => '2025-10-29',
                'status' => 'enviado',
            ],
            [
                'product_id' => $bolsoLongchamp->id,
                'color' => 'Negro',
                'size' => 'L',
                'purchase_price' => 35.00,
                'sale_price' => 11.47,
                'purchase_date' => '2025-10-14',
                'sale_date' => '2025-10-30',
                'status' => 'enviado',
            ],
            [
                'product_id' => $bolsoLongchamp->id,
                'color' => 'Negro',
                'size' => 'L',
                'purchase_price' => 35.00,
                'sale_price' => 11.47,
                'purchase_date' => '2025-10-14',
                'sale_date' => '2025-10-31',
                'status' => 'enviado',
            ],
            [
                'product_id' => $bolsoLongchamp->id,
                'color' => 'Negro',
                'size' => 'L',
                'purchase_price' => 36.00,
                'sale_price' => 11.48,
                'purchase_date' => '2025-10-14',
                'sale_date' => '2025-10-31',
                'status' => 'enviado',
            ],
            // Bolsos sin vender aún
            [
                'product_id' => $bolsoLongchamp->id,
                'color' => 'Negro',
                'size' => 'L',
                'purchase_price' => 13.41,
                'purchase_date' => '2025-10-29',
                'status' => 'en_stock',
            ],
            [
                'product_id' => $bolsoLongchamp->id,
                'color' => 'Negro',
                'size' => 'L',
                'purchase_price' => 13.41,
                'purchase_date' => '2025-10-29',
                'status' => 'publicado',
            ],
            [
                'product_id' => $bolsoLongchamp->id,
                'color' => 'Azul',
                'size' => 'L',
                'purchase_price' => 13.41,
                'purchase_date' => '2025-10-29',
                'status' => 'publicado',
            ],

            // Jerseys Ami Paris Grande
            [
                'product_id' => $jerseyAmi->id,
                'color' => 'Negro/Rojo',
                'size' => 'M',
                'purchase_price' => 13.86,
                'purchase_date' => '2025-10-29',
                'status' => 'en_stock',
            ],
            [
                'product_id' => $jerseyAmi->id,
                'color' => 'Negro/Rojo',
                'size' => 'M',
                'purchase_price' => 13.86,
                'purchase_date' => '2025-10-29',
                'status' => 'en_stock',
            ],

            // Jerseys Ami Paris Pequeño
            [
                'product_id' => $jerseyAmiPequeno->id,
                'color' => 'Gris',
                'size' => 'M',
                'purchase_price' => 0.00,
                'purchase_date' => '2025-10-29',
                'status' => 'en_stock',
                'notes' => 'Muestra o regalo',
            ],
            [
                'product_id' => $jerseyAmiPequeno->id,
                'color' => 'Negro',
                'size' => 'L',
                'purchase_price' => 0.00,
                'purchase_date' => '2025-10-29',
                'status' => 'en_stock',
                'notes' => 'Muestra o regalo',
            ],

            // Pantalones
            [
                'product_id' => $pantalones->id,
                'color' => null,
                'size' => 'S',
                'purchase_price' => 24.71,
                'purchase_date' => '2025-10-28',
                'status' => 'en_stock',
            ],
        ];

        foreach ($items as $itemData) {
            $itemData['user_id'] = $user->id;
            
            // Asignar cuenta solo si está vendido
            if (in_array($itemData['status'], ['vendido', 'enviado', 'entregado'])) {
                $itemData['account_id'] = $account ? $account->id : null;
            }
            
            InventoryItem::create($itemData);
        }

        $this->command->info('✓ Base de datos poblada con datos de ejemplo');
    }
}

