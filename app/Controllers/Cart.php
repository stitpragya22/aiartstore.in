<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Cart extends BaseController
{
    public function index()
    {
        $cart = session()->get('cart') ?? [];
        $productModel = model(ProductModel::class);
        $changed = false;
        foreach ($cart as $id => &$item) {
            $product = $productModel->find($id);
            if ($product && !empty($product['is_digital']) && $item['quantity'] != 1) {
                $item['quantity'] = 1;
                $changed = true;
            }
        }
        unset($item);
        if ($changed) {
            session()->set('cart', $cart);
        }
        $data['cart'] = $cart;
        $data['total'] = array_sum(array_map(function($i) {
            return $i['price'] * $i['quantity'];
        }, $cart));
        $data['title'] = 'Shopping Cart';
        return view('cart/index', $data);
    }

    public function add()
    {
        $id = (int) $this->request->getPost('id');
        $qty = (int)($this->request->getPost('quantity') ?? 1);

        if ($id < 1) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid product']);
        }

        $product = model(ProductModel::class)->find($id);
        if (!$product || $product['status'] !== 'active') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Product not found']);
        }

        if (auth()->loggedIn() && isProductPurchased($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'You already own this product']);
        }

        $cart = session()->get('cart') ?? [];

        if (isset($cart[$id])) {
            if (!empty($product['is_digital'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Item already in cart']);
            }
            $cart[$id]['quantity'] += $qty;
        } else {
            $cart[$id] = [
                'id'          => $product['id'],
                'title'       => $product['title'],
                'slug'        => $product['slug'],
                'price'       => $product['price'],
                'image'       => $product['image'],
                'quantity'    => !empty($product['is_digital']) ? 1 : $qty,
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
        $id = (int) $this->request->getPost('id');

        $cart = session()->get('cart') ?? [];

        if (isset($cart[$id])) {
            $product = model(ProductModel::class)->find($id);
            if (!empty($product['is_digital'])) {
                return redirect()->to('/cart');
            }
            $qty = (int)($this->request->getPost('quantity') ?? 1);
            if ($qty < 1) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $qty;
            }
        }

        session()->set('cart', $cart);
        return redirect()->to('/cart');
    }

    public function buyNow()
    {
        $id = (int) $this->request->getPost('id');
        if ($id < 1) {
            return redirect()->to('/shop')->with('error', 'Invalid request');
        }

        $product = model(ProductModel::class)->find($id);
        if (!$product || $product['status'] !== 'active') {
            return redirect()->to('/shop')->with('error', 'Product not found');
        }

        if (auth()->loggedIn() && isProductPurchased($id)) {
            return redirect()->to('/shop/' . $product['slug'])->with('error', 'You already own this product');
        }

        $cart = session()->get('cart') ?? [];
        $cart[$id] = [
            'id'       => $product['id'],
            'title'    => $product['title'],
            'slug'     => $product['slug'],
            'price'    => $product['price'],
            'image'    => $product['image'],
            'quantity' => 1,
        ];
        session()->set('cart', $cart);

        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('message', 'Please login to complete your purchase');
        }

        return redirect()->to('/checkout');
    }

    public function remove($id)
    {
        $id = (int) $id;
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
