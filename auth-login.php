<?php
require __DIR__ . '/database/database.php';
$articleDB = require_once('./database/models/ArticleDB.php');

const ERROR_REQUIRED            = "Ce champs est obligatoire";
const ERROR_EMAIL               = "L'email est incorrect";
const ERROR_EMAIL_UNKNOWN       = "L'email n'existe pas";
const ERROR_PASSWORD_MISMATCH   = "Le mot de passe est incorrect";
const ERROR_PASSWORD            = "Le password doit faire 6 caractères";

$errors = [
    'email'     => '',
    'password'  => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = filter_var(INPUT_POST, FILTER_VALIDATE_EMAIL);

    $email      = $_POST['email'] ?? "";
    $password   = $_POST['password'] ?? "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ERROR_EMAIL;
    } elseif (!$email) {
        $errors['email'] = ERROR_REQUIRED;
    }

    if (!$password) {
        $errors['password'] = ERROR_REQUIRED;
    } elseif (mb_strlen($password) < 6) {
        $errors['password'] = ERROR_PASSWORD;
    }

    if (!count(array_filter($errors))) {
        $statementUser = $pdo->prepare('SELECT * FROM user WHERE email = :email');
        $statementUser->bindValue(':email', $email);
        $statementUser->execute();
        
        $user = $statementUser->fetch();

        if(!$user) {
            $errors['email'] = ERROR_EMAIL_UNKNOWN;
        } else {
            if (!password_verify($password, $user['password'])) {
                $errors['password'] = ERROR_PASSWORD_MISMATCH;
            } else {
                $statementSession = $pdo->prepare('INSERT INTO session VALUES (DEFAULT, :userid)');
                $statementSession->bindValue(':userid', $user['id']);
                $statementSession->execute();

                $sessionId = $pdo->lastInsertId();
                setcookie('sessionId', $sessionId, time() + 60*60*24*14, "", "", false, true);

                header('Location: /');

            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/login.css">
    <title>Connexion</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
        <div class="block p-20 form-container">
                <h1>Inscription</h1>
                <form action="/auth-login.php" method="POST">
                    <div class="form-control">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?= $email ?? "" ?>">
                        <p class="text-danger">
                            <?= $errors['email'] ?? "" ?>
                        </p>
                    </div>
                    <div class="form-control">
                        <label for="password">mot de passe</label>
                        <input type="password" name="password" id="password">
                        <p class="text-danger">
                            <?= $errors['password'] ?? "" ?>
                        </p>
                    </div>
                    <div class="form-action">
                        <a href="/" class="btn btn-secondary" type="button">Annuler</a>
                        <button class="btn btn-primary" type="submit">Valider</button>
                    </div>
                </form>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>