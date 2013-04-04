<?php $view->extend('layout.php') ?>

<?php $view['slots']->set('title', $_('title.login')) ?>

<?php $view['slots']->start('stylesheets'); ?>
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/signin.css'); ?>" />
<?php $view['slots']->stop(); ?>

<?php $view['slots']->start('javascripts'); ?>
    <!--script src="<?php echo $view['assets']->getUrl('js/signin.js'); ?>"></script-->
<?php $view['slots']->stop(); ?>

<form action="<?php echo $url('UserLogin'); ?>" method="POST">
    <?php if($_session->getFlashBag()->has('error')): ?>
        <div class="message error">
            <?php foreach($_session->getFlashBag()->get('error') as $error): ?>
                <p><?php echo $_($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

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