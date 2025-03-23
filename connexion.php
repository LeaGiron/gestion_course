<?php
session_start();
require 'connexion-bdd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $pdo->prepare("SELECT orga_id, orga_mot_de_passe FROM organisateurs WHERE orga_email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['orga_mot_de_passe'])) {
                $_SESSION['user_id'] = $user['orga_id'];
                header("Location: tableau-de-bord.php");
                exit;
            }
        } catch (PDOException $e) {
        }
    }
    echo "<p>Email ou mot de passe incorrect.</p>";
}
?>