<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Lausunnonantajan käyttöliittymä (lausunto)
 *
 * Created: 28.1.2016
 */

include_once '_fmas_ui.php';  
 
$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){	
	if(isset($_GET['hakemus_id']) || isset($_POST['hakemus_id']) ) {		
		if(isset($_GET['hakemus_id'])) $hakemus_id = htmlspecialchars($_GET['hakemus_id']);	
		if(isset($_POST['hakemus_id'])) $hakemus_id = htmlspecialchars($_POST['hakemus_id']);			
	} else {
		header("location: lausunnonantaja_saapuneet_lausuntopyynnot.php");								
		die();		
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}
	
try {
	if ($api = createSoapClient()) {
		
		$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_hakemuksen_lausunnot_lausunnonantajalle", array("hakemus_id"=>$hakemus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id));
		
		$hakemusDTO = $vastaus["HakemusDTO"];
		$tutkimus_id = $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID;
		$hakemusversio_id = $hakemusDTO->HakemusversioDTO->ID;			
		//$annetut_lausunnot = $vastaus["LausuntopyynnotDTO"]["Lausuntopyynnot_joihin_annettu_lausunto"];
		$lausuntopyynto = $vastaus["LausuntopyynnotDTO"]["Lausuntopyynnot_joihin_ei_ole_annettu_lausuntoa"];
		if(isset($vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"])) $Tutkimuksen_viranomaisen_hakemuksetDTO = $vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"];
		
		$hakemusversioDTO = $hakemusDTO->HakemusversioDTO;
			
	} 
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

include './ui/views/lausunnonantaja_hakemus_lausunto_view.php';

?>