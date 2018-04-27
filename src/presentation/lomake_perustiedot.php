<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Lomakkkeen perustiedot
 *
 * Created: 24.4.2017
 */

include_once '_fmas_ui.php'; 

$kayt_id = $_SESSION["kayttaja_id"];

if(isset($_GET['lomake_id']) || isset($_POST['lomake_id'])){
	
	if(isset($_GET['lomake_id'])) $lomake_id = htmlspecialchars($_GET['lomake_id']); 
	if(isset($_POST['lomake_id'])) $lomake_id = htmlspecialchars($_POST['lomake_id']); 	
		
} else {
	header("location: lomakkeet.php");								
	die();		
}

if(kayttaja_on_kirjautunut()){
	try {
		if ($api = createSoapClient()) {

			if(isset($_POST["tallenna_lomake"]) || isset($_POST["lisaa_uusi_sivu"])){			
			
				$tallennus_koodi = null;
				
				if(isset($_POST["tallenna_lomake"])) $tallennus_koodi = "lomake_paivitys";
				
				if(isset($_POST["lisaa_uusi_sivu"]) && isset($_POST["uusi_sivu"])){

					if($_POST["uusi_sivu"]["Jarjestys"]=="" || $_POST["uusi_sivu"]["Sivun_tunniste"]=="" || $_POST["uusi_sivu"]["Sivun_nimi_fi"]=="" || $_POST["uusi_sivu"]["Sivun_nimi_en"]==""){
						$huomio_punainen = "Täytä pakolliset tiedot.";
						$header = "location: lomake_perustiedot.php?lomake_id=" . $lomake_id;
						header($header);								
						die();					
					}
					
					$tallennus_koodi = "uusi_sivu";
					
				}

				$_POST = poista_erikoismerkit($_POST);			
				$tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_lomake", array("tallennus_koodi"=>$tallennus_koodi, "data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));											
				
				//$header = "location: lomake_perustiedot.php?lomake_id=" . $lomake_id;
				//header($header);								
				//die();				
				
			}
			
			if(isset($_POST["poista_lomake_sivu"])){
				
				$_POST = poista_erikoismerkit($_POST);			
				$tallennus = suorita_logiikkakerroksen_funktio($api, "poista_lomakkeen_sivu", array("data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));											
				
				$header = "location: lomake_perustiedot.php?lomake_id=" . $lomake_id;
				header($header);								
				die();				
				
			}
		
			$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_lomake", array("lomake_id"=>$lomake_id,"token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));
			$lomakeDTO = $vastaus["LomakeDTO"];	
			
		} 
	} catch (SoapFault $ex) {
		header("Location: kirjaudu.php?ei_kirjauduttu=1");
		die();	
	}

	include 'helper_functions.php';
	include './ui/views/lomake_perustiedot_view.php';

} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}
	
?>