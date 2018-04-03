<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'header.php'; ?>

<div class="container">
    <div class="s-1 e-1 h-4">
        <?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'menu.php'; ?>
    </div>

    <div class="s-5 e-9 h-4 animal-content-container">

        <div class="animal-edit">
            <div class="animal-list">

                <?php foreach ($animals as $animal) : ?>

                    <div class="animal">
                        <img class="animal__photo" src="<?= $animal->photo; ?>" alt="Photo de l'animal">
                        <div class="animal__infos">
                            <div class="animal__type ">Type : <span class="animal__type__inner"><?= ucfirst(strtolower($animal->type)); ?></span></div>
                            <div class="animal__nom">Nom : <span class="animal__nom__inner"><?= $animal->nom; ?></span></div>
                            <div class="animal__race">Race : <span class="animal__race__inner"><?= ucfirst($animal->race); ?></span></div>
                            <div class="animal__age">Age : <span class="animal__age__inner"><?= $animal->age; ?> an<?= ($animal->age > 1) ? 's' : ''; ?></span></div>
                            <div class="animal__ville">Ville : <span class="animal__ville__inner"><?= ucfirst($animal->ville); ?></span></div>
                        </div>

                        <a href="/edit/animal/delete/<?= $animal->idAnimal; ?>" class="animal__delete"><span class="animal__delete__inner fa fa-close"></span></a>
                    </div>

                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <div class="s-9 e-13">
        <div class="animal-add">
            <a href="#" class="animal-add__link">Ajouter</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
