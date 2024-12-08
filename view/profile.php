<?php
session_start();
require_once '../service/db_connexion.php';
require_once '../service/functions.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php'); // Redirection vers la page de connexion
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupère les informations de l'utilisateur
$stmt = $db_connexion->prepare("
    SELECT u.username, ui.name, ui.surname, a.cp, a.city, u.password_hash
    FROM users u
    JOIN users_info ui ON u.id = ui.id_users
    JOIN adress a ON ui.id_adress = a.id
    WHERE u.id = :user_id
");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user_info = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Requête invalide : token CSRF non valide.");
    }

    // Initialiser les variables pour éviter les erreurs
    $current_password = isset($_POST['current_password']) ? trim($_POST['current_password']) : null;
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : null;
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : null;

    
    $success_message = "";
    $error_message = "";

    // Vérification du mot de passe actuel
    if (!empty($current_password) && password_verify($current_password, $user_info['password_hash'])) {
        if ($new_password === $confirm_password) {
            if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $new_password)) {
                // Hash du nouveau mot de passe
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Mise à jour dans la base de données
                $stmt = $db_connexion->prepare("UPDATE users SET password_hash = :new_password WHERE id = :user_id");
                $stmt->execute([':new_password' => $hashed_password, ':user_id' => $user_id]);

                $success_message = "Mot de passe changé avec succès.";
            } else {
                $error_message = "Le nouveau mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
            }
        } else {
            $error_message = "Les nouveaux mots de passe ne correspondent pas.";
        }
    } else {
        $error_message = "Mot de passe actuel incorrect.";
    }
}


// Génération d'un token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Troupe du Jet d'Encre</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/styleLogin.css">
    <script src="../script/script.js" defer></script>
</head>
<body>

<?php include '../component/navbar.php'; ?>

<!-- Rideaux -->
<img src="../image/rideau_gauche.png" alt="Rideau gauche" class="gauche">
<img src="../image/Rideau_droit.png" alt="Rideau droit" class="droite">

<!-- Conteneur Profil -->
<div class="form-container profile">
    <div class="form-section">
        <h2>Mon Profil</h2>
        <form action="profile.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" value="<?= htmlspecialchars($user_info['username'], ENT_QUOTES, 'UTF-8'); ?>" disabled>

            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user_info['name'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="surname">Prénom</label>
            <input type="text" id="surname" name="surname" value="<?= htmlspecialchars($user_info['surname'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="city">Ville</label>
            <input type="text" id="city" name="city" value="<?= htmlspecialchars($user_info['city'], ENT_QUOTES, 'UTF-8'); ?>" required>

            <label for="cp">Code postal</label>
            <input type="text" id="cp" name="cp" value="<?= htmlspecialchars($user_info['cp'], ENT_QUOTES, 'UTF-8'); ?>" maxlength="5" required>

            <label for="current_password">Mot de passe actuel</label>
            <input type="password" id="current_password" name="current_password">

            <label for="new_password">Nouveau mot de passe</label>
            <input type="password" id="new_password" name="new_password">

            <label for="confirm_password">Confirmer le nouveau mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password">

            <button type="submit" name="action" value="update_profile">Mettre à jour</button>
        </form>
    </div>

    <?php
    if (isset($success_message)) echo "<p class='success'>$success_message</p>";
    if (isset($error_message)) echo "<p class='error'>$error_message</p>";
    ?>
</div>

</body>
</html>
