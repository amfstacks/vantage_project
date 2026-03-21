<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?= view('components/home/hero_slider', ['featuredProperties' => $featuredProperties]) ?>

<!-- <div class="hero-area-slider">
  <div class="hero1-section-area">
    <img src="<?= base_url('assets/img/all-images/hero/hero-img1.png') ?>" alt="<?= esc(config('Site')->siteName) ?>" class="hero-img1">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="hero-header-area text-center">
            <h5>Discover Your Ideal Property Today!</h5>
            <div class="space32"></div>
            <h1>Find Your Perfect Home</h1>
            <div class="space40"></div>
            <div class="btn-area1">
              <a href="<?= base_url('properties') ?>" class="theme-btn2">View Listings <i class="fa-solid fa-arrow-right ml-2"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="properties-section-area sp2" style="background-color: #f9fafb;">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto">
        <div class="property-heading heading1 text-center space-margin60">
          <h5>Recent Listings</h5>
          <div class="space20"></div>
          <h2 class="text-anime-style-3">Explore Our Latest Properties</h2>
        </div>
      </div>
    </div>

    <div class="row">
        <?php if(empty($featuredProperties)): ?>
            <div class="col-12 text-center py-5">
                <h3 class="text-muted">No properties available yet.</h3>
                <p>Check back soon as we update our listings.</p>
            </div>
        <?php else: ?>
            <?php foreach($featuredProperties as $property): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <?= view('components/property_card', ['property' => $property]) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="<?= base_url('properties') ?>" class="theme-btn1">View All Properties <i class="fa-solid fa-arrow-right ml-2"></i></a>
        </div>
    </div>

  </div>
</div> -->

 <div class="testimonial-arrows">
    <div class="testimonial-prev-arrow">
      <button><i class="fa-solid fa-angle-left"></i></button>
    </div>
    <div class="testimonial-next-arrow">
      <button><i class="fa-solid fa-angle-right"></i></button>
    </div>
  </div>


<!-- //serach form  -->
  <div class="others-section-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="theme-btn1 open-search-filter-form">
            <p class="open-text">Open Search Form
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M18.031 16.6168L22.3137 20.8995L20.8995 22.3137L16.6168 18.031C15.0769 19.263 13.124 20 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2C15.968 2 20 6.032 20 11C20 13.124 19.263 15.0769 18.031 16.6168ZM16.0247 15.8748C17.2475 14.6146 18 12.8956 18 11C18 7.1325 14.8675 4 11 4C7.1325 4 4 7.1325 4 11C4 14.8675 7.1325 18 11 18C12.8956 18 14.6146 17.2475 15.8748 16.0247L16.0247 15.8748Z"></path>
              </svg>
            </p>
            <p class="close-text">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M10.5859 12L2.79297 4.20706L4.20718 2.79285L12.0001 10.5857L19.793 2.79285L21.2072 4.20706L13.4143 12L21.2072 19.7928L19.793 21.2071L12.0001 13.4142L4.20718 21.2071L2.79297 19.7928L10.5859 12Z"></path>
              </svg>
              Close
            </p>
          </div>
          <div class="property-tab-section search-filter-form">
            <div class="tab-header">
              <button class="tab-btn active" data-tab="for-sale">For Sale</button>
              <button class="tab-btn" data-tab="for-rent">For Rent</button>
            </div>

            <div class="tab-content1" id="for-sale">
              <div class="filters">
                <div class="filter-group">
                  <label>Status</label>
                  <select>
                    <option>All Status</option>
                    <option>For Rent</option>
                    <option>For Sale</option>
                  </select>
                </div>
                <div class="filter-group">
                  <label>Labels</label>
                  <select>
                    <option>All Labels</option>
                    <option>New Offer</option>
                    <option>Hot Offer</option>
                  </select>
                </div>
                <div class="filter-group">
                  <label>Types</label>
                  <select>
                    <option>All Types</option>
                    <option>Apartment</option>
                    <option>Bar</option>
                    <option>Cafe</option>
                    <option>House</option>
                    <option>Farm</option>
                    <option>Luxury Homes</option>
                    <option>Office</option>
                    <option>Single Family</option>
                    <option>Store</option>
                    <option>Villa</option>
                  </select>
                </div>
                <div class="filter-group">
                  <label for="customize-sale">Customize</label>
                  <button id="customize-sale" class="customize-sale show-form">
                    Advance <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6.17071 18C6.58254 16.8348 7.69378 16 9 16C10.3062 16 11.4175 16.8348 11.8293 18H22V20H11.8293C11.4175 21.1652 10.3062 22 9 22C7.69378 22 6.58254 21.1652 6.17071 20H2V18H6.17071ZM12.1707 11C12.5825 9.83481 13.6938 9 15 9C16.3062 9 17.4175 9.83481 17.8293 11H22V13H17.8293C17.4175 14.1652 16.3062 15 15 15C13.6938 15 12.5825 14.1652 12.1707 13H2V11H12.1707ZM6.17071 4C6.58254 2.83481 7.69378 2 9 2C10.3062 2 11.4175 2.83481 11.8293 4H22V6H11.8293C11.4175 7.16519 10.3062 8 9 8C7.69378 8 6.58254 7.16519 6.17071 6H2V4H6.17071Z"></path>
                      </svg></span>
                  </button>
                </div>
                <div class="search-button">
                  <button id="search-sale">Search <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M18.031 16.6168L22.3137 20.8995L20.8995 22.3137L16.6168 18.031C15.0769 19.263 13.124 20 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2C15.968 2 20 6.032 20 11C20 13.124 19.263 15.0769 18.031 16.6168ZM16.0247 15.8748C17.2475 14.6146 18 12.8956 18 11C18 7.1325 14.8675 4 11 4C7.1325 4 4 7.1325 4 11C4 14.8675 7.1325 18 11 18C12.8956 18 14.6146 17.2475 15.8748 16.0247L16.0247 15.8748Z"></path>
                    </svg></button>
                </div>
              </div>
            </div>

            <div class="tab-content1" id="for-rent" style="display: none;">
              <div class="filters">
                <div class="filter-group">
                  <label>Status</label>
                  <select>
                    <option>All Status</option>
                    <option>For Rent</option>
                    <option>For Sale</option>
                  </select>
                </div>
                <div class="filter-group">
                  <label>Labels</label>
                  <select>
                    <option>All Labels</option>
                    <option>New Offer</option>
                    <option>Hot Offer</option>
                  </select>
                </div>
                <div class="filter-group">
                  <label>Types</label>
                  <select>
                    <option>All Types</option>
                    <option>Apartment</option>
                    <option>Bar</option>
                    <option>Cafe</option>
                    <option>House</option>
                    <option>Farm</option>
                    <option>Luxury Homes</option>
                    <option>Office</option>
                    <option>Single Family</option>
                    <option>Store</option>
                    <option>Villa</option>
                  </select>
                </div>
                <div class="filter-group">
                  <label for="customize-sale">Customize</label>
                  <button id="customize-sale1" class="customize-sale show-form">
                    Advance <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6.17071 18C6.58254 16.8348 7.69378 16 9 16C10.3062 16 11.4175 16.8348 11.8293 18H22V20H11.8293C11.4175 21.1652 10.3062 22 9 22C7.69378 22 6.58254 21.1652 6.17071 20H2V18H6.17071ZM12.1707 11C12.5825 9.83481 13.6938 9 15 9C16.3062 9 17.4175 9.83481 17.8293 11H22V13H17.8293C17.4175 14.1652 16.3062 15 15 15C13.6938 15 12.5825 14.1652 12.1707 13H2V11H12.1707ZM6.17071 4C6.58254 2.83481 7.69378 2 9 2C10.3062 2 11.4175 2.83481 11.8293 4H22V6H11.8293C11.4175 7.16519 10.3062 8 9 8C7.69378 8 6.58254 7.16519 6.17071 6H2V4H6.17071Z"></path>
                      </svg></span>
                  </button>
                </div>
                <div class="search-button">
                  <button id="search-sale1">Search <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M18.031 16.6168L22.3137 20.8995L20.8995 22.3137L16.6168 18.031C15.0769 19.263 13.124 20 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2C15.968 2 20 6.032 20 11C20 13.124 19.263 15.0769 18.031 16.6168ZM16.0247 15.8748C17.2475 14.6146 18 12.8956 18 11C18 7.1325 14.8675 4 11 4C7.1325 4 4 7.1325 4 11C4 14.8675 7.1325 18 11 18C12.8956 18 14.6146 17.2475 15.8748 16.0247L16.0247 15.8748Z"></path>
                    </svg></button>
                </div>
              </div>
            </div>

            <div class="wd-search-form ">
              <div class=" group-select">
                <div class="box-select">
                  <h5>Bathrooms</h5>
                  <div class="nice-select" tabindex="0">
                    <span class="current">Bathrooms</span>
                    <ul class="list">
                      <li data-value="1" class="option">1</li>
                      <li data-value="2" class="option selected">2</li>
                      <li data-value="3" class="option">3</li>
                      <li data-value="4" class="option">4</li>
                      <li data-value="5" class="option">5</li>
                      <li data-value="6" class="option">6</li>
                      <li data-value="7" class="option">7</li>
                      <li data-value="8" class="option">8</li>
                      <li data-value="9" class="option">9</li>
                      <li data-value="10" class="option">10</li>
                    </ul>
                  </div>
                </div>
                <div class="box-select">
                  <h5>Bedrooms</h5>
                  <div class="nice-select" tabindex="0">
                    <span class="current">Bedrooms</span>
                    <ul class="list">
                      <li data-value="1" class="option">1</li>
                      <li data-value="2" class="option selected">2</li>
                      <li data-value="3" class="option">3</li>
                      <li data-value="4" class="option">4</li>
                      <li data-value="5" class="option">5</li>
                      <li data-value="6" class="option">6</li>
                      <li data-value="7" class="option">7</li>
                      <li data-value="8" class="option">8</li>
                      <li data-value="9" class="option">9</li>
                      <li data-value="10" class="option">10</li>
                    </ul>
                  </div>
                </div>
                <div class="box-select">
                  <h5>States</h5>
                  <div class="nice-select" tabindex="0">
                    <span class="current">All States</span>
                    <ul class="list">
                      <li data-value="1" class="option">New York</li>
                      <li data-value="2" class="option selected">California</li>
                      <li data-value="3" class="option">Texas</li>
                      <li data-value="4" class="option">Sydney</li>
                    </ul>
                  </div>
                </div>
                <div class="box-select">
                  <h5>City</h5>
                  <div class="nice-select" tabindex="0">
                    <span class="current">All Cities</span>
                    <ul class="list">
                      <li data-value="1" class="option">Alice</li>
                      <li data-value="2" class="option selected">Bridgaport</li>
                      <li data-value="3" class="option">Dallas</li>
                      <li data-value="4" class="option">Kingston</li>
                      <li data-value="5" class="option">Los Angeles</li>
                      <li data-value="6" class="option">New York</li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class=" group-select">
                <div class="box-select">
                  <h5>Garages</h5>
                  <div class="nice-select" tabindex="0">
                    <span class="current">Any Garages</span>
                    <ul class="list">
                      <li data-value="1" class="option">1</li>
                      <li data-value="2" class="option selected">2</li>
                      <li data-value="3" class="option">3</li>
                      <li data-value="4" class="option">4</li>
                      <li data-value="5" class="option">5</li>
                      <li data-value="6" class="option">6</li>
                      <li data-value="7" class="option">7</li>
                      <li data-value="8" class="option">8</li>
                      <li data-value="9" class="option">9</li>
                      <li data-value="10" class="option">10</li>
                    </ul>
                  </div>
                </div>
                <div class="box-select">
                  <h5>Rooms</h5>
                  <div class="nice-select" tabindex="0">
                    <span class="current">Any Rooms</span>
                    <ul class="list">
                      <li data-value="1" class="option">1</li>
                      <li data-value="2" class="option selected">2</li>
                      <li data-value="3" class="option">3</li>
                      <li data-value="4" class="option">4</li>
                      <li data-value="5" class="option">5</li>
                      <li data-value="6" class="option">6</li>
                      <li data-value="7" class="option">7</li>
                      <li data-value="8" class="option">8</li>
                      <li data-value="9" class="option">9</li>
                      <li data-value="10" class="option">10</li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="group-price">
                <div class="slider-item">
                  <div class="slider-label">Price Range: <span id="price-output">$200 - $2,500,000</span></div>
                  <div class="slider price-slider">
                    <input type="range" id="price-range-min" class="range-min" min="200" max="2500000" value="200" step="100">
                    <input type="range" id="price-range-max" class="range-max" min="200" max="2500000" value="2500000" step="100">
                    <div class="slider-fill"></div>
                  </div>
                </div>

                <div class="slider-item">
                  <div class="slider-label">Size Range: <span id="size-output">146 SqFt - 448 SqFt</span></div>
                  <div class="slider size-slider">
                    <input type="range" id="size-range-min" class="range-min" min="146" max="448" value="146" step="1">
                    <input type="range" id="size-range-max" class="range-max" min="146" max="448" value="448" step="1">
                    <div class="slider-fill"></div>
                  </div>
                </div>
              </div>
              <div class="group-checkbox">
                <div class=" title text-4 fw-6">Others Features</div>
                <div class="space16"></div>
                <div class="group-amenities ">
                  <fieldset class="checkbox-item style-1  ">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4">Air Conditioning</span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4"> Laundry</span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4">Refrigerator </span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4">Washer </span>
                    </label>
                  </fieldset>

                  <fieldset class="checkbox-item style-1  ">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4"> Barbeque</span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4"> Lawn</span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4">Sauna </span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4">Wifi </span>
                    </label>
                  </fieldset>

                  <fieldset class="checkbox-item style-1  ">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4">Dryer </span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4">Microwave</span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4"> Swimming Pool</span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4">Window Coverings</span>
                    </label>
                  </fieldset>

                  <fieldset class="checkbox-item style-1  ">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4"> Gym</span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4">Outdoor Shower </span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4"> TV Cable</span>
                    </label>
                  </fieldset>
                  <fieldset class="checkbox-item style-1   mt-12">
                    <label>
                      <input type="checkbox">
                      <span class="btn-checkbox"></span>
                      <span class="text-4">Fireplace </span>
                    </label>
                  </fieldset>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



   <!--===== ABOUT AREA STARTS =======-->
 <?= view('components/home/about_section', ['properties' => $featuredProperties]) ?>



 <div class="properties-section-area sp2" style="background-image: url('<?= base_url('assets/img/all-images/bg/bg1.png') ?>'); background-position: center; background-repeat: no-repeat; background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="property-heading heading1 text-center space-margin60">
                    <h5>Featured Collections</h5>
                    <div class="space20"></div>
                    <h2 class="text-anime-style-3">Explore Our Portfolio</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="property-feature-slider">
                    <div class="col-lg-12 m-auto" id="ajax-featured-container">
                        
                        <div class="text-center py-5 my-5">
                            <i class="fa-solid fa-circle-notch fa-spin fa-3x" style="color: #D4AF37;"></i>
                            <h5 class="mt-4" style="color: #555;">Curating premium properties...</h5>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('ajax-featured-container');
    
    // Fetch all tabs and their 10 properties instantly in the background
    fetch('<?= base_url('ajax/load-featured-tabs') ?>')
        .then(response => response.json())
        .then(data => {
            // Inject the complete HTML payload
            container.innerHTML = data.html;
        })
        .catch(error => {
            console.error('Failed to load featured properties:', error);
            container.innerHTML = '<div class="text-center py-5 text-danger">Failed to load collections. Please refresh the page.</div>';
        });
});
</script>


  


  <!--===== ITEMS AREA STARTS =======-->
  <div id="ajax-recent-properties-container">
    <div class="py-5 my-5 text-center">
        <i class="fa-solid fa-circle-notch fa-spin fa-3x" style="color: #D4AF37;"></i>
        <p class="mt-3 font-semibold text-gray-500">Loading newest market additions...</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recentContainer = document.getElementById('ajax-recent-properties-container');
    
    fetch('<?= base_url('ajax/load-recent-properties') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                recentContainer.innerHTML = data.html;
                
                // Initialize the Slick Sliders immediately after the HTML is injected into the DOM
                if (typeof $.fn.slick !== 'undefined') {
                    // Left Image Slider
                    $('.ajax-recent-images-slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                        fade: true,
                        asNavFor: '.ajax-recent-content-slider'
                    });
                    
                    // Right Content Slider
                    $('.ajax-recent-content-slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        asNavFor: '.ajax-recent-images-slider',
                        dots: false,
                        prevArrow: $('.recent-prev-arrow'),
                        nextArrow: $('.recent-next-arrow'),
                        focusOnSelect: true,
                        autoplay: true,
                        autoplaySpeed: 5000,
                    });
                }
            } else {
                recentContainer.innerHTML = ''; // Hide if no properties
            }
        })
        .catch(error => {
            console.error('Error fetching recent properties:', error);
            recentContainer.innerHTML = '';
        });
});
</script>


  <!--===== PROPERTY-LOCATION AREA STARTS =======-->
  <div class="property-location-section-area sp1">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 m-auto">
          <div class="property-headeing heading1 space-margin60 text-center">
            <h5>property location</h5>
            <div class="space20"></div>
            <h2 class="text-anime-style-3">Explore Our Property Location</h2>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12" data-aos="fade-up" data-aos-duration="1000">
          <div class="property-single-slider owl-carousel">
            <div class="propety-single-boxarea">
              <div class="img1 image-anime">
                <img src="assets/img/all-images/property_location/property-img1.png" alt="<?= esc(config('Site')->siteName) ?>">
              </div>
              <h3>32</h3>
              <a href="property-details-v1.html">San Francisco</a>
            </div>

            <div class="propety-single-boxarea">
              <div class="img1 image-anime">
                <img src="assets/img/all-images/property_location/property-img2.png" alt="<?= esc(config('Site')->siteName) ?>">
              </div>
              <h3>12</h3>
              <a href="property-details-v1.html">Los Angeles</a>
            </div>

            <div class="propety-single-boxarea">
              <div class="img1 image-anime">
                <img src="assets/img/all-images/property_location/property-img3.png" alt="<?= esc(config('Site')->siteName) ?>">
              </div>
              <h3>15</h3>
              <a href="property-details-v1.html">New York</a>
            </div>

            <div class="propety-single-boxarea">
              <div class="img1 image-anime">
                <img src="assets/img/all-images/property_location/property-img4.png" alt="<?= esc(config('Site')->siteName) ?>">
              </div>
              <h3>40</h3>
              <a href="property-details-v1.html">San Diego</a>
            </div>

            <div class="propety-single-boxarea">
              <div class="img1 image-anime">
                <img src="assets/img/all-images/property_location/property-img5.png" alt="<?= esc(config('Site')->siteName) ?>">
              </div>
              <h3>19</h3>
              <a href="property-details-v1.html">Dallas Texas</a>
            </div>
            <div class="propety-single-boxarea">
              <div class="img1 image-anime">
                <img src="assets/img/all-images/property_location/property-img1.png" alt="<?= esc(config('Site')->siteName) ?>">
              </div>
              <h3>32</h3>
              <a href="property-details-v1.html">San Francisco</a>
            </div>

            <div class="propety-single-boxarea">
              <div class="img1 image-anime">
                <img src="assets/img/all-images/property_location/property-img2.png" alt="<?= esc(config('Site')->siteName) ?>">
              </div>
              <h3>12</h3>
              <a href="property-details-v1.html">Los Angeles</a>
            </div>

            <div class="propety-single-boxarea">
              <div class="img1 image-anime">
                <img src="assets/img/all-images/property_location/property-img3.png" alt="<?= esc(config('Site')->siteName) ?>">
              </div>
              <h3>15</h3>
              <a href="property-details-v1.html">New York</a>
            </div>

            <div class="propety-single-boxarea">
              <div class="img1 image-anime">
                <img src="assets/img/all-images/property_location/property-img4.png" alt="<?= esc(config('Site')->siteName) ?>">
              </div>
              <h3>40</h3>
              <a href="property-details-v1.html">San Diego</a>
            </div>

            <div class="propety-single-boxarea">
              <div class="img1 image-anime">
                <img src="assets/img/all-images/property_location/property-img5.png" alt="<?= esc(config('Site')->siteName) ?>">
              </div>
              <h3>19</h3>
              <a href="property-details-v1.html">Dallas Texas</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


   <!--===== CTA AREA STARTS =======-->
  <div class="cta1-section-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="cta-bg-area" style="background-image: url(assets/img/all-images/bg/cta-bg1.png); background-position: center; background-repeat: no-repeat; background-size: cover;">
            <div class="row align-items-center">
              <div class="col-lg-5">
                <div class="cta-header">
                  <h2 class="text-anime-style-3">Step Into Your Dream Home with <?= esc(config('Site')->siteName) ?></h2>
                  <div class="space16"></div>
                  <p data-aos="fade-left" data-aos-duration="1000">At <?= esc(config('Site')->siteName) ?>, we believe your next home is more than just a place – it’s where your future begins you’re buy.</p>
                </div>
              </div>
              <div class="col-lg-2"></div>
              <div class="col-lg-5" data-aos="zoom-in" data-aos-duration="1000">
                <div class="btn-area1 text-center">
                  <a href="sidebar-grid.html" class="theme-btn1">Find Your Dream Home <span class="arrow1"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                        <path d="M12 13H4V11H12V4L20 12L12 20V13Z"></path>
                      </svg></span><span class="arrow2"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                        <path d="M12 13H4V11H12V4L20 12L12 20V13Z"></path>
                      </svg></span></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


<?= $this->endSection() ?>