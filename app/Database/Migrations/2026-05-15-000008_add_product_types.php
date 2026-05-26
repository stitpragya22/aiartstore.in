<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProductTypes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'product_type'  => ['type' => 'ENUM', 'constraint' => ['art', 'ebook', 'audio', 'bundle'], 'default' => 'art', 'after' => 'category_id'],
            'subtitle'      => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true, 'after' => 'title'],
            'highlights'    => ['type' => 'TEXT', 'null' => true, 'after' => 'description'],
            'features'      => ['type' => 'TEXT', 'null' => true, 'after' => 'highlights'],
            'details_json'  => ['type' => 'TEXT', 'null' => true, 'after' => 'features'],
            'content'       => ['type' => 'TEXT', 'null' => true, 'after' => 'details_json'],
            'preview_files' => ['type' => 'TEXT', 'null' => true, 'after' => 'content'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('products', 'product_type');
        $this->forge->dropColumn('products', 'subtitle');
        $this->forge->dropColumn('products', 'highlights');
        $this->forge->dropColumn('products', 'features');
        $this->forge->dropColumn('products', 'details_json');
        $this->forge->dropColumn('products', 'content');
        $this->forge->dropColumn('products', 'preview_files');
    }
}
