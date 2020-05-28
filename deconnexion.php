<?php
    session_start(); // J'exécute cette fonction utiliser les fonctions supergobales $_SESSION 
    require 'bdd.php'; // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.
    session_destroy();
    header('Location: connexion.php');

    $offline = $bdd -> prepare('UPDATE users SET etat = ? WHERE id = ? ');
    $offline -> execute(array('offline', $_SESSION['id']));

?>