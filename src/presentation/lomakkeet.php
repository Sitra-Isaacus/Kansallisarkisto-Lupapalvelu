<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Lomakkeet (pääkäyttäjä)
 *
 * Created: 4.7.2016
 */

include_once '_fmas_ui.php';  

$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){
	
	try {
		if ($api = createSoapClient()) {

			if(isset($_POST["luo_lomake"])){
				
				$tallennus = suorita_logiikkakerroksen_funktio($api, "luo_lomake", array("token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
		
				if(isset($tallennus["Uusi_lomakeDTO"]->ID)){
					$header = "Location: lomake_perustiedot.php?lomake_id=" . $tallennus["Uusi_lomakeDTO"]->ID . "";
					header($header);
					die();				
				}
				
			}
			
			if(isset($_GET["poista_lomake"])){			
				$poisto = suorita_logiikkakerroksen_funktio($api, "poista_lomake", array("lomake_id"=>$_GET["poista_lomake"],"token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));					
			}
		
			$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_lomakkeet", array("token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
			$lomakkeetDTO = $vastaus["LomakkeetDTO"];
			
		} 
	} catch (SoapFault $ex) {
		header("Location: kirjaudu.php?ei_kirjauduttu=1");
		die();	
	}

	include 'helper_functions.php';
	include './ui/views/lomakkeet_view.php';

} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}	
	
?>