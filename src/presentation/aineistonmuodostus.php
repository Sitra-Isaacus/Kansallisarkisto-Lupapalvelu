<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Viranomaisen käyttöliittymä (aineiston muodostus)
 *
 * Created: 26.10.2017
 */

include_once '_fmas_ui.php';  

$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){
	if(isset($_GET['hakemus_id']) || isset($_POST['hakemus_id'])) {	
		if(isset($_GET['hakemus_id'])) $hakemus_id = htmlspecialchars($_GET['hakemus_id']);	
		if(isset($_POST['hakemus_id'])) $hakemus_id = htmlspecialchars($_POST['hakemus_id']);			
	} else {
		header("location: aineistonmuodostaja_saapuneet_tilaukset.php");								
		die();		
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}

try {
	if ($api = createSoapClient()) {
		
		if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);
				
		if (isset($_POST["tallenna_aineistonmuodostus"])) {

			if(!isset($_POST["aineisto_lahetetty"]) || is_null($_POST["aineisto_lahetetty"]) || $_POST["aineisto_lahetetty"]==""){				
				$huomio_punainen = LIS_AINM_VA;								
			} else {
		
				$kuittaus = suorita_logiikkakerroksen_funktio($api, "tallenna_aineistonmuodostus", array("hakemus_id"=>$hakemus_id, "data"=>$_POST, "kayt_id"=>$_SESSION["kayttaja_id"]));

				if(isset($kuittaus["Aineistonmuodostus_tallennettu"]) && $kuittaus["Aineistonmuodostus_tallennettu"]){
					$huomio_vihrea = AINT_MUOD_TALL;						
				} else {
					$huomio_punainen = AINT_MUOD_FAIL;
				}

			}	
				
		}
				
		$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_hakemuksen_aineistonmuodostus", array("kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "hakemus_id"=>$hakemus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id));	
						
		$paatosDTO = $vastaus["PaatosDTO"];
		$hakemusDTO = $vastaus["PaatosDTO"]->HakemusDTO;
		$tutkimus_id = $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID;
		$hakemusversio_id = $hakemusDTO->HakemusversioDTO->ID;
		$aineistotilausDTO = $paatosDTO->AineistotilausDTO;
		$hakemusversioDTO = $hakemusDTO->HakemusversioDTO;	
		if(isset($vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"])) $Tutkimuksen_viranomaisen_hakemuksetDTO = $vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"];	
											 						
	} 
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

include './ui/views/aineistonmuodostus_view.php';

?>