<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Libraries\Watermark;

class Products extends BaseController
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data['products'] = $this->productModel->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->orderBy('products.id', 'DESC')
            ->findAll();
        $data['title'] = 'Manage Products';
        return view('admin/products/index', $data);
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $rules = [
                'title'       => 'required',
                'price'       => 'required|numeric',
                'category_id' => 'required',
            ];

            if ($this->validate($rules)) {
                $slug = url_title($this->request->getPost('title'), '-', true);
                $existing = $this->productModel->where('slug', $slug)->first();
                if ($existing) {
                    $slug .= '-' . uniqid();
                }

                $data = [
                    'category_id'   => $this->request->getPost('category_id'),
                    'title'         => $this->request->getPost('title'),
                    'slug'          => $slug,
                    'description'   => $this->request->getPost('description'),
                    'price'         => $this->request->getPost('price'),
                    'compare_price' => $this->request->getPost('compare_price') ?: null,
                    'tags'          => $this->request->getPost('tags'),
                    'dimensions'    => $this->request->getPost('dimensions'),
                    'file_size'     => $this->request->getPost('file_size'),
                    'is_featured'   => $this->request->getPost('is_featured') ? 1 : 0,
                    'status'        => $this->request->getPost('status') ?? 'active',
                ];

                $image = $this->request->getFile('image');
                if ($image && $image->isValid() && !$image->hasMoved()) {
                    $uploadPath = FCPATH . 'uploads/products';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    $newName = $slug . '_' . $image->getRandomName();
                    $image->move($uploadPath, $newName);
                    $data['image'] = $newName;

                    $watermark = new Watermark();
                    $wmName = 'wm_' . $newName;
                    $watermark->apply($uploadPath . '/' . $newName, $uploadPath . '/' . $wmName);
                    $data['image_watermarked'] = $wmName;
                }

                $file = $this->request->getFile('file');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $fileName = $slug . '_' . $file->getRandomName();
                    $file->move($uploadPath, $fileName);
                    $data['file'] = $fileName;
                }

                $this->productModel->save($data);
                return redirect()->to('/admin/products')->with('message', 'Product created successfully');
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data['categories'] = $this->categoryModel->where('status', 'active')->findAll();
        $data['title'] = 'Add Product';
        return view('admin/products/form', $data);
    }

    public function edit($id = null)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Product not found');
        }

        if ($this->request->is('post')) {
            $slug = url_title($this->request->getPost('title'), '-', true);
            $existing = $this->productModel->where('slug', $slug)->where('id !=', $id)->first();
            if ($existing) {
                $slug .= '-' . uniqid();
            }

            $data = [
                'category_id'   => $this->request->getPost('category_id'),
                'title'         => $this->request->getPost('title'),
                'slug'          => $slug,
                'description'   => $this->request->getPost('description'),
                'price'         => $this->request->getPost('price'),
                'compare_price' => $this->request->getPost('compare_price') ?: null,
                'tags'          => $this->request->getPost('tags'),
                'dimensions'    => $this->request->getPost('dimensions'),
                'file_size'     => $this->request->getPost('file_size'),
                'is_featured'   => $this->request->getPost('is_featured') ? 1 : 0,
                'status'        => $this->request->getPost('status') ?? 'active',
            ];

            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                $uploadPath = FCPATH . 'uploads/products';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                if ($product['image'] && file_exists($uploadPath . '/' . $product['image'])) {
                    unlink($uploadPath . '/' . $product['image']);
                }
                if ($product['image_watermarked'] && file_exists($uploadPath . '/' . $product['image_watermarked'])) {
                    unlink($uploadPath . '/' . $product['image_watermarked']);
                }

                $newName = $slug . '_' . $image->getRandomName();
                $image->move($uploadPath, $newName);
                $data['image'] = $newName;

                $watermark = new Watermark();
                $wmName = 'wm_' . $newName;
                $watermark->apply($uploadPath . '/' . $newName, $uploadPath . '/' . $wmName);
                $data['image_watermarked'] = $wmName;
            }

            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $uploadPath = FCPATH . 'uploads/products';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                if ($product['file'] && file_exists($uploadPath . '/' . $product['file'])) {
                    unlink($uploadPath . '/' . $product['file']);
                }
                $fileName = $slug . '_' . $file->getRandomName();
                $file->move($uploadPath, $fileName);
                $data['file'] = $fileName;
            }

            $this->productModel->update($id, $data);
            return redirect()->to('/admin/products')->with('message', 'Product updated successfully');
        }

        $data['product'] = $product;
        $data['categories'] = $this->categoryModel->where('status', 'active')->findAll();
        $data['title'] = 'Edit Product';
        return view('admin/products/form', $data);
    }

    public function delete($id = null)
    {
        $product = $this->productModel->find($id);
        if ($product) {
            $uploadPath = FCPATH . 'uploads/products';
            if ($product['image'] && file_exists($uploadPath . '/' . $product['image'])) {
                unlink($uploadPath . '/' . $product['image']);
            }
            if ($product['image_watermarked'] && file_exists($uploadPath . '/' . $product['image_watermarked'])) {
                unlink($uploadPath . '/' . $product['image_watermarked']);
            }
            if ($product['file'] && file_exists($uploadPath . '/' . $product['file'])) {
                unlink($uploadPath . '/' . $product['file']);
            }
            $this->productModel->delete($id);
        }
        return redirect()->to('/admin/products')->with('message', 'Product deleted successfully');
    }
}
