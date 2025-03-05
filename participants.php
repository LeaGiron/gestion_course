<?php
// Inclure la connexion à la base de données
require 'connexion-bdd.php';


// Récupérer les participants confirmés avec leurs informations d'inscription
$query = $pdo->query("
    SELECT p.part_id, p.part_nom, p.part_prenom, i.inscr_id
    FROM participants p
    JOIN inscriptions i ON p.part_id = i.part_id
    WHERE i.inscr_statut = 'confirmé'
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
    <div class="liste-participants">
        <header>
            <h1>Liste des Participants Confirmés</h1>
        </header>


        <section>
            <table border="1">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Course</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($participants): ?>
                        <?php foreach ($participants as $participant): ?>
                            <tr>
                                <td><?= $participant['part_nom'] ?></td>
                                <td><?= $participant['part_prenom'] ?></td>
                                <td><?= $participant['inscr_course'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Aucun participant confirmé trouvé</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
