<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Rekisteröinti
 *
 * Created: 5.4.2016
 */

include_once '_fmas_ui.php'; 
 
try {
	if ($api = createSoapClient()) {
								
		if (isset($_POST['rekisteroidy'])) {
			
			$_POST = poista_erikoismerkit($_POST);
			
			if(empty($_POST['etunimi'])){
				$huomio_punainen = EI_KELPO_NIMI;			
			} else {
				$etunimi = tarkista($_POST['etunimi']);
			}
			
			if(empty($_POST['sukunimi'])){
				$huomio_punainen = EI_KELPO_NIMI;					
			} else {
				$sukunimi = tarkista($_POST['sukunimi']);
			}
						
			if (filter_var($_POST['sahkopostiosoite'], FILTER_VALIDATE_EMAIL)) {
				$sahkopostiosoite = tarkista($_POST['sahkopostiosoite']);
			} else {
				$huomio_punainen = EI_KELPO_SAHKOPOSTI;					
			}

			if($_POST['salasana']==$_POST['salasana_vahvistus']){
				$salasana = $_POST['salasana_vahvistus'];
				
			} else {
				$huomio_punainen = SALASANAT_EIVAT_TASMAA;					
			}
							
			if (strlen($salasana) < 8) {
				$huomio_punainen = SALASANA_LIIAN_LYHYT;					
			} elseif(!preg_match("#[0-9]+#",$salasana)) {
				$huomio_punainen = SALASANA_NUMERO_PUUTTUU;
			} elseif(!preg_match("#[A-Z]+#",$salasana)) {
				$huomio_punainen = SALASANA_ISO_KIRJAIN_PUUTTUU;				
			} elseif(!preg_match("#[a-z]+#",$salasana)) {
				$huomio_punainen = SALASANA_PIENI_KIRJAIN_PUUTTUU;	
			} else {
				$salasana = tarkista($salasana);
			}
				
			if(!isset($huomio_punainen)){
				
				$parametrit = array();
				$parametrit["sahkopostiosoite"] = $sahkopostiosoite;
				$parametrit["sukunimi"] = $sukunimi;	
				$parametrit["etunimi"] = $etunimi;	
				$parametrit["puhelinnro"] = tarkista($_POST['puhelin']);	
				$parametrit["asiointikieli"] = $_POST['asiointikieli'];
				$parametrit["salasana"] = $salasana;
				$parametrit["syntymaaika"] = tarkista($_POST['syntymaaika']);
				$parametrit["kayt_kieli"] = $parametrit["asiointikieli"];
				
				$vastaus = suorita_logiikkakerroksen_funktio($api, "rekisteroi_kayttaja", $parametrit);
				
				if(isset($vastaus["kayttaja_luotu"]) && $vastaus["kayttaja_luotu"]){
					$huomio_vihrea = KAYTTAJA_LUOTU;
				} else {
					$huomio_punainen = LUOMINEN_EPAONNISTUI . " " . $vastaus["rekisterointi_virheilmoitus"];
				}
			}
			
		}
						
	} 
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}
	
include './ui/views/rekisteroidy_view.php';

?>