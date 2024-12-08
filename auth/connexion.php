<?php
session_start();
require_once '../service/db_connexion.php';
require_once '../service/functions.php'; // Inclure la constante BASE_URL




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'login') {
        // Gestion de la connexion
        $username = htmlspecialchars(trim($_POST['login_username']), ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars(trim($_POST['login_password']), ENT_QUOTES, 'UTF-8');

        // Vérifie le nombre de tentatives échouées
        $stmt = $db_connexion->prepare("SELECT attempts, last_attempt FROM failed_logins WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $failed_login = $stmt->fetch();

        if ($failed_login) {
            $time_diff = strtotime('now') - strtotime($failed_login['last_attempt']);
            if ($failed_login['attempts'] >= 3 && $time_diff < 900) { // Blocage pendant 15 minutes
                die("Trop de tentatives échouées. Veuillez réessayer dans 15 minutes.");
            } elseif ($time_diff >= 900) {
                // Réinitialise les tentatives après 15 minutes
                $stmt = $db_connexion->prepare("UPDATE failed_logins SET attempts = 0 WHERE username = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();
            }
        }

        // Vérifie les identifiants
        $stmt = $db_connexion->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Réinitialise les tentatives en cas de succès
            $stmt = $db_connexion->prepare("DELETE FROM failed_logins WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['id_role'];

            // génération du token CSRF
            if (!isset($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            header('Location: ../index.php');
            exit;
        } else {
            // Incrémente le nombre de tentatives échouées
            if ($failed_login) {
                $stmt = $db_connexion->prepare("UPDATE failed_logins SET attempts = attempts + 1, last_attempt = NOW() WHERE username = :username");
            } else {
                $stmt = $db_connexion->prepare("INSERT INTO failed_logins (username, attempts) VALUES (:username, 1)");
            }
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            die("Nom d'utilisateur ou mot de passe incorrect.");
        }
    } elseif ($_POST['action'] === 'register') {
        // Partie : Gestion de l'inscription
        $username = htmlspecialchars(trim($_POST['register_username']), ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars(trim($_POST['register_password']), ENT_QUOTES, 'UTF-8');
        $confirm_password = htmlspecialchars(trim($_POST['register_confirm_password']), ENT_QUOTES, 'UTF-8');
        $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
        $surname = htmlspecialchars(trim($_POST['surname']), ENT_QUOTES, 'UTF-8');
        $adress = htmlspecialchars(trim($_POST['adress']), ENT_QUOTES, 'UTF-8');
        $city = htmlspecialchars(trim($_POST['city']), ENT_QUOTES, 'UTF-8');
        $cp = htmlspecialchars(trim($_POST['cp']), ENT_QUOTES, 'UTF-8');

        // Valide un mot de passe fort
        if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
            die("Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.");
        }

        // Vérifie si les mots de passe correspondent
        if ($password !== $confirm_password) {
            die("Les mots de passe ne correspondent pas.");
        }

        // Hash du mot de passe
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Insérer dans adress
            $stmt = $db_connexion->prepare("INSERT INTO adress (cp, city) VALUES (:cp, :city)");
            $stmt->execute([':cp' => $cp, ':city' => $city]);
            $adress_id = $db_connexion->lastInsertId();

            // Insérer dans users
            $stmt = $db_connexion->prepare("INSERT INTO users (username, password_hash, id_role) VALUES (:username, :password_hash, :id_role)");
            $stmt->execute([':username' => $username, ':password_hash' => $hashed_password, ':id_role' => 2]); // Rôle par défaut : utilisateur standard
            $user_id = $db_connexion->lastInsertId();

            // Insérer dans users_info
            $stmt = $db_connexion->prepare("INSERT INTO users_info (name, surname, id_users, id_adress) VALUES (:name, :surname, :id_users, :id_adress)");
            $stmt->execute([':name' => $name, ':surname' => $surname, ':id_users' => $user_id, ':id_adress' => $adress_id]);

            echo "Inscription réussie ! Vous pouvez vous connecter.";
        } catch (PDOException $e) {
            die("Erreur lors de l'inscription : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Troupe du jet d'encre - Connexion</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/styleLogin.css">
    <script src="../script/script.js" defer></script>
</head>
<body>
    
<!-- Appel de la Navbar -->
<?php include '../component/navbar.php'; ?>


<!-- Rideaux -->
<img src="../image/rideau_gauche.png" alt="Rideau gauche" class="gauche">
<img src="../image/Rideau_droit.png" alt="Rideau droit" class="droite">

<!-- Conteneur Connexion/Inscription -->
<div class="form-container">
    <!-- Section Connexion -->
    <div class="form-section">
        <h2>Connexion</h2>
        <form action="connexion.php" method="POST">
            <input type="hidden" name="action" value="login">
            <label for="login-username">Nom d'utilisateur</label>
            <input type="text" id="login-username" name="login_username" placeholder="Votre nom d'utilisateur" required>

            <label for="login-password">Mot de passe</label>
            <input type="password" id="login-password" name="login_password" placeholder="Votre mot de passe" required>

            <button type="submit">Se connecter</button>
            <?php if (isset($login_error)) echo "<p class='error'>$login_error</p>"; ?>
        </form>
    </div>

    <div class="bordure"></div>

    <!-- Section Inscription -->
    <div class="form-section">
        <h2 id="inscription">Inscription</h2>
        <form action="connexion.php" method="POST">
            <input type="hidden" name="action" value="register">
            <label for="register-username">Nom d'utilisateur</label>
            <input type="text" id="register-username" name="register_username" placeholder="Votre nom d'utilisateur" required>

            <label for="name">Nom</label>
            <input type="text" id="name" name="name" placeholder="Votre nom" required>
            
            <label for="surname">Prénom</label>
            <input type="text" id="surname" name="surname" placeholder="Votre prénom" required>

            <label for="adress">Adresse</label>
            <input type="text" id="adress" name="adress" placeholder="Votre adresse" required>

            <label for="city">Ville</label>
            <input type="text" id="city" name="city" placeholder="Nom de la ville" required>

            <label for="cp">Code postal</label>
            <input type="text" id="cp" name="cp" placeholder="Code postal" maxlength="5" required>

            <label for="register-password">Mot de passe</label>
            <input type="password" id="register-password" name="register_password" placeholder="Votre mot de passe" required>

            <label for="register-confirm-password">Confirmer le mot de passe</label>
            <input type="password" id="register-confirm-password" name="register_confirm_password" placeholder="Confirmez votre mot de passe" required>

            <button type="submit">S'inscrire</button>
            <?php if (isset($register_error)) echo "<p class='error'>$register_error</p>"; ?>
            <?php if (isset($register_success)) echo "<p class='success'>$register_success</p>"; ?>
        </form>
    </div>
</div>

</body>
</html>
