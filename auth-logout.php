<?php

$pdo = require_once __DIR__ . '/database/database.php';

$session = $_COOKIE['sessionId'] ?? '';

if ($session) {

    $statementSession = $pdo->prepare('DELETE FROM session WHERE id = :id');
    $statementSession->bindValue(':id', $session);
    $statementSession->execute();

    setcookie('sessionId', $session, time() - 3600);

    header('Location: /');
}








?>

