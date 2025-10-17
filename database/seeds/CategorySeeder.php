<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Điện thoại & Tablet',
                'slug' => 'dien-thoai-tablet',
                'description' => 'Điện thoại thông minh và máy tính bảng các loại',
                'status' => true,
            ],
            [
                'name' => 'Laptop & Máy tính',
                'slug' => 'laptop-may-tinh',
                'description' => 'Laptop, PC và phụ kiện máy tính',
                'status' => true,
            ],
            [
                'name' => 'Thời trang',
                'slug' => 'thoi-trang',
                'description' => 'Quần áo, giày dép và phụ kiện thời trang',
                'status' => true,
            ],
            [
                'name' => 'Đồ gia dụng',
                'slug' => 'do-gia-dung',
                'description' => 'Đồ dùng trong gia đình, nhà bếp',
                'status' => true,
            ],
            [
                'name' => 'Sách & Văn phòng phẩm',
                'slug' => 'sach-van-phong-pham',
                'description' => 'Sách, truyện và dụng cụ văn phòng',
                'status' => true,
            ],
            [
                'name' => 'Thể thao & Du lịch',
                'slug' => 'the-thao-du-lich',
                'description' => 'Dụng cụ thể thao và đồ du lịch',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
