<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'order_number'  => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'subtotal'      => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'tax'           => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'discount'      => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'total'         => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'payment_method' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'payment_id'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'payment_status' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'pending'],
            'status'        => ['type' => 'ENUM', 'constraint' => ['pending', 'processing', 'completed', 'cancelled', 'refunded'], 'default' => 'pending'],
            'invoice_no'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'notes'         => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('orders');
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}
