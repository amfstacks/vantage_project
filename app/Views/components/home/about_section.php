<?php
/**
 * DRY About Section Component
 * Dynamically borrows images from the properties array.
 */

// 1. Set the default fallback images
$aboutImages = [
    base_url('assets/img/all-images/about/about-img2.png'), // The tall image
    base_url('assets/img/all-images/about/about-img1.png')  // The wide image
];

// 2. Try to grab 2 unique images from the $properties array
if (isset($properties) && is_array($properties)) {
    $validImages = [];
    foreach ($properties as $prop) {
        if (!empty($prop->image_path)) {
            $validImages[] = base_url($prop->image_path);
        }
    }

    // 3. Shuffle the deck and pick any two random images!
    if (!empty($validImages)) {
        shuffle($validImages); 
        if (isset($validImages[0])) $aboutImages[0] = $validImages[0];
        if (isset($validImages[1])) $aboutImages[1] = $validImages[1];
    }
}
?>

<div class="about1-section-area sp1">
    <div class="container">
        <div class="row align-items-center">
            
            <div class="col-lg-6">
                <div class="about-images-area">
                    <div class="img2 image-anime reveal">
                        <img src="<?= esc($aboutImages[0]) ?>" alt="<?= esc(config('Site')->siteName) ?> Property" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="img1 image-anime reveal">
                        <img src="<?= esc($aboutImages[1]) ?>" alt="<?= esc(config('Site')->siteName) ?> Interior" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="author-img aniamtion-key-1">
                        <h3><small>Luxury. Lifestyle. Legacy</small></h3>
                        <!-- <div class="space18"></div> -->
                    </div>
                </div>
            </div>
            
            <div class="col-lg-1"></div>
            
            <div class="col-lg-5">
                <div class="about-heading heading1">
                    <h5 data-aos="fade-left" data-aos-duration="800">About <?= esc(config('Site')->siteName) ?></h5>
                    <div class="space20"></div>
                    <h2 class="text-anime-style-3">Embrace the Elegance of Our Exclusive Properties</h2>
                    <div class="space18"></div>
                    <p data-aos="fade-left" data-aos-duration="900"><?= esc(config('Site')->siteNameLong) ?>is a premier, registered, and licensed real estate and lifestyle
development company headquartered in Abuja, Nigeria. <br>We specialize in luxury
residential, commercial, and investment real estate solutions, along with construction,
interior design, décor, general contracting, and investment consulting ... </p>
                    <div class="space32"></div>
                    
                    <div class="counter-boxes" data-aos="fade-left" data-aos-duration="1000">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-6">
                                <div class="counter-boxarea text-center">
                                    <h2><span class="counter">10</span>K+</h2>
                                    <div class="space12"></div>
                                    <p>Homes Sold</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-6">
                                <div class="counter-boxarea text-center">
                                    <h2><span class="counter">9</span>K+</h2>
                                    <div class="space12"></div>
                                    <p>Happy Clients</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-6">
                                <div class="space20 d-md-none d-block"></div>
                                <div class="counter-boxarea text-center">
                                    <h2><span class="counter">98</span>%</h2>
                                    <div class="space12"></div>
                                    <p>Satisfaction</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space32"></div>
                    
                    <div class="btn-area1" data-aos="fade-left" data-aos-duration="1100">
                        <a href="<?= base_url('about') ?>" class="theme-btn1">
                            Read more about us <i class="fa-solid fa-arrow-right ml-2 text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>