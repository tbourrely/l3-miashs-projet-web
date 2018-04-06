<!-- Footer -->
<footer id="footer">
    <a href="#" class="info fa fa-plus-circle"><span>About</span></a>
    <div class="inner">
        <div class="content">
            <h3>Vous cherchez un match ?</h3>
            <div>
                <?php
                    $link = 'home';
                    $params = [];

                    if (!isset($_SESSION['logged_in'])) { // pas connecté
                        $link = 'loginGET';
                        $text = 'Se connecter';
                    } else {
                        $link = 'profileGET';
                        $text = 'Trouver un match';
                        $params['id'] = "latest";
                    }

                    $link = \Pure\Router\Classes\Router::getCurrentRouter()->url($link, $params);
                ?>
                <a href="<?= $link; ?>" class="connect"><?= $text; ?></a>
            </div>
        </div>
        <div class="copyright">
            <h3>Follow me</h3>
            <ul class="icons">
                <li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
                <li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
                <li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
                <li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
            </ul>
            &copy; Untitled. Design: <a href="https://templated.co">TEMPLATED</a>. Images: <a href="https://unsplash.com/">Unsplash</a>.
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyBaWkAFg4F6jFxM8AFsKoNy7g8guvBpaoM&libraries=places&language=en"></script>
<script src="/src/assets/js/jquery.min.js"></script>
<script src="/src/assets/js/jquery.city-autocomplete.min.js"></script>
<script src="/src/assets/js/skel.min.js"></script>

<script src="/src/assets/js/util.js"></script>
<script src="/src/assets/js/main.js"></script>
<script src="/src/assets/js/custom.js"></script>



</body>
</html>