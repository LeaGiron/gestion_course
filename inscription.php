<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription à la course</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
session_start();

// Générer un jeton CSRF
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Si des erreurs existent en session, on les récupère
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']); // Supprime les erreurs après les avoir affichées
}
?>


<header>
    <div class="conteneur">
      <h1>Course de la Ville 2025</h1>
      <a href="index.html" class="bouton-connexion">Page d'accueil</a>
    </div>
</header>

  <div class="formulaire">
    <h1>Inscription à la course</h1>
    <form action="traitement-inscription.php" method="POST">
      <!-- Champ CSRF -->
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

      <label for="nom">Nom :</label>
      <input type="text" id="nom" name="nom" required placeholder="Votre nom" />

      <label for="prenom">Prénom :</label>
      <input type="text" id="prenom" name="prenom" required placeholder="Votre prénom" />

      <label for="date_de_naissance">Date de naissance :</label>
      <input type="date" id="date_de_naissance" name="date_de_naissance" required />

      <label for="email">Email :</label>
      <input type="email" id="email" name="email" required placeholder="Votre email" />
      <?php if (isset($errors['email'])) {echo "<p>" . $errors['email'] . "</p>";}?>

      <label for="telephone">Numéro de téléphone :</label>
      <input type="tel" id="telephone" name="telephone" required placeholder="Votre numéro de téléphone" />
      <?php if (isset($errors['telephone'])) { echo "<p>{$errors['telephone']}</p>"; } ?>

      <label for="course">Choisissez une course :</label>
      <select id="course" name="course" required>
          <option value="5km">5 km</option>
          <option value="10km">10 km</option>
          <option value="15km">15 km</option>
      </select>

      <button type="submit" class="btn">S'inscrire</button>
    </form>
  </div>

  <footer>
    <p class="footer-texte">&copy; 2025 Course de la Ville</p>
  </footer>

</body>
</html>