<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php'; ?>

<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === 1) : ?>

    <div class="container">
        <div class="s-1 e-1 h-4">
            <?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'menu.php'; ?>
        </div>

        <div class="s-1 e-13 h-4 row">
            <div class="home--connected">
                <div class="logo"></div>
                <div class="home--connected__text">
                    <h2>Bienvenue <span class="username"><?php echo (isset($_SESSION['user']['login'])) ? $_SESSION['user']['login'] : ''; ?></span> !</h2>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Main -->
    <div id="main">
        <div class="inner">
            <div class="columns">

                <?php foreach ($profils as $img => $link) : ?>
                    <div class="image fit">
                        <a href="<?=$link;?>"><img src="<?=$img?>" alt="" /></a>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>