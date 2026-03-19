<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AmenityModel;

class Settings extends BaseController
{
    protected $amenityModel;

    public function __construct()
    {
        $this->amenityModel = new AmenityModel();
    }

    // Load the main view and pass all existing amenities
    public function amenities()
    {
        $data = [
            'title'     => 'Manage Amenities',
            'amenities' => $this->amenityModel->orderBy('name', 'ASC')->findAll()
        ];

        return view('admin/amenities', $data);
    }

    // Handles BOTH creating a new amenity and updating an existing one
    public function saveAmenity()
    {
        // Define strict validation rules
        $rules = [
            'name' => 'required|min_length[2]|max_length[50]',
            'icon' => 'permit_empty|max_length[50]'
        ];

        if (!$this->validate($rules)) {
            // Validation failed, send them back with the errors
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $id = $this->request->getPost('id'); // Hidden field from the form
        
        $saveData = [
            'name' => $this->request->getPost('name'),
            'icon' => $this->request->getPost('icon'), // e.g., 'wifi', 'tv' for Lucide icons
        ];

        if (!empty($id)) {
            // Update existing
            $this->amenityModel->update($id, $saveData);
            $message = 'Amenity updated successfully.';
        } else {
            // Insert new
            $this->amenityModel->insert($saveData);
            $message = 'Amenity added successfully.';
        }

        return redirect()->to('/admin/amenities')->with('success', $message);
    }

    // Delete an amenity
    public function deleteAmenity($id)
    {
        if ($this->amenityModel->delete($id)) {
            return redirect()->to('/admin/amenities')->with('success', 'Amenity deleted successfully.');
        }
        return redirect()->to('/admin/amenities')->with('error', 'Failed to delete amenity.');
    }
}