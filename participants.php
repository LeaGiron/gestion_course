<?php
// Inclure la connexion à la base de données
require 'connexion-bdd.php';

// Récupérer les participants confirmés avec leurs informations d'inscription
$query = $pdo->query("
    SELECT p.part_id, p.part_nom, p.part_prenom, i.inscr_id, c.cour_distance
    FROM participants p
    JOIN inscriptions i ON p.part_id = i.part_id
    JOIN courses c ON i.cour_id = c.cour_id
    WHERE i.inscr_statut = 'confirmée'
");

// Récupérer tous les participants confirmés dans un tableau
$participants = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Participants Confirmés</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="page-container">
<header>
    <div class="conteneur">
      <h1>Course de la Ville 2025</h1>
      <a href="index.html" class="bouton-connexion">Page d'accueil</a>
    </div>
</header>

<div class="content">
    <div class="liste-participants">
            <h1>Liste des Participants Confirmés</h1>

            <table border="1">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Distance de la course</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($participants): ?>
                        <?php foreach ($participants as $participant): ?>
                            <tr>
                                <td><?= htmlspecialchars($participant['part_nom']) ?></td>
                                <td><?= htmlspecialchars($participant['part_prenom']) ?></td>
                                <td><?= htmlspecialchars($participant['cour_distance']) ?> km</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Aucun participant confirmé trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
    </div>
                    </div>

    <footer>
        <p class="footer-texte">&copy; 2025 Course de la Ville</p>
    </footer>
                    </div>

</body>
</html>