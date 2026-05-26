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

if (!function_exists('getUserPurchases')) {
    function getUserPurchases()
    {
        static $purchases = null;
        if ($purchases !== null) return $purchases;
        $purchases = [];
        if (!auth()->loggedIn()) return $purchases;
        $rows = model('App\Models\DownloadModel')->getPurchasesByUser(auth()->user()->id);
        foreach ($rows as $r) {
            if (!isset($purchases[$r['product_id']])) {
                $purchases[$r['product_id']] = [
                    'created_at'      => $r['created_at'],
                    'order_id'        => $r['order_id'],
                    'download_token'  => $r['download_token'] ?? null,
                ];
            }
        }
        return $purchases;
    }

    function isProductPurchased($productId)
    {
        if (!auth()->loggedIn()) {
            return false;
        }
        $downloadModel = model('App\Models\DownloadModel');
        return $downloadModel->hasActiveDownload(auth()->user()->id, $productId);
    }

    function getPurchaseInfo($productId)
    {
        $p = getUserPurchases();
        return $p[$productId] ?? null;
    }

    function getPurchaseDate($productId)
    {
        $info = getPurchaseInfo($productId);
        return $info ? $info['created_at'] : null;
    }

    function getPurchaseOrderId($productId)
    {
        $info = getPurchaseInfo($productId);
        return $info ? $info['order_id'] : null;
    }

    function getPurchaseDownloadUrl($productId)
    {
        if (!auth()->loggedIn()) {
            return site_url('/login');
        }

        $downloadModel = model('App\Models\DownloadModel');
        $download = $downloadModel->getUserProductDownload(auth()->user()->id, $productId);

        if (!$download) {
            return site_url('/downloads');
        }

        $download = $downloadModel->ensureToken($download);

        return site_url('/download/file/' . $download['download_token']);
    }

    function isDownloadAvailable($productId)
    {
        $purchases = getUserPurchases();
        if (!isset($purchases[$productId])) {
            return false;
        }
        $downloadModel = model('App\Models\DownloadModel');
        $download = $downloadModel->getUserProductDownload(auth()->user()->id, $productId);
        if (!$download) {
            return false;
        }
        return $downloadModel->isAvailable($download);
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
