<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

  <div class="hero-inner-section-area-sidebar position-relative">
    <img src="<?= base_url('assets/img/all-images/hero/hero-img1.png') ?>" alt="<?= esc(config('Site')->siteName) ?>" class="hero-img1" style="width: 100%; height: 100%; object-fit: cover; filter: brightness(0.6);">
    <div class="container position-relative" style="z-index: 2;">
      <div class="row">
        <div class="col-lg-12">
          <div class="hero-header-area text-center text-white">
            <a href="<?= base_url() ?>" class="text-white">Home 
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 16px; height: 16px; margin: 0 8px;">
                <path d="M13.1717 12.0007L8.22192 7.05093L9.63614 5.63672L16.0001 12.0007L9.63614 18.3646L8.22192 16.9504L13.1717 12.0007Z"></path>
              </svg> Contact Us
            </a>
            <div class="space24"></div>
            <h1 class="text-white">Contact Us</h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="contact-inner-section sp1">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8 m-auto text-center">
          <div class="heading1">
            <h5>Contact <?= esc(config('Site')->siteName) ?></h5>
            <div class="space32"></div>
            <h2>Let’s Start the Conversation</h2>
            <div class="space24"></div>
            <p class="text-gray-500" style="line-height: 1.8;">
              At <?= esc(config('Site')->siteNameLong) ?>, we value communication and are here to assist with all your real estate needs. Whether you’re buying, selling, renting, or managing a property, our dedicated team is ready to provide guidance and support. Reach out to us via phone or email—we’re here to make your real estate journey as smooth and successful as possible. Let’s turn your property goals into reality!
            </p>
            <div class="space40"></div>
            
            <div class="number-address-area d-flex justify-content-center flex-wrap gap-4 text-start">
              
              <div class="phone-number m-0 d-flex align-items-center">
                <div class="img1 mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21 16.42V19.9561C21 20.4811 20.5941 20.9167 20.0705 20.9537C19.6331 20.9846 19.2763 21 19 21C10.1634 21 3 13.8366 3 5C3 4.72371 3.01545 4.36687 3.04635 3.9295C3.08337 3.40588 3.51894 3 4.04386 3H7.5801C7.83678 3 8.05176 3.19442 8.07753 3.4498C8.10067 3.67907 8.12218 3.86314 8.14207 4.00202C8.34435 5.41472 8.75753 6.75936 9.3487 8.00303C9.44359 8.20265 9.38171 8.44159 9.20185 8.57006L7.04355 10.1118C8.35752 13.1811 10.8189 15.6425 13.8882 16.9565L15.4271 14.8019C15.5572 14.6199 15.799 14.5573 16.001 14.6532C17.2446 15.2439 18.5891 15.6566 20.0016 15.8584C20.1396 15.8782 20.3225 15.8995 20.5502 15.9225C20.8056 15.9483 21 16.1633 21 16.42Z"></path>
                  </svg>
                </div>
                <div class="content">
                  <p class="m-0 text-muted text-sm">Phone Number</p>
                  <?php 
                    // Safely extract the first number for the clickable tel link
                    $firstPhone = explode(' | ', config('Site')->contactPhone)[0]; 
                    $cleanPhone = preg_replace('/[^0-9+]/', '', $firstPhone);
                  ?>
                  <a href="tel:<?= $cleanPhone ?>" class="font-bold text-dark"><?= esc(config('Site')->contactPhone) ?></a>
                </div>
              </div>

              <div class="phone-number m-0 d-flex align-items-center">
                <div class="img1 mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3ZM12.0606 11.6829L5.64722 6.2377L4.35278 7.7623L12.0731 14.3171L19.6544 7.75616L18.3456 6.24384L12.0606 11.6829Z"></path>
                  </svg>
                </div>
                <div class="content">
                  <p class="m-0 text-muted text-sm">Email Address</p>
                  <a href="mailto:<?= esc(config('Site')->contactEmail) ?>" class="font-bold text-dark"><?= esc(config('Site')->contactEmail) ?></a>
                </div>
              </div>

            </div>

            <div class="space32"></div>
            
            <div class="number-address-area2 d-flex justify-content-center text-start">
              <div class="phone-number m-0 d-flex align-items-center">
                <div class="img1 mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.364 17.364L12 23.7279L5.63604 17.364C2.12132 13.8492 2.12132 8.15076 5.63604 4.63604C9.15076 1.12132 14.8492 1.12132 18.364 4.63604C21.8787 8.15076 21.8787 13.8492 18.364 17.364ZM12 15C14.2091 15 16 13.2091 16 11C16 8.79086 14.2091 7 12 7C9.79086 7 8 8.79086 8 11C8 13.2091 9.79086 15 12 15ZM12 13C10.8954 13 10 12.1046 10 11C10 9.89543 10.8954 9 12 9C13.1046 9 14 9.89543 14 11C14 12.1046 13.1046 13 12 13Z"></path>
                  </svg>
                </div>
                <div class="content">
                  <a href="https://maps.google.com/?q=<?= urlencode(config('Site')->address) ?>" target="_blank" class="font-bold text-dark"><?= esc(config('Site')->address) ?></a>
                </div>
              </div>
            </div>

            <div class="space40"></div>

            <div class="social d-flex justify-content-center">
              <ul class="d-flex gap-3 m-0 p-0" style="list-style: none;">
                <?php if(!empty(config('Site')->facebookUrl)): ?>
                <li><a href="<?= esc(config('Site')->facebookUrl) ?>" target="_blank" class="d-flex align-items-center justify-content-center rounded-circle border hover-shadow transition" style="width: 40px; height: 40px; color: #D4AF37;"><i class="fa-brands fa-facebook-f"></i></a></li>
                <?php endif; ?>
                
                <?php if(!empty(config('Site')->instagramUrl)): ?>
                <li><a href="<?= esc(config('Site')->instagramUrl) ?>" target="_blank" class="d-flex align-items-center justify-content-center rounded-circle border hover-shadow transition" style="width: 40px; height: 40px; color: #D4AF37;"><i class="fa-brands fa-instagram"></i></a></li>
                <?php endif; ?>
              </ul>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <?= $this->endSection() ?>