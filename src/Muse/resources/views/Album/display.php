<?php $view->extend('layout.php') ?>

<?php $view['slots']->set('title', '/' . $albumPath) ?>

<?php $view['slots']->start('stylesheets'); ?>
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/gallery.css'); ?>" />
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/pagination.css'); ?>" />
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start('javascripts'); ?>
    <script src="<?php echo $view['assets']->getUrl('js/gallery.js'); ?>"></script>
    <script src="<?php echo $view['assets']->getUrl('js/pagination.js'); ?>"></script>
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start('pageTitle') ?>
    <a href="<?php echo $url('Home') ?>">
        <?php echo $_('breadcrumb.home'); ?>
    </a>
    <?php if(trim($albumPath, '/') !== ''): ?>
        <?php $currentBreadCrumb = ''; ?>
        <?php foreach(explode('/', trim($albumPath, '/')) as $breadCrumb): ?>
            <?php $currentBreadCrumb .= '/' . $breadCrumb; ?>
            &gt; <a href="<?php echo $url('AlbumDisplay', array('albumPath' => ltrim($currentBreadCrumb, '/'), 'nbPerPage' => $nbPerPage)); ?>">
                <?php echo $breadCrumb; ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
<?php $view['slots']->stop() ?>


<?php if($_session->has('user') && $_session->get('user')->isAdmin()): ?>
    <div id="tools">
        <?php if(!$protection): ?>
            <a href="<?php echo $url('AlbumProtect', array('albumPath' => $albumPath)) ?>">
                <?php echo $_('tools.protect'); ?>
            </a>
        <?php elseif($protection->getPath() === $albumPath): ?>
            <a href="<?php echo $url('AlbumUnprotect', array('albumPath' => $albumPath)) ?>">
                <?php echo $_('tools.unprotect'); ?>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>


<?php if(!$isRoot): ?>
    <div class="item folder parent">
        <a href="<?php echo $url('AlbumDisplay', array('albumPath' => dirname($albumPath), 'nbPerPage' => $nbPerPage)); ?>" class="image">
            <img src="<?php echo $view['assets']->getUrl('img/parent.png'); ?>" width="150" height="150" />
        </a>
        <p class="name">
            <?php echo $_('gallery.parent') ?>
        </a>
    </div>
<?php endif; ?>

<?php foreach($items->getPaginatedPreviousData() as $item): ?>
    <?php $currentItemPath = ltrim($albumPath . '/' . $item->getName(), '/'); ?>
    <a
        href="<?php echo $url('PhotoDisplay', array('photo' => $currentItemPath)); ?>"
        class="fresco"
        data-fresco-caption="<?php echo basename($currentItemPath); ?>"
        data-fresco-group="photo"
    ></a>
<?php endforeach; ?>

<?php foreach($items->getPaginatedData() as $item): ?>
    <?php $currentItemPath = ltrim($albumPath . '/' . $item->getName(), '/'); ?>
    <div class="item <?php echo $item->getType(); ?>">
        <?php if($item->isAlbum()): ?>
            <a href="<?php echo $url('AlbumDisplay', array('albumPath' => $currentItemPath, 'nbPerPage' => $nbPerPage)); ?>" class="image">
                <img src="<?php echo $view['assets']->getUrl('img/folder.png'); ?>" width="150" height="150" />
            </a>
        <?php else: ?>
            <a
                href="<?php echo $url('PhotoDisplay', array('photo' => $currentItemPath)); ?>"
                class="image fresco"
                data-fresco-caption="<?php echo basename($currentItemPath); ?>"
                data-fresco-group="photo"
            >
                <img src="<?php echo $url('PhotoThumb', array('photo' => $currentItemPath, 'width' => 150, 'height' => 150)); ?>" width="150" height="150" />
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
    <?php $currentItemPath = ltrim($albumPath . '/' . $item->getName(), '/'); ?>
    <a
        href="<?php echo $url('PhotoDisplay', array('photo' => $currentItemPath)); ?>"
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
                href="<?php echo $url('AlbumDisplay', array('albumPath' => $albumPath, 'nbPerPage' => $nbPerPage, 'page' => $items->getFirstPage())); ?>"
                class="button first"
            >&nbsp;</a>

            <a
                href="<?php echo $url('AlbumDisplay', array('albumPath' => $albumPath, 'nbPerPage' => $nbPerPage, 'page' => $items->getPreviousPage())); ?>"
                class="button previous"
            >&nbsp;</a>
        <?php else: ?>
            <span class="button first">&nbsp;</span>
            <span class="button previous">&nbsp;</span>
        <?php endif; ?>

        <?php foreach($pages as $linkPage): ?>
            <a
                href="<?php echo $url('AlbumDisplay', array('albumPath' => $albumPath, 'nbPerPage' => $nbPerPage, 'page' => $linkPage)); ?>"
                class="page<?php if((int) $linkPage === (int) $page): ?> current<?php endif; ?>"
            >
                <?php echo $linkPage; ?>
            </a>
        <?php endforeach; ?>

        <?php if($items->hasNextPage()): ?>
            <a
                href="<?php echo $url('AlbumDisplay', array('albumPath' => $albumPath, 'nbPerPage' => $nbPerPage, 'page' => $items->getNextPage())); ?>"
                class="button next"
            >&nbsp;</a>

            <a
                href="<?php echo $url('AlbumDisplay', array('albumPath' => $albumPath, 'nbPerPage' => $nbPerPage, 'page' => $items->getLastPage())); ?>"
                class="button last"
            >&nbsp;</a>
        <?php else: ?>
            <span class="button next">&nbsp;</span>
            <span class="button last">&nbsp;</span>
        <?php endif; ?>
    </div>
<?php endif; ?>