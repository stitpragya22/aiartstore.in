<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Categories extends BaseController
{
    private $categoryModel;

    private string $uploadPath;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->uploadPath = FCPATH . 'uploads/categories';
    }

    public function index()
    {
        $data['categories'] = $this->categoryModel->orderBy('id', 'DESC')->findAll();
        $data['title'] = 'Manage Categories';
        return view('admin/categories/index', $data);
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $slug = url_title($this->request->getPost('name'), '-', true);
            if (!$this->categoryModel->save([
                'name'             => $this->request->getPost('name'),
                'slug'             => $slug,
                'description'      => $this->request->getPost('description'),
                'meta_title'       => $this->request->getPost('meta_title'),
                'meta_description' => $this->request->getPost('meta_description'),
                'is_curated'       => $this->request->getPost('is_curated') ? 1 : 0,
                'status'           => $this->request->getPost('status') ?? 'active',
            ])) {
                return redirect()->back()->with('errors', $this->categoryModel->errors())->withInput();
            }

            $id = $this->categoryModel->insertID();
            $this->handleImageUpload($id);

            return redirect()->to('/admin/categories')->with('message', 'Category created successfully');
        }

        $data['title'] = 'Add Category';
        return view('admin/categories/form', $data);
    }

    public function edit($id = null)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Category not found');
        }

        if ($this->request->is('post')) {
            $slug = url_title($this->request->getPost('name'), '-', true);

            $existing = $this->categoryModel->where('slug', $slug)->where('id !=', $id)->first();
            if ($existing) {
                $slug = $slug . '-' . $id;
            }

            $data = [
                'id'               => $id,
                'name'             => $this->request->getPost('name'),
                'slug'             => $slug,
                'description'      => $this->request->getPost('description'),
                'meta_title'       => $this->request->getPost('meta_title'),
                'meta_description' => $this->request->getPost('meta_description'),
                'is_curated'       => $this->request->getPost('is_curated') ? 1 : 0,
                'status'           => $this->request->getPost('status') ?? 'active',
            ];

            if (!$this->categoryModel->save($data)) {
                return redirect()->back()->with('errors', $this->categoryModel->errors())->withInput();
            }

            $imageFile = $this->request->getFile('image');
            $removeImage = $this->request->getPost('remove_image');
            if ($removeImage && (!$imageFile || !$imageFile->isValid())) {
                if (!empty($category['image'])) {
                    $oldFile = $this->uploadPath . '/' . $category['image'];
                    if (is_file($oldFile)) {
                        unlink($oldFile);
                    }
                }
                $this->categoryModel->save(['id' => $id, 'image' => null]);
            } else {
                $this->handleImageUpload($id, $category['image'] ?? null);
            }

            return redirect()->to('/admin/categories')->with('message', 'Category updated successfully');
        }

        $data['category'] = $category;
        $data['title'] = 'Edit Category';
        return view('admin/categories/form', $data);
    }

    public function toggle($id = null)
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Category not found']);
        }

        $field = $this->request->getPost('field');
        $value = $this->request->getPost('value');

        if (!in_array($field, ['status', 'is_curated'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid field']);
        }

        if ($field === 'status') {
            $value = ($value === 'active' || $value === '1') ? 'active' : 'inactive';
        } else {
            $value = ($value === '1' || $value === 1) ? 1 : 0;
        }

        $this->categoryModel->skipValidation(true)->update($id, [$field => $value]);
        return $this->response->setJSON(['status' => 'success', 'csrf_hash' => csrf_hash()]);
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/categories')->with('error', 'Invalid request');
        }

        $category = $this->categoryModel->find($id);
        if ($category && !empty($category['image'])) {
            $filePath = $this->uploadPath . '/' . $category['image'];
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }

        $this->categoryModel->delete($id);
        return redirect()->to('/admin/categories')->with('message', 'Category deleted successfully');
    }

    private function handleImageUpload(int $categoryId, ?string $existingImage = null): void
    {
        $file = $this->request->getFile('image');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return;
        }

        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])) {
            return;
        }

        if ($file->getSizeByUnit('mb') > 5) {
            return;
        }

        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($this->uploadPath, $newName);

        if ($existingImage) {
            $oldFile = $this->uploadPath . '/' . $existingImage;
            if (is_file($oldFile)) {
                unlink($oldFile);
            }
        }

        $this->categoryModel->save(['id' => $categoryId, 'image' => $newName]);
    }
}
