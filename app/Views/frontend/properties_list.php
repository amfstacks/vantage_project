<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<br>
<br>
<br>
<br>
<br>
<br>
<div class="property-inner-section-find sp1">
    <div class="container">
        
        <div class="row">
            <div class="col-lg-12">
                <div class="property-mapgrid-area">
                    <div class="heading1 d-flex flex-wrap justify-content-between align-items-center">
                        
                        <h3>Properties (<?= $total ?>)</h3>
                        
                        <div class="tabs-btn d-flex align-items-center">
                            <ul class="nav nav-pills d-none d-lg-flex me-4" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-grid" type="button" role="tab">
                                        <i class="fa-solid fa-border-all"></i>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-list" type="button" role="tab">
                                        <i class="fa-solid fa-list"></i>
                                    </button>
                                </li>
                            </ul>

                            <form action="<?= current_url() ?>" method="GET" class="d-flex">
                                <?php if(!empty($searchQuery)): ?><input type="hidden" name="q" value="<?= esc($searchQuery) ?>"><?php endif; ?>
                                <?php if(!empty($location)): ?><input type="hidden" name="location" value="<?= esc($location) ?>"><?php endif; ?>

                                <div class="filter-group me-3">
                                    <select name="per_page" onchange="this.form.submit()" class="form-select border-gray-200">
                                        <option value="12" <?= $perPage == 12 ? 'selected' : '' ?>>Show: 12</option>
                                        <option value="24" <?= $perPage == 24 ? 'selected' : '' ?>>Show: 24</option>
                                        <option value="48" <?= $perPage == 48 ? 'selected' : '' ?>>Show: 48</option>
                                    </select>
                                </div>
                                <div class="filter-group">
                                    <select name="sort" onchange="this.form.submit()" class="form-select border-gray-200">
                                        <option value="newest" <?= $currentSort == 'newest' ? 'selected' : '' ?>>Sort: Newest</option>
                                        <option value="oldest" <?= $currentSort == 'oldest' ? 'selected' : '' ?>>Sort: Oldest</option>
                                        <option value="price_low" <?= $currentSort == 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                                        <option value="price_high" <?= $currentSort == 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        <div class="space32"></div>

        <div class="tab-content" id="pills-tabContent">
            
            <div class="tab-pane fade show active" id="pills-grid" role="tabpanel">
                <div class="row">
                    <?php if(empty($properties)): ?>
                        <div class="col-12 text-center py-5">
                            <h4 class="text-muted">No properties match your search criteria.</h4>
                            <a href="<?= base_url('properties') ?>" class="btn btn-outline-primary mt-3">Clear Filters</a>
                        </div>
                    <?php else: ?>
                        <?php foreach($properties as $prop): ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <?= view('components/property_card', ['property' => $prop]) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-list" role="tabpanel">
                <div class="row">
                    <?php if(!empty($properties)): ?>
                        <?php foreach($properties as $prop): ?>
                            <?= view('components/property_card_list', ['property' => $prop]) ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div class="row mt-5">
            <div class="col-lg-12">
               <?= $pager->links('default', 'housebox_pager') ?>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>