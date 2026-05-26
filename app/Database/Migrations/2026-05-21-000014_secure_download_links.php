<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SecureDownloadLinks extends Migration
{
    public function up()
    {
        $this->forge->addColumn('downloads', [
            'download_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'after'      => 'order_id',
            ],
            'max_downloads' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 10,
                'after'      => 'download_count',
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'last_download_at',
            ],
            'revoked_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'expires_at',
            ],
        ]);

        $this->db->query('CREATE UNIQUE INDEX downloads_download_token_unique ON downloads (download_token)');
        $this->db->query('CREATE INDEX downloads_user_product_order_idx ON downloads (user_id, product_id, order_id)');
    }

    public function down()
    {
        $this->db->query('DROP INDEX downloads_user_product_order_idx ON downloads');
        $this->db->query('DROP INDEX downloads_download_token_unique ON downloads');

        $this->forge->dropColumn('downloads', [
            'download_token',
            'max_downloads',
            'expires_at',
            'revoked_at',
        ]);
    }
}
