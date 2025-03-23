<?php
session_start();
require 'connexion-bdd.php';

// Initialisation du tableau des erreurs
$errors = [];

// Vérification si les données sont bien envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $date_de_naissance = $_POST["date_de_naissance"];
    $email = trim($_POST["email"]);
    $telephone = trim($_POST["telephone"]);
    $course = $_POST["course"];

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'email est invalide (format attendu : email.test@domaine.fr).";
    }

    // Validation du numéro de téléphone (doit contenir exactement 10 chiffres)
    if (!preg_match('/^\d{10}$/', $telephone)) {
        $errors['telephone'] = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
    }

    // Vérifier si l'email existe déjà dans la base de données
    $stmt = $pdo->prepare("SELECT part_id FROM participants WHERE part_email = ?");
    $stmt->execute([$email]);
    $participant = $stmt->fetch();

    if ($participant) {
        $errors['email'] = "Cet email est déjà utilisé.";
    }

    // Si aucune erreur, on peut procéder à l'inscription
    if (empty($errors)) {
        try {
            // Vérifier si la course existe et récupérer son ID
            $stmt = $pdo->prepare("SELECT cour_id FROM courses WHERE cour_distance = ?");
            $stmt->execute([$course]);
            $course_data = $stmt->fetch();

            if (!$course_data) {
                die("Erreur : La course sélectionnée n'existe pas.");
            }

            $cour_id = $course_data["cour_id"];

            // Insérer dans la table participants
            $stmt = $pdo->prepare("INSERT INTO participants (part_nom, part_prenom, part_date_de_naissance, part_email, part_telephone) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $date_de_naissance, $email, $telephone]);

            // Récupérer l'ID du participant nouvellement créé
            $participant_id = $pdo->lastInsertId();

            // Insérer dans la table inscriptions
            $stmt = $pdo->prepare("INSERT INTO inscriptions (part_id, cour_id, inscr_date) VALUES (?, ?, ?)");
            $stmt->execute([$participant_id, $cour_id, date('Y-m-d H:i:s')]);

            // Supprimer les erreurs de session après succès
            unset($_SESSION['errors']);

            // Redirection vers la page des participants après l'inscription
            header("Location: participants.php");
            exit;
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    } else {
        // Enregistrer les erreurs en session pour affichage dans le formulaire
        $_SESSION['errors'] = $errors;
        header("Location: inscription.php");
        exit;
    }
}
?>