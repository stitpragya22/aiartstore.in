<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['name', 'slug', 'description', 'image', 'status'];
    protected $useTimestamps    = true;
    protected $validationRules  = [
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'required|min_length[2]|max_length[100]|is_unique[categories.slug,id,{id}]',
    ];

    public function getActive()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getWithProductCount()
    {
        return $this->select('categories.*, (SELECT COUNT(*) FROM products WHERE products.category_id = categories.id AND products.status = "active") as product_count')
            ->where('categories.status', 'active')
            ->orderBy('categories.name', 'ASC')
            ->findAll();
    }
}
