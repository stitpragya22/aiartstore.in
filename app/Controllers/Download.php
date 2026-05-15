<?php

namespace App\Controllers;

use App\Models\DownloadModel;
use App\Models\OrderItemModel;

class Download extends BaseController
{
    public function index()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();
        $downloadModel = new DownloadModel();
        $data['downloads'] = $downloadModel->getUserDownloads($user->id);
        $data['title'] = 'My Downloads';

        return view('downloads/index', $data);
    }

    public function file($productId, $orderId)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();
        $downloadModel = new DownloadModel();
        $download = $downloadModel->getDownload($user->id, $productId, $orderId);

        if (!$download) {
            return redirect()->to('/downloads')->with('error', 'Download not found');
        }

        $product = model(\App\Models\ProductModel::class)->find($productId);
        if (!$product || !$product['file']) {
            return redirect()->to('/downloads')->with('error', 'File not available');
        }

        $filePath = FCPATH . 'uploads/products/' . $product['file'];

        if (!file_exists($filePath)) {
            return redirect()->to('/downloads')->with('error', 'File not found on server');
        }

        $downloadModel->update($download['id'], [
            'download_count'  => $download['download_count'] + 1,
            'last_download_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->download($filePath, null);
    }

    public function invoice($orderId)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $order = model(\App\Models\OrderModel::class)->find($orderId);
        if (!$order || $order['user_id'] !== auth()->user()->id) {
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
}
