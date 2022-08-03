<?php

require('./includes/connexionBDD.php');

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? "";

if ($id) {

    $statement = $bdd->prepare('DELETE FROM articles WHERE idArticle = :id');

    $statement->bindValue(':id',$id, PDO::PARAM_INT);

    try {
        $statement->execute();
        header('Location: /');
    } catch (Exception $e) {
        header('Location: /');
    }
} 
