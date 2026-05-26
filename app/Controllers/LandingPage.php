<?php

namespace App\Controllers;

use App\Models\LandingPageModel;

class LandingPage extends BaseController
{
    public function index($slug = null)
    {
        if (!$slug) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $model = new LandingPageModel();
        $page = $model->getBySlug($slug);

        if (!$page) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data['p'] = $page;
        $data['title'] = $page['title'];
        $data['meta_description'] = $page['meta_description'] ?? '';
        $data['meta_image'] = $page['hero_image_backgroun']
            ? base_url('uploads/landing_pages/' . $page['hero_image_backgroun'])
            : '';

        return view('landing_page/index', $data);
    }
}
