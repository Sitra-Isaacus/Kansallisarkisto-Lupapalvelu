<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Lomakkkeen perustiedot
 *
 * Created: 24.4.2017
 */

include_once '_fmas_ui.php'; 

$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){
	if(isset($_GET['lomake_id']) || isset($_POST['lomake_id'])){
		
		if(isset($_GET['lomake_id'])) $lomake_id = htmlspecialchars($_GET['lomake_id']); 
		if(isset($_POST['lomake_id'])) $lomake_id = htmlspecialchars($_POST['lomake_id']); 	
			
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
	
		if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);	
	
		if(isset($_POST["uusi_riippuvuussaanto"]) || isset($_POST["tallenna_lomake"])){			
			
			if(isset($_POST["uusi_riippuvuussaanto"])){
				$tallennuskoodi = "uusi_riippuvuussaanto";
			} else {
				$tallennuskoodi = "saannon_paivitys";
			}

			$tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_lomakkeen_saanto", array("tallennuskoodi"=>$tallennuskoodi, "data"=>$_POST, "token"=>$_SESSION['kayttaja_token'], "kayt_id"=>$_SESSION['kayttaja_id']));											
			
			$header = "location: lomake_suhteet.php?lomake_id=" . $lomake_id;
			header($header);								
			die();				
			
		}	
		
		if(isset($_POST["poista_osio_saanto"])){
			
			$osio_poisto = suorita_logiikkakerroksen_funktio($api, "poista_lomakkeen_saanto", array("data"=>$_POST, "token"=>$_SESSION['kayttaja_token'], "kayt_id"=>$_SESSION['kayttaja_id']));

			$header = "location: lomake_suhteet.php?lomake_id=" . $lomake_id;
			header($header);								
			die();		
			
		}
	
		$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_lomake", array("lomake_id"=>$lomake_id,"token"=>$_SESSION['kayttaja_token'], "kayt_id"=>$_SESSION['kayttaja_id']));
		$lomakeDTO = $vastaus["LomakeDTO"];
		
	} 
} catch (SoapFault $ex) {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();	
}

include 'helper_functions.php';
include './ui/views/lomake_suhteet_view.php';
	
?>