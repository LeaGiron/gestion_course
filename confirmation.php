<?php
session_start();

$message = $_SESSION['confirmation_message'] ?? "Action effectuée.";
$participant_nom = $_SESSION['participant_nom'] ?? "";

unset($_SESSION['confirmation_message'], $_SESSION['participant_nom']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <div class="conteneur">
        <h1>Course de la Ville 2025</h1>
        <a href="index.html" class="bouton-connexion">Page d'accueil</a>
    </div>
</header>

<div class="confirmation-container">
    <h2>Confirmation</h2>
    <p><?= htmlspecialchars($message) ?></p>
    <?php if (!empty($participant_nom)) : ?>
        <p><strong>Participant :</strong> <?= htmlspecialchars($participant_nom) ?></p>
    <?php endif; ?>
    <a href="tableau-de-bord.php" class="bouton-retour">Retour au tableau de bord</a>
</div>

<footer>
    <p class="footer-texte">&copy; 2025 Course de la Ville</p>
</footer>

</body>
</html>
