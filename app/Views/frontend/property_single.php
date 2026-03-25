<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
// Determine Primary Image for Hero Background
$primaryImage = !empty($images) ? base_url($images[0]->image_path) : base_url('assets/img/all-images/hero/hero-img1.png');
// var_dump($primaryImage);
// exit;
// Format Display Price
$displayPrice = 'Price on Request';
$priceUnit = '';
if (!empty($property->prices)) {
    $priceObj = $property->prices[0];
    $currency = esc(config('Site')->currency);
    
    if (!empty($priceObj->discount_price) && $priceObj->discount_price > 0) {
        $displayPrice = $currency . number_format($priceObj->discount_price);
    } else {
        $displayPrice = $currency . number_format($priceObj->price);
    }
    
    if ($priceObj->price_unit !== 'One Time') {
        $priceUnit = '/' . esc($priceObj->price_unit);
    }
}
?>

<div class="hero-inner-section-area-sidebar position-relative">
  <img src="<?= $primaryImage ?>" alt="<?= esc($property->title) ?>" class="hero-img1" style="width: 100%; height: 100%; object-fit: cover; filter: brightness(0.5);">
  <div class="container position-relative" style="z-index: 2;">
    <div class="row">
      <div class="col-lg-12">
        <div class="hero-header-area text-center">
          <a href="<?= base_url() ?>">Home <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
            </svg> Listing <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
            </svg> <?= esc($property->property_type) ?></a>
          <div class="space24"></div>
          <h1><?= esc($property->title) ?></h1>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="properties-details4-area sp1">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        
        <?php if(!empty($images)): ?>
        <div class="images-area-details">
          <div class="img3-carousel">
            <?php foreach($images as $img): ?>
                <img src="<?= base_url($img->image_path) ?>" alt="<?= esc($property->title) ?>" style="object-fit: cover; height: 500px; border-radius: 8px;">
            <?php endforeach; ?>
          </div>
          
          <?php if(count($images) > 1): ?>
          <div class="space30"></div>
          <div class="img4">
            <?php foreach($images as $img): ?>
                <img src="<?= base_url($img->image_path) ?>" alt="Thumbnail" style="object-fit: cover; height: 120px; border-radius: 8px; cursor: pointer;">
            <?php endforeach; ?>
          </div>

          <div class="testimonial-arrows">
            <div class="prev-arrow">
              <button><i class="fa-solid fa-angle-left"></i></button>
            </div>
            <div class="next-arrow">
              <button><i class="fa-solid fa-angle-right"></i></button>
            </div>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="space80"></div>
        
        <div class="row">
          
          <div class="col-lg-8">
            <div class="details-siderbar">
              
              <div class="content-area">
                <div class="content heading2">
                  <h2><?= esc($property->title) ?></h2>
                  <ul>
                    <li><a href="javascript:void(0)"><?= $displayPrice ?></a></li>
                    <li><a href="javascript:void(0)"><?= $priceUnit ?></a></li>
                  </ul>
                </div>

                <div class="list-area">
                  <div class="list">
                    <ul>
                      <li>Features:</li>
                      <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/bed1.svg') ?>" alt="Beds">x<?= $property->bedrooms ?> <span> | </span></a></li>
                      <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/bath1.svg') ?>" alt="Baths">x<?= $property->bathrooms ?> <span> | </span></a></li>
                      <?php if($property->area_sqm): ?>
                        <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/sqare1.svg') ?>" alt="Area"><?= $property->area_sqm ?> sq</a></li>
                      <?php endif; ?>
                    </ul>
                    <div class="space24"></div>
                    <ul>
                      <li>Location:</li>
                      <li><a href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 23.7279L5.63604 17.364C2.12132 13.8492 2.12132 8.15076 5.63604 4.63604C9.15076 1.12132 14.8492 1.12132 18.364 4.63604C21.8787 8.15076 21.8787 13.8492 18.364 17.364L12 23.7279ZM16.9497 15.9497C19.6834 13.2161 19.6834 8.78392 16.9497 6.05025C14.2161 3.31658 9.78392 3.31658 7.05025 6.05025C4.31658 8.78392 4.31658 13.2161 7.05025 15.9497L12 20.8995L16.9497 15.9497ZM12 13C10.8954 13 10 12.1046 10 11C10 9.89543 10.8954 9 12 9C13.1046 9 14 9.89543 14 11C14 12.1046 13.1046 13 12 13Z"></path></svg> 
                        <?= esc($property->address ?? ($property->location . ', ' . $property->city)) ?>
                      </a></li>
                    </ul>
                  </div>

                  <ul class="share">
                    <li><a href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.001 4.52853C14.35 2.42 17.98 2.49 20.2426 4.75736C22.5053 7.02472 22.583 10.637 20.4786 12.993L11.9999 21.485L3.52138 12.993C1.41705 10.637 1.49571 7.01901 3.75736 4.75736C6.02157 2.49315 9.64519 2.41687 12.001 4.52853ZM18.827 6.1701C17.3279 4.66794 14.9076 4.60701 13.337 6.01687L12.0019 7.21524L10.6661 6.01781C9.09098 4.60597 6.67506 4.66808 5.17157 6.17157C3.68183 7.66131 3.60704 10.0473 4.97993 11.6232L11.9999 18.6543L19.0201 11.6232C20.3935 10.0467 20.319 7.66525 18.827 6.1701Z"></path></svg></a></li>
                  </ul>
                </div>
              </div>

              <div class="space32"></div>

              <div class="bg1">
                <h3>Description</h3>
                <div class="space12"></div>
                <div style="line-height: 1.8; color: #4b5563;">
                    <?= $property->description ?>
                </div>
              </div>
              
              <div class="space60"></div>

              <?php if(!empty($amenities)): ?>
              <div class="bg1">
                <h3>Property Amenities</h3>
                <div class="space12"></div>
                <div class="row">
                  <?php 
                    // Split amenities into two columns
                    $half = ceil(count($amenities) / 2);
                    $col1 = array_slice($amenities, 0, $half);
                    $col2 = array_slice($amenities, $half);
                  ?>
                  
                  <div class="col-lg-6 col-md-6">
                    <?php foreach($col1 as $am): ?>
                    <div class="list-box align-items-center mb-3">
                      <div class="icon d-flex justify-content-center align-items-center" style="width:40px; height:40px; background:#f9f9f9; border-radius:50%; border:1px solid #eee;">
                        <?php $iconClass = !empty($am->icon) ? esc($am->icon) : 'fa-check'; ?>
                        <i class="fa-solid <?= $iconClass ?>" style="color: #D4AF37; font-size:18px;"></i>
                      </div>
                      <div class="text ml-3">
                        <p class="m-0 font-bold"><?= esc($am->name) ?></p>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  </div>

                  <div class="col-lg-6 col-md-6">
                    <?php foreach($col2 as $am): ?>
                    <div class="list-box align-items-center mb-3">
                      <div class="icon d-flex justify-content-center align-items-center" style="width:40px; height:40px; background:#f9f9f9; border-radius:50%; border:1px solid #eee;">
                        <?php $iconClass = !empty($am->icon) ? esc($am->icon) : 'fa-check'; ?>
                        <i class="fa-solid <?= $iconClass ?>" style="color: #D4AF37; font-size:18px;"></i>
                      </div>
                      <div class="text ml-3">
                        <p class="m-0 font-bold"><?= esc($am->name) ?></p>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  </div>

                </div>
              </div>
              <?php endif; ?>

              <?php if(!empty($property->video_url)): ?>
              <div class="space60"></div>
              <div class="bg1">
                <h3>Play Video</h3>
                <div class="space32"></div>
                <div class="vide-images">
                  <div class="img1">
                    <img src="<?= $primaryImage ?>" alt="Video Thumbnail" style="height: 400px; width: 100%; object-fit: cover; filter: brightness(0.6); border-radius: 8px;">
                  </div>
                  <a href="<?= esc($property->video_url) ?>" class="popup-youtube">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M6 20.1957V3.80421C6 3.01878 6.86395 2.53993 7.53 2.95621L20.6432 11.152C21.2699 11.5436 21.2699 12.4563 20.6432 12.848L7.53 21.0437C6.86395 21.46 6 20.9812 6 20.1957Z"></path>
                    </svg>
                  </a>
                </div>
              </div>
              <?php endif; ?>

              <?php if(!empty($property->virtual_tour_url)): ?>
              <div class="space60"></div>
              <div class="bg1">
                <h3>Explore 360° View</h3>
                <div class="space32"></div>
                <div class="rotate-images position-relative">
                  <div class="img1">
                    <img src="<?= $primaryImage ?>" alt="360 View" style="height: 300px; width: 100%; object-fit: cover; filter: brightness(0.7); border-radius: 8px;">
                  </div>
                  <a href="<?= esc($property->virtual_tour_url) ?>" target="_blank" style="z-index: 10;">
                    <img src="<?= base_url('assets/img/icons/rotate.svg') ?>" alt="Rotate">
                  </a>
                </div>
              </div>
              <?php endif; ?>

            </div>
          </div>

          <div class="col-lg-4">
            <div class="all-side-details">
              
              <div class="details-siderbar2">
                <h4>Contact Seller</h4>
                <div class="space24"></div>
                <div class="personal-info">
                  <div class="img1">
                    <?php if(is_object($agent) && !empty($agent->avatar)): ?>
                        <img src="<?= base_url($agent->avatar) ?>" alt="Agent" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: #f1f1f1; display:flex; align-items:center; justify-content:center;">
                            <i class="fa-solid fa-user text-gray-400"></i>
                        </div>
                    <?php endif; ?>
                  </div>
                  <div class="content">
                    <a href="javascript:void(0)"><?= is_object($agent) ? esc($agent->first_name . ' ' . $agent->last_name) : 'Verified Agent' ?></a>
                    <?php if(is_object($agent) && !empty($agent->email)): ?>
                        <a href="mailto:<?= esc($agent->email) ?>"><i class="fa-solid fa-envelope mr-1 text-warning"></i> <?= esc($agent->email) ?></a>
                    <?php endif; ?>
                    <?php if(is_object($agent) && !empty($agent->phone)): ?>
                        <a href="tel:<?= esc($agent->phone) ?>"><i class="fa-solid fa-phone mr-1 text-warning"></i> <?= esc($agent->phone) ?></a>
                    <?php endif; ?>
                  </div>
                </div>
                
                <div class="space10"></div>
                <div class="input-area">
                  <input type="text" placeholder="Full Name">
                </div>
                <div class="input-area">
                  <input type="number" placeholder="Phone Number">
                </div>
                <div class="input-area">
                  <input type="email" placeholder="Email Address">
                </div>
                <div class="input-area">
                  <textarea placeholder="Hi, I am interested in <?= esc($property->title) ?>..."></textarea>
                </div>
                <div class="input-area">
                  <button type="submit" class="theme-btn1">Send Message <span class="arrow1"><i class="fa-solid fa-paper-plane"></i></span><span class="arrow2"><i class="fa-solid fa-paper-plane"></i></span></button>
                </div>
              </div>

              <div class="space30"></div>
              
              <div class="sidebar1-area">
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home1-tab" type="button" role="tab" style="width: 100%;">
                      Find Similar Properties
                    </button>
                  </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                  <div class="tab-pane fade show active" id="pills-home1" role="tabpanel">
                    
                    <form action="<?= base_url('properties') ?>" method="GET">
                        <div class="row">
                          <div class="col-lg-12">
                            
                            <div class="input-area">
                              <input type="text" name="q" placeholder="Type keyword (e.g. Duplex)">
                            </div>
                            
                            <div class="input-area">
                              <input type="text" name="location" placeholder="Location (e.g. Asokoro)">
                            </div>

                            <div class="input-area m-0">
                                <select name="sort" class="country-area nice-select w-100">
                                    <option value="newest">Sort: Newest</option>
                                    <option value="price_low">Price: Low to High</option>
                                    <option value="price_high">Price: High to Low</option>
                                </select>
                            </div>

                          </div>
                        </div>
                        
                        <div class="space32"></div>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="btn-area1">
                                    <button type="submit" class="theme-btn1 border-0" style="width: 100%; cursor:pointer;">Search Properties <span class="arrow1"><i class="fa-solid fa-search"></i></span><span class="arrow2"><i class="fa-solid fa-search"></i></span></button>
                                </div>
                            </div>
                        </div>
                    </form>

                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>