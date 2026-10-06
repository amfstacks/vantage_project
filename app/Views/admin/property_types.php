<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="va-page-intro">
    <div><h2>Property types</h2><p>Manage the reusable property categories available when creating or editing listings.</p></div>
    <a class="va-btn va-btn--light" href="<?= base_url('admin/properties/create') ?>"><i class="fa-solid fa-plus"></i> Add property</a>
</div>

<div class="va-taxonomy-grid">
    <section class="va-card va-taxonomy-form">
        <div class="va-card-head"><div><strong>Add or update property type</strong><div class="va-help">Examples: Apartment, Detached Duplex, Land, Commercial Property.</div></div></div>
        <div class="va-card-body">
            <form action="<?= base_url('admin/property-types/save') ?>" method="post" class="va-form-stack" id="propertyTypeForm">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="property-type-id">
                <div class="va-field"><label for="property-type-name">Name</label><input id="property-type-name" type="text" name="name" required maxlength="100" placeholder="e.g. Apartment"></div>
                <div class="va-field"><label for="property-type-slug">Slug</label><input id="property-type-slug" type="text" name="slug" maxlength="120" placeholder="apartment"><div class="va-help">Used internally. Leave blank and it is generated automatically.</div></div>
                <div class="va-field"><label for="property-type-description">Description <span class="va-optional">Optional</span></label><textarea id="property-type-description" name="description" maxlength="255" placeholder="Short internal description"></textarea></div>
                <div class="va-field"><label for="property-type-sort">Sort order</label><input id="property-type-sort" type="number" name="sort_order" value="0" step="1"><div class="va-help">Lower numbers appear first in dropdowns.</div></div>
                <div class="va-status-switch"><div><strong>Active</strong><span>Allow this type to be selected on new listings.</span></div><label class="va-switch"><input id="property-type-active" type="checkbox" name="is_active" value="1" checked><span class="va-switch-slider"></span></label></div>
                <div class="va-inline-actions"><button class="va-btn va-btn--gold" type="submit"><i class="fa-solid fa-check"></i> Save property type</button><button class="va-btn va-btn--light" type="button" id="clearPropertyType">Clear</button></div>
            </form>
        </div>
    </section>

    <section class="va-card">
        <div class="va-card-head"><div><strong>Existing property types</strong><div class="va-help">Types already assigned to listings cannot be deleted; deactivate them instead.</div></div><span class="va-badge va-badge--pending"><?= count($propertyTypes) ?> total</span></div>
        <div class="va-table-wrap">
            <table class="va-table va-table--responsive" style="min-width:760px">
                <thead><tr><th>Property type</th><th>Slug</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                <?php if (empty($propertyTypes)): ?><tr><td colspan="5"><div class="va-empty">No property types yet.</div></td></tr><?php else: ?>
                    <?php foreach ($propertyTypes as $type): ?>
                        <tr>
                            <td><div class="va-taxonomy-name"><span class="va-taxonomy-icon"><i class="fa-solid fa-building"></i></span><div><strong><?= esc($type->name) ?></strong><?php if (! empty($type->description)): ?><div class="va-help"><?= esc($type->description) ?></div><?php endif; ?></div></div></td>
                            <td><span class="va-code"><?= esc($type->slug) ?></span></td>
                            <td><?= (int) $type->sort_order ?></td>
                            <td><span class="va-badge va-badge--<?= (int) $type->is_active === 1 ? 'active' : 'sold' ?>"><?= (int) $type->is_active === 1 ? 'Active' : 'Inactive' ?></span></td>
                            <td><div class="va-inline-actions"><button class="va-btn va-btn--light va-btn--sm" type="button" data-edit-property-type data-item="<?= esc(json_encode(['id'=>(int)$type->id,'name'=>$type->name,'slug'=>$type->slug,'description'=>$type->description,'sort_order'=>(int)$type->sort_order,'is_active'=>(int)$type->is_active]), 'attr') ?>"><i class="fa-solid fa-pen"></i> Edit</button><form method="post" action="<?= base_url('admin/property-types/delete/' . $type->id) ?>" onsubmit="return confirm('Delete this property type?');"><?= csrf_field() ?><button class="va-btn va-btn--danger va-btn--sm" type="submit"><i class="fa-solid fa-trash"></i></button></form></div></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="va-mobile-card-list">
            <?php foreach ($propertyTypes as $type): ?>
                <article class="va-mobile-data-card">
                    <div class="va-mobile-data-card__head"><strong><?= esc($type->name) ?></strong><span class="va-badge va-badge--<?= (int) $type->is_active === 1 ? 'active' : 'sold' ?>"><?= (int) $type->is_active === 1 ? 'Active' : 'Inactive' ?></span></div>
                    <div class="va-mobile-data-card__meta"><span>Slug: <?= esc($type->slug) ?></span><span>Sort order: <?= (int) $type->sort_order ?></span><?php if (! empty($type->description)): ?><span><?= esc($type->description) ?></span><?php endif; ?></div>
                    <div class="va-mobile-data-card__actions"><button class="va-btn va-btn--light va-btn--sm" type="button" data-edit-property-type data-item="<?= esc(json_encode(['id'=>(int)$type->id,'name'=>$type->name,'slug'=>$type->slug,'description'=>$type->description,'sort_order'=>(int)$type->sort_order,'is_active'=>(int)$type->is_active]), 'attr') ?>"><i class="fa-solid fa-pen"></i> Edit</button></div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<script>
function editPropertyType(item){
    document.getElementById('property-type-id').value=item.id;
    document.getElementById('property-type-name').value=item.name || '';
    document.getElementById('property-type-slug').value=item.slug || '';
    document.getElementById('property-type-description').value=item.description || '';
    document.getElementById('property-type-sort').value=item.sort_order ?? 0;
    document.getElementById('property-type-active').checked=Number(item.is_active)===1;
    document.getElementById('property-type-name').focus();
    document.getElementById('propertyTypeForm').scrollIntoView({behavior:'smooth',block:'start'});
}
document.querySelectorAll('[data-edit-property-type]').forEach(function(button){button.addEventListener('click',function(){editPropertyType(JSON.parse(this.dataset.item));});});
document.getElementById('clearPropertyType').addEventListener('click',function(){document.getElementById('propertyTypeForm').reset();document.getElementById('property-type-id').value='';document.getElementById('property-type-sort').value='0';document.getElementById('property-type-active').checked=true;});
</script>
<?= $this->endSection() ?>
