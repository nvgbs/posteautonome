	/////////////////////////////////CONTROLE DE SAISIE POUR LE LOGIN/////////////////////////////////


	 var namelogincheck = false;
	 var passwordlogincheck = false;
	

	function checkloginname() 
	{
	 	  var goodColor = "#66cc66";
 		  var badColor = "#ff6666";
		  var logname = document.getElementById("name");

		  if ((logname.value.length < 3) || (logname.value.length > 20))
		  {		  		 
		  	logname.style.backgroundColor = badColor;
		  	namelogincheck = false;
		  			  		 
		  }
		  else
		  {
		  	logname.style.backgroundColor = goodColor;
		  	namelogincheck = true;		  		 
		  }
	      
	 }

	   
	function checkloginpass()
	{
   	  var password = document.getElementById('password');	   	 
   	  //var message = document.getElementById("confirmMessage");
   	  
   	  var goodColor = "#66cc66";
   	  var badColor = "#ff6666";

   	  if (password.value.length > 3 && password.value.length < 25)
   	  {
	   	  password.style.backgroundColor = goodColor;
	   	  passwordlogincheck = true;
   	  }
   	  else
   	  {
   	  	  password.style.backgroundColor = badColor;
   	      passwordlogincheck = false;	   	  	
   	  }
	   	  
	}


	  
	 function checkloginbutton()
	 {
	 	if (passwordlogincheck == true && namelogincheck == true)
	 	{
	  		document.getElementById("log_button").disabled = false;
	 	}
	 	else
	 	{
	  		document.getElementById("log_button").disabled = true;
	  	}
	 }