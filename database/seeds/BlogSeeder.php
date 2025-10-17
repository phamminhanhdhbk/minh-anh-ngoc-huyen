<?php

use Illuminate\Database\Seeder;
use App\BlogCategory;
use App\Post;
use App\PostTag;
use App\User;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo Blog Categories
        $categories = [
            [
                'name' => 'Tin tức công nghệ',
                'slug' => 'tin-tuc-cong-nghe',
                'description' => 'Cập nhật tin tức công nghệ mới nhất, xu hướng và đổi mới trong ngành',
                'status' => 1,
                'order' => 1
            ],
            [
                'name' => 'Hướng dẫn sử dụng',
                'slug' => 'huong-dan-su-dung',
                'description' => 'Các bài hướng dẫn chi tiết về cách sử dụng sản phẩm',
                'status' => 1,
                'order' => 2
            ],
            [
                'name' => 'Review sản phẩm',
                'slug' => 'review-san-pham',
                'description' => 'Đánh giá chi tiết các sản phẩm công nghệ',
                'status' => 1,
                'order' => 3
            ],
            [
                'name' => 'Khuyến mãi',
                'slug' => 'khuyen-mai',
                'description' => 'Thông tin về các chương trình khuyến mãi hấp dẫn',
                'status' => 1,
                'order' => 4
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::create($category);
        }

        // Tạo Tags
        $tags = [
            ['name' => 'Smartphone', 'slug' => 'smartphone'],
            ['name' => 'Laptop', 'slug' => 'laptop'],
            ['name' => 'Gaming', 'slug' => 'gaming'],
            ['name' => 'AI', 'slug' => 'ai'],
            ['name' => 'Tips & Tricks', 'slug' => 'tips-tricks'],
            ['name' => 'Review', 'slug' => 'review'],
            ['name' => 'Tutorial', 'slug' => 'tutorial'],
            ['name' => 'Deal', 'slug' => 'deal'],
        ];

        foreach ($tags as $tag) {
            PostTag::create($tag);
        }

        // Lấy user admin (giả sử user đầu tiên là admin)
        $adminUser = User::first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'is_admin' => 1,
            ]);
        }

        // Tạo 10 bài viết mẫu
        $posts = [
            [
                'title' => 'Top 5 Smartphone Đáng Mua Nhất Năm 2025',
                'excerpt' => 'Khám phá 5 chiếc smartphone được đánh giá cao nhất trong năm 2025 với công nghệ tiên tiến và giá cả hợp lý.',
                'content' => "Năm 2025 đánh dấu bước tiến mới trong công nghệ smartphone với nhiều đột phá về AI, camera và hiệu năng. Dưới đây là 5 chiếc smartphone đáng mua nhất:\n\n1. Samsung Galaxy S25 Ultra\nVới chip Snapdragon 8 Gen 3, camera 200MP và màn hình Dynamic AMOLED 2X 6.8 inch, Galaxy S25 Ultra là sự lựa chọn hoàn hảo cho những ai yêu thích nhiếp ảnh.\n\n2. iPhone 16 Pro Max\nApple tiếp tục dẫn đầu với chip A18 Bionic, hệ thống camera ProRAW và khả năng quay video 8K 60fps.\n\n3. Xiaomi 14 Pro\nVới giá cả phải chăng nhưng cấu hình mạnh mẽ, Xiaomi 14 Pro trang bị chip Snapdragon 8 Gen 3 và camera Leica.\n\n4. OnePlus 12\nThiết kế sang trọng, màn hình LTPO 120Hz và sạc nhanh 100W làm nên sức hút của OnePlus 12.\n\n5. Google Pixel 9 Pro\nKhả năng xử lý ảnh bằng AI vượt trội và Android gốc luôn được cập nhật sớm nhất.\n\nMỗi chiếc đều có ưu điểm riêng, tùy thuộc vào nhu cầu và ngân sách của bạn!",
                'category_id' => 1,
                'status' => 'published',
                'featured' => 1,
                'views' => rand(500, 2000),
                'tags' => [1, 6] // Smartphone, Review
            ],
            [
                'title' => 'Cách Tối Ưu Hiệu Năng Laptop Để Chơi Game Mượt Mà',
                'excerpt' => 'Hướng dẫn chi tiết các bước tối ưu hóa laptop gaming để đạt hiệu năng cao nhất.',
                'content' => "Laptop gaming là công cụ không thể thiếu cho game thủ, nhưng để đạt hiệu năng tối đa cần có những bước tối ưu hóa đúng cách:\n\n1. Cập nhật Driver Card Đồ Họa\nLuôn cập nhật driver mới nhất từ NVIDIA hoặc AMD để tận dụng tối đa hiệu năng GPU.\n\n2. Tắt Các Ứng Dụng Chạy Nền\nSử dụng Task Manager để đóng các ứng dụng không cần thiết, giải phóng RAM và CPU.\n\n3. Điều Chỉnh Cài Đặt Điện Năng\nChọn chế độ High Performance trong Power Options để CPU chạy ở tốc độ tối đa.\n\n4. Vệ Sinh Và Thay Keo Tản Nhiệt\nLàm sạch quạt tản nhiệt và thay keo tản nhiệt định kỳ để tránh thermal throttling.\n\n5. Nâng Cấp RAM Và SSD\nNâng cấp lên 16GB RAM và SSD NVMe sẽ cải thiện đáng kể tốc độ load game.\n\n6. Sử Dụng Cooling Pad\nMột đế tản nhiệt tốt giúp giảm nhiệt độ 5-10 độ C.\n\nÁp dụng những mẹo này, laptop của bạn sẽ chạy mượt mà hơn rất nhiều!",
                'category_id' => 2,
                'status' => 'published',
                'featured' => 1,
                'views' => rand(800, 1500),
                'tags' => [2, 3, 5, 7] // Laptop, Gaming, Tips, Tutorial
            ],
            [
                'title' => 'Trí Tuệ Nhân Tạo AI Đang Thay Đổi Cuộc Sống Như Thế Nào?',
                'excerpt' => 'Khám phá những ứng dụng thực tế của AI trong đời sống hàng ngày và tương lai của công nghệ này.',
                'content' => "Trí tuệ nhân tạo (AI) không còn là khái niệm xa vời mà đã trở thành một phần không thể thiếu trong cuộc sống:\n\n1. AI Trong Smartphone\nTrợ lý ảo như Siri, Google Assistant giúp bạn điều khiển thiết bị bằng giọng nói, camera AI cải thiện chất lượng ảnh.\n\n2. AI Trong Y Tế\nChẩn đoán bệnh nhanh hơn và chính xác hơn nhờ phân tích hình ảnh y khoa bằng AI.\n\n3. AI Trong Giáo Dục\nCá nhân hóa trải nghiệm học tập, đánh giá năng lực học sinh và hỗ trợ giáo viên.\n\n4. AI Trong Giao Thông\nXe tự lái, hệ thống điều khiển đèn tín hiệu thông minh giảm ùn tắc.\n\n5. AI Trong Thương Mại Điện Tử\nGợi ý sản phẩm cá nhân hóa, chatbot hỗ trợ khách hàng 24/7.\n\nTương Lai Của AI\nVới sự phát triển của GPT-4, Claude và các mô hình AI khác, chúng ta sẽ thấy AI trở nên thông minh và hữu ích hơn bao giờ hết.\n\nAI không phải để thay thế con người mà để hỗ trợ và nâng cao năng suất!",
                'category_id' => 1,
                'status' => 'published',
                'featured' => 1,
                'views' => rand(1000, 2500),
                'tags' => [4] // AI
            ],
            [
                'title' => 'Review Chi Tiết MacBook Pro M3: Có Đáng Đồng Tiền Bát Gạo?',
                'excerpt' => 'Đánh giá toàn diện về MacBook Pro M3 sau 1 tháng sử dụng thực tế.',
                'content' => "Sau 1 tháng sử dụng MacBook Pro M3, đây là đánh giá chi tiết của tôi:\n\nƯu Điểm:\n\n1. Hiệu Năng Vượt Trội\nChip M3 Pro với 12 nhân CPU và 18 nhân GPU xử lý mượt mà mọi tác vụ từ editing video 4K đến render 3D.\n\n2. Thời Lượng Pin Ấn Tượng\nDùng liên tục 12-14 giờ cho công việc văn phòng, 8-10 giờ cho editing video.\n\n3. Màn Hình Liquid Retina XDR\nĐộ sáng 1600 nits, HDR, màu sắc chuẩn xác cho công việc đồ họa.\n\n4. Bàn Phím Và Trackpad\nBàn phím Magic Keyboard gõ êm, trackpad lớn và mượt mà.\n\n5. Hệ Sinh Thái Apple\nTích hợp hoàn hảo với iPhone, iPad, AirPods.\n\nNhược Điểm:\n\n1. Giá Thành Cao\nGiá từ 50 triệu VNĐ, không phải ai cũng có thể chi trả.\n\n2. Thiếu Cổng USB-A\nChỉ có cổng USB-C/Thunderbolt 4, cần hub để kết nối thiết bị cũ.\n\n3. Không Thể Nâng Cấp\nRAM và SSD hàn chết, không thể nâng cấp sau này.\n\nKết Luận:\nMacBook Pro M3 là lựa chọn tuyệt vời cho dân chuyên nghiệp cần hiệu năng cao. Tuy nhiên, với mức giá này, cân nhắc kỹ nhu cầu thực tế của bạn!",
                'category_id' => 3,
                'status' => 'published',
                'featured' => 0,
                'views' => rand(600, 1200),
                'tags' => [2, 6] // Laptop, Review
            ],
            [
                'title' => 'Flash Sale Cuối Tuần: Giảm Giá Đến 50% Toàn Bộ Sản Phẩm',
                'excerpt' => 'Đừng bỏ lỡ cơ hội mua sắm với ưu đãi khủng trong dịp cuối tuần này!',
                'content' => "🔥 FLASH SALE CUỐI TUẦN - GIẢM GIÁ ĐẾN 50% 🔥\n\nThời gian: 48 giờ duy nhất từ 00:00 Thứ 7 đến 23:59 Chủ Nhật\n\nCác Deal Hot:\n\n1. Smartphone\n- iPhone 15 Pro: Giảm 20% (Còn 23.990.000đ)\n- Samsung Galaxy S24: Giảm 30% (Còn 17.490.000đ)\n- Xiaomi 14: Giảm 35% (Còn 12.990.000đ)\n\n2. Laptop\n- MacBook Air M2: Giảm 15% (Còn 25.490.000đ)\n- Dell XPS 15: Giảm 25% (Còn 33.740.000đ)\n- ASUS ROG Zephyrus G14: Giảm 30% (Còn 34.990.000đ)\n\n3. Tai Nghe\n- AirPods Pro 2: Giảm 20% (Còn 5.590.000đ)\n- Sony WH-1000XM5: Giảm 25% (Còn 7.490.000đ)\n- Samsung Galaxy Buds 2 Pro: Giảm 35% (Còn 3.240.000đ)\n\n4. Phụ Kiện\n- Sạc dự phòng: Giảm 50%\n- Ốp lưng, dán màn hình: Giảm 40%\n- Cáp sạc, tai nghe có dây: Giảm 45%\n\nƯu Đãi Thêm:\n✅ Miễn phí vận chuyển toàn quốc\n✅ Tặng voucher 500K cho đơn hàng từ 10 triệu\n✅ Trả góp 0% lãi suất 12 tháng\n✅ Bảo hành mở rộng thêm 6 tháng\n\nGhi Chú: Số lượng có hạn, nhanh tay đặt hàng ngay!\n\nLink đặt hàng: [Xem ngay]",
                'category_id' => 4,
                'status' => 'published',
                'featured' => 1,
                'views' => rand(2000, 3000),
                'tags' => [1, 2, 8] // Smartphone, Laptop, Deal
            ],
            [
                'title' => 'Hướng Dẫn Chụp Ảnh Đẹp Bằng Smartphone: 10 Mẹo Cho Người Mới',
                'excerpt' => 'Biến smartphone thành máy ảnh chuyên nghiệp với 10 mẹo chụp ảnh đơn giản nhưng hiệu quả.',
                'content' => "Smartphone ngày nay có camera rất tốt, nhưng để có ảnh đẹp cần biết cách:\n\n1. Làm Sạch Ống Kính\nVệ sinh ống kính camera thường xuyên, dùng khăn mềm lau nhẹ.\n\n2. Sử Dụng Ánh Sáng Tự Nhiên\nChụp vào buổi sáng sớm hoặc chiều muộn (Golden Hour) cho ánh sáng đẹp nhất.\n\n3. Quy Tắc 1/3 (Rule of Thirds)\nBật lưới 3x3 trong cài đặt camera, đặt chủ thể ở giao điểm các đường.\n\n4. Tránh Dùng Zoom Kỹ Thuật Số\nDi chuyển gần hơn thay vì zoom, tránh ảnh bị vỡ hạt.\n\n5. Chụp Ở Độ Phân Giải Cao Nhất\nBật chế độ 48MP hoặc 50MP nếu máy hỗ trợ.\n\n6. Sử Dụng Chế Độ HDR\nBật HDR cho ảnh có độ tương phản tốt hơn.\n\n7. Chụp Chân Dung Với Chế Độ Portrait\nLàm mờ nền tự nhiên, làm nổi bật chủ thể.\n\n8. Ổn Định Máy Khi Chụp\nDùng 2 tay cầm, tựa vào tường hoặc dùng tripod.\n\n9. Thử Nghiệm Các Góc Độ\nChụp từ trên cao, dưới thấp, nghiêng để có ảnh độc đáo.\n\n10. Chỉnh Sửa Sau Chụp\nDùng app như Lightroom Mobile, Snapseed để tinh chỉnh màu sắc, độ sáng.\n\nThực hành nhiều là cách tốt nhất để tiến bộ!",
                'category_id' => 2,
                'status' => 'published',
                'featured' => 0,
                'views' => rand(700, 1400),
                'tags' => [1, 5, 7] // Smartphone, Tips, Tutorial
            ],
            [
                'title' => 'Top 5 Game Mobile Hay Nhất Tháng 10/2025',
                'excerpt' => 'Tổng hợp những tựa game mobile đáng chơi nhất trong tháng này.',
                'content' => "Tháng 10/2025 chứng kiến sự ra mắt của nhiều tựa game mobile hấp dẫn:\n\n1. Genshin Impact 5.0\nCập nhật bản đồ Natlan mới, nhân vật Pyro 5 sao cực mạnh. Đồ họa tuyệt đẹp, gameplay hấp dẫn.\n\n2. Honor of Kings: World\nPhiên bản thế giới mở của game MOBA nổi tiếng. Đồ họa UE4, thế giới rộng lớn để khám phá.\n\n3. Mobile Legends: Bang Bang\nVẫn là tựa game MOBA phổ biến nhất Việt Nam với cập nhật tướng mới liên tục.\n\n4. PUBG Mobile 3.0\nBản đồ Erangel 2.0 được làm mới hoàn toàn, thêm nhiều cơ chế gameplay mới.\n\n5. Wuthering Waves\nGame nhập vai hành động thế giới mở với combat system sâu sắc, đồ họa đỉnh cao.\n\nYêu Cầu Cấu Hình:\n- RAM: Tối thiểu 6GB, khuyến nghị 8GB trở lên\n- Chip: Snapdragon 870 trở lên hoặc tương đương\n- Dung lượng: 5-15GB tùy game\n\nMẹo Chơi Game Mượt:\n- Đóng ứng dụng chạy nền\n- Bật Game Mode/Performance Mode\n- Điều chỉnh cài đặt đồ họa phù hợp với máy\n- Sử dụng wifi ổn định cho game online\n\nChúc bạn có những giờ phút giải trí vui vẻ!",
                'category_id' => 1,
                'status' => 'published',
                'featured' => 0,
                'views' => rand(900, 1600),
                'tags' => [1, 3] // Smartphone, Gaming
            ],
            [
                'title' => 'So Sánh Windows 11 Vs macOS Sonoma: Chọn Hệ Điều Hành Nào?',
                'excerpt' => 'Phân tích ưu nhược điểm của Windows 11 và macOS Sonoma để giúp bạn đưa ra lựa chọn phù hợp.',
                'content' => "Chọn hệ điều hành nào giữa Windows 11 và macOS Sonoma? Hãy xem so sánh chi tiết:\n\n🪟 WINDOWS 11\n\nƯu Điểm:\n- Tương thích với hầu hết phần mềm và game\n- Đa dạng thiết bị từ bình dân đến cao cấp\n- Dễ nâng cấp phần cứng\n- Hỗ trợ gaming tốt với DirectX 12\n- Giá thành laptop Windows thấp hơn\n\nNhược Điểm:\n- Có thể gặp lỗi và cần update thường xuyên\n- Bảo mật kém hơn macOS\n- Giao diện chưa nhất quán giữa các ứng dụng\n- Tối ưu pin kém hơn\n\n🍎 macOS SONOMA\n\nƯu Điểm:\n- Giao diện đẹp, nhất quán\n- Tối ưu hóa tốt với chip Apple Silicon\n- Bảo mật cao, ít virus\n- Tích hợp hoàn hảo với iPhone, iPad\n- Pin trâu hơn Windows\n- Ít lag, crash\n\nNhược Điểm:\n- Giá máy đắt\n- Ít phần mềm và game hơn Windows\n- Không thể nâng cấp phần cứng\n- Khó sửa chữa\n- Hạn chế tùy chỉnh\n\nKẾT LUẬN:\n\n✅ Chọn Windows nếu:\n- Ngân sách hạn chế\n- Cần chơi game\n- Làm việc với phần mềm chuyên dụng chỉ có trên Windows\n- Thích tùy biến và nâng cấp\n\n✅ Chọn macOS nếu:\n- Ngân sách thoải mái\n- Làm design, video editing\n- Có iPhone, iPad và muốn đồng bộ\n- Ưu tiên trải nghiệm mượt mà và bảo mật\n\nKhông có hệ điều hành nào tốt nhất, chỉ có hệ điều hành phù hợp nhất!",
                'category_id' => 1,
                'status' => 'published',
                'featured' => 0,
                'views' => rand(800, 1300),
                'tags' => [2] // Laptop
            ],
            [
                'title' => 'Bảo Mật Smartphone: 7 Cách Bảo Vệ Dữ Liệu Cá Nhân',
                'excerpt' => 'Hướng dẫn toàn diện về cách bảo vệ thông tin cá nhân trên smartphone khỏi tin tặc và phần mềm độc hại.',
                'content' => "Smartphone chứa rất nhiều thông tin cá nhân. Bảo vệ chúng là điều cần thiết:\n\n1. Sử Dụng Mật Khẩu/Vân Tay/FaceID Mạnh\n- Đặt mật khẩu ít nhất 6 số\n- Bật xác thực sinh trắc học\n- Không dùng mật khẩu đơn giản như 123456\n\n2. Cập Nhật Hệ Điều Hành Thường Xuyên\n- Luôn update phiên bản mới nhất\n- Các bản vá bảo mật rất quan trọng\n- Bật tự động cập nhật\n\n3. Chỉ Tải App Từ Nguồn Chính Thống\n- App Store cho iOS\n- Google Play cho Android\n- Tránh cài APK từ nguồn không rõ\n\n4. Kiểm Tra Quyền Ứng Dụng\n- Xem lại quyền truy cập của mỗi app\n- Tắt quyền không cần thiết\n- Chỉ cho phép khi đang sử dụng app\n\n5. Sử Dụng VPN Khi Dùng WiFi Công Cộng\n- Mã hóa kết nối internet\n- Bảo vệ thông tin khi dùng WiFi free\n- Chọn VPN uy tín như NordVPN, ExpressVPN\n\n6. Bật Tính Năng Find My Device\n- Có thể định vị máy khi bị mất\n- Xóa dữ liệu từ xa\n- Khóa máy từ xa\n\n7. Sao Lưu Dữ Liệu Định Kỳ\n- Backup lên cloud (iCloud, Google Drive)\n- Sao lưu vào máy tính\n- Đảm bảo không mất dữ liệu quan trọng\n\nBonus: Những Điều Nên Tránh\n❌ Không click vào link lạ trong tin nhắn\n❌ Không kết nối với WiFi không mật khẩu\n❌ Không để máy không khóa nơi công cộng\n❌ Không chia sẻ mật khẩu với người khác\n\nBảo mật là trách nhiệm của mỗi người dùng!",
                'category_id' => 2,
                'status' => 'published',
                'featured' => 0,
                'views' => rand(600, 1100),
                'tags' => [1, 5, 7] // Smartphone, Tips, Tutorial
            ],
            [
                'title' => 'Xu Hướng Công Nghệ 2025: Những Gì Sẽ Thay Đổi Cuộc Sống',
                'excerpt' => 'Dự đoán và phân tích các xu hướng công nghệ nổi bật sẽ định hình tương lai trong năm 2025.',
                'content' => "Năm 2025 hứa hẹn nhiều đột phá công nghệ thú vị. Cùng xem những xu hướng nổi bật:\n\n1. 🤖 AI Tích Hợp Sâu Hơn\n- ChatGPT và các AI chatbot trở nên thông minh hơn\n- AI trong smartphone giúp tối ưu hiệu năng tự động\n- AI assistant cá nhân hóa cao\n- AI tạo nội dung (văn bản, hình ảnh, video)\n\n2. 🕶️ Apple Vision Pro Và VR/AR\n- Kính thực tế hỗn hợp trở nên phổ biến\n- Ứng dụng trong gaming, giáo dục, làm việc\n- Metaverse phát triển mạnh\n\n3. 📱 Smartphone Gập Trở Thành Xu Hướng\n- Samsung Galaxy Z Fold/Flip thế hệ mới\n- Xiaomi, OPPO, Vivo đều có smartphone gập\n- Giá thành giảm, độ bền tăng\n\n4. 🚗 Xe Điện Và Xe Tự Lái\n- Tesla dẫn đầu về công nghệ tự lái\n- Nhiều hãng xe truyền thống chuyển sang điện\n- Trạm sạc nhanh phổ biến hơn\n\n5. ⚡ Sạc Nhanh Và Pin Bền Hơn\n- Sạc 200W-300W trở nên phổ biến\n- Pin silicon-carbon tăng dung lượng 20-30%\n- Sạc không dây xa 1-2 mét\n\n6. 🌐 6G Và Kết Nối Siêu Tốc\n- Bắt đầu nghiên cứu và thử nghiệm 6G\n- Tốc độ internet di động nhanh hơn gấp nhiều lần\n- Độ trễ gần như bằng 0\n\n7. 🏥 Công Nghệ Y Tế Thông Minh\n- Thiết bị đeo theo dõi sức khỏe chính xác hơn\n- Chẩn đoán bệnh bằng AI\n- Telemedicine phát triển\n\n8. ♻️ Công Nghệ Xanh\n- Thiết bị tiết kiệm năng lượng hơn\n- Vật liệu tái chế được ưu tiên\n- Carbon neutral trở thành tiêu chuẩn\n\n9. 🔐 Bảo Mật Quantum\n- Mã hóa lượng tử bảo vệ dữ liệu tốt hơn\n- Chống lại máy tính lượng tử hack\n\n10. 🏠 Smart Home Thông Minh Hơn\n- Nhà thông minh tích hợp AI\n- Tự động hóa toàn bộ ngôi nhà\n- Tiết kiệm năng lượng tối đa\n\nKẾT LUẬN:\nCông nghệ 2025 hướng đến việc làm cuộc sống tiện lợi hơn, thông minh hơn và bền vững hơn. Hãy chuẩn bị đón nhận những thay đổi tích cực này!\n\nBạn hào hứng với xu hướng nào nhất? Hãy để lại bình luận!",
                'category_id' => 1,
                'status' => 'published',
                'featured' => 1,
                'views' => rand(1500, 2500),
                'tags' => [4] // AI
            ],
        ];

        foreach ($posts as $index => $postData) {
            $tags = $postData['tags'];
            unset($postData['tags']);

            $post = Post::create([
                'blog_category_id' => $postData['category_id'],
                'user_id' => $adminUser->id,
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'status' => $postData['status'],
                'featured' => $postData['featured'],
                'views' => $postData['views'],
                'published_at' => now()->subDays(rand(1, 30)),
            ]);

            // Attach tags
            if (!empty($tags)) {
                $post->tags()->attach($tags);
            }
        }

        $this->command->info('✅ Đã tạo thành công 4 danh mục blog, 8 tags và 10 bài viết!');
    }
}
