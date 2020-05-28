<?php
	session_start(); // J'exécute cette fonction utiliser les fonctions supergobales $_SESSION .
	require('bdd.php'); // Ici la fonction require() appelle le fichier bdd.php afin de ne pas exécuter le reste du code s'il y a une erreur avec la connexion de la base de donnée.
?>
<!DOCTYPE html>
<html>
<head>
	<title>Accueil 👾 KJBI</title>
	<?php 
		include('head.php') // Ici la fonction include() va faire apparaître le fichier head.php et s'il y a une erreur avec ce fichier, ça ne va pas empêcher d'afficher tout le
							// reste du code, c'est donc le contraire de la fonction require() de ce point de vue.
	?>
</head>
<body>
	<?php
		include('menu.php');  // Ici la fonction include() va faire apparaître le fichier head.php et s'il y a une erreur avec ce fichier, ça ne va pas empêcher d'afficher tout le
								// reste du code, c'est donc le contraire de la fonction require() de ce point de vue.
	?>
	    <?php 

    	include 'footer.php'; // Ici, ça ne change rien au niveau du fonctionnement de la fonction inculde, c'est juste une évolution de la fonction où on enlève les parenthèses.

    ?>
</body>
</html>