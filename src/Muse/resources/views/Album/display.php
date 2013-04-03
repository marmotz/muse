<?php $view->extend('layout.php') ?>

<?php $view['slots']->set('title', '/' . $albumPath) ?>

<?php $view['slots']->start('pageTitle') ?>
<a href="<?php echo $u->generate('AlbumDisplay', array('album' => '', 'nbPerPage' => $nbPerPage)); ?>">Home</a>
<?php $currentBreadCrumb = ''; ?>
<?php foreach(explode('/', trim($albumPath, '/')) as $breadCrumb): ?>
<?php $currentBreadCrumb .= '/' . $breadCrumb; ?>
&gt; <a href="<?php echo $u->generate('AlbumDisplay', array('album' => ltrim($currentBreadCrumb, '/'), 'nbPerPage' => $nbPerPage)); ?>">
    <?php echo $breadCrumb; ?>
</a>
<?php endforeach; ?>
<?php $view['slots']->stop() ?>


<div id="gallery">
    <?php if(!$isRoot): ?>
    <div class="item folder parent">
        <a href="<?php echo $u->generate('AlbumDisplay', array('album' => dirname($albumPath), 'nbPerPage' => $nbPerPage)); ?>" class="image">
            <img src="<?php echo $view['assets']->getUrl('img/parent.png'); ?>" width="150" height="150" />
        </a>
        <p class="name">
            Parent
        </a>
    </div>
    <?php endif; ?>

    <?php foreach($items->getPaginatedPreviousData() as $item): ?>
    <?php     $currentItemPath = ltrim($albumPath . '/' . $item->getName(), '/'); ?>
    <a
        href="<?php echo $u->generate('PhotoDisplay', array('photo' => $currentItemPath)); ?>"
        class="fresco"
        data-fresco-caption="<?php echo basename($currentItemPath); ?>"
        data-fresco-group="photo"
    ></a>
    <?php endforeach; ?>

    <?php foreach($items->getPaginatedData() as $item): ?>
    <?php     $currentItemPath = ltrim($albumPath . '/' . $item->getName(), '/'); ?>
    <div class="item <?php echo $item->getType(); ?>">
        <?php if($item->isAlbum()): ?>
        <a href="<?php echo $u->generate('AlbumDisplay', array('album' => $currentItemPath, 'nbPerPage' => $nbPerPage)); ?>" class="image">
            <img src="<?php echo $view['assets']->getUrl('img/folder.png'); ?>" width="150" height="150" />
        </a>
        <?php else: ?>
        <a
            href="<?php echo $u->generate('PhotoDisplay', array('photo' => $currentItemPath)); ?>"
            class="image fresco"
            data-fresco-caption="<?php echo basename($currentItemPath); ?>"
            data-fresco-group="photo"
        >
            <img src="<?php echo $u->generate('PhotoThumb', array('photo' => $currentItemPath, 'width' => 150, 'height' => 150)); ?>" width="150" height="150" />
        </a>
        <?php endif; ?>
        <p class="name">
            <?php if($item->isAlbum()): ?>
            <?php echo $item->getName() ?>
            <?php else: ?>
            <?php echo basename($currentItemPath); ?>
            <?php endif; ?>
        </p>
    </div>
    <?php endforeach; ?>

    <?php foreach($items->getPaginatedNextData() as $item): ?>
    <?php     $currentItemPath = ltrim($albumPath . '/' . $item->getName(), '/'); ?>
    <a
        href="<?php echo $u->generate('PhotoDisplay', array('photo' => $currentItemPath)); ?>"
        class="fresco"
        data-fresco-caption="<?php echo basename($currentItemPath); ?>"
        data-fresco-group="photo"
    ></a>
    <?php endforeach; ?>

    <?php if($nbPages > 1): ?>
    <div class="pagination">
        <?php $pages = $items->getPages(); ?>

        <?php if($items->hasPreviousPage()): ?>
        <a
            href="<?php echo $u->generate('AlbumDisplay', array('album' => $albumPath, 'nbPerPage' => $nbPerPage, 'page' => $items->getFirstPage())); ?>"
            class="button first"
        >&nbsp;</a>

        <a
            href="<?php echo $u->generate('AlbumDisplay', array('album' => $albumPath, 'nbPerPage' => $nbPerPage, 'page' => $items->getPreviousPage())); ?>"
            class="button previous"
        >&nbsp;</a>
        <?php else: ?>
        <span class="button first">&nbsp;</span>
        <span class="button previous">&nbsp;</span>
        <?php endif; ?>

        <?php foreach($pages as $linkPage): ?>
        <a
            href="<?php echo $u->generate('AlbumDisplay', array('album' => $albumPath, 'nbPerPage' => $nbPerPage, 'page' => $linkPage)); ?>"
            class="page<?php if((int) $linkPage === (int) $page): ?> current<?php endif; ?>"
        >
            <?php echo $linkPage; ?>
        </a>
        <?php endforeach; ?>

        <?php if($items->hasNextPage()): ?>
        <a
            href="<?php echo $u->generate('AlbumDisplay', array('album' => $albumPath, 'nbPerPage' => $nbPerPage, 'page' => $items->getNextPage())); ?>"
            class="button next"
        >&nbsp;</a>

        <a
            href="<?php echo $u->generate('AlbumDisplay', array('album' => $albumPath, 'nbPerPage' => $nbPerPage, 'page' => $items->getLastPage())); ?>"
            class="button last"
        >&nbsp;</a>
        <?php else: ?>
        <span class="button next">&nbsp;</span>
        <span class="button last">&nbsp;</span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<script>
var maxWidth = 0;

$('#gallery .item').each(
    function() {
        maxWidth = Math.max(maxWidth, $(this).width());
    }
).width(maxWidth);

$('.pagination').each(
    function() {
        var maxWidth = 0;

        $(this).find('.page').each(
            function() {
                maxWidth = Math.max(maxWidth, $(this).width());
            }
        ).width(maxWidth);
    }
);
</script>