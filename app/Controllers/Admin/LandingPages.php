<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LandingPageModel;

class LandingPages extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new LandingPageModel();
    }

    public function index()
    {
        $data['landingPages'] = $this->model->orderBy('id', 'DESC')->findAll();
        $data['title'] = 'Landing Pages';
        return view('admin/landing_pages/index', $data);
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $slug = url_title($this->request->getPost('title'), '-', true);
            $existing = $this->model->where('slug', $slug)->first();
            if ($existing) {
                $slug .= '-' . uniqid();
            }

            $data = $this->gatherData($slug);

            $uploadPath = FCPATH . 'uploads/landing_pages';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $imageFields = [
                'hero_image_backgroun', 'feature_image_1', 'feature_image_2', 'feature_image_3',
                'feature_image_4', 'feature_image_5', 'feature_image_6', 'intro_image',
                'workshop_image_1', 'workshop_image_2', 'workshop_image_3', 'workshop_image_4',
                'workshop_image_5', 'workshop_image_6', 'testimonial_image_1', 'testimonial_image_2',
                'testimonial_image_3',
            ];

            foreach ($imageFields as $field) {
                $file = $this->request->getFile($field);
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                        return redirect()->back()->withInput()->with('error', "$field must be JPG, PNG or WebP.");
                    }
                    $newName = $slug . '_' . $field . '_' . $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $data[$field] = $newName;
                } elseif ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                    return redirect()->back()->withInput()->with('error', "$field upload failed: " . $file->getErrorString());
                }
            }

            if ($this->model->save($data) === false) {
                return redirect()->back()->withInput()->with('errors', $this->model->errors() ?: ['Save failed']);
            }

            return redirect()->to('/admin/landing-pages')->with('message', 'Landing page created');
        }

        $data['title'] = 'Create Landing Page';
        return view('admin/landing_pages/form', $data);
    }

    public function edit($id = null)
    {
        $page = $this->model->find($id);
        if (!$page) {
            return redirect()->to('/admin/landing-pages')->with('error', 'Landing page not found');
        }

        if ($this->request->is('post')) {
            $slug = url_title($this->request->getPost('title'), '-', true);
            $existing = $this->model->where('slug', $slug)->where('id !=', $id)->first();
            if ($existing) {
                $slug .= '-' . uniqid();
            }

            $data = $this->gatherData($slug);

            $uploadPath = FCPATH . 'uploads/landing_pages';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $imageFields = [
                'hero_image_backgroun', 'feature_image_1', 'feature_image_2', 'feature_image_3',
                'feature_image_4', 'feature_image_5', 'feature_image_6', 'intro_image',
                'workshop_image_1', 'workshop_image_2', 'workshop_image_3', 'workshop_image_4',
                'workshop_image_5', 'workshop_image_6', 'testimonial_image_1', 'testimonial_image_2',
                'testimonial_image_3',
            ];

            foreach ($imageFields as $field) {
                $file = $this->request->getFile($field);
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                        return redirect()->back()->withInput()->with('error', "$field must be JPG, PNG or WebP.");
                    }
                    $oldFile = basename($page[$field] ?? '');
                    if ($oldFile && file_exists($uploadPath . '/' . $oldFile)) {
                        unlink($uploadPath . '/' . $oldFile);
                    }
                    $newName = $slug . '_' . $field . '_' . $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $data[$field] = $newName;
                } elseif ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                    return redirect()->back()->withInput()->with('error', "$field upload failed: " . $file->getErrorString());
                }
            }

            if ($this->model->update($id, $data) === false) {
                return redirect()->back()->withInput()->with('errors', $this->model->errors() ?: ['Update failed']);
            }

            return redirect()->to('/admin/landing-pages')->with('message', 'Landing page updated');
        }

        $data['page'] = $page;
        $data['title'] = 'Edit Landing Page';
        return view('admin/landing_pages/form', $data);
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/landing-pages')->with('error', 'Invalid request');
        }

        $page = $this->model->find($id);
        if ($page) {
            $uploadPath = FCPATH . 'uploads/landing_pages';
            $imageFields = [
                'hero_image_backgroun', 'feature_image_1', 'feature_image_2', 'feature_image_3',
                'feature_image_4', 'feature_image_5', 'feature_image_6', 'intro_image',
                'workshop_image_1', 'workshop_image_2', 'workshop_image_3', 'workshop_image_4',
                'workshop_image_5', 'workshop_image_6', 'testimonial_image_1', 'testimonial_image_2',
                'testimonial_image_3',
            ];
            foreach ($imageFields as $field) {
                $f = basename($page[$field] ?? '');
                if ($f && file_exists($uploadPath . '/' . $f)) {
                    unlink($uploadPath . '/' . $f);
                }
            }
            $this->model->delete($id);
        }

        return redirect()->to('/admin/landing-pages')->with('message', 'Landing page deleted');
    }

    private function gatherData($slug)
    {
        return [
            'slug'                    => $slug,
            'title'                   => $this->request->getPost('title'),
            'meta_description'        => $this->request->getPost('meta_description'),
            'keywords'                => $this->request->getPost('keywords'),
            'headline'                => $this->request->getPost('headline'),
            'subheadline'             => $this->request->getPost('subheadline'),
            'video_link_youtube'      => $this->request->getPost('video_link_youtube'),
            'old_price_of_seminar'    => $this->request->getPost('old_price_of_seminar'),
            'new_price_of_seminar'    => $this->request->getPost('new_price_of_seminar'),
            'price'                   => $this->request->getPost('price') ?: 0,
            'redirection_link'        => $this->request->getPost('redirection_link'),
            'language'                => $this->request->getPost('language') ?? 'en',
            'lastdate'                => $this->request->getPost('lastdate'),
            'date'                    => $this->request->getPost('date'),
            'time'                    => $this->request->getPost('time'),
            'reserv_seat_messsage'    => $this->request->getPost('reserv_seat_messsage'),
            'timer_time_in_minutes'   => $this->request->getPost('timer_time_in_minutes') ?: null,
            'intro_title'             => $this->request->getPost('intro_title'),
            'intro_content'           => $this->request->getPost('intro_content'),
            'intro_video_link'        => $this->request->getPost('intro_video_link'),
            '_intro_join_button_text' => $this->request->getPost('_intro_join_button_text'),
            'workshop_section_title'  => $this->request->getPost('workshop_section_title'),
            'workshop_title_1'        => $this->request->getPost('workshop_title_1'),
            'workshop_details_1'      => $this->request->getPost('workshop_details_1'),
            'workshop_title_2'        => $this->request->getPost('workshop_title_2'),
            'workshop_details_2'      => $this->request->getPost('workshop_details_2'),
            'workshop_title_3'        => $this->request->getPost('workshop_title_3'),
            'workshop_details_3'      => $this->request->getPost('workshop_details_3'),
            'workshop_title_4'        => $this->request->getPost('workshop_title_4'),
            'workshop_details_4'      => $this->request->getPost('workshop_details_4'),
            'workshop_title_5'        => $this->request->getPost('workshop_title_5'),
            'workshop_details_5'      => $this->request->getPost('workshop_details_5'),
            'workshop_title_6'        => $this->request->getPost('workshop_title_6'),
            'workshop_details_6'      => $this->request->getPost('workshop_details_6'),
            'testimonial_section_title'   => $this->request->getPost('testimonial_section_title'),
            'testimonial_video_link_1'    => $this->request->getPost('testimonial_video_link_1'),
            'testimonial_video_link_2'    => $this->request->getPost('testimonial_video_link_2'),
            'testimonial_video_link_3'    => $this->request->getPost('testimonial_video_link_3'),
            'footer_section_title'        => $this->request->getPost('footer_section_title'),
            'footer_section_subtitle'     => $this->request->getPost('footer_section_subtitle'),
            'footer_link_title_1'         => $this->request->getPost('footer_link_title_1'),
            'footer_link_1'               => $this->request->getPost('footer_link_1'),
            'footer_link_title_2'         => $this->request->getPost('footer_link_title_2'),
            'footer_link_2'               => $this->request->getPost('footer_link_2'),
            'footer_link_title_3'         => $this->request->getPost('footer_link_title_3'),
            'footer_link_3'               => $this->request->getPost('footer_link_3'),
            'footer_link_title_4'         => $this->request->getPost('footer_link_title_4'),
            'footer_link_4'               => $this->request->getPost('footer_link_4'),
            'custom_js'                   => $this->request->getPost('custom_js'),
            'status'                      => $this->request->getPost('status') ?? 'active',
        ];
    }
}
