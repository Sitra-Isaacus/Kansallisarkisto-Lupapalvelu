<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Viranomaisen käyttöliittymä (viestit)
 *
 * Created: 22.12.2015
 */

include_once '_fmas_ui.php';  

$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){
	if( isset($_GET['hakemus_id']) || isset($_POST['hakemus_id']) ) {	
		if(isset($_GET['hakemus_id'])) $hakemus_id = htmlspecialchars($_GET['hakemus_id']);	
		if(isset($_POST['hakemus_id'])) $hakemus_id = htmlspecialchars($_POST['hakemus_id']);			
	} else {
		header("location: viranomainen_saapuneet_viestit.php");								
		die();		
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}
	
try {
	if ($api = createSoapClient()) {
		
		if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);
						
		// Lähetetään viesti		
		if (isset($_POST['laheta_viesti'])) {						
			if(empty($_POST["vastaanottaja"]) || empty($_POST["viesti"]) || !isset($_SESSION['kayttaja_id']) || !isset($hakemus_id)){	
				$huomio_punainen = VIEST_LAH_EPAONN;					
			} else {
										
				$vastaus = suorita_logiikkakerroksen_funktio($api, "laheta_viesti", array("kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "data"=>$_POST, "on_vastaus"=>false, "hakemus_id"=>$hakemus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id));	
				
				if(isset($vastaus["Viesti_lahetetty"]) && $vastaus["Viesti_lahetetty"]){
					if((isset($_POST["laheta_taydennyspyynto"]) && $_POST["laheta_taydennyspyynto"]=="taydennettavaa_hakemukseen") || (isset($_POST["laheta_lisatietopyynto"]) && $_POST["laheta_lisatietopyynto"]==1)){
						$huomio_vihrea = TAYDPYYNT_LAH;
					} else {
						$huomio_vihrea = VIESTI_LAH;
					}					
				} else {
					$huomio_punainen = VIESTI_LAH_FAIL;
				}
				
			}
		}
		
		// Lähetetään vastaus
		if (isset($_POST['laheta_vastaus'])) {
		
			if(!isset($_POST['vastaus']) || $_POST['vastaus']==""){
				$huomio_punainen = TYHJA_VIESTI;			
			} else {
				
				$vastaus = suorita_logiikkakerroksen_funktio($api, "laheta_viesti", array("kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "data"=>$_POST, "on_vastaus"=>true, "hakemus_id"=>$hakemus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id));	
				
				if(isset($vastaus["Viesti_lahetetty"]) && $vastaus["Viesti_lahetetty"]){
					$huomio_vihrea = VIESTI_LAH;
				} else {
					$huomio_punainen = VIESTI_LAH_FAIL;
				}
				
			}				
									 					
		}
				
		$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_hakemuksen_viestit_viranomaiselle", array("kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "hakemus_id"=>$hakemus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id));	
		
		$viestit = $vastaus["ViestitDTO"];
		$hakemusDTO = $vastaus["HakemusDTO"];
		$hakemusversioDTO = $hakemusDTO->HakemusversioDTO;
		$tutkimus_id = $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID;
		$hakemusversio_id = $hakemusDTO->HakemusversioDTO->ID;
		if(isset($vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"])) $Tutkimuksen_viranomaisen_hakemuksetDTO = $vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"];
		
		// Päivitetään SESSION muuttuja uudet_viestit_kpl:
		$lukemattomia=0;
		for($i=0; $i < sizeof($viestit); $i++){
			if($viestit[$i]->Luettu==0 && $viestit[$i]->KayttajaDTO_Vastaanottaja->ID==$kayt_id){
				$lukemattomia++;
			}
			if(isset($viestit[$i]->ViestitDTO_Vastaukset)){
				for($l=0; $l < sizeof($viestit[$i]->ViestitDTO_Vastaukset); $l++){
					if($viestit[$i]->ViestitDTO_Vastaukset[$l]->Luettu==0 && $viestit[$i]->ViestitDTO_Vastaukset[$l]->KayttajaDTO_Vastaanottaja->ID==$kayt_id){
						$lukemattomia++;
					}
				}
			}
		}
		
		//if($_SESSION["kayttaja_uudet_viestit_kpl"] > 0 && $lukemattomia > 0){
		//	$_SESSION['Istunto']->Kayttaja->uudet_viestit_kayttajalle_kpl = $_SESSION['Istunto']->Kayttaja->uudet_viestit_kayttajalle_kpl - $lukemattomia;
		//}
																			 						
	} 
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

include './ui/views/viranomainen_hakemus_viestit_view.php';

?>