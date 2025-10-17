<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SiteSetting;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $settings = SiteSetting::getAllGrouped();
        return view('admin.settings.index', compact('settings'));
    }

    public function edit()
    {
        $settings = SiteSetting::getAllGrouped();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();

            if ($setting) {
                // Handle file uploads for image type
                if ($setting->type === 'image' && $request->hasFile("files.{$key}")) {
                    // Delete old image
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }

                    // Store new image
                    $file = $request->file("files.{$key}");
                    $path = $file->store('settings', 'public');
                    $value = $path;
                }

                // Handle boolean values
                if ($setting->type === 'boolean') {
                    $value = $request->has("settings.{$key}") ? '1' : '0';
                }

                $setting->update(['value' => $value]);
            }
        }

        // Clear cache
        SiteSetting::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Cấu hình trang web đã được cập nhật thành công!');
    }

    public function reset()
    {
        $this->createDefaultSettings();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Đã khôi phục cấu hình mặc định!');
    }

    /**
     * Create default settings
     */
    public function createDefaultSettings()
    {
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
        }

        SiteSetting::clearCache();
    }
}
