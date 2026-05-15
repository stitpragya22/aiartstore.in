<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'category_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'title'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'            => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'description'     => ['type' => 'TEXT', 'null' => true],
            'price'           => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'compare_price'   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'image'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'image_watermarked' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'file_size'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'dimensions'      => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'tags'            => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'is_featured'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_digital'      => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'status'          => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('products');
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}
