<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php'; ?>

<!-- Main -->
<div id="preview" class="vertical profil">
    <div class="inner profil__inner">
        <div class="profil__content">
            <div class="image fit">
                <img src="<?= $animal->photo; ?>" alt="photo de <?= $animal->nom; ?>" />
            </div>
            <div class="content">
                <header>
                    <h2><?= ucfirst(strtolower($animal->type)); ?> | <?= $animal->nom; ?></h2>
                </header>

                <div class="profil__age">Age : <span class="fw-400"><?= ucfirst(strtolower($animal->age)); ?> an(s)</span></div>
                <div class="profil__race">Race : <span class="fw-400"><?= ucfirst(strtolower($animal->race)); ?></span></div>
                <div class="profil__ville">Ville : <span class="fw-400"><?= ucfirst(strtolower($animal->ville)); ?></span></div>
                <p class="profil__desc">
                    <?= $animal->description; ?>
                </p>

                <a href="<?= \Pure\Router\Classes\Router::getCurrentRouter()->url('match', ['match' => $animal->idAnimal]) ?>" class="match-button">
                    <svg class="heart" viewBox="0 0 32 29.6">
                        <path d="M23.6,0c-3.4,0-6.3,2.7-7.6,5.6C14.7,2.7,11.8,0,8.4,0C3.8,0,0,3.8,0,8.4c0,9.4,9.5,11.9,16,21.2
	c6.1-9.3,16-12.1,16-21.2C32,3.8,28.2,0,23.6,0z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <?php if (!empty($previousId)) : ?>
        <a href="<?= \Pure\Router\Classes\Router::getCurrentRouter()->url('profileGET', ['id' => $previousId]); ?>" class="nav previous"><span class="fa fa-chevron-left"></span></a>
    <?php endif; ?>

    <?php if(!empty($nextId)) : ?>
        <a href="<?= \Pure\Router\Classes\Router::getCurrentRouter()->url('profileGET', ['id' => $nextId]); ?>" class="nav next"><span class="fa fa-chevron-right"></span></a>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>