<?php $view->extend('layout.php') ?>

<?php $view['slots']->set('title', $_('title.login')) ?>

<?php $view['slots']->start('stylesheets'); ?>
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/form.user.css'); ?>" />
<?php $view['slots']->stop(); ?>

<form action="<?php echo $url('UserLogin'); ?>" method="POST">
    <?php echo $view->render('errors.php', array('_session' => $_session, '_' => $_)); ?>

    <p>
        <label for="email">
            <?php echo $_('form.login.email') ?>
        </label>
        <input type="text" id="email" name="email" />
    </p>

    <p>
        <label for="password">
            <?php echo $_('form.login.password') ?>
        </label>
        <input type="password" id="password" name="password" />
    </p>

    <p>
        <input type="submit" value="<?php echo $_('form.login.submit'); ?>" />
    </p>
</form>