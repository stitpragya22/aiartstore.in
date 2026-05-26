<?php

namespace App\Models;

use CodeIgniter\Model;

class DownloadModel extends Model
{
    protected $table            = 'downloads';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_id', 'product_id', 'order_id', 'download_token', 'download_count',
        'max_downloads', 'last_download_at', 'expires_at', 'revoked_at',
    ];
    protected $useTimestamps    = true;

    public function getUserDownloads($userId)
    {
        return $this->select('downloads.*, products.title, products.slug, products.image, products.file, products.file_size, products.dimensions')
            ->join('products', 'products.id = downloads.product_id')
            ->where('downloads.user_id', $userId)
            ->orderBy('downloads.id', 'DESC')
            ->findAll();
    }

    public function getPurchasesByUser($userId)
    {
        return $this->select('downloads.product_id, downloads.order_id, downloads.download_token, downloads.created_at')
            ->where('user_id', $userId)
            ->orderBy('downloads.id', 'DESC')
            ->findAll();
    }

    public function getPurchasedProductIds($userId)
    {
        $rows = $this->distinct()
            ->select('product_id')
            ->where('user_id', $userId)
            ->findAll();
        return array_column($rows, 'product_id');
    }

    public function getDownload($userId, $productId, $orderId)
    {
        return $this->where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->first();
    }

    public function getByToken(string $token): ?array
    {
        if ($token === '' || !preg_match('/^[a-f0-9]{64}$/', $token)) {
            return null;
        }

        return $this->select('downloads.*, products.title, products.file, products.status as product_status')
            ->join('products', 'products.id = downloads.product_id')
            ->where('downloads.download_token', $token)
            ->first();
    }

    public function getUserProductDownload(int $userId, int $productId): ?array
    {
        return $this->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }

    public function hasActiveDownload(int $userId, int $productId): bool
    {
        $builder = $this->builder();
        $builder->select('downloads.*, products.status as product_status');
        $builder->join('products', 'products.id = downloads.product_id');
        $builder->where('downloads.user_id', $userId);
        $builder->where('downloads.product_id', $productId);
        $download = $builder->get()->getRowArray();

        if (!$download) {
            return false;
        }

        return $this->isAvailable($download);
    }

    public function createAccess(int $userId, int $productId, int $orderId): int
    {
        $existing = $this->getUserProductDownload($userId, $productId);
        if ($existing) {
            if (!$this->isAvailable($existing)) {
                $this->reissueAccess((int) $existing['id']);
                $this->update($existing['id'], ['order_id' => $orderId]);
            }
            return (int) $existing['id'];
        }

        return (int) $this->insert([
            'user_id'        => $userId,
            'product_id'     => $productId,
            'order_id'       => $orderId,
            'download_token' => $this->generateUniqueToken(),
            'max_downloads'  => $this->defaultMaxDownloads(),
            'expires_at'     => $this->defaultExpiresAt(),
        ]);
    }

    public function ensureToken(array $download): array
    {
        if (!empty($download['download_token'])) {
            return $download;
        }

        $download['download_token'] = $this->generateUniqueToken();
        $this->update($download['id'], ['download_token' => $download['download_token']]);

        return $download;
    }

    public function getDownloadUrl(array $download): string
    {
        $download = $this->ensureToken($download);

        return site_url('/download/file/' . $download['download_token']);
    }

    public function isAvailable(array $download): bool
    {
        if (!empty($download['revoked_at'])) {
            return false;
        }

        if (!empty($download['expires_at']) && strtotime($download['expires_at']) < time()) {
            return false;
        }

        if ((int) ($download['max_downloads'] ?? 0) > 0 && (int) $download['download_count'] >= (int) $download['max_downloads']) {
            return false;
        }

        return ($download['product_status'] ?? 'active') === 'active';
    }

    public function recordDownload(array $download): bool
    {
        return $this->builder()
            ->set('download_count', 'download_count + 1', false)
            ->set('last_download_at', date('Y-m-d H:i:s'))
            ->where('id', (int) $download['id'])
            ->update();
    }

    public function reissueAccess(int $downloadId): bool
    {
        return $this->update($downloadId, [
            'download_token'  => $this->generateUniqueToken(),
            'download_count'  => 0,
            'max_downloads'   => $this->defaultMaxDownloads(),
            'last_download_at'=> null,
            'expires_at'      => $this->defaultExpiresAt(),
            'revoked_at'      => null,
        ]);
    }

    public function revokeAccess(int $downloadId): bool
    {
        return $this->update($downloadId, ['revoked_at' => date('Y-m-d H:i:s')]);
    }

    private function generateUniqueToken(): string
    {
        do {
            $token = bin2hex(random_bytes(32));
        } while ($this->where('download_token', $token)->first() !== null);

        return $token;
    }

    private function defaultMaxDownloads(): int
    {
        return max(0, (int) env('downloads.maxDownloads', 10));
    }

    private function defaultExpiresAt(): ?string
    {
        $days = (int) env('downloads.expiryDays', 365);

        if ($days <= 0) {
            return null;
        }

        return date('Y-m-d H:i:s', strtotime('+' . $days . ' days'));
    }
}
