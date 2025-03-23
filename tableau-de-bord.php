<?php
session_start();
require 'connexion-bdd.php'; 

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit;
}

try {
    // Récupérer les participants en attente d'approbation
    $stmt = $pdo->prepare("
        SELECT p.part_id, p.part_nom, p.part_prenom, p.part_email, c.cour_distance AS course
        FROM inscriptions i
        JOIN participants p ON i.part_id = p.part_id
        JOIN courses c ON i.cour_id = c.cour_id
        WHERE i.inscr_statut = 'en attente'
    ");
    $stmt->execute();
    $participantsEnAttente = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer la liste des participants inscrits (confirmés)
    $stmt = $pdo->prepare("
        SELECT p.part_id, p.part_nom, p.part_prenom, p.part_email, c.cour_distance AS course
        FROM inscriptions i
        JOIN participants p ON i.part_id = p.part_id
        JOIN courses c ON i.cour_id = c.cour_id
        WHERE i.inscr_statut = 'confirmée'
    ");
    $stmt->execute();
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer la liste des participants dont l'inscription a été annulée
    $stmt = $pdo->prepare("
        SELECT p.part_id, p.part_nom, p.part_prenom, p.part_email, c.cour_distance AS course
        FROM inscriptions i
        JOIN participants p ON i.part_id = p.part_id
        JOIN courses c ON i.cour_id = c.cour_id
        WHERE i.inscr_statut = 'annulée'
    ");
    $stmt->execute();
    $participantsAnnules = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tableau de bord</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header>
      <div class="conteneur">
        <h1>Course de la Ville 2025</h1>
          <a href="index.html" class="bouton-connexion">Page d'accueil</a>
      </div>
    </header>

  <div class="tableau-de-bord container">

    <div class="tableau-de-bord-header">
        <h1>Bienvenue sur votre tableau de bord, Organisateur</h1>
        <a href="deconnexion.php" class="bouton-deconnexion">Déconnexion</a>
    </div>


    <!-- Section des participants à accepter -->
    <section class="participants-a-accepter">
      <h2>Participants à accepter</h2>
      <p>Voici la liste des participants qui ont demandé à s'inscrire à vos courses.</p>
      <ul class="participants-liste">
        <?php if (!empty($participantsEnAttente)): ?>
          <?php foreach ($participantsEnAttente as $participant): ?>
            <li>
              <p><strong>Nom :</strong> <?= htmlspecialchars($participant['part_nom']) ?> <?= htmlspecialchars($participant['part_prenom']) ?></p>
              <p><strong>Course demandée :</strong> <?= htmlspecialchars($participant['course']) ?></p>
              <p><strong>Email :</strong> <?= htmlspecialchars($participant['part_email']) ?></p>
              <form action="accepter-participant.php" method="POST">
                <input type="hidden" name="participant_id" value="<?= $participant['part_id'] ?>">
                <button type="submit" name="action" value="accepter">Accepter</button>
              </form>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Aucun participant en attente.</p>
        <?php endif; ?>
      </ul>
    </section>

    <!-- Section des participants inscrits -->
    <section class="tous-les-participants">
      <h2>Liste de tous les participants inscrits</h2>
      <p>Voici la liste des participants inscrits à vos courses.</p>
      <ul class="tous-les-participants-liste">
        <?php if (!empty($participants)): ?>
          <?php foreach ($participants as $participant): ?>
            <li>
              <p><strong>Nom :</strong> <?= htmlspecialchars($participant['part_nom']) ?> <?= htmlspecialchars($participant['part_prenom']) ?></p>
              <p><strong>Course :</strong> <?= htmlspecialchars($participant['course']) ?></p>
              <p><strong>Email :</strong> <?= htmlspecialchars($participant['part_email']) ?></p>

            <div style="display: flex; justify-content: flex-start; gap: 10px;">
                <!-- Formulaire pour modifier les informations -->
                <form action="modifier-participant.php" method="POST">
                    <input type="hidden" name="participant_id" value="<?= htmlspecialchars($participant['part_id']) ?>">
                    <a href="modifier-participant.php?participant_id=<?= $participant['part_id'] ?>" class="modifier-bouton">Modifier les informations</a>
                </form>

                <!-- Formulaire pour annuler l'inscription -->
                <form action="annuler-participant.php" method="POST">
                    <input type="hidden" name="participant_id" value="<?= htmlspecialchars($participant['part_id']) ?>">
                    <button type="submit" name="action" value="annuler" class="btn-annuler">Annuler l'inscription</button>
                </form>
            </div>

            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Aucun participant inscrit.</p>
        <?php endif; ?>
      </ul>
    </section>

    <!-- Section des participants avec inscription annulée -->
    <section class="participants-annules">
      <h2>Participants avec inscription annulée</h2>
      <p>Voici la liste des participants dont l'inscription a été annulée.</p>
      <ul class="participants-annules-liste">
        <?php if (!empty($participantsAnnules)): ?>
          <?php foreach ($participantsAnnules as $participant): ?>
            <li>
              <p><strong>Nom :</strong> <?= htmlspecialchars($participant['part_nom']) ?> <?= htmlspecialchars($participant['part_prenom']) ?></p>
              <p><strong>Course :</strong> <?= htmlspecialchars($participant['course']) ?></p>
              <p><strong>Email :</strong> <?= htmlspecialchars($participant['part_email']) ?></p>
              <form action="restaurer-participant.php" method="POST">
                <input type="hidden" name="participant_id" value="<?= $participant['part_id'] ?>">
                <button type="submit" name="action" value="restaurer">Restaurer l'inscription</button>
              </form>
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Aucun participant avec inscription annulée.</p>
        <?php endif; ?>
      </ul>
    </section>
  </div>

  <footer>
        <p class="footer-texte">&copy; 2025 Course de la Ville</p>
    </footer>
    
</body>
</html>