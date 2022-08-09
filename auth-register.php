<?php

require __DIR__ . '/database/database.php';

const ERROR_REQUIRED            = "Ce champs est obligatoire";
const ERROR_STRING_TOO_SHORT    = "Le champs doit faire au minimum 3 caractères";
const ERROR_EMAIL               = "L'email est incorrect";
const ERROR_PASSWORD            = "Le password doit faire 6 caractères";
const ERROR_CONFIRME_PASSWORD   = "la confirmation du mot de passe est incorrect";

$errors = [
    'firstname'     => '',
    'lastname'   => '',
    'email'     => '',
    'password'  => '',
    'confirmPassword'  => ''
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les articles 

    $_POST = filter_input_array(INPUT_POST, [
        'firstname' => FILTER_SANITIZE_SPECIAL_CHARS,
        'lastname' => FILTER_SANITIZE_SPECIAL_CHARS,
        'email' => FILTER_VALIDATE_EMAIL,
        'password' => '',
        'confirmPassword' => ''
    ]);

    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if (!$firstname) {
        $errors['firstname'] = ERROR_REQUIRED;
    } elseif (mb_strlen($firstname) < 3) {
        $errors['firstname'] = ERROR_STRING_TOO_SHORT;
    }

    if (!$lastname) {
        $errors['lastname'] = ERROR_REQUIRED;
    } elseif (mb_strlen($lastname) < 3) {
        $errors['lastname'] = ERROR_STRING_TOO_SHORT;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ERROR_EMAIL;
    } elseif (!$email) {
        $errors['email'] = ERROR_REQUIRED;
    }

    if (!$password) {
        $errors['password'] = ERROR_REQUIRED;
    } elseif (mb_strlen($password) < 6) {
        $errors['password'] = ERROR_PASSWORD;
    } elseif ($password !== $confirmPassword) {
        $errors['confirmPassword'] = ERROR_CONFIRME_PASSWORD;
    }

    if (!count(array_filter($errors))) {
        $statementUser = $pdo->prepare('INSERT INTO user VALUES (
            DEFAULT,
            :firstname,
            :lastname,
            :email,
            :password
            )');

            $hashPassword = password_hash($password, PASSWORD_ARGON2I);
            $statementUser->bindValue(':firstname', $firstname);
            $statementUser->bindValue(':lastname', $lastname);
            $statementUser->bindValue(':email', $email);
            $statementUser->bindValue(':password', $hashPassword);

            $statementUser->execute();

            header('Location: ./auth-login.php');
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/register.css">
    <title>Inscription</title>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="block p-20 form-container">
                <h1>Inscription</h1>
                <form action="/auth-register.php" method="POST">
                    <div class="form-control">
                        <label for="firstname">Prénom</label>
                        <input type="text" name="firstname" id="firstname" value="<?= $firstname ?? "" ?>">
                        <p class="text-danger">
                            <?= $errors['firstname'] ?? "" ?>
                        </p>
                    </div>
                    <div class="form-control">
                        <label for="lastname">Nom</label>
                        <input type="text" name="lastname" id="lastname" value="<?= $lastname ?? "" ?>">
                        <p class="text-danger">
                            <?= $errors['lastname'] ?? "" ?>
                        </p>
                    </div>
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
                    <div class="form-control">
                        <label for="confirmPassword">Confirmation du mot de passe</label>
                        <input type="password" name="confirmPassword" id="confirmPassword">
                        <p class="text-danger">
                            <?= $errors['confirmPassword'] ?? "" ?>
                        </p>
                    </div>
                    <div class="form-action">
                        <a href="/" class="btn btn-secondary" type="button">Annuler</a>
                        <a href="/form-article.php">
                            <button class="btn btn-primary">Valider</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>

</body>

</html>