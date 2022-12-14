<?php

function isLoggedIn() {

    global $pdo;
    $sessionId = $_COOKIE['sessionId'] ?? '';

    if ($sessionId) {
        $statementSession = $pdo->prepare('SELECT * FROM session WHERE id=:id');
        $statementSession->bindValue(':id', $sessionId);
        $statementSession->execute();
        $session = $statementSession->fetch();


        if ($session) {
            $statementUser = $pdo->prepare('SELECT * FROM user WHERE id = :id'); 
            $statementUser->bindValue(':id', $session['userid']);
            $statementUser->execute();

            $user = $statementUser->fetch();
        }
    }

    return $user ?? "";

}



?>