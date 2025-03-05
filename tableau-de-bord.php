<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="tableau-de-bord">
        <header>
            <h1>Bienvenue sur votre tableau de bord, Organisateur</h1>
            <a href="connexion.php">Se déconnecter</a>
        </header>

        <!-- Section des participants à accepter -->
        <section class="participants-a-accepter">
            <h2>Participants à accepter</h2>
            <p>Voici la liste des participants qui ont demandé à s'inscrire à vos courses.</p>
            <ul class="participants-liste">
                <?php foreach ($participantsEnAttente as $participant): ?>
                    <li>
                        <p><strong>Nom :</strong> <?= htmlspecialchars($participant['part_nom']) ?> <?= htmlspecialchars($participant['part_prenom']) ?></p>
                        <p><strong>Course demandée :</strong> <?= htmlspecialchars($participant['course']) ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($participant['part_email']) ?></p>
                        <form action="accepter-participant.php" method="POST">
                            <input type="hidden" name="participant_id" value="<?= $participant['id'] ?>">
                            <button type="submit" name="action" value="accepter">Accepter</button>
                            <button type="submit" name="action" value="annuler">Annuler</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- Section des participants inscrits -->
        <section class="tous-les-participants">
            <h2>Liste de tous les participants</h2>
            <p>Voici la liste des participants inscrits à vos courses.</p>
            <ul class="tous-les-participants-liste">
                <?php foreach ($participants as $participant): ?>
                    <li>
                        <p><strong>Nom :</strong> <?= htmlspecialchars($participant['part_nom']) ?> <?= htmlspecialchars($participant['part_prenom']) ?></p>
                        <p><strong>Course :</strong> <?= htmlspecialchars($participant['course']) ?></p>
                        <p><strong>Email :</strong> <?= htmlspecialchars($participant['part_email']) ?></p>
                        <form action="modifier-participant.php" method="POST">
                            <input type="hidden" name="participant_id" value="<?= $participant['part_id'] ?>">
                            <button type="submit" name="action" value="modifier">Modifier les informations</button>
                            <button type="submit" name="action" value="confirmer">Confirmer l'inscription</button>
                            <button type="submit" name="action" value="annuler">Annuler l'inscription</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </div>
</body>
</html>
