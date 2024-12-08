<?php
// Inclure le fichier de connexion à la base de données
require_once '../service/db_connexion.php';
require_once '../service/functions.php'; // Inclure la constante BASE_URL


// Requête pour récupérer les données des acteurs et les pièces associées depuis la vue
$query = "SELECT * FROM view_actors_with_plays";

try {
    // Préparer et exécuter la requête
    $stmt = $db_connexion->prepare($query);
    $stmt->execute();
    $actors = $stmt->fetchAll();
} catch (PDOException $e) {
    echo '<p>Erreur lors de la récupération des données : ' . htmlspecialchars($e->getMessage()) . '</p>';
    $actors = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Troupe du jet d'encre</title>
    <link rel="stylesheet" href="../styles/style.css">
    <script src="../script/script.js" defer></script> 
</head>
<body>

<?php include '../component/navbar.php'; ?>



<img src="../image/rideau_gauche.png" alt="Rideau gauche" class="gauche">

<div class="main-container">
    <?php if (!empty($actors)): ?>
        <?php foreach ($actors as $actor): ?>
            <div class="stage">
                <div class="affiche-container">
                    <!-- Image de l'acteur -->
                    <img src="<?= htmlspecialchars($actor['picture_path']) ?>" alt="<?= htmlspecialchars($actor['name'] . ' ' . $actor['surname']) ?>" class="affiche">
                    
                    <div class="bordure"></div>
                    
                    <!-- Informations de l'acteur -->
                    <div class="texte-a-droite">
                        <h2><?= htmlspecialchars($actor['name'] . ' ' . $actor['surname']) ?></h2>
                        <p><strong>Description :</strong> <?= htmlspecialchars($actor['description']) ?></p>
                        <p><strong>Date d'admission :</strong> <?= htmlspecialchars($actor['admission_date']) ?></p>
                        <p><strong>Pièces jouées :</strong> <?= htmlspecialchars($actor['play_titles'] ?: 'Aucune pièce associée') ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun acteur trouvé.</p>
    <?php endif; ?>
</div> 

<img src="../image/rideau_droit.png" alt="Rideau droit" class="droite">

</body>
</html>
