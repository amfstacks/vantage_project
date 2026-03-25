<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= isset($title) ? esc($title) . ' | ' : '' ?><?= esc(config('Site')->siteName) ?></title>


  <meta name="description" content="<?= esc($meta_description ?? 'Discover premium real estate, luxury apartments, and exclusive properties with ' . config('Site')->siteName . '.') ?>">
  <meta name="author" content="Ajala Mayowa Felix">

  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= current_url() ?>">
  <meta property="og:title" content="<?= esc($title ?? config('Site')->siteName) ?>">
  <meta property="og:description" content="<?= esc($meta_description ?? 'Discover premium real estate and exclusive properties.') ?>">
  <meta property="og:image" content="<?= esc($og_image ?? base_url('assets/img/logo/logo1.png')) ?>">

  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="<?= current_url() ?>">
  <meta property="twitter:title" content="<?= esc($title ?? config('Site')->siteName) ?>">
  <meta property="twitter:description" content="<?= esc($meta_description ?? 'Discover premium real estate and exclusive properties.') ?>">
  <meta property="twitter:image" content="<?= esc($og_image ?? base_url('assets/img/logo/logo1.png')) ?>">

  <link rel="shortcut icon" href="<?= base_url('assets/img/logo/fav-logo1.png') ?>" type="image/x-icon">

  <link rel="shortcut icon" href="<?= base_url('assets/img/logo/fav-logo1.png') ?>" type="image/x-icon">

  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/aos.css') ?>">
  <!-- <link rel="stylesheet" href="<?= base_url('assets/css/plugins/fontawesome.css') ?>"> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/magnific-popup.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/mobile.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/owlcarousel.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/sidebar.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/slick-slider.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/nice-select.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/swiper-slider.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">

  <script src="<?= base_url('assets/js/plugins/jquery-3-7-1.min.js') ?>"></script>
</head>

<body class="homepage1-body">

  <div class="preloader">
    <div class="loading-container">
      <div class="loading"></div>
      <div id="loading-icon"><img src="<?= base_url('assets/img/logo/preloader.png') ?>" alt="<?= esc(config('Site')->siteName) ?>"></div>
    </div>
  </div>

  <div class="paginacontainer">
    <div class="progress-wrap">
      <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
      </svg>
    </div>
  </div>

  <div class="header-search-form-wrapper">
    <div class="tx-search-close tx-close"><i class="fa-solid fa-xmark"></i></div>
    <div class="header-search-container">
      <form role="search" action="<?= base_url('properties') ?>" method="GET" class="search-form">
        <input type="search" class="search-field" placeholder="Search properties…" value="" name="q">
        <button type="submit" class="search-submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M13.7955 13.8111L19 19M16 8.5C16 12.6421 12.6421 16 8.5 16C4.35786 16 1 12.6421 1 8.5C1 4.35786 4.35786 1 8.5 1C12.6421 1 16 4.35786 16 8.5Z" stroke="#030E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
      </form>
    </div>
  </div>
  <div class="body-overlay"></div>

  <header>
    <div class="header-area homepage1 header header-sticky d-none d-lg-block " id="header">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="header-top-area">
              <ul class="header-content">
                <li><a href="mailto:info@<?= esc(config('Site')->siteName) ?>.com"><i class="fa-solid fa-envelope"></i> info@<?= esc(config('Site')->siteName) ?>.com</a> <span> | </span></li>
                <li><a href="tel:(234)345-4574"><i class="fa-solid fa-phone"></i> (234) 345-4574</a></li>
              </ul>
              <ul class="list-content">
                <li>
                  <a href="<?= base_url('admin/dashboard') ?>" class="signin"><span> | </span>
                    <i class="fa-solid fa-user text-white"></i> SIGN IN
                  </a>
                </li>
              </ul>
            </div>
            <div class="header-elements">
              <div class="site-logo">
                <a href="<?= base_url() ?>">
                <?= view('components/logo', ['width' => '180px', 'class' => 'img-fluid']) ?>  
                <!-- <img src="<?= base_url('assets/img/logo/logo1.png') ?>" alt="<?= esc(config('Site')->siteName) ?>"></a> -->
              </div>
              <div class="main-menu">
                <ul>
                  <li><a href="<?= base_url() ?>">Home</a></li>
                  <li><a href="<?= base_url('properties') ?>">Properties</a></li>
                  <li><a href="<?= base_url('about') ?>">About Us</a></li>
                  <li><a href="<?= base_url('contact') ?>">Contact</a></li>
                </ul>
              </div>
              <div class="btn-area">
                <div class="search-icon header__search header-search-btn">
                  <a href="#"><i class="fa-solid fa-search"></i></a>
                </div>
                <a href="<?= base_url('properties') ?>" class="theme-btn1">View Listings <span class="arrow1"><i class="fa-solid fa-arrow-right"></i></span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="mobile-header mobile-header1 d-block d-lg-none">
    <div class="container-fluid">
      <div class="col-12">
        <div class="mobile-header-elements">
          <div class="mobile-logo">
            <a href="<?= base_url() ?>"><img src="<?= base_url('assets/img/logo/logo1.png') ?>" alt="<?= esc(config('Site')->siteName) ?>"></a>
          </div>
          <div class="mobile-right d-flex gap-1 align-items-center">
            <a class="circle-button user-icon" href="<?= base_url('admin/dashboard') ?>"><i class="fa-solid fa-user"></i></a>
            <div class="mobile-nav-icon dots-menu"><i class="fa-solid fa-bars"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="mobile-sidebar mobile-sidebar1">
    <div class="logosicon-area">
      <div class="logos">
        <img src="<?= base_url('assets/img/logo/logo1.png') ?>" alt="<?= esc(config('Site')->siteName) ?>">
      </div>
      <div class="menu-close"><i class="fa-solid fa-xmark text-2xl"></i></div>
    </div>
    <div class="mobile-nav mobile-nav1">
      <ul class="mobile-nav-list nav-list1">
        <li><a href="<?= base_url() ?>">Home</a></li>
        <li><a href="<?= base_url('properties') ?>">Properties</a></li>
        <li><a href="<?= base_url('about') ?>">About Us</a></li>
        <li><a href="<?= base_url('contact') ?>">Contact</a></li>
        <li><a href="<?= base_url('admin/dashboard') ?>">Login / Admin</a></li>
      </ul>
      <div class="allmobilesection">
        <a href="<?= base_url('contact') ?>" class="theme-btn1">Contact Us</a>
      </div>
    </div>
  </div>

  <?= $this->renderSection('content') ?>
  <div class="footer1-section-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="footer-logoarea">
            <!-- <img src="<?= base_url('assets/img/logo/logo1.png') ?>" alt="<?= esc(config('Site')->siteName) ?>"> -->
            <?= view('components/logo', ['width' => '220px', 'class' => 'footer-brand']) ?>
            <div class="space24"></div>
            <p>Your trusted real estate partner. We simplify the way people find, sell, and invest in properties.</p>
            <div class="space24"></div>
            <ul>
              <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
              <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
              <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
            </ul>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="footer-content">
            <h3>Quick Links</h3>
            <div class="space4"></div>
            <ul>
              <li><a href="<?= base_url() ?>">Home</a></li>
              <li><a href="<?= base_url('properties') ?>">All Properties</a></li>
              <li><a href="<?= base_url('about') ?>">About Us</a></li>
              <li><a href="<?= base_url('contact') ?>">Contact Support</a></li>
            </ul>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="footer-content2">
            <h3>Contact Us</h3>
            <div class="space4"></div>
            <ul>
              <li><a href="#"><i class="fa-solid fa-phone mr-2"></i> <?= esc(config('Site')->contactPhone) ?></a></li>
              <li><a href="#"><i class="fa-solid fa-map-marker-alt mr-2"></i> <?= esc(config('Site')->address) ?></a></li>
              <li><a href="mailto:info@<?= esc(config('Site')->siteName) ?>.com"><i class="fa-solid fa-envelope mr-2"></i> info@<?= esc(config('Site')->siteName) ?>.com</a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="space60"></div>
      <div class="row">
        <div class="col-lg-12">
          <div class="copyright text-center">
            <p>©Copyright <?= date('Y') ?> - <?= esc(config('Site')->siteName) ?> Platform. All Rights Reserved.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?= base_url('assets/js/plugins/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/aos.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/counter.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/gsap.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/ScrollTrigger.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/Splitetext.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/sidebar.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/swiper-slider.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/magnific-popup.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/mobilemenu.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/owlcarousel.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/nice-select.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/waypoints.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/slick-slider.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/circle-progress.js') ?>"></script>
  <!-- <script src="<?= base_url('assets/js/plugins/fontawesome.js') ?>"></script> -->
  <script src="<?= base_url('assets/js/main.js') ?>"></script>

 <style>
      /* Enforce Premium Typography on the Modal */
      #globalAmenitiesModal, 
      #globalAmenitiesModal .modal-title, 
      #globalAmenitiesModal * {
          font-family: inherit !important; /* Forces it to use Outfit/Montserrat */
      }

      /* The Gold Pulsating Button */
      .gold-pulse-btn {
          background-color: transparent;
          color: #D4AF37;
          border: 1px solid #D4AF37;
          border-radius: 6px;
          padding: 8px 16px;
          font-size: 13px;
          font-weight: 700;
          cursor: pointer;
          transition: all 0.3s ease;
          animation: pulse-gold 2s infinite;
          display: inline-flex;
          align-items: center;
          width: 100%;
          justify-content: center;
      }
      .gold-pulse-btn:hover {
          background-color: #D4AF37;
          color: #fff;
          animation: none;
          box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
      }
      @keyframes pulse-gold {
          0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4); }
          70% { box-shadow: 0 0 0 8px rgba(212, 175, 55, 0); }
          100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
      }

      /* Luxury Hover Effect for the Amenity Pills */
      .amenity-pill:hover {
          transform: translateY(-2px);
          box-shadow: 0 5px 15px rgba(0,0,0,0.05);
          border-color: #D4AF37 !important;
      }
  </style>

  <div class="modal fade" id="globalAmenitiesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <div class="modal-content shadow-lg" style="border-radius: 16px; border: none; overflow: hidden;">
        
        <div class="modal-header bg-gray-50" style="border-bottom: 1px solid #eaeaea; padding: 20px 24px;">
          <h4 class="modal-title font-extrabold text-gray-900 d-flex align-items-center" id="amenitiesModalTitle" style="font-size: 1.25rem;">
            <i class="fa-solid fa-gem mr-2" style="color: #D4AF37;"></i> Property Amenities
          </h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="box-shadow: none;"></button>
        </div>
        
        <div class="modal-body p-4" id="amenitiesModalBody" style="max-height: 70vh; overflow-y: auto;">
            </div>
        
      </div>
    </div>
  </div>

  <script>
    function openAmenitiesModal(propertyId, propertyTitle) {
        const modal = new bootstrap.Modal(document.getElementById('globalAmenitiesModal'));
        const modalTitle = document.getElementById('amenitiesModalTitle');
        const modalBody = document.getElementById('amenitiesModalBody');

        // Set title and loader
        modalTitle.innerHTML = `<i class="fa-solid fa-gem mr-2" style="color: #D4AF37;"></i> Features: ${propertyTitle}`;
        modalBody.innerHTML = '<div class="text-center py-5"><i class="fa-solid fa-circle-notch fa-spin fa-3x" style="color: #D4AF37;"></i><p class="mt-3 font-semibold text-gray-500">Loading premium features...</p></div>';
        
        modal.show();

        // Fetch the data
        fetch(`<?= base_url('ajax/get-amenities/') ?>${propertyId}`)
            .then(response => response.json())
            .then(data => {
                modalBody.innerHTML = data.html; 
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = '<div class="text-center py-5 text-danger"><i class="fa-solid fa-triangle-exclamation fa-2x mb-3"></i><br>Failed to load amenities. Please check your connection.</div>';
            });
    }
  </script>
</body>
</html>