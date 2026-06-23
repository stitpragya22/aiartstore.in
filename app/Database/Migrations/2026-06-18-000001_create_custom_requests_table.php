<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomRequestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'            => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'request_type'    => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'ai_art'],
            'plan'            => ['type' => 'ENUM', 'constraint' => ['free', 'paid'], 'default' => 'free'],
            'description'     => ['type' => 'TEXT'],
            'reference_image' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'          => ['type' => 'ENUM', 'constraint' => ['pending', 'in_progress', 'completed', 'rejected'], 'default' => 'pending'],
            'admin_notes'     => ['type' => 'TEXT', 'null' => true],
            'result_file'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sent_at'         => ['type' => 'DATETIME', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('status');
        $this->forge->createTable('custom_requests');
    }

    public function down()
    {
        $this->forge->dropTable('custom_requests');
    }
}
