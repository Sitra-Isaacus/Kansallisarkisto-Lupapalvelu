<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Lausunnonantajan käyttöliittymä (lausunto)
 *
 * Created: 28.1.2016
 */

include_once '_fmas_ui.php';  
 
$kayt_id = $_SESSION["kayttaja_id"];
$sivu = "lausunto_oletus";

if(kayttaja_on_kirjautunut()){
	if( (isset($_GET['hakemus_id']) || isset($_POST['hakemus_id']) ) && ( isset($_GET['lausunto_id']) || isset($_POST['lausunto_id'])) ) {		
		if(isset($_GET['hakemus_id'])) $hakemus_id = htmlspecialchars($_GET['hakemus_id']);	
		if(isset($_POST['hakemus_id'])) $hakemus_id = htmlspecialchars($_POST['hakemus_id']);	
		if(isset($_GET['lausunto_id'])) $lausunto_id = htmlspecialchars($_GET['lausunto_id']);
		if(isset($_POST['lausunto_id'])) $lausunto_id = htmlspecialchars($_POST['lausunto_id']);	
	} else {
		header("location: lausunnonantaja_saapuneet_lausuntopyynnot.php");								
		die();		
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}

if(isset($_GET["lahetys_epaonnistui"])) $huomio_punainen = poista_erikoismerkit($_GET["lahetys_epaonnistui"]);
if(isset($_GET["lausunto_lahetetty"])) $huomio_vihrea = poista_erikoismerkit($_GET["lausunto_lahetetty"]);
if(isset($_GET["fail"])) $huomio_punainen = poista_erikoismerkit($_GET["fail"]);
if(isset($_GET["success"])) $huomio_vihrea = poista_erikoismerkit($_GET["success"]);
	
try {
	if ($api = createSoapClient()) {
		
		if(isset($_POST)) $_POST = poista_erikoismerkit($_POST);
		
		// Tallennetaan liitetiedosto
		if(isset($_POST['lisaa_liite_asiakirja'])) { 
			if(file_exists($_FILES["lisaa_liite"]["tmp_name"])){
				
				$tiedosto = file_get_contents($_FILES["lisaa_liite"]["tmp_name"]);
				$tiedosto_encoded = base64_encode($tiedosto);

				// Check file size
				if ($_FILES["lisaa_liite"]["size"] > MAKSIMI_LIITETIEDOSTON_KOKO){
					$huomio_punainen = "Tiedosto on liian suuri.";
				} else {
							
					$tiedostonTallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_lausunnon_liitetiedosto", array("name"=>$_FILES["lisaa_liite"]["name"], "lausunto_id"=>$lausunto_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id, "tiedosto"=>$tiedosto_encoded));

					if(isset($tiedostonTallennus["Liitetiedosto_tallennettu"]) && $tiedostonTallennus["Liitetiedosto_tallennettu"]){
						$huomio_vihrea = LIITE_TALLENNETTU;
						header("location: lausunto.php?success=" . $huomio_vihrea . "&hakemus_id=" . $hakemus_id . "&lausunto_id=" . $lausunto_id . "");								
						die();																				
					} else {
						$huomio_punainen = $tiedostonTallennus["Liitetiedoston_tallennus_info"];									
						header("location: lausunto.php?fail=" . $huomio_punainen . "&hakemus_id=" . $hakemus_id . "&lausunto_id=" . $lausunto_id . "");								
						die();												
					}	
							
				}
																									
			}
		}		
		
		if (isset($_GET['poista_liite']) && isset($_GET['lausunto_id']) && isset($_GET['liite_id'])) {

			$poisto = suorita_logiikkakerroksen_funktio($api, "poista_lausunnon_liitetiedosto", array("pohja_rooli"=>$_SESSION["kayttaja_rooli"], "liite_id"=>$_GET["liite_id"], "lausunto_id"=>$_GET['lausunto_id'], "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));

			if(isset($poisto["Liitetiedosto_poistettu"]) && $poisto["Liitetiedosto_poistettu"]){
				$huomio_vihrea = LIITE_POISTETTU;
				header("location: lausunto.php?success=" . $huomio_vihrea . "&hakemus_id=" . $hakemus_id . "&lausunto_id=" . $lausunto_id . "");								
				die();					
			} else {
				$huomio_punainen = LIITE_POIST_FAIL;												
				header("location: lausunto.php?fail=" . $huomio_punainen . "&hakemus_id=" . $hakemus_id . "&lausunto_id=" . $lausunto_id . "");								
				die();					
			}
			
		}		
		
		if (isset($_POST['tallennuskohde']) && isset($_POST['tallennuskohde_id'])){ // Tallennetaan lomake 
		
			$responseToJSONarray = array();
			
			$tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_lausunto_lomake", array("hakemus_id"=>$hakemus_id,"lausunto_id"=>$lausunto_id,"kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));	
			
			$lausuntoDTO = $tallennus["LausuntoDTO"];
						
			if(isset($lausuntoDTO->Lomakkeen_sivutDTO["lausunto_oletus"]->OsiotDTO_taulu)) $responseToJSONarray["osiotDTO"] = $lausuntoDTO->Lomakkeen_sivutDTO["lausunto_oletus"]->OsiotDTO_taulu;												
			if(isset($lausuntoDTO->Lomakkeen_sivutDTO->Lomakkeen_sivutDTO)) $responseToJSONarray["nakymatDTO"] = $lausuntoDTO->Lomakkeen_sivutDTO->Lomakkeen_sivutDTO;						
			if(isset($tallennus["Lausunto_tallennettu"])) $responseToJSONarray["tallennusOnnistui"] = true;
			 						
			echo json_encode($responseToJSONarray);				
		
		} else if(isset($_POST['laheta_lausunto'])){
			if(isset($_POST["johtopaatos"])){
					
				$_POST["tallennuskohde_id"] = $lausunto_id;
				$_POST["tallennuskohde"] = "lausunto";
				$_POST["tallennuskohde_kentta"] = "Lausunto_julkaistu";
				$_POST["tallennuskohde_arvo"] = 1;
					
				$tallennus = suorita_logiikkakerroksen_funktio($api, "tallenna_lausunto_lomake", array("hakemus_id"=>$hakemus_id,"lausunto_id"=>$lausunto_id,"kayttajan_rooli"=>$_SESSION["kayttaja_rooli"], "data"=>$_POST, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$_SESSION["kayttaja_id"]));	
				
				if(isset($tallennus["Lausunto_tallennettu"])){
					$huomio_vihrea = LAUS_LAH;
					header("location: lausunto.php?lausunto_lahetetty=" . $huomio_vihrea . "&hakemus_id=" . $hakemus_id . "&lausunto_id=" . $lausunto_id . "");								
					die();						
				} else {
					$huomio_punainen = LAUS_LAH_FAIL;
					header("location: lausunto.php?lahetys_epaonnistui=" . $huomio_punainen . "&hakemus_id=" . $hakemus_id . "&lausunto_id=" . $lausunto_id . "");								
					die();						
				}
				
			} else {
				$huomio_punainen = LAUS_LAH_FAIL . " " . VAL_LAUS_JP;
				header("location: lausunto.php?lahetys_epaonnistui=" . $huomio_punainen . "&hakemus_id=" . $hakemus_id . "&lausunto_id=" . $lausunto_id . "");								
				die();						
			}		
		} else {
					
			$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_lausunto", array("lausunto_id"=>$lausunto_id, "hakemus_id"=>$hakemus_id, "token"=>$_SESSION["kayttaja_token"], "kayt_id"=>$kayt_id));
			
			$hakemusDTO = $vastaus["HakemusDTO"];
			$tutkimus_id = $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID;
			$hakemusversio_id = $hakemusDTO->HakemusversioDTO->ID;			
			$lausuntoDTO = $vastaus["LausuntoDTO"];
			$lomakkeetDTO_lausunto = $vastaus["LomakkeetDTO_Lausunto"];
			$hakemusversioDTO = $hakemusDTO->HakemusversioDTO;
			if(isset($vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"])) $Tutkimuksen_viranomaisen_hakemuksetDTO = $vastaus["Tutkimuksen_viranomaisen_hakemuksetDTO"];
			
		}
				
	} 
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

if (!isset($_POST['tallennuskohde']) && !isset($_POST['tallennuskohde_id'])){
	include 'helper_functions.php';
	include './ui/views/lausunto_view.php';
}

?>