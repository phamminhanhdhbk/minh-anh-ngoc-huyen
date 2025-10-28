# Hệ Thống Gửi Email Thông Báo Đơn Hàng

## ✅ Đã Cấu Hình

### 1. **Cấu hình Email (AWS SES)**
File `.env` đã được cấu hình:
```
MAIL_MAILER=smtp
MAIL_HOST=email-smtp.ap-northeast-1.amazonaws.com
MAIL_PORT=587
MAIL_USERNAME=AKIA45VRJ2LQYOHWKSWV
MAIL_PASSWORD=BM3wC7oNIpgPv4uj8vAAwWWazsXdU4RzZO/xUOvWLhqQ
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ngochuyen.site
MAIL_FROM_NAME="Ngọc Huyền Shop"
```

### 2. **Email Nhận Thông Báo**
Mặc định gửi đến:
- minhanh.itqn@gmail.com
- ngochuyen2410@gmail.com

### 3. **Files Đã Tạo**

#### a) Mailable Class
- **File:** `app/Mail/OrderPlaced.php`
- **Chức năng:** Xử lý gửi email thông báo đơn hàng mới

#### b) Email Template
- **File:** `resources/views/emails/order-placed.blade.php`
- **Nội dung:** 
  - Thông tin đơn hàng (ID, thời gian, trạng thái)
  - Thông tin khách hàng (tên, email, SĐT)
  - Địa chỉ giao hàng
  - Chi tiết sản phẩm (tên, số lượng, giá)
  - Tổng tiền
  - Phương thức thanh toán

#### c) Order Controller
- **File:** `app/Http/Controllers/OrderController.php`
- **Đã thêm:**
  - Import `Mail` facade và `OrderPlaced` class
  - Logic gửi email sau khi tạo đơn hàng thành công
  - Xử lý nhiều email (phân cách bằng dấu phẩy)
  - Log lỗi nếu gửi email thất bại (không ảnh hưởng đơn hàng)

#### d) Site Settings
- **Database:** Đã thêm setting `order_notification_emails`
- **Giá trị:** `minhanh.itqn@gmail.com,ngochuyen2410@gmail.com`

### 4. **Migration**
- **File:** `database/migrations/2025_10_28_000001_change_price_column_in_carts_table.php`
- **Đã fix:** Cột `price` trong bảng `carts` từ `decimal(10,2)` → `bigInteger`

## 🎯 Cách Hoạt Động

### Khi Khách Đặt Hàng:
1. Khách điền form checkout → Submit
2. Hệ thống tạo đơn hàng trong database
3. Tự động gửi email đến 2 địa chỉ đã cấu hình
4. Email chứa đầy đủ thông tin đơn hàng
5. Khách hàng được chuyển đến trang "Đặt hàng thành công"

### Nội Dung Email Gửi Đến Admin:
- 📧 **Subject:** Đơn hàng mới #[ID] - [Tên khách]
- 📋 **Thông tin đơn hàng:** Mã, thời gian, trạng thái
- 👤 **Thông tin khách:** Tên, email, SĐT
- 📦 **Địa chỉ giao hàng:** Địa chỉ đầy đủ
- 🛍️ **Chi tiết sản phẩm:** Bảng danh sách sản phẩm, số lượng, giá
- 💰 **Tổng tiền:** Tổng cộng đơn hàng
- 💳 **Thanh toán:** Phương thức và trạng thái

## 🎨 Quản Lý Email Nhận Thông Báo

### Cách 1: Qua Admin Panel
1. Đăng nhập Admin: http://ngochuyen.site/admin
2. Vào **Cấu hình** → **Chỉnh sửa cấu hình**
3. Chọn nhóm **"Đơn hàng"**
4. Tìm setting: **"Email nhận thông báo đơn hàng"**
5. Nhập danh sách email (phân cách bằng dấu phẩy)
6. Ví dụ: `email1@gmail.com,email2@gmail.com,email3@gmail.com`
7. Click **"Lưu thay đổi"**

### Cách 2: Qua Database
```sql
UPDATE site_settings 
SET value = 'email1@gmail.com,email2@gmail.com,email3@gmail.com'
WHERE key = 'order_notification_emails';
```

### Cách 3: Qua Tinker
```bash
php artisan tinker

App\SiteSetting::where('key', 'order_notification_emails')
    ->update(['value' => 'email1@gmail.com,email2@gmail.com']);
```

## 🧪 Test Gửi Email

### Test thủ công:
1. Mở trình duyệt ẩn danh
2. Vào http://ngochuyen.site/
3. Thêm sản phẩm vào giỏ hàng
4. Click "Thanh toán"
5. Điền thông tin khách hàng
6. Click "Đặt hàng"
7. Kiểm tra email `minhanh.itqn@gmail.com` và `ngochuyen2410@gmail.com`

### Kiểm tra log:
```bash
tail -f storage/logs/laravel.log | grep "Order notification"
```

Sẽ thấy:
```
[2025-10-28 06:30:45] local.INFO: Order notification email sent {"email":"minhanh.itqn@gmail.com","order_id":123}
[2025-10-28 06:30:46] local.INFO: Order notification email sent {"email":"ngochuyen2410@gmail.com","order_id":123}
```

## ⚠️ Lưu Ý Quan Trọng

### AWS SES Verification:
- Email `noreply@ngochuyen.site` phải được verify trong AWS SES
- Domain `ngochuyen.site` phải được verify
- Nếu AWS SES đang ở **Sandbox mode**:
  - Chỉ gửi được đến email đã verify
  - Cần verify `minhanh.itqn@gmail.com` và `ngochuyen2410@gmail.com` trong AWS SES Console
  - Hoặc request **Production Access** để gửi tới bất kỳ email nào

### Kiểm Tra AWS SES:
1. Đăng nhập AWS Console
2. Vào **SES** (Simple Email Service)
3. Region: **ap-northeast-1** (Tokyo)
4. Kiểm tra **"Verified identities"**
5. Đảm bảo có:
   - ✅ `ngochuyen.site` (domain)
   - ✅ `minhanh.itqn@gmail.com` (nếu Sandbox mode)
   - ✅ `ngochuyen2410@gmail.com` (nếu Sandbox mode)

### Nếu Email Không Gửi Được:
1. Kiểm tra log: `storage/logs/laravel.log`
2. Test SMTP connection:
```bash
php artisan tinker

Mail::raw('Test email', function($msg) {
    $msg->to('minhanh.itqn@gmail.com')->subject('Test');
});
```
3. Kiểm tra AWS SES Sending Statistics
4. Kiểm tra spam folder của Gmail

## 📊 Monitoring

### Xem lịch sử gửi email:
```bash
# Xem 50 log gần nhất
tail -n 50 storage/logs/laravel.log | grep "Order notification"

# Theo dõi real-time
tail -f storage/logs/laravel.log | grep "Order"
```

### AWS SES Dashboard:
- Vào AWS Console → SES → Dashboard
- Xem **Sending Statistics**:
  - Emails sent
  - Bounces (email bị trả lại)
  - Complaints (email bị báo spam)

## 🔧 Troubleshooting

### Vấn đề: Email không gửi được
**Giải pháp:**
```bash
# 1. Clear cache
php artisan config:clear
php artisan cache:clear

# 2. Kiểm tra cấu hình
php artisan tinker
config('mail.host')
config('mail.username')

# 3. Test gửi email
Mail::raw('Test', function($msg) {
    $msg->to('your-email@gmail.com')->subject('Test Mail');
});
```

### Vấn đề: Email vào spam
**Giải pháp:**
- Thêm SPF record cho domain
- Thêm DKIM trong AWS SES
- Verify domain trong AWS SES
- Tránh nội dung spam (quá nhiều link, chữ in hoa)

### Vấn đề: AWS SES Sandbox Mode
**Giải pháp:**
- Request Production Access trong AWS SES Console
- Hoặc verify từng email nhận trong AWS SES

## 📝 Tóm Tắt

✅ **Email tự động gửi khi:** Khách đặt hàng thành công  
📧 **Gửi đến:** minhanh.itqn@gmail.com, ngochuyen2410@gmail.com  
🎛️ **Quản lý qua:** Admin Panel → Cấu hình → Đơn hàng  
📊 **Theo dõi:** `storage/logs/laravel.log`  
🔐 **SMTP:** AWS SES (Tokyo region)  

**Hệ thống đã sẵn sàng hoạt động!** 🎉
