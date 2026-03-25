<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Dashboard') ?> | <?= esc(config('Site')->siteName) ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- <script src="https://unpkg.com/lucide@latest"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; } 
    </style>
</head>
<body class="bg-gray-50 text-gray-900 flex h-screen overflow-hidden">

    <aside class="w-64 bg-white border-r border-gray-200 flex-col hidden md:flex z-20">
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <span class="font-extrabold text-xl text-yellow-600 tracking-tight">
                <?= esc(config('Site')->siteName) ?><span class="text-gray-800">.</span>
            </span>
        </div>
        
       <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <?php 
            // Get the second segment of the URL (e.g., 'properties' from 'admin/properties')
            // If it's empty, default to 'dashboard'
            $currentSegment = service('uri')->getSegment(2) ?: 'dashboard'; 
            
            // DRY: Define our active and inactive Tailwind classes once
            $baseClass     = "flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors ";
            $activeClass   = "bg-yellow-50 text-yellow-700 font-semibold";
            $inactiveClass = "text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium";
            ?>

            <a href="<?= base_url('admin/dashboard') ?>" 
               class="<?= $baseClass . ($currentSegment === 'dashboard' ? $activeClass : $inactiveClass) ?>">
                <i class="fas fa-tachometer-alt w-5 h-5 text-center"></i> Dashboard
            </a>

            <a href="<?= base_url('admin/properties') ?>" 
               class="<?= $baseClass . ($currentSegment === 'properties' ? $activeClass : $inactiveClass) ?>">
               <i class="fas fa-building w-5 h-5 text-center"></i> Properties
            </a>

            <a href="<?= base_url('admin/amenities') ?>" 
               class="<?= $baseClass . ($currentSegment === 'amenities' ? $activeClass : $inactiveClass) ?>">
                <i class="fas fa-list-check w-5 h-5 text-center"></i> Amenities
            </a>
        </nav>
        
        <div class="p-4 border-t border-gray-200">
            <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium transition-colors">
                <i data-lucide="log-out" class="w-5 h-5"></i> Sign Out
            </a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-10">
            <div class="flex items-center gap-4">
                <button class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h1 class="text-xl font-bold text-gray-800"><?= esc($title ?? 'Dashboard') ?></h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-gray-900"><?= esc(session()->get('first_name') ?? 'Admin') ?></p>
                    <p class="text-xs text-gray-500 capitalize"><?= esc(session()->get('role') ?? 'Administrator') ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-700 font-bold border border-indigo-200 shadow-sm">
                    <?= strtoupper(substr(session()->get('first_name') ?? 'A', 0, 1)) ?>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
    
    <!-- <script> lucide.createIcons(); </script> -->
</body>
</html>