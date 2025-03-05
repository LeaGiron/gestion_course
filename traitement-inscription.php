<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $date_de_naissance = $_POST['date_de_naissance'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telephone = $_POST['telephone'];
    $nom_course = $_POST['course'];

    // Connexion à la base de données
    require 'connexion-bdd.php';

    try {
        $pdo->beginTransaction(); // Démarrer une transaction

        // Vérifier si l'email existe déjà dans `participants`
        $requete_verif_email = $pdo->prepare("SELECT part_id FROM participants WHERE part_email = :email");
        $requete_verif_email->bindParam(':email', $email);
        $requete_verif_email->execute();
        $participant_existant = $requete_verif_email->fetch(PDO::FETCH_ASSOC);

        if ($participant_existant) {
            // L'email existe déjà, récupérer l'ID du participant
            $id_participant = $participant_existant['part_id'];
        } else {
            // Insérer le participant dans la table `participants`
            $requete_ajout_participant = "INSERT INTO participants (part_nom, part_prenom, part_date_de_naissance, part_email, part_telephone) 
                                          VALUES (:nom, :prenom, :date_de_naissance, :email, :telephone)";
            
            $stmt = $pdo->prepare($requete_ajout_participant);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':date_de_naissance', $date_de_naissance);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->execute();

            // Récupérer l'ID du participant nouvellement créé
            $id_participant = $pdo->lastInsertId();
        }

        // Vérifier si le participant est déjà inscrit à cette course
        $requete_verif_inscription = $pdo->prepare("SELECT COUNT(*) FROM inscriptions WHERE part_id = :id_participant AND course_nom = :nom_course");
        $requete_verif_inscription->bindParam(':id_participant', $id_participant);
        $requete_verif_inscription->bindParam(':nom_course', $nom_course);
        $requete_verif_inscription->execute();
        $deja_inscrit = $requete_verif_inscription->fetchColumn();

        if ($deja_inscrit > 0) {
            $_SESSION['message'] = "<div class='alert error'>Vous êtes déjà inscrit à cette course.</div>";
        } else {
            // Insérer l'inscription dans la table `inscriptions`
            $requete_ajout_inscription = "INSERT INTO inscriptions (part_id, cour_id, date_inscription) 
                                          VALUES (:id_participant, :nom_course, NOW())";
            $stmt = $pdo->prepare($requete_ajout_inscription);
            $stmt->bindParam(':id_participant', $id_participant);
            $stmt->bindParam(':nom_course', $nom_course);
            $stmt->execute();

            $_SESSION['message'] = "<div class='alert success'>Inscription réussie ! Vous êtes inscrit à la course.</div>";
        }

        $pdo->commit();

        // Redirection vers la page des participants après inscription
        header("Location: participants.php");
        exit(); // Arrêter le script après la redirection
    } catch (PDOException $e) {
        $pdo->rollBack(); // Annuler en cas d'erreur
        $_SESSION['message'] = "<div class='alert error'>Erreur : " . $e->getMessage() . "</div>";

        // Rediriger en cas d'erreur vers la page des inscriptions
        header("Location: inscription.php");
        exit();
    }
}
?>
