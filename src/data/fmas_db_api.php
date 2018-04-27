<?php
/*
 * FMAS Käyttölupapalvelu
 * Database communication layer
 *
 * Created: 8.10.2015
*/

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
ini_set('default_socket_timeout', 600);
error_reporting(-1);

// Data transfer objektit
include_once 'dto/KayttolupaDTO.php';
include_once 'dto/AineistotilausDTO.php';
include_once 'dto/Aineistotilauksen_tilaDTO.php';
include_once 'dto/PaatosDTO.php';
include_once 'dto/HakemusDTO.php';
include_once 'dto/HakemusversioDTO.php';
include_once 'dto/ViestitDTO.php';
include_once 'dto/KayttajaDTO.php';
include_once 'dto/TutkimusDTO.php';
include_once 'dto/HakijaDTO.php';
include_once 'dto/Hakemuksen_tilaDTO.php';
include_once 'dto/Hakijan_rooliDTO.php';
include_once 'dto/KayttajaDTO.php';
include_once 'dto/Viranomaisen_rooliDTO.php';
include_once 'dto/Paakayttajan_rooliDTO.php';
include_once 'dto/SuojausDTO.php';
include_once 'dto/KayttolokiDTO.php';
include_once 'dto/Haettu_aineistoDTO.php';
include_once 'dto/Haettu_muuttujaDTO.php';
include_once 'dto/SitoumusDTO.php';
include_once 'dto/KoodistotDTO.php';
include_once 'dto/LiiteDTO.php';
include_once 'dto/LausuntopyyntoDTO.php';
include_once 'dto/LausuntoDTO.php';
include_once 'dto/Paatoksen_tilaDTO.php';
include_once 'dto/Kohdejoukon_tilausDTO.php';
include_once 'dto/Paatetty_aineistoDTO.php';
include_once 'dto/Paatetty_luvan_kohdeDTO.php';
include_once 'dto/Paatetty_muuttujaDTO.php';
include_once 'dto/OsioDTO.php';
include_once 'dto/Osio_sisaltoDTO.php';
include_once 'dto/Osio_lauseDTO.php';
include_once 'dto/Osio_saantoDTO.php';
include_once 'dto/Haettu_luvan_kohdeDTO.php';
include_once 'dto/Luvan_kohdeDTO.php';
include_once 'dto/Jarjestelman_hakemustyypitDTO.php';
include_once 'dto/Hakemusversion_tilaDTO.php';
include_once 'dto/Jarjestelman_hakijan_roolitDTO.php';
include_once 'dto/LomakeDTO.php';
include_once 'dto/Lomake_hakemusDTO.php';
include_once 'dto/Lomake_paatosDTO.php';
include_once 'dto/Lomakkeen_sivutDTO.php';
include_once 'dto/Asiakirjahallinta_liiteDTO.php';
include_once 'dto/Asiakirjahallinta_saantoDTO.php';
include_once 'dto/Hakemusversion_liiteDTO.php';
include_once 'dto/PaattajaDTO.php';
include_once 'dto/Osallistuva_organisaatioDTO.php';
include_once 'dto/Lausunnon_liiteDTO.php';
include_once 'dto/Paatoksen_liiteDTO.php';
include_once 'dto/MuuttujaDTO.php';
include_once 'dto/AsiaDTO.php';

// Data access objektit
include_once 'KayttolupaDAO.php';
include_once 'AineistotilausDAO.php';
include_once 'Aineistotilauksen_tilaDAO.php';
include_once 'PaatosDAO.php';
include_once 'HakemusDAO.php';
include_once 'HakemusversioDAO.php';
include_once 'ViestitDAO.php';
include_once 'Hakemuksen_tilaDAO.php';
include_once 'TutkimusDAO.php';
include_once 'Hakijan_rooliDAO.php';
include_once 'HakijaDAO.php';
include_once 'Viranomaisen_rooliDAO.php';
include_once 'KayttajaDAO.php';
include_once 'SuojausDAO.php';
include_once 'Paakayttajan_rooliDAO.php';
include_once 'KayttolokiDAO.php';
include_once 'Haettu_aineistoDAO.php';
include_once 'SitoumusDAO.php';
include_once 'LiiteDAO.php';
include_once 'Haettu_muuttujaDAO.php';
include_once 'KoodistotDAO.php';
include_once 'LausuntopyyntoDAO.php';
include_once 'LausuntoDAO.php';
include_once 'Lausunnon_lukeneetDAO.php';
include_once 'Paatoksen_tilaDAO.php';
include_once 'Paatetty_aineistoDAO.php';
include_once 'Paatetty_luvan_kohdeDAO.php';
include_once 'Paatetty_muuttujaDAO.php';
include_once 'OsioDAO.php';
include_once 'Osio_sisaltoDAO.php';
include_once 'Osio_lauseDAO.php';
include_once 'Osio_saantoDAO.php';
include_once 'Haettu_luvan_kohdeDAO.php';
include_once 'Luvan_kohdeDAO.php';
include_once 'Hakemusversion_tilaDAO.php';
include_once 'Jarjestelman_hakijan_roolitDAO.php';
include_once 'Lomakkeen_sivutDAO.php';
include_once 'LomakeDAO.php';
include_once 'Lomake_hakemusDAO.php';
include_once 'Lomake_paatosDAO.php';
include_once 'Asiakirjahallinta_liiteDAO.php';
include_once 'Asiakirjahallinta_saantoDAO.php';
include_once 'Hakemusversion_liiteDAO.php';
include_once 'PaattajaDAO.php';
include_once 'Osallistuva_organisaatioDAO.php';
include_once 'Lausunnon_liiteDAO.php';
include_once 'Paatoksen_liiteDAO.php';
include_once 'MuuttujaDAO.php';
include_once 'AsiaDAO.php';

include_once '_config.php';
include_once 'helper_functions.php';
require 'vendor/autoload.php';

use WSDL\WSDLCreator;
use WSDL\XML\Styles\RpcEncoded;

// set up WSDL generator
ini_set("soap.wsdl_cache_enabled", 0);

//$current_url = "http://" . $_SERVER['SERVER_NAME'] . strtok($_SERVER["REQUEST_URI"],'?');
//ini_set("soap.wsdl_cache_enabled", 0);

if (isset($_GET['wsdl'])) {
    $wsdl = new WSDLCreator('fmas_db_api', $current_url);
    $wsdl->setNamespace(WSDL_XML_NAMESPACE)->setBindingStyle(new RpcEncoded());;
    $wsdl->renderWSDL();
    exit;
}

//xml namespace
$options=array('uri'=>WSDL_XML_NAMESPACE, 'encoding'=>'UTF-8');

//create a new SOAP server
$server = new SoapServer("{$current_url}?wsdl", $options);
$server->setClass('fmas_db_api');
$server->handle();

class fmas_db_api {

	/**
	 * @WebMethod
	 * @desc Haetaan julkiset tilastotiedot käyttöluvista
	 * @param string[] $syoteparametrit {  }
	 * @return string[] $dto { 
		object { string[] $tilastohavainnot }, 
		object { string[] $tutkimusten_julkiset_kuvaukset } 
		}
	 */
	public function hae_lupapalvelun_tilastotiedot($syoteparametrit) {

		$dto = array();
		$dto["tilastohavainnot"] = array();
		$dto["tutkimusten_julkiset_kuvaukset"] = array();
	
		try {
			if ($db = $this->_connectToDb()) {

				$db->beginTransaction();

				$hakemusversioDAO = new HakemusversioDAO($db);
				$hakemusversion_tilaDAO = new Hakemusversion_tilaDAO($db);
				$hakemusDAO = new HakemusDAO($db);
				$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
				$asiaDAO = new AsiaDAO($db);
				$osioDAO = new OsioDAO($db);
				$osio_sisaltoDAO = new Osio_sisaltoDAO($db);
				$tutkimusDAO = new TutkimusDAO($db);
				$osallistuva_organisaatioDAO = new Osallistuva_organisaatioDAO($db);
				$haettu_aineistoDAO = new Haettu_aineistoDAO($db);
				
				$osiotDTO_tieteenalat = $osioDAO->hae_luokan_osiot("tieteenalat");				
				
				// Kerätään tilastointihavainnot viranomaishakemuksista				
				$hakemuksetDTO = $hakemusDAO->hae_kaikki_hakemukset();
								
				for($i=0; $i < sizeof($hakemuksetDTO); $i++){							
					if(!is_null($hakemuksetDTO[$i]->Viranomaisen_koodi)){
											
						$tilastohavainto = array();
						$hakemuksetDTO[$i]->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemuksetDTO[$i]->ID);
						$hakemuksetDTO[$i]->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemuksetDTO[$i]->HakemusversioDTO->ID);
						$hakemuksetDTO[$i]->AsiaDTO = $asiaDAO->hae_asia($hakemuksetDTO[$i]->AsiaDTO->ID);
								
						// Haetaan pvm, jolloin hakemus on jätetty / päätös tehty
						$hakemuksen_tilatDTO = $hakemuksen_tilaDAO->hae_hakemuksen_hakemuksen_tilat($hakemuksetDTO[$i]->ID);
						$pvm_hakemus_jatetty = null;
						$pvm_paatos_tehty = null;
								
						for($j=0; $j < sizeof($hakemuksen_tilatDTO); $j++){
							if(isset($hakemuksen_tilatDTO[$j]->Hakemuksen_tilan_koodi)){
								if($hakemuksen_tilatDTO[$j]->Hakemuksen_tilan_koodi=="hak_lah") $pvm_hakemus_jatetty = $hakemuksen_tilatDTO[$j]->Lisayspvm;
								if($hakemuksen_tilatDTO[$j]->Hakemuksen_tilan_koodi=="hak_paat") $pvm_paatos_tehty = $hakemuksen_tilatDTO[$j]->Lisayspvm;	
							} 																			 
						}
																											
						if(verifyDate($pvm_hakemus_jatetty) && verifyDate($pvm_paatos_tehty)){
							
							$hakijaDTO_vast_johtaja = hae_hakemusversion_vastuullinen_johtaja($db, $hakemuksetDTO[$i]->HakemusversioDTO->ID);
							
							$tilastohavainto["diaarinumero"] = $hakemuksetDTO[$i]->AsiaDTO->Diaarinumero;
							$tilastohavainto["pvm_hakemus_jatetty"] = $pvm_hakemus_jatetty;
							$tilastohavainto["lupaviranomainen"] = $hakemuksetDTO[$i]->Viranomaisen_koodi;
							$tilastohavainto["pvm_paatos_tehty"] = $pvm_paatos_tehty;
							$tilastohavainto["hakemuksen_tyyppi"] = $hakemuksetDTO[$i]->HakemusversioDTO->Hakemuksen_tyyppi;
							$tilastohavainto["lupaprosessin_kesto"] = laske_pvm_erotus($pvm_hakemus_jatetty, $pvm_paatos_tehty);
							$tilastohavainto["vast_johtajan_maa"] = $hakijaDTO_vast_johtaja->Maa;
							$tilastohavainto["tieteenala"] = hae_hakemusversion_tieteenala($db, $hakemuksetDTO[$i]->HakemusversioDTO, $osiotDTO_tieteenalat);
							$tilastohavainto["onko_pyydetty_taydennysta"] = ($hakemuksetDTO[$i]->HakemusversioDTO->Hakemuksen_tyyppi=="tayd_hak" ? true : false);
									
							array_push($dto["tilastohavainnot"], $tilastohavainto);
							
						}
						
					}							
				}
				
				// Kerätään tilastointihavainnot hankkeista/tutkimuksista sekä haetaan tutkimusten julkiset kuvaukset
				$tutkimuksetDTO = $tutkimusDAO->hae_tutkimukset();
				
				for($i=0; $i < sizeof($tutkimuksetDTO); $i++){
					
					$tutkimuksetDTO[$i]->HakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_kaikki_hakemusversiot($tutkimuksetDTO[$i]->ID);
										
					for($j=0; $j < sizeof($tutkimuksetDTO[$i]->HakemusversiotDTO); $j++){
						
						$hakemusversion_tilaDTO = $hakemusversion_tilaDAO->hae_hakemusversion_uusin_tila($tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->ID);
						
						if(($tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->Hakemuksen_tyyppi=="uus_hak" || $tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->Hakemuksen_tyyppi=="muutos_hak") && $hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_lah"){
																																		
							$tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->HakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset($tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->ID);																						
							$viimeinen_pvm_paatos_tehty = hae_hakemusten_viimeisin_paatospvm($db, $tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->HakemuksetDTO);
							
							if(verifyDate($hakemusversion_tilaDTO->Lisayspvm) && verifyDate($viimeinen_pvm_paatos_tehty)){
								
								$hakijaDTO_vast_johtaja = hae_hakemusversion_vastuullinen_johtaja($db, $tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->ID);
								$hakemusversion_tieteenala = hae_hakemusversion_tieteenala($db, $tutkimuksetDTO[$i]->HakemusversiotDTO[$j], $osiotDTO_tieteenalat);
								
								// Kerätään tilastointihavainto käyttöluvasta															
								$tilastohavainto = array();														
								$tilastohavainto["pvm_hakemus_jatetty"] = $hakemusversion_tilaDTO->Lisayspvm;								
								$tilastohavainto["lupaviranomainen"] = "LPAL";
								$tilastohavainto["pvm_paatos_tehty"] = $viimeinen_pvm_paatos_tehty;
								$tilastohavainto["hakemuksen_tyyppi"] = $tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->Hakemuksen_tyyppi;
								$tilastohavainto["lupaprosessin_kesto"] = laske_pvm_erotus($hakemusversion_tilaDTO->Lisayspvm, $viimeinen_pvm_paatos_tehty);
								$tilastohavainto["vast_johtajan_maa"] = $hakijaDTO_vast_johtaja->Maa;
								$tilastohavainto["tieteenala"] = $hakemusversion_tieteenala;
								$tilastohavainto["lupaviranomaisten_lkm"] = laske_hakemusten_lupaviranomaisten_lkm($tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->HakemuksetDTO);
								$tilastohavainto["onko_pyydetty_taydennysta"] = false;
								
								array_push($dto["tilastohavainnot"], $tilastohavainto);
								
								// Kerätään tutkimuksen julkiset kuvaukset
								$osio_sisaltoDTO_tutk_julkinen_kuvaus = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->ID, "FK_Hakemusversio", 842);								
								$osallistuvat_organisaatiotDTO = $osallistuva_organisaatioDAO->hae_hakemusversion_organisaatiot($tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->ID);
								
								$tutkimuksen_julk_kuvaus = array();
								$tutkimuksen_julk_kuvaus["projektin_nimi"] = $tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->Tutkimuksen_nimi;
								$tutkimuksen_julk_kuvaus["vast_johtajan_nimi"] = $hakijaDTO_vast_johtaja->Etunimi . " " . $hakijaDTO_vast_johtaja->Sukunimi;
								$tutkimuksen_julk_kuvaus["vast_johtajan_organisaatio"] = $hakijaDTO_vast_johtaja->Organisaatio;
								$tutkimuksen_julk_kuvaus["tieteenala"] = $hakemusversion_tieteenala;
								$tutkimuksen_julk_kuvaus["kayttoluvan_myontopaiva"] = $viimeinen_pvm_paatos_tehty;
								$tutkimuksen_julk_kuvaus["projektin_kesto"] = null; // todo
								$tutkimuksen_julk_kuvaus["julkinen_kuvaus_tutkimuksesta_suomeksi"] = $osio_sisaltoDTO_tutk_julkinen_kuvaus->Sisalto_text;
								
								// Rekisterinpitäjäorganisaatiot
								$tutkimuksen_julk_kuvaus["rekisterinpitajaorganisaatiot"] = array();
								foreach ($osallistuvat_organisaatiotDTO as $key => $osallistuva_organisaatioDTO) {
									array_push($tutkimuksen_julk_kuvaus["rekisterinpitajaorganisaatiot"], $osallistuva_organisaatioDTO->Nimi);
								}
								
								// Tietolähteet
								$tutkimuksen_julk_kuvaus["tietolahteet"] = hae_hakemusversion_tietolahteet($db, $tutkimuksetDTO[$i]->HakemusversiotDTO[$j]);
								
								array_push($dto["tutkimusten_julkiset_kuvaukset"], $tutkimuksen_julk_kuvaus);
								
							}	
							
						}
						
						if($tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->Hakemuksen_tyyppi=="tayd_hak" && $hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_lah"){ 
						
							$tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->HakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset($tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->ID);																				
							$viimeinen_pvm_paatos_tehty = hae_hakemusten_viimeisin_paatospvm($db, $tutkimuksetDTO[$i]->HakemusversiotDTO[$j]->HakemuksetDTO);
							$tilastohavainto = array_pop($dto["tilastohavainnot"]);	
							
							if(verifyDate($viimeinen_pvm_paatos_tehty)){ // Täydennyshakemuksen tapauksessa päivitetään lupaprosessin kestoa	
																															
								$tilastohavainto["pvm_paatos_tehty"] = $viimeinen_pvm_paatos_tehty;
								$tilastohavainto["lupaprosessin_kesto"] = laske_pvm_erotus($tilastohavainto["pvm_hakemus_jatetty"], $viimeinen_pvm_paatos_tehty);
								$tilastohavainto["onko_pyydetty_taydennysta"] = true;
							
								array_push($dto["tilastohavainnot"], $tilastohavainto);
								
							}	
						
						}
						
					}
					
				}
																														
				$db->commit();
				$db = null;

			}
		} catch (PDOException $ex) {
			echo($ex->getMessage());
		}
				
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Metodi lisää tai päivittää tietokantaan luvan kohteet ja muuttujat (noudettu metadatajärjestelmästä)
	 * @param string[] $syoteparametrit { 
			object { 
				string[] $luvan_kohteet 
			} 
		}
	 * @return string[] $dto { 
			object { int $luvan_kohteita_lisatty }, 
			object { int $luvan_kohteita_paivitetty }, 
			object { int $muuttujia_lisatty }, 
			object { int $muuttujia_paivitetty } 
		}
	 */
	public function paivita_aineistot($syoteparametrit) {

		$dto = array();
		$dto["luvan_kohteita_lisatty"] = 0;
		$dto["luvan_kohteita_paivitetty"] = 0;
		$dto["muuttujia_lisatty"] = 0;
		$dto["muuttujia_paivitetty"] = 0;		
		$toimivalta_organisaatiot = array("v_ETK", "v_Fimea", "v_Kela", "v_STM", "v_THL", "v_TTL", "v_Valvira", "v_VRK", "v_VSSHP");

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$luvan_kohteet = $parametrit["luvan_kohteet"];

			if(!is_null($luvan_kohteet)){
				try {
					if ($db = $this->_connectToDb()) {

						$db->beginTransaction();

						$luvan_kohdeDAO = new Luvan_kohdeDAO($db);
						$muuttujaDAO = new MuuttujaDAO($db);

						$luvan_kohteita_lisatty = 0;
						$luvan_kohteita_paivitetty = 0;
						$muuttujia_lisatty = 0;
						$muuttujia_paivitetty = 0;

						for($i=0; $i < sizeof($luvan_kohteet); $i++){
							if(isset($luvan_kohteet[$i]["Identifier"]) && isset($luvan_kohteet[$i]["Viranomaisen_koodi"])){
								
								// Tarkistetaan löytyykö rekisteriä tietokannasta 
								$luvan_kohdeDTO = $luvan_kohdeDAO->hae_luvan_kohde_identifierilla($luvan_kohteet[$i]["Identifier"]);

								if(in_array($luvan_kohteet[$i]["Viranomaisen_koodi"], $toimivalta_organisaatiot)){
									$Kuuluu_lupaviranomaisen_toimivaltaan = 1;
								} else {
									$Kuuluu_lupaviranomaisen_toimivaltaan = 0;
								}
								
								if(!isset($luvan_kohdeDTO->ID)){ 
									if($luvan_kohdeDAO->lisaa_luvan_kohde($luvan_kohteet[$i]["Luvan_kohteen_nimi"], $luvan_kohteet[$i]["Luvan_kohteen_tyyppi"], $luvan_kohteet[$i]["Viranomaisen_koodi"], $Kuuluu_lupaviranomaisen_toimivaltaan, null, $luvan_kohteet[$i]["Selite"], $luvan_kohteet[$i]["Identifier"], $luvan_kohteet[$i]["Viiteajankohta_alku"], $luvan_kohteet[$i]["Viiteajankohta_loppu"])){
										$luvan_kohteita_lisatty++;
									}
								} else {
									if($luvan_kohdeDAO->paivita_luvan_kohde($luvan_kohdeDTO->ID, $luvan_kohteet[$i]["Luvan_kohteen_nimi"], $luvan_kohteet[$i]["Selite"], $luvan_kohteet[$i]["Viiteajankohta_alku"], $luvan_kohteet[$i]["Viiteajankohta_loppu"])){
										$luvan_kohteita_paivitetty++;
									}
								}
								// Lisätään/päivitetään muuttujat
								if(isset($luvan_kohteet[$i]["Muuttujat"]) && !empty($luvan_kohteet[$i]["Muuttujat"])){
									for($j=0; $j < sizeof($luvan_kohteet[$i]["Muuttujat"]); $j++){

										$muuttujaDTO = $muuttujaDAO->hae_muuttuja_tunnisteilla($luvan_kohteet[$i]["Identifier"], $luvan_kohteet[$i]["Muuttujat"][$j]["Tunnus"]);

										$m_nimi = (isset($luvan_kohteet[$i]["Muuttujat"][$j]["Nimi"]) ? $luvan_kohteet[$i]["Muuttujat"][$j]["Nimi"] : null);
										$m_kuvaus = (isset($luvan_kohteet[$i]["Muuttujat"][$j]["Kuvaus"]) ? $luvan_kohteet[$i]["Muuttujat"][$j]["Kuvaus"] : null);
										$m_mittayksikko = (isset($luvan_kohteet[$i]["Muuttujat"][$j]["Mittayksikko"]) ? $luvan_kohteet[$i]["Muuttujat"][$j]["Mittayksikko"] : null);
										$m_luokitus = (isset($luvan_kohteet[$i]["Muuttujat"][$j]["Luokitus"]) ? $luvan_kohteet[$i]["Muuttujat"][$j]["Luokitus"] : null);
										
										if(!isset($muuttujaDTO->ID)){
											if($muuttujaDAO->lisaa_muuttuja($luvan_kohteet[$i]["Muuttujat"][$j]["Tunnus"], $luvan_kohteet[$i]["Identifier"], $m_nimi, $m_kuvaus, $m_mittayksikko, $m_luokitus)){
												$muuttujia_lisatty++;
											}
										} else {
											if($muuttujaDAO->paivita_muuttuja($muuttujaDTO->ID, $m_nimi, $m_kuvaus, $m_mittayksikko, $m_luokitus)){
												$muuttujia_paivitetty++;
											}
										}
										
									}
								}
							}
						}
						
						$dto["luvan_kohteita_lisatty"] = $luvan_kohteita_lisatty;
						$dto["luvan_kohteita_paivitetty"] = $luvan_kohteita_paivitetty;
						$dto["muuttujia_lisatty"] = $muuttujia_lisatty;
						$dto["muuttujia_paivitetty"] = $muuttujia_paivitetty;

						$db->commit();
						$db = null;

					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 *
	 * @WebMethod
	 * @desc Rekisteröidään lupajärjestelmään uusi käyttäjä
	 * @param string[] $syoteparametrit { 
			object { string $sahkopostiosoite }, 
			object { string $salasana }, 
			object { string $sukunimi }, 
			object { string $etunimi }, 
			object { string $puhelinnro }, 
			object { string $sahkopostivarmenne }, 
			object { string $asiointikieli }, 
			object { string $syntymaaika } 	 
		}
	 * @return string[] $dto { 
			object { boolean $kayttaja_luotu }, 
			object { string[] $Uusi_rekisteroity_kayttaja } 
		}
	 */
	public function rekisteroi_kayttaja($syoteparametrit) {

		$dto = array();
		$dto["kayttaja_luotu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$sahkopostiosoite = $parametrit["sahkopostiosoite"];
			$salasana = $parametrit["salasana"];
			$sukunimi = $parametrit["sukunimi"];
			$etunimi = $parametrit["etunimi"];
			$puhelinnro = $parametrit["puhelinnro"];
			$sahkopostivarmenne = $parametrit["sahkopostivarmenne"];
			$asiointikieli = (isset($parametrit["asiointikieli"]) ? $parametrit["asiointikieli"] : "fi"); // Oletuskieli on suomi
			$syntymaaika = $parametrit["syntymaaika"];

			if(!is_null($syntymaaika)){
				$syntymaaika =  date('Y-m-d', strtotime(str_replace('-', '/', $syntymaaika)));
			}

			if(!is_null($sahkopostiosoite) && !is_null($salasana) && !is_null($sukunimi) && !is_null($etunimi) && !is_null($sahkopostivarmenne)){
				try {
					if ($db = $this->_connectToDb()) {

						$db->beginTransaction();

						$kayttajaDAO = new KayttajaDAO($db);

						if(!$kayttajaDAO->sahkopostiosoite_loytyy_tietokannasta($sahkopostiosoite)){ // Tarkista löytyykö sähköpostiosoitetta tietokannasta
							$kayttajaDTO = $kayttajaDAO->luo_kayttaja($sukunimi, $etunimi, $sahkopostiosoite, crypt($salasana), $puhelinnro, $asiointikieli, $syntymaaika, $sahkopostivarmenne, 0);
						} else {
							
							$kayttajaDTO = $kayttajaDAO->hae_kayttaja_kayttajatunnuksella($sahkopostiosoite);

							// register user that has been invited - and already exists in the DB, but with no password
							if (!$kayttajaDTO->Salasana_hash && !$kayttajaDTO->Sahkopostivarmenne) {

								//debug_log("USER EXISTS BUT HAS NO PASS");
								$kayttajaDTO->Etunimi = $etunimi;
								$kayttajaDTO->Sukunimi = $sukunimi;
								$kayttajaDTO->Puhelinnumero = $puhelinnro;
								$kayttajaDTO->Kieli = $asiointikieli;
								$kayttajaDTO->Salasana_hash = crypt($salasana);
								$kayttajaDTO->Sahkopostivarmenne = $sahkopostivarmenne;
								$kayttajaDTO->Syntymaaika = $syntymaaika;
								$kayttajaDAO->register_existing_kayttaja($kayttajaDTO);
								
							}


						}

						if(!is_null($kayttajaDTO->ID)){
							$dto["Uusi_rekisteroity_kayttaja"]["KayttajaDTO"] = $kayttajaDTO;
						} else {
							$dto["rekisterointi_virheilmoitus"] = "Käyttäjän luominen epäonnistui.";
						}

						$db->commit();
						$db = null;

					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Vahvistetaan käyttäjän rekisteröinti lupajärjestelmään
	 * @param string[] $syoteparametrit { 
			object { string $sahkopostiosoite }, 
			object { string $varmenne } 
		}
	 * @return string[] $dto { 
		object{ boolean $kayttaja_varmennettu }, 
		object{ string[] $KayttajaDTO } 
		}
	 */
	public function varmenna_kayttaja($syoteparametrit) {

		$dto = array();
		$dto["kayttaja_varmennettu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$sahkopostiosoite = $parametrit["sahkopostiosoite"];
			$varmenne = $parametrit["varmenne"];

			if(!is_null($sahkopostiosoite) && !is_null($varmenne)){
				try {
					if ($db = $this->_connectToDb()) {

						$db->beginTransaction();

						$kayttajaDAO = new KayttajaDAO($db);

						$kayttajaDTO = $kayttajaDAO->hae_kayttaja_kayttajatunnuksella($sahkopostiosoite);

						if(isset($kayttajaDTO->Sahkopostivarmenne) && md5($sahkopostiosoite.$kayttajaDTO->Sahkopostivarmenne)==$varmenne){
							if($kayttajaDAO->varmenna_kayttaja($kayttajaDTO->ID)){
								$dto["kayttaja_varmennettu"] = true;
								$dto["KayttajaDTO"]["Varmennettu_kayttaja"] = $kayttajaDTO;
							}
						}
						
						$db->commit();
						$db = null;

					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Metodi liittää käyttäjän hakemuksen tutkimusryhmään sähköpostin ja varmenteen perusteella
	 * @param string[] $syoteparametrit { 
			object{ string $sahkopostiosoite }, 
			object{ string $kayttajan_liittamisen_varmenne } 
		}
	 * @return string[] $dto { 
			object{ boolean $kayttaja_liitetty_hakemukseen }, 
			object{ boolean $kayttaja_liitetty_hakemukseen_aiemmin }, 
			object{ object $HakemusversioDTO }, 
			object{ object $KayttajaDTO }, 
			object{ int $registration_needed } 
		}
	 */
	public function liita_kayttaja_hakemukseen($syoteparametrit) {

		$dto = array();
		$dto["kayttaja_liitetty_hakemukseen"] = false;
		$dto["kayttaja_liitetty_hakemukseen_aiemmin"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$sahkopostiosoite = $parametrit["sahkopostiosoite"];
			$hash = $parametrit["kayttajan_liittamisen_varmenne"];

			if(!is_null($sahkopostiosoite) && !is_null($hash)){
				try {
					if ($db = $this->_connectToDb()) {

						$db->beginTransaction();

						$kayttajaDAO = new KayttajaDAO($db);
						$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
						$hakemusversioDAO = new HakemusversioDAO($db);
						$hakijaDAO = new HakijaDAO($db);

						// get user by email
						$kayttajaDTO = $kayttajaDAO->hae_kayttaja_kayttajatunnuksella($sahkopostiosoite);

						// Haetaan käyttäjään liitetyt varmenteet
						$hakijatDTO = $hakijaDAO->hae_kayttajaan_ja_tunnukseen_liitetyt_hakijat($sahkopostiosoite, $kayttajaDTO->ID);

						for($i=0; $i < sizeof($hakijatDTO); $i++){
							if(isset($hakijatDTO[$i]->Varmenne) && md5($sahkopostiosoite.$hakijatDTO[$i]->Varmenne)==$hash){

								$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakijan_roolin_tiedot_hakijan_avaimella($hakijatDTO[$i]->ID);
								$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakijan_roolitDTO[0]->HakemusversioDTO->ID);
								$dto["HakemusversioDTO"] = $hakemusversioDTO;

								if($hakijatDTO[$i]->Jasen=="0000-00-00 00:00:00" || is_null($hakijatDTO[$i]->Jasen)){ 
									if($hakijaDAO->vahvista_hakijan_jasenyys($hakijatDTO[$i]->ID)){
										$dto["kayttaja_liitetty_hakemukseen"] = true;
									}
								} else {
									$dto["kayttaja_liitetty_hakemukseen_aiemmin"] = true;
								}
							}
						}

						$dto["KayttajaDTO"] = $kayttajaDTO;
						//debug_log($kayttajaDTO);
						
						if (
						 (!isset($kayttajaDTO->Salasana_hash) || !$kayttajaDTO->Salasana_hash) &&
						 (!isset($kayttajaDTO->Sahkopostivarmenne) || !$kayttajaDTO->Sahkopostivarmenne)
						) {
							$dto["registration_needed"] = 1;
						} else {
							$dto["registration_needed"] = 0;
						}

						$db->commit();
						$db = null;

					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Päivitetään käyttäjän tiedot järjestelmään
	 * @param string[] $syoteparametrit {						
			object{ int $kayt_id 	Käyttäjän ID }, 
			object{ string $token 	Käyttäjän token }, 
			object{ 
				string[] $data { 
					string $syntymaaika, 
					string $sukunimi, 
					string $etunimi, 
					string $asiointikieli, 
					string $puhelin 
				} 
			} 
		}
	 * @return string[] $dto { 
			object{ 
				boolean $kayttajan_tiedot_tallennettu 
			} 
		}
	 */
	public function tallenna_kayttajan_tiedot($syoteparametrit) {

		$dto = array();
		$dto["kayttajan_tiedot_tallennettu"] = false;

		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$token = $parametrit["token"];
			$synt_aika = null;

			if(isset($parametrit["data"]["syntymaaika"])) $synt_aika =  date('Y-m-d', strtotime(str_replace('-', '/', $parametrit["data"]["syntymaaika"])));
							
			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {

						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$kayttajaDAO = new KayttajaDAO($db);

							if($kayttajaDAO->paivita_kayttajan_tiedot($kayt_id, $parametrit["data"]["sukunimi"], $parametrit["data"]["etunimi"], $synt_aika, $parametrit["data"]["asiointikieli"], $parametrit["data"]["puhelin"])){
								$dto["kayttajan_tiedot_tallennettu"] = true;
							}
							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Käyttäjä $lahettaja lähettää viestin käyttäjälle $vastaanottaja, joka liittyy hakemukseen $hakemus_id
	 * @param string[] $syoteparametrit {
			object{ 
				string[] $data{
					int $vastaanottaja,
					int $laheta_lisatietopyynto,
					string $laheta_taydennyspyynto,
					string $vastaus,
					int $parent_id,
					string $viesti
				}  			
			},
			object{ int $kayt_id }, 
			object{ int $hakemus_id }, 
			object{ boolean $on_vastaus }, 
			object{ string $kayttajan_rooli }, 
			object{ string $taydennysasiakirjat }, 
			object{ string $token }
	 }
	 * @return string[] $dto {		
			object{ int $Lahetetty_viesti_ID },
			object{ boolean $Viesti_lahetetty },
			object{ boolean $Taydennyspyynto_lahetetty },
			object{ null|KayttajaDTO $KayttajaDTO_Vastaanottaja },
			object{ null|KayttajaDTO $KayttajaDTO_Vastaanottaja },
			object{ null|KayttajaDTO $KayttajaDTO_Lahettaja },
			object{ string[] $Istunto },
			object{ HakemusversioDTO $Taydennettava_hakemusversioDTO (optional) }
		}
	 */
	public function laheta_viesti($syoteparametrit) {

		$dto = array();
		$dto["Lahetetty_viesti_ID"] = null;
		$dto["Viesti_lahetetty"] = false;
		$dto["Taydennyspyynto_lahetetty"] = false;
		$dto["KayttajaDTO_Vastaanottaja"] = null;
		$dto["KayttajaDTO_Lahettaja"] = null;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$vastaanottaja = $parametrit["data"]["vastaanottaja"];
			$lahettaja = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];
			$on_vastaus = $parametrit["on_vastaus"];			
			$laheta_lisatietopyynto = false;
			$taydennettavaa_hakemukseen = 0;
			$kayttajan_rooli = null;
			$taydennysasiakirjat = null;

			if(isset($parametrit["kayttajan_rooli"])) $kayttajan_rooli = $parametrit["kayttajan_rooli"];
			if(isset($parametrit["data"]["laheta_lisatietopyynto"])) $laheta_lisatietopyynto = true;
			if(isset($parametrit["taydennysasiakirjat"])) $taydennysasiakirjat = $parametrit["taydennysasiakirjat"];
							
			if(!is_null($vastaanottaja) && !is_null($hakemus_id) && !is_null($lahettaja) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {

						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$lahettaja, "kayttajan_rooli"=>$kayttajan_rooli, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$viestitDAO = new ViestitDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							
							if(isset($parametrit["data"]["laheta_taydennyspyynto"]) && $parametrit["data"]["laheta_taydennyspyynto"]=="taydennettavaa_hakemukseen" && !$on_vastaus && isset($dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisroolin_koodi) && $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisroolin_koodi=="rooli_eettisensihteeri") $taydennettavaa_hakemukseen = 1;
							
							$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemus_id);
							$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);							
							
							if($laheta_lisatietopyynto){
																
								$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);

								// Vain käsittelijä ja päättäjä saavat pyytää lisätietoja
								if(isset($dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisroolin_koodi) && ($dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisroolin_koodi=="rooli_paattava" || $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisroolin_koodi=="rooli_kasitteleva")){

									// Tarkistetaan hakemuksen tila
									$hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemus_id);

									if($hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas" || $hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val"){

										// Muutetaan hakemuksen tila => hak_muuta
										$hakemuksen_tilaDAO->maarita_hakemuksen_tiloista_tamanhetkiset_pois($hakemus_id);
										$hakemuksen_tilaDAO->luo_hakemuksen_tila($hakemus_id, $lahettaja, "hak_muuta");
										$dto["Taydennyspyynto_lahetetty"] = true;
										$dto["Taydennettava_hakemusversioDTO"] = $hakemusDTO->HakemusversioDTO;

									}
								}
							}

							if($on_vastaus){ 
								if($viestitDTO = $viestitDAO->lisaa_viestiin_vastaus($hakemus_id, $lahettaja, $vastaanottaja, $parametrit["data"]["vastaus"], $parametrit["data"]["parent_id"])){
									$dto["Viesti_lahetetty"] = true;									
								}
							} else {
								if($Lahetetty_viesti_ID = $viestitDAO->laheta_viesti($hakemus_id, $lahettaja, $vastaanottaja, $parametrit["data"]["viesti"], $taydennettavaa_hakemukseen)){
									$dto["Viesti_lahetetty"] = true;
									$dto["Lahetetty_viesti_ID"] = $Lahetetty_viesti_ID;
								}
							}
														
							if(is_array($taydennysasiakirjat)){
								
								$liiteDAO = new LiiteDAO($db);
								$hakemusversion_liiteDAO = new Hakemusversion_liiteDAO($db);
								
								for($i=0; $i < sizeof($taydennysasiakirjat); $i++){
									
									$data=base64_decode($taydennysasiakirjat[$i]["file"]);
									$filetype = pathinfo($taydennysasiakirjat[$i]["name"],PATHINFO_EXTENSION);									
									
									if($filetype == "png" || $filetype == "pdf" || $filetype == "rtf" || $filetype == "doc" || $filetype == "docx" || $filetype == "xls" || $filetype == "xlsx" || $filetype == "wpd" || $filetype == "jpg" || $filetype == "txt") {
										
										if(filesize($data) > MAKSIMI_LIITETIEDOSTON_KOKO){ // Tiedoston koon tarkistus
											$dto["Liitetiedoston_tallennus_info"] = "Tiedosto on liian suuri.";
											return muodosta_dto($dto);
										}									
										
										if($fk_liite = $liiteDAO->lisaa_liitetiedosto($taydennysasiakirjat[$i]["name"], 53, $data, $filetype, "", 1, $lahettaja)){
											$hakemusversion_liiteDAO->lisaa_hakemusversion_liitetiedosto($hakemusDTO->HakemusversioDTO->ID, $fk_liite);	
										}
									}
									
								}
								
							}
							
							if($dto["Viesti_lahetetty"]){
								$dto["KayttajaDTO_Vastaanottaja"] = $kayttajaDAO->hae_kayttajan_tiedot($vastaanottaja);
								$dto["KayttajaDTO_Lahettaja"] = $kayttajaDAO->hae_kayttajan_tiedot($lahettaja);
							}
							
							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Tutkija lähettää aineistotilauksen
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ int $tutkimus_id }, 
			object{ string $token }, 
			object{
				string[] $data{
					int $fk_aineistotilaus,
					string $aineiston_muodostus_kuvaus (optional)
				}
			}
		}
	 * @return string[] $dto { object{ boolean $Aineistopyynto_lahetetty } }
	 */
	public function laheta_aineistopyynto($syoteparametrit) {

		$dto = array();
		$dto["Aineistopyynto_lahetetty"] = false;
		$aineiston_muodostus_kuvaus = null;

		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$tutkimus_id = $parametrit["tutkimus_id"];
			$fk_aineistotilaukset = $parametrit["data"]["fk_aineistotilaus"];
			if(isset($parametrit["data"]["aineiston_muodostus_kuvaus"])) $aineiston_muodostus_kuvaus = $parametrit["data"]["aineiston_muodostus_kuvaus"];

			if(!is_null($kayt_id) && !is_null($tutkimus_id) && !is_null($fk_aineistotilaukset) && !empty($fk_aineistotilaukset)){
				try {
					if ($db = $this->_connectToDb()) {

						if(kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_hakija", "kayt_id"=>$kayt_id, "tutkimus_id"=>$tutkimus_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$hakemusversioDAO = new HakemusversioDAO($db);
							$paatosDAO = new PaatosDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$aineistotilausDAO = new AineistotilausDAO($db);
							$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);

							for($i=0; $i < sizeof($fk_aineistotilaukset); $i++){
																							
								$aineistotilausDTO = $aineistotilausDAO->hae_aineistotilauksen_tiedot($fk_aineistotilaukset[$i]);
								
								// Haetaan hakemusversio ja tarkistetaan käyttöoikeudet
								$paatosDTO = $paatosDAO->hae_paatoksen_tiedot($aineistotilausDTO->PaatosDTO->ID);
								$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($paatosDTO->HakemusDTO->ID);
								$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
								
								if(hakemusversion_kayttajan_toiminto_sallittu($db, $kayt_id, $hakemusversioDTO, "lahetys")){
									$aineistotilausDAO->paivita_aineistotilauksen_tieto($aineistotilausDTO->ID, "Aineiston_tilaaja", $kayt_id, $kayt_id);
									if(!is_null($aineiston_muodostus_kuvaus)) $aineistotilausDAO->paivita_aineistotilauksen_tieto($aineistotilausDTO->ID, "Aineistonmuodostusprosessi_teksti", $aineiston_muodostus_kuvaus, $kayt_id);
									$aineistotilauksen_tilaDAO->lisaa_aineistotilaukseen_tila($aineistotilausDTO->ID, "aint_uusi", $kayt_id);									
								} 
								
							}

							if($db->commit()) $dto["Aineistopyynto_lahetetty"] = true;

							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Metodilla perutaan aineistotilaus
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ int $fk_aineistotilaus }, 
			object{ string $token } 			
		}
	 * @return string[] $dto { object{ boolean $Aineistopyynto_peruttu } }
	 */
	public function peru_aineistopyynto($syoteparametrit) {

		$dto = array();
		$dto["Aineistopyynto_peruttu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$fk_aineistotilaus = $parametrit["fk_aineistotilaus"];

			if(!is_null($fk_aineistotilaus) && !is_null($kayt_id)){
				try {
					if ($db = $this->_connectToDb()) {

						$db->beginTransaction();

						$paatosDAO = new PaatosDAO($db);
						$hakemusDAO = new HakemusDAO($db);
						$hakemusversioDAO = new HakemusversioDAO($db);
						$aineistotilausDAO = new AineistotilausDAO($db);					
				
						$aineistotilausDTO = $aineistotilausDAO->hae_aineistotilauksen_tiedot($fk_aineistotilaus);

						// Haetaan hakemusversio ja tarkistetaan käyttöoikeudet
						$paatosDTO = $paatosDAO->hae_paatoksen_tiedot($aineistotilausDTO->PaatosDTO->ID);
						$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($paatosDTO->HakemusDTO->ID);
						$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);					
					
						if(kayttajaAutentikoitu($db,array("hakemusversio_id"=>$hakemusversioDTO->ID, "token"=>$parametrit["token"], "kayttajan_rooli"=>"rooli_hakija", "kayt_id"=>$kayt_id))){																																			
							if(hakemusversion_kayttajan_toiminto_sallittu($db, $kayt_id, $hakemusversioDTO, "lahetys")){ 																																																			
								if(!isset($aineistotilausDTO->Aineistonmuodostaja) || is_null($aineistotilausDTO->Aineistonmuodostaja) || $aineistotilausDTO->Aineistonmuodostaja==0){

									$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);
									$aineistotilauksen_tilaDTO = $aineistotilauksen_tilaDAO->hae_tilan_koodi_aineistotilauksen_avaimella($fk_aineistotilaus);

									if($aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_uusi" || $aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_rekl"){

										// Aineistotilauksen tila -> keskeneräinen
										if($aineistotilauksen_tilaDAO->lisaa_aineistotilaukseen_tila($fk_aineistotilaus, "aint_keskenerainen", $kayt_id)) $dto["Aineistopyynto_peruttu"] = true;

									}
								}
							}							
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
						
						$db->commit();
						$db = null;						
						
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Tutkija lähettää reklamaatiotilauksen
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ int $fk_aineistotilaus }, 
			object{ string $token } 	
		}
	 * @return string[] $dto { object{ boolean $Reklamaatio_lahetetty } }
	 */
	public function laheta_reklamaatiotilaus($syoteparametrit) {

		$dto = array();
		$dto["Reklamaatio_lahetetty"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$fk_aineistotilaus = $parametrit["fk_aineistotilaus"];

			if(!is_null($fk_aineistotilaus) && !is_null($kayt_id)){
				try {
					if ($db = $this->_connectToDb()) {
						
						$db->beginTransaction();
						
						$paatosDAO = new PaatosDAO($db);
						$hakemusDAO = new HakemusDAO($db);
						$hakemusversioDAO = new HakemusversioDAO($db);
						$aineistotilausDAO = new AineistotilausDAO($db);					
				
						$aineistotilausDTO = $aineistotilausDAO->hae_aineistotilauksen_tiedot($fk_aineistotilaus);

						// Haetaan hakemusversio ja tarkistetaan käyttöoikeudet
						$paatosDTO = $paatosDAO->hae_paatoksen_tiedot($aineistotilausDTO->PaatosDTO->ID);
						$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($paatosDTO->HakemusDTO->ID);
						$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);						
						
						if(kayttajaAutentikoitu($db,array("hakemusversio_id"=>$hakemusversioDTO->ID, "token"=>$parametrit["token"], "kayttajan_rooli"=>"rooli_hakija", "kayt_id"=>$kayt_id))){
							if(hakemusversion_kayttajan_toiminto_sallittu($db, $kayt_id, $hakemusversioDTO, "lahetys")){ 
								
								$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);
								$aineistotilausDAO = new AineistotilausDAO($db);

								$aineistotilauksen_tilaDTO = $aineistotilauksen_tilaDAO->hae_tilan_koodi_aineistotilauksen_avaimella($fk_aineistotilaus);

								if($aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_toimitettu"){

									$aineistotilausDAO->paivita_aineistotilauksen_tieto($fk_aineistotilaus, "Aineisto_lahetetty", null, $kayt_id);
									$aineistotilausDAO->paivita_aineistotilauksen_tieto($fk_aineistotilaus, "Aineistonmuodostuksen_hinta", null, $kayt_id);
									$aineistotilausDAO->paivita_aineistotilauksen_tieto($fk_aineistotilaus, "Aineistonmuodostaja", null, $kayt_id);

									// Aineistotilauksen tila -> reklamaatio
									$aineistotilauksen_tilaDAO->lisaa_aineistotilaukseen_tila($fk_aineistotilaus, "aint_rekl", $kayt_id);

								}

								if($db->commit()){
									if($aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_toimitettu"){
										$dto["Reklamaatio_lahetetty"] = true;
									}
								}
								
							}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
						
						$db = null;
						
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Metodilla kuitataan aineisto palautetuksi, hävitetyksi tai arkistoiduksi
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ int $fk_aineistotilaus }, 
			object{ string $token },
			object{
				string[] $data{
					string $kuittauksen_tyyppi 
				}
			}				
		}
	 * @return string[] $dto { object{ boolean $Aineisto_kuitattu } }
	 */
	public function kuittaa_aineisto($syoteparametrit) {

		$dto = array();
		$dto["Aineisto_kuitattu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$fk_aineistotilaus = $parametrit["fk_aineistotilaus"];
			$aineisto_palautettu_koodi = $parametrit["data"]["kuittauksen_tyyppi"];

			if(!is_null($aineisto_palautettu_koodi) && !is_null($fk_aineistotilaus) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						
						$db->beginTransaction();
						
						$paatosDAO = new PaatosDAO($db);
						$hakemusDAO = new HakemusDAO($db);
						$hakemusversioDAO = new HakemusversioDAO($db);
						$aineistotilausDAO = new AineistotilausDAO($db);					
				
						// Haetaan hakemusversio ja tarkistetaan käyttöoikeudet
						$aineistotilausDTO = $aineistotilausDAO->hae_aineistotilauksen_tiedot($fk_aineistotilaus);																		
						$paatosDTO = $paatosDAO->hae_paatoksen_tiedot($aineistotilausDTO->PaatosDTO->ID);
						$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($paatosDTO->HakemusDTO->ID);
						$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);						
						
						if(kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_hakija", "hakemusversio_id"=>$hakemusversioDTO->ID, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							if(hakemusversion_kayttajan_toiminto_sallittu($db, $kayt_id, $hakemusversioDTO, "lahetys")){ 

								$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);

								if($aineistotilauksen_tilaDAO->lisaa_aineistotilaukseen_tila($fk_aineistotilaus, $aineisto_palautettu_koodi, $kayt_id)){
									if($db->commit()) $dto["Aineisto_kuitattu"] = true;																			
								}
							
							}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
						
						$db = null;
						
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Käyttäjä vapauttaa lukitsemansa hakemuslomakkeen muokattavaksi toisille hakijoille
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }
		}
	 * @return string[] $dto { object{ boolean $hakemusversiot_vapautettu } }
	 */
	public function vapauta_kayttajan_lukitsemat_hakemusversiot($syoteparametrit) {

		$dto = array();
		$dto["hakemusversiot_vapautettu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($kayt_id)){
				try {
					if ($db = $this->_connectToDb()) {

						$db->beginTransaction();

						vapauta_kayttajan_lukitsemat_hakemusversiot($db, $kayt_id);

						if($db->commit()) $dto["hakemusversiot_vapautettu"] = true;
													
						$db = null;

					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Funktiolla lähetetään viranomaiselle lausuntopyyntö
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ int $hakemus_id }, 
			object{ string $token },	
			object{ int $kayttajan_rooli },
			object{
				string[] $data{
					int $laus_antaja,
					string $laus_pvm,
					string $lausuntopyynto
				}
			}			
		}
	 * @return string[] $dto { 
			object{ boolean $Lausuntopyynto_lahetetty },
			object{ string[] $Istunto },
			object{ LausuntopyyntoDTO $LausuntopyyntoDTO (optional) }
		}
	 */
	public function laheta_lausuntopyynto($syoteparametrit) {

		$dto = array();
		$dto["Lausuntopyynto_lahetetty"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];
			$lausunnon_antaja = $parametrit["data"]["laus_antaja"];
			$lausunnon_mpvm = $parametrit["data"]["laus_pvm"];
			$lausunnon_mpvm = strtotime($lausunnon_mpvm);
			$lausunnon_mpvm = date('Y-m-d', $lausunnon_mpvm );
			$lausuntopyynto = $parametrit["data"]["lausuntopyynto"];
			$kayttajan_rooli = $parametrit["kayttajan_rooli"];

			if(!is_null($lausunnon_antaja) && !is_null($hakemus_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("sallitut_roolit"=>array("rooli_kasitteleva","rooli_paattava","rooli_eettisen_puheenjohtaja","rooli_eettisensihteeri"), "hakemus_id"=>$hakemus_id, "kayttajan_rooli"=>$kayttajan_rooli, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$hakemusDAO = new HakemusDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
							$lausuntoDAO = new LausuntoDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);

							$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemus_id);
							$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);

							// Luodaan lausuntopyyntö
							if($lausuntopyyntoDTO = $lausuntopyyntoDAO->lisaa_lausuntopyynto($hakemusDTO->HakemusversioDTO->TutkimusDTO->ID, $hakemusDTO->ID, $kayt_id, $lausunnon_antaja, $hakemusDTO->Diaarinumero, $lausuntopyynto, $lausunnon_mpvm, $kayt_id)){
								
								$fk_lausunto = $lausuntoDAO->alusta_lausunto($lausuntopyyntoDTO->ID, 0, $kayt_id); // Alustetaan lausunto

								if(is_numeric($fk_lausunto) && $fk_lausunto > 0){
								
									$lausuntopyyntoDTO->KayttajaDTO_Pyytaja = $kayttajaDAO->hae_kayttajan_tiedot($kayt_id);
									$lausuntopyyntoDTO->KayttajaDTO_Antaja = $kayttajaDAO->hae_kayttajan_tiedot($lausunnon_antaja);
									$lausuntopyyntoDTO->LausuntoDTO = new LausuntoDTO();
									$lausuntopyyntoDTO->LausuntoDTO->ID = $fk_lausunto;

									$dto["LausuntopyyntoDTO"] = $lausuntopyyntoDTO;
									$dto["Lausuntopyynto_lahetetty"] = true;
									
								}
								
							}

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Käyttäjän autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Päivitetään käyttäjän rooli pääkäyttäjän toimesta
	 * @param string[] $syoteparametrit {
			object{ int $tallentaja_id }, 
			object{ int $fk_kayttaja }, 
			object{ int $fk_viranomainen }, 
			object{ string $kayttaja_rooli }, 
			object{ string $rooli_valittu }, 
			object{ string $token },	
			object{ string $viranomaisen_koodi (optional) }			
		}
	 * @return string[] $dto {
			object{ boolean $Kayttajan_rooli_paivitetty },
			object{ string[] $Viranomaisen_rooliDTO{
					string[] $Lisatty_rooli{
						Viranomaisen_rooliDTO $viranomaisen_rooliDTO
					}
				} 	 			
			} (optional)
		}
	 */
	public function paivita_kayttajan_rooli($syoteparametrit) {

		$dto = array();
		$dto["Kayttajan_rooli_paivitetty"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["tallentaja_id"];	// tallentajan käyttäjä id
			$fk_kayttaja = $parametrit["fk_kayttaja"]; // tallennettavan käyttäjä id
			$fk_viranomainen = $parametrit["fk_viranomainen"]; // tallennettavan viranomaisen id
			$kayttaja_rooli = $parametrit["kayttaja_rooli"];
			$rooli_valittu = $parametrit["rooli_valittu"];
			$oikeusTallentamiseen = false;

			if(isset($parametrit["viranomaisen_koodi"])){
				$tallennettavan_vir_koodi = $parametrit["viranomaisen_koodi"];
			}
			if(!is_null($rooli_valittu) && !is_null($fk_kayttaja) && !is_null($fk_viranomainen) && !is_null($kayttaja_rooli) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$paakayttajan_rooliDAO = new Paakayttajan_rooliDAO($db);

							// Haetaan tallentajan viranomaisen koodit ja viranomaisen roolit
							$viranomaisen_roolitDTO_tallentaja = $viranomaisen_rooliDAO->hae_kayttajalle_viranomaisen_roolit($kayt_id);

							// Tarkistetaan onko oikeus tallentamiseen
							if($paakayttajan_rooliDAO->kayttaja_on_lupapalvelun_paakayttaja($kayt_id)){
								$oikeusTallentamiseen = true;
							} else {

								if($fk_viranomainen > 0){

									$viranomaisen_rooliDTO_tallennettava = $viranomaisen_rooliDAO->hae_viranomaisen_tiedot($fk_viranomainen);
									$tallennettavan_vir_koodi = $viranomaisen_rooliDTO_tallennettava->Viranomaisen_koodi;

									for($i=0; $i < sizeof($viranomaisen_roolitDTO_tallentaja); $i++){
										if($viranomaisen_roolitDTO_tallentaja[$i]->Viranomaisen_koodi==$tallennettavan_vir_koodi && $viranomaisen_roolitDTO_tallentaja[$i]->Viranomaisroolin_koodi=="rooli_viranomaisen_paak"){
											$oikeusTallentamiseen = true;
											break;
										}
									}
								} else {

									$viranomaisen_roolitDTO_tallennettava = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_koodit($fk_kayttaja);

									if(sizeof($viranomaisen_roolitDTO_tallennettava)==0){
										for($i=0; $i < sizeof($viranomaisen_roolitDTO_tallentaja); $i++){
											if($viranomaisen_roolitDTO_tallentaja[$i]->Viranomaisroolin_koodi=="rooli_viranomaisen_paak"){ 
												$oikeusTallentamiseen = true;
												$tallennettavan_vir_koodi = $viranomaisen_roolitDTO_tallentaja[$i]->Viranomaisen_koodi;
												break;
											}
										}
									} else {
										if(sizeof($viranomaisen_roolitDTO_tallennettava) > 0){
											for($i=0; $i < sizeof($viranomaisen_roolitDTO_tallentaja); $i++){
												if($viranomaisen_roolitDTO_tallentaja[$i]->Viranomaisroolin_koodi=="rooli_viranomaisen_paak"){
													for($j=0; $j < sizeof($viranomaisen_roolitDTO_tallennettava); $j++){ 
														if($viranomaisen_roolitDTO_tallentaja[$i]->Viranomaisen_koodi==$viranomaisen_roolitDTO_tallennettava[$j]->Viranomaisen_koodi){
															$oikeusTallentamiseen = true;
															$tallennettavan_vir_koodi = $viranomaisen_roolitDTO_tallennettava[$j]->Viranomaisen_koodi;
															break;
														}
													}
												}
											}
										}
									}
								}
							}
							if($oikeusTallentamiseen){

								// Lisätään rooli
								if($rooli_valittu=="valittu"){ 

									$viranomaisen_rooliDTO = $viranomaisen_rooliDAO->lisaa_viranomaisen_rooli($fk_kayttaja, $tallennettavan_vir_koodi, $kayttaja_rooli, $kayt_id);

									if(isset($viranomaisen_rooliDTO) && !is_null($viranomaisen_rooliDTO)){

										$dto["Kayttajan_rooli_paivitetty"] = true;
										$dto["Viranomaisen_rooliDTO"]["Lisatty_rooli"] = $viranomaisen_rooliDTO;

									}

								}
								// Poistetaan rooli
								if($rooli_valittu=="ei_valittu"){
									if($viranomaisen_rooliDAO->poista_kayttajan_viranomaisen_rooli($kayt_id, $fk_viranomainen)){
										$dto["Kayttajan_rooli_paivitetty"] = true;
									}
								}
							} else {
								throw new SoapFault(ERR_INVALID_ID, "Ei oikeutta tallentamiseen.");
							}
							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Pääkäyttäjä tallentaa lomakkeen tiedot
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ string $token }, 
			object{ string $tallennus_koodi },
			object{
				string[] $data{
					int $lomake_id,
					string $lomakkeen_nimi,
					string $lomakkeen_tyyppi,
					string $uusi_hakemus_teksti_fi (optional),
					string $uusi_hakemus_teksti_en (optional),
					string $Paatostyyppi (optional),
					string[] $sivu{
						string $Sivun_nimi_fi,
						string $Sivun_nimi_en
					} (optional),
					string[] $uusi_sivu{
						string $Sivun_nimi_fi,
						string $Sivun_nimi_en,
						string $Sivun_tunniste,
						int $Jarjestys
					} (optional)
				}
			}			
		}
	 * @return string[] $dto {
			object{ string[] $Istunto{ KayttajaDTO $Kayttaja } }
		}
	 */
	public function tallenna_lomake($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$data = $parametrit["data"];
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($data) && !is_null($kayt_id) && !is_null($parametrit["token"]) && !is_null($data["lomake_id"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

								if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){
									$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;
								} else if($viranomaisen_rooliDTO_autentikoitu_kayttaja = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, "rooli_viranomaisen_paak")) {
									$dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO = $viranomaisen_rooliDTO_autentikoitu_kayttaja;
								} else {
									throw new SoapFault(ERR_INVALID_ID, "Ei käyttöoikeutta.");
								}

								$db->beginTransaction();

								$lomakeDAO = new LomakeDAO($db);
								$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);
								$lomake_hakemusDAO = new Lomake_hakemusDAO($db);
								$osioDAO = new OsioDAO($db);
								$lomake_paatosDAO = new Lomake_paatosDAO($db);

								$lomakeDTO = $lomakeDAO->hae_lomake($data["lomake_id"]);

								if(isset($parametrit["tallennus_koodi"]) && $parametrit["tallennus_koodi"]=="uusi_sivu" && isset($data["uusi_sivu"])){ // Lisätään uusi sivu

									// Lisätään uusi lomakkeen sivu
									$lomakkeen_sivuDTO = $lomakkeen_sivutDAO->lisaa_lomakkeen_sivu($data["lomake_id"], null, null, $data["uusi_sivu"]["Jarjestys"], $kayt_id);
			
									if(isset($data["uusi_sivu"]["Sivun_nimi_fi"])) $lomakkeen_sivutDAO->paivita_lomakkeen_sivun_tieto($lomakkeen_sivuDTO->ID, "Nimi_fi", $data["uusi_sivu"]["Sivun_nimi_fi"], $kayt_id);
									if(isset($data["uusi_sivu"]["Sivun_nimi_en"])) $lomakkeen_sivutDAO->paivita_lomakkeen_sivun_tieto($lomakkeen_sivuDTO->ID, "Nimi_en", $data["uusi_sivu"]["Sivun_nimi_en"], $kayt_id);
									
									if($data["uusi_sivu"]["Sivun_tunniste"]=="uusi_sivu"){
										$sivun_tun = $lomakkeen_sivuDTO->ID;
									} else {
										$sivun_tun = $data["uusi_sivu"]["Sivun_tunniste"];
									}
									
									$lomakkeen_sivutDAO->paivita_lomakkeen_sivu($lomakkeen_sivuDTO->ID, $sivun_tun, null, $data["uusi_sivu"]["Jarjestys"]);

									// Jos on sivupohja => luodaan osioita
									if($sivun_tun=="hakemus_liitteet"){
										$osioDAO->luo_osio(null, null, "hakemus_liitteet", $lomakeDTO->Asiakirjatyyppi, null, "liitteet", null, null, 1, null, null, 1, $kayt_id);
									}
									
								} else { // Tallennetaan lomake 

									$lomakeDAO->paivita_lomake($data["lomake_id"], null, null, $data["lomakkeen_nimi"], $data["lomakkeen_tyyppi"]);

									if($data["lomakkeen_tyyppi"]=="Hakemus"){

										// Tarkistetaan löytyykö lomakkeen hakemus-metatietoja
										$lomake_hakemusDTO = $lomake_hakemusDAO->hae_lomakkeen_lomake_hakemus($data["lomake_id"]);
									
										if(isset($data["uusi_hakemus_teksti_fi"])) $lomake_hakemusDAO->paivita_lomake_hakemuksen_tieto($lomake_hakemusDTO->ID, "Uusi_hakemus_painike_teksti_fi", $data["uusi_hakemus_teksti_fi"], $kayt_id);
										if(isset($data["uusi_hakemus_teksti_en"])) $lomake_hakemusDAO->paivita_lomake_hakemuksen_tieto($lomake_hakemusDTO->ID, "Uusi_hakemus_painike_teksti_en", $data["uusi_hakemus_teksti_en"], $kayt_id);
										
										// Luodaan uusi lomake hakemus
										if(is_null($lomake_hakemusDTO->ID)) $lomake_hakemusDAO->luo_lomake_hakemus($data["lomake_id"], $koodi, $kayt_id); 											
										
										if(isset($data["sivu"])){
											foreach($data["sivu"] as $fk_lomakkeen_sivu => $sivu) {

												$lomakkeen_sivuDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivu($fk_lomakkeen_sivu);

												if(isset($sivu["Sivun_nimi_fi"])) $lomakkeen_sivutDAO->paivita_lomakkeen_sivun_tieto($lomakkeen_sivuDTO->ID, "Nimi_fi", $sivu["Sivun_nimi_fi"], $kayt_id);
												if(isset($sivu["Sivun_nimi_en"])) $lomakkeen_sivutDAO->paivita_lomakkeen_sivun_tieto($lomakkeen_sivuDTO->ID, "Nimi_en", $sivu["Sivun_nimi_en"], $kayt_id);
												
											}
										}
										
									} else if($data["lomakkeen_tyyppi"]=="Päätös"){

										// Tarkistetaan löytyykö päätös-sivua
										$lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($data["lomake_id"]);

										// Lisätään päätös-sivu jos ei löydy
										if(empty($lomakkeen_sivutDTO)){
											$lomakkeen_sivuDTO = $lomakkeen_sivutDAO->lisaa_lomakkeen_sivu($data["lomake_id"], null, null, 1, $kayt_id);
											$lomakkeen_sivutDAO->paivita_lomakkeen_sivun_tieto($lomakkeen_sivuDTO->ID, "Nimi_fi", "Päätös", $kayt_id);
											$lomakkeen_sivutDAO->paivita_lomakkeen_sivun_tieto($lomakkeen_sivuDTO->ID, "Nimi_en", "Decision", $kayt_id);
											$lomakkeen_sivutDAO->paivita_lomakkeen_sivu($lomakkeen_sivuDTO->ID, "paatos_oletus", "PAATOS", 1);
										}

										// Tarkistetaan löytyykö lomakkeen päätös-metatietoja
										$lomake_paatosDTO = $lomake_paatosDAO->hae_lomakkeen_lomake_paatos($data["lomake_id"]);

										if(is_null($lomake_paatosDTO->ID)){ // Luodaan uusi lomake päätös
											$lomake_paatosDAO->luo_lomake_paatos($data["lomake_id"], $data["Paatostyyppi"], $kayt_id);
										} else { // Päivitetään lomake päätös
											$lomake_paatosDAO->paivita_lomake_paatoksen_tieto($lomake_paatosDTO->ID, "Paatostyyppi", $data["Paatostyyppi"], $kayt_id);
										}

									} else if($data["lomakkeen_tyyppi"]=="Lausunto"){

										// Tarkistetaan löytyykö lausunto-sivua
										$lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($data["lomake_id"]);

										// Lisätään lausunto-sivu jos ei löydy
										if(empty($lomakkeen_sivutDTO)){
											$lomakkeen_sivuDTO = $lomakkeen_sivutDAO->lisaa_lomakkeen_sivu($data["lomake_id"], null, null, 1, $kayt_id);
											$lomakkeen_sivutDAO->paivita_lomakkeen_sivun_tieto($lomakkeen_sivuDTO->ID, "Nimi_fi", "Lausunto", $kayt_id);
											$lomakkeen_sivutDAO->paivita_lomakkeen_sivun_tieto($lomakkeen_sivuDTO->ID, "Nimi_en", "Review", $kayt_id);											
											$lomakkeen_sivutDAO->paivita_lomakkeen_sivu($lomakkeen_sivuDTO->ID, "lausunto_oletus", "LAUSUNTO", 1);
										}

									} else {
										if($lomakeDTO->Lomakkeen_tyyppi=="Hakemus"){ // Poistetaan lomake hakemus
											$lomake_hakemusDTO = $lomake_hakemusDAO->hae_lomakkeen_lomake_hakemus($lomakeDTO->ID);
											$lomake_hakemusDAO->merkitse_lomake_hakemus_poistetuksi($lomake_hakemusDTO->ID, $kayt_id);											
										}
									}
								}
								
								$db->commit();
								$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Pääkäyttäjä tallentaa lomakkeeseen riippuvuussäännön
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ string $token }, 	
			object{ string $tallennuskoodi }, 
			object{
				string[] $data{ 
					int $lomake_id,
					string[] $Uusi_Osio_saanto{
						string $Saanto,
						int $FK_Osio_muuttuja 
					} (optional),
					string[] $Uusi_Osio_lause{
						string $Predikaatti,
						int $FK_Osio_muuttuja
					} (optional),
					string[] $Osio_saanto{
						string $Saanto,
						int $FK_Osio_muuttuja
					} (optional),
					string[] $Osio_lause{
						int $FK_Osio_muuttuja,
						string $Predikaatti
					}
				}
			}		
		}
	 * @return string[] $dto {
			object{ string[] $Istunto{ KayttajaDTO $Kayttaja } }
		}
	 */
	public function tallenna_lomakkeen_saanto($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$data = $parametrit["data"];
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($data) && !is_null($kayt_id) && !is_null($parametrit["token"]) && !is_null($data["lomake_id"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							//if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){

								if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){
									$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;
								} else if($viranomaisen_rooliDTO_autentikoitu_kayttaja = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, "rooli_viranomaisen_paak")) {
									$dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO = $viranomaisen_rooliDTO_autentikoitu_kayttaja;
								} else {
									throw new SoapFault(ERR_INVALID_ID, "Ei käyttöoikeutta.");
								}

								$db->beginTransaction();

								$osio_saantoDAO = new Osio_saantoDAO($db);
								$osio_lauseDAO = new Osio_lauseDAO($db);

								if(isset($parametrit["tallennuskoodi"]) && $parametrit["tallennuskoodi"]=="uusi_riippuvuussaanto" && isset($data["Uusi_Osio_saanto"]["Saanto"]) && isset($data["Uusi_Osio_saanto"]["FK_Osio_muuttuja"]) && isset($data["Uusi_Osio_lause"]["FK_Osio_muuttuja"]) && isset($data["Uusi_Osio_lause"]["Predikaatti"]) ){

									$osio_saantoDTO = $osio_saantoDAO->luo_osio_saanto($data["Uusi_Osio_saanto"]["Saanto"], $data["Uusi_Osio_saanto"]["FK_Osio_muuttuja"], $kayt_id);
									$osio_lauseDAO->luo_osio_lause($osio_saantoDTO->ID, null, $data["Uusi_Osio_lause"]["Predikaatti"], $data["Uusi_Osio_lause"]["FK_Osio_muuttuja"], 1, $kayt_id);

								} else if(isset($parametrit["tallennuskoodi"]) && $parametrit["tallennuskoodi"]=="saannon_paivitys" && isset($data["Osio_saanto"]) && isset($data["Osio_lause"])){

									foreach($data["Osio_saanto"] as $fk_osio_saanto => $osio_saanto) { 
										$osio_saantoDAO->paivita_osion_saannon_tieto($fk_osio_saanto, "Saanto", $osio_saanto["Saanto"], $kayt_id);
										$osio_saantoDAO->paivita_osion_saannon_tieto($fk_osio_saanto, "FK_Osio_muuttuja", $osio_saanto["FK_Osio_muuttuja"], $kayt_id);

									}
									foreach($data["Osio_lause"] as $fk_osio_lause => $osio_lause) { 
										$osio_lauseDAO->paivita_osio_lauseen_tieto($fk_osio_lause, "FK_Osio_muuttuja", $osio_lause["FK_Osio_muuttuja"], $kayt_id);
										$osio_lauseDAO->paivita_osio_lauseen_tieto($fk_osio_lause, "Predikaatti", $osio_lause["Predikaatti"], $kayt_id);
									}
								} else {
									throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
								}
								
								$db->commit();
								$db = null;

							//}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Pääkäyttäjä tallentaa lomakkeen sivun tiedot
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ string $token }, 	
			object{ string $tallennus_koodi }, 
			object{ 
				string[] $data{ 
					int $lomake_id,
					int $lomake_sivu_id,
					string[] $uusi_kokonaisuus{
						string $Viranomainen,
						string $Nimi,
						int $Jarjestys
					} (optional),
					string[] $Osio (optional),
					string[] $Osio_parent (optional),
					string $sivun_nimi_fi (optional),
					string $sivun_nimi_en (optional)					
				}
			}		
		}
	 * @return string[] $dto {
			object{ 
				string[] $Istunto{ KayttajaDTO $Kayttaja } 
			}, 
			object{
				string[] $Virheilmoitus
			}
		}
	 */
	public function tallenna_lomake_sivu($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$data = $parametrit["data"];
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($data) && !is_null($kayt_id) && !is_null($parametrit["token"]) && !is_null($data["lomake_id"]) && !is_null($data["lomake_sivu_id"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){
								$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;
							} else if($viranomaisen_rooliDTO_autentikoitu_kayttaja = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, "rooli_viranomaisen_paak")) {
								$dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO = $viranomaisen_rooliDTO_autentikoitu_kayttaja;
							} else {
								throw new SoapFault(ERR_INVALID_ID, "Ei käyttöoikeutta.");
							}
							
								$db->beginTransaction();

								$lomakeDAO = new LomakeDAO($db);
								$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);
								$lomake_hakemusDAO = new Lomake_hakemusDAO($db);
								$osioDAO = new OsioDAO($db);

								$lomakeDTO = $lomakeDAO->hae_lomake($data["lomake_id"]);
								$lomakkeen_sivuDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivu($data["lomake_sivu_id"]);
								$vir_tunniste = null;

								// Lisätään uusi kysymyskokonaisuus
								if(isset($parametrit["tallennus_koodi"]) && $parametrit["tallennus_koodi"]=="uusi_kokonaisuus" && isset($data["lisaa_uusi_kokonaisuus"]) && $data["lisaa_uusi_kokonaisuus"]["Nimi"]!="" && $data["lisaa_uusi_kokonaisuus"]==true && isset($data["uusi_kokonaisuus"]) && $data["uusi_kokonaisuus"]["Jarjestys"]!=""){ 

									if(isset($data["uusi_kokonaisuus"]["Viranomainen"])){
										$vir_tunniste = $data["uusi_kokonaisuus"]["Viranomainen"];
									}
									if(isset($dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisen_koodi)){
										$vir_tunniste = $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisen_koodi;
									}
									// Uusi kysymyskokonaisuus
									$osioDTO_kokonaisuus = $osioDAO->luo_osio(null, $data["uusi_kokonaisuus"]["Nimi"], $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, $vir_tunniste, "laatikko", null, null, null, null, null, $data["uusi_kokonaisuus"]["Jarjestys"], $kayt_id);
									$osioDTO_laatikko_otsikko = $osioDAO->luo_osio($osioDTO_kokonaisuus->ID, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, $vir_tunniste, "laatikko_otsikko", null, null, null, null, null, 1, $kayt_id);
									$osioDTO_laatikko_sisalto = $osioDAO->luo_osio($osioDTO_kokonaisuus->ID, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, $vir_tunniste, "laatikko_sisalto", null, null, null, null, null, 2, $kayt_id);

								} else if(isset($parametrit["tallennus_koodi"]) && $parametrit["tallennus_koodi"]=="uusi_kysymys" && isset($data["Osio_parent"])){ // Uusi kysymys kysymyskokonaisuuteen

									$dto["Virheilmoitus"]["Tietoja_puuttuu"] = true;

									foreach($data["Osio_parent"] as $fk_osio_parent => $uusi_osio) {

										$vir_tunniste = null;

										if(isset($uusi_osio["Uusi_kysymys"]["Viranomainen"])){
											$vir_tunniste = $uusi_osio["Uusi_kysymys"]["Viranomainen"];
										}
										if(isset($dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisen_koodi)){
											$vir_tunniste = $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisen_koodi;
										}

										if(isset($uusi_osio["Uusi_kysymys"]["Osio_tyyppi"]) && $uusi_osio["Uusi_kysymys"]["Osio_tyyppi"]!=""){

											$dto["Virheilmoitus"]["Tietoja_puuttuu"] = false;

											if( isset($uusi_osio["Uusi_kysymys"]["Pakollinen_tieto"]) && $uusi_osio["Uusi_kysymys"]["Pakollinen_tieto"]=="on" ){
												$pakollinen_tieto = 1;
											} else {
												$pakollinen_tieto = 0;
											}
											// Haetaan kysymyskokonaisuuden osio, jonka alle kysymys lisätään
											$osiotDTO_lapset = $osioDAO->hae_lapsiosiot($fk_osio_parent, "fi");

											// Luodaan osio: kysymyskokonaisuuden kysymys
											if($uusi_osio["Uusi_kysymys"]["Osio_tyyppi"]=="lomake_tutkimuksen_nimi"){  

												$osioDTO_kysymys = $osioDAO->luo_osio($osiotDTO_lapset[1]->ID, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, $vir_tunniste, "kysymys", null, "TUTKIMUKSEN_NIMI", 1, null, null, $uusi_osio["Uusi_kysymys"]["Jarjestys"], $kayt_id);

											} else {

												$osioDTO_kysymys = $osioDAO->luo_osio($osiotDTO_lapset[1]->ID, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, $vir_tunniste, "kysymys", null, null, $pakollinen_tieto, null, null, $uusi_osio["Uusi_kysymys"]["Jarjestys"], $kayt_id);

												// Päivitetään otsikon ja mahdollisesti infotekstin käännökset
												if(isset($uusi_osio["Uusi_kysymys"]["Otsikko_fi"])) $osioDAO->paivita_osion_tieto($osioDTO_kysymys->ID, "Otsikko_fi", $uusi_osio["Uusi_kysymys"]["Otsikko_fi"], $kayt_id);
												if(isset($uusi_osio["Uusi_kysymys"]["Otsikko_en"])) $osioDAO->paivita_osion_tieto($osioDTO_kysymys->ID, "Otsikko_en", $uusi_osio["Uusi_kysymys"]["Otsikko_en"], $kayt_id);												

												if(isset($uusi_osio["Uusi_kysymys"]["Infoteksti_fi"])) $osioDAO->paivita_osion_tieto($osioDTO_kysymys->ID, "Infoteksti_fi", $uusi_osio["Uusi_kysymys"]["Infoteksti_fi"], $kayt_id);
												if(isset($uusi_osio["Uusi_kysymys"]["Infoteksti_en"])) $osioDAO->paivita_osion_tieto($osioDTO_kysymys->ID, "Infoteksti_en", $uusi_osio["Uusi_kysymys"]["Infoteksti_en"], $kayt_id);												

											}
											// Luodaan osio: kysymyskokonaisuuden kysymyksen vastauskenttä
											if($uusi_osio["Uusi_kysymys"]["Osio_tyyppi"]=="textarea" || $uusi_osio["Uusi_kysymys"]["Osio_tyyppi"]=="textarea_large"){

												$osioDTO_vastauskentta = $osioDAO->luo_osio($osioDTO_kysymys->ID, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, $vir_tunniste, $uusi_osio["Uusi_kysymys"]["Osio_tyyppi"], null, null, $pakollinen_tieto, null, null, 1, $kayt_id);

											} else if($uusi_osio["Uusi_kysymys"]["Osio_tyyppi"]=="lomake_tutkimuksen_nimi"){

												$osioDTO_vastauskentta = $osioDAO->luo_osio($osioDTO_kysymys->ID, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, $vir_tunniste, $uusi_osio["Uusi_kysymys"]["Osio_tyyppi"], null, null, 1, null, null, 1, $kayt_id);

											} else if($uusi_osio["Uusi_kysymys"]["Osio_tyyppi"]=="date_range"){

												$osioDTO_date_start = $osioDAO->luo_osio($osioDTO_kysymys->ID, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, $vir_tunniste, "date_start", null, null, $pakollinen_tieto, null, null, 1, $kayt_id);
												$osioDTO_date_end = $osioDAO->luo_osio($osioDTO_kysymys->ID, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, $vir_tunniste, "date_end", null, null, $pakollinen_tieto, null, null, 2, $kayt_id);

											} else if($uusi_osio["Uusi_kysymys"]["Osio_tyyppi"]=="checkbox" && isset($uusi_osio["Uusi_kysymys"]["Osio_checkbox"])){

												$checkbox_jarj = 1;

												for($i=0; $i < sizeof($uusi_osio["Uusi_kysymys"]["Osio_checkbox"]); $i++){
													if(isset($uusi_osio["Uusi_kysymys"]["Osio_checkbox"][$i]["Otsikko_en"]) && isset($uusi_osio["Uusi_kysymys"]["Osio_checkbox"][$i]["Otsikko_fi"]) && $uusi_osio["Uusi_kysymys"]["Osio_checkbox"][$i]["Otsikko_fi"]!="" && $uusi_osio["Uusi_kysymys"]["Osio_checkbox"][$i]["Otsikko_en"]!=""){

														if(isset($uusi_osio["Uusi_kysymys"]["Osio_checkbox"][$i]["Jarjestys"]) && !is_null($uusi_osio["Uusi_kysymys"]["Osio_checkbox"][$i]["Jarjestys"])){
															$checkbox_jarj = $uusi_osio["Uusi_kysymys"]["Osio_checkbox"][$i]["Jarjestys"];
														}
														$osioDTO_uusi_checkbox = $osioDAO->luo_osio($osioDTO_kysymys->ID, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, $vir_tunniste, "checkbox", null, null, $pakollinen_tieto, null, null, $checkbox_jarj, $kayt_id);
														$osioDAO->paivita_osion_tieto($osioDTO_uusi_checkbox->ID, "Osio_luokka", "checkbox-" . $osioDTO_kysymys->ID, $kayt_id);

														if(isset($uusi_osio["Uusi_kysymys"]["Osio_checkbox"][$i]["Otsikko_fi"])) $osioDAO->paivita_osion_tieto($osioDTO_uusi_checkbox->ID, "Otsikko_fi", $uusi_osio["Uusi_kysymys"]["Osio_checkbox"][$i]["Otsikko_fi"], $kayt_id);
														if(isset($uusi_osio["Uusi_kysymys"]["Osio_checkbox"][$i]["Otsikko_en"])) $osioDAO->paivita_osion_tieto($osioDTO_uusi_checkbox->ID, "Otsikko_en", $uusi_osio["Uusi_kysymys"]["Osio_checkbox"][$i]["Otsikko_en"], $kayt_id);														

														$checkbox_jarj++;

													}
												}
											} else if($uusi_osio["Uusi_kysymys"]["Osio_tyyppi"]=="radio" && isset($uusi_osio["Uusi_kysymys"]["Osio_radio"])){

												$radio_jarj = 1;

												for($i=0; $i < sizeof($uusi_osio["Uusi_kysymys"]["Osio_radio"]); $i++){
													if(isset($uusi_osio["Uusi_kysymys"]["Osio_radio"][$i]["Otsikko_en"]) && isset($uusi_osio["Uusi_kysymys"]["Osio_radio"][$i]["Otsikko_fi"]) && $uusi_osio["Uusi_kysymys"]["Osio_radio"][$i]["Otsikko_fi"]!="" && $uusi_osio["Uusi_kysymys"]["Osio_radio"][$i]["Otsikko_en"]!=""){

														if(isset($uusi_osio["Uusi_kysymys"]["Osio_radio"][$i]["Jarjestys"]) && !is_null($uusi_osio["Uusi_kysymys"]["Osio_radio"][$i]["Jarjestys"])){
															$radio_jarj = $uusi_osio["Uusi_kysymys"]["Osio_radio"][$i]["Jarjestys"];
														}
														$osioDTO_uusi_radio = $osioDAO->luo_osio($osioDTO_kysymys->ID, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, $vir_tunniste, "radio", null, null, $pakollinen_tieto, null, null, $radio_jarj, $kayt_id);
														$osioDAO->paivita_osion_tieto($osioDTO_uusi_radio->ID, "Osio_luokka", "radio-" . $osioDTO_kysymys->ID, $kayt_id);

														if(isset($uusi_osio["Uusi_kysymys"]["Osio_radio"][$i]["Otsikko_fi"])) $osioDAO->paivita_osion_tieto($osioDTO_uusi_radio->ID, "Otsikko_fi", $uusi_osio["Uusi_kysymys"]["Osio_radio"][$i]["Otsikko_fi"], $kayt_id);
														if(isset($uusi_osio["Uusi_kysymys"]["Osio_radio"][$i]["Otsikko_en"])) $osioDAO->paivita_osion_tieto($osioDTO_uusi_radio->ID, "Otsikko_en", $uusi_osio["Uusi_kysymys"]["Osio_radio"][$i]["Otsikko_en"], $kayt_id);														

														$radio_jarj++;

													}
												}

											} else {

												$dto["Virheilmoitus"]["Virheellinen_kysymyksen_tyyppi"] = true;

											}
										}

									}
								} else { // Päivitetään lomakkeen tiedot

									if(isset($data["sivun_nimi_fi"])) $lomakkeen_sivutDAO->paivita_lomakkeen_sivun_tieto($lomakkeen_sivuDTO->ID, "Nimi_fi", $data["sivun_nimi_fi"], $kayt_id);
									if(isset($data["sivun_nimi_en"])) $lomakkeen_sivutDAO->paivita_lomakkeen_sivun_tieto($lomakkeen_sivuDTO->ID, "Nimi_en", $data["sivun_nimi_en"], $kayt_id);									

									// Osioiden päivitys
									if(isset($data["Osio"])){
										foreach($data["Osio"] as $fk_osio => $osio) {

											$osio_on_pakollinen = false;
											$osioDTO_paivitettava = $osioDAO->hae_osio($fk_osio);

											foreach($osio as $kentan_nimi => $kentan_arvo) {

												if($kentan_nimi=="Otsikko_fi" || $kentan_nimi=="Otsikko_en"){

													$osioDTO_paivitettavan_lapset = $osioDAO->hae_lapsiosiot($fk_osio, "fi");

													if (!empty($osioDTO_paivitettavan_lapset)) {
														if($osioDTO_paivitettavan_lapset[0]->Osio_tyyppi=="lomake_tutkimuksen_nimi"){
															continue;
														}
													}
													
													$osioDAO->paivita_osion_tieto($osioDTO_paivitettava->ID, $kentan_nimi, $kentan_arvo, $kayt_id);
													
												}

												if($kentan_nimi=="Osio_tyyppi"){

													// Jos osion tyyppi on sama => ei tehdä muutoksia
													if($kentan_arvo==$osioDTO_paivitettava->Osio_tyyppi || ($kentan_arvo=="date_range" && $osioDTO_paivitettava->Osio_tyyppi=="date_start")){
														continue;
													} else {

														if($osioDTO_paivitettava->Osio_tyyppi=="lomake_tutkimuksen_nimi" || $osioDTO_paivitettava->Osio_tyyppi=="radio" || $osioDTO_paivitettava->Osio_tyyppi=="date_start" || $osioDTO_paivitettava->Osio_tyyppi=="checkbox" || $osioDTO_paivitettava->Osio_tyyppi=="textarea" || $osioDTO_paivitettava->Osio_tyyppi=="textarea_large"){

															$osiotDTO_sisaret = $osioDAO->hae_sisarosiot($osioDTO_paivitettava->ID, $osioDTO_paivitettava->OsioDTO_parent->ID);

															for($s=0; $s < sizeof($osiotDTO_sisaret); $s++){
																$osioDAO->merkitse_osio_poistetuksi($osiotDTO_sisaret[$s]->ID, $kayt_id);
															}
															//if($osioDTO_paivitettava->Osio_tyyppi!="checkbox" || $osioDTO_paivitettava->Osio_tyyppi!="radio"){
																//$osioDAO->merkitse_osio_poistetuksi($osioDTO_paivitettava->ID, $kayt_id);
															//	$osioDAO->paivita_osion_tieto($osioDTO_paivitettava->ID, "Osio_luokka", null, $kayt_id);
															//}
															if($kentan_arvo!="radio" || $kentan_arvo!="checkbox"){
																$osioDAO->paivita_osion_tieto($osioDTO_paivitettava->ID, "Osio_luokka", null, $kayt_id);
															}
															if($kentan_arvo=="radio" || $kentan_arvo=="checkbox"){
																$osioDAO->merkitse_osio_poistetuksi($osioDTO_paivitettava->ID, $kayt_id);
															}
														}
														if($kentan_arvo=="date_range"){

															// Muutetaan eka osio date_startiksi
															$osioDAO->paivita_osion_tieto($fk_osio, $kentan_nimi, "date_start", $kayt_id);

															// Luodaan samalla hierarkian tasolle date_end osio
															$osioDTO_date_end = $osioDAO->luo_osio($osioDTO_paivitettava->OsioDTO_parent->ID, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, null, "date_end", null, null, $osioDTO_paivitettava->Pakollinen_tieto, null, null, 2, $kayt_id);

															continue;

														}
														if($kentan_arvo=="lomake_tutkimuksen_nimi"){

															$osioDAO->paivita_osion_tieto($osioDTO_paivitettava->ID, "Osio_tyyppi", "lomake_tutkimuksen_nimi", $kayt_id);
															$osioDAO->paivita_osion_tieto($osioDTO_paivitettava->OsioDTO_parent->ID, "Otsikko", "TUTKIMUKSEN_NIMI", $kayt_id);
															$osioDAO->paivita_osion_tieto($osioDTO_paivitettava->OsioDTO_parent->ID, "Pakollinen_tieto", 1, $kayt_id);
															$osioDAO->paivita_osion_tieto($osioDTO_paivitettava->ID, "Pakollinen_tieto", 1, $kayt_id);
															break;

														}
													}
												}
												if($kentan_nimi=="Pakollinen_tieto"){

													$osio_on_pakollinen = true;

													// Pakollinen tieto periytyy myös alemmalle tasolle (vastauskenttä)
													$lapsiOsiotDTO = $osioDAO->hae_lapsiosiot($fk_osio, "fi");
													for($i=0; $i < sizeof($lapsiOsiotDTO); $i++){
														$osioDAO->paivita_osion_tieto($lapsiOsiotDTO[$i]->ID, "Pakollinen_tieto", 1, $kayt_id);
													}

													$kentan_arvo = 1;

												} 

												$osioDAO->paivita_osion_tieto($fk_osio, $kentan_nimi, $kentan_arvo, $kayt_id);

											}

											// Varmistetaan että osio ei ole pakollinen
											if($osioDTO_paivitettava->Osio_tyyppi=="kysymys" && !$osio_on_pakollinen){

												$osioDAO->paivita_osion_tieto($fk_osio, "Pakollinen_tieto", 0, $kayt_id);
												$lapsiOsiotDTO = $osioDAO->hae_lapsiosiot($fk_osio, "fi");

												for($i=0; $i < sizeof($lapsiOsiotDTO); $i++){
													$osioDAO->paivita_osion_tieto($lapsiOsiotDTO[$i]->ID, "Pakollinen_tieto", 0, $kayt_id);
												}

											}
										}
									}
									if(isset($data["Osio_parent"])){
										foreach($data["Osio_parent"] as $parent_osio => $osio) {

											if(isset($osio["Osio_checkbox"])){
												for($i=0; $i < sizeof($osio["Osio_checkbox"]); $i++){
													if(isset($osio["Osio_checkbox"][$i]["Otsikko_fi"]) && isset($osio["Osio_checkbox"][$i]["Otsikko_en"]) && $osio["Osio_checkbox"][$i]["Otsikko_en"]!= "" && $osio["Osio_checkbox"][$i]["Otsikko_fi"]!=""){
														$uus_checkbox_jarj = null;
														if(isset($osio["Osio_checkbox"][$i]["Jarjestys"])) $uus_checkbox_jarj = $osio["Osio_checkbox"][$i]["Jarjestys"];

														$osioDTO_uusi_checkbox = $osioDAO->luo_osio($parent_osio, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, null, "checkbox", null, null, null, null, null, $uus_checkbox_jarj, $kayt_id);
														$osioDAO->paivita_osion_tieto($osioDTO_uusi_checkbox->ID, "Osio_luokka", "checkbox-" . $parent_osio, $kayt_id);

														if(isset($osio["Osio_checkbox"][$i]["Otsikko_fi"])) $osioDAO->paivita_osion_tieto($osioDTO_uusi_checkbox->ID, "Otsikko_fi", $osio["Osio_checkbox"][$i]["Otsikko_fi"], $kayt_id);
														if(isset($osio["Osio_checkbox"][$i]["Otsikko_en"])) $osioDAO->paivita_osion_tieto($osioDTO_uusi_checkbox->ID, "Otsikko_en", $osio["Osio_checkbox"][$i]["Otsikko_en"], $kayt_id);
														//if(isset($osio["Osio_checkbox"][$i]["Otsikko_sv"])) $osioDAO->paivita_osion_tieto($osioDTO_uusi_checkbox->ID, "Otsikko_sv", $osio["Osio_checkbox"][$i]["Otsikko_sv"], $kayt_id);
													}
												}
											}
											if(isset($osio["Osio_radio"])){
												for($i=0; $i < sizeof($osio["Osio_radio"]); $i++){
													if(isset($osio["Osio_radio"][$i]["Otsikko_fi"]) && isset($osio["Osio_radio"][$i]["Otsikko_en"]) && $osio["Osio_radio"][$i]["Otsikko_en"]!= "" && $osio["Osio_radio"][$i]["Otsikko_fi"]!=""){
														$uus_radio_jarj = null;

														if(isset($osio["Osio_radio"][$i]["Jarjestys"])) $uus_radio_jarj = $osio["Osio_radio"][$i]["Jarjestys"];

														$osioDTO_uusi_radio = $osioDAO->luo_osio($parent_osio, null, $lomakkeen_sivuDTO->Sivun_tunniste, $lomakeDTO->ID, null, "radio", null, null, null, null, null, $uus_radio_jarj, $kayt_id);
														$osioDAO->paivita_osion_tieto($osioDTO_uusi_radio->ID, "Osio_luokka", "radio-" . $parent_osio, $kayt_id);

														if(isset($osio["Osio_radio"][$i]["Otsikko_fi"])) $osioDAO->paivita_osion_tieto($osioDTO_uusi_radio->ID, "Otsikko_fi", $osio["Osio_radio"][$i]["Otsikko_fi"], $kayt_id);
														if(isset($osio["Osio_radio"][$i]["Otsikko_en"])) $osioDAO->paivita_osion_tieto($osioDTO_uusi_radio->ID, "Otsikko_en", $osio["Osio_radio"][$i]["Otsikko_en"], $kayt_id);
														//if(isset($osio["Osio_radio"][$i]["Otsikko_sv"])) $osioDAO->paivita_osion_tieto($osioDTO_uusi_radio->ID, "Otsikko_sv", $osio["Osio_radio"][$i]["Otsikko_sv"], $kayt_id);
													}
												}
											}

										}
									}
								}
								
								$db->commit();
								$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Pääkäyttäjä luo uuden lomakkeen lupajärjestelmään
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ string $token }		 
		}
	 * @return string[] $dto {
			object{ 
				string[] $Istunto{ KayttajaDTO $Kayttaja } 
			},
			object{ 
				LomakeDTO $Uusi_lomakeDTO
			}			
		}
	 */
	public function luo_lomake($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){
								$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;
							} else if($viranomaisen_rooliDTO_autentikoitu_kayttaja = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, "rooli_viranomaisen_paak")) {
								$dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO = $viranomaisen_rooliDTO_autentikoitu_kayttaja;
							} else {
								throw new SoapFault(ERR_INVALID_ID, "Ei käyttöoikeutta.");
							}

							$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;

							$db->beginTransaction();

							$lomakeDAO = new LomakeDAO($db);

							$dto["Uusi_lomakeDTO"] = $lomakeDAO->luo_lomake($kayt_id);

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Pääkäyttäjä lisää lupajärjestelmään uuden liitetyypin
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ string $token },
			object{ 
				string[] $data{ 
					int $lomake_id,
					string $uusi_liitetiedosto_tyypit,
					string[] $uusi_liitetiedosto{
						string $Asiakirjan_tarkenne,
						string $Liitteen_nimi_en,
						string $Liitteen_nimi_fi,
						string $Lisatiedot_fi,
						string $Lisatiedot_en,
						string $Pakollinen,
						string $Osio_ehto,
						string $Ehto
					}
				}
			}		
		}
	 * @return string[] $dto {
			object{ 
				string[] $Istunto{ KayttajaDTO $Kayttaja } 
			}		 
		}
	 */
	public function lisaa_liitetyyppi($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$data = $parametrit["data"];
			$lomake_id = $data["lomake_id"];

			if(!is_null($lomake_id) && !is_null($data) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){

								$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;

								$db->beginTransaction();

								$lomakeDAO = new LomakeDAO($db);
								$asiakirjahallinta_liiteDAO = new Asiakirjahallinta_liiteDAO($db);
								$asiakirjahallinta_saantoDAO = new Asiakirjahallinta_saantoDAO($db);

								$lomakeDTO = $lomakeDAO->hae_lomake($lomake_id);

								$sallitut_tyypit = "";
								if(isset($data["uusi_liitetiedosto_tyypit"]) && !empty($data["uusi_liitetiedosto_tyypit"])){
									foreach($data["uusi_liitetiedosto_tyypit"] as $nro => $tyyppi) {

										if($tyyppi=="docx") $tyyppi = "doc,docx";
										if($tyyppi=="xlsx") $tyyppi = "xls,xlsx";

										if($sallitut_tyypit==""){
											$sallitut_tyypit = $tyyppi;
										} else {
											$sallitut_tyypit = $sallitut_tyypit . "," . $tyyppi;
										}

									}
								}
								if(isset($data["uusi_liitetiedosto"]["Asiakirjan_tarkenne"]) && isset($data["uusi_liitetiedosto"]["Liitteen_nimi_en"]) && $data["uusi_liitetiedosto"]["Liitteen_nimi_en"]!="" && isset($data["uusi_liitetiedosto"]["Liitteen_nimi_fi"]) && $data["uusi_liitetiedosto"]["Liitteen_nimi_fi"]!=""){

									$fk_asiakirjahallinta_liite = $asiakirjahallinta_liiteDAO->luo_asiakirjahallinta_liite($data["uusi_liitetiedosto"]["Asiakirjan_tarkenne"], $lomakeDTO->ID, $data["uusi_liitetiedosto"]["Liitteen_nimi_fi"], $data["uusi_liitetiedosto"]["Liitteen_nimi_en"], null, 500000000, $sallitut_tyypit, $data["uusi_liitetiedosto"]["Lisatiedot_fi"], $data["uusi_liitetiedosto"]["Lisatiedot_en"], null, $kayt_id);

									// Määritetään liitetyypin pakollisuus
									if(isset($data["uusi_liitetiedosto"]["Pakollinen"]) && !empty($data["uusi_liitetiedosto"]["Pakollinen"])){

										if($data["uusi_liitetiedosto"]["Pakollinen"]=="pakollinen"){
											$asiakirjahallinta_saantoDAO->luo_asiakirjalle_saanto($fk_asiakirjahallinta_liite, "liite_on_pakollinen", $kayt_id);
										}
										if($data["uusi_liitetiedosto"]["Pakollinen"]=="ehdollisesti_pakollinen" && isset($data["uusi_liitetiedosto"]["Osio_ehto"]) && isset($data["uusi_liitetiedosto"]["Ehto"])){

											// Luodaan sääntö
											$asiakirjahallinta_saantoDTO = $asiakirjahallinta_saantoDAO->luo_asiakirjalle_saanto($fk_asiakirjahallinta_liite, "liite_on_ehdollisesti_pakollinen", $kayt_id);

											// Luodaan ehto säännölle
											$osio_lauseDAO = new Osio_lauseDAO($db);
											$osio_lauseDAO->luo_osio_lause(null, $asiakirjahallinta_saantoDTO->ID, $data["uusi_liitetiedosto"]["Ehto"], $data["uusi_liitetiedosto"]["Osio_ehto"], 1, $kayt_id);

										}
									}
								} else {
									throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Pakollisia tietoja puuttuu.");
								}
								$db->commit();
								$db = null;

							}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Pääkäyttäjä tallentaa liitetyypin
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ string $token },
			object{ 
				string[] $data{ 
					int $lomake_id,	
					string $liitetiedosto[]											
				}
			}		
		}
	 * @return string[] $dto {
			object{ 
				string[] $Istunto{ KayttajaDTO $Kayttaja } 
			}			 
		}
	 */
	public function tallenna_liitetyyppi($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$data = $parametrit["data"];
			$lomake_id = $data["lomake_id"];

			if(!is_null($lomake_id) && !is_null($data) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){

								$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;

								$db->beginTransaction();

								$lomakeDAO = new LomakeDAO($db);
								$asiakirjahallinta_liiteDAO = new Asiakirjahallinta_liiteDAO($db);
								$asiakirjahallinta_saantoDAO = new Asiakirjahallinta_saantoDAO($db);
								$osio_lauseDAO = new Osio_lauseDAO($db);

								$lomakeDTO = $lomakeDAO->hae_lomake($lomake_id);

								if(isset($data["liitetiedosto"]) && !empty($data["liitetiedosto"])){
									foreach($data["liitetiedosto"] as $fk_asiakirjahallinta_liite => $asiakirja_liite) {
										foreach($asiakirja_liite as $kentta => $arvo) {

											if($kentta=="Lisatiedot_en" || $kentta=="Lisatiedot_fi" || $kentta=="Asiakirjan_tarkenne" || $kentta=="Liitteen_nimi_fi" || $kentta=="Liitteen_nimi_en" || $kentta=="Liitteen_nimi_en"){
												$asiakirjahallinta_liiteDAO->paivita_liite_asiakirjan_tieto($fk_asiakirjahallinta_liite, $kentta, $arvo, $kayt_id);
											}

											if($kentta=="Sallittu_tyyppi"){

												$sallitut_tyypit = "";
												if(!empty($arvo)){
													foreach($arvo as $nro => $tyyppi) {

														if($tyyppi=="docx") $tyyppi = "doc,docx";
														if($tyyppi=="xlsx") $tyyppi = "xls,xlsx";

														if($sallitut_tyypit==""){
															$sallitut_tyypit = $tyyppi;
														} else {
															$sallitut_tyypit = $sallitut_tyypit . "," . $tyyppi;
														}

													}
												}

												$asiakirjahallinta_liiteDAO->paivita_liite_asiakirjan_tieto($fk_asiakirjahallinta_liite, "Sallitut_tiedostotyypit", $sallitut_tyypit, $kayt_id);

											}
											if($kentta=="Pakollinen"){

												$asiakirjahallinta_saannotDTO = $asiakirjahallinta_saantoDAO->hae_asiakirjan_saannot($fk_asiakirjahallinta_liite);
												$liite_on_pakollinen = false;
												$liite_on_ehd_pakollinen = false;
												$asiakirjahallinta_saantoDTO_pakollinen = null;
												$asiakirjahallinta_saantoDTO_ehd_pakollinen = null;

												if(!empty($asiakirjahallinta_saannotDTO)){
													for($i=0; $i < sizeof($asiakirjahallinta_saannotDTO); $i++){

														if($asiakirjahallinta_saannotDTO[$i]->Saanto=="liite_on_pakollinen"){
															$liite_on_pakollinen = true;
															$asiakirjahallinta_saantoDTO_pakollinen = $asiakirjahallinta_saannotDTO[$i];
														}
														if($asiakirjahallinta_saannotDTO[$i]->Saanto=="liite_on_ehdollisesti_pakollinen"){
															$liite_on_ehd_pakollinen = true;
															$asiakirjahallinta_saantoDTO_ehd_pakollinen = $asiakirjahallinta_saannotDTO[$i];
														}

													}
												}
												if(($arvo=="ei_pakollinen" || $arvo=="ehdollisesti_pakollinen") && $liite_on_pakollinen){ // Muutetaan ei pakolliseksi
													$asiakirjahallinta_saantoDAO->poista_asiakirjan_saanto($asiakirjahallinta_saantoDTO_pakollinen->ID, $kayt_id);
												}
												if(($arvo=="ei_pakollinen" || $arvo=="pakollinen") && $liite_on_ehd_pakollinen){

													$asiakirjahallinta_saantoDAO->poista_asiakirjan_saanto($asiakirjahallinta_saantoDTO_ehd_pakollinen->ID, $kayt_id);

													$osio_lauseDTO = $osio_lauseDAO->hae_asiakirjan_saannon_lause($asiakirjahallinta_saantoDTO_ehd_pakollinen->ID);
													$osio_lauseDAO->merkitse_osio_lause_poistetuksi($osio_lauseDTO->ID, $kayt_id);

												}
												if($arvo=="pakollinen" && !$liite_on_pakollinen){ // Muutetaan pakolliseksi
													$asiakirjahallinta_saantoDAO->luo_asiakirjalle_saanto($fk_asiakirjahallinta_liite, "liite_on_pakollinen", $kayt_id);
												}

												if($arvo=="ehdollisesti_pakollinen" && !$liite_on_ehd_pakollinen){ // Muutetaan ehdollisesti pakolliseksi

													$asiakirjahallinta_saantoDTO = $asiakirjahallinta_saantoDAO->luo_asiakirjalle_saanto($fk_asiakirjahallinta_liite, "liite_on_ehdollisesti_pakollinen", $kayt_id);

													// Luodaan ehto säännölle
													$osio_lauseDAO->luo_osio_lause(null, $asiakirjahallinta_saantoDTO->ID, $asiakirja_liite["Ehto"], $asiakirja_liite["Osio_ehto"], 1, $kayt_id);

												}

												if($arvo=="ehdollisesti_pakollinen" && $liite_on_ehd_pakollinen){ // Päivitetään ehdollisesti pakolliseksi

													$osio_lauseDTO = $osio_lauseDAO->hae_asiakirjan_saannon_lause($asiakirjahallinta_saantoDTO_ehd_pakollinen->ID);
													$osio_lauseDAO->paivita_osio_lauseen_tieto($osio_lauseDTO->ID, "Predikaatti", $asiakirja_liite["Ehto"], $kayt_id);
													$osio_lauseDAO->paivita_osio_lauseen_tieto($osio_lauseDTO->ID, "FK_Osio_muuttuja", $asiakirja_liite["Osio_ehto"], $kayt_id);

												}
											}
										}
									}
								} else {
									throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
								}
								
								$db->commit();
								$db = null;

							}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Hakija luo tutkimusryhmään uuden hakijan
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ string $token },	
			object{ int $hakemusversio_id },
			object{ string $sahkopostivarmenne } (optional),
			string[] $data{ 
				string $etunimi,
				string $sukunimi,
				string $sahkoposti,
				string $organisaatio,
				string $oppiarvo,
				string $osoite (optional),
				string $puhelin (optional),
				string $maa (optional),
				string[] $roolit,
				int $haetaanko_kayttolupaa
			}	
		}
	 * @return string[] $dto {
			object{ string[] $Istunto{ KayttajaDTO $Kayttaja } },
			object { boolean $sahkopostikutsu_lahetetty },
			object { KayttajaDTO $KayttajaDTO_Uusi_hakija },
			object { HakijaDTO $HakijaDTO_Uusi } (optional)
			
		}
	 */
	public function luo_hakija($syoteparametrit) {

		$dto = array();
		$dto["sahkopostikutsu_lahetetty"] = false;

		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemusversio_id = $parametrit["hakemusversio_id"];
			$token = $parametrit["token"];
			$etunimi = $parametrit["data"]["etunimi"];
			$sukunimi = $parametrit["data"]["sukunimi"];
			$sahkopostiosoite = $parametrit["data"]["sahkoposti"];
			$organisaatio = $parametrit["data"]["organisaatio"];
			$oppiarvo = $parametrit["data"]["oppiarvo"];
			$osoite = (isset($parametrit["data"]["osoite"]) ? $parametrit["data"]["osoite"] : null);
			$puhelin = (isset($parametrit["data"]["puhelin"]) ? $parametrit["data"]["puhelin"] : null);
			$maa = (isset($parametrit["data"]["maa"]) ? $parametrit["data"]["maa"] : null);
			$sahkopostivarmenne = $parametrit["sahkopostivarmenne"];
			$roolit = $parametrit["data"]["roolit"];
			$haetaan_kayttolupaa = (isset($parametrit["data"]["haetaanko_kayttolupaa"]) ? 1 : 0); 

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {

						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_hakija","kayt_id"=>$kayt_id, "hakemusversio_id"=>$hakemusversio_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$hakemusversioDAO = new HakemusversioDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$hakijaDAO = new HakijaDAO($db);
							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);

							$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusversio_id);

							$nyt = date_format(date_create(), 'Y-m-d H:i:s');
							$valittu_kayttajaDTO = $kayttajaDAO->hae_kayttaja_kayttajatunnuksella($sahkopostiosoite);

							if(!isset($valittu_kayttajaDTO->ID)){ // Luodaan uusi käyttäjä mikäli ei löydy

								// sahkopostivarmenne is not passed here, kayttaja is created with without it - and this sahkopostivarmenne will be added later, upon registration
								$valittu_kayttajaDTO = $kayttajaDAO->luo_kayttaja($sukunimi, $etunimi, $sahkopostiosoite, null, $puhelin, null, null, null, 0);
								
							}
							
							if(!is_null($sahkopostivarmenne) && !is_null($valittu_kayttajaDTO->ID) && $valittu_kayttajaDTO->ID!=$kayt_id){ 
								
								$hakijaDTO = $hakijaDAO->luo_hakija($valittu_kayttajaDTO->ID, $sukunimi, $etunimi, $sahkopostiosoite, $organisaatio, $oppiarvo, $haetaan_kayttolupaa, $nyt, null, $sahkopostivarmenne, $kayt_id);
							
								// Lisätään ei-pakolliset tiedot päivittämällä
								if(!is_null($osoite)) $hakijaDAO->paivita_hakijan_tieto($hakijaDTO->ID, "Osoite", $osoite);
								if(!is_null($puhelin)) $hakijaDAO->paivita_hakijan_tieto($hakijaDTO->ID, "Puhelin", $puhelin);
								if(!is_null($maa)) $hakijaDAO->paivita_hakijan_tieto($hakijaDTO->ID, "Maa", $maa);
							
							}
							
							if(isset($hakijaDTO) && is_array($roolit) && !empty($roolit)){
								for($i=0; $i < sizeof($roolit); $i++){
									$hakijan_rooliDAO->luo_hakijan_rooli($hakemusversioDTO, $hakijaDTO, $roolit[$i]);
								}
							}
								
							$dto["KayttajaDTO_Uusi_hakija"] = $valittu_kayttajaDTO; 
							if(isset($hakijaDTO)) $dto["HakijaDTO_Uusi"] = $hakijaDTO;

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @deprecated
	 * @desc Lupapalvelun tai viranomaisen pääkäyttäjä lisää järjestelmään uuden viranomaisen (toiminto ei ole käyttöliittymässä)
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function lisaa_viranomainen($syoteparametrit) {

		$dto = array();

		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$tallentaja_id = $parametrit["tallentaja_id"];
			$tallennettavan_kayttajatunnus = $parametrit["tallennettavan_kayttajatunnus"];
			$tallennettavan_vir_koodi = $parametrit["tallennettavan_vir_koodi"];
			$tallennettavan_roolit = $parametrit["tallennettavan_roolit"];

			if(!is_null($tallennettavan_roolit) && !is_null($tallentaja_id) && !is_null($tallennettavan_kayttajatunnus) && !is_null($tallennettavan_vir_koodi) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$tallentaja_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);

							if($tallennettavan_vir_koodi=="ei_viranomaista"){
								if($viranomaisen_rooliDTO = tarkista_kayttajan_viranomaisen_rooli($db, $tallentaja_id, "rooli_viranomaisen_paak")){
									$tallennettavan_vir_koodi = $viranomaisen_rooliDTO->Viranomaisen_koodi;
								} else {
									throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
								}
							}
							// Haetaan käyttäjä
							$kayttajaDTO = $kayttajaDAO->hae_kayttaja_kayttajatunnuksella($tallennettavan_kayttajatunnus);

							for($i=0; $i < sizeof($tallennettavan_roolit); $i++){

								// Tarkistetaan onko tallennettavaa roolia jo olemassa
								if(!$viranomaisen_rooliDAO->viranomaiskayttajalla_on_haettu_rooli($kayttajaDTO->ID, $tallennettavan_vir_koodi, $tallennettavan_roolit[$i])){

									$viranomaisen_rooliDTO_lisatty = $viranomaisen_rooliDAO->lisaa_viranomaisen_rooli($kayttajaDTO->ID, $tallennettavan_vir_koodi, $tallennettavan_roolit[$i], $tallentaja_id);

									if(isset($viranomaisen_rooliDTO_lisatty->ID)){
										$dto["Lisatyt_kayttajat"][$i]["Kayttaja_lisatty"] = true;
									} else {
										$dto["Lisatyt_kayttajat"][$i]["Kayttaja_lisatty"] = false;
									}
								}
							}
							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Aineistonmuodostukseen liittyvät formien tiedot tallennetaan tietokantaan
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ int $hakemus_id }, 
			string[] $data{
				string $aineisto_lahetetty,
				string $aineistonmuodostuksen_hinta
			}
		}
	 * @return string[] $dto { 
			object{ string[] $Istunto{ KayttajaDTO $Kayttaja } },
			object { boolean $Aineistonmuodostus_tallennettu } 
		}
	 */
	public function tallenna_aineistonmuodostus($syoteparametrit) {

		$dto = array();
		$dto["Aineistonmuodostus_tallennettu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];

			if(!is_null($hakemus_id) && !is_null($kayt_id)){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_aineistonmuodostaja","hakemus_id"=>$hakemus_id, "kayt_id"=>$kayt_id))){

							$db->beginTransaction();

							$paatosDAO = new PaatosDAO($db);
							$aineistotilausDAO = new AineistotilausDAO($db);
							$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);

							$paatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemus_id);
							$paatosDTO->AineistotilausDTO = $aineistotilausDAO->hae_aineistotilaus_paatokselle($paatosDTO->ID);

							if(isset($parametrit["data"]["aineisto_lahetetty"]) && $parametrit["data"]["aineisto_lahetetty"]!=""){

								$aineisto_lahetetty_js = $parametrit["data"]["aineisto_lahetetty"];
								$aineisto_lahetetty = strtotime($aineisto_lahetetty_js);
								$aineisto_lahetetty = date('Y-m-d', $aineisto_lahetetty );

								$aineistotilausDAO->maarita_aineistotilauksen_lahetyspvm($paatosDTO->AineistotilausDTO->ID, $aineisto_lahetetty);

								// Aineistotilauksen tila -> toimitettu
								$aineistotilauksen_tilaDAO->lisaa_aineistotilaukseen_tila($paatosDTO->AineistotilausDTO->ID, "aint_toimitettu", $kayt_id);

							}
							if(isset($parametrit["data"]["aineistonmuodostuksen_hinta"])){  
								$aineistotilausDAO->maarita_aineistotilaukselle_hinta($paatosDTO->AineistotilausDTO->ID, $parametrit["data"]["aineistonmuodostuksen_hinta"]);
							}
							if($db->commit()) $dto["Aineistonmuodostus_tallennettu"] = true;

							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Hakemuksen (ID: hakemus_id) päätökseen liittyvät tiedot tallennetaan tietokantaan käyttäjän (ID: kayt_id) toimesta
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ int $hakemus_id }, 		
			object{ string $kayttajan_rooli }, 	
			object{ string $token },
			string[] $data{
				int $laheta_paatos_hyvaksyttavaksi (optional),
				int $laheta_hyvaksymispyynto (optional),	
				int $allekirjoita_paatos (optional),
				int $palauta_paatos_kasiteltavaksi (optional),
				int $laheta_lausunto_tiedoksi (optional),
			}	
		}
	 * @return string[] $dto {
			object{ string[] $Istunto{ KayttajaDTO $Kayttaja } },
			object { boolean $Paatos_tallennettu },
			object { HakijaDTO|null $HakijaDTO_Yhteyshenkilo },	
			object { PaatosDTO|null $Allekirjoitettu_PaatosDTO }
		}
	 */
	public function tallenna_paatos($syoteparametrit) {

		$dto = array();
		$dto["Paatos_tallennettu"] = false;
		$dto["HakijaDTO_Yhteyshenkilo"] = null;
		$dto["Allekirjoitettu_PaatosDTO"] = null;
		$paatos_lahetetaan_hyvaksyttavaksi = false;
		$paatos_allekirjoitetaan = false;
		$paatos_palautetaan_kasittelyyn = false;
		$laheta_lausunto_tiedoksi = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);

			if(isset($parametrit["data"]["laheta_paatos_hyvaksyttavaksi"]) && !is_null($parametrit["data"]["laheta_paatos_hyvaksyttavaksi"])){
				$paatos_lahetetaan_hyvaksyttavaksi = true;
			} else if(isset($parametrit["data"]["laheta_hyvaksymispyynto"]) && !is_null($parametrit["data"]["laheta_hyvaksymispyynto"])){
				$paatos_lahetetaan_hyvaksyttavaksi = true;
			} else if(isset($parametrit["data"]["allekirjoita_paatos"]) && !is_null($parametrit["data"]["allekirjoita_paatos"])){
				$paatos_allekirjoitetaan = true;
			} else if(isset($parametrit["data"]["palauta_paatos_kasiteltavaksi"]) && !is_null($parametrit["data"]["palauta_paatos_kasiteltavaksi"])){
				$paatos_palautetaan_kasittelyyn = true;
			} else if(isset($parametrit["data"]["laheta_lausunto_tiedoksi"]) && !is_null($parametrit["data"]["laheta_lausunto_tiedoksi"])){
				$laheta_lausunto_tiedoksi = true;
			} else{
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
								
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];
			$kayttajan_rooli = $parametrit["kayttajan_rooli"];

			if(!is_null($kayttajan_rooli) && !is_null($hakemus_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db, array("sallitut_roolit"=>array("rooli_paattava","rooli_kasitteleva","rooli_eettisen_puheenjohtaja","rooli_eettisensihteeri"),"kayttajan_rooli"=>$kayttajan_rooli, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							
							$viranomaisen_rooliDTO = $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO;

							$db->beginTransaction();

							$paatosDAO = new PaatosDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							$aineistotilausDAO = new AineistotilausDAO($db);
							$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);
							$kayttolupaDAO = new KayttolupaDAO($db);
							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$paattajaDAO = new PaattajaDAO($db);
							$hakijaDAO = new HakijaDAO($db);
							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);

							$viranomaisroolin_koodi = $viranomaisen_rooliDTO->Viranomaisroolin_koodi;
							$paatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemus_id);							
														
							if(kayttaja_on_paatoksen_valmistelija_tai_paattaja($db, $kayt_id, $paatosDTO->ID)){
								
								$paatosDTO->HakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($paatosDTO->HakemusDTO->ID);
								$paatosDTO->AineistotilausDTO = $aineistotilausDAO->hae_aineistotilaus_paatokselle($paatosDTO->ID);
								$paatosDTO->HakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($paatosDTO->HakemusDTO->HakemusversioDTO->ID);
								
								if($paatos_lahetetaan_hyvaksyttavaksi){
									
									$hakemuksen_tilaDAO->maarita_hakemuksen_tiloista_tamanhetkiset_pois($hakemus_id);
									$hakemuksen_tilaDAO->luo_hakemuksen_tila($hakemus_id, $kayt_id, "hak_val");
									
								} else if($laheta_lausunto_tiedoksi){
								
									$hakemuksen_tilaDAO->maarita_hakemuksen_tiloista_tamanhetkiset_pois($hakemus_id);

									if($hakemuksen_tilaDAO->luo_hakemuksen_tila($hakemus_id, $kayt_id, "hak_paat")){
										if($paatoksen_tilaDAO->luo_paatokselle_paatoksen_tila($paatosDTO->ID, $paatosDTO->Alustava_paatos, $kayt_id)){																						
											if($paatosDAO->paivita_paatoksen_tieto($paatosDTO->ID, "Paatospvm", date_format(date_create(), 'Y-m-d H:i:s'), $kayt_id)){
												
												$paatosDTO->Paatoksen_tilaDTO = $paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($paatosDTO->ID);											
												
												// Luodaan täydennyshakemus
												if($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_ehd_hyvaksytty" && $paatosDTO->Ehdollisen_paatoksen_tyyppi=="ehd_paat_hak"){
													$this->luo_hakemus(muotoile_soap_parametrit(array("taydennetty_hakemus"=>1,"hakemusversio_id"=>$paatosDTO->HakemusDTO->HakemusversioDTO->ID,"lomake_id"=>$paatosDTO->HakemusDTO->HakemusversioDTO->LomakeDTO->ID, "token"=>$parametrit["token"], "kayt_id"=>$parametrit["kayt_id"])));
												}
												
												$dto["Allekirjoitettu_PaatosDTO"] = $paatosDTO;
												
											}																							
										}
									}
								
								} else if($paatos_allekirjoitetaan){
								
									// Haetaan hakemuksen yhteyshenkilö								
									$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijan_rooli($paatosDTO->HakemusDTO->HakemusversioDTO->ID, "rooli_hak_yht");
									$hakijaDTO_Yhteyshenkilo = $hakijaDAO->hae_hakijan_tiedot($hakijan_roolitDTO[0]->HakijaDTO->ID);
									$hakijaDTO_Yhteyshenkilo->KayttajaDTO = $kayttajaDAO->hae_kayttajan_tiedot($hakijaDTO_Yhteyshenkilo->KayttajaDTO->ID);
									$dto["HakijaDTO_Yhteyshenkilo"] = $hakijaDTO_Yhteyshenkilo;

									// Merkitse allekirjoitus
									$paattajaDTO = $paattajaDAO->hae_paatoksen_paattaja($paatosDTO->ID, $kayt_id);

									$paattajaDAO->paivita_paattajan_tieto($paattajaDTO->ID, "Paatos_allekirjoitettu", 1, $kayt_id);

									// Vaihda hakemuksen ja päätöksen tilaa, mikäli allekirjoittaneiden lkm == päättäjien lkm
									$paattajatDTO = $paattajaDAO->hae_paatoksen_paattajat($paatosDTO->ID);

									$p=0;

									for($i=0; $i < sizeof($paattajatDTO); $i++){
										if($paattajatDTO[$i]->Paatos_allekirjoitettu==1) $p++;
									}
									
									if(sizeof($paattajatDTO)==$p){

										$hakemuksen_tilaDAO->maarita_hakemuksen_tiloista_tamanhetkiset_pois($hakemus_id);

										if($hakemuksen_tilaDAO->luo_hakemuksen_tila($hakemus_id, $kayt_id, "hak_paat")){
											if($paatoksen_tilaDAO->luo_paatokselle_paatoksen_tila($paatosDTO->ID, $paatosDTO->Alustava_paatos, $kayt_id)){
												
												if($paatosDTO->HakemusDTO->HakemusversioDTO->LomakeDTO->ID==1){ // Aineistotilaus vain käyttölupahakemukseen
													if($aineistotilausDTO = $aineistotilausDAO->lisaa_aineistotilaus($paatosDTO->ID, $kayt_id)){
														$aineistotilauksen_tilaDAO->lisaa_aineistotilaukseen_tila($aineistotilausDTO->ID, "aint_keskenerainen", $kayt_id);													
													}												
												}
												
												if($paatosDAO->paivita_paatoksen_tieto($paatosDTO->ID, "Paatospvm", date_format(date_create(), 'Y-m-d H:i:s'), $kayt_id)){
													$paatosDTO->Paatoksen_tilaDTO = $paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($paatosDTO->ID);
													$dto["Allekirjoitettu_PaatosDTO"] = $paatosDTO;
												}											

											}
										}
										// todo käyttölupien päivitys(?)

									}
								
								} else if($paatos_palautetaan_kasittelyyn){
									
									$hakemuksen_tilaDAO->maarita_hakemuksen_tiloista_tamanhetkiset_pois($hakemus_id);
									$hakemuksen_tilaDAO->luo_hakemuksen_tila($hakemus_id, $kayt_id, "hak_kas");

									$paattajatDTO = $paattajaDAO->hae_paatoksen_paattajat($paatosDTO->ID);

									for($i=0; $i < sizeof($paattajatDTO); $i++){
										$paattajaDAO->merkitse_paattaja_poistetuksi($paattajatDTO[$i]->ID, $kayt_id);
									}
									
									$paatosDAO->paivita_paatoksen_tieto($paatosDTO->ID, "Palautettu_kasittelyyn", 1, $kayt_id);								
									
								} else {
									throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
								}
																						
								if($db->commit()) $dto["Paatos_tallennettu"] = true;

								$db = null;
								
							} else {
								throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
							}
						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Viranomainen ottaa hakemuksen käsittelyyn. Alustetaan päätös ja sen alitaulut. Merkitään myös mahdollinen aiempi hakemus korvatuksi.
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ int $hakemus_id }, 		
			object{ int $kasittelija }, 	
			object{ string $kayttajan_rooli },
			object{ string $token }	
		}
	 * @return string[] $dto {
			object{ string[] $Istunto{ KayttajaDTO $Kayttaja } }, 
			object { boolean $Hakemus_kasittelyssa }
		}
	 */
	public function ota_hakemus_viranomaiskasittelyyn($syoteparametrit) {

		$dto = array();
		$dto["Hakemus_kasittelyssa"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];
			$kasittelija = $parametrit["kasittelija"];
			$kayttajan_rooli = $parametrit["kayttajan_rooli"]; // sallitut parametrit: rooli_eettisensihteeri, rooli_kasitteleva tai rooli_paattava

			if(!is_null($kayttajan_rooli) && !is_null($hakemus_id) && !is_null($kayt_id) && !is_null($parametrit["token"]) && ($kayttajan_rooli=="rooli_eettisensihteeri" || $kayttajan_rooli=="rooli_kasitteleva" || $kayttajan_rooli=="rooli_paattava")){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("sallitut_roolit"=>array("rooli_paattava","rooli_kasitteleva","rooli_eettisen_puheenjohtaja","rooli_eettisensihteeri"),"kayttajan_rooli"=>$kayttajan_rooli, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$viranomaisen_rooliDTO = $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO;

							$db->beginTransaction();

							$hakemusDAO = new HakemusDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$paatosDAO = new PaatosDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);

							$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemus_id);
							$hakemusDTO->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemus_id);

							if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_lah"){
								$hakemusDTO = siirra_hakemus_kasittelyyn($db, $hakemusDTO, $kayt_id, $kasittelija, true);
							} else { // Päivitetään käsittelijä
								if(!is_null($kasittelija)){
									$hakemusDTO->PaatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemus_id);
									$paatosDAO->paivita_paatoksen_tieto($hakemusDTO->PaatosDTO->ID, "Kasittelija", $kasittelija, $kayt_id);
								}
							}

							$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);

							// Tarkistetaan löytyykö aiempia versioita. Jos löytyy, niin merkitään hakemus korvatuksi 
							if($hakemusDTO->HakemusversioDTO->Versio > 1){

								$hakemusversiotDTO = $hakemusversioDAO->hae_muutoshakemuksen_aiemmat_hakemusversiot($hakemusDTO->HakemusversioDTO->TutkimusDTO->ID, $hakemusDTO->HakemusversioDTO->Versio);

								for($i=0; $i < sizeof($hakemusversiotDTO); $i++){

									$hakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset_viranomaiselle($hakemusversiotDTO[$i]->ID, $hakemusDTO->Viranomaisen_koodi);

									for($j=0; $j < sizeof($hakemuksetDTO); $j++){
										$hakemuksen_tilaDAO->maarita_hakemuksen_tiloista_tamanhetkiset_pois($hakemuksetDTO[$j]->ID);
										$hakemuksen_tilaDAO->luo_hakemuksen_tila($hakemuksetDTO[$j]->ID, $kayt_id, "hak_korvattu");
									}
								}
							}
							if($db->commit()) $dto["Hakemus_kasittelyssa"] = true;

							$db = null;

						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Aineistonmuodostaja ottaa aineistotilauksen käsittelyyn
	 * @param string[] $syoteparametrit {
			object{ int $kayt_id }, 
			object{ int $aineistotilaus_id },
			object{ int $kasittelija }	
		}
	 * @return string[] $dto {
			object{ string[] $Istunto{ KayttajaDTO $Kayttaja } }, 
			object { boolean $Aineistotilaus_kasittelyssa }		 
		}
	 */
	public function ota_aineistotilaus_kasittelyyn($syoteparametrit) {

		$dto = array();
		$dto["Aineistotilaus_kasittelyssa"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$aineistotilaus_id = $parametrit["aineistotilaus_id"];
			$kasittelija = $parametrit["kasittelija"];

			if(!is_null($aineistotilaus_id) && !is_null($kayt_id)){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_aineistonmuodostaja", "kayt_id"=>$kayt_id))){

							$db->beginTransaction();

							$aineistotilausDAO = new AineistotilausDAO($db);
							$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);

							$aineistotilausDTO = $aineistotilausDAO->hae_aineistotilauksen_tiedot($aineistotilaus_id);
							$aineistotilausDTO->Aineistotilauksen_tilaDTO = $aineistotilauksen_tilaDAO->hae_tilan_koodi_aineistotilauksen_avaimella($aineistotilausDTO->ID);

							if($aineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_kas" || $aineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_rekl" || $aineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_uusi"){
								if($aineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi!="aint_kas") $aineistotilauksen_tilaDAO->lisaa_aineistotilaukseen_tila($aineistotilausDTO->ID, "aint_kas", $kayt_id);
								if($aineistotilausDAO->paivita_aineistotilauksen_tieto($aineistotilausDTO->ID, "Aineistonmuodostaja", $kasittelija, $kayt_id)) $dto["Aineistotilaus_kasittelyssa"] = true; 
							}
							$db->commit();

							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @deprecated	 
	 * @desc Metodilla viranomainen määrittää näkyykö lausunto hakijoille (toiminto on poissa käytöstä atm)
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function vaihda_lausunnon_nakyvyys($syoteparametrit){

		$dto = array();
		$dto["Lausunnon_nakyvyys_paivitetty"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$lausunto_id = $parametrit["lausunto_id"];
			$naytetaankoLausuntoHakijoille = $parametrit["naytetaankoLausuntoHakijoille"];

			if(!is_null($naytetaankoLausuntoHakijoille) && !is_null($lausunto_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$lausuntoDAO = new LausuntoDAO($db);

							if($lausuntoDAO->muuta_lausunnon_nakyvyys($lausunto_id, $naytetaankoLausuntoHakijoille)){
								$dto["Lausunnon_nakyvyys_paivitetty"] = true;
							}
							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function laheta_taydennysasiakirjat($syoteparametrit){

		$dto = array();
		$dto["Liitetiedostot_tallennettu"] = array();
		$dto["Hakemuksen_tila_paivitetty"] = false;
		$dto["Paatoksen_tila_paivitetty"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$fk_paatos = $parametrit["fk_paatos"];
			$tiedostot = $parametrit["tiedostot"];

			if(!is_null($fk_paatos) && !is_null($tiedostot) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_hakija","kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$paatosDAO = new PaatosDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							
							$paatosDTO = $paatosDAO->hae_paatoksen_tiedot($fk_paatos);
							$paatosDTO->HakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($paatosDTO->HakemusDTO->ID);
							
							$dto = dto_taulukkomuotoon($this->tallenna_liitetiedostot(muotoile_soap_parametrit(array("liite_tallennuskohde"=>"FK_Hakemusversio", "tiedostot"=>$tiedostot, "fk_kohde"=>$paatosDTO->HakemusDTO->HakemusversioDTO->ID, "token"=>$parametrit["token"], "kayt_id"=>$kayt_id))));	
							
							if(isset($dto["Liitetiedostot_tallennettu"])){
				
								$tallennettuja = 0;
										
								for($i=0; $i < sizeof($dto["Liitetiedostot_tallennettu"]); $i++){
									if($dto["Liitetiedostot_tallennettu"][$i]["tallennettu"]) $tallennettuja++;
								}		

								if($tallennettuja > 0){									
																											
									$dto_tilat = paivita_tilatiedot($db, array("fk_paatos"=>$paatosDTO->ID, "paatos_uusi_tila"=>"paat_tila_kesken", "fk_hakemus"=>$paatosDTO->HakemusDTO->ID, "hakemus_uusi_tila"=>"hak_kas", "kayt_id"=>$kayt_id));
									
									if(isset($dto_tilat["Hakemuksen_tila_paivitetty"])) $dto["Hakemuksen_tila_paivitetty"] = $dto_tilat["Hakemuksen_tila_paivitetty"];
									if(isset($dto_tilat["Paatoksen_tila_paivitetty"])) $dto["Paatoksen_tila_paivitetty"] = $dto_tilat["Paatoksen_tila_paivitetty"];
									
									// Hakemusta on täydennetty, joten sen tyyppi muutetaan täydennetyksi hakemukseksi
									if($dto["Hakemuksen_tila_paivitetty"] && $dto["Paatoksen_tila_paivitetty"]) $hakemusversioDAO->paivita_hakemusversion_tieto($paatosDTO->HakemusDTO->HakemusversioDTO->ID, "Hakemuksen_tyyppi", "tayd_hak");
																											
								}	
				
							}							
							
							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters1");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}	
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_liitetiedostot($syoteparametrit){

		$dto = array();
		$dto["Liitetiedostot_tallennettu"] = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$tiedostot = $parametrit["tiedostot"];
			$liite_tallennuskohde = $parametrit["liite_tallennuskohde"];
			$fk_kohde = $parametrit["fk_kohde"];

			if(!is_null($fk_kohde) && !is_null($liite_tallennuskohde) && !is_null($tiedostot) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							if(is_array($tiedostot)){																
								for($i=0; $i < sizeof($tiedostot); $i++){
									
									$dto["Liitetiedostot_tallennettu"][$i]["name"] = $tiedostot[$i]["name"];
									$dto["Liitetiedostot_tallennettu"][$i]["tallennettu"] = false;
									
									if($liite_tallennuskohde=="FK_Hakemusversio"){																		
										$dto_tallennus = dto_taulukkomuotoon($this->tallenna_hakemusversioon_liitetiedosto(muotoile_soap_parametrit(array("liitteen_koodi"=>53, "tiedosto"=>$tiedostot[$i]["file"], "name"=>$tiedostot[$i]["name"],"hakemusversio_id"=>$fk_kohde, "token"=>$parametrit["token"], "kayt_id"=>$kayt_id))));									
										if(isset($dto_tallennus["Liitetiedosto_tallennettu"]) && $dto_tallennus["Liitetiedosto_tallennettu"]) $dto["Liitetiedostot_tallennettu"][$i]["tallennettu"] = true;																														
									}
									
								}								
							}							
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}

			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters3");
			}

		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}	
	
	/**
	 * @WebMethod
	 * @desc Funktiolla tallennetaan liitetiedosto palvelimelle sekä liitetiedoston tiedot tietokantaan
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_hakemusversioon_liitetiedosto($syoteparametrit){

		$dto = array();
		$dto["Liitetiedosto_tallennettu"] = false;
		$dto["Uusi_liite_ID"] = null;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemusversio_id = $parametrit["hakemusversio_id"];
			$tiedosto = $parametrit["tiedosto"];
			$liite = $parametrit["name"];
			$liitteen_koodi = $parametrit["liitteen_koodi"];

			if(!is_null($liite) && !is_null($liitteen_koodi) && !is_null($tiedosto) && !is_null($hakemusversio_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_hakija","kayt_id"=>$kayt_id, "token"=>$parametrit["token"], "hakemusversio_id"=>$hakemusversio_id))){

							$db->beginTransaction();

							$liiteDAO = new LiiteDAO($db);
							$hakemusversion_liiteDAO = new Hakemusversion_liiteDAO($db);
							$asiakirjahallinta_liiteDAO = new Asiakirjahallinta_liiteDAO($db);

							$data=base64_decode($tiedosto);
							$filetype = pathinfo($liite,PATHINFO_EXTENSION);

							// Tiedoston koon tarkistus
							if(filesize($data) > MAKSIMI_LIITETIEDOSTON_KOKO){
								$dto["Liitetiedoston_tallennus_info"] = "Tiedosto on liian suuri.";
								return muodosta_dto($dto);
							}
							
							// Salli vain tietyt tiedoston tyypit
							$tiedostotyyppi_sallittu = false;
							$sallitut_tiedostotyypit = "";
							
							if(is_numeric($liitteen_koodi)){

								$asiakirjahallinta_liiteDTO = $asiakirjahallinta_liiteDAO->hae_liite_asiakirjahallinnan_tiedot($liitteen_koodi);
								$sallitut_tiedostotyypit = $asiakirjahallinta_liiteDTO->Sallitut_tiedostotyypit;
							
								if(strpos($sallitut_tiedostotyypit, $filetype) !== false) $tiedostotyyppi_sallittu = true;
																	
							} else {
								if($filetype == "png" || $filetype == "pdf" || $filetype == "rtf" || $filetype == "doc" || $filetype == "docx" || $filetype == "xls" || $filetype == "xlsx" || $filetype == "wpd" || $filetype == "jpg" || $filetype == "txt") $tiedostotyyppi_sallittu = true; 																
								$sallitut_tiedostotyypit = "png, pdf, rtf, doc, docx, xls, xlsx, wpd, jpg, txt";
							}

							if($tiedostotyyppi_sallittu) {

								$liitteen_tyyppi_loytynyt = false;
								$hakemusversion_liitteetDTO = $hakemusversion_liiteDAO->hae_hakemusversion_liitteet($hakemusversio_id);

								if($liitteen_koodi!=53){
									for($i=0; $i < sizeof($hakemusversion_liitteetDTO); $i++){

										$liiteDTO = $liiteDAO->hae_liite($hakemusversion_liitteetDTO[$i]->LiiteDTO->ID);

										if($liiteDTO->Liitteen_tyypin_koodi==$liitteen_koodi){

											$liitteen_tyyppi_loytynyt = true;

											// Merkitään aiemmat liitteet poistetuksi
											$hakemusversion_liiteDAO->merkitse_hakemusversion_liite_poistetuksi($liiteDTO->ID, $hakemusversio_id, $kayt_id);
											$liiteDAO->merkitse_liite_poistetuksi($liiteDTO->ID, $kayt_id);

											if($fk_liite = $liiteDAO->lisaa_liitetiedosto($liite, $liitteen_koodi, $data, $filetype, "Luonnos", $liiteDTO->Versio + 1, $kayt_id)){
												$hakemusversion_liiteDAO->lisaa_hakemusversion_liitetiedosto($hakemusversio_id, $fk_liite);
												$dto["Liitetiedosto_tallennettu"] = true;
												$dto["Uusi_liite_ID"] = $fk_liite;
											}
										}
										
									}
								}
								
								if(!$liitteen_tyyppi_loytynyt){
									if($fk_liite = $liiteDAO->lisaa_liitetiedosto($liite, $liitteen_koodi, $data, $filetype, "Luonnos", 1, $kayt_id)){
										$hakemusversion_liiteDAO->lisaa_hakemusversion_liitetiedosto($hakemusversio_id, $fk_liite);
										$dto["Liitetiedosto_tallennettu"] = true;
										$dto["Uusi_liite_ID"] = $fk_liite;
									}
								}
								
							} else {
								$dto["Liitetiedoston_tallennus_info"] = "Sallitut tiedostotyypit ovat: " . $sallitut_tiedostotyypit;
								return muodosta_dto($dto);
							}

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}

			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}

		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Funktiolla tallennetaan lausunnon liitetiedosto palvelimelle sekä liitetiedoston tiedot tietokantaan
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_lausunnon_liitetiedosto($syoteparametrit){

		$dto = array();
		$dto["Liitetiedosto_tallennettu"] = false;
		$dto["Liitetiedosto_ID"] = null;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$lausunto_id = $parametrit["lausunto_id"];
			$tiedosto = $parametrit["tiedosto"];
			$liite = $parametrit["name"];

			if(!is_null($liite) && !is_null($tiedosto) && !is_null($lausunto_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_lausunnonantaja","kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$liiteDAO = new LiiteDAO($db);
							$lausunnon_liiteDAO = new Lausunnon_liiteDAO($db);

							$data=base64_decode($tiedosto);
							$filetype = pathinfo($liite,PATHINFO_EXTENSION);

							// Tiedoston koon tarkistus
							if(filesize($data) > MAKSIMI_LIITETIEDOSTON_KOKO){
								$dto["Liitetiedoston_tallennus_info"] = "Tiedosto on liian suuri.";
								return muodosta_dto($dto);
							}

							// Salli vain tietyt tiedoston tyypit
							if($filetype == "png" || $filetype == "pdf" || $filetype == "rtf" || $filetype == "doc" || $filetype == "docx" || $filetype == "xls" || $filetype == "xlsx" || $filetype == "wpd" || $filetype == "jpg" || $filetype == "txt") {
								if($fk_liite = $liiteDAO->lisaa_liitetiedosto($liite, "liite_lausunto", $data, $filetype, "Luonnos", 1, $kayt_id)){
									$lausunnon_liiteDAO->lisaa_lausunnon_liitetiedosto($lausunto_id, $fk_liite);
									$dto["Liitetiedosto_tallennettu"] = true;
									$dto["Liitetiedosto_ID"] = $fk_liite;
								}
							} else {
								$dto["Liitetiedoston_tallennus_info"] = "Sallitut tiedostotyypit ovat: png, pdf, rtf, doc, docx, xls, xlsx, wpd, jpg ja txt";
								return muodosta_dto($dto);
							}

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}

			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}

		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Funktiolla tallennetaan päätöksen liitetiedosto palvelimelle 
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_paatoksen_liitetiedosto($syoteparametrit){

		$dto = array();
		$dto["Liitetiedosto_tallennettu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$paatos_id = $parametrit["paatos_id"];
			$tiedosto = $parametrit["tiedosto"];
			$liite = $parametrit["name"];
			$liitteen_nimi = $parametrit["liitteen_nimi"];

			if(!is_null($liite) && !is_null($tiedosto) && !is_null($paatos_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							if(kayttaja_on_paatoksen_valmistelija_tai_paattaja($db, $kayt_id, $paatos_id)){

								$db->beginTransaction();

								$liiteDAO = new LiiteDAO($db);
								$paatoksen_liiteDAO = new Paatoksen_liiteDAO($db);
								$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);

								if($paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($paatos_id)->Paatoksen_tilan_koodi=="paat_tila_kesken"){

									$data=base64_decode($tiedosto);
									$filetype = pathinfo($liite,PATHINFO_EXTENSION);

									// Tiedoston koon tarkistus
									if(filesize($data) > MAKSIMI_LIITETIEDOSTON_KOKO){
										$dto["Liitetiedoston_tallennus_info"] = "Virhe: Tiedosto on liian suuri.";
										return muodosta_dto($dto);
									}

									// Salli vain tietyt tiedoston tyypit
									if($filetype == "png" || $filetype == "pdf" || $filetype == "rtf" || $filetype == "doc" || $filetype == "docx" || $filetype == "xls" || $filetype == "xlsx" || $filetype == "wpd" || $filetype == "jpg" || $filetype == "txt") {
										if($fk_liite = $liiteDAO->lisaa_liitetiedosto($liite, "liite_paatos", $data, $filetype, "Luonnos", 1, $kayt_id)){
											$paatoksen_liiteDAO->lisaa_paatoksen_liitetiedosto($paatos_id, $fk_liite, $liitteen_nimi);
											$dto["Liitetiedosto_tallennettu"] = true;
										}
									} else {
										$dto["Liitetiedoston_tallennus_info"] = "Virhe: Sallitut tiedostotyypit ovat: png, pdf, rtf, doc, docx, xls, xlsx, wpd, jpg ja txt";
										return muodosta_dto($dto);
									}
								} else {
									$dto["Liitetiedoston_tallennus_info"] = "Virhe: Käyttäjä ei ole hakemuksen käsittelijä tai päättäjä.";
								}
								$db->commit();
								$db = null;

							} else {
								throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
							}
						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}

			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}

		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Metodilla hakija tallentaa keskeneräisen hakemuksen
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_hakemus($syoteparametrit){

		$dto = array();
		$dto["Hakemusversio_tallennettu"] = false;
		$tietoja_muutettu = false;

		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemusversio_id = $parametrit["hakemusversio_id"];
			$data = $parametrit["data"];

			if(!is_null($data) && !is_null($hakemusversio_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_hakija","kayt_id"=>$kayt_id, "token"=>$parametrit["token"], "hakemusversio_id"=>$hakemusversio_id))){

							$db->beginTransaction();

							$hakemusversioDAO = new HakemusversioDAO($db);
							$hakemusversion_tilaDAO = new Hakemusversion_tilaDAO($db);
							$jarjestelman_hakijan_roolitDAO = new Jarjestelman_hakijan_roolitDAO($db);

							// Tallennus on mahdollista vain jos hakemusversio ei ole lukittu
							$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusversio_id);
							$hakemusversioDTO->Hakemusversion_tilaDTO = $hakemusversion_tilaDAO->hae_hakemusversion_uusin_tila($hakemusversio_id);

							if($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken"){

								if($parametrit["tallennettavat_tiedot"]=="hakemus_aineisto" && !is_null($parametrit["haetun_aineiston_indeksi"])){
									
									$haettu_aineistoDAO = new Haettu_aineistoDAO($db);
									$haettu_aineistoDTO = $haettu_aineistoDAO->hae_hakemusversion_haetun_aineiston_indeksin_aineisto($hakemusversio_id, $parametrit["haetun_aineiston_indeksi"]);										
									$fk_haettu_aineisto = $haettu_aineistoDTO->ID;
									
								}
									
								$osioDAO = new OsioDAO($db);
								$osio_sisaltoDAO = new Osio_sisaltoDAO($db);

								$tallennuskohde = $data["tallennuskohde"];
								$tallennuskohde_id = $data["tallennuskohde_id"];
								$tallennuskohde_nimi = $data["tallennuskohde_nimi"];
								$tallennuskohde_arvo = $data["tallennuskohde_arvo"];

								if(isset($data["tallennuskohde_kentta"])) $tallennuskohde_kentta = $data["tallennuskohde_kentta"];

								if($tallennuskohde=="lomake_tutkimuksen_nimi" && $tallennuskohde_kentta=="Tutkimuksen_nimi"){
									
									$hakemusversioDAO->paivita_hakemusversion_tieto($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo);
									$hakemusversioDTO->Tutkimuksen_nimi = $tallennuskohde_arvo;
									$tietoja_muutettu = true;
									
								}
									
								if($tallennuskohde=="muutoshakemus_tyyppi"){
									if($tallennuskohde_kentta=="Muun_muutoshakemuksen_tyypin_selite"){
										$hakemusversioDAO->paivita_hakemusversion_tieto($hakemusversio_id, $tallennuskohde_kentta, $tallennuskohde_arvo);
										$tietoja_muutettu = true;
									} else {
										if(isset($hakemusversioDTO->$tallennuskohde_kentta) && $hakemusversioDTO->$tallennuskohde_kentta==1){
											$hakemusversioDAO->paivita_hakemusversion_tieto($hakemusversio_id, $tallennuskohde_kentta, 0);
											$hakemusversioDAO->paivita_hakemusversion_tieto($hakemusversio_id, "Muun_muutoshakemuksen_tyypin_selite", "");
											$tietoja_muutettu = true;
										} else {
											$hakemusversioDAO->paivita_hakemusversion_tieto($hakemusversio_id, $tallennuskohde_kentta, 1);
											$tietoja_muutettu = true;
										}
									}
								}
									
								if($tallennuskohde=="hakijan_tiedot" || $tallennuskohde=="haetaanko_kayttolupaa"){

									$hakijaDAO = new HakijaDAO($db);

									if($tallennuskohde_id > 0){
										$hakijaDAO->paivita_hakijan_tieto($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo);
										$tietoja_muutettu = true;
									}
										
								}
									
								if($tallennuskohde=="sitoumus" && $tallennuskohde_arvo==1){
									
									$sitoumusDAO = new SitoumusDAO($db);
									$sitoumusDAO->tallenna_sitoumus($hakemusversioDTO->TutkimusDTO->ID, $kayt_id);
									
									$dto["Tallennettu_sitoumus"]["SitoumusDTO"] = $sitoumusDAO->hae_tutkimuksen_kayttajan_sitoumus($hakemusversioDTO->TutkimusDTO->ID, $kayt_id);									
									if(intval($dto["Tallennettu_sitoumus"]["SitoumusDTO"]->ID) > 0) $tietoja_muutettu = true;
									
								}
									
								if($tallennuskohde=="hakijan_rooli"){

									$hakijaDAO = new HakijaDAO($db);
									$hakijan_rooliDAO = new Hakijan_rooliDAO($db);

									if($tallennuskohde_id > 0){

										$hakijaDTO = $hakijaDAO->hae_hakijan_tiedot($tallennuskohde_id);
										$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_fk_hakijan_roolit($hakemusversio_id, $tallennuskohde_id);
										$rooli_valittu = false;
										$poistettava_hakijan_rooli_id = null;

										for($i=0; $i < sizeof($hakijan_roolitDTO); $i++){
											if($hakijan_roolitDTO[$i]->Hakijan_roolin_koodi==$tallennuskohde_arvo){
												$rooli_valittu = true;
												$poistettava_hakijan_rooli_id = $hakijan_roolitDTO[$i]->ID;
											}
										}
										
										if(!$rooli_valittu){
											$hakijan_rooliDAO->luo_hakijan_rooli($hakemusversioDTO, $hakijaDTO, $tallennuskohde_arvo);
											$tietoja_muutettu = true;
										} else {
											$hakijan_rooliDAO->poista_hakijan_rooli($poistettava_hakijan_rooli_id);
											$tietoja_muutettu = true;
										}
											
									}
										
								}
									
								if($tallennuskohde=="haettu_muuttuja"){

									$haettu_muuttujaDAO = new Haettu_muuttujaDAO($db);

									if($tallennuskohde_id > 0){ // $tallennuskohde_id = fk_haettu_luvan_kohde

										$haetut_muuttujatDTO = $haettu_muuttujaDAO->hae_haetun_luvan_kohteen_haetut_muuttujat($tallennuskohde_id);

										if($tallennuskohde_arvo=="valitse_kaikki" || $tallennuskohde_arvo=="poista_kaikki"){ // Tallennetaan kaikki muuttujat

											// Haetaan kaikki aineiston muuttujat
											$muuttujaDAO = new MuuttujaDAO($db);
											$haettu_luvan_kohdeDAO = new Haettu_luvan_kohdeDAO($db);
											$luvan_kohdeDAO = new Luvan_kohdeDAO($db);

											$haettu_luvan_kohdeDTO = $haettu_luvan_kohdeDAO->hae_haettu_luvan_kohde($tallennuskohde_id);
											$haettu_luvan_kohdeDTO->Luvan_kohdeDTO = $luvan_kohdeDAO->hae_luvan_kohde($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID);
											$muuttujatDTO = $muuttujaDAO->hae_luvan_kohteen_muuttujat($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier);

											for($i=0; $i < sizeof($muuttujatDTO); $i++){

												$muuttuja_valittu = false;
												$poistettava_haettu_muuttuja_id = null;

												for($j=0; $j < sizeof($haetut_muuttujatDTO); $j++){ // Tarkistetaan onko muuttuja valittu
													if($haetut_muuttujatDTO[$j]->Muuttujan_koodi==$muuttujatDTO[$i]->Tunnus){
														$muuttuja_valittu = true; 
														$poistettava_haettu_muuttuja_id = $haetut_muuttujatDTO[$j]->ID;
													} 
												}
												
												if($muuttuja_valittu && $tallennuskohde_arvo=="poista_kaikki"){ // Poistetaan haettu muuttuja
													$haettu_muuttujaDAO->merkitse_haettu_muuttuja_poistetuksi($poistettava_haettu_muuttuja_id, $kayt_id);
													if(!$tietoja_muutettu) $tietoja_muutettu = true;
												}
												
												if(!$muuttuja_valittu && $tallennuskohde_arvo=="valitse_kaikki"){ // Lisätään haettu muuttuja
													$haettu_muuttujaDAO->lisaa_haettu_muuttuja_haettuun_luvan_kohteeseen($tallennuskohde_id, $muuttujatDTO[$i]->Tunnus, "");
													if(!$tietoja_muutettu) $tietoja_muutettu = true;
												}
												
											}
											
										} else { // Tallennetaan valittu muuttuja

											$muuttuja_valittu = false;
											$poistettava_haettu_muuttuja_id = null;

											for($i=0; $i < sizeof($haetut_muuttujatDTO); $i++){
												if($haetut_muuttujatDTO[$i]->Muuttujan_koodi==$tallennuskohde_arvo){ 
													$muuttuja_valittu = true;
													$poistettava_haettu_muuttuja_id = $haetut_muuttujatDTO[$i]->ID;
												}
											}
											
											if(!$muuttuja_valittu){
												$haettu_muuttujaDAO->lisaa_haettu_muuttuja_haettuun_luvan_kohteeseen($tallennuskohde_id, $tallennuskohde_arvo, "");
												$tietoja_muutettu = true;
											} else {
												$haettu_muuttujaDAO->merkitse_haettu_muuttuja_poistetuksi($poistettava_haettu_muuttuja_id, $kayt_id);
												$tietoja_muutettu = true;
											}

										}
										
									}
									
								}
								
								if($tallennuskohde=="haettu_luvan_kohde"){

									$haettu_luvan_kohdeDAO = new Haettu_luvan_kohdeDAO($db);
									$luvan_kohdeDAO = new Luvan_kohdeDAO($db);

									if($tallennuskohde_kentta!="Poistettava_haettu_luvan_kohde"){
										if($tallennuskohde_id!=0){ // Päivitetään olemassaoleva Haettu_luvan_kohde
										
											$haettu_luvan_kohdeDAO->paivita_tieto_haetun_aineiston_haettuun_lupaan($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo);
											$tietoja_muutettu = true;
											
										} else { // Luodaan uusi Haettu_luvan_kohde
											if($tallennuskohde_kentta=="Uusi_haettu_luvan_kohde"){
												$dto["Uusi_alustettu_tieto"]["Haettu_luvan_kohdeDTO"] = $haettu_luvan_kohdeDAO->alusta_haettu_luvan_kohde($fk_haettu_aineisto, $tallennuskohde_nimi, $kayt_id);
												$tietoja_muutettu = true;
											} else {
												$dto["Uusi_tallennettu_tieto"]["Haettu_luvan_kohdeDTO"] = $haettu_luvan_kohdeDAO->lisaa_tieto_haetun_aineiston_haettuun_lupaan($fk_haettu_aineisto, $tallennuskohde_nimi, $tallennuskohde_kentta, $tallennuskohde_arvo, $kayt_id);
												$tietoja_muutettu = true;
											}
										}
									} else {
										$dto["Luvan_kohde_poistettu"] = $haettu_luvan_kohdeDAO->merkitse_haettu_luvan_kohde_poistetuksi($tallennuskohde_id, $kayt_id);
										if($dto["Luvan_kohde_poistettu"]) $tietoja_muutettu = true;
									}
									
								}
									
								if($tallennuskohde=="organisaatio"){

									$osallistuva_organisaatioDAO = new Osallistuva_organisaatioDAO($db);

									if($tallennuskohde_id==0){
										
										$tallennuskohde_id = $osallistuva_organisaatioDAO->luo_organisaatio($hakemusversio_id, $kayt_id);
										$dto["Uusi_tutkimuksen_organisaatio_id"] = $tallennuskohde_id;
										$tietoja_muutettu = true;
										
									}
										
									if($tallennuskohde_kentta=="MTA_allekirjoittaja" || $tallennuskohde_kentta=="Y_tunnus" || $tallennuskohde_kentta=="Rekisterinpitaja" || $tallennuskohde_kentta=="Rooli" || $tallennuskohde_kentta=="Edustajan_email" || $tallennuskohde_kentta=="Edustaja" || $tallennuskohde_kentta=="Osoite" || $tallennuskohde_kentta=="Nimi"){										
										$osallistuva_organisaatioDAO->paivita_organisaation_tieto($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo, $kayt_id);
										$tietoja_muutettu = true;									
									}
										
									if($tallennuskohde_kentta=="Poistettava_organisaatio"){
										if($osallistuva_organisaatioDAO->merkitse_organisaatio_poistetuksi($tallennuskohde_id, $kayt_id)){
											$dto["Organisaatio_poistettu"] = true;
											$tietoja_muutettu = true;
										}
									}
										
								}
								
								if($tallennuskohde=="haettu_aineisto"){
									if(!is_null($tallennuskohde_id) && ($tallennuskohde_kentta=="Poimitaanko_verrokeille_samat" || $tallennuskohde_kentta=="Poimitaanko_viitehenkiloille_samat")){
										$haettu_aineistoDAO->paivita_haetun_aineiston_kentta($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo);
										$tietoja_muutettu = true;
									}
								}
									
								if($tallennuskohde=="osio"){

									$osioDTO = $osioDAO->hae_osio($tallennuskohde_id);

									if($parametrit["tallennettavat_tiedot"]=="hakemus_aineisto"){
										$osio_sisaltoDTO = $osio_sisaltoDAO->hae_haetun_aineiston_ja_osion_sisalto($fk_haettu_aineisto, $osioDTO->ID);
									} else {
										$osio_sisaltoDTO = $osio_sisaltoDAO->hae_hakemusversion_ja_osion_sisalto($hakemusversio_id, $osioDTO->ID);
									}
										
									if(isset($osio_sisaltoDTO->ID)){ // Merkataan vanha tieto poistetuksi, mikäli tiedot ovat päivittyneet

										if( ( ($osioDTO->Osio_tyyppi=="date_start" || $osioDTO->Osio_tyyppi=="date_end") && $osio_sisaltoDTO->Sisalto_date!=date('Y-m-d', strtotime($tallennuskohde_arvo)) ) || ( ($osioDTO->Osio_tyyppi=="textarea" || $osioDTO->Osio_tyyppi=="textarea_large" || $osioDTO->Osio_tyyppi=="kysymys_ja_textarea_large") && $osio_sisaltoDTO->Sisalto_text!=$tallennuskohde_arvo)){

											$osio_sisaltoDAO->merkitse_osio_sisalto_poistetuksi($osio_sisaltoDTO->ID,$kayt_id);

											if($parametrit["tallennettavat_tiedot"]=="hakemus_aineisto"){
												$osio_sisaltoDAO->lisaa_haettuun_aineistoon_osio_sisalto($fk_haettu_aineisto, $osioDTO->ID, $osioDTO->Osio_tyyppi, $tallennuskohde_arvo, $kayt_id);
												$tietoja_muutettu = true;
											} else {
												$osio_sisaltoDAO->lisaa_hakemusversion_osio_sisalto($hakemusversio_id, $osioDTO->ID, $osioDTO->Osio_tyyppi, $tallennuskohde_arvo, $kayt_id);
												$tietoja_muutettu = true;
											}

										}
											
										if($osioDTO->Osio_tyyppi=="checkbox"){
											$osio_sisaltoDAO->merkitse_osio_sisalto_poistetuksi($osio_sisaltoDTO->ID,$kayt_id);
											$tietoja_muutettu = true;
										}
											
									} else { // Luodaan uusi tieto

										if($osioDTO->Osio_tyyppi=="radio"){ // Merkitään saman luokan muiden sisältöjen tilat poistetuksi

											$osiotDTO = $osioDAO->hae_luokan_osiot($osioDTO->Osio_luokka);

											for($i=0; $i < sizeof($osiotDTO); $i++){
												if($parametrit["tallennettavat_tiedot"]=="hakemus_aineisto"){
													$osio_sisaltoDAO->merkitse_osio_sisalto_poistetuksi($osio_sisaltoDAO->hae_haetun_aineiston_ja_osion_sisalto($fk_haettu_aineisto, $osiotDTO[$i]->ID)->ID,$kayt_id);
													if(!$tietoja_muutettu) $tietoja_muutettu = true;
												} else {
													$osio_sisaltoDAO->merkitse_osio_sisalto_poistetuksi($osio_sisaltoDAO->hae_hakemusversion_ja_osion_sisalto($hakemusversio_id, $osiotDTO[$i]->ID)->ID,$kayt_id);
													if(!$tietoja_muutettu) $tietoja_muutettu = true;
												}
											}
											
										}
											
										if($parametrit["tallennettavat_tiedot"]=="hakemus_aineisto"){
											$osio_sisaltoDAO->lisaa_haettuun_aineistoon_osio_sisalto($fk_haettu_aineisto, $osioDTO->ID, $osioDTO->Osio_tyyppi, $tallennuskohde_arvo, $kayt_id);
											$tietoja_muutettu = true;
										} else {
											$osio_sisaltoDAO->lisaa_hakemusversion_osio_sisalto($hakemusversio_id, $osioDTO->ID, $osioDTO->Osio_tyyppi, $tallennuskohde_arvo, $kayt_id);
											$tietoja_muutettu = true;
										}
											
									}

								}

							} else {
								throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Lähetettyä hakemusta ei voi tallentaa.");
							}
							
							if($db->commit()){
								if($tietoja_muutettu) $dto["Hakemusversio_tallennettu"] = true;
							}

							$db = null;

						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}

			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}

		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		$dto_hakemusversio = dto_taulukkomuotoon($this->hae_hakemusversio($syoteparametrit));
		$dto = array_merge($dto,$dto_hakemusversio);		
		
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_paatos_lomake($syoteparametrit){

		$dto = array();
		$dto["Paatos_tallennettu"] = false;

		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];
			$data = $parametrit["data"];
			$kayttajan_rooli = $parametrit["kayttajan_rooli"];

			if(!is_null($data) && !is_null($hakemus_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"], "hakemus_id"=>$hakemus_id))){
							if($viranomaisen_rooliDTO = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, $kayttajan_rooli)){

								$db->beginTransaction();

								$hakemusDAO = new HakemusDAO($db);
								$paatosDAO = new PaatosDAO($db);
								$paattajaDAO = new PaattajaDAO($db);
								$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);
								$paatetty_luvan_kohdeDAO = new Paatetty_luvan_kohdeDAO($db);
								$kayttolupaDAO = new KayttolupaDAO($db);

								// Tallennus on mahdollista vain jos päätös ei ole tehty
								$paatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemus_id);
								$paatosDTO->Paatoksen_tilaDTO = $paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($paatosDTO->ID);

								if($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken"){

									$osioDAO = new OsioDAO($db);
									$osio_sisaltoDAO = new Osio_sisaltoDAO($db);

									$tallennuskohde = $data["tallennuskohde"];
									$tallennuskohde_id = $data["tallennuskohde_id"];
									$tallennuskohde_nimi = $data["tallennuskohde_nimi"];
									$tallennuskohde_arvo = $data["tallennuskohde_arvo"];

									if(isset($data["tallennuskohde_kentta"])) $tallennuskohde_kentta = $data["tallennuskohde_kentta"];

									if($tallennuskohde=="paatos"){

										if($tallennuskohde_kentta=="Lakkaamispvm") $tallennuskohde_arvo = date('Y-m-d', strtotime($tallennuskohde_arvo) );
										$paatosDAO->paivita_paatoksen_tieto($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo, $kayt_id);

									}
									
									if($tallennuskohde=="kayttolupa"){

										$kayttolupaDTO = $kayttolupaDAO->hae_kayttajan_ja_paatoksen_kayttolupa($paatosDTO->ID, $tallennuskohde_arvo);

										if(!isset($kayttolupaDTO->ID)){ // Lisätään jos lupaa ei löydy
											$kayttolupaDAO->lisaa_paatokseen_kayttolupa($paatosDTO->Lakkaamispvm, $tallennuskohde_arvo, $kayt_id, $paatosDTO->ID);
										} else { // Poistetaan jos lupa löytyy
											$kayttolupaDAO->poista_kayttajan_paatos($paatosDTO->ID, $tallennuskohde_arvo, $kayt_id);
										}
									}
									
									if($tallennuskohde=="paattaja" || $tallennuskohde=="pj"){

										// Tarkistetaan löytyykö päättäjä kannasta
										$paattajaDTO = $paattajaDAO->hae_paatoksen_paattaja($paatosDTO->ID, $tallennuskohde_arvo);

										if(isset($paattajaDTO->ID) && !is_null($paattajaDTO->ID)){ // Poistetaan päättäjä
											$paattajaDAO->merkitse_paattaja_poistetuksi($paattajaDTO->ID, $kayt_id);
										} else { // Lisätään päättäjä
											$paattajaDAO->lisaa_paattaja_paatokseen($paatosDTO->ID, $tallennuskohde_arvo, $kayt_id);
										}
										
									}
									
									if($tallennuskohde=="paatetty_luvan_kohde"){ 
										$paatetty_luvan_kohdeDAO->paivita_paatetyn_luvan_kohteen_tieto($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo, $kayt_id);
									}
									
									if($tallennuskohde=="osio"){

										$osioDTO = $osioDAO->hae_osio($tallennuskohde_id);

										$osio_sisaltoDTO = $osio_sisaltoDAO->hae_paatoksen_ja_osion_sisalto($paatosDTO->ID, $osioDTO->ID);

										if(isset($osio_sisaltoDTO->ID)){ // Merkataan vanha tieto poistetuksi, mikäli tiedot ovat päivittyneet

											if( ( ($osioDTO->Osio_tyyppi=="date_start" || $osioDTO->Osio_tyyppi=="date_end") && $osio_sisaltoDTO->Sisalto_date!=date('Y-m-d', strtotime($tallennuskohde_arvo)) ) || ( ($osioDTO->Osio_tyyppi=="textarea" || $osioDTO->Osio_tyyppi=="textarea_large" || $osioDTO->Osio_tyyppi=="kysymys_ja_textarea_large") && $osio_sisaltoDTO->Sisalto_text!=$tallennuskohde_arvo)){

												$osio_sisaltoDAO->merkitse_osio_sisalto_poistetuksi($osio_sisaltoDTO->ID,$kayt_id);
												$osio_sisaltoDAO->lisaa_paatoksen_osio_sisalto($paatosDTO->ID, $osioDTO->ID, $osioDTO->Osio_tyyppi, $tallennuskohde_arvo, $kayt_id);

											}
											if($osioDTO->Osio_tyyppi=="checkbox"){
												$osio_sisaltoDAO->merkitse_osio_sisalto_poistetuksi($osio_sisaltoDTO->ID,$kayt_id);
											}
										} else { // Luodaan uusi tieto

											if($osioDTO->Osio_tyyppi=="radio"){ // Merkitään saman luokan muiden sisältöjen tilat poistetuksi

												$osiotDTO = $osioDAO->hae_luokan_osiot($osioDTO->Osio_luokka);

												for($i=0; $i < sizeof($osiotDTO); $i++){
													$osio_sisaltoDAO->merkitse_osio_sisalto_poistetuksi($osio_sisaltoDAO->hae_paatoksen_ja_osion_sisalto($paatosDTO->ID, $osiotDTO[$i]->ID)->ID,$kayt_id);
												}
											}
											$osio_sisaltoDAO->lisaa_paatoksen_osio_sisalto($paatosDTO->ID, $osioDTO->ID, $osioDTO->Osio_tyyppi, $tallennuskohde_arvo, $kayt_id);

										}

									}

								} else {
									throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Lähetettyä hakemusta ei voi tallentaa.");
								}
								if($db->commit()){
									$dto["Paatos_tallennettu"] = true;
								}

								$db = null;

							}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}

			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}

		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_lausunto_lomake($syoteparametrit){

		$dto = array();
		$dto["Lausunto_tallennettu"] = false;

		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$lausunto_id = $parametrit["lausunto_id"];
			$data = $parametrit["data"];

			if(!is_null($data) && !is_null($lausunto_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							if($viranomaisen_rooliDTO = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, "rooli_lausunnonantaja")){

								$db->beginTransaction();

								$lausuntoDAO = new LausuntoDAO($db);
								$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
								
								$lausuntoDTO = $lausuntoDAO->hae_lausunto($lausunto_id);
								$lausuntopyyntoDTO = $lausuntopyyntoDAO->hae_lausuntopyynnon_tiedot($lausuntoDTO->LausuntopyyntoDTO->ID);
								
								// Tallennus on mahdollista vain jos lausuntopyyntö on lähetetty käyttäjälle ja lausuntoa ei ole julkaistu
								if($lausuntopyyntoDTO->KayttajaDTO_Antaja->ID==$kayt_id && $lausuntoDTO->Lausunto_julkaistu==0){

									$osioDAO = new OsioDAO($db);
									$osio_sisaltoDAO = new Osio_sisaltoDAO($db);

									$tallennuskohde = $data["tallennuskohde"];
									$tallennuskohde_id = $data["tallennuskohde_id"];
									$tallennuskohde_nimi = $data["tallennuskohde_nimi"];
									$tallennuskohde_arvo = $data["tallennuskohde_arvo"];
									$tietoja_muutettu = false;

									if(isset($data["tallennuskohde_kentta"])) $tallennuskohde_kentta = $data["tallennuskohde_kentta"];

									if($tallennuskohde=="lausunto"){ 
										$lausuntoDAO->paivita_lausunnon_tieto($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo, $kayt_id);
										$tietoja_muutettu = true;
									}
									
									if($tallennuskohde=="osio"){

										$osioDTO = $osioDAO->hae_osio($tallennuskohde_id);

										$osio_sisaltoDTO = $osio_sisaltoDAO->hae_lausunnon_ja_osion_sisalto($lausuntoDTO->ID, $osioDTO->ID);

										if(isset($osio_sisaltoDTO->ID)){ // Merkataan vanha tieto poistetuksi, mikäli tiedot ovat päivittyneet

											if( ( ($osioDTO->Osio_tyyppi=="date_start" || $osioDTO->Osio_tyyppi=="date_end") && $osio_sisaltoDTO->Sisalto_date!=date('Y-m-d', strtotime($tallennuskohde_arvo)) ) || ( ($osioDTO->Osio_tyyppi=="textarea" || $osioDTO->Osio_tyyppi=="textarea_large" || $osioDTO->Osio_tyyppi=="kysymys_ja_textarea_large") && $osio_sisaltoDTO->Sisalto_text!=$tallennuskohde_arvo)){

												$osio_sisaltoDAO->merkitse_osio_sisalto_poistetuksi($osio_sisaltoDTO->ID,$kayt_id);
												$osio_sisaltoDAO->lisaa_lausunnon_osio_sisalto($lausuntoDTO->ID, $osioDTO->ID, $osioDTO->Osio_tyyppi, $tallennuskohde_arvo, $kayt_id);
												$tietoja_muutettu = true;

											}
											
											if($osioDTO->Osio_tyyppi=="checkbox"){
												$osio_sisaltoDAO->merkitse_osio_sisalto_poistetuksi($osio_sisaltoDTO->ID,$kayt_id);
												$tietoja_muutettu = true;
											}
											
										} else { // Luodaan uusi tieto

											if($osioDTO->Osio_tyyppi=="radio"){ // Merkitään saman luokan muiden sisältöjen tilat poistetuksi

												$osiotDTO = $osioDAO->hae_luokan_osiot($osioDTO->Osio_luokka);

												for($i=0; $i < sizeof($osiotDTO); $i++){
													$osio_sisaltoDAO->merkitse_osio_sisalto_poistetuksi($osio_sisaltoDAO->hae_lausunnon_ja_osion_sisalto($lausuntoDTO->ID, $osiotDTO[$i]->ID)->ID,$kayt_id);
												}
											}
											
											$osio_sisaltoDAO->lisaa_lausunnon_osio_sisalto($lausuntoDTO->ID, $osioDTO->ID, $osioDTO->Osio_tyyppi, $tallennuskohde_arvo, $kayt_id);
											$tietoja_muutettu = true;

										}

									}

								} else {
									throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Lähetettyä lausuntoa ei voi tallentaa.");
								}
								
								if($tietoja_muutettu && $db->commit()) $dto["Lausunto_tallennettu"] = true;
																	
								$db = null;

							} else {
								throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
							}
						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}

			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}

		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_metatiedot($syoteparametrit){

		$dto = array();
		$dto["Metatiedot_tallennettu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$kayttajan_rooli = $parametrit["kayttajan_rooli"];
			$data = $parametrit["data"];

			if(!is_null($data) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "kayttajan_rooli"=>$kayttajan_rooli, "token"=>$parametrit["token"]))){
							if($viranomaisen_rooliDTO = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, $kayttajan_rooli)){
								
								$tallennuskohde = $data["tallennuskohde"];
								$tallennuskohde_id = $data["tallennuskohde_id"];
								$tallennuskohde_nimi = $data["tallennuskohde_nimi"];
								$tallennuskohde_arvo = $data["tallennuskohde_arvo"];
								$tallennuskohde_kentta = $data["tallennuskohde_kentta"];

								$db->beginTransaction();

								if(is_numeric($tallennuskohde_id) && !is_null($tallennuskohde_kentta)){
									if($tallennuskohde=="Asia"){

										$asiaDAO = new AsiaDAO($db);
										if($asiaDAO->paivita_asian_tieto($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo)) $dto["Metatiedot_tallennettu"] = true;

									} else if($tallennuskohde=="Hakemus"){

										$hakemusDAO = new HakemusDAO($db);
										if($hakemusDAO->paivita_hakemuksen_tieto($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo)) $dto["Metatiedot_tallennettu"] = true;

									} else if($tallennuskohde=="Paatos"){

										$paatosDAO = new PaatosDAO($db);
										if($paatosDAO->paivita_paatoksen_tieto($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo, $kayt_id)) $dto["Metatiedot_tallennettu"] = true;

									} else if($tallennuskohde=="Lausunto"){

										$lausuntoDAO = new LausuntoDAO($db);
										if($lausuntoDAO->paivita_lausunnon_tieto($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo, $kayt_id)) $dto["Metatiedot_tallennettu"] = true;

									} else if($tallennuskohde=="Liite"){

										$liiteDAO = new LiiteDAO($db);
										if($liiteDAO->paivita_liitteen_tieto($tallennuskohde_id, $tallennuskohde_kentta, $tallennuskohde_arvo, $kayt_id)) $dto["Metatiedot_tallennettu"] = true;

									} else {
										throw new SoapFault(ERR_INVALID_ID, "Invalid parameters");
									}
								} else {
									throw new SoapFault(ERR_INVALID_ID, "Invalid parameters");
								}

								$db->commit();
								$db = null;
								
							}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}

			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}

		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function luo_hakemus($syoteparametrit){

		$dto = array();
		$uusi_hakemusversioDTO = new HakemusversioDTO();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			//$hakemustyyppi = $parametrit["hakemustyyppi"];
			$hakemustyyppi = null;
			$lomake_id = null;
			$taydennetty_hakemus = 0;

			if(isset($parametrit["lomake_id"])) $lomake_id = $parametrit["lomake_id"];
			if(isset($parametrit["taydennetty_hakemus"])) $taydennetty_hakemus = $parametrit["taydennetty_hakemus"];

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$tutkimusDAO = new TutkimusDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							$haettu_aineistoDAO = new Haettu_aineistoDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$hakijaDAO = new HakijaDAO($db);
							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
							$haettu_luvan_kohdeDAO = new Haettu_luvan_kohdeDAO($db);
							$haettu_muuttujaDAO = new Haettu_muuttujaDAO($db);
							$liiteDAO = new LiiteDAO($db);
							$hakemusversion_tilaDAO = new Hakemusversion_tilaDAO($db);
							$lomakeDAO = new LomakeDAO($db);

							if(isset($parametrit["hakemusversio_id"]) && !is_null($parametrit["hakemusversio_id"])){ // Hakemus on muutoshakemus

								$valittu_hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($parametrit["hakemusversio_id"]);
								$lomake_id = $valittu_hakemusversioDTO->LomakeDTO->ID;
								$edellinen_hakemusversioDTO = $hakemusversioDAO->hae_tutkimuksen_uusin_hakemusversio($valittu_hakemusversioDTO->TutkimusDTO->ID);

								if($hakemusversion_tilaDAO->hae_hakemusversion_uusin_tila($edellinen_hakemusversioDTO->ID)->Hakemusversion_tilan_koodi=="hv_kesken"){ // Muutoshakemusta ei luoda koska keskeneräinen muutoshakemus on jo olemassa
									$dto["HakemusversioDTO"] = $edellinen_hakemusversioDTO;
									return $dto;
								} else {
									$versio = $edellinen_hakemusversioDTO->Versio + 1; // Vain lähetetylle hakemusversiolle voi tehdä uuden muutoshakemuksen
								}

							} else { // Alustetaan uusi tutkimus

								$lomakeDTO = $lomakeDAO->hae_lomake($lomake_id);
								$hakemustyyppi = $lomakeDTO->Asiakirjatyyppi;

								// Haetaan pienin vapaa Tutkimuksen_tunnus-arvo ja lisätään se Tutkimus taulun tunnuksen numeroksi
								$suurin_tutk_tunnus = $tutkimusDAO->hae_seuraava_vapaa_tutkimuksen_tunnus();
								$pienin_vapaa_tutk_tunnus = $suurin_tutk_tunnus+1;
								$i = 1;

								while($i < $suurin_tutk_tunnus+1){

									if(!$tutkimusDAO->tutkimus_loytyi_tunnuksella($i)){
										$pienin_vapaa_tutk_tunnus = $i;
										break;
									}
									$i++;

								}

								$tutkimusDTO = $tutkimusDAO->lisaa_tutkimus($pienin_vapaa_tutk_tunnus, $kayt_id);
								$versio = 1;

							}
							
							if(isset($edellinen_hakemusversioDTO)){ // Muutoshakemus tai täydennyshakemus

								$vt = "$versio/" . $edellinen_hakemusversioDTO->TutkimusDTO->ID;

								if($taydennetty_hakemus){
									$uusi_hakemusversioDTO = $hakemusversioDAO->luo_hakemusversio($edellinen_hakemusversioDTO->TutkimusDTO->ID, $lomake_id, $vt, $versio, $edellinen_hakemusversioDTO->Asiakirjatyyppi, "tayd_hak", "Luonnos", $kayt_id);
								} else {
									$uusi_hakemusversioDTO = $hakemusversioDAO->luo_hakemusversio($edellinen_hakemusversioDTO->TutkimusDTO->ID, $lomake_id, $vt, $versio, $edellinen_hakemusversioDTO->Asiakirjatyyppi, "muutos_hak", "Luonnos", $kayt_id);
								}

							} else { // Uusi hakemus

								if(!isset($parametrit["aiempi_tutkimusnro"])){
									$vt = "$versio/" . $tutkimusDTO->ID;
								} else {
									$vt = $parametrit["aiempi_tutkimusnro"];
								}

								$uusi_hakemusversioDTO = $hakemusversioDAO->luo_hakemusversio($tutkimusDTO->ID, $lomake_id, $vt, $versio, $hakemustyyppi, "uus_hak", "Luonnos", $kayt_id);

							}
							
							if($versio == 1) { // Uusi hakemusversio

								// Alustetaan Haettu_aineisto-tauluun tyhjä tietue
								$haettu_aineistoDTO = $haettu_aineistoDAO->alusta_haettu_aineisto_hakemusversioon($uusi_hakemusversioDTO, 0, $kayt_id);

								// Annetaan hakemuksen perustajalle vastuullisen johtajan rooli
								$haetaan_kayttolupaa = 0;
								if($lomakeDTO->ID==1) $haetaan_kayttolupaa = 1;

								$hakijaDTO = $hakijaDAO->luo_hakija_kayttajan_tiedoista($kayttajaDAO->hae_kayttajan_tiedot($kayt_id), $haetaan_kayttolupaa);
								$hakijaDAO->vahvista_hakijan_jasenyys($hakijaDTO->ID);
								$hakijan_rooliDAO->luo_hakijan_rooli($uusi_hakemusversioDTO, $hakijaDTO, "rooli_vast");
								
								$uusi_hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[0] = $hakijaDTO; // Oletusarvoisesti hakija hakee käsittelyoikeutta aineistoon

							} else { // Muutoshakemus

								$osio_sisaltoDAO = new Osio_sisaltoDAO($db);
								$hakemusversion_liiteDAO = new Hakemusversion_liiteDAO($db);
								$osallistuva_organisaatioDAO = new Osallistuva_organisaatioDAO($db);

								$edellinen_hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($edellinen_hakemusversioDTO->ID);

								// Kopioidaan hakemusversion tiedot
								$hakemusversioDAO->kopioi_edellisen_hakemusversion_tiedot_muutoshakemusversioon($edellinen_hakemusversioDTO, $uusi_hakemusversioDTO);

								// Kopioidaan edelliseen hakemusversioon ja osioihin viittaavat sisällöt
								$Osioiden_sisallotDTO_hakemusversio = $osio_sisaltoDAO->hae_osion_sisallot("FK_Hakemusversio", $edellinen_hakemusversioDTO->ID);

								for($i=0; $i < sizeof($Osioiden_sisallotDTO_hakemusversio); $i++){
									$osio_sisaltoDAO->lisaa_osion_sisalto_kopiosta("FK_Hakemusversio", $uusi_hakemusversioDTO->ID, $Osioiden_sisallotDTO_hakemusversio[$i], $kayt_id);
								}
								$edelliset_haetut_aineistotDTO = $haettu_aineistoDAO->hae_hakemusversion_haetut_aineistot($edellinen_hakemusversioDTO->ID);

								for ($i = 0; $i < sizeof($edelliset_haetut_aineistotDTO); $i++) {

									$uusi_haettu_aineistoDTO = $haettu_aineistoDAO->alusta_haettu_aineisto_hakemusversioon($uusi_hakemusversioDTO, $edelliset_haetut_aineistotDTO[$i]->Aineiston_indeksi, $kayt_id);
									$haettu_aineistoDAO->kopioi_edellisen_haetun_aineiston_tiedot_uuteen_haettuun_aineistoon($edelliset_haetut_aineistotDTO[$i], $uusi_haettu_aineistoDTO);

									// Kopioidaan edelliseen haettuun aineistoon ja osioihin viittaavat sisällöt
									$Osioiden_sisallotDTO_haettu_aineisto = $osio_sisaltoDAO->hae_osion_sisallot("FK_Haettu_aineisto", $edelliset_haetut_aineistotDTO[$i]->ID);

									for($a=0; $a < sizeof($Osioiden_sisallotDTO_haettu_aineisto); $a++){
										$osio_sisaltoDAO->lisaa_osion_sisalto_kopiosta("FK_Haettu_aineisto", $uusi_haettu_aineistoDTO->ID, $Osioiden_sisallotDTO_haettu_aineisto[$a], $kayt_id);
									}

									// TODO
									// Kopioidaan Haettu_luvan_kohde-taulun tietueet edellisestä versiosta; £££korjaa tämä vaihe: kori kopioidaan edelliseltä päätökseltä, jos sellainen on
									$edelliset_haetut_luvan_kohteetDTO = $haettu_luvan_kohdeDAO->hae_haetun_aineiston_haetut_luvan_kohteet($edelliset_haetut_aineistotDTO[$i]->ID);

									//for ($l = 0; $l < sizeof($edelliset_haetut_luvan_kohteetDTO); $l++) {
									foreach($edelliset_haetut_luvan_kohteetDTO as $indx => $haettu_luvan_kohdeDTO_edellinen){

										$uusi_haettu_luvan_kohdeDTO = $haettu_luvan_kohdeDAO->kopioi_haettu_luvan_kohde_ja_liita_haettuun_aineistoon($haettu_luvan_kohdeDTO_edellinen, $uusi_haettu_aineistoDTO, $kayt_id);

										$edelliset_haetut_muuttujatDTO = $haettu_muuttujaDAO->hae_haetun_luvan_kohteen_haetut_muuttujat($haettu_luvan_kohdeDTO_edellinen->ID);

										for($j=0; $j < sizeof($edelliset_haetut_muuttujatDTO); $j++){ 
											$haettu_muuttujaDAO->lisaa_haettu_muuttuja_haettuun_luvan_kohteeseen($uusi_haettu_luvan_kohdeDTO->ID, $edelliset_haetut_muuttujatDTO[$j]->Muuttujan_koodi, $edelliset_haetut_muuttujatDTO[$j]->Lisatieto);
										}
									}


								}
								// Kopioidaan edellisen hakemusversion liitteet
								$edellisen_hakemusversion_liitteetDTO = $hakemusversion_liiteDAO->hae_hakemusversion_liitteet($edellinen_hakemusversioDTO->ID);

								for($i=0; $i < sizeof($edellisen_hakemusversion_liitteetDTO); $i++){
									$edellinen_liiteDTO = $liiteDAO->hae_liite($edellisen_hakemusversion_liitteetDTO[$i]->LiiteDTO->ID);
									$fk_liite_uusi = $liiteDAO->lisaa_liitetiedosto($edellinen_liiteDTO->Liitetiedosto_nimi, $edellinen_liiteDTO->Liitteen_tyypin_koodi, $edellinen_liiteDTO->Liitetiedosto_blob, $edellinen_liiteDTO->Tiedostomuoto, "Luonnos", $edellinen_liiteDTO->Versio, $edellinen_liiteDTO->Lisaaja);
									$hakemusversion_liiteDAO->lisaa_hakemusversion_liitetiedosto($uusi_hakemusversioDTO->ID, $fk_liite_uusi);
								}
								// Kopioidaan Hakijat rooleineen edellisestä versiosta
								$edelliset_hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijat($edellinen_hakemusversioDTO->ID);

								for($i=0; $i < sizeof($edelliset_hakijan_roolitDTO); $i++){

									$edellinen_hakijaDTO = $hakijaDAO->hae_hakijan_tiedot($edelliset_hakijan_roolitDTO[$i]->HakijaDTO->ID);
									$uusi_hakijaDTO = $hakijaDAO->luo_hakija($edellinen_hakijaDTO->KayttajaDTO->ID, $edellinen_hakijaDTO->Sukunimi, $edellinen_hakijaDTO->Etunimi, $edellinen_hakijaDTO->Sahkopostiosoite, $edellinen_hakijaDTO->Organisaatio, $edellinen_hakijaDTO->Oppiarvo, $edellinen_hakijaDTO->Haetaanko_kayttolupaa, $edellinen_hakijaDTO->Kutsuttu_jaseneksi, $edellinen_hakijaDTO->Jasen, $edellinen_hakijaDTO->Varmenne, $kayt_id);
									$hakijaDAO->vahvista_hakijan_jasenyys($uusi_hakijaDTO->ID);

									// Kopioidaan roolit
									$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakijan_roolin_tiedot_hakijan_avaimella($edellinen_hakijaDTO->ID);

									for($y=0; $y < sizeof($hakijan_roolitDTO); $y++){
										$hakijan_rooliDAO->luo_hakijan_muu_rooli($uusi_hakemusversioDTO, $uusi_hakijaDTO, $hakijan_roolitDTO[$y]->Hakijan_roolin_koodi, $hakijan_roolitDTO[$y]->Muun_roolin_selite);
									}
								}
								// Kopioidaan organisaatiotiedot
								$edelliset_osallistuvat_organisaatiotDTO = $osallistuva_organisaatioDAO->hae_hakemusversion_organisaatiot($edellinen_hakemusversioDTO->ID);

								for($i=0; $i < sizeof($edelliset_osallistuvat_organisaatiotDTO); $i++){

									$fk_osallistuva_org_uus = $osallistuva_organisaatioDAO->luo_organisaatio($uusi_hakemusversioDTO->ID, $kayt_id);
									$osallistuva_organisaatioDAO->paivita_organisaation_tieto($fk_osallistuva_org_uus, "Nimi", $edelliset_osallistuvat_organisaatiotDTO[$i]->Nimi, $kayt_id);
									$osallistuva_organisaatioDAO->paivita_organisaation_tieto($fk_osallistuva_org_uus, "Osoite", $edelliset_osallistuvat_organisaatiotDTO[$i]->Osoite, $kayt_id);
									$osallistuva_organisaatioDAO->paivita_organisaation_tieto($fk_osallistuva_org_uus, "Rekisterinpitaja", $edelliset_osallistuvat_organisaatiotDTO[$i]->Rekisterinpitaja, $kayt_id);
									$osallistuva_organisaatioDAO->paivita_organisaation_tieto($fk_osallistuva_org_uus, "Rooli", $edelliset_osallistuvat_organisaatiotDTO[$i]->Rooli, $kayt_id);
									$osallistuva_organisaatioDAO->paivita_organisaation_tieto($fk_osallistuva_org_uus, "Edustaja", $edelliset_osallistuvat_organisaatiotDTO[$i]->Edustaja, $kayt_id);
									$osallistuva_organisaatioDAO->paivita_organisaation_tieto($fk_osallistuva_org_uus, "Edustajan_email", $edelliset_osallistuvat_organisaatiotDTO[$i]->Edustajan_email, $kayt_id);

								}

							}

							$hakemusversion_tilaDAO->luo_hakemusversion_tila($uusi_hakemusversioDTO->ID, "hv_kesken", $kayt_id);
							$dto["HakemusversioDTO"] = $uusi_hakemusversioDTO;

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}

			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}

		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Hakemus lähetetään viranomaisille käsiteltäväksi
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function laheta_hakemus($syoteparametrit){

		$dto = array();
		$dto["Hakemus_lahetetty"] = false;
		$dto["HakijaDTO_Yhteyshenkilo"] = null;
		$dto["Lahetetty_HakemusversioDTO"] = null;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemusversio_id = $parametrit["hakemusversio_id"];
			$muutoshakemus_viranomaiset = array();

			if(isset($parametrit["muutoshakemus_viranomaiset"]) && !empty($parametrit["muutoshakemus_viranomaiset"])) $muutoshakemus_viranomaiset = $parametrit["muutoshakemus_viranomaiset"];

			if(!is_null($hakemusversio_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_hakija","hakemusversio_id"=>$hakemusversio_id, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$hakemusversioDAO = new HakemusversioDAO($db);
							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
							$hakijaDAO = new HakijaDAO($db);
							$hakemusversion_tilaDAO = new Hakemusversion_tilaDAO($db);
							$haettu_aineistoDAO = new Haettu_aineistoDAO($db);
							$luvan_kohdeDAO = new Luvan_kohdeDAO($db);
							$koodistotDAO = new KoodistotDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$haettu_luvan_kohdeDAO = new Haettu_luvan_kohdeDAO($db);
							$haettu_muuttujaDAO = new Haettu_muuttujaDAO($db);
							$paatosDAO = new PaatosDAO($db);
							$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);
							$osioDAO = new OsioDAO($db);
							$osio_sisaltoDAO = new Osio_sisaltoDAO($db);
							$jarjestelman_hakijan_roolitDAO = new Jarjestelman_hakijan_roolitDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							
							$toimivalta_organisaatiot = array("v_ETK", "v_Fimea", "v_Kela", "v_STM", "v_THL", "v_TK", "v_TTL", "v_Valvira", "v_VRK", "v_VSSHP");							
							$viranomaiset = array();
							$hakemuksia_lahetetty_kpl = 0;
							$korvattavat_hakemuksetDTO = array();
							$biopankit = array("AURIA", "BOREALIS", "FHRB", "HKI_BIO",	"ITAS_BIO",	"KESK_BIO",	"THL_BIO", "TMPR_BIO", "VERIP_BIO");							

							$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusversio_id);
							
							// Tarkistetaan onko hakijan roolilla oikeus lähettää hakemus
							$hakijalla_oikeus_lahettaa = false;							
							$hakijan_roolitDTO_hakemusversio = $hakijan_rooliDAO->hae_hakemusversion_hakijan_roolit($hakemusversioDTO->ID); 
							$jarjestelman_hakijan_roolitDTO = $jarjestelman_hakijan_roolitDAO->hae_lomakkeen_roolit_joilla_lahetys_sallittu($hakemusversioDTO->LomakeDTO->ID);
							
							for($i=0; $i < sizeof($hakijan_roolitDTO_hakemusversio); $i++){
								for($j=0; $j < sizeof($jarjestelman_hakijan_roolitDTO); $j++){
									if($hakijan_roolitDTO_hakemusversio[$i]->Hakijan_roolin_koodi==$jarjestelman_hakijan_roolitDTO[$j]->Hakijan_roolin_koodi){
										if($hakijaDAO->hae_hakijan_tiedot($hakijan_roolitDTO_hakemusversio[$i]->HakijaDTO->ID)->KayttajaDTO->ID==$kayt_id){
											$hakijalla_oikeus_lahettaa = true;
											break 2;
										}
									}
								}
							}
							
							if($hakijalla_oikeus_lahettaa){
							
								// Haetaan hakemuksen yhteyshenkilön tiedot
								$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijan_rooli($hakemusversioDTO->ID, "rooli_hak_yht");
								$hakijaDTO_Yhteyshenkilo = $hakijaDAO->hae_hakijan_tiedot($hakijan_roolitDTO[0]->HakijaDTO->ID);
								$hakijaDTO_Yhteyshenkilo->KayttajaDTO = $kayttajaDAO->hae_kayttajan_tiedot($hakijaDTO_Yhteyshenkilo->KayttajaDTO->ID);
								$dto["HakijaDTO_Yhteyshenkilo"] = $hakijaDTO_Yhteyshenkilo;
								
								//if(isset($dto["HakijaDTO_Yhteyshenkilo"]->Sahkopostiosoite) && filter_var($dto["HakijaDTO_Yhteyshenkilo"]->Sahkopostiosoite, FILTER_VALIDATE_EMAIL)){

									if($hakemusversioDTO->LomakeDTO->ID==1 && $hakemusversioDTO->Hakemuksen_tyyppi!="muutos_hak" && $hakemusversioDTO->Hakemuksen_tyyppi!="tayd_hak"){ // Uusi käyttölupahakemus

										// Selvitetään aineistosta mille viranomaisille lähetetään hakemus
										$aineiston_luvan_kohteet = array();
										$haetut_aineistotDTO = $haettu_aineistoDAO->hae_hakemusversion_haetut_aineistot($hakemusversio_id);

										for($i=0; $i < sizeof($haetut_aineistotDTO); $i++){

											// Selvitetään poimitaanko kohdejoukko biopankkiaineistosta
											// Kohdejoukko
											$osio_sisaltoDTO_poim_kohd_bio = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($haetut_aineistotDTO[$i]->ID, "FK_Haettu_aineisto", 982);																			
											$biopankkiaineistot_tunnistetaan_kohdejoukosta = (isset($osio_sisaltoDTO_poim_kohd_bio->Sisalto_boolean) && $osio_sisaltoDTO_poim_kohd_bio->Sisalto_boolean==1 ? true : false);
											
											if(!$biopankkiaineistot_tunnistetaan_kohdejoukosta){ // Tapaus
												$osio_sisaltoDTO_poim_tapaus_bio = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($haetut_aineistotDTO[$i]->ID, "FK_Haettu_aineisto", 986);
												$biopankkiaineistot_tunnistetaan_kohdejoukosta = (isset($osio_sisaltoDTO_poim_tapaus_bio->Sisalto_boolean) && $osio_sisaltoDTO_poim_tapaus_bio->Sisalto_boolean==1 ? true : false);
											}
											
											if(!$biopankkiaineistot_tunnistetaan_kohdejoukosta){ // Verrokit
												$osio_sisaltoDTO_poim_verrokit_bio = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($haetut_aineistotDTO[$i]->ID, "FK_Haettu_aineisto", 990);
												$biopankkiaineistot_tunnistetaan_kohdejoukosta = (isset($osio_sisaltoDTO_poim_verrokit_bio->Sisalto_boolean) && $osio_sisaltoDTO_poim_verrokit_bio->Sisalto_boolean==1 ? true : false);
											}											
											
											$haetut_luvan_kohteetDTO = $haettu_luvan_kohdeDAO->hae_haetun_aineiston_haetun_luvan_kohteet($haetut_aineistotDTO[$i]->ID);

											foreach ($haetut_luvan_kohteetDTO as $kohde_tyyppi => $haettu_luvan_kohdeDTO) {
												for($j=0; $j < sizeof($haettu_luvan_kohdeDTO); $j++){
													
													if($haettu_luvan_kohdeDTO[$j]->Luvan_kohdeDTO->ID!=178) array_push($aineiston_luvan_kohteet, $haettu_luvan_kohdeDTO[$j]->Luvan_kohdeDTO->ID);
														
													if(!$biopankkiaineistot_tunnistetaan_kohdejoukosta){ // Selvitetään poimittavista muuttujista mille biopankeille lähetetään hakemus
														
														$haetut_muuttujatDTO = $haettu_muuttujaDAO->hae_haetun_luvan_kohteen_haetut_muuttujat($haettu_luvan_kohdeDTO[$j]->ID);
														
														if(!empty($haetut_muuttujatDTO)){
															for($m=0; $m < sizeof($haetut_muuttujatDTO); $m++){
																if(in_array($haetut_muuttujatDTO[$m]->Muuttujan_koodi, $biopankit)){
																	if(in_array($haetut_muuttujatDTO[$m]->Muuttujan_koodi, $viranomaiset)){
																		continue;
																	} else {
																		array_push($viranomaiset, $haetut_muuttujatDTO[$m]->Muuttujan_koodi);
																	}
																}
															}
														}
														
													}
													
												}
											}
																							
											if($biopankkiaineistot_tunnistetaan_kohdejoukosta){ // Selvitetään kohdejoukon määrittelystä mille biopankeille lähetetään hakemus  
												
												$osiotDTO_biopankkivalinnat = $osioDAO->hae_luokan_osiot("checkbox-997");
											
												for($j=0; $j < sizeof($osiotDTO_biopankkivalinnat); $j++){
													
													$osio_sisaltoDTO_biopankki = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($haetut_aineistotDTO[$i]->ID, "FK_Haettu_aineisto", $osiotDTO_biopankkivalinnat[$j]->ID);
														
													if(isset($osio_sisaltoDTO_biopankki->Sisalto_boolean) && $osio_sisaltoDTO_biopankki->Sisalto_boolean==1){
														if (in_array($osiotDTO_biopankkivalinnat[$j]->Otsikko, $viranomaiset)) {
															continue;
														} else {
															array_push($viranomaiset, $osiotDTO_biopankkivalinnat[$j]->Otsikko);
														}											
													}
													
												}
											
											} 
																																																			
										}
										
										$luvan_kohteet = array_unique($aineiston_luvan_kohteet);
										
										if(TOISIOLAKI_PAALLA){ // Toisiolaki 44 §
											
											$viranomaiset_ja_lupaviranomainen = maarita_hakemuksen_viranomaiset_ja_lupaviranomainen($db, $luvan_kohteet);											
											$viranomaiset = array_merge($viranomaiset, $viranomaiset_ja_lupaviranomainen);
											
										} else {											
											for($i=0; $i < sizeof($luvan_kohteet); $i++){

												$luvan_kohdeDTO = $luvan_kohdeDAO->hae_luvan_kohde($luvan_kohteet[$i]);
																						
												if(isset($luvan_kohdeDTO->ID)){
													if (in_array($luvan_kohdeDTO->Viranomaisen_koodi, $viranomaiset)) {
														continue;
													} else {
														if($luvan_kohdeDTO->Viranomaisen_koodi=="v_BIO") continue;
														array_push($viranomaiset, $luvan_kohdeDTO->Viranomaisen_koodi);
													}
												}
												
											}																						
										}
																				
										
										
									} else if($hakemusversioDTO->LomakeDTO->ID==1 && $hakemusversioDTO->Hakemuksen_tyyppi=="muutos_hak") { 
										$viranomaiset = $muutoshakemus_viranomaiset;
									} else if(($hakemusversioDTO->LomakeDTO->ID==1 || $hakemusversioDTO->LomakeDTO->ID==27) && $hakemusversioDTO->Hakemuksen_tyyppi=="tayd_hak"){

										// Selvitetään viranomainen, jolle täydennyshakemus lähetetään
										$tutk_hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_kaikki_hakemusversiot($hakemusversioDTO->TutkimusDTO->ID);

										for($j=0; $j < sizeof($tutk_hakemusversiotDTO); $j++){ 

											$tutk_hakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset($tutk_hakemusversiotDTO[$j]->ID);

											for($i=0; $i < sizeof($tutk_hakemuksetDTO); $i++){
												
												if($hakemusversioDTO->LomakeDTO->ID==1){ // Käyttölupahakemus on tilassa "lisätietoa pyydetty"
													if($hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($tutk_hakemuksetDTO[$i]->ID)->Hakemuksen_tilan_koodi=="hak_muuta"){
														array_push($viranomaiset, $tutk_hakemuksetDTO[$i]->Viranomaisen_koodi);
														array_push($korvattavat_hakemuksetDTO, $tutk_hakemuksetDTO[$i]);
													} 
												}
												
												if($hakemusversioDTO->LomakeDTO->ID==27){ // Eettinen lausuntopyyntö on tilassa "ehdollisesti hyväksytty"
													$tutk_hakemuksetDTO[$i]->PaatosDTO = $paatosDAO->hae_hakemuksen_paatos($tutk_hakemuksetDTO[$i]->ID);
													if($paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($tutk_hakemuksetDTO[$i]->PaatosDTO->ID)->Paatoksen_tilan_koodi=="paat_tila_ehd_hyvaksytty"){
														array_push($viranomaiset, $tutk_hakemuksetDTO[$i]->Viranomaisen_koodi);
														array_push($korvattavat_hakemuksetDTO, $tutk_hakemuksetDTO[$i]);													
													}
												}
												
											}
										}
									} else if($hakemusversioDTO->LomakeDTO->ID==27 && ($hakemusversioDTO->Hakemuksen_tyyppi=="muutos_hak" || $hakemusversioDTO->Hakemuksen_tyyppi=="uus_hak")){ // ID=27 => Eettinen lausuntopyyntö
										array_push($viranomaiset, "v_VSSHP");
									} else {
										throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Tuntematon hakemuksen tyyppi");
									}
									
									for($i=0; $i < sizeof($viranomaiset); $i++){

										// Korvataan edelliset hakemukset mikäli kyseessä on täydennyshakemus
										if($hakemusversioDTO->Hakemuksen_tyyppi=="tayd_hak"){

											$tutkimuksen_hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_kaikki_hakemusversiot($hakemusversioDTO->TutkimusDTO->ID);
											$vir_om_hakemuksetDTO = array();

											for($j=0; $j < sizeof($tutkimuksen_hakemusversiotDTO); $j++){

												$vir_om_hakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset_viranomaiselle($tutkimuksen_hakemusversiotDTO[$j]->ID, $viranomaiset[$i]);

												for($h=0; $h < sizeof($vir_om_hakemuksetDTO); $h++){
													if($hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($vir_om_hakemuksetDTO[$h])->Hakemuksen_tilan_koodi!="hak_korvattu"){
														$hakemuksen_tilaDAO->maarita_hakemuksen_tiloista_tamanhetkiset_pois($vir_om_hakemuksetDTO[$h]->ID);
														$hakemuksen_tilaDAO->luo_hakemuksen_tila($vir_om_hakemuksetDTO[$h]->ID, $kayt_id, "hak_korvattu");
													}
												}

											}
										}

										// Luetaan viranomaisen lyhenne hakemuksen tunnusta varten
										$koodistotDTO = $koodistotDAO->hae_koodin_tiedot($viranomaiset[$i], "fi");
										$Hakemuksen_tunnus = $koodistotDTO->Selite2 . " " . $hakemusversioDTO->Hakemusversion_tunnus;

										// Alustetaan Hakemus-tietue
										if($hakemusDTO = $hakemusDAO->luo_hakemus($hakemusversio_id, $viranomaiset[$i], $Hakemuksen_tunnus, $kayt_id)){

											if(($hakemusversioDTO->LomakeDTO->ID==1 || $hakemusversioDTO->LomakeDTO->ID==27) && $hakemusversioDTO->Hakemuksen_tyyppi=="tayd_hak"){ // Täydennyshakemus saa saman asianumeron mitä edellinen hakemus

												$hakemuksen_tilaDAO->luo_hakemuksen_tila($hakemusDTO->ID, $kayt_id, "hak_lah"); 
												$hakemusDTO = siirra_hakemus_kasittelyyn($db, $hakemusDTO, $kayt_id, $paatosDAO->hae_hakemuksen_paatos($korvattavat_hakemuksetDTO[$i]->ID)->Kasittelija, false);
												$hakemusDAO->paivita_hakemuksen_tieto($hakemusDTO->ID, "FK_Asia", $korvattavat_hakemuksetDTO[$i]->AsiaDTO->ID);

											} else { // Luodaan hakemuksen_tila
												$hakemuksen_tilaDAO->luo_hakemuksen_tila($hakemusDTO->ID, $kayt_id, "hak_lah"); 
											}

											$hakemuksia_lahetetty_kpl++;

										}
									}
									
									$viranomaisia_kpl = sizeof($viranomaiset);

									if($viranomaisia_kpl > 0 && $hakemuksia_lahetetty_kpl==$viranomaisia_kpl){
										if($hakemusversion_tilaDAO->luo_hakemusversion_tila($hakemusversio_id, "hv_lah", $kayt_id)){

											$dto["Hakemus_lahetetty"] = true;
											$dto["Hakemuksen_lahetys_info"] = "Hakemus on lähetetty $viranomaisia_kpl viranomaiselle.";
											$dto["Lahetetty_HakemusversioDTO"] = $hakemusversioDTO;

										}
									} else {
										$dto["Hakemuksen_lahetys_info"] = "Hakemuksen lähetys epäonnistui.";
									}

								//} else {
								//	$dto["Hakemuksen_lahetys_info"] = "Hakemuksen yhteyshenkilön sähköpostiosoite on virheellinen.";
								//}

							} else {
								$dto["Hakemuksen_lahetys_info"] = "Sinulla ei ole käyttöoikeuksia lähettää hakemusta.";
							}	
								
							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function kirjaa_lokiin($syoteparametrit){

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);

			if(!is_null($parametrit["kayt_id"])){
				try {

					if ($db = $this->_connectToDb()) {

						$db->beginTransaction();

						$kayttolokiDAO = new KayttolokiDAO($db);
						$kayttolokiDTO = $kayttolokiDAO->kirjaa_lokiin($parametrit);
						$dto["KayttolokiDTO"] = $kayttolokiDTO;

						$db->commit();
						$db = null;

					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}

			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}

		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request formatz");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Käyttäjän kirjautuminen
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function kirjaudu_lupapalveluun($syoteparametrit){

		$dto = array();
		$dto["Kayttaja_kirjautunut"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$salasana = $parametrit["salasana"];
			$sahkopostiosoite = $parametrit["sahkopostiosoite"];

			if(!is_null($salasana) && !is_null($sahkopostiosoite)){
				try {

					if ($db = $this->_connectToDb()) {

						$db->beginTransaction();

						$kayttajaDAO = new KayttajaDAO($db);
						$suojausDAO = new SuojausDAO($db);
						$paakayttajan_rooliDAO = new Paakayttajan_rooliDAO($db);
						$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);

						$kayttajaDTO = $kayttajaDAO->hae_varmennettu_kayttaja_kayttajatunnuksella($sahkopostiosoite);

						if (isset($kayttajaDTO->ID) && crypt($salasana, $kayttajaDTO->Salasana_hash)==$kayttajaDTO->Salasana_hash) { 

							// Luodaan user token
							$length = 78;
							$token = bin2hex(openssl_random_pseudo_bytes($length));

							// Poistetaan vanhat tokenit jos sellaisia löytyy
							//$suojausDAO->poista_kayttajan_tokenit($kayttajaDTO->ID);
							$suojausDAO->poista_kayttajan_vanhentuneet_tokenit($kayttajaDTO->ID);
							
							// Lisätään uusi tokeni tietokantaan
							$kayttajaDTO->SuojausDTO = $suojausDAO->lisaa_kayttajan_token($kayttajaDTO->ID,$token);

							// Tarkistetaan onko käyttäjä pääkäyttäjä
							if($paakayttajan_rooliDTO = $paakayttajan_rooliDAO->hae_kayttajalle_paakayttajan_rooli($kayttajaDTO->ID)){
								$kayttajaDTO->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO;
							} else { // Tarkistetaan onko käyttäjä viranomainen
								if($viranomaisen_roolitDTO = $viranomaisen_rooliDAO->hae_kayttajalle_viranomaisen_roolit($kayttajaDTO->ID)){
									$kayttajaDTO->Viranomaisen_roolitDTO = $viranomaisen_roolitDTO;
								}
							}
							
							$kayttajaDTO->Salasana_hash = null;							
							$kayttajaDTO->Lukemattomien_viestien_maara = hae_kayttajan_lukemattomien_viestien_maara($db, $kayttajaDTO->ID);
							$kayttajaDTO->Eraantyvien_kayttolupien_maara = hae_eraantyvien_kayttolupien_maara_kayttajalle($db, $kayttajaDTO->ID);
							$kayttajaDTO->Lukemattomien_lausuntojen_maara = hae_saapuneiden_lausuntojen_maara_kayttajalle($db, $kayttajaDTO->ID);
														
							$dto["KayttajaDTO"] = $kayttajaDTO;
							
						}

						$db->commit();
						$db = null;

					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}

			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}

		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request formatz");
		}
		
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Haetaan lupapalvelun pääkäyttäjälle tiedot viranomaisten rooleista
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_kayttajaroolit_lupapalvelun_paakayttajalle($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){

								$db->beginTransaction();

								$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
								$kayttajaDAO = new KayttajaDAO($db);

								// Haetaan järjestelmästä löytyvät viranomaisten koodit
								$viranomaisten_roolitDTO_vir_koodit = $viranomaisen_rooliDAO->hae_viranomaisten_koodit();

								for($v=0; $v < sizeof($viranomaisten_roolitDTO_vir_koodit); $v++){ 

									$dto["Viranomaisten_roolitDTO"][$viranomaisten_roolitDTO_vir_koodit[$v]->Viranomaisen_koodi] = $viranomaisen_rooliDAO->hae_organisaation_viranomaisen_roolit_ja_kayttajat($viranomaisten_roolitDTO_vir_koodit[$v]->Viranomaisen_koodi);

									for($i=0; $i < sizeof($dto["Viranomaisten_roolitDTO"][$viranomaisten_roolitDTO_vir_koodit[$v]->Viranomaisen_koodi]); $i++){
										$dto["Viranomaisten_roolitDTO"][$viranomaisten_roolitDTO_vir_koodit[$v]->Viranomaisen_koodi][$i]->KayttajaDTO->Viranomaisen_roolitDTO = $viranomaisen_rooliDAO->hae_kayttajalle_viranomaisen_roolit($dto["Viranomaisten_roolitDTO"][$viranomaisten_roolitDTO_vir_koodit[$v]->Viranomaisen_koodi][$i]->KayttajaDTO->ID);
									}
								}
								$dto["Autentikoitu_lupapalvelun_paakayttaja"]["Paakayttajan_rooliDTO"] = $paakayttajan_rooliDTO_autentikoitu_kayttaja;

								$db->commit();
								$db = null;

							}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_kayttajan_tiedot($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);

			if(!is_null($parametrit["roolin_koodi"]) && !is_null($parametrit["kayt_id"]) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>$parametrit["roolin_koodi"], "kayt_id"=>$parametrit["kayt_id"], "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$kayttajaDAO = new KayttajaDAO($db);
							$dto["KayttajaDTO"]["Omat_tiedot"] = $kayttajaDAO->hae_kayttajan_tiedot($parametrit["kayt_id"]);

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Haetaan viranomaisen pääkäyttäjälle tiedot viranomaisten rooleista
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_kayttajaroolit_viranomaisen_paakayttajalle($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_viranomaisen_paak", "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							//if($viranomaisen_rooliDTO = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, "rooli_viranomaisen_paak")){

								$viranomaisen_rooliDTO = $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO;

								$db->beginTransaction();

								$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);

								$viranomaisen_roolitDTO = $viranomaisen_rooliDAO->hae_organisaation_viranomaisen_roolit_ja_kayttajat($viranomaisen_rooliDTO->Viranomaisen_koodi);

								for($i=0; $i < sizeof($viranomaisen_roolitDTO); $i++){
									$viranomaisen_roolitDTO[$i]->KayttajaDTO->Viranomaisen_roolitDTO = $viranomaisen_rooliDAO->hae_kayttajalle_viranomaisen_roolit($viranomaisen_roolitDTO[$i]->KayttajaDTO->ID);
								}
								//$dto["Autentikoitu_viranomainen"]["Viranomaisen_rooliDTO"] = $viranomaisen_rooliDTO;
								$dto["Viranomaisten_roolitDTO"] = $viranomaisen_roolitDTO;

								$db->commit();
								$db = null;

							//}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Funktio hakee lausunnonantajalle saapuneet lausuntopyynnöt (sivulle lausunnonantaja_saapuneet_lausuntopyynnot.php)
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_saapuneet_lausuntopyynnot($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_lausunnonantaja", "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
							$lausuntoDAO = new LausuntoDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$asiaDAO = new AsiaDAO($db);

							// Haetaan lausuntopyynnöt
							$lausuntopyynnotDTO_kaikki = $lausuntopyyntoDAO->hae_lausuntopyynnot_lausunnonmuodostajalle($kayt_id);
							$lausuntopyynnotDTO = array();

							// Karsitaan ne lausuntopyynnöt, joihin on jo lähetetty lausunto
							for($i=0; $i < sizeof($lausuntopyynnotDTO_kaikki); $i++){

								$lausuntoDTO = $lausuntoDAO->hae_lausuntopyynnolle_lausunto($lausuntopyynnotDTO_kaikki[$i]->ID);

								if(isset($lausuntoDTO->Lausunto_julkaistu) && $lausuntoDTO->Lausunto_julkaistu==1){
									continue; // Pyynnölle on jo annettu lausunto
								} else {

									$lausuntopyynnotDTO_kaikki[$i]->LausuntoDTO = $lausuntoDTO;

									// Haetaan jokaisen lausuntopyynnön tutkimuksen nimi, lausunnon pyytäjän nimi & viranomaisen koodi ja hakemuksen ID
									$lausuntopyynnotDTO_kaikki[$i]->KayttajaDTO_Pyytaja = $kayttajaDAO->hae_kayttajan_tiedot($lausuntopyynnotDTO_kaikki[$i]->KayttajaDTO_Pyytaja->ID);
									$lausuntopyynnotDTO_kaikki[$i]->KayttajaDTO_Pyytaja->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($lausuntopyynnotDTO_kaikki[$i]->KayttajaDTO_Pyytaja->ID);

									$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($lausuntopyynnotDTO_kaikki[$i]->HakemusDTO->ID);
									$hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemusDTO->ID);
									
									// Filtteröidään hakemuksen tilan perusteella 
									if($hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu" && $hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_paat" && $hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_lah" && $hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_korvattu"){
									
										$hakemusDTO->AsiaDTO = $asiaDAO->hae_asia($hakemusDTO->AsiaDTO->ID);
										$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);

										$lausuntopyynnotDTO_kaikki[$i]->TutkimusDTO = $hakemusversioDTO->TutkimusDTO;
										$lausuntopyynnotDTO_kaikki[$i]->TutkimusDTO->HakemusversioDTO = $hakemusversioDTO;
										$lausuntopyynnotDTO_kaikki[$i]->TutkimusDTO->HakemusversioDTO->HakemusDTO = $hakemusDTO;

										array_push($lausuntopyynnotDTO,$lausuntopyynnotDTO_kaikki[$i]);

									}	
										
								}
							}

							$dto["LausuntopyynnotDTO"] = $lausuntopyynnotDTO;

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Funktio noutaa saapuneet aineistotilaukset aineistonmuodostajalle (aineistonmuodostaja_saapuneet_tilaukset.php)
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_saapuneet_aineistotilaukset($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($kayt_id)){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] =  kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "kayttajan_rooli"=>"rooli_aineistonmuodostaja"))){

								$db->beginTransaction();

								$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
								$hakemusDAO = new HakemusDAO($db);
								$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
								$paatosDAO = new PaatosDAO($db);
								$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);
								$hakemusversioDAO = new HakemusversioDAO($db);
								$aineistotilausDAO = new AineistotilausDAO($db);
								$kayttajaDAO = new KayttajaDAO($db);
								$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);
								$asiaDAO = new AsiaDAO($db);

								$hakemuksetDTO_aineistotilaukset = array();
								$viranomaisen_rooliDTO = $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO;

								// Haetaan viranomaiselle lähetetyt hakemukset
								$hakemuksetDTO = $hakemusDAO->hae_viranomaisorganisaation_hakemukset($viranomaisen_rooliDTO->Viranomaisen_koodi);

								for($i=0; $i < sizeof($hakemuksetDTO); $i++){

									$hakemuksetDTO[$i]->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemuksetDTO[$i]->ID);
									$hakemuksetDTO[$i]->AsiaDTO = $asiaDAO->hae_asia($hakemuksetDTO[$i]->AsiaDTO->ID);

									if($hakemuksetDTO[$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat"){

										// Haetaan hyväksytyt päätökset
										$hakemuksetDTO[$i]->PaatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemuksetDTO[$i]->ID);
										$hakemuksetDTO[$i]->PaatosDTO->Paatoksen_tilaDTO = $paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($hakemuksetDTO[$i]->PaatosDTO->ID);

										if($hakemuksetDTO[$i]->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty"){

											$hakemuksetDTO[$i]->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemuksetDTO[$i]->HakemusversioDTO->ID);
											$hakemuksetDTO[$i]->PaatosDTO->AineistotilausDTO = $aineistotilausDAO->hae_aineistotilaus_paatokselle($hakemuksetDTO[$i]->PaatosDTO->ID);
											$hakemuksetDTO[$i]->PaatosDTO->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja = $kayttajaDAO->hae_kayttajan_tiedot($hakemuksetDTO[$i]->PaatosDTO->AineistotilausDTO->Aineistonmuodostaja);
											$hakemuksetDTO[$i]->PaatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO = $aineistotilauksen_tilaDAO->hae_tilan_koodi_aineistotilauksen_avaimella($hakemuksetDTO[$i]->PaatosDTO->AineistotilausDTO->ID);

											if($hakemuksetDTO[$i]->PaatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi!="aint_keskenerainen"){
												/*
												if($hakemuksetDTO[$i]->PaatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_uusi"){
													$tilauspvm = $hakemuksetDTO[$i]->PaatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO->Lisayspvm;
												} else {
													$aineistotilauksen_tilaDTO = $aineistotilauksen_tilaDAO->hae_tilan_koodi_aineistotilauksen_avaimella_ja_tilan_koodilla($hakemuksetDTO[$i]->PaatosDTO->AineistotilausDTO->ID, "aint_uusi");
													$tilauspvm = $aineistotilauksen_tilaDTO->Lisayspvm;
												}
												*/

												array_push($hakemuksetDTO_aineistotilaukset, $hakemuksetDTO[$i]);

											}
										}
									}
								}
								// Haetaan viranomaisen aineistonmuodostajat
								$viranomaisen_roolitDTO = $viranomaisen_rooliDAO->hae_viranomaisten_roolit_koodin_ja_roolin_perusteella($viranomaisen_rooliDTO->Viranomaisen_koodi, $viranomaisen_rooliDTO->Viranomaisroolin_koodi);

								for($i=0; $i < sizeof($viranomaisen_roolitDTO); $i++){
									$viranomaisen_roolitDTO[$i]->KayttajaDTO = $kayttajaDAO->hae_kayttajan_tiedot($viranomaisen_roolitDTO[$i]->KayttajaDTO->ID);
								}
								$dto["Viranomaisen_roolitDTO_Aineistonmuodostajat"] = $viranomaisen_roolitDTO;
								//$dto = hae_lukemattomien_viestien_maara_kayttajan_roolille($dto,$db,$kayt_id,$viranomaisen_rooliDTO->Viranomaisroolin_koodi);
								$dto["HakemuksetDTO"]["Aineistotilaukset"] = $hakemuksetDTO_aineistotilaukset;

								$db->commit();
								$db = null;


						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Lausunnonantajalle haetaan annetut lausunnot
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_annetut_lausunnot($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_lausunnonantaja", "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

								$db->beginTransaction();

								$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
								$lausuntoDAO = new LausuntoDAO($db);
								$hakemusversioDAO = new HakemusversioDAO($db);
								$hakemusversion_tilaDAO = new Hakemusversion_tilaDAO($db);
								$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
								$kayttajaDAO = new KayttajaDAO($db);
								$hakemusDAO = new HakemusDAO($db);
								$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
								$asiaDAO = new AsiaDAO($db);

								// temp ratkaisu?: Haetaan vain ne annetut lausunnot, jotka käyttäjä on antanut (ei siis haeta muiden käyttäjien lausuntoja, vaikka niillä olisi sama vir_koodi mitä käyttäjällä $kayt_id)
								$lausuntopyynnotDTO = $lausuntopyyntoDAO->hae_lausuntopyynnot_lausunnonmuodostajalle($kayt_id);
								$viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($kayt_id);
								$annetut_lausunnotDTO = array();

								for($i=0; $i < sizeof($lausuntopyynnotDTO); $i++){

									$lausuntoDTO = $lausuntoDAO->hae_lausuntopyynnolle_julkaistu_lausunto($lausuntopyynnotDTO[$i]->ID);

									if(isset($lausuntoDTO->ID) && !is_null($lausuntoDTO->ID)){

										$lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja = $kayttajaDAO->hae_kayttajan_tiedot($lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja->ID);
										$lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja->ID);

										$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($lausuntopyynnotDTO[$i]->HakemusDTO->ID);
										$hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemusDTO->ID);
										
										if($hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu" && $hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_lah"){
										
											$hakemusDTO->AsiaDTO = $asiaDAO->hae_asia($hakemusDTO->AsiaDTO->ID);
											$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);

											$lausuntopyynnotDTO[$i]->TutkimusDTO = $hakemusversioDTO->TutkimusDTO;
											$lausuntopyynnotDTO[$i]->TutkimusDTO->HakemusversioDTO = $hakemusversioDTO;
											$lausuntopyynnotDTO[$i]->TutkimusDTO->HakemusversioDTO->HakemusDTO = $hakemusDTO;
											$lausuntoDTO->LausuntopyyntoDTO = $lausuntopyynnotDTO[$i];

											array_push($annetut_lausunnotDTO, $lausuntoDTO);
											
										}	

									}
								}
								$dto["LausunnotDTO"]["Annetut_lausunnot"] = $annetut_lausunnotDTO;

								$db->commit();
								$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Haetaan tietokannasta viranomaiselle saapuneet hakemukset sivulle viranomainen_saapuneet_hakemukset.php
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_saapuneet_hakemukset_viranomaiselle($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$kayttajan_rooli = $parametrit["kayttajan_rooli"]; // sallitut parametrit: rooli_kasitteleva tai rooli_paattava

			if(!is_null($kayttajan_rooli) && !is_null($kayt_id) && !is_null($parametrit["token"]) && ($kayttajan_rooli=="rooli_kasitteleva" || $kayttajan_rooli=="rooli_paattava" || $kayttajan_rooli=="rooli_eettisensihteeri" || $kayttajan_rooli=="rooli_eettisen_puheenjohtaja")){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>$kayttajan_rooli, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$viranomaisen_rooliDTO = $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO;

							$db->beginTransaction();

							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							$hakemusversion_tilaDAO = new Hakemusversion_tilaDAO($db);
							$paatosDAO = new PaatosDAO($db);
							$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
							$hakijaDAO = new HakijaDAO($db);
							$paattajaDAO = new PaattajaDAO($db);
							$asiaDAO = new AsiaDAO($db);

							$uudet_hakemuksetDTO = array();
							$avatut_hakemuksetDTO = array();
							$paatetyt_hakemuksetDTO = array();
							$omat_hakemuksetDTO = array();

							// Haetaan hakemusten tilat
							$hakemuksen_tilatDTO = $hakemuksen_tilaDAO->hae_hakemuksen_tilat();

							for($i=0; $i < sizeof($hakemuksen_tilatDTO); $i++){ 

								$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemuksen_tilatDTO[$i]->HakemusDTO->ID);
								$kayttaja_on_hakemuksen_paattaja = false;

								if($hakemusDTO->Viranomaisen_koodi==$viranomaisen_rooliDTO->Viranomaisen_koodi){ 

									$hakemusDTO->Hakemuksen_tilaDTO = $hakemuksen_tilatDTO[$i];
									$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);

									if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_muuta" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ 

										$hakemusDTO->PaatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemusDTO->ID);
										$hakemusDTO->PaatosDTO->KayttajaDTO_Kasittelija = $kayttajaDAO->hae_kayttajan_tiedot($hakemusDTO->PaatosDTO->Kasittelija);
										$hakemusDTO->PaatosDTO->PaattajatDTO = $paattajaDAO->hae_paatoksen_paattajat($hakemusDTO->PaatosDTO->ID);
										$hakemusDTO->PaatosDTO->Paatoksen_tilaDTO = $paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($hakemusDTO->PaatosDTO->ID);
										
										if(($kayttajan_rooli=="rooli_kasitteleva" || $kayttajan_rooli=="rooli_eettisensihteeri") && $hakemusDTO->PaatosDTO->KayttajaDTO_Kasittelija->ID==$kayt_id && $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_paat") array_push($omat_hakemuksetDTO, $hakemusDTO);

										if(($kayttajan_rooli=="rooli_eettisen_puheenjohtaja" || $kayttajan_rooli=="rooli_paattava") && isset($hakemusDTO->PaatosDTO->PaattajatDTO) && !empty($hakemusDTO->PaatosDTO->PaattajatDTO)){

											for($p=0; $p < sizeof($hakemusDTO->PaatosDTO->PaattajatDTO); $p++){
												if($hakemusDTO->PaatosDTO->PaattajatDTO[$p]->KayttajaDTO->ID==$kayt_id){
													$kayttaja_on_hakemuksen_paattaja = true;
													break;
												} 
											}
										}
									}
									// Päättävälle roolille haetaan hakemus vain jos käyttäjä on hakemuksen päättäjä
									if(($kayttajan_rooli=="rooli_eettisen_puheenjohtaja" || $kayttajan_rooli=="rooli_paattava") && !$kayttaja_on_hakemuksen_paattaja) continue;

									// Tarkistetaan onko hakemus yhteishakemus
									$tutkimuksen_hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_kaikki_hakemusversiot($hakemusDTO->HakemusversioDTO->TutkimusDTO->ID);
									$muiden_vir_om_hakemukset = array();
									$hakemushistoria = array();

									for($j=0; $j < sizeof($tutkimuksen_hakemusversiotDTO); $j++){

										$tutkimuksen_hakemusversiotDTO[$j]->Hakemusversion_tilaDTO = $hakemusversion_tilaDAO->hae_hakemusversion_uusin_tila($tutkimuksen_hakemusversiotDTO[$j]->ID);

										if($tutkimuksen_hakemusversiotDTO[$j]->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_lah" || $tutkimuksen_hakemusversiotDTO[$j]->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_peruttu"){

											$tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset($tutkimuksen_hakemusversiotDTO[$j]->ID);

											for($h=0; $h < sizeof($tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO); $h++){

												$tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]->ID);
												$tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO = $paatosDAO->hae_hakemuksen_paatos($tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]->ID);
												$tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->KayttajaDTO_Kasittelija = $kayttajaDAO->hae_kayttajan_tiedot($tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Kasittelija);
												$tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]->AsiaDTO = $asiaDAO->hae_asia($tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]->AsiaDTO->ID);
												
												if($tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]->Viranomaisen_koodi!=$hakemusDTO->Viranomaisen_koodi && $tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]->ID!=$hakemusDTO->ID){
													array_push($muiden_vir_om_hakemukset, $tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]);
												}
												array_push($hakemushistoria, $tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO[$h]);

											}
										}
									}

									$hakemusDTO->muiden_viranomaisten_HakemuksetDTO = $muiden_vir_om_hakemukset;
									$hakemusDTO->hakemushistoria_HakemuksetDTO = $hakemushistoria;

									// Asetetaan muiden taulujen tietyt attribuutit Hakemus DTO-luokkaan lajittelun nopeuttamiseksi
									$hakemusDTO->Hakemuksen_tila = $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi;
									$hakemusDTO->Tutkimuksen_nimi = $hakemusDTO->HakemusversioDTO->Tutkimuksen_nimi;
									$hakemusDTO->Tilan_pvm = $hakemusDTO->Hakemuksen_tilaDTO->Lisayspvm;
									
									if(isset($hakemusDTO->PaatosDTO->KayttajaDTO_Kasittelija->Etunimi)){
										$hakemusDTO->Kasittelijan_nimi = $hakemusDTO->PaatosDTO->KayttajaDTO_Kasittelija->Etunimi . " " . $hakemusDTO->PaatosDTO->KayttajaDTO_Kasittelija->Sukunimi;
									} else {
										$hakemusDTO->Kasittelijan_nimi = "";
									}
																											
									// Haetaan hakemuksen lähettäjä

									//$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijan_rooli($hakemusDTO->HakemusversioDTO->ID, "rooli_hak_yht");
									//$hakijaDTO = $hakijaDAO->hae_hakijan_tiedot($hakijan_roolitDTO[0]->HakijaDTO->ID);
									//$hakemusDTO->Hakemuksen_yhteyshenkilo = $hakijaDTO->Etunimi . " " . $hakijaDTO->Sukunimi . ", " . $hakijaDTO->Organisaatio;
									$kayttajaDTO_hak_lahettaja = $kayttajaDAO->hae_kayttajan_tiedot($hakemusDTO->Lisaaja);
									$hakemusDTO->Hakemuksen_yhteyshenkilo = $kayttajaDTO_hak_lahettaja->Etunimi . " " . $kayttajaDTO_hak_lahettaja->Sukunimi; 

									if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_lah") array_push($uudet_hakemuksetDTO,$hakemusDTO);
									if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_muuta" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val") array_push($avatut_hakemuksetDTO,$hakemusDTO);
									if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat") array_push($paatetyt_hakemuksetDTO,$hakemusDTO);

								}
							}
							$dto["HakemuksetDTO"]["Uudet"] = $uudet_hakemuksetDTO;
							$dto["HakemuksetDTO"]["Avatut"] = $avatut_hakemuksetDTO;
							$dto["HakemuksetDTO"]["Paatetyt"] = $paatetyt_hakemuksetDTO;
							$dto["HakemuksetDTO"]["Omat"] = $omat_hakemuksetDTO;

							// Haetaan käsittelijät 
							if($viranomaisen_rooliDTO->Viranomaisen_koodi=="v_VSSHP"){
								$viranomaisten_roolitDTO = $viranomaisen_rooliDAO->hae_viranomaisten_roolit_koodin_ja_roolin_perusteella($viranomaisen_rooliDTO->Viranomaisen_koodi, "rooli_eettisensihteeri");
							} else {
								$viranomaisten_roolitDTO = $viranomaisen_rooliDAO->hae_viranomaisten_roolit_koodin_ja_roolin_perusteella($viranomaisen_rooliDTO->Viranomaisen_koodi, "rooli_kasitteleva");
							}
							
							for($i=0; $i < sizeof($viranomaisten_roolitDTO); $i++){
								$viranomaisten_roolitDTO[$i]->KayttajaDTO = $kayttajaDAO->hae_kayttajan_tiedot($viranomaisten_roolitDTO[$i]->KayttajaDTO->ID);
							}
							
							$dto["Viranomaisten_roolitDTO"]["Kasittelijat"] = $viranomaisten_roolitDTO;

							//$dto = hae_lukemattomien_viestien_maara_kayttajan_roolille($dto,$db,$kayt_id,$viranomaisen_rooliDTO->Viranomaisroolin_koodi);
							//$dto = hae_saapuneiden_lausuntojen_maara_kayttajalle($dto,$db,$kayt_id);

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_saapuneet_lausunnot_viranomaiselle($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$viranomaisroolin_koodi = $parametrit["viranomaisroolin_koodi"];

			if(!is_null($viranomaisroolin_koodi) && !is_null($kayt_id) && !is_null($parametrit["token"]) && ($viranomaisroolin_koodi=="rooli_eettisen_puheenjohtaja" || $viranomaisroolin_koodi=="rooli_eettisensihteeri" || $viranomaisroolin_koodi=="rooli_kasitteleva" || $viranomaisroolin_koodi=="rooli_paattava")){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>$viranomaisroolin_koodi, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

								$db->beginTransaction();

								$paatosDAO = new PaatosDAO($db);
								$hakemusDAO = new HakemusDAO($db);
								$hakemusversioDAO = new HakemusversioDAO($db);
								$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
								$lausuntoDAO = new LausuntoDAO($db);
								$lausunnon_lukeneetDAO = new Lausunnon_lukeneetDAO($db);
								$kayttajaDAO = new KayttajaDAO($db);
								$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);

								$lukemattomat_lausunnotDTO = array();
								$luetut_lausunnotDTO = array();
								$lausuntopyynnotDTO = $lausuntopyyntoDAO->hae_lausuntopyynnot_lausunnonpyytajalle($kayt_id);

								for($i=0; $i < sizeof($lausuntopyynnotDTO); $i++){

									$lausuntoDTO = $lausuntoDAO->hae_lausuntopyynnolle_lausunto($lausuntopyynnotDTO[$i]->ID);

									if(isset($lausuntoDTO->ID) && !is_null($lausuntoDTO->ID)){
										if($lausuntoDTO->Lausunto_julkaistu==1){
											// Haetaan lausunnon antajan nimi ja viranomaisen koodi
											$lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja = $kayttajaDAO->hae_kayttajan_tiedot($lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja->ID);
											$lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja->ID);
											$lausuntopyynnotDTO[$i]->HakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($lausuntopyynnotDTO[$i]->HakemusDTO->ID);
											$lausuntopyynnotDTO[$i]->TutkimusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($lausuntopyynnotDTO[$i]->HakemusDTO->HakemusversioDTO->ID);
											$lausuntopyynnotDTO[$i]->TutkimusDTO->HakemusversioDTO->HakemusDTO = $lausuntopyynnotDTO[$i]->HakemusDTO; // temp settii, poista
											$lausuntoDTO->LausuntopyyntoDTO = $lausuntopyynnotDTO[$i];

											if($lausunnon_lukeneetDAO->lausunto_on_luettu($kayt_id, $lausuntoDTO->ID)){
												array_push($luetut_lausunnotDTO, $lausuntoDTO);
											} else {
												array_push($lukemattomat_lausunnotDTO, $lausuntoDTO);
											} 
										}
									}

								}
								$dto["LausunnotDTO"]["Lukemattomat"] = $lukemattomat_lausunnotDTO;
								$dto["LausunnotDTO"]["Luetut"] = $luetut_lausunnotDTO;

								$db->commit();
								$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_yhteenveto_viranomaiselle($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$viranomaisroolin_koodi = $parametrit["viranomaisroolin_koodi"];

			if(!is_null($viranomaisroolin_koodi) && !is_null($kayt_id) && !is_null($parametrit["token"]) && ($viranomaisroolin_koodi=="rooli_aineistonmuodostaja" || $viranomaisroolin_koodi=="rooli_paattava" || $viranomaisroolin_koodi=="rooli_kasitteleva")){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							if($viranomaisen_rooliDTO = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, $viranomaisroolin_koodi)){

								$db->beginTransaction();

								$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
								$hakemusDAO = new HakemusDAO($db);
								$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
								$hakemusversioDAO = new HakemusversioDAO($db);
								$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
								$lausuntoDAO = new LausuntoDAO($db);

								$hak_lah = 0;
								$hak_kas = 0;
								$hak_paat = 0;
								$lp_pyydetty = 0;
								$lp_vastattu = 0;
								$aint_uusi = 0;
								$aint_kas = 0;
								$aint_toimitettu = 0;
								$aint_rekl = 0;

								if($viranomaisen_rooliDTO->Viranomaisroolin_koodi=="rooli_kasitteleva" || $viranomaisen_rooliDTO->Viranomaisroolin_koodi=="rooli_paattava"){

									$tutkimukset = array();

									$hakemuksetDTO = $hakemusDAO->hae_viranomaisorganisaation_hakemukset($viranomaisen_rooliDTO->Viranomaisen_koodi);

									for($i=0; $i < sizeof($hakemuksetDTO); $i++){

										$hakemuksen_tilatDTO = $hakemuksen_tilaDAO->hae_hakemuksen_hakemuksen_tilat($hakemuksetDTO[$i]->ID);

										for($j=0; $j < sizeof($hakemuksen_tilatDTO); $j++){

											if($hakemuksen_tilatDTO[$j]->Hakemuksen_tilan_koodi=="hak_lah"){
												$hak_lah++;
											}

											if($hakemuksen_tilatDTO[$j]->Hakemuksen_tilan_koodi=="hak_kas"){
												$hak_kas++;
											}

											if($hakemuksen_tilatDTO[$j]->Hakemuksen_tilan_koodi=="hak_paat"){
												$hak_paat++;
											}

										}

										$hakemuksetDTO[$i]->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemuksetDTO[$i]->HakemusversioDTO->ID);
										array_push($tutkimukset,$hakemuksetDTO[$i]->HakemusversioDTO->TutkimusDTO->ID);

									}
									$tutkimukset = array_unique($tutkimukset);

									for($i=0; $i < sizeof($tutkimukset); $i++){

										$lausuntopyynnotDTO = $lausuntopyyntoDAO->hae_tutkimuksen_lausuntopyynnot($tutkimukset[$i]);

										for($j=0; $j < sizeof($lausuntopyynnotDTO); $j++){

											$lp_pyydetty++;

											$lausuntoDTO = $lausuntoDAO->hae_lausuntopyynnolle_julkaistu_lausunto($lausuntopyynnotDTO[$j]->ID);

											if(isset($lausuntoDTO->ID) && !is_null($lausuntoDTO->ID) && !empty($lausuntoDTO->ID)){
												$lp_vastattu++;
											}

										}
									}
									$dto["Yhteenveto_viranomaiselle"]["hak_lah"] = $hak_lah;
									$dto["Yhteenveto_viranomaiselle"]["hak_kas"] = $hak_kas;
									$dto["Yhteenveto_viranomaiselle"]["hak_paat"] = $hak_paat;
									$dto["Yhteenveto_viranomaiselle"]["lp_pyydetty"] = $lp_pyydetty;
									$dto["Yhteenveto_viranomaiselle"]["lp_vastattu"] = $lp_vastattu;

								}
								if($viranomaisen_rooliDTO->Viranomaisroolin_koodi=="rooli_aineistonmuodostaja"){

									$paatosDAO = new PaatosDAO($db);
									$aineistotilausDAO = new AineistotilausDAO($db);
									$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);

									$hakemuksetDTO = $hakemusDAO->hae_viranomaisorganisaation_hakemukset($viranomaisen_rooliDTO->Viranomaisen_koodi);

									for($i=0; $i < sizeof($hakemuksetDTO); $i++){

										$aineistotilauksen_tilatDTO = $aineistotilauksen_tilaDAO->hae_tilojen_koodi_aineistotilauksen_avaimella($aineistotilausDAO->hae_id_paatoksen_avaimella($paatosDAO->hae_hakemuksen_paatos($hakemuksetDTO->ID)->ID)->ID);

										for($j=0; $j < sizeof($aineistotilauksen_tilatDTO); $j++){

											if($aineistotilauksen_tilatDTO[$j]->Aineistotilauksen_tilan_koodi=="aint_uusi"){
												$aint_uusi++;
											}
											if($aineistotilauksen_tilatDTO[$j]->Aineistotilauksen_tilan_koodi=="aint_kas"){
												$aint_kas++;
											}

											if($aineistotilauksen_tilatDTO[$j]->Aineistotilauksen_tilan_koodi=="aint_toimitettu"){
												$aint_toimitettu++;
											}

											if($aineistotilauksen_tilatDTO[$j]->Aineistotilauksen_tilan_koodi=="aint_rekl"){
												$aint_rekl++;
											}

										}
									}
									$dto["Yhteenveto_viranomaiselle"]["aint_uusi"] = $aint_uusi;
									$dto["Yhteenveto_viranomaiselle"]["aint_kas"] = $aint_kas;
									$dto["Yhteenveto_viranomaiselle"]["aint_toimitettu"] = $aint_toimitettu;
									$dto["Yhteenveto_viranomaiselle"]["aint_rekl"] = $aint_rekl;

								}
								$dto["Autentikoitu_viranomainen"]["Viranomaisen_rooliDTO"] = $viranomaisen_rooliDTO;

								$db->commit();
								$db = null;

							}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Etsitään tietokannasta hakemusta termien perusteella
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function etsi_hakemuksia($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$viranomaisroolin_koodi = $parametrit["viranomaisroolin_koodi"];
			$tutk_nimi=""; $diar_nro=""; $hak_tila=""; $hak_nimi=""; $hak_rooli=""; $tutk_nro=""; $vuosi_alku=""; $vuosi_loppu=""; $nimi1=""; $nimi2="";

			if(isset($parametrit["data"]["tutk_nimi"])) $tutk_nimi = $parametrit["data"]["tutk_nimi"];
			if(isset($parametrit["data"]["diar_nro"])) $diar_nro = $parametrit["data"]["diar_nro"];
			if(isset($parametrit["data"]["hak_tila"])) $hak_tila = $parametrit["data"]["hak_tila"];
			if(isset($parametrit["data"]["hak_nimi"])) $hak_nimi = $parametrit["data"]["hak_nimi"];
			if(isset($parametrit["data"]["hak_rooli"])) $hak_rooli = $parametrit["data"]["hak_rooli"];
			if(isset($parametrit["data"]["tutk_nro"])) $tutk_nro = $parametrit["data"]["tutk_nro"];
			if(isset($parametrit["data"]["vuosi_alku"])) $vuosi_alku = $parametrit["data"]["vuosi_alku"];
			if(isset($parametrit["data"]["vuosi_loppu"])) $vuosi_loppu = $parametrit["data"]["vuosi_loppu"];

			if($hak_nimi!=""){
				$hak_nimet = explode(" ", $hak_nimi);
				$nimi1 = $hak_nimet[0];
				$nimi2 = $hak_nimet[1];
			}
			
			$loydetyt_hakemusversiot = array();

			if(!is_null($viranomaisroolin_koodi) && !is_null($kayt_id) && !is_null($parametrit["token"]) && ($viranomaisroolin_koodi=="rooli_eettisen_puheenjohtaja" || $viranomaisroolin_koodi=="rooli_eettisensihteeri" || $viranomaisroolin_koodi=="rooli_kasitteleva" || $viranomaisroolin_koodi=="rooli_paattava")){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>$viranomaisroolin_koodi, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$viranomaisen_rooliDTO = $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO;

							$db->beginTransaction();

							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
							$hakijaDAO = new HakijaDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);

							$loydetyt_hakemuksetDTO = array();
							$etsittavat = array();
													
							// Etsitään tutkimuksen nimen perusteella
							$loydetyt_nimella = array();							
							if($tutk_nimi!=""){
								
								$hakemusversiotDTO = $hakemusversioDAO->etsi_tutkimuksen_nimella($tutk_nimi);
								
								for($i=0; $i < sizeof($hakemusversiotDTO); $i++){
									array_push($loydetyt_nimella, $hakemusversiotDTO[$i]->ID);
								}
								
								$etsittavat[] = $loydetyt_nimella;
								
							}
							
							// Etsitään hakijan nimen perusteella
							$loydetyt_hakijalla = array();							
							if($nimi1!="" || $nimi2!=""){
				
								$hakijatDTO = $hakijaDAO->etsi_hakijan_nimella($nimi1, $nimi2);
								
								for($i=0; $i < sizeof($hakijatDTO); $i++){
									
									$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakijan_roolin_tiedot_hakijan_avaimella($hakijatDTO[$i]->ID);
									
									for($j=0; $j < sizeof($hakijan_roolitDTO); $j++){
										array_push($loydetyt_hakijalla, $hakijan_roolitDTO[$j]->HakemusversioDTO->ID);
									}
									
								}
								
								$etsittavat[] = $loydetyt_hakijalla;
								
							}
							
							// Etsitään hakijan roolin perusteella 
							$loydetyt_roolilla = array();							
							if($hak_rooli!=""){
						
								$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakijan_roolilla($hak_rooli);
								
								for($i=0; $i < sizeof($hakijan_roolitDTO); $i++){
									array_push($loydetyt_roolilla, $hakijan_roolitDTO[$i]->HakemusversioDTO->ID);
								}
								
								$etsittavat[] = $loydetyt_roolilla;
								
							}
							
							// Etsitään tunnuksella
							$loydetyt_tunnuksella = array();
							if($tutk_nro!=""){
								
								$hakemusDTO = $hakemusDAO->hae_hakemus_tunnuksella($tutk_nro);
								array_push($loydetyt_tunnuksella, $hakemusDTO->HakemusversioDTO->ID);
								$etsittavat[] = $loydetyt_tunnuksella;
								
							}
							
							// Etsitään tilan perusteella
							$loydetyt_tilalla = array();
							if($hak_tila!=""){
								
								$hakemuksen_tilatDTO = $hakemuksen_tilaDAO->etsi_tilan_perusteella($hak_tila);
								
								for($i=0; $i < sizeof($hakemuksen_tilatDTO); $i++){
									array_push($loydetyt_tilalla, $hakemusDAO->hae_hakemuksen_tiedot($hakemuksen_tilatDTO[$i]->HakemusDTO->ID)->HakemusversioDTO->ID);									
								}
								
								$etsittavat[] = $loydetyt_tilalla;
								
							}
							
							// Etsitään käsittelyvuosien perusteella
							$loydetyt_vuosilla = array();
							if($vuosi_alku!="" || $vuosi_loppu!=""){
								
								if($vuosi_alku!="" && $vuosi_loppu!="")	$hakemuksen_tilatDTO = $hakemuksen_tilaDAO->etsi_alkuvuoden_ja_loppuvuoden_perusteella($vuosi_alku, $vuosi_loppu);																																		
								if($vuosi_alku!="" && $vuosi_loppu=="") $hakemuksen_tilatDTO = $hakemuksen_tilaDAO->etsi_alkuvuoden_ja_loppuvuoden_perusteella($vuosi_alku, date("Y"));													
								if($vuosi_alku=="" && $vuosi_loppu!="") $hakemuksen_tilatDTO = $hakemuksen_tilaDAO->etsi_alkuvuoden_ja_loppuvuoden_perusteella(2018, $vuosi_loppu);	
																	
								foreach ($hakemuksen_tilatDTO as $indx => $hakemuksen_tilaDTO){	 								
									array_push($loydetyt_vuosilla, $hakemusDAO->hae_hakemuksen_tiedot($hakemuksen_tilaDTO->HakemusDTO->ID)->HakemusversioDTO->ID);								
								}
								
								$etsittavat[] = $loydetyt_vuosilla;
								
							}
							
							// $dto["etsittavat"] = $etsittavat;
				
							if(sizeof($etsittavat)>1){
								$loydetyt_hakemusversiot = array_unique(call_user_func_array('array_intersect', $etsittavat));
							} else {
								$loydetyt_hakemusversiot = array_unique($etsittavat[0]);
							}
							
							//$dto["loydetyt_hv"] = $loydetyt_hakemusversiot;	
				
							// Käydään kaikki löydetyt hakemusversiot läpi ja haetaan niiden tiedot
							foreach ($loydetyt_hakemusversiot as $indx => $loydetty_hakemusversio_id){

								$hakemusDTO = $hakemusDAO->hae_hakemusversion_uusin_hakemus_viranomaiselle($loydetty_hakemusversio_id, $viranomaisen_rooliDTO->Viranomaisen_koodi);
								$hakemusDTO->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemusDTO->ID);
							
								if(isset($hakemusDTO->Hakemuksen_tunnus) && !is_null($hakemusDTO->Hakemuksen_tunnus)){
								
									// Filtteröidään hakemuksen tunnuksella
									if($tutk_nro!="" && $hakemusDTO->Hakemuksen_tunnus!=$tutk_nro) continue; 
									
									// Filtteröidään tilan perusteella
									if($hak_tila!="" && $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!=$hak_tila) continue;
									
									$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
									
									array_push($loydetyt_hakemuksetDTO, $hakemusDTO);
									
								}
								
							}

							$dto["HakemuksetDTO"]["Loydetyt"] = $loydetyt_hakemuksetDTO;

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemukset_tutkijalle($syoteparametrit) {

		$dto = array();
		$dto["HakemusversiotDTO"]["Keskeneraiset"] = array();
		$dto["Lomake_hakemuksetDTO"] = array();
		$dto["Paatetyt_tutkimukset"]["TutkimuksetDTO"] = array();
		$dto["Lahetetyt_tutkimukset"]["TutkimuksetDTO"] = array();
		$dto["Uusimpien_hakemusten_idt"] = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$kayt_kieli = "fi"; // Oletuskieli on suomi
			if(isset($parametrit["kayt_kieli"]) && ($parametrit["kayt_kieli"]=="fi" || $parametrit["kayt_kieli"]=="en")) $kayt_kieli = $parametrit["kayt_kieli"];

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							vapauta_kayttajan_lukitsemat_hakemusversiot($db, $kayt_id);

							$hakemusversioDAO = new HakemusversioDAO($db);
							$hakijaDAO = new HakijaDAO($db);
							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$paatosDAO = new PaatosDAO($db);
							$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);
							$hakemusversion_tilaDAO = new Hakemusversion_tilaDAO($db);
							$tutkimusDAO = new TutkimusDAO($db);
							$lomakeDAO = new LomakeDAO($db);
							$lomake_hakemusDAO = new Lomake_hakemusDAO($db);
							$viestitDAO = new ViestitDAO($db);
							$asiaDAO = new AsiaDAO($db);
							$aineistotilausDAO = new AineistotilausDAO($db);
							$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$paatoksen_liiteDAO = new Paatoksen_liiteDAO($db);

							$lomake_hakemuksetDTO = $lomake_hakemusDAO->hae_hakemus_lomakkeet();

							for($i=0; $i < sizeof($lomake_hakemuksetDTO); $i++){

								$lomake_hakemuksetDTO[$i]->LomakeDTO = $lomakeDAO->hae_lomake($lomake_hakemuksetDTO[$i]->LomakeDTO->ID);
																																	
								if($kayt_kieli=="en"){
									$lomake_hakemuksetDTO[$i]->Uusi_hakemus_painike_teksti = $lomake_hakemuksetDTO[$i]->Uusi_hakemus_painike_teksti_en;
								} else {
									$lomake_hakemuksetDTO[$i]->Uusi_hakemus_painike_teksti = $lomake_hakemuksetDTO[$i]->Uusi_hakemus_painike_teksti_fi;
								}
																	
							}
							
							$dto["Lomake_hakemuksetDTO"] = $lomake_hakemuksetDTO;

							$hakijatDTO = $hakijaDAO->hae_kayttajan_hakijat($kayt_id);
							$kayttajan_hakemusversio_idt = array();
							$kayttajan_keskeneraiset_hakemusversiotDTO = array();
							$kayttajan_tutkimukset = array();

							// Haetaan keskeneräiset hakemukset
							for($h=0; $h < sizeof($hakijatDTO); $h++){

								if(is_null($hakijatDTO[$h]->Jasen)) continue; // Skipataan jos hakija ei ole tutkimusryhmän jäsen (vielä)
							
								$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakijan_roolin_tiedot_hakijan_avaimella($hakijatDTO[$h]->ID);

								for($r=0; $r < sizeof($hakijan_roolitDTO); $r++){
									if(!in_array($hakijan_roolitDTO[$r]->HakemusversioDTO->ID,$kayttajan_hakemusversio_idt)){
										
										$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakijan_roolitDTO[$r]->HakemusversioDTO->ID);

										if($hakemusversioDTO->Lisaaja==$kayt_id || $hakijan_roolitDTO[$r]->Hakijan_roolin_koodi=="rooli_hak_yht" || $hakijan_roolitDTO[$r]->Hakijan_roolin_koodi=="rooli_vast"){
											$hakemusversioDTO->On_oikeus_poistaa = true;
										}
										
										array_push($kayttajan_hakemusversio_idt,$hakemusversioDTO->ID);

										if(!in_array($hakemusversioDTO->TutkimusDTO->ID, $kayttajan_tutkimukset)){
											array_push($kayttajan_tutkimukset, $hakemusversioDTO->TutkimusDTO->ID);
										}
										
										$hakemusversioDTO->Hakemusversion_tilaDTO = $hakemusversion_tilaDAO->hae_hakemusversion_uusin_tila($hakemusversioDTO->ID);
										$hakemusversioDTO->LomakeDTO = $lomakeDAO->hae_lomake($hakemusversioDTO->LomakeDTO->ID);
										
										if($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken"){
											array_push($kayttajan_keskeneraiset_hakemusversiotDTO, $hakemusversioDTO);
										}

									}
								}
								
							}
							
							$dto["HakemusversiotDTO"]["Keskeneraiset"] = $kayttajan_keskeneraiset_hakemusversiotDTO;
							$dto["Aineistotilaukset"]["PaatoksetDTO"] = array();							

							for($i=0; $i < sizeof($kayttajan_tutkimukset); $i++){

								$tutkimuksen_paatettyjen_hakemusten_lkm = 0;
								$tutkimuksen_lahetettyjen_hakemusten_lkm = 0;
								$aineistotilaus_sallittu = false;
								$lisatietoa_pyydetty = false;
								$taydennyshakemus_olemassa = false;
								$tutkimusDTO = $tutkimusDAO->hae_tutkimus($kayttajan_tutkimukset[$i]);
								$tutkimusDTO->Tutkimuksen_nimi = $hakemusversioDAO->hae_tutkimuksen_uusin_hakemusversio($tutkimusDTO->ID)->Tutkimuksen_nimi; // Tutkimuksen nimi on uusimman hakemusversion nimi

								$tutkimuksen_hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_kaikki_hakemusversiot($tutkimusDTO->ID);

								for($j=0; $j < sizeof($tutkimuksen_hakemusversiotDTO); $j++){

									$tutkimuksen_hakemusversiotDTO[$j]->Hakemusversion_tilaDTO = $hakemusversion_tilaDAO->hae_hakemusversion_uusin_tila($tutkimuksen_hakemusversiotDTO[$j]->ID);

									if($tutkimuksen_hakemusversiotDTO[$j]->Hakemuksen_tyyppi=="tayd_hak" && $tutkimuksen_hakemusversiotDTO[$j]->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") $taydennyshakemus_olemassa = true;
																												
									if($tutkimuksen_hakemusversiotDTO[$j]->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_lah"){

										$hakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset($tutkimuksen_hakemusversiotDTO[$j]->ID);

										for($h=0; $h < sizeof($hakemuksetDTO); $h++){

											$tutkimuksen_lahetettyjen_hakemusten_lkm++;

											$hakemuksetDTO[$h]->AsiaDTO = $asiaDAO->hae_asia($hakemuksetDTO[$h]->AsiaDTO->ID); // Haetaan diaarinro
											$hakemuksetDTO[$h]->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemuksetDTO[$h]->ID);

											if($hakemuksetDTO[$h]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_muuta") $lisatietoa_pyydetty = true;
											
											if($hakemuksetDTO[$h]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat" || $hakemuksetDTO[$h]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_korvattu"){

												$paatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemuksetDTO[$h]->ID);
												$paatosDTO->Paatoksen_tilaDTO = $paatoksen_tilaDTO = $paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($paatosDTO->ID);

												if($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_ehd_hyvaksytty" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hylatty" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty"){

													$tutkimuksen_paatettyjen_hakemusten_lkm++;
													$paatosDTO->Paatoksen_liitteetDTO = $paatoksen_liiteDAO->hae_paatoksen_liitteet($paatosDTO->ID);
														
													if($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_ehd_hyvaksytty") $lisatietoa_pyydetty = true;	
														
													if($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty"){

														$paatosDTO->AineistotilausDTO = $aineistotilausDAO->hae_aineistotilaus_paatokselle($paatosDTO->ID);
																													
														if(isset($paatosDTO->AineistotilausDTO->ID)){

															$paatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO = $aineistotilauksen_tilaDAO->hae_tilan_koodi_aineistotilauksen_avaimella($paatosDTO->AineistotilausDTO->ID);

															if(isset($paatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi)){
																if($paatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_keskenerainen"){ 
																	if($hakemuksetDTO[$h]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_korvattu") $aineistotilaus_sallittu = true;
																} else {

																	// Haetaan aineiston tilaajan ja muodostajan tiedot
																	$paatosDTO->AineistotilausDTO->KayttajaDTO_Aineiston_tilaaja = $kayttajaDAO->hae_kayttajan_tiedot($paatosDTO->AineistotilausDTO->Aineiston_tilaaja);
																	$paatosDTO->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja = $kayttajaDAO->hae_kayttajan_tiedot($paatosDTO->AineistotilausDTO->Aineistonmuodostaja);

																	$paatosDTO->HakemusDTO = $hakemuksetDTO[$h];
																	$paatosDTO->HakemusDTO->HakemusversioDTO = $tutkimuksen_hakemusversiotDTO[$j];

																	// Haetaan aineistotilauksen historia
																	$paatosDTO->AineistotilausDTO->Aineistotilauksen_tilatDTO = $aineistotilauksen_tilaDAO->hae_tilojen_koodi_aineistotilauksen_avaimella($paatosDTO->AineistotilausDTO->ID);

																	array_push($dto["Aineistotilaukset"]["PaatoksetDTO"], $paatosDTO);

																}
															}
														} 

													} 

												}
												
												$hakemuksetDTO[$h]->PaatosDTO = $paatosDTO;

											}
											// Haetaan löytyykö hakemuksen viestejä
											$hakemuksen_viestitDTO = $viestitDAO->hae_hakemuksen_viestit($hakemuksetDTO[$h]->ID);

											if(sizeof($hakemuksen_viestitDTO) > 0){
												$hakemuksetDTO[$h]->Hakemus_sisaltaa_viesteja = true;
											} else {
												$hakemuksetDTO[$h]->Hakemus_sisaltaa_viesteja = false;
											}
										}
										$tutkimuksen_hakemusversiotDTO[$j]->HakemuksetDTO = $hakemuksetDTO;

									}
								}

								$tutkimusDTO->HakemusversiotDTO = $tutkimuksen_hakemusversiotDTO;
								
								// Luokitellaan hakemukset
								$hakemuksetDTO_uusimmat = hae_tutkimuksen_uusimmat_hakemukset($db, $kayttajan_tutkimukset[$i]);								
								$paatetyt_tutkimus_idt = array();
								$lahetetyt_tutkimus_idt = array();
								$paatettyja = 0;
								$hyvaksyttyja = 0;
								$ei_paatettyja = 0;
								
								foreach ($hakemuksetDTO_uusimmat as $vir_koodi => $hakemusDTO) {

									array_push($dto["Uusimpien_hakemusten_idt"], $hakemusDTO->ID);
									$hakemusDTO->PaatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemusDTO->ID);
									$hakemusDTO->PaatosDTO->Paatoksen_tilaDTO = $paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($hakemusDTO->PaatosDTO->ID);

									if(isset($hakemusDTO->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi) && $hakemusDTO->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty"){
										$paatettyja++;
										$hyvaksyttyja++;
									} else if(isset($hakemusDTO->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi) && ($hakemusDTO->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hylatty" || $hakemusDTO->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_ehd_hyvaksytty")) {
										$paatettyja++; 
									} else {
										$ei_paatettyja++;
									}
									
									if($hakemusDTO->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi!="paat_tila_hylatty" && $hyvaksyttyja!=sizeof($hakemuksetDTO_uusimmat) && $lisatietoa_pyydetty && !$taydennyshakemus_olemassa) $tutkimusDTO->Taydennyshakemuksen_luominen_sallittu = true;
									
									if($paatettyja==sizeof($hakemuksetDTO_uusimmat)){
										
										if($aineistotilaus_sallittu && $hyvaksyttyja==sizeof($hakemuksetDTO_uusimmat)) $tutkimusDTO->Aineistotilaus_sallittu = true;
										// t
										
										if(!in_array($tutkimusDTO->ID,$paatetyt_tutkimus_idt)){
											array_push($dto["Paatetyt_tutkimukset"]["TutkimuksetDTO"], $tutkimusDTO);	
											array_push($paatetyt_tutkimus_idt, $tutkimusDTO->ID);
										} 		
										
									} else {
										if($ei_paatettyja > 0 && !in_array($tutkimusDTO->ID,$lahetetyt_tutkimus_idt)){											
											array_push($dto["Lahetetyt_tutkimukset"]["TutkimuksetDTO"], $tutkimusDTO);
											array_push($lahetetyt_tutkimus_idt, $tutkimusDTO->ID);
										} 
									}									

								}								
																
							}

							$dto["HakemusversiotDTO"]["Lahetetyt_uusimmat"] = array();

							// Haetaan tutkimusten uusimmat lähetetyt hakemusversiot (muutoshakemus listaa varten)
							for($i=0; $i < sizeof($kayttajan_tutkimukset); $i++){

								$tutk_uusin_hakemusversioDTO = $hakemusversioDAO->hae_tutkimuksen_uusin_hakemusversio($kayttajan_tutkimukset[$i]);
								$tutk_uusin_hakemusversioDTO->Hakemusversion_tilaDTO = $hakemusversion_tilaDAO->hae_hakemusversion_uusin_tila($tutk_uusin_hakemusversioDTO->ID);
								$tutk_uusimmat_hakemuksetDTO = hae_tutkimuksen_uusimmat_hakemukset($db, $kayttajan_tutkimukset[$i]);
								$muutoshakemus_sallittu = false;
								
								foreach ($tutk_uusimmat_hakemuksetDTO as $viranomaisen_koodi => $uusi_hakemusDTO){
									if($uusi_hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat" || $uusi_hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_peruttu"){
										$muutoshakemus_sallittu = true;	
										break;
									}																		
								}								
								
								if($muutoshakemus_sallittu && $tutk_uusin_hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_lah"){	 																																
									$dto["HakemusversiotDTO"]["Lahetetyt_uusimmat"][sizeof($dto["HakemusversiotDTO"]["Lahetetyt_uusimmat"])] = $tutk_uusin_hakemusversioDTO;									
								}

							}
					
							//$dto = hae_eraantyvien_kayttolupien_maara_kayttajalle(hae_lukemattomien_viestien_maara_kayttajan_roolille($dto,$db,$kayt_id,"rooli_hakija"),$db,$kayt_id);

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_lomakkeet($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){
								$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;
							} else if($viranomaisen_rooliDTO_autentikoitu_kayttaja = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, "rooli_viranomaisen_paak")) {
								$dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO = $viranomaisen_rooliDTO_autentikoitu_kayttaja;
							} else {
								throw new SoapFault(ERR_INVALID_ID, "Ei käyttöoikeutta.");
							}

							$db->beginTransaction();

							$lomakeDAO = new LomakeDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);

							$lomakkeetDTO = $lomakeDAO->hae_lomakkeet();

							// Haetaan lomakkeen tekijät
							for($i=0; $i < sizeof($lomakkeetDTO); $i++){
								$lomakkeetDTO[$i]->KayttajaDTO = $kayttajaDAO->hae_kayttajan_tiedot($lomakkeetDTO[$i]->KayttajaDTO->ID);
							}

							$dto["LomakkeetDTO"] = $lomakkeetDTO;

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_lomake($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$lomake_id = $parametrit["lomake_id"];

			if(!is_null($lomake_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){
								$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;
							} else if($viranomaisen_rooliDTO_autentikoitu_kayttaja = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, "rooli_viranomaisen_paak")) {
								$dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO = $viranomaisen_rooliDTO_autentikoitu_kayttaja;
							} else {
								throw new SoapFault(ERR_INVALID_ID, "Ei käyttöoikeutta.");
							}
							$db->beginTransaction();

							$lomakeDAO = new LomakeDAO($db);
							$lomake_hakemusDAO = new Lomake_hakemusDAO($db);
							$lomake_paatosDAO = new Lomake_paatosDAO($db);
							$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);
							$koodistotDAO = new KoodistotDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$osioDAO = new OsioDAO($db);
							$asiakirjahallinta_liiteDAO = new Asiakirjahallinta_liiteDAO($db);
							$asiakirjahallinta_saantoDAO = new Asiakirjahallinta_saantoDAO($db);
							$osio_lauseDAO = new Osio_lauseDAO($db);

							$lomakeDTO = $lomakeDAO->hae_lomake($lomake_id);

							// Haetaan hakemuskohtaiset metatiedot
							if($lomakeDTO->Lomakkeen_tyyppi=="Hakemus") $lomakeDTO->Lomake_hakemusDTO = $lomake_hakemusDAO->hae_lomakkeen_lomake_hakemus($lomakeDTO->ID);
																					
							// Haetaan päätöskohtaiset metatiedot
							if($lomakeDTO->Lomakkeen_tyyppi=="Päätös"){
								$lomakeDTO->Lomake_paatosDTO = $lomake_paatosDAO->hae_lomakkeen_lomake_paatos($lomakeDTO->ID);
							}
							// Haetaan lomakkeen sivut
							$lomakeDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($lomakeDTO->ID);

							foreach($lomakeDTO->Lomakkeen_sivutDTO as $tunniste => $lomake_sivutDTO) {

								$kayt_kieli = "fi";
							
								if(!is_null($dto["Istunto"]["Kayttaja"]->Kieli_koodi)) $kayt_kieli = $dto["Istunto"]["Kayttaja"]->Kieli_koodi;
									
								// Haetaan sivun osiot
								$lomakeDTO->Lomakkeen_sivutDTO[$tunniste]->OsiotDTO_puu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_puu($lomakeDTO->ID, $tunniste, null, null, false, null);
								$lomakeDTO->Lomakkeen_sivutDTO[$tunniste]->OsiotDTO_taulu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_taulukko($tunniste, null, null, $lomakeDTO->ID, false, null);

							}
							// Sivupohjaiset tiedot
							if(isset($lomakeDTO->Lomakkeen_sivutDTO["hakemus_liitteet"])){
								
								$lomakeDTO->Asiakirjahallinta_liitteetDTO = $asiakirjahallinta_liiteDAO->hae_lomakkeen_liitetyypit($lomakeDTO->ID, "fi");

								for($i=0; $i < sizeof($lomakeDTO->Asiakirjahallinta_liitteetDTO); $i++){

									$lomakeDTO->Asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO = $asiakirjahallinta_saantoDAO->hae_asiakirjan_saannot($lomakeDTO->Asiakirjahallinta_liitteetDTO[$i]->ID);

									if(isset($lomakeDTO->Asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO[0]->ID)){
										$lomakeDTO->Asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO[0]->Osio_lauseetDTO[0] = $osio_lauseDAO->hae_asiakirjan_saannon_lause($lomakeDTO->Asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO[0]->ID);
									}
								}

							}
							
							if(!is_null($dto["Istunto"]["Kayttaja"]->Kieli_koodi)){
								$kayt_kieli = $dto["Istunto"]["Kayttaja"]->Kieli_koodi;
							} else {
								$kayt_kieli = "fi";
							}
							
							$dto["KoodistotDTO_viranomaiset"] = $koodistotDAO->hae_viranomaiset($kayt_kieli);
							$dto["LomakeDTO"] = $lomakeDTO;

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Metodi hakee käyttäjälle erääntyvät käyttöluvat
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_eraantyvat_kayttoluvat($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							vapauta_kayttajan_lukitsemat_hakemusversiot($db, $kayt_id);

							$kayttolupaDao = new KayttolupaDAO($db);
							$kayttoluvatDTO = $kayttolupaDao->hae_kayttajan_kayttoluvat($kayt_id);
							//$eraantyvia_kayttolupia_kpl = 0;

							if(!empty($kayttoluvatDTO) && !is_null($kayttoluvatDTO)){

								$paatosDAO = new PaatosDAO($db);
								$hakemusDAO = new HakemusDAO($db);
								$hakemusversioDAO = new HakemusversioDAO($db);
								$aineistotilausDAO = new AineistotilausDAO($db);
								$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);

								for($i=0; $i < sizeof($kayttoluvatDTO); $i++){

									$kayttolupaDTO = $kayttoluvatDTO[$i];

									if(isset($kayttolupaDTO->Lakkaamispvm)){

										$date_tanaan = new DateTime(date_format(date_create(), 'Y-m-d H:i:s'));
										$paiviaLakkaamisPaivaan = $date_tanaan->diff(new DateTime($kayttolupaDTO->Lakkaamispvm))->days;

										if($paiviaLakkaamisPaivaan < 31 && $aineistotilauksen_tilaDAO->hae_tilan_koodi_aineistotilauksen_avaimella($aineistotilausDAO->hae_id_paatoksen_avaimella($kayttolupaDTO->PaatosDTO->ID)->ID)->Aineistotilauksen_tilan_koodi=="aint_toimitettu"){

											$kayttolupaDTO->PaatosDTO = $paatosDAO->hae_paatoksen_tiedot($kayttolupaDTO->PaatosDTO->ID);
											$kayttolupaDTO->PaatosDTO->HakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($kayttolupaDTO->PaatosDTO->HakemusDTO->ID);
											$kayttolupaDTO->PaatosDTO->HakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($kayttolupaDTO->PaatosDTO->HakemusDTO->HakemusversioDTO->ID);

											$dto["KayttoluvatDTO"][sizeof($dto["KayttoluvatDTO"])] = $kayttolupaDTO;
											//$eraantyvia_kayttolupia_kpl++;

										}
									}
								}
							}

							//$dto = hae_eraantyvien_kayttolupien_maara_kayttajalle(hae_lukemattomien_viestien_maara_kayttajan_roolille($dto,$db,$kayt_id,"rooli_hakija"),$db,$kayt_id);

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemusversio($syoteparametrit) {

		$dto = array();

		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemusversio_id = $parametrit["hakemusversio_id"];
			$kayttajan_rooli = $parametrit["kayttajan_rooli"];
			$valittu_sivu = null;
			$tallennussivu = null;
			$hakemus_id = null;			
			$kayt_kieli = (isset($parametrit["kayt_kieli"]) ? $parametrit["kayt_kieli"] : "fi"); // Oletuskieli on suomi			

			if(isset($parametrit["hakemus_id"])) $hakemus_id = $parametrit["hakemus_id"];
			if(isset($parametrit["tallennettavat_tiedot"])) $tallennussivu = $parametrit["tallennettavat_tiedot"];
			if(isset($parametrit["sivu"])) $valittu_sivu = $parametrit["sivu"];
			
			if(!is_null($kayttajan_rooli) && !is_null($hakemusversio_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {

						$db->beginTransaction();

						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>$kayttajan_rooli, "hakemusversio_id"=>$hakemusversio_id, "kayt_id"=>$kayt_id, "hakemus_id"=>$hakemus_id, "token"=>$parametrit["token"]))){

							// Konstruktoidaan Data Access Objektit
							$hakemusversioDAO = new HakemusversioDAO($db);
							$hakemusversion_tilaDAO = new Hakemusversion_tilaDAO($db);
							$lomakeDAO = new LomakeDAO($db);
							$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);
							$osioDAO = new OsioDAO($db);
							$jarjestelman_hakijan_roolitDAO = new Jarjestelman_hakijan_roolitDAO($db);
							$hakijaDAO = new HakijaDAO($db);
							$sitoumusDAO = new SitoumusDAO($db);
							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
							$haettu_aineistoDAO = new Haettu_aineistoDAO($db);
							$luvan_kohdeDAO = new Luvan_kohdeDAO($db);
							$haettu_luvan_kohdeDAO = new Haettu_luvan_kohdeDAO($db);
							$liiteDAO = new LiiteDAO($db);
							$asiakirjahallinta_liiteDAO = new Asiakirjahallinta_liiteDAO($db);
							$asiakirjahallinta_saantoDAO = new Asiakirjahallinta_saantoDAO($db);
							$hakemusversion_liiteDAO = new Hakemusversion_liiteDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$osio_lauseDAO = new Osio_lauseDAO($db);

							$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusversio_id);
							$hakemusversioDTO->Hakemusversion_tilaDTO = $hakemusversion_tilaDAO->hae_hakemusversion_uusin_tila($hakemusversioDTO->ID);

							$lomakeDTO = $lomakeDAO->hae_lomake($hakemusversioDTO->LomakeDTO->ID, null);

							if(is_null($tallennussivu)){
								$hakemusversioDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($lomakeDTO->ID);
							} else {
								$hakemusversioDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivu_tunnisteella($lomakeDTO->ID, $tallennussivu);
							}

							$dto["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"] = $jarjestelman_hakijan_roolitDAO->hae_hakemustyypin_hakijan_roolit($lomakeDTO->ID);

							$hakemusversioDTO->LomakeDTO = $lomakeDTO;
							$hakemusversioDTO->Haettu_aineistoDTO = $haettu_aineistoDAO->hae_hakemusversion_haetun_aineiston_indeksin_aineisto($hakemusversio_id, 0);
							$hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO = $haettu_luvan_kohdeDAO->hae_haetun_aineiston_haetun_luvan_kohteet($hakemusversioDTO->Haettu_aineistoDTO->ID);

							foreach($hakemusversioDTO->Lomakkeen_sivutDTO as $sivu => $lomakkeen_sivuDTO){

								if($sivu=="hakemus_organisaatiotiedot"){
									
									$osallistuva_organisaatioDAO = new Osallistuva_organisaatioDAO($db);
									$hakemusversioDTO->Osallistuvat_organisaatiotDTO = $osallistuva_organisaatioDAO->hae_hakemusversion_organisaatiot($hakemusversio_id);
									
								}  
								
								if($sivu=="hakemus_aineisto"){

									$haettu_muuttujaDAO = new Haettu_muuttujaDAO($db);
									$muuttujaDAO = new MuuttujaDAO($db);

									if(is_null($tallennussivu)){ 
										
										// Haetaan kaikki luvan kohteet
										$dto["Luvan_kohteetDTO"]["Kaikki"] = $luvan_kohdeDAO->hae_kaikki_luvan_kohteet();

										// Haetaan viranomaisten luvan kohteet
										$dto["Luvan_kohteetDTO"]["Viranomaiset"] = $luvan_kohdeDAO->hae_viranomaisten_luvan_kohteet();

										// Haetaan TAIKA Tilastoaineisto -> Muuttujat listaus
										$taika_luvan_kohteetDTO = $luvan_kohdeDAO->hae_tyypin_luvan_kohteet("Taika_tilastoaineisto");
										$aineistokatalogi_luvan_kohteetDTO = $luvan_kohdeDAO->hae_tyypin_luvan_kohteet("Aineistokatalogi");
										$luvan_kohteetDTO = array_merge($taika_luvan_kohteetDTO, $aineistokatalogi_luvan_kohteetDTO);
										
										foreach($luvan_kohteetDTO as $luvan_kohde_identifier => $taika_luvan_kohdeDTO){
											$luvan_kohteetDTO[$luvan_kohde_identifier]->MuuttujatDTO = $muuttujaDAO->hae_luvan_kohteen_muuttujat($luvan_kohde_identifier);
										}
									
										$dto["Luvan_kohteetDTO"]["Taika"] = $luvan_kohteetDTO;

									}
									
									if($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_lah"){
										
										if(is_null($tallennussivu)) $hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_puu($lomakeDTO->ID, "hakemus_aineisto", $hakemusversioDTO->Haettu_aineistoDTO->ID, "FK_Haettu_aineisto", true, $hakemusversioDTO->Hakemusversion_tilaDTO->Lisayspvm);
										$hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu =  $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_taulukko("hakemus_aineisto",$hakemusversioDTO->Haettu_aineistoDTO->ID, "FK_Haettu_aineisto", $lomakeDTO->ID, true, $hakemusversioDTO->Hakemusversion_tilaDTO->Lisayspvm);
									
									} else {
										
										if(is_null($tallennussivu)) $hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_puu($lomakeDTO->ID, "hakemus_aineisto", $hakemusversioDTO->Haettu_aineistoDTO->ID, "FK_Haettu_aineisto", true, null);
										$hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu =  $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_taulukko("hakemus_aineisto",$hakemusversioDTO->Haettu_aineistoDTO->ID, "FK_Haettu_aineisto", $lomakeDTO->ID, true, null);
									
									}
									if(isset($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO)){
										foreach($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO as $kohde_tyyppi => $luvan_kohteet_tyyppi){
											for($i=0; $i < sizeof($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$kohde_tyyppi]); $i++){
												$hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$kohde_tyyppi][$i]->Haetut_muuttujatDTO = $haettu_muuttujaDAO->hae_haetun_luvan_kohteen_haetut_muuttujat($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$kohde_tyyppi][$i]->ID);
											}
										}
									}
									
								} else if($sivu=="hakemus_viranomaiskohtaiset"){

									$viranomaiset = array();

									if(isset($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO) && !empty($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO)){
										foreach($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO as $kohde_tyyppi => $luvan_kohteet_tyyppi){
											foreach($luvan_kohteet_tyyppi as $key => $haettu_luvan_kohdeDTO){
												if($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi!="v_0"){ // Ei viranomaista
													array_push($viranomaiset,$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi);
												}
											}
										}
									}

									$viranomaiset = array_unique($viranomaiset);

									if(!empty($viranomaiset)){
										if($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_lah"){
											if(is_null($tallennussivu)) $hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu = $osioDAO->hae_lomakkeen_sivun_ja_viranomaisten_osiot_puu($lomakeDTO->ID, $sivu, $hakemusversioDTO->ID, "FK_Hakemusversio", true, $viranomaiset, $hakemusversioDTO->Hakemusversion_tilaDTO->Lisayspvm);
											$hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu = $osioDAO->hae_lomakkeen_sivun_ja_viranomaisten_osiot_taulukko($lomakeDTO->ID, $sivu, $hakemusversioDTO->ID, "FK_Hakemusversio", true, $viranomaiset, $hakemusversioDTO->Hakemusversion_tilaDTO->Lisayspvm);
										} else {
											if(is_null($tallennussivu)) $hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu = $osioDAO->hae_lomakkeen_sivun_ja_viranomaisten_osiot_puu($lomakeDTO->ID, $sivu, $hakemusversioDTO->ID, "FK_Hakemusversio", true, $viranomaiset, null);
											$hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu = $osioDAO->hae_lomakkeen_sivun_ja_viranomaisten_osiot_taulukko($lomakeDTO->ID, $sivu, $hakemusversioDTO->ID, "FK_Hakemusversio", true, $viranomaiset, null);
										}
									}
								} else {																	
									if($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_lah"){
										if(is_null($tallennussivu)) $hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_puu($lomakeDTO->ID, $sivu, $hakemusversioDTO->ID, "FK_Hakemusversio", true, $hakemusversioDTO->Hakemusversion_tilaDTO->Lisayspvm);
										$hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_taulukko($sivu, $hakemusversioDTO->ID, "FK_Hakemusversio", $lomakeDTO->ID, true, $hakemusversioDTO->Hakemusversion_tilaDTO->Lisayspvm);
									} else {
										if(is_null($tallennussivu)) $hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_puu($lomakeDTO->ID, $sivu, $hakemusversioDTO->ID, "FK_Hakemusversio", true, null);
										$hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_taulukko($sivu, $hakemusversioDTO->ID, "FK_Hakemusversio", $lomakeDTO->ID, true, null);
									}																										
								}

							}
							
							// Haetaan Hakemukseen viittaavat liitteiden metatiedot
							$asiakirjahallinta_liitteetDTO = $asiakirjahallinta_liiteDAO->hae_lomakkeen_liitetyypit($lomakeDTO->ID, $kayt_kieli);

							// Haetaan asiakirjaan liittyvät säännöt
							for($i=0; $i < sizeof($asiakirjahallinta_liitteetDTO); $i++){

								$asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO = $asiakirjahallinta_saantoDAO->hae_asiakirjan_saannot($asiakirjahallinta_liitteetDTO[$i]->ID);

								if(isset($asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO[0]->ID)){
									$asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO[0]->Osio_lauseetDTO[0] = $osio_lauseDAO->hae_asiakirjan_saannon_lause($asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO[0]->ID);
								}

							}
							
							// Haetaan hakemusversioon lisätyt liitteet
							$hakemusversion_liitteetDTO = $hakemusversion_liiteDAO->hae_hakemusversion_liitteet($hakemusversio_id);
							$hakemusversioDTO->LiitteetDTO = array();

							for($i=0; $i < sizeof($hakemusversion_liitteetDTO); $i++){
								$hakemusversioDTO->LiitteetDTO[$i] = $liiteDAO->hae_liite($hakemusversion_liitteetDTO[$i]->LiiteDTO->ID);
								$hakemusversioDTO->LiitteetDTO[$i]->KayttajaDTO = $kayttajaDAO->hae_kayttajan_tiedot($hakemusversioDTO->LiitteetDTO[$i]->Lisaaja);
							}
							
							// Haetaan hakijat
							$vastaus = hae_hakemusversion_hakijat($db, $hakemusversioDTO);

							$hakemusversioDTO = $vastaus["hakemusversioDTO"];
							$sitoumuksetDTO = $vastaus["sitoumuksetDTO"];

							$hakemusversioDTO->Asiakirjahallinta_liitteetDTO = $asiakirjahallinta_liitteetDTO;
							
							// Haetaan edellisten hakemusten tilat
							if($hakemusversioDTO->Versio > 1 && $valittu_sivu=="hakemus_esikatsele_ja_laheta") $dto["Uusimmat_hakemuksetDTO"] = hae_tutkimuksen_uusimmat_hakemukset($db, $hakemusversioDTO->TutkimusDTO->ID);
																													
							//$dto["Istunto"]["Tutkimukset"][$hakemusversioDTO->TutkimusDTO->ID]["SitoumuksetDTO"] = $sitoumuksetDTO;
							//$dto["Istunto"]["Tutkimukset"][$hakemusversioDTO->TutkimusDTO->ID]["Hakemusversiot"][$hakemusversioDTO->ID] = lukitse_hakemusversio_jos_vapaa($db, $kayt_id, $hakemusversioDTO);							
							$dto["SitoumuksetDTO"] = $sitoumuksetDTO;
							$dto["HakemusversioDTO"] = lukitse_hakemusversio_jos_vapaa($db, $kayt_id, $hakemusversioDTO);
							
							// Haetaan hakemus
							if(!is_null($hakemus_id)){

								$hakemusDAO = new HakemusDAO($db);
								$asiaDAO = new AsiaDAO($db);
								$paatosDAO = new PaatosDAO($db);
								$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);

								$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemus_id);
								$hakemusDTO->AsiaDTO = $asiaDAO->hae_asia($hakemusDTO->AsiaDTO->ID);
								$hakemusDTO->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemusDTO->ID);
								$hakemusDTO->PaatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemusDTO->ID);
								$kayttajaDTO_Kasittelija = $kayttajaDAO->hae_kayttajan_tiedot($hakemusDTO->PaatosDTO->Kasittelija);
								$hakemusDTO->Kasittelijan_nimi = $kayttajaDTO_Kasittelija->Etunimi . " " . $kayttajaDTO_Kasittelija->Sukunimi;

								$dto["HakemusDTO"] = $hakemusDTO;
								$dto["Tutkimuksen_viranomaisen_hakemuksetDTO"] = hae_tutkimuksen_muut_viranomaisen_hakemukset($db, $hakemusversioDTO->TutkimusDTO->ID, $hakemusDTO->Viranomaisen_koodi, $hakemusDTO->ID);

							}
							
						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Käyttäjän autentikointi epäonnistui");
						}

						$db->commit();
						$db = null;

					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Palvelu hakee hakemukseen liittyvät viestit tutkijalle (sivu hakemus_viestit.php)
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemuksen_viestit_tutkijalle($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];

			if(!is_null($hakemus_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_hakija", "hakemus_id"=>$hakemus_id, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$hakemusDAO = new HakemusDAO($db);
							$viestitDAO = new ViestitDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);

							$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemus_id);
							$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);

							$dto["HakemusDTO"] = $hakemusDTO;

							// Haetaan saapuneet ja lähetetyt viestit (viestit jotka eivät ole vastauksia)
							$viestitDTO = $viestitDAO->hae_vastaanottajalle_hakemuksen_viestit_jotka_eivat_ole_vastauksia($kayt_id, $hakemusDTO->ID);

							for($i=0; $i < sizeof($viestitDTO); $i++){

								// Merkitään viesti luetuksi
								if($viestitDTO[$i]->Luettu==0 && $viestitDTO[$i]->KayttajaDTO_Vastaanottaja->ID==$kayt_id){ 
									$viestitDAO->merkitse_viesti_luetuksi($viestitDTO[$i]->ID);
								}
								$viestitDTO[$i]->HakemusDTO = $hakemusDTO;

								// Haetaan lähettäjän nimi
								$viestitDTO[$i]->KayttajaDTO_Lahettaja = $kayttajaDAO->hae_kayttajan_tiedot($viestitDTO[$i]->KayttajaDTO_Lahettaja->ID);

								// Haetaan lähettäjän viranomaisen koodi
								$viestitDTO[$i]->KayttajaDTO_Lahettaja->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($viestitDTO[$i]->KayttajaDTO_Lahettaja->ID);

								// Haetaan vastaanottajan nimi
								$viestitDTO[$i]->KayttajaDTO_Vastaanottaja = $kayttajaDAO->hae_kayttajan_tiedot($viestitDTO[$i]->KayttajaDTO_Vastaanottaja->ID);

								$vastaus_id = $viestitDTO[$i]->ViestitDTO_Child->ID;
								$viimeinen_vastaus_id = null;
								$v = 0;

								// Käydään viestin kaikki vastaukset c läpi
								while($vastaus_id != null){

									$viestitDTO[$i]->ViestitDTO_Vastaukset[$v] = $viestitDAO->hae_viesti($vastaus_id);
									$viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->KayttajaDTO_Lahettaja = $kayttajaDAO->hae_kayttajan_tiedot($viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->KayttajaDTO_Lahettaja->ID);

									$viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->KayttajaDTO_Vastaanottaja =  $kayttajaDAO->hae_kayttajan_tiedot($viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->KayttajaDTO_Vastaanottaja->ID);

									$viimeinen_vastaus_id = $vastaus_id;

									if(isset($viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->ViestitDTO_Child->ID)){
										$vastaus_id = $viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->ViestitDTO_Child->ID;
										$v++;
									} else {
										$vastaus_id = null;
									}
								}
								// Merkitään vastaus luetuksi
								if($viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->Luettu==0 && $viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->KayttajaDTO_Vastaanottaja->ID==$kayt_id){
									$viestitDAO->merkitse_viesti_luetuksi($viimeinen_vastaus_id);
								}

							}

							$dto["ViestitDTO"] = $viestitDTO;

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_metatiedot($syoteparametrit) {

		$dto = array();
		$dto["HakemusDTO"] = array();
		$liite_id = null;
		$lausunto_id = null;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$metatiedot_kohde = $parametrit["metatiedot_kohde"];

			$hakemus_id = $parametrit["hakemus_id"];
			if(isset($parametrit["liite_id"])) $liite_id = $parametrit["liite_id"];
			if(isset($parametrit["lausunto_id"])) $lausunto_id = $parametrit["lausunto_id"];

			if(!is_null($metatiedot_kohde) && !is_null($kayt_id) && !is_null($hakemus_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("hakemus_id"=>$hakemus_id, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$hakemusDAO = new HakemusDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);

							$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemus_id);
							$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
							$hakemusDTO->HakemusversioDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($hakemusDTO->HakemusversioDTO->LomakeDTO->ID);
							$hakemusDTO->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemusDTO->ID);

							if($metatiedot_kohde=="Paatos"){
								$paatosDAO = new PaatosDAO($db);
								$hakemusDTO->PaatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemusDTO->ID);
							} 

							if($metatiedot_kohde=="Asia"){
								$asiaDAO = new AsiaDAO($db);
								$hakemusDTO->AsiaDTO = $asiaDAO->hae_asia($hakemusDTO->AsiaDTO->ID);
							}
							if($metatiedot_kohde=="Liite" && !is_null($liite_id)){
								$liiteDAO = new LiiteDAO($db);
								$dto["LiiteDTO"] = $liiteDAO->hae_liite($liite_id);
							}
							if($metatiedot_kohde=="Lausunto" && !is_null($lausunto_id)){
								$lausuntoDAO = new LausuntoDAO($db);
								$dto["LausuntoDTO"] = $lausuntoDAO->hae_lausunto($lausunto_id);
							}
							$dto["HakemusDTO"] = $hakemusDTO;

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Funktio hakee tutkijalle tietokannasta aineistotilaukseen liittyvät resurssit sivulle hakemus_aineistotilaukset.php
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_tutkimuksen_aineistotilaus_tutkijalle($syoteparametrit) {

		$dto = array();
		$kayttaja_hakee_aineistoa = false;
		$dto["HakemuksetDTO_aineistopyynto"] = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$tutkimus_id = $parametrit["tutkimus_id"];

			if(!is_null($tutkimus_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_hakija","kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$hakemusversioDAO = new HakemusversioDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$paatosDAO = new PaatosDAO($db);
							$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);
							$aineistotilausDAO = new AineistotilausDAO($db);
							$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);
							$kayttolupaDAO = new KayttolupaDAO($db);
							$sitoumusDAO = new SitoumusDAO($db);

							$kayttaja_hakee_aineistoa = $sitoumusDAO->tutkimuksen_kayttaja_on_sitoutunut($tutkimus_id, $kayt_id);

							if($kayttaja_hakee_aineistoa){

								$hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_kaikki_hakemusversiot($tutkimus_id);

								for($i=0; $i < sizeof($hakemusversiotDTO); $i++){

									$hakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset($hakemusversiotDTO[$i]->ID);

									for($j=0; $j < sizeof($hakemuksetDTO); $j++){ 

										$hakemuksetDTO[$j]->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemuksetDTO[$j]->ID);

										if($hakemuksetDTO[$j]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat"){ 

											$hakemuksetDTO[$j]->PaatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemuksetDTO[$j]->ID);
											$hakemuksetDTO[$j]->PaatosDTO->Paatoksen_tilaDTO = $paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($hakemuksetDTO[$j]->PaatosDTO->ID);

											if($hakemuksetDTO[$j]->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty"){ 

												$kayttoluvatDTO = $kayttolupaDAO->hae_paatokseen_liittyvat_kayttoluvat($hakemuksetDTO[$j]->PaatosDTO->ID);

												for($k=0; $k < sizeof($kayttoluvatDTO); $k++){
													if($kayttoluvatDTO[$k]->KayttajaDTO->ID==$kayt_id){	// Käyttäjä on saanut luvan aineistoon

														$hakemuksetDTO[$j]->PaatosDTO->AineistotilausDTO = $aineistotilausDAO->hae_aineistotilaus_paatokselle($hakemuksetDTO[$j]->PaatosDTO->ID);
														$hakemuksetDTO[$j]->PaatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO = $aineistotilauksen_tilaDAO->hae_tilan_koodi_aineistotilauksen_avaimella($hakemuksetDTO[$j]->PaatosDTO->AineistotilausDTO->ID);

														if($hakemuksetDTO[$j]->PaatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_keskenerainen"){

															$hakemuksetDTO[$j]->HakemusversioDTO = $hakemusversiotDTO[$i];
															array_push($dto["HakemuksetDTO_aineistopyynto"],$hakemuksetDTO[$j]);

														}
													}
												}

											}
										}
									}
								}
							}
							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Palvelu hakee hakemukseen liittyvät viestit viranomaiselle (sivu viranomainen_hakemus_viestit.php)
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemuksen_viestit_viranomaiselle($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];
			$kayttajan_rooli = $parametrit["kayttajan_rooli"];

			if(!is_null($hakemus_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("sallitut_roolit"=>array("rooli_viranomaisen_paak","rooli_paattava","rooli_kasitteleva","rooli_lausunnonantaja","rooli_aineistonmuodostaja","rooli_eettisensihteeri","rooli_eettisen_puheenjohtaja"),"kayttajan_rooli"=>$kayttajan_rooli, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$viranomaisen_rooliDTO = $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO;

							$hakemusDAO = new HakemusDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
							$hakijaDAO = new HakijaDAO($db);
							$paatosDAO = new PaatosDAO($db);
							$paattajaDAO = new PaattajaDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$aineistotilausDAO = new AineistotilausDAO($db);
							$viestitDAO = new ViestitDAO($db);
							$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);
							
							$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemus_id);																				
							$hakemusDTO->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemusDTO->ID);
							$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
							$hakemusDTO->HakemusversioDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($hakemusDTO->HakemusversioDTO->LomakeDTO->ID);
							$hakemusDTO->PaatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemusDTO->ID);
							$hakemusDTO->PaatosDTO->PaattajatDTO = $paattajaDAO->hae_paatoksen_paattajat($hakemusDTO->PaatosDTO->ID);
							$kayttajaDTO_Kasittelija = $kayttajaDAO->hae_kayttajan_tiedot($hakemusDTO->PaatosDTO->Kasittelija);
							$hakemusDTO->Kasittelijan_nimi = $kayttajaDTO_Kasittelija->Etunimi . " " . $kayttajaDTO_Kasittelija->Sukunimi;
							
							$dto["HakemusDTO"] = $hakemusDTO;
							$dto["Tutkimuksen_viranomaisen_hakemuksetDTO"] = hae_tutkimuksen_muut_viranomaisen_hakemukset($db, $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID, $hakemusDTO->Viranomaisen_koodi, $hakemus_id);

							// Haetaan vastaanottajat (hakemuksen hakijat)
							$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijan_roolit($hakemusDTO->HakemusversioDTO->ID);

							for($i=0; $i < sizeof($hakijan_roolitDTO); $i++){ 
								$hakijan_roolitDTO[$i]->HakijaDTO = $hakijaDAO->hae_hakijan_tiedot($hakijan_roolitDTO[$i]->HakijaDTO->ID); 
								$hakijan_roolitDTO[$i]->HakijaDTO->KayttajaDTO = $kayttajaDAO->hae_kayttajan_tiedot($hakijan_roolitDTO[$i]->HakijaDTO->KayttajaDTO->ID);
							}
							$dto["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"] = $hakijan_roolitDTO;

							// Haetaan vastaanottajat (Tutkimukseen liittyvät toisten organisaatioiden viranomaiset)
							$muiden_vir_om_hakemuksetDTO = $hakemusDAO->hae_muiden_organisaatioiden_hakemusversion_hakemukset($hakemusDTO->HakemusversioDTO->ID, $hakemusDTO->Viranomaisen_koodi);
							$viranomaiset = array();
							$v = 0;

							for($i=0; $i < sizeof($muiden_vir_om_hakemuksetDTO); $i++){ 

								$paatosDTO = $paatosDAO->hae_hakemuksen_paatos($muiden_vir_om_hakemuksetDTO[$i]->ID);

								if(!is_null($paatosDTO->Kasittelija)){

									$viranomaiset[$v] = $kayttajaDAO->hae_kayttajan_tiedot($paatosDTO->Kasittelija);
									$viranomaiset[$v]->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($paatosDTO->Kasittelija);
									$v++;

								}
								$aineistotilausDTO = $aineistotilausDAO->hae_aineistotilaus_paatokselle($paatosDTO->ID);

								if(!is_null($aineistotilausDTO->Aineistonmuodostaja)){ 

									$viranomaiset[$v] = $kayttajaDAO->hae_kayttajan_tiedot($aineistotilausDTO->Aineistonmuodostaja);
									$viranomaiset[$v]->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($aineistotilausDTO->Aineistonmuodostaja);
									$v++;

									/*
									$kohdejoukon_tilausDTO = $kohdejoukon_tilausDAO->hae_kohdejoukon_tilaus_aineistotilaukselle($aineistotilausDTO->ID);

									if(!is_null($kohdejoukon_tilausDTO->Kohdejoukon_muodostaja)){

										$viranomaiset[$v] = $kayttajaDAO->hae_kayttajan_tiedot($kohdejoukon_tilausDTO->Kohdejoukon_muodostaja);
										$viranomaiset[$v]->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($kohdejoukon_tilausDTO->Kohdejoukon_muodostaja);
										$v++;

									}
									*/
								}
							}

							// Haetaan vastaanottajat (kaikki viranomaiset, joilla on sama Viranomaisen koodi)
							$omat_viranomaisen_roolitDTO = $viranomaisen_rooliDAO->hae_organisaation_viranomaiset_poislukien_haettu_kayttaja($hakemusDTO->Viranomaisen_koodi, $kayt_id);

							for($i=0; $i < sizeof($omat_viranomaisen_roolitDTO); $i++){

								$viranomaiset[$v] = $kayttajaDAO->hae_kayttajan_tiedot($omat_viranomaisen_roolitDTO[$i]->KayttajaDTO->ID);
								$viranomaiset[$v]->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($omat_viranomaisen_roolitDTO[$i]->KayttajaDTO->ID);
								$v++;

							}
							
							$dto["KayttajatDTO"]["Vastaanottajat_viranomaiset"] = $viranomaiset;

							// Haetaan saapuneet ja lähetetyt viestit (viestit jotka eivät ole vastauksia)
							$viestitDTO = $viestitDAO->hae_kayttajalle_hakemuksen_viestit_jotka_eivat_ole_vastauksia($kayt_id, $hakemusDTO->ID);

							// Haetaan viestien vastaukset sekä viestien lähettäjän ja vastaanottajan nimet
							for($i=0; $i < sizeof($viestitDTO); $i++){

								if($viestitDTO[$i]->Luettu==0 && $viestitDTO[$i]->KayttajaDTO_Vastaanottaja->ID==$kayt_id){ // Merkataan luetuksi (jos ei ole luettu)
									$viestitDAO->merkitse_viesti_luetuksi($viestitDTO[$i]->ID);
								}
								// Haetaan lähettäjän nimi
								$viestitDTO[$i]->KayttajaDTO_Lahettaja = $kayttajaDAO->hae_kayttajan_tiedot($viestitDTO[$i]->KayttajaDTO_Lahettaja->ID);

								// Haetaan vastaanottajan nimi
								$viestitDTO[$i]->KayttajaDTO_Vastaanottaja = $kayttajaDAO->hae_kayttajan_tiedot($viestitDTO[$i]->KayttajaDTO_Vastaanottaja->ID);

								$vastaus_id = $viestitDTO[$i]->ViestitDTO_Child->ID;
								$v = 0;

								// Käydään viestin i kaikki vastaukset c läpi
								while($vastaus_id != null){

									$viestitDTO[$i]->ViestitDTO_Vastaukset[$v] = $viestitDAO->hae_viesti($vastaus_id);

									if($viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->Luettu==0 && $viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->KayttajaDTO_Vastaanottaja->ID==$kayt_id){ // Merkataan luetuksi (jos ei ole luettu) 
										$viestitDAO->merkitse_viesti_luetuksi($viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->ID);
									}
									$viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->KayttajaDTO_Lahettaja = $kayttajaDAO->hae_kayttajan_tiedot($viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->KayttajaDTO_Lahettaja->ID);

									if(isset($viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->ViestitDTO_Child->ID)){
										$vastaus_id = $viestitDTO[$i]->ViestitDTO_Vastaukset[$v]->ViestitDTO_Child->ID;
										$v++;
									} else {
										$vastaus_id = null;
									}

								}

							}
							
							$dto["ViestitDTO"] = $viestitDTO;							
							
							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Viranomaiselle ladataan hakemukseen liittyvät lausunnot (viranomainen_hakemus_lausunto.php)
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemuksen_lausunnot_viranomaiselle($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];
			$kayttajan_rooli = $parametrit["kayttajan_rooli"];

			if(!is_null($hakemus_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("sallitut_roolit"=>array("rooli_viranomaisen_paak","rooli_paattava","rooli_kasitteleva","rooli_lausunnonantaja","rooli_aineistonmuodostaja","rooli_eettisensihteeri","rooli_eettisen_puheenjohtaja"),"kayttajan_rooli"=>$kayttajan_rooli, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$viranomaisen_rooliDTO = $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO;

							$hakemusDAO = new HakemusDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
							$lausuntoDAO = new LausuntoDAO($db);
							$lausunnon_lukeneetDAO = new Lausunnon_lukeneetDAO($db);
							$paatosDAO = new PaatosDAO($db);
							$paattajaDAO = new PaattajaDAO($db);
							$lausunnon_liiteDAO = new Lausunnon_liiteDAO($db);
							$liiteDAO = new LiiteDAO($db);
							$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);

							$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemus_id);
							$hakemusDTO->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemusDTO->ID);
							$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
							$hakemusDTO->HakemusversioDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($hakemusDTO->HakemusversioDTO->LomakeDTO->ID);
							$hakemusDTO->PaatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemusDTO->ID);
							$hakemusDTO->PaatosDTO->PaattajatDTO = $paattajaDAO->hae_paatoksen_paattajat($hakemusDTO->PaatosDTO->ID);

							$dto["HakemusDTO"] = $hakemusDTO;
							$dto["Tutkimuksen_viranomaisen_hakemuksetDTO"] = hae_tutkimuksen_muut_viranomaisen_hakemukset($db, $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID, $hakemusDTO->Viranomaisen_koodi, $hakemus_id);

							// Haetaan lausunnonantajat (todo: viranomaisen pääkäyttä määrittelee lausunnonantajat?)
							
							$viranomaisen_roolitDTO = $viranomaisen_rooliDAO->hae_viranomaisten_roolit_koodin_ja_roolin_perusteella($viranomaisen_rooliDTO->Viranomaisen_koodi, "rooli_lausunnonantaja");
							$lausunnonantaja_lista = array();

							for($i=0; $i < sizeof($viranomaisen_roolitDTO); $i++){
								$viranomaisen_roolitDTO[$i]->KayttajaDTO = $kayttajaDAO->hae_kayttajan_tiedot($viranomaisen_roolitDTO[$i]->KayttajaDTO->ID);
								if($viranomaisen_roolitDTO[$i]->KayttajaDTO->ID!=$kayt_id) array_push($lausunnonantaja_lista, $viranomaisen_roolitDTO[$i]);
							}
							$dto["Viranomaisen_roolitDTO"]["Lausunnonantajat"] = $lausunnonantaja_lista;

							// Haetaan pyydetyt lausunnot
							$lausuntopyynnotDTO = $lausuntopyyntoDAO->hae_hakemuksen_lausuntopyynnot($hakemusDTO->ID);

							for($i=0; $i < sizeof($lausuntopyynnotDTO); $i++){

								// Haetaan käyttäjän (pyytäjän) nimet
								$lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja = $kayttajaDAO->hae_kayttajan_tiedot($lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja->ID);
								$lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja->ID);

								// Haetaan lausunnon antajan nimi jos sellaista löytyy
								if(isset($lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja->ID) && !is_null($lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja->ID)){  
									$lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja = $kayttajaDAO->hae_kayttajan_tiedot($lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja->ID);
									$lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja->ID);
								}
								
								// Hae lausunto (vastaus) jos sellainen löytyy
								$lausuntopyynnotDTO[$i]->LausuntoDTO = $lausuntoDAO->hae_lausuntopyynnolle_julkaistu_lausunto($lausuntopyynnotDTO[$i]->ID);

								if(isset($lausuntopyynnotDTO[$i]->LausuntoDTO->ID) && !is_null($lausuntopyynnotDTO[$i]->LausuntoDTO->ID)){ 
								
									if($lausunnon_lukeneetDAO->lausunto_on_luettu($kayt_id, $lausuntopyynnotDTO[$i]->LausuntoDTO->ID)==0){ // Merkataan lausunto luetuksi (jos käyttäjä ei ole lukenut lausuntoa)
										$lausunnon_lukeneetDAO->merkkaa_lausunto_luetuksi($lausuntopyynnotDTO[$i]->LausuntoDTO->ID, $kayt_id);
									}
									
									// Haetaan lausunnon liitteet
									$lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunnon_liitteetDTO = $lausunnon_liiteDAO->hae_lausunnon_liitteet($lausuntopyynnotDTO[$i]->LausuntoDTO->ID);
									
									if(!empty($lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunnon_liitteetDTO)){
										for($j=0; $j < sizeof($lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunnon_liitteetDTO); $j++){
											$lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunnon_liitteetDTO[$j]->LiiteDTO = $liiteDAO->hae_liite($lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunnon_liitteetDTO[$j]->LiiteDTO->ID);
										}
									}
									
								}
							}
							$dto["LausuntopyynnotDTO"] = $lausuntopyynnotDTO;
							//$dto = hae_saapuneiden_lausuntojen_maara_kayttajalle($dto,$db,$kayt_id);

							$db->commit();
							$db = null;

						}

					} else {
						throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
					}	 

				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Funktio hakee tietokannasta viranomaiselle aineiston muodostamiseen liittyvät datat sivulle aineistonmuodostaja_hakemus_aineistotilaus.php
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemuksen_aineistonmuodostus($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];

			if(!is_null($hakemus_id) && !is_null($kayt_id)){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("hakemus_id"=>$hakemus_id, "kayt_id"=>$kayt_id, "kayttajan_rooli"=>"rooli_aineistonmuodostaja"))){

								$viranomaisen_rooliDTO = $dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO;

								$db->beginTransaction();

								$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
								$kayttajaDAO = new KayttajaDAO($db);
								$paatosDAO = new PaatosDAO($db);
								$aineistotilausDAO = new AineistotilausDAO($db);
								$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);
								$hakemusDAO = new HakemusDAO($db);
								$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
								$hakemusversioDAO = new HakemusversioDAO($db);
								$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);

								$paatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemus_id);
								$paatosDTO->HakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemus_id);
								$paatosDTO->HakemusDTO->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($paatosDTO->HakemusDTO->ID);
								$paatosDTO->HakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($paatosDTO->HakemusDTO->HakemusversioDTO->ID);
								$paatosDTO->HakemusDTO->HakemusversioDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($paatosDTO->HakemusDTO->HakemusversioDTO->LomakeDTO->ID);
								$paatosDTO->AineistotilausDTO = $aineistotilausDAO->hae_aineistotilaus_paatokselle($paatosDTO->ID);
								$paatosDTO->AineistotilausDTO->KayttajaDTO_Aineiston_tilaaja = $kayttajaDAO->hae_kayttajan_tiedot($paatosDTO->AineistotilausDTO->Aineiston_tilaaja);
								$paatosDTO->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja = $kayttajaDAO->hae_kayttajan_tiedot($paatosDTO->AineistotilausDTO->Aineistonmuodostaja);
								$paatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO = $aineistotilauksen_tilaDAO->hae_tilan_koodi_aineistotilauksen_avaimella($paatosDTO->AineistotilausDTO->ID);

								$dto["Tutkimuksen_viranomaisen_hakemuksetDTO"] = hae_tutkimuksen_muut_viranomaisen_hakemukset($db, $paatosDTO->HakemusDTO->HakemusversioDTO->TutkimusDTO->ID, $paatosDTO->HakemusDTO->Viranomaisen_koodi, $hakemus_id);	
								$dto["PaatosDTO"] = $paatosDTO;

								$db->commit();
								$db = null;


						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_paatos($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];
			$kayttajan_rooli = $parametrit["kayttajan_rooli"];

			if(!is_null($kayttajan_rooli) && !is_null($hakemus_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>$kayttajan_rooli, "hakemus_id"=>$hakemus_id, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$paatosDAO = new PaatosDAO($db);
							$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);
							$paatetty_aineistoDAO = new Paatetty_aineistoDAO($db);
							$paatetty_luvan_kohdeDAO = new Paatetty_luvan_kohdeDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							$lomakeDAO = new LomakeDAO($db);
							$lomake_paatosDAO = new Lomake_paatosDAO($db);
							$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);
							$osioDAO = new OsioDAO($db);
							$osio_sisaltoDAO = new Osio_sisaltoDAO($db);
							$sitoumusDAO = new SitoumusDAO($db);
							$luvan_kohdeDAO = new Luvan_kohdeDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$osio_lauseDAO = new Osio_lauseDAO($db);
							$kayttolupaDAO = new KayttolupaDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$paattajaDAO = new PaattajaDAO($db);
							$hakijaDAO = new HakijaDAO($db);
							$paatoksen_liiteDAO = new Paatoksen_liiteDAO($db);
							$liiteDAO = new LiiteDAO($db);
							$asiaDAO = new AsiaDAO($db);
							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
							$osallistuva_organisaatioDAO = new Osallistuva_organisaatioDAO($db);

							$kayt_kieli = "fi"; // Oletuskieli on suomi

							if(!is_null($dto["Istunto"]["Kayttaja"]->Kieli_koodi)) $kayt_kieli = $dto["Istunto"]["Kayttaja"]->Kieli_koodi;
							 
							$paatosDTO = $paatosDAO->hae_hakemuksen_paatos($hakemus_id);
							$paatosDTO->Paatoksen_tilaDTO = $paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($paatosDTO->ID);
							$paatosDTO->HakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($paatosDTO->HakemusDTO->ID);
							$paatosDTO->HakemusDTO->AsiaDTO = $asiaDAO->hae_asia($paatosDTO->HakemusDTO->AsiaDTO->ID);
							$paatosDTO->HakemusDTO->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($paatosDTO->HakemusDTO->ID);
							$paatosDTO->HakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($paatosDTO->HakemusDTO->HakemusversioDTO->ID);
							$paatosDTO->HakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO = $osallistuva_organisaatioDAO->hae_hakemusversion_organisaatiot($paatosDTO->HakemusDTO->HakemusversioDTO->ID);
							$paatosDTO->HakemusDTO->HakemusversioDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($paatosDTO->HakemusDTO->HakemusversioDTO->LomakeDTO->ID);
														
							// Haetaan hakemuksesta laskutustiedot
							$laskutustieto_1 = null; $laskutustieto_2 = null;
							$osio_sisaltoDTO_verkkolasku = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($paatosDTO->HakemusDTO->HakemusversioDTO->ID, "FK_Hakemusversio", 44);

							if(isset($osio_sisaltoDTO_verkkolasku->Sisalto_boolean) && $osio_sisaltoDTO_verkkolasku->Sisalto_boolean==1){

								$osio_sisaltoDTO_org_nimi = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($paatosDTO->HakemusDTO->HakemusversioDTO->ID, "FK_Hakemusversio", 54);
								$osio_sisaltoDTO_y_tunnus = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($paatosDTO->HakemusDTO->HakemusversioDTO->ID, "FK_Hakemusversio", 62);

								if(isset($osio_sisaltoDTO_org_nimi->Sisalto_text) && !is_null($osio_sisaltoDTO_org_nimi->Sisalto_text)) $laskutustieto_1 = $osio_sisaltoDTO_org_nimi->Sisalto_text;
								if(isset($osio_sisaltoDTO_y_tunnus->Sisalto_text) && !is_null($osio_sisaltoDTO_y_tunnus->Sisalto_text)) $laskutustieto_2 = $osio_sisaltoDTO_y_tunnus->Sisalto_text;

							} else {

								$osio_sisaltoDTO_paperilasku = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($paatosDTO->HakemusDTO->HakemusversioDTO->ID, "FK_Hakemusversio", 45);

								if(isset($osio_sisaltoDTO_paperilasku->Sisalto_boolean) && $osio_sisaltoDTO_paperilasku->Sisalto_boolean==1){

									$osio_sisaltoDTO_yht_henk = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($paatosDTO->HakemusDTO->HakemusversioDTO->ID, "FK_Hakemusversio", 56);
									$osio_sisaltoDTO_osoite = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($paatosDTO->HakemusDTO->HakemusversioDTO->ID, "FK_Hakemusversio", 60);

									if(isset($osio_sisaltoDTO_yht_henk->Sisalto_text) && !is_null($osio_sisaltoDTO_yht_henk->Sisalto_text)) $laskutustieto_1 = $osio_sisaltoDTO_yht_henk->Sisalto_text;
									if(isset($osio_sisaltoDTO_osoite->Sisalto_text) && !is_null($osio_sisaltoDTO_osoite->Sisalto_text)) $laskutustieto_2 = $osio_sisaltoDTO_osoite->Sisalto_text;

								}

							}

							$paatosDTO->Laskutustieto_1 = $laskutustieto_1;
							$paatosDTO->Laskutustieto_2 = $laskutustieto_2;

							$paatosDTO->Paatetyt_aineistotDTO = $paatetty_aineistoDAO->hae_paatoksen_paatetyt_aineistot($paatosDTO->ID);

							// Haetaan hakemuksen yht. henkilö ja vast. johtaja
							$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijan_rooli($paatosDTO->HakemusDTO->HakemusversioDTO->ID, "rooli_hak_yht");
							$dto["HakijaDTO_Yhteyshenkilo"] = $hakijaDAO->hae_hakijan_tiedot($hakijan_roolitDTO[0]->HakijaDTO->ID);

							$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijan_rooli($paatosDTO->HakemusDTO->HakemusversioDTO->ID, "rooli_vast");
							$dto["HakijaDTO_Vastuullinen_johtaja"] = $hakijaDAO->hae_hakijan_tiedot($hakijan_roolitDTO[0]->HakijaDTO->ID);

							for($i=0; $i < sizeof($paatosDTO->Paatetyt_aineistotDTO); $i++){
								$paatosDTO->Paatetyt_aineistotDTO[$i]->Paatetyt_luvan_kohteetDTO = $paatetty_luvan_kohdeDAO->hae_paatetyn_aineiston_paatetyt_luvan_kohteet($paatosDTO->Paatetyt_aineistotDTO[$i]->ID);
							}
							$lomakeDTO = $lomakeDAO->hae_lomake($paatosDTO->LomakeDTO->ID);

							// Lomakepohjat generoidaan eri tavalla PDF-muotoon
							if($lomakeDTO->ID==42){ // Strukturoitu
								$dto["Form_template"] = "structured";
							} else if($lomakeDTO->ID==44){ // Vapaamuotoinen
								$dto["Form_template"] = "simple";
							} else { // Kustomoitu
								$dto["Form_template"] = "custom";
							}
							
							$paatosDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($lomakeDTO->ID);

							foreach($paatosDTO->Lomakkeen_sivutDTO as $sivu => $lomakkeen_sivuDTO){
								if($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken"){
									$paatosDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_puu($lomakeDTO->ID, $sivu, $paatosDTO->ID, "FK_Paatos", true, null);
									$paatosDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_taulukko($sivu,$paatosDTO->ID, "FK_Paatos", $lomakeDTO->ID, true, null);
								} else {
									$paatosDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_puu($lomakeDTO->ID, $sivu, $paatosDTO->ID, "FK_Paatos", true, $paatosDTO->Paatoksen_tilaDTO->Lisayspvm);
									$paatosDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_taulukko($sivu,$paatosDTO->ID, "FK_Paatos", $lomakeDTO->ID, true, $paatosDTO->Paatoksen_tilaDTO->Lisayspvm);
								}
							}

	
							// Haetaan käyttäjät, jotka hakevat käyttölupaa
							$hakemusversion_hakijat = hae_hakemusversion_hakijat($db, $paatosDTO->HakemusDTO->HakemusversioDTO);
							$sitoumuksetDTO = $hakemusversion_hakijat["sitoumuksetDTO"];
																																			
							for($i=0; $i < sizeof($sitoumuksetDTO); $i++){

								$sitoumuksetDTO[$i]->KayttajaDTO = $kayttajaDAO->hae_kayttajan_tiedot($sitoumuksetDTO[$i]->KayttajaDTO->ID);

								// Haetaan hakijan käyttölupa
								$sitoumuksetDTO[$i]->KayttajaDTO->KayttolupaDTO = $kayttolupaDAO->hae_kayttajan_ja_paatoksen_kayttolupa($paatosDTO->ID, $sitoumuksetDTO[$i]->KayttajaDTO->ID);

								// Haetaan hakijan tiedot
								$sitoumuksetDTO[$i]->KayttajaDTO->HakijaDTO = $hakijaDAO->hae_hakemusversion_hakija($paatosDTO->HakemusDTO->HakemusversioDTO->ID, $sitoumuksetDTO[$i]->KayttajaDTO->ID);

							}
							
							$dto["SitoumuksetDTO"] = $sitoumuksetDTO;

							// Haetaan päätöspohjat
							$lomakkeetDTO = $lomakeDAO->hae_tyypin_lomakkeet("Päätös");
							$lomakkeetDTO_paatos = array();

							for($i=0; $i < sizeof($lomakkeetDTO); $i++){

								$lomakkeetDTO[$i]->Lomake_paatosDTO = $lomake_paatosDAO->hae_lomakkeen_lomake_paatos($lomakkeetDTO[$i]->ID);

								if($paatosDTO->HakemusDTO->HakemusversioDTO->Versio==1 && $lomakkeetDTO[$i]->Lomake_paatosDTO->Paatostyyppi=="paatos"){
									array_push($lomakkeetDTO_paatos, $lomakkeetDTO[$i]);
								} else if($paatosDTO->HakemusDTO->HakemusversioDTO->Versio > 1 && $lomakkeetDTO[$i]->Lomake_paatosDTO->Paatostyyppi=="muutospaatos"){
									array_push($lomakkeetDTO_paatos, $lomakkeetDTO[$i]);
								} else {
									if(is_null($lomakkeetDTO[$i]->Lomake_paatosDTO->Paatostyyppi)) array_push($lomakkeetDTO_paatos, $lomakkeetDTO[$i]);
								}
								
							}
							
							$dto["LomakkeetDTO_Paatos"] = $lomakkeetDTO_paatos;

							// Haetaan viranomaisen luvan kohteet
							$dto["Luvan_kohteetDTO"] = $luvan_kohdeDAO->hae_viranomaisen_luvan_kohteet($dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisen_koodi);

							// Haetaan kaikki organisaation päättäjät/puheenjohtajat
							if($dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisen_koodi=="v_VSSHP"){
								$viranomaisten_roolitDTO_paattajat = $viranomaisen_rooliDAO->hae_viranomaisten_roolit_koodin_ja_roolin_perusteella($dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisen_koodi, "rooli_eettisen_puheenjohtaja");
							} else {
								$viranomaisten_roolitDTO_paattajat = $viranomaisen_rooliDAO->hae_viranomaisten_roolit_koodin_ja_roolin_perusteella($dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO->Viranomaisen_koodi, "rooli_paattava");
							}
							
							for($i=0; $i < sizeof($viranomaisten_roolitDTO_paattajat); $i++){
								$viranomaisten_roolitDTO_paattajat[$i]->KayttajaDTO = $kayttajaDAO->hae_kayttajan_tiedot($viranomaisten_roolitDTO_paattajat[$i]->KayttajaDTO->ID);
							}
							
							$dto["Viranomaisten_roolitDTO_Paattajat"] = $viranomaisten_roolitDTO_paattajat;

							// Haetaan päätöksen päättäjät
							$paatosDTO->PaattajatDTO = $paattajaDAO->hae_paatoksen_paattajat($paatosDTO->ID);

							for($i=0; $i < sizeof($paatosDTO->PaattajatDTO); $i++){
								$paatosDTO->PaattajatDTO[$i]->KayttajaDTO = $kayttajaDAO->hae_kayttajan_tiedot($paatosDTO->PaattajatDTO[$i]->KayttajaDTO->ID);
							}
							// Haetaan päätöksen käsittelijä
							$paatosDTO->KayttajaDTO_Kasittelija = $kayttajaDAO->hae_kayttajan_tiedot($paatosDTO->Kasittelija);

							// Haetaan päätöksen liitteet
							$paatosDTO->Paatoksen_liitteetDTO = $paatoksen_liiteDAO->hae_paatoksen_liitteet($paatosDTO->ID);

							for($i=0; $i < sizeof($paatosDTO->Paatoksen_liitteetDTO); $i++){
								$paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO = $liiteDAO->hae_liite($paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->ID);
							}
							
							$dto["PaatosDTO"] = $paatosDTO;
							$dto["Tutkimuksen_viranomaisen_hakemuksetDTO"] = hae_tutkimuksen_muut_viranomaisen_hakemukset($db, $paatosDTO->HakemusDTO->HakemusversioDTO->TutkimusDTO->ID, $paatosDTO->HakemusDTO->Viranomaisen_koodi, $hakemus_id);

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Lausunnonantajalle ladataan hakemukseen liittyvät lausunnot ja lausuntopyynnöt (lausunnonantaja_hakemus_lausunto.php)
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemuksen_lausunnot_lausunnonantajalle($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];

			if(!is_null($hakemus_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						//if(kayttajaAutentikoitu($db,array("hakemus_id"=>$hakemus_id, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_lausunnonantaja", "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

								$db->beginTransaction();

								$hakemusDAO = new HakemusDAO($db);
								$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
								$hakemusversioDAO = new HakemusversioDAO($db);
								$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
								$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
								$kayttajaDAO = new KayttajaDAO($db);
								$lausuntoDAO = new LausuntoDAO($db);
								$liiteDAO = new LiiteDAO($db);
								$lausunnon_liiteDAO = new Lausunnon_liiteDAO($db);
								$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);

								$lausuntopyynnot_joihin_annettu_lausunto = array();
								$lausuntopyynnot = array();
								$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemus_id);
								$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
								$hakemusDTO->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemusDTO->ID);
								$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
								$hakemusDTO->HakemusversioDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($hakemusDTO->HakemusversioDTO->LomakeDTO->ID);
								
								$dto["HakemusDTO"] = $hakemusDTO;
								$dto["Tutkimuksen_viranomaisen_hakemuksetDTO"] = hae_tutkimuksen_muut_viranomaisen_hakemukset($db, $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID, $hakemusDTO->Viranomaisen_koodi, $hakemus_id);

								// Haetaan lausuntopyyntö / annetut lausunnot
								$lausuntopyynnotDTO = $lausuntopyyntoDAO->hae_antajalle_tutkimuksen_lausuntopyynnot($hakemusDTO->HakemusversioDTO->TutkimusDTO->ID, $kayt_id);

								for($i=0; $i < sizeof($lausuntopyynnotDTO); $i++){

									$viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja->ID);

									if($hakemusDTO->Viranomaisen_koodi==$viranomaisen_rooliDTO->Viranomaisen_koodi){

										$lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja = $kayttajaDAO->hae_kayttajan_tiedot($lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja->ID);
										$lausuntopyynnotDTO[$i]->KayttajaDTO_Pyytaja->Viranomaisen_rooliDTO = $viranomaisen_rooliDTO;

										$lausuntopyynnotDTO[$i]->LausuntoDTO = $lausuntoDAO->hae_lausuntopyynnolle_lausunto($lausuntopyynnotDTO[$i]->ID);
										
										// Haetaan lausunnon liitteet
										if(isset($lausuntopyynnotDTO[$i]->LausuntoDTO->ID)){
											
											$lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunnon_liitteetDTO = $lausunnon_liiteDAO->hae_lausunnon_liitteet($lausuntopyynnotDTO[$i]->LausuntoDTO->ID);
											
											if(!empty($lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunnon_liitteetDTO)){
												for($j=0; $j < sizeof($lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunnon_liitteetDTO); $j++){
													if(isset($lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunnon_liitteetDTO[$j]->LiiteDTO->ID)){
														$lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunnon_liitteetDTO[$j]->LiiteDTO = $liiteDAO->hae_liite($lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunnon_liitteetDTO[$j]->LiiteDTO->ID);
													}													
												}
											}
											
										}
										
										array_push($lausuntopyynnot, $lausuntopyynnotDTO[$i]);

										/*
										// Tarkistetaan onko pyyntöön annettu lausunto
										$lausuntopyynnotDTO[$i]->LausuntoDTO = $lausuntoDAO->hae_lausuntopyynnolle_julkaistu_lausunto($lausuntopyynnotDTO[$i]->ID);

										if(isset($lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunto_julkaistu) && $lausuntopyynnotDTO[$i]->LausuntoDTO->Lausunto_julkaistu==1){

											$lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja = $kayttajaDAO->hae_kayttajan_tiedot($lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja->ID);
											$lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($lausuntopyynnotDTO[$i]->KayttajaDTO_Antaja->ID);
											array_push($lausuntopyynnot_joihin_annettu_lausunto, $lausuntopyynnotDTO[$i]);

										} else {

											// Haetaan keskeneräinen lausunto mikäli sellainen löytyy
											$lausuntopyynnotDTO[$i]->LausuntoDTO = $lausuntoDAO->hae_lausuntopyynnolle_keskenerainen_lausunto($lausuntopyynnotDTO[$i]->ID);
											array_push($lausuntopyynnot, $lausuntopyynnotDTO[$i]);

										}
										*/
									}
								}
								// TODO: Hae myös muiden antamat lausunnot?

								//$dto["LausuntopyynnotDTO"]["Lausuntopyynnot_joihin_annettu_lausunto"] = $lausuntopyynnot_joihin_annettu_lausunto;
								$dto["LausuntopyynnotDTO"]["Lausuntopyynnot_joihin_ei_ole_annettu_lausuntoa"] = $lausuntopyynnot;

								$db->commit();
								$db = null;

							}
						//} else {
						//	throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						//}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_lausunto($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemus_id = $parametrit["hakemus_id"];
			$lausunto_id = $parametrit["lausunto_id"];

			if(!is_null($lausunto_id) && !is_null($hakemus_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("hakemus_id"=>$hakemus_id, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							$hakemusDAO = new HakemusDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);
							$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
							$lausuntoDAO = new LausuntoDAO($db);
							$lausunnon_liiteDAO = new Lausunnon_liiteDAO($db);
							$liiteDAO = new LiiteDAO($db);
							$lomakeDAO = new LomakeDAO($db);
							$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);
							$osioDAO = new OsioDAO($db);
							$kayttajaDAO = new KayttajaDAO($db);
							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$asiaDAO = new AsiaDAO($db);
							$osallistuva_organisaatioDAO = new Osallistuva_organisaatioDAO($db);

							$lausuntopyynnot_joihin_annettu_lausunto = array();
							$lausuntopyynnot = array();

							$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($hakemus_id);
							$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
							$hakemusDTO->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemusDTO->ID);
							$hakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
							$hakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO = $osallistuva_organisaatioDAO->hae_hakemusversion_organisaatiot($hakemusDTO->HakemusversioDTO->ID);
							$hakemusDTO->HakemusversioDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($hakemusDTO->HakemusversioDTO->LomakeDTO->ID);
							$hakemusDTO->AsiaDTO = $asiaDAO->hae_asia($hakemusDTO->AsiaDTO->ID);
							
							$lausuntoDTO = $lausuntoDAO->hae_lausunto($lausunto_id);
							$lausuntoDTO->LausuntopyyntoDTO = $lausuntopyyntoDAO->hae_lausuntopyynnon_tiedot($lausuntoDTO->LausuntopyyntoDTO->ID);
							$lausuntoDTO->LausuntopyyntoDTO->KayttajaDTO_Antaja = $kayttajaDAO->hae_kayttajan_tiedot($lausuntoDTO->LausuntopyyntoDTO->KayttajaDTO_Antaja->ID);
							$lausuntoDTO->LausuntopyyntoDTO->KayttajaDTO_Antaja->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($lausuntoDTO->LausuntopyyntoDTO->KayttajaDTO_Antaja->ID);
							$lausuntoDTO->LausuntopyyntoDTO->KayttajaDTO_Pyytaja = $kayttajaDAO->hae_kayttajan_tiedot($lausuntoDTO->LausuntopyyntoDTO->KayttajaDTO_Pyytaja->ID);

							$lausuntoDTO->LomakeDTO = $lomakeDAO->hae_lomake($lausuntoDTO->LomakeDTO->ID);
							$lausuntoDTO->Lomakkeen_sivutDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivut($lausuntoDTO->LomakeDTO->ID);

							foreach($lausuntoDTO->Lomakkeen_sivutDTO as $sivu => $lomakkeen_sivuDTO){

								if($lausuntoDTO->Lausunto_julkaistu==1){
									$lausuntoDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_puu($lausuntoDTO->LomakeDTO->ID, $sivu, $lausuntoDTO->ID, "FK_Lausunto", true, $lausuntoDTO->Muokkauspvm);
									$lausuntoDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu =  $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_taulukko($sivu, $lausuntoDTO->ID, "FK_Lausunto", $lausuntoDTO->LomakeDTO->ID, true, $lausuntoDTO->Muokkauspvm);
								} else {
									$lausuntoDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu = $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_puu($lausuntoDTO->LomakeDTO->ID, $sivu, $lausuntoDTO->ID, "FK_Lausunto", true, null);
									$lausuntoDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu =  $osioDAO->hae_lomakkeen_sivun_osiot_ja_sisallot_taulukko($sivu, $lausuntoDTO->ID, "FK_Lausunto", $lausuntoDTO->LomakeDTO->ID, true, null);
								}

							}
							
							$lausuntoDTO->Lausunnon_liitteetDTO = $lausunnon_liiteDAO->hae_lausunnon_liitteet($lausuntoDTO->ID);

							for($i=0; $i < sizeof($lausuntoDTO->Lausunnon_liitteetDTO); $i++){
								$lausuntoDTO->Lausunnon_liitteetDTO[$i]->LiiteDTO = $liiteDAO->hae_liite($lausuntoDTO->Lausunnon_liitteetDTO[$i]->LiiteDTO->ID);
							}
							$dto["HakemusDTO"] = $hakemusDTO;
							$dto["LausuntoDTO"] = $lausuntoDTO;
							$dto["Tutkimuksen_viranomaisen_hakemuksetDTO"] = hae_tutkimuksen_muut_viranomaisen_hakemukset($db, $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID, $hakemusDTO->Viranomaisen_koodi, $hakemus_id);

							// Haetaan lomakepohjat
							$dto["LomakkeetDTO_Lausunto"] = $lomakeDAO->hae_tyypin_lomakkeet("Lausunto");

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Haetaan kaikki käyttäjälle saapuneet viestit (sivu viranomainen_saapuneet_viestit.php ja viestit.php)
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_saapuneet_viestit($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){

							$db->beginTransaction();

							if(isset($parametrit["roolin_koodi"])){

								$viranomaisroolin_koodi = $parametrit["roolin_koodi"];

								if($viranomaisroolin_koodi=="rooli_kasitteleva" || $viranomaisroolin_koodi=="rooli_paattava" || $viranomaisroolin_koodi=="rooli_lausunnonantaja" || $viranomaisroolin_koodi=="rooli_aineistonmuodostaja"){

									$viranomaisen_rooliDTO = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, $viranomaisroolin_koodi);

									if(!isset($viranomaisen_rooliDTO->ID)){
										throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
									}
								}
							}
							
							vapauta_kayttajan_lukitsemat_hakemusversiot($db, $kayt_id);

							$kayttajaDTO = new KayttajaDTO();
							$kayttajaDTO->ID = $kayt_id;

							$kayttajaDAO = new KayttajaDAO($db);
							$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
							$viestitDAO = new ViestitDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$hakemusversioDAO = new HakemusversioDAO($db);

							// Haetaan uudet saapuneet viestit
							$lukemattomat_viestitDTO = $viestitDAO->hae_lukemattomat_viestit_kayttajalle($kayt_id);
							$roolin_lukemattomat_viestitDTO = array();

							for($i=0; $i < sizeof($lukemattomat_viestitDTO); $i++){

								// Haetaan diiarinro ja hakemusversio id
								$lukemattomat_viestitDTO[$i]->HakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($lukemattomat_viestitDTO[$i]->HakemusDTO->ID);

								// Filtteröidään viestit roolin perusteella. Esim. jos hakijalle ei näytetä viranomaisroolin viestejä
								if(!isset($viranomaisroolin_koodi)){ // hakija
									if(!kayttaja_on_hakemuksen_hakija($db, $lukemattomat_viestitDTO[$i]->HakemusDTO->HakemusversioDTO->ID, $kayt_id)){
										continue;
									}
								}
								
								if(isset($viranomaisroolin_koodi)){

									if($viranomaisroolin_koodi=="rooli_kasitteleva" || $viranomaisroolin_koodi=="rooli_paattava"){
										if(!kayttaja_on_tutkimuksen_viranomainen($db, $lukemattomat_viestitDTO[$i]->HakemusDTO->ID, $kayt_id)){
											continue;
										}
									}
									if($viranomaisroolin_koodi=="rooli_lausunnonantaja"){
										if(!kayttaja_on_tutkimuksen_lausunnonantaja($db, $lukemattomat_viestitDTO[$i]->HakemusDTO->ID, $kayt_id)){
											continue;
										}
									}
									if($viranomaisroolin_koodi=="rooli_aineistonmuodostaja"){
										if(!kayttaja_on_tutkimuksen_aineistonmuodostaja($db, $lukemattomat_viestitDTO[$i]->HakemusDTO->ID, $kayt_id)){
											continue;
										}
									}
								}
								
								if(isset($viranomaisen_rooliDTO)){
									if($lukemattomat_viestitDTO[$i]->HakemusDTO->Viranomaisen_koodi != $viranomaisen_rooliDTO->Viranomaisen_koodi){
										$lukemattomat_viestitDTO[$i]->HakemusDTO = $hakemusDAO->hae_hakemusversion_uusin_hakemus_viranomaiselle($lukemattomat_viestitDTO[$i]->HakemusDTO->HakemusversioDTO->ID, $viranomaisen_rooliDTO->Viranomaisen_koodi);
									}
								}
								// Haetaan tutkimuksen nimi
								$lukemattomat_viestitDTO[$i]->HakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($lukemattomat_viestitDTO[$i]->HakemusDTO->HakemusversioDTO->ID);

								// Haetaan lähettäjän nimi
								$lukemattomat_viestitDTO[$i]->KayttajaDTO_Lahettaja = $kayttajaDAO->hae_kayttajan_tiedot($lukemattomat_viestitDTO[$i]->KayttajaDTO_Lahettaja->ID);

								// Haetaan viranomaisen koodi
								$lukemattomat_viestitDTO[$i]->KayttajaDTO_Lahettaja->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($lukemattomat_viestitDTO[$i]->KayttajaDTO_Lahettaja->ID);

								array_push($roolin_lukemattomat_viestitDTO, $lukemattomat_viestitDTO[$i]);

							}
							$dto["ViestitDTO"]["Lukemattomat"] = $roolin_lukemattomat_viestitDTO;

							// Haetaan vanhat saapuneet viestit 
							$luetut_viestitDTO = $viestitDAO->hae_luetut_viestit_kayttajalle($kayt_id);
							$roolin_luetut_viestitDTO = array();

							for($i=0; $i < sizeof($luetut_viestitDTO); $i++){

								// Haetaan diiarinro ja hakemusversio id
								$luetut_viestitDTO[$i]->HakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($luetut_viestitDTO[$i]->HakemusDTO->ID);

								if(!isset($viranomaisroolin_koodi)){ // hakija
									if(!kayttaja_on_hakemuksen_hakija($db, $luetut_viestitDTO[$i]->HakemusDTO->HakemusversioDTO->ID, $kayt_id)){
										continue;
									}
								}

								if(isset($viranomaisroolin_koodi)){
									if($viranomaisroolin_koodi=="rooli_kasitteleva" || $viranomaisroolin_koodi=="rooli_paattava"){
										if(!kayttaja_on_tutkimuksen_viranomainen($db, $luetut_viestitDTO[$i]->HakemusDTO->ID, $kayt_id)){
											continue;
										}
									}
									if($viranomaisroolin_koodi=="rooli_lausunnonantaja"){
										if(!kayttaja_on_tutkimuksen_lausunnonantaja($db, $luetut_viestitDTO[$i]->HakemusDTO->ID, $kayt_id)){
											continue;
										}
									}
									if($viranomaisroolin_koodi=="rooli_aineistonmuodostaja"){
										if(!kayttaja_on_tutkimuksen_aineistonmuodostaja($db, $luetut_viestitDTO[$i]->HakemusDTO->ID, $kayt_id)){
											continue;
										}
									}
								}
								if(isset($viranomaisen_rooliDTO)){
									if($luetut_viestitDTO[$i]->HakemusDTO->Viranomaisen_koodi != $viranomaisen_rooliDTO->Viranomaisen_koodi){
										$luetut_viestitDTO[$i]->HakemusDTO = $hakemusDAO->hae_hakemusversion_uusin_hakemus_viranomaiselle($luetut_viestitDTO[$i]->HakemusDTO->HakemusversioDTO->ID, $viranomaisen_rooliDTO->Viranomaisen_koodi);
									}
								}
								// Haetaan tutkimuksen nimi
								$luetut_viestitDTO[$i]->HakemusDTO->HakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($luetut_viestitDTO[$i]->HakemusDTO->HakemusversioDTO->ID);

								// Haetaan lähettäjän nimi
								$luetut_viestitDTO[$i]->KayttajaDTO_Lahettaja = $kayttajaDAO->hae_kayttajan_tiedot($luetut_viestitDTO[$i]->KayttajaDTO_Lahettaja->ID);

								// Haetaan viranomaisen koodi
								$luetut_viestitDTO[$i]->KayttajaDTO_Lahettaja->Viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli($luetut_viestitDTO[$i]->KayttajaDTO_Lahettaja->ID);

								array_push($roolin_luetut_viestitDTO, $luetut_viestitDTO[$i]);

							}
							$dto["ViestitDTO"]["Luetut"] = $roolin_luetut_viestitDTO;

							//$dto = hae_lukemattomien_viestien_maara_kayttajan_roolille($dto,$db,$kayt_id,$roolin_koodi);
							//$dto = hae_eraantyvien_kayttolupien_maara_kayttajalle($dto,$db,$kayt_id);

							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function avaa_liitetiedosto($syoteparametrit) {

		$dto = array();
		$dto["Avattu_liiteDTO"] = null;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$avattava_liite_id = $parametrit["avattava_liite_id"];
			$kayttaja_autentikoitu = false;

			if(!is_null($avattava_liite_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						
						$db->beginTransaction();
																		
						$hakemusversion_liiteDAO = new Hakemusversion_liiteDAO($db);												
						$hakemusversion_liiteDTO = $hakemusversion_liiteDAO->hae_liite($avattava_liite_id);

						if(isset($hakemusversion_liiteDTO->HakemusversioDTO->ID) && !is_null($hakemusversion_liiteDTO->HakemusversioDTO->ID)){
							if(kayttajaAutentikoitu($db,array("hakemusversio_id"=>$hakemusversion_liiteDTO->HakemusversioDTO->ID,"kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))) $kayttaja_autentikoitu = true;
						} else {
							
							$paatoksen_liiteDAO = new Paatoksen_liiteDAO($db);
							$paatoksen_liiteDTO = $paatoksen_liiteDAO->hae_liite($avattava_liite_id);
							
							if(isset($paatoksen_liiteDTO->PaatosDTO->ID) && !is_null($paatoksen_liiteDTO->PaatosDTO->ID)){
								
								$paatosDAO = new PaatosDAO($db);
								// todo 24.1: tutkijan auth
								
								$fk_hakemus = $paatosDAO->hae_paatoksen_tiedot($paatoksen_liiteDTO->PaatosDTO->ID)->HakemusDTO->ID;
								
								if(kayttajaAutentikoitu($db,array("hakemus_id"=>$fk_hakemus,"kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))) $kayttaja_autentikoitu = true;								
							
							} else {
								
								$lausunnon_liiteDAO = new Lausunnon_liiteDAO($db);
								$lausunnon_liiteDTO = $lausunnon_liiteDAO->hae_liite($avattava_liite_id);
								
								if(isset($lausunnon_liiteDTO->LausuntoDTO->ID) && !is_null($lausunnon_liiteDTO->LausuntoDTO->ID)){
									
									$lausuntoDAO = new LausuntoDAO($db);
									$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
									
									if(kayttajaAutentikoitu($db,array("hakemus_id"=>$lausuntopyyntoDAO->hae_lausuntopyynnon_tiedot($lausuntoDAO->hae_lausunto($lausunnon_liiteDTO->LausuntoDTO->ID)->LausuntopyyntoDTO->ID)->HakemusDTO->ID,"kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))) $kayttaja_autentikoitu = true;
									
								}
								
							}
							
						}
						
						if($kayttaja_autentikoitu){
							
							$liiteDAO = new LiiteDAO($db);
							$liiteDTO = $liiteDAO->hae_liite($avattava_liite_id);						
							$dto["Avattu_liiteDTO"] = $liiteDTO;
							
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Käyttäjän autentikointi epäonnistui");
						}
																								
						$db->commit();
						$db = null;						
						
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}

		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_hakemus($syoteparametrit) {

		$dto = array();
		$dto["Hakemus_poistettu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemusversio_id = $parametrit["hakemusversio_id"];

			if(!is_null($hakemusversio_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"], "hakemusversio_id"=>$hakemusversio_id))){

							$db->beginTransaction();

							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
							$hakijaDAO = new HakijaDAO($db);

							// Vain hakemuksen lisääjä, vast. johtaja ja yht. henkilö saavat poistaa hakemuksen
							$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakijan_roolit_jotka_saavat_poistaa_hakemusversion($hakemusversio_id, $kayt_id);
							$hakijatDTO = $hakijaDAO->hae_kayttajan_hakijat($kayt_id);
							$oikeusPoistaaHakemus = false;

							for($i=0;$i < sizeof($hakijan_roolitDTO); $i++){
								for($j=0; $j < sizeof($hakijatDTO); $j++){
									if($hakijan_roolitDTO[$i]->HakijaDTO->ID == $hakijatDTO[$j]->ID){
										$oikeusPoistaaHakemus = true;
										break 2;
									}
								}
							}

							if($oikeusPoistaaHakemus){

								$haettu_aineistoDAO = new Haettu_aineistoDAO($db);
								$hakemusDAO = new HakemusDAO($db);
								$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
								//$liitteetDAO = new LiitteetDAO($db);
								$hakemusversioDAO = new HakemusversioDAO($db);
								$tutkimusDAO = new TutkimusDAO($db);

								// Poistetaan haetut aineistot, lupien kohteet ja muuttujat
								//$haetut_aineistotDTO = $haettu_aineistoDAO->hae_hakemusversion_haetut_aineistot($hakemusversio_id);


								// Poista tiedot Haettu_aineisto-taulusta
								//$haettu_aineistoDAO->poista_hakemusversion_haetut_aineistot($hakemusversio_id);

								// Poista tiedot Hakijan_rooli-taulusta ja sen jälkeen emotaulusta Hakija
								//$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijan_roolit($hakemusversio_id);

								//for($i=0; $i < sizeof($hakijan_roolitDTO); $i++){
								//	$hakijan_rooliDAO->poista_hakijan_hakijan_rooli($hakijan_roolitDTO[$i]->HakijaDTO->ID);
								//	$hakijaDAO->poista_hakija($hakijan_roolitDTO[$i]->HakijaDTO->ID);
								//}
								// Poistetaan hakemuksen_tila ja hakemus
								//$hakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset($hakemusversio_id);

								//for($i=0; $i < sizeof($hakemuksetDTO); $i++){
								//	$hakemuksen_tilaDAO->poista_hakemuksen_hakemuksen_tila($hakemuksetDTO[$i]->ID);
								//}
								//$hakemusDAO->poista_hakemusversion_hakemus($hakemusversio_id);

								// Poista tiedot Liitteet-taulusta
								//$liitteetDAO->poista_hakemusversion_liitteet($hakemusversio_id);

								// Poista tiedot Hakemusversio-taulusta, otetaan ensin talteen FK_Tutkimus ja Versio
								$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusversio_id);

								$hakemusversioDAO->merkitse_hakemusversio_poistetuksi($hakemusversio_id, $kayt_id);
								//$hakemusversioDAO->poista_hakemusversio($hakemusversio_id);

								// Poista tiedot Tutkimus-taulusta jos versio on 1
								if ($hakemusversioDTO->Versio == 1) {

									$tutkimusDAO->merkitse_tutkimus_poistetuksi($hakemusversioDTO->TutkimusDTO->ID, $kayt_id);

									// Poista ensin tutkimukselle annetut sitoumukset
									//$sitoumusDAO = new SitoumusDAO($db);
									//$sitoumusDAO->poista_tutkimuksen_sitoumukset($hakemusversioDTO->TutkimusDTO->ID);
									//$tutkimusDAO->poista_tutkimus($hakemusversioDTO->TutkimusDTO->ID);

								}
							} else {
								throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Auth error.");
							}

							if($db->commit()){
								$dto["Hakemus_poistettu"] = true;
							}

							$db = null;

						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_hakija_tutkimusryhmasta($syoteparametrit) {

		$dto = array();
		$dto["Hakija_poistettu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemusversio_id = $parametrit["hakemusversio_id"];
			$poistettavan_kayttaja_id = $parametrit["poistettavan_kayttaja_id"];

			if(!is_null($poistettavan_kayttaja_id) && !is_null($hakemusversio_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"], "hakemusversio_id"=>$hakemusversio_id))){

							$db->beginTransaction();

							$hakijaDAO = new HakijaDAO($db);
							$hakijan_rooliDAO = new Hakijan_rooliDAO($db);

							// todo: Lisää alkuun toiminto: tarkista saako käyttäjä poistaa hakijan

							// Poista tiedot Hakijan_rooli-taulusta ja sen jälkeen emotaulusta Hakija
							$hakijaDTO = $hakijaDAO->hae_hakemusversion_hakija($hakemusversio_id, $poistettavan_kayttaja_id);
							$hakijan_rooliDAO->poista_hakijan_hakijan_rooli($hakijaDTO->ID);
							$hakijaDAO->poista_hakija($poistettavan_kayttaja_id);

							if($db->commit()){
								$dto["Hakija_poistettu"] = true;
							}

							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}
	/**
	 * @WebMethod
	 * @desc Liitetiedosto poistetaan
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_hakemusversion_liitetiedosto($syoteparametrit) {

		$dto = array();
		$dto["Liitetiedosto_poistettu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemusversio_id = $parametrit["hakemusversio_id"];
			$poistettava_liite = $parametrit["poistettava_liite"];
			$liite_id = $parametrit["liite_id"];

			if(!is_null($liite_id) && !is_null($hakemusversio_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"], "hakemusversio_id"=>$hakemusversio_id))){

							$db->beginTransaction();

							$liiteDAO = new LiiteDAO($db);
							$hakemusversion_liiteDAO = new Hakemusversion_liiteDAO($db);
							$hakemusversion_tilaDAO = new Hakemusversion_tilaDAO($db);
							
							if($hakemusversion_tilaDAO->hae_hakemusversion_uusin_tila($hakemusversio_id)->Hakemusversion_tilan_koodi=="hv_kesken"){ // Hakemus on luonnnos

								$hakemusversion_liiteDTO = $hakemusversion_liiteDAO->hae_liite($liite_id);
								
								if(isset($hakemusversion_liiteDTO->ID) && !is_null($hakemusversion_liiteDTO->ID)){
								
									$hakemusversion_liiteDAO->merkitse_hakemusversion_liite_poistetuksi($liite_id, $hakemusversio_id, $kayt_id);
									$liiteDAO->merkitse_liite_poistetuksi($liite_id, $kayt_id);

									if($db->commit()) $dto["Liitetiedosto_poistettu"] = true;																			
																		
								} else {
									throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
								}	
							}
						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
					}
					
					$db = null;
					
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}
	
	/**
	 * @WebMethod
	 * @desc Päätöksen liitetiedosto poistetaan 
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_paatoksen_liitetiedosto($syoteparametrit) {

		$dto = array();
		$dto["Liitetiedosto_poistettu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$paatos_id = $parametrit["paatos_id"];
			$liite_id = $parametrit["liite_id"];

			if(!is_null($liite_id) && !is_null($paatos_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){ 
							if(kayttaja_on_paatoksen_valmistelija_tai_paattaja($db, $kayt_id, $paatos_id)){

								$db->beginTransaction();

								$liiteDAO = new LiiteDAO($db);
								$paatoksen_liiteDAO = new Paatoksen_liiteDAO($db);
								$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);

								if($paatoksen_tilaDAO->hae_paatoksen_uusin_paatoksen_tila($paatos_id)->Paatoksen_tilan_koodi=="paat_tila_kesken"){
									$paatoksen_liiteDAO->merkitse_paatoksen_liite_poistetuksi($liite_id, $paatos_id, $kayt_id);
									if($liiteDAO->merkitse_liite_poistetuksi($liite_id, $kayt_id)) $dto["Liitetiedosto_poistettu"] = true; 
								}
								$db->commit(); 

								$db = null;

							}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Lausunnon liitetiedosto poistetaan 
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_lausunnon_liitetiedosto($syoteparametrit) {

		$dto = array();
		$dto["Liitetiedosto_poistettu"] = false;

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$lausunto_id = $parametrit["lausunto_id"];
			$liite_id = $parametrit["liite_id"];

			if(!is_null($liite_id) && !is_null($lausunto_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						
						$db->beginTransaction();
						
						$lausuntoDAO = new LausuntoDAO($db);
						$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
								
						$lausuntopyyntoDTO = $lausuntopyyntoDAO->hae_lausuntopyynnon_tiedot($lausuntoDAO->hae_lausunto($lausunto_id)->LausuntopyyntoDTO->ID);  
								
						if(kayttajaAutentikoitu($db,array("kayttajan_rooli"=>"rooli_lausunnonantaja", "hakemus_id"=>$lausuntopyyntoDTO->HakemusDTO->ID, "kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){ 
							if($lausuntopyyntoDTO->KayttajaDTO_Antaja->ID==$kayt_id){ // Poistaja on lausunnonantaja	
								
								$liiteDAO = new LiiteDAO($db);
								$lausunnon_liiteDAO = new Lausunnon_liiteDAO($db);

								$lausunnon_liiteDTO = $lausunnon_liiteDAO->hae_lausunnon_liite($lausunto_id);
								
								if(isset($lausunnon_liiteDTO->ID) && !is_null($lausunnon_liiteDTO->ID)){
									$lausunnon_liiteDAO->merkitse_lausunnon_liite_poistetuksi($liite_id, $lausunto_id, $kayt_id);
									if($liiteDAO->merkitse_liite_poistetuksi($liite_id, $kayt_id)) $dto["Liitetiedosto_poistettu"] = true; 
								} else {
									throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
								}
								
							} else {
								throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
							}
						} else {
							throw new SoapFault(ERR_AUTH_FAIL, "Autentikointi epäonnistui.");
						}
						
						$db->commit(); 
						$db = null;						
						
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_osion_sisalto($syoteparametrit) {

		$dto = array();
		$dto["Osio_sisalto_poistettu"] = false;

		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$fk_osio_sisalto = $parametrit["fk_osio_sisalto"];
			$kayttaja_autentikoitu = false;

			if(!is_null($kayt_id) && !is_null($fk_osio_sisalto) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {

						$osio_sisaltoDAO = new Osio_sisaltoDAO($db);
						$osio_sisaltoDTO = $osio_sisaltoDAO->hae_osio_sisalto($fk_osio_sisalto);

						if(isset($osio_sisaltoDTO->HakemusversioDTO->ID) && is_numeric($osio_sisaltoDTO->HakemusversioDTO->ID)){ 
							
							if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"], "hakemusversio_id"=>$osio_sisaltoDTO->HakemusversioDTO->ID))) $kayttaja_autentikoitu = true;
						
						} else {
							if(isset($osio_sisaltoDTO->Haettu_aineistoDTO->ID) && is_numeric($osio_sisaltoDTO->Haettu_aineistoDTO->ID)){								
								
								$haettu_aineistoDAO = new Haettu_aineistoDAO($db);
								if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"], "hakemusversio_id"=>$haettu_aineistoDAO->hae_haettu_aineisto($osio_sisaltoDTO->Haettu_aineistoDTO->ID)->HakemusversioDTO->ID))) $kayttaja_autentikoitu = true;								
							
							} else {
								if(isset($osio_sisaltoDTO->PaatosDTO->ID) && is_numeric($osio_sisaltoDTO->PaatosDTO->ID)){									
									
									$paatosDAO = new PaatosDAO($db);
									if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"], "hakemus_id"=>$paatosDAO->hae_paatoksen_tiedot($id)->HakemusDTO->ID))) $kayttaja_autentikoitu = true;																		
								
								} else {
									if(isset($osio_sisaltoDTO->LausuntoDTO->ID) && is_numeric($osio_sisaltoDTO->LausuntoDTO->ID)){
										
										$lausuntoDAO = new LausuntoDAO($db);
										$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);	
										
										if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"], "hakemus_id"=>$lausuntopyyntoDAO->hae_lausuntopyynnon_tiedot($lausuntoDAO->hae_lausunto($osio_sisaltoDTO->LausuntoDTO->ID)->LausuntopyyntoDTO->ID)->HakemusDTO->ID ))) $kayttaja_autentikoitu = true;
									
									}	
								}								
							}
						}
						
						if($kayttaja_autentikoitu){

							$db->beginTransaction();

							$osio_sisaltoDAO->merkitse_osio_sisalto_poistetuksi($fk_osio_sisalto,$kayt_id);

							if($db->commit()) $dto["Osio_sisalto_poistettu"] = true;
															
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Käyttäjän autentikointi epäonnistui.");
						}
						
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc Metodilla tutkija (ID: kayt_id) peruuttaa hakemuksen  (ID: hakemusversio_id) lähetyksen
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function peruuta_hakemus($syoteparametrit) {

		$dto = array();

		// check input: "syoteparametrit" is set and is array with more than one element
		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$hakemusversio_id = $parametrit["hakemusversio_id"];

			if(!is_null($hakemusversio_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if(kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"], "hakemusversio_id"=>$hakemusversio_id))){

							$db->beginTransaction();

							$hakemusversioDAO = new HakemusversioDAO($db);
							$hakemusversion_tilaDAO = new Hakemusversion_tilaDAO($db);
							$hakemusDAO = new HakemusDAO($db);
							$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);

							$haettu_hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusversio_id);

							$tutkimuksen_hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_poistamattomat_hakemusversiot($haettu_hakemusversioDTO->TutkimusDTO->ID);
							$hakemusversiotDTO = array();

							foreach($tutkimuksen_hakemusversiotDTO as $fk_hakemusversio => $hakemusversioDTO) {
								if($hakemusversion_tilaDAO->hae_hakemusversion_uusin_tila($fk_hakemusversio)->Hakemusversion_tilan_koodi=="hv_lah") array_push($hakemusversiotDTO,$hakemusversioDTO);
							}

							for($i=0; $i < sizeof($hakemusversiotDTO); $i++){

								// Haetaan hakemukset
								$hakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset($hakemusversiotDTO[$i]->ID);

								for($j=0; $j < sizeof($hakemuksetDTO); $j++){

									// Tarkistetaan onko hakemuksen tila lähetetty
									$hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemuksetDTO[$j]->ID);

									if($hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas" || $hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_lah" || $hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_muuta"){

										// Muutetaan hakemuksen tilaa
										$hakemuksen_tilaDAO->maarita_hakemuksen_tiloista_tamanhetkiset_pois($hakemuksetDTO[$j]->ID);
										$hakemuksen_tilaDAO->luo_hakemuksen_tila($hakemuksetDTO[$j]->ID, $kayt_id, "hak_peruttu");
										$hakemusversion_tilaDAO->luo_hakemusversion_tila($hakemusversiotDTO[$i]->ID, "hv_peruttu", $kayt_id);
										//$hakemusversioDAO->peru_hakemusversio($hakemusversiotDTO[$i]->ID);

										$dto["Peruttu_info"]["Hakemusversio"][$i]["Hakemus"][$j] = "Hakemus " . $hakemuksetDTO[$j]->Hakemuksen_tunnus . " on peruttu.";

									} else if($hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat"){
										$dto["Peruttu_info"]["Hakemusversio"][$i]["Hakemus"][$j] = "Hakemukselle " . $hakemuksetDTO[$j]->Hakemuksen_tunnus . " on tehty jo päätös viranomaisen toimesta.";
									} else if($hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_peruttu"){
										$dto["Peruttu_info"]["Hakemusversio"][$i]["Hakemus"][$j] = "Hakemus " . $hakemuksetDTO[$j]->Hakemuksen_tunnus . " on peruttu jo aiemmin.";
									}  else if($hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_korvattu"){
										$dto["Peruttu_info"]["Hakemusversio"][$i]["Hakemus"][$j] = "Korvattua hakemusta " . $hakemuksetDTO[$j]->Hakemuksen_tunnus . " ei voi perua.";
									} else {
										$dto["Peruttu_info"]["Hakemusversio"][$i]["Hakemus"][$j] = "Hakemuksen " . $hakemuksetDTO[$j]->Hakemuksen_tunnus . " peruminen epäonnistui.";
									}
								}
							}
							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_osio($syoteparametrit) {

		$dto = array();

		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$data = $parametrit["data"];
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($data) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							
							if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){
								$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;
							} else if($viranomaisen_rooliDTO_autentikoitu_kayttaja = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, "rooli_viranomaisen_paak")) {
								$dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO = $viranomaisen_rooliDTO_autentikoitu_kayttaja;
							} else {
								throw new SoapFault(ERR_AUTH_FAIL, "Ei käyttöoikeutta.");
							}							
							
							$db->beginTransaction();

							$osioDAO = new OsioDAO($db);

							if(isset($data["poista_osio"]["Osio"])){
								foreach($data["poista_osio"]["Osio"] as $fk_osio => $osio) { 

									// Haetaan poistettavan osion tiedot
									$osioDTO_poistettava = $osioDAO->hae_osio($fk_osio);									
									$osioDAO->merkitse_osio_poistetuksi($osioDTO_poistettava->ID, $kayt_id);

								}
							}
							
							$db->commit();
							$db = null;

						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_liitetyyppi($syoteparametrit) {

		$dto = array();

		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$data = $parametrit["data"];
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($data) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){

								$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;

								$db->beginTransaction();

								if(isset($data["poista_liitetyyppi"]) && !empty($data["poista_liitetyyppi"])){
									foreach($data["poista_liitetyyppi"] as $fk_asiakirjahallinta_liite => $al) {

										$asiakirjahallinta_liiteDAO = new Asiakirjahallinta_liiteDAO($db);
										$asiakirjahallinta_saantoDAO = new Asiakirjahallinta_saantoDAO($db);

										$asiakirjahallinta_liiteDTO = $asiakirjahallinta_liiteDAO->hae_liite_asiakirjahallinnan_tiedot($fk_asiakirjahallinta_liite);

										$asiakirjahallinta_liiteDAO->merkitse_asiakirja_liite_poistetuksi($fk_asiakirjahallinta_liite, $kayt_id);

										$asiakirjahallinta_saannotDTO = $asiakirjahallinta_saantoDAO->hae_asiakirjan_saannot($fk_asiakirjahallinta_liite);

										if(!empty($asiakirjahallinta_saannotDTO)){
											for($i=0; $i < sizeof($asiakirjahallinta_saannotDTO); $i++){
												$asiakirjahallinta_saantoDAO->poista_asiakirjan_saanto($asiakirjahallinta_saannotDTO[$i]->ID, $kayt_id);
											}
										}
									}
								} else {
									throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
								}

								$db->commit();
								$db = null;

							}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_lomakkeen_sivu($syoteparametrit) {

		$dto = array();

		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$data = $parametrit["data"];
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($data) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){

								$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;

								$db->beginTransaction();

								$lomakkeen_sivutDAO = new Lomakkeen_sivutDAO($db);

								if(isset($data["poista_lomake_sivu"])){
									foreach($data["poista_lomake_sivu"] as $fk_lomakkeen_sivu => $lomake_sivu) { 

										$lomakkeen_sivuDTO = $lomakkeen_sivutDAO->hae_lomakkeen_sivu($fk_lomakkeen_sivu);										
										$lomakkeen_sivutDAO->merkitse_lomakkeen_sivu_poistetuksi($fk_lomakkeen_sivu, $kayt_id);

									}
								}
								$db->commit();
								$db = null;

							}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_lomake($syoteparametrit) {

		$dto = array();

		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$lomake_id = $parametrit["lomake_id"];
			$kayt_id = $parametrit["kayt_id"];

			if(!is_null($lomake_id) && !is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							
								if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){
									$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;
								} else if($viranomaisen_rooliDTO_autentikoitu_kayttaja = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, "rooli_viranomaisen_paak")) {
									$dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO = $viranomaisen_rooliDTO_autentikoitu_kayttaja;
								} else {
									throw new SoapFault(ERR_AUTH_FAIL, "Ei käyttöoikeutta.");
								}						

								$db->beginTransaction();

								$lomakeDAO = new LomakeDAO($db);
								$lomake_hakemusDAO = new Lomake_hakemusDAO($db);

								$lomakeDTO = $lomakeDAO->hae_lomake($lomake_id);
								
								if(isset($dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO->ID) || (isset($lomakeDTO->Lisaaja) && $lomakeDTO->Lisaaja==$kayt_id)){
									$lomake_hakemusDAO->merkitse_lomake_hakemus_poistetuksi($lomake_hakemusDAO->hae_lomakkeen_lomake_hakemus($lomake_id)->ID, $kayt_id);
									$lomakeDAO->merkitse_lomake_poistetuksi($lomake_id, $kayt_id);
								}
								
								$db->commit();
								$db = null;

							
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		
		return muodosta_dto($dto);

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_lomakkeen_saanto($syoteparametrit) {

		$dto = array();

		if (!$syoteparametrit || is_array($syoteparametrit)) {

			$parametrit = parametrit_taulukkomuotoon($syoteparametrit);
			$kayt_id = $parametrit["kayt_id"];
			$data = $parametrit["data"];

			if(!is_null($kayt_id) && !is_null($parametrit["token"])){
				try {
					if ($db = $this->_connectToDb()) {
						if($dto["Istunto"]["Kayttaja"] = kayttajaAutentikoitu($db,array("kayt_id"=>$kayt_id, "token"=>$parametrit["token"]))){
							//if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){

								if($paakayttajan_rooliDTO_autentikoitu_kayttaja = tarkista_lupapalvelun_paakayttajan_rooli($db, $kayt_id)){
									$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;
								} else if($viranomaisen_rooliDTO_autentikoitu_kayttaja = tarkista_kayttajan_viranomaisen_rooli($db, $kayt_id, "rooli_viranomaisen_paak")) {
									$dto["Istunto"]["Kayttaja"]->Viranomaisen_rooliDTO = $viranomaisen_rooliDTO_autentikoitu_kayttaja;
								} else {
									throw new SoapFault(ERR_INVALID_ID, "Ei käyttöoikeutta.");
								}

								//$dto["Istunto"]["Kayttaja"]->Paakayttajan_rooliDTO = $paakayttajan_rooliDTO_autentikoitu_kayttaja;

								$db->beginTransaction();

								$osio_saantoDAO = new Osio_saantoDAO($db);
								$osio_lauseDAO = new Osio_lauseDAO($db);

								if(isset($data["poista_osio_saanto"]["Osio_saanto"])){
									foreach($data["poista_osio_saanto"]["Osio_saanto"] as $fk_osio_saanto => $os) { 

										$osio_lauseDAO->merkitse_osio_saannon_lause_poistetuksi($fk_osio_saanto, $kayt_id);
										$osio_saantoDAO->merkitse_osio_saanto_poistetuksi($fk_osio_saanto, $kayt_id);

									}
								}

								$db->commit();
								$db = null;

							//}
						} else {
							throw new SoapFault(ERR_INVALID_ID, "Pyydettyä resurssia ei löydetty.");
						}	 
					}
				} catch (PDOException $ex) {
					echo($ex->getMessage());
				}
			} else {
				throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid parameters");
			}
		} else {
			throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
		}
		return muodosta_dto($dto);

	}

	private function _connectToDb() {
		if (version_compare(phpversion(), '5.3.3', '<=')) {
			//return new PDO('mysql:host=localhost;dbname='.DB_DATABASE_NAME.';charset=latin1_swedish_ci', DB_USER_NAME, DB_PASSWORD, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ORACLE_NULLS => PDO::NULL_EMPTY_STRING));
			return new PDO('mysql:host=localhost;dbname='.DB_DATABASE_NAME.';charset=latin1_swedish_ci', DB_USER_NAME, DB_PASSWORD, array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		} else {

			$dsn = 'mysql:dbname=' . DB_DATABASE_NAME . ';host=127.0.0.1;charset=utf8;';

			try {
				$dbh = new PDO($dsn, "root", DB_PASSWORD);
			} catch (PDOException $e) {
				echo 'Connection failed: ' . $e->getMessage();
			}

			return $dbh;
		
		}		
	}
	
	private function _disconnectFromDb($db, $sth){
		$sth = null;
		$db = null;
	}

}