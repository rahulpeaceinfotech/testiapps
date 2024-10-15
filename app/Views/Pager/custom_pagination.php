<!-- app/Views/Pager/custom_pagination.php -->
<div class="pagination">
    <?php if ($pager->hasPreviousPage()): ?>
        <a href="<?= $pager->getPreviousPage() ?>" class="btn btn-primary">Previous</a>
    <?php endif; ?>

    <?php foreach ($pager->getLinks() as $link): ?>
        <a href="<?= $link['uri'] ?>" class="btn btn-secondary <?= $link['active'] ? 'active' : '' ?>">
            <?= $link['title'] ?>
        </a>
    <?php endforeach; ?>

    <?php if ($pager->hasNextPage()): ?>
        <a href="<?= $pager->getNextPage() ?>" class="btn btn-primary">Next</a>
    <?php endif; ?>
</div>
