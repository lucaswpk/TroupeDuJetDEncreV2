<?php
session_start();
require_once './service/functions.php'; // Inclure la constante BASE_URL
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Théâtre</title>
    <link rel="stylesheet" href="./styles/style.css">
    <script src="./script/script.js" defer></script> 
</head>
<body>

    <?php include './component/navbar.php'; ?>


    <img src="./image/rideau_gauche.png" alt="Rideau gauche" class="gauche">
<div class="stage">

    
    <div class="affiche-container">
        <img src="./image/Affiche.jpg" alt="Affiche" class="affiche">
        <div class="bordure"></div> 
        <div class="texte-a-droite">
            <p>Bonjour, futur spectateur, sur notre nouveau site ! Tu y trouveras beaucoup de renseignement sur la troupe du jet d'encre, nos dates de sortie, nos acteurs et plus encore !</p>
        </div>
    </div>
 </div>   
    <img src="./image/rideau_droit.png" alt="Rideau droit" class="droite">

    <div class="carrousel">
        <div class="carrousel-container">
            <div class="comment">
                <p>"Super troupe de théâtre ! Les spectacles sont incroyables."</p>
                <span>- Spectateur A</span>
            </div>
            <div class="comment">
                <p>"Un moment magique à chaque représentation."</p>
                <span>- Spectateur B</span>
            </div>
            <div class="comment">
                <p>"Les acteurs sont très talentueux, je recommande vivement !"</p>
                <span>- Spectateur C</span>
            </div>
        </div>
        <button class="prev">❮</button>
        <button class="next">❯</button>
    </div>
    
</body>
</html>
