<?php 
session_start();// J'exécute cette fonction utiliser les fonctions supergobales $_SESSION 
require('bdd.php'); // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.
	if(isset($_POST['inscription']))
	{
		$pseudo = htmlspecialchars($_POST['pseudo']);
        $email = htmlspecialchars($_POST['email']);
        $email2 = htmlspecialchars($_POST['email2']);
        $mdp = sha1($_POST['mdp']);
        $mdp2 = sha1($_POST['mdp2']);

        if(!empty($pseudo) AND !empty($email) AND !empty($email2) AND !empty($mdp) AND !empty($mdp2))
        {
        	if(strlen($pseudo) <= 12)
            {    
                if($email == $email2)
                {
                    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
                    $requete_email = $bdd -> prepare('SELECT email FROM users WHERE email = ?');
                    $requete_email -> execute(array($email));
                    $email_doublon = $requete_email -> rowCount();
                    if($email_doublon == 0)
                    {
                        if($mdp == $mdp2)
                        {
                            $requete_pseudo = $bdd -> prepare('SELECT pseudo FROM users WHERE pseudo = ? ');
                            $requete_pseudo -> execute(array($pseudo));
                            $pseudo_exist = $requete_pseudo -> rowCount();
                            if($pseudo_exist ==  0)
                            {
                                $creation_compte = $bdd -> prepare('INSERT INTO users(pseudo, email, mdp, avatar, miniature, etat, bio) VALUES(?, ?, ?, ?, ?, ?, ?)');
                                $creation_compte -> execute(array($pseudo, $email, $mdp, "0.jpg", "0.jpg", "", ""));
                               	$ok = "";
                            }
                            else
                            {
                                $error = "Le pseudo est déjà pris, dommage :) </p>";
                            }
                        }
                        else
                        {
                            $error = "Votre mot de passe n'est pas le même !";
                        }
                    }
                    else
                    {
                        $error = "L'email est déjà utilisé !";
                    }
                }
                else
                {
                    $error = "Votre adresse email ne correspond pas !"; 
                }
            }
            else
            {
                $error = "Votre pseudo doit comporter uniquement 12	caractères !"; 
            }
        }
		else
	    {
		    $error = "Tous les champs doivent être remplis !";
	    }
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Inscription | Kjbi</title>
	<?php 
		include('head.php');
	?>
</head>
<body>
	<?php 
		include('menu.php');
	?>
	<?php 
		if(isset($ok))
		{
	?>
		<h1>Félécitations, vous venez de créer un compte !</h1>
	<?php 
		}
		else
		{
	?>
	<form method="POST" class="formIn">
		<div class="inscription flex-column">
            <h1 class="title-inscription">INSCRIPTION</h1>
            <div class="flex-column label-mdp-connexion">
                <label for="pseudo">Nom d'utilisateur  </label>
                <input type="text" name="pseudo" placeholder="Nom d'Utilisateur" id="pseudo" value="<?php if(isset($pseudo)){echo $pseudo;} ?>" class="connexion-pseudo">
            </div>
            <div class="flex-column label-mdp-connexion">
                <label for="email">E-Mail </label>
                <input type="email" name="email" id="email" placeholder="Adresse Mail" value="<?php if(isset($email)){echo $email;} ?>" class="connexion-pseudo">
            </div>
            <div class="flex-column label-mdp-connexion">
				<label for="email2">Confirmation E-Mail </label>
				<input type="email" name="email2" id="email2" placeholder="Confirmation Adresse Mail" value="<?php if(isset($email2)){echo $email2;} ?>" class="connexion-pseudo">
            </div>
            <div class="flex-column label-mdp-connexion">
				<label for="mdp">Mot De Passe  </label>
				<input type="password" name="mdp" id="mdp" placeholder="Mot De Passe" class="connexion-mdp">
            </div>
            <div class="flex-column label-mdp-connexion">
				<label for="mdp2">Confirmation Mot De Passe </label>
				<input type="password" name="mdp2" id="mdp2" placeholder="Confirmation Mot De Passe" class="connexion-mdp">
                <?php if(isset($error)){ echo '<p class="alerte-connexion">⚠️'.$error.'⚠️</p>';} ?>
            </div>
				<input type="submit" name="inscription" class="btn-connexion" value="S'inscrire">
		</div>
	</form>
	<?php 
		}
	?>
</body>
</html>