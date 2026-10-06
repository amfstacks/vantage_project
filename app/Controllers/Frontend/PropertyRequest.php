<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyPriceModel;
use App\Models\PropertyRequestModel;

class PropertyRequest extends BaseController
{
    public function store()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'ok' => false,
                'message' => 'Invalid request method.',
            ]);
        }

        // Honeypot: bots tend to fill hidden fields.
        if (trim((string) $this->request->getPost('company')) !== '') {
            return $this->response->setJSON([
                'ok' => true,
                'message' => 'Request received.',
                'whatsapp_url' => null,
            ]);
        }

        $rules = [
            'property_id' => 'required|is_natural_no_zero',
            'full_name' => 'required|min_length[2]|max_length[120]',
            'phone' => 'required|min_length[7]|max_length[30]',
            'email' => 'permit_empty|valid_email|max_length[160]',
            'request_type' => 'required|in_list[information,viewing,call,offer]',
            'preferred_date' => 'permit_empty|valid_date[Y-m-d]',
            'message' => 'permit_empty|max_length[1500]',
        ];

        if (! $this->validate($rules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'ok' => false,
                'message' => 'Please check the highlighted details and try again.',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $propertyModel = new PropertyModel();
        $property = $propertyModel
            ->where('id', (int) $this->request->getPost('property_id'))
            ->where('status', 'active')
            ->first();

        if (! $property) {
            return $this->response->setStatusCode(404)->setJSON([
                'ok' => false,
                'message' => 'This property is no longer available.',
            ]);
        }

        $priceModel = new PropertyPriceModel();
        $property->prices = $priceModel->where('property_id', $property->id)->findAll();

        $db = db_connect();
        if (! $db->tableExists('property_requests')) {
            log_message('error', 'Property request attempted before the property_requests table was installed.');

            return $this->response->setStatusCode(503)->setJSON([
                'ok' => false,
                'message' => 'Property requests are temporarily unavailable. Please contact us directly while the site update is completed.',
            ]);
        }

        $requestModel = new PropertyRequestModel();
        $reference = 'REQ-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
        $sourceUrl = base_url('property/' . $property->slug);
        $preferredDate = trim((string) $this->request->getPost('preferred_date')) ?: null;
        $email = trim((string) $this->request->getPost('email')) ?: null;
        $clientIp = (string) $this->request->getIPAddress();

        $payload = [
            'property_id' => $property->id,
            'request_reference' => $reference,
            'full_name' => trim((string) $this->request->getPost('full_name')),
            'phone' => trim((string) $this->request->getPost('phone')),
            'email' => $email,
            'request_type' => (string) $this->request->getPost('request_type'),
            'preferred_date' => $preferredDate,
            'message' => trim((string) $this->request->getPost('message')),
            'status' => 'new',
            'source_url' => $sourceUrl,
            'client_ip_hash' => hash('sha256', $clientIp . '|' . config('Site')->siteName),
        ];

        if (! $requestModel->insert($payload)) {
            return $this->response->setStatusCode(500)->setJSON([
                'ok' => false,
                'message' => 'We could not save your request. Please try again.',
            ]);
        }

        $requestId = $requestModel->getInsertID();
        $typeLabel = [
            'information' => 'Property information',
            'viewing' => 'Schedule a viewing',
            'call' => 'Request a call back',
            'offer' => 'Discuss an offer',
        ][$payload['request_type']] ?? ucfirst($payload['request_type']);

        $lines = [
            'Hello Vantage Luxe Realty,',
            '',
            'I just submitted a property request on your website.',
            '',
            'REQUEST DETAILS',
            'Reference: ' . $reference,
            'Request: ' . $typeLabel,
            'Name: ' . $payload['full_name'],
            'Phone: ' . $payload['phone'],
        ];

        if ($payload['email']) {
            $lines[] = 'Email: ' . $payload['email'];
        }
        if ($payload['preferred_date']) {
            $lines[] = 'Preferred viewing date: ' . date('D, j M Y', strtotime($payload['preferred_date']));
        }
        if ($payload['message']) {
            $lines[] = 'Message: ' . $payload['message'];
        }

        $lines = array_merge($lines, [
            '',
            'PROPERTY DETAILS',
            'Property: ' . $property->title,
            'Reference: ' . property_reference($property),
            'Purpose: For ' . ucfirst((string) $property->purpose),
            'Type: ' . $property->property_type,
            'Location: ' . trim($property->location . ', ' . $property->city, ', '),
            'Address: ' . (trim((string) $property->address) !== '' ? trim((string) $property->address) : 'Not specified'),
            'Price: ' . property_price_text($property),
            'Bedrooms: ' . (int) $property->bedrooms,
            'Bathrooms: ' . (int) $property->bathrooms,
            'Toilets: ' . (int) $property->toilets,
            'Area: ' . ((float) $property->area_sqm > 0 ? rtrim(rtrim(number_format((float) $property->area_sqm, 2, '.', ','), '0'), '.') . ' sqm' : 'Not specified'),
        ]);

        if (trim((string) $property->video_url) !== '') {
            $lines[] = 'Video: ' . trim((string) $property->video_url);
        }
        if (trim((string) $property->virtual_tour_url) !== '') {
            $lines[] = 'Virtual tour: ' . trim((string) $property->virtual_tour_url);
        }

        $lines = array_merge($lines, [
            'Property URL: ' . $sourceUrl,
            '',
            'Website request ID: #' . $requestId,
        ]);

        $whatsappNumber = clean_phone_digits(config('Site')->whatsappNumber);
        $whatsappUrl = $whatsappNumber !== ''
            ? 'https://wa.me/' . $whatsappNumber . '?text=' . rawurlencode(implode("\n", $lines))
            : null;

        return $this->response->setJSON([
            'ok' => true,
            'message' => 'Your request has been saved. Opening WhatsApp so you can send it directly to our team.',
            'reference' => $reference,
            'whatsapp_url' => $whatsappUrl,
        ]);
    }
}
