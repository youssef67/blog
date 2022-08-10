<?php
$pdo = require_once __DIR__ . '/database/database.php';
$articleDB = require_once('./database/models/ArticleDB.php');
require_once __DIR__ . '/database/security.php';

$articles = [];

$currentUser = isLoggedIn();
if (!$currentUser) {
    header('Location: /');
}

$articles = $articleDB->fetchAllUserArticles($currentUser['id']);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/profil.css">
    <title>Mon profile</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <h1>Mon espace</h1>
            <h2>Mes informations</h2>
            <div class="info-container">
                <ul>
                    <li>
                        <strong>Prénom :</strong>
                        <p><?= $currentUser['firstname'] ?></p>
                    </li>
                    <li>
                        <strong>Nom :</strong>
                        <p><?= $currentUser['lastname'] ?></p>
                    </li>
                    <li>
                        <strong>Email :</strong>
                        <p><?= $currentUser['email'] ?></p>
                    </li>
                </ul>
            </div>
            <h3>Mes articles</h3>
            <div class="articles-list">
                <ul>
                    <?php foreach ($articles as $a): ?>
                    <li>
                        <span><?= $a['title']?></span>
                        <div class="article-actions">
                            <a href="/form-article.php?id=<?= $a['id'] ?>" class="btn btn-primary btn-small">Modifier</a>
                            <a href="/delete-article.php?id=<?= $a['id'] ?>&origin=profil" class="btn btn-secondary btn-small">Supprimer</a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>