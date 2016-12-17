<?php 
 session_start();

 ?>


<!DOCTYPE html>
<html>
	<head>

		<title>Graff de la Bernarderie</title>
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../res/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../res/stylesheet/cssperso.css">		
		<link href="../res/bootstrap/css/bootstrap-theme.css" rel="stylesheet">
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	

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








		<div class="container-fluid">
			<div class="row">
				<div class="form-inline">
					<div class="btn_align">
						<div class="form-group">
		      					<form action="../controleur/comparechartcontrol.php" method="POST">
		      						<input type="hidden" name="switchtypedate1" value="<?php echo $_SESSION['datechart1'] ?>">
		      						<input type="hidden" name="switchtypedate2" value="<?php echo $_SESSION['datechart2'] ?>">		      						
									<input type="submit" name="changedata" class="btn btn-default" value="Energie Solaire" >
								</form>
		   				</div>	

						<div class="form-group">
		      					<form action="../controleur/comparechartcontrol.php" method="POST">
		      						<input type="hidden" name="switchtypedate1" value="<?php echo $_SESSION['datechart1'] ?>">
		      						<input type="hidden" name="switchtypedate2" value="<?php echo $_SESSION['datechart2'] ?>">		      						
									<input type="submit" name="changedata" class="btn btn-default" value="Energie Eolienne" >
								</form>
		   				</div>

						<div class="form-group">
		      					<form action="../controleur/comparechartcontrol.php" method="POST">
		      						<input type="hidden" name="switchtypedate1" value="<?php echo $_SESSION['datechart1'] ?>">
		      						<input type="hidden" name="switchtypedate2" value="<?php echo $_SESSION['datechart2'] ?>">		      						
									<input type="submit" name="changedata" class="btn btn-default" value="Tension Bus" >
								</form>
		   				</div>

		   				<div class="form-group">
		      					<form action="../controleur/comparechartcontrol.php" method="POST">
		      						<input type="hidden" name="switchtypedate1" value="<?php echo $_SESSION['datechart1'] ?>">
		      						<input type="hidden" name="switchtypedate2" value="<?php echo $_SESSION['datechart2'] ?>">	      						
									<input type="submit" name="changedata" class="btn btn-default" value="Consommation" >
								</form>
		   				</div>
		   				<div class="form-group">
		      					<form action="../controleur/comparechartcontrol.php" method="POST">
		      						<input type="hidden" name="switchtypedate1" value="<?php echo $_SESSION['datechart1'] ?>">
		      						<input type="hidden" name="switchtypedate2" value="<?php echo $_SESSION['datechart2'] ?>">		      						
									<input type="submit" name="changedata" class="btn btn-default" value="Température" >
								</form>
		   				</div>
					</div>
				</div>
			</div>
		</div>

				<?php			    
			      echo '<script>';
			      echo 'var mydata = '.json_encode($_SESSION['tabchart1']);			   
			      echo '</script>';			        
			    

			    	    
			      echo '<script>';
			      echo 'var mydata2 = '.json_encode($_SESSION['tabchart2']);			   
			      echo '</script>';			        
			   

			    		    
			      echo '<script>';
			      echo 'var mydata3 = '.json_encode($_SESSION['tabchart3']);			   
			      echo '</script>';			        
			    ?>




			<?php 
			    if ($_SESSION['chartname'] == 'Température')
			    {
			 ?>
		 			<div class="container-fluid">

		 				<div class="row">
			    			<div id="curve_chart" style="width: 85em; height: 36em;" display: block></div>
			    		</div>

			    		<div class="row">
			    			<div id="curve_chart2" style="width: 85em; height: 36em;" display: block></div>
			    		</div>

			    		<div class="row">
			    			<div id="curve_chart3" style="width: 85em; height: 36em;" display: block></div>
			    		</div>

			    	</div>
		 	<?php
				}
			    else if ($_SESSION['chartname'] == 'Tension Bus')
			    {
			?>

			    <div class="container-fluid">
			    	<div class="row">			    				    	
			    		<div id="curve_chart" style="width: 85em; height: 36em;" display: block></div>			   			 	
			    	</div>
			    </div>			
		 	<?php
				}
				else
				{
		 	?>
		 		<div class="container-fluid">
		 			<div class="row">
						<div id="curve_chart" style="width: 85em; height: 36em;" display: block></div>
					</div>

					<div class="row">
			    		<div id="curve_chart2" style="width: 85em; height: 36em;" display: block></div>
			    	</div>
			    </div>
	 	 	<?php
				}
			 ?>

			    <script>			   
			   
			    google.charts.load('current', {'packages':['corechart']});
			    google.charts.setOnLoadCallback(drawChart1); 
			    google.charts.setOnLoadCallback(drawChart2);
			    google.charts.setOnLoadCallback(drawChart3);

			      

			    function drawChart1()
			    {
			        var data = google.visualization.arrayToDataTable(mydata);
			         
			        var options = {
			            title: '',
			            
			            legend: { position: 'bottom' }
			        };
			         
			        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
			         
			        chart.draw(data, options);
			    }

			   

			    function drawChart2()
			    {
			        var data = google.visualization.arrayToDataTable(mydata2);
			         
			        var options = {
			            title: '',
			           
			            legend: { position: 'bottom' }
			        };
			         
			        var chart = new google.visualization.LineChart(document.getElementById('curve_chart2'));
			         
			        chart.draw(data, options);
			    }

			    function drawChart3()
			    {
			        var data = google.visualization.arrayToDataTable(mydata3);
			         
			        var options = {
			            title: '',
			            
			            legend: { position: 'bottom' }
			        };
			         
			        var chart = new google.visualization.LineChart(document.getElementById('curve_chart3'));
			         
			        chart.draw(data, options);
			    }
			     
			    </script>

		<?php
		}
		else
		{	    
			header('location: ../index.php');
		}
		?>







			    <script type="text/javascript" src="../res/jquery-2.2.3.min.js"></script>
			    <script src="../res/bootstrap/js/bootstrap.min.js"></script>

	</body>

</html>