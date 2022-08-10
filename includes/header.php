<?php

$currentUser = $currentUser ?? false;

?>


<header>
    <a href="/" class="logo">Dyma Blog</a>
    <ul class="header-menu">
        <?php if ($currentUser) : ?>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
                <a href="/form-article.php">Ecrire un article</a>
            </li>
            <li>
                <a href="/auth-logout.php">Deconnexion</a>
            </li>
            <li class="<?= $_SERVER['REQUEST_URI'] === '/auth-profile.php' ? 'active' : '' ?> header-profil">
                <a href="/auth-profile.php"><?= $currentUser['firstname'][0] . $currentUser['lastname'][0]?></a>
            </li>
        <?php else : ?>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/auth-login.php' ? 'active' : '' ?>>
                <a href="/auth-login.php">Connexion</a>
            </li>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/auth-register.php' ? 'active' : '' ?>>
                <a href="/auth-register.php">S'inscrire</a>
            </li>
        <?php endif; ?>
    </ul>
</header>