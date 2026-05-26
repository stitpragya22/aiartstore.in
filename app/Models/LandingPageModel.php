<?php

namespace App\Models;

use CodeIgniter\Model;

class LandingPageModel extends Model
{
    protected $table            = 'landing_pages';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'id', 'slug', 'title', 'meta_description', 'keywords', 'subheadline', 'headline',
        'hero_image_backgroun', 'video_link_youtube', 'old_price_of_seminar', 'new_price_of_seminar',
        'redirection_link', 'language', 'lastdate', 'date', 'time', 'reserv_seat_messsage',
        'timer_time_in_minutes', 'feature_image_1', 'feature_image_2', 'feature_image_3',
        'feature_image_4', 'feature_image_5', 'feature_image_6', 'intro_image', 'intro_title',
        'intro_content', 'intro_video_link', '_intro_join_button_text', 'workshop_section_title',
        'workshop_image_1', 'workshop_title_1', 'workshop_details_1', 'workshop_image_2',
        'workshop_title_2', 'workshop_details_2', 'workshop_image_3', 'workshop_title_3',
        'workshop_details_3', 'workshop_image_4', 'workshop_title_4', 'workshop_details_4',
        'workshop_image_5', 'workshop_title_5', 'workshop_details_5', 'workshop_image_6',
        'workshop_title_6', 'workshop_details_6', 'testimonial_section_title', 'testimonial_image_1',
        'testimonial_image_2', 'testimonial_image_3', 'testimonial_video_link_1',
        'testimonial_video_link_2', 'testimonial_video_link_3', 'footer_section_title',
        'footer_section_subtitle', 'footer_link_title_1', 'footer_link_1', 'footer_link_title_2',
        'footer_link_2', 'footer_link_title_3', 'footer_link_3', 'footer_link_title_4',
        'footer_link_4', 'price', 'status', 'custom_js',
    ];
    protected $useTimestamps    = true;
    protected $validationRules  = [
        'title' => 'required|min_length[2]|max_length[255]',
        'slug'  => 'required|min_length[2]|max_length[255]',
    ];

    public function getActive()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getBySlug($slug)
    {
        return $this->where('slug', $slug)->where('status', 'active')->first();
    }
}
