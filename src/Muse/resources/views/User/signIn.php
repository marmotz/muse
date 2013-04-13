<?php $view->extend('layout.php') ?>

<?php $view['slots']->set('title', $_('title.login')) ?>

<?php $view['slots']->start('stylesheets'); ?>
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/form.user.css'); ?>" />
<?php $view['slots']->stop(); ?>

<form action="<?php echo $url('UserLogin'); ?>" method="POST">
    <?php echo $view->render('errors.php', array('_session' => $_session, '_' => $_)); ?>

    <p>
        <label for="email">
            <?php echo $_('form.signin.email') ?>
        </label>
        <input type="text" id="email" name="email" autofocus="autofocus" />
    </p>

    <p>
        <label for="password">
            <?php echo $_('form.signin.password') ?>
        </label>
        <input type="password" id="password" name="password" />
    </p>

    <p>
        <input type="submit" value="<?php echo $_('form.signin.submit'); ?>" />
    </p>

    <p>
        <a
            class="button"
            href="<?php
                echo $url(
                    'AlbumDisplay',
                    array(
                        'albumPath' => $_session->get('lastAlbumPath', ''),
                        'page'      => $_session->get('lastPage',      1 ),
                        'nbPerPage' => $_session->get('lastNbPerPage', 50),
                    )
                );
            ?>"
        >
            <?php echo $_('form.signin.cancel'); ?>
        </a>
    </p>
</form>