<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Stap 1: Rollen aanmaken
        $roles = [
            'super_admin',
            'content_editor',
            'blog_author',
            'review_moderator',
            'customer_service',
            'product_manager',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Stap 2: Permissies aanmaken
        $resources = [
            'blog', 'brand', 'category', 'color', 'order', 'product',
            'product_color_stock', 'review', 'setting', 'user'
        ];

        $actions = [
            'view', 'view_any', 'create', 'update', 'delete', 'delete_any',
            'restore', 'restore_any', 'force_delete', 'force_delete_any',
            'replicate', 'reorder'
        ];

        $widgetPermissions = [
            'view_widget_DashboardStats',
            'view_widget_LatestOrders',
            'view_widget_OrderStats',
        ];

        $pagePermissions = [
            'view_filament::pages/roles',
            'view_filament::pages/permissions',
        ];

        $allPermissions = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $allPermissions[] = "{$action}_{$resource}";
            }
        }

        $allPermissions = array_merge($allPermissions, $widgetPermissions, $pagePermissions);


        foreach ($allPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Stap 3: Permissies koppelen aan rollen

        // super_admin krijgt alles
        Role::findByName('super_admin')->syncPermissions(Permission::all());

        // content_editor: beheer blogs en reviews
        Role::findByName('content_editor')->syncPermissions([
            'view_any_blog', 'view_blog', 'update_blog', 'delete_blog', 'restore_blog',
            'force_delete_blog', 'reorder_blog', 'replicate_blog',
            'view_review', 'view_any_review', 'update_review', 'delete_review',
            'restore_review', 'force_delete_review',
            'view_widget_DashboardStats'
        ]);

        // blog_author: alleen eigen blogs aanmaken/bewerken
        Role::findByName('blog_author')->syncPermissions([
            'view_blog', 'create_blog', 'update_blog',
        ]);

        // review_moderator
        Role::findByName('review_moderator')->syncPermissions([
            'view_review', 'view_any_review', 'update_review',
            'delete_review', 'restore_review', 'force_delete_review',
        ]);

        // customer_service
        Role::findByName('customer_service')->syncPermissions([
            'view_order', 'view_any_order', 'update_order',
            'view_user', 'view_any_user',
        ]);

        // product_manager
        Role::findByName('product_manager')->syncPermissions([
            'view_product', 'view_any_product', 'create_product', 'update_product',
            'delete_product', 'replicate_product', 'reorder_product',
            'view_category', 'view_any_category', 'create_category', 'update_category',
            'delete_category', 'replicate_category',
            'view_color', 'view_any_color', 'create_color', 'update_color',
            'delete_color', 'replicate_color',
            'view_brand', 'view_any_brand', 'create_brand', 'update_brand',
            'delete_brand', 'replicate_brand',
            'view_widget_OrderStats'
        ]);

        // Stap 4: Rollen koppelen aan users (optioneel, op basis van e-mail)
        $userRoles = [
            'admin@gmail.com' => 'super_admin',
            'content_editor@gmail.com' => 'content_editor',
            'blog_author@gmail.com' => 'blog_author',
            'review_moderator@gmail.com' => 'review_moderator',
            'customer_service@gmail.com' => 'customer_service',
            'product_manager@gmail.com' => 'product_manager',
        ];

        foreach ($userRoles as $email => $role) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->syncRoles([$role]);
            }
        }
    }
}
