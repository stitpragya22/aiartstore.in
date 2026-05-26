<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBlogTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 100, 'unique' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'meta_title'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'meta_description' => ['type' => 'TEXT', 'null' => true],
            'status'      => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('blog_categories');

        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'category_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'author_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'title'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'slug'            => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'excerpt'         => ['type' => 'TEXT', 'null' => true],
            'content'         => ['type' => 'LONGTEXT', 'null' => true],
            'featured_image'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'tags'            => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'focus_keyword'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'seo_score'       => ['type' => 'INT', 'constraint' => 3, 'default' => 0],
            'meta_title'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'meta_description' => ['type' => 'TEXT', 'null' => true],
            'status'          => ['type' => 'ENUM', 'constraint' => ['draft', 'published', 'archived'], 'default' => 'draft'],
            'published_at'    => ['type' => 'DATETIME', 'null' => true],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('category_id', 'blog_categories', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('author_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('blog_posts');
    }

    public function down()
    {
        $this->forge->dropTable('blog_posts');
        $this->forge->dropTable('blog_categories');
    }
}
