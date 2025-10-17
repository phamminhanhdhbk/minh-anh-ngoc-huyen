<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Shop VO')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1000;
            transition: all 0.3s;
        }

        .sidebar-brand {
            padding: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            border: none;
            transition: all 0.3s;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .dropdown-menu-custom {
            background: rgba(255,255,255,0.05);
            border-left: 3px solid rgba(255,255,255,0.3);
            margin-left: 1rem;
            padding: 0.5rem 0;
            display: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .dropdown-menu-custom.show {
            display: block;
        }

        .dropdown-menu-custom .nav-link {
            padding: 0.5rem 1.5rem 0.5rem 2.5rem;
            font-size: 0.9rem;
        }

        .dropdown-toggle-custom {
            cursor: pointer;
            position: relative;
        }

        .dropdown-toggle-custom::after {
            content: '\f107';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 1.5rem;
            transition: transform 0.3s;
        }

        .dropdown-toggle-custom.active::after {
            transform: rotate(180deg);
        }

        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
        }

        .top-navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .stats-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .table-actions {
            white-space: nowrap;
        }

        .badge-status {
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand text-white">
            <h4 class="mb-0">
                <i class="fas fa-cogs me-2"></i>
                Admin Panel
            </h4>
        </div>

        <div class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                       href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-folder me-2"></i>Quản lý Danh mục
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}"
                       href="{{ route('admin.products.index') }}">
                        <i class="fas fa-box me-2"></i>Quản lý Sản phẩm
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
                       href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart me-2"></i>Quản lý Đơn hàng
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                       href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users me-2"></i>Quản lý Users
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
                       href="{{ route('admin.reviews.index') }}">
                        <i class="fas fa-star me-2"></i>Quản lý Đánh giá
                    </a>
                </li>

                <!-- Blog Management Dropdown -->
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle-custom {{ request()->routeIs('admin.blog-categories.*') || request()->routeIs('admin.posts.*') || request()->routeIs('admin.post-tags.*') || request()->routeIs('admin.post-comments.*') ? 'active' : '' }}"
                       onclick="toggleBlogMenu(event)">
                        <i class="fas fa-blog me-2"></i>Quản lý Blog
                    </a>
                    <ul class="nav flex-column dropdown-menu-custom {{ request()->routeIs('admin.blog-categories.*') || request()->routeIs('admin.posts.*') || request()->routeIs('admin.post-tags.*') || request()->routeIs('admin.post-comments.*') ? 'show' : '' }}" id="blogDropdown">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}"
                               href="{{ route('admin.blog-categories.index') }}">
                                <i class="fas fa-folder-open me-2"></i>Danh mục
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}"
                               href="{{ route('admin.posts.index') }}">
                                <i class="fas fa-file-alt me-2"></i>Bài viết
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.post-tags.*') ? 'active' : '' }}"
                               href="{{ route('admin.post-tags.index') }}">
                                <i class="fas fa-tags me-2"></i>Tags
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.post-comments.*') ? 'active' : '' }}"
                               href="{{ route('admin.post-comments.index') }}">
                                <i class="fas fa-comments me-2"></i>Bình luận
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}"
                       href="{{ route('admin.menus.index') }}">
                        <i class="fas fa-bars me-2"></i>Quản lý Menu
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                       href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cog me-2"></i>Cấu hình Website
                    </a>
                </li>

                <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>Xem Website
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation -->
        <nav class="top-navbar">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h5 class="mb-0 d-inline-block ms-2">@yield('page-title', 'Dashboard')</h5>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-2"></i>{{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                                <i class="fas fa-home me-2"></i>Trang chủ
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Sidebar Toggle for Mobile
        $(document).ready(function() {
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('show');
            });

            // Close sidebar when clicking outside on mobile
            $(document).click(function(e) {
                if (!$(e.target).closest('#sidebar, #sidebarToggle').length) {
                    $('#sidebar').removeClass('show');
                }
            });
        });

        // Blog Dropdown Toggle
        function toggleBlogMenu(event) {
            event.preventDefault();
            const dropdown = document.getElementById('blogDropdown');
            const toggle = event.currentTarget;
            
            dropdown.classList.toggle('show');
            toggle.classList.toggle('active');
        }

        // Auto-dismiss alerts
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);

        // Confirm delete
        function confirmDelete(message) {
            return confirm(message || 'Bạn có chắc chắn muốn xóa?');
        }
    </script>

    @stack('scripts')
</body>
</html>
