<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>
        <?= esc($title ?? config('Site')->defaultTitle) ?> | <?= esc(config('Site')->siteName) ?>
    </title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    <?= $this->renderSection('content') ?>

    <footer class="mt-8 text-center text-sm text-gray-500">
        <p>Need help? Contact us at <?= esc(config('Site')->contactPhone) ?> or <?= esc(config('Site')->contactEmail) ?></p>
        <p>&copy; <?= date('Y') ?> <?= esc(config('Site')->siteName) ?>. All rights reserved.</p>
    </footer>

</body>
</html>