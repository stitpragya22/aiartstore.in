<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'order_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'invoice_no'  => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'total'       => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'status'      => ['type' => 'ENUM', 'constraint' => ['paid', 'cancelled', 'refunded'], 'default' => 'paid'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('invoices');
    }

    public function down()
    {
        $this->forge->dropTable('invoices');
    }
}
