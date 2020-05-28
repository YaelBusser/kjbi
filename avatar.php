<?php 
	session_start(); // J'exécute cette fonction utiliser les fonctions supergobales $_SESSION 
	require 'bdd.php'; // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.

	if(!empty($_POST['send'])) // Cet algorithme traite les données de l'utilisateur via un formulaire et je communique avec la base de donnée.
        {
            $nom_avatar = strtolower($_FILES['avatar']['name']); // Je récupère le nom et je le met en minuscule pour mon initialisation.
            $chemin_temp = $_FILES['avatar']['tmp_name']; /*Je stock le chemin temporaire dans cette variable, le chemin temporaire est l'endroit où tous les fichiers
                                                            uploadés via un formulaire sont stockés.  */
            $chemin_avatar = 'avatars'; // Je stock dans cette variable mon dossier où j'irais piocher dans ce dossier tous les fichiers afin de les affichés sur le site.
            $extension = array('png','jpg','jpeg','gif'); // Je stock dans cette variable un tableau comportant tous les types de fichiers que l'utilisateur a le droit d'uploader comme avatar
            $size = 2097152; // Je stock dans cette variable vraiment 2 octet en base 2 car c'est la vraie valeur pour l'ordinateur.
            if($_FILES['avatar']['size'] < $size)
            {
                if(!empty($chemin_temp))
                {
                    $img = explode('.', $nom_avatar); /* Ici la fonction explode() sert à séparer en deux parties le nom du fichier complet par le "." et la partie gauche
                                                    et la partie droite du point sont chacune stockée dans un tableau de la variable $img[info1, info2] */
                    $extension_avatar = $img[1]; // Donc de ce coté droit du point j'ai l'extension qui est stockée dans cette nouvelle variable.
                    $nom_avatar = $_SESSION['id']; // Ici je stocke dans cette variable le nom que je donnerais au fichier.
                    if(in_array($extension_avatar, $extension)) // La fonction in_array() permet de voir s'il y a une correspondance dans deux variable.
                    {
                        $array_mime = array('image/png','image/jpeg','image/x-icon','image/gif'); /* Je stock tous les différents types de mimes acceptés pour éviter d'avoir 
                                                                                                    par exemple une personne malintensionnée qui créer un fichier txt en y mettant
                                                                                                    des requêtes SQL et qui va ensuite modifier l'extension pour que son fichier 
                                                                                                    soit accepté. */
                        $mime_test = mime_content_type($chemin_temp); // Cette fonction : mime_content_type va récupérer la mime du fichier (ce que contient réellement le fichier).
                        if(in_array($mime_test, $array_mime)) // Je vois ici si la personne n'est pas mal intensionnée.
                        {
                            move_uploaded_file($chemin_temp, ''.$chemin_avatar.'/'.$nom_avatar.'.'.$extension_avatar.''); /* C'est ici qu'avec la fonction move_uploaded_file que 
                                                                                                                             je vais déplacer le fichier de son chemin temporaire
                                                                                                                            vers mon dossier de référence que j'ai choisit. */       

                            $requete_avatar = $bdd -> prepare('UPDATE users SET avatar = ? WHERE id = ?'); /* Je fais une requête SQL pour modifier dans la base de donnée le nouveau
                                                                                                            nom du fichier. */
                            $requete_avatar -> execute(array(''.$nom_avatar.'.'.$extension_avatar.'', $_SESSION['id'])); /* Les requêtes préparées sont très conseillées car
                                                                                                                            cela évite les injections SQL car la base de donnée va 
                                                                                                                            analyser et compiler avant d'exécuté cette requête, et 
                                                                                                                            comme cette requête est préparée. Nous n'avons plus besoin
                                                                                                                            d'y refaire appel et cela optimise grandement le site et 
                                                                                                                            son flux. */
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
                <span class="avatar-modif-profil"><a href="edit.php">modifier le profil</a></span>
                <span class="avatar-modif-pp"><a href="avatar.php">modifier la photo de profil</a></span>
                <span class="avatar-modif-miniature"><a href="miniature.php">modifier la miniature</a></span>
                <span class="avatar-modif-mdp"><a href="mdp.php">modifier le mot de passe</a></span>
            </div>
            <div class="content-edit-modif-profil">
                <div class="flex-column">
                    <div class="flex head-edit">
                        <img src="avatars/<?php echo $user['avatar']; ?>" class="edit-avatar">
                        <p class="edit-pseudo"><?php echo $user['pseudo']; ?></p>
                    </div>
                    <table class="table-edit-avatar">
                        <form enctype="multipart/form-data" method="POST">
                        	<tr>
                        		<td>
                        			<img src="avatars/<?php echo $_SESSION['avatar']; ?>" class="avatar-img-modif">
                        		</td>
                        	</tr>
                            <tr>
                                <td>
                                    <input type="file" name="avatar" value="<?php echo $user['pseudo']; ?>" id="modif-avatar">
                                </td>
                                <td align="right">
                                    <input type="submit" name="send" value="modifier" class="edit-submit-modif-avatar">
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
</body>
</html>