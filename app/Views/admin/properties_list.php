<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="va-page-intro">
    <div><h2>Property inventory</h2><p>Search, filter, edit, publish and manage media for every listing.</p></div>
    <a class="va-btn va-btn--gold" href="<?= base_url('admin/properties/create') ?>"><i class="fa-solid fa-plus"></i> Add property</a>
</div>

<section class="va-card">
    <div class="va-card-head">
        <form class="va-filters" action="<?= base_url('admin/properties') ?>" method="get">
            <div class="va-field"><label>Search</label><input type="search" name="q" value="<?= esc($q) ?>" placeholder="Title, area, type"></div>
            <div class="va-field"><label>Status</label><select name="status"><option value="">All statuses</option><option value="active" <?= $currentStatus === 'active' ? 'selected' : '' ?>>Active</option><option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option><option value="sold" <?= $currentStatus === 'sold' ? 'selected' : '' ?>>Sold</option></select></div>
            <div class="va-field"><label>Purpose</label><select name="purpose"><option value="">All purposes</option><?php foreach ($purposes as $purpose): ?><option value="<?= (int) $purpose->id ?>" <?= (int) $currentPurpose === (int) $purpose->id ? 'selected' : '' ?>><?= esc($purpose->name) ?><?= (int) $purpose->is_active !== 1 ? ' — inactive' : '' ?></option><?php endforeach; ?></select></div>
            <div class="va-field"><label>Property type</label><select name="type"><option value="">All property types</option><?php foreach ($propertyTypes as $type): ?><option value="<?= (int) $type->id ?>" <?= (int) $currentPropertyType === (int) $type->id ? 'selected' : '' ?>><?= esc($type->name) ?><?= (int) $type->is_active !== 1 ? ' — inactive' : '' ?></option><?php endforeach; ?></select></div>
            <button class="va-btn va-btn--dark" type="submit"><i class="fa-solid fa-filter"></i> Apply</button>
            <a class="va-btn va-btn--light" href="<?= base_url('admin/properties') ?>">Clear</a>
        </form>
    </div>
    <div class="va-table-wrap">
        <table class="va-table">
            <thead><tr><th>Property</th><th>Purpose</th><th>Price</th><th>Status</th><th>Added</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if (empty($properties)): ?>
                <tr><td colspan="6"><div class="va-empty"><i class="fa-solid fa-building"></i><p>No properties match your filters.</p></div></td></tr>
            <?php else: ?>
                <?php foreach ($properties as $property): ?>
                <tr>
                    <td><div class="va-property-cell"><img class="va-thumb" src="<?= esc(property_image_url($property->image_path ?? null)) ?>" alt=""><div><strong><?= esc($property->title) ?></strong><small><?= esc(property_reference($property)) ?> · <?= esc(trim($property->location . ', ' . $property->city, ', ')) ?></small></div></div></td>
                    <td><span class="va-badge va-badge--pending"><?= esc(ucwords(str_replace('-', ' ', (string) $property->purpose))) ?></span><div class="va-help" style="margin-top:4px"><?= esc($property->property_type) ?></div></td>
                    <td><strong><?= esc(property_price_text($property)) ?></strong></td>
                    <td><span class="va-badge va-badge--<?= esc($property->status) ?>"><?= esc(ucfirst((string) $property->status)) ?></span></td>
                    <td><?= esc(date('j M Y', strtotime($property->created_at))) ?></td>
                    <td>
                        <div class="va-inline-actions">
                            <?php if ($property->status === 'active'): ?><a class="va-btn va-btn--light va-btn--sm" href="<?= base_url('property/' . $property->slug) ?>" target="_blank" title="View listing"><i class="fa-solid fa-eye"></i></a><?php endif; ?>
                            <a class="va-btn va-btn--light va-btn--sm" href="<?= base_url('admin/properties/edit/' . $property->id) ?>"><i class="fa-solid fa-pen"></i> Edit</a>
                            <form action="<?= base_url('admin/properties/delete/' . $property->id) ?>" method="post" onsubmit="return confirm('Delete this property permanently? This also removes its images and related requests.');"><?= csrf_field() ?><button class="va-btn va-btn--danger va-btn--sm" type="submit"><i class="fa-solid fa-trash"></i></button></form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?= $pager->links('default', 'admin_pager') ?>
</section>

<?= $this->endSection() ?>
