<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Aineistotilaus
 *
 * Created: 24.10.2017
 */

include_once '_fmas_ui.php';  

$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){ 
	if( isset($_GET['tutkimus_id']) || isset($_POST['tutkimus_id']) ) {	
		if(isset($_GET['tutkimus_id'])) $tutkimus_id = htmlspecialchars($_GET['tutkimus_id']);	
		if(isset($_POST['tutkimus_id'])) $tutkimus_id = htmlspecialchars($_POST['tutkimus_id']);			
	} else {
		header("location: index.php");								
		die();		
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}
	
try {
	if ($api = createSoapClient()) {
				
		if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);	
		
		// Lähetetään aineistopyyntö		
		if(isset($_POST["laheta_aineistopyynto"])){
						
			if(!isset($_POST["fk_aineistotilaus"]) || empty($_POST["fk_aineistotilaus"])){	
			
				$huomio_punainen = VAL_VIR_JAL;	
				
			} else {
				
				$aineistopyynnon_lahetys = suorita_logiikkakerroksen_funktio($api, "laheta_aineistopyynto", array("data"=>$_POST, "tutkimus_id"=>$tutkimus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id));
					
				if(isset($aineistopyynnon_lahetys["Aineistopyynto_lahetetty"]) && $aineistopyynnon_lahetys["Aineistopyynto_lahetetty"]){
					
					$huomio_vihrea = AINT_LAHETETTY;
					header("location: index.php?aint_lahetetty=1");
					die();				
									
				} else {
					$huomio_punainen = AINT_EPAONNISTUI;
				}				
				
			}
																																					
		}
		
		$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_tutkimuksen_aineistotilaus_tutkijalle", array("tutkimus_id"=>$tutkimus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id));
		$hakemuksetDTO_aineistopyynto = $vastaus["HakemuksetDTO_aineistopyynto"];		
				
	} 
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

include './ui/views/aineistotilaus_view.php';

?>