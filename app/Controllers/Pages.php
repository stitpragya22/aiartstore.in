<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function terms()
    {
        $data['title'] = 'Terms & Conditions';
        $data['meta_description'] = 'Read the terms and conditions for using AI Art Store. Understand your rights and obligations when purchasing and downloading AI-generated digital artwork.';
        return view('pages/terms', $data);
    }

    public function privacy()
    {
        $data['title'] = 'Privacy Policy';
        $data['meta_description'] = 'Learn how AI Art Store collects, uses, and protects your personal data. Our privacy policy explains data handling practices for our AI art marketplace.';
        return view('pages/privacy', $data);
    }

    public function refund()
    {
        $data['title'] = 'Refund Policy';
        $data['meta_description'] = 'Review the refund policy for AI Art Store digital purchases. Learn about our 14-day refund window and how to request a refund for AI-generated art.';
        return view('pages/refund', $data);
    }

    public function faq()
    {
        $data['title'] = 'Frequently Asked Questions';
        $data['meta_description'] = 'Find answers to common questions about AI Art Store — purchases, downloads, accounts, refunds, and more. Quick help for our AI-generated art marketplace.';
        return view('pages/faq', $data);
    }

    public function about()
    {
        $data['title'] = 'About Us - Premium AI Art Gallery';
        $data['meta_description'] = 'Learn about AI Art Store — your destination for premium AI-generated digital art. Discover our mission, our artists, and how we bring unique AI artworks to creators worldwide.';
        return view('pages/about', $data);
    }

    public function contact()
    {
        $data['title'] = 'Contact Us';
        $data['meta_description'] = 'Get in touch with AI Art Store. Contact our support team for help with orders, downloads, accounts, or any questions about our AI-generated art collection.';
        return view('pages/contact', $data);
    }
}
