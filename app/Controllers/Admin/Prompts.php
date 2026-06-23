<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PromptModel;
use App\Models\PromptImageModel;
use App\Models\CategoryModel;
use App\Libraries\SocialMediaSharing;

class Prompts extends BaseController
{
    private $promptModel;
    private $promptImageModel;
    private $categoryModel;
    private string $uploadPath;

    public function __construct()
    {
        $this->promptModel = new PromptModel();
        $this->promptImageModel = new PromptImageModel();
        $this->categoryModel = new CategoryModel();
        $this->uploadPath = FCPATH . 'uploads/prompts';
    }

    public function index()
    {
        $prompts = $this->promptModel->getWithImages();

        $promptIds = array_column($prompts, 'id');
        $allImages = $promptIds
            ? $this->promptImageModel->whereIn('prompt_id', $promptIds)->orderBy('id', 'ASC')->findAll()
            : [];

        $groupedImages = [];
        foreach ($allImages as $img) {
            $groupedImages[$img['prompt_id']][] = $img;
        }

        // Load categories for display
        $categories = $this->categoryModel->findAll();
        $catMap = [];
        foreach ($categories as $c) {
            $catMap[$c['id']] = $c['name'];
        }
        $data['prompts'] = $prompts;
        $data['groupedImages'] = $groupedImages;
        $data['catMap'] = $catMap;
        $data['title'] = 'Prompt Library';
        return view('admin/prompts/index', $data);
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $data = [
                'title'                 => $this->request->getPost('title'),
                'slug'                  => $this->request->getPost('slug') ?: url_title($this->request->getPost('title'), '-', true),
                'prompt'                => $this->request->getPost('prompt'),
                'notes'                 => $this->request->getPost('notes'),
                'status'                => $this->request->getPost('status') ?? 'active',
                'category_id'           => $this->request->getPost('category_id') ?: null,
                'min_subscription_level' => $this->request->getPost('min_subscription_level') ?? 0,
                'seo_title'             => $this->request->getPost('seo_title'),
                'seo_description'       => $this->request->getPost('seo_description'),
                'seo_keywords'          => $this->request->getPost('seo_keywords'),
                'seo_thumbnail'         => $this->request->getPost('seo_thumbnail'),
            ];

            if (!$this->promptModel->save($data)) {
                return redirect()->back()->with('errors', $this->promptModel->errors())->withInput();
            }

            $id = $this->promptModel->insertID();
            $this->handleImageUploads($id);

            return redirect()->to('/admin/prompts')->with('message', 'Prompt created successfully');
        }

        $data['categories'] = $this->categoryModel->where('status', 'active')->findAll();
        $data['title'] = 'Add Prompt';
        return view('admin/prompts/form', $data);
    }

    public function edit($id = null)
    {
        $prompt = $this->promptModel->find($id);
        if (!$prompt) {
            return redirect()->to('/admin/prompts')->with('error', 'Prompt not found');
        }

        if ($this->request->is('post')) {
            $data = [
                'id'                    => $id,
                'title'                 => $this->request->getPost('title'),
                'slug'                  => $this->request->getPost('slug') ?: url_title($this->request->getPost('title'), '-', true),
                'prompt'                => $this->request->getPost('prompt'),
                'notes'                 => $this->request->getPost('notes'),
                'status'                => $this->request->getPost('status') ?? 'active',
                'category_id'           => $this->request->getPost('category_id') ?: null,
                'min_subscription_level' => $this->request->getPost('min_subscription_level') ?? 0,
                'seo_title'             => $this->request->getPost('seo_title'),
                'seo_description'       => $this->request->getPost('seo_description'),
                'seo_keywords'          => $this->request->getPost('seo_keywords'),
                'seo_thumbnail'         => $this->request->getPost('seo_thumbnail'),
            ];

            if (!$this->promptModel->save($data)) {
                return redirect()->back()->with('errors', $this->promptModel->errors())->withInput();
            }

            $this->handleImageUploads($id);

            return redirect()->to('/admin/prompts')->with('message', 'Prompt updated successfully');
        }

        $images = $this->promptImageModel->where('prompt_id', $id)->findAll();
        $data['prompt'] = $prompt;
        $data['images'] = $images;
        $data['categories'] = $this->categoryModel->where('status', 'active')->findAll();
        $data['title'] = 'Edit Prompt';
        return view('admin/prompts/form', $data);
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/prompts')->with('error', 'Invalid request');
        }

        $prompt = $this->promptModel->find($id);
        if ($prompt) {
            $images = $this->promptImageModel->where('prompt_id', $id)->findAll();
            foreach ($images as $img) {
                $filePath = $this->uploadPath . '/' . $img['image'];
                if (is_file($filePath)) {
                    unlink($filePath);
                }
            }
            $this->promptImageModel->where('prompt_id', $id)->delete();
            $this->promptModel->delete($id);
        }

        return redirect()->to('/admin/prompts')->with('message', 'Prompt deleted successfully');
    }

    public function deleteImage($imageId = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/prompts')->with('error', 'Invalid request');
        }

        $image = $this->promptImageModel->find($imageId);
        if ($image) {
            $filePath = $this->uploadPath . '/' . $image['image'];
            if (is_file($filePath)) {
                unlink($filePath);
            }
            $this->promptImageModel->delete($imageId);
        }

        return redirect()->back()->with('message', 'Image removed');
    }

    public function shareFacebookLink($id = null)
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $prompt = $this->promptModel->find($id);
        if (!$prompt) {
            return $this->response->setJSON(['success' => false, 'message' => 'Prompt not found']);
        }

        $sharer = new SocialMediaSharing();
        $result = $sharer->shareToFacebookLink($prompt);

        return $this->response->setJSON($result);
    }

    public function shareFacebookPhoto($id = null)
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $prompt = $this->promptModel->find($id);
        if (!$prompt) {
            return $this->response->setJSON(['success' => false, 'message' => 'Prompt not found']);
        }

        $images = $this->promptImageModel->where('prompt_id', $id)->orderBy('id', 'ASC')->findAll();

        $sharer = new SocialMediaSharing();
        $result = $sharer->shareToFacebookPhoto($prompt, $images);

        return $this->response->setJSON($result);
    }

    public function shareFacebookGallery($id = null)
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $prompt = $this->promptModel->find($id);
        if (!$prompt) {
            return $this->response->setJSON(['success' => false, 'message' => 'Prompt not found']);
        }

        $images = $this->promptImageModel->where('prompt_id', $id)->orderBy('id', 'ASC')->findAll();

        $sharer = new SocialMediaSharing();
        $result = $sharer->shareToFacebookGallery($prompt, $images);

        return $this->response->setJSON($result);
    }

    public function shareInstagram($id = null)
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $prompt = $this->promptModel->find($id);
        if (!$prompt) {
            return $this->response->setJSON(['success' => false, 'message' => 'Prompt not found']);
        }

        $images = $this->promptImageModel->where('prompt_id', $id)->orderBy('id', 'ASC')->findAll();

        $sharer = new SocialMediaSharing();
        $result = $sharer->shareToInstagram($prompt, $images);

        return $this->response->setJSON($result);
    }

    private function handleImageUploads(int $promptId): void
    {
        $files = $this->request->getFiles();
        if (!isset($files['images']) || empty($files['images'])) {
            return;
        }

        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }

        foreach ($files['images'] as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) {
                continue;
            }

            if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])) {
                continue;
            }

            if ($file->getSizeByUnit('mb') > 5) {
                continue;
            }

            $newName = $file->getRandomName();
            $file->move($this->uploadPath, $newName);

            $this->promptImageModel->save([
                'prompt_id' => $promptId,
                'image'     => $newName,
            ]);
        }
    }
}
