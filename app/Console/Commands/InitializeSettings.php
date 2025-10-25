<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SiteSetting;

class InitializeSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:initialize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize default site settings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Initializing default site settings...');

        $defaultSettings = [
            // General
            [
                'key' => 'site_name',
                'value' => 'Shop VO',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Tên trang web',
                'description' => 'Tên hiển thị của trang web',
                'sort_order' => 1
            ],
            [
                'key' => 'site_description',
                'value' => 'Cửa hàng trực tuyến uy tín, chất lượng cao với giá cả hợp lý.',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Mô tả trang web',
                'description' => 'Mô tả ngắn về trang web',
                'sort_order' => 2
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'label' => 'Logo trang web',
                'description' => 'Logo hiển thị trên header',
                'sort_order' => 3
            ],

            // Contact
            [
                'key' => 'contact_phone',
                'value' => '0123 456 789',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Số điện thoại',
                'description' => 'Số điện thoại liên hệ',
                'sort_order' => 1
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@shopvo.com',
                'type' => 'email',
                'group' => 'contact',
                'label' => 'Email liên hệ',
                'description' => 'Email để khách hàng liên hệ',
                'sort_order' => 2
            ],
            [
                'key' => 'contact_address',
                'value' => '123 Đường ABC, Quận 1, TP.HCM',
                'type' => 'textarea',
                'group' => 'contact',
                'label' => 'Địa chỉ',
                'description' => 'Địa chỉ cửa hàng',
                'sort_order' => 3
            ],

            // Social
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/shopvo',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Link trang Facebook',
                'sort_order' => 1
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/shopvo',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Link trang Instagram',
                'sort_order' => 2
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/shopvo',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Twitter URL',
                'description' => 'Link trang Twitter',
                'sort_order' => 3
            ],
            [
                'key' => 'social_zalo',
                'value' => 'https://zalo.me/shopvo',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Zalo URL',
                'description' => 'Link trang Zalo',
                'sort_order' => 4
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/@shopvo',
                'type' => 'url',
                'group' => 'social',
                'label' => 'YouTube URL',
                'description' => 'Link trang YouTube',
                'sort_order' => 5
            ],
            [
                'key' => 'social_tiktok',
                'value' => 'https://www.tiktok.com/@shopvo',
                'type' => 'url',
                'group' => 'social',
                'label' => 'TikTok URL',
                'description' => 'Link trang TikTok',
                'sort_order' => 6
            ],

            // Business
            [
                'key' => 'business_hours',
                'value' => 'Thứ 2 - Chủ nhật: 8:00 - 22:00',
                'type' => 'text',
                'group' => 'business',
                'label' => 'Giờ làm việc',
                'description' => 'Giờ mở cửa của cửa hàng',
                'sort_order' => 1
            ],
            [
                'key' => 'free_shipping_amount',
                'value' => '500000',
                'type' => 'number',
                'group' => 'business',
                'label' => 'Miễn phí ship từ',
                'description' => 'Số tiền tối thiểu để miễn phí vận chuyển (VND)',
                'sort_order' => 2
            ],
            [
                'key' => 'enable_reviews',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'business',
                'label' => 'Cho phép đánh giá',
                'description' => 'Cho phép khách hàng đánh giá sản phẩm',
                'sort_order' => 3
            ]
        ];

        foreach ($defaultSettings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
            $this->line('✓ ' . $setting['label']);
        }

        SiteSetting::clearCache();

        $this->info('Settings initialized successfully!');
        return 0;
    }
}
