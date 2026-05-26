<?php

namespace App\Controllers;

use App\Models\BlogPostModel;
use App\Models\BlogCategoryModel;

class Blog extends BaseController
{
    public function index()
    {
        $model = new BlogPostModel();
        $catModel = new BlogCategoryModel();

        $category = $this->request->getGet('category');

        if ($category) {
            $cat = $catModel->where('slug', $category)->where('status', 'active')->first();
            if (!$cat) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $posts = $model->getPublished()->where('blog_posts.category_id', $cat['id'])->findAll();
            $data['current_category'] = $cat;
        } else {
            $posts = $model->getPublished()->findAll();
            $data['current_category'] = null;
        }

        $data['posts'] = $posts;
        $data['categories'] = $catModel->getActive()->findAll();
        $data['title'] = 'Blog';
        $data['meta_description'] = 'Read latest articles about AI art, digital creativity, and more.';

        return view('blog/index', $data);
    }

    public function detail($slug)
    {
        $model = new BlogPostModel();
        $post = $model->getBySlug($slug);

        if (!$post) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['post'] = $post;
        $data['recent'] = $model->getRecent(4);
        $data['title'] = $post['meta_title'] ?: $post['title'];
        $data['meta_description'] = $post['meta_description'] ?: mb_substr(strip_tags($post['excerpt'] ?: $post['content']), 0, 160);
        $data['meta_image'] = $post['featured_image'] ?? '';

        return view('blog/detail', $data);
    }
}
