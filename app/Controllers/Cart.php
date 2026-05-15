<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Cart extends BaseController
{
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        $data['cart'] = $cart;
        $data['total'] = array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
        $data['title'] = 'Shopping Cart';
        return view('cart/index', $data);
    }

    public function add()
    {
        $id = $this->request->getPost('id');
        $qty = (int)($this->request->getPost('quantity') ?? 1);

        $product = model(ProductModel::class)->find($id);
        if (!$product || $product['status'] !== 'active') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Product not found']);
        }

        $cart = session()->get('cart') ?? [];

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $cart[$id] = [
                'id'          => $product['id'],
                'title'       => $product['title'],
                'slug'        => $product['slug'],
                'price'       => $product['price'],
                'image'       => $product['image'],
                'quantity'    => $qty,
            ];
        }

        session()->set('cart', $cart);

        $totalQty = array_sum(array_column($cart, 'quantity'));

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Item added to cart',
                'count'   => $totalQty,
            ]);
        }

        return redirect()->to('/cart')->with('message', 'Item added to cart');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $qty = (int)($this->request->getPost('quantity') ?? 1);

        $cart = session()->get('cart') ?? [];

        if ($qty < 1) {
            unset($cart[$id]);
        } elseif (isset($cart[$id])) {
            $cart[$id]['quantity'] = $qty;
        }

        session()->set('cart', $cart);
        return redirect()->to('/cart');
    }

    public function remove($id)
    {
        $cart = session()->get('cart') ?? [];
        unset($cart[$id]);
        session()->set('cart', $cart);
        return redirect()->to('/cart');
    }

    public function count()
    {
        $cart = session()->get('cart') ?? [];
        $count = array_sum(array_column($cart, 'quantity'));
        return $this->response->setJSON(['count' => $count]);
    }
}
