<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Käyttäjäroolit (pääkäyttäjä)
 *
 * Created: 4.7.2016
 */

include_once '_fmas_ui.php';  

if(isset($_GET["kayttajarooli"])) $_SESSION["kayttaja_rooli"] = $_GET["kayttajarooli"];
	
$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){
	
	session_write_close();	
	
	try {
		if ($api = createSoapClient()) {
			
			if (isset($_POST['fk_viranomainen']) && isset($_POST['kayttaja_rooli']) && isset($_POST['rooli_valittu'])){
				
				$_POST = poista_erikoismerkit($_POST);
				
				if(isset($_POST['viranomaisen_koodi'])){
					$vastaus = suorita_logiikkakerroksen_funktio($api, "paivita_kayttajan_rooli", array("viranomaisen_koodi"=>$_POST['viranomaisen_koodi'], "rooli_valittu"=>$_POST['rooli_valittu'], "kayttaja_rooli"=>$_POST['kayttaja_rooli'], "fk_viranomainen"=>$_POST['fk_viranomainen'], "fk_kayttaja"=>$_POST['fk_kayttaja'], "token"=>$_SESSION["kayttaja_token"], "tallentaja_id"=>$_SESSION["kayttaja_id"]));				
				} else {
					$vastaus = suorita_logiikkakerroksen_funktio($api, "paivita_kayttajan_rooli", array("rooli_valittu"=>$_POST['rooli_valittu'], "kayttaja_rooli"=>$_POST['kayttaja_rooli'], "fk_viranomainen"=>$_POST['fk_viranomainen'], "fk_kayttaja"=>$_POST['fk_kayttaja'], "token"=>$_SESSION["kayttaja_token"], "tallentaja_id"=>$_SESSION["kayttaja_id"]));
				}
				
				if(isset($vastaus["Kayttajan_rooli_paivitetty"]) && $vastaus["Kayttajan_rooli_paivitetty"]){
					$tallennusOnnistui = true;
				} else {
					$tallennusOnnistui = false;
				}
				
				if(isset($vastaus["Viranomaisen_rooliDTO"]["Lisatty_rooli"]->ID)){
					$viranomaisenUusiID = $vastaus["Viranomaisen_rooliDTO"]["Lisatty_rooli"]->ID;
				} else {
					$viranomaisenUusiID = null;
				}

				$responseToJSONarray["tallennusOnnistui"] = $tallennusOnnistui;
				$responseToJSONarray["viranomaisenUusiID"] = $viranomaisenUusiID;
				
				echo json_encode($responseToJSONarray);				
					
			} else {		
		
				if($_SESSION["kayttaja_rooli"] == "rooli_lupapalvelun_paak"){				
					$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_kayttajaroolit_lupapalvelun_paakayttajalle", array("token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
					$viranomaiset = $vastaus["Viranomaisten_roolitDTO"];			
				} else {
					$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_kayttajaroolit_viranomaisen_paakayttajalle", array("token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
					$kayttajat = $vastaus["Viranomaisten_roolitDTO"];					
				}
				
			}
															
		} 
	} catch (SoapFault $ex) {
		header("Location: kirjaudu.php?ei_kirjauduttu=1");
		die();	
	}

	if (!isset($_POST['kayttaja_id']) && !isset($_POST['kayttaja_rooli']) && !isset($_POST['rooli_valittu'])){
		include './ui/views/kayttajaroolit_view.php';
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}	
	
?>