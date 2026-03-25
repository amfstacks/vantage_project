<?php $pager->setSurroundCount(2) ?>

<div class="pagination-area">
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($pager->hasPrevious()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Previous">
                        <i class="fa-solid fa-angle-left"></i>
                    </a>
                </li>
            <?php endif ?>

            <?php foreach ($pager->links() as $link) : ?>
                <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                    <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
                </li>
            <?php endforeach ?>

            <?php if ($pager->hasNext()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Next">
                        <i class="fa-solid fa-angle-right"></i>
                    </a>
                </li>
            <?php endif ?>
        </ul>
    </nav>
</div>