<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'category_id', 'title', 'slug', 'description', 'price', 'compare_price',
        'image', 'image_watermarked', 'file', 'file_size', 'dimensions', 'tags',
        'is_featured', 'is_digital', 'status'
    ];
    protected $useTimestamps    = true;
    protected $validationRules  = [
        'title'       => 'required|min_length[3]|max_length[255]',
        'slug'        => 'required|min_length[3]|max_length[255]|is_unique[products.slug,id,{id}]',
        'price'       => 'required|numeric',
        'category_id' => 'permit_empty|is_natural_no_zero',
    ];

    public function getFeatured()
    {
        return $this->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.status', 'active')
            ->where('products.is_featured', 1)
            ->orderBy('products.id', 'DESC')
            ->findAll(12);
    }

    public function getActive()
    {
        return $this->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.status', 'active')
            ->orderBy('products.id', 'DESC');
    }

    public function getBySlug($slug)
    {
        return $this->select('products.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.slug', $slug)
            ->where('products.status', 'active')
            ->first();
    }

    public function search($query)
    {
        return $this->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.status', 'active')
            ->groupStart()
                ->like('products.title', $query)
                ->orLike('products.tags', $query)
                ->orLike('products.description', $query)
            ->groupEnd()
            ->orderBy('products.id', 'DESC');
    }
}
