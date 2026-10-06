<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc><?= esc(base_url('/'), 'html') ?></loc><changefreq>daily</changefreq><priority>1.0</priority></url>
    <url><loc><?= esc(base_url('properties'), 'html') ?></loc><changefreq>daily</changefreq><priority>0.9</priority></url>
    <url><loc><?= esc(base_url('about'), 'html') ?></loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
    <url><loc><?= esc(base_url('contact'), 'html') ?></loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
    <?php foreach ($properties as $property): ?>
        <url>
            <loc><?= esc(base_url('property/' . $property->slug), 'html') ?></loc>
            <?php if (! empty($property->updated_at)): ?><lastmod><?= esc(date('c', strtotime($property->updated_at)), 'html') ?></lastmod><?php endif; ?>
            <changefreq>weekly</changefreq><priority>0.8</priority>
        </url>
    <?php endforeach; ?>
</urlset>
