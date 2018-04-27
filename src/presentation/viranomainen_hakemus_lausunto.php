<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Viranomaisen käyttöliittymä (lausunnot)
 *
 * Created: 18.1.2016
 */

include_once '_fmas_ui.php';  
 
$kayt_id = $_SESSION["kayttaja_id"];
$hakemusDTO = null;
$lausunnonantajat = array();
$lausuntopyynnot = array();

if(kayttaja_on_kirjautunut()){
	if(isset($_GET['hakemus_id']) || isset($_POST['hakemus_id'])) {		
		if(isset($_GET['hakemus_id'])) $hakemus_id = htmlspecialchars($_GET['hakemus_id']);	
		if(isset($_POST['hakemus_id'])) $hakemus_id = htmlspecialchars($_POST['hakemus_id']);			
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
		
		if (isset($_POST['laheta_lausunto'])) {
			
			// Tarkistetaan pakolliset kentät
			if(empty($_POST["lausuntopyynto"]) || empty($_POST["laus_antaja"]) || !isset($_SESSION["kayttaja_id"]) || !isset($hakemus_id) || !isset($hakemus_id) || !isset($_SESSION["kayttaja_rooli"])){			
				$huomio_punainen = LAH_FAIL_PAK;			
			} else {
				
				$lausuntopyynnon_lahetys = suorita_logiikkakerroksen_funktio($api, "laheta_lausuntopyynto", array("kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "data"=>$_POST, "hakemus_id"=>$hakemus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
				
				if(isset($lausuntopyynnon_lahetys["Lausuntopyynto_lahetetty"]) && $lausuntopyynnon_lahetys["Lausuntopyynto_lahetetty"]){
					$huomio_vihrea = LP_LAH;
				} else {
					$huomio_punainen = LP_LAH_FAIL;	
				}				
				
			}
												 			 				
		}
		/*
		if (isset($_POST['lausunto_naytetaan_hakijoille'])) { 
		
			if($_POST['lausunto_naytetaan_hakijoille']=="on"){
				$naytetaankoLausuntoHakijoille = 1;
			} else {
				$naytetaankoLausuntoHakijoille = 0;
			}

			$lausunnon_nakyvyys = suorita_logiikkakerroksen_funktio($api, "vaihda_lausunnon_nakyvyys", array("naytetaankoLausuntoHakijoille"=>$naytetaankoLausuntoHakijoille, "lausunto_id"=>$_POST['lausunto_id'], "token"=>$_SESSION['suojaustunnus'], "kayt_id"=>$_SESSION['pohja_id']));
			$nakyvyysVaihdettu = $lausunnon_nakyvyys["Lausunnon_nakyvyys_paivitetty"];
	
		}		
												
		// Lisätään lausunnon avaaminen lokiin
		if (isset($_POST['merkitse_lausunto_luetuksi'])) {	
			suorita_logiikkakerroksen_funktio($api, "kirjaa_lokiin", array("kayt_id"=>$_SESSION['pohja_id'], "lausunto_id"=>$_POST["luettu_lausunto_id"], "toiminto"=>"lausunto_luettu", "pohja_rooli"=>$_SESSION['pohja_rooli']));	
		}		
		*/
		
		$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_hakemuksen_lausunnot_viranomaiselle", array("kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "hakemus_id"=>$hakemus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id));	
		
		$hakemusDTO = $vastaus["HakemusDTO"];
		$hakemusversioDTO = $hakemusDTO->HakemusversioDTO;
		$tutkimus_id = $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID;
		$hakemusversio_id = $hakemusDTO->HakemusversioDTO->ID;		
		$lausunnonantajat = $vastaus["Viranomaisen_roolitDTO"]["Lausunnonantajat"];
		$lausuntopyynnot = $vastaus["LausuntopyynnotDTO"];		
		$lausuntopyynto_sallittu = false;
		
		if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_kas" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_val"){
			
			if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_kas" && ($_SESSION["kayttaja_rooli"]=="rooli_kasitteleva" || $_SESSION["kayttaja_rooli"]=="rooli_eettisensihteeri")){
				if($hakemusDTO->PaatosDTO->Kasittelija==$_SESSION["kayttaja_id"]) $lausuntopyynto_sallittu = true;
			}
			
			if($_SESSION["kayttaja_rooli"]=="rooli_paattava" || $_SESSION["kayttaja_rooli"]=="rooli_eettisen_puheenjohtaja"){ 
				for($i=0; $i < sizeof($hakemusDTO->PaatosDTO->PaattajatDTO); $i++){ 
					if($hakemusDTO->PaatosDTO->PaattajatDTO[$i]->KayttajaDTO->ID==$_SESSION["kayttaja_id"]) $lausuntopyynto_sallittu = true;
				}
			}
			
		}
		
		if(isset($vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"])) $Tutkimuksen_viranomaisen_hakemuksetDTO = $vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"];
		
	} 
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

if(!isset($_POST['merkitse_lausunto_luetuksi'])) {	
	include './ui/views/viranomainen_hakemus_lausunto_view.php';
}

?>