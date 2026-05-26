<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function terms()
    {
        $data['title'] = 'Terms & Conditions';
        return view('pages/terms', $data);
    }

    public function privacy()
    {
        $data['title'] = 'Privacy Policy';
        return view('pages/privacy', $data);
    }

    public function refund()
    {
        $data['title'] = 'Refund Policy';
        return view('pages/refund', $data);
    }

    public function faq()
    {
        $data['title'] = 'FAQ';
        return view('pages/faq', $data);
    }

    public function contact()
    {
        $data['title'] = 'Contact Us';
        return view('pages/contact', $data);
    }
}
