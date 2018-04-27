<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Hakemus
 *
 * Created: 29.3.2017
 */

include_once '_fmas_ui.php';

if(kayttaja_on_kirjautunut()){	
	if((isset($_GET['tutkimus_id']) && isset($_GET['hakemusversio_id'])) || (isset($_POST['tutkimus_id']) && isset($_POST['hakemusversio_id']) && isset($_POST['sivu']))) {

		// Muuttujien alustus
		$kayt_id = $_SESSION["kayttaja_id"];
		$hakija_kayttaja_id = null;
		$luo_hakija = null;
		$aineiston_indeksi = null;
		$hakemus_id = null;
		$sivu = null;
		$asiakirjahallinta_liitteetDTO = null;
		$sitoumuksetDTO = null;
		$jarjestelman_hakijan_roolitDTO = null;
		$kaikki_luvan_kohteet = null;
		$viranomaisten_luvan_kohteet = null;
		$taika_luvan_kohteet = null;
		$hakemuksen_viranomaiset = null;
		$nayta_poim_muuttujat_biopankit = true;

		if(isset($_GET['hakemusversio_id'])) $hakemusversio_id = htmlspecialchars($_GET['hakemusversio_id']); if(isset($_POST['hakemusversio_id'])) $hakemusversio_id = htmlspecialchars($_POST['hakemusversio_id']);
		if(isset($_GET['tutkimus_id'])) $tutkimus_id = htmlspecialchars($_GET['tutkimus_id']); if(isset($_POST['tutkimus_id'])) $tutkimus_id = htmlspecialchars($_POST['tutkimus_id']);
		if(isset($_GET["hakija_kayttaja_id"])) $hakija_kayttaja_id = htmlspecialchars($_GET['hakija_kayttaja_id']); if(isset($_POST['hakija_kayttaja_id'])) $hakija_kayttaja_id = htmlspecialchars($_POST['hakija_kayttaja_id']); 
		if(isset($_GET["luo_hakija"])) $luo_hakija = htmlspecialchars($_GET['luo_hakija']);
		if(isset($_GET["aineiston_indeksi"])) $aineiston_indeksi = htmlspecialchars($_GET["aineiston_indeksi"]);
		if(isset($_GET["laheta_hakemus"])) $laheta_hakemus = htmlspecialchars($_GET["laheta_hakemus"]); 
		if(isset($_GET["hakemus_id"])) $hakemus_id = htmlspecialchars($_GET['hakemus_id']);
		if(isset($_GET['sivu'])) $sivu = htmlspecialchars($_GET['sivu']); if(isset($_POST['sivu'])) $sivu = htmlspecialchars($_POST['sivu']);	 

	} else {
		header("location: index.php");
		die();
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}

if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);
if(isset($_GET)) $_GET = poista_erikoismerkit($_GET);

if(isset($_GET["hakemus_luotu"])) $huomio_vihrea = UUSI_HAKEMUS_LUOTU;
if(isset($_GET["tarkista_email"])) $huomio_punainen = VIRH_EMAIL;
if(isset($_GET["hakemuksen_lahetys_info"])) $huomio_punainen = $_GET["hakemuksen_lahetys_info"];
	
try {
	if ($api = createSoapClient()) {
				
		// Lähetetään liitetiedosto
		if(isset($_POST['lisaa_liite_asiakirja'])) { 
			if(file_exists($_FILES["lisaa_liite"]["tmp_name"])){
							
				$tiedosto = file_get_contents($_FILES["lisaa_liite"]["tmp_name"]);
				$tiedosto_encoded = base64_encode($tiedosto);

				// Check file size
				if ($_FILES["lisaa_liite"]["size"] > MAKSIMI_LIITETIEDOSTON_KOKO) {
					$huomio_punainen = "Tiedosto on liian suuri.";
				} else {
							
					$tiedostonTallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_hakemusversioon_liitetiedosto", array("name"=>$_FILES["lisaa_liite"]["name"], "hakemusversio_id"=>$hakemusversio_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id, "tiedosto"=>$tiedosto_encoded, "liitteen_koodi"=>$_POST['liitteen_koodi']));

					if(isset($tiedostonTallennus["Liitetiedosto_tallennettu"]) && $tiedostonTallennus["Liitetiedosto_tallennettu"]){
						$huomio_vihrea = LIITE_TALLENNETTU;
					} else {
						$huomio_punainen = $tiedostonTallennus["Liitetiedoston_tallennus_info"];
					}			
							
				}
					
			}
		}		
						
		if(!is_null($hakija_kayttaja_id) && isset($_POST["poista_hakija"])) { 
		
			$hakijan_poisto = suorita_logiikkakerroksen_funktio($api, "poista_hakija_tutkimusryhmasta", array("poistettavan_kayttaja_id"=>$hakija_kayttaja_id,"pohja_rooli"=>$_SESSION["kayttaja_rooli"], "hakemusversio_id"=>$hakemusversio_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
		
			$hakija_kayttaja_id = null;
			$luo_hakija = null;
		
			if($hakijan_poisto["Hakija_poistettu"]){
				$huomio_vihrea = HAKIJA_POISTETTU;
			} else {
				$huomio_punainen = HAKIJAN_POISTO_EPAONN;
			} 
		
		}

		if(isset($_POST["laheta_sahkopostikutsu"])) {
			if(isset($_POST["roolit"]) && !empty($_POST["roolit"]) && isset($_POST["oppiarvo"]) && !empty($_POST["oppiarvo"]) && isset($_POST["organisaatio"]) && !empty($_POST["organisaatio"]) && isset($_POST["sahkoposti"]) && !empty($_POST["sahkoposti"]) && isset($_POST["sukunimi"]) && !empty($_POST["sukunimi"]) && isset($_POST["etunimi"]) && !empty($_POST["etunimi"])){
				
				if (!filter_var($_POST["sahkoposti"], FILTER_VALIDATE_EMAIL) || $_SESSION["kayttaja_email"]==$_POST["sahkoposti"]) {									  
				  header("location: hakemus.php?tarkista_email=1&tutkimus_id=" . $tutkimus_id . "&hakemusversio_id=" . $hakemusversio_id . "&sivu=" . $sivu);
				  die();				  
				}
				
				$kutsu = suorita_logiikkakerroksen_funktio($api, "luo_hakija_ja_laheta_sahkopostikutsu", array("data"=>$_POST, "pohja_rooli"=>$_SESSION["kayttaja_rooli"], "hakemusversio_id"=>$hakemusversio_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"], "kayt_kieli"=>$_SESSION["kayttaja_kieli"]));
				
				if(isset($kutsu["sahkopostikutsu_lahetetty"]) && $kutsu["sahkopostikutsu_lahetetty"]) $huomio_vihrea = KUTSU_LAHETETTY;
								
			} else {
				$huomio_punainen = KUTSU_EPAONNISTUI;
			}
		}	
	
		// Poistetaan liitetiedosto
		if (isset($_GET['poista_liite'])) {

			$poisto = suorita_logiikkakerroksen_funktio($api, "poista_hakemusversion_liitetiedosto", array("pohja_rooli"=>$_SESSION["kayttaja_rooli"], "poistettava_liite"=>$_GET['poista_liite'], "liite_id"=>$_GET["liite_id"], "hakemusversio_id"=>$hakemusversio_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));

			if(isset($poisto["Liitetiedosto_poistettu"]) && $poisto["Liitetiedosto_poistettu"]){
				$huomio_vihrea = LIITE_POISTETTU;
			} else {
				$huomio_punainen = LIITE_POIST_FAIL;
			}
			
		}
		
		// Lisätään liitteen avaaminen lokiin
		//if (isset($_POST['merkitse_liite_luetuksi'])) {
		//	suorita_logiikkakerroksen_funktio($api, "kirjaa_lokiin", array("pohja_rooli"=>$_SESSION['pohja_rooli'],  "liite_id"=>$_POST["avattu_liite_id"], "toiminto"=>"liite_avattu", "hakemusversio_id"=>$_SESSION['hakemusversio_id'], "kayt_id"=>$kayt_id));
		//}

		if (isset($_POST['tallennuskohde']) && isset($_POST['tallennuskohde_id'])){ // Tallennetaan hakemus

			$responseToJSONarray = array();

			if($sivu=="hakemus_aineisto"){

				$tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_hakemus", array("sivu"=>$sivu, "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "tutkimus_id"=>$tutkimus_id, "haetun_aineiston_indeksi"=>$aineiston_indeksi, "data"=>$_POST, "hakemusversio_id"=>$hakemusversio_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"], "tallennettavat_tiedot"=>$sivu));

				if(isset($tallennus["HakemusversioDTO"]->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO)){
					
					$haetut_luvan_kohteetDTO = $tallennus["HakemusversioDTO"]->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO;
					$nayta_biopankkinaytteiden_kasittely = false;
					
					foreach ($haetut_luvan_kohteetDTO as $joukko => $haettu_luvan_kohdeDTO) {
						for($i=0; $i < sizeof($haettu_luvan_kohdeDTO); $i++){
							if(isset($haettu_luvan_kohdeDTO[$i]->Luvan_kohdeDTO->Luvan_kohteen_tyyppi) && $haettu_luvan_kohdeDTO[$i]->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Biopankki"){
								if($haettu_luvan_kohdeDTO[$i]->Luvan_kohdeDTO->ID!=500){
									$nayta_biopankkinaytteiden_kasittely = true;
									break 2;						
								}
							} 
						}
					}
					
					$responseToJSONarray["nayta_biopankkinaytteiden_kasittely"] = $nayta_biopankkinaytteiden_kasittely;
					
				}
					
				if(isset($tallennus["HakemusversioDTO"]->Haettu_aineistoDTO->Poimitaanko_verrokeille_samat)) $responseToJSONarray["Poimitaanko_verrokeille_samat"] = $tallennus["HakemusversioDTO"]->Haettu_aineistoDTO->Poimitaanko_verrokeille_samat;
				if(isset($tallennus["HakemusversioDTO"]->Haettu_aineistoDTO->Poimitaanko_viitehenkiloille_samat)) $responseToJSONarray["Poimitaanko_viitehenkiloille_samat"] = $tallennus["HakemusversioDTO"]->Haettu_aineistoDTO->Poimitaanko_viitehenkiloille_samat;				
				if(isset($tallennus["Uusi_tallennettu_tieto"]["Haettu_luvan_kohdeDTO"])) $responseToJSONarray["uusi_lisatty_haettu_luvan_kohde_ID"] = $tallennus["Uusi_tallennettu_tieto"]["Haettu_luvan_kohdeDTO"]->ID; 																 
				if(isset($tallennus["Uusi_alustettu_tieto"]["Haettu_luvan_kohdeDTO"])) $responseToJSONarray["uusi_alustettu_haettu_luvan_kohde_ID"] = $tallennus["Uusi_alustettu_tieto"]["Haettu_luvan_kohdeDTO"]->ID;				
				if(isset($tallennus["Luvan_kohde_poistettu"])) $responseToJSONarray["luvan_kohde_poistettu"] = true;									 
								
			} else {
				$tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_hakemus", array("sivu"=>$sivu, "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "tutkimus_id"=>$tutkimus_id, "data"=>$_POST, "hakemusversio_id"=>$hakemusversio_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"], "tallennettavat_tiedot"=>$sivu));
			}
			
			if(isset($tallennus["HakemusversioDTO"]->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu)){
				$responseToJSONarray["osiotDTO"] = $tallennus["HakemusversioDTO"]->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu;
			}
			
			if(isset($tallennus["HakemusversioDTO"]->Lomakkeen_sivutDTO)){
				
				$responseToJSONarray["nakymatDTO"] = $tallennus["HakemusversioDTO"]->Lomakkeen_sivutDTO;				
				$responseToJSONarray["nakymatDTO"][$sivu]->OsiotDTO_taulu = null;
				$responseToJSONarray["nakymatDTO"][$sivu]->OsiotDTO_puu = null;
								
			}
			
			if(isset($tallennus["Hakemusversio_tallennettu"])) $responseToJSONarray["tallennusOnnistui"] = true;

			if(isset($tallennus["Tallennettu_sitoumus"]["SitoumusDTO"])){
				$tallennus["Tallennettu_sitoumus"]["SitoumusDTO"]->Lisayspvm = muotoilepvm($tallennus["Tallennettu_sitoumus"]["SitoumusDTO"]->Lisayspvm, 'fi');
				$responseToJSONarray["tallennettu_sitoumusDTO"] = $tallennus["Tallennettu_sitoumus"]["SitoumusDTO"];
			}
			
			if(isset($tallennus["Uusi_tutkimuksen_organisaatio_id"])) $responseToJSONarray["Uusi_tutkimuksen_organisaatio_id"] = $tallennus["Uusi_tutkimuksen_organisaatio_id"];
			if(isset($tallennus["Organisaatio_poistettu"])) $responseToJSONarray["Organisaatio_poistettu"] = true;
			
			echo json_encode($responseToJSONarray);
									
		} else if(isset($laheta_hakemus) && $laheta_hakemus==1){

			$muutoshakemus_viranomaiset = array();
		
			if(isset($_POST["muutoshakemus_viranomaiset"]))	$muutoshakemus_viranomaiset = $_POST["muutoshakemus_viranomaiset"];
			
			$vastaus = suorita_logiikkakerroksen_funktio($api, "laheta_hakemus", array("muutoshakemus_viranomaiset"=>$muutoshakemus_viranomaiset, "tutkimus_id"=>$tutkimus_id, "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"],"sivu"=>$sivu, "hakemusversio_id"=>$hakemusversio_id, "token"=>$_SESSION["kayttaja_token"], "kayt_kieli"=>$_SESSION["kayttaja_kieli"], "kayt_id"=>$_SESSION["kayttaja_id"]));

			if(isset($vastaus["Hakemus_lahetetty"]) && $vastaus["Hakemus_lahetetty"] && isset($vastaus["Hakemuksen_lahetys_info"])){
				$huomio_vihrea = $vastaus["Hakemuksen_lahetys_info"];
				header("Location: index.php?hakemus_lahetetty=" . $huomio_vihrea);
				die();				
			} else {
				$huomio_punainen = $vastaus["Hakemuksen_lahetys_info"];
				header("location: hakemus.php?hakemuksen_lahetys_info=" . $vastaus["Hakemuksen_lahetys_info"] . "&tutkimus_id=" . $tutkimus_id . "&hakemusversio_id=" . $hakemusversio_id . "&sivu=" . $sivu);
				die();
			}
			
		} else { // Haetaan hakemuksen tiedot
							
			$generoi_pdf = false;
			
			if(isset($_GET["generoi_pdf"]) && $_GET["generoi_pdf"]==1) $generoi_pdf = true;
							 			
			if(!isset($_GET["lataa_cachesta"]) || $_GET["lataa_cachesta"]==0){
				if($sivu=="hakemus_aineisto"){

					$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_hakemusversio", array("generoi_pdf"=>$generoi_pdf, "hakemus_id"=>$hakemus_id, "tutkimus_id"=>$tutkimus_id, "haetun_aineiston_indeksi"=>$aineiston_indeksi,"kayttajan_rooli"=>$_SESSION["kayttaja_rooli"],"sivu"=>$sivu, "hakemusversio_id"=>$hakemusversio_id, "token"=>$_SESSION["kayttaja_token"], "kayt_kieli"=>$_SESSION["kayttaja_kieli"], "kayt_id"=>$_SESSION["kayttaja_id"]));
					
					if(isset($vastaus["Luvan_kohteetDTO"]["Kaikki"])) $kaikki_luvan_kohteet = $vastaus["Luvan_kohteetDTO"]["Kaikki"];
					if(isset($vastaus["Luvan_kohteetDTO"]["Viranomaiset"])) $viranomaisten_luvan_kohteet = $vastaus["Luvan_kohteetDTO"]["Viranomaiset"];
					if(isset($vastaus["Luvan_kohteetDTO"]["Taika"])) $taika_luvan_kohteet = $vastaus["Luvan_kohteetDTO"]["Taika"];					
					
				} else {
					$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_hakemusversio", array("generoi_pdf"=>$generoi_pdf, "hakemus_id"=>$hakemus_id, "tutkimus_id"=>$tutkimus_id, "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"],"sivu"=>$sivu, "hakemusversio_id"=>$hakemusversio_id, "token"=>$_SESSION["kayttaja_token"], "kayt_kieli"=>$_SESSION["kayttaja_kieli"], "kayt_id"=>$_SESSION["kayttaja_id"]));
				}
			}
							
			$hakemusversioDTO = $vastaus["HakemusversioDTO"];
			$hakemusversioDTO->Lukittu_toiselle_kayttajalle = hakemusversio_lukittu_toiselle_kayttajalle($vastaus["HakemusversioDTO"], $_SESSION["kayttaja_id"]);
			
			if(is_null($sivu)){ // Oletusarvoisesti näytetään ensimmäinen järjestyksessä oleva sivu

				$i = 0;

				foreach($hakemusversioDTO->Lomakkeen_sivutDTO as $sivun_tunniste => $nakyma_hakemusversio){
					if($i==0) $sivu = $sivun_tunniste;
					$i++;
				}
								
				if(!is_null($hakemus_id)){
					header("location: hakemus.php?tutkimus_id=" . $tutkimus_id . "&hakemusversio_id=" . $hakemusversio_id . "&sivu=" . $sivu . "&hakemus_id=" . $hakemus_id);
				} else {
					header("location: hakemus.php?tutkimus_id=" . $tutkimus_id . "&hakemusversio_id=" . $hakemusversio_id . "&sivu=" . $sivu);
				}
				
				die();

			}

			if($sivu=="hakemus_aineisto" && isset($hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu[982]->Osio_sisaltoDTO->Sisalto_boolean) && !is_null($hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu[982]->Osio_sisaltoDTO->Sisalto_boolean)) $nayta_poim_muuttujat_biopankit = false;
						
			if($sivu=="hakemus_liitteet") $asiakirjahallinta_liitteetDTO = $vastaus["HakemusversioDTO"]->Asiakirjahallinta_liitteetDTO;
										
			if($sivu=="hakemus_esikatsele_ja_laheta"){				
				$uusimmat_hakemuksetDTO = (isset($vastaus["Uusimmat_hakemuksetDTO"]) ? $vastaus["Uusimmat_hakemuksetDTO"] : array());
				$hakemuksen_viranomaiset = (isset($vastaus["hakemuksen_viranomaiset"]) ? $vastaus["hakemuksen_viranomaiset"] : array());			
			}
			
			if(isset($vastaus["HakemusDTO"])){
				$hakemusDTO = $vastaus["HakemusDTO"];
				$hakemusversioDTO->HakemusDTO = $hakemusDTO;
			} 	
			
			if(isset($vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"])) $Tutkimuksen_viranomaisen_hakemuksetDTO = $vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"];
 			if(isset($vastaus["SitoumuksetDTO"])) $sitoumuksetDTO = $vastaus["SitoumuksetDTO"];
			if(isset($vastaus["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"])) $jarjestelman_hakijan_roolitDTO = $vastaus["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"];
			
		}
	}
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

if (!isset($_POST['tallennuskohde']) && !isset($_POST['tallennuskohde_id'])){
	include 'helper_functions.php';
	include './ui/views/hakemus_view.php';
}
?>