<?php
session_start();
require 'connexion-bdd.php';

// Traitement du formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Vérifie que l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Format d'email invalide.");
    }

    try {
        // Vérification de l'email dans la base de données
        $stmt = $pdo->prepare("SELECT orga_id, orga_mot_de_passe FROM organisateurs WHERE orga_email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si un utilisateur est trouvé
        if ($user) {
            echo "Utilisateur trouvé : <pre>";
            var_dump($user);
            echo "</pre><br>";
        } else {
            die("Aucun utilisateur trouvé avec cet email.");
        }

        // Vérifie si le mot de passe est correct
        if (password_verify($password, $user['orga_mot_de_passe'])) {
            $_SESSION['user_id'] = $user['orga_id'];
            echo "Connexion réussie. Redirection...";
            header("Location: tableau-de-bord.php");
            exit;
        } else {
            die("Email ou mot de passe incorrect.");
        }
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}
?>
<?php
session_start();
require 'connexion-bdd.php';

// Traitement du formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Vérifie que l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Format d'email invalide.");
    }

    try {
        // Vérification de l'email dans la base de données
        $stmt = $pdo->prepare("SELECT orga_id, orga_mot_de_passe FROM organisateurs WHERE orga_email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si un utilisateur est trouvé
        if ($user) {
            echo "Utilisateur trouvé : <pre>";
            var_dump($user);
            echo "</pre><br>";
        } else {
            die("Aucun utilisateur trouvé avec cet email.");
        }

        // Vérifie si le mot de passe est correct
        if (password_verify($password, $user['orga_mot_de_passe'])) {
            $_SESSION['user_id'] = $user['orga_id'];
            echo "Connexion réussie. Redirection...";
            header("Location: tableau-de-bord.php");
            exit;
        } else {
            die("Email ou mot de passe incorrect.");
        }
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}
?>
<?php
session_start();
require 'connexion-bdd.php';

// Traitement du formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Vérifie que l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Format d'email invalide.");
    }

    try {
        // Vérification de l'email dans la base de données
        $stmt = $pdo->prepare("SELECT orga_id, orga_mot_de_passe FROM organisateurs WHERE orga_email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si un utilisateur est trouvé
        if ($user) {
            echo "Utilisateur trouvé : <pre>";
            var_dump($user);
            echo "</pre><br>";
        } else {
            die("Aucun utilisateur trouvé avec cet email.");
        }

        // Vérifie si le mot de passe est correct
        if (password_verify($password, $user['orga_mot_de_passe'])) {
            $_SESSION['user_id'] = $user['orga_id'];
            echo "Connexion réussie. Redirection...";
            header("Location: tableau-de-bord.php");
            exit;
        } else {
            die("Email ou mot de passe incorrect.");
        }
    } catch (PDOException $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
}
?>
