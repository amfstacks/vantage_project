<?php $pager->setSurroundCount(2) ?>

<nav class="flex items-center justify-between" aria-label="Pagination">
    <div class="hidden sm:block">
        <p class="text-sm text-gray-700">
            Showing page <span class="font-medium text-gray-900"><?= $pager->getCurrentPageNumber() ?></span> of 
            <span class="font-medium text-gray-900"><?= $pager->getPageCount() ?></span>
        </p>
    </div>
    
    <div class="flex-1 flex justify-between sm:justify-end gap-2">
        <?php if ($pager->hasPrevious()) : ?>
            <a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" 
               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                &laquo; Previous
            </a>
        <?php else: ?>
            <span class="relative inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                &laquo; Previous
            </span>
        <?php endif ?>

        <?php if ($pager->hasNext()) : ?>
            <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" 
               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Next &raquo;
            </a>
        <?php else: ?>
            <span class="relative inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-400 bg-gray-50 cursor-not-allowed">
                Next &raquo;
            </span>
        <?php endif ?>
    </div>
</nav>