<?php
// Safety check: ensure we have a property object
if (!isset($property)) return; 

// Determine the primary image or use a placeholder
$thumbnail = !empty($property->image_path) ? base_url($property->image_path) : base_url('assets/img/all-images/properties/property-img1.png');

// Get the first available price
$displayPrice = 'Price on Request';
if (!empty($property->prices)) {
    $priceObj = $property->prices[0];
    $displayPrice = esc(config('Site')->currency) . number_format($priceObj->price);
    if ($priceObj->price_unit !== 'One Time') {
        $displayPrice .= ' <span style="font-size:12px; font-weight:normal; color:#666;">/' . esc($priceObj->price_unit) . '</span>';
    }
}
?>

<div class="property-boxarea" data-aos="fade-up" data-aos-duration="800">
    <div class="img1 image-anime" style="height: 250px; overflow: hidden;">
        <a href="<?= base_url('property/' . $property->id) ?>">
            <img src="<?= $thumbnail ?>" alt="<?= esc($property->title) ?>" style="width: 100%; height: 100%; object-fit: cover;">
        </a>
    </div>
    
    <div class="category-list">
        <ul>
            <li>
                <a href="javascript:void(0)" style="background: <?= $property->purpose === 'sale' ? '#007bff' : ($property->purpose === 'shortlet' ? '#6f42c1' : '#20c997') ?>; color: white;">
                    For <?= ucfirst(esc($property->purpose)) ?>
                </a>
            </li>
            <li><a href="javascript:void(0)"><?= esc($property->property_type) ?></a></li>
        </ul>
    </div>
    
    <div class="content-area">
        <a href="<?= base_url('property/' . $property->id) ?>" class="text-truncate" style="display: block; max-width: 100%;" title="<?= esc($property->title) ?>">
            <?= esc($property->title) ?>
        </a>
        <div class="space18"></div>
        <p><i class="fa-solid fa-location-dot mr-1 text-gray-400"></i> <?= esc($property->location . ', ' . $property->city) ?></p>
        <div class="space24"></div>
        
        <ul>
            <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/bed1.svg') ?>" alt="Beds"> x<?= $property->bedrooms ?></a></li>
            <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/bath1.svg') ?>" alt="Baths"> x<?= $property->bathrooms ?></a></li>
            <?php if($property->area_sqm): ?>
                <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/sqare1.svg') ?>" alt="Area"> <?= $property->area_sqm ?> sqm</a></li>
            <?php endif; ?>
        </ul>
        
        <div class="btn-area">
            <a href="<?= base_url('property/' . $property->id) ?>" class="nm-btn"><?= $displayPrice ?></a>
        </div>
    </div>
</div>