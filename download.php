<?php

require_once "config.php";
require_once "functions.php";

$title = 'Загрузка данных';
$errors = [];
$users = selectPetAge3($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $form = $_POST;

    if (isset($form['doUpload'])) {
        $errors['myFile'] = validateXmlFile('myFile'); // валидируем файл
        $errors = array_filter($errors);
        
        if (empty($errors)) {
            // В данном не будем сохранять файл, работаем с его временной версией
            $tmpFileName = $_FILES['myFile']['tmp_name'];
            readXmlFileAndWriteDb($tmpFileName, $pdo);
            header("Location: download.php");
            exit();
        } 
    }
}

$main = includeTemplate("load.php", [
    "errors" => $errors,
    "users" => $users
]);

$layoutContent = includeTemplate("layout.php", [
    "title" => $title,
    "main" => $main
]);

print($layoutContent);
