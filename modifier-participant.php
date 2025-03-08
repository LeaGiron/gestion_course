<?php
session_start();
require 'connexion-bdd.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

// Vérifier si l'ID du participant est passé en paramètre
if (isset($_GET['participant_id'])) {
    $participant_id = $_GET['participant_id'];

    try {
        // Récupérer les informations du participant
        $stmt = $pdo->prepare("SELECT * FROM participants WHERE part_id = ?");
        $stmt->execute([$participant_id]);
        $participant = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$participant) {
            echo "Participant introuvable.";
            exit;
        }
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    echo "Aucun participant sélectionné.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les informations du participant</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header>
      <div class="conteneur">
        <h1>Course de la Ville 2025</h1>
          <a href="index.html" class="bouton-connexion">Page d'accueil</a>
      </div>
    </header>

<div class="formulaire">
    <h1>Modifier les informations du participant</h1>
    <!-- Formulaire pour modifier les informations -->
    <form action="traitement-modifier-participant.php" method="POST">
        <input type="hidden" name="participant_id" value="<?= htmlspecialchars($participant['part_id']) ?>">

        <label for="part_nom">Nom :</label>
        <input type="text" id="part_nom" name="part_nom" value="<?= htmlspecialchars($participant['part_nom']) ?>" required>

        <label for="part_prenom">Prénom :</label>
        <input type="text" id="part_prenom" name="part_prenom" value="<?= htmlspecialchars($participant['part_prenom']) ?>" required>

        <label for="part_date_de_naissance">Date de naissance :</label>
        <input type="date" id="part_date_de_naissance" name="part_date_de_naissance" value="<?= htmlspecialchars($participant['part_date_de_naissance']) ?>" required>

        <label for="part_email">Email :</label>
        <input type="email" id="part_email" name="part_email" value="<?= htmlspecialchars($participant['part_email']) ?>" required>

        <label for="part_telephone">Téléphone :</label>
        <input type="tel" id="part_telephone" name="part_telephone" value="<?= htmlspecialchars($participant['part_telephone']) ?>" required>

        <button type="submit">Sauvegarder les modifications</button>
    </form>

</div>

    <footer>
        <p class="footer-texte">&copy; 2025 Course de la Ville</p>
    </footer>

</body>
</html>
