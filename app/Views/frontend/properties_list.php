<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="vl-page-hero vl-page-hero--listings">
    <div class="vl-container">
        <span class="vl-kicker">Curated listings</span>
        <h1>Find your next address.</h1>
        <p>Explore the full collection or refine it by purpose, neighbourhood, property type, bedrooms and budget. Leave every filter untouched to see all active properties.</p>
    </div>
</section>

<section class="vl-section-sm vl-listing-shell">
    <div class="vl-container">
        <button class="vl-mobile-filter-toggle" type="button" data-filter-toggle aria-expanded="false" aria-controls="propertyFilterForm">
            <span><i class="fa-solid fa-sliders"></i> Search & filters</span>
            <span class="vl-mobile-filter-toggle__meta"><strong data-results-count-mobile><?= number_format((int) $total) ?></strong> found <i class="fa-solid fa-chevron-down"></i></span>
        </button>

        <form id="propertyFilterForm" class="vl-filter-card" action="<?= base_url('properties') ?>" method="get" data-ajax-url="<?= base_url('ajax/properties') ?>" novalidate>
            <div class="vl-filter-card__head">
                <div>
                    <span class="vl-kicker">Refine your search</span>
                    <h2>Find exactly what fits</h2>
                </div>
                <button class="vl-filter-card__close" type="button" data-filter-close aria-label="Close filters"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="vl-filter-grid">
                <div class="vl-field vl-field--search">
                    <label for="filter-q">Keyword</label>
                    <div class="vl-input-icon"><i class="fa-solid fa-magnifying-glass"></i><input id="filter-q" type="search" name="q" value="<?= esc($filters['q']) ?>" placeholder="Area, property or keyword"></div>
                </div>
                <div class="vl-field">
                    <label for="filter-purpose">Purpose</label>
                    <select id="filter-purpose" name="purpose" data-search-select data-search-placeholder="Search purposes…">
                        <option value="">Any purpose</option>
                        <?php foreach ($purposes as $purpose): ?>
                            <option value="<?= esc($purpose->slug) ?>" <?= $filters['purpose'] === $purpose->slug ? 'selected' : '' ?>><?= esc($purpose->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="vl-field">
                    <label for="filter-location">Location</label>
                    <select id="filter-location" name="location" data-search-select data-search-placeholder="Search locations…">
                        <option value="">All locations</option>
                        <?php foreach ($locations as $item): ?>
                            <option value="<?= esc($item->location) ?>" <?= $filters['location'] === $item->location ? 'selected' : '' ?>><?= esc($item->location) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="vl-field">
                    <label for="filter-type">Property type</label>
                    <select id="filter-type" name="type" data-search-select data-search-placeholder="Search property types…">
                        <option value="">All property types</option>
                        <?php foreach ($types as $item): ?>
                            <option value="<?= esc($item->slug) ?>" <?= $filters['type'] === $item->slug ? 'selected' : '' ?>><?= esc($item->name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="vl-field">
                    <label for="filter-bedrooms">Bedrooms</label>
                    <select id="filter-bedrooms" name="bedrooms" data-search-select data-search-placeholder="Search…">
                        <option value="">Any</option>
                        <?php foreach ([1,2,3,4,5] as $bed): ?>
                            <option value="<?= $bed ?>" <?= (int) $filters['bedrooms'] === $bed ? 'selected' : '' ?>><?= $bed ?>+ bedrooms</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="vl-filter-actions">
                    <button class="vl-btn vl-btn--gold" type="submit" aria-label="Apply filters"><i class="fa-solid fa-sliders"></i> Apply</button>
                </div>
            </div>

            <div class="vl-filter-secondary">
                <div class="vl-field">
                    <label for="min-price">Min price (₦)</label>
                    <input id="min-price" type="number" min="0" step="1000" name="min_price" value="<?= esc((string) $filters['min_price']) ?>" placeholder="No minimum">
                </div>
                <div class="vl-field">
                    <label for="max-price">Max price (₦)</label>
                    <input id="max-price" type="number" min="0" step="1000" name="max_price" value="<?= esc((string) $filters['max_price']) ?>" placeholder="No maximum">
                </div>
                <div class="vl-field">
                    <label for="filter-sort">Sort by</label>
                    <select id="filter-sort" name="sort" data-search-select data-search-placeholder="Search sorting options…">
                        <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>Newest first</option>
                        <option value="price_low" <?= $filters['sort'] === 'price_low' ? 'selected' : '' ?>>Price: low to high</option>
                        <option value="price_high" <?= $filters['sort'] === 'price_high' ? 'selected' : '' ?>>Price: high to low</option>
                        <option value="oldest" <?= $filters['sort'] === 'oldest' ? 'selected' : '' ?>>Oldest first</option>
                    </select>
                </div>
                <div class="vl-filter-reset-wrap">
                    <a class="vl-btn vl-btn--light vl-btn--wide" href="<?= base_url('properties') ?>" data-clear-filters><i class="fa-solid fa-rotate-left"></i> Clear filters</a>
                </div>
            </div>
        </form>

        <div class="vl-results-bar">
            <div>
                <div class="vl-results-count"><span data-results-count><?= number_format((int) $total) ?></span> propert<?= (int) $total === 1 ? 'y' : 'ies' ?> found</div>
                <div class="vl-muted vl-results-caption">Results update without reloading the whole page.</div>
            </div>
            <a class="vl-btn vl-btn--light" href="<?= base_url('contact') ?>"><i class="fa-regular fa-message"></i> Need help choosing?</a>
        </div>

        <div id="propertyResults" aria-live="polite">
            <?= view('components/property_results', ['properties' => $properties, 'pager' => $pager]) ?>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
