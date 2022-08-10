<?php
$pdo = require_once __DIR__ . '/database/database.php';
$articleDB = require_once('./database/models/ArticleDB.php');
require_once __DIR__ './database/security.php';

$currentUser = isLoggedIn();
if (!$currentUser) {
    header('Location: /');
}

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? "";

if ($id) {
    $article = $articleDB->fetchOne($id);

    if ($article['author'] !== $currentUser['id']) {
        header('Location: /');
    }

    $articleDB->deleteOne($id);

    if (isset($_GET['origin'])) {
        header('Location: /auth-profile.php');
    } else {
        header('Location: /');
    }
}
