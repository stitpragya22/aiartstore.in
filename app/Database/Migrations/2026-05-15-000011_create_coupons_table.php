<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'code'        => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'type'        => ['type' => 'ENUM', 'constraint' => ['percentage', 'fixed'], 'default' => 'percentage'],
            'value'       => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'min_amount'  => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0],
            'max_uses'    => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'used_count'  => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'starts_at'   => ['type' => 'DATETIME', 'null' => true],
            'expires_at'  => ['type' => 'DATETIME', 'null' => true],
            'status'      => ['type' => 'ENUM', 'constraint' => ['active', 'inactive', 'expired'], 'default' => 'active'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('coupons');
    }

    public function down()
    {
        $this->forge->dropTable('coupons');
    }
}
