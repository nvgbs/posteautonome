<?php 

session_start();

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Ajouter un utilisateur</title>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Ajouter un utilisateur</title>

		<!-- Appel Feuille de Style Bootstrap -->
		<link href="../res/bootstrap/css/bootstrap.css" rel="stylesheet">

		<!-- Appel à la feuille de style thème Bootstrap -->
		<link href="../res/bootstrap/css/bootstrap-theme.css" rel="stylesheet">

		<link href="../res/stylesheet/cssperso.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>

		<?php 

			if (isset($_SESSION['name']) AND ($_SESSION['role'] == 1))
			{
				
		?>  

				<div id="containeur" class="container-fluid">

				<!-- ********* HEADER ********** -->

				<!--Balise html5 qui englobe toute la navigation sur une ligne -->
				<header class="row">

				<!-- Création Barre de Navigation noire avec balise NAV html5 -->
				<!-- Balise NAV englobe toute la  navigation -->
				    <nav class="navbar navbar-inverse" role="navigation">    
				       <!-- DIV qui permet la navigaton sur terminal mobile -->
				      <div class="navbar-header">
				      <!-- Barre de navigation smartphone avec menu minifié -->
				         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbarmobile" 
				           aria-expanded= "false">
				            <span class="sr-only">Toggle navigation</span>
				            <span class="icon-bar"></span>
				            <span class="icon-bar"></span>
				            <span class="icon-bar"></span>
				         </button>
				         <!-- Nom ou Logo - class navbar-brand -->
				          <a href="#" class="navbar-brand"><strong>Poste Autonome</strong></a>
				      </div>
				    
				    
				     <!-- DIV qui permet la navigation sur tablette et écran -->
				    <div class="collapse navbar-collapse" id="navbarmobile">
				    <!-- MENU -->
				      <ul class="nav navbar-nav">
				         <li class="active"><a href="adduser.php">Ajouter un utilisateur</a></li>
				         <li><a href="importfile.php">Importer un fichier</a></li>
				         <li><a href="dataview.php">Visualiser des données</a></li>
				        
				      </ul>     
				     
				      
				      <!-- MENU DEROULANT -->
				      <ul class="nav navbar-nav navbar-right">
				         <li class="dropdown">
				           <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['name']?><span class="caret"></span></a>   
				            
				             <!-- CONTENU DU MENU DEROULANT -->
				             <ul class="dropdown-menu">              
				               <li><a href="../index.php">Se déconnecter</a></li>
				             </ul>    
				         </li>
				        </ul>
				    </div>
				    <!--Fin Barre de Navigation -->
				 </nav>
				</header>
				</div>



				<div class="container" style="margin-top:4em;">
				    <div class="row">
				     
			              <div class="row">
			              <div class="col-sm-4"> </div>
			                <div class="col-sm-4" style="margin-top:5em;">
			                 <div class="jumbotron" style="background-color: rgb(116,118,120);">
			                 <p class="titleimport" style="font-family: arial; font-weight: bold; color: #FFFFFF"> Nouveau Compte </p>
			                   <div style="margin-top:3em";>
			                  <form  action="../controleur/addusercontrol.php" method="POST" name="formadduser" id="formadduser" onkeyup="checkbutton(); return false;" class="form-horizontal" accept-charset="utf-8">
			                      <div class="form-group">
			                      <div class="col-md-12"><input type="text" class="form-control" name = "add_name" id="add_name" placeholder="Pseudo" autocomplete="off" onkeyup="checkname(); return false;"/>
			                      </div>
			                      </div> 
			                      
			                      <div class="form-group">
			                      <div class="col-md-12"><input type="password" class="form-control" name = "add_password" id="add_password" onkeyup="checkpass(); return false;" placeholder="Mot de passe"/></div>
			                      </div> 

			                      <div class="form-group">
			                      <div class="col-md-12"><input type="password" class="form-control" name = "confirm_password" id="confirm_password" onkeyup="checkconfirmpass(); return false;" placeholder="Confirmation du mot de passe"/></div>
			                      </div>

			                      <div class="form-group">
			                      <div class="col-md-12"><input type=checkbox class=checkbox-primary value = "1" name = "add_admin" id="add_admin"> &nbsp; &nbsp; <label style="color: #FFFFFF;">Droits Administrateur</label>
			                      </div>
			                      </div>                     
			                      
					                <div class="form-group">
			                      		<div class="col-md-offset-0 col-md-12"><input type="submit" id="add_button" class="btn btn-info btn btn-info" value="Enregistrer" disabled ="true"/></div>
			                      	</div>
			                                       
			                  </form>
			                  </div>
			                 </div>	                
                		</div>
                		<div class="col-sm-4">  </div>             
         			 </div>
      			
   			 		</div>
 				</div>

				<?php	
				if (isset($_SESSION['msg_adduser']))
				{
					switch ($_SESSION['msg_adduser']) 
					{
						case true:
				?>
							<div class="alert alert-info" role="alert">
								<p class = "msg_useradd"> Utilisateur enregistré ! </p>
							</div>
				<?php
							unset($_SESSION['msg_adduser']);
							break;
						
						case false:
				?>
							<div class="alert alert-warning" role="alert">
								<p class = "msg_useradd"> Utilisateur déja existant ! </p>
							</div>
				<?php							
							unset($_SESSION['msg_adduser']);
							break;						
					}	
				}
				?>
			
		<?php 

			}
			else
			{
				 header('Location: ../index.php');			
			}
		?>
					<script type="text/javascript" src="../res/jquery-2.2.3.min.js"></script>
		  			<script type="text/javascript" src="../controleur/inputcontrol/adduser.js"></script>
		  			<script src="../res/bootstrap/js/bootstrap.min.js"></script>
	</body>

</html>