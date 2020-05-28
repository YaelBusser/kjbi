<?php 
	session_start(); // J'exécute cette fonction utiliser les fonctions supergobales $_SESSION 
	require 'bdd.php'; // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.
	if(isset($_POST['add']))
	{
		$req_exist = $bdd -> prepare('SELECT * FROM amis WHERE pseudo1 = ? AND pseudo2 = ? OR pseudo1 = ? AND pseudo2 = ?');
		$req_exist -> execute(array($_SESSION['pseudo'], $_GET['pseudo'], $_GET['pseudo'], $_SESSION['pseudo']));
		$exist_amis = $req_exist -> rowCount();
		if($exist_amis > 0 )
		{
			$requete_add_amis = $bdd -> prepare('UPDATE amis SET demande = ? WHERE pseudo1 = ? AND pseudo2 = ?');
			$requete_add_amis -> execute(array(1, $_SESSION['pseudo'], $_GET['pseudo']));
		}
		else
		{
			$requete_add_amis = $bdd -> prepare('INSERT INTO amis(pseudo1, pseudo2, demande, accept, refuse) VALUES(?, ?, ?, ?, ?)');
			$requete_add_amis -> execute(array($_SESSION['pseudo'], $_GET['pseudo'], 1, 0, 0));
		}
	}
	if(isset($_POST['undemande']))
	{
		$requete_demande = $bdd -> prepare('DELETE FROM amis WHERE pseudo1 = ? AND pseudo2 = ?');
		$requete_demande -> execute(array($_SESSION['pseudo'], $_GET['pseudo']));
	}
	if(isset($_POST['accept']))
	{
		$requete_accept = $bdd -> prepare('UPDATE amis SET demande = ?, accept = ? WHERE pseudo1 = ? AND pseudo2 = ?');
		$requete_accept -> execute(array(0, 1, $_GET['pseudo'], $_SESSION['pseudo']));
	}
	if(isset($_POST['del-friend']))
	{
		$del = $bdd -> prepare('DELETE FROM amis WHERE pseudo1 = ? AND pseudo2 = ? OR pseudo1 = ? AND pseudo2 = ?');
		$del -> execute(array($_SESSION['pseudo'], $_GET['pseudo'], $_GET['pseudo'], $_SESSION['pseudo']));
	}
	$req_amis = $bdd -> prepare('SELECT * FROM amis WHERE pseudo1 = ? OR pseudo2= ?');
	$req_amis -> execute(array($_GET['pseudo'], $_GET['pseudo']));
	$nb_amis = $req_amis -> rowCount();

	$req_pub = $bdd -> prepare('SELECT * FROM publications WHERE pseudo = ?');
	$req_pub -> execute(array($_GET['pseudo']));
	$nb_pub = $req_pub -> rowCount();
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $_GET['pseudo'] ?></title>
	<?php 
		include 'head.php';
	?>
</head>
<body>
	<?php 
		include 'menu.php';

		$query_personne = $bdd -> prepare('SELECT * FROM users WHERE pseudo = ?');
		$query_personne -> execute(array($_GET['pseudo']));
		$personne = $query_personne -> fetch();
	?>
    <div class="flex-column bloc-profil">
	<div class="flex profil">
			<img src="miniatures/<?php echo $personne['miniature']; ?>" class="miniature" style="background-size: cover;">
			<div class="flex partie-profil">
				<img class="avatar" src="avatars/<?php echo $personne['avatar']; ?>">
				<h1 class="pseudo-profil"><?php echo $personne['pseudo']; ?></h1>
				<form method="POST">
					<?php 
						$req_exist = $bdd -> prepare('SELECT * FROM amis WHERE pseudo1 = ? AND pseudo2 = ? AND demande = ?');
						$req_exist -> execute(array($_SESSION['pseudo'], $_GET['pseudo'], 1));
						$exist_amis = $req_exist -> rowCount();

						$requete_supp_amis = $bdd -> prepare('SELECT * FROM amis WHERE pseudo1 = ? AND pseudo2 = ? OR pseudo1 = ? AND pseudo2 = ?');
						$requete_supp_amis -> execute(array($_SESSION['pseudo'], $_GET['pseudo'], $_GET['pseudo'], $_SESSION['pseudo']));
						$rien = $requete_supp_amis -> rowCount();
						if($exist_amis)
						{
					?>
						<input type="submit" name="undemande" id="-" value="ne plus demander" class="undemande">
					<?php 
						}
						else
						{
					?>
						<?php 
							$new_req = $bdd -> prepare('SELECT * FROM amis WHERE pseudo1 = ? AND pseudo2 = ? AND demande = ?');
							$new_req -> execute(array($_GET['pseudo'], $_SESSION['pseudo'], 1));
							$new_req = $new_req -> rowCount();
							if($new_req > 0)
							{
						?>
								<input type="submit" id="+" name="accept" value="+ accepter" class="add" >
						<?php
							} 
							else if($rien == null)
							{
						?>
								<input type="submit" id="+" name="add" value="+ ajouter" class="add" >
						<?php }?>
					<?php }?>
						<?php 
							$already_friend = $bdd -> prepare('SELECT * FROM amis WHERE pseudo1 = ? AND pseudo2 = ? AND accept = ? OR pseudo1 = ? AND pseudo2 = ? AND accept = ?');
							$already_friend -> execute(array($_SESSION['pseudo'], $_GET['pseudo'], 1,$_GET['pseudo'], $_SESSION['pseudo'], 1));
							$friend = $already_friend -> rowCount();
							if($friend)
							{ 
						?>
							<input type="submit" name="del-friend" value="supprimer cet ami" class="del-friend">
						<?php }?>

						<p><?php echo '<p class="nb-amis-user">'.$nb_amis.'<span style="font-weight: normal; margin-left: 0.2em;">amis</span></p>'; ?></p>

						<p><?php echo '<p class="nb-publication-user">'.$nb_pub.'<span style="font-weight: normal; margin-left: 0.2em;">publications</span></p>' ?></p>
				</form>
			</div>
	</div>
	<div class="publication-profil">
				<div class="flex-column">
					<div class="flex head-pub">
						<img src="images/photo.png" class="photo">
						<h1 class="title-pub">PUBLICATIONS</h1>
					</div>
					<div class="barre-pub"></div>
                    <div>
                        <?php 
                        
                            $requete_pub = $bdd -> prepare('SELECT * FROM publications WHERE pseudo = ? ORDER BY id DESC');
                            $requete_pub -> execute(array($_GET['pseudo']));
                            $pub = $requete_pub -> fetchAll();
                            
                        ?>
                        <?php 
                            foreach($pub as $publication)
                            {
                                echo '
                                
                                
                                    
                                    <div class="bloc-publi">
                                        <div class="overlay">
                                            <p class="text-publi">'.$publication["comment"].'</p>
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
	<script>
		document.getElementById('+').style.cursor = 'pointer';
		document.getElementById('-').style.cursor = 'pointer';
	</script>
</body>
</html>