<!doctype html>
<html lang="en">
<head id="muse" data-template-set="html5-reset">
    <meta charset="utf-8">

    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title>[Muse] <?php $view['slots']->output('title', '') ?></title>

    <meta name="title" content="[Muse] <?php $view['slots']->output('title', '') ?>" />
    <meta name="description" content="Gallery" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" href="<?php echo $view['assets']->getUrl('img/favicon.ico'); ?>" />


    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/reset.css'); ?>" />
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/style.css'); ?>" />
    <link rel="stylesheet" href="<?php echo $view['assets']->getUrl('css/fresco.css'); ?>" />
    <?php $view['slots']->output('stylesheets', '') ?>
</head>

<body id="<?php echo $_controller . $_action; ?>" class="<?php echo $_controller; ?> <?php echo $_action; ?>">
    <header>
        <div id="menu">
            <a href="<?php echo $url('Home') ?>">
                <?php echo $_('menu.home'); ?>
            </a>

            <?php if($_session->has('user')): ?>
                <a>
                    <?php echo $_session->get('user')->getName(); ?>
                </a>

                <a href="<?php echo $url('UserSignOut') ?>">
                    <?php echo $_('menu.signout'); ?>
                </a>
            <?php else: ?>
                <a href="<?php echo $url('UserSignIn') ?>">
                    <?php echo $_('menu.signin'); ?>
                </a>
            <?php endif; ?>
        </div>
        <div id="pageTitle">
            <?php
                $view['slots']->output(
                    'pageTitle',
                    $view['slots']->get(
                        'title',
                        ''
                    )
                );
            ?>
        </div>
    </header>

    <div id="content">
        <?php $view['slots']->output('_content') ?>
    </div>

    <footer>
        <p>
            <small>
                <?php
                    echo $_(
                        'footer.powered',
                        array(
                            '%name%' => '<a href="https://github.com/marmotz/muse">Muse</a>'
                        )
                    );
                ?>
            </small>
        </p>
    </footer>

    <script src="<?php echo $view['assets']->getUrl('js/jquery.js'); ?>"></script>
    <script src="<?php echo $view['assets']->getUrl('js/fresco.js'); ?>"></script>
    <?php $view['slots']->output('javascripts', '') ?>
</body>
</html>