<?php 

$dsn = "mysql:host=localhost;dbname=troupeV2;charset=utf8";
$username = "root";
$password = '';

try {
    $db_connexion = new PDO($dsn, $username, $password);
    $db_connexion->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
    $db_connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db_connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo '<p>Impossible de se connecter à la BDD</p>';
}
