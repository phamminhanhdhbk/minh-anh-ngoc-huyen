<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Menu;
use App\MenuItem;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing menus
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MenuItem::truncate();
        Menu::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create Header Menu
        $headerMenu = Menu::create([
            'name' => 'Menu Chính',
            'slug' => 'main-menu',
            'location' => 'header',
            'description' => 'Menu chính hiển thị ở header',
            'is_active' => true,
            'order' => 1
        ]);

        // Header Menu Items
        $homeItem = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Trang chủ',
            'route' => 'home',
            'icon' => 'fas fa-home',
            'order' => 1,
            'is_active' => true
        ]);

        $productsItem = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Sản phẩm',
            'route' => 'products.index',
            'icon' => 'fas fa-shopping-bag',
            'order' => 2,
            'is_active' => true
        ]);

        // Get some categories for submenu
        $categories = \App\Category::take(5)->get();
        $order = 1;
        foreach ($categories as $category) {
            MenuItem::create([
                'menu_id' => $headerMenu->id,
                'parent_id' => $productsItem->id,
                'title' => $category->name,
                'url' => '/categories/' . $category->slug,
                'order' => $order++,
                'is_active' => true
            ]);
        }

        $blogItem = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Blog',
            'route' => 'blog.index',
            'icon' => 'fas fa-blog',
            'order' => 3,
            'is_active' => true
        ]);

        $aboutItem = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Giới thiệu',
            'url' => '/about',
            'icon' => 'fas fa-info-circle',
            'order' => 4,
            'is_active' => true
        ]);

        $contactItem = MenuItem::create([
            'menu_id' => $headerMenu->id,
            'title' => 'Liên hệ',
            'url' => '/contact',
            'icon' => 'fas fa-envelope',
            'order' => 5,
            'is_active' => true
        ]);

        // Create Footer Menu
        $footerMenu = Menu::create([
            'name' => 'Menu Footer',
            'slug' => 'footer-menu',
            'location' => 'footer',
            'description' => 'Menu hiển thị ở footer',
            'is_active' => true,
            'order' => 2
        ]);

        // Footer Menu Items - Quick Links
        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Về chúng tôi',
            'url' => '/about',
            'order' => 1,
            'is_active' => true
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Điều khoản sử dụng',
            'url' => '/terms',
            'order' => 2,
            'is_active' => true
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Chính sách bảo mật',
            'url' => '/privacy',
            'order' => 3,
            'is_active' => true
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Hướng dẫn mua hàng',
            'url' => '/shopping-guide',
            'order' => 4,
            'is_active' => true
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'title' => 'Chính sách đổi trả',
            'url' => '/return-policy',
            'order' => 5,
            'is_active' => true
        ]);

        // Create Mobile Menu
        $mobileMenu = Menu::create([
            'name' => 'Menu Mobile',
            'slug' => 'mobile-menu',
            'location' => 'mobile',
            'description' => 'Menu dành cho thiết bị di động',
            'is_active' => true,
            'order' => 3
        ]);

        // Mobile menu items (simplified version of header menu)
        MenuItem::create([
            'menu_id' => $mobileMenu->id,
            'title' => 'Trang chủ',
            'route' => 'home',
            'icon' => 'fas fa-home',
            'order' => 1,
            'is_active' => true
        ]);

        MenuItem::create([
            'menu_id' => $mobileMenu->id,
            'title' => 'Sản phẩm',
            'route' => 'products.index',
            'icon' => 'fas fa-shopping-bag',
            'order' => 2,
            'is_active' => true
        ]);

        MenuItem::create([
            'menu_id' => $mobileMenu->id,
            'title' => 'Blog',
            'route' => 'blog.index',
            'icon' => 'fas fa-blog',
            'order' => 3,
            'is_active' => true
        ]);

        MenuItem::create([
            'menu_id' => $mobileMenu->id,
            'title' => 'Tài khoản',
            'url' => '/account',
            'icon' => 'fas fa-user',
            'order' => 4,
            'is_active' => true
        ]);

        $this->command->info('Menu seeded successfully!');
        $this->command->info('- Main Menu (Header): ' . $headerMenu->allItems()->count() . ' items');
        $this->command->info('- Footer Menu: ' . $footerMenu->allItems()->count() . ' items');
        $this->command->info('- Mobile Menu: ' . $mobileMenu->allItems()->count() . ' items');
    }
}
