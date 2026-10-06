<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$heroProperty = $featuredProperties[0] ?? null;
$heroImage = $heroProperty ? property_image_url($heroProperty->image_path ?? null) : base_url('assets/img/all-images/hero/hero-img1.png');
?>

<section class="vl-hero vl-hero--cinematic">
    <img class="vl-hero__media" src="<?= esc($heroImage) ?>" alt="Luxury property in Abuja" fetchpriority="high">
    <div class="vl-hero__glow" aria-hidden="true"></div>
    <div class="vl-container vl-hero__content">
        <span class="vl-hero__eyebrow"><i class="fa-solid fa-gem"></i> Premium real estate in Abuja</span>
        <h1>Find a property that feels <em>worth arriving at.</em></h1>
        <p class="vl-hero__copy">Vantage Luxe Realty brings premium homes, apartments, land and shortlets into one refined search experience — with clear property details, quality media and direct enquiry support.</p>
        <div class="d-flex flex-wrap gap-2 mt-4">
            <a class="vl-btn vl-btn--gold" href="<?= base_url('properties') ?>">Explore properties <i class="fa-solid fa-arrow-right"></i></a>
            <a class="vl-btn vl-btn--ghost" href="<?= base_url('contact') ?>">Speak with our team</a>
        </div>
        <div class="vl-hero__stats">
            <div class="vl-stat"><strong><?= number_format((int) $activeCount) ?>+</strong><span>Active listings</span></div>
            <div class="vl-stat"><strong><?= number_format((int) $saleCount) ?>+</strong><span>For sale</span></div>
            <div class="vl-stat"><strong><?= number_format((int) $shortletCount) ?>+</strong><span>Shortlet options</span></div>
        </div>
    </div>
    <div class="vl-scroll-cue" aria-hidden="true"><span></span> Discover</div>
</section>

<!-- Non-blocking property finder: compact until the visitor asks for it. -->
<div class="vl-search-dock" data-search-dock>
    <button class="vl-search-dock__trigger" type="button" data-search-dock-trigger aria-expanded="false" aria-controls="homePropertyFinder">
        <span class="vl-search-dock__icon"><i class="fa-solid fa-magnifying-glass"></i></span>
        <span><small>Property finder</small><strong>What are you looking for?</strong></span>
        <i class="fa-solid fa-chevron-up vl-search-dock__chevron"></i>
    </button>

    <div class="vl-search-dock__panel" id="homePropertyFinder" data-search-dock-panel aria-hidden="true">
        <div class="vl-search-dock__head">
            <div><span class="vl-kicker">Find your fit</span><h2>Search the collection</h2></div>
            <button type="button" class="vl-modal-icon-btn" data-search-dock-close aria-label="Close property finder"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form class="vl-property-finder" action="<?= base_url('properties') ?>" method="get" novalidate>
            <div class="vl-field">
                <label for="home-location">Location</label>
                <select id="home-location" name="location" data-search-select data-search-placeholder="Search locations…">
                    <option value="">Any location</option>
                    <?php foreach ($locations as $location): ?>
                        <option value="<?= esc($location->location) ?>"><?= esc($location->location) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="vl-field">
                <label for="home-purpose">Purpose</label>
                <select id="home-purpose" name="purpose" data-search-select data-search-placeholder="Search purposes…">
                    <option value="">Any purpose</option>
                    <?php foreach ($purposes as $purpose): ?>
                        <option value="<?= esc($purpose->slug) ?>"><?= esc($purpose->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="vl-field">
                <label for="home-type">Property type</label>
                <select id="home-type" name="type" data-search-select data-search-placeholder="Search property types…">
                    <option value="">Any property type</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= esc($type->slug) ?>"><?= esc($type->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="vl-btn vl-btn--gold vl-btn--wide" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Show matching properties</button>
            <p class="vl-finder-note"><i class="fa-solid fa-circle-info"></i> Leave any option untouched to see the full collection.</p>
        </form>
    </div>
</div>


<section class="vl-section vl-section--cream">
    <div class="vl-container">
        <div class="vl-section-head">
            <div class="vl-section-head__copy">
                <span class="vl-kicker">Fresh opportunities</span>
                <h2 class="vl-title">Recently added properties</h2>
                <p class="vl-lead">A curated look at the latest homes and investment opportunities currently available through Vantage Luxe Realty.</p>
            </div>
            <a class="vl-btn vl-btn--light" href="<?= base_url('properties') ?>">View all listings <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="vl-grid">
            <?php if (empty($featuredProperties)): ?>
                <div class="vl-empty"><i class="fa-solid fa-building"></i><h3>New listings are being prepared</h3><p class="vl-muted">Please check back shortly.</p></div>
            <?php else: ?>
                <?php foreach ($featuredProperties as $property): ?>
                    <?= view('components/property_card', ['property' => $property]) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="vl-section vl-dark-section vl-location-section">
    <div class="vl-container">
        <div class="vl-section-head">
            <div class="vl-section-head__copy">
                <span class="vl-kicker">Browse by area</span>
                <h2 class="vl-title">Explore where you want to live or invest</h2>
                <p class="vl-lead vl-lead--dark">Every neighbourhood below is represented by real imagery from an active listing in that location.</p>
            </div>
            <a class="vl-text-link vl-text-link--light" href="<?= base_url('properties') ?>">Explore every location <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="vl-location-grid vl-location-grid--visual">
            <?php if (empty($locations)): ?>
                <a class="vl-location-card vl-location-card--visual" href="<?= base_url('properties') ?>">
                    <img src="<?= base_url('assets/img/all-images/hero/hero-img1.png') ?>" alt="Properties in Abuja" loading="lazy">
                    <span class="vl-location-card__overlay"></span>
                    <span class="vl-location-card__content"><strong>Abuja</strong><small>Explore available properties <i class="fa-solid fa-arrow-right"></i></small></span>
                </a>
            <?php else: ?>
                <?php foreach ($locations as $location): ?>
                    <?php $locationImage = property_image_url($location->image_path ?? null); ?>
                    <a class="vl-location-card vl-location-card--visual" href="<?= base_url('properties?location=' . urlencode($location->location)) ?>" data-reveal>
                        <img src="<?= esc($locationImage) ?>" alt="Property in <?= esc($location->location) ?>" loading="lazy" decoding="async">
                        <span class="vl-location-card__overlay"></span>
                        <span class="vl-location-card__content">
                            <strong><?= esc($location->location) ?></strong>
                            <small><?= number_format((int) $location->property_count) ?> active listing<?= (int) $location->property_count === 1 ? '' : 's' ?> <i class="fa-solid fa-arrow-right"></i></small>
                        </span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="vl-section" style="background:#faf9f7">
    <div class="vl-container">
        <div class="vl-section-head">
            <div class="vl-section-head__copy">
                <span class="vl-kicker">A better property journey</span>
                <h2 class="vl-title">Built for clarity before commitment</h2>
            </div>
        </div>
        <div class="vl-trust-grid">
            <article class="vl-trust-card" data-reveal>
                <div class="vl-trust-card__icon"><i class="fa-solid fa-images"></i></div>
                <h3>Media-first listings</h3>
                <p>Property photography is presented cleanly and YouTube walkthroughs appear as proper embedded video experiences when supplied.</p>
            </article>
            <article class="vl-trust-card" data-reveal>
                <div class="vl-trust-card__icon"><i class="fa-solid fa-filter"></i></div>
                <h3>Fast, focused discovery</h3>
                <p>Search and filtering are designed to help you narrow down location, purpose, property type, bedrooms and budget without unnecessary friction.</p>
            </article>
            <article class="vl-trust-card" data-reveal>
                <div class="vl-trust-card__icon"><i class="fa-brands fa-whatsapp"></i></div>
                <h3>One-step enquiry</h3>
                <p>Your enquiry is saved first, then prepared for WhatsApp with the property link and request details so the conversation can continue immediately.</p>
            </article>
        </div>
    </div>
</section>

<section class="vl-section-sm">
    <div class="vl-container">
        <div class="vl-premium-cta" data-reveal>
            <div>
                <span class="vl-kicker">Ready to move?</span>
                <h2 class="vl-title">Let the right property be easier to find.</h2>
                <p>Browse the full catalogue or tell us what you are looking for and let our team guide the next step.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a class="vl-btn vl-btn--gold" href="<?= base_url('properties') ?>">Browse properties</a>
                <a class="vl-btn vl-btn--ghost" href="<?= base_url('contact') ?>">Contact us</a>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
