<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Annetut lausunnot (lausunnonantajan käyttöliittymä)
 *
 * Created: 4.2.2016
 */

include_once '_fmas_ui.php';  

$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){	
	try {
		if ($api = createSoapClient()) {
			
			$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_annetut_lausunnot", array("token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));						
			$annetut_lausunnot = $vastaus["LausunnotDTO"]["Annetut_lausunnot"];		
			
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}

	include './ui/views/lausunnonantaja_annetut_lausunnot_view.php';
	
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}	

?>