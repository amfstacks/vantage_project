<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AmenityModel;
use App\Models\PropertyAmenityModel;
use App\Models\PropertyImageModel;
use App\Models\PropertyModel;
use App\Models\PropertyPriceModel;
use App\Models\PropertyRequestModel;
use App\Models\PropertyPurposeModel;
use App\Models\PropertyTypeModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class PropertyManager extends BaseController
{
    private PropertyModel $propertyModel;
    private AmenityModel $amenityModel;
    private PropertyAmenityModel $propertyAmenityModel;
    private PropertyImageModel $propertyImageModel;
    private PropertyPriceModel $propertyPriceModel;
    private PropertyPurposeModel $purposeModel;
    private PropertyTypeModel $propertyTypeModel;
    private $db;

    public function __construct()
    {
        $this->propertyModel = new PropertyModel();
        $this->amenityModel = new AmenityModel();
        $this->propertyAmenityModel = new PropertyAmenityModel();
        $this->propertyImageModel = new PropertyImageModel();
        $this->propertyPriceModel = new PropertyPriceModel();
        $this->purposeModel = new PropertyPurposeModel();
        $this->propertyTypeModel = new PropertyTypeModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $q = trim((string) $this->request->getGet('q'));
        $status = trim((string) $this->request->getGet('status'));
        $purposeId = (int) $this->request->getGet('purpose');
        $propertyTypeId = (int) $this->request->getGet('type');

        $builder = $this->propertyModel
            ->select("properties.*, (SELECT pi.image_path FROM property_images pi WHERE pi.property_id = properties.id ORDER BY pi.is_primary DESC, pi.id ASC LIMIT 1) AS image_path", false);

        if ($q !== '') {
            $builder->groupStart()
                ->like('properties.title', $q)
                ->orLike('properties.location', $q)
                ->orLike('properties.city', $q)
                ->orLike('properties.property_type', $q)
                ->groupEnd();
        }
        if (in_array($status, ['pending', 'active', 'sold'], true)) {
            $builder->where('properties.status', $status);
        }
        if ($purposeId > 0) {
            $builder->where('properties.purpose_id', $purposeId);
        }
        if ($propertyTypeId > 0) {
            $builder->where('properties.property_type_id', $propertyTypeId);
        }

        $properties = $builder->orderBy('properties.created_at', 'DESC')->paginate(12);
        $this->attachPrices($properties);

        return view('admin/properties_list', [
            'title' => 'Manage Properties',
            'properties' => $properties,
            'pager' => $this->propertyModel->pager,
            'q' => $q,
            'currentStatus' => $status,
            'currentPurpose' => $purposeId,
            'currentPropertyType' => $propertyTypeId,
            'purposes' => $this->purposeModel->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
            'propertyTypes' => $this->propertyTypeModel->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('admin/property_form', [
            'title' => 'Add Property',
            'amenities' => $this->amenityModel->orderBy('name', 'ASC')->findAll(),
            'selectedAmenities' => [],
            'existingImages' => [],
            'propertyPrices' => [],
            'purposes' => $this->purposeModel->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
            'propertyTypes' => $this->propertyTypeModel->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        if (! $this->validate($this->validationRules())) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $files = $this->request->getFileMultiple('images') ?? [];
        $fileError = $this->validateImages($files, true);
        if ($fileError !== null) {
            return redirect()->back()->withInput()->with('error', $fileError);
        }

        try {
            [$purpose, $propertyType] = $this->resolveTaxonomiesFromPost();
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        $slug = $this->uniqueSlug((string) $this->request->getPost('title'));
        $data = $this->propertyPayload($slug, $purpose, $propertyType);
        $uploadedPaths = [];

        $this->db->transBegin();
        try {
            $this->propertyModel->insert($data);
            $id = (int) $this->propertyModel->getInsertID();

            $this->syncAmenities($id);
            $this->syncPrices($id);
            $uploadedPaths = $this->saveImages($id, $files, true);

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('Database transaction failed.');
            }
            $this->db->transCommit();

            return redirect()->to('/admin/properties')->with('success', $data['status'] === 'active'
                ? 'Property published successfully.'
                : 'Property saved as draft.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            $this->cleanupFiles($uploadedPaths);
            log_message('error', 'Property store failed: {message}', ['message' => $e->getMessage()]);
            $message = $e instanceof \RuntimeException
                ? $e->getMessage()
                : 'Could not save the property. Please review the details and try again.';
            return redirect()->back()->withInput()->with('error', $message);
        }
    }

    public function edit($id)
    {
        $property = $this->propertyModel->find((int) $id);
        if (! $property) {
            throw PageNotFoundException::forPageNotFound('Property not found.');
        }

        $propertyAmenities = $this->propertyAmenityModel->where('property_id', $property->id)->findAll();

        return view('admin/property_form', [
            'title' => 'Edit Property',
            'property' => $property,
            'amenities' => $this->amenityModel->orderBy('name', 'ASC')->findAll(),
            'selectedAmenities' => array_map(static fn ($item) => (int) (is_array($item) ? $item['amenity_id'] : $item->amenity_id), $propertyAmenities),
            'existingImages' => $this->propertyImageModel->where('property_id', $property->id)->orderBy('is_primary', 'DESC')->findAll(),
            'propertyPrices' => $this->propertyPriceModel->where('property_id', $property->id)->findAll(),
            'purposes' => $this->purposeModel->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
            'propertyTypes' => $this->propertyTypeModel->orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function update($id)
    {
        $property = $this->propertyModel->find((int) $id);
        if (! $property) {
            throw PageNotFoundException::forPageNotFound('Property not found.');
        }

        if (! $this->validate($this->validationRules())) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $files = $this->request->getFileMultiple('images') ?? [];
        $fileError = $this->validateImages($files, false);
        if ($fileError !== null) {
            return redirect()->back()->withInput()->with('error', $fileError);
        }

        try {
            [$purpose, $propertyType] = $this->resolveTaxonomiesFromPost($property);
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        $title = (string) $this->request->getPost('title');
        $slug = $property->title === $title ? $property->slug : $this->uniqueSlug($title, $property->id);
        $data = $this->propertyPayload($slug, $purpose, $propertyType);
        $uploadedPaths = [];

        $this->db->transBegin();
        try {
            $this->propertyModel->update($property->id, $data);
            $this->syncAmenities($property->id);
            $this->syncPrices($property->id);

            if ($files !== []) {
                $hasPrimary = $this->propertyImageModel
                    ->where('property_id', $property->id)
                    ->where('is_primary', 1)
                    ->first() !== null;
                $uploadedPaths = $this->saveImages($property->id, $files, ! $hasPrimary);
            }

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('Database transaction failed.');
            }
            $this->db->transCommit();

            return redirect()->to('/admin/properties')->with('success', 'Property updated successfully.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            $this->cleanupFiles($uploadedPaths);
            log_message('error', 'Property update failed: {message}', ['message' => $e->getMessage()]);
            $message = $e instanceof \RuntimeException
                ? $e->getMessage()
                : 'Could not update the property. Please try again.';
            return redirect()->back()->withInput()->with('error', $message);
        }
    }

    public function delete($id)
    {
        $property = $this->propertyModel->find((int) $id);
        if (! $property) {
            return redirect()->to('/admin/properties')->with('error', 'Property not found.');
        }

        $images = $this->propertyImageModel->where('property_id', $property->id)->findAll();
        $paths = array_map(static fn ($image) => (string) $image->image_path, $images);

        $this->db->transBegin();
        try {
            if ($this->db->tableExists('property_requests')) {
                (new PropertyRequestModel())->where('property_id', $property->id)->delete();
            }
            $this->propertyAmenityModel->where('property_id', $property->id)->delete();
            $this->propertyPriceModel->where('property_id', $property->id)->delete();
            $this->propertyImageModel->where('property_id', $property->id)->delete();
            $this->propertyModel->delete($property->id);

            if ($this->db->transStatus() === false) {
                throw new \RuntimeException('Database transaction failed.');
            }
            $this->db->transCommit();
            $this->cleanupFiles($paths);

            return redirect()->to('/admin/properties')->with('success', 'Property deleted.');
        } catch (\Throwable $e) {
            $this->db->transRollback();
            return redirect()->to('/admin/properties')->with('error', 'Could not delete the property.');
        }
    }

    public function deleteImage($imageId)
    {
        $image = $this->propertyImageModel->find((int) $imageId);
        if (! $image) {
            return redirect()->back()->with('error', 'Image not found.');
        }

        $propertyId = (int) $image->property_id;
        $wasPrimary = (int) $image->is_primary === 1;
        $this->propertyImageModel->delete($image->id);
        $this->cleanupFiles([(string) $image->image_path]);

        if ($wasPrimary) {
            $replacement = $this->propertyImageModel->where('property_id', $propertyId)->orderBy('id', 'ASC')->first();
            if ($replacement) {
                $this->propertyImageModel->update($replacement->id, ['is_primary' => 1]);
            }
        }

        return redirect()->back()->with('success', 'Image removed.');
    }

    public function setPrimaryImage($imageId)
    {
        $image = $this->propertyImageModel->find((int) $imageId);
        if (! $image) {
            return redirect()->back()->with('error', 'Image not found.');
        }

        $this->db->transStart();
        $this->db->table('property_images')->where('property_id', $image->property_id)->update(['is_primary' => 0]);
        $this->propertyImageModel->update($image->id, ['is_primary' => 1]);
        $this->db->transComplete();

        return redirect()->back()->with('success', 'Primary image updated.');
    }

    private function validationRules(): array
    {
        return [
            'title' => 'required|min_length[5]|max_length[255]',
            'purpose_id' => 'required|is_natural_no_zero',
            'property_type_id' => 'required|is_natural_no_zero',
            'location' => 'required|max_length[100]',
            'city' => 'required|max_length[100]',
            'description' => 'required|min_length[10]',
            'prices.*.price' => 'required|numeric',
            'prices.*.price_unit' => 'required|max_length[30]',
            'prices.*.purpose_id' => 'permit_empty|is_natural_no_zero',
            'prices.*.discount_price' => 'permit_empty|numeric',
            'bedrooms' => 'permit_empty|is_natural',
            'bathrooms' => 'permit_empty|is_natural',
            'toilets' => 'permit_empty|is_natural',
            'area_sqm' => 'permit_empty|numeric',
            'video_url' => 'permit_empty|valid_url_strict|max_length[255]',
            'virtual_tour_url' => 'permit_empty|valid_url_strict|max_length[255]',
            'meta_title' => 'permit_empty|max_length[100]',
            'meta_description' => 'permit_empty|max_length[180]',
        ];
    }

    private function propertyPayload(string $slug, object $purpose, object $propertyType): array
    {
        $action = (string) $this->request->getPost('action');
        $status = $action === 'publish' ? 'active' : ($action === 'sold' ? 'sold' : 'pending');

        return [
            'user_id' => (int) (session()->get('user_id') ?: 1),
            'slug' => $slug,
            'title' => trim((string) $this->request->getPost('title')),
            'purpose_id' => (int) $purpose->id,
            'purpose' => (string) $purpose->slug,
            'property_type_id' => (int) $propertyType->id,
            'property_type' => (string) $propertyType->name,
            'address' => trim((string) $this->request->getPost('address')),
            'location' => trim((string) $this->request->getPost('location')),
            'city' => trim((string) $this->request->getPost('city')),
            'latitude' => trim((string) $this->request->getPost('latitude')),
            'longitude' => trim((string) $this->request->getPost('longitude')),
            'bedrooms' => (int) ($this->request->getPost('bedrooms') ?: 0),
            'bathrooms' => (int) ($this->request->getPost('bathrooms') ?: 0),
            'toilets' => (int) ($this->request->getPost('toilets') ?: 0),
            'area_sqm' => $this->request->getPost('area_sqm') !== '' ? $this->request->getPost('area_sqm') : null,
            'description' => (string) $this->request->getPost('description'),
            'video_url' => trim((string) $this->request->getPost('video_url')) ?: null,
            'virtual_tour_url' => trim((string) $this->request->getPost('virtual_tour_url')) ?: null,
            'meta_title' => trim((string) $this->request->getPost('meta_title')) ?: null,
            'meta_description' => trim((string) $this->request->getPost('meta_description')) ?: null,
            'status' => $status,
        ];
    }

    private function resolveTaxonomiesFromPost(?object $existingProperty = null): array
    {
        $purposeId = (int) $this->request->getPost('purpose_id');
        $propertyTypeId = (int) $this->request->getPost('property_type_id');

        $purpose = $purposeId > 0 ? $this->purposeModel->find($purposeId) : null;
        $propertyType = $propertyTypeId > 0 ? $this->propertyTypeModel->find($propertyTypeId) : null;

        if (! $purpose) {
            throw new \RuntimeException('Please select a valid property purpose.');
        }
        if (! $propertyType) {
            throw new \RuntimeException('Please select a valid property type.');
        }
        $sameExistingPurpose = $existingProperty && (int) ($existingProperty->purpose_id ?? 0) === (int) $purpose->id;
        $sameExistingType = $existingProperty && (int) ($existingProperty->property_type_id ?? 0) === (int) $propertyType->id;

        if ((int) $purpose->is_active !== 1 && ! $sameExistingPurpose) {
            throw new \RuntimeException('The selected property purpose is inactive. Activate it first or choose another purpose.');
        }
        if ((int) $propertyType->is_active !== 1 && ! $sameExistingType) {
            throw new \RuntimeException('The selected property type is inactive. Activate it first or choose another type.');
        }

        return [$purpose, $propertyType];
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = url_title($title, '-', true) ?: 'property';
        $slug = $base;
        $counter = 2;

        while (true) {
            $query = (new PropertyModel())->where('slug', $slug);
            if ($ignoreId !== null) {
                $query->where('id !=', $ignoreId);
            }
            if (! $query->first()) {
                return $slug;
            }
            $slug = $base . '-' . $counter++;
        }
    }

    private function syncAmenities(int $propertyId): void
    {
        $this->propertyAmenityModel->where('property_id', $propertyId)->delete();
        $selected = $this->request->getPost('amenities');
        if (! is_array($selected) || $selected === []) {
            return;
        }

        $rows = [];
        foreach (array_unique(array_map('intval', $selected)) as $amenityId) {
            if ($amenityId > 0) {
                $rows[] = ['property_id' => $propertyId, 'amenity_id' => $amenityId];
            }
        }
        if ($rows !== []) {
            $this->propertyAmenityModel->insertBatch($rows);
        }
    }

    private function syncPrices(int $propertyId): void
    {
        $prices = $this->request->getPost('prices');
        if (! is_array($prices) || $prices === []) {
            throw new \RuntimeException('At least one price is required.');
        }

        $rows = [];
        foreach ($prices as $index => $price) {
            $amount = isset($price['price']) ? (float) $price['price'] : 0;
            if ($amount <= 0) {
                continue;
            }

            $purposeId = isset($price['purpose_id']) && $price['purpose_id'] !== ''
                ? (int) $price['purpose_id']
                : null;
            if ($purposeId !== null && ! $this->purposeModel->find($purposeId)) {
                throw new \RuntimeException('Price option ' . ((int) $index + 1) . ' has an invalid purpose.');
            }

            $discount = isset($price['discount_price']) && $price['discount_price'] !== ''
                ? (float) $price['discount_price']
                : null;
            if ($discount !== null && $discount <= 0) {
                $discount = null;
            }
            if ($discount !== null && $discount >= $amount) {
                throw new \RuntimeException('Discount price for price option ' . ((int) $index + 1) . ' must be lower than the regular price.');
            }

            $discountPercentage = $discount !== null
                ? round((($amount - $discount) / $amount) * 100, 2)
                : null;

            $rows[] = [
                'property_id' => $propertyId,
                'purpose_id' => $purposeId,
                'price' => $amount,
                'price_unit' => trim((string) ($price['price_unit'] ?? 'One Time')) ?: 'One Time',
                'discount_price' => $discount,
                'discount_percentage' => $discountPercentage,
            ];
        }

        if ($rows === []) {
            throw new \RuntimeException('At least one valid price is required.');
        }

        $this->propertyPriceModel->where('property_id', $propertyId)->delete();
        $this->propertyPriceModel->insertBatch($rows);

        // Keep legacy property-level price columns synchronized for backward compatibility and SEO fallbacks.
        $first = $rows[0];
        $this->propertyModel->update($propertyId, [
            'price' => $first['price'],
            'price_unit' => $first['price_unit'],
            'discount_price' => $first['discount_price'],
        ]);
    }

    private function validateImages(array $files, bool $required): ?string
    {
        $validFiles = array_values(array_filter($files, static fn ($file) => $file && $file->getError() !== UPLOAD_ERR_NO_FILE));
        if ($required && $validFiles === []) {
            return 'Please upload at least one property image.';
        }
        if (count($validFiles) > 20) {
            return 'You can upload a maximum of 20 images at once.';
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        foreach ($validFiles as $file) {
            if (! $file->isValid()) {
                return 'One of the uploaded images is invalid.';
            }
            if ($file->getSize() > 5 * 1024 * 1024) {
                return 'Each property image must be 5 MB or smaller.';
            }
            if (! in_array($file->getMimeType(), $allowedMimes, true)) {
                return 'Only JPG, PNG and WebP images are allowed.';
            }
        }

        return null;
    }

    private function saveImages(int $propertyId, array $files, bool $firstAsPrimary): array
    {
        $uploadDir = FCPATH . 'uploads/properties';
        if (! is_dir($uploadDir) && ! mkdir($uploadDir, 0755, true) && ! is_dir($uploadDir)) {
            throw new \RuntimeException('Could not create the property upload directory.');
        }

        $savedPaths = [];
        $isPrimary = $firstAsPrimary ? 1 : 0;
        foreach ($files as $file) {
            if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $newName = $file->getRandomName();
            $file->move($uploadDir, $newName);
            $path = 'uploads/properties/' . $newName;
            $savedPaths[] = $path;

            $this->propertyImageModel->insert([
                'property_id' => $propertyId,
                'image_path' => $path,
                'is_primary' => $isPrimary,
            ]);
            $isPrimary = 0;
        }

        return $savedPaths;
    }

    private function cleanupFiles(array $paths): void
    {
        foreach ($paths as $path) {
            $full = FCPATH . ltrim((string) $path, '/');
            if (is_file($full)) {
                @unlink($full);
            }
        }
    }

    private function attachPrices(array &$properties): void
    {
        if ($properties === []) {
            return;
        }
        $ids = array_map(static fn ($property) => (int) $property->id, $properties);
        $prices = $this->propertyPriceModel->whereIn('property_id', $ids)->orderBy('price', 'ASC')->findAll();
        $grouped = [];
        foreach ($prices as $price) {
            $grouped[$price->property_id][] = $price;
        }
        foreach ($properties as $property) {
            $property->prices = $grouped[$property->id] ?? [];
        }
    }
}
