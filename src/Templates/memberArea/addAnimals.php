<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'header.php'; ?>

<div class="container">
    <div class="s-1 e-1 h-4">
        <?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'menu.php'; ?>
    </div>

    <div class="s-4 e-10 h-4 animal-add-container" style="">

        <form class="add-animal-form" action="#" method="POST" enctype="multipart/form-data">
            <label class="add-animal-form__label" for="nom">Nom</label>
            <input class="add-animal-form__nom" id="nom" type="text" name="nom" required>

            <label class="add-animal-form__label" for="type">Type</label>
            <select class="add-animal-form__type" id="type" name="type" required>
                <option value="">Choix</option>
                <option value="CHIEN">Chien</option>
                <option value="CHAT">Chat</option>
            </select>

            <label class="add-animal-form__label" for="age">Age</label>
            <input class="add-animal-form__age" id="age" type="number" min="0" max="100" name="age" value="0" required>

            <label class="add-animal-form__label" for="race">Race</label>
            <input class="add-animal-form__race" id="race" type="text" name="race" required>

            <label class="add-animal-form__label" for="ville">Ville</label>
            <input class="add-animal-form__ville" id="ville" type="text" name="ville" required>

            <input class="add-animal-form__photo" id="photo" type="file" name="photo" required>
            <label class="add-animal-form__label" for="photo">Choisissez une image...</label>

            <div class="add-animal-form__submit">
                <input type="submit" value="Ajouter">
            </div>
        </form>

    </div>
</div>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
