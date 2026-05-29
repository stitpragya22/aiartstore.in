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

    private function productDetailsJson(string $productType): ?string
    {
        $details = $this->request->getPost('details_json');
        if (!is_array($details)) {
            return $details ?: null;
        }

        $allowedByType = [
            'ebook'  => ['author', 'pages', 'language', 'isbn'],
            'audio'  => ['duration', 'narrator', 'bitrate'],
            'bundle' => ['bundle_items'],
        ];

        $allowed = $allowedByType[$productType] ?? [];
        $details = array_intersect_key($details, array_flip($allowed));
        $details = array_filter($details, static fn($value) => trim((string) $value) !== '');

        return $details ? json_encode($details) : null;
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

                $productType = $this->request->getPost('product_type') ?? 'art';
                $detailsJson = $this->productDetailsJson($productType);

                $data = [
                    'category_id'   => $this->request->getPost('category_id'),
                    'product_type'  => $productType,
                    'title'         => $this->request->getPost('title'),
                    'subtitle'      => $this->request->getPost('subtitle'),
                    'slug'          => $slug,
                    'description'   => $this->request->getPost('description'),
                    'highlights'    => $this->request->getPost('highlights'),
                    'features'      => $this->request->getPost('features'),
                    'details_json'  => $detailsJson,
                    'content'       => $this->request->getPost('content'),
                    'price'         => $this->request->getPost('price'),
                    'compare_price' => $this->request->getPost('compare_price') ?: null,
                    'tags'          => $this->request->getPost('tags'),
                    'dimensions'    => $this->request->getPost('dimensions'),
                    'file_size'     => $this->request->getPost('file_size'),
                    'is_featured'   => $this->request->getPost('is_featured') ? 1 : 0,
                    'status'        => $this->request->getPost('status') ?? 'active',
                ];

                $uploadPath = FCPATH . 'uploads/products';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $image = $this->request->getFile('image');
                if ($image && $image->isValid() && !$image->hasMoved()) {
                    if (!in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                        return redirect()->back()->withInput()->with('error', 'Image must be JPG, PNG or WebP only.');
                    }
                    $newName = $slug . '_' . $image->getRandomName();
                    $image->move($uploadPath, $newName);
                    $data['image'] = $newName;

                    $watermark = new Watermark();
                    $wmName = 'wm_' . $newName;
                    $watermark->apply($uploadPath . '/' . $newName, $uploadPath . '/' . $wmName);
                    $data['image_watermarked'] = $wmName;
                } elseif ($this->request->getFile('image') && $this->request->getFile('image')->getError() !== UPLOAD_ERR_NO_FILE) {
                    return redirect()->back()->withInput()->with('error', 'Image upload failed. Check file size and type (max 40MB, jpg/png/webp only).');
                }

                $file = $this->request->getFile('file');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'application/zip', 'application/pdf', 'image/tiff', 'image/vnd.adobe.photoshop'];
                    if (!in_array($file->getMimeType(), $allowedTypes)) {
                        return redirect()->back()->withInput()->with('error', 'Product file must be JPG, PNG, WebP, TIFF, PSD, ZIP or PDF.');
                    }
                    $filePath = WRITEPATH . 'uploads/products';
                    if (!is_dir($filePath)) {
                        mkdir($filePath, 0755, true);
                    }
                    $fileName = $slug . '_' . $file->getRandomName();
                    $file->move($filePath, $fileName);
                    $data['file'] = $fileName;
                } elseif ($this->request->getFile('file') && $this->request->getFile('file')->getError() !== UPLOAD_ERR_NO_FILE) {
                    return redirect()->back()->withInput()->with('error', 'File upload failed. Check file size (max 40MB).');
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

            $productType = $this->request->getPost('product_type') ?? 'art';
            $detailsJson = $this->productDetailsJson($productType);

            $data = [
                'category_id'   => $this->request->getPost('category_id'),
                'product_type'  => $productType,
                'title'         => $this->request->getPost('title'),
                'subtitle'      => $this->request->getPost('subtitle'),
                'slug'          => $slug,
                'description'   => $this->request->getPost('description'),
                'highlights'    => $this->request->getPost('highlights'),
                'features'      => $this->request->getPost('features'),
                'details_json'  => $detailsJson,
                'content'       => $this->request->getPost('content'),
                'price'         => $this->request->getPost('price'),
                'compare_price' => $this->request->getPost('compare_price') ?: null,
                'tags'          => $this->request->getPost('tags'),
                'dimensions'    => $this->request->getPost('dimensions'),
                'file_size'     => $this->request->getPost('file_size'),
                'is_featured'   => $this->request->getPost('is_featured') ? 1 : 0,
                'status'        => $this->request->getPost('status') ?? 'active',
            ];

            $uploadPath = FCPATH . 'uploads/products';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $image = $this->request->getFile('image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                if (!in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                    return redirect()->back()->withInput()->with('error', 'Image must be JPG, PNG or WebP only.');
                }
                $oldImage = basename($product['image'] ?? '');
                $oldWatermarked = basename($product['image_watermarked'] ?? '');
                if ($oldImage && file_exists($uploadPath . '/' . $oldImage)) {
                    unlink($uploadPath . '/' . $oldImage);
                }
                if ($oldWatermarked && file_exists($uploadPath . '/' . $oldWatermarked)) {
                    unlink($uploadPath . '/' . $oldWatermarked);
                }

                $newName = $slug . '_' . $image->getRandomName();
                $image->move($uploadPath, $newName);
                $data['image'] = $newName;

                $watermark = new Watermark();
                $wmName = 'wm_' . $newName;
                $watermark->apply($uploadPath . '/' . $newName, $uploadPath . '/' . $wmName);
                $data['image_watermarked'] = $wmName;
            } elseif ($image && $image->getError() !== UPLOAD_ERR_NO_FILE) {
                return redirect()->back()->withInput()->with('error', 'Image upload failed: ' . $image->getErrorString());
            }

            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'application/zip', 'application/pdf', 'image/tiff', 'image/vnd.adobe.photoshop'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    return redirect()->back()->withInput()->with('error', 'Product file must be JPG, PNG, WebP, TIFF, PSD, ZIP or PDF.');
                }
                $filePath = WRITEPATH . 'uploads/products';
                if (!is_dir($filePath)) {
                    mkdir($filePath, 0755, true);
                }
                $oldFile = basename($product['file'] ?? '');
                if ($oldFile && file_exists(WRITEPATH . 'uploads/products/' . $oldFile)) {
                    unlink(WRITEPATH . 'uploads/products/' . $oldFile);
                } elseif ($oldFile && file_exists($uploadPath . '/' . $oldFile)) {
                    unlink($uploadPath . '/' . $oldFile);
                }
                $fileName = $slug . '_' . $file->getRandomName();
                $file->move($filePath, $fileName);
                $data['file'] = $fileName;
            } elseif ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                return redirect()->back()->withInput()->with('error', 'File upload failed: ' . $file->getErrorString());
            }

            if ($this->productModel->update($id, $data) === false) {
                $errors = $this->productModel->errors();
                return redirect()->back()->withInput()->with('errors', $errors ?: ['Update failed, please check your input']);
            }

            return redirect()->to('/admin/products')->with('message', 'Product updated successfully');
        }

        $data['product'] = $product;
        $data['categories'] = $this->categoryModel->where('status', 'active')->findAll();
        $data['title'] = 'Edit Product';
        return view('admin/products/form', $data);
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/products')->with('error', 'Invalid request');
        }

        $product = $this->productModel->find($id);
        if ($product) {
            $uploadPath = FCPATH . 'uploads/products';
            $filePath = WRITEPATH . 'uploads/products';
            $oldImage = basename($product['image'] ?? '');
            $oldWatermarked = basename($product['image_watermarked'] ?? '');
            $oldFile = basename($product['file'] ?? '');
            if ($oldImage && file_exists($uploadPath . '/' . $oldImage)) {
                unlink($uploadPath . '/' . $oldImage);
            }
            if ($oldWatermarked && file_exists($uploadPath . '/' . $oldWatermarked)) {
                unlink($uploadPath . '/' . $oldWatermarked);
            }
            if ($oldFile && file_exists($filePath . '/' . $oldFile)) {
                unlink($filePath . '/' . $oldFile);
            } elseif ($oldFile && file_exists($uploadPath . '/' . $oldFile)) {
                unlink($uploadPath . '/' . $oldFile);
            }
            $this->productModel->delete($id);
        }
        return redirect()->to('/admin/products')->with('message', 'Product deleted successfully');
    }
}
