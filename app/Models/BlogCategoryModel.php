<?php

namespace App\Models;

use CodeIgniter\Model;

class BlogCategoryModel extends Model
{
    protected $table = 'blog_categories';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'slug', 'description', 'meta_title', 'meta_description', 'status'];
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'required|min_length[2]|max_length[100]',
    ];

    public function getActive()
    {
        return $this->where('status', 'active')->orderBy('name', 'ASC');
    }
}
