<?php
if (!isset($property)) return; 

$thumbnail = !empty($property->image_path) ? base_url($property->image_path) : base_url('assets/img/all-images/properties/property-img1.png');
$linkUrl = !empty($property->slug) ? base_url('property/' . esc($property->slug)) : base_url('property/' . $property->id);

$displayPrice = 'Price on Request';
if (!empty($property->prices)) {
    $priceObj = $property->prices[0];
    $currency = esc(config('Site')->currency);
    if (!empty($priceObj->discount_price) && $priceObj->discount_price > 0) {
        $displayPrice = $currency . number_format($priceObj->discount_price) . ' <s style="font-size: 13px; color: #a1a1aa; margin-left: 6px;">' . $currency . number_format($priceObj->price) . '</s>';
    } else {
        $displayPrice = $currency . number_format($priceObj->price);
    }
    if ($priceObj->price_unit !== 'One Time') {
        $displayPrice .= ' <span style="font-size:12px; font-weight:normal; color:#666;">/' . esc($priceObj->price_unit) . '</span>';
    }
}
?>

<div class="col-lg-6 mb-4" data-aos="fade-up" data-aos-duration="800">
    <div class="property-boxarea2">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6">
                <div class="img1" style="height: 280px; border-radius: 12px; overflow: hidden;">
                    <a href="<?= $linkUrl ?>" style="display: block; height: 100%;">
                        <img src="<?= $thumbnail ?>" alt="<?= esc($property->title) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </a>
                </div>
            </div>

            <div class="col-lg-6 col-md-6">
                <div class="category-list mt-3 mt-md-0">
                    <ul>
                        <li><a href="javascript:void(0)" style="background: <?= $property->purpose === 'sale' ? '#D4AF37' : ($property->purpose === 'shortlet' ? '#6f42c1' : '#20c997') ?>; color: white;">For <?= ucfirst(esc($property->purpose)) ?></a></li>
                        <li><a href="javascript:void(0)"><?= esc($property->property_type) ?></a></li>
                    </ul>
                </div>
                <div class="content-area mt-3">
                    <a href="<?= $linkUrl ?>" class="text-truncate" style="display: block; font-size: 1.2rem; font-weight: bold;" title="<?= esc($property->title) ?>"><?= esc($property->title) ?></a>
                    <div class="space18"></div>
                    <p><i class="fa-solid fa-location-dot mr-1 text-gray-400"></i> <?= esc($property->location . ', ' . $property->city) ?></p>
                    <div class="space24"></div>
                    <ul>
                        <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/bed1.svg') ?>" alt="Beds">x<?= $property->bedrooms ?></a></li>
                        <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/bath1.svg') ?>" alt="Baths">x<?= $property->bathrooms ?></a></li>
                        <?php if($property->area_sqm): ?>
                            <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/sqare1.svg') ?>" alt="Area"><?= $property->area_sqm ?> sq</a></li>
                        <?php endif; ?>
                    </ul>
                    <div class="btn-area mt-4 d-flex justify-content-between align-items-center">
                        <button type="button" class="gold-pulse-btn" style="padding: 6px 12px; width: auto;" onclick="openAmenitiesModal(<?= $property->id ?>, '<?= esc(addslashes($property->title)) ?>')">
                            <i class="fa-solid fa-sparkles"></i> Amenities
                        </button>
                        <a href="<?= $linkUrl ?>" class="nm-btn"><?= $displayPrice ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>