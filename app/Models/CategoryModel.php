<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id', 'name', 'slug', 'description', 'image', 'is_curated', 'status', 'meta_title', 'meta_description'];
    protected $useTimestamps    = true;
    protected $validationRules  = [
        'id'   => 'permit_empty|is_natural_no_zero',
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'required|min_length[2]|max_length[100]|is_unique[categories.slug,id,{id}]',
    ];

    public function getActive()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getCurated()
    {
        return $this->select('categories.*, (SELECT COUNT(*) FROM products WHERE products.category_id = categories.id AND products.status = "active") as product_count')
            ->where('categories.status', 'active')
            ->where('categories.is_curated', 1)
            ->orderBy('categories.name', 'ASC')
            ->findAll();
    }

    public function getWithProductCount()
    {
        return $this->select('categories.*, (SELECT COUNT(*) FROM products WHERE products.category_id = categories.id AND products.status = "active") as product_count')
            ->where('categories.status', 'active')
            ->orderBy('categories.name', 'ASC')
            ->findAll();
    }
}
