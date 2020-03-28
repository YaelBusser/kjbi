<?php
    session_start();
    require 'bdd.php';
    session_destroy();
    header('Location: connexion.php');

    $offline = $bdd -> prepare('UPDATE users SET etat = ? WHERE id = ? ');
    $offline -> execute(array('offline', $_SESSION['id']));

?>