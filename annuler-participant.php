<?php
session_start();
require 'connexion-bdd.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["participant_id"])) {
    
    // Vérification du token CSRF
    if (!isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur CSRF : jeton invalide.");
    }
    
    $participant_id = $_POST["participant_id"];

    try {
        // Mise à jour du statut en "annulée"
        $stmt = $pdo->prepare("UPDATE inscriptions SET inscr_statut = 'annulée' WHERE part_id = ?");
        $stmt->execute([$participant_id]);

        // Récupération des informations du participant
        $stmt = $pdo->prepare("SELECT part_nom, part_prenom FROM participants WHERE part_id = ?");
        $stmt->execute([$participant_id]);
        $participant = $stmt->fetch(PDO::FETCH_ASSOC);

        // Stocker un message de confirmation dans la session
        $_SESSION['confirmation_message'] = "L'inscription de " . htmlspecialchars($participant['part_nom']) . " " . htmlspecialchars($participant['part_prenom']) . " a été annulée.";

        header("Location: tableau-de-bord.php"); // Redirection vers le tableau de bord
        exit;

    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    header("Location: tableau-de-bord.php");
    exit;
}
?>