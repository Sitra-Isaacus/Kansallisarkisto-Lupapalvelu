<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Lausunnonantajan käyttöliittymän etusivu
 *
 * Created: 27.1.2016
 */

include_once '_fmas_ui.php';  

$_SESSION["kayttaja_rooli"] = "rooli_lausunnonantaja";
$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){	

	session_write_close();

	try {
		if ($api = createSoapClient()) {
			
			$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_saapuneet_lausuntopyynnot", array("token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"], "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"]));						
			$lausuntopyynnot = $vastaus["LausuntopyynnotDTO"];
			
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}

	include './ui/views/lausunnonantaja_saapuneet_lausuntopyynnot_view.php';
	
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}	
	
?>
