<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\PropertyImageModel;
use App\Models\PropertyModel;
use App\Models\PropertyPriceModel;
use App\Models\PropertyPurposeModel;
use App\Models\PropertyTypeModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Properties extends BaseController
{
    public function index()
    {
        $result = $this->searchProperties();
        $db = \Config\Database::connect();

        $locations = $db->table('properties')
            ->select('location')
            ->where('status', 'active')
            ->where('location !=', '')
            ->groupBy('location')
            ->orderBy('location', 'ASC')
            ->get()->getResult();

        $purposes = (new PropertyPurposeModel())
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $types = (new PropertyTypeModel())
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('frontend/properties_list', array_merge($result, [
            'title' => 'Premium Properties in Abuja',
            'meta_description' => 'Browse homes, land, apartments, shortlets and investment properties in Abuja. Filter by location, property type, purpose, bedrooms and price.',
            'canonical_url' => base_url('properties'),
            'locations' => $locations,
            'purposes' => $purposes,
            'types' => $types,
        ]));
    }

    public function ajax()
    {
        $result = $this->searchProperties();
        $html = view('components/property_results', $result);

        return $this->response->setJSON([
            'ok' => true,
            'html' => $html,
            'total' => $result['total'],
            'page' => $result['pager']->getCurrentPage('default'),
            'page_count' => $result['pager']->getPageCount('default'),
        ]);
    }

    public function show(string $slug)
    {
        $propertyModel = new PropertyModel();
        $property = $propertyModel
            ->select('properties.*, pp.name AS purpose_name, pp.slug AS purpose_slug, pt.name AS property_type_name, pt.slug AS property_type_slug')
            ->join('property_purposes pp', 'pp.id = properties.purpose_id', 'left')
            ->join('property_types pt', 'pt.id = properties.property_type_id', 'left')
            ->where('properties.slug', $slug)
            ->where('properties.status', 'active')
            ->first();

        if (! $property) {
            throw PageNotFoundException::forPageNotFound('Property not found.');
        }

        $imageModel = new PropertyImageModel();
        $images = $imageModel
            ->where('property_id', $property->id)
            ->orderBy('is_primary', 'DESC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $priceModel = new PropertyPriceModel();
        $property->prices = $priceModel
            ->select('property_prices.*, property_purposes.name AS purpose_name, property_purposes.slug AS purpose_slug')
            ->join('property_purposes', 'property_purposes.id = property_prices.purpose_id', 'left')
            ->where('property_prices.property_id', $property->id)
            ->orderBy('property_prices.id', 'ASC')
            ->findAll();

        $db = \Config\Database::connect();
        $amenities = $db->table('property_amenities')
            ->select('amenities.name, amenities.icon')
            ->join('amenities', 'amenities.id = property_amenities.amenity_id')
            ->where('property_amenities.property_id', $property->id)
            ->orderBy('amenities.name', 'ASC')
            ->get()->getResult();

        $agent = null;
        if (! empty($property->user_id) && $db->tableExists('users')) {
            $agent = $db->table('users')
                ->select('first_name, last_name, email')
                ->where('id', $property->user_id)
                ->get()->getRow();
        }

        if ($db->fieldExists('view_count', 'properties')) {
            $db->table('properties')->where('id', $property->id)->set('view_count', 'view_count + 1', false)->update();
        }

        $related = (new PropertyModel())
            ->select("properties.*, pp.name AS purpose_name, pt.name AS property_type_name, (SELECT pi.image_path FROM property_images pi WHERE pi.property_id = properties.id ORDER BY pi.is_primary DESC, pi.id ASC LIMIT 1) AS image_path", false)
            ->join('property_purposes pp', 'pp.id = properties.purpose_id', 'left')
            ->join('property_types pt', 'pt.id = properties.property_type_id', 'left')
            ->where('properties.status', 'active')
            ->where('properties.id !=', $property->id)
            ->groupStart()
                ->where('properties.location', $property->location)
                ->orWhere('properties.property_type_id', $property->property_type_id)
            ->groupEnd()
            ->orderBy('properties.created_at', 'DESC')
            ->limit(3)
            ->find();
        $this->attachPrices($related, $priceModel);

        $cleanDescription = trim(preg_replace('/\s+/', ' ', strip_tags((string) $property->description)) ?? '');
        $seoDescription = trim((string) $property->meta_description) ?: mb_substr($cleanDescription, 0, 155);
        $seoTitle = trim((string) $property->meta_title) ?: ($property->title . ' | ' . config('Site')->siteNameLong);
        $primaryImage = ! empty($images) ? property_image_url($images[0]->image_path) : property_image_url(null);
        $canonical = base_url('property/' . $property->slug);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $property->title,
            'url' => $canonical,
            'description' => $seoDescription,
            'primaryImageOfPage' => $primaryImage,
            'mainEntity' => [
                '@type' => 'Place',
                'name' => $property->title,
                'description' => $cleanDescription,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $property->address,
                    'addressLocality' => $property->location,
                    'addressRegion' => $property->city,
                    'addressCountry' => 'NG',
                ],
            ],
        ];

        $price = property_price_data($property);
        if ($price['has_price']) {
            $schema['mainEntity']['offers'] = [
                '@type' => 'Offer',
                'priceCurrency' => 'NGN',
                'price' => (string) $price['effective_price'],
                'availability' => 'https://schema.org/InStock',
                'url' => $canonical,
            ];
        }

        return view('frontend/property_single', [
            'title' => $seoTitle,
            'meta_description' => $seoDescription,
            'og_image' => $primaryImage,
            'canonical_url' => $canonical,
            'structured_data' => [$schema],
            'property' => $property,
            'images' => $images,
            'amenities' => $amenities,
            'agent' => $agent,
            'relatedProperties' => $related,
            'youtubeEmbed' => youtube_embed_url($property->video_url),
        ]);
    }

    private function searchProperties(): array
    {
        $model = new PropertyModel();
        $priceModel = new PropertyPriceModel();

        $q = trim((string) $this->request->getGet('q'));
        $location = trim((string) $this->request->getGet('location'));
        $purpose = trim((string) $this->request->getGet('purpose'));
        $type = trim((string) $this->request->getGet('type'));
        $sort = trim((string) ($this->request->getGet('sort') ?: 'newest'));
        $bedrooms = (int) $this->request->getGet('bedrooms');
        $minPrice = (float) $this->request->getGet('min_price');
        $maxPrice = (float) $this->request->getGet('max_price');
        $perPage = (int) ($this->request->getGet('per_page') ?: 12);
        $perPage = in_array($perPage, [9, 12, 18, 24], true) ? $perPage : 12;

        $builder = $model
            ->select("properties.*, pp.name AS purpose_name, pp.slug AS purpose_slug, pt.name AS property_type_name, pt.slug AS property_type_slug, (SELECT pi.image_path FROM property_images pi WHERE pi.property_id = properties.id ORDER BY pi.is_primary DESC, pi.id ASC LIMIT 1) AS image_path, COALESCE((SELECT MIN(ppx.price) FROM property_prices ppx WHERE ppx.property_id = properties.id), NULLIF(properties.price, 0)) AS lowest_price", false)
            ->join('property_purposes pp', 'pp.id = properties.purpose_id', 'left')
            ->join('property_types pt', 'pt.id = properties.property_type_id', 'left')
            ->where('properties.status', 'active');

        if ($q !== '') {
            $builder->groupStart()
                ->like('properties.title', $q)
                ->orLike('properties.description', $q)
                ->orLike('properties.location', $q)
                ->orLike('properties.city', $q)
                ->orLike('properties.property_type', $q)
                ->groupEnd();
        }
        if ($location !== '') {
            $builder->where('properties.location', $location);
        }
        if ($purpose !== '') {
            $validPurpose = (new PropertyPurposeModel())->where('slug', $purpose)->where('is_active', 1)->first();
            if ($validPurpose) {
                $builder->where('properties.purpose_id', (int) $validPurpose->id);
            } else {
                $purpose = '';
            }
        }
        if ($type !== '') {
            $typeModel = new PropertyTypeModel();
            $validType = $typeModel->where('slug', $type)->where('is_active', 1)->first();
            if (! $validType) {
                // Backward compatibility for existing URLs that used the type name.
                $validType = (new PropertyTypeModel())->where('name', $type)->where('is_active', 1)->first();
            }
            if ($validType) {
                $builder->where('properties.property_type_id', (int) $validType->id);
                $type = (string) $validType->slug;
            } else {
                $type = '';
            }
        }
        if ($bedrooms > 0) {
            $builder->where('properties.bedrooms >=', $bedrooms);
        }

        $priceExpression = 'COALESCE((SELECT MIN(ppx.price) FROM property_prices ppx WHERE ppx.property_id = properties.id), NULLIF(properties.price, 0))';
        if ($minPrice > 0) {
            $builder->where($priceExpression . ' >= ' . $minPrice, null, false);
        }
        if ($maxPrice > 0) {
            $builder->where($priceExpression . ' <= ' . $maxPrice, null, false);
        }

        switch ($sort) {
            case 'price_low':
                $builder->orderBy('lowest_price IS NULL', 'ASC', false)->orderBy('lowest_price', 'ASC');
                break;
            case 'price_high':
                $builder->orderBy('lowest_price', 'DESC');
                break;
            case 'oldest':
                $builder->orderBy('properties.created_at', 'ASC');
                break;
            default:
                $sort = 'newest';
                $builder->orderBy('properties.created_at', 'DESC');
                break;
        }

        $properties = $builder->paginate($perPage);
        $pager = $model->pager;
        $total = $pager->getTotal('default');

        $this->attachPrices($properties, $priceModel);

        return [
            'properties' => $properties,
            'pager' => $pager,
            'total' => $total,
            'filters' => [
                'q' => $q,
                'location' => $location,
                'purpose' => $purpose,
                'type' => $type,
                'bedrooms' => $bedrooms,
                'min_price' => $minPrice > 0 ? $minPrice : '',
                'max_price' => $maxPrice > 0 ? $maxPrice : '',
                'sort' => $sort,
                'per_page' => $perPage,
            ],
        ];
    }

    private function attachPrices(array &$properties, PropertyPriceModel $priceModel): void
    {
        if ($properties === []) {
            return;
        }

        $ids = array_map(static fn ($property) => (int) $property->id, $properties);
        $prices = $priceModel
            ->select('property_prices.*, property_purposes.name AS purpose_name, property_purposes.slug AS purpose_slug')
            ->join('property_purposes', 'property_purposes.id = property_prices.purpose_id', 'left')
            ->whereIn('property_prices.property_id', $ids)
            ->orderBy('property_prices.id', 'ASC')
            ->findAll();

        $grouped = [];
        foreach ($prices as $price) {
            $grouped[$price->property_id][] = $price;
        }
        foreach ($properties as $property) {
            $property->prices = $grouped[$property->id] ?? [];
        }
    }
}
