<?php
session_start();

// Inclure la connexion à la base de données
require 'connexion-bdd.php';

// Requête pour récupérer les participants à accepter
$ParticipantsEnAttente = "SELECT * FROM participants WHERE statut = 'en_attente'"; 
$participantsEnAttente = $pdo->query($participantsEnAttente)->fetchAll(PDO::FETCH_ASSOC);

// Requête pour récupérer tous les participants inscrits
$participants = "SELECT * FROM participants WHERE statut = 'inscrit'"; 
$participants = $pdo->query($participants)->fetchAll(PDO::FETCH_ASSOC);

include 'tableau-de-bord.php';
?>
