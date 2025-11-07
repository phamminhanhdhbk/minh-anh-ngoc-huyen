<!DOCTYPE html>
<html lang="vi">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(isset($seoModel))
        @include('components.seo-meta', ['model' => $seoModel])
    @else
        @include('components.seo-meta', [
            'title' => $seoTitle ?? (isset($title) ? $title : setting("site_name", "Shop VO") . ' - ' . setting("site_description", "Mua sắm trực tuyến")),
            'description' => $seoDescription ?? setting('site_description', 'Cửa hàng điện tử hàng đầu Việt Nam'),
            'keywords' => $seoKeywords ?? setting('site_name', 'Shop VO') . ', mua sắm, điện tử',
            'ogImage' => $seoImage ?? setting('site_logo')
        ])
    @endif

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-6W28SJSDM0"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-6W28SJSDM0');
</script>

    <style>
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .product-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
        }
        .category-card {
            transition: all 0.3s;
        }
        .category-card:hover {
            transform: scale(1.05);
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .footer {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 40px 0;
        }
        .cart-badge {
            position: absolute;
            top: -12px;
            right: -12px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border-radius: 50%;
            min-width: 24px;
            height: 24px;
            font-size: 11px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #27ae60;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4);
            padding: 0 2px;
        }
        .auth-card {
            max-width: 400px;
            margin: 2rem auto;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        /* Dropdown Menu Styles */
        .navbar .dropdown-menu {
            border: 0;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }

        .navbar .dropdown-item {
            padding: 0.5rem 1.5rem;
            transition: background-color 0.2s;
        }

        .navbar .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .navbar .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        /* Toast Notification Styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast-notification {
            min-width: 350px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s ease-out forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast-notification.hide {
            animation: slideOut 0.3s ease-out forwards;
        }

        /* Cart Button Styles */
        .navbar .nav-link.cart-link {
            color: rgba(255, 255, 255, 0.8) !important;
            transition: all 0.3s ease;
            position: relative;
            display: inline-block !important;
            line-height: 1;
            padding: 0.5rem 0.25rem !important;
        }

        .navbar .nav-link.cart-link:hover {
            color: white !important;
            transform: scale(1.15);
        }

        .navbar .nav-item.me-3 {
            display: flex;
            align-items: center;
        }

        @media (max-width: 991px) {
            .navbar .nav-link.cart-link {
                padding: 0.5rem 0.5rem !important;
            }
        }

        @media (max-width: 576px) {
            .navbar .nav-link.cart-link {
                padding: 0.5rem 0.25rem !important;
            }

            .cart-badge {
                top: -10px;
                right: -10px;
                min-width: 22px;
                height: 22px;
                font-size: 10px;
                border-width: 1.5px;
            }

            /* Mobile navbar spacing */
            .navbar-nav {
                gap: 0.5rem;
            }

            .navbar-nav .nav-item {
                margin-right: 0 !important;
            }

            .navbar-nav .nav-item.me-2 {
                margin-right: 0.5rem !important;
            }

            .navbar-collapse {
                gap: 0.5rem;
            }

            .navbar form.d-flex {
                margin-right: 0.5rem !important;
                margin-bottom: 0.5rem;
                width: 100%;
            }

            .navbar form.d-flex input {
                font-size: 0.9rem;
            }

            .navbar form.d-flex button {
                padding: 0.375rem 0.75rem;
            }
        }

        .toast-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
        }

        .toast-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
        }

        .toast-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
        }

        .toast-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
        }

        .toast-notification .btn-close {
            filter: brightness(0) invert(1);
            cursor: pointer;
            opacity: 0.8;
            z-index: 10;
            position: relative;
            pointer-events: auto !important;
            flex-shrink: 0;
            width: 1em;
            height: 1em;
            padding: 0.25em;
        }

        .toast-notification .btn-close:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .toast-notification .btn-close:active {
            transform: scale(0.95);
        }

        .toast-icon {
            font-size: 24px;
            margin-right: 12px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Navigation -->
    @hasSection('navbar')
        @yield('navbar')
    @else
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                @if(setting('site_logo'))
                    <img src="{{ setting('site_logo') }}" alt="{{ setting('site_name', 'Shop VO') }}" style="height: 40px;" class="me-2">
                @else
                    <i class="fas fa-store me-2"></i>
                @endif
                {{ setting('site_name', 'Shop VO') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Dynamic Menu from Database -->
                @php
                    $headerMenu = \App\Menu::where('location', 'header')
                        ->where('is_active', true)
                        ->with(['items' => function($query) {
                            $query->whereNull('parent_id')
                                ->where('is_active', true)
                                ->orderBy('order');
                        }, 'items.children' => function($query) {
                            $query->where('is_active', true)
                                ->orderBy('order');
                        }])
                        ->first();
                @endphp

                @if($headerMenu && $headerMenu->items->count() > 0)
                    <ul class="navbar-nav me-auto">
                        @foreach($headerMenu->items as $item)
                            @php
                                $hasChildren = $item->children->where('is_active', true)->count() > 0;
                                $itemUrl = $item->url ?: ($item->route ? route($item->route) : '#');
                                $isActive = request()->url() === $itemUrl || ($item->route && request()->routeIs($item->route));
                            @endphp

                            <li class="nav-item{{ $hasChildren ? ' dropdown' : '' }}{{ $item->css_class ? ' ' . $item->css_class : '' }}">
                                @if($hasChildren)
                                    <a class="nav-link dropdown-toggle{{ $isActive ? ' active' : '' }}"
                                       href="{{ $itemUrl }}"
                                       id="navbarDropdown{{ $item->id }}"
                                       role="button"
                                       data-bs-toggle="dropdown"
                                       aria-haspopup="true"
                                       aria-expanded="false">
                                        @if($item->icon)<i class="{{ $item->icon }} me-1"></i>@endif
                                        {{ $item->title }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $item->id }}">
                                        @foreach($item->children->where('is_active', true)->sortBy('order') as $child)
                                            @php
                                                $childUrl = $child->url ?: ($child->route ? route($child->route) : '#');
                                                $childIsActive = request()->url() === $childUrl || ($child->route && request()->routeIs($child->route));
                                            @endphp
                                            <li>
                                                <a class="dropdown-item{{ $childIsActive ? ' active' : '' }}"
                                                   href="{{ $childUrl }}"
                                                   target="{{ $child->target }}">
                                                    @if($child->icon)<i class="{{ $child->icon }} me-1"></i>@endif
                                                    {{ $child->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <a class="nav-link{{ $isActive ? ' active' : '' }}"
                                       href="{{ $itemUrl }}"
                                       target="{{ $item->target }}">
                                        @if($item->icon)<i class="{{ $item->icon }} me-1"></i>@endif
                                        {{ $item->title }}
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <!-- Fallback menu if no database menu -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Trang chủ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index') }}">Sản phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('blog.index') }}">
                                <i class="fas fa-blog me-1"></i>Blog
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('contact.index') }}">
                                <i class="fas fa-envelope me-1"></i>Liên hệ
                            </a>
                        </li>
                    </ul>
                @endif

                <!-- Search Form -->
                @if(!Request::is('login') && !Request::is('register'))
                <form class="d-flex me-2 me-md-3" method="GET" action="{{ route('products.index') }}">
                    <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                @endif

                <!-- Right Side -->
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                            </a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i>Đăng ký
                                </a>
                            </li>
                        @endif
                    @else
                        <!-- Cart -->
                        @if(!Request::is('login') && !Request::is('register'))
                        <li class="nav-item me-2 me-md-0 d-flex align-items-center">
                            <a href="{{ route('cart.index') }}" class="nav-link cart-link position-relative" title="Giỏ hàng" style="padding: 0.5rem 0 !important;">
                                <i class="fas fa-shopping-cart fa-lg"></i>
                                <span class="cart-badge cart-count" id="cart-count">0</span>
                            </a>
                        </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if(Auth::user()->is_admin)
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Quản trị
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    @endif

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    @if(!Request::is('login') && !Request::is('register'))
    @hasSection('footer')
        @yield('footer')
    @else
    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>
                        @if(setting('site_logo'))
                            <img src="{{ setting('site_logo') }}" alt="{{ setting('site_name', 'Shop VO') }}" style="height: 30px;" class="me-2">
                        @else
                            <i class="fas fa-store me-2"></i>
                        @endif
                        {{ setting('site_name', 'Shop VO') }}
                    </h5>
                    <p>{{ setting('site_description', 'Cửa hàng điện tử hàng đầu Việt Nam') }}</p>
                    <p><strong>Giờ làm việc:</strong><br>{{ setting('business_hours', 'Thứ 2 - Chủ nhật: 8:00 - 22:00') }}</p>
                </div>
                <div class="col-md-4">
                    <h5>Liên hệ</h5>
                    <p><i class="fas fa-phone me-2"></i>{{ setting('contact_phone', '0123 456 789') }}</p>
                    <p><i class="fas fa-envelope me-2"></i>{{ setting('contact_email', 'info@shopvo.com') }}</p>
                    <p><i class="fas fa-map-marker-alt me-2"></i>{{ setting('contact_address', '123 Nguyễn Văn A, Quận 1, TP.HCM') }}</p>
                </div>
                <div class="col-md-4">
                    <h5>Theo dõi chúng tôi</h5>
                    <div class="d-flex gap-3 align-items-center">
                        @if(setting('social_facebook'))
                        <a href="{{ setting('social_facebook') }}" class="text-white" target="_blank">
                            <i class="fab fa-facebook fa-2x"></i>
                        </a>
                        @endif
                        @if(setting('social_instagram'))
                        <a href="{{ setting('social_instagram') }}" class="text-white" target="_blank">
                            <i class="fab fa-instagram fa-2x"></i>
                        </a>
                        @endif
                        @if(setting('social_youtube'))
                        <a href="{{ setting('social_youtube') }}" class="text-white" target="_blank">
                            <i class="fab fa-youtube fa-2x"></i>
                        </a>
                        @endif
                        @if(setting('social_tiktok'))
                        <a href="{{ setting('social_tiktok') }}" class="text-white" target="_blank">
                            <i class="fab fa-tiktok fa-2x"></i>
                        </a>
                        @endif
                        @if(setting('social_zalo'))
                        <a href="{{ setting('social_zalo') }}" target="_blank" title="Zalo" class="d-flex align-items-center">
                            <img src="{{ asset('images/social-icons/zalo.png') }}" alt="Zalo" style="width: 40px; height: 40px;">
                        </a>
                        @endif
                    </div>
                    @if(setting('free_shipping_amount'))
                    <div class="mt-3">
                        <p><i class="fas fa-shipping-fast me-2"></i>Miễn phí vận chuyển cho đơn hàng từ {{ number_format(setting('free_shipping_amount')) }}₫</p>
                    </div>
                    @endif
                </div>
            </div>
            <hr class="mt-4">
            <div class="text-center">
                <p>&copy; 2025 {{ setting('site_name', 'Shop VO') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
    @endif
    @endif

    <!-- jQuery (load first) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Initialize Bootstrap dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            // Enable all dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
    </script>

    <script>
        // Update cart count on page load
        $(document).ready(function() {
            @auth
            updateCartCount();
            @endauth
        });

        function updateCartCount() {
            $.get('{{ route("cart.count") }}', function(data) {
                $('#cart-count').text(data.count);
                $('.cart-count').text(data.count);
                if (data.count > 0) {
                    $('.cart-count').show();
                } else {
                    $('.cart-count').text('0');
                }
            });
        }

        // Add to cart function
        function addToCart(productId, quantity = 1) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('{{ route("cart.add") }}', {
                product_id: productId,
                quantity: quantity
            }, function(data) {
                if (data.success) {
                    updateCartCount();
                    showAlert('success', data.message);
                }
            }).fail(function() {
                showAlert('danger', 'Có lỗi xảy ra. Vui lòng thử lại!');
            });
        }

        function showAlert(type, message) {
            showToast(type, message);
        }

        function showToast(type = 'info', message = '', title = '') {
            const iconMap = {
                'success': '<i class="fas fa-check-circle toast-icon"></i>',
                'danger': '<i class="fas fa-exclamation-circle toast-icon"></i>',
                'warning': '<i class="fas fa-exclamation-triangle toast-icon"></i>',
                'info': '<i class="fas fa-info-circle toast-icon"></i>'
            };

            const typeMap = {
                'success': 'success',
                'danger': 'danger',
                'error': 'danger',
                'warning': 'warning',
                'info': 'info'
            };

            const actualType = typeMap[type] || 'info';
            const icon = iconMap[actualType];
            const displayTitle = title || (actualType === 'success' ? 'Thành công' : actualType === 'danger' ? 'Lỗi' : actualType === 'warning' ? 'Cảnh báo' : 'Thông báo');

            const toastHtml = `
                <div class="toast-notification toast-${actualType}" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="p-3 d-flex align-items-center flex-grow-1">
                            ${icon}
                            <div>
                                <div class="fw-bold">${displayTitle}</div>
                                <div class="mt-1" style="font-size: 0.9rem;">${message}</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-3 ms-auto" aria-label="Close"></button>
                    </div>
                </div>
            `;

            const container = document.getElementById('toastContainer');
            const toastElement = document.createElement('div');
            toastElement.innerHTML = toastHtml;
            const toast = toastElement.firstElementChild;
            container.appendChild(toast);

            const closeBtn = toast.querySelector('.btn-close');
            console.log('Toast created, close button found:', closeBtn); // Debug

            // Close button event
            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    console.log('Close button clicked!'); // Debug
                    e.preventDefault();
                    e.stopPropagation();
                    toast.classList.add('hide');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                            console.log('Toast removed'); // Debug
                        }
                    }, 300);
                });
                
                // Also add direct onclick for testing
                closeBtn.style.pointerEvents = 'auto';
                console.log('Event listener attached'); // Debug
            } else {
                console.error('Close button not found!'); // Debug
            }

            // Auto close after 4 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.classList.add('hide');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, 4000);
        }
    </script>

    @stack('scripts')
</body>
</html>
