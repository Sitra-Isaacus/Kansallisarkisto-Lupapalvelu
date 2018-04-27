<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Metatiedot
 *
 * Created: 17.10.2017
 */

include_once '_fmas_ui.php';

if(kayttaja_on_kirjautunut()){
	if(isset($_GET['metatiedot_kohde']) && isset($_GET['hakemus_id'])) {

		// Muuttujien alustus
		$kayt_id = $_SESSION["kayttaja_id"];
		$metatiedot_kohde = htmlspecialchars($_GET['metatiedot_kohde']); 
		$hakemus_id = htmlspecialchars($_GET['hakemus_id']); 
		$liite_id = null;
		$lausunto_id = null;
		
		if(isset($_GET["liite_id"])) $liite_id = htmlspecialchars($_GET["liite_id"]);
		if(isset($_GET["lausunto_id"])) $lausunto_id = htmlspecialchars($_GET["lausunto_id"]);
		
	} else {
		header("location: viranomainen_saapuneet_hakemukset.php");
		die();
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}
	
try {
	if ($api = createSoapClient()) {
		
		if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);
		if(isset($_GET)) $_GET = poista_erikoismerkit($_GET);
		
		if (isset($_POST['tallennuskohde']) && isset($_POST['tallennuskohde_id'])){ // Tallennetaan metatiedot
		
			$responseToJSONarray = array();		
			
			$tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_metatiedot", array("kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
		
			if(isset($tallennus["Metatiedot_tallennettu"])) $responseToJSONarray["tallennusOnnistui"] = true;			
			echo json_encode($responseToJSONarray);
		
		} else {
		
			if($metatiedot_kohde=="Liite"){
				$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_metatiedot", array("hakemus_id"=>$hakemus_id, "liite_id"=>$liite_id, "metatiedot_kohde"=>$metatiedot_kohde, "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));			
			} else if($metatiedot_kohde=="Lausunto"){
				$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_metatiedot", array("hakemus_id"=>$hakemus_id, "lausunto_id"=>$lausunto_id, "metatiedot_kohde"=>$metatiedot_kohde, "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
			} else {
				$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_metatiedot", array("metatiedot_kohde"=>$metatiedot_kohde, "hakemus_id"=>$hakemus_id, "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));	
			}
			
			$hakemusDTO = $vastaus["HakemusDTO"];				
			$tutkimus_id = $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID;
			$hakemusversio_id = $hakemusDTO->HakemusversioDTO->ID;
			$hakemus_id = $hakemusDTO->ID;		
			$hakemusversioDTO = $hakemusDTO->HakemusversioDTO;		
				
			if($metatiedot_kohde=="Hakemus"){
				$metatieto_kohdeDTO = $hakemusDTO;
			} else if($metatiedot_kohde=="Paatos"){
				$metatieto_kohdeDTO = $hakemusDTO->PaatosDTO;
			} else if($metatiedot_kohde=="Asia"){
				$metatieto_kohdeDTO = $hakemusDTO->AsiaDTO;
			} else if($metatiedot_kohde=="Liite" && isset($vastaus["LiiteDTO"])){
				$metatieto_kohdeDTO = $vastaus["LiiteDTO"];
			} else if($metatiedot_kohde=="Lausunto" && isset($vastaus["LausuntoDTO"])){
				$metatieto_kohdeDTO = $vastaus["LausuntoDTO"];	
			} else {
				header("location: viranomainen_saapuneet_hakemukset.php");
				die();			
			}
			
		}
	}
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

if (!isset($_POST['tallennuskohde']) && !isset($_POST['tallennuskohde_id'])){
	include './ui/views/metatiedot_view.php';
}

?>