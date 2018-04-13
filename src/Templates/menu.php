<!--    Made by Erik Terwan    -->
<!--   24th of November 2015   -->
<!--    All rights reserved    -->
<nav id="menuContainer">
    <div id="menuToggle">
        <!--
        A fake / hidden checkbox is used as click reciever,
        so you can use the :checked selector on it.
        -->
        <input type="checkbox" />

        <!--
        Some spans to act as a hamburger.

        They are acting like a real hamburger,
        not that McDonalds stuff.
        -->
        <span></span>
        <span></span>
        <span></span>

        <!--
        Too bad the menu has to be inside of the button
        but hey, it's pure CSS magic.
        -->
        <ul id="menu">
            <li><a href="<?= \Pure\Router\Classes\Router::getCurrentRouter()->url('editGET'); ?>">Mon Profil</a></li>
            <li><a href="<?= \Pure\Router\Classes\Router::getCurrentRouter()->url('editAnimauxGET'); ?>">Mes Animaux</a></li>
            <li><a href="<?= \Pure\Router\Classes\Router::getCurrentRouter()->url('listMatchs'); ?>">Mes Matchs</a></li>
            <li><a href="<?= \Pure\Router\Classes\Router::getCurrentRouter()->url('profileGET', ['id' => 'latest']); ?>">Trouver un match</a></li>
            <li><a href="<?= \Pure\Router\Classes\Router::getCurrentRouter()->url('logout'); ?>">Déconnexion</a></li>
        </ul>
    </div>
</nav>