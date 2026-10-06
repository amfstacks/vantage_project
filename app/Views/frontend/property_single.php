<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$locationText = trim((string) $property->location . (! empty($property->city) ? ', ' . $property->city : ''));
$priceData = property_price_data($property);
$purposeLabel = trim((string) ($property->purpose_name ?? '')) ?: ucfirst((string) $property->purpose);
$typeLabel = trim((string) ($property->property_type_name ?? '')) ?: (string) $property->property_type;
$firstImage = ! empty($images) ? property_image_url($images[0]->image_path) : property_image_url(null);
$requestMessage = 'Hi, I am interested in ' . $property->title . '. Please share more details.';
$currency = (string) config('Site')->currency;

$displayPrices = (! empty($property->prices) && is_array($property->prices)) ? $property->prices : [];
if ($displayPrices === [] && isset($property->price) && (float) $property->price > 0) {
    $displayPrices[] = (object) [
        'price' => (float) $property->price,
        'discount_price' => $property->discount_price ?? null,
        'discount_percentage' => null,
        'price_unit' => $property->price_unit ?? 'One Time',
        'purpose_name' => null,
    ];
}

$galleryItems = [];
if (! empty($images)) {
    foreach ($images as $index => $image) {
        $galleryItems[] = [
            'url' => property_image_url($image->image_path),
            'alt' => $property->title . ' - image ' . ($index + 1),
        ];
    }
} else {
    $galleryItems[] = ['url' => $firstImage, 'alt' => $property->title];
}
?>

<section class="vl-detail vl-detail--premium">
    <div class="vl-container">
        <nav class="vl-breadcrumb" aria-label="Breadcrumb">
            <a href="<?= base_url('/') ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
            <a href="<?= base_url('properties') ?>">Properties</a><i class="fa-solid fa-chevron-right"></i>
            <span><?= esc(property_reference($property)) ?></span>
        </nav>

        <header class="vl-detail-head vl-detail-head--premium">
            <div class="vl-detail-head__copy">
                <div class="vl-detail-badges">
                    <?php if ($purposeLabel !== ''): ?><span class="vl-badge vl-badge--gold"><?= esc($purposeLabel) ?></span><?php endif; ?>
                    <?php if ($typeLabel !== ''): ?><span class="vl-badge vl-badge--dark"><?= esc($typeLabel) ?></span><?php endif; ?>
                    <span class="vl-detail-ref"><i class="fa-solid fa-hashtag"></i> <?= esc(property_reference($property)) ?></span>
                </div>
                <h1><?= esc($property->title) ?></h1>
                <div class="vl-detail-location"><i class="fa-solid fa-location-dot"></i> <?= esc($locationText ?: 'Abuja') ?><?php if (! empty($property->address)): ?><span>•</span> <?= esc($property->address) ?><?php endif; ?></div>
            </div>
            <div class="vl-detail-price vl-detail-price--premium">
                <small><?= count($displayPrices) > 1 ? 'Starting from' : 'Listed price' ?></small>
                <strong><?= esc(property_price_text($property)) ?></strong>
                <?php if ($priceData['has_discount']): ?><div class="vl-detail-price__was">Was <s><?= esc($currency) . number_format((float) $priceData['price'], 0) ?></s></div><?php endif; ?>
                <?php if (count($displayPrices) > 1): ?><a href="#property-pricing"><?= count($displayPrices) ?> pricing options <i class="fa-solid fa-arrow-down"></i></a><?php endif; ?>
            </div>
        </header>

        <div class="vl-property-media" data-reveal>
            <button class="vl-property-media__main" type="button" data-gallery-open="0" aria-label="Open full property gallery">
                <img src="<?= esc($firstImage) ?>" alt="<?= esc($property->title) ?>" fetchpriority="high">
                <span class="vl-property-media__shade"></span>
                <span class="vl-property-media__photo-count"><i class="fa-regular fa-images"></i> <?= count($galleryItems) ?> photo<?= count($galleryItems) === 1 ? '' : 's' ?></span>
                <span class="vl-property-media__zoom"><i class="fa-solid fa-expand"></i></span>
            </button>

            <?php if (count($galleryItems) > 1): ?>
                <div class="vl-property-thumbnails" aria-label="Property image thumbnails">
                    <?php foreach ($galleryItems as $index => $item): ?>
                        <button type="button" class="vl-property-thumb <?= $index === 0 ? 'is-active' : '' ?>" data-gallery-open="<?= $index ?>" aria-label="Open image <?= $index + 1 ?> of <?= count($galleryItems) ?>">
                            <img src="<?= esc($item['url']) ?>" alt="<?= esc($item['alt']) ?>" loading="lazy" decoding="async">
                            <?php if ($index === 5 && count($galleryItems) > 6): ?><span>+<?= count($galleryItems) - 6 ?></span><?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="vl-property-media__actions">
                <button class="vl-media-action" type="button" data-gallery-open="0"><i class="fa-regular fa-images"></i> View all photos</button>
                <?php if ($youtubeEmbed): ?>
                    <button class="vl-media-action vl-media-action--video" type="button" data-video-open><i class="fa-solid fa-play"></i> View video walkthrough</button>
                <?php endif; ?>
                <?php if (! empty($property->virtual_tour_url)): ?>
                    <a class="vl-media-action" href="<?= esc($property->virtual_tour_url) ?>" target="_blank" rel="noopener"><i class="fa-solid fa-vr-cardboard"></i> Virtual tour</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="vl-detail-grid vl-detail-grid--premium">
            <div class="vl-detail-main">
                <section class="vl-panel vl-panel--elevated" data-reveal>
                    <div class="vl-panel-heading"><div><span class="vl-kicker">At a glance</span><h2>Property overview</h2></div></div>
                    <div class="vl-keyfacts vl-keyfacts--premium">
                        <div class="vl-keyfact"><i class="fa-solid fa-bed"></i><div><strong><?= (int) $property->bedrooms ?: '—' ?></strong><span>Bedrooms</span></div></div>
                        <div class="vl-keyfact"><i class="fa-solid fa-bath"></i><div><strong><?= (int) $property->bathrooms ?: '—' ?></strong><span>Bathrooms</span></div></div>
                        <div class="vl-keyfact"><i class="fa-solid fa-toilet"></i><div><strong><?= (int) $property->toilets ?: '—' ?></strong><span>Toilets</span></div></div>
                        <div class="vl-keyfact"><i class="fa-solid fa-ruler-combined"></i><div><strong><?= ! empty($property->area_sqm) ? esc(rtrim(rtrim(number_format((float) $property->area_sqm, 2), '0'), '.')) . ' m²' : '—' ?></strong><span>Area</span></div></div>
                    </div>
                </section>

                <?php if ($displayPrices !== []): ?>
                    <section class="vl-panel vl-panel--elevated" id="property-pricing" data-reveal>
                        <div class="vl-panel-heading">
                            <div><span class="vl-kicker">Flexible options</span><h2>Pricing</h2></div>
                            <span class="vl-panel-count"><?= count($displayPrices) ?> option<?= count($displayPrices) === 1 ? '' : 's' ?></span>
                        </div>
                        <div class="vl-pricing-list">
                            <?php foreach ($displayPrices as $index => $option): ?>
                                <?php
                                $regular = (float) ($option->price ?? 0);
                                $discount = isset($option->discount_price) && $option->discount_price !== null ? (float) $option->discount_price : 0;
                                $hasDiscount = $discount > 0 && $regular > 0 && $discount < $regular;
                                $effective = $hasDiscount ? $discount : $regular;
                                $percentage = isset($option->discount_percentage) && (float) $option->discount_percentage > 0
                                    ? (float) $option->discount_percentage
                                    : ($hasDiscount && $regular > 0 ? (($regular - $discount) / $regular) * 100 : 0);
                                $unit = trim((string) ($option->price_unit ?? 'One Time')) ?: 'One Time';
                                $optionPurpose = trim((string) ($option->purpose_name ?? ''));
                                ?>
                                <article class="vl-pricing-option">
                                    <div class="vl-pricing-option__index"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></div>
                                    <div class="vl-pricing-option__body">
                                        <div class="vl-pricing-option__tags">
                                            <?php if ($optionPurpose !== ''): ?><span><?= esc($optionPurpose) ?></span><?php endif; ?>
                                            <span><?= strcasecmp($unit, 'One Time') === 0 ? 'One-time price' : 'Per ' . esc($unit) ?></span>
                                            <?php if ($percentage > 0): ?><span class="vl-pricing-option__discount"><?= esc(rtrim(rtrim(number_format($percentage, 2), '0'), '.')) ?>% off</span><?php endif; ?>
                                        </div>
                                        <div class="vl-pricing-option__amount">
                                            <strong><?= esc($currency) . number_format($effective, 0) ?></strong>
                                            <?php if ($hasDiscount): ?><s><?= esc($currency) . number_format($regular, 0) ?></s><?php endif; ?>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <section class="vl-panel vl-panel--elevated" data-reveal>
                    <div class="vl-panel-heading"><div><span class="vl-kicker">The details</span><h2>About this property</h2></div></div>
                    <div class="vl-description vl-description--premium"><?= property_safe_html($property->description) ?></div>
                </section>

                <section class="vl-panel vl-panel--elevated" data-reveal>
                    <div class="vl-panel-heading"><div><span class="vl-kicker">Listing information</span><h2>Property details</h2></div></div>
                    <dl class="vl-property-facts">
                        <div><dt>Reference</dt><dd><?= esc(property_reference($property)) ?></dd></div>
                        <div><dt>Purpose</dt><dd><?= esc($purposeLabel ?: 'Not specified') ?></dd></div>
                        <div><dt>Property type</dt><dd><?= esc($typeLabel ?: 'Not specified') ?></dd></div>
                        <div><dt>Location</dt><dd><?= esc($property->location ?: 'Not specified') ?></dd></div>
                        <div><dt>City</dt><dd><?= esc($property->city ?: 'Not specified') ?></dd></div>
                        <div><dt>Address</dt><dd><?= esc($property->address ?: 'Available on request') ?></dd></div>
                    </dl>
                </section>

                <?php if (! empty($amenities)): ?>
                    <section class="vl-panel vl-panel--elevated" data-reveal>
                        <div class="vl-panel-heading"><div><span class="vl-kicker">Included</span><h2>Features & amenities</h2></div><span class="vl-panel-count"><?= count($amenities) ?></span></div>
                        <div class="vl-amenities vl-amenities--premium">
                            <?php foreach ($amenities as $amenity): ?>
                                <div class="vl-amenity"><span class="vl-amenity__icon"><i class="fa-solid <?= esc($amenity->icon ?: 'fa-check') ?>"></i></span><span><?= esc($amenity->name) ?></span></div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ($youtubeEmbed): ?>
                    <section class="vl-panel vl-panel--elevated" data-reveal>
                        <div class="vl-panel-heading">
                            <div><span class="vl-kicker">Watch the space</span><h2>Video walkthrough</h2></div>
                            <button class="vl-text-link" type="button" data-video-open>Full screen <i class="fa-solid fa-expand"></i></button>
                        </div>
                        <div class="vl-video vl-video--premium">
                            <iframe src="<?= esc($youtubeEmbed) ?>" title="Video tour of <?= esc($property->title) ?>" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if (! empty($property->virtual_tour_url)): ?>
                    <section class="vl-panel vl-panel--tour" data-reveal>
                        <div><span class="vl-kicker">Immersive viewing</span><h2>Virtual tour available</h2><p>Explore the property in an interactive viewing experience.</p></div>
                        <a class="vl-btn vl-btn--dark" href="<?= esc($property->virtual_tour_url) ?>" target="_blank" rel="noopener">Launch virtual tour <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                    </section>
                <?php endif; ?>
            </div>

            <aside class="vl-sticky vl-detail-aside">
                <div class="vl-property-contact-card">
                    <span class="vl-kicker">Private enquiry</span>
                    <h2>Want to know more?</h2>
                    <p>Ask a question, request a call or schedule a viewing. Your request is saved before WhatsApp opens.</p>
                    <div class="vl-property-contact-card__price">
                        <small><?= count($displayPrices) > 1 ? 'From' : 'Price' ?></small>
                        <strong><?= esc(property_price_text($property)) ?></strong>
                    </div>
                    <button class="vl-btn vl-btn--gold vl-btn--wide" type="button" data-enquiry-open><i class="fa-brands fa-whatsapp"></i> Make an enquiry</button>
                    <div class="vl-agent">
                        <div class="vl-agent__avatar"><i class="fa-solid fa-user-check"></i></div>
                        <div><strong><?= is_object($agent) && trim(($agent->first_name ?? '') . ' ' . ($agent->last_name ?? '')) !== '' ? esc(trim(($agent->first_name ?? '') . ' ' . ($agent->last_name ?? ''))) : 'Vantage Luxe Team' ?></strong><small>Property enquiry support</small></div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php if (! empty($relatedProperties)): ?>
<section class="vl-section vl-related-section">
    <div class="vl-container">
        <div class="vl-section-head"><div><span class="vl-kicker">Keep exploring</span><h2 class="vl-title">Similar properties</h2><p class="vl-lead">More listings that may fit the same location or property profile.</p></div><a class="vl-btn vl-btn--light" href="<?= base_url('properties') ?>">Browse all</a></div>
        <div class="vl-grid">
            <?php foreach ($relatedProperties as $item): ?><?= view('components/property_card', ['property' => $item]) ?><?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Sticky, non-blocking enquiry action. -->
<div class="vl-property-action-dock" data-property-action-dock>
    <div>
        <small><?= count($displayPrices) > 1 ? 'From' : 'Listed at' ?></small>
        <strong><?= esc(property_price_text($property)) ?></strong>
    </div>
    <button class="vl-btn vl-btn--gold" type="button" data-enquiry-open><i class="fa-brands fa-whatsapp"></i> <span>Make an enquiry</span></button>
</div>

<!-- Full image gallery. -->
<div class="vl-gallery-modal" id="propertyGalleryModal" data-gallery-modal aria-hidden="true" role="dialog" aria-modal="true" aria-label="Property image gallery">
    <div class="vl-gallery-modal__topbar">
        <div><strong><?= esc($property->title) ?></strong><span data-gallery-counter>1 / <?= count($galleryItems) ?></span></div>
        <button type="button" class="vl-modal-icon-btn vl-modal-icon-btn--dark" data-gallery-close aria-label="Close gallery"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <button class="vl-gallery-modal__nav vl-gallery-modal__nav--prev" type="button" data-gallery-prev aria-label="Previous image"><i class="fa-solid fa-chevron-left"></i></button>
    <figure class="vl-gallery-modal__stage"><img data-gallery-view src="<?= esc($galleryItems[0]['url']) ?>" alt="<?= esc($galleryItems[0]['alt']) ?>"></figure>
    <button class="vl-gallery-modal__nav vl-gallery-modal__nav--next" type="button" data-gallery-next aria-label="Next image"><i class="fa-solid fa-chevron-right"></i></button>
    <div class="vl-gallery-modal__rail">
        <?php foreach ($galleryItems as $index => $item): ?>
            <button type="button" class="<?= $index === 0 ? 'is-active' : '' ?>" data-gallery-jump="<?= $index ?>"><img src="<?= esc($item['url']) ?>" alt="" loading="lazy"></button>
        <?php endforeach; ?>
    </div>
    <script type="application/json" data-gallery-data><?= json_encode($galleryItems, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</div>

<?php if ($youtubeEmbed): ?>
<div class="vl-video-modal" data-video-modal data-video-src="<?= esc($youtubeEmbed) ?>" aria-hidden="true" role="dialog" aria-modal="true" aria-label="Video walkthrough">
    <div class="vl-video-modal__shell">
        <div class="vl-video-modal__head"><div><span class="vl-kicker">Video walkthrough</span><strong><?= esc($property->title) ?></strong></div><button type="button" class="vl-modal-icon-btn vl-modal-icon-btn--dark" data-video-close aria-label="Close video"><i class="fa-solid fa-xmark"></i></button></div>
        <div class="vl-video-modal__frame"><iframe data-video-frame src="" title="Video walkthrough of <?= esc($property->title) ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>
    </div>
</div>
<?php endif; ?>

<!-- Enquiry drawer. -->
<div class="vl-enquiry-layer" data-enquiry-layer aria-hidden="true">
    <button class="vl-enquiry-layer__backdrop" type="button" data-enquiry-close aria-label="Close enquiry form"></button>
    <aside class="vl-enquiry-drawer" role="dialog" aria-modal="true" aria-labelledby="enquiry-title">
        <div class="vl-enquiry-drawer__head">
            <div><span class="vl-kicker">Make an enquiry</span><h2 id="enquiry-title">Interested in this property?</h2><p><?= esc($property->title) ?></p></div>
            <button type="button" class="vl-modal-icon-btn" data-enquiry-close aria-label="Close enquiry form"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="vl-enquiry-drawer__property">
            <img src="<?= esc($firstImage) ?>" alt="">
            <div><strong><?= esc(property_reference($property)) ?></strong><span><?= esc($locationText ?: 'Abuja') ?></span></div>
            <b><?= esc(property_price_text($property)) ?></b>
        </div>
        <form id="propertyRequestForm" action="<?= base_url('ajax/property-request') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="property_id" value="<?= (int) $property->id ?>">
            <input type="text" name="company" value="" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px" aria-hidden="true">
            <div class="vl-field"><label for="request-name">Full name</label><input id="request-name" type="text" name="full_name" required placeholder="Your full name" autocomplete="name"></div>
            <div class="vl-form-grid">
                <div class="vl-field"><label for="request-phone">Phone</label><input id="request-phone" type="tel" name="phone" required placeholder="080…" autocomplete="tel"></div>
                <div class="vl-field"><label for="request-email">Email <span class="vl-optional-label">Optional</span></label><input id="request-email" type="email" name="email" placeholder="you@email.com" autocomplete="email"></div>
            </div>
            <div class="vl-field">
                <label for="request-type">What would you like?</label>
                <select id="request-type" name="request_type" required data-search-select data-search-placeholder="Choose request type…">
                    <option value="information">More information</option>
                    <option value="viewing">Schedule a viewing</option>
                    <option value="call">Request a call back</option>
                    <option value="offer">Discuss an offer</option>
                </select>
            </div>
            <div class="vl-field"><label for="request-date">Preferred viewing date <span class="vl-optional-label">Optional</span></label><input id="request-date" type="date" name="preferred_date" min="<?= date('Y-m-d') ?>"></div>
            <div class="vl-field"><label for="request-message">Message</label><textarea id="request-message" name="message" placeholder="<?= esc($requestMessage) ?>"><?= esc($requestMessage) ?></textarea></div>
            <button class="vl-btn vl-btn--gold vl-btn--wide" type="submit"><i class="fa-brands fa-whatsapp"></i> Save request & continue to WhatsApp</button>
            <div class="vl-request-status" data-request-status role="status"></div>
        </form>
        <p class="vl-enquiry-drawer__note"><i class="fa-solid fa-shield-halved"></i> Your enquiry is recorded first, then WhatsApp opens with the property details ready to send.</p>
    </aside>
</div>

<?= $this->endSection() ?>
