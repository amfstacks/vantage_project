<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="va-page-intro"><div><h2>Property amenities</h2><p>Create reusable features that can be assigned to listings.</p></div></div>
<div class="va-dashboard-grid">
    <section class="va-card">
        <div class="va-card-head"><div><strong>Add or update amenity</strong><div class="va-help">Use a Font Awesome class such as fa-wifi, fa-car or fa-shield-halved.</div></div></div>
        <div class="va-card-body">
            <form action="<?= base_url('admin/amenities/save') ?>" method="post" class="va-form-stack">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="amenity-id">
                <div class="va-field"><label for="amenity-name">Amenity name</label><input id="amenity-name" type="text" name="name" required maxlength="50" placeholder="24/7 Electricity"></div>
                <div class="va-field"><label for="amenity-icon">Font Awesome icon class</label><input id="amenity-icon" type="text" name="icon" maxlength="50" placeholder="fa-bolt"><div class="va-help">Enter only the icon class, not the fa-solid prefix.</div></div>
                <div class="va-inline-actions"><button class="va-btn va-btn--gold" type="submit"><i class="fa-solid fa-check"></i> Save amenity</button><button class="va-btn va-btn--light" type="button" id="clearAmenity">Clear</button></div>
            </form>
        </div>
    </section>
    <section class="va-card">
        <div class="va-card-head"><strong>Existing amenities</strong><span class="va-badge va-badge--pending"><?= count($amenities) ?> total</span></div>
        <div class="va-table-wrap">
            <table class="va-table" style="min-width:560px"><thead><tr><th>Feature</th><th>Icon</th><th>Actions</th></tr></thead><tbody>
            <?php if (empty($amenities)): ?><tr><td colspan="3"><div class="va-empty">No amenities yet.</div></td></tr><?php else: ?>
                <?php foreach ($amenities as $amenity): ?><tr><td><strong><?= esc($amenity->name) ?></strong></td><td><i class="fa-solid <?= esc($amenity->icon ?: 'fa-check') ?>"></i> <span class="va-help"><?= esc($amenity->icon ?: 'fa-check') ?></span></td><td><div class="va-inline-actions"><button class="va-btn va-btn--light va-btn--sm" type="button" onclick="editAmenity(<?= (int) $amenity->id ?>, <?= json_encode($amenity->name) ?>, <?= json_encode($amenity->icon) ?>)"><i class="fa-solid fa-pen"></i> Edit</button><form method="post" action="<?= base_url('admin/amenities/delete/' . $amenity->id) ?>" onsubmit="return confirm('Delete this amenity?');"><?= csrf_field() ?><button class="va-btn va-btn--danger va-btn--sm" type="submit"><i class="fa-solid fa-trash"></i></button></form></div></td></tr><?php endforeach; ?>
            <?php endif; ?>
            </tbody></table>
        </div>
    </section>
</div>
<script>
function editAmenity(id, name, icon){document.getElementById('amenity-id').value=id;document.getElementById('amenity-name').value=name;document.getElementById('amenity-icon').value=icon || '';document.getElementById('amenity-name').focus();}
document.getElementById('clearAmenity').addEventListener('click',function(){document.getElementById('amenity-id').value='';document.getElementById('amenity-name').value='';document.getElementById('amenity-icon').value='';});
</script>
<?= $this->endSection() ?>
