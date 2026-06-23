<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterCustomRequestsPlan extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE custom_requests CHANGE plan plan VARCHAR(20) NOT NULL DEFAULT 'free'");
        $this->db->query("UPDATE custom_requests SET plan = '499' WHERE plan = 'paid'");
    }

    public function down()
    {
        $this->db->query("UPDATE custom_requests SET plan = 'paid' WHERE plan = '499'");
        $this->db->query("ALTER TABLE custom_requests CHANGE plan plan ENUM('free','paid') NOT NULL DEFAULT 'free'");
    }
}
