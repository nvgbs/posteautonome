<?php 

class sqlrequest
{

	private $bdd = null;



	function __construct() 
	{
        
    }


	function opendb() 
	{	       
        $bdd = new PDO('mysql:host=127.0.0.1:3306; dbname=sncf_db; charset=utf8', 'root', '');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);           
               
       	return $bdd;
	}



	//////////////////////////////////////////////////// METHODES POUR AJOUTER ET CONTROLER UN UTILISATEUR /////////////////////////////////////////////////////////////////////


	function checklogin ($name, $password) 
	{
		//ouverture de la connexion à la bdd
		$bdd = $this->opendb();
		//creation d'une variable
		$rdy_to_login = 0 ;
		//requete SQL
		$reponse = $bdd->query('SELECT usr_password FROM user WHERE usr_name = "' . $name . '"');
		//Boucle sur toutes les résultats de ma requete
		while($donnees = $reponse->fetch())
		{			
			if ($donnees['usr_password'] === $password)
			{
				$rdy_to_login++;
			}
		}
		//on retroune le résultat de $rdy_to_login à logincontrol.php (controleur)
		return $rdy_to_login;		
	}



	function checkrole($name)
	{
		$bdd = $this->opendb();		
		$role = 0;
		$reponse = $bdd->query('SELECT usr_role FROM user WHERE usr_name= "' . $name . '"');

		while($donnees = $reponse->fetch())
		{
			if ($donnees['usr_role'] == 1)
			{
				$role++;
			}
		}
		return $role;
	}


	function checkadduser($name)
	{
		//connexion à la base de données
		$bdd = $this->opendb();
		//creation d'une variable
		$rdy_to_adduser = 0;
		//requete SQL
		$reponse = $bdd->query('SELECT usr_name FROM user');
		//boucle sur les résultats de ma requete
		while($donnees = $reponse->fetch())
		{
			//controle si l'utilisateur existe déja ou non
			if ($donnees['usr_name'] === $name)
			{
				$rdy_to_adduser++;							
			}
		}
		//retourne la variable sur addusercontrol.php
		return $rdy_to_adduser;	
	}


	function adduser($name,$password,$role)
	{
		$bdd = $this->opendb();
		
		$requete = $bdd->prepare('INSERT INTO user(usr_name, usr_password, usr_role) VALUES (?,?,?)');
		$requete->execute(array($name, $password, $role));
	}



	//////////////////////////////////////////////////// METHODES POUR INTEGRER UN CSV DANS LA DB /////////////////////////////////////////////////////////////////////


	function checkdate($date)
	{
		$rdy_to_date = 0;

		$bdd = $this->opendb();

		$reponse = $bdd->query('SELECT mes_date FROM mesure');		

		while ($donnees = $reponse->fetch())
		{
			if ($donnees['mes_date'] == $date)
			{
				$rdy_to_date++;
			}
		}
		return $rdy_to_date;
	}


	function addcsv($date)
	{
		// Connexion à la DB
		$bdd = $this->opendb();
		// Transfert du contenu du fichier CSV
		$csv = new SplFileObject($_FILES['importfile']['tmp_name']);
		/*Récupère une ligne depuis le fichier et l'analyse comme étant des données CSV et retourne un
		tableau contenant tous les champs lus*/
	    $csv->setFlags(SplFileObject::READ_CSV);
	    // Définit le délimiteur ainsi que le caractère utilisé pour entourer les champs CSV analysés
	    $csv->setCsvControl(';');     	
	    // Initialisation de la chaîne SQL
	    $sql = 'INSERT INTO mesure ( mes_time, mes_id_data, mes_data, mes_date) VALUES ';
	    // Creation de variable (elle servira a sauter la premiere ligne du fichier CSV)
	    $entete = true;
	    // Creation de variable (elle servira a stop la derniere ligne du fichier CSV)
	    $endline = 0;


	    // Pour chaque ligne du fichier CSV
	    foreach($csv as $ligne) 
	    {
	    	//comptabilisation des lignes
	    	$endline++;	    	
	        // Test pour sauter la ligne d'entête
	        if( $entete ) {
	            $entete = false;
	            continue;
	        }
	        // Test pour sauter la derniere ligne
	        else if ($endline == 290)
	        {
	        	continue;
	        }
	        // Pour chaque colonne de la ligne du fichier CSV
	        for( $i = 1 ; $i<13 ; $i++ ) 
	        {
	     		if ($ligne[$i] < 0 AND ($i == 1 || $i == 2 || $i == 3))
	     		{
	     			// On corrige les valeurs negatives en les mettant a 0
	     			$sql .= "(".$ligne[0].",".$i.",".'0'.",'".$date."'),";
	     		}
	     		else
	     		{
	     			// On rajoute une ligne à la chaîne SQL
	     			// $ligne[0] correspond au temps en seconde
	     			// $i (fk) concorde avec les id de ma table data_type
	     			// $ligne[$i] correspond a la valeur de la colonne
	     			// $date c'est la date choisie par l'utilisateur
	     			$sql .= "(".$ligne[0].",".$i.",".$ligne[$i].",'".$date."'),";
	     		}          			        
	        }
	    }
	    // On enlève juste la dernière virgule inutile
	    $sql = rtrim( $sql, ',' );	     
	    // On exécute la requête ainsi construite
	    $bdd->query( $sql );
	}




	function deletecsv($date)
	{
		// Connexion à la BDD
		$bdd = $this->opendb();
		// Requete SQL preparée
		$requete = $bdd->prepare('DELETE FROM mesure WHERE mes_date = ?');
		// Paramètre de notre requete SQL préparée
		$requete->execute(array($date));		
	}




	//////////////////////////////////////////////////// METHODES POUR AFFICHER DES GRAPHIQUES /////////////////////////////////////////////////////////////////////


	function checkdateview($dateview)
	{
		$bdd = $this->opendb();

		$rdy_check_dateview = 0;

		$reponse = $bdd->query('SELECT mes_date from mesure');

		while ($donnees = $reponse->fetch())
		{
			if ($donnees['mes_date'] == $dateview)
			{
				$rdy_check_dateview++;
			}
		}
		return $rdy_check_dateview;
	}

	

	function sunview($dateview)
	{
		//connexion a la db
		$bdd = $this->opendb();
		//variable pour la premiere ligne		
		$entete = true;		
		//premiere requete sql préparée
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 1 ORDER BY mes_time');
		$requete1->execute(array($dateview));
		// deuxieme requete sql préparée
		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 2 ORDER BY mes_time');
		$requete2->execute(array($dateview));		
		// tant que mes requetes sql ont des réponses
		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch())
		{	
			//juste pour une seule fois je nomme mon abscisse et mes courbes (obligatoire pour GoogleChart)
			if( $entete ) 
			{
	            $entete = false;
	            // abscisses, premiere courbe, seconde courbe
	            $tab[] = array('Time','Puissance Solaire (W) ','Irradiation (W/m²) ');
	        }
	        // transformation du temps en seconde en heure / minutes
	        $secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;

			// condition pour rajouter un zero au debut ou a la fin (ex: 00:00)
			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}
			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}
			// concaténation de la date
			$timedata = $time[0] . ":" . $time[1];
			// rajout de la ligne a mon tableau (heure / data1 / data2)
	 		$tab[] = array($timedata, floatval($donnees1['mes_data']), floatval($donnees2['mes_data']));
		}
		//on retourne le tableau
		return $tab;
	}	


	function windview1($dateview)
	{
		$bdd = $this->opendb();			
		$entete = true;	

		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 3 ORDER BY mes_time');
		$requete1->execute(array($dateview));		

		while($donnees1 = $requete1->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	           $tab[] = array('Time','Puissance Eolienne (W) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']));
		}

		return $tab;		
	}


	function windview2($dateview)
	{
		$bdd = $this->opendb();			
		$entete = true;	
		

		$requete2 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 4 ORDER BY mes_time');
		$requete2->execute(array($dateview));

		

		while($donnees1 = $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	           $tab[] = array('Time', 'Vitesse du vent (m/s)');
	        }

			$secondes = $donnees2['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees2['mes_data']));
		}

		return $tab;		
	}

	function busview($dateview)
	{
		//connexion a la db
		$bdd = $this->opendb();
		//variable pour la premiere ligne			
		$entete = true;		
		// requete sql préparée
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 5 ORDER BY mes_time');
		$requete1->execute(array($dateview));		
		// tant que ma requete sql a des réponses
		while($donnees1 = $requete1->fetch())
		{
			//juste pour une seule fois je nomme mon abscisse et ma courbe (obligatoire pour GoogleChart)
			if( $entete ) 
			{
	           $entete = false;
	           // creation de mon tableau + titre abscisse, titre de la courbe
	           $tab[] = array('Time','Tension Bus (V) ');
	        }
	        // transformation du temps en seconde en heure / minutes
			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;			
			// condition pour rajouter un zero au debut ou a la fin (ex: 00:00)
			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}
			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}
			// concaténation de la date
			$timedata = $time[0] . ":" . $time[1];
			// rajout de la ligne a mon tableau (heure / data1 / data2)	
	 		$tab[] = array($timedata, floatval($donnees1['mes_data']));	
		}		
		return $tab;
	}



	function consoview($dateview)
	{
		$bdd = $this->opendb();			
		$entete = true;
		
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 6 ORDER BY mes_time');
		$requete1->execute(array($dateview));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 7 ORDER BY mes_time');
		$requete2->execute(array($dateview));

		$requete3 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 8 ORDER BY mes_time');
		$requete3->execute(array($dateview));

		$requete4 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 9 ORDER BY mes_time');
		$requete4->execute(array($dateview));
		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch() AND $donnees3 = $requete3->fetch() AND $donnees4 = $requete4->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','P_ABS URA (W) ','P_Conso URA (W) ', 'P_Commande (W) ', 'Ch_Commande (W) ');
	        }

	        $secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];
			

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']),floatval($donnees2['mes_data']),floatval($donnees3['mes_data']),floatval($donnees4['mes_data']));	 		
		}

		return $tab;
	}

	


	function tempview($dateview)
	{
		$bdd = $this->opendb();		
		$entete = true;	
		
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 10 ORDER BY mes_time');
		$requete1->execute(array($dateview));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 11 ORDER BY mes_time');
		$requete2->execute(array($dateview));

		$requete3 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 12 ORDER BY mes_time');
		$requete3->execute(array($dateview));

		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch() AND $donnees3 = $requete3->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','Température Batterie (°C) ','Température Guerite (°C) ', 'Température Extérieur (°C) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']),floatval($donnees2['mes_data']),floatval($donnees3['mes_data']));
		}

		return $tab;
	 }




	 //////////////////////////////////////////////////// METHODES POUR AFFICHER DES GRAPHIQUES DE 3 JOURS /////////////////////////////////////////////////////////////////////




	 function sunview3days($dateview)
	 {
		$bdd = $this->opendb();			
		$entete = true;

		list($day, $month, $year) = preg_split('[/]', $dateview); 	 
		$date0 = $year . '-' . $month . '-' . $day;			 	 	
		$dateorigin1 = date_create($date0);
		$dateorigin2 = date_create($date0);		

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));		
		$date1 = date_format($dateorigin1, 'Y-m-d');		

		date_sub($dateorigin2, date_interval_create_from_date_string(' 2 days '));
		$date2 = date_format($dateorigin2, 'Y-m-d');		

		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 1 ORDER BY mes_date');
		$requete1->execute(array($date2, $date1, $date0));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 2 ORDER BY mes_date');
		$requete2->execute(array($date2, $date1, $date0));		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','Puissance Solaire (W) ','Irradiation (W/m²) ');
	        }

	        $secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']), floatval($donnees2['mes_data']));
		}

		return $tab;
	  }	


	 function windview3days1($dateview)
	 {
		$bdd = $this->opendb();			
		$entete = true;	

		list($day, $month, $year) = preg_split('[/]', $dateview); 	
		$date0 = $year . '-' . $month . '-' . $day;			 	 	

		$dateorigin1 = date_create($date0);
		$dateorigin2 = date_create($date0);		

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));		
		$date1 = date_format($dateorigin1, 'Y-m-d');		

		date_sub($dateorigin2, date_interval_create_from_date_string(' 2 days '));
		$date2 = date_format($dateorigin2, 'Y-m-d');

		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 3 ORDER BY mes_date');
		$requete1->execute(array($date2, $date1, $date0));

		

		

		while($donnees1 = $requete1->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	           $tab[] = array('Time','Puissance Eolienne (W)');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']));
		}

		return $tab;		
	 }



	 function windview3days2($dateview)
	 {
		$bdd = $this->opendb();			
		$entete = true;	

		list($day, $month, $year) = preg_split('[/]', $dateview); 	
		$date0 = $year . '-' . $month . '-' . $day;			 	 	

		$dateorigin1 = date_create($date0);
		$dateorigin2 = date_create($date0);		

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));		
		$date1 = date_format($dateorigin1, 'Y-m-d');		

		date_sub($dateorigin2, date_interval_create_from_date_string(' 2 days '));
		$date2 = date_format($dateorigin2, 'Y-m-d');

		

		$requete2 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 4 ORDER BY mes_date');
		$requete2->execute(array($date2, $date1, $date0));

		

		while($donnees1 = $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	           $tab[] = array('Time','Vitesse du vent (m/s) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees2['mes_data']));
		}

		return $tab;		
	 }



	 function busview3days($dateview)
	 {
	 	$bdd = $this->opendb();			
		$entete = true;	

		list($day, $month, $year) = preg_split('[/]', $dateview); 	
		$date0 = $year . '-' . $month . '-' . $day;			 	 	

		$dateorigin1 = date_create($date0);
		$dateorigin2 = date_create($date0);		

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));		
		$date1 = date_format($dateorigin1, 'Y-m-d');		

		date_sub($dateorigin2, date_interval_create_from_date_string(' 2 days '));
		$date2 = date_format($dateorigin2, 'Y-m-d');	


		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 5 ORDER BY mes_date');
		$requete1->execute(array($date2, $date1, $date0));		

		while($donnees1 = $requete1->fetch())
		{
			if( $entete ) 
			{
	           $entete = false;
	           $tab[] = array('Time','Tension Bus (V) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;			

			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}
			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];	

			$tab[] = array($timedata, floatval($donnees1['mes_data']));			
		}	
		
		return $tab;
	 }



	 function consoview3days($dateview)
	 {
		$bdd = $this->opendb();			
		$entete = true;

		list($day, $month, $year) = preg_split('[/]', $dateview); 	 
		$date0 = $year . '-' . $month . '-' . $day;			 	 	

		$dateorigin1 = date_create($date0);
		$dateorigin2 = date_create($date0);		

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));		
		$date1 = date_format($dateorigin1, 'Y-m-d');		

		date_sub($dateorigin2, date_interval_create_from_date_string(' 2 days '));
		$date2 = date_format($dateorigin2, 'Y-m-d');
		
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 6 ORDER BY mes_date');
		$requete1->execute(array($date2, $date1, $date0));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 7 ORDER BY mes_date');
		$requete2->execute(array($date2, $date1, $date0));

		$requete3 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 8 ORDER BY mes_date');
		$requete3->execute(array($date2, $date1, $date0));

		$requete4 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 9 ORDER BY mes_date');
		$requete4->execute(array($date2, $date1, $date0));
		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch() AND $donnees3 = $requete3->fetch() AND $donnees4 = $requete4->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','P_ABS URA (W) ','P_Conso URA (W) ', 'P_Commande (W) ', 'Ch_Commande (W) ');
	        }

	        $secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];
			

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']),floatval($donnees2['mes_data']),floatval($donnees3['mes_data']),floatval($donnees4['mes_data']));	 		
		}

		return $tab;
	 }



	 function tempview3days($dateview)
	 {
		$bdd = $this->opendb();		
		$entete = true;	


		list($day, $month, $year) = preg_split('[/]', $dateview); 	
		$date0 = $year . '-' . $month . '-' . $day;			 	 	 

		$dateorigin1 = date_create($date0);
		$dateorigin2 = date_create($date0);		

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));		
		$date1 = date_format($dateorigin1, 'Y-m-d');		

		date_sub($dateorigin2, date_interval_create_from_date_string(' 2 days '));
		$date2 = date_format($dateorigin2, 'Y-m-d');
		
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 10 ORDER BY mes_date');
		$requete1->execute(array($date2, $date1, $date0));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 11 ORDER BY mes_date');
		$requete2->execute(array($date2, $date1, $date0));

		$requete3 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?,?) AND mes_id_data = 12 ORDER BY mes_date');
		$requete3->execute(array($date2, $date1, $date0));

		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch() AND $donnees3 = $requete3->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','Température Batterie (°C) ','Température Guerite (°C) ', 'Température Extérieur (°C) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']),floatval($donnees2['mes_data']),floatval($donnees3['mes_data']));
		}

		return $tab;
	 }





	 //////////////////////////////////////////////////// METHODES POUR AFFICHER DES GRAPHIQUES DE 2 JOURS /////////////////////////////////////////////////////////////////////


	 function sunview2days($dateview)
	 {
		$bdd = $this->opendb();			
		$entete = true;

		list($day, $month, $year) = preg_split('[/]', $dateview); 	 
		$date0 = $year . '-' . $month . '-' . $day;			 	 	

		$dateorigin1 = date_create($date0);				

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));
		$date1 = date_format($dateorigin1, 'Y-m-d');				

		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?) AND mes_id_data = 1 ORDER BY mes_date');
		$requete1->execute(array($date1, $date0));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?) AND mes_id_data = 2 ORDER BY mes_date');
		$requete2->execute(array($date1, $date0));		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','Puissance Solaire (W) ','Irradiation (W/m²) ');
	        }

	        $secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']), floatval($donnees2['mes_data']));
		}

		return $tab;
	  }	



	 function windview2days1($dateview)
	 {
		$bdd = $this->opendb();			
		$entete = true;	

		list($day, $month, $year) = preg_split('[/]', $dateview); 	
		$date0 = $year . '-' . $month . '-' . $day;			 	 	

		$dateorigin1 = date_create($date0);				

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));		
		$date1 = date_format($dateorigin1, 'Y-m-d');		

		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?) AND mes_id_data = 3 ORDER BY mes_date');
		$requete1->execute(array($date1, $date0));

		
		

		while($donnees1 = $requete1->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	           $tab[] = array('Time','Puissance Eolienne (W)');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']));
		}

		return $tab;		
	 }


	 function windview2days2($dateview)
	 {
		$bdd = $this->opendb();			
		$entete = true;	

		list($day, $month, $year) = preg_split('[/]', $dateview); 	
		$date0 = $year . '-' . $month . '-' . $day;			 	 	

		$dateorigin1 = date_create($date0);				

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));		
		$date1 = date_format($dateorigin1, 'Y-m-d');		

		

		$requete2 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?) AND mes_id_data = 4 ORDER BY mes_date');
		$requete2->execute(array($date1, $date0));

		

		while($donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	           $tab[] = array('Time','Vitesse du vent (m/s) ');
	        }

			$secondes = $donnees2['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees2['mes_data']));
		}

		return $tab;		
	 }


	 function busview2days($dateview)
	 {
	 	$bdd = $this->opendb();			
		$entete = true;	

		list($day, $month, $year) = preg_split('[/]', $dateview); 	
		$date0 = $year . '-' . $month . '-' . $day;			 	 	

		$dateorigin1 = date_create($date0);				

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));		
		$date1 = date_format($dateorigin1, 'Y-m-d');			


		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?) AND mes_id_data = 5 ORDER BY mes_date');
		$requete1->execute(array($date1, $date0));		

		while($donnees1 = $requete1->fetch())
		{
			if( $entete ) 
			{
	           $entete = false;
	           $tab[] = array('Time','Tension Bus (V) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;			

			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}
			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];	

			$tab[] = array($timedata, floatval($donnees1['mes_data']));			
		}	
		
		return $tab;
	 }



	 function consoview2days($dateview)
	 {
		$bdd = $this->opendb();			
		$entete = true;

		list($day, $month, $year) = preg_split('[/]', $dateview); 	
		$date0 = $year . '-' . $month . '-' . $day;			 	 	

		$dateorigin1 = date_create($date0);				

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));		
		$date1 = date_format($dateorigin1, 'Y-m-d');		
		
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?) AND mes_id_data = 6 ORDER BY mes_date');
		$requete1->execute(array($date1, $date0));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?) AND mes_id_data = 7 ORDER BY mes_date');
		$requete2->execute(array($date1, $date0));

		$requete3 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?) AND mes_id_data = 8 ORDER BY mes_date');
		$requete3->execute(array($date1, $date0));

		$requete4 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?) AND mes_id_data = 9 ORDER BY mes_date');
		$requete4->execute(array($date1, $date0));
		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch() AND $donnees3 = $requete3->fetch() AND $donnees4 = $requete4->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','P_ABS URA (W) ','P_Conso URA (W) ', 'P_Commande (W) ', 'Ch_Commande (W) ');
	        }

	        $secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];
			

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']),floatval($donnees2['mes_data']),floatval($donnees3['mes_data']),floatval($donnees4['mes_data']));	 		
		}

		return $tab;
	 }



	 function tempview2days($dateview)
	 {
		$bdd = $this->opendb();		
		$entete = true;	


		list($day, $month, $year) = preg_split('[/]', $dateview); 	
		$date0 = $year . '-' . $month . '-' . $day;			 	 	 

		$dateorigin1 = date_create($date0);			

		date_sub($dateorigin1, date_interval_create_from_date_string(' 1 days '));		
		$date1 = date_format($dateorigin1, 'Y-m-d');		
		
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date IN (?,?) AND mes_id_data = 10 ORDER BY mes_date');
		$requete1->execute(array($date1, $date0));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?) AND mes_id_data = 11 ORDER BY mes_date');
		$requete2->execute(array($date1, $date0));

		$requete3 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date IN (?,?) AND mes_id_data = 12 ORDER BY mes_date');
		$requete3->execute(array($date1, $date0));

		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch() AND $donnees3 = $requete3->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','Température Batterie (°C) ','Température Guerite (°C) ', 'Température Extérieur (°C) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']),floatval($donnees2['mes_data']),floatval($donnees3['mes_data']));
		}

		return $tab;
	 }



//////////////////////////////////////////////////// METHODES POUR COMPARER DES JOURS /////////////////////////////////////////////////////////////////////




function comparesunview1($dateview1, $dateview2)
	{
		$bdd = $this->opendb();			
		$entete = true;		

		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 1 ORDER BY mes_time');
		$requete1->execute(array($dateview1));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 1 ORDER BY mes_time');
		$requete2->execute(array($dateview2));		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','Puissance Solaire ' . $_SESSION['datechart1'] . ' (W) ','Puissance Solaire ' . $_SESSION['datechart2'] . ' (W) ');
	        }

	        $secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']), floatval($donnees2['mes_data']));
		}

		return $tab;
	}

	function comparesunview2($dateview1, $dateview2)
	{
		$bdd = $this->opendb();			
		$entete = true;		

		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 2 ORDER BY mes_time');
		$requete1->execute(array($dateview1));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 2 ORDER BY mes_time');
		$requete2->execute(array($dateview2));		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time',' Irridiation ' . $_SESSION['datechart1'] . ' (W/m²) ',' Irridiation ' . $_SESSION['datechart2'] . ' (W/m²) ');
	        }

	        $secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']), floatval($donnees2['mes_data']));
		}

		return $tab;
	}


	function comparewindview1($dateview1, $dateview2)
	{
		$bdd = $this->opendb();			
		$entete = true;	

		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 3 ORDER BY mes_time');
		$requete1->execute(array($dateview1));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 3 ORDER BY mes_time');
		$requete2->execute(array($dateview2));

		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	           $tab[] = array('Time','Puissance Eolienne ' . $_SESSION['datechart1'] . ' (W) ','Puissance Eolienne ' . $_SESSION['datechart2'] . ' (W) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']), floatval($donnees2['mes_data']));
		}

		return $tab;		
	}

	function comparewindview2($dateview1, $dateview2)
	{
		$bdd = $this->opendb();			
		$entete = true;	

		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 4 ORDER BY mes_time');
		$requete1->execute(array($dateview1));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 4 ORDER BY mes_time');
		$requete2->execute(array($dateview2));

		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	           $tab[] = array('Time','Vitesse du vent ' . $_SESSION['datechart1'] . ' (m/s) ','Vitesse du vent ' . $_SESSION['datechart2'] . ' (m/s) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']), floatval($donnees2['mes_data']));
		}

		return $tab;		
	}


	function comparebusview($dateview1, $dateview2)
	{
		$bdd = $this->opendb();			
		$entete = true;
		

		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 5 ORDER BY mes_time');
		$requete1->execute(array($dateview1));

		$requete2 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 5 ORDER BY mes_time');
		$requete2->execute(array($dateview2));

		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	           $entete = false;
	           $tab[] = array('Time','Tension Bus ' . $_SESSION['datechart1'] . ' (V) ','Tension Bus ' . $_SESSION['datechart2'] . ' (V) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;

			

			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];


	 		$tab[] = array($timedata, floatval($donnees1['mes_data']), floatval($donnees2['mes_data']));	
		}
		
		return $tab;
	}


	function compareconsoview1($dateview1, $dateview2)
	{
		$bdd = $this->opendb();			
		$entete = true;
		
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 6 ORDER BY mes_time');
		$requete1->execute(array($dateview1));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 7 ORDER BY mes_time');
		$requete2->execute(array($dateview1));

		$requete3 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 6 ORDER BY mes_time');
		$requete3->execute(array($dateview2));

		$requete4 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 7 ORDER BY mes_time');
		$requete4->execute(array($dateview2));
		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch() AND $donnees3 = $requete3->fetch() AND $donnees4 = $requete4->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','P_ABS URA ' . $_SESSION['datechart1'] . ' (W) ','P_Conso URA ' . $_SESSION['datechart1'] . ' (W) ', 'P_ABS URA ' . $_SESSION['datechart2'] . ' (W) ', 'P_Conso URA ' . $_SESSION['datechart2'] . ' (W) ');
	        }

	        $secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];
			

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']),floatval($donnees2['mes_data']),floatval($donnees3['mes_data']),floatval($donnees4['mes_data']));	 		
		}

		return $tab;
	}


	function compareconsoview2($dateview1, $dateview2)
	{
		$bdd = $this->opendb();			
		$entete = true;
		
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 8 ORDER BY mes_time');
		$requete1->execute(array($dateview1));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 9 ORDER BY mes_time');
		$requete2->execute(array($dateview1));

		$requete3 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 8 ORDER BY mes_time');
		$requete3->execute(array($dateview2));

		$requete4 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 9 ORDER BY mes_time');
		$requete4->execute(array($dateview2));
		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch() AND $donnees3 = $requete3->fetch() AND $donnees4 = $requete4->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','P_Commande ' . $_SESSION['datechart1'] . ' (W) ','Ch_Commande ' . $_SESSION['datechart1'] . ' (W) ', 'P_Commande ' . $_SESSION['datechart2'] . ' (W) ', 'Ch_Commande ' . $_SESSION['datechart2'] . ' (W) ');
	        }

	        $secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];
			

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']),floatval($donnees2['mes_data']),floatval($donnees3['mes_data']),floatval($donnees4['mes_data']));	 		
		}

		return $tab;
	}





	function comparetempview1($dateview1, $dateview2)
	{
		$bdd = $this->opendb();		
		$entete = true;	
		
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 10 ORDER BY mes_time');
		$requete1->execute(array($dateview1));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 10 ORDER BY mes_time');
		$requete2->execute(array($dateview2));


		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','Température Batterie ' . $_SESSION['datechart1'] . ' (°C) ','Température Batterie ' . $_SESSION['datechart2'] . ' (°C) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']),floatval($donnees2['mes_data']));
		}

		return $tab;
	 }

	function comparetempview2($dateview1, $dateview2)
	{
		$bdd = $this->opendb();		
		$entete = true;	
		
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 11 ORDER BY mes_time');
		$requete1->execute(array($dateview1));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 11 ORDER BY mes_time');
		$requete2->execute(array($dateview2));
		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','Température Guerite ' . $_SESSION['datechart1'] . ' (°C) ','Température Guerite ' . $_SESSION['datechart2'] . ' (°C) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']),floatval($donnees2['mes_data']));
		}

		return $tab;
	 }

	function comparetempview3($dateview1, $dateview2)
	{
		$bdd = $this->opendb();		
		$entete = true;	
		
		$requete1 = $bdd->prepare('SELECT mes_data, mes_time from mesure WHERE mes_date= ? AND mes_id_data = 12 ORDER BY mes_time');
		$requete1->execute(array($dateview1));

		$requete2 = $bdd->prepare('SELECT mes_data from mesure WHERE mes_date= ? AND mes_id_data = 12 ORDER BY mes_time');
		$requete2->execute(array($dateview2));
		

		while($donnees1 = $requete1->fetch() AND $donnees2 = $requete2->fetch())
		{
			if( $entete ) 
			{
	            $entete = false;
	            $tab[] = array('Time','Température Extérieur ' . $_SESSION['datechart1'] . ' (°C) ','Température Extérieur ' . $_SESSION['datechart2'] . ' (°C) ');
	        }

			$secondes = $donnees1['mes_time'];
			$temp = $secondes % 3600;
			$time[0] = ( $secondes - $temp ) / 3600 ;
			$time[2] = $temp % 60 ;
			$time[1] = ( $temp - $time[2] ) / 60;


			if (strlen($time[0]) < 2)
			{
				$time[0] = "0" . $time[0];
			}

			if (strlen($time[1]) < 2)
			{
				$time[1] =  "0" . $time[1];
			}

			$timedata = $time[0] . ":" . $time[1];

	 		$tab[] = array($timedata, floatval($donnees1['mes_data']),floatval($donnees2['mes_data']));
		}

		return $tab;
	 }

}


?>