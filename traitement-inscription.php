<?php
session_start();
require 'connexion-bdd.php';

// Initialisation de $errors
$errors = [];

// Vérification si les données sont bien envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $date_de_naissance = $_POST["date_de_naissance"];
    $email = $_POST["email"];
    $telephone = $_POST["telephone"];
    $course = $_POST["course"];

    // Validation de l'email
    if (!preg_match("/^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,6}$/i", $email)) {
        $errors['email'] = "L'email est invalide (format attendu : email.test@domaine.fr).";
    }

    // Vérifier si l'email existe déjà dans la table participants
    $stmt = $pdo->prepare("SELECT part_id FROM participants WHERE part_email = ?");
    $stmt->execute([$email]);
    $participant = $stmt->fetch();
    
    if ($participant) {
        // Si l'email existe déjà, on ajoute l'erreur
        $errors['email'] = "Cet email est déjà utilisé.";
    }

    if (empty($errors)) { // Si aucune erreur, on procède à l'inscription
        try {
            // Vérifier si la course existe et récupérer son ID
            $stmt = $pdo->prepare("SELECT cour_id FROM courses WHERE cour_distance = ?");
            $stmt->execute([$course]);
            $course_data = $stmt->fetch();

            if (!$course_data) {
                die("Erreur : La course sélectionnée n'existe pas.");
            }

            $cour_id = $course_data["cour_id"];

            // Vérifier si l'email existe déjà dans la table participants
            $stmt = $pdo->prepare("SELECT part_id FROM participants WHERE part_email = ?");
            $stmt->execute([$email]);
            $participant = $stmt->fetch();

            if ($participant) {
                $participant_id = $participant['part_id'];
            } else {
                // Insérer dans la table participants
                $stmt = $pdo->prepare("INSERT INTO participants (part_nom, part_prenom, part_date_de_naissance, part_email, part_telephone) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nom, $prenom, $date_de_naissance, $email, $telephone]);

                // Récupérer l'ID du participant créé
                $participant_id = $pdo->lastInsertId();
            }

            // Insérer dans la table inscriptions
            $stmt = $pdo->prepare("INSERT INTO inscriptions (part_id, cour_id, inscr_date) VALUES (?, ?, ?)");
            $stmt->execute([$participant_id, $cour_id, date('Y-m-d H:i:s')]);

            // On vide les erreurs en session après succès
            unset($_SESSION['email_error']);
            unset($_SESSION['form_data']);

            header("Location: participants.php");
            exit;
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    } else {
        // Si des erreurs existent, on les passe en session pour qu'elles soient affichées dans le formulaire
        $_SESSION['errors'] = $errors;
        header("Location: inscription.php");
        exit;
    }
}
