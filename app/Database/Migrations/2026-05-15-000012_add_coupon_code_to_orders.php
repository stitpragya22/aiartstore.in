<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCouponCodeToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'coupon_code' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'customer_email'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'coupon_code');
    }
}
