<?php

require_once 'config.php';
require_once 'functions.php';

$title = "Регистрация";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required = ['email', 'password', 'password-repeat'];
    $form = $_POST;

    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = "Это поле должно быть заполнено";
        }
    }

    if (!array_key_exists('email', $errors) && filter_var($form['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Некорретный email';
    }

    $errors = array_filter($errors);

    if (empty($errors)) {
        if (addUser($pdo, $errors, $form)) {
            header("Location: /");
            exit();
        }
    }

}

$main = includeTemplate("reg.php", [
    "errors" => $errors
]);

$layoutContent = includeTemplate("layout.php", [
    "title" => $title,
    "main" => $main
]);

print($layoutContent);