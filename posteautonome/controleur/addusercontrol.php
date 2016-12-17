<?php  
session_start();

require ('../modele/sqlrequest.php');
 //vérification classiques, si les champs existent et non vide (obsolète avec le controle de saisie...) 
 if ((isset($_POST['add_name'])) AND (isset($_POST['add_password'])) AND !empty($_POST['add_name']) AND !empty($_POST['add_password']))
 {
    //création de mon objet
  	$db = new sqlrequest(); 
    //lancement de la methode pour vérifier si l'utilisateur existe
  	$validadduser = $db->checkadduser($_POST['add_name']);
    //en fonction de la réponse de checkadduser
      switch($validadduser) 
      {
        case 0:
          //on rajoute l'utilisateur dans la base de données
          $db->adduser($_POST['add_name'], $_POST['add_password'], $_POST['add_admin']);
          header('Location: ../view/adduser.php');
          //creation d'une variable de session pour afficher un message
          $_SESSION['msg_adduser'] = true ;
          break;
        
        default:
          header('Location: ../view/adduser.php');
          $_SESSION['msg_adduser'] = false ;
          break;
      }
  }  
  else
  {
    header('Location: ../view/adduser.php');
  }
 ?>