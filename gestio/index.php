<?PHP

   session_start();
   include("funcions.php");
   guarda_log();
   $_SESSION['idioma'] = 'ca'; //web no multi idioma, establir per defecte !!si usuari no validat es desactivara!!	   

	print("<!DOCTYPE HTML PUBLIC \"-//W3C/DTD HTML 4.0//EN\"\n");
	print("\"http://www.w3.org/TR/html4/strict.dtd\"> \n");
	print("<HTML LANG=\"es\">\n");
	print("<HEAD>\n");
	print("<meta charset=\"utf-8\">\n");
	print("<TITLE>Validar usuari</TITLE>\n");

	print("<LINK REL=\"stylesheet\" TYPE=\"text/css\" HREF=\"bootstrap.css\">\n");
	print("</HEAD>\n");
	print("<BODY>\n");

  //print ("<BR><BR><P ALIGN='CENTER'>". xifra_clau("Cuantum","C17mEd")."</P>\n");
  //exit();
   
	if (isset($_SESSION["temps_actiu"])) //veure si sessio caducada, si caducada elimina sessio 
	{
		caducat($_SESSION["temps_actiu"]);
	}
   
   if (isset($_REQUEST['entrar']) && isset($_SESSION['idioma']))  //idioma per si caducat no existira, no entrar
   {
	   if (isset($_REQUEST['usuari']) && isset($_REQUEST['clau']))
	   {
		  if (valida_usuari(treu_caracters($_REQUEST['usuari']),treu_caracters($_REQUEST['clau'])) === "SI")
		  {
			 $usuari_validat = treu_caracters($_REQUEST['usuari']);
			 $clau_crypt = xifra_clau($_REQUEST['usuari'], $_REQUEST['clau']);
			 $_SESSION["usuari_validat"] = $usuari_validat;
			 $_SESSION["clau"] = $clau_crypt;
			 $_SESSION["temps_actiu"]=time(); //per poder calcular la caducitat
			
            guarda_log($usuari_validat);
            print ("Usuari Validat");
            header("Location: noticia.php");
            exit();

			  		  
		  }
		  else {
		  guarda_log('ERROR intent acces: ');
		  print ("<P ALIGN='CENTER'><h2>Usuari o contrasenya incorrecte</h2></P>\n");
		  }
	   }
   }

// usuari  validat o no
   if (isset($_SESSION["usuari_validat"]))
   {
   		if (!isset($_REQUEST['entrar'])) // usuari validat no apretat entrar (torna enrere)
		{
            guarda_log($usuari_validat);
            //print ("Usuari Validat");
            header("Location: noticia.php");
            exit();
			
			
		}
   }
   else
   {
      //unset ($_SESSION['idioma']); //si usuari no correcte desactivar variable sessio
	  
	  if(session_id()!=""){  //si la sesio ha estat caducada no estara iniciada i donaria error al fer unset i destroy
	  session_unset();
	  session_destroy();  
      }
	  
	  print("<BR><BR>\n");
      print(" <div class='col-md-4'>\n");
      print("<h1>Identificat per poder entrar</h1>\n");
      print("<br />\n");
      print("<FORM CLASS='login-form' NAME='login' ACTION='index.php' METHOD='POST'>\n");
      print("<br />\n");
	  print("<input type='text' NAME='usuari' class='form-control' id='usuari' placeholder='Usuari' required />\n");
      print("<br />\n");
	  print("<input type='PASSWORD' NAME='clau' class='form-control' id='clau' placeholder='Contrasenya' required />\n");
      print("<br />\n");
      print("<P><INPUT TYPE='SUBMIT' NAME='entrar' VALUE='entrar' class='btn btn-default'></P>\n");
      print("</FORM>\n");
      print("</div>\n");
      print("<P></P>\n");
   }
print("</BODY>\n");
print("</HTML>\n");   
?>
