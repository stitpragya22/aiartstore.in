<?php

namespace App\Models;

use CodeIgniter\Model;

class PromptImageModel extends Model
{
    protected $table            = 'prompt_images';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['prompt_id', 'image'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';
}
