<?php 

session_start();
session_destroy();
session_start();

?>

<!DOCTYPE html>
<html>
<head>
<title>Poste Autonome</title>
<meta charset="utf-8">
<!-- Compatibilité Internet Explorer-->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Definition de l'echelle d'affichage a 1. 100% -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Appel Feuille de Style Bootstrap -->
<link href="res/bootstrap/css/bootstrap.css" rel="stylesheet">
<!-- Appel à la feuille de style thème Bootstrap -->
<link href="res/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
<!-- Appel à la feuille de style thème perso -->
<link href="res/stylesheet/login.css" rel="stylesheet">
</head>


	<body>
<!-- ma class container qui englobe tout-->
<div class="container">
    <!-- je crée une ligne (row)-->
    <div class="row">
      <!-- je crée un conteneur prenant les 12 colonnes de ma row-->
      <div class="col-xs-12">
          <!-- ma class main-->    
          <div class="main">
              <!-- je crée une ligne (row)-->                  
              <div class="row">
                <!-- je crée un conteneur prenant les 12 colonnes de ma row-->
                <div class="col-xs-12 col-sm-6 col-sm-offset-1">                          
                  <h1>POSTE AUTONOME</h1>
                  <h2>LA BERNARDERIE</h2>
                  <!--Formulaire-->                          
                  <form method="POST" action="controleur/logincontrol.php" name="form" id="formlogin" onkeyup="checkloginbutton(); return false;" class="form-horizontal" accept-charset="utf-8">
                      <!--class formgroup equivalent a une nouvelle ligne-->
                      <div class="form-group">
                        <!--mon input text prend 8 colonnes sur des ecrans moyen-->
                        <div class="col-md-8">
                        <input type="text" name = "name" id="name" placeholder="Idenfiant" class="form-control" onkeyup="checkloginname(); return false;"/>
                        </div>
                      </div> 
                      
                      <div class="form-group">
                        <div class="col-md-8"><input type="password" name ="password" id="password" placeholder="Mot de passe" class="form-control" onkeyup="checkloginpass(); return false;"/>
                        </div>
                      </div>                      
                      <div class="form-group">
                        <div class="col-md-offset-0 col-md-8"><input type="submit" id="log_button" class="btn btn-success btn btn-success" value="Connexion" disabled ="true"/></div>
                      </div>                  
                  </form>
                    <p class="credits"> <a href="http://www.sncf-reseau.fr/fr" target="_blank">SNCF RESEAU - INGENIERIE & PROJETS</a>
                      </br>6 avenue François Mitterrand
                      </br>93574 La Plaine St Denis
                    </p>                 
                 </div>
               </div>
          </div>
      </div>
    </div>
  </div>

         <script type="text/javascript" src="res/jquery-2.2.3.min.js"></script>
         <script type="text/javascript" src="controleur/inputcontrol/login.js"></script>
	</body>




</html>