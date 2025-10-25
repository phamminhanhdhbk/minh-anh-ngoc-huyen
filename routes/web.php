<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Home page
Route::get('/', 'PublicController@index')->name('home');

// Products
Route::get('/products', 'ProductController@index')->name('products.index');
Route::get('/products/{product}', 'ProductController@show')->name('products.show');

// Cart
Route::get('/cart', 'CartController@index')->name('cart.index');
Route::post('/cart/add', 'CartController@add')->name('cart.add');
Route::put('/cart/{cart}', 'CartController@update')->name('cart.update');
Route::delete('/cart/{cart}', 'CartController@remove')->name('cart.remove');
Route::get('/cart/count', 'CartController@count')->name('cart.count');

// Checkout & Orders
Route::get('/checkout', 'CartController@checkout')->name('checkout');
Route::post('/checkout', 'OrderController@store')->name('checkout.process');
Route::post('/orders', 'OrderController@store')->name('orders.store');
Route::get('/orders/{order}/success', 'OrderController@success')->name('order.success');

// Reviews
Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews', 'ReviewController@store')->name('reviews.store');
    Route::get('/reviews/{review}/edit', 'ReviewController@edit')->name('reviews.edit');
    Route::put('/reviews/{review}', 'ReviewController@update')->name('reviews.update');
    Route::delete('/reviews/{review}', 'ReviewController@destroy')->name('reviews.destroy');
    Route::post('/reviews/{review}/helpful', 'ReviewController@toggleHelpful')->name('reviews.helpful');
    Route::post('/reviews/{review}/report', 'ReviewController@report')->name('reviews.report');

    // Wishlist
    Route::get('/wishlist', 'WishlistController@index')->name('wishlist.index');
    Route::post('/wishlist/add', 'WishlistController@store')->name('wishlist.add');
    Route::delete('/wishlist/remove', 'WishlistController@destroy')->name('wishlist.remove');
    Route::post('/wishlist/toggle', 'WishlistController@toggle')->name('wishlist.toggle');
    Route::get('/wishlist/count', 'WishlistController@count')->name('wishlist.count');
});

// Public review routes
Route::get('/products/{product}/reviews', 'ReviewController@productReviews')->name('reviews.product');

// Blog routes (public)
Route::get('/blog', 'BlogController@index')->name('blog.index');
Route::get('/blog/{slug}', 'BlogController@show')->name('blog.show');
Route::post('/blog/{post}/comment', 'BlogController@storeComment')->name('blog.comment');

Auth::routes();

// Dashboard route (old /home)
Route::get('/dashboard', 'HomeController@index')->name('dashboard');

// Include test routes
require_once __DIR__ . '/test.php';

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', 'Admin\AdminController@dashboard')->name('dashboard');

    // Categories
    Route::resource('categories', 'Admin\CategoryController');

    // Products
    Route::resource('products', 'Admin\ProductController');
    Route::patch('products/{product}/toggle-featured', 'Admin\ProductController@toggleFeatured')->name('products.toggle-featured');
    Route::patch('products/{product}/toggle-status', 'Admin\ProductController@toggleStatus')->name('products.toggle-status');

    // Product Images
    Route::delete('product-images/{image}', 'Admin\ProductController@deleteImage')->name('product-images.delete');
    Route::patch('product-images/{image}/primary', 'Admin\ProductController@setPrimaryImage')->name('product-images.primary');
    Route::post('products/{product}/reorder-images', 'Admin\ProductController@reorderImages')->name('products.reorder-images');

    // Orders
    Route::get('orders', 'Admin\OrderController@index')->name('orders.index');
    Route::get('orders/{order}', 'Admin\OrderController@show')->name('orders.show');
    Route::patch('orders/{order}/status', 'Admin\OrderController@updateStatus')->name('orders.updateStatus');

    // Users
    Route::resource('users', 'Admin\UserController');
    Route::patch('users/{user}/toggle-admin', 'Admin\UserController@toggleAdmin')->name('users.toggle-admin');

    // Site Settings
    Route::get('settings', 'Admin\SiteSettingController@index')->name('settings.index');
    Route::get('settings/edit', 'Admin\SiteSettingController@edit')->name('settings.edit');
    Route::put('settings', 'Admin\SiteSettingController@update')->name('settings.update');
    Route::post('settings/reset', 'Admin\SiteSettingController@reset')->name('settings.reset');

    // Reviews Management
    Route::get('reviews', 'Admin\ReviewController@index')->name('reviews.index');
    Route::get('reviews/{review}', 'Admin\ReviewController@show')->name('reviews.show');
    Route::patch('reviews/{review}/approve', 'Admin\ReviewController@approve')->name('reviews.approve');
    Route::patch('reviews/{review}/reject', 'Admin\ReviewController@reject')->name('reviews.reject');
    Route::delete('reviews/{review}', 'Admin\ReviewController@destroy')->name('reviews.destroy');

    // Blog Management
    Route::post('posts/upload-image', 'Admin\PostController@uploadImage')->name('posts.upload-image');
    Route::resource('blog-categories', 'Admin\BlogCategoryController');
    Route::resource('posts', 'Admin\PostController');
    Route::resource('post-tags', 'Admin\PostTagController');

    // Post Comments Management
    Route::get('post-comments', 'Admin\PostCommentController@index')->name('post-comments.index');
    Route::patch('post-comments/{comment}/approve', 'Admin\PostCommentController@approve')->name('post-comments.approve');
    Route::patch('post-comments/{comment}/reject', 'Admin\PostCommentController@reject')->name('post-comments.reject');
    Route::delete('post-comments/{comment}', 'Admin\PostCommentController@destroy')->name('post-comments.destroy');

    // Menu Management
    Route::resource('menus', 'Admin\MenuController');
    Route::post('menus/{menu}/items', 'Admin\MenuController@storeItem')->name('menus.items.store');
    Route::put('menus/{menu}/items/{item}', 'Admin\MenuController@updateItem')->name('menus.items.update');
    Route::delete('menus/{menu}/items/{item}', 'Admin\MenuController@destroyItem')->name('menus.items.destroy');
    Route::post('menus/{menu}/update-order', 'Admin\MenuController@updateOrder')->name('menus.update-order');

    // Theme Management
    Route::resource('themes', 'Admin\ThemeController');
    Route::post('themes/{theme}/activate', 'Admin\ThemeController@activate')->name('themes.activate');
    Route::get('themes/{theme}/preview', 'Admin\ThemeController@preview')->name('themes.preview');
    Route::get('themes/clear-preview', 'Admin\ThemeController@clearPreview')->name('themes.clearPreview');
    Route::post('themes/{theme}/settings', 'Admin\ThemeController@updateSettings')->name('themes.updateSettings');
});
