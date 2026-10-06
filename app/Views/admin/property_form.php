<?php helper('form'); ?>
<?= $this->extend('layouts/admin') ?>

<?= $this->section('head') ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
.ql-toolbar.ql-snow{border:1px solid #dededb;border-radius:11px 11px 0 0;background:#fafafa}.ql-container.ql-snow{border:1px solid #dededb;border-top:0;border-radius:0 0 11px 11px;background:#fff;min-height:220px}.ql-editor{min-height:220px;font-size:15px;line-height:1.7}
@media(max-width:640px){.ql-toolbar.ql-snow{overflow-x:auto;white-space:nowrap}.ql-editor{min-height:180px;font-size:16px}}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$isEdit = isset($property) && is_object($property);
$formAction = $isEdit ? base_url('admin/properties/update/' . $property->id) : base_url('admin/properties/store');
$value = static function (string $name, $fallback = '') {
    $oldValue = old($name);
    return $oldValue !== null ? $oldValue : $fallback;
};
$currentDescription = (string) $value('description', $isEdit ? $property->description : '');

$currentPurposeId = (int) $value('purpose_id', $isEdit ? ($property->purpose_id ?? 0) : 0);
if ($currentPurposeId === 0 && $isEdit) {
    foreach ($purposes as $purposeOption) {
        if ((string) $purposeOption->slug === (string) ($property->purpose ?? '')) {
            $currentPurposeId = (int) $purposeOption->id;
            break;
        }
    }
}
$currentPropertyTypeId = (int) $value('property_type_id', $isEdit ? ($property->property_type_id ?? 0) : 0);
if ($currentPropertyTypeId === 0 && $isEdit) {
    foreach ($propertyTypes as $typeOption) {
        if (strcasecmp((string) $typeOption->name, (string) ($property->property_type ?? '')) === 0) {
            $currentPropertyTypeId = (int) $typeOption->id;
            break;
        }
    }
}

$prices = old('prices');
if (! is_array($prices) || $prices === []) {
    if (! empty($propertyPrices)) {
        $prices = array_map(static fn ($price) => [
            'price' => $price->price,
            'price_unit' => $price->price_unit,
            'purpose_id' => $price->purpose_id ?? '',
            'discount_price' => $price->discount_price,
            'discount_percentage' => $price->discount_percentage ?? null,
        ], $propertyPrices);
    } elseif ($isEdit && isset($property->price) && (float) $property->price > 0) {
        $prices = [[
            'price' => $property->price,
            'price_unit' => $property->price_unit ?: 'One Time',
            'purpose_id' => '',
            'discount_price' => $property->discount_price,
            'discount_percentage' => null,
        ]];
    } else {
        $prices = [['price' => '', 'price_unit' => 'One Time', 'purpose_id' => '', 'discount_price' => '', 'discount_percentage' => null]];
    }
}

$discountPercent = static function (array $price): float {
    $base = (float) ($price['price'] ?? 0);
    $discount = (float) ($price['discount_price'] ?? 0);
    if ($base <= 0 || $discount <= 0 || $discount >= $base) {
        return 0;
    }
    return round((($base - $discount) / $base) * 100, 2);
};
?>

<div class="va-page-intro">
    <div><h2><?= $isEdit ? 'Edit listing' : 'Create a new listing' ?></h2><p>Build a complete, media-rich property page with database-driven categories, flexible pricing and search metadata.</p></div>
    <div class="va-inline-actions">
        <?php if ($isEdit && $property->status === 'active'): ?><a class="va-btn va-btn--light" href="<?= base_url('property/' . $property->slug) ?>" target="_blank"><i class="fa-solid fa-eye"></i> Preview live page</a><?php endif; ?>
    </div>
</div>

<form action="<?= esc($formAction) ?>" method="post" enctype="multipart/form-data" id="propertyForm" class="va-form-stack">
    <?= csrf_field() ?>

    <section class="va-card">
        <div class="va-card-head"><div><strong>Property identity</strong><div class="va-help">Property purpose and type are centrally managed, so your listings stay consistent.</div></div></div>
        <div class="va-card-body va-form-grid">
            <div class="va-field va-span-2"><label for="title">Property title</label><input id="title" type="text" name="title" required maxlength="255" value="<?= esc((string) $value('title', $isEdit ? $property->title : '')) ?>" placeholder="e.g. Contemporary 4-bedroom detached duplex in Asokoro"></div>

            <div class="va-field">
                <div class="va-field-label-row"><label for="purpose_id">Purpose</label><a href="<?= base_url('admin/purposes') ?>" target="_blank">Manage</a></div>
                <select id="purpose_id" name="purpose_id" required>
                    <option value="">Select purpose</option>
                    <?php foreach ($purposes as $purposeOption): ?>
                        <?php $isSelected = $currentPurposeId === (int) $purposeOption->id; ?>
                        <option value="<?= (int) $purposeOption->id ?>" <?= $isSelected ? 'selected' : '' ?> <?= (int) $purposeOption->is_active !== 1 && ! $isSelected ? 'disabled' : '' ?>><?= esc($purposeOption->name) ?><?= (int) $purposeOption->is_active !== 1 ? ' — inactive' : '' ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($purposes)): ?><div class="va-field-warning">No purposes exist yet. <a href="<?= base_url('admin/purposes') ?>">Create one first.</a></div><?php endif; ?>
            </div>

            <div class="va-field">
                <div class="va-field-label-row"><label for="property_type_id">Property type</label><a href="<?= base_url('admin/property-types') ?>" target="_blank">Manage</a></div>
                <select id="property_type_id" name="property_type_id" required>
                    <option value="">Select property type</option>
                    <?php foreach ($propertyTypes as $typeOption): ?>
                        <?php $isSelected = $currentPropertyTypeId === (int) $typeOption->id; ?>
                        <option value="<?= (int) $typeOption->id ?>" <?= $isSelected ? 'selected' : '' ?> <?= (int) $typeOption->is_active !== 1 && ! $isSelected ? 'disabled' : '' ?>><?= esc($typeOption->name) ?><?= (int) $typeOption->is_active !== 1 ? ' — inactive' : '' ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($propertyTypes)): ?><div class="va-field-warning">No property types exist yet. <a href="<?= base_url('admin/property-types') ?>">Create one first.</a></div><?php endif; ?>
            </div>

            <div class="va-field"><label for="location">Location / district</label><input id="location" type="text" name="location" required value="<?= esc((string) $value('location', $isEdit ? $property->location : '')) ?>" placeholder="Asokoro"></div>
            <div class="va-field"><label for="city">City</label><input id="city" type="text" name="city" required value="<?= esc((string) $value('city', $isEdit ? $property->city : config('Site')->defaultCity)) ?>" placeholder="Abuja"></div>
            <div class="va-field va-span-2"><label for="address">Full address</label><input id="address" type="text" name="address" value="<?= esc((string) $value('address', $isEdit ? $property->address : '')) ?>" placeholder="Street, estate or landmark details"></div>
        </div>
    </section>

    <section class="va-card">
        <div class="va-card-head">
            <div><strong>Pricing</strong><div class="va-help">Create multiple pricing options. A purpose can be attached to an individual price when required.</div></div>
            <button class="va-btn va-btn--light va-btn--sm" type="button" data-add-price><i class="fa-solid fa-plus"></i> Add price option</button>
        </div>
        <div class="va-card-body va-price-list" data-price-list data-next-index="<?= count($prices) ?>">
            <?php foreach ($prices as $index => $price): ?>
                <?php $percent = $discountPercent($price); ?>
                <div class="va-price-row" data-price-row>
                    <div class="va-price-row__head">
                        <div class="va-price-row__title"><span class="va-price-number">#<?= (int) $index + 1 ?></span><div><strong>Price option</strong><small>Configure amount, billing unit and optional purpose.</small></div></div>
                        <div class="va-price-row__tools">
                            <span class="va-discount-badge <?= $percent > 0 ? 'has-discount' : '' ?>" data-discount-badge><?= $percent > 0 ? esc(rtrim(rtrim(number_format($percent, 2), '0'), '.')) . '% OFF' : 'No discount' ?></span>
                            <button class="va-btn va-btn--danger va-btn--sm va-price-remove" type="button" data-remove-price title="Remove price"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="va-price-row__grid">
                        <div class="va-field"><label>Price (₦)</label><input data-price-input type="number" min="1" step="0.01" name="prices[<?= $index ?>][price]" required value="<?= esc((string) ($price['price'] ?? '')) ?>" placeholder="25000000"></div>
                        <div class="va-field"><label>Price unit</label><select name="prices[<?= $index ?>][price_unit]" required><?php foreach (['One Time','Year','Month','Week','Night','Day'] as $unit): ?><option value="<?= esc($unit) ?>" <?= ($price['price_unit'] ?? 'One Time') === $unit ? 'selected' : '' ?>><?= esc($unit) ?></option><?php endforeach; ?></select></div>
                        <div class="va-field"><label>Purpose <span class="va-optional">Optional</span></label><select name="prices[<?= $index ?>][purpose_id]"><option value="">General / any purpose</option><?php foreach ($purposes as $purposeOption): ?><option value="<?= (int) $purposeOption->id ?>" <?= (int) ($price['purpose_id'] ?? 0) === (int) $purposeOption->id ? 'selected' : '' ?> <?= (int) $purposeOption->is_active !== 1 && (int) ($price['purpose_id'] ?? 0) !== (int) $purposeOption->id ? 'disabled' : '' ?>><?= esc($purposeOption->name) ?><?= (int) $purposeOption->is_active !== 1 ? ' — inactive' : '' ?></option><?php endforeach; ?></select></div>
                        <div class="va-field"><label>Discount price (₦) <span class="va-optional">Optional</span></label><input data-discount-input type="number" min="0" step="0.01" name="prices[<?= $index ?>][discount_price]" value="<?= esc((string) ($price['discount_price'] ?? '')) ?>" placeholder="e.g. 22500000"><input data-discount-hidden type="hidden" name="prices[<?= $index ?>][discount_percentage]" value="<?= esc((string) ($percent ?: '')) ?>"><div class="va-help" data-discount-help><?= $percent > 0 ? 'Customer saves ' . esc(rtrim(rtrim(number_format($percent, 2), '0'), '.')) . '%.' : 'Percentage is calculated automatically.' ?></div></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <template id="priceRowTemplate">
            <div class="va-price-row" data-price-row>
                <div class="va-price-row__head">
                    <div class="va-price-row__title"><span class="va-price-number">#__NUMBER__</span><div><strong>Price option</strong><small>Configure amount, billing unit and optional purpose.</small></div></div>
                    <div class="va-price-row__tools"><span class="va-discount-badge" data-discount-badge>No discount</span><button class="va-btn va-btn--danger va-btn--sm va-price-remove" type="button" data-remove-price title="Remove price"><i class="fa-solid fa-trash"></i></button></div>
                </div>
                <div class="va-price-row__grid">
                    <div class="va-field"><label>Price (₦)</label><input data-price-input type="number" min="1" step="0.01" name="prices[__INDEX__][price]" required placeholder="e.g. 25000000"></div>
                    <div class="va-field"><label>Price unit</label><select name="prices[__INDEX__][price_unit]" required><option>One Time</option><option>Year</option><option>Month</option><option>Week</option><option>Night</option><option>Day</option></select></div>
                    <div class="va-field"><label>Purpose <span class="va-optional">Optional</span></label><select name="prices[__INDEX__][purpose_id]"><option value="">General / any purpose</option><?php foreach ($purposes as $purposeOption): ?><?php if ((int) $purposeOption->is_active === 1): ?><option value="<?= (int) $purposeOption->id ?>"><?= esc($purposeOption->name) ?></option><?php endif; ?><?php endforeach; ?></select></div>
                    <div class="va-field"><label>Discount price (₦) <span class="va-optional">Optional</span></label><input data-discount-input type="number" min="0" step="0.01" name="prices[__INDEX__][discount_price]" placeholder="Optional"><input data-discount-hidden type="hidden" name="prices[__INDEX__][discount_percentage]"><div class="va-help" data-discount-help>Percentage is calculated automatically.</div></div>
                </div>
            </div>
        </template>
    </section>

    <section class="va-card">
        <div class="va-card-head"><div><strong>Property details</strong><div class="va-help">Useful specifications for buyers, renters and search filters.</div></div></div>
        <div class="va-card-body va-form-grid va-form-grid--3">
            <div class="va-field"><label>Bedrooms</label><input type="number" min="0" name="bedrooms" value="<?= esc((string) $value('bedrooms', $isEdit ? $property->bedrooms : 0)) ?>"></div>
            <div class="va-field"><label>Bathrooms</label><input type="number" min="0" name="bathrooms" value="<?= esc((string) $value('bathrooms', $isEdit ? $property->bathrooms : 0)) ?>"></div>
            <div class="va-field"><label>Toilets</label><input type="number" min="0" name="toilets" value="<?= esc((string) $value('toilets', $isEdit ? $property->toilets : 0)) ?>"></div>
            <div class="va-field"><label>Area (m²)</label><input type="number" min="0" step="0.01" name="area_sqm" value="<?= esc((string) $value('area_sqm', $isEdit ? $property->area_sqm : '')) ?>" placeholder="500"></div>
            <div class="va-field"><label>Latitude</label><input type="text" name="latitude" value="<?= esc((string) $value('latitude', $isEdit ? $property->latitude : '')) ?>" placeholder="9.0765"></div>
            <div class="va-field"><label>Longitude</label><input type="text" name="longitude" value="<?= esc((string) $value('longitude', $isEdit ? $property->longitude : '')) ?>" placeholder="7.3986"></div>
        </div>
    </section>

    <section class="va-card">
        <div class="va-card-head"><div><strong>Description</strong><div class="va-help">Use headings, lists and emphasis to make long property information easy to scan.</div></div></div>
        <div class="va-card-body">
            <input type="hidden" name="description" id="descriptionInput" value="<?= esc($currentDescription) ?>">
            <div id="quillEditor"><?= $currentDescription ?></div>
        </div>
    </section>

    <section class="va-card">
        <div class="va-card-head"><div><strong>Photos & video</strong><div class="va-help">The primary image becomes the listing cover. YouTube URLs are embedded professionally on the property page.</div></div></div>
        <div class="va-card-body va-form-stack">
            <?php if ($isEdit && ! empty($existingImages)): ?>
                <div>
                    <div class="va-section-title"><div><h2>Current gallery</h2><p>Choose the best image as the listing cover or remove outdated photos.</p></div></div>
                    <div class="va-image-grid">
                        <?php foreach ($existingImages as $image): ?>
                            <div class="va-image-card">
                                <img src="<?= esc(property_image_url($image->image_path)) ?>" alt="Property image">
                                <div class="va-image-card__foot">
                                    <?php if ((int) $image->is_primary === 1): ?><span class="va-badge va-badge--active"><i class="fa-solid fa-star"></i> Primary</span><?php else: ?><button class="va-btn va-btn--light va-btn--sm" type="submit" form="primary-image-<?= (int) $image->id ?>" title="Set as primary"><i class="fa-regular fa-star"></i></button><?php endif; ?>
                                    <button class="va-btn va-btn--danger va-btn--sm" type="submit" form="delete-image-<?= (int) $image->id ?>" onclick="return confirm('Remove this image?');"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <label class="va-dropzone" for="images"><i class="fa-regular fa-images"></i><strong style="display:block;margin-top:10px"><?= $isEdit ? 'Add more property photos' : 'Upload property photos' ?></strong><span class="va-help">JPG, PNG or WebP. Up to 20 files, maximum 5 MB each.</span><input id="images" data-image-input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple <?= $isEdit ? '' : 'required' ?> style="display:block;margin:14px auto 0;max-width:330px"></label>
            <div class="va-image-grid" data-image-preview></div>

            <div class="va-form-grid">
                <div class="va-field"><label for="video_url">YouTube video URL</label><input id="video_url" type="url" name="video_url" value="<?= esc((string) $value('video_url', $isEdit ? $property->video_url : '')) ?>" placeholder="https://www.youtube.com/watch?v=..."><div class="va-help">YouTube watch, share, Shorts and embed URLs are supported.</div></div>
                <div class="va-field"><label for="virtual_tour_url">Virtual tour URL</label><input id="virtual_tour_url" type="url" name="virtual_tour_url" value="<?= esc((string) $value('virtual_tour_url', $isEdit ? $property->virtual_tour_url : '')) ?>" placeholder="https://..."></div>
            </div>
        </div>
    </section>

    <section class="va-card">
        <div class="va-card-head"><div><strong>Amenities</strong><div class="va-help">Select every feature that meaningfully describes this property.</div></div><a class="va-btn va-btn--light va-btn--sm" href="<?= base_url('admin/amenities') ?>" target="_blank">Manage amenities</a></div>
        <div class="va-card-body">
            <?php if (empty($amenities)): ?><div class="va-empty">No amenities have been created yet.</div><?php else: ?>
                <div class="va-check-grid">
                    <?php foreach ($amenities as $amenity): ?>
                        <label class="va-check"><input type="checkbox" name="amenities[]" value="<?= (int) $amenity->id ?>" <?= in_array((int) $amenity->id, array_map('intval', $selectedAmenities ?? []), true) ? 'checked' : '' ?>><i class="fa-solid <?= esc($amenity->icon ?: 'fa-check') ?>" style="color:#9b7615"></i><span><?= esc($amenity->name) ?></span></label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="va-card">
        <div class="va-card-head"><div><strong>Search & social metadata</strong><div class="va-help">Optional. Leave blank and the site generates sensible metadata from the property content.</div></div></div>
        <div class="va-card-body va-form-grid">
            <div class="va-field va-span-2"><label>SEO title</label><input type="text" name="meta_title" maxlength="100" value="<?= esc((string) $value('meta_title', $isEdit ? $property->meta_title : '')) ?>" placeholder="Luxury 4 Bedroom Duplex in Asokoro | Vantage Luxe Realty"></div>
            <div class="va-field va-span-2"><label>Meta description</label><textarea name="meta_description" maxlength="180" placeholder="A concise description for Google and social sharing."><?= esc((string) $value('meta_description', $isEdit ? $property->meta_description : '')) ?></textarea></div>
        </div>
    </section>

    <div class="va-actions-sticky">
        <a class="va-btn va-btn--light" href="<?= base_url('admin/properties') ?>">Cancel</a>
        <?php if ($isEdit): ?><button class="va-btn va-btn--light" type="submit" name="action" value="sold"><i class="fa-solid fa-handshake"></i> Mark sold</button><?php endif; ?>
        <button class="va-btn va-btn--light" type="submit" name="action" value="draft"><i class="fa-regular fa-floppy-disk"></i> Save draft</button>
        <button class="va-btn va-btn--gold" type="submit" name="action" value="publish"><i class="fa-solid fa-paper-plane"></i> <?= $isEdit ? 'Update & publish' : 'Publish property' ?></button>
    </div>
</form>

<?php if ($isEdit && ! empty($existingImages)): ?>
    <?php foreach ($existingImages as $image): ?>
        <form id="primary-image-<?= (int) $image->id ?>" method="post" action="<?= base_url('admin/properties/set-primary-image/' . $image->id) ?>" hidden><?= csrf_field() ?></form>
        <form id="delete-image-<?= (int) $image->id ?>" method="post" action="<?= base_url('admin/properties/delete-image/' . $image->id) ?>" hidden><?= csrf_field() ?></form>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Quill === 'undefined') return;
    const editor = new Quill('#quillEditor', {
        theme: 'snow',
        placeholder: 'Describe the property, location advantages, finishing, services, title documentation and other important details…',
        modules: { toolbar: [[{ header: [2, 3, false] }], ['bold', 'italic'], [{ list: 'ordered' }, { list: 'bullet' }], ['blockquote'], ['clean']] }
    });
    document.getElementById('propertyForm').addEventListener('submit', function () {
        document.getElementById('descriptionInput').value = editor.root.innerHTML;
    });
});
</script>
<?= $this->endSection() ?>
