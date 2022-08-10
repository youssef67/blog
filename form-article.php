<?php
$pdo = require_once __DIR__ . '/database/database.php';
$articleDB = require_once('./database/models/ArticleDB.php');
require_once __DIR__ . '/database/security.php';

$currentUser = isLoggedIn();

if (!$currentUser) {
    header('Location: /');
}
const ERROR_REQUIRED            = "Ce champs est obligatoire";
const ERROR_TITLE_TOO_SHORT     = "Le titre doit faire au minimum 10 caractères";
const ERROR_CONTENT_TOO_SHORT   = "Le contenu doit faire au minimum 20 caractères";
const ERROR_IMAGE_URL           = "L'image doit être une url valide";

$errors = [
    'title'     => '',
    'content'   => '',
    'image'     => '',
    'category'  => ''
];
$category = '';

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? "";

if ($id) {

    $article = $articleDB->fetchOne($id);

    if ($article['author'] !== $currentUser['id']) {
        header('Location: /');
    }

    $idArticle = $article['id'] ?? '';
    $title = $article['title'] ?? '';
    $image = $article['image'] ?? '';
    $category = $article['category'] ?? '';
    $content = $article['content'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les articles 

    $_POST = filter_input_array(INPUT_POST, [
        'title'     => FILTER_SANITIZE_SPECIAL_CHARS,
        'image'     => FILTER_SANITIZE_URL,
        'category'  => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'content'   => [
            'filter'    => FILTER_SANITIZE_SPECIAL_CHARS,
            'flag'      => FILTER_FLAG_NO_ENCODE_QUOTES
        ]
    ]);

    $article = [...$_POST];

    // Gestion erreurs

    //Titre
    if (!$article['title']) {
        $errors['title'] = ERROR_REQUIRED;
    } elseif (mb_strlen($article['title']) < 10) {
        $errors['title'] = ERROR_TITLE_TOO_SHORT;
    }

    // Image
    if (!$article['image']) {
        $errors['image'] = ERROR_REQUIRED;
    } elseif (!filter_var($article['image'], FILTER_VALIDATE_URL)) {
        $errors['image'] = ERROR_IMAGE_URL;
    }

    // Categorie
    if (!$article['category']) {
        $errors['category'] = ERROR_REQUIRED;
    }

    // Contenu
    if (!$article['content']) {
        $errors['content'] = ERROR_REQUIRED;
    } elseif (mb_strlen($article['content']) < 20) {
        $errors['content'] = ERROR_CONTENT_TOO_SHORT;
    }

    if (!count(array_filter($errors))) {
        if ($id) {
            $articleDB->updateOne(['id' => $id, ...$article, 'author' => $currentUser['id']]); 
        } else {
            $articleDB->createOne([...$article, 'author' => $currentUser['id']]);
        }
        header('location: /');
    } else {
        var_dump('pas ok');
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/style.css">
    <title><?= $id ? 'Modifier' : 'Créer' ?> un article</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="block p-20 form-container">
                <h1><?= $id ? 'Modifier' : 'Créer' ?> un article</h1>
                <form action="/form-article.php<?= $id ? "?id=$id" : '' ?>" method="POST">
                    <div class="form-control">
                        <label for="title">Titre</label>
                        <input type="text" name="title" id="title" value="<?= $title ?? "" ?>">
                        <p class="text-danger">
                            <?= $errors['title'] ?? "" ?>
                        </p>
                    </div>
                    <div class="form-control">
                        <label for="image">Image</label>
                        <input type="text" name="image" id="image" value="<?= $image ?? "" ?>">
                        <p class="text-danger">
                            <?= $errors['image'] ?? "" ?>
                        </p>
                    </div>
                    <div class="form-control">
                        <label for="category">Catégories</label>
                        <select name="category" id="category" value="<?= $category ?? "" ?>">
                            <option <?= !$category || $category === 'technologie' ? 'selected' : '' ?> value="technologie">Technologie</option>
                            <option <?= !$category || $category === 'nature' ? 'selected' : '' ?> value="nature">Nature</option>
                            <option <?= !$category || $nature === 'politique' ? 'selected' : '' ?> value="politique">Politique</option>
                        </select>
                        <p class="text-danger">
                            <?= $errors['category'] ?? "" ?>
                        </p>
                    </div>
                    <div class="form-control">
                        <label for="content">Contenu</label>
                        <textarea name="content" id="content"><?= $content ?? "" ?></textarea>
                        <p class="text-danger">
                            <?= $errors['content'] ?? "" ?>
                        </p>
                    </div>
                    <div class="form-action">
                        <a href="/" class="btn btn-secondary" type="button">Annuler</a>
                        <a href="/form-article.php">
                            <button class="btn btn-primary"><?= $id ? 'Modifier' : 'Sauvegarder' ?></button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>