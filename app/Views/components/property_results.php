<div class="vl-grid">
    <?php if (empty($properties)): ?>
        <div class="vl-empty">
            <i class="fa-solid fa-house-circle-xmark"></i>
            <h3>No matching properties yet</h3>
            <p class="vl-muted">Try widening the location, property type or price filters.</p>
        </div>
    <?php else: ?>
        <?php foreach ($properties as $property): ?>
            <?= view('components/property_card', ['property' => $property]) ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if (! empty($properties)): ?>
    <?= $pager->links('default', 'housebox_pager') ?>
<?php endif; ?>
