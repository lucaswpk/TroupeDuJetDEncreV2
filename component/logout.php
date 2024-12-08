<?php
session_start();

// Vérifie que la requête est bien envoyée en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie que le token CSRF est présent et valide
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Requête invalide : token CSRF non valide.");
    }

    // Détruit la session pour déconnecter l'utilisateur
    session_unset(); // Supprime toutes les variables de session
    session_destroy(); // Détruit la session côté serveur
    session_write_close(); // Assure que tout est bien écrit

    // Redirige vers la page de connexion
    header('Location: ../auth/connexion.php');
    exit;
} else {
    // Si une requête non-POST est envoyée, redirige par sécurité
    header('Location: ../index.php');
    exit;
}
