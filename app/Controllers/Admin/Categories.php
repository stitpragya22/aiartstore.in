<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Categories extends BaseController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
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
            $this->categoryModel->save([
                'name'        => $this->request->getPost('name'),
                'slug'        => $slug,
                'description' => $this->request->getPost('description'),
                'status'      => $this->request->getPost('status') ?? 'active',
            ]);
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
            $this->categoryModel->update($id, [
                'name'        => $this->request->getPost('name'),
                'slug'        => url_title($this->request->getPost('name'), '-', true),
                'description' => $this->request->getPost('description'),
                'status'      => $this->request->getPost('status') ?? 'active',
            ]);
            return redirect()->to('/admin/categories')->with('message', 'Category updated successfully');
        }

        $data['category'] = $category;
        $data['title'] = 'Edit Category';
        return view('admin/categories/form', $data);
    }

    public function delete($id = null)
    {
        $this->categoryModel->delete($id);
        return redirect()->to('/admin/categories')->with('message', 'Category deleted successfully');
    }
}
