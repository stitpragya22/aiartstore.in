<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Shield Auth Routes
service('auth')->routes($routes);

// Home
$routes->get('/', 'Home::index');

// Blog (public)
$routes->get('/blog', 'Blog::index');
$routes->get('/blog/(:any)', 'Blog::detail/$1');

// Shop / Gallery
$routes->get('/shop', 'Shop::index');
$routes->get('/shop/category/(:segment)', 'Shop::category/$1');
$routes->get('/shop/(:segment)', 'Shop::detail/$1');
$routes->post('/shop/review/(:num)', 'Shop::submitReview/$1');

// Google Auth
$routes->get('/auth/google/login', 'GoogleAuth::login');
$routes->get('/auth/google/callback', 'GoogleAuth::callback');
$routes->get('/google-auth/login', 'GoogleAuth::login');
$routes->get('/google-auth/callback', 'GoogleAuth::callback');

// Cart
$routes->get('/cart', 'Cart::index');
$routes->post('/cart/add', 'Cart::add');
$routes->post('/cart/buy-now', 'Cart::buyNow');
$routes->post('/cart/update', 'Cart::update');
$routes->post('/cart/remove/(:num)', 'Cart::remove/$1');
$routes->get('/cart/count', 'Cart::count');

// Checkout
$routes->get('/checkout', 'Checkout::index');
$routes->post('/checkout/createOrder', 'Checkout::createOrder');
$routes->post('/checkout/verify', 'Checkout::verify');
$routes->post('/razorpay/webhook', 'Checkout::webhook');

// Coupon validation (AJAX)
$routes->post('/checkout/validate-coupon', 'Checkout::validateCoupon');

// Prompts (public - free prompts; subscription prompts require login)
$routes->get('/prompts', 'Prompts::index');
$routes->get('/prompts/(:num)/(:any)', 'Prompts::detail/$1/$2');

// Orders / Downloads / Subscriptions
$routes->group('', ['filter' => 'session'], static function ($routes) {
    $routes->get('/orders', 'Orders::index');
    $routes->get('/orders/(:any)', 'Orders::detail/$1');
    $routes->get('/downloads', 'Download::index');
    $routes->get('/download/file/(:segment)', 'Download::fileByToken/$1');
    $routes->get('/download/file/(:num)/(:num)', 'Download::file/$1/$2');
    $routes->get('/download/invoice/(:num)', 'Download::invoice/$1');
    $routes->get('/wishlist', 'Wishlist::index');
    $routes->post('/wishlist/toggle', 'Wishlist::toggle');
    $routes->get('/subscriptions/my', 'Subscriptions::my');
    $routes->get('/subscriptions/purchase/(:num)', 'Subscriptions::purchase/$1');
    $routes->post('/subscriptions/purchase/(:num)', 'Subscriptions::purchase/$1');
    $routes->post('/subscriptions/verify', 'Subscriptions::verify');
    $routes->get('/custom-request/my', 'CustomRequest::my');
    $routes->get('/custom-request/track/(:num)', 'CustomRequest::track/$1');
    $routes->post('/custom-request/track/(:num)', 'CustomRequest::track/$1');
    $routes->post('/custom-request/submit', 'CustomRequest::submit');
});

// Subscription Plans (public)
$routes->get('/subscriptions/plans', 'Subscriptions::plans');

// Sitemaps / Feeds
$routes->get('/sitemap.xml', 'Sitemap::index');
$routes->get('/feed/products.xml', 'Sitemap::feed');
$routes->get('/feed/products.csv', 'Sitemap::feedCsv');

// Custom AI Requests
$routes->get('/custom-request', 'CustomRequest::index');
$routes->post('/custom-request/submit', 'CustomRequest::submit');
$routes->get('/custom-request/success', 'CustomRequest::success');

// Legal Pages
$routes->get('/terms', 'Pages::terms');
$routes->get('/privacy', 'Pages::privacy');
$routes->get('/refund', 'Pages::refund');
$routes->get('/faq', 'Pages::faq');
$routes->get('/about', 'Pages::about');
$routes->get('/contact', 'Pages::contact');

// Admin Routes
$routes->group('admin', ['filter' => 'group:superadmin,admin,developer'], function($routes) {
    $routes->get('/', 'Admin\Dashboard::index');

    $routes->get('products', 'Admin\Products::index');
    $routes->get('products/create', 'Admin\Products::create');
    $routes->post('products/create', 'Admin\Products::create');
    $routes->get('products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->post('products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->post('products/delete/(:num)', 'Admin\Products::delete/$1');

    $routes->get('categories', 'Admin\Categories::index');
    $routes->get('categories/create', 'Admin\Categories::create');
    $routes->post('categories/create', 'Admin\Categories::create');
    $routes->get('categories/edit/(:num)', 'Admin\Categories::edit/$1');
    $routes->post('categories/edit/(:num)', 'Admin\Categories::edit/$1');
    $routes->post('categories/toggle/(:num)', 'Admin\Categories::toggle/$1');
    $routes->post('categories/delete/(:num)', 'Admin\Categories::delete/$1');

    $routes->get('orders', 'Admin\Orders::index');
    $routes->get('orders/(:num)', 'Admin\Orders::detail/$1');
    $routes->post('orders/update-status/(:num)', 'Admin\Orders::updateStatus/$1');
    $routes->post('orders/reissue-download/(:num)', 'Admin\Orders::reissueDownload/$1');
    $routes->post('orders/revoke-download/(:num)', 'Admin\Orders::revokeDownload/$1');

    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings', 'Admin\Settings::index');

    $routes->get('users', 'Admin\Users::index');
    $routes->post('users/toggle-group/(:num)', 'Admin\Users::toggleGroup/$1');

    // Blog
    $routes->get('blog/categories', 'Admin\Blog::categories');
    $routes->get('blog/categories/create', 'Admin\Blog::categoryCreate');
    $routes->post('blog/categories/create', 'Admin\Blog::categoryCreate');
    $routes->get('blog/categories/edit/(:num)', 'Admin\Blog::categoryEdit/$1');
    $routes->post('blog/categories/edit/(:num)', 'Admin\Blog::categoryEdit/$1');
    $routes->post('blog/categories/delete/(:num)', 'Admin\Blog::categoryDelete/$1');

    $routes->get('blog/posts', 'Admin\Blog::posts');
    $routes->get('blog/posts/create', 'Admin\Blog::postCreate');
    $routes->post('blog/posts/create', 'Admin\Blog::postCreate');
    $routes->get('blog/posts/edit/(:num)', 'Admin\Blog::postEdit/$1');
    $routes->post('blog/posts/edit/(:num)', 'Admin\Blog::postEdit/$1');
    $routes->post('blog/posts/delete/(:num)', 'Admin\Blog::postDelete/$1');

    // Prompts
    $routes->get('prompts', 'Admin\Prompts::index');
    $routes->get('prompts/create', 'Admin\Prompts::create');
    $routes->post('prompts/create', 'Admin\Prompts::create');
    $routes->get('prompts/edit/(:num)', 'Admin\Prompts::edit/$1');
    $routes->post('prompts/edit/(:num)', 'Admin\Prompts::edit/$1');
    $routes->post('prompts/delete/(:num)', 'Admin\Prompts::delete/$1');
    $routes->post('prompts/delete-image/(:num)', 'Admin\Prompts::deleteImage/$1');
    $routes->post('prompts/share-facebook/(:num)', 'Admin\Prompts::shareFacebook/$1');
    $routes->post('prompts/share-instagram/(:num)', 'Admin\Prompts::shareInstagram/$1');

    // Coupons
    $routes->get('coupons', 'Admin\Coupons::index');
    $routes->get('coupons/create', 'Admin\Coupons::create');
    $routes->post('coupons/create', 'Admin\Coupons::create');
    $routes->get('coupons/edit/(:num)', 'Admin\Coupons::edit/$1');
    $routes->post('coupons/edit/(:num)', 'Admin\Coupons::edit/$1');
    $routes->post('coupons/delete/(:num)', 'Admin\Coupons::delete/$1');

    // Landing Pages
    $routes->get('landing-pages', 'Admin\LandingPages::index');
    $routes->get('landing-pages/create', 'Admin\LandingPages::create');
    $routes->post('landing-pages/create', 'Admin\LandingPages::create');
    $routes->get('landing-pages/edit/(:num)', 'Admin\LandingPages::edit/$1');
    $routes->post('landing-pages/edit/(:num)', 'Admin\LandingPages::edit/$1');
    $routes->post('landing-pages/delete/(:num)', 'Admin\LandingPages::delete/$1');

    // Subscription Plans
    $routes->get('subscription-plans', 'Admin\SubscriptionPlans::index');
    $routes->get('subscription-plans/create', 'Admin\SubscriptionPlans::create');
    $routes->post('subscription-plans/create', 'Admin\SubscriptionPlans::create');
    $routes->get('subscription-plans/edit/(:num)', 'Admin\SubscriptionPlans::edit/$1');
    $routes->post('subscription-plans/edit/(:num)', 'Admin\SubscriptionPlans::edit/$1');
    $routes->post('subscription-plans/delete/(:num)', 'Admin\SubscriptionPlans::delete/$1');

    // Custom AI Requests
    $routes->get('custom-requests', 'Admin\CustomRequests::index');
    $routes->get('custom-requests/detail/(:num)', 'Admin\CustomRequests::detail/$1');
    $routes->post('custom-requests/detail/(:num)', 'Admin\CustomRequests::detail/$1');
    $routes->post('custom-requests/delete/(:num)', 'Admin\CustomRequests::delete/$1');

    // User Subscriptions (admin)
    $routes->get('user-subscriptions', 'Admin\UserSubscriptions::index');
    $routes->get('user-subscriptions/create', 'Admin\UserSubscriptions::create');
    $routes->post('user-subscriptions/create', 'Admin\UserSubscriptions::create');
    $routes->post('user-subscriptions/cancel/(:num)', 'Admin\UserSubscriptions::cancel/$1');
});

// Landing Pages (public)
$routes->get('/lp/(:any)', 'LandingPage::index/$1');
