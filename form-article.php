<?php

$pdo = require_once('./includes/connexionBDD.php');

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

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? "";

if ($id) {

    $articleIndex = array_search($id, array_column($articles, 'id'));

    $article = $articles[$articleIndex];

    $title = $article['title'] ?? '';
    $image = $article['image'] ?? '';
    $category = $article['category'] ?? '';
    $content = $article['content'] ?? '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les articles 

    $_POST = filter_input_array(INPUT_POST, [
        'title'     => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'image'     => FILTER_SANITIZE_URL,
        'category'  => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'content'   => [
            'filter'    => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flag'      => FILTER_FLAG_NO_ENCODE_QUOTES
        ]
    ]);

    $title = $_POST['title'] ?? '';
    $image = $_POST['image'] ?? '';
    $category = $_POST['category'] ?? '';
    $content = $_POST['content'] ?? '';

    // Gestion erreurs

    //Titre
    if (!$title) {
        $errors['title'] = ERROR_REQUIRED;
    } elseif (mb_strlen($title) < 10) {
        $errors['title'] = ERROR_TITLE_TOO_SHORT;
    }

    // Image
    if (!$image) {
        $errors['image'] = ERROR_REQUIRED;
    } elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors['image'] = ERROR_IMAGE_URL;
    }

    // Categorie
    if (!$category) {
        $errors['category'] = ERROR_REQUIRED;
    }

    // Contenu
    if (!$content) {
        $errors['content'] = ERROR_REQUIRED;
    } elseif (mb_strlen($content) < 20) {
        $errors['content'] = ERROR_CONTENT_TOO_SHORT;
    }

    if (!count(array_filter($errors))) {
        if ($id) {
            $articles[$articleIndex]['title'] = $title;
            $articles[$articleIndex]['image'] = $image;
            $articles[$articleIndex]['category'] = $category;
            $articles[$articleIndex]['content'] = $content;
        } else {
            $articles = [...$articles, [
                'id'   => time(),
                'title' => $title,
                'image' => $image,
                'category' => $category,
                'content' => $content
            ]];
        }

        file_put_contents($filename, json_encode($articles));

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
    <link rel="stylesheet" href="/public/css/form-article.css">
    <title><?= $id ? 'Modifier' : 'Créer' ?> un article'</title>
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
                            <option <?= $category === 'nature' ? 'selected' : '' ?> value="nature">Nature</option>
                            <option <?= $category === 'politique' ? 'selected' : '' ?> value="politique">Politique</option>
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