<?php $pager->setSurroundCount(2) ?>
<nav class="va-pagination" aria-label="Admin pagination">
    <?php if ($pager->hasPrevious()): ?><a href="<?= esc($pager->getPrevious()) ?>"><i class="fa-solid fa-arrow-left"></i></a><?php endif; ?>
    <?php foreach ($pager->links() as $link): ?>
        <?php if ($link['active']): ?><span class="active"><?= esc($link['title']) ?></span><?php else: ?><a href="<?= esc($link['uri']) ?>"><?= esc($link['title']) ?></a><?php endif; ?>
    <?php endforeach; ?>
    <?php if ($pager->hasNext()): ?><a href="<?= esc($pager->getNext()) ?>"><i class="fa-solid fa-arrow-right"></i></a><?php endif; ?>
</nav>
