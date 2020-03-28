<?php
	if(isset($_GET['id']) OR isset($_SESSION['id'])){ 
        $requete_user = $bdd -> prepare('SELECT * FROM users WHERE id = ?');
        $requete_user -> execute(array($_SESSION['id']));
        $user = $requete_user -> fetch();
?>
<header>
    <div class="flex">
        <nav class="menu flex-column">
            <div class="head-menu">
                <a href="accueil.php" class="kjbi-hl flex">
                    <img src="images/photo.png" class="img-photo-hl">
                    <h1>KJBi</h1>
                </a>
            </div>
            <a href="profil.php" class="menu-profil flex-column auto">
                <img src="avatars/<?php echo $user['avatar']; ?>" class="menu-img-profil">
                <p class="menu-pseudo"><?php echo $user['pseudo']; ?></p>

                <?php 
                    if($user['etat'] == 'online'){
                        $etat = '<span style="color: green;">En ligne</span>';
                    }else{
                        $etat = '<span style="color: red">Hors ligne</span>';
                    }
                ?>

                <p class="menu-etat"><?php echo $etat; ?></p>
            </a>
            <form method="GET" action="users.php">
                <div class="form-search-hl flex">
                    <img src="images/search.png" class="img-search-hl">
                    <input type="search" name="pseudo" class="search-user-hl" placeholder="Rechercher une personne..." autocomplete="off">
                </div>
            </form>


        </nav>
        <div class="menu-deux"></div>
    </div>
    
    
<?php 
		$requete_notif = $bdd -> prepare('SELECT * FROM amis WHERE pseudo2 = ? AND demande = ?');
		$requete_notif -> execute(array($_SESSION['pseudo'], 1));
		$notif = $requete_notif -> rowCount();

		$etat = $bdd -> prepare('SELECT * FROM users WHERE id = ?');
		$etat -> execute(array($_SESSION['id']));
		$etat = $etat -> fetch();
?>
    
    
	<div class="partie-amis">
		<div class="head-partie-amis">
			<div class="flex">
			<div class="flex">
				<?php if(!$notif){ echo "<p id='notif-amis-none'><span style='color: rgba(0,0,0,0);'></span></p>"; } else{ echo '<p id="notif-amis">'.$notif.'</p>';}; ?></p>
				<div id="notif-menu">
					<?php 
						$req_demande = $bdd -> prepare('SELECT * FROM amis WHERE pseudo2 = ? AND demande = ?');
						$req_demande -> execute(array($_SESSION['pseudo'], 1));
						$notif_demande = $req_demande -> fetchAll();
						foreach($notif_demande as $sender_demander)
						{
							$req_demande_user = $bdd -> prepare('SELECT * FROM users WHERE pseudo = ?');
							$req_demande_user -> execute(array($sender_demander['pseudo1']));
							$demander_user = $req_demande_user -> fetch();
							echo '
								<div class="partie-accept-refuse">
									<div class="bloc-demander flex">
										<a href="user.php?pseudo='.$demander_user['pseudo'].'"><img src="avatars/'.$demander_user['avatar'].'" class="avatar-demander"></a>
											<a href="user.php?pseudo='.$sender_demander['pseudo1'].'">
												<p class="sender-demande">'.$sender_demander['pseudo1'].'</p>
											</a>
											<form method="POST" class="flex">
												<input type="submit" class="add-amis-menu" name="accept'.$sender_demander['pseudo1'].'" value=" accepter">
												<input type="submit" class="del-amis-menu" name="refuse'.$sender_demander['pseudo1'].'" value=" refuser">
											</form>
									</div>
                                </div>
									
								';
								$compte_accept = $bdd -> prepare('SELECT * FROM users WHERE pseudo = ?');
								$compte_accept -> execute(array($sender_demander['pseudo1']));
								$compte_accept = $compte_accept -> fetch();
								if(isset($_POST['accept'.$sender_demander['pseudo1'].'']))
								{
									$accept = $bdd -> prepare('UPDATE amis SET demande = ?, accept = ? WHERE pseudo1 = ? AND pseudo2 = ?');
									$accept -> execute(array(0, 1, $sender_demander['pseudo1'], $_SESSION['pseudo']));
								}
                                if(isset($_POST['refuse'.$sender_demander['pseudo1'].'']))
                                {
                                    $refuse = $bdd -> prepare('DELETE FROM amis WHERE pseudo1 = ? AND pseudo2 = ?');
                                    $refuse -> execute(array($sender_demander['pseudo1'], $_SESSION['pseudo']));
                                }
						}
					?>
				</div>
				<p class="menu-amis" id="menu-amis">AMIS</p>
				<form method="POST" class="flex">
					<div class="flex-column">
						<div class="flex">
							<input type="search" name="pseudo-amis" class="recherche-amis" placeholder="Chercher des amis..." id="pseudo-amis">
							<p id="x">x</p>
						</div>

				</form>
				<div class="barre-amis"></div>
					<?php 

						$test = $bdd -> prepare('SELECT * FROM amis WHERE pseudo2 = ? AND accept = ? AND demande = ? OR pseudo1 = ? AND accept = ? AND demande = ?');
						$test -> execute(array($_SESSION['pseudo'], 1, 0, $_SESSION['pseudo'], 1, 0));
						$test = $test -> fetchAll();
						foreach($test as $te)
						{
							if($te['pseudo1'] == $_SESSION['pseudo'])
							{
								$pseudonyme = $te['pseudo2'];
							}
							else
							{
								$pseudonyme = $te['pseudo1'];
							}
							$requete_pseudonyme = $bdd -> prepare('SELECT * FROM users WHERE pseudo = ?');
							$requete_pseudonyme -> execute(array($pseudonyme));
							$amis_accepted = $requete_pseudonyme -> fetch();

							if($amis_accepted['etat'] == 'online')
							{
								$amis_accepted['etat'] = '<span class="online">En Ligne</span>';
							}
							else
							{
								$amis_accepted['etat'] = '<span class="offline">Hors Ligne</span>';
							}

							if(isset($amis_accepted['pseudo']))
							{ echo '
									<div class="pseudo-amis flex">
										<a href="user.php?pseudo='.$amis_accepted['pseudo'].'"><img src="avatars/'.$amis_accepted['avatar'].'" class="amis-avatar"></a>
										<div class="flex-column">
											<a href="user.php?pseudo='.$amis_accepted['pseudo'].'"><p class="nom-liste-amis">'.$amis_accepted['pseudo'].'</p></a>
											'.$amis_accepted['etat'].'
										</div>
										<a href="chat.php?pseudo='.$amis_accepted['pseudo'].'"><img src="images/chat.png" class="img-chat" id="img-chat"></a>
									</div>
								';
							}
						}

					?>
			</div>
			</div>
			</div>
		</div>
	</div>
	<?php 
		if($notif > 0)
		{
		?>
		<script>
			document.getElementById('notif-amis').style.backgroundColor = "rgba(255,0,0,0.8)";
			document.getElementById('notif-amis').style.cursor = "pointer";

			document.getElementById('notif-amis').onclick = function() { notif() };
			function notif()
			{
				const notif = document.getElementById('notif-menu').style;
				if(notif.display == 'block')
				{
					notif.display = 'none';
				}
				else
				{
					notif.display = 'block';
				}
			}
            document.getElementById('add-pub-menu').onclick = function() {
                add_menu()
            }
            function add_menu() {
                const add = document.getElementById('bloc-body').style;

                if (add.visibility == 'visible') {

                } else {
                    add.visibility = 'visible';
                }

            }
		</script>
	<?php 
		}
		else
		{
	?>
	
	<?php }?>
</header>
<script src="js/anims.js"></script>
<?php 
	}
	else
	{
?>
	<nav class="menu-hl flex-column">
        <a href="accueil.php" class="kjbi-hl flex">
            <img src="images/photo.png" class="img-photo-hl">
            <h1>KJBi</h1>
        </a>
		<a href="connexion.php" class="menu-hl-connexion flex">
            <img src="images/login.png" class="login-hl">
            <p>connexion</p>
        </a>
		<a href="inscription.php" class="menu-hl-inscription flex">
            <img src="images/inscription.png" class="sign-in-hl">
            <p>inscription</p>
        </a>
        <form method="GET" action="users.php">
            <div class="form-search-hl flex">
                <img src="images/search.png" class="img-search-hl">
                <input type="search" class="search-user-hl" placeholder="Rechercher une personne...">
            </div>
        </form>
	</nav>
<?php 
	}
?>

