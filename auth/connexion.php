<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion/Inscription</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/styleLogin.css">
    <script src="../script/script.js" defer></script>
</head>
<body>

<!-- Barre de navigation -->
<nav>
    <h1>TROUPE DU JET D'ENCRE</h1>
    <div id="nav-links">
        <a href="../index.html">Accueil</a>
        <a href="acteurs.html">Acteurs</a>
        <a href="pièces.html">Pièces</a>
        <a href="connexion.php" class="active">Connexion/Inscription</a>
    </div>
    <div id="CmdMenu" title="Afficher/Masquer le Menu">&#9776;</div>
</nav>

<!-- Rideaux -->
<img src="../image/rideau_gauche.png" alt="Rideau gauche" class="gauche">
<img src="../image/Rideau_droit.png" alt="Rideau droit" class="droite">

<!-- Conteneur Connexion/Inscription -->
<div class="form-container">
    <!-- Section Connexion -->
    <div class="form-section">
        <h2>Connexion</h2>
        <form action="login.php" method="POST">
            <label for="login-username">Nom d'utilisateur</label>
            <input type="text" id="login-username" name="name" placeholder="Votre nom d'utilisateur" required>

            <label for="login-password">Mot de passe</label>
            <input type="password" id="login-password" name="password" placeholder="Votre mot de passe" required>

            <button type="submit">Se connecter</button>
        </form>
    </div>
    
    <div class="bordure"></div> 

    <div class="form-section">
        <h2 id="inscription">Inscription</h2>
        <form action="register.php" method="POST">
            <label for="register-username">Nom d'utilisateur</label>
            <input type="text" id="register-username" name="name" placeholder="Votre nom d'utilisateur" required>

            <label for="name">Nom</label>
            <input type="text" id="name" name="name" placeholder="Nom de l'adresse" required>

            <label for="number">Numéro</label>
            <input type="number" id="number" name="number" placeholder="Numéro" required>

            <label for="type">Type</label>
            <input type="text" id="type" name="type" placeholder="Type d'adresse (ex: Rue, Avenue)" required>

            <label for="city">Ville</label>
            <input type="text" id="city" name="city" placeholder="Nom de la ville" required>

            <label for="cp">Code postal</label>
            <input type="text" id="cp" name="cp" placeholder="Code postal" maxlength="5" required>

            <label for="register-password">Mot de passe</label>
            <input type="password" id="register-password" name="password" placeholder="Votre mot de passe" required>

            <label for="register-confirm-password">Confirmer le mot de passe</label>
            <input type="password" id="register-confirm-password" name="confirm_password" placeholder="Confirmez votre mot de passe" required>

            <button type="submit">S'inscrire</button>
        </form>
    </div>
</div>

</body>
</html>
