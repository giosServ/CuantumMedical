<?PHP
 
session_start();
include("funcions.php");
include("text.php");
$validat="NO"; //per defecte no validat

caducat($_SESSION["temps_actiu"]); //si temps actiu te mes de xx (30) minuts caduca la sessio

if (isset($_SESSION["usuari_validat"])) //veure si la variable de sesio esta establerta
{
	$_SESSION["temps_actiu"]=time(); //actualitza temps acces a sesio per caducar passats 30 minuts
	if (existeix("select codi from usuaris where codi='". treu_caracters($_SESSION["usuari_validat"])."' and clau ='".treu_caracters($_SESSION["clau"])."'") == "SI") //tornar a validar l usuari i la clau ja xifrada tal com es guarda
	{
		$validat="SI"; //validat
	}
}

if ($validat =="SI") //usuari validat dibuixa pagina i / o processa formulari
{
    
    
    guarda_log($_SESSION["usuari_validat"]);
    

    /*
    //la primera vegada que s entra
	//guardar variables de busqueda per quan s'hagi de canviar de pagina doncs no s'ha entrat al form o per si s'ha de donar alta
	if( !isset($_SESSION['v_nom_tip_proj'])){
		$_SESSION['v_nom_tip_proj'] ="";
	}else
	if(isset($_REQUEST['id_tip_proj'])){
		$_SESSION['v_nom_tip_proj'] = treu_caracters($_REQUEST['id_tip_proj']);
	}
    */
    
    
    
	print("<!DOCTYPE HTML PUBLIC \"-//W3C/DTD HTML 4.0//EN\"\n");
	print("\"http://www.w3.org/TR/html4/strict.dtd\"> \n");
	print("<HTML LANG=\"es\">\n");
	print("<HEAD>\n");
	print("<meta charset=\"utf-8\">\n");
	print("<TITLE>MODIFICAR - ALTA NOTICIES</TITLE>\n");
	print("<link rel=stylesheet type=text/css href=cuantum.css>");
	print("<LINK REL=\"stylesheet\" TYPE=\"text/css\" HREF=\"bootstrap.css\">\n");
	print("</HEAD>\n");
	print("<BODY>\n");


    /*
	print("<FORM CLASS='login-form' NAME='formulari' ACTION='noticia.php' METHOD='POST'>\n");
	//generar list tipus noticies
	genera_list("id_tip_proj","select id, nom_tip_proj_".$_SESSION["idioma"]." from tip_proj",$_SESSION['v_nom_tip_proj']);
	print("<INPUT TYPE='SUBMIT' NAME='buscar' VALUE='BUSCAR' class='btn btn-default'>\n");
	$t_nom = "af_proj_".$_SESSION['idioma'];
	print("<INPUT TYPE='SUBMIT' NAME='alta' VALUE='".$$t_nom."' class='btn btn-default'>\n");
	print("</FORM>\n");
    */
    
        
	if(isset($_REQUEST['alta'])){ //es vol afegir un projecte dibuixa el form per alta projecte
	alta_noticia();  //dibuixa el form per alta noticia
	exit();
    }
    
    if(isset($_REQUEST['alta_noticia'])){ //es vol donar d alta una noticia a bd

    $error = "";
    if ($_REQUEST['data_noticia'] !=""){$error = $error. chec_txt('data_noticia', $_REQUEST['data_noticia'], '0' ,'10');} //chec llarg nom si n hi ha
    if ($_REQUEST['nom_es'] !=""){$error = $error. chec_txt('nom_es', $_REQUEST['nom_es'], '0' ,'250');} //chec llarg nom si n hi ha
    if ($_REQUEST['nom_ca'] !=""){$error = $error. chec_txt('nom_ca', $_REQUEST['nom_ca'], '0' ,'250');}
    if ($_REQUEST['nom_en'] !=""){$error = $error. chec_txt('nom_en', $_REQUEST['nom_en'], '0' ,'250');} //chec llarg nom si n hi ha
    

    //foto veure si s'ha pogut copiar al tmp i si es imatge ULL veure si es mecessari o si la imatge es obligada
    if (is_uploaded_file ($_FILES['foto']['tmp_name'])) {			
        $nomDirectori = $ruta_imatge;
        $nomAr = $_FILES['foto']['name'];
        $tipoFichero = substr($_FILES['foto']['type'],0,5); 
            if ( $tipoFichero == "image" || $tipoFichero == "video") // video afegit Carles 13/5/2019
            {
                //tot correcte
            }
            else 
            {
            $error=$error."Error en el formato de la imagen";
            }
    }
    else 
    {
        //no s'ha carregat l' arxiu per que	no s'ha seleccionat 
        if ($_FILES['foto']['name'] == "") {
            //$tmp_v_foto=$_REQUEST['foto_antiga'];
            //no s'ha carregat l' arxiu per que	no s'ha seleccionat 
        }
        else
        {
            $error=$error."Error en el tamaño de la imagen";
        }
    }


    if ($error != "") {
        print ($error);
        unset ($_REQUEST['alta_noticia']);
        $error="";
        exit();
    }

    // si no s'ha seleccionat cap imatge guardar la antiga, si s'ha seleccionat guardar al directori final
    if (isset($nomDirectori)  != ""){
        $idUnic = time();
        $nomAr = $idUnic . "-" . $nomAr;
        //move_uploaded_file ($_FILES['foto']['tmp_name'],$nomDirectori . $nomAr); 
        move_uploaded_file ($_FILES['foto']['tmp_name'],'../imatges/' . $nomAr);
        $nomAr =$nomDirectori . $nomAr;
    }else{
        $nomAr ='';
    }

    //trim per treure linies blanc textarea. es vol donar d alta una noticia a bd
    donar_alta_noticia(treu_caracters($_REQUEST['data_noticia']) ,treu_caracters($_REQUEST['nom_es']) , treu_caracters($_REQUEST['nom_ca']), treu_caracters($_REQUEST['nom_en']), trim(treu_caracters($_REQUEST['text_cap_es'])), trim(treu_caracters($_REQUEST['text_cap_ca'])), trim(treu_caracters($_REQUEST['text_cap_en'])), trim(treu_caracters($_REQUEST['text_es'])),trim(treu_caracters($_REQUEST['text_ca'])),trim(treu_caracters($_REQUEST['text_en'])),$nomAr);		
    
	}

    if(isset($_REQUEST['eli'])){ //es vol eliminar un projecte 
	elimina(treu_caracters($_REQUEST['eli']),"noticia");
	}
	
    if(isset($_REQUEST['id'])){ //es vol entrar al detall per modificar

		/*if($_SESSION['idioma']=='es'){
		$camps_mostra=array("nom_menu_es", "cos_menu_es","preu","text_petit_es","descripcio_es","foto","foto_detall"); //camps que s'ensenyaran al "grid"
		}
		else{
		$camps_mostra=array("nom_menu_ca", "cos_menu_ca","preu","text_petit_ca","descripcio_ca","foto","foto_detall"); //camps que s'ensenyaran al "grid"
		}

		$camps_busca=array("id"=>treu_caracters($_REQUEST['id'])); //camps on es pot buscar
		*/
		
		genera_form_modificar_noticia(treu_caracters($_REQUEST['id']));
		
		$_SESSION['var_id'] = treu_caracters($_REQUEST['id']); //guarda per fer servir al modificar 
        exit();
		
	}
	
    if(isset($_REQUEST['mod_noticia'])){  //es vol modificar una noticia , introduits al form modificar 

    $error = "";
    if ($_REQUEST['data_noticia'] !=""){$error = $error. chec_txt('data_noticia', $_REQUEST['data_noticia'], '0' ,'10');} //chec llarg nom si n hi ha
    if ($_REQUEST['nom_es'] !=""){$error = $error. chec_txt('nom_es', $_REQUEST['nom_es'], '0' ,'250');} //chec llarg nom si n hi ha
    if ($_REQUEST['nom_ca'] !=""){$error = $error. chec_txt('nom_ca', $_REQUEST['nom_ca'], '0' ,'250');}
    if ($_REQUEST['des_en'] !=""){$error = $error. chec_txt('nom_en', $_REQUEST['des_en'], '0' ,'250');} //chec llarg nom si n hi ha
    

    //foto veure si s'ha pogut copiar al tmp i si es imatge ULL veure si es mecessari o si la imatge es obligada
    if (is_uploaded_file ($_FILES['foto']['tmp_name'])) {			
        $nomDirectori = $ruta_imatge;
        $nomAr = $_FILES['foto']['name'];
        $tipoFichero = substr($_FILES['foto']['type'],0,5); 
            if ( $tipoFichero == "image" || $tipoFichero == "video") // video afegit Carles 13/5/2019
            {
                //tot correcte
            }else 
            {
            $error=$error."Error en el formato de la imagen";
            }
    }
    else 
    {
        //no s'ha carregat l' arxiu per que	no s'ha seleccionat 
        if ($_FILES['foto']['name'] == "") {
            $tmp_v_foto=$_REQUEST['foto_antiga'];
            //no s'ha carregat l' arxiu per que	no s'ha seleccionat 
        }
        else
        {
            $error=$error."Error en el tamaño de la imagen";
        }
    }


    if ($error != "") {
        print ($error);
        unset ($_REQUEST['alta_noticia']);
        $error="";
        exit();
    }

    // si no s'ha seleccionat cap imatge guardar la antiga, si s'ha seleccionat guardar al directori final
    if (isset($nomDirectori)  != ""){
        $idUnic = time();
        $nomAr = $idUnic . "-" . $nomAr;
        //move_uploaded_file ($_FILES['foto']['tmp_name'],$nomDirectori . $nomAr); 
        move_uploaded_file ($_FILES['foto']['tmp_name'],'../imatges/' . $nomAr);
        $nomAr =$nomDirectori . $nomAr;
    }else{
        $nomAr =$tmp_v_foto;
    }


        
        
		//trim per treure linies blanc textarea
		guarda_noticia(treu_caracters($_REQUEST['data_noticia']) ,treu_caracters($_REQUEST['nom_es']) , treu_caracters($_REQUEST['nom_ca']), treu_caracters($_REQUEST['nom_en']), treu_caracters($_REQUEST['text_cap_es']), treu_caracters($_REQUEST['text_cap_ca']), trim(treu_caracters($_REQUEST['text_cap_en'])), trim(treu_caracters($_REQUEST['text_es'])),trim(treu_caracters($_REQUEST['text_ca'])),trim(treu_caracters($_REQUEST['text_en'])),treu_caracters($_SESSION['var_id']), $nomAr);		
    

        
	}


    $camps_mostra=array('data_noticia','nom_es', 'nom_ca','nom_en','text_cap_es','text_cap_ca','text_cap_en','text_es','text_ca','text_en','foto','id'); //camps que s'ensenyaran al "grid" importanr id ultim si s'activa eliminar
    $sql='select * from noticia';
  
    //function seleccio_multiple_amb_cap_i_limit_sql ($sql,$pagina,$inici,$num, $taula,$camps_mostra, $foto='',$link_id='',$eliminar='')

    if(isset($_REQUEST['inici'])){ //si es la primera vegada inici val 0 si no es recull del valor d'inici a la url y 
        $inici=treu_caracters($_REQUEST['inici']);
        //$_SESSION['var_inici'] = $inici;
    }
    else{
        $inici = 0;
        }

    $num=10;
    print("<br />\n");
    print("<A HREF='noticia.php?alta'><button>ALTA NOVA NOTICIA</button></A>\n");
    print("<br />\n");
    print("<br />\n");

    print("<div class=\"container-fluid\">\n");
	print("<div class=\"row\">\n");

    seleccio_multiple_amb_cap_i_limit_sql ($sql,'noticia.php',$inici,$num,'noticia',$camps_mostra ,'foto','id','eli');
    
    
	print("</div>\n");	
	print("</div>\n");
	
	print("</BODY>\n");
	print("</HTML>\n");
	
}
else //usuari no validat, eliminar variables sessio i redirigir a login
{
	guarda_log('ERROR intent acces o caducat: ');
	session_unset();
	session_destroy ();
	header("Location: fi.php");
	exit();
}
?>
