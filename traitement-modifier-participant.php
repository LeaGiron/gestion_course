<?php
session_start();
require 'connexion-bdd.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

// Vérifier si les données du formulaire sont envoyées
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Vérifier le jeton CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("Erreur de sécurité : jeton CSRF invalide.");
        }

    $participant_id = $_POST['participant_id'];
    $nom = $_POST['part_nom'];
    $prenom = $_POST['part_prenom'];
    $date_de_naissance = $_POST['part_date_de_naissance'];
    $email = $_POST['part_email'];
    $telephone = $_POST['part_telephone'];

    try {
        // Mettre à jour les informations du participant dans la base de données
        $stmt = $pdo->prepare("UPDATE participants SET part_nom = ?, part_prenom = ?, part_date_de_naissance = ?, part_email = ?, part_telephone = ? WHERE part_id = ?");
        $stmt->execute([$nom, $prenom, $date_de_naissance, $email, $telephone, $participant_id]);

        // Redirection vers le tableau de bord après la mise à jour
        header("Location: tableau-de-bord.php");
        exit;
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "Aucune donnée soumise.";
    exit;
}
?>