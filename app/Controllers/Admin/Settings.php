<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AmenityModel;
use App\Models\PropertyAmenityModel;
use App\Models\PropertyModel;
use App\Models\PropertyPriceModel;
use App\Models\PropertyPurposeModel;
use App\Models\PropertyTypeModel;

class Settings extends BaseController
{
    private AmenityModel $amenityModel;
    private PropertyTypeModel $propertyTypeModel;
    private PropertyPurposeModel $purposeModel;

    public function __construct()
    {
        $this->amenityModel = new AmenityModel();
        $this->propertyTypeModel = new PropertyTypeModel();
        $this->purposeModel = new PropertyPurposeModel();
    }

    public function amenities()
    {
        return view('admin/amenities', [
            'title' => 'Manage Amenities',
            'amenities' => $this->amenityModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function saveAmenity()
    {
        $rules = [
            'name' => 'required|min_length[2]|max_length[50]',
            'icon' => 'permit_empty|max_length[80]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $id = (int) $this->request->getPost('id');
        $name = trim((string) $this->request->getPost('name'));
        $icon = trim((string) $this->request->getPost('icon'));
        $icon = preg_replace('/^fa-(solid|regular|brands)\s+/i', '', $icon) ?? $icon;

        $duplicate = (new AmenityModel())->where('name', $name);
        if ($id > 0) {
            $duplicate->where('id !=', $id);
        }
        if ($duplicate->first()) {
            return redirect()->back()->withInput()->with('error', 'An amenity with this name already exists.');
        }

        $data = ['name' => $name, 'icon' => $icon ?: 'fa-check'];
        if ($id > 0) {
            if (! $this->amenityModel->find($id)) {
                return redirect()->to('/admin/amenities')->with('error', 'Amenity not found.');
            }
            $this->amenityModel->update($id, $data);
            return redirect()->to('/admin/amenities')->with('success', 'Amenity updated successfully.');
        }

        $this->amenityModel->insert($data);
        return redirect()->to('/admin/amenities')->with('success', 'Amenity added successfully.');
    }

    public function deleteAmenity($id)
    {
        $id = (int) $id;
        if (! $this->amenityModel->find($id)) {
            return redirect()->to('/admin/amenities')->with('error', 'Amenity not found.');
        }

        $db = \Config\Database::connect();
        $db->transStart();
        (new PropertyAmenityModel())->where('amenity_id', $id)->delete();
        $this->amenityModel->delete($id);
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/admin/amenities')->with('error', 'Failed to delete amenity.');
        }

        return redirect()->to('/admin/amenities')->with('success', 'Amenity deleted successfully.');
    }

    public function propertyTypes()
    {
        return view('admin/property_types', [
            'title' => 'Property Types',
            'propertyTypes' => $this->propertyTypeModel
                ->orderBy('sort_order', 'ASC')
                ->orderBy('name', 'ASC')
                ->findAll(),
        ]);
    }

    public function savePropertyType()
    {
        if (! $this->validate([
            'name' => 'required|min_length[2]|max_length[100]',
            'slug' => 'permit_empty|max_length[120]',
            'description' => 'permit_empty|max_length[255]',
            'sort_order' => 'permit_empty|integer',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $id = (int) $this->request->getPost('id');
        $name = trim((string) $this->request->getPost('name'));
        $slug = $this->normaliseSlug((string) $this->request->getPost('slug'), $name);
        $existing = $id > 0 ? $this->propertyTypeModel->find($id) : null;
        if ($id > 0 && ! $existing) {
            return redirect()->to('/admin/property-types')->with('error', 'Property type not found.');
        }

        if ($this->taxonomyDuplicate($this->propertyTypeModel, 'name', $name, $id)) {
            return redirect()->back()->withInput()->with('error', 'A property type with this name already exists.');
        }
        if ($this->taxonomyDuplicate($this->propertyTypeModel, 'slug', $slug, $id)) {
            return redirect()->back()->withInput()->with('error', 'That property type slug is already in use.');
        }

        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'sort_order' => (int) ($this->request->getPost('sort_order') ?: 0),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $db = \Config\Database::connect();
        $db->transStart();
        if ($id > 0) {
            $this->propertyTypeModel->update($id, $data);
            // Keep the legacy text column synchronized for existing frontend code and SEO fallbacks.
            $db->table('properties')->where('property_type_id', $id)->update(['property_type' => $name]);
        } else {
            $this->propertyTypeModel->insert($data);
        }
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Could not save the property type.');
        }

        return redirect()->to('/admin/property-types')->with('success', $id > 0 ? 'Property type updated.' : 'Property type added.');
    }

    public function deletePropertyType($id)
    {
        $id = (int) $id;
        $type = $this->propertyTypeModel->find($id);
        if (! $type) {
            return redirect()->to('/admin/property-types')->with('error', 'Property type not found.');
        }

        $inUse = (new PropertyModel())->where('property_type_id', $id)->countAllResults();
        if ($inUse > 0) {
            return redirect()->to('/admin/property-types')->with('error', 'This property type is assigned to ' . $inUse . ' listing(s). Deactivate it instead of deleting it.');
        }

        $this->propertyTypeModel->delete($id);
        return redirect()->to('/admin/property-types')->with('success', 'Property type deleted.');
    }

    public function purposes()
    {
        return view('admin/purposes', [
            'title' => 'Property Purposes',
            'purposes' => $this->purposeModel
                ->orderBy('sort_order', 'ASC')
                ->orderBy('name', 'ASC')
                ->findAll(),
        ]);
    }

    public function savePurpose()
    {
        if (! $this->validate([
            'name' => 'required|min_length[2]|max_length[100]',
            'slug' => 'permit_empty|max_length[120]',
            'description' => 'permit_empty|max_length[255]',
            'sort_order' => 'permit_empty|integer',
        ])) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $id = (int) $this->request->getPost('id');
        $name = trim((string) $this->request->getPost('name'));
        $slug = $this->normaliseSlug((string) $this->request->getPost('slug'), $name);
        $existing = $id > 0 ? $this->purposeModel->find($id) : null;
        if ($id > 0 && ! $existing) {
            return redirect()->to('/admin/purposes')->with('error', 'Purpose not found.');
        }

        if ($this->taxonomyDuplicate($this->purposeModel, 'name', $name, $id)) {
            return redirect()->back()->withInput()->with('error', 'A purpose with this name already exists.');
        }
        if ($this->taxonomyDuplicate($this->purposeModel, 'slug', $slug, $id)) {
            return redirect()->back()->withInput()->with('error', 'That purpose slug is already in use.');
        }

        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'sort_order' => (int) ($this->request->getPost('sort_order') ?: 0),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];

        $db = \Config\Database::connect();
        $db->transStart();
        if ($id > 0) {
            $this->purposeModel->update($id, $data);
            // Legacy purpose is now VARCHAR and mirrors the taxonomy slug for backwards compatibility.
            $db->table('properties')->where('purpose_id', $id)->update(['purpose' => $slug]);
        } else {
            $this->purposeModel->insert($data);
        }
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Could not save the purpose.');
        }

        return redirect()->to('/admin/purposes')->with('success', $id > 0 ? 'Purpose updated.' : 'Purpose added.');
    }

    public function deletePurpose($id)
    {
        $id = (int) $id;
        $purpose = $this->purposeModel->find($id);
        if (! $purpose) {
            return redirect()->to('/admin/purposes')->with('error', 'Purpose not found.');
        }

        $propertyUsage = (new PropertyModel())->where('purpose_id', $id)->countAllResults();
        $priceUsage = (new PropertyPriceModel())->where('purpose_id', $id)->countAllResults();
        if ($propertyUsage > 0 || $priceUsage > 0) {
            return redirect()->to('/admin/purposes')->with('error', 'This purpose is already in use. Deactivate it instead of deleting it.');
        }

        $this->purposeModel->delete($id);
        return redirect()->to('/admin/purposes')->with('success', 'Purpose deleted.');
    }

    private function normaliseSlug(string $slug, string $fallback): string
    {
        $value = trim($slug) !== '' ? trim($slug) : $fallback;
        return url_title($value, '-', true) ?: 'item';
    }

    private function taxonomyDuplicate($model, string $field, string $value, int $ignoreId): bool
    {
        $query = $model->where($field, $value);
        if ($ignoreId > 0) {
            $query->where('id !=', $ignoreId);
        }
        return $query->first() !== null;
    }
}
