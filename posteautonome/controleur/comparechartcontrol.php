<?php 

session_start();

	require ('../modele/sqlrequest.php');


	if (isset($_POST['dateview1']) AND !empty($_POST['dateview1']) AND isset($_POST['dateview2']) AND !empty($_POST['dateview2']) AND preg_match('#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#',$_POST['dateview1']))
	{
		// LA VAR DE SESSION DEVIENT LA DATE RENTRE DANS LE CALENDAR
		$_SESSION['datechart1'] = $_POST['dateview1'];
		$_SESSION['datechart2'] = $_POST['dateview2'];
		// LA VAR DE SESSION POUR L'AFFICHAGE DEVIENT LA DATE RENTRE DANS LE CALENDAR										
		$_SESSION['datedisplay1'] = $_SESSION['datechart1'];	
		$_SESSION['datedisplay2'] = $_SESSION['datechart2'];								
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN 
		list($day, $month, $year) = preg_split('[/]', $_POST['dateview1']);					
		$dateview1 = $year . '-' . $month . '-' . $day;

		list($day2, $month2, $year2) = preg_split('[/]', $_POST['dateview2']);					
		$dateview2 = $year2 . '-' . $month2 . '-' . $day2;			
		// CREATION DE L'OBJET DE NOTRE CLASSE SQLREQUEST
		$bdd = new sqlrequest();															
		// CONTROLE DUNE DATE REMPLIE PAR DES DONNEES
		$validviewdate1 = $bdd->checkdateview($dateview1);
		$validviewdate2 = $bdd->checkdateview($dateview2);



		if ($validviewdate1 != 0 AND $validviewdate2 != 0)
		{
			switch ($_POST['datatype']) 
			{
				case $_POST['datatype'] == "sun":
					$_SESSION['namey'] == "test";
					$_SESSION['chartname'] = "Energie Solaire";			
					$_SESSION['tabchart1'] = $bdd->comparesunview1($dateview1, $dateview2);
					$_SESSION['tabchart2'] = $bdd->comparesunview2($dateview1, $dateview2);									
					header('Location: ../view/comparechart.php');
					break;				
				
				case $_POST['datatype'] == "wind":
					$_SESSION['namey'] == "test";
					$_SESSION['chartname'] = "Energie Eolienne";
					$_SESSION['tabchart1'] = $bdd->comparewindview1($dateview1, $dateview2);
					$_SESSION['tabchart2'] = $bdd->comparewindview2($dateview1, $dateview2);								
					header('Location: ../view/comparechart.php');
					break;

				case $_POST['datatype'] == "bus":
					$_SESSION['namey'] == "test";
					$_SESSION['chartname'] = "Tension Bus";				
					$_SESSION['tabchart1'] = $bdd->comparebusview($dateview1, $dateview2);			
					header('Location: ../view/comparechart.php');
					break;

				case $_POST['datatype'] == "conso":
					$_SESSION['namey'] == "test";
					$_SESSION['tabchart1'] = $bdd->compareconsoview1($dateview1, $dateview2);
					$_SESSION['tabchart2'] = $bdd->compareconsoview2($dateview1, $dateview2);
					$_SESSION['chartname'] = "Consommation";									
					header('Location: ../view/comparechart.php');
					break;

				case $_POST['datatype'] == "temp":
					$_SESSION['namey'] == "test";
					$_SESSION['chartname'] = "Température";	
					$_SESSION['tabchart1'] = $bdd->comparetempview1($dateview1, $dateview2);
					$_SESSION['tabchart2'] = $bdd->comparetempview2($dateview1, $dateview2);			
					$_SESSION['tabchart3'] = $bdd->comparetempview3($dateview1, $dateview2);
					header('Location: ../view/comparechart.php');	
					break;
			}

		}
		else
		{
			header('Location: ../view/dataview.php');
			$_SESSION['msg_dataview'] = 1;
		}		

	}
	else if (isset($_POST['switchtypedate1']) AND isset($_POST['switchtypedate2']))
	{
		// LA VAR DE SESSION DEVIENT LA DATE RENTRE DANS LE CALENDAR
		$_SESSION['datechart1'] = $_POST['switchtypedate1'];
		$_SESSION['datechart2'] = $_POST['switchtypedate2'];
		// LA VAR DE SESSION POUR L'AFFICHAGE DEVIENT LA DATE RENTRE DANS LE CALENDAR										
		$_SESSION['datedisplay1'] = $_SESSION['datechart1'];	
		$_SESSION['datedisplay2'] = $_SESSION['datechart2'];								
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN 
		list($day, $month, $year) = preg_split('[/]', $_POST['switchtypedate1']);					
		$dateview1 = $year . '-' . $month . '-' . $day;

		list($day2, $month2, $year2) = preg_split('[/]', $_POST['switchtypedate2']);					
		$dateview2 = $year2 . '-' . $month2 . '-' . $day2;			
		// CREATION DE L'OBJET DE NOTRE CLASSE SQLREQUEST
		$bdd = new sqlrequest();															
		// CONTROLE DUNE DATE REMPLIE PAR DES DONNEES
		$validviewdate1 = $bdd->checkdateview($dateview1);
		$validviewdate2 = $bdd->checkdateview($dateview2);



		if ($validviewdate1 != 0 AND $validviewdate2 != 0)
		{
			switch ($_POST['changedata']) 
			{
				case $_POST['changedata'] == "Energie Solaire":
					$_SESSION['chartname'] = "Energie Solaire";			
					$_SESSION['tabchart1'] = $bdd->comparesunview1($dateview1, $dateview2);
					$_SESSION['tabchart2'] = $bdd->comparesunview2($dateview1, $dateview2);									
					header('Location: ../view/comparechart.php');
					break;				
				
				case $_POST['changedata'] == "Energie Eolienne":
					$_SESSION['chartname'] = "Energie Eolienne";
					$_SESSION['tabchart1'] = $bdd->comparewindview1($dateview1, $dateview2);
					$_SESSION['tabchart2'] = $bdd->comparewindview2($dateview1, $dateview2);								
					header('Location: ../view/comparechart.php');
					break;

				case $_POST['changedata'] == "Tension Bus":
					$_SESSION['chartname'] = "Tension Bus";				
					$_SESSION['tabchart1'] = $bdd->comparebusview($dateview1, $dateview2);			
					header('Location: ../view/comparechart.php');
					break;

				case $_POST['changedata'] == "Consommation":
					$_SESSION['tabchart1'] = $bdd->compareconsoview1($dateview1, $dateview2);
					$_SESSION['tabchart2'] = $bdd->compareconsoview2($dateview1, $dateview2);
					$_SESSION['chartname'] = "Consommation";									
					header('Location: ../view/comparechart.php');
					break;

				case $_POST['changedata'] == "Température":
					$_SESSION['chartname'] = "Température";	
					$_SESSION['tabchart1'] = $bdd->comparetempview1($dateview1, $dateview2);
					$_SESSION['tabchart2'] = $bdd->comparetempview2($dateview1, $dateview2);			
					$_SESSION['tabchart3'] = $bdd->comparetempview3($dateview1, $dateview2);
					header('Location: ../view/comparechart.php');	
					break;
			}

		}

	}
	else
	{
		header('Location: ../view/dataview.php');
		$_SESSION['msg_dataview'] = 2;
	}









	










	
	
	