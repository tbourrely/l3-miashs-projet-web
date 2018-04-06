<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php'; ?>

<!-- Main -->
<div id="preview" class="vertical">
    <div class="inner">
        <div class="image fit">
            <img src="<?= $animal->photo; ?>" alt="photo de <?= $animal->nom; ?>" />
        </div>
        <div class="content">
            <header>
                <h2><?= $animal->nom; ?></h2>
            </header>
            <p>
                <?= $animal->description; ?>
            </p>
        </div>
    </div>
    <a href="<?= $previousUrl; ?>" class="nav previous"><span class="fa fa-chevron-left"></span></a>
    <a href="<?= $nextUrl; ?>" class="nav next"><span class="fa fa-chevron-right"></span></a>
</div>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>