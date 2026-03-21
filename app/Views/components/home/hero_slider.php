<?php
/**
 * DRY Hero Slider Component
 * Dynamically uses uploaded property images as backgrounds.
 */

// Define our dynamic marketing text for the 2 slides
$marketingContent = [
    [
        'subtitle' => 'Premium Real Estate Platform',
        'title'    => 'Find Your Dream Home For Sale or Rent',
    ],
    [
        'subtitle' => 'Discover Your Ideal Property Today',
        'title'    => 'Explore Luxury Shortlets & Apartments',
    ]
];

$slides = [];
$slideCount = 0;

// Grab up to 2 images from the properties passed from the Home Controller
if (isset($featuredProperties) && !empty($featuredProperties)) {
    foreach ($featuredProperties as $prop) {
        if ($slideCount >= 2) break; // We only want 2 slides
        
        if (!empty($prop->image_path)) {
            $slides[] = [
                'image'    => base_url($prop->image_path),
                'subtitle' => $marketingContent[$slideCount]['subtitle'],
                'title'    => $marketingContent[$slideCount]['title'],
                // Button 1 links to the specific property in the background!
                'link'     => base_url('property/' . $prop->id) 
            ];
            $slideCount++;
        }
    }
}

// BULLETPROOF FALLBACK: If the database has less than 2 properties with images, 
// fill the remaining slots with the default template images so the slider never breaks.
while (count($slides) < 2) {
    $idx = count($slides);
    $slides[] = [
        'image'    => base_url('assets/img/all-images/hero/hero-img' . ($idx + 1) . '.png'),
        'subtitle' => $marketingContent[$idx]['subtitle'],
        'title'    => $marketingContent[$idx]['title'],
        'link'     => base_url('properties')
    ];
}
?>

<div class="hero-area-slider">
    <?php foreach($slides as $slide): ?>
        <div class="hero1-section-area">
            
            <img src="<?= esc($slide['image']) ?>" alt="<?= esc(config('Site')->siteName) ?> Property" class="hero-img1" style="object-fit: cover; filter: brightness(0.6);">
            
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="hero-header-area text-center">
                            <h5><?= esc($slide['subtitle']) ?></h5>
                            <div class="space32"></div>
                            <h1><?= esc($slide['title']) ?></h1>
                            <div class="space40"></div>
                            
                            <div class="btn-area1">
                                <a href="<?= esc($slide['link']) ?>" class="theme-btn1">
                                    View This Property <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
                                </a>
                                <a href="<?= base_url('properties') ?>" class="theme-btn2">
                                    Browse All Listings <i class="fa-solid fa-building ml-2 text-sm"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>