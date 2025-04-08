<?php
session_start();
require 'connexion-bdd.php'; // Connexion à la base de données

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

// Vérification du jeton CSRF dans le formulaire
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Tentative de CSRF détectée.");
}

// Vérifier si l'ID du participant a été envoyé
if (isset($_POST['participant_id'])) {
    $participant_id = $_POST['participant_id'];

    try {
        // Mettre à jour le statut du participant dans la table des inscriptions
        $stmt = $pdo->prepare("UPDATE inscriptions SET inscr_statut = 'confirmée' WHERE part_id = :participant_id AND inscr_statut = 'annulée'");
        $stmt->bindParam(':participant_id', $participant_id, PDO::PARAM_INT);
        
        // Exécuter la requête
        if ($stmt->execute()) {
            // Rediriger vers le tableau de bord après la restauration
            header("Location: tableau-de-bord.php?message=Inscription restaurée");
            exit;
        } else {
            echo "Erreur lors de la restauration de l'inscription.";
        }
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "Aucun participant sélectionné.";
}
?>