<?php
session_start();
require 'connexion-bdd.php';

// Vérification du token CSRF
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur CSRF : jeton invalide.");
    }

    // Récupération des informations de connexion
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $pdo->prepare("SELECT orga_id, orga_mot_de_passe FROM organisateurs WHERE orga_email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['orga_mot_de_passe'])) {
                $_SESSION['user_id'] = $user['orga_id'];
                header("Location: tableau-de-bord.php");
                exit;
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
        }
    }
    echo "<p>Email ou mot de passe incorrect.</p>";
}

// Générer un jeton CSRF si nécessaire
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="conteneur">
          <h1>Course de la Ville 2025</h1>
          <a href="index.html" class="bouton-connexion">Page d'accueil</a>
        </div>
    </header>

    <div class="page-connexion">
        <h1>Connectez-vous</h1>
        <form action="connexion.php" method="POST">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <!-- Champ caché pour le token CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <button type="submit">Se connecter</button>
        </form>
    </div>

    <footer>
        <p class="footer-texte">&copy; 2025 Course de la Ville</p>
    </footer>

</body>
</html>
