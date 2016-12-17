<?php 

session_start();

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title> Visualiser des données </title>
		<link href="../res/stylesheet/cssperso.css" rel="stylesheet">
		<!-- Appel Feuille de Style Bootstrap -->
		<link href="../res/bootstrap/css/bootstrap.css" rel="stylesheet">
		<!-- Appel à la feuille de style thème Bootstrap -->
		<link href="../res/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
		
		<link rel="stylesheet" type="text/css" href="../res/stylesheet/calendar.css">

		
	</head>


	<body>


	<?php
			if (isset($_SESSION['name']))
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
						    <?php
						    if ($_SESSION['role'] == 1)
						    {
						    ?>
						    	<ul class="nav navbar-nav">
							         <li><a href="adduser.php">Ajouter un utilisateur</a></li>
							         <li><a href="importfile.php">Importer un fichier</a></li>
							         <li class="active"><a href="dataview.php">Visualiser des données</a></li>						        
						      	</ul>
						    <?php
						    }
						    else
						    {
						    ?>
						    	<ul class="nav navbar-nav">
						    		<li class="active"><a href="dataview.php">Visualiser des données</a></li>
						    	</ul>
						    <?php
							}
							?>
						      
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
					<div class = row>
						<div class="col-sm-2"></div>
						<div class="col-sm-4">
				    		<div class="jumbotron" style="background-color:rgb(77,79,83)";>
				    		 <p class = "titleimport" style="font-family: arial; font-weight: bold; color: #FFFFFF"> Visualiser une date </p>
							  <form method="POST" action="../controleur/dataviewcontrol.php"  enctype="multipart/form-data">
							 	<label style="margin-top:3em; font-weight: bold; color: #FFFFFF">Choisir une mesure:</label> <br/>
							     <select name = "datatype" id = "datatype"> 
									 <option value="sun"> Energie Solaire </option>
									 <option value="wind"> Energie Eolienne </option>
									 <option value="bus"> Tension Bus </option>
									 <option value="conso"> Consommation </option>
									 <option value="temp"> Temperature </option>								 									 
								 </select><br/>

							      <label for="icone" style="margin-top:3em; font-weight: bold; font-family: arial; color: #FFFFFF ">Choisir une date:</label><br/>
							      <div style="margin-bottom:3em;">
								     <input type="text" name="dateview" id="dateview" class="calendrier" size="8" autocomplete="off" placeholder="Calendrier" style="margin-right:14em;"/>							     
							         <input type="submit" name= "button_view" id ="button_view" class="btn btn-success btn btn-success" value="Visualiser">
						         </div> 
							  </form>
						   </div>
						</div>

						<div class="col-sm-4">
							<div class="jumbotron" style="background-color:rgb(160,160,160)";>
							 <p class = "titleimport" style="font-family: arial; font-weight: bold; color: #FFFFFF"> Comparer des dates </p>														   				
							  <form method="POST" action="../controleur/comparechartcontrol.php"  enctype="multipart/form-data">
							 	<label style="margin-top:3em; color: #FFFFFF; font-family: arial;">Choisir une mesure:</label> <br/>
							     <select name = "datatype" id = "datatype"> 
									 <option value="sun"> Energie Solaire </option>
									 <option value="wind"> Energie Eolienne </option>
									 <option value="bus"> Tension Bus </option>
									 <option value="conso"> Consommation </option>
									 <option value="temp"> Temperature </option>								 									 
								 </select></br>
							     <label for="icone" style="margin-top:3em; font-family: arial; color: #FFFFFF">Choisir des dates:</label><br/>
							     <div style="margin-bottom:3em;">
								     <input type="text" name="dateview1" id="dateview" class="calendrier" size="8" autocomplete="off" placeholder="Calendrier" style="margin-right:3em;"/>							     
								     <input type="text" name="dateview2" id="dateview" class="calendrier" size="8" autocomplete="off" placeholder="Calendrier" style="margin-right:4em;"/>							     
							         <input type="submit" name= "button_compare" id ="button_view" class="btn btn-success btn btn-success" value="Comparer">
							     </div> 
						      </form>
							</div>						
						</div>
					</div>
				</div>





	<?php	
				if (isset($_SESSION['msg_dataview']))
				{
					switch ($_SESSION['msg_dataview']) 
					{					
						case 1:
	?>
							<div class="alert alert-warning" role="alert" >
								<p class = "msg_viewdata"> 	Aucunes données trouvées pour cette date !</p>
							</div>
	<?php							
							unset($_SESSION['msg_dataview']);
							break;
						case 2:
	?>							
							<div class="alert alert-danger" role="alert">
								<p class = "msg_viewdata"> 	La date renseignée est incorrecte !</p>
							</div>
	<?php							
							unset($_SESSION['msg_dataview']);
							break;						
					}	
				}
	?>

	<?php 

			}
			else
			{
				header('location: ../index.php');
			}
	?>

			<script type="text/javascript" src="../res/jquery-2.2.3.min.js"></script>
	        <script type="text/javascript" src="../res/calendar.js"></script>
	        <script src="../res/bootstrap/js/bootstrap.min.js"></script>

	</body>
</html>