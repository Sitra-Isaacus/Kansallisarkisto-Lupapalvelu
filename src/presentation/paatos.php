<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Päätös
 *
 * Created: 20.6.2017
 */

include_once '_fmas_ui.php';

if(kayttaja_on_kirjautunut()){
	if( isset($_GET['hakemus_id']) || isset($_POST['hakemus_id']) ) {

		// Muuttujien alustus
		$kayt_id = $_SESSION["kayttaja_id"];
		$hakemus_id = null;
		$sivu = "paatos_oletus";

		if(isset($_GET["hakemus_id"])) $hakemus_id = htmlspecialchars($_GET['hakemus_id']); if(isset($_POST['hakemus_id'])) $hakemus_id = htmlspecialchars($_POST['hakemus_id']);
		if(isset($_GET['sivu'])) $sivu = htmlspecialchars($_GET['sivu']); if(isset($_POST['sivu'])) $sivu = htmlspecialchars($_POST['sivu']);	 

	} else {
		header("location: viranomainen_saapuneet_hakemukset.php");
		die();
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}

if(isset($_GET["lahetys_epaonnistui"])) $huomio_punainen = poista_erikoismerkit($_GET["lahetys_epaonnistui"]);
if(isset($_GET["fail"])) $huomio_punainen = poista_erikoismerkit($_GET["fail"]);
if(isset($_GET["success"])) $huomio_vihrea = poista_erikoismerkit($_GET["success"]);

try {
	if ($api = createSoapClient()) {

		if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);
		if(isset($_GET)) $_GET = poista_erikoismerkit($_GET);

		// Tallennetaan liitetiedosto
		if(isset($_POST['lisaa_liite_asiakirja']) && isset($_POST['paatos_id'])) {
			if(file_exists($_FILES["lisaa_liite"]["tmp_name"])){
						
				$tiedosto = file_get_contents($_FILES["lisaa_liite"]["tmp_name"]);
				$tiedosto_encoded = base64_encode($tiedosto);

				// Check file size
				if ($_FILES["lisaa_liite"]["size"] > MAKSIMI_LIITETIEDOSTON_KOKO) {
					$huomio_punainen = "Tiedosto on liian suuri.";
				} else {
						
					$liitteen_nimi = $_POST["liitteen_nimi"];
							
					if(is_null($liitteen_nimi) || $liitteen_nimi=="") $liitteen_nimi = LIITE; // Oletusnimi jos nimeä ei ole määritelty
						
					$tiedostonTallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_paatoksen_liitetiedosto", array("liitteen_nimi"=>$liitteen_nimi, "name"=>$_FILES["lisaa_liite"]["name"], "paatos_id"=>poista_erikoismerkit($_POST["paatos_id"]), "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id, "tiedosto"=>$tiedosto_encoded));

					if(isset($tiedostonTallennus["Liitetiedosto_tallennettu"]) && $tiedostonTallennus["Liitetiedosto_tallennettu"]){
								
						$huomio_vihrea = LIITE_TALLENNETTU;
						header("location: paatos.php?sivu=paatos_oletus&success=" . $huomio_vihrea . "&hakemus_id=" . $hakemus_id . "");								
						die();							
								
					} else {
								
						$huomio_punainen = $tiedostonTallennus["Liitetiedoston_tallennus_info"];
						header("location: paatos.php?sivu=paatos_oletus&fail=" . $huomio_punainen . "&hakemus_id=" . $hakemus_id . "");								
						die();
								
					}
						
				}
				
			}
		}		
		
		if (isset($_GET['poista_liite']) && isset($_GET['paatos_id']) && isset($_GET['liite_id'])) {

			$poisto = suorita_logiikkakerroksen_funktio($api, "poista_paatoksen_liitetiedosto", array("pohja_rooli"=>$_SESSION["kayttaja_rooli"], "liite_id"=>$_GET["liite_id"], "paatos_id"=>$_GET['paatos_id'], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));

			if(isset($poisto["Liitetiedosto_poistettu"]) && $poisto["Liitetiedosto_poistettu"]){
				
				$huomio_vihrea = LIITE_POISTETTU;
				header("location: paatos.php?sivu=paatos_oletus&success=" . $huomio_vihrea . "&hakemus_id=" . $hakemus_id . "");								
				die();	
				
			} else {
				
				$huomio_punainen = LIITE_POIST_FAIL;
				header("location: paatos.php?sivu=paatos_oletus&fail=" . $huomio_punainen . "&hakemus_id=" . $hakemus_id . "");								
				die();	
				
			}

		}

		if (isset($_POST['tallennuskohde']) && isset($_POST['tallennuskohde_id'])){ // Tallennetaan lomake

			$responseToJSONarray = array();

			$tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_paatos_lomake", array("sivu"=>$sivu, "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "hakemus_id"=>$hakemus_id, "data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"], "tallennettavat_tiedot"=>$sivu));

			$paatosDTO = $tallennus["PaatosDTO"];

			if(isset($paatosDTO->Lomakkeen_sivutDTO["paatos_oletus"]->OsiotDTO_taulu)) $responseToJSONarray["osiotDTO"] = $paatosDTO->Lomakkeen_sivutDTO["paatos_oletus"]->OsiotDTO_taulu;
			if(isset($paatosDTO->Lomakkeen_sivutDTO->Lomakkeen_sivutDTO)) $responseToJSONarray["nakymatDTO"] = $paatosDTO->Lomakkeen_sivutDTO->Lomakkeen_sivutDTO;
			if(isset($tallennus["Paatos_tallennettu"])) $responseToJSONarray["tallennusOnnistui"] = true;
			 
			echo json_encode($responseToJSONarray);

		} else if(isset($_POST['laheta_hyvaksymispyynto']) || isset($_POST['laheta_lausunto_tiedoksi']) || isset($_POST['palauta_paatos_kasiteltavaksi']) || isset($_POST['allekirjoita_paatos']) || isset($_POST['peruuta_paatos']) || isset($_POST['laheta_paatos_hyvaksyttavaksi'])){

			if(isset($_POST["laheta_lausunto_tiedoksi"])){
				
				if(!isset($_POST["paatos_eettinen"])) $huomio_punainen = "Pakollisia tietoja puuttuu: Päätös";																	
				if(!isset($_POST["poytakirjaotteet_kpl"]) || $_POST["poytakirjaotteet_kpl"] == 0) $huomio_punainen = "Lisää pöytäkirjanote.";																																		
				if(isset($_POST["paatos_eettinen"]) && $_POST["paatos_eettinen"]=="paat_tila_ehd_hyvaksytty" && !isset($_POST["ehdollinen_paatos"])) $huomio_punainen = "Pakollisia tietoja puuttuu: Täydennyspyyntö";	
																		
			}
			
			if(isset($_POST['laheta_paatos_hyvaksyttavaksi'])){

				if(!isset($_POST["paatos"])) $huomio_punainen = VALITSE_PAATOS;
			
				if(isset($_POST["paatos"]) && $_POST["paatos"]=="paat_tila_hyvaksytty" && (!isset($_POST["kayttolupa"]) || !isset($_POST["lupa_voimassa_pvm"]))){					
					if(!isset($_POST["kayttolupa"])) $huomio_punainen = VAL_HENK_KL_MYON;
					if(!isset($_POST["lupa_voimassa_pvm"])) $huomio_punainen = TAYTA_LUV_VOIM;										
				}

				if(!isset($_POST["paattajat"])) $huomio_punainen = VAL_HENK_PH;
									
			}
			
			if(isset($_POST['laheta_hyvaksymispyynto'])){ 
			
				if(!isset($_POST["paatos_eettinen"])) $huomio_punainen = "Pakollisia tietoja puuttuu: Päätös";																		
				if(!isset($_POST["pjt"])) $huomio_punainen = VAL_HENK_PH;
															
			}

			if(!isset($huomio_punainen)){
				
				$tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_paatos", array("kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "data"=>$_POST,"hakemus_id"=>$hakemus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));

				if(isset($tallennus["Paatos_tallennettu"]) && $tallennus["Paatos_tallennettu"]){
					if(isset($_POST["laheta_lausunto_tiedoksi"])) $huomio_vihrea = LAUS_ON_LAH;
					if(isset($_POST['laheta_paatos_hyvaksyttavaksi'])) $huomio_vihrea = PAAT_LAH_HYV;
					if(isset($_POST['allekirjoita_paatos'])) $huomio_vihrea = PAAT_ALLEKIRJOITETTU;
					if(isset($_POST['laheta_hyvaksymispyynto'])) $huomio_vihrea = "Hyväksymispyyntö lähetetty puheenjohtajalle";

				} else {
					if(isset($_POST['laheta_paatos_hyvaksyttavaksi'])) $huomio_punainen = PAAT_LAH_HYV_FAIL;
					if(isset($_POST['allekirjoita_paatos'])) $huomio_punainen = PAAT_ALLEKIRJOITETTU_FAIL;
				}
				
				if(isset($huomio_vihrea)){
					header("location: paatos.php?sivu=paatos_oletus&success=" . $huomio_vihrea . "&hakemus_id=" . $hakemus_id . "");								
					die();						
				}
				
				if($huomio_punainen){
					header("location: paatos.php?sivu=paatos_oletus&fail=" . $huomio_punainen . "&hakemus_id=" . $hakemus_id . "");								
					die();					
				}
				
			}
			
			if(isset($huomio_punainen)){
				header("location: paatos.php?lahetys_epaonnistui=" . $huomio_punainen . "&sivu=paatos_oletus&hakemus_id=" . $hakemus_id . "");
				die();
			} else {
				header("location: paatos.php?sivu=paatos_oletus&hakemus_id=" . $hakemus_id . "");
				die();
			} 
						
		} else {

			$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_paatos", array("hakemus_id"=>$hakemus_id, "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "sivu"=>$sivu, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));

			$paatosDTO = $vastaus["PaatosDTO"];
			$hakemusDTO = $paatosDTO->HakemusDTO;
			$hakemusversioDTO = $paatosDTO->HakemusDTO->HakemusversioDTO;

			$tutkimus_id = $paatosDTO->HakemusDTO->HakemusversioDTO->TutkimusDTO->ID;
			$hakemusversio_id = $paatosDTO->HakemusDTO->HakemusversioDTO->ID;
			$hakemus_id = $paatosDTO->HakemusDTO->ID;

			$sitoumuksetDTO = $vastaus["SitoumuksetDTO"];
			$lomakkeetDTO_Paatos = $vastaus["LomakkeetDTO_Paatos"];
			$luvan_kohteetDTO = $vastaus["Luvan_kohteetDTO"];
			$viranomaisten_roolitDTO_Paattajat = $vastaus["Viranomaisten_roolitDTO_Paattajat"];
			
			$rak_kl_paatos_valittu = false;
			$vap_kl_paatos_valittu = false;
			$eett_paatos_valittu = false;
			
			if($_SESSION["kayttaja_rooli"]=="rooli_eettisensihteeri" || $_SESSION["kayttaja_rooli"]=="rooli_eettisen_puheenjohtaja"){
				$eett_paatos_valittu = true;
			} else {
				if($paatosDTO->LomakeDTO->ID==42) $rak_kl_paatos_valittu = true;
				if($paatosDTO->LomakeDTO->ID==44) $vap_kl_paatos_valittu = true;				
			}
						
			if(isset($vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"])) $Tutkimuksen_viranomaisen_hakemuksetDTO = $vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"];

			if(is_null($sivu)){ // Oletusarvoisesti näytetään ensimmäinen järjestyksessä oleva sivu

				$i = 0;

				foreach($paatosDTO->Lomakkeen_sivutDTO as $sivun_tunniste => $ls){
					if($i==0) $sivu = $sivun_tunniste;
					$i++;
				}

				header("location: paatos.php?hakemus_id=" . $hakemus_id . "&sivu=" . $sivu);
				die();

			}

			$lomakkeen_muokkaus_sallittu = false;
			$paatos_allekirjoitettu = false;
			$kayttaja_allekirjoittanut_paatoksen = false;

			//if($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" && ($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_kas" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_val")){

				if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_kas" && ($_SESSION["kayttaja_rooli"]=="rooli_eettisensihteeri" || $_SESSION["kayttaja_rooli"]=="rooli_kasitteleva")){
					if($paatosDTO->Kasittelija==$_SESSION["kayttaja_id"]) $lomakkeen_muokkaus_sallittu = true;
				}
				
				//if($paatosDTO->Kasittelija==$_SESSION['Istunto']->Kayttaja->id && $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_val" && $_SESSION['Istunto']->Kayttaja->Valittu_rooli->roolin_koodi=="rooli_eettisensihteeri") $lomakkeen_muokkaus_sallittu = true;

				//if($_SESSION['Istunto']->Kayttaja->Valittu_rooli->roolin_koodi=="rooli_paattava" && $paatosDTO->HakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val"){
					for($i=0; $i < sizeof($paatosDTO->PaattajatDTO); $i++){

						if($paatosDTO->PaattajatDTO[$i]->Paatos_allekirjoitettu){

							$paatos_allekirjoitettu = true;

							if($paatosDTO->PaattajatDTO[$i]->KayttajaDTO->ID==$_SESSION["kayttaja_id"]) $kayttaja_allekirjoittanut_paatoksen = true;

							if(($_SESSION["kayttaja_rooli"]=="rooli_paattava" || $_SESSION["kayttaja_rooli"]=="rooli_eettisen_puheenjohtaja") && $paatosDTO->HakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val" && $paatosDTO->PaattajatDTO[$i]->KayttajaDTO->ID==$_SESSION["kayttaja_id"]){
								$lomakkeen_muokkaus_sallittu = false;
							}

						}

						//if($_SESSION['Istunto']->Kayttaja->Valittu_rooli->roolin_koodi=="rooli_paattava" && $paatosDTO->HakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val" && $paatosDTO->PaattajatDTO[$i]->KayttajaDTO->ID==$_SESSION['Istunto']->Kayttaja->id && $paatosDTO->PaattajatDTO[$i]->Paatos_allekirjoitettu==0) $lomakkeen_muokkaus_sallittu = true;

					}
				//}

			//}

		}

	}
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

if (!isset($_POST['tallennuskohde']) && !isset($_POST['tallennuskohde_id'])){
	include 'helper_functions.php';
	include './ui/views/paatos_view.php';
}

?>