<?php

session_start();

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$name = 'pets';

require 'vendor/autoload.php';

try {
    $pdo = new PDO("mysql:host={$host};dbname={$name}", $user, $pass);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage();
    die();
}