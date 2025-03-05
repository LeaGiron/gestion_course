<?php
$config = require 'config.php';

try {
    // Connexion à la base de données avec les informations retournées par config.php
    $pdo = new PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['db'] . ";charset=utf8", $config['user'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
