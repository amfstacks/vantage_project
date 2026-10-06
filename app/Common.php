<?php

/**
 * Application-wide helpers for Vantage Luxe Realty.
 * This file is loaded by CodeIgniter during bootstrap.
 */

if (! function_exists('property_image_url')) {
    function property_image_url(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return base_url('assets/img/all-images/properties/property-img1.png');
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        return base_url(ltrim($path, '/'));
    }
}

if (! function_exists('property_price_data')) {
    /**
     * Return normalized pricing data for a property object.
     * Supports the newer property_prices relation and legacy columns on properties.
     */
    function property_price_data(object $property): array
    {
        $price = null;
        $discount = null;
        $unit = 'One Time';

        if (! empty($property->prices) && is_array($property->prices)) {
            $first = $property->prices[0] ?? null;
            if (is_object($first)) {
                $price = isset($first->price) ? (float) $first->price : null;
                $discount = isset($first->discount_price) && $first->discount_price !== null
                    ? (float) $first->discount_price
                    : null;
                $unit = trim((string) ($first->price_unit ?? 'One Time')) ?: 'One Time';
            }
        }

        if (($price === null || $price <= 0) && isset($property->price) && (float) $property->price > 0) {
            $price = (float) $property->price;
            $discount = isset($property->discount_price) && $property->discount_price !== null
                ? (float) $property->discount_price
                : null;
            $unit = trim((string) ($property->price_unit ?? 'One Time')) ?: 'One Time';
        }

        $effective = ($discount !== null && $discount > 0 && $price !== null && $discount < $price)
            ? $discount
            : $price;

        return [
            'price' => $price,
            'discount_price' => $discount,
            'effective_price' => $effective,
            'unit' => $unit,
            'has_price' => $effective !== null && $effective > 0,
            'has_discount' => $discount !== null && $discount > 0 && $price !== null && $discount < $price,
        ];
    }
}

if (! function_exists('property_price_text')) {
    function property_price_text(object $property, bool $includeUnit = true): string
    {
        $data = property_price_data($property);
        if (! $data['has_price']) {
            return 'Price on request';
        }

        $currency = (string) config('Site')->currency;
        $text = $currency . number_format((float) $data['effective_price'], 0);
        if ($includeUnit && strcasecmp($data['unit'], 'One Time') !== 0) {
            $text .= ' / ' . $data['unit'];
        }

        return $text;
    }
}

if (! function_exists('youtube_video_id')) {
    function youtube_video_id(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        $patterns = [
            '#(?:youtube\.com/watch\?(?:.*&)?v=|youtu\.be/|youtube\.com/embed/|youtube\.com/shorts/)([A-Za-z0-9_-]{6,})#i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}

if (! function_exists('youtube_embed_url')) {
    function youtube_embed_url(?string $url): ?string
    {
        $id = youtube_video_id($url);
        return $id ? 'https://www.youtube-nocookie.com/embed/' . rawurlencode($id) . '?rel=0&modestbranding=1' : null;
    }
}

if (! function_exists('property_safe_html')) {
    /**
     * Keep basic editorial formatting while removing executable/embedded content.
     */
    function property_safe_html(?string $html): string
    {
        $allowed = '<p><br><strong><b><em><i><ul><ol><li><h2><h3><h4><blockquote>';
        $clean = strip_tags((string) $html, $allowed);
        // Description markup does not need attributes; removing them also strips event handlers/styles.
        $clean = preg_replace('/<([a-z0-9]+)\b[^>]*>/i', '<$1>', $clean) ?? $clean;
        return $clean;
    }
}

if (! function_exists('property_reference')) {
    function property_reference(object $property): string
    {
        if (! empty($property->reference_code)) {
            return (string) $property->reference_code;
        }

        return 'VLR-' . str_pad((string) ($property->id ?? 0), 5, '0', STR_PAD_LEFT);
    }
}

if (! function_exists('clean_phone_digits')) {
    function clean_phone_digits(?string $phone): string
    {
        return preg_replace('/\D+/', '', (string) $phone) ?? '';
    }
}
