<?php

if (!function_exists('getWishlistCount')) {
    function getWishlistCount()
    {
        if (!auth()->loggedIn()) {
            return 0;
        }
        return model('App\Models\WishlistModel')->where('user_id', auth()->user()->id)->countAllResults();
    }
}

if (!function_exists('isProductWishlisted')) {
    function isProductWishlisted($productId)
    {
        if (!auth()->loggedIn()) {
            return false;
        }
        return model('App\Models\WishlistModel')->isWishlisted(auth()->user()->id, (int)$productId);
    }
}
