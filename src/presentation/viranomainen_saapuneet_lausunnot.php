<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Saapuneet lausunnot (viranomaisen käyttöliittymä)
 *
 * Created: 1.2.2016
 */

include_once '_fmas_ui.php';  

$_SESSION["kayttaja_lukemattomat_lausunnot_kpl"] = 0;

if(kayttaja_on_kirjautunut()){

	session_write_close();

	try {
		if ($api = createSoapClient()) {
			
			$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_saapuneet_lausunnot_viranomaiselle", array("token"=>$_SESSION['kayttaja_token'], "kayt_id"=>$_SESSION['kayttaja_id'], "viranomaisroolin_koodi"=>$_SESSION['kayttaja_rooli']));						
			$lukemattomat_lausunnot = $vastaus["LausunnotDTO"]["Lukemattomat"];
			$luetut_lausunnot = $vastaus["LausunnotDTO"]["Luetut"];
									
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}

	include './ui/views/viranomainen_saapuneet_lausunnot_view.php';

} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}	
	
?>