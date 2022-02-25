<?php 
    session_start();
    require("bdd.php");
    if(isset($_POST['btn-connexion'])){
        $mailConnexion = htmlspecialchars($_POST["mail"]);
        $mdpConnexion = htmlspecialchars($_POST["mdp"]);
        $mdpConnexion = sha1($mdpConnexion);
        echo $mdpConnexion;
        $requete_membres = $bdd -> prepare("SELECT mail, mdp FROM users WHERE mail = ? AND mdp = ?");
        $requete_membres -> execute(array($mailConnexion, $mdpConnexion));
        $user_exist = $requete_membres -> rowCount();
        if($user_exist == 1){
            $requete_user = $bdd -> prepare("SELECT * FROM users WHERE mail = ?");
            $requete_user -> execute(array($mailConnexion));
            $user = $requete_user -> fetch(); 
            $_SESSION["id"] = $user["id"];
            $_SESSION["pseudo"] = $user["pseudo"];
            $_SESSION["mail"] = $user["mail"];
            $_SESSION["mdp"] = $user["mdp"];
            header("Location: connexion.php");
        }
    }
    if(isset($_POST['btnInscription'])){
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $difficulte = htmlspecialchars($_POST["difficulte"]);
        $force = htmlspecialchars($_POST["force"]);
        $agilite = htmlspecialchars($_POST["agilite"]);
        $dexterite = htmlspecialchars($_POST["dexterite"]);
        $constitution = htmlspecialchars($_POST["constitution"]);
        $mailInscription = htmlspecialchars($_POST['mailInscription']);
        $mailInscription2 = htmlspecialchars($_POST['mailInscription2']);
        $mdpInscription = sha1($_POST['mdpInscription']);
        $mdpInscription2 = sha1($_POST['mdpInscription2']); 
    }
?>
<!DOCTYPE html>
<html>
    <?php
        include("head.php");
    ?>
    <body lang="fr">
        <header>

        </header>
        <div class="loader">
            <span class="lettre">C</span>
            <span class="lettre">H</span>
            <span class="lettre">A</span>
            <span class="lettre">R</span>
            <span class="lettre">G</span>
            <span class="lettre">E</span>
            <span class="lettre">M</span>
            <span class="lettre">E</span>
            <span class="lettre">N</span>
            <span class="lettre">T</span>
        </div>
        <div class="flex-column accueil-block">
            <img src="images/LogoAventure.jpg">
            <h1>Le jeu</h1>
            <form method="POST" class="form-connexion">
                <div class="flex-column connexion" id="connexion">
                    <h2>Connexion</h2>
                    <div class="flex-column">
                        <div class="flex auto">
                            <label for="mail"><i class="fa fa-user"></i></label>
                            <input type="email" name="mail" id="mail" placeholder="Entrez votre adresse mail...">
                        </div>
                        <div class="erreurForm" id="erreurMail"></div>
                    </div>
                    <div class="flex auto">
                        <label for="mdp"><i class="fa fa-lock"></i></label>
                        <input type="password" name="mdp" id="mdp" placeholder="Entrez votre mot de passe...">
                    </div>
                    <input type="submit" name="btn-connexion" id="btn" value="Entrez dans l'aventure !">
                    <p class="text-under-submit">Pas encore inscrit ? <span id="span-inscription">Cliquez ici.</span></p>
                </div>
            </form>
            <form method="GET" action="inscription.php" class="form-connexion">
                <div class="flex-column inscription" id="inscription">
                    <h2>Créer votre avatar !</h2>
                    <div class="flex-column">
                        <div class="flex auto">
                            <label for="pseudo"><i class="fa fa-user-circle"></i></label>
                            <input type="text" name="pseudo" id="pseudo" placeholder="Entrez votre pseudonyme...">
                        </div>
                        <div class="erreurForm"></div>
                    </div>
                    <div class="flex-column">
                        <div class="flex auto">
                            <label for="difficulte"><i class="iconify" data-icon="emojione-monotone:level-slider"></i></label>
                            <select name="difficulte" id="difficulte">
                                <option value=""><span>Veuillez sélectionner une difficulté...</span></option>
                                <option value="facile">facile</option>
                                <option value="normale">normale</option>
                                <option value="challenge">challenge</option>
                                <option value="impossible">impossible</option>
                            </select>
                        </div>
                        <div class="erreurForm"></div>
                    </div>
                    <div class="flex caracteristiques">
                        <label for="force"><i class="iconify" data-icon="icon-park-outline:muscle"></i></label>
                        <img id="img-arrow-left-force" src="images/arrow_left.png" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" >
                        <input type="number" min="0" max="40" placeholder="0" name="force" id="force" value="10" readonly>
                        <img id="img-arrow-right-force" src="images/arrow_right.png" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                        <div class="bonus-malus" id="bonusMalusForce"></div>
                    </div>
                    <div class="flex caracteristiques">
                        <label for="agilite"><i class="iconify" data-icon="grommet-icons:yoga"></i></label>
                        <img id="img-arrow-left-agilite" src="images/arrow_left.png" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" >
                        <input type="number" min="0" max="40" placeholder="0" name="agilite" id="agilite" value="10" readonly>
                        <img id="img-arrow-right-agilite" src="images/arrow_right.png" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                        <div class="bonus-malus" id="bonusMalusAgilite"></div>
                    </div>
                    <div class="flex caracteristiques">
                        <label for="dexterite"><i class="iconify" data-icon="icon-park-outline:brain"></i></label>
                        <img id="img-arrow-left-dexterite" src="images/arrow_left.png" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" >
                        <input type="number" min="0" max="40" placeholder="0" name="dexterite" id="dexterite" value="10" readonly>
                        <img id="img-arrow-right-dexterite" src="images/arrow_right.png" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                        <div class="bonus-malus" id="bonusMalusDexterite"></div>
                    </div>
                    <div class="flex caracteristiques">
                        <label for="constitution"><i class="iconify" data-icon="ion:body"></i></label>
                        <img id="img-arrow-left-constitution" src="images/arrow_left.png" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" >
                        <input type="number" min="0" max="40" placeholder="0" name="constitution" id="constitution" value="10" readonly>
                        <img id="img-arrow-right-constitution" src="images/arrow_right.png" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                        <div class="bonus-malus" id="bonusMalusConstitution"></div>
                    </div>
                    <div class="flex auto">
                        <label for="mailInscription"><i class="iconify" data-icon="bi:at"></i></label>
                        <input type="email" name="mailInscription" id="mailInscription" placeholder="Entrez votre adresse mail...">
                    </div>
                    <div class="flex auto">
                        <label for="mailInscription2"><i class="iconify" data-icon="bi:at"></i></label>
                        <input type="email" name="mailInscription2" id="mailInscription2" placeholder="Confirmez votre adresse mail...">
                    </div>
                    <div class="flex auto">
                        <label for="mdpInscription"><i class="fa fa-lock"></i></label>
                        <input type="password" name="mdpInscription" id="mdpInscription" placeholder="Entrez votre mot de passe...">
                    </div>
                    <div class="flex auto">
                        <label for="mdpInscription2"><i class="fa fa-lock"></i></label>
                        <input type="password" name="mdpInscription2" id="mdpInscription2" placeholder="Confirmez votre mot de passe...">
                    </div>
                    <input type="submit" name="btnInscription" id="btnInscription" value="S'inscrire">
                    <p class="text-under-submit">Déjà inscrit ? <span id="span-connexion">Cliquez ici.</span></p>
                </div>
            </form>
        </div>
        <div class="accueil-description">
            <h1>Description</h1>
            <div class="block-p-description auto">
                <p>Bienvenue dans Aventure, jeu de rôle en ligne dans lequel vous incarnerez un avatar.</p>
                <p>Cet avatar arrivera dans un monde dont il ne connaît rien avec un équipement limité.</p>
                <p>Son seul but sera de survivre, de progresser et de trouver la clé permettant de passer dans le monde suivant.</p>
                <p>Etes-vous prêt à tenter l’aventure ?</p>
            </div>
        </div>
        <footer>
            <?php 
                include("footer.php");
            ?>
        </footer>
        <script src="js/main.js"></script>
    </body>
</html>