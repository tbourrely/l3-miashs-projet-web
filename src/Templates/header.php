<!DOCTYPE HTML>
<!--
	Radius by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
<head>
    <title>Radius by TEMPLATED</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/src/assets/css/main.css" />
    <link rel="stylesheet" href="/src/assets/css/grid.css">
    <link rel="stylesheet" href="/src/assets/css/menu.css">
    <link rel="stylesheet" href="/src/assets/css/custom.css" />
    <link rel="stylesheet" href="/src/assets/css/menu.css">
</head>
<body>

<!-- Header -->
<header id="header"
        class="preview">
    <div class="inner">
<!--        <div class="content">-->
<!--            <h1>Radius</h1>-->
<!--            <h2>A fully responsive masonry-style<br />-->
<!--                portfolio template.</h2>-->
<!--            --><?php //echo (isset($isHome) && $isHome === true) ? '<a href="#" class="button big alt"><span>Let\'s Go</span></a>' : ''; ?>
<!--        </div>-->
        <a href="/" class="button hidden"><span>Let's Go</span></a>
    </div>
</header>

<?php //var_dump($_SESSION); ?>

<div class="errors">
    <?php if (isset($_SESSION['errors']['login'])) : ?>
        <?php foreach ($_SESSION['errors']['login'] as $errorMessage) : ?>
            <div class="errors__message hidden">
                <div class="errors__message__inner">
                    <?php echo $errorMessage; ?>
                </div>
                <div class="errors__message__close"><a class="errors__message__close__cross" href="#"><i class="fa fa-remove"></i></a></div>
            </div>
        <?php endforeach; ?>

        <?php unset($_SESSION['errors']['login']); ?>
    <?php endif; ?>
</div>
