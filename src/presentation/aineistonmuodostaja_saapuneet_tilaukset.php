<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Aineistonmuodostajan käyttöliittymän etusivu
 *
 * Created: 28.4.2016
 */

include_once '_fmas_ui.php';  

$_SESSION["kayttaja_rooli"] = "rooli_aineistonmuodostaja";

$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){
	
	session_write_close();

	try {
		if ($api = createSoapClient()) {
		
			if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);
			if(isset($_GET)) $_GET = poista_erikoismerkit($_GET);
			
			if(isset($_POST["tallenna_kasittelija"])){

				$vastaus = suorita_logiikkakerroksen_funktio($api, "ota_aineistotilaus_kasittelyyn", array("aineistotilaus_id"=>$_POST["aineistotilaus_id"], "kasittelija"=>$_POST["kasittelija"], "kayt_id"=>$_SESSION["kayttaja_id"]));

				if(isset($vastaus["Aineistotilaus_kasittelyssa"]) && $vastaus["Aineistotilaus_kasittelyssa"]){
					$huomio_vihrea = AINT_OT_KAS;
				} else {
					$huomio_punainen = AINT_OT_EP;
				}			
				
			}
			
			$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_saapuneet_aineistotilaukset", array("kayt_id"=>$kayt_id));
			$hakemukset = $vastaus["HakemuksetDTO"]["Aineistotilaukset"];
			if(isset($vastaus["Viranomaisen_roolitDTO_Aineistonmuodostajat"])) $aineistonmuodostajat = $vastaus["Viranomaisen_roolitDTO_Aineistonmuodostajat"];
		
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}

	include './ui/views/aineistonmuodostaja_saapuneet_tilaukset_view.php';
	
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}	

?>