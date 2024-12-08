<nav>
    <h1>TROUPE DU JET D'ENCRE</h1>
    <div id="CmdMenu" title="Afficher/Masquer le Menu">&#9776;</div>
    <div id="nav-links">
        <!-- Liens généraux -->
        <a href="<?= BASE_URL; ?>index.php">Accueil</a>
        <a href="<?= BASE_URL; ?>view/actors.php">Acteurs</a>
        <a href="<?= BASE_URL; ?>view/plays.php">Pièces</a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Profil et Déconnexion pour les utilisateurs connectés -->
            <a href="<?= BASE_URL; ?>view/profile.php">Profil</a>
            <form action="<?= BASE_URL; ?>component/logout.php" method="POST" class="nav-logout-form">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="nav-logout-button">Logout</button>
            </form>

        <?php else: ?>
            <!-- Connexion/Inscription pour les visiteurs non connectés -->
            <a href="<?= BASE_URL; ?>auth/connexion.php">Connexion/Inscription</a>
        <?php endif; ?>
    </div>
</nav>
