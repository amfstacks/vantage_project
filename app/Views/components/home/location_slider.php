<?php if (empty($locationsData)) return; ?>

<div class="property-location-section-area sp1">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 m-auto">
          <div class="property-headeing heading1 space-margin60 text-center">
            <h5>Property Locations</h5>
            <div class="space20"></div>
            <h2 class="text-anime-style-3">Explore Our Top Destinations</h2>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12"> 
          <div class="property-single-slider owl-carousel ajax-loc-carousel">
            
            <?php foreach($locationsData as $loc): ?>
                <div class="propety-single-boxarea" style="cursor: pointer;" onclick="window.location.href='<?= esc($loc['url']) ?>'">
                  
                  <div class="img1" style="height: 400px; border-radius: 12px; overflow: hidden; visibility: visible; opacity: 1;">
                    <img src="<?= esc($loc['image']) ?>" alt="<?= esc($loc['name']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                  </div>
                  
                  <h3><?= esc($loc['count']) ?></h3>
                  <a href="<?= esc($loc['url']) ?>"><?= esc($loc['name']) ?></a>
                  
                </div>
            <?php endforeach; ?>

          </div>
        </div>
      </div>
    </div>
</div>