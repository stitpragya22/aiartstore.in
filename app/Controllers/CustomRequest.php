<?php

namespace App\Controllers;

use App\Models\CustomRequestModel;
use App\Models\CustomRequestMessageModel;

class CustomRequest extends BaseController
{
    public function index()
    {
        $data['title'] = 'Request Custom AI Art';
        $data['meta_description'] = 'Request custom AI-generated artwork or AI music tailored to your needs. Choose from flexible plans.';
        return view('custom_request/index', $data);
    }

    public function submit()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/custom-request');
        }

        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to submit a request');
        }

        $user = auth()->user();
        $name = $this->request->getPost('name') ?: ($user->username ?: $user->name ?: '');
        $email = $user->email; // always from session, never from request

        $rules = [
            'name'         => 'required|min_length[2]|max_length[100]',
            'request_type' => 'required|in_list[ai_art,ai_audio,other]',
            'plan'         => 'required|in_list[free,99,249,499]',
            'description'  => 'required|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $plan = $this->request->getPost('plan');
        $model = new CustomRequestModel();

        if ($plan === 'free') {
            $recent = $model->where('email', $email)
                ->where('plan', 'free')
                ->where('created_at >', date('Y-m-d H:i:s', strtotime('-4 days')))
                ->first();
            if ($recent && $recent['status'] !== 'completed') {
                $nextAllowed = date('d M Y', strtotime($recent['created_at'] . ' +4 days'));
                return redirect()->back()->withInput()->with('error', "You already have a free request in progress. You can submit another free request after {$nextAllowed} or once your current request is fulfilled.");
            }
        }

        $data = [
            'name'         => $name,
            'email'        => $email,
            'request_type' => $this->request->getPost('request_type'),
            'plan'         => $this->request->getPost('plan'),
            'description'  => $this->request->getPost('description'),
            'status'       => 'pending',
        ];

        $image = $this->request->getFile('reference_image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            if (in_array($image->getMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/gif'])) {
                $uploadPath = FCPATH . 'uploads/custom-requests';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
                $newName = $image->getRandomName();
                $image->move($uploadPath, $newName);
                $data['reference_image'] = 'uploads/custom-requests/' . $newName;
            }
        }

        $model->save($data);
        $requestId = $model->insertID();

        $this->sendConfirmation($email, $name, $requestId);
        $this->notifyAdmin($requestId, $data);

        return redirect()->to('/custom-request/success');
    }

    public function success()
    {
        $data['title'] = 'Request Submitted';
        $data['meta_description'] = 'Your custom AI art request has been submitted successfully.';
        return view('custom_request/success', $data);
    }

    public function my()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();
        $model = new CustomRequestModel();
        $data['requests'] = $model->where('email', $user->email)->orderBy('id', 'DESC')->findAll();
        $data['title'] = 'My Custom Requests';
        return view('custom_request/my', $data);
    }

    public function track($id)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();
        $model = new CustomRequestModel();
        $request = $model->where('id', $id)->where('email', $user->email)->first();

        if (!$request) {
            return redirect()->to('/custom-request/my')->with('error', 'Request not found');
        }

        $msgModel = new CustomRequestMessageModel();

        if ($this->request->is('post')) {
            $msgData = [
                'request_id' => $id,
                'sender'     => 'customer',
                'message'    => $this->request->getPost('message'),
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $file = $this->request->getFile('file');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'application/pdf', 'application/zip', 'audio/mpeg', 'audio/wav'];
                if (in_array($file->getMimeType(), $allowed)) {
                    $uploadPath = FCPATH . 'uploads/custom-requests/messages';
                    if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $msgData['file'] = 'uploads/custom-requests/messages/' . $newName;
                }
            }

            if (!empty($msgData['message']) || !empty($msgData['file'])) {
                $msgModel->insert($msgData);
            }

            return redirect()->to('/custom-request/track/' . $id);
        }

        $data['request'] = $request;
        $data['messages'] = $msgModel->where('request_id', $id)->orderBy('id', 'ASC')->findAll();
        $data['title'] = 'Request #' . $id;
        return view('custom_request/track', $data);
    }

    private function sendConfirmation(string $email, string $name, int $requestId): void
    {
        $msg = \Config\Services::email();
        $msg->setTo($email);
        $msg->setSubject('Custom Request #' . $requestId . ' Received - AI Art Store');
        $msg->setMessage(view('emails/custom_request_confirmation', [
            'name'      => $name,
            'requestId' => $requestId,
        ]));
        $msg->send();
    }

    private function notifyAdmin(int $requestId, array $data): void
    {
        $adminEmail = get_custom_setting('admin_email', env('email.adminEmail', 'info@aiartstore.in'));
        if (!$adminEmail) return;

        $email = \Config\Services::email();
        $email->setTo($adminEmail);
        $email->setSubject('New Custom Request #' . $requestId . ' - ' . $data['name']);
        $message = view('emails/custom_request_admin_notification', [
            'name'         => $data['name'],
            'email'        => $data['email'],
            'requestType'  => $data['request_type'],
            'plan'         => $data['plan'],
            'description'  => $data['description'],
            'requestId'    => $requestId,
        ]);
        $email->setMessage($message);
        $email->send();
    }
}
