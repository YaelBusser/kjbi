<?php
    session_start(); // J'exécute cette fonction utiliser les fonctions supergobales $_SESSION 
    require 'bdd.php'; // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recherche</title>
    <?php 
        include 'head.php';
    ?>
</head>
<body>
<?php
    include 'menu.php';
    $avatar = "";
    $_GET['pseudo'] = htmlspecialchars($_GET['pseudo']);
    if(!empty($_GET['pseudo']))
    {
        $requete_search = $bdd -> prepare('SELECT * FROM users WHERE pseudo LIKE ? ');
        $requete_search -> execute(array('%'.$_GET['pseudo'].'%'));
        $users = $requete_search -> fetchAll();
        if($users)
        { 
            $pseudo_recherche = "";
            foreach($users as $pseudonyme)
            {
                $pseudo_recherche .= '
                <div class="flex">
                    <a href="user.php?pseudo='.$pseudonyme['pseudo'].'">
                        <img src="avatars/'.$pseudonyme['avatar'].'" class="avatar-recherche">
                    </a>
                    <p class="avatar-recherche">
                        <a href="user.php?pseudo='.$pseudonyme['pseudo'].'">'.$pseudonyme['pseudo'].'</a>
                    </p>
                </div>
                ';
            }
        }
        else
        {
             $pseudo_recherche =  'Aucun résultat pour '.$_GET['pseudo'].'';
        }
    }
    else
    {
        echo 'Il y\'a une erreur dans l\'url ou vous avez fait une recherche vide';
    }
?>
    <div class="compte-recherche">
        <?php if(isset($pseudo_recherche)){ echo $pseudo_recherche; } ?>
    </div>

</body>
</html>