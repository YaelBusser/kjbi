<?php 
    require("bdd.php");
    if(isset($_GET['pseudo']) && isset($_GET["difficulte"]) && isset($_GET["force"]) &&
        isset($_GET["agilite"]) && isset($_GET["dexterite"]) && isset($_GET["constitution"]) && 
        isset($_GET['mailInscription']) && isset($_GET['mailInscription2']) && isset($_GET['mdpInscription']) &&
        isset($_GET['mdpInscription2'])){
            $pseudo = htmlspecialchars($_GET['pseudo']);
            $difficulte = htmlspecialchars($_GET["difficulte"]);
            $force = htmlspecialchars($_GET["force"]);
            $agilite = htmlspecialchars($_GET["agilite"]);
            $dexterite = htmlspecialchars($_GET["dexterite"]);
            $constitution = htmlspecialchars($_GET["constitution"]);
            $mailInscription = htmlspecialchars($_GET['mailInscription']);
            $mailInscription2 = htmlspecialchars($_GET['mailInscription2']);
            $mdpInscription = sha1($_GET['mdpInscription']);
            $mdpInscription2 = sha1($_GET['mdpInscription2']); 
            
            $creation_compte = $bdd -> prepare("INSERT INTO users(pseudo, strength, agilite, dexterite, constitution, mail, mdp) VALUES(?, ?, ?, ?, ?, ?, ?)");
            $creation_compte -> execute(array($pseudo, $force, $agilite, $dexterite, $constitution, $mailInscription, $mdpInscription));

            echo "<p>Pseudo : ".$pseudo.
                 "</p><p>Difficulté : ".$difficulte.
                 "</p><p>Force : ".$force.
                 "</p><p>Agilite : ".$agilite.
                 "</p><p>Dextérité : ".$dexterite.
                 "</p><p>Constitution : ".$constitution.
                 "</p><p>Mail : ".$mailInscription.
                 "</p><p>Mail 2 : ".$mailInscription2.
                 "</p><p>Mot De Passe : ".$mdpInscription.
                 "</p><p>Mot De Passe 2 :".$mdpInscription2."</p>"   
            ;
    }else{
        Header("Location: accueil.php");
    }
?>