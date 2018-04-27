<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Lomakkkeen sivun tiedot
 *
 * Created: 24.4.2017
 */

include_once '_fmas_ui.php'; 


$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){
	if( ( isset($_GET['lomake_id']) || isset($_POST['lomake_id']) ) && (isset($_GET['lomake_sivu_id']) || isset($_POST['lomake_sivu_id'])) ){	
		if(isset($_GET['lomake_id'])) $lomake_id = htmlspecialchars($_GET['lomake_id']); if(isset($_POST['lomake_id'])) $lomake_id = htmlspecialchars($_POST['lomake_id']); 	 
		if(isset($_GET['lomake_sivu_id'])) $lomake_sivu_id = htmlspecialchars($_GET['lomake_sivu_id']); if(isset($_POST['lomake_sivu_id'])) $lomake_sivu_id = htmlspecialchars($_POST['lomake_sivu_id']);  		
	} else {
		header("location: lomakkeet.php");								
		die();		
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}
	
try {
	if ($api = createSoapClient()) {
		
		if(isset($_POST["poista_liitetyyppi"])){ 
		
			$_POST = poista_erikoismerkit($_POST);
			
			$liite_poisto = suorita_logiikkakerroksen_funktio($api, "poista_liitetyyppi", array("data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
		
			$header = $header = "location: lomake_sivu.php?lomake_sivu_id=" . $lomake_sivu_id . "&lomake_id=" . $lomake_id;
			header($header);								
			die();			
		
		}		
		
		if(isset($_POST["tallenna_liitetyypit"])){ 
		
			$_POST = poista_erikoismerkit($_POST);
			
			$liite_tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_liitetyyppi", array("data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
		
			$header = $header = "location: lomake_sivu.php?lomake_sivu_id=" . $lomake_sivu_id . "&lomake_id=" . $lomake_id;
			header($header);								
			die();			
		
		}
		
		if(isset($_POST["lisaa_uusi_liitetiedosto"])){
			
			$_POST = poista_erikoismerkit($_POST);
			
			$liite_lisays = suorita_logiikkakerroksen_funktio($api, "lisaa_liitetyyppi", array("data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
			
			$header = $header = "location: lomake_sivu.php?lomake_sivu_id=" . $lomake_sivu_id . "&lomake_id=" . $lomake_id;
			header($header);								
			die();	
			
		}
		
		if(isset($_POST["tallenna_lomake"]) || isset($_POST["lisaa_uusi_kokonaisuus"]) || isset($_POST["lisaa_uusi_kysymys"])){
		
			$tallennus_koodi = null;
			
			if(isset($_POST["lisaa_uusi_kysymys"]) && isset($_POST["Osio_parent"])){				
				$tallennus_koodi = "uusi_kysymys";				
			} 
			
			if(isset($_POST["lisaa_uusi_kokonaisuus"]) && isset($_POST["uusi_kokonaisuus"])){
			
				// Tarkistetaan puuttuvat tiedot
				if($_POST["uusi_kokonaisuus"]["Jarjestys"]=="" || $_POST["uusi_kokonaisuus"]["Nimi"]==""){
					$huomio_punainen = "Täytä pakolliset tiedot.";
					$header = "location: lomake_sivu.php?lomake_sivu_id=" . $lomake_sivu_id . "&lomake_id=" . $lomake_id;
					header($header);								
					die();					
				}
				
				$tallennus_koodi = "uusi_kokonaisuus";
				
			} 
			
			if(isset($_POST["tallenna_lomake"])) $tallennus_koodi = "lomake_paivitys";
							
			$_POST = poista_erikoismerkit($_POST);
			
			//echo "<pre>";	
			//print_r($_POST);
			//echo "</pre>";

			$tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_lomake_sivu", array("tallennus_koodi"=>$tallennus_koodi, "data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));											
			
			if(isset($tallennus["Virheilmoitus"]["Tietoja_puuttuu"]) && $tallennus["Virheilmoitus"]["Tietoja_puuttuu"]){
				$huomio_punainen = "Täytä pakolliset tiedot.";			
			} 			
			
			//$header = $header = "location: lomake_sivu.php?lomake_sivu_id=" . $lomake_sivu_id . "&lomake_id=" . $lomake_id;
			//header($header);								
			//die();				
			
		}		
	
		if(isset($_POST["poista_osio"])){
			
			$_POST = poista_erikoismerkit($_POST);
			
			$osio_poisto = suorita_logiikkakerroksen_funktio($api, "poista_osio", array("data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));

			$header = $header = "location: lomake_sivu.php?lomake_sivu_id=" . $lomake_sivu_id . "&lomake_id=" . $lomake_id;
			header($header);								
			die();	
			
		}
	
		$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_lomake", array("lomake_id"=>$lomake_id,"token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));				
		$lomakeDTO = $vastaus["LomakeDTO"];
		$koodistotDTO_organisaatiot = $vastaus["KoodistotDTO_viranomaiset"];
		
		foreach($lomakeDTO->Lomakkeen_sivutDTO as $tunniste => $lomake_sivuDTO) {
			if($lomake_sivuDTO->ID==$lomake_sivu_id){
				$haettu_lomake_sivuDTO = $lomake_sivuDTO;
			}
		}
		
	} 
} catch (SoapFault $ex) {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();	
}

include 'helper_functions.php';
include './ui/views/lomake_sivu_view.php';
	
?>