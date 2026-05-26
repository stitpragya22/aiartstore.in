<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentWebhookSupport extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'gateway_order_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'payment_method',
            ],
            'payment_verified_at' => [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'payment_status',
            ],
            'fulfillment_sent_at' => [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'payment_verified_at',
            ],
        ]);

        $this->db->query('CREATE INDEX orders_gateway_order_id_idx ON orders (gateway_order_id)');

        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'provider'           => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'razorpay'],
            'event'              => ['type' => 'VARCHAR', 'constraint' => 100],
            'gateway_order_id'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'gateway_payment_id' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'payload'            => ['type' => 'LONGTEXT', 'null' => true],
            'status'             => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'received'],
            'message'            => ['type' => 'TEXT', 'null' => true],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['gateway_order_id', 'gateway_payment_id']);
        $this->forge->createTable('payment_events');
    }

    public function down()
    {
        $this->forge->dropTable('payment_events');
        $this->db->query('DROP INDEX orders_gateway_order_id_idx ON orders');
        $this->forge->dropColumn('orders', [
            'gateway_order_id',
            'payment_verified_at',
            'fulfillment_sent_at',
        ]);
    }
}
