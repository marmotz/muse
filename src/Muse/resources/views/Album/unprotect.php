<?php $view->extend('layout.php') ?>

<?php $view['slots']->set('title', $_('title.unprotect')) ?>

<?php $view['slots']->start('stylesheets'); ?>
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/form.user.css'); ?>" />
<?php $view['slots']->stop(); ?>

<form action="<?php echo $url('AlbumDecrypt', array('albumPath' => $albumPath)); ?>" method="POST">
    <?php echo $view->render('errors.php', array('_session' => $_session, '_' => $_)); ?>

    <p class="info">
        <?php echo $_('form.unprotect.intro') ?>
    </p>

    <p>
        <label for="password">
            <?php echo $_('form.unprotect.password') ?>
        </label>
        <input type="password" id="password" name="password" />
    </p>

    <p>
        <input type="submit" value="<?php echo $_('form.unprotect.submit'); ?>" />
    </p>
</form>