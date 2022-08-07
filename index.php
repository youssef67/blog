<?php

$articleDB = require_once('./database/models/ArticleDB.php');


$articles = $articleDB->fetchAll();
$categories = [];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCat = $_GET['cat'] ?? "";

if (count($articles)) {

    $cattmp = array_map(fn ($e) => $e['category'], $articles);

    // Récupérer les différentes catégories et le nombre d'articles par catégories
    $categories = array_reduce($cattmp, function ($acc, $cat) {
        if (isset($acc[$cat])) {
            $acc[$cat]++;
        } else {
            $acc[$cat] = 1;
        }

        return $acc;
    }, []);

    // Récupérer les articles par catégories
    $articlesPerCategory = array_reduce($articles, function ($acc, $article) {

        if (isset($acc[$article['category']])) {
            $acc[$article['category']] = [...$acc[$article['category']], $article];
        } else {
            $acc[$article['category']] = [$article];
        }

        return $acc;
    }, []);
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/index.css">
    <title>Blog</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="newsfeed-container">
                <div class="category-container">
                    <ul>
                        <li class=<?= $selectedCat ? '' : 'cat-active' ?>><a href="/index.php">tous les articles <span class="small">(<?= count($articles) ?>)</span></a></li>
                        <?php foreach ($categories as $catName => $catNum) : ?>
                            <li class="<?= $selectedCat === $catName ? 'cat-active' : '' ?>"><a href="/index.php?cat=<?= $catName ?>"><?= $catName ?> <span class="small">(<?= $catNum ?>)</span></a></li>
                        <?php endforeach; ?>
                    </ul>

                </div>

                <div class="article-category-container">
                    <?php if (!$selectedCat) : ?>
                        <?php foreach ($categories as $cat => $num) : ?>
                            <h2><?= $cat ?></h2>
                            <div class="articles-container">
                                <?php foreach ($articlesPerCategory[$cat] as $a) : ?>
                                    <a href="/show-article.php?id=<?= $a['id'] ?>" class="article block">
                                        <div class="overflow">
                                            <div class="img-container" style="background-image: url(<?= $a['image'] ?>);"></div>
                                        </div>
                                        <h3><?= $a['title'] ?></h3>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <h2><?= $selectedCat ?></h2>
                        <div class="articles-container">
                            <?php foreach ($articlesPerCategory[$selectedCat] as $a) : ?>
                                <a href="/show-article.php?id=<?= $a['id'] ?>" class="article block">
                                    <div class="overflow">
                                        <div class="img-container" style="background-image: url(<?= $a['image'] ?>);"></div>
                                    </div>
                                    <h3><?= $a['title'] ?></h3>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>