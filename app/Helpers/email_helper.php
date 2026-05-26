<?php

if (!function_exists('_orderEmail')) {
    function _orderEmail($order)
    {
        if (!empty($order['customer_email'])) return $order['customer_email'];
        if (!empty($order['email'])) return $order['email'];
        try {
            $user = auth()->user();
            if ($user && $user->email) return $user->email;
        } catch (\Exception $e) {}
        return env('email.adminEmail', 'info@aiartstore.in');
    }
}

if (!function_exists('sendOrderConfirmation')) {
    function sendOrderConfirmation($order, $items)
    {
        $email = \Config\Services::email();
        $email->setTo(_orderEmail($order));
        $email->setSubject('Order Confirmed - ' . $order['order_number']);

        $message = view('emails/order_confirmation', [
            'order' => $order,
            'items' => $items,
        ]);
        $email->setMessage($message);

        return $email->send();
    }
}

if (!function_exists('sendDownloadLinks')) {
    function sendDownloadLinks($order, $items)
    {
        $downloadModel = model(\App\Models\DownloadModel::class);
        foreach ($items as $key => $item) {
            if (empty($order['id']) || empty($item['product_id'])) {
                continue;
            }

            $download = $downloadModel->getDownload((int) $order['user_id'], (int) $item['product_id'], (int) $order['id']);
            if ($download) {
                $items[$key]['download_url'] = $downloadModel->getDownloadUrl($download);
            }
        }

        $email = \Config\Services::email();
        $email->setTo(_orderEmail($order));
        $email->setSubject('Your Downloads Are Ready - ' . $order['order_number']);

        $message = view('emails/download_links', [
            'order' => $order,
            'items' => $items,
        ]);
        $email->setMessage($message);

        return $email->send();
    }
}

if (!function_exists('sendInvoiceEmail')) {
    function sendInvoiceEmail($order, $pdfContent)
    {
        $email = \Config\Services::email();
        $email->setTo(_orderEmail($order));
        $email->setSubject('Invoice - ' . $order['order_number']);

        $message = view('emails/invoice', ['order' => $order]);
        $email->setMessage($message);
        $email->attach($pdfContent, 'attachment', 'invoice-' . $order['order_number'] . '.pdf', 'application/pdf');

        return $email->send();
    }
}

if (!function_exists('sendAdminNotification')) {
    function sendAdminNotification($order)
    {
        $email = \Config\Services::email();
        $to = env('email.adminEmail', 'info@aiartstore.in');
        $email->setTo($to);
        $email->setSubject('New Order - ' . $order['order_number']);

        $order['customer_email'] = _orderEmail($order);
        $message = view('emails/admin_notification', ['order' => $order]);
        $email->setMessage($message);

        return $email->send();
    }
}
