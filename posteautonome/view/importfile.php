<?php 

session_start();

?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Importer un fichier</title>
		<link href="../res/stylesheet/cssperso.css" rel="stylesheet">
		<!-- Appel Feuille de Style Bootstrap -->
		<link href="../res/bootstrap/css/bootstrap.css" rel="stylesheet">
		<!-- Appel à la feuille de style thème Bootstrap -->
		<link href="../res/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
		
		<link rel="stylesheet" type="text/css" href="../res/stylesheet/calendar.css">

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
			<div class="container-fluid">

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
					         <li><a href="adduser.php">Ajouter un utilisateur</a></li>
					         <li class="active"> <a href="importfile.php">Importer un fichier</a></li>
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






				
					  


					<div class="container-fluid" style="margin-top: 10em;">

					

					 	<div class = "row">
					 	<div class="col-sm-2"></div>
				    		<div class="col-sm-4">
				    		<div class="jumbotron" style="background-color: rgb(185,185,185);">
				    		   <p class="titleimport" style="font-family: arial; font-weight: bold;"> Importer un fichier </p>				      				
								 <form method="POST" action="../controleur/importcontrol.php"  enctype="multipart/form-data">
								     
								     <input type="file" class="file" name="importfile" id="importfile" style="margin-top:3em;" onchange="checkfile(); return false;"/><br />
								     <label style="margin-top:3em;" for="icone">Choisir une date:</label>
								     <div style="margin-bottom:3em;">
								     <input type="text" name="date" id="date" class="calendrier" size="8" autocomplete="off" placeholder="Calendrier" onblur="checkdate(); return false;"/>	     
							         <input type="submit" name= "button_import" id ="button_import" class="btn btn-success btn btn-success" value="Importer" disabled= "true" style="margin-left:10em;"> 			
							         </div>				          
							     </form>			   			
							 </div>
							 </div>

							

				   			 <div class="col-sm-4">
				   			 <div class="jumbotron" style="background-color: rgb(225,225,225);">
				   				<p class = "titleimport" style="font-family: arial; font-weight: bold;"> Supprimer un fichier </p>
								 <form method="POST" action="../controleur/importcontrol.php"  enctype="multipart/form-data">
								     <label for="icone" style="margin-top:8em;">Choisir une date:</label>
								     <div style="margin-bottom:3em;">
								     <input type="text" name="datedelete" id="datedelete" class="calendrier" size="8" autocomplete="off" placeholder="Calendrier" onblur="checkdatedelete(); return false;"/>
							         <input type="submit" name= "button_delete" id ="button_delete" class="btn btn-danger btn btn-danger" value="Supprimer" disabled="true" style="margin-left:10em;">
							         </div>
							     </form>
							</div>
							</div>
							 <div class="col-sm-2"></div>
						</div>
					</div>

				<?php	
				if (isset($_SESSION['msg_importcontrol']))
				{
					switch ($_SESSION['msg_importcontrol']) 
					{
						case 1:
				?>
							<div class="alert alert-success" role="alert">
								<p class = "msg_addcsv"> Fichier Excel ajouté avec succès ! </p>
							</div>
				<?php
							unset($_SESSION['msg_importcontrol']);
							break;
						
						case 2:
				?>
							<div class="alert alert-warning" role="alert">
								<p class = "msg_addcsv"> La date selectionnée contient déja des données ! </p>
							</div>
				<?php							
							unset($_SESSION['msg_importcontrol']);
							break;
						case 3:
				?>
							<div class="alert alert-warning" role="alert">
								<p class = "msg_addcsv"> La date selectionnée ne contient aucune données à effacer ! </p>
							</div>
				<?php							
							unset($_SESSION['msg_importcontrol']);
							break;
						case 4:
				?>
							<div class="alert alert-danger" role="alert">
								<p class = "msg_addcsv"> Les données ont été supprimé avec succès ! </p>
							</div>
				<?php							
							unset($_SESSION['msg_importcontrol']);
							break;
							case 5:
				?>
							<div class="alert alert-danger" role="alert">
								<p class = "msg_addcsv"> La date est incorrecte ! </p>
							</div>
				<?php							
							unset($_SESSION['msg_importcontrol']);
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
        <script type="text/javascript" src="../res/calendar.js"></script>
        <script type="text/javascript" src="../controleur/inputcontrol/importfile.js"></script>
        <script src="../res/bootstrap/js/bootstrap.min.js"></script>
	</body>


</html>