<?php


try {
    $bdd = new PDO("mysql:host=localhost;dbname=blog", "root", "kurosaki", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo $e->getMessage();
}

return $bdd;
