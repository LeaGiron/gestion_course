<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription à la course</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="page-inscription">
    <h1>Inscription à la course</h1>
    <form action="traitement-inscription.php" method="POST">
      <label for="nom">Nom :</label>
      <input type="text" id="nom" name="nom" required placeholder="Votre nom" />

      <label for="prenom">Prénom :</label>
      <input type="text" id="prenom" name="prenom" required placeholder="Votre prénom" />

      <label for="date_de_naissance">Date de naissance :</label>
      <input type="date" id="date_de_naissance" name="date_de_naissance" required />

      <label for="email">Email :</label>
      <input type="email" id="email" name="email" required placeholder="Votre email" />

      <label for="telephone">Numéro de téléphone :</label>
      <input type="tel" id="telephone" name="telephone" required placeholder="Votre numéro de téléphone" />

      <label for="course">Choisissez une course :</label>
      <select id="course" name="course" required>
          <option value="5km">5 km</option>
          <option value="10km">10 km</option>
          <option value="15km">15 km</option>
      </select>

      <button type="submit" class="btn">S'inscrire</button>
    </form>
  </div>
</body>
</html>

