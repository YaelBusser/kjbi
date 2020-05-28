<?php 
	session_start(); // J'exécute cette fonction utiliser les fonctions supergobales $_SESSION 
	require 'bdd.php'; // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.
	$requete_user = $bdd -> prepare('SELECT * FROM users WHERE id = ?');
	$requete_user -> execute(array($_SESSION['id']));
	$user = $requete_user -> fetch();

	$req_amis = $bdd -> prepare('SELECT * FROM amis WHERE pseudo1 = ? OR pseudo2= ?');
	$req_amis -> execute(array($_SESSION['pseudo'], $_SESSION['pseudo']));
	$nb_amis = $req_amis -> rowCount();

	$req_pub = $bdd -> prepare('SELECT * FROM publications WHERE pseudo = ?');
	$req_pub -> execute(array($_SESSION['pseudo']));
	$nb_pub = $req_pub -> rowCount();
	
    if(!empty($_POST['minia_send']))
        {
            $nom_post = strtolower($_FILES['post']['name']);
            $chemin_temp = $_FILES['post']['tmp_name'];
            $chemin_post = 'publications';
            $extension = array('png','jpg','jpeg','gif');
            $size = 2097152;
            if($_FILES['post']['size'] < $size)
            {
                if(!empty($chemin_temp))
                {
                    $img = explode('.', $nom_post);
                    $extension_post = $img[1];
                    
                    $nb_pub_img = $nb_pub + 1;
                    $nom_post = ''.$_SESSION['pseudo'].''.$nb_pub_img.'';
                    
                    if(in_array($extension_post, $extension))
                    {
                        $array_mime = array('image/png','image/jpeg','image/x-icon','image/gif');
                        $mime_test = mime_content_type($chemin_temp);
                        if(in_array($mime_test, $array_mime))
                        {
                            move_uploaded_file($chemin_temp, ''.$chemin_post.'/'.$nom_post.'.'.$extension_post.'');
                            $requete_post = $bdd -> prepare('INSERT INTO publications(pseudo, media, comment, jm) VALUES(?, ?, ?, ?)');
                            $requete_post -> execute(array($_SESSION['pseudo'],''.$nom_post.'.'.$extension_post.'', $_POST['comment-pub'], 0));
                        }
                        else
                        {
                            echo 'Erreur mime ! ';
                        }
                    }
                    else
                    {
                        echo 'Veuillez choisir votre photo au format png, jpg, jpeg ou gif !';
                    }
                    
                }
            }
            else
            {
                echo 'Votre publication ne doit pas dépasser les 2 Mo !';
            }
        }

	if(isset($_SESSION['id']) OR isset($_GET['pseudo']) OR isset($_GET['id']))
	{
?>
<!DOCTYPE html>
<html>
<head>
	<title>KJBi 😷 <?php echo $_SESSION['pseudo']; ?></title>
	<?php 
		include 'head.php';
	?>
</head>
<body>
		<?php 
			include 'menu.php';
		?>
		<form enctype="multipart/form-data" method="POST">
			<div id="bloc-body">
			    <div id="bloc-add-pub">
                    <div class="flex-column">
                        <img src="images/croix.png" id="croix">
                        <p class="intro-pub">Partagez ce que vous voulez :)</p>	
                            
                        <img src="images/img-pub.png" class="img-publier">
                        <div class="auto flex-column">
                            <input type="file" name="post" class="input-post">
                            <textarea class="comment-pub" maxlength="500" placeholder="Commentez votre publication..." name="comment-pub"></textarea>
                            <input type="submit" name="minia_send" value="publier" class="btn-minia">
                        </div>
                    </div>
			    </div>
			</div>
		</form>
		<div class="flex-column bloc-profil">
			<div class="flex profil">
				<img src="miniatures/<?php echo $user['miniature']; ?>" class="miniature">
				<div class="partie-profil flex">
					<img class="avatar" src="avatars/<?php echo $user['avatar']; ?>">
					<h1 class="pseudo-profil"><?php echo $user['pseudo']; ?></h1>
					<a href="edit.php"><p class="modifier">modifier</p></a>
					<p><?php echo '<p class="nb-amis-profil">'.$nb_amis.'<span style="font-weight: normal; margin-left: 0.2em;">amis</span></p>'; ?></p>
					<p><?php echo '<p class="nb-publication-profil">'.$nb_pub.'<span style="font-weight: normal; margin-left: 0.2em;">publications</span></p>' ?></p>
					<?php 
						echo '<p class="profil-bio">'.$user['bio'].'</p>';
					?>
				</div>
			</div>
			<div class="publication-profil">
				<div class="flex-column">
					<div class="flex head-pub">
						<img src="images/photo.png" class="photo">
						<h1 class="title-pub">PUBLICATIONS</h1>
					</div>
					<img src="images/add-pub.png" class="img-add-pub" id="add-pub">
					<div class="barre-pub"></div>
                    <div>
                        <?php 
                        
                            $requete_pub = $bdd -> prepare('SELECT * FROM publications WHERE pseudo = ? ORDER BY id DESC');
                            $requete_pub -> execute(array($_SESSION['pseudo']));
                            $pub = $requete_pub -> fetchAll();      
                        ?>
                        <?php 
                            foreach($pub as $publication)
                            {
                                echo '    
                                    <div class="bloc-publi">
                                        <div class="overlay">
                                            <p class="text-publi">'.$publication["comment"].'</p>
                                            <p class="text-publi">'.$publication["jm"].'j\'aime</p>
                                        </div>
                                        <img src="publications/'.$publication['media'].'" class="img-pub">
                                    </div>
                                '; 
                            }
                        ?> 
                    </div>
				</div>
		    </div>
		</div>

	<?php 
		}
		else
		{
			header("Location: connexion.php");
		}
	?>
	<?php 
		include 'footer.php';
	?>
	<script>
		document.getElementById('add-pub').onclick = function() {
    		add()
		}

		function add() {
		    const add = document.getElementById('bloc-body').style;

		    if (add.visibility == 'visible') {

		    } else {
		        add.visibility = 'visible';
		    }

		}
		
		document.getElementById('croix').onclick = function() {
		    croix()
		}

		function croix() {
		    const body = document.getElementById('bloc-body').style;
		    body.visibility = 'hidden';
		}
        		document.getElementById('add-pub').onclick = function() {
    		add()
		}
                
	</script>
</body>
</html>