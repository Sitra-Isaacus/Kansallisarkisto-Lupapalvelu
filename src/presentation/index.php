<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: main page
 *
 * Created: 2.10.2015
 */

include_once '_fmas_ui.php';  

$_SESSION["kayttaja_rooli"] = "rooli_hakija";
if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);
if(isset($_GET)) $_GET = poista_erikoismerkit($_GET);

// Ilmoitustekstit
if(isset($_GET["tiedosto_liian_suuri"])) $huomio_punainen = "Täydennysasiakirja on liian suuri";
if(isset($_GET["hakemus_poistettu"])) $huomio_vihrea = HAK_POISTETTU;
if(isset($_GET["aint_lahetetty"])) $huomio_vihrea = AINT_LAHETETTY;
if(isset($_GET["hakemus_peruttu"])) $huomio_vihrea = $_GET["hakemus_peruttu"];
if(isset($_GET["hakemuksen_peruminen_epaonnistui"])) $huomio_punainen = HAK_PER_FAIL;
if(isset($_GET["hakemus_lahetetty"])) $huomio_vihrea = $_GET["hakemus_lahetetty"];

if(kayttaja_on_kirjautunut()){	

	session_write_close();

	try {
		if ($api = createSoapClient()) {
						
			// Toimitetaan täydennysasiakirjat
			if(isset($_POST["laheta_tayd_ask"]) && isset($_POST["taydennys_paatos_id"])){

				$taydennysasiakirjat = array();
			
				if(isset($_FILES["taydennysasiakirjat"]) && !empty($_FILES["taydennysasiakirjat"])){				
					for($i=0; $i < sizeof($_FILES["taydennysasiakirjat"]["tmp_name"]); $i++){
						if($_FILES["taydennysasiakirjat"]["tmp_name"][$i]!=""){
							
							$taydennysasiakirja = array();						
							$taydennysasiakirja["file"] = base64_encode(file_get_contents($_FILES["taydennysasiakirjat"]["tmp_name"][$i]));
							$taydennysasiakirja["name"] =  $_FILES["taydennysasiakirjat"]["name"][$i];
							
							if ($_FILES["taydennysasiakirjat"]["size"][$i] > MAKSIMI_LIITETIEDOSTON_KOKO) { 		
								header("location: index.php?tiedosto_liian_suuri=1");
								die();						
							}
							
							array_push($taydennysasiakirjat, $taydennysasiakirja);
							
						}
					}

					if(!empty($taydennysasiakirjat)){
						
						$tayd_ask_lah = suorita_logiikkakerroksen_funktio($api, "laheta_taydennysasiakirjat", array("tiedostot"=>$taydennysasiakirjat, "token"=>$_SESSION["kayttaja_token"], "fk_paatos"=>$_POST["taydennys_paatos_id"], "kayt_id"=>$_SESSION["kayttaja_id"]));
					
						if(isset($tayd_ask_lah["Liitetiedostot_tallennettu"])){
							
							$tallennettuja = 0;
							
							for($i=0; $i < sizeof($tayd_ask_lah["Liitetiedostot_tallennettu"]); $i++){
								if($tayd_ask_lah["Liitetiedostot_tallennettu"][$i]["tallennettu"]) $tallennettuja++;
							}
							
							if($tallennettuja==sizeof($tayd_ask_lah["Liitetiedostot_tallennettu"])) $huomio_vihrea = "Täydennysasiakirjat toimitettu";
							
						} else {
							$huomio_punainen = "Täydennysasiakirjojen lähettäminen epäonnistui";
						}						
						
					} else {
						$huomio_punainen = "Valitse ainakin yksi tiedosto";
					}				
				} 								
			}		
			
			// Kuitataan aineisto
			if(isset($_POST["kuittaa_aineisto"]) && isset($_POST["aineistotilaus_id"])){
				
				$aineiston_kuittaus = suorita_logiikkakerroksen_funktio($api, "kuittaa_aineisto", array("data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "fk_aineistotilaus"=>$_POST["aineistotilaus_id"], "kayt_id"=>$_SESSION["kayttaja_id"]));
			
				if(isset($aineiston_kuittaus["Aineisto_kuitattu"]) && $aineiston_kuittaus["Aineisto_kuitattu"]){
					$huomio_vihrea = AIN_KUITATTU;
				} else {
					$huomio_punainen = KUIT_EPAONNISTUI;
				}			
				
			}
			
			// Lähetetään reklamaatiotilaus
			if(isset($_GET["laheta_reklamaatiotilaus"]) && is_numeric($_GET["laheta_reklamaatiotilaus"])){ 

				$reklamaatiotilaus = suorita_logiikkakerroksen_funktio($api, "laheta_reklamaatiotilaus", array("token"=>$_SESSION["kayttaja_token"], "fk_aineistotilaus"=>$_GET["laheta_reklamaatiotilaus"], "kayt_id"=>$_SESSION["kayttaja_id"]));
			
				if(isset($reklamaatiotilaus["Reklamaatio_lahetetty"]) && $reklamaatiotilaus["Reklamaatio_lahetetty"]){
					$huomio_vihrea = REKL_LAH;
				} else {
					$huomio_punainen = REKL_LAH_FAIL;
				}
			
			}
			
			// Perutaan aineistotilaus
			if(isset($_GET["peru_aineistopyynto"]) && is_numeric($_GET["peru_aineistopyynto"])){ 
			
				$aineistopyynnon_peruminen = suorita_logiikkakerroksen_funktio($api, "peru_aineistopyynto", array("token"=>$_SESSION["kayttaja_token"], "fk_aineistotilaus"=>$_GET["peru_aineistopyynto"], "kayt_id"=>$_SESSION["kayttaja_id"]));
			
				if(isset($aineistopyynnon_peruminen["Aineistopyynto_peruttu"]) && $aineistopyynnon_peruminen["Aineistopyynto_peruttu"]){
					$huomio_vihrea = AINT_PERU_LAHETETTY;
				} else {
					$huomio_punainen = AINT_PERU_EPAONNISTUI;
				}
			
			}
			
			// Alustetaan uusi tutkimus
			if(isset($_POST['luo_hakemus'])){
							
				$lomake_id = key($_POST['luo_hakemus']);
									
				$vastaus = suorita_logiikkakerroksen_funktio($api, "luo_hakemus", array("lomake_id"=>$lomake_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
				
				if(isset($vastaus["HakemusversioDTO"]->ID)){
					if($vastaus["HakemusversioDTO"]->LomakeDTO->ID==1){
						header("Location: hakemus.php?sivu=hakemus_perustiedot&hakemus_luotu=1&tutkimus_id=" . $vastaus["HakemusversioDTO"]->TutkimusDTO->ID . "&hakemusversio_id=" . $vastaus["HakemusversioDTO"]->ID);
						die();
					} else if($vastaus["HakemusversioDTO"]->LomakeDTO->ID==27){
						header("Location: hakemus.php?sivu=42&hakemus_luotu=1&tutkimus_id=" . $vastaus["HakemusversioDTO"]->TutkimusDTO->ID . "&hakemusversio_id=" . $vastaus["HakemusversioDTO"]->ID);
						die();
					} else {
						$huomio_vihrea = UUSI_HAKEMUS_LUOTU;						
					}										 
					
				} else {
					$huomio_punainen = HAKEMUKSEN_LUOM_EPAONNISTUI;
				}
				
			}

			// Poistetaan hakemus
			if(isset($_GET['poista_hakemus'])){
				
				$vastaus = suorita_logiikkakerroksen_funktio($api, "poista_hakemus", array("token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"], "hakemusversio_id"=>$_GET['poista_hakemus']));
				
				if(isset($vastaus["Hakemus_poistettu"]) && $vastaus["Hakemus_poistettu"]==true){ 
				
					// Lisätään kirjautuminen lokiin
					suorita_logiikkakerroksen_funktio($api, "kirjaa_lokiin", array("hakemusversio_id"=>$_GET['poista_hakemus'], "kayt_id"=>$_SESSION["kayttaja_id"], "toiminto"=>"hak_poistettu", "pohja_rooli"=>$_SESSION["kayttaja_rooli"]));						
					
					header("location: index.php?hakemus_poistettu=1");												
					die();
				
				} else {
					$huomio_punainen = HAK_EI_POIST;
				}
										
			}

			// Perutaan hakemus			
			if(isset($_GET['peruuta_hakemus'])){
				
				$vastaus = suorita_logiikkakerroksen_funktio($api, "peruuta_hakemus", array("token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"], "hakemusversio_id"=>$_GET['peruuta_hakemus']));
				
				if(!is_null($vastaus["Peruttu_info"])){
													
					// Ilmoita peruminen
					$viesti = "";
					for($l=0; $l < sizeof($vastaus["Peruttu_info"]["Hakemusversio"]); $l++){
						for($j=0; $j < sizeof($vastaus["Peruttu_info"]["Hakemusversio"][$l]["Hakemus"]); $j++){
							$viesti = $viesti . "<br>" . $vastaus["Peruttu_info"]["Hakemusversio"][$l]["Hakemus"][$j];
						}
					}
					
					$huomio_vihrea = $viesti;			

				} else {						
					header("location: index.php?hakemuksen_peruminen_epaonnistui=1");								
					die();	
				}
										
			}			
					
			// Muutoshakemuksen luonti 
			if (isset($_POST['tee_muutoshakemus'])) {
				if(isset($_POST['muutoshakemuksen_tyyppi'])){
					
					if($_POST['muutoshakemuksen_tyyppi']=="muutoshakemus_olemassa_olevaan"){					
						if(isset($_POST['valittu_hakemus']) && !is_null($_POST['valittu_hakemus']) && $_POST['valittu_hakemus']!=""){
							$vastaus = suorita_logiikkakerroksen_funktio($api, "luo_hakemus", array("hakemusversio_id"=>$_POST['valittu_hakemus'], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
						} else {
							$huomio_punainen = VALITSE_HAKEMUS;
						}										
					}
					
					if($_POST['muutoshakemuksen_tyyppi']=="muutoshakemus_aiempaan"){
						if(isset($_POST['aiempi_diaarinumero']) && !is_null($_POST['aiempi_diaarinumero']) && $_POST['aiempi_diaarinumero']!=""){
							$vastaus = suorita_logiikkakerroksen_funktio($api, "luo_hakemus", array("aiempi_tutkimusnro"=>$_POST['aiempi_diaarinumero'], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
						} else {
							$huomio_punainen = "Määritä diaarinumero aiempaan paperihakemukseen";
						}
					}
					
					if(isset($vastaus["HakemusversioDTO"]->ID)){	
						header("Location: hakemus.php?hakemus_luotu=1&tutkimus_id=" . $vastaus["HakemusversioDTO"]->TutkimusDTO->ID . "&hakemusversio_id=" . $vastaus["HakemusversioDTO"]->ID);
						die();		
					} else {
						if(!isset($huomio_punainen)) $huomio_punainen = HAKEMUKSEN_LUOM_EPAONNISTUI;
					}	
					
				} else {
					$huomio_punainen = "Valitse muutoshakemuksen tyyppi";
				}				
			}	
			
			if(isset($_GET['tee_taydennyshakemus']) && $_GET['tee_taydennyshakemus']){
				
				$vastaus = suorita_logiikkakerroksen_funktio($api, "luo_hakemus", array("taydennetty_hakemus"=>1, "hakemusversio_id"=>$_GET['hakemusversio_id'], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
				
				if(isset($vastaus["HakemusversioDTO"]->ID)){	
					header("Location: hakemus.php?hakemus_luotu=1&tutkimus_id=" . $vastaus["HakemusversioDTO"]->TutkimusDTO->ID . "&hakemusversio_id=" . $vastaus["HakemusversioDTO"]->ID);
					die();		
				} else {
					$huomio_punainen = HAKEMUKSEN_LUOM_EPAONNISTUI;
				}			
				
			}
			
			if (!isset($_POST['jarjesta_keskeneraiset'])) {
				
				$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_hakemukset_tutkijalle", array("kayt_kieli"=>$_SESSION["kayttaja_kieli"], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
								
				$tutkimukset_lahetetyt = $vastaus["Lahetetyt_tutkimukset"]["TutkimuksetDTO"];
				$tutkimukset_paatetyt = $vastaus["Paatetyt_tutkimukset"]["TutkimuksetDTO"];
				$paatokset_aineistotilaukset = $vastaus["Aineistotilaukset"]["PaatoksetDTO"];
				$lomake_hakemuksetDTO = $vastaus["Lomake_hakemuksetDTO"];
				$kayttajaDTO = $vastaus["Istunto"]["Kayttaja"];
				
				$uusimpien_hakemusten_idt = (isset($vastaus["Uusimpien_hakemusten_idt"]) ? $vastaus["Uusimpien_hakemusten_idt"] : array());
				if(isset($_GET["kirjauduttu_sisaan"])) $huomio_sininen = $_SESSION["kayttaja_nimi"] . ": " . OLET_KIRJ;				
				
			}
			
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}

	if (!isset($_POST['jarjesta_keskeneraiset'])) {
		include './ui/views/index_view.php';
	}

} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}	

?>