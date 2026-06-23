<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CustomRequestModel;
use App\Models\CustomRequestMessageModel;

class CustomRequests extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new CustomRequestModel();
    }

    public function index()
    {
        $data['requests'] = $this->model->orderBy('id', 'DESC')->findAll();
        $data['title'] = 'Custom Requests';
        return view('admin/custom_requests/index', $data);
    }

    public function detail($id)
    {
        $request = $this->model->find($id);
        if (!$request) {
            return redirect()->to('/admin/custom-requests')->with('error', 'Request not found');
        }

        $msgModel = new CustomRequestMessageModel();

        if ($this->request->is('post')) {
            $action = $this->request->getPost('action');

            if ($action === 'update') {
                $update = [
                    'admin_notes' => $this->request->getPost('admin_notes'),
                    'status'      => $this->request->getPost('status'),
                ];

                $file = $this->request->getFile('result_file');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/mp4', 'application/zip'];
                    if (in_array($file->getMimeType(), $allowed)) {
                        if ($request['result_file'] && file_exists(FCPATH . $request['result_file'])) {
                            unlink(FCPATH . $request['result_file']);
                        }
                        $uploadPath = FCPATH . 'uploads/custom-requests/results';
                        if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
                        $newName = $file->getRandomName();
                        $file->move($uploadPath, $newName);
                        $update['result_file'] = 'uploads/custom-requests/results/' . $newName;
                    }
                }

                $sendEmail = $this->request->getPost('send_email') && $update['status'] === 'completed' && !empty($update['result_file']);

                $this->model->update($id, $update);

                if ($sendEmail) {
                    $this->sendResultEmail($request['email'], $request['name'], $update['result_file'], $id);
                    $this->model->update($id, ['sent_at' => date('Y-m-d H:i:s')]);
                    return redirect()->to('/admin/custom-requests/detail/' . $id)->with('message', 'Updated and result sent to customer');
                }

                if ($update['status'] !== $request['status']) {
                    $this->sendStatusUpdate($request['email'], $request['name'], $update['status'], $id);
                }

                return redirect()->to('/admin/custom-requests/detail/' . $id)->with('message', 'Request updated');
            }

            if ($action === 'message') {
                $msgData = [
                    'request_id' => $id,
                    'sender'     => 'admin',
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
                    $this->sendMessageNotification($request['email'], $request['name'], $id);
                }

                return redirect()->to('/admin/custom-requests/detail/' . $id)->with('message', 'Message sent');
            }
        }

        $data['request'] = $request;
        $data['messages'] = $msgModel->where('request_id', $id)->orderBy('id', 'ASC')->findAll();
        $data['title'] = 'Custom Request #' . $id;
        return view('admin/custom_requests/detail', $data);
    }

    public function delete($id)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/custom-requests')->with('error', 'Invalid request');
        }

        $request = $this->model->find($id);
        if ($request) {
            if ($request['reference_image'] && file_exists(FCPATH . $request['reference_image'])) {
                unlink(FCPATH . $request['reference_image']);
            }
            if ($request['result_file'] && file_exists(FCPATH . $request['result_file'])) {
                unlink(FCPATH . $request['result_file']);
            }
            $this->model->delete($id);
        }

        return redirect()->to('/admin/custom-requests')->with('message', 'Request deleted');
    }

    private function sendResultEmail($to, $name, $resultFile, $requestId)
    {
        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setSubject('Your Custom AI Art Request is Ready - AI Art Store');
        $message = view('emails/custom_request_result', [
            'name'       => $name,
            'resultFile' => base_url($resultFile),
            'requestId'  => $requestId,
        ]);
        $email->setMessage($message);

        $fullPath = FCPATH . $resultFile;
        if (file_exists($fullPath)) {
            $email->attach($fullPath);
        }

        return $email->send();
    }

    private function sendStatusUpdate($to, $name, $status, $requestId): void
    {
        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setSubject('Custom Request #' . $requestId . ' Status Update - AI Art Store');
        $email->setMessage(view('emails/custom_request_status_update', [
            'name'      => $name,
            'status'    => $status,
            'requestId' => $requestId,
        ]));
        $email->send();
    }

    private function sendMessageNotification($to, $name, $requestId): void
    {
        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setSubject('New Message on Custom Request #' . $requestId . ' - AI Art Store');
        $email->setMessage(view('emails/custom_request_message_notification', [
            'name'      => $name,
            'requestId' => $requestId,
        ]));
        $email->send();
    }
}
