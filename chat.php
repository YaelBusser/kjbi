<?php 
	session_start(); // J'exécute cette fonction utiliser les fonctions supergobales $_SESSION 
	require 'bdd.php'; // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.
?>
<!DOCTYPE html>
<html>
<head>
	<title>KJBI | CHAT</title>
	<?php 
		include 'head.php';
	?>
</head>
<body>
	<?php
		include 'menu.php';
	?>
	<div class="box-chat">
        <div class="box-send flex">
            <img src="images/importer.png" class="img-import">
            <input type="text" name="send-msg" placeholder="envoyer un message à <?php echo $_GET['pseudo']; ?>" class="send-msg">
        </div>
	</div>
</body>
</html>