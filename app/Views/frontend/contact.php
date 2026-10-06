<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php $site = config('Site'); $wa = clean_phone_digits($site->whatsappNumber); ?>
<section class="vl-page-hero"><div class="vl-container"><span class="vl-kicker">Contact</span><h1>Let’s talk about your next move.</h1><p>Buying, renting, investing or searching for a shortlet? Reach the Vantage Luxe Realty team directly and tell us what you need.</p></div></section>
<section class="vl-content-page">
    <div class="vl-container vl-contact-grid">
        <div>
            <span class="vl-kicker">Get in touch</span>
            <h2 class="vl-title">Property decisions deserve clear conversations.</h2>
            <p class="vl-lead">Use the channels below for general enquiries. For a specific property, open the listing and use its enquiry form so the property reference and URL are included automatically.</p>
            <div class="vl-contact-cards">
                <a class="vl-contact-card" href="tel:<?= esc(clean_phone_digits($site->contactPhone)) ?>"><i class="fa-solid fa-phone"></i><div><strong>Call us</strong><div class="vl-muted"><?= esc($site->contactPhone) ?></div></div></a>
                <a class="vl-contact-card" href="mailto:<?= esc($site->contactEmail) ?>"><i class="fa-regular fa-envelope"></i><div><strong>Email</strong><div class="vl-muted"><?= esc($site->contactEmail) ?></div></div></a>
                <?php if ($wa): ?><a class="vl-contact-card" href="https://wa.me/<?= esc($wa) ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i><div><strong>WhatsApp</strong><div class="vl-muted">Start a direct chat</div></div></a><?php endif; ?>
                <div class="vl-contact-card"><i class="fa-solid fa-location-dot"></i><div><strong>Location</strong><div class="vl-muted"><?= esc($site->address) ?></div></div></div>
            </div>
        </div>
        <div class="vl-panel" style="padding:34px;background:#faf9f7">
            <span class="vl-kicker">Start with the listings</span>
            <h2 class="vl-title" style="font-size:2rem">Find the property first, then send a detailed request.</h2>
            <p class="vl-lead">Each property page now supports a structured enquiry that is stored in the system and then transferred into WhatsApp with the listing URL and your details.</p>
            <div class="d-flex flex-wrap gap-2 mt-4"><a class="vl-btn vl-btn--gold" href="<?= base_url('properties') ?>">Browse properties <i class="fa-solid fa-arrow-right"></i></a><a class="vl-btn vl-btn--light" href="<?= base_url('about') ?>">About Vantage Luxe</a></div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
