<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/TroupeDuJetDEncreV2/');
}
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarre la session uniquement si aucune session n'est active
}

// Fonction renderLogoutButton
function renderLogoutButton() {
    if (isset($_SESSION['user_id'])) {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        echo '<form action="' . BASE_URL . 'component/logout.php" method="POST" style="display: inline;">';
        echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
        echo '<button type="submit">Logout</button>';
        echo '</form>';
    }
}
?>
