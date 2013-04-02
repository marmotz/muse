<?php $view->extend('layout.php') ?>

<?php $view['slots']->set('title', '/' . $albumPath) ?>

<header>
    <h1>
        <a href="<?php echo $u->generate('AlbumDisplay', array('album' => '', 'nbPerPage' => $nbPerPage)); ?>">Home</a>
        <?php $currentBreadCrumb = ''; ?>
        <?php foreach(explode('/', trim($albumPath, '/')) as $breadCrumb): ?>
        <?php $currentBreadCrumb .= '/' . $breadCrumb; ?>
        &gt; <a href="<?php echo $u->generate('AlbumDisplay', array('album' => ltrim($currentBreadCrumb, '/'), 'nbPerPage' => $nbPerPage)); ?>">
            <?php echo $breadCrumb; ?>
        </a>
        <?php endforeach; ?>
    </h1>
</header>

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

    <?php foreach($items as $item): ?>
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
</div>

<script>
var maxWidth = 0;

$('#gallery .item').each(
    function() {
        maxWidth = Math.max(maxWidth, $(this).width());
    }
).width(maxWidth);
</script>