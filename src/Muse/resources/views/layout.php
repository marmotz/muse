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

    <script src="<?php echo $view['assets']->getUrl('js/jquery.js'); ?>"></script>
    <script src="<?php echo $view['assets']->getUrl('js/fresco.js'); ?>"></script>
</head>

<body>

    <header>
        <h1 id="pageTitle">
            <?php $view['slots']->output('pageTitle', '') ?>
        </h1>
    </header>

    <?php $view['slots']->output('_content') ?>

    <!-- <aside>

        <h2>Sidebar Content</h2>

    </aside> -->

    <footer>
        <p><small>powered by muse</small></p>
    </footer>
</body>
</html>