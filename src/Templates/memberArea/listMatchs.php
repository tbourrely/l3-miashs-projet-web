<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'header.php'; ?>

<div class="container">
    <div class="s-1 e-1 h-4">
        <?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'menu.php'; ?>
    </div>

    <div class="s-4 e-10 h-4 animal-content-container">

        <?php if (!empty($animals)) : ?>
            <div class="animal-edit">
                <div class="animal-list">

                    <?php foreach ($animals->getIterator() as $animal) : ?>

                        <?php $compte = $animal->getCompte(); ?>

                        <div class="animal">
                            <div class="animal__photo">
                                <img class="animal__photo__inner" src="<?= $animal->photo; ?>" alt="Photo de l'animal">
                            </div>
                            <div class="animal__infos animal__infos--match">
                                <div class="animal__type ">Type : <span class="animal__type__inner"><?= ucfirst(strtolower($animal->type)); ?></span></div>
                                <div class="animal__nom">Nom : <span class="animal__nom__inner"><?= $animal->nom; ?></span></div>
                                <div class="animal__race">Race : <span class="animal__race__inner"><?= ucfirst($animal->race); ?></span></div>
                                <div class="animal__age">Age : <span class="animal__age__inner"><?= $animal->age; ?> an<?= ($animal->age > 1) ? 's' : ''; ?></span></div>
                                <div class="animal__ville">Ville : <span class="animal__ville__inner"><?= ucfirst($animal->ville); ?></span></div>
                                <div class="animal__email">Propriétaire : <span class="animal__email__inner"><?= $compte->email; ?></span><a href="mailto:<?= $compte->email; ?>" class="animal__contact"><span class="animal__contact_inner fa fa-envelope-o"></span></a></div>
                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        <?php else : ?>
            <div class="no-match">
                <h2>Aucun animal trouvé...</h2>
            </div>
        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
