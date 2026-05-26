<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCustomerEmailToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'customer_email' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'user_id'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'customer_email');
    }
}
