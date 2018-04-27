<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: viestit
 *
 * Created: 15.10.2015
 */
 
include_once '_fmas_ui.php'; 

$_SESSION["kayttaja_uudet_viestit_kpl"] = 0;

if(kayttaja_on_kirjautunut()){

	session_write_close();
	
	try {
		if ($api = createSoapClient()) {		
			$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_saapuneet_viestit", array("token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"], "roolin_koodi"=>$_SESSION["kayttaja_rooli"]));
			$uudet_saapuneet_viestit = $vastaus["ViestitDTO"]["Lukemattomat"];
			$vanhat_saapuneet_viestit = $vastaus["ViestitDTO"]["Luetut"];
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}

	include './ui/views/viestit_view.php';
	
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}	

?>