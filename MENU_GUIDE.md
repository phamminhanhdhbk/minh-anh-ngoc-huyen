# Hướng dẫn sử dụng hệ thống Menu

## Tổng quan
Hệ thống quản lý menu động cho phép bạn tạo và quản lý menu với các tính năng:
- Tạo nhiều menu khác nhau (header, footer, sidebar, mobile)
- Hỗ trợ menu đa cấp (nested menu)
- Quản lý thứ tự menu items
- Icon Font Awesome
- Mở link cùng tab hoặc tab mới

## Quản lý trong Admin

### 1. Truy cập quản lý Menu
- Đăng nhập Admin Panel
- Vào menu "Quản lý Menu" ở sidebar
- URL: `/admin/menus`

### 2. Tạo Menu mới
1. Click "Tạo Menu Mới"
2. Nhập thông tin:
   - **Tên Menu**: Tên hiển thị trong admin
   - **Slug**: Định danh unique cho menu (vd: main-menu, footer-menu)
   - **Vị trí**: header, footer, sidebar, mobile
   - **Mô tả**: Mô tả ngắn về menu
   - **Thứ tự**: Thứ tự sắp xếp
   - **Hiển thị**: Bật/tắt menu

### 3. Thêm Menu Items
Sau khi tạo menu, click "Chỉnh sửa" để thêm các mục menu:

**Thông tin Menu Item:**
- **Tiêu đề**: Text hiển thị
- **Menu cha**: Chọn nếu là submenu (dropdown)
- **URL**: Link trực tiếp (vd: https://example.com, /about)
- **Route Name**: Tên route Laravel (vd: home, products.index)
- **Icon**: Font Awesome class (vd: fas fa-home)
- **Mở link**: _self (cùng tab) hoặc _blank (tab mới)
- **CSS Class**: Class tùy chỉnh
- **Thứ tự**: Số thứ tự hiển thị
- **Hiển thị**: Bật/tắt menu item

**Lưu ý:**
- URL và Route Name: Chỉ cần điền 1 trong 2
- Ưu tiên Route Name nếu cả 2 đều có giá trị

### 4. Tạo Menu đa cấp (Dropdown)
1. Tạo menu item cha trước
2. Khi tạo menu item con, chọn menu item cha ở field "Menu cha"
3. Hệ thống tự động tạo dropdown

## Sử dụng trong Frontend

### 1. Hiển thị menu theo Slug
```blade
{!! renderMenu('main-menu') !!}
```

### 2. Hiển thị menu theo Location
```blade
{!! renderMenu('header', 'location') !!}
```

### 3. Tùy chỉnh CSS class
```blade
{!! renderMenu('main-menu', 'slug', 'navbar-nav ms-auto') !!}
```

### 4. Ví dụ trong Header
```blade
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Logo</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            {!! renderMenu('main-menu', 'slug', 'navbar-nav ms-auto') !!}
        </div>
    </div>
</nav>
```

### 5. Ví dụ trong Footer
```blade
<footer class="footer bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Quick Links</h5>
                {!! renderMenu('footer-menu', 'slug', 'footer-menu-list') !!}
            </div>
        </div>
    </div>
</footer>
```

## CSS cho Menu

### Dropdown Menu Style
```css
/* Dropdown hover effect */
.navbar-nav .dropdown:hover .dropdown-menu {
    display: block;
    margin-top: 0;
}

/* Dropdown animation */
.dropdown-menu {
    animation: fadeInDown 0.3s ease-in-out;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translate3d(0, -10px, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

/* Active menu item */
.nav-link.active {
    font-weight: bold;
    color: #0d6efd !important;
}
```

### Footer Menu Style
```css
.footer-menu-list {
    list-style: none;
    padding: 0;
}

.footer-menu-list .nav-item {
    margin-bottom: 10px;
}

.footer-menu-list .nav-link {
    color: #adb5bd;
    padding: 5px 0;
    display: block;
    transition: color 0.3s;
}

.footer-menu-list .nav-link:hover {
    color: #fff;
    text-decoration: none;
}
```

## Functions Helper

### renderMenu($identifier, $type = 'slug', $cssClass = 'navbar-nav')
Render menu HTML từ slug hoặc location.

**Parameters:**
- `$identifier`: Menu slug hoặc location
- `$type`: 'slug' hoặc 'location' (default: 'slug')
- `$cssClass`: CSS class cho thẻ `<ul>` (default: 'navbar-nav')

**Return:** HTML string

### renderMenuItem($item, $depth = 0)
Render một menu item kèm children (recursive).

**Parameters:**
- `$item`: MenuItem object
- `$depth`: Độ sâu menu (default: 0)

**Return:** HTML string

### getMenuItemUrl($item)
Lấy URL của menu item.

**Priority:**
1. URL field
2. Route name
3. '#' nếu không có

**Return:** string

### isMenuItemActive($item)
Kiểm tra xem menu item có đang active không.

**Logic:**
- Exact URL match
- Route name match
- URL starts with (cho parent pages)

**Return:** boolean

## Menu Locations

### Các vị trí mặc định:
- **header**: Menu chính ở đầu trang
- **footer**: Menu ở chân trang
- **sidebar**: Menu thanh bên
- **mobile**: Menu cho thiết bị di động

Bạn có thể tạo thêm location tùy chỉnh.

## Font Awesome Icons

### Cách sử dụng:
1. Truy cập: https://fontawesome.com/icons
2. Tìm icon muốn dùng
3. Copy class name (vd: `fas fa-home`)
4. Paste vào field "Icon" khi tạo menu item

### Icon phổ biến:
- `fas fa-home` - Trang chủ
- `fas fa-shopping-bag` - Sản phẩm
- `fas fa-blog` - Blog
- `fas fa-info-circle` - Giới thiệu
- `fas fa-envelope` - Liên hệ
- `fas fa-user` - Tài khoản
- `fas fa-shopping-cart` - Giỏ hàng
- `fas fa-heart` - Yêu thích
- `fas fa-phone` - Điện thoại

## Tips & Best Practices

### 1. Đặt tên Slug
- Sử dụng chữ thường, không dấu
- Dùng dấu gạch ngang (-) thay khoảng trắng
- Ví dụ: main-menu, footer-links, mobile-nav

### 2. Cấu trúc Menu tốt
- Không quá nhiều cấp (tối đa 2-3 cấp)
- Nhóm các item liên quan
- Giữ số lượng items hợp lý (5-7 items chính)

### 3. Performance
- Cache menu nếu ít thay đổi
- Eager load relationships khi query

### 4. Route Names
- Ưu tiên dùng Route Name hơn URL cứng
- Dễ bảo trì khi thay đổi URL structure

### 5. Mobile Menu
- Tạo menu riêng cho mobile (đơn giản hơn)
- Hoặc sử dụng Bootstrap collapse

## Ví dụ Menu Structure

```
Main Menu
├─ Trang chủ (/)
├─ Sản phẩm (/products)
│  ├─ Điện thoại (/categories/dien-thoai)
│  ├─ Laptop (/categories/laptop)
│  └─ Phụ kiện (/categories/phu-kien)
├─ Blog (/blog)
├─ Giới thiệu (/about)
└─ Liên hệ (/contact)
```

## Troubleshooting

### Menu không hiển thị
- Kiểm tra menu có `is_active = true`
- Kiểm tra menu items có `is_active = true`
- Kiểm tra slug hoặc location đúng chưa

### Dropdown không hoạt động
- Kiểm tra Bootstrap JS đã load
- Kiểm tra parent_id đã set đúng
- Kiểm tra CSS conflict

### Icon không hiển thị
- Kiểm tra Font Awesome đã load
- Kiểm tra class name đúng format
- Dùng Developer Tools để debug

## Database Schema

### Table: menus
- `id`: Primary key
- `name`: Tên menu
- `slug`: Unique identifier
- `location`: Vị trí (header, footer, etc)
- `description`: Mô tả
- `is_active`: Trạng thái
- `order`: Thứ tự
- `timestamps`

### Table: menu_items
- `id`: Primary key
- `menu_id`: Foreign key to menus
- `parent_id`: Self-referencing foreign key
- `title`: Tiêu đề
- `url`: URL trực tiếp
- `route`: Route name Laravel
- `icon`: Font Awesome class
- `target`: _self hoặc _blank
- `css_class`: Custom CSS class
- `order`: Thứ tự
- `is_active`: Trạng thái
- `timestamps`

## API Methods (Models)

### Menu Model
```php
// Get menu items (root level only)
$menu->items

// Get all menu items (including nested)
$menu->allItems

// Check if menu is active
$menu->is_active
```

### MenuItem Model
```php
// Get parent item
$item->parent

// Get children items
$item->children

// Get URL (computed)
$item->url  // Returns URL or route URL

// Get menu
$item->menu
```

## Kết luận
Hệ thống menu đã được thiết lập với đầy đủ tính năng. Bạn có thể:
1. Quản lý menu trong admin panel
2. Hiển thị menu động trên frontend
3. Tùy chỉnh style CSS
4. Tạo menu đa cấp với icon

Chúc bạn sử dụng hiệu quả! 🎉
