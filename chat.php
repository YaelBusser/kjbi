<?php 
	session_start();
	require 'bdd.php';
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