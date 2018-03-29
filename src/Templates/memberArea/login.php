<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'header.php'; ?>

<div class="fullVH">
    <div class="h-100">
        <div class="h-100">
            <div class="login">
                <form action="<?php echo $action; ?>" class="login__form" method="POST" enctype="multipart/form-data">

                    <label class="login__form__label" for="login">Login</label>
                    <input type="text" id='login' class="login__form__input" name="login" placeholder="login" required>

                    <label class="login__form__label" for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="login__form__input" placeholder="mot de passe" required>

                    <input type="submit" class="login__form__submit" value="se connecter">
                </form>
            </div>
        </div>
    </div>
</div>


<?php require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'footer.php'; ?>


