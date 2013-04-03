<?php $view->extend('layout.php') ?>

<?php $view['slots']->set('title', $_('title.login')) ?>

<?php $view['slots']->start('stylesheets'); ?>
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/login.css'); ?>" />
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start('javascripts'); ?>
    <script src="<?php echo $view['assets']->getUrl('js/login.js'); ?>"></script>
<?php $view['slots']->stop(); ?>

<form action="<?php echo $url('UserLogin'); ?>" method="POST">
    <p>
        <label for="login">
            <?php echo $_('form.login.login') ?>
        </label>
        <input type="text" id="login" name="login" />
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