<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php'; ?>

<!-- Main -->
<div id="main">
    <div class="inner">
        <div class="columns">

            <?php foreach ($profils as $link => $img) : ?>
                <div class="image fit">
                    <a href="<?=$link;?>"><img src="<?=$img?>" alt="" /></a>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>