<?php

namespace App\Controllers;

use App\Models\DownloadModel;
use App\Models\OrderItemModel;

class Download extends BaseController
{
    public function index()
    {
        if (!auth()->loggedIn()) {
            return $this->redirectToLogin();
        }

        $user = auth()->user();
        $downloadModel = new DownloadModel();
        $downloads = $downloadModel->getUserDownloads($user->id);
        foreach ($downloads as $key => $download) {
            $downloads[$key] = $downloadModel->ensureToken($download);
            $downloads[$key]['is_available'] = $downloadModel->isAvailable($downloads[$key]);
        }

        $data['downloads'] = $downloads;
        $data['title'] = 'My Downloads';

        return view('downloads/index', $data);
    }

    public function file($productId, $orderId)
    {
        if (!auth()->loggedIn()) {
            return $this->redirectToLogin();
        }

        $user = auth()->user();
        $downloadModel = new DownloadModel();
        $download = $downloadModel->getDownload($user->id, $productId, $orderId);

        if (!$download) {
            return redirect()->to('/downloads')->with('error', 'Download not found');
        }

        return redirect()->to($downloadModel->getDownloadUrl($download));
    }

    public function fileByToken(string $token)
    {
        if (!auth()->loggedIn()) {
            return $this->redirectToLogin();
        }

        $user = auth()->user();
        $downloadModel = new DownloadModel();
        $download = $downloadModel->getByToken($token);

        if (!$download || (int) $download['user_id'] !== (int) $user->id) {
            return redirect()->to('/downloads')->with('error', 'Download not found');
        }

        if (!$downloadModel->isAvailable($download)) {
            return redirect()->to('/downloads')->with('error', 'This download link is expired, revoked, or has reached its download limit.');
        }

        if (empty($download['file'])) {
            return redirect()->to('/downloads')->with('error', 'File not available');
        }

        $filePath = $this->resolveProductFile($download['file']);
        if ($filePath === null) {
            log_message('error', 'Download file missing: ' . $download['file'] . ' for download id ' . $download['id']);
            return redirect()->to('/downloads')->with('error', 'File not found on server');
        }

        $downloadModel->recordDownload($download);
        $this->response->noCache();

        return $this->response->download($filePath, null);
    }

    public function invoice($orderId)
    {
        if (!auth()->loggedIn()) {
            return $this->redirectToLogin();
        }

        $order = model(\App\Models\OrderModel::class)->find($orderId);
        if (!$order || (int)$order['user_id'] !== (int)auth()->user()->id) {
            return redirect()->to('/orders')->with('error', 'Order not found');
        }

        $order['items'] = model(OrderItemModel::class)->where('order_id', $orderId)->findAll();
        $order['invoice'] = model(\App\Models\InvoiceModel::class)->where('order_id', $orderId)->first();

        $html = view('orders/invoice_pdf', ['order' => $order]);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('invoice-' . $order['order_number'] . '.pdf', ['Attachment' => true]);
    }

    private function redirectToLogin()
    {
        session()->setTempdata('beforeLoginUrl', current_url(), 300);

        return redirect()->to('/login')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    private function resolveProductFile(string $file): ?string
    {
        $file = basename($file);
        if (!preg_match('/^[a-zA-Z0-9_\-.]+$/', $file)) {
            return null;
        }

        $paths = [
            WRITEPATH . 'uploads/products/' . $file,
            FCPATH . 'uploads/products/' . $file,
        ];

        foreach ($paths as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}
