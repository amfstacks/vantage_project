<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?= esc($title ?? 'Admin') ?> | <?= esc(config('Site')->siteNameLong) ?></title>
    <link rel="icon" href="<?= base_url('assets/img/logo/fav-logo1.png') ?>" type="image/png">
    <link rel="stylesheet" href="<?= base_url('assets/css/plugins/fontawesome.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/vantage-admin.css') ?>?v=2.1.0">
    <?= $this->renderSection('head') ?>
</head>
<body>
<?php $segment = service('uri')->getSegment(2) ?: 'dashboard'; ?>
<div class="va-shell">
    <aside class="va-sidebar" data-admin-sidebar>
        <a class="va-brand" href="<?= base_url('admin/dashboard') ?>"><img src="<?= base_url(config('Site')->logoPath) ?>" alt="<?= esc(config('Site')->siteNameLong) ?>"></a>
        <nav class="va-nav">
            <a class="<?= $segment === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <a class="<?= $segment === 'properties' ? 'active' : '' ?>" href="<?= base_url('admin/properties') ?>"><i class="fa-solid fa-building"></i> Properties</a>
            <a class="<?= $segment === 'requests' ? 'active' : '' ?>" href="<?= base_url('admin/requests') ?>"><i class="fa-solid fa-message"></i> Property requests</a>
            <div class="va-nav-section">Listing setup</div>
            <a class="<?= $segment === 'property-types' ? 'active' : '' ?>" href="<?= base_url('admin/property-types') ?>"><i class="fa-solid fa-layer-group"></i> Property types</a>
            <a class="<?= $segment === 'purposes' ? 'active' : '' ?>" href="<?= base_url('admin/purposes') ?>"><i class="fa-solid fa-tags"></i> Purposes</a>
            <a class="<?= $segment === 'amenities' ? 'active' : '' ?>" href="<?= base_url('admin/amenities') ?>"><i class="fa-solid fa-list-check"></i> Amenities</a>
            <a href="<?= base_url('/') ?>" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> View website</a>
        </nav>
        <div class="va-side-bottom"><a href="<?= base_url('logout') ?>"><i class="fa-solid fa-right-from-bracket"></i> Sign out</a></div>
    </aside>

    <div class="va-main">
        <header class="va-top">
            <div style="display:flex;align-items:center;gap:12px"><button class="va-mobile-toggle" type="button" data-admin-toggle aria-label="Toggle admin menu"><i class="fa-solid fa-bars"></i></button><h1><?= esc($title ?? 'Admin') ?></h1></div>
            <div class="va-profile"><div class="va-profile__text" style="text-align:right"><strong style="display:block;font-size:.86rem"><?= esc(session()->get('first_name') ?? 'Administrator') ?></strong><span style="color:#777;font-size:.72rem"><?= esc(ucfirst((string) (session()->get('role') ?? 'admin'))) ?></span></div><div class="va-avatar"><?= esc(strtoupper(substr((string) (session()->get('first_name') ?? 'A'), 0, 1))) ?></div></div>
        </header>
        <main class="va-content">
            <?php if (session()->getFlashdata('success')): ?><div class="va-flash va-flash--success"><i class="fa-solid fa-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?><div class="va-flash va-flash--error"><i class="fa-solid fa-triangle-exclamation"></i> <?= session()->getFlashdata('error') ?></div><?php endif; ?>
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>
<script src="<?= base_url('assets/js/vantage-admin.js') ?>?v=2.1.0" defer></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
