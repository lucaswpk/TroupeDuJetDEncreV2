<?php
// Inclure le fichier de connexion à la base de données
require_once '../service/db_connexion.php';
require_once '../service/functions.php'; // Inclure la constante BASE_URL

// Requête pour récupérer les données des pièces et leurs acteurs depuis la vue
$query = "SELECT play_id, play_title, play_description, play_date, picture_path, actor_names FROM view_plays_with_actors";

try {
    // Préparer et exécuter la requête
    $stmt = $db_connexion->prepare($query);
    $stmt->execute();
    $plays = $stmt->fetchAll();
} catch (PDOException $e) {
    echo '<p>Erreur lors de la récupération des données : ' . htmlspecialchars($e->getMessage()) . '</p>';
    $plays = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pièces de théâtre - Troupe du jet d'encre</title>
    <link rel="stylesheet" href="../styles/style.css">
    <script src="../script/script.js" defer></script> 
</head>
<body>

<?php include '../component/navbar.php'; ?>



<img src="../image/rideau_gauche.png" alt="Rideau gauche" class="gauche">

<div class="main-container">
    <?php if (!empty($plays)): ?>
        <?php foreach ($plays as $play): ?>
            <div class="stage">
                <div class="affiche-container">
                    <!-- Image de la pièce -->
                    <img src="<?= htmlspecialchars($play['picture_path']) ?>" alt="<?= htmlspecialchars($play['play_title']) ?>" class="affiche">

                    <div class="bordure"></div>
                    
                    <!-- Informations de la pièce -->
                    <div class="texte-a-droite">
                        <h2><?= htmlspecialchars($play['play_title']) ?></h2>
                        <p><strong>Description :</strong> <?= htmlspecialchars($play['play_description']) ?></p>
                        <p><strong>Date :</strong> <?= htmlspecialchars($play['play_date']) ?></p>
                        <p><strong>Acteurs :</strong> <?= htmlspecialchars($play['actor_names']) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune pièce trouvée.</p>
    <?php endif; ?>
</div> 

<img src="../image/rideau_droit.png" alt="Rideau droit" class="droite">

</body>
</html>
