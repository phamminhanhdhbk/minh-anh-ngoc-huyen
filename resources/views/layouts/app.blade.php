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
            background-color: #2c3e50;
            color: white;
            padding: 40px 0;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            max-width: 400px;
            margin: 2rem auto;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                </ul>

                <!-- Search Form -->
                @if(!Request::is('login') && !Request::is('register'))
                <form class="d-flex me-3" method="GET" action="{{ route('products.index') }}">
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
                        <li class="nav-item me-3">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-light position-relative">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="cart-badge cart-count" id="cart-count">0</span>
                            </a>
                        </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu">
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
                    <div class="d-flex gap-3">
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show position-fixed" style="top: 80px; right: 20px; z-index: 9999;" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('body').append(alertHtml);

            setTimeout(function() {
                $('.alert').alert('close');
            }, 3000);
        }
    </script>

    @stack('scripts')
</body>
</html>
