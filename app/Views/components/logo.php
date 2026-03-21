<?php
// Load the global configuration once
$site = config('Site');

// Set up fallbacks in case we call the logo without passing dimensions
$w   = $width ?? 'auto';
$h   = $height ?? 'auto';
$css = $class ?? ''; 
?>
<img src="<?= base_url($site->logoPath) ?>" 
     alt="<?= esc($site->siteName) ?> Logo" 
     width="<?= esc($w) ?>" 
     height="<?= esc($h) ?>" 
     class="<?= esc($css) ?>">