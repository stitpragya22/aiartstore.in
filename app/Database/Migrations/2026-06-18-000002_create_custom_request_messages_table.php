<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomRequestMessagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'request_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'sender'     => ['type' => 'ENUM', 'constraint' => ['customer', 'admin'], 'default' => 'customer'],
            'message'    => ['type' => 'TEXT'],
            'file'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('request_id');
        $this->forge->createTable('custom_request_messages');
    }

    public function down()
    {
        $this->forge->dropTable('custom_request_messages');
    }
}
