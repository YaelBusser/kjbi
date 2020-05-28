<?php
session_start(); // J'exécute cette fonction utiliser les fonctions supergobales $_SESSION 
require 'bdd.php'; // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.

if (!empty($_POST['minia_send']))
{
    $nom_miniature = strtolower($_FILES['miniature']['name']);
    $chemin_temp = $_FILES['miniature']['tmp_name'];
    $chemin_miniature = 'miniatures';
    $extension = array(
        'png',
        'jpg',
        'jpeg',
        'gif'
    );
    $size = 2097152;
    if ($_FILES['miniature']['size'] < $size)
    {
        if (!empty($chemin_temp))
        {
            $img = explode('.', $nom_miniature);
            $extension_miniature = $img[1];
            $nom_miniature = $_SESSION['id'];
            if (in_array($extension_miniature, $extension))
            {
                $array_mime = array(
                    'image/png',
                    'image/jpeg',
                    'image/gif'
                );
                $mime_test = mime_content_type($chemin_temp);
                if (in_array($mime_test, $array_mime))
                {
                    move_uploaded_file($chemin_temp, '' . $chemin_miniature . '/' . $nom_miniature . '.' . $extension_miniature . '');
                    $requete_miniature = $bdd->prepare('UPDATE users SET miniature = ? WHERE id = ?');
                    $requete_miniature->execute(array(
                        '' . $nom_miniature . '.' . $extension_miniature . '',
                        $_SESSION['id']
                    ));
                    header('Location: profil.php');
                }
                else
                {
                    echo 'Erreur mime ! ';
                }
            }
            else
            {
                echo 'Veuillez choisir une miniature au format png, jpg, jpeg ou gif !';
            }

        }
    }
    else
    {
        echo 'Votre photo de profil ne doit pas dépasser les 2 Mo !';
    }
}

$user = $bdd->prepare('SELECT * FROM users WHERE pseudo = ?');
$user->execute(array(
    $_SESSION['pseudo']
));
$user = $user->fetch();

if (isset($_POST['modif']))
{
    if (strlen($_POST['modif-pseudo']) < 13)
    {
        $requete_pseudo = $bdd->prepare('SELECT * FROM users WHERE pseudo = ?');
        $requete_pseudo->execute(array(
            $_POST['modif-pseudo']
        ));
        $pseudo_exist = $requete_pseudo->rowCount();

        if ($pseudo_exist == 0 || $_POST['modif-pseudo'] == $_SESSION['pseudo'])
        {
            $requete_email = $bdd->prepare('SELECT * FROM users WHERE email = ?');
            $requete_email->execute(array(
                $_POST['modif-email']
            ));
            $email_exist = $requete_email->rowCount();

            if ($email_exist == 0 || $_POST['modif-email'] == $_SESSION['email'])
            {
                $modif_pseudo1 = $bdd->prepare('UPDATE amis SET pseudo1 = ? WHERE pseudo1 = ?');
                $modif_pseudo1->execute(array(
                    $_POST['modif-pseudo'],
                    $_SESSION['pseudo']
                ));

                $modif_pseudo2 = $bdd->prepare('UPDATE amis SET pseudo2 = ? WHERE pseudo2 = ?');
                $modif_pseudo2->execute(array(
                    $_POST['modif-pseudo'],
                    $_SESSION['pseudo']
                ));

                $_SESSION['pseudo'] = $_POST['modif-pseudo'];
                $_SESSION['email'] = $_POST['modif-email'];
                $_SESSION['bio'] = $_POST['modif-bio'];

                $modif = $bdd->prepare('UPDATE users SET pseudo = ?, email = ?, bio = ? WHERE id = ?');
                $modif->execute(array(
                    $_SESSION['pseudo'],
                    $_SESSION['email'],
                    $_SESSION['bio'],
                    $_SESSION['id']
                ));
            }
            else
            {
                $error = 'Cette addresse mail est déjà prise !';
            }
        }
        else
        {
            $error = 'Ce pseudo existe déjà !';
        }
    }
    else
    {
        $error = "Votre nom d'utilisateur ne doit pas dépasser 12 caractères !";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modifier le profil * <?php echo $_SESSION['pseudo'] ?></title>
    <?php include 'head.php'; ?>
</head>
<body>
    <?php
include 'menu.php';
?>
    <div class="bloc-edit-profil">
        <div class="flex">
            <div class="flex-column content-list-edit">
                <span class="edit-modif-profil"><a href="edit.php">modifier le profil</a></span>
                <span class="edit-modif-pp"><a href="avatar.php">modifier la photo de profil</a></span>
                <span class="edit-modif-miniature"><a href="miniature.php">modifier la miniature</a></span>
                <span class="edit-modif-mdp"><a href="mdp.php">modifier le mot de passe</a></span>
            </div>
            <div class="content-edit-modif-profil">
                <div class="flex-column">
                    <div class="flex head-edit">
                        <img src="avatars/<?php echo $user['avatar']; ?>" class="edit-avatar">
                        <p class="edit-pseudo"><?php echo $user['pseudo']; ?></p>
                    </div>
                    <table class="table-edit-profil">
                        <form method="POST">
                            <tr>
                                <td align="right">
                                    <label for="modif-pseudo" class="label-modif-pseudo">nom d'utilisateur</label>
                                </td>
                                <td>
                                    <input type="text" name="modif-pseudo" value="<?php echo $user['pseudo']; ?>" id="modif-pseudo">
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <label for="modif-email" class="label-modif-email">adresse mail</label>
                                </td>
                                <td>
                                    <input type="email" name="modif-email" value="<?php echo $user['email']; ?>" id="modif-email">
                                </td>
                            </tr>
                            <tr>
                                <td align="right">
                                    <label for="modif-bio" class="label-modif-bio">biographie</label>
                                </td>
                                <td>
                                    <textarea name="modif-bio" id="modif-bio" maxlength="200"><?php echo $user['bio']; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <?php 
                                        if (isset($error))
                                        {
                                            echo '<p class="alerte-edit">' . $error . '</p>';
                                        } 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td align="right">
                                    <input type="submit" name="modif" value="modifier" class="edit-submit-modif">
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
