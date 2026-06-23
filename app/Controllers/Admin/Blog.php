<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BlogCategoryModel;
use App\Models\BlogPostModel;

class Blog extends BaseController
{
    private $catModel;
    private $postModel;

    public function __construct()
    {
        $this->catModel = new BlogCategoryModel();
        $this->postModel = new BlogPostModel();
    }

    // ========== CATEGORIES ==========

    public function categories()
    {
        $categories = $this->catModel->orderBy('id', 'DESC')->findAll();
        $postCounts = [];
        foreach ($categories as $cat) {
            $postCounts[$cat['id']] = $this->postModel->where('category_id', $cat['id'])->countAllResults(false);
        }
        $data['categories'] = $categories;
        $data['postCounts'] = $postCounts;
        $data['title'] = 'Blog Categories';
        return view('admin/blog/categories_index', $data);
    }

    public function categoryCreate()
    {
        if ($this->request->is('post')) {
            $slug = url_title($this->request->getPost('name'), '-', true);
            $existing = $this->catModel->where('slug', $slug)->first();
            if ($existing) {
                $slug .= '-' . uniqid();
            }
            if (!$this->catModel->save([
                'name'             => $this->request->getPost('name'),
                'slug'             => $slug,
                'description'      => $this->request->getPost('description'),
                'meta_title'       => $this->request->getPost('meta_title'),
                'meta_description' => $this->request->getPost('meta_description'),
                'status'           => $this->request->getPost('status') ?? 'active',
            ])) {
                return redirect()->back()->with('errors', $this->catModel->errors())->withInput();
            }
            return redirect()->to('/admin/blog/categories')->with('message', 'Category created');
        }
        $data['title'] = 'Add Blog Category';
        return view('admin/blog/category_form', $data);
    }

    public function categoryEdit($id)
    {
        $cat = $this->catModel->find($id);
        if (!$cat) return redirect()->to('/admin/blog/categories')->with('error', 'Not found');

        if ($this->request->is('post')) {
            $slug = url_title($this->request->getPost('name'), '-', true);
            $existing = $this->catModel->where('slug', $slug)->where('id !=', $id)->first();
            if ($existing) {
                $slug .= '-' . uniqid();
            }
            if (!$this->catModel->update($id, [
                'name'             => $this->request->getPost('name'),
                'slug'             => $slug,
                'description'      => $this->request->getPost('description'),
                'meta_title'       => $this->request->getPost('meta_title'),
                'meta_description' => $this->request->getPost('meta_description'),
                'status'           => $this->request->getPost('status') ?? 'active',
            ])) {
                return redirect()->back()->with('errors', $this->catModel->errors())->withInput();
            }
            return redirect()->to('/admin/blog/categories')->with('message', 'Category updated');
        }
        $data['category'] = $cat;
        $data['title'] = 'Edit Blog Category';
        return view('admin/blog/category_form', $data);
    }

    public function categoryDelete($id)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/blog/categories')->with('error', 'Invalid request');
        }

        $this->catModel->delete($id);
        return redirect()->to('/admin/blog/categories')->with('message', 'Category deleted');
    }

    // ========== POSTS ==========

    public function posts()
    {
        $data['posts'] = $this->postModel->select('blog_posts.*, blog_categories.name as category_name')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->orderBy('blog_posts.id', 'DESC')
            ->findAll();
        $data['title'] = 'Blog Posts';
        return view('admin/blog/posts_index', $data);
    }

    public function postCreate()
    {
        if ($this->request->is('post')) {
            $slug = url_title($this->request->getPost('title'), '-', true);
            $existing = $this->postModel->where('slug', $slug)->first();
            if ($existing) $slug .= '-' . uniqid();

            $status = $this->request->getPost('status') ?? 'draft';
            $data = [
                'category_id'      => $this->request->getPost('category_id'),
                'author_id'        => auth()->user()->id,
                'title'            => $this->request->getPost('title'),
                'slug'             => $slug,
                'excerpt'          => $this->request->getPost('excerpt'),
                'content'          => $this->request->getPost('content'),
                'tags'             => $this->request->getPost('tags'),
                'focus_keyword'    => $this->request->getPost('focus_keyword'),
                'seo_score'        => (int)($this->request->getPost('seo_score') ?? 0),
                'meta_title'       => $this->request->getPost('meta_title'),
                'meta_description' => $this->request->getPost('meta_description'),
                'status'           => $status,
                'published_at'     => $status === 'published' ? ($this->request->getPost('published_at') ?: date('Y-m-d H:i:s')) : null,
            ];

            $image = $this->request->getFile('featured_image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                if (in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                    $uploadPath = FCPATH . 'uploads/blog';
                    if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
                    $newName = $slug . '_' . $image->getRandomName();
                    $image->move($uploadPath, $newName);
                    $data['featured_image'] = 'uploads/blog/' . $newName;
                }
            }

            if (!$this->postModel->save($data)) {
                return redirect()->back()->with('errors', $this->postModel->errors())->withInput();
            }
            return redirect()->to('/admin/blog/posts')->with('message', 'Post saved');
        }

        $data['categories'] = $this->catModel->where('status', 'active')->findAll();
        $data['title'] = 'Write Blog Post';
        return view('admin/blog/post_form', $data);
    }

    public function postEdit($id)
    {
        $post = $this->postModel->find($id);
        if (!$post) return redirect()->to('/admin/blog/posts')->with('error', 'Not found');

        if ($this->request->is('post')) {
            $slug = url_title($this->request->getPost('title'), '-', true);
            $existing = $this->postModel->where('slug', $slug)->where('id !=', $id)->first();
            if ($existing) $slug .= '-' . uniqid();

            $status = $this->request->getPost('status') ?? 'draft';
            $data = [
                'category_id'      => $this->request->getPost('category_id'),
                'title'            => $this->request->getPost('title'),
                'slug'             => $slug,
                'excerpt'          => $this->request->getPost('excerpt'),
                'content'          => $this->request->getPost('content'),
                'tags'             => $this->request->getPost('tags'),
                'focus_keyword'    => $this->request->getPost('focus_keyword'),
                'seo_score'        => (int)($this->request->getPost('seo_score') ?? 0),
                'meta_title'       => $this->request->getPost('meta_title'),
                'meta_description' => $this->request->getPost('meta_description'),
                'status'           => $status,
                'published_at'     => $status === 'published' ? ($this->request->getPost('published_at') ?: date('Y-m-d H:i:s')) : $post['published_at'],
            ];

            $image = $this->request->getFile('featured_image');
            if ($image && $image->isValid() && !$image->hasMoved()) {
                if (in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                    if ($post['featured_image'] && file_exists(FCPATH . $post['featured_image'])) {
                        unlink(FCPATH . $post['featured_image']);
                    }
                    $uploadPath = FCPATH . 'uploads/blog';
                    if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
                    $newName = $slug . '_' . $image->getRandomName();
                    $image->move($uploadPath, $newName);
                    $data['featured_image'] = 'uploads/blog/' . $newName;
                }
            }

            if (!$this->postModel->update($id, $data)) {
                return redirect()->back()->with('errors', $this->postModel->errors())->withInput();
            }

            if ($this->request->getPost('save_and_stay')) {
                return redirect()->to('/admin/blog/posts/edit/' . $id)->with('message', 'Post updated');
            }
            return redirect()->to('/admin/blog/posts')->with('message', 'Post updated');
        }

        $data['post'] = $post;
        $data['categories'] = $this->catModel->where('status', 'active')->findAll();
        $data['title'] = 'Edit Blog Post';
        return view('admin/blog/post_form', $data);
    }

    public function postDelete($id)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/blog/posts')->with('error', 'Invalid request');
        }

        $post = $this->postModel->find($id);
        if ($post && $post['featured_image'] && file_exists(FCPATH . $post['featured_image'])) {
            unlink(FCPATH . $post['featured_image']);
        }
        $this->postModel->delete($id);
        return redirect()->to('/admin/blog/posts')->with('message', 'Post deleted');
    }
}
