<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Käyttäjän rekisteröinnin vahvistus
 *
 * Created: 15.11.2016
 */

include_once '_fmas_ui.php'; 

if (isset($_GET['sahkopostiosoite']) && isset($_GET['varmenne'])) { 		
	try {
		if ($api = createSoapClient()) {
			
			$_GET = poista_erikoismerkit($_GET);
			$vastaus = suorita_logiikkakerroksen_funktio($api, "varmenna_kayttaja", array("sahkopostiosoite"=>$_GET['sahkopostiosoite'], "varmenne"=>$_GET['varmenne']));
			
			if(isset($vastaus["KayttajaDTO"]["Varmennettu_kayttaja"]) && isset($vastaus["kayttaja_varmennettu"]) && $vastaus["kayttaja_varmennettu"]){
				$kayttaja_varmennettu = true;
			}
						
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}
} else {	
	header("Location: kirjaudu.php");
	die();		
}

include './ui/views/kayttajan_varmennus_view.php';

?>