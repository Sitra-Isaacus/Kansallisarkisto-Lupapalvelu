<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: login page
 *
 * Created: 9.10.2015
 */

include_once '_fmas_ui.php';

// Ilmoitustekstit
if(!isset($_SESSION["kayttaja_id"])) $huomio_sininen = KIRJ_TUN_SAL;
if(isset($_GET["kirjauduttu_ulos"])) $huomio_sininen = OLET_KIRJ_ULOS;
if(isset($_GET["tunnus_ja_pw_pakollisia"])) $huomio_punainen = TUN_SAL_PAKK;
if(isset($_GET["kirjautuminen_epaonnistui"])) $huomio_punainen = KIRJAUTUMINEN_EPAONNISTUI;
if(isset($_GET["ei_kirjauduttu"])) $huomio_punainen = ET_OLE_KIRJ;

function kirjautuminen_epaonnistui(){
	session_unset();
	header('Location: kirjaudu.php?kirjautuminen_epaonnistui=1');
	die();		
}

if (isset($_POST['kirjaudu'])) {
	
	$tun = strtolower(tarkista($_POST['tunnus']));
	$sala = tarkista($_POST['salasana']);

	if (strlen($tun) == 0 || strlen($sala) == 0) {
		header('Location: kirjaudu.php?tunnus_ja_pw_pakollisia=1');
		die();
	}
		
	try {
		if ($api = createSoapClient()) {
					
			if($vastaus = suorita_logiikkakerroksen_funktio($api, "kirjaudu_lupapalveluun", array("salasana"=>$sala, "sahkopostiosoite"=>$tun))){		

				if(isset($vastaus["KayttajaDTO"]->SuojausDTO->Suojaustunnus) && isset($vastaus["KayttajaDTO"]->ID) && isset($vastaus["KayttajaDTO"]->Sahkopostiosoite) && $vastaus["KayttajaDTO"]->Sahkopostiosoite==$tun){
						
					session_start();	
					
					$kayttajaDTO = $vastaus["KayttajaDTO"];
					$_SESSION["kayttaja_roolit"] = array();
					$_SESSION["kayttaja_viranomainen"] = null;	
																
					if(isset($kayttajaDTO->Viranomaisen_roolitDTO) && !empty($kayttajaDTO->Viranomaisen_roolitDTO)){ // Käyttäjä on viranomainen 						
						if(sizeof($kayttajaDTO->Viranomaisen_roolitDTO) > 1){ // Viranomaisella on useita rooleja													
														
							$_SESSION["kayttaja_rooli"] = $kayttajaDTO->Viranomaisen_roolitDTO[0]->Viranomaisroolin_koodi; // Oletusrooli
							$_SESSION["kayttaja_viranomainen"] = $kayttajaDTO->Viranomaisen_roolitDTO[0]->Viranomaisen_koodi;
							
							for($i=0; $i < sizeof($kayttajaDTO->Viranomaisen_roolitDTO); $i++){
								$_SESSION["kayttaja_roolit"][$i]["Viranomaisroolin_koodi"] = $kayttajaDTO->Viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi;
								$_SESSION["kayttaja_roolit"][$i]["Viranomaisen_koodi"] = $kayttajaDTO->Viranomaisen_roolitDTO[$i]->Viranomaisen_koodi;																							
							}
							
							
						} else { // Viranomaisella on yksi rooli
												
							$_SESSION["kayttaja_rooli"] = $kayttajaDTO->Viranomaisen_roolitDTO[0]->Viranomaisroolin_koodi; // Oletusrooli 
							$_SESSION["kayttaja_viranomainen"] = $kayttajaDTO->Viranomaisen_roolitDTO[0]->Viranomaisen_koodi;	
							$_SESSION["kayttaja_roolit"][0]["Viranomaisroolin_koodi"] = $kayttajaDTO->Viranomaisen_roolitDTO[0]->Viranomaisroolin_koodi;
							$_SESSION["kayttaja_roolit"][0]["Viranomaisen_koodi"] = $kayttajaDTO->Viranomaisen_roolitDTO[0]->Viranomaisen_koodi;
														
						}
						
					} else { // Käyttäjä ei ole viranomainen						
						if(isset($kayttajaDTO->Paakayttajan_rooliDTO) && isset($kayttajaDTO->Paakayttajan_rooliDTO->ID)){ // Käyttäjä on pääkäyttäjä	
						
							$_SESSION["kayttaja_rooli"] = $kayttajaDTO->Paakayttajan_rooliDTO->Paakayttajaroolin_koodi;	
							
						} else { // Käyttäjä on tutkija
						
							$_SESSION["kayttaja_rooli"] = "rooli_hakija";
						
						}						
					}
										
					$_SESSION["kayttaja_id"] = $kayttajaDTO->ID;
					$_SESSION["kayttaja_token"] = $kayttajaDTO->SuojausDTO->Suojaustunnus;
					$_SESSION["kayttaja_kieli"] = $kayttajaDTO->Kieli_koodi;
					$_SESSION["kayttaja_email"] = $kayttajaDTO->Sahkopostiosoite;
					$_SESSION["kayttaja_nimi"] = $kayttajaDTO->Etunimi . " " .	$kayttajaDTO->Sukunimi;					
					$_SESSION["kayttaja_uudet_viestit_kpl"] = $kayttajaDTO->Lukemattomien_viestien_maara;
					$_SESSION["kayttaja_eraantyvat_kayttoluvat_kpl"] = $kayttajaDTO->Eraantyvien_kayttolupien_maara;
					$_SESSION["kayttaja_lukemattomat_lausunnot_kpl"] = $kayttajaDTO->Lukemattomien_lausuntojen_maara;
					
					session_write_close();
					
					// Lisätään kirjautuminen lokiin
					suorita_logiikkakerroksen_funktio($api, "kirjaa_lokiin", array("kayt_id"=>$kayttajaDTO->ID, "toiminto"=>"in", "pohja_rooli"=>$_SESSION["kayttaja_rooli"]));	
										
					if($_SESSION["kayttaja_rooli"] == "rooli_hakija"){ // Käyttäjä on hakija											
						header('Location: index.php?kirjauduttu_sisaan=1');
						die();
					} else { // Jos käyttäjä on viranomainen niin se ohjataan viranomaisen käyttöliittymään
						
						// Viranomainen
						if($_SESSION["kayttaja_rooli"] == "rooli_eettisen_puheenjohtaja" || $_SESSION["kayttaja_rooli"] == "rooli_eettisensihteeri" || $_SESSION["kayttaja_rooli"] == "rooli_kasitteleva" || $_SESSION["kayttaja_rooli"] == "rooli_paattava"){
							header('Location: viranomainen_saapuneet_hakemukset.php?kayttajarooli=' . $_SESSION["kayttaja_rooli"] . '&kirjauduttu_sisaan=1');
							die();							
						}
						
						// Lausunnonantaja
						if($_SESSION["kayttaja_rooli"] == "rooli_lausunnonantaja"){ 					
							header('Location: lausunnonantaja_saapuneet_lausuntopyynnot.php?kirjauduttu_sisaan=1');
							die();						
						}
						
						// Aineistonmuodostaja
						if ($_SESSION["kayttaja_rooli"] == "rooli_aineistonmuodostaja"){
							header('Location: aineistonmuodostaja_saapuneet_tilaukset.php?kirjauduttu_sisaan=1');
							die();						
						}
						
						// Pääkäyttäjä
						if ($_SESSION["kayttaja_rooli"] == "rooli_lupapalvelun_paak" || $_SESSION["kayttaja_rooli"] == "rooli_viranomaisen_paak"){
							header('Location: kayttajaroolit.php?kirjauduttu_sisaan=1&kayttajarooli=' . $_SESSION["kayttaja_rooli"] . '');
							die();						
						}					
					
					}						
					
				} else {
					kirjautuminen_epaonnistui();					
				}				
			} else {				
				kirjautuminen_epaonnistui();		
			}		
						
		}	
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}			
}

if (isset($_GET['ulos'])) {
	
	session_start();
		
	if(isset($_SESSION["kayttaja_id"])){	
		try {
			if ($api = createSoapClient()) { // Lisätään uloskirjautuminen lokiin									
				suorita_logiikkakerroksen_funktio($api, "kirjaa_lokiin", array("kayt_id"=>$_SESSION["kayttaja_id"], "toiminto"=>"out", "pohja_rooli"=>$_SESSION["kayttaja_rooli"]));									
			}	
		} catch (SoapFault $ex) {
			header('Location: virhe.php?virhe=' . $ex->getMessage());
			die();
		}
	}
	
	session_unset();	
	header('Location: kirjaudu.php?kirjauduttu_ulos=1');
	die();
	
}	

include './ui/views/kirjaudu_view.php';

?>