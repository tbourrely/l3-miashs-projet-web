<!DOCTYPE HTML>
<!--
	Radius by TEMPLATED
	templated.co @templatedco
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html lang="fr">
<head>
    <title>Radius by TEMPLATED</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- dependencies -->
    <link rel="stylesheet" type="text/css" href="/src/assets/css/city-autocomplete.min.css">

    <!-- main stylesheets -->
    <link rel="stylesheet" type="text/css" href="/src/assets/css/main.css" />
    <link rel="stylesheet" type="text/css" href="/src/assets/css/grid.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/parts/menu.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/custom.css" />

    <!-- parts -->
    <link rel="stylesheet" type="text/css" href="/src/assets/css/parts/addAnimals.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/parts/editAnimals.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/parts/editProfile.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/parts/footer.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/parts/homepage.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/parts/loginForm.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/parts/logo.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/parts/menu.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/parts/messages.css">
    <link rel="stylesheet" type="text/css" href="/src/assets/css/parts/profil.css">
</head>
<body>

<!-- Header -->
<header id="header"
        class="preview">
    <div class="inner">
        <a href="/" class="button hidden"><span>Let's Go</span></a>
    </div>
</header>

<?php //var_dump($_SESSION); ?>

<div class="message-container">
    <?php if (isset($_SESSION['errors'])) : ?>
        <?php foreach ($_SESSION['errors'] as $errorType) : ?>
            <?php foreach ($errorType as $errorMessage) : ?>
                <div class="message hidden">
                    <div class="message__inner">
                        <?php echo $errorMessage; ?>
                    </div>
                    <div class="message__close"><a class="message__close__cross" href="#"><i class="fa fa-remove"></i></a></div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])) : ?>
        <?php foreach ($_SESSION['success'] as $succesType) : ?>
            <?php foreach ($succesType as $successMessage) : ?>
                <div class="message success hidden">
                    <div class="message__inner">
                        <?php echo $successMessage; ?>
                    </div>
                    <div class="message__close"><a class="message__close__cross" href="#"><i class="fa fa-remove"></i></a></div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
</div>
