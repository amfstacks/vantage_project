<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc(config('Site')->siteName) ?></title>

  <link rel="shortcut icon" href="<?= base_url('assets/img/logo/fav-logo1.png') ?>" type="image/x-icon">

  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/aos.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/plugins/fontawesome.css') ?>">
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
      <form role="search" action="<?= base_url('search') ?>" method="GET" class="search-form">
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
                    <i class="fa-solid fa-user text-white"></i> Login / Portal
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
              <li><a href="tel:(234)345-4574"><i class="fa-solid fa-phone mr-2"></i> (234) 345-4574</a></li>
              <li><a href="#"><i class="fa-solid fa-map-marker-alt mr-2"></i> 123 Real Estate Ave, Abuja, Nigeria</a></li>
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
  <script src="<?= base_url('assets/js/main.js') ?>"></script>

</body>
</html>