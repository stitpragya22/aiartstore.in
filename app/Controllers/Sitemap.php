<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\BlogPostModel;

class Sitemap extends BaseController
{
    private function ex($str)
    {
        return htmlspecialchars((string)$str, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    public function index()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        $products = $productModel->select('products.slug, products.updated_at')
            ->where('products.status', 'active')
            ->orderBy('products.id', 'DESC')
            ->findAll();
        $categories = $categoryModel->where('status', 'active')->findAll();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $blogPosts = model(BlogPostModel::class)
            ->select('slug, updated_at, published_at')
            ->where('status', 'published')
            ->where('published_at <=', date('Y-m-d H:i:s'))
            ->findAll();

        $pages = [
            ['loc' => site_url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => site_url('/shop'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => site_url('/blog'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => site_url('/faq'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => site_url('/terms'), 'priority' => '0.3', 'changefreq' => 'monthly'],
            ['loc' => site_url('/privacy'), 'priority' => '0.3', 'changefreq' => 'monthly'],
            ['loc' => site_url('/refund'), 'priority' => '0.3', 'changefreq' => 'monthly'],
            ['loc' => site_url('/contact'), 'priority' => '0.5', 'changefreq' => 'monthly'],
        ];

        foreach ($pages as $page) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . $this->ex($page['loc']) . '</loc>' . "\n";
            $xml .= '    <priority>' . $page['priority'] . '</priority>' . "\n";
            $xml .= '    <changefreq>' . $page['changefreq'] . '</changefreq>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        foreach ($categories as $cat) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . $this->ex(site_url('/shop/category/' . $cat['slug'])) . '</loc>' . "\n";
            $xml .= '    <priority>0.7</priority>' . "\n";
            $xml .= '    <changefreq>weekly</changefreq>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        foreach ($products as $p) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . $this->ex(site_url('/shop/' . $p['slug'])) . '</loc>' . "\n";
            $xml .= '    <priority>0.8</priority>' . "\n";
            $xml .= '    <changefreq>monthly</changefreq>' . "\n";
            $xml .= '    <lastmod>' . date('c', strtotime($p['updated_at'])) . '</lastmod>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        foreach ($blogPosts as $bp) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . $this->ex(site_url('/blog/' . $bp['slug'])) . '</loc>' . "\n";
            $xml .= '    <priority>0.7</priority>' . "\n";
            $xml .= '    <changefreq>monthly</changefreq>' . "\n";
            $xml .= '    <lastmod>' . date('c', strtotime($bp['updated_at'] ?? $bp['published_at'])) . '</lastmod>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return $this->response->setContentType('application/xml')->setBody($xml);
    }

    public function feed()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        $currency = strtoupper(config('Razorpay')->currency);

        $products = $productModel->select('products.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.status', 'active')
            ->orderBy('products.id', 'DESC')
            ->findAll();
        $categories = $categoryModel->where('status', 'active')->findAll();

        $catMap = [];
        foreach ($categories as $c) {
            $catMap[$c['id']] = $c;
        }

        $googleCatMap = [
            'abstract' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
            'fantasy' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
            'landscape' => 'Media > Photography > Stock Photographs',
            'portrait' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
            'sci-fi' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
            'minimalist' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
            'nature' => 'Media > Photography > Stock Photographs',
            'anime' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . "\n";
        $xml .= '  <channel>' . "\n";
        $xml .= '    <title>AI Art Store Products</title>' . "\n";
        $xml .= '    <link>' . $this->ex(site_url('/shop')) . '</link>' . "\n";
        $xml .= '    <description>AI-generated digital art collection</description>' . "\n";

        foreach ($products as $p) {
            $catSlug = $catMap[$p['category_id']]['slug'] ?? '';
            $googleCat = $googleCatMap[$catSlug] ?? 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts';
            $imageUrl = $p['image']
                ? base_url('uploads/products/' . $p['image'])
                : ($p['image_watermarked'] ? base_url('uploads/products/' . $p['image_watermarked']) : '');

            $watermarkedUrl = $p['image_watermarked']
                ? base_url('uploads/products/' . $p['image_watermarked'])
                : '';

            $productUrl = site_url('/shop/' . $p['slug']);
            $pId = (int) $p['id'];

            $xml .= '    <item>' . "\n";
            $xml .= '      <g:id>' . $this->ex($pId) . '</g:id>' . "\n";
            $xml .= '      <g:mpn>' . $this->ex('ART-' . $pId) . '</g:mpn>' . "\n";
            $xml .= '      <g:identifier_exists>FALSE</g:identifier_exists>' . "\n";
            $xml .= '      <g:title>' . $this->ex($p['title']) . '</g:title>' . "\n";
            $xml .= '      <g:description>' . $this->ex(substr(strip_tags($p['description'] ?? ''), 0, 5000)) . '</g:description>' . "\n";
            $xml .= '      <g:link>' . $this->ex($productUrl) . '</g:link>' . "\n";
            $xml .= '      <g:image_link>' . $this->ex($imageUrl) . '</g:image_link>' . "\n";
            if ($watermarkedUrl) {
                $xml .= '      <g:additional_image_link>' . $this->ex($watermarkedUrl) . '</g:additional_image_link>' . "\n";
            }
            $xml .= '      <g:availability>in_stock</g:availability>' . "\n";
            $xml .= '      <g:price>' . number_format((float)$p['price'], 2, '.', '') . ' ' . $currency . '</g:price>' . "\n";
            $xml .= '      <g:sale_price>' . number_format((float)$p['price'], 2, '.', '') . ' ' . $currency . '</g:sale_price>' . "\n";
            $xml .= '      <g:brand>AI Art Store</g:brand>' . "\n";
            $xml .= '      <g:condition>new</g:condition>' . "\n";
            $xml .= '      <g:google_product_category>' . $this->ex($googleCat) . '</g:google_product_category>' . "\n";
            $xml .= '      <g:product_type>' . $this->ex($catMap[$p['category_id']]['name'] ?? '') . '</g:product_type>' . "\n";
            $xml .= '      <g:is_bundle>no</g:is_bundle>' . "\n";
            $xml .= '      <g:multipack>0</g:multipack>' . "\n";
            $xml .= '      <g:shipping>' . "\n";
            $xml .= '        <g:country>IN</g:country>' . "\n";
            $xml .= '        <g:service>Digital Delivery</g:service>' . "\n";
            $xml .= '        <g:price>0.00 INR</g:price>' . "\n";
            $xml .= '      </g:shipping>' . "\n";
            if (!empty($p['tags'])) {
                $xml .= '      <g:custom_label_0>' . $this->ex($p['tags']) . '</g:custom_label_0>' . "\n";
            }
            if (!empty($p['file_size'])) {
                $xml .= '      <g:custom_label_1>' . $this->ex($p['file_size']) . '</g:custom_label_1>' . "\n";
            }
            $xml .= '    </item>' . "\n";
        }

        $xml .= '  </channel>' . "\n";
        $xml .= '</rss>' . "\n";

        return $this->response->setContentType('application/xml')->setBody($xml);
    }

    public function feedCsv()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        $currency = strtoupper(config('Razorpay')->currency);

        $products = $productModel->select('products.*, categories.name as category_name, categories.slug as category_slug')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.status', 'active')
            ->orderBy('products.id', 'DESC')
            ->findAll();
        $categories = $categoryModel->where('status', 'active')->findAll();

        $catMap = [];
        foreach ($categories as $c) {
            $catMap[$c['id']] = $c;
        }

        $googleCatMap = [
            'abstract' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
            'fantasy' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
            'landscape' => 'Media > Photography > Stock Photographs',
            'portrait' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
            'sci-fi' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
            'minimalist' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
            'nature' => 'Media > Photography > Stock Photographs',
            'anime' => 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts',
        ];

        $out = fopen('php://temp', 'w+');

        fputcsv($out, [
            'id', 'title', 'description', 'link', 'image_link',
            'additional_image_link', 'availability', 'price', 'sale_price',
            'brand', 'condition', 'google_product_category', 'product_type',
            'mpn', 'identifier_exists', 'shipping', 'custom_label_0'
        ]);

        foreach ($products as $p) {
            $catSlug = $catMap[$p['category_id']]['slug'] ?? '';
            $googleCat = $googleCatMap[$catSlug] ?? 'Arts & Entertainment > Hobbies & Creative Arts > Arts & Crafts';
            $imageUrl = $p['image']
                ? base_url('uploads/products/' . $p['image'])
                : ($p['image_watermarked'] ? base_url('uploads/products/' . $p['image_watermarked']) : '');
            $watermarkedUrl = $p['image_watermarked']
                ? base_url('uploads/products/' . $p['image_watermarked'])
                : '';
            $pId = (int) $p['id'];

            fputcsv($out, [
                $pId,
                $p['title'],
                substr(strip_tags($p['description'] ?? ''), 0, 5000),
                site_url('/shop/' . $p['slug']),
                $imageUrl,
                $watermarkedUrl,
                'in_stock',
                number_format((float)$p['price'], 2, '.', '') . ' ' . $currency,
                number_format((float)$p['price'], 2, '.', '') . ' ' . $currency,
                'AI Art Store',
                'new',
                $googleCat,
                $catMap[$p['category_id']]['name'] ?? '',
                'ART-' . $pId,
                'FALSE',
                'IN::Digital Delivery::0.00 INR',
                $p['tags'] ?? '',
            ]);
        }

        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);

        return $this->response
            ->setContentType('text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="aiartstore-products.csv"')
            ->setBody($csv);
    }
}
