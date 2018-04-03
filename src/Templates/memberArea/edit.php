<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'header.php'; ?>

<div class="container">
    <div class="s-1 e-1 h-4">
        <?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'menu.php'; ?>
    </div>

    <div class="s-1 e-13 h-4">
        <div class="profil-edit">
            <h2>Modification de votre profil</h2>
            <form class="profil-edit__form" action="<?php echo $action;?>" method="POST">
                <label for="email" class="text--medium fw-400">Email</label>
                <input type="email" id="email" name="email" value="<?= $currentEmail ?>">

                <label for="password" class="text--medium fw-400">Mot de passe</label>
                <input type="password" id="password" name="password">

                <label for="password2" class="text--medium fw-400">Veuillez confirmer votre mot de passe</label>
                <input type="password" id="password2" name="password2">

                <input type="submit" value="Modifier">
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'footer.php'; ?>
