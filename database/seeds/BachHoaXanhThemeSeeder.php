<?php

use Illuminate\Database\Seeder;
use App\Theme;

class BachHoaXanhThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if Bach Hoa Xanh theme already exists
        $existingTheme = Theme::where('slug', 'bach-hoa-xanh-theme')->first();

        if ($existingTheme) {
            echo "Bach Hoa Xanh theme already exists!\n";
            return;
        }

        // Create Bach Hoa Xanh Theme
        Theme::create([
            'name' => 'Bách Hóa Xanh Theme',
            'slug' => 'bach-hoa-xanh-theme',
            'view_path' => 'themes.bachoaxanh.home',
            'description' => 'Theme grocery store chuyên nghiệp với màu xanh lá chủ đạo, sidebar danh mục chi tiết, flash sale banner và layout tối ưu. Thiết kế giống Bách Hóa Xanh.',
            'thumbnail' => null,
            'author' => 'VoShop Team',
            'version' => 'v1.0.0',
            'settings' => json_encode([
                'primary_color' => '#0fa83a',
                'secondary_color' => '#0c8c31',
                'accent_color' => '#ffc107',
                'show_top_bar' => true,
                'show_sidebar' => true,
                'show_flash_sale' => true,
                'show_category_pills' => true,
                'products_per_row' => 6,
            ]),
            'is_active' => false,
            'is_default' => false,
            'order' => 4,
        ]);

        echo "Bach Hoa Xanh theme seeded successfully!\n";
    }
}
