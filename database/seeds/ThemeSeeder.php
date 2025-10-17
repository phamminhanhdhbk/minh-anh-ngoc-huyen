<?php

use Illuminate\Database\Seeder;
use App\Theme;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing themes
        Theme::truncate();

        // Default Theme (current welcome.blade.php)
        Theme::create([
            'name' => 'Classic Theme',
            'slug' => 'classic',
            'view_path' => 'welcome',
            'description' => 'Theme mặc định với thiết kế đơn giản, chuyên nghiệp',
            'author' => 'VoShop Team',
            'version' => '1.0.0',
            'is_active' => false,
            'is_default' => true,
            'order' => 1
        ]);

        // Gradient Theme (new modern theme)
        Theme::create([
            'name' => 'Gradient Modern',
            'slug' => 'gradient-modern',
            'view_path' => 'themes.gradient.home',
            'description' => 'Theme hiện đại với gradient màu tím-xanh đẹp mắt, animation mượt mà',
            'author' => 'VoShop Team',
            'version' => '1.0.0',
            'is_active' => true,
            'is_default' => false,
            'order' => 2,
            'settings' => [
                'primary_color' => '#667eea',
                'secondary_color' => '#764ba2',
                'accent_color' => '#f093fb'
            ]
        ]);

        $this->command->info('Themes seeded successfully!');
        $this->command->info('- Classic Theme (default)');
        $this->command->info('- Gradient Modern (active)');
    }
}
