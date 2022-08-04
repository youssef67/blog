<?php

$pdo = require_once('./includes/connexionBDD.php');

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? "";

if ($id) {

    $statement = $pdo->prepare('DELETE FROM articles WHERE id = :id');

    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    $statement->execute();
    header('Location: /');
}
