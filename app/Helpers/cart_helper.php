<?php

if (!function_exists('getCartCount')) {
    function getCartCount()
    {
        $cart = session()->get('cart') ?? [];
        return array_sum(array_column($cart, 'quantity'));
    }
}

if (!function_exists('getCartTotal')) {
    function getCartTotal()
    {
        $cart = session()->get('cart') ?? [];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice($price)
    {
        return '₹' . number_format($price, 2);
    }

    function formatPriceRs($price)
    {
        return '₹' . number_format((float)$price, 0);
    }
}
