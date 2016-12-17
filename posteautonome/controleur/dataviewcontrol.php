<?php 

session_start();

	require ('../modele/sqlrequest.php');





	if (isset($_POST['dateview']) AND !empty($_POST['dateview']) AND preg_match('#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#',$_POST['dateview']))
	{
		// la date choisi est conservé dans une var de session
		$_SESSION['datechart'] = $_POST['dateview'];
		// cette variable de session est conservé pour l'affichage										
		$_SESSION['datedisplay'] = $_SESSION['datechart'];									
		// split de la date pour la mettre au format américain
		list($day, $month, $year) = preg_split('[/]', $_POST['dateview']);
		$dateview = $year . '-' . $month . '-' . $day;			
		// creation de l'objet sqlrequest
		$bdd = new sqlrequest();															
		// controle d'une date valide (remplie)
		$validviewdate = $bdd->checkdateview($dateview);									
		// en fonction de la réponse de checkdateview
		switch ($validviewdate) 
		{
			case 0:																											
				header('Location: ../view/dataview.php');
				$_SESSION['msg_dataview'] = 1;
				break;
			
			case $validviewdate > 0: 														

				if ($_POST['datatype'] == "sun")
				{	
					$_SESSION['nameY'] = "(W) (W/m²)";
					$_SESSION['chartname'] = "Energie Solaire";			
					$_SESSION['tabchart1'] = $bdd->sunview($dateview);					
					header('Location: ../view/chartdisplay.php');
				}
				else if (($_POST['datatype'] == "wind"))
				{
					$_SESSION['nameY'] = "(W) (m/s)";	
					$_SESSION['chartname'] = "Energie Eolienne";
					$_SESSION['tabchart1'] = $bdd->windview1($dateview);
					$_SESSION['tabchart2'] = $bdd->windview2($dateview);					
					header('Location: ../view/chartdisplay.php');
				}
				else if (($_POST['datatype'] == "bus"))
				{						
					$_SESSION['chartname'] = "Tension Bus";				
					$_SESSION['tabchart1'] = $bdd->busview($dateview);					
					header('Location: ../view/chartdisplay.php');					
				}
				else if (($_POST['datatype'] == "conso"))
				{
					$_SESSION['nameY'] = "(W)";
					$_SESSION['chartname'] = "Consommation";
					$_SESSION['tabchart1'] = $bdd->consoview($dateview);					
					header('Location: ../view/chartdisplay.php');
				}
				else if (($_POST['datatype'] == "temp"))
				{
					$_SESSION['nameY'] = "(°C)";	
					$_SESSION['chartname'] = "Température";				
					$_SESSION['tabchart1'] = $bdd->tempview($dateview);					
					header('Location: ../view/chartdisplay.php');					
				}				
				break;
		}

	}	




	//////////////////////////////////////////////////// CONTROLE POUR AFFICHER DES GRAPHIQUES DE 2 JOURS /////////////////////////////////////////////////////////////////////


	else if (isset($_POST['dateview2days'])  AND isset($_POST['nameview2days']))
	{
		$bdd = new sqlrequest();
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN 
		list($day, $month, $year) = preg_split('[/]', $_POST['dateview2days']);						
		$date0 = $year . '-' . $month . '-' . $day;	
		// CREATION DE LA DATE EN OBJET POUR UTILISER DATESUB OU DATEADD
		$dateorigin1 = date_create($date0);																
		// APPLICATION DES LA METHODE SUB DATE, SOUSTRACTION DE 2 JOURs POUR L'AFFICHAGE
		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));
		// RECUPERATION DE LA DATE EN STRING POUR L'AFFICHAGE					
		$date1 = date_format($dateorigin1, 'd/m/Y');													
		// CREATION DE LA SESSION DATEDISPLAY POUR LAFFICHAGE DANS CHARTDISPLAY
		$_SESSION['datedisplay'] = $date1 . " au " . $_POST['dateview2days'];						



		switch ($_POST['nameview2days'])
		{
			case $_POST['nameview2days'] == "Energie Solaire":
			$_SESSION['tabchart1'] = $bdd->sunview2days($_POST['dateview2days']);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview2days'] == "Energie Eolienne":
			$_SESSION['tabchart1'] = $bdd->windview2days1($_POST['dateview2days']);
			$_SESSION['tabchart2'] = $bdd->windview2days2($_POST['dateview2days']);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview2days'] == "Tension Bus":
			$_SESSION['tabchart1'] = $bdd->busview2days($_POST['dateview2days']);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview2days'] == "Consommation":
			$_SESSION['tabchart1'] = $bdd->consoview2days($_POST['dateview2days']);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview2days'] == "Température":
			$_SESSION['tabchart1'] = $bdd->tempview2days($_POST['dateview2days']);
			header('Location: ../view/chartdisplay.php');
			break;
		}
		
	}

	////////////////////////////////////////////////////CONTROLE POUR AFFICHER DES GRAPHIQUES DE 3 JOURS /////////////////////////////////////////////////////////////////////

	else if (isset($_POST['dateview3days'])AND isset($_POST['nameview3days']))
	{
		$bdd = new sqlrequest();
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN
		list($day, $month, $year) = preg_split('[/]', $_POST['dateview3days']); 						  	 
		$date0 = $year . '-' . $month . '-' . $day;			 	 						 
		// CREATION DE LA DATE EN OBJET POUR UTILISER DATESUB OU DATEADD
		$dateorigin1 = date_create($date0);																
		// APPLICATION DES LA METHODE SUB DATE, SOUSTRACTION DE 2 JOURs POUR L'AFFICHAGE
		date_sub($dateorigin1, date_interval_create_from_date_string(' 2 days '));	
		// RECUPERATION DE LA DATE EN STRING POUR L'AFFICHAGE					
		$date1 = date_format($dateorigin1, 'd/m/Y');													
		// CREATION DE LA SESSION DATEDISPLAY POUR LAFFICHAGE DANS CHARTDISPLAY
		$_SESSION['datedisplay'] = $date1 . " au " . $_POST['dateview3days'];							


		switch ($_POST['nameview3days'])
		{
			case $_POST['nameview3days'] == "Energie Solaire":
			$_SESSION['tabchart1'] = $bdd->sunview3days($_POST['dateview3days']);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview3days'] == "Energie Eolienne":
			$_SESSION['tabchart1'] = $bdd->windview3days1($_POST['dateview3days']);
			$_SESSION['tabchart2'] = $bdd->windview3days2($_POST['dateview3days']);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview3days'] == "Tension Bus":
			$_SESSION['tabchart1'] = $bdd->busview3days($_POST['dateview3days']);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview3days'] == "Consommation":
			$_SESSION['tabchart1'] = $bdd->consoview3days($_POST['dateview3days']);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview3days'] == "Température":
			$_SESSION['tabchart1'] = $bdd->tempview3days($_POST['dateview3days']);
			header('Location: ../view/chartdisplay.php');
			break;
		}
	}

	//////////////////////////////////////////////////// CONTROLE POUR AFFICHER DES GRAPHIQUES DE 1 JOUR DIRECTEMENT VIA CHARTDISPLAY ////////////////////////////////////////////////////////

	else if (isset($_POST['dateview1day'])AND isset($_POST['nameview1day']))
	{

		$bdd = new sqlrequest();
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN 
		list($day, $month, $year) = preg_split('[/]', $_POST['dateview1day']);									
		$dateview1day = $year . '-' . $month . '-' . $day;	
		// CREATION DE LA SESSION DATEDISPLAY POUR LAFFICHAGE DANS CHARTDISPLAY
		$_SESSION['datedisplay'] = $_POST['dateview1day'];														


		switch ($_POST['nameview1day'])
		{
			case $_POST['nameview1day'] == "Energie Solaire":
			$_SESSION['tabchart1'] = $bdd->sunview($dateview1day);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview1day'] == "Energie Eolienne":
			$_SESSION['tabchart1'] = $bdd->windview1($dateview1day);
			$_SESSION['tabchart2'] = $bdd->windview2($dateview1day);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview1day'] == "Tension Bus":
			$_SESSION['tabchart1'] = $bdd->busview($dateview1day);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview1day'] == "Consommation":
			$_SESSION['tabchart1'] = $bdd->consoview($dateview1day);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameview1day'] == "Température":
			$_SESSION['tabchart1'] = $bdd->tempview($dateview1day);
			header('Location: ../view/chartdisplay.php');
			break;
		}
	}





	//////////////////////////////////////////////////// CONTROLE POUR AFFICHER DES GRAPHIQUES DU JOUR PRECEDENT /////////////////////////////////////////////////////////////////////


	// CONTROLE ET LANCEMENT DES METHODES SI ON EST SUR UN JOUR DE VISUALISATION
	else if (isset($_POST['dateprevday']) AND isset($_POST['nameprevday']) AND $_SESSION['tabchartsize'] == 1) 		
	{
		$bdd = new sqlrequest();
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN 
		list($day, $month, $year) = preg_split('[/]', $_POST['dateprevday']);			 
		$date0 = $year . '-' . $month . '-' . $day;
		
		// creation de la date en objet pour utiliser data_sub
		$date1 = date_create($date0);																					
		// soustraction de 1 jour
		date_sub($date1, date_interval_create_from_date_string(' 1 days '));
		// récupération de la date dans une variable
		$datefunction = date_format($date1, 'Y-m-d');

		// RECUPERATION DE LA DATE EN STRING POUR LA SESSION TABCHART
		$_SESSION['datechart'] = date_format($date1, 'd/m/Y');
		// RECUPERATION DE LA DATE EN STRING POUR L'AFFICHAGE													
		$_SESSION['datedisplay'] = date_format($date1, 'd/m/Y');												
		// FUNCTION POUR VOIR SI LA DATE DANS LA DB CONTIENT DES DONNEES
		$validatedateprevday = $bdd->checkdateview($datefunction);														


		switch ($validatedateprevday) 																				
		{
			case 0:										
					header('Location: ../view/chartdisplay.php');
					$_SESSION['msg_dateview'] = 1;				
				break;
			
			case $validatedateprevday > 0: 																			
				if ($_POST['nameprevday'] == "Energie Solaire")
				{	
					$_SESSION['chartname'] = "Energie Solaire";			
					$_SESSION['tabchart1'] = $bdd->sunview($datefunction);					
					header('Location: ../view/chartdisplay.php');
				}
				else if ($_POST['nameprevday'] == "Energie Eolienne")
				{
					$_SESSION['chartname'] = "Energie Eolienne";
					$_SESSION['tabchart1'] = $bdd->windview1($datefunction);
					$_SESSION['tabchart2'] = $bdd->windview2($datefunction);					
					header('Location: ../view/chartdisplay.php');
				}
				else if ($_POST['nameprevday'] == "Tension Bus")
				{	
					$_SESSION['chartname'] = "Tension Bus";				
					$_SESSION['tabchart1'] = $bdd->busview($datefunction);					
					header('Location: ../view/chartdisplay.php');
					var_dump($_SESSION['tabchart1']);
				}
				else if ($_POST['nameprevday'] == "Consommation")
				{
					$_SESSION['chartname'] = "Consommation";
					$_SESSION['tabchart1'] = $bdd->consoview($datefunction);					
					header('Location: ../view/chartdisplay.php');
				}
				else if ($_POST['nameprevday'] == "Température")
				{	
					$_SESSION['chartname'] = "Température";				
					$_SESSION['tabchart1'] = $bdd->tempview($datefunction);					
					header('Location: ../view/chartdisplay.php');					
				}				
				break;
		}
	}
	// CONTROLE ET LANCEMENT DES METHODES SI ON EST SUR 2 JOURS DE VISUALISATION
	else if (isset($_POST['dateprevday']) AND isset($_POST['nameprevday']) AND $_SESSION['tabchartsize'] == 2) 		
	{
		$bdd = new sqlrequest();
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN	
		list($day, $month, $year) = preg_split('[/]', $_POST['dateprevday']); 	 									 
		$date0 = $year . '-' . $month . '-' . $day;			 	 						 
		// CREATION DE LA DATE EN OBJET POUR UTILISER DATESUB OU DATEADD
		$date1 = date_create($date0);
		// CREATION DE LA DATE EN OBJET POUR UTILISER DATESUB OU DATEADD																		
		$date2 = date_create($date0);																							
		// APPLICATION DES LA METHODE SUB DATE, SOUSTRACTION DE 1 JOUR
		date_sub($date1, date_interval_create_from_date_string(' 1 days '));
		// APPLICATION DES LA METHODE SUB DATE, SOUSTRACTION DE 2 JOURS										
		date_sub($date2, date_interval_create_from_date_string(' 2 days '));									
		// RECUPERATION DE LA DATE EN STRING POUR LA PASSER EN PARAMETRE DES FUNCTIONS
		$datefunction = date_format($date1, 'd/m/Y');
		// RECUPERATION DE LA DATE EN STRING POUR LA SESSION TABCHART																
		$_SESSION['datechart'] = date_format($date2, 'd/m/Y');													
		// RECUPERATION DE LA DATE EN STRING POUR L'AFFICHAGE
		$dateaffich1 = date_format($date1, 'd/m/Y');	
		// RECUPERATION DE LA DATE EN STRING POUR L'AFFICHAGE															
		$dateaffich2 = date_format($date2, 'd/m/Y');
		// CREATION DE LA SESSION DATEDISPLAY POUR LAFFICHAGE DANS CHARTDISPLAY															
		$_SESSION['datedisplay'] = $dateaffich2 . " au " . $dateaffich1;												


		switch ($_POST['nameprevday'])
		{
			case $_POST['nameprevday'] == "Energie Solaire":
			$_SESSION['tabchart1'] = $bdd->sunview2days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameprevday'] == "Energie Eolienne":
			$_SESSION['tabchart1'] = $bdd->windview2days1($datefunction);
			$_SESSION['tabchart2'] = $bdd->windview2days2($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameprevday'] == "Tension Bus":
			$_SESSION['tabchart1'] = $bdd->busview2days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameprevday'] == "Consommation":
			$_SESSION['tabchart1'] = $bdd->consoview2days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameprevday'] == "Température":
			$_SESSION['tabchart1'] = $bdd->tempview2days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;
		}
	}
	// CONTROLE ET LANCEMENT DES METHODES SI ON EST SUR UN JOUR DE VISUALISATION
	else if (isset($_POST['dateprevday']) AND isset($_POST['nameprevday']) AND $_SESSION['tabchartsize'] == 3) 		
	{
		$bdd = new sqlrequest();
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN	
		list($day, $month, $year) = preg_split('[/]', $_POST['dateprevday']); 										 	 
		$date0 = $year . '-' . $month . '-' . $day;			 	 						 
		// CREATION DE LA DATE EN OBJET POUR UTILISER DATESUB OU DATEADD
		$date1 = date_create($date0);
		// CREATION DE LA DATE EN OBJET POUR UTILISER DATESUB OU DATEADD																		
		$date2 = date_create($date0);																						
		// APPLICATION DES LA METHODE SUB DATE, SOUSTRACTION DE 1 JOUR
		date_sub($date1, date_interval_create_from_date_string(' 1 days '));
		// APPLICATION DES LA METHODE SUB DATE, SOUSTRACTION DE 3 JOURS									
		date_sub($date2, date_interval_create_from_date_string(' 3 days '));										
		// RECUPERATION DE LA DATE EN STRING POUR LA PASSER EN PARAMETRE DES FUNCTIONS
		$datefunction = date_format($date1, 'd/m/Y');	
		// RECUPERATION DE LA DATE EN STRING POUR LA SESSION TABCHART															
		$_SESSION['datechart'] = date_format($date1, 'd/m/Y');													
		// RECUPERATION DE LA DATE EN STRING POUR L'AFFICHAGE
		$dateaffich1 = date_format($date1, 'd/m/Y');
		// RECUPERATION DE LA DATE EN STRING POUR L'AFFICHAGE																
		$dateaffich2 = date_format($date2, 'd/m/Y');
		// CREATION DE LA SESSION DATEDISPLAY POUR LAFFICHAGE DANS CHARTDISPLAY															
		$_SESSION['datedisplay'] = $dateaffich2 . " au " . $dateaffich1;												


		switch ($_POST['nameprevday'])
		{
			case $_POST['nameprevday'] == "Energie Solaire":
			$_SESSION['tabchart1'] = $bdd->sunview3days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameprevday'] == "Energie Eolienne":
			$_SESSION['tabchart1'] = $bdd->windview3days1($datefunction);
			$_SESSION['tabchart2'] = $bdd->windview3days2($datefunction);

			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameprevday'] == "Tension Bus":
			$_SESSION['tabchart1'] = $bdd->busview3days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameprevday'] == "Consommation":
			$_SESSION['tabchart1'] = $bdd->consoview3days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['nameprevday'] == "Température":
			$_SESSION['tabchart1'] = $bdd->tempview3days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;
		}
	}


	//////////////////////////////////////////////////// CONTROLE POUR AFFICHER DES GRAPHIQUES DU JOUR SUIVANT ////////////////////////////////////////////////////////////////////

	// CONTROLE ET LANCEMENT DES METHODES SI ON EST SUR UN JOUR DE VISUALISATION
	else if (isset($_POST['datenextday']) AND isset($_POST['namenextday']) AND $_SESSION['tabchartsize'] == 1) 		
	{
		$bdd = new sqlrequest();
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN 
		list($day, $month, $year) = preg_split('[/]', $_POST['datenextday']); 	 									
		$date0 = $year . '-' . $month . '-' . $day;			 	 						 
		// CREATION DE LA DATE EN OBJET POUR UTILISER DATESUB OU DATEADD
		$date1 = date_create($date0);																			
		// APPLICATION DES LA METHODE ADD DATE, RAJOUT DE 1 JOUR 
		date_add($date1, date_interval_create_from_date_string('1 days'));										
		// RECUPERATION DE LA DATE EN STRING POUR LA PASSER EN PARAMETRE DES FUNCTIONS
		$datefunction = date_format($date1, 'Y-m-d');																
		// RECUPERATION DE LA DATE EN STRING POUR LA SESSION TABCHART
		$_SESSION['datechart'] = date_format($date1, 'd/m/Y');	
	 	// RECUPERATION DE LA DATE EN STRING POUR L'AFFICHAGE												
		$_SESSION['datedisplay'] = date_format($date1, 'd/m/Y');												
		// FUNCTION POUR VOIR SI LA DATE DANS LA DB CONTIENT DES DONNEES
		$validatedatenextday = $bdd->checkdateview($datefunction);														

		// SWITCH POUR DETERMINER QUOI FAIRE EN FONCTION DE LA REPONSE DE CHECKDATEVIEW
		switch ($validatedatenextday) 																				
		{
			case 0:																	
					header('Location: ../view/chartdisplay.php');
					$_SESSION['msg_dateview'] = 1;	
				break;
			
			case $validatedatenextday > 0: 																			

				if ($_POST['namenextday'] == "Energie Solaire")
				{	
					$_SESSION['chartname'] = "Energie Solaire";			
					$_SESSION['tabchart1'] = $bdd->sunview($datefunction);					
					header('Location: ../view/chartdisplay.php');
				}
				else if ($_POST['namenextday'] == "Energie Eolienne")
				{
					$_SESSION['chartname'] = "Energie Eolienne";
					$_SESSION['tabchart1'] = $bdd->windview1($datefunction);	
					$_SESSION['tabchart2'] = $bdd->windview2($datefunction);				
					header('Location: ../view/chartdisplay.php');
				}
				else if ($_POST['namenextday'] == "Tension Bus")
				{	
					$_SESSION['chartname'] = "Tension Bus";				
					$_SESSION['tabchart1'] = $bdd->busview($datefunction);					
					header('Location: ../view/chartdisplay.php');
					var_dump($_SESSION['tabchart1']);
				}
				else if ($_POST['namenextday'] == "Consommation")
				{
					$_SESSION['chartname'] = "Consommation";
					$_SESSION['tabchart1'] = $bdd->consoview($datefunction);					
					header('Location: ../view/chartdisplay.php');
				}
				else if ($_POST['namenextday'] == "Température")
				{	
					$_SESSION['chartname'] = "Température";				
					$_SESSION['tabchart1'] = $bdd->tempview($datefunction);					
					header('Location: ../view/chartdisplay.php');					
				}				
				break;
		}
	}
	// CONTROLE ET LANCEMENT DES METHODES SI ON EST SUR 2 JOURS DE VISUALISATION
	else if (isset($_POST['datenextday']) AND isset($_POST['namenextday']) AND $_SESSION['tabchartsize'] == 2) 	
	{
		$bdd = new sqlrequest();
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN
		list($day, $month, $year) = preg_split('[/]', $_POST['datenextday']); 	 	 							
		$date0 = $year . '-' . $month . '-' . $day;			 	 						 
		// CREATION DE LA DATE EN OBJET POUR UTILISER DATESUB OU DATEADD
		$date1 = date_create($date0);																	
		// APPLICATION DES LA METHODE ADD DATE, RAJOUT DE 1 JOUR
		date_add($date1, date_interval_create_from_date_string('1 days'));									
		// RECUPERATION DE LA DATE EN STRING POUR LA PASSER EN PARAMETRE DES FUNCTIONS
		$datefunction = date_format($date1, 'd/m/Y');															
		// RECUPERATION DE LA DATE EN STRING POUR LA SESSION TABCHART
		$_SESSION['datechart'] = date_format($date1, 'd/m/Y');												
		// RECUPERATION DE LA DATE EN STRING POUR L'AFFICHAGE
		$dateaffich1 = date_format($date1, 'd/m/Y');


			
		// CREATION DE LA SESSION DATEDISPLAY POUR LAFFICHAGE DANS CHARTDISPLAY
		$_SESSION['datedisplay'] = $_POST['datenextday'] . " au " . $dateaffich1;								

		switch ($_POST['namenextday'])
		{
			case $_POST['namenextday'] == "Energie Solaire":
			$_SESSION['tabchart1'] = $bdd->sunview2days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['namenextday'] == "Energie Eolienne":
			$_SESSION['tabchart1'] = $bdd->windview2days1($datefunction);
			$_SESSION['tabchart2'] = $bdd->windview2days2($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['namenextday'] == "Tension Bus":
			$_SESSION['tabchart1'] = $bdd->busview2days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['namenextday'] == "Consommation":
			$_SESSION['tabchart1'] = $bdd->consoview2days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['namenextday'] == "Température":
			$_SESSION['tabchart1'] = $bdd->tempview2days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;
		}
	}
	// CONTROLE ET LANCEMENT DES METHODES SI ON EST SUR 3 JOURS DE VISUALISATION
	else if (isset($_POST['datenextday']) AND isset($_POST['namenextday']) AND $_SESSION['tabchartsize'] == 3) 	
	{
		$bdd = new sqlrequest();
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN
		list($day, $month, $year) = preg_split('[/]', $_POST['datenextday']); 	 									 
		$date0 = $year . '-' . $month . '-' . $day;			 	 						 
		// CREATION DE LA DATE EN OBJET POUR UTILISER DATESUB OU DATEADD
		$date1 = date_create($date0);
		// CREATION D'UNE 2EME DATE OBJET																	
		$date2 = date_create($date0);																	
						
		// APPLICATION DES LA METHODE ADD DATE, RAJOUT DE 1 JOUR
		date_add($date1, date_interval_create_from_date_string('1 days'));
		// APPLICATION DES LA METHODE SUB DATE, RAJOUT DE 1 JOUR									
		date_sub($date2, date_interval_create_from_date_string(' 1 days '));								
		// RECUPERATION DE LA DATE EN STRING POUR LA PASSER EN PARAMETRE DES FUNCTIONS	
		$datefunction = date_format($date1, 'd/m/Y');
		// RECUPERATION DE LA DATE EN STRING POUR L'AFFICHAGE																									
		$date3 = date_format($date2, 'd/m/Y');															
		// RECUPERATION DE LA DATE EN STRING POUR LA SESSION TABCHART
		$_SESSION['datechart'] = date_format($date1, 'd/m/Y');												
		// RECUPERATION DE LA DATE EN STRING POUR L'AFFICHAGE
		$dateaffich1 = date_format($date1, 'd/m/Y');															
		
		// CREATION DE LA SESSION DATEDISPLAY POUR LAFFICHAGE DANS CHARTDISPLAY
		$_SESSION['datedisplay'] = $date3 . " au " . $dateaffich1;											

		switch ($_POST['namenextday'])
		{
			case $_POST['namenextday'] == "Energie Solaire":

			$_SESSION['tabchart1'] = $bdd->sunview3days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['namenextday'] == "Energie Eolienne":

			$_SESSION['tabchart1'] = $bdd->windview3days1($datefunction);
			$_SESSION['tabchart2'] = $bdd->windview3days2($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['namenextday'] == "Tension Bus":

			$_SESSION['tabchart1'] = $bdd->busview3days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['namenextday'] == "Consommation":

			$_SESSION['tabchart1'] = $bdd->consoview3days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;

			case $_POST['namenextday'] == "Température":

			$_SESSION['tabchart1'] = $bdd->tempview3days($datefunction);
			header('Location: ../view/chartdisplay.php');
			break;
		}
	}


//////////////////////////////////////////////////// CONTROLE POUR CHANGER DE TYPE DE DONNEES ////////////////////////////////////////////////////////////////////


	else if (isset($_POST['switchtypedate']) AND $_SESSION['tabchartsize'] == 1)
	{
		$bdd = new sqlrequest();
		// MISE EN FORME DE LA DATE AU FORMAT AMERICAIN 
		list($day, $month, $year) = preg_split('[/]', $_POST['switchtypedate']); 	 									
		$date0 = $year . '-' . $month . '-' . $day;							
											
		// FUNCTION POUR VOIR SI LA DATE DANS LA DB CONTIENT DES DONNEES
		$validatedatenextday = $bdd->checkdateview($date0);														

		// SWITCH POUR DETERMINER QUOI FAIRE EN FONCTION DE LA REPONSE DE CHECKDATEVIEW
		switch ($validatedatenextday) 																				
		{
			case 0:																	
					header('Location: ../view/chartdisplay.php');
					$_SESSION['msg_dateview'] = 1;	
				break;
			
			case $validatedatenextday > 0: 																			

				if (isset($_POST['sundata']))
				{
					$_SESSION['nameY'] = "(W) (W/m²)";	
					$_SESSION['chartname'] = "Energie Solaire";			
					$_SESSION['tabchart1'] = $bdd->sunview($date0);					
					header('Location: ../view/chartdisplay.php');
				}
				else if (isset($_POST['winddata']))
				{
					$_SESSION['nameY'] = "(W) (m/s)";	
					$_SESSION['chartname'] = "Energie Eolienne";
					$_SESSION['tabchart1'] = $bdd->windview1($date0);
					$_SESSION['tabchart2'] = $bdd->windview2($date0);					
					header('Location: ../view/chartdisplay.php');
				}
				else if (isset($_POST['busdata']))
				{	
					$_SESSION['nameY'] = "(V)";
					$_SESSION['chartname'] = "Tension Bus";				
					$_SESSION['tabchart1'] = $bdd->busview($date0);					
					header('Location: ../view/chartdisplay.php');
					var_dump($_SESSION['tabchart1']);
				}
				else if (isset($_POST['consodata']))
				{	
					$_SESSION['nameY'] = "(W)";
					$_SESSION['chartname'] = "Consommation";
					$_SESSION['tabchart1'] = $bdd->consoview($date0);					
					header('Location: ../view/chartdisplay.php');
				}
				else if (isset($_POST['tempdata']))
				{	
					$_SESSION['nameY'] = "(°C)";
					$_SESSION['chartname'] = "Température";				
					$_SESSION['tabchart1'] = $bdd->tempview($date0);					
					header('Location: ../view/chartdisplay.php');					
				}				
				break;
		}
	}
	else if (isset($_POST['switchtypedate']) AND $_SESSION['tabchartsize'] == 2)
	{		
		$bdd = new sqlrequest();						
												
	
		if (isset($_POST['sundata']))
		{	
			$_SESSION['nameY'] = "(W) (W/m²)";
			$_SESSION['chartname'] = "Energie Solaire";			
			$_SESSION['tabchart1'] = $bdd->sunview2days($_POST['switchtypedate']);					
			header('Location: ../view/chartdisplay.php');
		}
		else if (isset($_POST['winddata']))
		{
			$_SESSION['nameY'] = "(W) (m/s)";
			$_SESSION['chartname'] = "Energie Eolienne";
			$_SESSION['tabchart1'] = $bdd->windview2days1($_POST['switchtypedate']);	
			$_SESSION['tabchart2'] = $bdd->windview2days2($_POST['switchtypedate']);				
			header('Location: ../view/chartdisplay.php');
		}
		else if (isset($_POST['busdata']))
		{	
			$_SESSION['nameY'] = "(V)";
			$_SESSION['chartname'] = "Tension Bus";				
			$_SESSION['tabchart1'] = $bdd->busview2days($_POST['switchtypedate']);					
			header('Location: ../view/chartdisplay.php');
			var_dump($_SESSION['tabchart1']);
		}
		else if (isset($_POST['consodata']))
		{
			$_SESSION['nameY'] = "(W)";
			$_SESSION['chartname'] = "Consommation";
			$_SESSION['tabchart1'] = $bdd->consoview2days($_POST['switchtypedate']);					
			header('Location: ../view/chartdisplay.php');
		}
		else if (isset($_POST['tempdata']))
		{	
			$_SESSION['nameY'] = "(°C)";
			$_SESSION['chartname'] = "Température";				
			$_SESSION['tabchart1'] = $bdd->tempview2days($_POST['switchtypedate']);					
			header('Location: ../view/chartdisplay.php');					
		}
		
	}
	else if (isset($_POST['switchtypedate']) AND $_SESSION['tabchartsize'] == 3)
	{		
		$bdd = new sqlrequest();						
												
	
		if (isset($_POST['sundata']))
		{	
			$_SESSION['nameY'] = "(W) (W/m²)";
			$_SESSION['chartname'] = "Energie Solaire";			
			$_SESSION['tabchart1'] = $bdd->sunview3days($_POST['switchtypedate']);					
			header('Location: ../view/chartdisplay.php');
		}
		else if (isset($_POST['winddata']))
		{
			$_SESSION['nameY'] = "(W) (m/s)";
			$_SESSION['chartname'] = "Energie Eolienne";
			$_SESSION['tabchart1'] = $bdd->windview3days1($_POST['switchtypedate']);
			$_SESSION['tabchart2'] = $bdd->windview3days2($_POST['switchtypedate']);						
			header('Location: ../view/chartdisplay.php');
		}
		else if (isset($_POST['busdata']))
		{	
			$_SESSION['nameY'] = "(V)";
			$_SESSION['chartname'] = "Tension Bus";				
			$_SESSION['tabchart1'] = $bdd->busview3days($_POST['switchtypedate']);					
			header('Location: ../view/chartdisplay.php');
			var_dump($_SESSION['tabchart1']);
		}
		else if (isset($_POST['consodata']))
		{
			$_SESSION['nameY'] = "(W)";
			$_SESSION['chartname'] = "Consommation";
			$_SESSION['tabchart1'] = $bdd->consoview3days($_POST['switchtypedate']);					
			header('Location: ../view/chartdisplay.php');
		}
		else if (isset($_POST['tempdata']))
		{	
			$_SESSION['nameY'] = "(°C)";
			$_SESSION['chartname'] = "Température";				
			$_SESSION['tabchart1'] = $bdd->tempview3days($_POST['switchtypedate']);					
			header('Location: ../view/chartdisplay.php');					
		}
		
	}
	else
	{
		header('Location: ../view/dataview.php');
		$_SESSION['msg_dataview'] = 2;
	}
	




















 ?>