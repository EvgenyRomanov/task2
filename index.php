<?php

require_once 'config.php';
require_once 'functions.php';


$title = 'Авторизация';
$errors = [];

if (isset($_SESSION['user'])) {
    header("Location: download.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$form = $_POST;
	$required = ['email', 'password'];

	foreach ($required as $field) {
	    if (empty($form[$field])) {
	        $errors[$field] = 'Это поле должно быть заполнено';
        }
    }

	$email = $form['email'];
    $user = issetUser($pdo, $email);

	if (!count($errors)) {
        if (!empty($user)) {
            if (password_verify($form['password'], $user['password'])) {
                $_SESSION['user'] = $user;
            } else {
                $errors['password'] = 'Неверный пароль';
            }
        } else {
            $errors['email'] = 'Такой пользователь не найден';
        }
    }

	if (!count($errors)) {
        header("Location: download.php");
		exit();
	}
}

$main = includeTemplate("enter.php", [
    "errors" => $errors
]);

$layoutContent = includeTemplate("layout.php", [
    "title" => $title,
    "main" => $main
]);

print($layoutContent);