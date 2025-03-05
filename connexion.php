<?php
session_start();
require 'connexion-bdd.php';

// Traitement du formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Vérifie que l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Format d'email invalide.");
    }

    try {
        // Recherche de l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT util_id_utilisateur, util_mot_de_passe FROM utilisateurs WHERE util_email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifie si l'utilisateur existe et si le mot de passe est correct
        if ($user && password_verify($password, $user['util_mot_de_passe'])) {
            $_SESSION['user_id'] = $user['util_id_utilisateur'];
            header("Location: tableau-de-bord.php"); 
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>
