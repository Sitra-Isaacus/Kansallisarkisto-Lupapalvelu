<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Viranomaisen käyttöliittymän etusivu
 *
 * Created: 25.11.2015
 */

include_once '_fmas_ui.php';  

if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);
if(isset($_GET)) $_GET = poista_erikoismerkit($_GET);

if(isset($_GET["kayttajarooli"])) $_SESSION["kayttaja_rooli"] = $_GET["kayttajarooli"];	
		
$kayt_id = $_SESSION['kayttaja_id'];
$jarjestys_tyyppi = "desc";
$jarjestys_kohde = null;
$jarjestys_kentta = null;

if(kayttaja_on_kirjautunut()){	

	session_write_close();

	try {
		if ($api = createSoapClient()) {
		
			// Hakemuksen käsittely
			if (isset($_POST['tallenna_kasittelija'])) {
							
				//$hakemus_id = substr($_POST["hakemus_id"], 1);			
				$vastaus = suorita_logiikkakerroksen_funktio($api, "ota_hakemus_viranomaiskasittelyyn", array("kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "hakemus_id"=>$_POST["hakemus_id"], "kasittelija"=>$_POST["kasittelija"], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));

				if(isset($vastaus["Hakemus_kasittelyssa"]) && $vastaus["Hakemus_kasittelyssa"]){
					$huomio_vihrea = "Hakemus on otettu viranomaiskäsittelyyn.";
				} else {
					$huomio_punainen = "Tallennus epäonnistui.";
				}
				
			}
					
			if(isset($_GET["jarjestys_tyyppi"]) && isset($_GET["jarjestys_kentta"]) && isset($_GET["jarjestys_kohde"])){
				
				$jarjestys_tyyppi = $_GET["jarjestys_tyyppi"];
				$jarjestys_kohde = $_GET["jarjestys_kohde"];
				$jarjestys_kentta = $_GET["jarjestys_kentta"];
				
				$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_saapuneet_hakemukset_viranomaiselle", array("jarjestys_kohde"=>$_GET["jarjestys_kohde"], "jarjestys_kentta"=>$_GET["jarjestys_kentta"], "jarjestys_tyyppi"=>$_GET["jarjestys_tyyppi"], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"], "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"]));
			} else {
				$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_saapuneet_hakemukset_viranomaiselle", array("token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"], "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"]));
			}
			
			$hakemuksetDTO_kasiteltavat = array_merge($vastaus["HakemuksetDTO"]["Uudet"], $vastaus["HakemuksetDTO"]["Omat"], $vastaus["HakemuksetDTO"]["Avatut"]);
			
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}

	if (!isset($_POST['jarjesta_hakemukset'])) {
		include './ui/views/viranomainen_saapuneet_hakemukset_view.php';
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}

?>