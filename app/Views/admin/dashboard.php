<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="va-page-intro">
    <div>
        <h2>Portfolio overview</h2>
        <p>Track listings and incoming buyer or renter enquiries from one place.</p>
    </div>
    <a class="va-btn va-btn--gold" href="<?= base_url('admin/properties/create') ?>"><i class="fa-solid fa-plus"></i> Add property</a>
</div>

<div class="va-grid-stats">
    <div class="va-stat"><div class="va-stat__icon"><i class="fa-solid fa-building"></i></div><strong><?= number_format((int) $totalProperties) ?></strong><span>Total properties</span></div>
    <div class="va-stat"><div class="va-stat__icon"><i class="fa-solid fa-circle-check"></i></div><strong><?= number_format((int) $activeProperties) ?></strong><span>Active listings</span></div>
    <div class="va-stat"><div class="va-stat__icon"><i class="fa-regular fa-clock"></i></div><strong><?= number_format((int) $pendingApproval) ?></strong><span>Draft / pending</span></div>
    <div class="va-stat"><div class="va-stat__icon"><i class="fa-solid fa-handshake"></i></div><strong><?= number_format((int) $soldProperties) ?></strong><span>Sold</span></div>
    <div class="va-stat"><div class="va-stat__icon"><i class="fa-solid fa-message"></i></div><strong><?= number_format((int) $newRequests) ?></strong><span>New requests</span></div>
</div>

<div class="va-dashboard-grid">
    <section class="va-card">
        <div class="va-card-head"><div><strong>Recent properties</strong><div class="va-help">Latest records added to the portfolio.</div></div><a class="va-btn va-btn--light va-btn--sm" href="<?= base_url('admin/properties') ?>">View all</a></div>
        <div class="va-table-wrap">
            <table class="va-table">
                <thead><tr><th>Property</th><th>Purpose</th><th>Price</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($recentProperties)): ?>
                    <tr><td colspan="5"><div class="va-empty">No properties yet.</div></td></tr>
                <?php else: ?>
                    <?php foreach ($recentProperties as $property): ?>
                        <tr>
                            <td><div class="va-property-cell"><img class="va-thumb" src="<?= esc(property_image_url($property->image_path ?? null)) ?>" alt=""><div><strong><?= esc($property->title) ?></strong><small><?= esc(trim($property->location . ', ' . $property->city, ', ')) ?></small></div></div></td>
                            <td><?= esc(ucfirst((string) $property->purpose)) ?></td>
                            <td><strong><?= esc(property_price_text($property)) ?></strong></td>
                            <td><span class="va-badge va-badge--<?= esc($property->status) ?>"><?= esc(ucfirst((string) $property->status)) ?></span></td>
                            <td><a class="va-btn va-btn--light va-btn--sm" href="<?= base_url('admin/properties/edit/' . $property->id) ?>">Edit</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="va-card">
        <div class="va-card-head"><div><strong>Latest enquiries</strong><div class="va-help">Most recent property requests.</div></div><a class="va-btn va-btn--light va-btn--sm" href="<?= base_url('admin/requests') ?>">Open inbox</a></div>
        <div class="va-card-body" style="display:grid;gap:12px">
            <?php if (empty($recentRequests)): ?>
                <div class="va-empty">No property requests yet.</div>
            <?php else: ?>
                <?php foreach ($recentRequests as $request): ?>
                    <div style="border:1px solid #ececea;border-radius:14px;padding:14px">
                        <div style="display:flex;justify-content:space-between;gap:10px;align-items:center"><strong><?= esc($request->full_name) ?></strong><span class="va-badge va-badge--<?= esc($request->status) ?>"><?= esc(ucfirst((string) $request->status)) ?></span></div>
                        <div class="va-help" style="margin-top:5px"><?= esc($request->property_title ?? 'Property') ?></div>
                        <div class="va-help" style="margin-top:7px"><i class="fa-solid fa-phone"></i> <?= esc($request->phone) ?> · <?= esc(date('j M, H:i', strtotime($request->created_at))) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
