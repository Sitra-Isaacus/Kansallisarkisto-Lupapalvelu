<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Viestit
 *
 * Created: 12.1.2016
 */

include_once '_fmas_ui.php';  

$kayt_id = $_SESSION["kayttaja_id"];
$taydennysasiakirjat = array();

if(kayttaja_on_kirjautunut()){
	if(isset($_GET['hakemus_id']) || isset($_POST['hakemus_id'])) {	
		if(isset($_GET['hakemus_id'])) $hakemus_id = htmlspecialchars($_GET['hakemus_id']);	
		if(isset($_POST['hakemus_id'])) $hakemus_id = htmlspecialchars($_POST['hakemus_id']);			
	} else {
		header("location: index.php");								
		die();		
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}
	
try {	
	if ($api = createSoapClient()) {
	
		if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);

		if(isset($_FILES["taydennysasiakirjat"])){							
			for($i=0; $i < sizeof($_FILES["taydennysasiakirjat"]["tmp_name"]); $i++){
				
				$taydennysasiakirja = array();
				
				$taydennysasiakirja["file"] = base64_encode(file_get_contents($_FILES["taydennysasiakirjat"]["tmp_name"][$i]));
				$taydennysasiakirja["name"] =  $_FILES["taydennysasiakirjat"]["name"][$i];
				
				if ($_FILES["taydennysasiakirjat"]["size"][$i] > MAKSIMI_LIITETIEDOSTON_KOKO) { 
					$huomio_punainen = "Tiedosto " . $_FILES["taydennysasiakirjat"]["name"][$i] . " on liian suuri.";
					header("location: hakemus_viestit.php?hakemus_id=" . $hakemus_id . "");
					die();						
				}
				
				array_push($taydennysasiakirjat, $taydennysasiakirja);
				
			}			
		}
					
		// Lähetetään vastaus
		if (isset($_POST['laheta_vastaus'])) {
											
			if(!isset($_POST['vastaus']) || $_POST['vastaus']==""){
				header("location: hakemus_viestit.php?hakemus_id=" . $hakemus_id . "");
				$huomio_punainen = TYHJA_VIESTI;
				die();				
			}

			$vastaus = suorita_logiikkakerroksen_funktio($api, "laheta_viesti", array("taydennysasiakirjat"=>$taydennysasiakirjat, "data"=>$_POST, "on_vastaus"=>true, "hakemus_id"=>$_POST["hakemus_id"], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id));
			
			if(isset($vastaus["Viesti_lahetetty"]) && $vastaus["Viesti_lahetetty"]){
				if(!empty($taydennysasiakirjat)){
					$huomio_vihrea = TAYDASK_TOIM;
				} else {
					$huomio_vihrea = VIESTI_LAH;
				}				
			} else {
				$huomio_punainen = VIESTI_LAH_FAIL;
			}			
				 
		}	
	
		// Haetaan viestit
		$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_hakemuksen_viestit_tutkijalle", array("hakemus_id"=>$hakemus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id));
		$viestit = $vastaus["ViestitDTO"];
							
	}				 
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

include './ui/views/hakemus_viestit_view.php';

?>