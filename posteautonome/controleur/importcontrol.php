<?php 
session_start();

	require ('../modele/sqlrequest.php');

	if (isset($_FILES['importfile']['name']) AND !empty($_FILES['importfile']['name']) AND isset($_POST['date']) AND !empty($_POST['date']))
	{  	//regex pour controler que le format de la date est correcte
		if (preg_match('#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#',$_POST['date']))
		{
			//split la date pour la transformater en format américain
			list($day, $month, $year) = preg_split('[/]', $_POST['date']);
			//on met la date au format américain (AAAA-MM-JJ)
			$date = $year . '-' . $month . '-' . $day;
			//création de mon objet
			$db = new sqlrequest();		
			//methode pour savoir si elle la date selectionné est déja remplie
			$datevalid = $db->checkdate($date);
			//en fonction de la réponse on lance la methode ou non
			switch ($datevalid)
			{
				case 0:
				//méthode pour intégrer le CSV dans la DB
					$db->addcsv($date);					
					header('Location: ../view/importfile.php');
					$_SESSION['msg_importcontrol'] = 1;
					break;
				case $datevalid > 0:			
					header('Location: ../view/importfile.php');
					$_SESSION['msg_importcontrol'] = 2;
					break;
			}		
		}		
		else
		{
			header('Location: ../view/importfile.php');
			$_SESSION['msg_importcontrol'] = 5;
		}
	}
	else if ((isset($_POST['button_delete'])))
	{
			if (preg_match('#^[0-9]{2}/[0-9]{2}/[0-9]{4}$#',$_POST['datedelete'])) // REGEX CONTROLE DATE AU BON FORMAT
			{
				list($day, $month, $year) = preg_split('[/]', $_POST['datedelete']);	// ON SPLIT LA DATE

				$date = $year . '-' . $month . '-' . $day;	// ON LA MET AU FORMAT AMERICAIN (AAAA-MM-JJ)

				echo $date;

				$db = new sqlrequest();		

				$datevalid = $db->checkdate($date);	// ON LANCE LA METHODE POUR VOIR SI ELLE NEXISTE PAS DEJA

				switch ($datevalid)	// EN FONCTION DE DATEVALID ON ENREGISTRE OU NON LA DATE
				{
					case 0:											
						header('Location: ../view/importfile.php');
						$_SESSION['msg_importcontrol'] = 3;
						break;
					case $datevalid > 0:
						$db->deletecsv($date);			
						header('Location: ../view/importfile.php');
						$_SESSION['msg_importcontrol'] = 4;
						break;
				}


			}
			else
			{
				header('Location: ../view/importfile.php');
				$_SESSION['msg_importcontrol'] = 5;
			}

	}

 ?>