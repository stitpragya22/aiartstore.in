<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Shield Auth Routes
service('auth')->routes($routes);

// Home
$routes->get('/', 'Home::index');

// Shop / Gallery
$routes->get('/shop', 'Shop::index');
$routes->get('/shop/(:segment)', 'Shop::detail/$1');

// Google Auth
$routes->get('/google-auth/login', 'GoogleAuth::login');
$routes->get('/google-auth/callback', 'GoogleAuth::callback');

// Cart
$routes->get('/cart', 'Cart::index');
$routes->post('/cart/add', 'Cart::add');
$routes->post('/cart/update', 'Cart::update');
$routes->get('/cart/remove/(:num)', 'Cart::remove/$1');
$routes->get('/cart/count', 'Cart::count');

// Checkout
$routes->get('/checkout', 'Checkout::index');
$routes->post('/checkout/createOrder', 'Checkout::createOrder');
$routes->post('/checkout/verify', 'Checkout::verify');

// Orders (must be after google-auth routes to avoid conflict)
$routes->get('/orders', 'Orders::index');
$routes->get('/orders/(:any)', 'Orders::detail/$1');

// Downloads
$routes->get('/downloads', 'Download::index');
$routes->get('/download/file/(:num)/(:num)', 'Download::file/$1/$2');
$routes->get('/download/invoice/(:num)', 'Download::invoice/$1');

// Admin Routes
$routes->group('admin', ['filter' => 'group:superadmin,admin,developer'], function($routes) {
    $routes->get('/', 'Admin\Dashboard::index');

    $routes->get('products', 'Admin\Products::index');
    $routes->get('products/create', 'Admin\Products::create');
    $routes->post('products/create', 'Admin\Products::create');
    $routes->get('products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->post('products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->get('products/delete/(:num)', 'Admin\Products::delete/$1');

    $routes->get('categories', 'Admin\Categories::index');
    $routes->get('categories/create', 'Admin\Categories::create');
    $routes->post('categories/create', 'Admin\Categories::create');
    $routes->get('categories/edit/(:num)', 'Admin\Categories::edit/$1');
    $routes->post('categories/edit/(:num)', 'Admin\Categories::edit/$1');
    $routes->get('categories/delete/(:num)', 'Admin\Categories::delete/$1');

    $routes->get('orders', 'Admin\Orders::index');
    $routes->get('orders/(:num)', 'Admin\Orders::detail/$1');
    $routes->post('orders/update-status/(:num)', 'Admin\Orders::updateStatus/$1');

    $routes->get('users', 'Admin\Users::index');
    $routes->post('users/toggle-group/(:num)', 'Admin\Users::toggleGroup/$1');
});
