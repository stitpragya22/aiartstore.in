<?php

namespace App\Models;

use CodeIgniter\Model;

class PromptModel extends Model
{
    protected $table            = 'prompts';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['title', 'slug', 'prompt', 'notes', 'status', 'category_id', 'min_subscription_level', 'seo_title', 'seo_description', 'seo_keywords', 'seo_thumbnail'];
    protected $useTimestamps    = true;
    protected $validationRules  = [
        'title' => 'required|min_length[2]|max_length[255]',
    ];

    public function getWithImages()
    {
        return $this->select('prompts.*, (SELECT COUNT(*) FROM prompt_images WHERE prompt_images.prompt_id = prompts.id) as image_count')
            ->orderBy('prompts.id', 'DESC')
            ->findAll();
    }
}
