<?php
/*
 * FMAS Käyttölupapalvelu
 * Business logic layer
 *
 * Created: 8.10.2015
*/

define('FMAS', true); // security GP

// error reporting settings moved to /logic/_config.php
define("LANGUAGE_FILES_BASE", "lang_%s.php");
include_once '_config.php';
require_once 'vendor/autoload.php';
include_once("helper_functions.php");

use WSDL\WSDLCreator;
use WSDL\XML\Styles\RpcEncoded;

//$current_url = "http://" . $_SERVER['SERVER_NAME'] . strtok($_SERVER["REQUEST_URI"],'?');
ini_set("soap.wsdl_cache_enabled", 0);
ini_set('default_socket_timeout', 600);

if (isset($_GET['wsdl'])) {
    $wsdl = new WSDLCreator('fmas_business_logic', $current_url);
    $wsdl->setNamespace(WSDL_XML_NAMESPACE)->setBindingStyle(new RpcEncoded());;
    $wsdl->renderWSDL();
    exit;
}

//xml namespace
$options=array('uri'=>WSDL_XML_NAMESPACE, 'encoding'=>'UTF-8');

//create a new SOAP server
$server = new SoapServer("{$current_url}?wsdl", $options);

$server->setClass('fmas_business_logic');
$server->handle();

class fmas_business_logic {

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function paivita_aineistot($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("luvan_kohteet"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"paivita_aineistot",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * create a applicant and send the email
	 * input: "kayt_id", "hakemusversio_id", "token", "data['sahkoposti']"
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function luo_hakija_ja_laheta_sahkopostikutsu($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "hakemusversio_id", "token", "data"))){
			if(isset($syoteparametrit_taulukko["data"]["sahkoposti"])){
				
				// Lisätään parametriksi sähköpostivarmenne
				$sahkopostivarmenne = bin2hex(openssl_random_pseudo_bytes(85));
				$syoteparametrit_taulukko["sahkopostivarmenne"] = $sahkopostivarmenne;				
				$syoteparametrit = muodosta_dto($syoteparametrit_taulukko);

				$dto_taulukko = dto_taulukkomuotoon(suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"luo_hakija",$syoteparametrit));						
			
				$dto_taulukko["sahkopostikutsu_lahetetty"] = false;
				
				$sahkopostiosoite = $syoteparametrit_taulukko["data"]["sahkoposti"];

				// Lähetetään varmenne käyttäjän sähköpostiin  
				if(isset($dto_taulukko["KayttajaDTO_Uusi_hakija"]->ID) && $dto_taulukko["KayttajaDTO_Uusi_hakija"]->ID!=$syoteparametrit_taulukko["kayt_id"]){

					sisallyta_kielitiedosto((isset($syoteparametrit_taulukko["kayt_kieli"]) ? $syoteparametrit_taulukko["kayt_kieli"] : "fi"));
				
					$hash = MD5($sahkopostiosoite.$sahkopostivarmenne);
					$link = KAYT_LIITT_TUTK_RYHM_URL . "?sahkopostiosoite=$sahkopostiosoite&hash=$hash";

					$message = "<p>" . EMAIL_KUTSU_VIESTI . ".</p>";
					$message .= "<br>";
					$message .= $link;

					//debug_log("INVITE EMAIL:\n{$message}");
						
					if(laheta_sposti($sahkopostiosoite, EMAIL_KUTSU_OTSIKKO, $message)){
						$dto_taulukko["sahkopostikutsu_lahetetty"] = true;
					} else {
						throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Sähköpostin lähettäminen käyttäjälle epäonnistui");
					}

				}

				return muodosta_dto($dto_taulukko);			
							
			} else {
				throw new SoapFault(ERR_MISSING_PARAMETER, "Käyttäjän sähköposti puuttuu");
			}			
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}	

	/**
	 * register user
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function rekisteroi_kayttaja($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("sahkopostiosoite", "sukunimi", "etunimi", "salasana"))){
			$sahkopostivarmenne = bin2hex(openssl_random_pseudo_bytes(78));
			$sahkopostiosoite = $syoteparametrit_taulukko["sahkopostiosoite"];

			$syoteparametrit_taulukko["sahkopostivarmenne"] = $sahkopostivarmenne;
			$syoteparametrit = muodosta_dto($syoteparametrit_taulukko);

			$dto = suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"rekisteroi_kayttaja",$syoteparametrit);
			$dto_taulukko = dto_taulukkomuotoon($dto);

			// Lähetetään vahvistusviesti, jos tietokantaan luotiin uusi käyttäjä
			if(isset($dto_taulukko["Uusi_rekisteroity_kayttaja"]["KayttajaDTO"]->ID) && isset($dto_taulukko["Uusi_rekisteroity_kayttaja"]["KayttajaDTO"]->Sahkopostivarmenne) && $dto_taulukko["Uusi_rekisteroity_kayttaja"]["KayttajaDTO"]->Kayttaja_varmennettu==0){
				$hash_varmenne = MD5($sahkopostiosoite.$dto_taulukko["Uusi_rekisteroity_kayttaja"]["KayttajaDTO"]->Sahkopostivarmenne);

				sisallyta_kielitiedosto((isset($syoteparametrit_taulukko["kayt_kieli"]) ? $syoteparametrit_taulukko["kayt_kieli"] : "fi"));
				
				$link = KAYTTAJAN_VARMENNUS_URL . "?sahkopostiosoite=$sahkopostiosoite&varmenne=$hash_varmenne";
				$subject = "" . REKISTEROINTI_OTSIKKO . ".";
				$message = "<h1>" . REKISTEROINTI_VIESTI_A . "</h1> " . REKISTEROINTI_VIESTI_B . ".";
				$message .= "<br>";
				$message .= $link;

				//debug_log("reg email:");
				//debug_log($message);

				$retval = laheta_sposti($sahkopostiosoite, $subject, $message);

				if(!$retval) {
					throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Sähköpostin lähettäminen käyttäjälle epäonnistui");
				} else {
					$dto_taulukko["kayttaja_luotu"] = true;
				}

			}
			return muodosta_dto($dto_taulukko);

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * verify the user (with email link)
	 * input: "sahkopostiosoite", "varmenne"
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function varmenna_kayttaja($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("sahkopostiosoite", "varmenne"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"varmenna_kayttaja",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * assign the user to application
	 * input: "sahkopostiosoite", "kayttajan_liittamisen_varmenne"
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function liita_kayttaja_hakemukseen($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("sahkopostiosoite", "kayttajan_liittamisen_varmenne"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"liita_kayttaja_hakemukseen",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * save the users info
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_kayttajan_tiedot($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"tallenna_kayttajan_tiedot",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * create a form
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function luo_lomake($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"luo_lomake",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * save form
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_lomake($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"tallenna_lomake",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * generate page to save form
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_lomake_sivu($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"tallenna_lomake_sivu",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * save the form result
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_lomakkeen_saanto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"tallenna_lomakkeen_saanto",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * add the type of attachment
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function lisaa_liitetyyppi($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "data"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"lisaa_liitetyyppi",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * save the type of attachment
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_liitetyyppi($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "data"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"tallenna_liitetyyppi",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * change the user role
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function paivita_kayttajan_rooli($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("rooli_valittu", "kayttaja_rooli", "fk_viranomainen", "fk_kayttaja", "token", "tallentaja_id"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"paivita_kayttajan_rooli",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * add the authority
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function lisaa_viranomainen($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("tallennettavan_kayttajatunnus", "tallennettavan_vir_koodi", "tallennettavan_roolit", "token", "tallentaja_id"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"lisaa_viranomainen",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * add the authority field
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function lisaa_viranomaiskohtainen_kentta($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("viranomainen", "tallentaja_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"lisaa_viranomaiskohtainen_kentta",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * assign authority to the application
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function ota_hakemus_viranomaiskasittelyyn($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "hakemus_id", "kasittelija"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"ota_hakemus_viranomaiskasittelyyn",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * promote a case to be signed
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function ota_aineistotilaus_kasittelyyn($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "aineistotilaus_id", "kasittelija"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"ota_aineistotilaus_kasittelyyn",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}	

	/**
	 * change the visibility of the statement (lausunto)
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function vaihda_lausunnon_nakyvyys($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "lausunto_id", "naytetaankoLausuntoHakijoille"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"vaihda_lausunnon_nakyvyys",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function laheta_lausuntopyynto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("data", "kayt_id", "token", "hakemus_id"))){
			
			$dto_lausuntopyynto = dto_taulukkomuotoon(suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"laheta_lausuntopyynto",$syoteparametrit));
			
			if(isset($dto_lausuntopyynto["Lausuntopyynto_lahetetty"]) && $dto_lausuntopyynto["Lausuntopyynto_lahetetty"]){
				
				sisallyta_kielitiedosto((isset($dto_lausuntopyynto["LausuntopyyntoDTO"]->KayttajaDTO_Antaja->Kieli_koodi) ? $dto_lausuntopyynto["LausuntopyyntoDTO"]->KayttajaDTO_Antaja->Kieli_koodi : "fi"));
												
				$vastOtNimi = $dto_lausuntopyynto["LausuntopyyntoDTO"]->KayttajaDTO_Antaja->Etunimi . " " . $dto_lausuntopyynto["LausuntopyyntoDTO"]->KayttajaDTO_Antaja->Sukunimi;
				$lahettNimi = $dto_lausuntopyynto["LausuntopyyntoDTO"]->KayttajaDTO_Pyytaja->Etunimi . " " . $dto_lausuntopyynto["LausuntopyyntoDTO"]->KayttajaDTO_Pyytaja->Sukunimi;
				
				$otsikko = LAUSUNTOPYYNTO_OTSIKKO;
				$viesti = "
				" . HEI . " " . $vastOtNimi . "
				<p>" . LAUSUNTOPYYNTO_VIESTI_A . ".<br>
				<b>" . LAUS_PYYTAJA . ":</b> ".$lahettNimi.".</p>
		
				<p>" . VOIT_TARK_PROS . ": 
				<a href='" . PRESENTATION_SERVER . "kirjaudu.php'>" . PRESENTATION_SERVER . "kirjaudu.php</a></p>
				<hr /><p style='font-size: 80%;'>" . AUTO_VIESTI . "</p>
				";
								
				$dto_lausuntopyynto["Email_lahetetty_lausunnonantajalle"] = laheta_sposti($dto_lausuntopyynto["LausuntopyyntoDTO"]->KayttajaDTO_Antaja->Sahkopostiosoite, $otsikko, $viesti);
				
			}
			
			return muodosta_dto($dto_lausuntopyynto);
			
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function laheta_aineistopyynto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("data", "kayt_id", "tutkimus_id"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"laheta_aineistopyynto",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function peru_aineistopyynto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("token","kayt_id", "fk_aineistotilaus"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"peru_aineistopyynto",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function laheta_reklamaatiotilaus($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("token", "fk_aineistotilaus", "kayt_id"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"laheta_reklamaatiotilaus",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function kuittaa_aineisto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("data", "kayt_id", "token", "fk_aineistotilaus"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"kuittaa_aineisto",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function laheta_lausunto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("lausunto_julkaistu", "data", "kayt_id", "token", "hakemus_id"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"laheta_lausunto",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function laheta_viesti($syoteparametrit){

		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("on_vastaus", "kayt_id", "token", "hakemus_id", "data"))){
			
			$dto_laheta_viesti = dto_taulukkomuotoon(suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"laheta_viesti",$syoteparametrit));
	
			if(isset($dto_laheta_viesti["Taydennettava_hakemusversioDTO"]) && isset($dto_laheta_viesti["Taydennyspyynto_lahetetty"]) && $dto_laheta_viesti["Taydennyspyynto_lahetetty"]){
				$dto_paatos = dto_taulukkomuotoon($this->luo_hakemus(muotoile_soap_parametrit(array("taydennetty_hakemus"=>1,"hakemusversio_id"=>$dto_laheta_viesti["Taydennettava_hakemusversioDTO"]->ID,"lomake_id"=>$dto_laheta_viesti["Taydennettava_hakemusversioDTO"]->LomakeDTO->ID, "token"=>$syoteparametrit_taulukko["token"], "kayt_id"=>$syoteparametrit_taulukko["kayt_id"]))));
			}
			
			// Lähetetään vastaanottajalle sähköposti viestin saapumisesta
			if(isset($dto_laheta_viesti["Viesti_lahetetty"]) && $dto_laheta_viesti["Viesti_lahetetty"]==true && isset($dto_laheta_viesti["KayttajaDTO_Vastaanottaja"]->Sahkopostiosoite) && filter_var($dto_laheta_viesti["KayttajaDTO_Vastaanottaja"]->Sahkopostiosoite, FILTER_VALIDATE_EMAIL)){
				
				sisallyta_kielitiedosto((isset($dto_laheta_viesti["KayttajaDTO_Vastaanottaja"]->Kieli_koodi) ? $dto_laheta_viesti["KayttajaDTO_Vastaanottaja"]->Kieli_koodi : "fi"));
				
				$vastOtNimi = $dto_laheta_viesti["KayttajaDTO_Vastaanottaja"]->Etunimi . " " . $dto_laheta_viesti["KayttajaDTO_Vastaanottaja"]->Sukunimi;
				$lahettNimi = $dto_laheta_viesti["KayttajaDTO_Lahettaja"]->Etunimi . " " . $dto_laheta_viesti["KayttajaDTO_Lahettaja"]->Sukunimi;
				
				$viesti = "
				" . HEI . " " . $vastOtNimi . "
				<p>" . VIESTI_SISALTO_A . ".<br>
				<b>" . VIESTIN_LAHETTAJA . ":</b> ".$lahettNimi.".</p>
		
				<p>" . VOIT_TARK_PROS . ": 
				<a href='" . PRESENTATION_SERVER . "kirjaudu.php'>" . PRESENTATION_SERVER . "kirjaudu.php</a></p>
				<hr /><p style='font-size: 80%;'>" . AUTO_VIESTI . "</p>
				";
								
				laheta_sposti($dto_laheta_viesti["KayttajaDTO_Vastaanottaja"]->Sahkopostiosoite, VIESTI_OTSIKKO, $viesti);				
				
			}
			
			return muodosta_dto($dto_laheta_viesti);
			
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function laheta_taydennysasiakirjat($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("fk_paatos", "kayt_id", "token", "tiedostot"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"laheta_taydennysasiakirjat",$syoteparametrit);			
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}	
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_hakemusversioon_liitetiedosto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "hakemusversio_id", "name", "tiedosto"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"tallenna_hakemusversioon_liitetiedosto",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_lausunnon_liitetiedosto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "lausunto_id", "name", "tiedosto"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"tallenna_lausunnon_liitetiedosto",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_paatoksen_liitetiedosto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "paatos_id", "name", "tiedosto"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"tallenna_paatoksen_liitetiedosto",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}	
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_metatiedot($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "data"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"tallenna_metatiedot",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}	

	/**
	 * update application
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_hakemus($syoteparametrit){

		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "hakemusversio_id", "tallennettavat_tiedot", "data"))){
			
			$yhteys = $this->yhdista_datakerrokseen();

			$dto_taulukko = dto_taulukkomuotoon(suorita_datakerroksen_funktio($yhteys,"tallenna_hakemus",$syoteparametrit));
						
			$dto_taulukko["kayt_id"] = $syoteparametrit_taulukko["kayt_id"]; $dto_taulukko["tutkimus_id"] = $syoteparametrit_taulukko["tutkimus_id"]; $dto_taulukko["token"] = $syoteparametrit_taulukko["token"]; $dto_taulukko["hakemusversio_id"] = $syoteparametrit_taulukko["hakemusversio_id"]; 
		
			$hakemusversioDTO = (isset($dto_taulukko["HakemusversioDTO"]) ? $dto_taulukko["HakemusversioDTO"] : null);
									
			if(isset($syoteparametrit_taulukko["sivu"])){
				$dto_taulukko["sivu"] = $syoteparametrit_taulukko["sivu"];
			} else {
				$i = 0;
				foreach($hakemusversioDTO->Lomakkeen_sivutDTO as $sivun_tunniste => $nakyma_hakemusversio){
					if($i==0) $dto_taulukko["sivu"] = $sivun_tunniste;
					$i++;
				}
			}
													
			$hakemusversioDTO->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu = paivita_osioiden_tilat($dto_taulukko, $hakemusversioDTO->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu, $dto_taulukko["sivu"], $yhteys);
				
			// Tarkistetaan hakemuksen puuttuvat tiedot
			if(!is_null($hakemusversioDTO)) $hakemusversioDTO = tarkista_hakemusversion_puuttuvat_tiedot($hakemusversioDTO, $dto_taulukko["SitoumuksetDTO"], $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"]);
			
			// front-end puolella json.parse herjaa erikoismerkeistä
			// temp ratkaisu => tyhjennetään osiotaulun sisällöt, koska front-end javascript puolella tarvitaan vain niiden tilatiedot
			$osiotDTO = $hakemusversioDTO->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu;
			
			if(is_array($osiotDTO)){
				foreach($osiotDTO as $fk_osio => $osioDTO){
					if(isset($osiotDTO[$fk_osio]->Osio_sisaltoDTO->Sisalto_text)) $osiotDTO[$fk_osio]->Osio_sisaltoDTO->Sisalto_text = "";			  			
				}			
			}
			
			$hakemusversioDTO->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu = $osiotDTO;
			
			$dto_taulukko["HakemusversioDTO"] = $hakemusversioDTO;			
															
			return muodosta_dto($dto_taulukko);
			
			/*
			if(isset($dto_tallennus["Organisaatio_poistettu"]) ||  isset($dto_tallennus["Uusi_tutkimuksen_organisaatio_id"]) || isset($dto_tallennus["Uusi_tallennettu_tieto"]["Haettu_luvan_kohdeDTO"]) || isset($dto_tallennus["Luvan_kohde_poistettu"]) || isset($dto_tallennus["Uusi_alustettu_tieto"]["Haettu_luvan_kohdeDTO"])){
				if($syoteparametrit_taulukko["data"]["tallennuskohde_kentta"]!="Rekisterinpitaja"){
					return muodosta_dto($dto_tallennus);
				}
			}

			$dto_hakemusversio = dto_taulukkomuotoon($this->hae_hakemusversio($syoteparametrit));
			$dto_taulukko = array_merge($dto_tallennus,$dto_hakemusversio);

			return muodosta_dto($dto_taulukko);	
			*/

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_paatos_lomake($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "hakemus_id", "tallennettavat_tiedot", "data"))){

			$yhteys = $this->yhdista_datakerrokseen();

			$dto_tallennus = dto_taulukkomuotoon(suorita_datakerroksen_funktio($yhteys,"tallenna_paatos_lomake",$syoteparametrit));
			$dto_paatos = dto_taulukkomuotoon($this->hae_paatos($syoteparametrit));
			$dto_taulukko = array_merge($dto_tallennus,$dto_paatos);

			return muodosta_dto($dto_taulukko);

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_lausunto_lomake($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "lausunto_id", "data"))){

			$yhteys = $this->yhdista_datakerrokseen();

			$dto_tallennus = dto_taulukkomuotoon(suorita_datakerroksen_funktio($yhteys,"tallenna_lausunto_lomake",$syoteparametrit));
			$dto_lausunto = dto_taulukkomuotoon($this->hae_lausunto($syoteparametrit));
			$dto_taulukko = array_merge($dto_tallennus,$dto_lausunto);

			return muodosta_dto($dto_taulukko);

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_paatos($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "hakemus_id", "data"))){
			
			$dto_paatoksen_tallennus = dto_taulukkomuotoon(suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"tallenna_paatos",$syoteparametrit));

			// Lähetetään sähköposti hakemuksen yhteyshenkilölle
			if($dto_paatoksen_tallennus["Paatos_tallennettu"]) {
				
				sisallyta_kielitiedosto((isset($dto_paatoksen_tallennus["HakijaDTO_Yhteyshenkilo"]->KayttajaDTO->Kieli_koodi) ? $dto_paatoksen_tallennus["HakijaDTO_Yhteyshenkilo"]->KayttajaDTO->Kieli_koodi : "fi"));
				
				$yhtHenkNimi = $dto_paatoksen_tallennus["HakijaDTO_Yhteyshenkilo"]->Etunimi . " " . $dto_paatoksen_tallennus["HakijaDTO_Yhteyshenkilo"]->Sukunimi;
				$tutNimi = $dto_paatoksen_tallennus["Allekirjoitettu_PaatosDTO"]->HakemusDTO->HakemusversioDTO->Tutkimuksen_nimi;

				if(filter_var($dto_paatoksen_tallennus["HakijaDTO_Yhteyshenkilo"]->Sahkopostiosoite, FILTER_VALIDATE_EMAIL)){
				
					$postiOs = $dto_paatoksen_tallennus["HakijaDTO_Yhteyshenkilo"]->Sahkopostiosoite;
					$otsikko = "Sähköinen käyttölupapalvelu: Päätös vastaanotettu";
					$paatos = $dto_paatoksen_tallennus["Allekirjoitettu_PaatosDTO"]->Paatoksen_tilaDTO->Paatoksen_tilan_koodi;
					$tila = "";
					if ($paatos == "paat_tila_hylatty") $tila = "hylätty";
					else if ($paatos == "paat_tila_hyvaksytty") $tila = "hyväksytty";
					else if ($paatos == "paat_tila_peruttu") $tila = "peruttu";
					else if ($paatos == "paat_tila_korvattu") $tila = "korvattu";
					else if ($paatos == "paat_tila_rauennut") $tila = "rauennut";

					$viesti = "" . HEI . " " . $yhtHenkNimi . "\n
						<p>" . HAKEMUKSEN . " \"".$tutNimi."\" ". PAAT_VALMISTUNUT . ".\n<br>
						<b>" . PAATOS . ":</b> " . HAKEMUS . " " . ON . " " . $tila."</p>
						<p>" . VOIT_TARK_PROS . ": 
						<a href='" . PRESENTATION_SERVER . "kirjaudu.php'>" . PRESENTATION_SERVER . "kirjaudu.php</a></p>
						<hr /><p style='font-size: 80%;'>" . AUTO_VIESTI . "</p>
						";
						
				
					$dto_paatoksen_tallennus["Email_lahetetty"] = laheta_sposti($postiOs, $otsikko, $viesti);
					
				} else {
					$dto_paatoksen_tallennus["Email_lahetetty"] = false;
				}
				
			}
			
			return muodosta_dto($dto_paatoksen_tallennus);
			
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function tallenna_aineistonmuodostus($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "hakemus_id", "data"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"tallenna_aineistonmuodostus",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc send application
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function laheta_hakemus($syoteparametrit){

		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("tutkimus_id", "kayt_id", "token", "hakemusversio_id"))){
			
			$dto_hakemusversio = dto_taulukkomuotoon($this->hae_hakemusversio($syoteparametrit));
			$hakemusversioDTO = $dto_hakemusversio["HakemusversioDTO"];

			$pakollisia_tietoja_puuttuu = false;
			$taydennettavat_sivut = "";
			$sivu_nro = 1;
			
			foreach($hakemusversioDTO->Lomakkeen_sivutDTO as $sivun_tunniste => $lomakkeen_sivutDTO){
				if($lomakkeen_sivutDTO->Pakollisia_tietoja_puuttuu==1){
					
					if(!$pakollisia_tietoja_puuttuu) $pakollisia_tietoja_puuttuu = true;
					
					if($sivu_nro==1){
						if(isset($syoteparametrit_taulukko["kayt_kieli"]) && $syoteparametrit_taulukko["kayt_kieli"]=="en"){
							$taydennettavat_sivut = $lomakkeen_sivutDTO->Nimi_en;
						} else {
							$taydennettavat_sivut = $lomakkeen_sivutDTO->Nimi_fi;
						}								
					} else {
						if(isset($syoteparametrit_taulukko["kayt_kieli"]) && $syoteparametrit_taulukko["kayt_kieli"]=="en"){
							$taydennettavat_sivut = $taydennettavat_sivut . ", " . mb_strtolower($lomakkeen_sivutDTO->Nimi_en, 'UTF-8');
						} else {	
							$taydennettavat_sivut = $taydennettavat_sivut . ", " . mb_strtolower($lomakkeen_sivutDTO->Nimi_fi, 'UTF-8');
						}	
					}
					
					$sivu_nro++;
											
				} 																	
			}

			if(!$pakollisia_tietoja_puuttuu){
			
				$dto_hakemuksen_lahetys = dto_taulukkomuotoon(suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"laheta_hakemus",muodosta_dto($syoteparametrit_taulukko)));
				
				// Lähetetään sähköposti hakemuksen yhteyshenkilölle
				if($dto_hakemuksen_lahetys["Hakemus_lahetetty"]) {
					
					sisallyta_kielitiedosto((isset($dto_hakemuksen_lahetys["HakijaDTO_Yhteyshenkilo"]->KayttajaDTO->Kieli_koodi) ? $dto_hakemuksen_lahetys["HakijaDTO_Yhteyshenkilo"]->KayttajaDTO->Kieli_koodi : "fi"));
					
					$yhtHenkNimi = $dto_hakemuksen_lahetys["HakijaDTO_Yhteyshenkilo"]->Etunimi . " " . $dto_hakemuksen_lahetys["HakijaDTO_Yhteyshenkilo"]->Sukunimi;
					$tutNimi = $dto_hakemuksen_lahetys["Lahetetty_HakemusversioDTO"]->Tutkimuksen_nimi;

					$postiOs = $dto_hakemuksen_lahetys["HakijaDTO_Yhteyshenkilo"]->Sahkopostiosoite;
					$viesti = "" . HEI . " " . htmlentities($yhtHenkNimi, ENT_COMPAT, "UTF-8") . ",
						<p>" . HAKEMUS . " \"".htmlentities($tutNimi, ENT_COMPAT, "UTF-8")."\" " . LAHETA_HAKEMUS_VIESTI_A . ".</p>
						<p>" . VOIT_TARK_PROS . ": 
						<a href='" . PRESENTATION_SERVER . "kirjaudu.php'>" . PRESENTATION_SERVER . "kirjaudu.php</a></p>
						<hr /><p style='font-size: 80%;'>" . AUTO_VIESTI . "</p>
						";
						
					$dto_hakemuksen_lahetys["Email_lahetetty_yhteyshenkilolle"] = laheta_sposti($postiOs, LAHETA_HAKEMUS_OTSIKKO, $viesti);
					
				}
				
				return muodosta_dto($dto_hakemuksen_lahetys);
				
			} else {
				
				$dto = array();
				$dto["Hakemus_lahetetty"] = false;
				
				if(isset($syoteparametrit_taulukko["kayt_kieli"]) && $syoteparametrit_taulukko["kayt_kieli"]=="en"){
					$dto["Hakemuksen_lahetys_info"] = "The following pages lack mandatory information: " . $taydennettavat_sivut . "";
				} else {
					$dto["Hakemuksen_lahetys_info"] = "Seuraavilta sivuilta puuttuu pakollisia tietoja: " . $taydennettavat_sivut . "";
				}
				
				return muodosta_dto($dto);	
				
			}	

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function kirjaa_lokiin($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "toiminto"))){
			if($syoteparametrit_taulukko["toiminto"]=="out"){
				suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"vapauta_kayttajan_lukitsemat_hakemusversiot",$syoteparametrit);
			}
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"kirjaa_lokiin",$syoteparametrit);

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * create an application ('Uusi käyttölupahakemus' button goes here)
	 *
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function luo_hakemus($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"luo_hakemus",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function kirjaudu_lupapalveluun($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("sahkopostiosoite", "salasana"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"kirjaudu_lupapalveluun",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_kayttajan_tiedot($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("roolin_koodi","kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_kayttajan_tiedot",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit	 
	 * @return string[] $dto
	 */
	public function hae_lupapalvelun_tilastotiedot($syoteparametrit) {
		return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_lupapalvelun_tilastotiedot",$syoteparametrit);
	}	

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_saapuneet_lausuntopyynnot($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_saapuneet_lausuntopyynnot",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_annetut_lausunnot($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_annetut_lausunnot",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_saapuneet_hakemukset_viranomaiselle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			
			$dto = suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_saapuneet_hakemukset_viranomaiselle",$syoteparametrit);
			
			if(isset($syoteparametrit_taulukko["jarjestys_kohde"]) && isset($syoteparametrit_taulukko["jarjestys_kentta"]) && isset($syoteparametrit_taulukko["jarjestys_tyyppi"])){

				$dto_taulukko = dto_taulukkomuotoon($dto);
				$lajiteltavat_hakemuksetDTO = $dto_taulukko["HakemuksetDTO"][$syoteparametrit_taulukko["jarjestys_kohde"]];
				
				if($syoteparametrit_taulukko["jarjestys_kentta"]=="Diaarinumero" || $syoteparametrit_taulukko["jarjestys_kentta"]=="Hakemuksen_tunnus" || $syoteparametrit_taulukko["jarjestys_kentta"]=="Kasittelijan_nimi" || $syoteparametrit_taulukko["jarjestys_kentta"]=="Tutkimuksen_nimi" || $syoteparametrit_taulukko["jarjestys_kentta"]=="Hakemuksen_tila"){
					if($syoteparametrit_taulukko["jarjestys_tyyppi"]=="asc") usort($lajiteltavat_hakemuksetDTO, jarjesta_objekti_avaimena_string($syoteparametrit_taulukko["jarjestys_kentta"]));
					if($syoteparametrit_taulukko["jarjestys_tyyppi"]=="desc") usort($lajiteltavat_hakemuksetDTO, array_reverse(jarjesta_objekti_avaimena_string($syoteparametrit_taulukko["jarjestys_kentta"])));
				}
				
				if($syoteparametrit_taulukko["jarjestys_kentta"]=="Tilan_pvm"){
					if($syoteparametrit_taulukko["jarjestys_tyyppi"]=="asc") usort($lajiteltavat_hakemuksetDTO, jarjesta_objekti_avaimena_nro($syoteparametrit_taulukko["jarjestys_kentta"]));
					if($syoteparametrit_taulukko["jarjestys_tyyppi"]=="desc") usort($lajiteltavat_hakemuksetDTO, array_reverse(jarjesta_objekti_avaimena_nro($syoteparametrit_taulukko["jarjestys_kentta"])));	
				}
				
				$dto_taulukko["HakemuksetDTO"][$syoteparametrit_taulukko["jarjestys_kohde"]] = $lajiteltavat_hakemuksetDTO;

				return muodosta_dto($dto_taulukko);
				
			}
			
			return $dto;
			
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_saapuneet_aineistotilaukset($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_saapuneet_aineistotilaukset",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemuksen_aineistonmuodostus($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "hakemus_id"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_hakemuksen_aineistonmuodostus",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}	

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_saapuneet_lausunnot_viranomaiselle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "viranomaisroolin_koodi"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_saapuneet_lausunnot_viranomaiselle",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function etsi_hakemuksia($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("viranomaisroolin_koodi", "data", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"etsi_hakemuksia",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_yhteenveto_viranomaiselle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("viranomaisroolin_koodi", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_yhteenveto_viranomaiselle",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_metatiedot($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("hakemus_id", "kayttajan_rooli", "metatiedot_kohde", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_metatiedot",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}	

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemukset_tutkijalle($syoteparametrit){

		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){

			$dto = suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_hakemukset_tutkijalle",$syoteparametrit);

			return $dto;

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}

	}
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_eraantyvat_kayttoluvat($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_eraantyvat_kayttoluvat",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_kayttajaroolit_lupapalvelun_paakayttajalle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_kayttajaroolit_lupapalvelun_paakayttajalle",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_viranomaiskohtaiset_lupapalvelun_paakayttajalle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_viranomaiskohtaiset_lupapalvelun_paakayttajalle",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_viranomaiskohtaiset_viranomaisen_paakayttajalle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_viranomaiskohtaiset_viranomaisen_paakayttajalle",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_kayttajaroolit_viranomaisen_paakayttajalle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_kayttajaroolit_viranomaisen_paakayttajalle",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc Haetaan hakemus
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemusversio($syoteparametrit) {

		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("hakemusversio_id", "kayt_id", "token","tutkimus_id"))){
										 			
			// Haetaan hakemus
			$yhteys = $this->yhdista_datakerrokseen();
			$dto_taulukko = dto_taulukkomuotoon(suorita_datakerroksen_funktio($yhteys,"hae_hakemusversio",$syoteparametrit));

			$dto_taulukko["kayt_id"] = $syoteparametrit_taulukko["kayt_id"]; $dto_taulukko["tutkimus_id"] = $syoteparametrit_taulukko["tutkimus_id"]; $dto_taulukko["token"] = $syoteparametrit_taulukko["token"]; $dto_taulukko["hakemusversio_id"] = $syoteparametrit_taulukko["hakemusversio_id"]; 
		
			$hakemusversioDTO = (isset($dto_taulukko["HakemusversioDTO"]) ? $dto_taulukko["HakemusversioDTO"] : null);
			
			// Määritetään viranomaiset, joille muutoshakemuksen voi lähettää
			if(isset($syoteparametrit_taulukko["sivu"]) && $syoteparametrit_taulukko["sivu"]=="hakemus_esikatsele_ja_laheta" && $hakemusversioDTO->Versio > 1){				
				$dto_taulukko["hakemuksen_viranomaiset"] = maarita_muutoshakemuksen_viranomaiset((isset($dto_taulukko["Uusimmat_hakemuksetDTO"]) ? $dto_taulukko["Uusimmat_hakemuksetDTO"] : array()), $hakemusversioDTO);				
			}
			
			// Generoidaan PDF
			if(isset($syoteparametrit_taulukko["generoi_pdf"]) && $syoteparametrit_taulukko["generoi_pdf"]){
				
				$dto_taulukko["kayt_kieli"] = $syoteparametrit_taulukko["kayt_kieli"];
				
				try {
					$dto_taulukko["pdf_content"] = self::hakemus_pdf($dto_taulukko);
				} catch (SoapFault $e) {
					$dto_taulukko["error"] = $e->getMessage();
				}
				
				$dto_taulukko["document_filename"] = 'hakemus'; // it means the downloaded file will be 'hakemus.pdf'
			
			}
						
			if(isset($syoteparametrit_taulukko["sivu"])){
				$dto_taulukko["sivu"] = $syoteparametrit_taulukko["sivu"];
			} else {
				$i = 0;
				foreach($hakemusversioDTO->Lomakkeen_sivutDTO as $sivun_tunniste => $nakyma_hakemusversio){
					if($i==0) $dto_taulukko["sivu"] = $sivun_tunniste;
					$i++;
				}
			}
													
			// Päivitetään osioiden tilatiedot
			foreach($hakemusversioDTO->Lomakkeen_sivutDTO as $sivun_tunniste => $nakyma_hakemusversio){
				$hakemusversioDTO->Lomakkeen_sivutDTO[$sivun_tunniste]->OsiotDTO_taulu = paivita_osioiden_tilat($dto_taulukko, $hakemusversioDTO->Lomakkeen_sivutDTO[$sivun_tunniste]->OsiotDTO_taulu, $sivun_tunniste, $yhteys);	
			}	
								
			// Tarkistetaan hakemuksen puuttuvat tiedot
			if(!is_null($hakemusversioDTO)) $hakemusversioDTO = tarkista_hakemusversion_puuttuvat_tiedot($hakemusversioDTO, $dto_taulukko["SitoumuksetDTO"], $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"]);
				
			// front-end puolella json.parse herjaa erikoismerkeistä
			// temp ratkaisu => tyhjennetään osiotaulun sisällöt, koska front-end javascript puolella tarvitaan vain niiden tilatiedot
			foreach($hakemusversioDTO->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu as $fk_osio => $osioDTO){
				if(isset($hakemusversioDTO->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu[$fk_osio]->Osio_sisaltoDTO->Sisalto_text)) $hakemusversioDTO->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu[$fk_osio]->Osio_sisaltoDTO->Sisalto_text = "";			  			
			}
		
			$dto_taulukko["HakemusversioDTO"] = $hakemusversioDTO;
			
			return muodosta_dto($dto_taulukko);

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param array $dto_taulukko
	 * @param string $template
	 * @return string[] $pdf_content
	 */
	public function hakemus_pdf($dto_taulukko) {

		$hakemusversioDTO = $dto_taulukko["HakemusversioDTO"];
		$kieli = (isset($dto_taulukko["kayt_kieli"]) ? $dto_taulukko["kayt_kieli"] : "fi"); // Oletuskieli on suomi

		if($kieli=="en" || kieli=="fi"){
			include_once sprintf(LANGUAGE_FILES_BASE, $kieli);
		} else {
			include_once("lang_fi.php"); // Oletuskieli on suomi
		}
		
		// template data, used in the template to loop through
		$data = array(
			'HakemusPVM' => date_format(date_create($hakemusversioDTO->Lisayspvm), 'd.m.Y'),

			'Oppiarvo' => '',
			'Hakijan_nimi' => '',
			'Hakijan_osoite' => '',
			'Hakijan_sahkoposti' => '',
			'Hakijan_puhelin' => '',

			'liitteet' => array(),
			'segments' => segmentit_pdf_rakenteeseen(array("kieli"=>$kieli, "luvan_kohteetDTO_taika"=>$dto_taulukko["Luvan_kohteetDTO"]["Taika"], "lomakkeen_sivutDTO"=>$dto_taulukko["HakemusversioDTO"]->Lomakkeen_sivutDTO, "hakemusversioDTO"=>$dto_taulukko["HakemusversioDTO"]) ),

		);

		//debug_log("hakevat");
		//debug_log($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat);

		for ($i=0; $i<count($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat); $i++) {
			if (is_array($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO)) {
				for ($j=0; $j<count($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO); $j++) {
					$hakija = $hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i];
					// notice: some of the hakemusversios don't have any hakija with role 'rooli_hak_yht' (only role 'rooli_vast' is present)
					if ($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO[$j]->Hakijan_roolin_koodi == 'rooli_hak_yht') {
						$data['Oppiarvo'] = $hakija->Oppiarvo;
						$data['Hakijan_nimi'] = $hakija->Etunimi . " " . $hakija->Sukunimi;
						$data['Hakijan_osoite'] = $hakija->Osoite;
						$data['Hakijan_sahkoposti'] = $hakija->Sahkopostiosoite;
						$data['Hakijan_puhelin'] = $hakija->Puhelin;
						break 2;
					}
				}
			}
		}

		if (isset($hakemusversioDTO->LiitteetDTO) && is_array($hakemusversioDTO->LiitteetDTO)) {
			for ($i=0; $i<count($hakemusversioDTO->LiitteetDTO); $i++) {
				$data['liitteet'][] = $hakemusversioDTO->LiitteetDTO[$i]->Liitetiedosto_nimi;
			}
		}

		$data["FK_Lomake"] = $hakemusversioDTO->LomakeDTO->ID;
		
		$pdf_content = self::wrap_pdf($data, '', 'hakemus');
		$pdf_content = base64_encode($pdf_content);

		return $pdf_content;
	}

	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_paatos($syoteparametrit) {

		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu(
			$syoteparametrit, array("hakemus_id", "kayt_id", "token"))){

			$yhteys = $this->yhdista_datakerrokseen();
			
			// Haetaan päätös			
			$dto_taulukko = dto_taulukkomuotoon(
                suorita_datakerroksen_funktio($yhteys,"hae_paatos",$syoteparametrit)
            );
			$dto_taulukko["kayt_id"] = $syoteparametrit_taulukko["kayt_id"];
			$dto_taulukko["token"] = $syoteparametrit_taulukko["token"];
			$dto_taulukko["hakemus_id"] = $syoteparametrit_taulukko["hakemus_id"];

			$dto_taulukko["sivu"] = "paatos_oletus";

			/* if (self::access_check) {
				throw new SoapFault(LIBXML_ERR_ERROR, "Permissions error.");
			}*/

            // Generoidaan PDF
			if(isset($syoteparametrit_taulukko["generoi_pdf"]) && $syoteparametrit_taulukko["generoi_pdf"]){
				
				$dto_taulukko["kayt_kieli"] = $syoteparametrit_taulukko["kayt_kieli"];
				
				$template = 'structured';
				if (isset($dto_taulukko["Form_template"])) $template = $dto_taulukko["Form_template"];

				try {

					// debug
					//if (TITLES_OUT==1) {
					//	return self::paatos_pdf($dto_taulukko, $template);
					//}

					$dto_taulukko["pdf_content"] = self::paatos_pdf($dto_taulukko, $template);
					
				} catch (SoapFault $e) {
					$dto_taulukko["error"] = $e->getMessage();
				}
				
				$dto_taulukko["document_filename"] = 'paatos'; // it means the downloaded file will be 'paatos.pdf'

			}
			
            // Päivitetään haetun sivun osioiden tilat
            $dto_taulukko["PaatosDTO"]->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu = paivita_osioiden_tilat($dto_taulukko, $dto_taulukko["PaatosDTO"]->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu, $dto_taulukko["sivu"], $yhteys);			

			// front-end puolella json.parse herjaa erikoismerkeistä
			// temp ratkaisu => tyhjennetään osiotaulun sisällöt, koska front-end javascript puolella tarvitaan vain niiden tilatiedot
			$osiotDTO = $dto_taulukko["PaatosDTO"]->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu;
			
			if(is_array($osiotDTO) && !empty($osiotDTO)){
				foreach($osiotDTO as $fk_osio => $osioDTO){
					if(isset($osiotDTO[$fk_osio]->Osio_sisaltoDTO->Sisalto_text)) $osiotDTO[$fk_osio]->Osio_sisaltoDTO->Sisalto_text = "";			  			
				}				
			}
			
			$dto_taulukko["PaatosDTO"]->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu = $osiotDTO;
			
			return muodosta_dto($dto_taulukko);

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param array $dto_taulukko
	 * @param string $template
	 * @return string[] $pdf_content
	 */
	public function paatos_pdf($dto_taulukko, $template = 'structured') {

        $paatosDTO = $dto_taulukko["PaatosDTO"];
        $sitoumuksetDTO = $dto_taulukko["SitoumuksetDTO"];		
        //$hakijaDTO = $dto_taulukko["HakijaDTO"]; // this object is not here anymore

		$kieli = (isset($dto_taulukko["kayt_kieli"]) ? $dto_taulukko["kayt_kieli"] : "fi"); // Oletuskieli on suomi

		if($kieli=="en" || kieli=="fi"){
			include_once sprintf(LANGUAGE_FILES_BASE, $kieli);
		} else {
			include_once("lang_fi.php"); // Oletuskieli on suomi
		}

		// template data, used in the template to loop through
		$data = array(

            'Viranomaisen_koodi' => $paatosDTO->HakemusDTO->Viranomaisen_koodi,

            'Oppiarvo' => $dto_taulukko['HakijaDTO_Yhteyshenkilo']->Oppiarvo,
            'Hakijan_nimi' => $dto_taulukko['HakijaDTO_Yhteyshenkilo']->Etunimi . " " . $dto_taulukko['HakijaDTO_Yhteyshenkilo']->Sukunimi,
            'Hakijan_osoite' => $dto_taulukko['HakijaDTO_Yhteyshenkilo']->Osoite,
            'Hakijan_sahkoposti' => $dto_taulukko['HakijaDTO_Yhteyshenkilo']->Sahkopostiosoite,
            'Hakijan_puhelin' => $dto_taulukko['HakijaDTO_Yhteyshenkilo']->Puhelin,
			'Diaarinumero' => $paatosDTO->HakemusDTO->AsiaDTO->Diaarinumero,
			'Paatospvm' => $paatosDTO->Paatospvm,

            'Vastuullinen_johtaja_oppiarvo' => $dto_taulukko['HakijaDTO_Vastuullinen_johtaja']->Oppiarvo,
            'Vastuullinen_johtaja_nimi' => $dto_taulukko['HakijaDTO_Vastuullinen_johtaja']->Etunimi . " " . $dto_taulukko['HakijaDTO_Vastuullinen_johtaja']->Sukunimi,
            'Vastuullinen_johtaja_organisaatio' => $dto_taulukko['HakijaDTO_Vastuullinen_johtaja']->Organisaatio,

            'Viranomaisen_titteli' => ' ', // we don't have any title/salutation in `Kayttaja` table yet
            'Viranomaisen_nimi' => $paatosDTO->KayttajaDTO_Kasittelija->Etunimi . " " . $paatosDTO->KayttajaDTO_Kasittelija->Sukunimi,
            'Viranomaisen_sahkoposti' => $paatosDTO->KayttajaDTO_Kasittelija->Sahkopostiosoite,
            'Viranomaisen_puhelin' => $paatosDTO->KayttajaDTO_Kasittelija->Puhelinnumero,

			'Lakkaamispvm' => $paatosDTO->Lakkaamispvm,
			'Tutkimuksen_nimi' => $paatosDTO->HakemusDTO->HakemusversioDTO->Tutkimuksen_nimi,

			'Hinta_arvio' => $paatosDTO->Hinta_arvio,
			'Hinta_arvio_alkuvuosi' => $paatosDTO->Hinta_arvio_alkuvuosi,
			'Hinta_arvio_loppuvuosi' => $paatosDTO->Hinta_arvio_loppuvuosi,
			'Luvan_lausunnot' => $paatosDTO->Luvan_lausunnot,
			'Sovelletut_oikeusohjeet' => $paatosDTO->Sovelletut_oikeusohjeet,
			'Luvan_ehdot' => $paatosDTO->Luvan_ehdot,

			'sitoumukset' => array(),
            'paatetyt_aineistot' => array(),
            'paattajat' => array(),
            'paatoksen_liitteet' => array(),

            // this field is used by paatos_simple template
            'Vapaamuotoinen_paatos' => $paatosDTO->Vapaamuotoinen_paatos,

			'paat_tila_hylatty' => false,
			'paat_tila_hyvaksytty' => false,

			'Hylkayksen_perustelut' => $paatosDTO->Hylkayksen_perustelut,
			'Luovutettavat_tiedot' => $paatosDTO->Luovutettavat_tiedot,
			'Luovutustapa' => $paatosDTO->Luovutustapa,

			'Maksu_euroina' => $paatosDTO->Maksu_euroina,
			'Maksu_peruste' => $paatosDTO->Maksu_peruste,

			'Laskutustieto_1' => $paatosDTO->Laskutustieto_1,
			'Laskutustieto_2' => $paatosDTO->Laskutustieto_2,

			'Rekisterinpitajat' => array(),

			// deprecated fields - replaced with $paatosDTO->HakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Nimi  loop
			'Rekisterinpitaja_nimi' => $paatosDTO->Rekisterinpitaja_nimi,
			'Rekisterinpitaja_osoite' => $paatosDTO->Rekisterinpitaja_osoite,
			'Rekisterinpitaja_posti' => $paatosDTO->Rekisterinpitaja_posti,
			'Rekisterinpitaja_puhelin' => $paatosDTO->Rekisterinpitaja_puhelin,

			// deprecated fields: replaced by Laskutustieto_1, Laskutustieto_2
			'Maksu_organisaation_nimi' => $paatosDTO->Maksu_organisaation_nimi,
			'Maksu_organisaation_y_tunnus' => $paatosDTO->Maksu_organisaation_y_tunnus,

		);

		if (is_array($paatosDTO->HakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO)) {

			for ($i=0; $i<count($paatosDTO->HakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO); $i++) {

				$data['Rekisterinpitajat'][] = array(
					'nimi' => $paatosDTO->HakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Nimi,
					'osoite' => $paatosDTO->HakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Osoite,
				);

			}

		}

        if ($template == 'custom') {
			
			$pdf_osiot_param = array();
			$pdf_osiot_param["osiotDTO_taulu"] = $paatosDTO->Lomakkeen_sivutDTO[$dto_taulukko["sivu"]]->OsiotDTO_taulu;			
            $data['titles'] = osiot_pdf_rakenteeseen($pdf_osiot_param);
            if (TITLES_OUT==1) return $data['titles'];
			
        }

        if ($paatosDTO->HakemusDTO->Viranomaisen_koodi) {
            $viranomaisen_slug = str_replace("v_", "", strtolower($paatosDTO->HakemusDTO->Viranomaisen_koodi));
            $logo_path = "templates/{$viranomaisen_slug}.png";
            if (is_file($logo_path)) $data['logo'] = "templates/{$viranomaisen_slug}.png";
        }

		// sitoumukset

		if (is_array($sitoumuksetDTO)) {
			foreach ($sitoumuksetDTO as $sitoumuksi) {
				$data['sitoumukset'][] = array(
					'Oppiarvo' => $sitoumuksi->KayttajaDTO->HakijaDTO->Oppiarvo,
					'Nimi' => $sitoumuksi->KayttajaDTO->HakijaDTO->Etunimi . " " . $sitoumuksi->KayttajaDTO->HakijaDTO->Sukunimi,
					'Organisaatio' => $sitoumuksi->KayttajaDTO->HakijaDTO->Organisaatio,
				);
			}
		}

        // paatetyt_aineistot

        if (is_array($paatosDTO->Paatetyt_aineistotDTO)) {
            foreach ($paatosDTO->Paatetyt_aineistotDTO as $paatety_aineisto) {
                if (is_array($paatety_aineisto->Paatetyt_luvan_kohteetDTO)) {
                    $cnt = 0;
                    $data['paatetyt_aineistot'][$cnt] = array();

                    foreach ($paatety_aineisto->Paatetyt_luvan_kohteetDTO as $paatety_luvan_kohte) {

                        if ($paatety_luvan_kohte->Luvan_kohdeDTO) {

                            $data['paatetyt_aineistot'][$cnt] = array(

                                // this _vals are not used in the pdf doc, but they might be interesting cause they are populated with some data

                                '_Luvan_kohdeDTO_ID' => $paatety_luvan_kohte->Luvan_kohdeDTO->ID,

                                '_Kohde_tyyppi' => $paatety_luvan_kohte->Kohde_tyyppi,
                                '_Muuttujat_lueteltuna' => $paatety_luvan_kohte->Muuttujat_lueteltuna,
                                '_Poiminta_ajankohdat' => $paatety_luvan_kohte->Poiminta_ajankohdat,

                                'Luvan_kohteen_nimi' => $paatety_luvan_kohte->Luvan_kohdeDTO->Luvan_kohteen_nimi,
                                'Luvan_kohteen_tyyppi' => $paatety_luvan_kohte->Luvan_kohdeDTO->Luvan_kohteen_tyyppi,
                            );
                        }

                        $cnt++;
                    }

                }

            }
        }

        // Paattajat

        if (is_array($paatosDTO->PaattajatDTO)) {
            foreach ($paatosDTO->PaattajatDTO as $paattajaDTO) {
                $data['paattajat'][] = $paattajaDTO->KayttajaDTO->Etunimi . " " . $paattajaDTO->KayttajaDTO->Sukunimi;
            }
        }

        // Paatoksen_liitteet

        if (is_array($paatosDTO->Paatoksen_liitteetDTO)) {
            foreach ($paatosDTO->Paatoksen_liitteetDTO as $Paatoksen_liitteetDTO) {
                $data['paatoksen_liitteet'][] = $Paatoksen_liitteetDTO->Liitteen_nimi;
            }
        }

		// this vars are used in structured paatos template only
		if ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi == "paat_tila_hylatty") $data["paat_tila_hylatty"] = true; // red content is displayed
		if ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi == "paat_tila_hyvaksytty") $data["paat_tila_hyvaksytty"] = true; // green content is displayed

		$pdf_content = self::wrap_pdf($data, $template, 'paatos');
		$pdf_content = base64_encode($pdf_content);

		return $pdf_content;
	}

	public function wrap_pdf($data, $template, $document_type) {

		$html2pdf = new Html2Pdf();

		$html2pdf->setTestTdInOnePage(false);
		
		if ($template!='') $template  = "_{$template}";
		$template_path = "templates/{$document_type}{$template}_html2pdf.html.php";
		if (!is_file($template_path)) {
			throw new SoapFault(ERR_MISSING_PARAMETER, "No such template: {$template_path}");
		}

		ob_start();
		include($template_path);
		$html = ob_get_contents();
		ob_end_clean();

		$html2pdf->writeHTML($html);

		ob_start();
		$html2pdf->output();
		$pdf_content = ob_get_contents();
		ob_end_clean();

		return $pdf_content;

	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemuksen_liitteet($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			// TODO: koontitoiminto (kehitys3 demosta)
			return muodosta_dto(tarkista_hakemuksen_puuttuvat_tiedot(dto_taulukkomuotoon(suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_hakemuksen_liitteet",$syoteparametrit))));
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemuksen_viestit_tutkijalle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("hakemus_id", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_hakemuksen_viestit_tutkijalle",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemuksen_viestit_viranomaiselle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("hakemus_id", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_hakemuksen_viestit_viranomaiselle",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemuksen_lausunnot_viranomaiselle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayttajan_rooli","hakemus_id", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_hakemuksen_lausunnot_viranomaiselle",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_tutkimuksen_aineistotilaus_tutkijalle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("tutkimus_id","kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_tutkimuksen_aineistotilaus_tutkijalle",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_lomakkeet($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_lomakkeet",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_lomake($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("lomake_id","kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_lomake",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_hakemuksen_lausunnot_lausunnonantajalle($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("hakemus_id", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_hakemuksen_lausunnot_lausunnonantajalle",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_lausunto($syoteparametrit) {
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("lausunto_id","hakemus_id", "kayt_id", "token"))){
			
			// Haetaan hakemus
			$yhteys = $this->yhdista_datakerrokseen();
			$dto_taulukko = dto_taulukkomuotoon(suorita_datakerroksen_funktio($yhteys,"hae_lausunto",$syoteparametrit));

			$dto_taulukko["kayt_id"] = $syoteparametrit_taulukko["kayt_id"]; $dto_taulukko["lausunto_id"] = $syoteparametrit_taulukko["lausunto_id"]; $dto_taulukko["token"] = $syoteparametrit_taulukko["token"]; $dto_taulukko["hakemus_id"] = $syoteparametrit_taulukko["hakemus_id"]; 

			$dto_taulukko["sivu"] = "lausunto_oletus";

            // Generoidaan lausunto PDF
            if ($syoteparametrit_taulukko["format"] == "pdf") {
				
				$kieli = (isset($syoteparametrit_taulukko["kayt_kieli"]) ? $syoteparametrit_taulukko["kayt_kieli"] : "fi"); // Oletuskieli on suomi
				
                try {
                    $dto_taulukko["pdf_content"] = self::lausunto_pdf($dto_taulukko["LausuntoDTO"], $dto_taulukko["HakemusDTO"], $kieli);
                } catch (SoapFault $e) {
                    $dto_taulukko["error"] = $e->getMessage();
                }
				
                $dto_taulukko["document_filename"] = 'lausunto'; // it means the downloaded file will be 'lausunto.pdf'
				
            }
			
			// Päivitetään haetun sivun osioiden tilat
			$dto_taulukko["LausuntoDTO"]->Lomakkeen_sivutDTO["lausunto_oletus"]->OsiotDTO_taulu = paivita_osioiden_tilat($dto_taulukko, $dto_taulukko["LausuntoDTO"]->Lomakkeen_sivutDTO["lausunto_oletus"]->OsiotDTO_taulu, "lausunto_oletus", $yhteys);

			// front-end puolella json.parse herjaa erikoismerkeistä
			// temp ratkaisu => tyhjennetään osiotaulun sisällöt, koska front-end javascript puolella tarvitaan vain niiden tilatiedot
			$osiotDTO = $dto_taulukko["LausuntoDTO"]->Lomakkeen_sivutDTO["lausunto_oletus"]->OsiotDTO_taulu;
			
			if(is_array($osiotDTO) && !empty($osiotDTO)){
				foreach($osiotDTO as $fk_osio => $osioDTO){
					if(isset($osiotDTO[$fk_osio]->Osio_sisaltoDTO->Sisalto_text)) $osiotDTO[$fk_osio]->Osio_sisaltoDTO->Sisalto_text = "";			  			
				}
			}
			
			$dto_taulukko["LausuntoDTO"]->Lomakkeen_sivutDTO["lausunto_oletus"]->OsiotDTO_taulu = $osiotDTO;
			
			return muodosta_dto($dto_taulukko);

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}


    /**
     * @WebMethod
     * @desc x
     * @param object $paatosDTO
     * @param array $sitoumuksetDTO
     * @param object $hakijaDTO
     * @return string[] $pdf_content
     */
    public function lausunto_pdf($lausuntoDTO, $hakemusDTO, $kieli) {

		if($kieli=="en" || kieli=="fi"){
			include_once sprintf(LANGUAGE_FILES_BASE, $kieli);
		} else {
			include_once("lang_fi.php"); // Oletuskieli on suomi
		}	
	
        // template data, used in the template to loop through
        $data = array(
            'Document_date' => date_format(date_create($lausuntoDTO->Lisayspvm), 'd.m.Y'),
            'Tutkimuksen_nimi' => $hakemusDTO->HakemusversioDTO->Tutkimuksen_nimi,
			'Diaarinumero' => $hakemusDTO->AsiaDTO->Diaarinumero,
            'Lausunnonantaja_nimi' => $lausuntoDTO->LausuntopyyntoDTO->KayttajaDTO_Antaja->Etunimi . " " . $lausuntoDTO->LausuntopyyntoDTO->KayttajaDTO_Antaja->Sukunimi,
            'Viranomaisen_koodi' => $lausuntoDTO->LausuntopyyntoDTO->KayttajaDTO_Antaja->Viranomaisen_rooliDTO->Viranomaisen_koodi,
			'Johtopaatoksen_perustelut' => $lausuntoDTO->Johtopaatoksen_perustelut,
			'Ehdollinen_puoltaminen' => $lausuntoDTO->Ehdollinen_puoltaminen,
			
			'laus_kylla_checked' => false,
			'laus_ehto_checked' => false,
			'laus_ei_checked' => false,

			'Vastuuorganisaatiot' => array(),
			'liitteet' => array(),

        );

		if ($lausuntoDTO->Lausunto_koodi == "laus_kylla") $data['laus_kylla_checked'] = true;
		if ($lausuntoDTO->Lausunto_koodi == "laus_ehto") $data['laus_ehto_checked'] = true;
		if ($lausuntoDTO->Lausunto_koodi == "laus_ei") $data['laus_ei_checked'] = true;

		if (is_array($hakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO)) {
			foreach ($hakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO as $organisaatio) {
				$data['Vastuuorganisaatiot'][] = $organisaatio->Nimi;
			}
		}

		if (is_array($lausuntoDTO->Lausunnon_liitteetDTO)) {

			for ($i=0; $i<count($lausuntoDTO->Lausunnon_liitteetDTO); $i++) {

				if (
					isset($lausuntoDTO->Lausunnon_liitteetDTO[$i]->LiiteDTO->Liitetiedosto_nimi) &&
					$lausuntoDTO->Lausunnon_liitteetDTO[$i]->LiiteDTO->Liitetiedosto_nimi != ''
				) {
					$data['liitteet'][] = $lausuntoDTO->Lausunnon_liitteetDTO[$i]->LiiteDTO->Liitetiedosto_nimi;
				}

			}
		}

		$pdf_osiot_param = array();
		$pdf_osiot_param["kieli"] = $kieli;
		$pdf_osiot_param["osiotDTO_taulu"] = $lausuntoDTO->Lomakkeen_sivutDTO["lausunto_oletus"]->OsiotDTO_taulu;			
		
		$data['titles'] = osiot_pdf_rakenteeseen($pdf_osiot_param);

        $pdf_content = self::wrap_pdf($data, "", "lausunto");
		$pdf_content = 	base64_encode($pdf_content);

        return $pdf_content;
    }

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function hae_saapuneet_viestit($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"hae_saapuneet_viestit",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function avaa_liitetiedosto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("avattava_liite_id", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"avaa_liitetiedosto",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function peruuta_hakemus($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "hakemusversio_id"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"peruuta_hakemus",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_hakemus($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "hakemusversio_id"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"poista_hakemus",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_hakija_tutkimusryhmasta($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "hakemusversio_id", "poistettavan_kayttaja_id"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"poista_hakija_tutkimusryhmasta",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_viranomaiskohtainen_kentta($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("poistaja_id", "token", "fk_viranomaiskohtaiset"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"poista_viranomaiskohtainen_kentta",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_osio($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("data", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"poista_osio",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_lomakkeen_saanto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("data", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"poista_lomakkeen_saanto",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_liitetyyppi($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("data", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"poista_liitetyyppi",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_lomakkeen_sivu($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("data", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"poista_lomakkeen_sivu",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_lomake($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("lomake_id", "kayt_id", "token"))){
			return suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"poista_lomake",$syoteparametrit);
		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_hakemusversion_liitetiedosto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "hakemusversio_id", "liite_id"))){
			$dto = suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"poista_hakemusversion_liitetiedosto",$syoteparametrit);
			$dto_taulukko = dto_taulukkomuotoon($dto);

			// Lisätään liitteen poisto lokiin
			if(isset($dto_taulukko["Liitetiedosto_poistettu"]) && $dto_taulukko["Liitetiedosto_poistettu"]){
				suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"kirjaa_lokiin",muodosta_dto(array("pohja_rooli"=>$syoteparametrit_taulukko["pohja_rooli"], "liite_id"=>$syoteparametrit_taulukko["liite_id"], "toiminto"=>"liite_poisto", "hakemusversio_id"=>$syoteparametrit_taulukko["hakemusversio_id"], "kayt_id"=>$syoteparametrit_taulukko["kayt_id"])));
			}
			return $dto;

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}
	
	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_paatoksen_liitetiedosto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "paatos_id", "liite_id"))){
			$dto = suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"poista_paatoksen_liitetiedosto",$syoteparametrit);
			$dto_taulukko = dto_taulukkomuotoon($dto);			
			return $dto;

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}

	/**
	 * @WebMethod
	 * @desc x
	 * @param string[] $syoteparametrit
	 * @return string[] $dto
	 */
	public function poista_lausunnon_liitetiedosto($syoteparametrit){
		if($syoteparametrit_taulukko = pakollisia_parametreja_puuttuu($syoteparametrit, array("kayt_id", "token", "lausunto_id", "liite_id"))){
			$dto = suorita_datakerroksen_funktio($this->yhdista_datakerrokseen(),"poista_lausunnon_liitetiedosto",$syoteparametrit);
			$dto_taulukko = dto_taulukkomuotoon($dto);			
			return $dto;

		} else {
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja puuttuu.");
		}
	}	

	private function yhdista_datakerrokseen() {
		// create an instance of SOAP client
		$options = array('location' => FMAS_DATASOURCE_URL,
				'trace' => 1,
				'cache_wsdl' => WSDL_CACHE_NONE);
		$data = new SoapClient(FMAS_DATASOURCE_URL."?wsdl", $options);
		return $data;
	}
}

?>