<?php

namespace App\Controllers;

use App\Models\PromptModel;
use App\Models\PromptImageModel;
use App\Models\CategoryModel;
use App\Models\UserSubscriptionModel;

class Prompts extends BaseController
{
    private $promptModel;
    private $promptImageModel;
    private $categoryModel;
    private $subscriptionModel;

    public function __construct()
    {
        $this->promptModel = new PromptModel();
        $this->promptImageModel = new PromptImageModel();
        $this->categoryModel = new CategoryModel();
        $this->subscriptionModel = new UserSubscriptionModel();
    }

    public function index()
    {
        $userId = auth()->loggedIn() ? auth()->id() : null;
        $userLevel = $userId ? $this->subscriptionModel->getHighestLevelForUser($userId) : 0;

        $promptModel = $this->promptModel
            ->select('prompts.*, categories.name as category_name')
            ->join('categories', 'categories.id = prompts.category_id', 'left')
            ->where('prompts.status', 'active');

        $categoryFilter = $this->request->getGet('category');
        if ($categoryFilter) {
            $promptModel->where('prompts.category_id', $categoryFilter);
        }

        $prompts = $promptModel->orderBy('prompts.id', 'DESC')->findAll();

        $promptIds = array_column($prompts, 'id');
        $allImages = $promptIds
            ? $this->promptImageModel->whereIn('prompt_id', $promptIds)->orderBy('id', 'ASC')->findAll()
            : [];

        $groupedImages = [];
        foreach ($allImages as $img) {
            $groupedImages[$img['prompt_id']][] = $img;
        }

        $categories = $this->categoryModel->where('status', 'active')->findAll();

        $data['prompts'] = $prompts;
        $data['groupedImages'] = $groupedImages;
        $data['categories'] = $categories;
        $data['userLevel'] = $userLevel;
        $data['isLoggedIn'] = $userId !== null;
        $data['title'] = 'Prompt Library - AI Art Store';
        $data['meta_description'] = 'Browse our curated library of AI prompts. Unlock premium prompts with a subscription.';
        return view('prompts/index', $data);
    }

    public function detail($id = null, $slug = null)
    {
        $prompt = $this->promptModel
            ->select('prompts.*, categories.name as category_name')
            ->join('categories', 'categories.id = prompts.category_id', 'left')
            ->where('prompts.id', $id)
            ->where('prompts.status', 'active')
            ->first();

        if (!$prompt) {
            return redirect()->to('/prompts')->with('error', 'Prompt not found');
        }

        $expectedSlug = $prompt['slug'] ?: url_title($prompt['title'], '-', true);
        if ($slug !== $expectedSlug) {
            return redirect()->to('/prompts/' . $prompt['id'] . '/' . $expectedSlug);
        }

        $userId = auth()->loggedIn() ? auth()->id() : null;
        $userLevel = $userId ? $this->subscriptionModel->getHighestLevelForUser($userId) : 0;

        if ($prompt['min_subscription_level'] > $userLevel) {
            return redirect()->to('/prompts')->with('error', 'Please upgrade your subscription to access this prompt');
        }

        $images = $this->promptImageModel->where('prompt_id', $id)->orderBy('id', 'ASC')->findAll();

        $data['prompt'] = $prompt;
        $data['images'] = $images;
        $data['title'] = $prompt['seo_title'] ?: $prompt['title'] . ' - AI Prompt';
        $data['meta_title'] = $prompt['seo_title'] ?: $prompt['title'] . ' - AI Art Store';
        $data['meta_description'] = $prompt['seo_description'] ?: 'View the "' . $prompt['title'] . '" AI prompt and its reference images.';
        $data['meta_keywords'] = $prompt['seo_keywords'] ?: '';
        $data['meta_image'] = 'uploads/prompts/' . ($prompt['seo_thumbnail'] ?: ($images[0]['image'] ?? ''));
        return view('prompts/detail', $data);
    }
}
