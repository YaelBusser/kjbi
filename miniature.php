<?php 
	session_start(); // J'exécute cette fonction utiliser les fonctions supergobales $_SESSION 
	require 'bdd.php'; // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.

	if(!empty($_POST['send']))
        {
            $nom_miniature = strtolower($_FILES['miniature']['name']); 
            $chemin_temp = $_FILES['miniature']['tmp_name'];
            $chemin_miniature = 'miniatures';
            $extension = array('png','jpg','jpeg','gif');
            $size = 2097152;
            if($_FILES['miniature']['size'] < $size)
            {
                if(!empty($chemin_temp))
                {
                    $img = explode('.', $nom_miniature);
                    $extension_miniature = $img[1];
                    $nom_miniature = $_SESSION['id'];
                    if(in_array($extension_miniature, $extension))
                    {
                        $array_mime = array('image/png','image/jpeg','image/x-icon','image/gif');
                        $mime_test = mime_content_type($chemin_temp);
                        if(in_array($mime_test, $array_mime))
                        {
                            move_uploaded_file($chemin_temp, ''.$chemin_miniature.'/'.$nom_miniature.'.'.$extension_miniature.'');
                            $requete_miniature = $bdd -> prepare('UPDATE users SET miniature = ? WHERE id = ?');
                            $requete_miniature -> execute(array(''.$nom_miniature.'.'.$extension_miniature.'', $_SESSION['id']));
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
                <span class="miniature-modif-profil"><a href="edit.php">modifier le profil</a></span>
                <span class="miniature-modif-pp"><a href="avatar.php">modifier la photo de profil</a></span>
                <span class="miniature-modif-miniature"><a href="miniature.php">modifier la miniature</a></span>
                <span class="miniature-modif-mdp"><a href="mdp.php">modifier le mot de passe</a></span>
            </div>
            <div class="content-edit-modif-profil">
                <div class="flex-column">
                    <div class="flex head-edit">
                        <img src="avatars/<?php echo $user['avatar']; ?>" class="edit-avatar">
                        <p class="edit-pseudo"><?php echo $user['pseudo']; ?></p>
                    </div>
                    <table class="table-edit-miniature">
                        <form enctype="multipart/form-data" method="POST">
                        	<tr>
                        		<td>
                        			<img src="miniatures/<?php echo $user['miniature']; ?>" class="miniature-img-modif">
                        		</td>
                        	</tr>
                            <tr>
                                <td>
                                    <input type="file" name="miniature" value="<?php echo $user['pseudo']; ?>" id="modif-miniature">
                                </td>
                                <td>
                                    <input type="submit" name="send" value="modifier" class="miniature-submit-modif">
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