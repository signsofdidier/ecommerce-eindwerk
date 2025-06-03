<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// Models
use App\Models\Tenant;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //
        // 1) Maak ÉÉN Super Admin‐account (zonder tenant_id).
        //
        //    Let op: e-mail "superadmin@localhost" moet uniek zijn. We maken het hier
        //    dus slechts één keer, om de UniqueConstraintViolationException te voorkomen.
        //
        User::create([
            'name'           => 'Super Admin',
            'email'          => 'superadmin@localhost',
            'password'       => Hash::make('password'),
            'is_super_admin' => true,
            'tenant_id'      => null, // Super Admin heeft géén tenant
        ]);

        //
        // 2) Maak TENANT 1 aan (bedrijf1) en bind deze als currentTenant.
        //
        $tenant = Tenant::create([
            'subdomain' => 'bedrijf1',
            'name'      => 'Firma Meubels',
        ]);

        // Bind deze Tenant in de container; de BelongsToTenant‐trait gebruikt dit
        app()->instance('currentTenant', $tenant);

        //
        // 3) Maak (tenant‐)admin voor Company Tenant 1 aan (optioneel).
        //    Dat is een aparte gebruiker met tenant_id = 1. Deze gebruiker kan
        //    straks inloggen op http://bedrijf1.localhost/admin (Filament) of
        //    op Livewire‐routes met tenant‐middleware.
        //
        User::create([
            'tenant_id'      => $tenant->id,
            'name'           => 'Admin Firma Meubels',
            'email'          => 'beheer@' . $tenant->subdomain . '.localhost',
            'password'       => Hash::make('password'),
            'is_super_admin' => false,
        ]);

        //
        // 4) Seed alle tenant‐specifieke data (Brand, Category, Color, Product, etc.)
        //    met tenant_id = $tenant->id.
        //

        // --- BRANDS ---
        $brands = [
            'Designo',
            'SitWell',
            'NordicHome',
            'UrbanCraft',
            'VintageVibe',
        ];
        foreach ($brands as $brandName) {
            Brand::create([
                'tenant_id' => $tenant->id,
                'name'      => $brandName,
                'slug'      => Str::slug($brandName),
            ]);
        }

        // --- CATEGORIES ---
        $categories = [
            'Chairs',
            'Sofas',
            'Tables',
            'Coffee Tables',
            'Cabinets',
            'Cupboards',
            'Accessories',
        ];
        foreach ($categories as $categoryName) {
            Category::create([
                'tenant_id' => $tenant->id,
                'name'      => $categoryName,
                'slug'      => Str::slug($categoryName),
            ]);
        }

        // --- COLORS ---
        $colors = [
            ['name' => 'Black',        'hex' => '#000000'],
            ['name' => 'White Broken', 'hex' => '#F5F5F5'],
            ['name' => 'Turquoise',    'hex' => '#1DE9B6'],
            ['name' => 'Green Lime',   'hex' => '#C6FF00'],
            ['name' => 'Taupe',        'hex' => '#483C32'],
            ['name' => 'Walnut',       'hex' => '#77604E'],
        ];
        foreach ($colors as $colorData) {
            Color::create([
                'tenant_id' => $tenant->id,
                'name'      => $colorData['name'],
                'hex'       => $colorData['hex'],
            ]);
        }

        // --- PRODUCTS ---
        // We hergebruiken de bestaande productdefinitie, maar voegen telkens explicit 'tenant_id' => $tenant->id toe.
        // Bij de merken (brand_id) en categorieën (category_id) gaan we er in dit voorbeeld van uit
        // dat de IDs van de seeded merken en categorieën matchen met de volgorde hierboven.
        //
        // Let op: als je in de toekomst merken of categorieën in een andere volgorde seedt,
        // moet je hier óf dynamisch de juiste ID’s ophalen, óf hard‐codede IDs aanpassen.
        //
        $products = [
            [
                'name'         => 'Nordic Lounge Chair',
                'brand_id'     => 1, // Designo
                'category_id'  => 1, // Chairs
                'description'  => 'Comfortable lounge chair with Scandinavian design, perfect for any modern interior.',
                'price'        => 199.99,
                'is_active'    => true,
                'is_featured'  => true,
                'in_stock'     => true,
                'on_sale'      => false,
                'colors'       => [1, 2, 5], // Black, White Broken, Taupe
                'shipping_cost'=> 65.00,
            ],
            [
                'name'         => 'UrbanCraft Coffee Table',
                'brand_id'     => 4, // UrbanCraft
                'category_id'  => 4, // Coffee Tables
                'description'  => 'Modern coffee table with a metal frame and oak wood top.',
                'price'        => 349.00,
                'is_active'    => true,
                'is_featured'  => false,
                'in_stock'     => true,
                'on_sale'      => true,
                'colors'       => [5, 6], // Taupe, Walnut
                'shipping_cost'=> 65.00,
            ],
            [
                'name'         => 'Designo 3-Seat Sofa',
                'brand_id'     => 1, // Designo
                'category_id'  => 2, // Sofas
                'description'  => 'Luxurious 3-seater sofa upholstered in soft fabric. Available in several trendy colors.',
                'price'        => 899.50,
                'is_active'    => true,
                'is_featured'  => true,
                'in_stock'     => true,
                'on_sale'      => true,
                'colors'       => [2, 3, 4], // White Broken, Turquoise, Green Lime
                'shipping_cost'=> 65.00,
            ],
            [
                'name'         => 'SitWell Dining Table',
                'brand_id'     => 2, // SitWell
                'category_id'  => 3, // Tables
                'description'  => 'Sturdy dining table with a minimalist design, seats up to six people.',
                'price'        => 599.00,
                'is_active'    => true,
                'is_featured'  => false,
                'in_stock'     => false,
                'on_sale'      => false,
                'colors'       => [1, 2, 5, 6], // Black, White Broken, Taupe, Walnut
                'shipping_cost'=> 65.00,
            ],
            [
                'name'         => 'VintageVibe Cabinet',
                'brand_id'     => 5, // VintageVibe
                'category_id'  => 5, // Cabinets
                'description'  => 'Stylish cabinet in vintage style, ideal for your hallway or living room.',
                'price'        => 279.99,
                'is_active'    => true,
                'is_featured'  => true,
                'in_stock'     => true,
                'on_sale'      => false,
                'colors'       => [5, 6, 4], // Taupe, Walnut, Green Lime
                'shipping_cost'=> 65.00,
            ],
            [
                'name'         => 'Taupe Serenity Armchair',
                'brand_id'     => 5, // VintageVibe
                'category_id'  => 1, // Chairs
                'description'  => 'A cozy armchair in elegant taupe with walnut wooden legs. Perfect for reading nooks or as an accent chair.',
                'price'        => 269.00,
                'is_active'    => true,
                'is_featured'  => false,
                'in_stock'     => true,
                'on_sale'      => true,
                'colors'       => [5, 6], // Taupe, Walnut
                'shipping_cost'=> 65.00,
            ],
            [
                'name'         => 'Lime Green Modern Sofa',
                'brand_id'     => 2, // SitWell
                'category_id'  => 2, // Sofas
                'description'  => 'Contemporary sofa with vibrant lime green fabric. Comfortable seating for your living room.',
                'price'        => 799.00,
                'is_active'    => true,
                'is_featured'  => false,
                'in_stock'     => true,
                'on_sale'      => false,
                'colors'       => [4, 2], // Green Lime, White Broken
                'shipping_cost'=> 65.00,
            ],
            [
                'name'         => 'Turquoise Dining Table',
                'brand_id'     => 3, // NordicHome
                'category_id'  => 3, // Tables
                'description'  => 'Unique dining table with a smooth turquoise finish, perfect for a stylish and lively dining area.',
                'price'        => 689.50,
                'is_active'    => true,
                'is_featured'  => false,
                'in_stock'     => false,
                'on_sale'      => false,
                'colors'       => [3, 2], // Turquoise, White Broken
                'shipping_cost'=> 65.00,
            ],
            [
                'name'         => 'Minimalist Walnut Coffee Table',
                'brand_id'     => 4, // UrbanCraft
                'category_id'  => 4, // Coffee Tables
                'description'  => 'A minimalist coffee table with a rich walnut top and sturdy black legs. Ideal for modern interiors.',
                'price'        => 325.00,
                'is_active'    => true,
                'is_featured'  => false,
                'in_stock'     => true,
                'on_sale'      => true,
                'colors'       => [6, 1], // Walnut, Black
                'shipping_cost'=> 65.00,
            ],
            [
                'name'         => 'Oak & Taupe Storage Cabinet',
                'brand_id'     => 1, // Designo
                'category_id'  => 5, // Cabinets
                'description'  => 'Functional storage cabinet combining oak and taupe for a timeless look. Plenty of space for your essentials.',
                'price'        => 499.99,
                'is_active'    => true,
                'is_featured'  => false,
                'in_stock'     => true,
                'on_sale'      => false,
                'colors'       => [5, 2], // Taupe, White Broken
                'shipping_cost'=> 65.00,
            ],
        ];

        // Eigen afbeeldingsmappen voor producten (optioneel: niet verplicht)
        $localImages = [
            'chairs'   => ['plantkast-1.jpg', 'plantkast-2.jpg', 'plantkast-3.jpg'],
            'sofas'    => ['zetel-1.jpg', 'zetel-2.jpg', 'zetel-3.jpg', 'zetel-4.jpg'],
            'tables'   => ['table-1.jpg', 'table-2.jpg', 'table-3.jpg'],
            'coffee'   => ['salontafel.jpg'],
            'cabinets' => ['kast-1.jpg', 'kast-2.jpg', 'kast-3.jpg'],
            'default'  => ['comode-1.jpg', 'comode-2.jpg', 'comode-3.jpg'],
        ];

        foreach ($products as $productData) {
            $nameLower = strtolower($productData['name']);

            if (str_contains($nameLower, 'chair')) {
                $imgs = $localImages['chairs'];
            } elseif (str_contains($nameLower, 'sofa')) {
                $imgs = $localImages['sofas'];
            } elseif (str_contains($nameLower, 'table')) {
                $imgs = (str_contains($nameLower, 'coffee'))
                    ? $localImages['coffee']
                    : $localImages['tables'];
            } elseif (str_contains($nameLower, 'cabinet')) {
                $imgs = $localImages['cabinets'];
            } else {
                $imgs = $localImages['default'];
            }

            // Kies een willekeurig aantal afbeeldingen (1–3)
            $chosenImgs = array_slice($imgs, 0, rand(1, count($imgs)));
            $chosenImgs = array_map(fn($img) => 'products/' . ltrim($img, '/'), $chosenImgs);

            // Maak het Product aan, met expliciete tenant_id
            $product = Product::create([
                'tenant_id'    => $tenant->id,
                'name'         => $productData['name'],
                'slug'         => Str::slug($productData['name']),
                'brand_id'     => $productData['brand_id'],
                'category_id'  => $productData['category_id'],
                'images'       => $chosenImgs, // sla een PHP-array op, automatisch omgezet naar JSON
                'description'  => $productData['description'],
                'price'        => $productData['price'],
                'is_active'    => $productData['is_active'],
                'is_featured'  => $productData['is_featured'],
                'in_stock'     => $productData['in_stock'],
                'on_sale'      => $productData['on_sale'],
                'shipping_cost'=> $productData['shipping_cost'],
            ]);

            // Koppel kleuren via de pivot‐tabel (let op: tenant_id op de pivot)
            $product->colors()->attach(
                $productData['colors'],
                ['tenant_id' => $tenant->id]
            );
        }

        //
        // 5) Na afloop “unbind” je de currentTenant uit de container,
        //    zodat je erbuiten geen onverwachte context meer houdt.
        //
        app()->forgetInstance('currentTenant');
    }
}
