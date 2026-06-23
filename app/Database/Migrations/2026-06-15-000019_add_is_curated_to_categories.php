<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsCuratedToCategories extends Migration
{
    public function up()
    {
        $this->forge->addColumn('categories', [
            'is_curated' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'image',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('categories', 'is_curated');
    }
}
