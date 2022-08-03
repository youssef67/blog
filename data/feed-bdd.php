<?php

require('./includes/connexionBDD.php');

$filename = __DIR__.'/data/articles.json';
$articles = [];


$articles = json_decode(file_get_contents($filename), TRUE); 


$statement = $bdd->prepare('INSERT INTO articles VALUES (
    DEFAULT,
    :title,
    :image,
    :category,
    :content
)');

foreach ($articles as $a) {

    $statement->bindValue(':title', $a['title']);
    $statement->bindValue(':image', $a['image']);
    $statement->bindValue(':category', $a['category']);
    $statement->bindValue(':content', $a['content']);

    $statement->execute();


    // try {
    // } catch (Exception $e) {
    //     echo mb_strlen($a['title']);
    //     // print_r($a);
    //     print_r($e);
    //     echo $e->getMessage();
    // }
}

// $statement->bindValue(':title', $)
// echo '<pre>';
// print_r($articles);
// echo '</pre>';
