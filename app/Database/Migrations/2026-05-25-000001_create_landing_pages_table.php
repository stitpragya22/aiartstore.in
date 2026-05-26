<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLandingPagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'slug'                    => ['type' => 'VARCHAR', 'constraint' => 255, 'unique' => true],
            'title'                   => ['type' => 'VARCHAR', 'constraint' => 255],
            'meta_description'        => ['type' => 'TEXT', 'null' => true],
            'keywords'                => ['type' => 'TEXT', 'null' => true],
            'headline'                => ['type' => 'TEXT', 'null' => true],
            'subheadline'             => ['type' => 'TEXT', 'null' => true],
            'hero_image_backgroun'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'video_link_youtube'      => ['type' => 'TEXT', 'null' => true],
            'old_price_of_seminar'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'new_price_of_seminar'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'price'                   => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00],
            'redirection_link'        => ['type' => 'TEXT', 'null' => true],
            'language'                => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'en'],
            'lastdate'                => ['type' => 'DATE', 'null' => true],
            'date'                    => ['type' => 'DATE', 'null' => true],
            'time'                    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'reserv_seat_messsage'    => ['type' => 'TEXT', 'null' => true],
            'timer_time_in_minutes'   => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'feature_image_1'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'feature_image_2'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'feature_image_3'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'feature_image_4'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'feature_image_5'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'feature_image_6'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'intro_image'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'intro_title'             => ['type' => 'TEXT', 'null' => true],
            'intro_content'           => ['type' => 'TEXT', 'null' => true],
            'intro_video_link'        => ['type' => 'TEXT', 'null' => true],
            '_intro_join_button_text' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'workshop_section_title'  => ['type' => 'TEXT', 'null' => true],
            'workshop_image_1'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'workshop_title_1'        => ['type' => 'TEXT', 'null' => true],
            'workshop_details_1'      => ['type' => 'TEXT', 'null' => true],
            'workshop_image_2'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'workshop_title_2'        => ['type' => 'TEXT', 'null' => true],
            'workshop_details_2'      => ['type' => 'TEXT', 'null' => true],
            'workshop_image_3'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'workshop_title_3'        => ['type' => 'TEXT', 'null' => true],
            'workshop_details_3'      => ['type' => 'TEXT', 'null' => true],
            'workshop_image_4'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'workshop_title_4'        => ['type' => 'TEXT', 'null' => true],
            'workshop_details_4'      => ['type' => 'TEXT', 'null' => true],
            'workshop_image_5'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'workshop_title_5'        => ['type' => 'TEXT', 'null' => true],
            'workshop_details_5'      => ['type' => 'TEXT', 'null' => true],
            'workshop_image_6'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'workshop_title_6'        => ['type' => 'TEXT', 'null' => true],
            'workshop_details_6'      => ['type' => 'TEXT', 'null' => true],
            'testimonial_section_title'   => ['type' => 'TEXT', 'null' => true],
            'testimonial_image_1'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'testimonial_image_2'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'testimonial_image_3'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'testimonial_video_link_1'    => ['type' => 'TEXT', 'null' => true],
            'testimonial_video_link_2'    => ['type' => 'TEXT', 'null' => true],
            'testimonial_video_link_3'    => ['type' => 'TEXT', 'null' => true],
            'footer_section_title'        => ['type' => 'TEXT', 'null' => true],
            'footer_section_subtitle'     => ['type' => 'TEXT', 'null' => true],
            'footer_link_title_1'         => ['type' => 'TEXT', 'null' => true],
            'footer_link_1'               => ['type' => 'TEXT', 'null' => true],
            'footer_link_title_2'         => ['type' => 'TEXT', 'null' => true],
            'footer_link_2'               => ['type' => 'TEXT', 'null' => true],
            'footer_link_title_3'         => ['type' => 'TEXT', 'null' => true],
            'footer_link_3'               => ['type' => 'TEXT', 'null' => true],
            'footer_link_title_4'         => ['type' => 'TEXT', 'null' => true],
            'footer_link_4'               => ['type' => 'TEXT', 'null' => true],
            'custom_js'                   => ['type' => 'TEXT', 'null' => true],
            'status'                      => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at'                  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'                  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('landing_pages');
    }

    public function down()
    {
        $this->forge->dropTable('landing_pages');
    }
}
