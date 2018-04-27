<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Omat tiedot
 *
 * Created: 8.4.2016
 */

include_once '_fmas_ui.php'; 

if(isset($_GET["kieli"])) $_SESSION["kayttaja_kieli"] = poista_erikoismerkit($_GET["kieli"]);	
if(isset($_GET["tallennettu_nimi"])) $_SESSION["kayttaja_nimi"] = urldecode($_GET["tallennettu_nimi"]);	

if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);
if(isset($_GET)) $_GET = poista_erikoismerkit($_GET);
 
if(kayttaja_on_kirjautunut()){

	session_write_close();		
 
	try {
		if ($api = createSoapClient()) {
							
			if(isset($_POST['tallenna_omat_tiedot'])){
				
				$tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_kayttajan_tiedot", array("data"=>$_POST, "token"=>$_SESSION['kayttaja_token'], "kayt_id"=>$_SESSION['kayttaja_id']));
				
				if(isset($tallennus["kayttajan_tiedot_tallennettu"]) && $tallennus["kayttajan_tiedot_tallennettu"]){				
					$huomio_vihrea = TALLENNETTU_TALL;											
				} else {
					$huomio_punainen = TIEDOT_EI_TALLENNETTU;
				}
					
			}
					
			$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_kayttajan_tiedot", array("roolin_koodi"=>$_SESSION['kayttaja_rooli'], "token"=>$_SESSION['kayttaja_token'], "kayt_id"=>$_SESSION['kayttaja_id']));
			$kayttaja = $vastaus["KayttajaDTO"]["Omat_tiedot"];
			
			if(isset($tallennus["kayttajan_tiedot_tallennettu"]) && $tallennus["kayttajan_tiedot_tallennettu"]){			
				header('Location: omat_tiedot.php?tallennettu_nimi=' . urlencode($kayttaja->Etunimi) . ' ' . urlencode($kayttaja->Sukunimi) . '&kieli=' . $kayttaja->Kieli_koodi . '');
				die();							
			}
					
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}

	include './ui/views/omat_tiedot_view.php';

} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}	
	
?>