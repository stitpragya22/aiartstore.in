<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomRequestModel extends Model
{
    protected $table            = 'custom_requests';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['name', 'email', 'request_type', 'plan', 'description', 'reference_image', 'status', 'admin_notes', 'result_file', 'sent_at'];
    protected $validationRules  = [
        'name'        => 'required|min_length[2]|max_length[100]',
        'email'       => 'required|valid_email|max_length[100]',
        'request_type'=> 'required|max_length[50]',
        'plan'        => 'required|in_list[free,99,249,499]',
        'description' => 'required|min_length[10]',
    ];
}
