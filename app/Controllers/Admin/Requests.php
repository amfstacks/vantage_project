<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyRequestModel;

class Requests extends BaseController
{
    public function index()
    {
        $model = new PropertyRequestModel();
        $status = trim((string) $this->request->getGet('status'));
        $q = trim((string) $this->request->getGet('q'));

        $builder = $model
            ->select('property_requests.*, properties.title AS property_title, properties.slug AS property_slug')
            ->join('properties', 'properties.id = property_requests.property_id', 'left');

        if (in_array($status, ['new', 'contacted', 'scheduled', 'closed'], true)) {
            $builder->where('property_requests.status', $status);
        }

        if ($q !== '') {
            $builder->groupStart()
                ->like('property_requests.full_name', $q)
                ->orLike('property_requests.phone', $q)
                ->orLike('property_requests.email', $q)
                ->orLike('property_requests.request_reference', $q)
                ->orLike('properties.title', $q)
                ->groupEnd();
        }

        $requests = $builder->orderBy('property_requests.created_at', 'DESC')->paginate(15);

        return view('admin/requests_list', [
            'title' => 'Property Requests',
            'requests' => $requests,
            'pager' => $model->pager,
            'currentStatus' => $status,
            'q' => $q,
        ]);
    }

    public function updateStatus($id)
    {
        $model = new PropertyRequestModel();
        $request = $model->find((int) $id);
        if (! $request) {
            return redirect()->to('/admin/requests')->with('error', 'Request not found.');
        }

        $status = (string) $this->request->getPost('status');
        if (! in_array($status, ['new', 'contacted', 'scheduled', 'closed'], true)) {
            return redirect()->back()->with('error', 'Invalid request status.');
        }

        $model->update($request->id, ['status' => $status]);
        return redirect()->back()->with('success', 'Request status updated.');
    }
}
