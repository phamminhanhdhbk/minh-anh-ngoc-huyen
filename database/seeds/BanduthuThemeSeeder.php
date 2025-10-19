<?php

use Illuminate\Database\Seeder;
use App\Theme;

class BanduthuThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if Banduthu theme already exists
        $existingTheme = Theme::where('slug', 'banduthu-theme')->first();

        if ($existingTheme) {
            echo "Banduthu theme already exists!\n";
            return;
        }

        // Create Banduthu Theme
        Theme::create([
            'name' => 'Banduthu Theme',
            'slug' => 'banduthu-theme',
            'view_path' => 'themes.banduthu.home',
            'description' => 'Theme e-commerce chuyên nghiệp với màu đỏ-cam chủ đạo, sidebar danh mục, banner slider và sản phẩm bán chạy. Thiết kế giống Banduthu.com',
            'thumbnail' => null,
            'author' => 'VoShop Team',
            'version' => 'v1.0.0',
            'settings' => json_encode([
                'primary_color' => '#dc3545',
                'secondary_color' => '#ffc107',
                'accent_color' => '#0d6efd',
                'show_top_bar' => true,
                'show_sidebar' => true,
                'show_hot_deals' => true,
                'products_per_row' => 4,
            ]),
            'is_active' => false,
            'is_default' => false,
            'order' => 3,
        ]);

        echo "Banduthu theme seeded successfully!\n";
    }
}
