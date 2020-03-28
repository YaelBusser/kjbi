<?php 
	session_start();
	require 'bdd.php';

	if(!empty($_POST['send']))
        {
            $nom_avatar = strtolower($_FILES['avatar']['name']);
            $chemin_temp = $_FILES['avatar']['tmp_name'];
            $chemin_avatar = 'avatars';
            $extension = array('png','jpg','jpeg','gif');
            $size = 2097152;
            if($_FILES['avatar']['size'] < $size)
            {
                if(!empty($chemin_temp))
                {
                    $img = explode('.', $nom_avatar);
                    $extension_avatar = $img[1];
                    $nom_avatar = $_SESSION['id'];
                    if(in_array($extension_avatar, $extension))
                    {
                        $array_mime = array('image/png','image/jpeg','image/x-icon','image/gif');
                        $mime_test = mime_content_type($chemin_temp);
                        if(in_array($mime_test, $array_mime))
                        {
                            move_uploaded_file($chemin_temp, ''.$chemin_avatar.'/'.$nom_avatar.'.'.$extension_avatar.'');
                            $requete_avatar = $bdd -> prepare('UPDATE users SET avatar = ? WHERE id = ?');
                            $requete_avatar -> execute(array(''.$nom_avatar.'.'.$extension_avatar.'', $_SESSION['id']));
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