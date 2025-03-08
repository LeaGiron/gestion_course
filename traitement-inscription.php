<?php
require 'connexion-bdd.php';

// Vérification si les données sont bien envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $date_de_naissance = $_POST["date_de_naissance"];
    $email = $_POST["email"];
    $telephone = $_POST["telephone"];
    $course = $_POST["course"]; 

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

        // Récupérer l'ID du participant nouvellement créé
        $participant_id = $pdo->lastInsertId();
    }

    // Insérer dans la table inscriptions
    $stmt = $pdo->prepare("INSERT INTO inscriptions (part_id, cour_id, inscr_date) VALUES (?, ?, ?)");
    $stmt->execute([$participant_id, $cour_id, date('Y-m-d H:i:s')]);    

    // Redirection vers la page des participants après l'inscription
    header("Location: participants.php");
}

?>
