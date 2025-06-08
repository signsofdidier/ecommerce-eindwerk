<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. COMPANY
        $company1 = Company::first() ?? Company::create([
            'name' => 'Company 1',
            'slug' => 'company-1', // ✅ verplicht voor URL
        ]);
        $company2 = Company::where('name', 'Company 2')->first() ?? Company::create([
            'name' => 'Company 2',
            'slug' => 'company-2', // ✅ idem
        ]);


        // 2. USERS
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $admin->companies()->syncWithoutDetaching([$company1->id, $company2->id]);

        $didier = User::create([
            'name' => 'Didier Vanassche',
            'email' => 'didier.v@hotmail.com',
            'password' => Hash::make('password'),
        ]);
        $didier->companies()->syncWithoutDetaching([$company1->id, $company2->id]);

        $sophie = User::create([
            'name' => 'Sophie Adams',
            'email' => 'sophie@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $sophie->companies()->syncWithoutDetaching([$company1->id]);

        $charles = User::create([
            'name' => 'Charles Peters',
            'email' => 'charles@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $charles->companies()->syncWithoutDetaching([$company1->id]);



        // 3. BRANDS
        $brands = [
            'Designo',
            'SitWell',
            'NordicHome',
            'UrbanCraft',
            'VintageVibe'
        ];
        $brandModels = [];
        foreach ($brands as $brand) {
            $brandModels[$brand] = Brand::create([
                'name' => $brand,
                'slug' => Str::slug($brand),
                'company_id' => $company1->id,
            ]);
        }

        // 4. CATEGORIES
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
            $categoryModels[$cat] = Category::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'company_id' => $company1->id,
            ]);
        }

        // 5. COLORS
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
            $colorModels[$color['name']] = Color::create([
                ...$color,
                'company_id' => $company1->id,
            ]);
        }


        // 6. PRODUCTS DATA
        $products = [
            [
                'name' => 'Nordic Lounge Chair',
                'brand' => 'NordicHome',
                'category' => 'Chairs',
                'description' => 'Comfortable lounge chair with Scandinavian design, perfect for any modern interior.',
                'price' => 199.99,
                'is_active' => true,
                'is_featured' => true,
                'in_stock' => true,
                'on_sale' => false,
                'color_names' => ['Black', 'White Broken', 'Taupe'],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'UrbanCraft Coffee Table',
                'brand' => 'UrbanCraft',
                'category' => 'Coffee Tables',
                'description' => 'Modern coffee table with a metal frame and oak wood top.',
                'price' => 349.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => true,
                'color_names' => ['Taupe', 'Walnut'],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Designo 3-Seat Sofa',
                'brand' => 'Designo',
                'category' => 'Sofas',
                'description' => 'Luxurious 3-seater sofa upholstered in soft fabric. Available in several trendy colors.',
                'price' => 899.50,
                'is_active' => true,
                'is_featured' => true,
                'in_stock' => true,
                'on_sale' => true,
                'color_names' => ['White Broken', 'Turquoise', 'Green Lime'],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'SitWell Dining Table',
                'brand' => 'SitWell',
                'category' => 'Tables',
                'description' => 'Sturdy dining table with a minimalist design, seats up to six people.',
                'price' => 599.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => false,
                'on_sale' => false,
                'color_names' => ['Black', 'White Broken', 'Taupe', 'Walnut'],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'VintageVibe Cabinet',
                'brand' => 'VintageVibe',
                'category' => 'Cabinets',
                'description' => 'Stylish cabinet in vintage style, ideal for your hallway or living room.',
                'price' => 279.99,
                'is_active' => true,
                'is_featured' => true,
                'in_stock' => true,
                'on_sale' => false,
                'color_names' => ['Taupe', 'Walnut', 'Green Lime'],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Taupe Serenity Armchair',
                'brand' => 'VintageVibe',
                'category' => 'Chairs',
                'description' => 'A cozy armchair in elegant taupe with walnut wooden legs. Perfect for reading nooks or as an accent chair.',
                'price' => 269.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => true,
                'color_names' => ['Taupe', 'Walnut'],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Lime Green Modern Sofa',
                'brand' => 'SitWell',
                'category' => 'Sofas',
                'description' => 'Contemporary sofa with vibrant lime green fabric. Comfortable seating for your living room.',
                'price' => 799.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => false,
                'color_names' => ['Green Lime', 'White Broken'],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Turquoise Dining Table',
                'brand' => 'NordicHome',
                'category' => 'Tables',
                'description' => 'Unique dining table with a smooth turquoise finish, perfect for a stylish and lively dining area.',
                'price' => 689.50,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => false,
                'on_sale' => false,
                'color_names' => ['Turquoise', 'White Broken'],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Minimalist Walnut Coffee Table',
                'brand' => 'UrbanCraft',
                'category' => 'Coffee Tables',
                'description' => 'A minimalist coffee table with a rich walnut top and sturdy black legs. Ideal for modern interiors.',
                'price' => 325.00,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => true,
                'color_names' => ['Walnut', 'Black'],
                'shipping_cost' => 65.00,
            ],
            [
                'name' => 'Oak & Taupe Storage Cabinet',
                'brand' => 'Designo',
                'category' => 'Cabinets',
                'description' => 'Functional storage cabinet combining oak and taupe for a timeless look. Plenty of space for your essentials.',
                'price' => 499.99,
                'is_active' => true,
                'is_featured' => false,
                'in_stock' => true,
                'on_sale' => false,
                'color_names' => ['Taupe', 'White Broken'],
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

            $brandId = $brandModels[$productData['brand']]->id;
            $categoryId = $categoryModels[$productData['category']]->id;
            $colorIds = collect($productData['color_names'])
                ->map(fn($name) => $colorModels[$name]->id)
                ->toArray();

            $product = Product::create([
                'name'        => $productData['name'],
                'slug'        => Str::slug($productData['name']),
                'brand_id'    => $brandId,
                'category_id' => $categoryId,
                'images'      => $imgs,
                'description' => $productData['description'],
                'price'       => $productData['price'],
                'is_active'   => $productData['is_active'],
                'is_featured' => $productData['is_featured'],
                'in_stock'    => $productData['in_stock'],
                'on_sale'     => $productData['on_sale'],
                'shipping_cost' => $productData['shipping_cost'],
                'company_id' => $company1->id,

            ]);
            $product->colors()->attach($colorIds);
        }

        /*Setting::create([
            'company_id' => $company1->id,
            'free_shipping_threshold' => 1000,
        ]);*/

    }
}
