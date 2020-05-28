<?php 
	session_start(); // J'exécute cette fonction utiliser les fonctions supergobales $_SESSION 
	require 'bdd.php'; // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.

	if(!empty($_POST['send']))
        {
            $nom_mdp = strtolower($_FILES['mdp']['name']); 
            $chemin_temp = $_FILES['mdp']['tmp_name'];
            $chemin_mdp = 'mdps';
            $extension = array('png','jpg','jpeg','gif');
            $size = 2097152;
            if($_FILES['mdp']['size'] < $size)
            {
                if(!empty($chemin_temp))
                {
                    $img = explode('.', $nom_mdp);
                    $extension_mdp = $img[1];
                    $nom_mdp = $_SESSION['id'];
                    if(in_array($extension_mdp, $extension))
                    {
                        $array_mime = array('image/png','image/jpeg','image/x-icon','image/gif');
                        $mime_test = mime_content_type($chemin_temp);
                        if(in_array($mime_test, $array_mime))
                        {
                            move_uploaded_file($chemin_temp, ''.$chemin_mdp.'/'.$nom_mdp.'.'.$extension_mdp.'');
                            $requete_mdp = $bdd -> prepare('UPDATE users SET avatar = ? WHERE id = ?');
                            $requete_mdp -> execute(array(''.$nom_mdp.'.'.$extension_mdp.'', $_SESSION['id']));
                        }
                        else
                        {
                            echo 'Erreur mime ! ';
                        }
                    }
                    else
                    {
                        echo 'Veuillez choisir un avatar au format png, jpg, jpeg ou gif !';
                    }
                    
                }
            }
            else
            {
                echo 'Votre photo de profil ne doit pas dépasser les 2 Mo !';
            }
        }
?>
<!DOCTYPE html>
<html>
<head>
	<title>photo de profil * KJBi</title>
	<?php 
		include 'head.php';
	?>
</head>
<body>
	<?php 
		include 'menu.php';
	?>
	<div class="bloc-edit-profil">
        <div class="flex">
            <div class="flex-column content-list-edit">
                <span class="mdp-modif-profil"><a href="edit.php">modifier le profil</a></span>
                <span class="mdp-modif-pp"><a href="avatar.php">modifier la photo de profil</a></span>
                <span class="mdp-modif-miniature"><a href="miniature.php">modifier la miniature</a></span>
                <span class="mdp-modif-mdp"><a href="mdp.php">modifier le mot de passe</a></span>
            </div>
            <div class="content-edit-modif-profil">
                <div class="flex-column">
                    <div class="flex head-edit">
                        <img src="avatars/<?php echo $user['avatar']; ?>" class="edit-avatar">
                        <p class="edit-pseudo"><?php echo $user['pseudo']; ?></p>
                    </div>
                    <table class="table-edit-mdp">
                        <form enctype="multipart/form-data" method="POST">
                        	<tr>
                        		<td>
                        			<label for="modif-mdp">mot de passe actuel</label>
                        		</td>
                                <td>
                                    <input type="password" name="mdp" id="modif-mdp">
                                </td>
                        	</tr>
                            <tr>
                                <td align="right">
                                    <input type="submit" name="send" value="modifier" class="mdp-submit-modif">
                                </td>
                            <tr>
                                <td></td>
                                <td>
                                    <?php if(isset($error)) { echo '<p class="alerte-edit">'.$error.'</p>'; } ?>
                                </td>
                            </tr>
                        </form>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php 

    	include 'footer.php';

    ?>
</body>
</html>