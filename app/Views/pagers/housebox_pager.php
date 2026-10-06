<?php $pager->setSurroundCount(2) ?>
<nav class="vl-pagination" aria-label="Property pagination">
    <?php if ($pager->hasPrevious()): ?>
        <a href="<?= esc($pager->getPrevious()) ?>" aria-label="Previous page"><i class="fa-solid fa-arrow-left"></i></a>
    <?php endif; ?>

    <?php foreach ($pager->links() as $link): ?>
        <?php if ($link['active']): ?>
            <span class="active" aria-current="page"><?= esc($link['title']) ?></span>
        <?php else: ?>
            <a href="<?= esc($link['uri']) ?>"><?= esc($link['title']) ?></a>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($pager->hasNext()): ?>
        <a href="<?= esc($pager->getNext()) ?>" aria-label="Next page"><i class="fa-solid fa-arrow-right"></i></a>
    <?php endif; ?>
</nav>
