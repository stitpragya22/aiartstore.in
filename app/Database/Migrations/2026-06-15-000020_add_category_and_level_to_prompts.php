<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryAndLevelToPrompts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('prompts', [
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'id',
            ],
            'min_subscription_level' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'status',
            ],
        ]);
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('prompts', 'prompts_category_id_foreign');
        $this->forge->dropColumn('prompts', 'category_id');
        $this->forge->dropColumn('prompts', 'min_subscription_level');
    }
}
