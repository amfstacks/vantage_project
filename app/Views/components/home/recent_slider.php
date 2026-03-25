<?php if (empty($recentProperties)) return; ?>

<div class="items2-section-area sp1" style="background-image: url('<?= base_url('assets/img/all-images/bg/bg2.png') ?>'); background-position: center; background-repeat: no-repeat; background-size: cover; background-attachment: fixed;">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 m-auto">
          <div class="item-header heading1 text-center space-margin60">
            <h5>Fresh on the Market</h5>
            <div class="space20"></div>
            <h2 class="text-anime-style-3">Recently Uploaded Properties</h2>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="items-images-area position-relative">
          <div class="row">
            
            <div class="col-lg-5">
              <div class="images-area ajax-recent-images-slider">
                <?php foreach($recentProperties as $prop): ?>
                    <?php $thumbnail = !empty($prop->image_path) ? base_url($prop->image_path) : base_url('assets/img/all-images/properties/property-img1.png'); ?>
                    <div class="img1 image-anime" style="height: 450px;">
                        <img src="<?= $thumbnail ?>" alt="<?= esc($prop->title) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                    </div>
                <?php endforeach; ?>
              </div>
            </div>

            <div class="col-lg-7">
              <div class="slider-content-area ajax-recent-content-slider">
                <?php foreach($recentProperties as $prop): ?>
                    <?php 
                        $linkUrl = !empty($prop->slug) ? base_url('property/' . esc($prop->slug)) : base_url('property/' . $prop->id);
                        $displayPrice = 'Price on Request';
                        if (!empty($prop->prices)) {
                            $priceObj = $prop->prices[0];
                            $currency = esc(config('Site')->currency);
                            if (!empty($priceObj->discount_price) && $priceObj->discount_price > 0) {
                                $displayPrice = $currency . number_format($priceObj->discount_price) . ' <s style="font-size: 14px; color: #a1a1aa; margin-left: 8px;">' . $currency . number_format($priceObj->price) . '</s>';
                            } else {
                                $displayPrice = $currency . number_format($priceObj->price);
                            }
                            if ($priceObj->price_unit !== 'One Time') $displayPrice .= ' <span style="font-size:14px; color:#666;">/' . esc($priceObj->price_unit) . '</span>';
                        }
                    ?>
                    
                    <div class="items-content-area px-lg-4">
                      <div class="category-list">
                        <ul>
                          <li><a href="javascript:void(0)" style="background: <?= $prop->purpose === 'sale' ? '#D4AF37' : ($prop->purpose === 'shortlet' ? '#6f42c1' : '#20c997') ?>; color: white;">For <?= ucfirst(esc($prop->purpose)) ?></a></li>
                          <li><a href="javascript:void(0)"><?= esc($prop->property_type) ?></a></li>
                        </ul>
                      </div>
                      <div class="content-area">
                        <div class="space24"></div>
                        <a href="<?= $linkUrl ?>" class="text-truncate" style="display:block; max-width:100%; font-size: 1.5rem; font-weight: bold;"><?= esc($prop->title) ?></a>
                        <div class="space20"></div>
                        <p><i class="fa-solid fa-location-dot text-gray-400 mr-2"></i> <?= esc($prop->address ?? ($prop->location . ', ' . $prop->city)) ?></p>
                        <div class="space24"></div>
                        <ul>
                          <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/bed1.svg') ?>" alt="Beds"> x<?= $prop->bedrooms ?> Beds</a></li>
                          <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/bath1.svg') ?>" alt="Baths"> x<?= $prop->bathrooms ?> Baths</a></li>
                          <?php if($prop->area_sqm): ?>
                              <li><a href="javascript:void(0)"><img src="<?= base_url('assets/img/icons/sqare1.svg') ?>" alt="Area"> <?= $prop->area_sqm ?> sqm</a></li>
                          <?php endif; ?>
                        </ul>
                        <div class="space32"></div>
                        
                        <div class="btn-area d-flex align-items-center justify-content-between">
                          <div class="name-area d-flex align-items-center">
                            <!-- <div class="img mr-3">
                              <div style="width: 50px; height: 50px; border-radius: 50%; background: #f1f1f1; display:flex; align-items:center; justify-content:center; border: 2px solid #D4AF37;">
                                  <i class="fa-solid fa-user-tie text-gray-500"></i>
                              </div>
                            </div> -->
                             <button type="button" class="gold-pulse-btn mr-3" onclick="openAmenitiesModal(<?= $prop->id ?>, '<?= esc(addslashes($prop->title)) ?>')">
                                <i class="fa-solid fa-sparkles mr-2"></i> View Amenities
                            </button>
                            <div class="text">
                              <!-- <a href="javascript:void(0)" class="font-bold">Verified Agent</a> -->
                            </div>
                          </div>
                          
                          <div class="btn-area">
                           
                            <a href="<?= $linkUrl ?>" class="nm-btn"><?= $displayPrice ?></a>
                          </div>
                        </div>

                      </div>
                    </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <div class="testimonial-arrows d-flex gap-2 position-absolute" style="bottom: 0; right: 15px;">
            <div class="recent-prev-arrow cursor-pointer" style="width: 45px; height: 45px; background: #fff; border-radius: 50%; display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); color: #D4AF37;">
              <i class="fa-solid fa-angle-left"></i>
            </div>
            <div class="recent-next-arrow cursor-pointer" style="width: 45px; height: 45px; background: #D4AF37; border-radius: 50%; display:flex; align-items:center; justify-content:center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); color: #fff;">
              <i class="fa-solid fa-angle-right"></i>
            </div>
          </div>
          
        </div>
      </div>
    </div>
</div>