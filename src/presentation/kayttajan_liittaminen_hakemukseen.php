<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Käyttäjä liitetään hakemukseen varmenteen perusteella
 *
 * Created: 29.4.2016
 */

include_once '_fmas_ui.php'; 

if (isset($_GET['sahkopostiosoite']) && isset($_GET['hash'])) { 		
	try {
		if ($api = createSoapClient()) {
			
			$_GET = poista_erikoismerkit($_GET);
			
			$vastaus = suorita_logiikkakerroksen_funktio($api, "liita_kayttaja_hakemukseen", array("sahkopostiosoite"=>$_GET['sahkopostiosoite'], "kayttajan_liittamisen_varmenne"=>$_GET['hash']));

			$registrationNeeded = $vastaus["registration_needed"];
			$kayttajaLiitettyHakemukseen = $vastaus["kayttaja_liitetty_hakemukseen"];
			$kayttajaOnLiitettyJoAiemmin = $vastaus["kayttaja_liitetty_hakemukseen_aiemmin"];
			$kayttaja = $vastaus["KayttajaDTO"];
			$hakemusversioDTO = $vastaus["HakemusversioDTO"];
			
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}
} else {	
	header("Location: kirjaudu.php");
	die();		
}

include './ui/views/kayttajan_liittaminen_hakemukseen_view.php';

?>