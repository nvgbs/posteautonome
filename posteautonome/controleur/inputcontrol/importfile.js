///////////////////////////////// CONTROLE DE SAISIE POUR LIMPORT DE FICHIER /////////////////////////////////

var check_file = false;
var check_date = false;
var check_datedelete = false;


function checkfile ()
{
	if (document.getElementById("importfile") != null)
	{
		check_file = true;
		console.log("YOLO LE FICHIER");
	}
	else
	{
		check_file = false;
		console.log("LOYO LE FICHIER");
	}

	if (check_file == true && check_date == true)
	{
		document.getElementById("button_import").disabled = false;	
		console.log("YOLO LE BOUTON");
	}
	else
	{
		document.getElementById("button_import").disabled = true;
		console.log("LOYO LE BOUTON");
	}
}


function checkdate ()
{
	if (document.getElementById("date") != null)
	{
		check_date = true;
		document.getElementById("button_delete").disabled = false;		
	}
	else
	{
		check_date = false;
		console.log("LOYO LA DATE ");
	}

	if (check_file == true && check_date == true)
	{
		document.getElementById("button_import").disabled = false;		
		console.log("YOLO LE BOUTON");
	}
	else
	{
		document.getElementById("button_import").disabled = true;		
		console.log("LOYO LE BOUTON");
	}
}

function checkdatedelete ()
{
	if (document.getElementById("datedelete") != null)
	{
		check_datedelete = true;
		document.getElementById("button_delete").disabled = false;		
	}
	else
	{
		check_datedelete = false;
		console.log("LOYO LA DATE DELETE ");
	}
}

