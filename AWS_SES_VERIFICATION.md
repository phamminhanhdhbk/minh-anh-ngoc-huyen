# Hướng Dẫn Verify Email AWS SES

## ⚠️ Vấn Đề Hiện Tại

AWS SES đang ở **Sandbox Mode** - chỉ gửi được email đến địa chỉ đã verify.

**Lỗi:** 
```
554 Message rejected: Email address is not verified.
```

## 🔧 Giải Pháp: Verify Email

### BƯỚC 1: Truy cập AWS SES Console

1. Đăng nhập: https://console.aws.amazon.com/ses/
2. **QUAN TRỌNG:** Chọn Region: **Asia Pacific (Tokyo) ap-northeast-1**
   - Góc trên bên phải, click vào region dropdown
   - Chọn "ap-northeast-1" (Tokyo)

### BƯỚC 2: Verify Email Address

#### A. Verify minhanh.itqn@gmail.com

1. Click menu **"Verified identities"** (bên trái)
2. Click nút **"Create identity"**
3. Chọn **"Email address"**
4. Nhập: `minhanh.itqn@gmail.com`
5. Click **"Create identity"**

#### B. Kiểm tra Email

1. Mở Gmail: https://mail.google.com/
2. Đăng nhập vào `minhanh.itqn@gmail.com`
3. Tìm email từ: **"Amazon Web Services"**
4. Subject: **"Amazon SES Email Address Verification Request in region..."**
5. Click vào link trong email
6. Sẽ thấy trang: **"Congratulations! You verified..."**

#### C. Verify ngochuyen2410@gmail.com

1. Lặp lại các bước A và B cho `ngochuyen2410@gmail.com`

### BƯỚC 3: Verify MAIL_FROM_ADDRESS

Cần verify thêm email dùng làm FROM address. Có 2 lựa chọn:

#### Option 1: Dùng email đã verify làm FROM
```
MAIL_FROM_ADDRESS=minhanh.itqn@gmail.com
```

#### Option 2: Verify domain ngochuyen.site (Recommended)
1. AWS SES → **"Create identity"**
2. Chọn **"Domain"**
3. Nhập: `ngochuyen.site`
4. AWS sẽ cho DNS records như:
   ```
   Type: TXT
   Name: _amazonses.ngochuyen.site
   Value: [một chuỗi random]
   
   Type: CNAME
   Name: [random]._domainkey.ngochuyen.site
   Value: [random].dkim.amazonses.com
   ```
5. Vào **Domain Registrar** (nơi mua domain)
6. Thêm các DNS records trên
7. Đợi verify (5-30 phút)

Sau khi verify domain, có thể dùng:
```
MAIL_FROM_ADDRESS=noreply@ngochuyen.site
MAIL_FROM_ADDRESS=info@ngochuyen.site
MAIL_FROM_ADDRESS=admin@ngochuyen.site
```

### BƯỚC 4: Test Lại

```bash
cd /d/ALL-PROJECT/project-vo/laravel-project
php artisan config:clear
php artisan test:email minhanh.itqn@gmail.com
```

Sẽ thấy:
```
✅ Email sent successfully!
📧 Please check inbox (and spam folder)
```

## 🚀 Request Production Access (Tùy chọn)

Để gửi email tới BẤT KỲ địa chỉ nào (không cần verify):

1. AWS SES Console → **"Account dashboard"**
2. Nhìn thấy: **"Sandbox"** status
3. Click **"Request production access"**
4. Điền form:
   - **Mail type:** Transactional
   - **Website URL:** http://ngochuyen.site
   - **Use case description:** 
     ```
     Chúng tôi vận hành website thương mại điện tử ngochuyen.site.
     Cần gửi email thông báo đơn hàng đến khách hàng và admin khi có đơn hàng mới.
     Ước tính gửi 100-500 emails/tháng.
     ```
   - **Process for handling bounces/complaints:**
     ```
     Chúng tôi sẽ theo dõi bounce rate và complaint rate trong AWS SES dashboard.
     Nếu có bounce hoặc complaint, sẽ xóa email đó khỏi danh sách gửi.
     ```
5. Click **"Submit request"**
6. Đợi AWS review (thường 24-48 giờ)

## 📊 Kiểm Tra Status

### Check Verified Emails:
1. AWS SES → **"Verified identities"**
2. Sẽ thấy danh sách:
   ```
   ✅ minhanh.itqn@gmail.com     Status: Verified
   ✅ ngochuyen2410@gmail.com    Status: Verified
   ✅ ngochuyen.site             Status: Verified (nếu đã verify domain)
   ```

### Check Sandbox Status:
1. AWS SES → **"Account dashboard"**
2. Phần **"Sending statistics"**
3. Status:
   - **Sandbox:** Chỉ gửi được đến email đã verify
   - **Production:** Gửi được đến bất kỳ email nào

## 🧪 Test Command

Sau khi verify xong:

```bash
# Test gửi đến minhanh
php artisan test:email minhanh.itqn@gmail.com

# Test gửi đến ngochuyen
php artisan test:email ngochuyen2410@gmail.com
```

## ❓ Troubleshooting

### Email không nhận được verification email từ AWS?

1. Kiểm tra **Spam/Junk folder**
2. Tìm email từ: **no-reply-aws@amazon.com**
3. Nếu không có, click **"Resend"** trong AWS Console

### Domain verify mất bao lâu?

- DNS propagation: 5-30 phút
- AWS check: ngay lập tức sau khi DNS update
- Tổng: thường dưới 1 giờ

### Vẫn bị lỗi "not verified"?

1. Kiểm tra region đúng: **ap-northeast-1**
2. Clear config: `php artisan config:clear`
3. Kiểm tra `.env` có đúng email verified
4. Test bằng command: `php artisan test:email`

## 📝 Tóm Tắt Các Bước

1. ✅ Đăng nhập AWS SES Console
2. ✅ Chọn region: ap-northeast-1 (Tokyo)
3. ✅ Verify email: minhanh.itqn@gmail.com
4. ✅ Verify email: ngochuyen2410@gmail.com
5. ✅ Click link trong email từ AWS
6. ✅ Cập nhật `.env`: MAIL_FROM_ADDRESS
7. ✅ Test: `php artisan test:email`
8. 🎉 Hoàn tất!
