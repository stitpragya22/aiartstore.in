<?php

namespace App\Models;

use CodeIgniter\Model;

class DownloadModel extends Model
{
    protected $table            = 'downloads';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['user_id', 'product_id', 'order_id', 'download_count', 'last_download_at'];
    protected $useTimestamps    = true;

    public function getUserDownloads($userId)
    {
        return $this->select('downloads.*, products.title, products.slug, products.image, products.file, products.file_size, products.dimensions')
            ->join('products', 'products.id = downloads.product_id')
            ->where('downloads.user_id', $userId)
            ->orderBy('downloads.id', 'DESC')
            ->findAll();
    }

    public function getDownload($userId, $productId, $orderId)
    {
        return $this->where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->first();
    }
}
