<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Company;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. COMPANY aanmaken (voor tenant multi-tenancy)
        $company = Company::create([
            'name' => 'Demo Company',
            'slug' => 'demo-company',
        ]);

        // USERS (koppelen aan company_id)
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
        ]);
        User::create([
            'name' => 'Didier Vanassche',
            'email' => 'didier.v@hotmail.com',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
        ]);
        User::create([
            'name' => 'Sophie Adams',
            'email' => 'sophie@gmail.com',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
        ]);
        User::create([
            'name' => 'Charles Peters',
            'email' => 'charles@gmail.com',
            'password' => Hash::make('password'),
            'company_id' => $company->id,
        ]);

        // BRANDS (koppelen aan company_id)
        $brands = [
            'Designo',
            'SitWell',
            'NordicHome',
            'UrbanCraft',
            'VintageVibe'
        ];
        $brandModels = [];
        foreach ($brands as $brand) {
            $brandModels[] = Brand::create([
                'name' => $brand,
                'slug' => Str::slug($brand),
                'company_id' => $company->id,
            ]);
        }

        // CATEGORIES (koppelen aan company_id)
        $categories = [
            'Chairs',
            'Sofas',
            'Tables',
            'Coffee Tables',
            'Cabinets',
            'Cupboards',
            'Accessories'
        ];
        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'company_id' => $company->id,
            ]);
        }

        // COLORS (koppelen aan company_id)
        $colors = [
            ['name' => 'Black',        'hex' => '#000000'],
            ['name' => 'White Broken', 'hex' => '#F5F5F5'],
            ['name' => 'Turquoise',    'hex' => '#1DE9B6'],
            ['name' => 'Green Lime',   'hex' => '#C6FF00'],
            ['name' => 'Taupe',        'hex' => '#483C32'],
            ['name' => 'Walnut',       'hex' => '#77604E'],
        ];
        $colorModels = [];
        foreach ($colors as $color) {
            $colorModels[] = Color::create([
                ...$color,
                'company_id' => $company->id,
            ]);
        }

        // PRODUCTS DATA (koppelen aan company_id)
        $products = [
            [
                'name' => 'Nordic Lounge Chair',
                'brand_id' => 3,
                'category_id' => 1,
                'description' => 'Comfortable lounge chair with Scandinavian design, perfect for any modern interior.',
                'price' => 199.99,
                'is_active' => true,
                'is_featured' => true,
                'in_stock' => true,
                'on_sale' => false,
                'colors' => [1, 2, 5],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'UrbanCraft Coffee Table',
                'brand_id' => 4,
                'category_id' => 4,
                'description' => 'Modern coffee table with a metal frame and oak wood top.',
                'price' => 349.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => true,
                'colors' => [5, 6],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Designo 3-Seat Sofa',
                'brand_id' => 1,
                'category_id' => 2,
                'description' => 'Luxurious 3-seater sofa upholstered in soft fabric. Available in several trendy colors.',
                'price' => 899.50,
                'is_active' => true,
                'is_featured' => true,
                'in_stock' => true,
                'on_sale' => true,
                'colors' => [2, 3, 4],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'SitWell Dining Table',
                'brand_id' => 2,
                'category_id' => 3,
                'description' => 'Sturdy dining table with a minimalist design, seats up to six people.',
                'price' => 599.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => false,
                'on_sale' => false,
                'colors' => [1, 2, 5, 6],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'VintageVibe Cabinet',
                'brand_id' => 5,
                'category_id' => 5,
                'description' => 'Stylish cabinet in vintage style, ideal for your hallway or living room.',
                'price' => 279.99,
                'is_active' => true,
                'is_featured' => true,
                'in_stock' => true,
                'on_sale' => false,
                'colors' => [5, 6, 4],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Taupe Serenity Armchair',
                'brand_id' => 5,
                'category_id' => 1,
                'description' => 'A cozy armchair in elegant taupe with walnut wooden legs. Perfect for reading nooks or as an accent chair.',
                'price' => 269.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => true,
                'colors' => [5, 6],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Lime Green Modern Sofa',
                'brand_id' => 2,
                'category_id' => 2,
                'description' => 'Contemporary sofa with vibrant lime green fabric. Comfortable seating for your living room.',
                'price' => 799.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => false,
                'colors' => [4, 2],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Turquoise Dining Table',
                'brand_id' => 3,
                'category_id' => 3,
                'description' => 'Unique dining table with a smooth turquoise finish, perfect for a stylish and lively dining area.',
                'price' => 689.50,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => false,
                'on_sale' => false,
                'colors' => [3, 2],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Minimalist Walnut Coffee Table',
                'brand_id' => 4,
                'category_id' => 4,
                'description' => 'A minimalist coffee table with a rich walnut top and sturdy black legs. Ideal for modern interiors.',
                'price' => 325.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => true,
                'colors' => [6, 1],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Oak & Taupe Storage Cabinet',
                'brand_id' => 1,
                'category_id' => 5,
                'description' => 'Functional storage cabinet combining oak and taupe for a timeless look. Plenty of space for your essentials.',
                'price' => 499.99,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => false,
                'colors' => [5, 2],
                'shipping_cost' => 65.00,
            ],
        ];

        $localImages = [
            'chairs'   => ['plantkast-1.jpg', 'plantkast-2.jpg', 'plantkast-3.jpg'],
            'sofas'    => ['zetel-1.jpg', 'zetel-2.jpg', 'zetel-3.jpg', 'zetel-4.jpg'],
            'tables'   => ['table-1.jpg', 'table-2.jpg', 'table-3.jpg'],
            'coffee'   => ['salontafel.jpg'],
            'cabinets' => ['kast-1.jpg', 'kast-2.jpg', 'kast-3.jpg'],
            'default'  => ['comode-1.jpg', 'comode-2.jpg', 'comode-3.jpg'],
        ];

        foreach ($products as $productData) {
            $name = strtolower($productData['name']);

            if (str_contains($name, 'chair')) {
                $imgs = $localImages['chairs'];
            } elseif (str_contains($name, 'sofa')) {
                $imgs = $localImages['sofas'];
            } elseif (str_contains($name, 'table')) {
                $imgs = (str_contains($name, 'coffee')) ? $localImages['coffee'] : $localImages['tables'];
            } elseif (str_contains($name, 'cabinet')) {
                $imgs = $localImages['cabinets'];
            } else {
                $imgs = $localImages['default'];
            }

            $imgs = array_slice($imgs, 0, rand(1, count($imgs)));
            $imgs = array_map(fn($img) => 'products/' . ltrim($img, '/'), $imgs);

            $product = Product::create([
                'name'        => $productData['name'],
                'slug'        => Str::slug($productData['name']),
                'brand_id'    => $productData['brand_id'],
                'category_id' => $productData['category_id'],
                'images'      => $imgs,
                'description' => $productData['description'],
                'price'       => $productData['price'],
                'is_active'   => $productData['is_active'],
                'is_featured' => $productData['is_featured'],
                'in_stock'    => $productData['in_stock'],
                'on_sale'     => $productData['on_sale'],
                'shipping_cost' => $productData['shipping_cost'],
                'company_id'  => $company->id,
            ]);
            $colorPivotData = [];
            foreach ($productData['colors'] as $colorId) {
                $colorPivotData[$colorId] = ['company_id' => $company->id];
            }
            $product->colors()->attach($colorPivotData);
        }

        // SETTINGS (koppelen aan company_id)
        Setting::create([
            'company_id' => $company->id,
            'free_shipping_threshold' => 1000,
            // Voeg hier andere kolommen toe indien nodig
        ]);
    }
}
