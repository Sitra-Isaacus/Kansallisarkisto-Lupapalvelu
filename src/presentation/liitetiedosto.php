<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Liitetiedoston avaaminen
 *
 * Created: 15.12.2016
 */
 
include_once '_fmas_ui.php';
  
if(kayttaja_on_kirjautunut()){	

	try {
		if ($api = createBinarySoapClient()) {														
			if (isset($_GET['avaa'])) {

				$_GET = poista_erikoismerkit($_GET);
			
				$vastaus = suorita_logiikkakerroksen_funktio($api, "avaa_liitetiedosto", array("avattava_liite_id"=>$_GET['avaa'], "token"=>$_SESSION['kayttaja_token'], "kayt_id"=>$_SESSION['kayttaja_id']));
			
				if(isset($vastaus["Avattu_liiteDTO"]->Liitetiedosto_blob)){
					$tiedostomuoto = $vastaus["Avattu_liiteDTO"]->Tiedostomuoto;
					$liitetiedosto = base64_decode($vastaus["Avattu_liiteDTO"]->Liitetiedosto_blob);
				} else {
					header('Location: virhe.php?virhe=Liitetiedostoa ei löydy tai siihen ei ole pääsyoikeutta');
					die();			
				}
											
			}												
		} 
	} catch (SoapFault $ex) {
		header('Location: virhe.php?virhe=' . $ex->getMessage());
		die();
	}

	if($tiedostomuoto=="pdf"){
		header("Content-Type: application/pdf");	
	} else if($tiedostomuoto=="jpg"){
		header("Content-type: image/jpeg");
	} else if($tiedostomuoto=="txt"){
		header("Content-type: text/html");		
	} else if($tiedostomuoto=="wpd"){
		header("Content-type: application/wordperfect");	
	} else if($tiedostomuoto=="xlsx"){ 
		header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	} else if($tiedostomuoto=="xls"){ 
		header("Content-type: application/vnd.ms-excel");
	} else if($tiedostomuoto=="docx"){ 	
		header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
	} else if($tiedostomuoto=="doc"){ 	
		header("Content-type: application/msword");
	} else if($tiedostomuoto=="rtf"){ 
		header("Content-type: application/rtf");
	} else if($tiedostomuoto=="png"){ 
		header("Content-type: image/png");		
	} else {		
		header('Location: virhe.php?virhe=Sallitut tiedostotyypit ovat: png, pdf, jpg ja txt.');
		die();		
	}

	header("Content-Disposition: download; filename=tiedosto." . $tiedostomuoto . "");
	header("Content-length: ".strlen($liitetiedosto));
	
	//if($tiedostomuoto=="png"){

	//	$im = imagecreatefromstring($liitetiedosto);
	//	if ($im !== false) {		
	//		imagepng($im);
	//		imagedestroy($im);
	//	}
	
	//} else {

		echo $liitetiedosto;		
	//}

	
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}	

?>