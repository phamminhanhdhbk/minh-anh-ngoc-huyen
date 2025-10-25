# 🚨 Fix Upload File Size Limit - Quick Guide

## Vấn đề
```
Warning: POST Content-Length of 15882032 bytes exceeds the limit of 8388608 bytes
```

## ✅ Giải pháp nhanh (3 bước)

### 1️⃣ Sửa php.ini
Mở file: `C:\xampp\php\php.ini`

Tìm và sửa các dòng sau:
```ini
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
max_input_time = 300
memory_limit = 512M
```

### 2️⃣ Restart Apache
- Mở XAMPP Control Panel
- Stop Apache
- Start Apache lại

### 3️⃣ Kiểm tra
Chạy lệnh này để kiểm tra:
```bash
php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL; echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
```

Kết quả mong đợi:
```
upload_max_filesize: 100M
post_max_size: 100M
```

## 🎯 Đã tự động cập nhật

✅ `.htaccess` - Thêm PHP upload limits  
✅ ProductController - Tăng limit lên 20MB/ảnh  
✅ Form views - Thêm thông báo hỗ trợ WebP và 20MB limit  
✅ Validation messages - Thông báo lỗi rõ ràng hơn  

## 📊 Giới hạn mới

| Setting | Old | New |
|---------|-----|-----|
| upload_max_filesize | 2M | 100M |
| post_max_size | 8M | 100M |
| Laravel validation | 2MB | 20MB |
| Supported formats | jpg,png,gif | jpg,png,gif,webp |

## ⚡ Tối ưu thêm

Sau khi upload, chạy lệnh để tối ưu hình ảnh:
```bash
php artisan images:optimize products
```

Điều này sẽ:
- Giảm 50-70% dung lượng ảnh
- Tự động resize về 1200px width
- Compress với quality 80%
- Giữ nguyên aspect ratio

## 🔍 Troubleshooting

**Vẫn bị lỗi?**
1. Check xem có dùng Nginx không? → Cần sửa `nginx.conf`
2. Check server có giới hạn CloudFlare? → Tăng limit trong CloudFlare
3. Clear browser cache và thử lại

**Muốn tăng hơn 100MB?**
Sửa trong `php.ini` và `.htaccess` thành giá trị lớn hơn (ví dụ: 200M)

## 📞 Support

Nếu vẫn gặp vấn đề, check file log:
- Laravel: `storage/logs/laravel.log`
- Apache: `C:\xampp\apache\logs\error.log`
