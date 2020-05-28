<?php
session_start(); // J'exécute cette fonction utiliser les fonctions supergobales $_SESSION 
    require('bdd.php'); // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.
    if(isset($_POST['connexion']))
    {
        $pseudo = $_POST['pseudo'];
        $mdp = $_POST['mdp'];
        if(!empty($pseudo) AND !empty($mdp))
        {
            $mdp = sha1($mdp);

        	$requete_user = $bdd -> prepare('SELECT * FROM users WHERE pseudo = ? OR email = ?');
            $requete_user -> execute(array($pseudo, $pseudo));
            $user = $requete_user -> fetch(); 

            $requete_membres = $bdd -> prepare('SELECT * FROM users WHERE mdp = ? AND pseudo = ? OR mdp = ? AND email = ?');
            $requete_membres -> execute(array($mdp, $pseudo, $mdp, $pseudo));
            $pseudo_exist = $requete_membres -> rowCount();
            if($pseudo_exist)
            {
                $_SESSION['id'] = $user['id'];
                $_SESSION['pseudo'] = $user['pseudo'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['mdp'] = $user['mdp'];
                $_SESSION['avatar'] = $user['avatar'];

                $online = $bdd -> prepare('UPDATE users SET etat = ? WHERE id = ? ');
                $online -> execute(array('online', $_SESSION['id']));

                header('Location: profil.php');
            }
            else
            {
                $error = "L'identifiant ou le mot de passe est incorrect !";
            }
        }
        else
        {
            $error = "Tous les champs doivent être complétés !";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Connexion | Kjbi</title>
	<?php 
		include('head.php');
	?>
</head>
<body>
	<?php 
		include('menu.php');
	?>
	<form method="POST">
		<div class="connexion flex-column">
            <h1 class="title-connexion">CONNEXION</h1>
			<div class="connexion-un">
                <label for="pseudo">nom d'utilisateur ou adresse mail</label>
                <input type="text" name="pseudo" id="pseudo" placeholder="" value="<?php if(isset($pseudo)){ echo $pseudo; } ?>"  class="connexion-pseudo">
            </div>
            
            <span class="label-mdp-connexion">
                <label for="mdp">mot de passe</label>
                <input type="password" name="mdp" id="mdp" placeholder="" class="connexion-mdp">
            <?php if(isset($error)){ echo '<p class="alerte-connexion">⚠️'.$error.'⚠️</p>';} ?>
            </span>
            
            <input type="submit" name="connexion" class="btn-connexion" value="connexion">
		</div>
	</form>
</body>
</html>