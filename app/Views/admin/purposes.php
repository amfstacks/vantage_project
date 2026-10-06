<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="va-page-intro">
    <div><h2>Property purposes</h2><p>Control the purposes used across property listings and optional purpose-specific pricing.</p></div>
    <a class="va-btn va-btn--light" href="<?= base_url('admin/properties/create') ?>"><i class="fa-solid fa-plus"></i> Add property</a>
</div>

<div class="va-taxonomy-grid">
    <section class="va-card va-taxonomy-form">
        <div class="va-card-head"><div><strong>Add or update purpose</strong><div class="va-help">Examples: For Sale, For Rent, Shortlet (Daily), Lease.</div></div></div>
        <div class="va-card-body">
            <form action="<?= base_url('admin/purposes/save') ?>" method="post" class="va-form-stack" id="purposeForm">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="purpose-id">
                <div class="va-field"><label for="purpose-name">Display name</label><input id="purpose-name" type="text" name="name" required maxlength="100" placeholder="e.g. Shortlet (Daily)"></div>
                <div class="va-field"><label for="purpose-slug">Slug</label><input id="purpose-slug" type="text" name="slug" maxlength="120" placeholder="shortlet"><div class="va-help">Used in filters and legacy URLs. Keep it short and stable.</div></div>
                <div class="va-field"><label for="purpose-description">Description <span class="va-optional">Optional</span></label><textarea id="purpose-description" name="description" maxlength="255" placeholder="Short internal description"></textarea></div>
                <div class="va-field"><label for="purpose-sort">Sort order</label><input id="purpose-sort" type="number" name="sort_order" value="0" step="1"><div class="va-help">Lower numbers appear first in dropdowns.</div></div>
                <div class="va-status-switch"><div><strong>Active</strong><span>Allow this purpose to be selected on new listings and prices.</span></div><label class="va-switch"><input id="purpose-active" type="checkbox" name="is_active" value="1" checked><span class="va-switch-slider"></span></label></div>
                <div class="va-inline-actions"><button class="va-btn va-btn--gold" type="submit"><i class="fa-solid fa-check"></i> Save purpose</button><button class="va-btn va-btn--light" type="button" id="clearPurpose">Clear</button></div>
            </form>
        </div>
    </section>

    <section class="va-card">
        <div class="va-card-head"><div><strong>Existing purposes</strong><div class="va-help">Purposes already used by a property or price should be deactivated instead of deleted.</div></div><span class="va-badge va-badge--pending"><?= count($purposes) ?> total</span></div>
        <div class="va-table-wrap">
            <table class="va-table va-table--responsive" style="min-width:760px">
                <thead><tr><th>Purpose</th><th>Slug</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                <?php if (empty($purposes)): ?><tr><td colspan="5"><div class="va-empty">No purposes yet.</div></td></tr><?php else: ?>
                    <?php foreach ($purposes as $purpose): ?>
                        <tr>
                            <td><div class="va-taxonomy-name"><span class="va-taxonomy-icon"><i class="fa-solid fa-tags"></i></span><div><strong><?= esc($purpose->name) ?></strong><?php if (! empty($purpose->description)): ?><div class="va-help"><?= esc($purpose->description) ?></div><?php endif; ?></div></div></td>
                            <td><span class="va-code"><?= esc($purpose->slug) ?></span></td>
                            <td><?= (int) $purpose->sort_order ?></td>
                            <td><span class="va-badge va-badge--<?= (int) $purpose->is_active === 1 ? 'active' : 'sold' ?>"><?= (int) $purpose->is_active === 1 ? 'Active' : 'Inactive' ?></span></td>
                            <td><div class="va-inline-actions"><button class="va-btn va-btn--light va-btn--sm" type="button" data-edit-purpose data-item="<?= esc(json_encode(['id'=>(int)$purpose->id,'name'=>$purpose->name,'slug'=>$purpose->slug,'description'=>$purpose->description,'sort_order'=>(int)$purpose->sort_order,'is_active'=>(int)$purpose->is_active]), 'attr') ?>"><i class="fa-solid fa-pen"></i> Edit</button><form method="post" action="<?= base_url('admin/purposes/delete/' . $purpose->id) ?>" onsubmit="return confirm('Delete this purpose?');"><?= csrf_field() ?><button class="va-btn va-btn--danger va-btn--sm" type="submit"><i class="fa-solid fa-trash"></i></button></form></div></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="va-mobile-card-list">
            <?php foreach ($purposes as $purpose): ?>
                <article class="va-mobile-data-card">
                    <div class="va-mobile-data-card__head"><strong><?= esc($purpose->name) ?></strong><span class="va-badge va-badge--<?= (int) $purpose->is_active === 1 ? 'active' : 'sold' ?>"><?= (int) $purpose->is_active === 1 ? 'Active' : 'Inactive' ?></span></div>
                    <div class="va-mobile-data-card__meta"><span>Slug: <?= esc($purpose->slug) ?></span><span>Sort order: <?= (int) $purpose->sort_order ?></span><?php if (! empty($purpose->description)): ?><span><?= esc($purpose->description) ?></span><?php endif; ?></div>
                    <div class="va-mobile-data-card__actions"><button class="va-btn va-btn--light va-btn--sm" type="button" data-edit-purpose data-item="<?= esc(json_encode(['id'=>(int)$purpose->id,'name'=>$purpose->name,'slug'=>$purpose->slug,'description'=>$purpose->description,'sort_order'=>(int)$purpose->sort_order,'is_active'=>(int)$purpose->is_active]), 'attr') ?>"><i class="fa-solid fa-pen"></i> Edit</button></div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<script>
function editPurpose(item){
    document.getElementById('purpose-id').value=item.id;
    document.getElementById('purpose-name').value=item.name || '';
    document.getElementById('purpose-slug').value=item.slug || '';
    document.getElementById('purpose-description').value=item.description || '';
    document.getElementById('purpose-sort').value=item.sort_order ?? 0;
    document.getElementById('purpose-active').checked=Number(item.is_active)===1;
    document.getElementById('purpose-name').focus();
    document.getElementById('purposeForm').scrollIntoView({behavior:'smooth',block:'start'});
}
document.querySelectorAll('[data-edit-purpose]').forEach(function(button){button.addEventListener('click',function(){editPurpose(JSON.parse(this.dataset.item));});});
document.getElementById('clearPurpose').addEventListener('click',function(){document.getElementById('purposeForm').reset();document.getElementById('purpose-id').value='';document.getElementById('purpose-sort').value='0';document.getElementById('purpose-active').checked=true;});
</script>
<?= $this->endSection() ?>
