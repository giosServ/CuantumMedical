<?PHP
//session_start();

function guarda_log($no_validat=''){

		/* //PER MySQL!!!!
        include("text.php");	//per noms
		
		$v_err_con = "err_conexio";  //per si hi ha errors text a ensenyar 
		$conexio = mysqli_connect ($servidor, $us_b_d, $clau_us_b_d,$b_d) or die ($$v_err_con." ERROR 1"); //conexio amb el servidor
		//mysql_select_db ($b_d) or die ( $$v_err_con." ERROR 2"); //conexio amb la base de dades
		*/
    
        
        $db = new SQLite3("db/cnews.db");
            
		$instruccio = "INSERT INTO logs ( ";

		if($no_validat!=''){
		$instruccio = $instruccio. "no_validat,";
		}
		
		$instruccio = $instruccio. "pagina, ";
		$instruccio = $instruccio. "navegador, ";
        $instruccio = $instruccio. "ip ";
		
		
		$instruccio = $instruccio. ") VALUES ('";

        if($no_validat!=''){
		$instruccio = $instruccio. $no_validat." Ip: ".$_SERVER["REMOTE_ADDR"]."Port: ".$_SERVER["REMOTE_PORT"]."','";
		}
		
		$instruccio = $instruccio.$_SERVER["PHP_SELF"]."','";
		$instruccio = $instruccio.$_SERVER["HTTP_USER_AGENT"]."','";
        $instruccio = $instruccio.$_SERVER["REMOTE_ADDR"]."')";
	    
    
        //print($instruccio);
		//exit();
    
		/*mysqli_set_charset( $conexio, 'utf8');
		$consulta = mysqli_query ($conexio,$instruccio); //executa sql
		*/
        $db->exec($instruccio);
        $db->close();

    /*
		if(!$consulta) { //no s'ha pogut executar el sql
			//die ($$v_err_con." ERROR 3"); 
		}
		else { // s'ha executat el sql
			mysqli_close ($conexio); 
			Return "OK";
		}
    */
}

function caducat($temps){    //si la ultima vegada actualitzada sesio mes de 30 minuts, caducarla (1800)
	if(isset($_SESSION["temps_actiu"]) && (time() - $_SESSION["temps_actiu"] > 1800)){
		session_unset();
		session_destroy();
	}
}

function valida_usuari($usuari,$clau){   //valida l usuari retorna SI o NO o l error generat
  
            /*
			include("text.php");	//per noms
			$idioma=$_SESSION["idioma"]; //per noms idioma
			$v_err_con = "err_conexio";  //per si hi ha errors text a ensenyar 
            $conexio = mysqli_connect ($servidor, $us_b_d, $clau_us_b_d,$b_d) or die ($$v_err_con." ERROR 1"); //conexio amb el servidor
            //mysql_select_db ($b_d) or die ( $$v_err_con." ERROR 2"); //conexio amb la base de dades
            */
    
                    $db = new SQLite3('db/cnews.db');

                    $clau_crypt = xifra_clau($usuari, $clau);
                    $instruccio = "select codi, clau from usuaris where codi = '" . $usuari . "' and clau = '" . $clau_crypt . "';";
                   
            /*
			mysqli_set_charset( $conexio, 'utf8');		  
			$consulta = mysqli_query ($conexio, $instruccio); //executa sql
	        */
            
                    $result = $db->query($instruccio);
                      
                    while ($row = $result->fetchArray()) 
                    {		
						echo "......................";				
                        return "SI";
                        $db->close();
                        
                        exit();
                    }
                    $db->close();
                    return "NO";
     
    
    /*
			if(!$consulta) { //no s'ha pogut executar el sql
			die ($$v_err_con." ERROR 3"); 
			}
			else { // s'ha executat el sql
				$nfilas = mysqli_num_rows ($consulta); //num de registres retornats
				mysqli_close ($conexio); //tancar la conexio
				if ($nfilas > 0)
				{
					return "SI";
				}
				else{
					return "NO";
				}
			}
    */
}

function xifra_clau($usuari,$clau){
	$salt = substr ($usuari, 0, 2);
	$clau_crypt = crypt ($clau, $salt);
	return  $clau_crypt;
}

function treu_caracters($text){ //treu caracters lletjos ' (o espais si tot text en blanc) abans de guardar a bd
	if (trim($text) == ""){
		return "";
	}
	else{
		$text = str_replace("'","’",$text);
		$text = str_replace('"',"”",$text);
		//$text = str_replace("%"," ",$text);
		//$text = str_replace("&"," ",$text);		
		$text = str_replace("<","‹",$text);		
		$text = str_replace(">","›",$text);				
		return $text;
	}
}

function existeix($sql) {
    /*
	include("text.php");	//per noms
	$idioma=$_SESSION["idioma"]; //per noms idioma
    
    $v_err_con = "err_conexio";  //per si hi ha errors text a ensenyar 
    $conexio = mysqli_connect ($servidor, $us_b_d, $clau_us_b_d,$b_d) or die ($$v_err_con." ERROR 1"); //conexio amb el servidor
    //mysql_select_db ($b_d) or die ( $$v_err_con." ERROR 2"); //conexio amb la base de dades
    */
    
    $db = new SQLite3('db/cnews.db');
    
    $instruccio = $sql; //sql a enviar
    
    $result = $db->query($instruccio);

    while ($row = $result->fetchArray()) 
    {
        $db->close();
        return "SI";
        exit();
    }
    $db->close();
    return "NO";

    /*
	mysqli_set_charset( $conexio, 'utf8');
	$consulta = mysqli_query ($conexio,$instruccio); //executa sql
	
	if(!$consulta) { //no s'ha pogut executar el sql
	die ($$v_err_con." ERROR 3"); 
	}
	else { // s'ha executat el sql
		$nfilas = mysqli_num_rows ($consulta); //num de registres retornats
		mysqli_close ($conexio); //tancar la conexio
		if ($nfilas > 0)
		{
			return "SI";
		}
		else{
			return "NO";
		}
	}
    */
}

function seleccio_multiple_amb_cap_i_limit_sql ($sql,$pagina,$inici,$num, $taula,$camps_mostra, $foto='',$link_id='',$eliminar=''){//($pagina,$inici,$num,$codi,$nom_usuari,$email_usuari){
    include("text.php");	//per noms
    $idioma=$_SESSION["idioma"]; //per noms idioma
    /*
	
	$idioma=$_SESSION["idioma"]; //per noms idioma
	$v_err_con = "err_con"."_".$idioma;  //per si hi ha errors de conexio a bd text a ensenyar 
    
    $conexio = mysqli_connect($servidor, $us_b_d, $clau_us_b_d, $b_d) or die ($$v_err_con." ERROR 1"); //conexio amb el servidor PHP VER 7!!!
    */
    $db = new SQLite3('db/cnews.db');
	$instruccio = $sql;
	//print($instruccio);
	//exit();

    //ja tinc l'SQL
    
    /*
    mysqli_set_charset( $conexio, 'utf8'); //conexio amb el servidor PHP VER 7!!!	
    $consulta = mysqli_query ($conexio,$instruccio); //conexio amb el servidor PHP VER 7!!!
	*/
    $consulta = $db->query($instruccio);
    
    if(!$consulta) { //no s'ha pogut executar el SQL que conta registres
		die ("ERROR 3 A"); 
	}
	else { // s'ha executat el SQL que conta registres
		//dubuixa mostrant x de xx d'un total d' x
        
        
        
        /*
        $r = $db->prepare("SELECT * FROM logs;");
        $r->execute();
        $result = $r->fetchAll();
        echo "result contains " . count($result);
        */
        // query the database and save the result in $result
        
        $nreg = 0;
        while ($consulta->fetchArray()){$nreg++;}
        
		if ($nreg > 0) {
			// Mostrar num inicial i final de les files a mostrar
			
			//$e_consulta_us="consulta_us_".$idioma; 
			//echo ("<P> ".$$e_consulta_us." ". ($inici + 1) . " - ");
			echo ("<P> ". ($inici + 1) . " - ");
			echo($inici + $num);
			$e_total="total_".$idioma;
			echo (" ".$$e_total." ".$nreg."\n");
			// Mostrar botons ant seg
			if ($nreg > $num) {
				$e_anterior="anterior_".$idioma;
				$e_seguent="seguent_".$idioma;
				if ($inici > 0){
					echo ("[ <A HREF='$pagina?inici=" . ($inici - $num) . "'>".$$e_anterior."</A> | ");
				}
				else{
					echo ("[ ".$$e_anterior." | ");
				}
				if ($nreg > ($inici + $num)){
					echo ("<A HREF='$pagina?inici=" . ($inici + $num) . "'>".$$e_seguent."</A> ]\n");
				}
				else{
					echo ($$e_seguent." ]\n");
				}
			}
			
            
            //echo ("<A HREF='$pagina?torna'>VOLVER</A> \n");
            echo ("</P>\n");
		}
	}
	// segon SQL per recuperar registres a mostrar a la taula
	//$instruccio=$instruccio." order by ".$camps_mostra[0]." desc limit ".$inici."," .$num; 
	$instruccio=$instruccio." order by id desc limit ".$inici."," .$num; 	
	
	$consulta = $db->query($instruccio);
	//print($instruccio);
	//exit();
	if(!$consulta) {
		die ($$v_err_con." ERROR 3 B"); 
	}
	else { 
	  

   // Mostrar resultados de la consulta
        $nreg = 0;
        while ($consulta->fetchArray()){$nreg++;}
        
		//$nreg = $db->query("select count(*) from (".$instruccio .")" );
		if ($nreg > 0){
			echo ("<div class=\"table-responsive\">\n");
			echo ("<TABLE class=\"table table-striped\"> \n");
			echo ("<TR>\n");
			foreach ($camps_mostra as $tmp_camps_mostra){
				$tmp_camps_mostra_idioma = $tmp_camps_mostra."_".$idioma;
				echo ("<TH>".$$tmp_camps_mostra_idioma."</TH>\n");
			}
			
			if ($eliminar == 'eli') { //si eliminar, afegir columna
			echo ("<TH></TH>");
			}
			
			echo ("</TR>\n");
            
         while($resultat = $consulta->fetchArray(SQLITE3_ASSOC))

			{
				echo ("<TR>\n");
				foreach ($camps_mostra as $tmp_camps_mostra){
					if ($tmp_camps_mostra == $foto) { 					//ULL si es foto
						if ($resultat[$tmp_camps_mostra] != ""){
							echo ("<TD><A TARGET='_blank' HREF='../" . $resultat[$tmp_camps_mostra] . "'><IMG class='petita' BORDER='0' SRC='../".$resultat[$tmp_camps_mostra]."' ALT=''></A></TD>\n"); 
                            
						}
						else{
							echo ("<TD>&nbsp;</TD>\n");
						}	
					}
					else{
						if ($tmp_camps_mostra == $link_id) {						//ULL si es id per link 
							if ($resultat[$tmp_camps_mostra] != ""){
								echo ("<TD><A HREF='$pagina?".$link_id."=" . $resultat[$tmp_camps_mostra] . "'>MODIFICAR</A></TD>\n");
							}
							else{
								echo ("<TD>&nbsp;</TD>\n");
							}	
						}
						else {
							echo ("<TD>" .   substr($resultat[$tmp_camps_mostra],0,110) . "</TD>\n");
						}
					}
				}
				if ($eliminar == 'eli') { // ull a si s afegeix eliminar, ULL importanr camp id ultim si s'activa eliminar 
					//echo ("<TD><A HREF='$pagina?".$eliminar."=" . $resultat[$tmp_camps_mostra] . "'>ELIMINAR</A></TD>\n");
					echo ("<TD><A HREF='$pagina?".$eliminar."=" . $resultat[$tmp_camps_mostra] . "' onclick=\"return confirm('Vols eliminar la noticia?')\">ELIMINAR</A></TD>\n");
				}
				echo ("</TR>\n"); 
			}
         echo ("</TABLE>\n");
		 echo ("</div>\n");
		}
		else{
			$e_err_sense_registres="err_sense_registres_".$idioma;
			echo ($$e_err_sense_registres);
		}
		// Cerrar conexión
		$db->close(); //tancar la conexio PHP VER 7!!!
	}
	
}

function alta_noticia(){  //dibuixa el frmulari per alta de noticies
			
								
						print("<div class=\"container-fluid\">\n");
							print("<div class=\"row\">\n");
								print("<form calass=\"form-horizontal\" action='noticia.php' METHOD='POST' ENCTYPE='multipart/form-data'>\n");				

									print("<div class=\"col-md-4\">\n");
										print("<br>\n");
										print("<br>\n");
										print( "<LABEL>FOTO</LABEL>\n");
										print( "<INPUT TYPE=\"FILE\"  SIZE=\"100\" NAME=\"foto\" VALUE=\"\">\n"); //foto principal
										//print("<INPUT TYPE='hidden' NAME='foto_antiga' VALUE='".$resultat[8]."'>\n");
										//print("<img src='../".$resultat[8]."'> \n");
										print("<br>");
                                        print("<LABEL>Data de la noticia</LABEL>\n");	
                                        print("<input type=\"text\" required class=\"form-control\" id='data_noticia' name='data_noticia' placeholder='introdueix data de la noticia' VALUE=''>\n");	
										print("<br>");
                                        print("<LABEL>Nom noticia en castella</LABEL>\n");	
                                        print("<input type=\"text\" required class=\"form-control\" id='nom_es' name='nom_es' placeholder='introdueix nom noticia' VALUE=''>\n");	
                                        print("<br>");	
                                        print("<LABEL>Nom noticia en catala</LABEL>\n");	
                                        print("<input type=\"text\" required class=\"form-control\" id='nom_ca' name='nom_ca' placeholder='introdueix nom noticia' VALUE=''>\n");
                                        print("<br>");
                                        print("<LABEL>Nom noticia en angles</LABEL>\n");	
                                        print("<input type=\"text\" required class=\"form-control\" id='nom_en' name='nom_en' placeholder='introdueix nom noticia' VALUE=''>\n");			
                                        print("<br>");										
									print("<br>");

    
    
    
									print("</div>\n");								
								
									print("<div class=\"col-md-4\">\n");
    
										print("<br>");
										print("<br>");
										print("<LABEL>Capçalera noticia en castella</LABEL>\n");										
										print("<textarea rows='6' required class=\"form-control\" id='text_cap_es' name='text_cap_es' placeholder='introdueix capçalera noticia' VALUE=''>\n");						
										print("</textarea>\n");
			
										print("<br>");
										print("<LABEL>Capçalera noticia en catala</LABEL>\n");										
										print("<textarea rows='6' required class=\"form-control\" id='text_cap_ca' name='text_cap_ca' placeholder='introdueix capçalera noticia' VALUE=''>\n");						
										print("</textarea>\n");
    
    
										print("<br>");
										print("<LABEL>Capçalera noticia en angles</LABEL>\n");										
										print("<textarea rows='6' required class=\"form-control\" id='text_cap_en' name='text_cap_en' placeholder='introdueix capçalera noticia' VALUE=''>\n");						
										print("</textarea>\n");
    
									print("</div>\n");
								
									print(" <div class=\"col-md-4\">\n");
    

    								    print("<br>");
										print("<br>");
										print("<LABEL>Text noticia en castella</LABEL>\n");										
										print("<textarea rows='6' required class=\"form-control\" id='text_es' name='text_es' placeholder='introdueix text noticia' VALUE=''>\n");						
										print("</textarea>\n");
			
										print("<br>");
										print("<LABEL>Text noticia en catala</LABEL>\n");										
										print("<textarea rows='6' required class=\"form-control\" id='text_ca' name='text_ca' placeholder='introdueix text noticia' VALUE=''>\n");						
										print("</textarea>\n");
    
    
										print("<br>");
										print("<LABEL>Text noticia en angles</LABEL>\n");										
										print("<textarea rows='6' required class=\"form-control\" id='text_en' name='text_en' placeholder='introdueix text noticia' VALUE=''>\n");						
										print("</textarea>\n");
                                        print("<br>");
                                        print("<br>");

										print("<INPUT TYPE='SUBMIT' NAME='alta_noticia' VALUE='AFEGIR NOTICIA' class='btn btn-default'>\n");
								
									print("</div>\n");								
								
								
								print("</form>\n");
							print("</div>\n");
						print("</div>\n");

}

function chec_txt($camp, $txt, $minim ,$maxim) { 
    	if (trim($txt) == "" or strlen($txt) < $minim or strlen($txt) > $maxim  ) {
	     $errors = "error en ".$camp;
		 return $errors;
		 }
	else { return "" ;}
}

function donar_alta_noticia($data_noticia, $nom_es , $nom_ca,$nom_en,$text_cap_es,$text_cap_ca,$text_cap_en,$text_es,$text_ca,$text_en,$foto){ //a la base de dades

/*	include("text.php");	//per noms
	$idioma=$_SESSION["idioma"]; //per noms idioma
	$v_err_con = "err_con"."_".$idioma;  //per si hi ha errors text a ensenyar 
    $conexio = mysqli_connect ($servidor, $us_b_d, $clau_us_b_d,$b_d) or die ($$v_err_con." ERROR 1"); //conexio amb el servidor
    //mysql_select_db ($b_d) or die ( $$v_err_con." ERROR 2"); //conexio amb la base de dades
*/	
    $db = new SQLite3('db/cnews.db');
    
	$instruccio = "INSERT INTO noticia ( ";
    $instruccio = $instruccio. "data_noticia,";
	$instruccio = $instruccio. "nom_es,";
    $instruccio = $instruccio. "nom_ca,";
    $instruccio = $instruccio. "nom_en,";
    $instruccio = $instruccio. "text_cap_es,";
    $instruccio = $instruccio. "text_cap_ca,";
    $instruccio = $instruccio. "text_cap_en,";
    $instruccio = $instruccio. "text_es,";
    $instruccio = $instruccio. "text_ca,";
    $instruccio = $instruccio. "text_en,";
	$instruccio = $instruccio. "foto ";
	$instruccio = $instruccio. ") VALUES ('";
	$instruccio = $instruccio. treu_caracters($data_noticia)."','";
    $instruccio = $instruccio. treu_caracters($nom_es)."','";
    $instruccio = $instruccio. treu_caracters($nom_ca)."','";
    $instruccio = $instruccio. treu_caracters($nom_en)."','";
    
	$instruccio = $instruccio. trim(treu_caracters($text_cap_es))."','";
    $instruccio = $instruccio. trim(treu_caracters($text_cap_ca))."','";
    $instruccio = $instruccio. trim(treu_caracters($text_cap_en))."','";
    
    $instruccio = $instruccio. trim(treu_caracters($text_es))."','";
    $instruccio = $instruccio. trim(treu_caracters($text_ca))."','";
    $instruccio = $instruccio. trim(treu_caracters($text_en))."','";
    
	$instruccio = $instruccio. treu_caracters($foto)."')";

    $db->exec($instruccio);
    $db->close();
    
    //print $instruccio;
    //exit();
    
    
	/*
	='".treu_caracters($preu)."',text_petit_".$idioma."='".treu_caracters($text_petit)."',
	descripcio_".$idioma."='".treu_caracters($descripcio)."',foto='".treu_caracters($foto)."',
	foto_detall='".treu_caracters($foto_detall)."' WHERE id='".$id."'";
	*/
	
    /*
	mysqli_set_charset( $conexio, 'utf8');
	$consulta = mysqli_query ($conexio,$instruccio); //executa sql
	*/
    
    /*
    
	if(!$consulta) { //no s'ha pogut executar el sql
		die ($$v_err_con." ERROR 3"); 
	}
	else { // s'ha executat el sql

        mysqli_close ($conexio); 
        unset ($_REQUEST['alta_noticia']);
        unset ($_REQUEST['alta']);
		//header("Location: noticia.php");
                
        //print("NOTICIA DONADA D'ALTA CORRECTAMENT <A HREF='noticia.php?alta'\"><button id=stop>ALTA NOVA NOTICIA</button></A>");
        //print("<A HREF='noticia.php'\"><button id=stop>TORNAR A NOTICIES</button></A>");
        
        Return "OK";
        
	}
    */
}

function elimina($id,$taula){
/*
	include("text.php");	//per noms
	$idioma=$_SESSION["idioma"]; //per noms idioma
	$v_err_con = "err_con"."_".$idioma;  //per si hi ha errors text a ensenyar 
	$conexio = mysqli_connect($servidor, $us_b_d, $clau_us_b_d, $b_d) or die ($$v_err_con." ERROR 1"); //conexio amb el servidor PHP VER 7!!!
*/	
    $db = new SQLite3('db/cnews.db');
    
    $instruccio = "DELETE FROM ".treu_caracters($taula)." WHERE id='".treu_caracters($id)."'";
	
    $db->exec($instruccio);
    $db->close();
    
    /*
    mysqli_set_charset( $conexio, 'utf8'); //conexio amb el servidor PHP VER 7!!!
            $consulta = mysqli_query ($conexio,$instruccio); //conexio amb el servidor PHP VER 7!!!
	
	if(!$consulta) { //no s'ha pogut executar el sql
		die ($$v_err_con." ERROR 3"); 
	}
	else { // s'ha executat el sql
		mysqli_close ($conexio);
		Return "OK";
	}
*/
    


}

function genera_form_modificar_noticia ($id) { //dibuixa form modificar menus ull!!! env_mod per al request ull!!!!! IMPORTANT ordre de camps passats i ordre mostrar
/*
			include("text.php");	//per noms
			$idioma=$_SESSION["idioma"]; //per noms idioma
            $v_err_con = "err_conexio";  //per si hi ha errors text a ensenyar 
            $conexio = mysqli_connect ($servidor, $us_b_d, $clau_us_b_d,$b_d) or die ($$v_err_con." ERROR 1"); //conexio amb el servidor
            //mysql_select_db ($b_d) or die ( $$v_err_con." ERROR 2"); //conexio amb la base de dades
	
*/
    
            $instruccio="select data_noticia, nom_es,nom_ca,nom_en,text_cap_es,text_cap_ca,text_cap_en,text_es,text_ca,text_en,foto from noticia  where id =".$id;
			
            //echo ($instruccio);
			//exit();
			//ja tinc l'SQL
            /*
            mysqli_set_charset( $conexio, 'utf8');
            $consulta = mysqli_query ($conexio,$instruccio); //executa sql
            */
            $db = new SQLite3('db/cnews.db');
            $consulta = $db->query($instruccio);
    
    
			if(!$consulta) { //no s'ha pogut executar el sql
			die ($$v_err_con." ERROR 3"); 
			}
			else { // s'ha executat el sql
				
				$nreg = 0;
                while ($consulta->fetchArray()){$nreg++;}
				
				//echo("inici");
				
				if ($nreg > 0){
				
					while($resultat = $consulta->fetchArray(SQLITE3_NUM)) 
					{
						print("<div class=\"container-fluid\">\n");
							print("<div class=\"row\">\n");
								print("<form calass=\"form-horizontal\" action='noticia.php' METHOD='POST' ENCTYPE='multipart/form-data'>\n");				

									print("<div class=\"col-md-4\">\n");
										print("<br>");
										print("<br>");
										print( "<LABEL>FOTO</LABEL>\n");
										print( "<INPUT TYPE=\"FILE\"  SIZE=\"100\" NAME=\"foto\" VALUE=\"\">\n"); //foto principal
										print("<INPUT TYPE='hidden' NAME='foto_antiga' VALUE='".$resultat[10]."'>\n");
										print("<img  class='petita' src='../".$resultat[10]."'> \n");
										print("<br>");
                                        print("<LABEL>Data de la noticia</LABEL>\n");	
                                        print("<input type=\"text\" required class=\"form-control\" id='data_noticia' name='data_noticia' placeholder='introdueix data de la  noticia' VALUE='".$resultat[0]."'>\n");                        
										print("<br>");
                                        print("<LABEL>Nom noticia en castella</LABEL>\n");	
                                        print("<input type=\"text\" required class=\"form-control\" id='nom_es' name='nom_es' placeholder='introdueix nom noticia' VALUE='".$resultat[1]."'>\n");	
                                        print("<br>");	
                                        print("<LABEL>Nom noticia en catala</LABEL>\n");	
                                        print("<input type=\"text\" required class=\"form-control\" id='nom_ca' name='nom_ca' placeholder='introdueix nom noticia' VALUE='".$resultat[2]."'>\n");
                                        print("<br>");
                                        print("<LABEL>Nom noticia en angles</LABEL>\n");	
                                        print("<input type=\"text\" required class=\"form-control\" id='nom_en' name='nom_en' placeholder='introdueix nom noticia' VALUE='".$resultat[3]."'>\n");			
                                        print("<br>");										
									print("<br>");

    
    
    
									print("</div>\n");								
								
									print("<div class=\"col-md-4\">\n");
    
										print("<br>");
										print("<br>");
										print("<LABEL>Capçalera noticia en castella</LABEL>\n");
                        
										print("<textarea rows='6' required class=\"form-control\" id='text_cap_es' name='text_cap_es' placeholder='introdueix capçalera noticia' VALUE=''>".$resultat[4]."\n");						
										print("</textarea>\n");
			
										print("<br>");
										print("<LABEL>Capçalera noticia en catala</LABEL>\n");										
										print("<textarea rows='6' required class=\"form-control\" id='text_cap_ca' name='text_cap_ca' placeholder='introdueix capçalera noticia' VALUE=''>".$resultat[5]."\n");						
										print("</textarea>\n");
    
    
										print("<br>");
										print("<LABEL>Capçalera noticia en angles</LABEL>\n");										
										print("<textarea rows='6' required class=\"form-control\" id='text_cap_en' name='text_cap_en' placeholder='introdueix capçalera noticia' VALUE=''>".$resultat[6]."\n");						
										print("</textarea>\n");
    
									print("</div>\n");
								
									print(" <div class=\"col-md-4\">\n");
    

    								    print("<br>");
										print("<br>");
										print("<LABEL>Text noticia en castella</LABEL>\n");										
										print("<textarea rows='6' required class=\"form-control\" id='text_es' name='text_es' placeholder='introdueix text noticia' VALUE=''>".$resultat[7]."\n");						
										print("</textarea>\n");
			
										print("<br>");
										print("<LABEL>Text noticia en catala</LABEL>\n");										
										print("<textarea rows='6' required class=\"form-control\" id='text_ca' name='text_ca' placeholder='introdueix text noticia' VALUE=''>".$resultat[8]."\n");						
										print("</textarea>\n");
    
    
										print("<br>");
										print("<LABEL>Text noticia en angles</LABEL>\n");										
										print("<textarea rows='6' required class=\"form-control\" id='text_en' name='text_en' placeholder='introdueix text noticia' VALUE=''>".$resultat[9]."\n");						
										print("</textarea>\n");
                                        print("<br>");
                                        print("<br>");

										print("<INPUT TYPE='SUBMIT' NAME='mod_noticia' VALUE='MODIFICAR NOTICIA' class='btn btn-default'>\n");
								
									print("</div>\n");								
								
								
								print("</form>\n");
							print("</div>\n");
						print("</div>\n");
                    
                    }
				}
				$db->close();
			}
}

function guarda_noticia($data_noticia, $nom_es , $nom_ca,$nom_en,$text_cap_es,$text_cap_ca,$text_cap_en,$text_es,$text_ca,$text_en,$id,$foto) { //guarda modificacio del projecte 

    /*
	include("text.php");	//per noms
	$idioma=$_SESSION["idioma"]; //per noms idioma
	$v_err_con = "err_con"."_".$idioma;  //per si hi ha errors de conexio a bd text a ensenyar 
    
    $conexio = mysqli_connect($servidor, $us_b_d, $clau_us_b_d, $b_d) or die ($$v_err_con." ERROR 1"); //conexio amb el servidor PHP VER 7!!!
    */
    
	$instruccio = "UPDATE noticia SET nom_es='".treu_caracters($nom_es)."',";
    $instruccio = $instruccio. "data_noticia='".treu_caracters($data_noticia)."',";
    $instruccio = $instruccio. "nom_ca='".treu_caracters($nom_ca)."',";
    $instruccio = $instruccio. "nom_en='".treu_caracters($nom_en)."',";
    $instruccio = $instruccio. "text_cap_es='".trim(treu_caracters($text_cap_es))."',";
    $instruccio = $instruccio. "text_cap_ca='".trim(treu_caracters($text_cap_ca))."',";
    $instruccio = $instruccio. "text_cap_en='".trim(treu_caracters($text_cap_en))."',";
    $instruccio = $instruccio. "text_es='".trim(treu_caracters($text_es))."',";
    $instruccio = $instruccio. "text_ca='".trim(treu_caracters($text_ca))."',";
    $instruccio = $instruccio. "text_en='".trim(treu_caracters($text_en))."',";
    
	$instruccio = $instruccio. "foto='".treu_caracters($foto)."' WHERE id='".$id."'";

    //print($instruccio);
    //exit();
    
	/*
	='".treu_caracters($preu)."',text_petit_".$idioma."='".treu_caracters($text_petit)."',
	descripcio_".$idioma."='".treu_caracters($descripcio)."',foto='".treu_caracters($foto)."',
	foto_detall='".treu_caracters($foto_detall)."' WHERE id='".$id."'";
	*/
    $db = new SQLite3('db/cnews.db');
    $db->exec($instruccio);
    $db->close();
    /*
	mysqli_set_charset( $conexio, 'utf8');
	$consulta = mysqli_query ($conexio,$instruccio); //executa sql
	
	if(!$consulta) { //no s'ha pogut executar el sql
		die ($$v_err_con." ERROR 3"); 
	}
	else { // s'ha executat el sql
		mysqli_close ($conexio); 
		Return "OK";
	}
    */

}

//FUNCIONS QUE ES FAN SERVIR A LA WEB CUANTUM

function WebNoticies ($idioma){
    
 //include("text.php");	//per noms
            /*
            $conexio = mysqli_connect ($servidor, $us_b_d, $clau_us_b_d,$b_d) or die ("ERROR DE CONEXION 1"); //conexio amb el servidor

			$instruccio="select id, data_noticia, nom_".$idioma.",text_cap_".$idioma.",text_".$idioma.",foto from noticia order by id desc";
			
			//echo ($instruccio);
			//exit();
			//ja tinc l'SQL
            mysqli_set_charset( $conexio, 'utf8');
            $consulta = mysqli_query ($conexio,$instruccio); //executa sql
            */
            $db = new SQLite3('gestio/db/cnews.db');
            $instruccio="select id, data_noticia, nom_".$idioma.",text_cap_".$idioma.",text_".$idioma.",foto from noticia order by id desc";

            //print ($instruccio);
            //exit();
            
            $consulta = $db->query($instruccio);


    
			if(!$consulta) { //no s'ha pogut executar el sql
			die ("ERROR DE CONEXION 2"); 
			}
			else { // s'ha executat el sql
				
				$nreg = 0;
                while ($consulta->fetchArray()){$nreg++;}
				
				//echo("inici");
                //numero noticia, per posar a dreta o esquerra (l'id $resultat[0] pot no ser consecutiu si se n'han borrat )
				$nnoti=1;
				if ($nreg > 0){
					while($resultat = $consulta->fetchArray(SQLITE3_NUM))
					{
                        if ($nnoti %2==0){
                            print("<div name='news".$resultat[0]."' class='noticia left'>\n");
                        }else{
                            print("<div name='news".$resultat[0]."' class='noticia right'>\n");
                        }
                            print("<h2>".$resultat[2]."</h2>\n");
                            $ext = substr($resultat[5], -3);
                            if( $ext == "jpg" || $ext == "png" )
								print("<div class='divfotonoticia'> <img class=fotonoticia src=".$resultat[5]." /></div>\n");
							#Carles 13/05/2019
							if( $ext == "avi" || $ext == "mpg" || $ext == "mp4" || $ext == "mkv" || $ext == "webm"  )
							{								
								print('<div class=divfotonoticia> 
										<video width="640" height="428" controls>
											<source src="'.$resultat[5].'" type="video/mp4">
											Your browser does not support the video tag.
										</video> 
										</div>');
							}
                            print("<div class='textnoticia'>\n");
                            print("<h3>".nl2br($resultat[3])."</h3>\n");     
                            //print("<div><p>".str_replace(PHP_EOL,'<br />',$resultat[4])."</p>\n");
                            print("<div><p>".nl2br($resultat[4])."</p>\n");
                            print("<p></p></div>\n");
                            print("</div>\n");
                            print("<div class='clear'></div>\n");                     
                            print("</div>\n");
                    $nnoti= $nnoti+1;
                    }
				}
				$db->close();
			}
    
}

function WebNoticiesPeu($idioma){
    
            /*
 include("text.php");	//per noms
            
            $conexio = mysqli_connect ($servidor, $us_b_d, $clau_us_b_d,$b_d) or die ("ERROR DE CONEXION 1"); //conexio amb el servidor

			$instruccio="select id, data_noticia, nom_".$idioma.",text_cap_".$idioma.",text_".$idioma.",foto from noticia order by id desc";
			
			//echo ($instruccio);
			//exit();
			//ja tinc l'SQL
            mysqli_set_charset( $conexio, 'utf8');
            $consulta = mysqli_query ($conexio,$instruccio); //executa sql
            */
    
            $db = new SQLite3('gestio/db/cnews.db');
            $instruccio="select id, data_noticia, nom_".$idioma.",text_cap_".$idioma.",text_".$idioma.",foto from noticia order by id desc";
	        $consulta = $db->query($instruccio);
    
			if(!$consulta) { //no s'ha pogut executar el sql
			die ("ERROR DE CONEXION 2"); 
			}
			else { // s'ha executat el sql
				
				$nreg = 0;
                while ($consulta->fetchArray()){$nreg++;}
				
				//echo("inici");
				
				if ($nreg > 0){
					while($resultat = $consulta->fetchArray(SQLITE3_NUM))
					{
                        print("<div class=peutoticia>\n");
                            print("<div class=datanoticiapeu>".$resultat[1]."</div>\n");
                            print("<div class=cosnoticiapeu><a class=newsnav href='?activepage=page_news&news=".$resultat[0]."'>".$resultat[2]."</a></div>\n");
                        print("</div>\n");
                    }
				}
				$db->close();
			}    
    
    
}


?>
