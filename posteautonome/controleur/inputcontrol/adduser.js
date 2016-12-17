	///////////////////////////////// CONTROLE DE SAISIE POUR LAJOUT DUN USER /////////////////////////////////

	var namecheck = false;
	var passwordcheck = false;
	var confirmpass = false;

	function checkname() 
		{
				 var goodColor = "#66cc66";
	   	 		 var badColor = "#ff6666";
		  		 var addname1 = document.getElementById("add_name");

		  if ((add_name.value.length < 3) || (add_name.value.length > 20))
		  {
		  		 
		  		addname1.style.backgroundColor = badColor;
		  		namecheck = false;
		  			  		 
		  }
		  else
		  {
		  		 addname1.style.backgroundColor = goodColor;
		  		 namecheck = true;		  		 
		  }
	      
	    }

	   
	function checkpass()
	   {
	   	  var password1 = document.getElementById('add_password');	   	 
	   	  //var message = document.getElementById("confirmMessage");
	   	  
	   	  var goodColor = "#66cc66";
	   	  var badColor = "#ff6666";

	   	  if (password1.value.length > 3 && password1.value.length < 25)
	   	  {
		   	  password1.style.backgroundColor = goodColor;
		   	  passwordcheck = true;
	   	  }
	   	  else
	   	  {
	   	  	password1.style.backgroundColor = badColor;
	   	  	passwordcheck = false;	   	  	
	   	  }
	   	  
	   }


	   function checkconfirmpass()
	   {
	   	  var password1 = document.getElementById('add_password');
	   	  var password2 = document.getElementById('confirm_password');	   	  	  

	   	  var goodColor = "#66cc66";
	   	  var badColor = "#ff6666";



	   	  if (password1.value == password2.value)
	      {
			password2.style.backgroundColor = goodColor;
			confirmpass = true;
		  }
		  else
		  {
			password2.style.backgroundColor = badColor;
			confirmpass = false;			
			//document.getElementById("add_button").disabled = true;		
	     }

	 }

	 function checkbutton()
	 {
	 	if (confirmpass == true && passwordcheck == true && namecheck == true)
	  	{
	  		document.getElementById("add_button").disabled = false;
	 	}
	 	else
	  	{
	  		document.getElementById("add_button").disabled = true;
	  	}
	 }