<?php

$filename = __DIR__ . '/data/articles.json';

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? "";


if ($id) {

    $articles = json_decode(file_get_contents($filename), true);
    $articleIndex = array_search($id, array_column($articles, 'id'));

    if (count($articles)) {

        array_splice($articles, $articleIndex, 1);

        file_put_contents($filename, json_encode($articles));

        header('Location: /');
    }
} else {
    header('Location: /');
}
