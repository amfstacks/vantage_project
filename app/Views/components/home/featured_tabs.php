<?php
if (empty($tabsData)) return;

function getCategoryIcon($cat) {
    $cat = strtolower($cat);
    if (in_array($cat, ['sale', 'for sale'])) return 'fa-tags';
    if (in_array($cat, ['rent', 'for rent'])) return 'fa-key';
    if (in_array($cat, ['shortlet', 'short stay'])) return 'fa-suitcase-rolling';
    if (str_contains($cat, 'apartment') || str_contains($cat, 'flat')) return 'fa-building';
    if (str_contains($cat, 'villa') || str_contains($cat, 'mansion')) return 'fa-house-chimney-window';
    if (str_contains($cat, 'land') || str_contains($cat, 'plot')) return 'fa-map-location-dot';
    return 'fa-house';
}

function formatCategoryName($cat) {
    if ($cat === 'sale') return 'For Sale';
    if ($cat === 'rent') return 'For Rent';
    return ucwords($cat);
}
?>

<div class="tabs-btn-area space-margin60" data-aos="fade-up" data-aos-duration="1000">
    <ul class="nav nav-pills justify-content-center" id="featured-pills-tab" role="tablist">
        <?php $i = 0; foreach($tabsData as $category => $properties): ?>
            <?php $tabId = 'pills-' . url_title($category, '-', true); ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?= $i === 0 ? 'active' : '' ?>" 
                        id="<?= $tabId ?>-tab" 
                        data-bs-toggle="pill" 
                        data-bs-target="#<?= $tabId ?>" 
                        type="button" 
                        role="tab" 
                        aria-controls="<?= $tabId ?>" 
                        aria-selected="<?= $i === 0 ? 'true' : 'false' ?>">
                    <i class="fa-solid <?= getCategoryIcon($category) ?> mr-2"></i>
                    <?= esc(formatCategoryName($category)) ?>
                </button>
            </li>
        <?php $i++; endforeach; ?>
    </ul>
</div>

<div class="tab-content" id="pills-tabContent" data-aos="fade-up" data-aos-duration="1000">
    <?php $i = 0; foreach($tabsData as $category => $properties): ?>
        <?php $tabId = 'pills-' . url_title($category, '-', true); ?>
        
        <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>" 
             id="<?= $tabId ?>" 
             role="tabpanel" 
             aria-labelledby="<?= $tabId ?>-tab" 
             tabindex="0">
            
            <div class="row">
                <?php if(empty($properties)): ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No properties found in this category.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($properties as $prop): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <?= view('components/property_card', ['property' => $prop]) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="<?= base_url('properties?filter=' . urlencode($category)) ?>" class="theme-btn1">
                        View All <?= esc(formatCategoryName($category)) ?> Properties <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>

        </div>
    <?php $i++; endforeach; ?>
</div>