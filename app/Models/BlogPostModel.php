<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogPostModel extends Model
{
    protected $table = 'blog_posts';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'category_id', 'author_id', 'title', 'slug', 'excerpt', 'content',
        'featured_image', 'tags', 'focus_keyword', 'seo_score',
        'meta_title', 'meta_description', 'status', 'published_at'
    ];
    protected $validationRules = [
        'title'       => 'required|min_length[3]|max_length[255]',
        'slug'        => 'required|min_length[3]|max_length[255]',
        'category_id' => 'permit_empty|is_natural_no_zero',
        'status'      => 'permit_empty|in_list[draft,published,archived]',
    ];

    public function getPublished()
    {
        return $this->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->where('blog_posts.status', 'published')
            ->where('blog_posts.published_at <=', date('Y-m-d H:i:s'))
            ->orderBy('blog_posts.published_at', 'DESC');
    }

    public function getBySlug($slug)
    {
        return $this->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->where('blog_posts.slug', $slug)
            ->where('blog_posts.status', 'published')
            ->first();
    }

    public function getRecent($limit = 5)
    {
        return $this->select('blog_posts.*, blog_categories.name as category_name')
            ->join('blog_categories', 'blog_categories.id = blog_posts.category_id', 'left')
            ->where('blog_posts.status', 'published')
            ->where('blog_posts.published_at <=', date('Y-m-d H:i:s'))
            ->orderBy('blog_posts.published_at', 'DESC')
            ->findAll($limit);
    }
}
