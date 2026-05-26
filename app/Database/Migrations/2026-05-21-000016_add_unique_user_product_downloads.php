<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUniqueUserProductDownloads extends Migration
{
    public function up()
    {
        $this->db->query('CREATE UNIQUE INDEX downloads_user_product_unique ON downloads (user_id, product_id)');
    }

    public function down()
    {
        $this->db->query('DROP INDEX downloads_user_product_unique ON downloads');
    }
}
