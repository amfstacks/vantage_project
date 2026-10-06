<?php
if (! isset($property) || ! is_object($property)) {
    return;
}

$image = property_image_url($property->image_path ?? null);
$url = base_url('property/' . ($property->slug ?: $property->id));
$price = property_price_data($property);
$locationText = trim((string) $property->location . (! empty($property->city) ? ', ' . $property->city : ''));
$purposeLabel = trim((string) ($property->purpose_name ?? '')) ?: ucfirst((string) $property->purpose);
$typeLabel = trim((string) ($property->property_type_name ?? '')) ?: (string) $property->property_type;
$prices = (! empty($property->prices) && is_array($property->prices)) ? $property->prices : [];
$firstPricePurpose = ! empty($prices[0]->purpose_name) ? trim((string) $prices[0]->purpose_name) : '';
$currency = (string) config('Site')->currency;

$pricingPayload = [];
foreach ($prices as $option) {
    $regular = (float) ($option->price ?? 0);
    $discount = isset($option->discount_price) && $option->discount_price !== null ? (float) $option->discount_price : 0;
    $hasDiscount = $discount > 0 && $regular > 0 && $discount < $regular;
    $effective = $hasDiscount ? $discount : $regular;
    $percentage = isset($option->discount_percentage) && (float) $option->discount_percentage > 0
        ? (float) $option->discount_percentage
        : ($hasDiscount && $regular > 0 ? (($regular - $discount) / $regular) * 100 : 0);

    $pricingPayload[] = [
        'price' => $regular,
        'effective' => $effective,
        'discount' => $hasDiscount ? $discount : null,
        'percentage' => $percentage,
        'unit' => trim((string) ($option->price_unit ?? 'One Time')) ?: 'One Time',
        'purpose' => trim((string) ($option->purpose_name ?? '')),
    ];
}
$pricingJson = htmlspecialchars(json_encode($pricingPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]', ENT_QUOTES, 'UTF-8');
?>
<article class="vl-property-card" data-reveal>
    <a class="vl-property-card__media" href="<?= esc($url) ?>" aria-label="View <?= esc($property->title) ?>">
        <img src="<?= esc($image) ?>" alt="<?= esc($property->title) ?> in <?= esc($locationText) ?>" loading="lazy" decoding="async">
        <div class="vl-property-card__badges">
            <?php if ($purposeLabel !== ''): ?><span class="vl-badge vl-badge--gold"><?= esc($purposeLabel) ?></span><?php endif; ?>
            <?php if ($typeLabel !== ''): ?><span class="vl-badge vl-badge--dark"><?= esc($typeLabel) ?></span><?php endif; ?>
        </div>
        <span class="vl-property-card__view"><i class="fa-solid fa-arrow-up-right"></i></span>
    </a>
    <div class="vl-property-card__body">
        <div class="vl-property-card__meta"><i class="fa-solid fa-location-dot"></i> <?= esc($locationText ?: 'Abuja') ?></div>
        <h3><a href="<?= esc($url) ?>"><?= esc($property->title) ?></a></h3>

        <div class="vl-property-card__features">
            <?php if ((int) $property->bedrooms > 0): ?><span><i class="fa-solid fa-bed"></i> <?= (int) $property->bedrooms ?> bed<?= (int) $property->bedrooms === 1 ? '' : 's' ?></span><?php endif; ?>
            <?php if ((int) $property->bathrooms > 0): ?><span><i class="fa-solid fa-bath"></i> <?= (int) $property->bathrooms ?> bath<?= (int) $property->bathrooms === 1 ? '' : 's' ?></span><?php endif; ?>
            <?php if (! empty($property->area_sqm)): ?><span><i class="fa-solid fa-ruler-combined"></i> <?= esc(rtrim(rtrim(number_format((float) $property->area_sqm, 2), '0'), '.')) ?> m²</span><?php endif; ?>
        </div>

        <div class="vl-property-card__foot">
            <div class="vl-price">
                <?php if ($price['has_price']): ?>
                    <div class="vl-price__line">
                        <strong><?= esc($currency) . number_format((float) $price['effective_price'], 0) ?></strong>
                        <?php if ($price['has_discount']): ?><s><?= esc($currency) . number_format((float) $price['price'], 0) ?></s><?php endif; ?>
                    </div>
                    <small>
                        <?= strcasecmp((string) $price['unit'], 'One Time') === 0 ? 'As listed' : 'Per ' . esc($price['unit']) ?>
                        <?php if ($firstPricePurpose !== ''): ?><span class="vl-price__purpose">• <?= esc($firstPricePurpose) ?></span><?php endif; ?>
                    </small>
                <?php else: ?>
                    <strong>Price on request</strong>
                    <small>Contact us for details</small>
                <?php endif; ?>

                <?php if (count($prices) > 1): ?>
                    <button
                        class="vl-price-options-link"
                        type="button"
                        data-pricing-trigger
                        data-property-title="<?= esc($property->title) ?>"
                        data-property-url="<?= esc($url) ?>"
                        data-currency="<?= esc($currency) ?>"
                        data-pricing="<?= $pricingJson ?>"
                    >
                        <i class="fa-solid fa-layer-group"></i> <?= count($prices) ?> pricing options
                    </button>
                <?php endif; ?>
            </div>
            <a class="vl-card-link" href="<?= esc($url) ?>" aria-label="Open property"><i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</article>
