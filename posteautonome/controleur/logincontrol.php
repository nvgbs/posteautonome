<?php 

session_start();
 

require ('../modele/sqlrequest.php');


//vérification si les post existent et sont remplis
if ((isset($_POST['name'])) AND (isset($_POST['password'])) AND !empty($_POST['name']) AND !empty($_POST['password']))
{	
	//creation de mon objet sqlrequest
	$db = new sqlrequest();	
	// lancement de la methode pour controler si les logs sont bons et recup la réponse
	$log = $db->checklogin($_POST["name"], $_POST['password']);
	// variable de session
	$_SESSION['name'] = $_POST["name"]; 
	// en fonction de la réponse
	if ($log >= 1) 
	{
		// lancement de la méthode pour sauvegarder le role de l'utilisateur (admin ou user)
		$_SESSION['role'] = $db->checkrole($_POST["name"]);	
		header('Location: ../view/dataview.php');		
		exit();
	}
	else
	{		
		header('Location: ../index.php'); 
		exit();
	}
}
else
{		
		header('Location: ../index.php');
}


 ?>