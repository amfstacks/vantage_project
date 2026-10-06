<!doctype html>
<html lang="en-NG">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#111111">

    <?php
        $site = config('Site');
        $pageTitle = $title ?? $site->defaultTitle;
        $fullTitle = str_contains($pageTitle, $site->siteName) ? $pageTitle : $pageTitle . ' | ' . $site->siteNameLong;
        $description = $meta_description ?? ('Discover premium properties for sale, rent and shortlet in Abuja with ' . $site->siteNameLong . '.');
        $canonical = $canonical_url ?? current_url();
        $socialImage = $og_image ?? base_url($site->logoPath);
        $segment = service('uri')->getSegment(1);
        $whatsapp = clean_phone_digits($site->whatsappNumber);

        $globalSchema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'RealEstateAgent',
                    '@id' => base_url('/') . '#organization',
                    'name' => $site->siteNameLong,
                    'url' => base_url('/'),
                    'logo' => base_url($site->logoPath),
                    'email' => $site->contactEmail,
                    'telephone' => $site->contactPhone,
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => 'Abuja',
                        'addressCountry' => 'NG',
                    ],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => base_url('/') . '#website',
                    'url' => base_url('/'),
                    'name' => $site->siteNameLong,
                    'publisher' => ['@id' => base_url('/') . '#organization'],
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => base_url('properties') . '?q={search_term_string}',
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ];
    ?>

    <title><?= esc($fullTitle) ?></title>
    <meta name="description" content="<?= esc($description) ?>">
    <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
    <link rel="canonical" href="<?= esc($canonical) ?>">

    <meta property="og:locale" content="en_NG">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= esc($site->siteNameLong) ?>">
    <meta property="og:title" content="<?= esc($fullTitle) ?>">
    <meta property="og:description" content="<?= esc($description) ?>">
    <meta property="og:url" content="<?= esc($canonical) ?>">
    <meta property="og:image" content="<?= esc($socialImage) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($fullTitle) ?>">
    <meta name="twitter:description" content="<?= esc($description) ?>">
    <meta name="twitter:image" content="<?= esc($socialImage) ?>">

    <link rel="icon" href="<?= base_url('assets/img/logo/fav-logo1.png') ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/fontawesome.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/vantage-premium.css') ?>?v=3.0.0">

    <script type="application/ld+json"><?= json_encode($globalSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
    <?php if (! empty($structured_data)): ?>
        <?php foreach ($structured_data as $schema): ?>
            <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
        <?php endforeach; ?>
    <?php endif; ?>
    <?= $this->renderSection('head') ?>
</head>
<body>
    <div class="vl-topbar">
        <div class="vl-container vl-topbar__inner">
            <div class="vl-topbar__left">
                <a href="mailto:<?= esc($site->contactEmail) ?>"><i class="fa-regular fa-envelope"></i> <?= esc($site->contactEmail) ?></a>
                <span><i class="fa-solid fa-location-dot"></i> <?= esc($site->address) ?></span>
            </div>
            <div class="vl-topbar__right">
                <span>Premium property advisory</span>
                <a href="tel:<?= esc(clean_phone_digits($site->contactPhone)) ?>"><i class="fa-solid fa-phone"></i> <?= esc($site->contactPhone) ?></a>
            </div>
        </div>
    </div>

    <header class="vl-header">
        <div class="vl-container vl-nav">
            <a class="vl-logo" href="<?= base_url('/') ?>" aria-label="<?= esc($site->siteNameLong) ?> home">
                <img src="<?= base_url($site->logoPath) ?>" alt="<?= esc($site->siteNameLong) ?>" width="190" height="68">
            </a>

            <nav aria-label="Primary navigation">
                <ul class="vl-menu">
                    <li><a class="<?= $segment === '' ? 'active' : '' ?>" href="<?= base_url('/') ?>">Home</a></li>
                    <li><a class="<?= $segment === 'properties' || $segment === 'property' ? 'active' : '' ?>" href="<?= base_url('properties') ?>">Properties</a></li>
                    <li><a class="<?= $segment === 'about' ? 'active' : '' ?>" href="<?= base_url('about') ?>">About</a></li>
                    <li><a class="<?= $segment === 'contact' ? 'active' : '' ?>" href="<?= base_url('contact') ?>">Contact</a></li>
                </ul>
            </nav>

            <div class="vl-nav__actions">
                <a class="vl-icon-btn" href="<?= base_url('properties') ?>" aria-label="Search properties"><i class="fa-solid fa-magnifying-glass"></i></a>
                <a class="vl-btn vl-btn--dark" href="<?= base_url('properties') ?>">Explore listings <i class="fa-solid fa-arrow-right"></i></a>
                <button type="button" class="vl-icon-btn vl-mobile-toggle" data-mobile-toggle aria-expanded="false" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
        <div class="vl-mobile-panel" data-mobile-panel>
            <a href="<?= base_url('/') ?>">Home</a>
            <a href="<?= base_url('properties') ?>">Properties</a>
            <a href="<?= base_url('about') ?>">About</a>
            <a href="<?= base_url('contact') ?>">Contact</a>
            <a class="vl-btn vl-btn--gold" href="<?= base_url('properties') ?>">Explore listings</a>
        </div>
    </header>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <div class="vl-pricing-modal" data-pricing-modal aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="pricing-modal-title">
        <button class="vl-pricing-modal__backdrop" type="button" data-pricing-close aria-label="Close pricing options"></button>
        <div class="vl-pricing-modal__sheet">
            <div class="vl-pricing-modal__head">
                <div><span class="vl-kicker">Pricing options</span><h2 id="pricing-modal-title" data-pricing-title>Available prices</h2></div>
                <button class="vl-modal-icon-btn" type="button" data-pricing-close aria-label="Close pricing options"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="vl-pricing-modal__body" data-pricing-body></div>
            <a class="vl-btn vl-btn--dark vl-btn--wide" href="#" data-pricing-property-link>View full property <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>

    <footer class="vl-footer">
        <div class="vl-container">
            <div class="vl-footer-grid">
                <div>
                    <img class="vl-footer-logo" src="<?= base_url($site->logoPath) ?>" alt="<?= esc($site->siteNameLong) ?>">
                    <p><?= esc($site->tagline) ?> We help clients discover, evaluate and enquire about quality properties with a clear, modern experience from first search to first conversation.</p>
                </div>
                <div>
                    <h4>Explore</h4>
                    <ul>
                        <li><a href="<?= base_url('properties') ?>">All properties</a></li>
                        <li><a href="<?= base_url('properties?purpose=sale') ?>">Properties for sale</a></li>
                        <li><a href="<?= base_url('properties?purpose=rent') ?>">Properties for rent</a></li>
                        <li><a href="<?= base_url('properties?purpose=shortlet') ?>">Shortlets</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Contact</h4>
                    <ul>
                        <li><a href="tel:<?= esc(clean_phone_digits($site->contactPhone)) ?>"><?= esc($site->contactPhone) ?></a></li>
                        <li><a href="mailto:<?= esc($site->contactEmail) ?>"><?= esc($site->contactEmail) ?></a></li>
                        <li><?= esc($site->address) ?></li>
                        <li><a href="<?= base_url('contact') ?>">Contact our team</a></li>
                    </ul>
                </div>
            </div>
            <div class="vl-footer-bottom">
                <span>© <?= date('Y') ?> <?= esc($site->siteNameLong) ?>. All rights reserved.</span>
                <span>Luxury real estate, built around clarity and trust.</span>
            </div>
        </div>
    </footer>

    <?php if ($whatsapp !== ''): ?>
        <a class="vl-whatsapp-float" href="https://wa.me/<?= esc($whatsapp) ?>" target="_blank" rel="noopener" aria-label="Chat with <?= esc($site->siteName) ?> on WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    <?php endif; ?>

    <script src="<?= base_url('assets/js/plugins/bootstrap.min.js') ?>" defer></script>
    <script src="<?= base_url('assets/js/vantage-premium.js') ?>?v=3.0.0" defer></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
