<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            // Điện thoại & Tablet (category_id: 1)
            [
                'name' => 'iPhone 15 Pro Max',
                'slug' => 'iphone-15-pro-max',
                'description' => 'iPhone 15 Pro Max với chip A17 Pro mạnh mẽ, camera 48MP chất lượng cao và thiết kế titan cao cấp.',
                'price' => 29900000,
                'sale_price' => 27900000,
                'stock' => 50,
                'sku' => 'IP15PM-256',
                'image' => 'https://via.placeholder.com/400x400/007bff/ffffff?text=iPhone+15+Pro+Max',
                'status' => true,
                'featured' => true,
                'category_id' => 1,
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'description' => 'Galaxy S24 Ultra với S Pen tích hợp, camera 200MP và hiệu suất vượt trội.',
                'price' => 26900000,
                'sale_price' => 24900000,
                'stock' => 30,
                'sku' => 'SS24U-256',
                'image' => 'https://via.placeholder.com/400x400/28a745/ffffff?text=Galaxy+S24+Ultra',
                'status' => true,
                'featured' => true,
                'category_id' => 1,
            ],
            [
                'name' => 'iPad Pro M2 11 inch',
                'slug' => 'ipad-pro-m2-11-inch',
                'description' => 'iPad Pro với chip M2 mạnh mẽ, màn hình Liquid Retina và hỗ trợ Apple Pencil.',
                'price' => 22900000,
                'sale_price' => 21900000,
                'stock' => 25,
                'sku' => 'IPP11-256',
                'image' => 'https://via.placeholder.com/400x400/6f42c1/ffffff?text=iPad+Pro+M2',
                'status' => true,
                'featured' => false,
                'category_id' => 1,
            ],

            // Laptop & Máy tính (category_id: 2)
            [
                'name' => 'MacBook Air M2',
                'slug' => 'macbook-air-m2',
                'description' => 'MacBook Air với chip M2 thế hệ mới, thiết kế mỏng nhẹ và thời lượng pin lâu dài.',
                'price' => 28900000,
                'sale_price' => 26900000,
                'stock' => 20,
                'sku' => 'MBA-M2-256',
                'image' => 'https://via.placeholder.com/400x400/dc3545/ffffff?text=MacBook+Air+M2',
                'status' => true,
                'featured' => true,
                'category_id' => 2,
            ],
            [
                'name' => 'Dell XPS 13',
                'slug' => 'dell-xps-13',
                'description' => 'Dell XPS 13 với màn hình InfinityEdge, hiệu suất cao và thiết kế premium.',
                'price' => 24900000,
                'sale_price' => 22900000,
                'stock' => 15,
                'sku' => 'DXPS13-512',
                'image' => 'https://via.placeholder.com/400x400/fd7e14/ffffff?text=Dell+XPS+13',
                'status' => true,
                'featured' => true,
                'category_id' => 2,
            ],

            // Thời trang (category_id: 3)
            [
                'name' => 'Áo thun Nike Dri-FIT',
                'slug' => 'ao-thun-nike-dri-fit',
                'description' => 'Áo thun Nike với công nghệ Dri-FIT thấm hút mồ hôi, thoải mái khi vận động.',
                'price' => 890000,
                'sale_price' => 690000,
                'stock' => 100,
                'sku' => 'NIKE-DF-001',
                'image' => 'https://via.placeholder.com/400x400/20c997/ffffff?text=Nike+Dri-FIT',
                'status' => true,
                'featured' => false,
                'category_id' => 3,
            ],
            [
                'name' => 'Giày Adidas Ultraboost',
                'slug' => 'giay-adidas-ultraboost',
                'description' => 'Giày chạy Adidas Ultraboost với đế Boost êm ái và thiết kế năng động.',
                'price' => 3200000,
                'sale_price' => 2800000,
                'stock' => 40,
                'sku' => 'ADS-UB-42',
                'image' => 'https://via.placeholder.com/400x400/6610f2/ffffff?text=Adidas+Ultraboost',
                'status' => true,
                'featured' => true,
                'category_id' => 3,
            ],

            // Đồ gia dụng (category_id: 4)
            [
                'name' => 'Nồi cơm điện Panasonic 1.8L',
                'slug' => 'noi-com-dien-panasonic-1-8l',
                'description' => 'Nồi cơm điện Panasonic 1.8L với lòng nồi chống dính và chế độ hẹn giờ.',
                'price' => 1200000,
                'sale_price' => 990000,
                'stock' => 60,
                'sku' => 'PAN-RC-18',
                'image' => 'https://via.placeholder.com/400x400/e83e8c/ffffff?text=Noi+Com+Panasonic',
                'status' => true,
                'featured' => false,
                'category_id' => 4,
            ],

            // Sách & Văn phòng phẩm (category_id: 5)
            [
                'name' => 'Bộ bút bi Thiên Long',
                'slug' => 'bo-but-bi-thien-long',
                'description' => 'Bộ 10 cây bút bi Thiên Long chất lượng cao, mực xanh êm trượt.',
                'price' => 50000,
                'sale_price' => 40000,
                'stock' => 200,
                'sku' => 'TL-BB-10',
                'image' => 'https://via.placeholder.com/400x400/0dcaf0/ffffff?text=But+Bi+Thien+Long',
                'status' => true,
                'featured' => false,
                'category_id' => 5,
            ],

            // Thể thao & Du lịch (category_id: 6)
            [
                'name' => 'Balo du lịch Samsonite 35L',
                'slug' => 'balo-du-lich-samsonite-35l',
                'description' => 'Balo du lịch Samsonite 35L chống nước, nhiều ngăn tiện dụng và thiết kế ergonomic.',
                'price' => 1800000,
                'sale_price' => 1500000,
                'stock' => 35,
                'sku' => 'SMS-BP-35',
                'image' => 'https://via.placeholder.com/400x400/198754/ffffff?text=Balo+Samsonite',
                'status' => true,
                'featured' => true,
                'category_id' => 6,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
