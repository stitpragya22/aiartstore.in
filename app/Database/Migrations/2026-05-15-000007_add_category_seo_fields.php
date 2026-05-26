<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategorySeoFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('categories', [
            'meta_title'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'description'],
            'meta_description' => ['type' => 'TEXT', 'null' => true, 'after' => 'meta_title'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('categories', 'meta_title');
        $this->forge->dropColumn('categories', 'meta_description');
    }
}
