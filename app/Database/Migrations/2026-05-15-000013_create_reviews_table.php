<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'rating'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 5],
            'title'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'review'     => ['type' => 'TEXT', 'null' => true],
            'status'     => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'rejected'], 'default' => 'pending'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['product_id', 'status']);
        $this->forge->createTable('reviews');
    }

    public function down()
    {
        $this->forge->dropTable('reviews');
    }
}
