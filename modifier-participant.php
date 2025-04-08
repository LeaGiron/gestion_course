<?php
session_start();
require 'connexion-bdd.php';

// Générer un token CSRF s'il n'existe pas déjà
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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

// Initialisation du tableau d'erreurs
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Vérifier si le token CSRF est valide
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur : Token CSRF invalide.");
    }

    $email = $_POST['part_email']; 
    $part_telephone = $_POST['part_telephone'];

    // Validation de l'email
    if (!preg_match("/^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,6}$/i", $email)) {
        $errors['email'] = "L'email est invalide (format email.test@domaine.fr attendu).";
    }

    // Validation du numéro de téléphone (10 chiffres requis)
    if (!preg_match('/^\d{10}$/', $part_telephone)) {
        $errors['telephone'] = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
    }

    // Si aucune erreur, mise à jour des informations
    if (empty($errors)) {
        $part_nom = $_POST['part_nom'];
        $part_prenom = $_POST['part_prenom'];
        $part_date_de_naissance = $_POST['part_date_de_naissance'];

        try {
            $stmt = $pdo->prepare("UPDATE participants SET part_nom = ?, part_prenom = ?, part_date_de_naissance = ?, part_email = ?, part_telephone = ? WHERE part_id = ?");
            $stmt->execute([$part_nom, $part_prenom, $part_date_de_naissance, $email, $part_telephone, $participant_id]);

            // Rediriger vers le tableau de bord
            header("Location: tableau-de-bord.php"); 
            exit;
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
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

    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <!-- Formulaire pour modifier les informations -->
    <form action="" method="POST">
        <input type="hidden" name="participant_id" value="<?= htmlspecialchars($participant['part_id']) ?>">

        <label for="part_nom">Nom :</label>
        <input type="text" id="part_nom" name="part_nom" value="<?= htmlspecialchars($participant['part_nom']) ?>" required>

        <label for="part_prenom">Prénom :</label>
        <input type="text" id="part_prenom" name="part_prenom" value="<?= htmlspecialchars($participant['part_prenom']) ?>" required>

        <label for="part_date_de_naissance">Date de naissance :</label>
        <input type="date" id="part_date_de_naissance" name="part_date_de_naissance" value="<?= htmlspecialchars($participant['part_date_de_naissance']) ?>" required>

        <label for="part_email">Email :</label>
        <input type="email" id="part_email" name="part_email" value="<?= htmlspecialchars($participant['part_email']) ?>" required>
        <?php if (isset($errors['email'])) { echo "<p>{$errors['email']}</p>"; } ?>

        <label for="part_telephone">Téléphone :</label>
        <input type="tel" id="part_telephone" name="part_telephone" value="<?= htmlspecialchars($participant['part_telephone']) ?>" required>
        <?php if (isset($errors['telephone'])) { echo "<p>{$errors['telephone']}</p>"; } ?>

        <button type="submit">Sauvegarder les modifications</button>
    </form>
</div>

<footer>
    <p class="footer-texte">&copy; 2025 Course de la Ville</p>
</footer>

</body>
</html>