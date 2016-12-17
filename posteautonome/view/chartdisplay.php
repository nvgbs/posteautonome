<?php 
 session_start();

 ?>


<!DOCTYPE html>
<html>
	<head>
		<title>Graff de la Bernarderie</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Appel Feuille de Style Bootstrap -->
		<link href="../res/bootstrap/css/bootstrap.css" rel="stylesheet">
		<!-- Appel à la feuille de style thème Bootstrap -->
		<link href="../res/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
		<link href="../res/stylesheet/cssperso.css" rel="stylesheet">
		

		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

	</head>
 
	<body>

	<?php 


	if (isset($_SESSION['name']))
	{

			/* calcul de la taille de la variable de session qui alimente le graphique*/
			$count = count($_SESSION['tabchart1']);
			/*EN FONCTION DE LA TAILLE CREATION D'UNE VARIABLE DE SESSION POUR DETERMINER LE TYPE DE GRAPHIQUE AFFICHE (1,2,3 jours)*/
				if ($count > 800)
				{
					$_SESSION['tabchartsize'] = 3;
				}
				else if ($count < 800 AND $count >300)
				{
					$_SESSION['tabchartsize'] = 2;
				}
				else
				{
					$_SESSION['tabchartsize'] = 1;
				}
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
				

		
			<div class="row">
				 <div class="form-inline">
					<div class="btn_align">
						<div class="form-group">
		      					<form action="../controleur/dataviewcontrol.php" method="POST">
		      						<input type="hidden" name="switchtypedate" value="<?php echo $_SESSION['datechart'] ?>">		      						
									<input type="submit" name="sundata" class="btn btn-default" value="Energie Solaire" >
								</form>
		   				</div>	

						<div class="form-group">
		      					<form action="../controleur/dataviewcontrol.php" method="POST">
		      						<input type="hidden" name="switchtypedate" value="<?php echo $_SESSION['datechart'] ?>">		      						
									<input type="submit" name="winddata" class="btn btn-default" value="Energie Eolienne" >
								</form>
		   				</div>

						<div class="form-group">
		      					<form action="../controleur/dataviewcontrol.php" method="POST">
		      						<input type="hidden" name="switchtypedate" value="<?php echo $_SESSION['datechart'] ?>">		      						
									<input type="submit" name="busdata" class="btn btn-default" value="Bus Tension" >
								</form>
		   				</div>

		   				<div class="form-group">
		      					<form action="../controleur/dataviewcontrol.php" method="POST">
		      						<input type="hidden" name="switchtypedate" value="<?php echo $_SESSION['datechart'] ?>">		      						
									<input type="submit" name="consodata" class="btn btn-default" value="Consommation" >
								</form>
		   				</div>
		   				<div class="form-group">
		      					<form action="../controleur/dataviewcontrol.php" method="POST">
		      						<input type="hidden" name="switchtypedate" value="<?php echo $_SESSION['datechart'] ?>">		      						
									<input type="submit" name="tempdata" class="btn btn-default" value="Temperature">
								</form>
		   				</div>
					</div>
				</div>
			</div>		
	

			<?php
			    if (isset($_SESSION['msg_dateview']))
			    {
			?>
			    	<div class="row" >
			    	<div class="col-xs-12" style="height: 36em; margin-bottom:5em; margin-top:5em;">
			    	<p class ="msg_viewdata"> Aucune données trouvées pour cette date</p>
			    	</div>
			    	</div>

			<?php	
			    	unset($_SESSION['msg_dateview']);
			    }
			    else
			    {
			?>

			<?php
						    if ($_SESSION['chartname'] == "Energie Eolienne")
						    {			   
					?>	
							 		<div class="row">
							 			<div class="col-xs-12">
							   				<div id="curve_chart" style="width: 85em; height: 36em; margin-top:5em; margin-bottom:5em;" display: block></div>
							   			 </div>
							   		</div>

							   		<div class="row">
							 			<div class="col-xs-12">
							   				<div id="curve_chart2" style="width: 85em; height: 36em; margin-top:5em; margin-bottom:5em;" display: block></div>
							   			 </div>
							   		</div>
						   	
					<?php
							  }
							  else
							  {
					 ?>
								 		<div class="row">
								 			<div class="col-xs-12">
								   				<div id="curve_chart" style="width: 85em; height: 36em; margin-top:5em; margin-bottom:5em;" display: block></div>
								   			 </div>
								   		</div>
							<?php
						}
					?>		

		<?php
			    }
		 ?>

	

			<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
			    <div class="row">			
						<div class="col-md-2">
			  					<form action="../controleur/dataviewcontrol.php" method="POST">
			  						<input type="hidden" name="dateprevday" value="<?php echo $_SESSION['datechart'] ?>">
			  						<input type="hidden" name="nameprevday" value="<?php echo $_SESSION['chartname'] ?>">
									<input type="submit" name="prevday" class="btn btn-default btn-block" value="Jour précédent" >
								</form>
							</div>

							<div class="col-md-1"></div>
						
							<div class="col-md-2">					
			  					<form action="../controleur/dataviewcontrol.php" method="POST">
			  						<input type="hidden" name="dateview1day" value="<?php echo $_SESSION['datechart'] ?>">
			  						<input type="hidden" name="nameview1day" value="<?php echo $_SESSION['chartname'] ?>">
									<input type="submit" name="1day" class="btn btn-default btn-block" value="1 jour" >
								</form>
							</div>

							<div class="col-md-2">
			  					<form action="../controleur/dataviewcontrol.php" method="POST">
			  						<input type="hidden" name="dateview2days" value="<?php echo $_SESSION['datechart'] ?>">
			  						<input type="hidden" name="nameview2days" value="<?php echo $_SESSION['chartname'] ?>">
									<input type="submit" name="2days" class="btn btn-default btn-block" value="2 jours" >
								</form>
							</div>

							<div class="col-md-2">
			  					<form action="../controleur/dataviewcontrol.php" method="POST">
			  						<input type="hidden" name="dateview3days" value="<?php echo $_SESSION['datechart'] ?>">
			  						<input type="hidden" name="nameview3days" value="<?php echo $_SESSION['chartname'] ?>">
									<input type="submit" name="3days" class="btn btn-default btn-block" value="3 jours" >
								</form>
							</div>			

							<div class="col-md-1"></div>

							<div class="col-md-2">
			  					<form action="../controleur/dataviewcontrol.php" method="POST">
			  						<input type="hidden" name="datenextday" value="<?php echo $_SESSION['datechart'] ?>">
			  						<input type="hidden" name="namenextday" value="<?php echo $_SESSION['chartname'] ?>">
									<input type="submit" name="nextday" class="btn btn-default btn-block" value="Jour suivant" >
								</form>
					  		</div>
					  	</div>
			</nav>
	
	</div>



			<?php

					if ($_SESSION['chartname'] == "Energie Eolienne")
					{
						  echo '<script>';
						  echo 'var mydata2 = '.json_encode($_SESSION['tabchart2']);
						  echo '</script>';

						  echo '<script>';
				      	  echo 'var namechart1 = '.json_encode("Puissance Eolienne");
				          echo '</script>';

				          echo '<script>';
				      	  echo 'var namechart2 = '.json_encode("Vitesse du vent");
				          echo '</script>';    



					}




				      echo '<script>';
				      echo 'var namechart1 = '.json_encode($_SESSION['chartname']);
				      echo '</script>';

					  // Retourne la représentation JSON d'une valeur pour la passer a mon script
				      echo '<script>';
				      echo 'var mydata = '.json_encode($_SESSION['tabchart1']);
				      echo '</script>';
		 		
				      echo '<script>';
				      echo 'var datechart = '.json_encode($_SESSION['datedisplay']);
				      echo '</script>';

			     

	 		?>








			     
			    <script>
			    
			   	// je charge tous les éléments dont j'ai besoin de l'API de Googlechart
			    google.charts.load('current', {'packages':['corechart']});
			    // j'appelle la methode de mon graphique
			    google.charts.setOnLoadCallback(drawChart); 

			    function drawChart()
			    {
			    	// je donne en parametre à mon graphique mon objet Json
			        var data = google.visualization.arrayToDataTable(mydata);
			        // je lui défini des options (titre, position du titre etc...)
			        var options = {
			            title: namechart1 + ' du ' + datechart,
			            titlePosition: 'out',			           
			            
			            legend: { position: 'bottom' },
			             hAxis: 
			             	{
				        		title: 'Temps'
				        	},
				         vAxis: 
				         	{
				          		
				        	},
			        };
			        // je pointe sur la balise balise HTML comportant l'id curve_chart
			        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
			        // j'y inclus toutes les options
			        chart.draw(data, options);
			    }

			    
			    google.charts.setOnLoadCallback(drawChart2); 

			    function drawChart2()
			    {
			    	
			        var data = google.visualization.arrayToDataTable(mydata2);
			         
			        var options = {
			            title: namechart2 + ' du ' + datechart,
			            
			            legend: { position: 'bottom' },
			             hAxis: 
			             	{
				        		title: 'Temps'
				        	},
				         vAxis: 
				         	{
				          		
				        	},
			        };
			         
			        var chart = new google.visualization.LineChart(document.getElementById('curve_chart2'));
			         
			        chart.draw(data, options);
			    }			     
			    </script>


		<?php
			}
			else
			{
				header('Location: ../index.php');
			}
			?>

			    <script type="text/javascript" src="../res/jquery-2.2.3.min.js"></script>
			    <script src="../res/bootstrap/js/bootstrap.min.js"></script>

	</body>

</html>