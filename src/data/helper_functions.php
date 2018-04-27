<?php 

$interface = 'browser';
if (isset($_SERVER['TERM'])) $interface = $_SERVER['TERM'];

if (!function_exists('debug_log')) {

	function debug_log($obj)
	{

		global $interface;

		//file_put_contents("/tmp/debug_{$interface}.log", date("Ymd H:i:s")." ".$_SERVER['REQUEST_URI']."\n".$ob."\n", FILE_APPEND);

		if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == '192.168.104.250') {

			ob_start();
			print_r($obj);
			$ob = ob_get_contents();
			ob_end_clean();
			file_put_contents("/tmp/db_debug_{$interface}.log", date("Ymd H:i:s") . " " . $_SERVER['REQUEST_URI'] . "\n" . $ob . "\n", FILE_APPEND);

		}

	}

}

function muodosta_dto($muotoiltava_vastaus){

	$dto = array();

	foreach($muotoiltava_vastaus as $kentta => $arvo) { 
		$objekti = new stdClass();
		$objekti->$kentta = $arvo; 
		array_push($dto, $objekti);
	}

	return $dto;

}

function dto_taulukkomuotoon($syoteparametrit){
	$parametrit = array();

	for($i=0; $i < sizeof($syoteparametrit); $i++){
		if (is_object($syoteparametrit[$i])) { 
			$alempi_taulu = get_object_vars($syoteparametrit[$i]);
		} else {
			$alempi_taulu = $syoteparametrit[$i];
		}
		foreach($alempi_taulu as $kentta => $arvo) {
			$parametrit[$kentta] = $arvo;
		}

	}
	return $parametrit;
}

function muotoile_soap_parametrit($parametrit){
	
	$soap_parametrit = array();

	foreach($parametrit as $kentta => $arvo) { 
		$objekti = new stdClass();
		$objekti->$kentta = $arvo;
		array_push($soap_parametrit, $objekti);
	}
	
	return $soap_parametrit;
	
}

function parametrit_taulukkomuotoon($syoteparametrit){

	$parametrit = array();

	for($i=0; $i < sizeof($syoteparametrit); $i++){

		$objektin_muuttujat = get_object_vars($syoteparametrit[$i]);

		foreach($objektin_muuttujat as $kentta => $arvo) {
			$parametrit[$kentta] = dekoodaa_erikoismerkit($arvo);
		}
		
	}
	
	return $parametrit;

}

function dekoodaa_erikoismerkit($data){

	if(!is_null($data) && is_array($data)){
		foreach ($data as $key => $value) {
			if(is_array($value)){
				$data[$key] = dekoodaa_erikoismerkit($value);
			} else {
				$data[$key] = html_entity_decode($value, ENT_COMPAT, "UTF-8");
			}
		}
	} else {
		if(!is_null($data)){
			$data = html_entity_decode($data, ENT_COMPAT, "UTF-8");
		}
	}
	return $data;

}

function hae_tutkimuksen_uusimmat_hakemukset($db, $fk_tutkimus){
	
	$hakemusversioDAO = new HakemusversioDAO($db);
	$hakemusDAO = new HakemusDAO($db);
	$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
	$uusin_hakemusDTO = array();
	$uusin_versio = array();
	
	$hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_kaikki_hakemusversiot($fk_tutkimus);
	
	for($i=0; $i < sizeof($hakemusversiotDTO); $i++){
		
		$hakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset($hakemusversiotDTO[$i]->ID);
				
		for($j=0; $j < sizeof($hakemuksetDTO); $j++){	
		
			if(!isset($uusin_versio[$hakemuksetDTO[$j]->Viranomaisen_koodi])) $uusin_versio[$hakemuksetDTO[$j]->Viranomaisen_koodi] = $hakemusversiotDTO[$i]->Versio;
			
			if($hakemusversiotDTO[$i]->Versio >= $uusin_versio[$hakemuksetDTO[$j]->Viranomaisen_koodi]){
				$uusin_versio[$hakemuksetDTO[$j]->Viranomaisen_koodi] = $hakemusversiotDTO[$i]->Versio;
				$hakemuksetDTO[$j]->Hakemuksen_tilaDTO = $hakemuksen_tilaDAO->hae_hakemuksen_uusimman_tilan_tiedot($hakemuksetDTO[$j]->ID); 
				$uusin_hakemusDTO[$hakemuksetDTO[$j]->Viranomaisen_koodi] = $hakemuksetDTO[$j];
			} 
			
		}			
				
	}
	
	return $uusin_hakemusDTO;
	
}

function vapauta_kayttajan_lukitsemat_hakemusversiot($db, $fk_kayttaja){

	$hakemusversioDAO = new HakemusversioDAO($db);
	$hakijaDAO = new HakijaDAO($db);
	$hakijan_rooliDAO = new Hakijan_rooliDAO($db);

	$hakijatDTO = $hakijaDAO->hae_kayttajan_hakijat($fk_kayttaja);
	$hakemusversio_idt = array();

	for($i=0; $i < sizeof($hakijatDTO); $i++){
		
		$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakijan_roolin_tiedot_hakijan_avaimella($hakijatDTO[$i]->ID);
		
		for($j=0; $j < sizeof($hakijan_roolitDTO); $j++){
			array_push($hakemusversio_idt, $hakijan_roolitDTO[$j]->HakemusversioDTO->ID);
		}
			
	}
	
	$hakemusversio_idt = array_unique($hakemusversio_idt);

	foreach($hakemusversio_idt as $indx => $fk_hakemusversio){	
		if($hakemusversioDAO->hae_hakemusversion_tiedot($fk_hakemusversio)->Muokkaaja==$fk_kayttaja){
			$hakemusversioDAO->paivita_hakemusversion_muokkaaja($fk_hakemusversio, null, null);
		}
	}
	
}

function lukitse_hakemusversio_jos_vapaa($db, $kayt_id, $hakemusversioDTO){

	if($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken" && (is_null($hakemusversioDTO->Muokkaaja) || $hakemusversioDTO->Muokkaaja==$kayt_id || time() - strtotime($hakemusversioDTO->Muokkauspvm) > HAKEMUSVERSIO_LOCK_TIME )){ 
		$hakemusversioDAO = new HakemusversioDAO($db);
		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if($hakemusversioDAO->paivita_hakemusversion_muokkaaja($hakemusversioDTO->ID, $kayt_id, $nyt)){ // Lukitaan hakemusversio
			$hakemusversioDTO->Muokkaaja = $kayt_id;
			$hakemusversioDTO->Muokkauspvm = $nyt;
		}
	} else { // Haetaan muokkaajan tiedot

		$kayttajaDAO = new KayttajaDAO($db);
		$hakemusversioDTO->KayttajaDTO_Muokkaaja = $kayttajaDAO->hae_kayttajan_tiedot($hakemusversioDTO->Muokkaaja);

	}

	return $hakemusversioDTO;

}

function hae_kayttajan_lukemattomien_viestien_maara($db, $fk_kayttaja){

	$viestitDAO = new ViestitDAO($db);
	$lukemattomat_viestitDTO = $viestitDAO->hae_lukemattomat_viestit_kayttajalle($fk_kayttaja);
	
	return sizeof($lukemattomat_viestitDTO); 

}

function hae_eraantyvien_kayttolupien_maara_kayttajalle($db, $kayt_id){

	$kayttolupaDAO = new KayttolupaDAO($db);
	$kayttoluvatDTO = $kayttolupaDAO->hae_kayttajan_kayttoluvat($kayt_id);
	$eraantyvia_kayttolupia_kpl = 0;

	if(!empty($kayttoluvatDTO) && !is_null($kayttoluvatDTO)){

		$paatosDAO = new PaatosDAO($db);
		$hakemusDAO = new HakemusDAO($db);
		$hakemusversioDAO = new HakemusversioDAO($db);
		$aineistotilausDAO = new AineistotilausDAO($db);
		$aineistotilauksen_tilaDAO = new Aineistotilauksen_tilaDAO($db);

		for($i=0; $i < sizeof($kayttoluvatDTO); $i++){

			$kayttolupaDTO = $kayttoluvatDTO[$i];

			if(isset($kayttolupaDTO->Lakkaamispvm)){

				$date_tanaan = new DateTime(date_format(date_create(), 'Y-m-d H:i:s'));
				$paiviaLakkaamisPaivaan = $date_tanaan->diff(new DateTime($kayttolupaDTO->Lakkaamispvm))->days;

				if($paiviaLakkaamisPaivaan < 31 && $aineistotilauksen_tilaDAO->hae_tilan_koodi_aineistotilauksen_avaimella($aineistotilausDAO->hae_id_paatoksen_avaimella($kayttolupaDTO->PaatosDTO->ID)->ID)->Aineistotilauksen_tilan_koodi=="aint_toimitettu"){
					$eraantyvia_kayttolupia_kpl++;
				}
			}
		}
	}

	return $eraantyvia_kayttolupia_kpl;

}

function hae_saapuneiden_lausuntojen_maara_kayttajalle($db, $kayt_id){

	$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);
	$lausuntoDAO = new LausuntoDAO($db);
	$lausunnon_lukeneetDAO = new Lausunnon_lukeneetDAO($db);

	$lukemattomia = 0;

	$lausuntopyynnotDTO = $lausuntopyyntoDAO->hae_lausuntopyynnot_lausunnonpyytajalle($kayt_id);

	for($i=0; $i < sizeof($lausuntopyynnotDTO); $i++){

		$lausuntoDTO = $lausuntoDAO->hae_lausuntopyynnolle_lausunto($lausuntopyynnotDTO[$i]->ID);

		if(isset($lausuntoDTO->ID) && !is_null($lausuntoDTO->ID)){
			if($lausuntoDTO->Lausunto_julkaistu==1){
				if($lausunnon_lukeneetDAO->lausunto_on_luettu($kayt_id, $lausuntoDTO->ID) == 0){
					$lukemattomia++;
				} 
			}
		}

	}

	return $lukemattomia;

}

function kayttajaAutentikoitu($db,$parametrit){

	// Jätettty keskeneräiseksi (odotetaan suomi.fi tunnistuksen toteutusta)

	if(isset($parametrit["kayt_id"]) && is_numeric($parametrit["kayt_id"])){

		$token = $parametrit["token"];
		$fk_kayttaja = $parametrit["kayt_id"];
		$fk_tutkimus = (isset($parametrit["tutkimus_id"]) ? $parametrit["tutkimus_id"] : null);
		$fk_hakemusversio = (isset($parametrit["hakemusversio_id"]) ? $parametrit["hakemusversio_id"] : null);
		$fk_hakemus = (isset($parametrit["hakemus_id"]) ? $parametrit["hakemus_id"] : null);
		$kayttajan_rooli = (isset($parametrit["kayttajan_rooli"]) ? $parametrit["kayttajan_rooli"] : null); // Käyttäjän hakema rooli
		$sallitut_roolit = (isset($parametrit["sallitut_roolit"]) ? $parametrit["sallitut_roolit"] : array()); // Lista rooleista, joiden oikeutta käyttäjä saa hakea
		$haettu_rooli_sallittu = (empty($sallitut_roolit) ? true : false); // Kaikkien roolien oikeuksia voi hakea, jos sallittuja rooleja ei ole erikseen määritelty
		$sallittu_token = false;
	
		$kayttajaDAO = new KayttajaDAO($db);
		$suojausDAO = new SuojausDAO($db);
		$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
		$suojauksetDTO = $suojausDAO->hae_kayttajan_suojaustunnukset($fk_kayttaja);
						
		foreach ($suojauksetDTO as $indx => $suojausDTO) {
			if(isset($suojausDTO->KayttajaDTO->ID) && isset($suojausDTO->Suojaustunnus) && $suojausDTO->Suojaustunnus==$parametrit["token"]){
				$kayttajaDTO = $suojausDTO->KayttajaDTO;
				$sallittu_token = true;
				break;
			}
		}	
		
		if($sallittu_token){
							
			if(!is_null($kayttajan_rooli)){
				
				if(!empty($sallitut_roolit)){
					for($i=0; $i < sizeof($sallitut_roolit); $i++){
						if($sallitut_roolit[$i]==$kayttajan_rooli) $haettu_rooli_sallittu = true;
					}
					if(!$haettu_rooli_sallittu) return false;
				}				
				
				// Tarkistetaan löytyykö käyttäjälle viranomaisen rooli
				if($kayttajan_rooli=="rooli_eettisen_puheenjohtaja" || $kayttajan_rooli=="rooli_eettisensihteeri" || $kayttajan_rooli=="rooli_aineistonmuodostaja" || $kayttajan_rooli=="rooli_lausunnonantaja" || $kayttajan_rooli=="rooli_kasitteleva" || $kayttajan_rooli=="rooli_paattava" || $kayttajan_rooli=="rooli_viranomaisen_paak"){ 

					$viranomaisen_rooliDTO_autentikoitu_kayttaja = tarkista_kayttajan_viranomaisen_rooli($db, $fk_kayttaja, $kayttajan_rooli);

					if(isset($viranomaisen_rooliDTO_autentikoitu_kayttaja->ID)){ 
						$kayttajaDTO->Viranomaisen_rooliDTO = $viranomaisen_rooliDTO_autentikoitu_kayttaja;
					} else {
						return false;
					}
					
				}
				
				// Tarkistetaan löytyykö käyttäjälle pääkäyttäjän rooli
				if($kayttajan_rooli=="rooli_lupapalvelun_paak"){					
					$kayttajaDTO->Paakayttajan_rooliDTO = tarkista_lupapalvelun_paakayttajan_rooli($db, $fk_kayttaja);					
					if(!isset($kayttajaDTO->Paakayttajan_rooliDTO->ID) || is_null($kayttajaDTO->Paakayttajan_rooliDTO->ID)) return false;																	
				} 					
				
			}

			if(!is_null($fk_hakemusversio) || !is_null($fk_hakemus) || !is_null($fk_tutkimus)){	
													
				// Tarkistetaan onko tutkijalla oikeus hakemusversioon
				if(!is_null($fk_hakemusversio)){ 
					if(is_null($kayttajan_rooli) || $kayttajan_rooli=="rooli_hakija"){
						if(kayttaja_on_hakemuksen_hakija($db, $fk_hakemusversio, $fk_kayttaja)) return $kayttajaDTO;
					}	
					if(is_null($kayttajan_rooli) || $kayttajan_rooli=="rooli_lausunnonantaja" || $kayttajan_rooli=="rooli_aineistonmuodostaja" || $kayttajan_rooli=="rooli_eettisensihteeri" || $kayttajan_rooli=="rooli_eettisen_puheenjohtaja" || $kayttajan_rooli=="rooli_kasitteleva" || $kayttajan_rooli=="rooli_paattava"){
						if(kayttaja_on_hakemusversion_viranomainen($db, $fk_hakemusversio, $fk_kayttaja)) return $kayttajaDTO; 
					}
				}
				
				// Tarkistetaan onko tutkijalla oikeus tutkimukseen
				if(!is_null($fk_tutkimus) && (is_null($kayttajan_rooli) || $kayttajan_rooli=="rooli_hakija")){
					if(kayttaja_on_tutkimuksen_hakija($db, $fk_tutkimus, $fk_kayttaja)) return $kayttajaDTO;
				}
				
				// Tarkistetaan onko käyttäjällä oikeus hakemukseen
				if(!is_null($fk_hakemus) && (is_null($kayttajan_rooli) || $kayttajan_rooli=="rooli_hakija" || $kayttajan_rooli=="rooli_lausunnonantaja" || $kayttajan_rooli=="rooli_aineistonmuodostaja" || $kayttajan_rooli=="rooli_eettisensihteeri" || $kayttajan_rooli=="rooli_eettisen_puheenjohtaja" || $kayttajan_rooli=="rooli_kasitteleva" || $kayttajan_rooli=="rooli_paattava")){						
					if(kayttaja_on_tutkimuksen_viranomainen($db, $fk_hakemus, $fk_kayttaja)) return $kayttajaDTO;
					if(kayttaja_on_viranomaishakemuksen_hakija($db, $fk_hakemus, $fk_kayttaja)) return $kayttajaDTO;				
				}
								
				return false;
				
			}						
																
			return $kayttajaDTO;
		
		}

	}
	
	return false;

}

function kayttaja_on_tutkimuksen_hakija($db, $fk_tutkimus, $fk_kayttaja){
		
	$hakemusversioDAO = new HakemusversioDAO($db);
	$kayttaja_on_tutkimuksen_hakija = false;
	
	$hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_kaikki_hakemusversiot($fk_tutkimus);
	
	for($i=0; $i < sizeof($hakemusversiotDTO); $i++){
		if(kayttaja_on_hakemuksen_hakija($db, $hakemusversiotDTO[$i]->ID, $fk_kayttaja)){
			$kayttaja_on_tutkimuksen_hakija = true;
			break;
		}
	}
	
	return $kayttaja_on_tutkimuksen_hakija;
	
}	

function kayttaja_on_hakemuksen_hakija($db, $fk_hakemusversio, $fk_kayttaja){

	$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
	$hakijaDAO = new HakijaDAO($db);

	$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijan_roolit($fk_hakemusversio);

	for($i=0; $i < sizeof($hakijan_roolitDTO); $i++){
		
		$hakijaDTO = $hakijaDAO->hae_hakijan_tiedot($hakijan_roolitDTO[$i]->HakijaDTO->ID);
		
		if($hakijaDTO->KayttajaDTO->ID==$fk_kayttaja && !is_null($hakijaDTO->Jasen)) return true;
					
	}
	
	return false;

}

function kayttaja_on_viranomaishakemuksen_hakija($db, $fk_hakemus, $fk_kayttaja){
	
	$hakemusDAO = new HakemusDAO($db);
	$fk_hakemusversio = $hakemusDAO->hae_hakemuksen_tiedot($fk_hakemus)->HakemusversioDTO->ID;

	if(is_numeric($fk_hakemus)) return kayttaja_on_hakemuksen_hakija($db, $hakemusDAO->hae_hakemuksen_tiedot($fk_hakemus)->HakemusversioDTO->ID, $fk_kayttaja);
	
	return false;
	
}

function kayttaja_on_hakemusversion_viranomainen($db, $fk_hakemusversio, $fk_kayttaja){
	
	$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
	$hakemusDAO = new HakemusDAO($db);	
	$hakemusversioDAO = new HakemusversioDAO($db);
	
	$viranomaisen_roolitDTO = $viranomaisen_rooliDAO->hae_kayttajalle_viranomaisen_roolit($fk_kayttaja);
	$vir_rooli_loytyi = false;
	$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

	for($i=0; $i < sizeof($viranomaisen_roolitDTO); $i++){
		if($viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_lausunnonantaja" || $viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_eettisen_puheenjohtaja" || $viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_eettisensihteeri" || $viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_aineistonmuodostaja" || $viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_kasitteleva" || $viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_paattava"){
			$vir_rooli_loytyi = true;
			$viranomaisen_rooliDTO = $viranomaisen_roolitDTO[$i];
			break;
		}
	}

	if($vir_rooli_loytyi){

		$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($fk_hakemusversio);
		$hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_kaikki_hakemusversiot($hakemusversioDTO->TutkimusDTO->ID);		
		
		for($i=0; $i < sizeof($hakemusversiotDTO); $i++){ 
		
			$hakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset($hakemusversiotDTO[$i]->ID);
			
			for($j=0; $j < sizeof($hakemuksetDTO); $j++){
				if($hakemuksetDTO[$j]->Viranomaisen_koodi==$viranomaisen_rooliDTO->Viranomaisen_koodi) return true;
			}
		
		}

	}	

	return false;
	
}

// Viranomaisella on pääsyoikeus hakemukseen jos tutkimuksen rekistereistä löytyy sama Viranomaisen_koodi mitä viranomaisella itsellään on
function kayttaja_on_tutkimuksen_viranomainen($db, $fk_hakemus, $fk_kayttaja){

	$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
	$hakemusDAO = new HakemusDAO($db);	
	$hakemusversioDAO = new HakemusversioDAO($db);
	
	$viranomaisen_roolitDTO = $viranomaisen_rooliDAO->hae_kayttajalle_viranomaisen_roolit($fk_kayttaja);
	$vir_rooli_loytyi = false;
	$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

	for($i=0; $i < sizeof($viranomaisen_roolitDTO); $i++){
		if($viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_lausunnonantaja" || $viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_eettisen_puheenjohtaja" || $viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_eettisensihteeri" || $viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_aineistonmuodostaja" || $viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_kasitteleva" || $viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_paattava"){
			$vir_rooli_loytyi = true;
			$viranomaisen_rooliDTO = $viranomaisen_roolitDTO[$i];
			break;
		}
	}

	if($vir_rooli_loytyi){

		$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($fk_hakemus);
		$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
		$hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_kaikki_hakemusversiot($hakemusversioDTO->TutkimusDTO->ID);		
		
		for($i=0; $i < sizeof($hakemusversiotDTO); $i++){ 
		
			$hakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset($hakemusversiotDTO[$i]->ID);
			
			for($j=0; $j < sizeof($hakemuksetDTO); $j++){
				if($hakemuksetDTO[$j]->Viranomaisen_koodi==$viranomaisen_rooliDTO->Viranomaisen_koodi) return true;
			}
		
		}

	}	

	return false;

}

// Input:
// $db = PDO database connection
// $fk_kayttaja = Foreign key to Kayttaja table
// $fk_paatos = Foreign key to Paatos table
// 
// Output:
// Palauttaa true jos käyttäjä on päätöksen käsittelijä tai päättäjä
function kayttaja_on_paatoksen_valmistelija_tai_paattaja($db, $fk_kayttaja, $fk_paatos){

	$paatosDAO = new PaatosDAO($db);
	$paattajaDAO = new PaattajaDAO($db);

	$paatosDTO = $paatosDAO->hae_paatoksen_tiedot($fk_paatos);

	if($paatosDTO->Kasittelija==$fk_kayttaja){
		return true;
	}
	$paattajatDTO = $paattajaDAO->hae_paatoksen_paattajat($fk_paatos);

	for($i=0; $i < sizeof($paattajatDTO); $i++){
		if($paattajatDTO[$i]->KayttajaDTO->ID==$fk_kayttaja) return true;
	}
	return false;

}

function kayttaja_on_tutkimuksen_lausunnonantaja($db, $fk_hakemus, $fk_kayttaja){

	$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
	$hakemusDAO = new HakemusDAO($db);
	$hakemusversioDAO = new HakemusversioDAO($db);
	$lausuntopyyntoDAO = new LausuntopyyntoDAO($db);

	$viranomaisen_roolitDTO = $viranomaisen_rooliDAO->hae_kayttajalle_viranomaisen_roolit($fk_kayttaja);
	$laus_ant_rooli_loytyi = false;
	$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

	for($i=0; $i < sizeof($viranomaisen_roolitDTO); $i++){
		if($viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_lausunnonantaja"){
			$laus_ant_rooli_loytyi = true;
			$viranomaisen_rooliDTO = $viranomaisen_roolitDTO[$i];
			break;
		}
	}

	if($laus_ant_rooli_loytyi){

		$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($fk_hakemus);
		$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
		$lausuntopyynnotDTO = $lausuntopyyntoDAO->hae_antajalle_tutkimuksen_lausuntopyynnot($hakemusversioDTO->TutkimusDTO->ID, $fk_kayttaja);

		if(sizeof($lausuntopyynnotDTO) > 0){
			return true;
		}
	}
	return false;

}

function kayttaja_on_tutkimuksen_aineistonmuodostaja($db, $fk_hakemus, $fk_kayttaja){
	
	return true;
	/* todo
	$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);
	$hakemusDAO = new HakemusDAO($db);
	$hakemusversioDAO = new HakemusversioDAO($db);
	$haettu_aineistoDAO = new Haettu_aineistoDAO($db);
	$haettu_koriDAO = new Haettu_koriDAO($db);
	$rekisteriDAO = new RekisteriDAO($db);

	$viranomaisen_roolitDTO = $viranomaisen_rooliDAO->hae_kayttajalle_viranomaisen_roolit($fk_kayttaja);
	$ain_muod_rooli_loytyi = false;
	$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

	for($i=0; $i < sizeof($viranomaisen_roolitDTO); $i++){
		if($viranomaisen_roolitDTO[$i]->Viranomaisroolin_koodi=="rooli_aineistonmuodostaja"){
			$ain_muod_rooli_loytyi = true;
			$viranomaisen_rooliDTO = $viranomaisen_roolitDTO[$i];
			break;
		}
	}
	if($ain_muod_rooli_loytyi){

		$hakemusDTO = $hakemusDAO->hae_hakemuksen_tiedot($fk_hakemus);
		$hakemusversioDTO = $hakemusversioDAO->hae_hakemusversion_tiedot($hakemusDTO->HakemusversioDTO->ID);
		$hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_lukitut_ja_peruuttamattomat_hakemusversiot($hakemusversioDTO->TutkimusDTO->ID);

		for($i=0; $i < sizeof($hakemusversiotDTO); $i++){

			$haetut_aineistotDTO = $haettu_aineistoDAO->hae_hakemusversion_haetut_aineistot($hakemusversiotDTO[$i]->ID);

			for($j=0; $j < sizeof($haetut_aineistotDTO); $j++){

				$haetut_koritDTO = $haettu_koriDAO->hae_haetun_aineiston_haetut_korit($haetut_aineistotDTO[$j]->ID);

				for($k=0; $k < sizeof($haetut_koritDTO); $k++){
					if($rekisteriDAO->hae_rekisteri($haetut_koritDTO[$k]->RekisteriDTO->ID)->Viranomaisen_koodi==$viranomaisen_rooliDTO->Viranomaisen_koodi){
						return true;
					}
				}
			}
		}
	}
	
	return false;
	*/
}


function hakemusversion_kayttajan_toiminto_sallittu($db, $fk_kayttaja, $hakemusversioDTO, $toiminto){
	
	$jarjestelman_hakijan_roolitDAO = new Jarjestelman_hakijan_roolitDAO($db);
	
	$hakijan_roolitDTO = hae_kayttajan_ja_hakemusversion_hakijan_roolit($db, $fk_kayttaja, $hakemusversioDTO->ID);	
	$jarjestelman_hakijan_roolitDTO = $jarjestelman_hakijan_roolitDAO->hae_hakemustyypin_hakijan_roolit($hakemusversioDTO->LomakeDTO->ID);
	
	for($i=0; $i < sizeof($hakijan_roolitDTO); $i++){
		for($j=0; $j < sizeof($jarjestelman_hakijan_roolitDTO); $j++){
			if($jarjestelman_hakijan_roolitDTO[$j]->Hakijan_roolin_koodi==$hakijan_roolitDTO[$i]->Hakijan_roolin_koodi){
				if($toiminto=="lahetys" && $jarjestelman_hakijan_roolitDTO[$j]->Hakemuksen_lahetys_sallittu==1) return true;
			}
		}
	}
	
	return false;
	
}

function tarkista_kayttajan_viranomaisen_rooli($db, $fk_kayttaja, $viranomaisroolin_koodi){

	$viranomaisen_rooliDAO = new Viranomaisen_rooliDAO($db);

	$viranomaisen_rooliDTO = $viranomaisen_rooliDAO->hae_kayttajan_viranomaisen_rooli_roolin_perusteella($fk_kayttaja, $viranomaisroolin_koodi);

	if(isset($viranomaisen_rooliDTO->ID)){ // Rooli löytyi
		return $viranomaisen_rooliDTO;
	}
	return false;

}

function tarkista_lupapalvelun_paakayttajan_rooli($db, $fk_kayttaja){

	$paakayttajan_rooliDAO = new Paakayttajan_rooliDAO($db);

	$paakayttajan_rooliDTO = $paakayttajan_rooliDAO->hae_kayttajalle_paakayttajan_rooli($fk_kayttaja);

	if(isset($paakayttajan_rooliDTO->ID)){ // Rooli löytyi
		return $paakayttajan_rooliDTO;
	}
	
	return false;

}

function hae_hakemusversion_hakijat($db, $hakemusversioDTO){

	$vastaus = array();
	$hakijaDAO = new HakijaDAO($db);
	$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
	$sitoumusDAO = new SitoumusDAO($db);

	$kasittelyoikeutta_hakevat_hakijatDTO = array();
	$ei_kasittelyoikeutta_hakevat_hakijatDTO = array();
	$sitoumuksetDTO = array();

	$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijan_roolit($hakemusversioDTO->ID);
	$hakemusversion_kayttajat = array();
	$hakijatDTO = array();

	if(is_array($hakijan_roolitDTO)){ 
		for($i=0; $i < sizeof($hakijan_roolitDTO); $i++){

			$hakijan_roolitDTO[$i]->HakijaDTO = $hakijaDAO->hae_hakijan_tiedot($hakijan_roolitDTO[$i]->HakijaDTO->ID);

			if(!in_array($hakijan_roolitDTO[$i]->HakijaDTO->KayttajaDTO->ID, $hakemusversion_kayttajat)){

				array_push($hakemusversion_kayttajat, $hakijan_roolitDTO[$i]->HakijaDTO->KayttajaDTO->ID);
				array_push($hakijatDTO, $hakijan_roolitDTO[$i]->HakijaDTO);

			}
		}
	}

	//$hakijatDTO = $hakijaDAO->hae_kayttajan_hakijat($kayt_id);

	for($i=0; $i < sizeof($hakijatDTO); $i++){

		$hakijan_roolitDTO = array();
		$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_fk_hakijan_roolit($hakemusversioDTO->ID, $hakijatDTO[$i]->ID);

		if(!empty($hakijan_roolitDTO)){

			$hakijatDTO[$i]->Hakijan_roolitDTO = $hakijan_roolitDTO;

			if($hakijatDTO[$i]->Haetaanko_kayttolupaa==1){

				$sitoumusDTO = $sitoumusDAO->hae_tutkimuksen_kayttajan_sitoumus($hakemusversioDTO->TutkimusDTO->ID, $hakijatDTO[$i]->KayttajaDTO->ID);

				if(isset($sitoumusDTO->ID)){
					$hakijatDTO[$i]->KayttajaDTO->SitoumusDTO = $sitoumusDTO;
					array_push($sitoumuksetDTO, $sitoumusDTO);
				}
				array_push($kasittelyoikeutta_hakevat_hakijatDTO, $hakijatDTO[$i]);

			} else {
				array_push($ei_kasittelyoikeutta_hakevat_hakijatDTO, $hakijatDTO[$i]);
			}

		}

	}

	$hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat = $kasittelyoikeutta_hakevat_hakijatDTO;
	$hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat = $ei_kasittelyoikeutta_hakevat_hakijatDTO;

	$vastaus["hakemusversioDTO"] = $hakemusversioDTO;
	$vastaus["sitoumuksetDTO"] = $sitoumuksetDTO;

	return $vastaus;

}

function hae_kayttajan_ja_hakemusversion_hakijan_roolit($db, $fk_kayttaja, $fk_hakemusversio){
	
	$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
	$hakijaDAO = new HakijaDAO($db);

	$hv_hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijan_roolit($fk_hakemusversio);
	$hakijan_roolitDTO = array();
	
	for($i=0; $i < sizeof($hv_hakijan_roolitDTO); $i++){
		if($hakijaDAO->hae_hakijan_tiedot($hv_hakijan_roolitDTO[$i]->HakijaDTO->ID)->KayttajaDTO->ID==$fk_kayttaja){
			array_push($hakijan_roolitDTO, $hv_hakijan_roolitDTO[$i]);
		}
	}

	return $hakijan_roolitDTO;
	
}

function hae_tutkimuksen_muut_viranomaisen_hakemukset($db, $fk_tutkimus, $viranomaisen_koodi, $ohita_hakemus_id){

	$hakemusversioDAO = new HakemusversioDAO($db);
	$hakemusversion_tilaDAO = new Hakemusversion_tilaDAO($db);
	$hakemusDAO = new HakemusDAO($db);

	$hakemusversiotDTO = $hakemusversioDAO->hae_tutkimuksen_kaikki_hakemusversiot($fk_tutkimus);
	$tutkimuksen_vir_om_hakemukset = array();

	for($i=0; $i < sizeof($hakemusversiotDTO); $i++){

		$hakemusversiotDTO[$i]->Hakemusversion_tilaDTO = $hakemusversion_tilaDAO->hae_hakemusversion_uusin_tila($hakemusversiotDTO[$i]->ID);

		if($hakemusversiotDTO[$i]->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_lah"){

			$hakemuksetDTO = $hakemusDAO->hae_hakemusversion_hakemukset_viranomaiselle($hakemusversiotDTO[$i]->ID, $viranomaisen_koodi);

			for($j=0; $j < sizeof($hakemuksetDTO); $j++){
				if($hakemuksetDTO[$j]->ID!=$ohita_hakemus_id){
					$hakemuksetDTO[$j]->HakemusversioDTO = $hakemusversiotDTO[$i];
					$hakemuksetDTO[$j]->HakemusversioDTO->TutkimusDTO = new TutkimusDTO(); 
					$hakemuksetDTO[$j]->HakemusversioDTO->TutkimusDTO->ID = $fk_tutkimus;
					array_push($tutkimuksen_vir_om_hakemukset, $hakemuksetDTO[$j]);
				}
			}

		}
	}
	return $tutkimuksen_vir_om_hakemukset;

}

function siirra_hakemus_kasittelyyn($db, $hakemusDTO, $kayt_id, $kasittelija, $luo_asia){

	$hakemusDAO = new HakemusDAO($db);
	$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
	$paatosDAO = new PaatosDAO($db);
	$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);
	//$haettu_aineistoDAO = new Haettu_aineistoDAO($db);
	//$paatetty_aineistoDAO = new Paatetty_aineistoDAO($db);
	//$haettu_luvan_kohdeDAO = new Haettu_luvan_kohdeDAO($db);
	//$paatetty_luvan_kohdeDAO = new Paatetty_luvan_kohdeDAO($db);
	$koodistotDAO = new KoodistotDAO($db);
	$asiaDAO = new AsiaDAO($db);

	$hakemuksen_tilaDAO->maarita_hakemuksen_tiloista_tamanhetkiset_pois($hakemusDTO->ID);
	$hakemuksen_tilaDAO->luo_hakemuksen_tila($hakemusDTO->ID, $kayt_id, "hak_kas");

	// Luodaan Paatos-taulu
	$hakemusDTO->PaatosDTO = $paatosDAO->lisaa_paatos_hakemukseen($hakemusDTO->ID, null, $kasittelija, $kayt_id); 

	// Luodaan Paatoksen_tila -taulu
	$paatoksen_tilaDAO->luo_paatokselle_paatoksen_tila($hakemusDTO->PaatosDTO->ID, "paat_tila_kesken", $kayt_id);

	// Haetaan hakemuksen aineistot
	/*
	$haettu_aineistoDTO = $haettu_aineistoDAO->hae_hakemusversion_haetut_aineistot($hakemusDTO->HakemusversioDTO->ID);

	for($i=0; $i < sizeof($haettu_aineistoDTO); $i++){

		// Luodaan Paatetty_aineisto -taulu
		$paatetty_aineistoDTO = $paatetty_aineistoDAO->lisaa_paatetty_aineisto_paatokseen($hakemusDTO->PaatosDTO->ID, $haettu_aineistoDTO[$i]->Aineiston_indeksi, $kayt_id);

		// Haetaan Haettu_kori
		$haetut_luvan_kohteetDTO = $haettu_luvan_kohdeDAO->hae_haetun_aineiston_haetun_luvan_kohteet($haettu_aineistoDTO[$i]->ID);

		foreach($haetut_luvan_kohteetDTO as $kohde_tyyppi => $haetut_luvan_kohteetDTO) {
			for($j=0; $j < sizeof($haetut_luvan_kohteetDTO); $j++){

				$haettu_luvan_kohdeDTO = $haetut_luvan_kohteetDTO[$j];

				// Luodaan Paatetty_kori -taulu
				//$luvan_kohdeDTO = $luvan_kohdeDAO->hae_luvan_kohde($haetut_luvan_kohteetDTO[$j]->Luvan_kohdeDTO->ID);

				if($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi==$hakemusDTO->Viranomaisen_koodi){

					$paatetty_luvan_kohdeDTO = $paatetty_luvan_kohdeDAO->lisaa_paatetty_luvan_kohde_paatettyyn_aineistoon($paatetty_aineistoDTO->ID, $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID, $haettu_luvan_kohdeDTO->Kohde_tyyppi, $haettu_luvan_kohdeDTO->Muuttujat_lueteltuna, $haettu_luvan_kohdeDTO->Poiminta_ajankohdat, $kayt_id);

					// Haetaan Haetun_korin muuttujat
					//$haetut_muuttujatDTO = $haettu_muuttujaDAO->hae_haetun_korin_haetut_muuttujat($haetut_luvan_kohteetDTO[$j]->ID);

					//for($l=0; $l < sizeof($haetut_muuttujatDTO); $l++){

						// Luodaan päätetty muuttuja
						//$paatetty_muuttujaDAO->lisaa_paatetty_muuttuja_paatettyyn_koriin($paatetty_koriDTO->ID, $haetut_muuttujatDTO[$l]->Muuttujan_koodi, $haetut_muuttujatDTO[$l]->Lisatieto);

						//}
				}
			}
		}
	}
	*/
	// Luodaan asia
	if($luo_asia){

		$koodistoDTO = $koodistotDAO->hae_koodin_tiedot($hakemusDTO->Viranomaisen_koodi,"fi");
		$asiaDTO = $asiaDAO->luo_asia($koodistoDTO->Selite1, null, "Julkinen", "Sisältää henkilötietoja", "PS", null, $kayt_id);

		// Asianumero
		$asiaDAO->paivita_asian_tieto($asiaDTO->ID, "Diaarinumero", "LPAL/" . $asiaDAO->hae_vuoden_juokseva_nro(date("Y")) . "/07.03.03/" . $koodistoDTO->Selite2 . "" . date("Y"));

		// Liitetään asia hakemukseen
		$hakemusDAO->paivita_hakemuksen_tieto($hakemusDTO->ID, "FK_Asia", $asiaDTO->ID);

	}
	return $hakemusDTO;

}

function paivita_tilatiedot($db, $parametrit){
	
	$tilatiedot = array();
	$tilatiedot["Hakemuksen_tila_paivitetty"] = false;
	$tilatiedot["Paatoksen_tila_paivitetty"] = false;	
	
	$kayt_id = $parametrit["kayt_id"];
	$fk_hakemusversio = $parametrit["fk_hakemusversio"];
	$fk_hakemus = $parametrit["fk_hakemus"];
	$fk_paatos = $parametrit["fk_paatos"];
	$fk_aineistotilaus = $parametrit["fk_aineistotilaus"];
			
	$hakemusversio_uusi_tila = $parametrit["hakemusversio_uusi_tila"];
	$hakemus_uusi_tila = $parametrit["hakemus_uusi_tila"];
	$paatos_uusi_tila = $parametrit["paatos_uusi_tila"];
	$aineistotilaus_uusi_tila = $parametrit["aineistotilaus_uusi_tila"];
	
	if(!is_null($kayt_id)){
	
		$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
		$paatoksen_tilaDAO = new Paatoksen_tilaDAO($db);
							
		if(!is_null($fk_hakemus) && !is_null($hakemus_uusi_tila)){
			if($hakemuksen_tilaDAO->luo_hakemuksen_tila($fk_hakemus, $kayt_id, $hakemus_uusi_tila)) $tilatiedot["Hakemuksen_tila_paivitetty"] = true;																	
		}
							
		if(!is_null($fk_paatos) && !is_null($paatos_uusi_tila)){
			if($paatoksen_tilaDAO->luo_paatokselle_paatoksen_tila($fk_paatos, $paatos_uusi_tila, $kayt_id)) $tilatiedot["Paatoksen_tila_paivitetty"] = true;																	
		}	
	
	}

	return $tilatiedot;
	
}

function hae_hakemusversion_tieteenala($db, $hakemusversioDTO, $osiotDTO_tieteenalat){
	
	$osio_sisaltoDAO = new Osio_sisaltoDAO($db);
	$tieteenala = null;						
						
	if($hakemusversioDTO->LomakeDTO->ID==1){ // Tieteenalat on vain käyttölupahakemuksen lomakkeessa 
						
		$tieteenaloja_lkm = 0;
													
		for($j=0; $j < sizeof($osiotDTO_tieteenalat); $j++){
								
			$osio_sisaltoDTO = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($hakemusversioDTO->ID, "FK_Hakemusversio", $osiotDTO_tieteenalat[$j]->ID);
					
			if(isset($osio_sisaltoDTO->ID) && !is_null($osio_sisaltoDTO->ID)){								
				$tieteenala = $osiotDTO_tieteenalat[$j]->Otsikko;
				$tieteenaloja_lkm++;
			} 
								
			if($tieteenaloja_lkm > 1) break;
								
		}
							
		if($tieteenaloja_lkm > 1) $tieteenala = "t_moni"; // Tilastointihavainto on ”monitieteinen”, jos tieteenaloja on enemmän kuin yksi
							
	}	
						
	return $tieteenala;					
	
}

function hae_hakemusversion_vastuullinen_johtaja($db, $fk_hakemusversio){
	
	$hakijan_rooliDAO = new Hakijan_rooliDAO($db);
	$hakijaDAO = new HakijaDAO($db);
	$hakijaDTO_vast_johtaja = null;						
	$hakijan_roolitDTO = $hakijan_rooliDAO->hae_hakemusversion_hakijan_rooli($fk_hakemusversio, "rooli_vast");
						
	if(isset($hakijan_roolitDTO[0]->HakijaDTO->ID) && !is_null($hakijan_roolitDTO[0]->HakijaDTO->ID)){
		$hakijaDTO = $hakijaDAO->hae_hakijan_tiedot($hakijan_roolitDTO[0]->HakijaDTO->ID);
		if(isset($hakijaDTO->ID) && !is_null($hakijaDTO->ID)) $hakijaDTO_vast_johtaja = $hakijaDTO;
	}

	return $hakijaDTO_vast_johtaja;						
	
}

function laske_pvm_erotus($pvm_alku, $pvm_loppu){
	
	$pvm_erotus = null;
						
	if(verifyDate($pvm_alku) && verifyDate($pvm_loppu)){	
						
		$alku = new DateTime($pvm_alku);
		$loppu = new DateTime($pvm_loppu);
		$pvm_ero = $alku->diff($loppu);
		$pvm_ero->format('%R');
		$pvm_erotus = $pvm_ero->days;
							
	}

	return $pvm_erotus;
	
}

function hae_hakemusten_viimeisin_paatospvm($db, $hakemuksetDTO){
	
	$viimeinen_pvm_paatos_tehty_date = null;
	$hakemuksen_tilaDAO = new Hakemuksen_tilaDAO($db);
	$paatos_pvmt = array();
								
	for($h=0; $h < sizeof($hakemuksetDTO); $h++){
									
		$hakemuksen_tilatDTO = $hakemuksen_tilaDAO->hae_hakemuksen_hakemuksen_tilat($hakemuksetDTO[$h]->ID);
									
		for($p=0; $p < sizeof($hakemuksen_tilatDTO); $p++){
			if(isset($hakemuksen_tilatDTO[$p]->Hakemuksen_tilan_koodi) && $hakemuksen_tilatDTO[$p]->Hakemuksen_tilan_koodi=="hak_paat") array_push($paatos_pvmt, $hakemuksen_tilatDTO[$p]->Lisayspvm);																				
		}
									
	}
		
	$viimeinen_pvm_paatos_tehty = 0;
		
	foreach($paatos_pvmt as $date){
		$curDate = strtotime($date);
		if ($curDate > $viimeinen_pvm_paatos_tehty){
			$viimeinen_pvm_paatos_tehty = $curDate; 	
			$viimeinen_pvm_paatos_tehty_date = $date;
		}		
	}	
	
	return $viimeinen_pvm_paatos_tehty_date;
	
}

function laske_hakemusten_lupaviranomaisten_lkm($hakemuksetDTO){
	
	$lupaviranomaiset = array();
	
	for($i=0; $i < sizeof($hakemuksetDTO); $i++){
		if(!in_array($hakemuksetDTO[$i]->Viranomaisen_koodi, $lupaviranomaiset)) array_push($lupaviranomaiset, $hakemuksetDTO[$i]->Viranomaisen_koodi);
	}
	
	return sizeof($lupaviranomaiset);
	
}

function hae_hakemusversion_tietolahteet($db, $hakemusversioDTO){
	
	$haettu_aineistoDAO = new Haettu_aineistoDAO($db);
	$osio_sisaltoDAO = new Osio_sisaltoDAO($db);
	$haettu_luvan_kohdeDAO = new Haettu_luvan_kohdeDAO($db);
	$haettu_muuttujaDAO = new Haettu_muuttujaDAO($db);
	$osioDAO = new OsioDAO($db);
	$luvan_kohdeDAO = new Luvan_kohdeDAO($db);
	
	$viranomaiset = array();
	$biopankit = array("AURIA", "BOREALIS", "FHRB", "HKI_BIO",	"ITAS_BIO",	"KESK_BIO",	"THL_BIO", "TMPR_BIO", "VERIP_BIO");
	
	
	// Selvitetään aineistosta mille viranomaisille lähetetään hakemus
	$aineiston_luvan_kohteet = array();
	$haetut_aineistotDTO = $haettu_aineistoDAO->hae_hakemusversion_haetut_aineistot($hakemusversioDTO->ID);

	for($i=0; $i < sizeof($haetut_aineistotDTO); $i++){

		// Selvitetään poimitaanko kohdejoukko biopankkiaineistosta
		// Kohdejoukko
		$osio_sisaltoDTO_poim_kohd_bio = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($haetut_aineistotDTO[$i]->ID, "FK_Haettu_aineisto", 982);																			
		$biopankkiaineistot_tunnistetaan_kohdejoukosta = (isset($osio_sisaltoDTO_poim_kohd_bio->Sisalto_boolean) && $osio_sisaltoDTO_poim_kohd_bio->Sisalto_boolean==1 ? true : false);
											
		if(!$biopankkiaineistot_tunnistetaan_kohdejoukosta){ // Tapaus
			$osio_sisaltoDTO_poim_tapaus_bio = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($haetut_aineistotDTO[$i]->ID, "FK_Haettu_aineisto", 986);
			$biopankkiaineistot_tunnistetaan_kohdejoukosta = (isset($osio_sisaltoDTO_poim_tapaus_bio->Sisalto_boolean) && $osio_sisaltoDTO_poim_tapaus_bio->Sisalto_boolean==1 ? true : false);
		}
											
		if(!$biopankkiaineistot_tunnistetaan_kohdejoukosta){ // Verrokit
			$osio_sisaltoDTO_poim_verrokit_bio = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($haetut_aineistotDTO[$i]->ID, "FK_Haettu_aineisto", 990);
			$biopankkiaineistot_tunnistetaan_kohdejoukosta = (isset($osio_sisaltoDTO_poim_verrokit_bio->Sisalto_boolean) && $osio_sisaltoDTO_poim_verrokit_bio->Sisalto_boolean==1 ? true : false);
		}											
											
		$haetut_luvan_kohteetDTO = $haettu_luvan_kohdeDAO->hae_haetun_aineiston_haetun_luvan_kohteet($haetut_aineistotDTO[$i]->ID);

		foreach ($haetut_luvan_kohteetDTO as $kohde_tyyppi => $haettu_luvan_kohdeDTO) {
			for($j=0; $j < sizeof($haettu_luvan_kohdeDTO); $j++){
					
				if($haettu_luvan_kohdeDTO[$j]->Luvan_kohdeDTO->ID!=178) array_push($aineiston_luvan_kohteet, $haettu_luvan_kohdeDTO[$j]->Luvan_kohdeDTO->ID);
														
				if(!$biopankkiaineistot_tunnistetaan_kohdejoukosta){ // Selvitetään poimittavista muuttujista mille biopankeille lähetetään hakemus
														
					$haetut_muuttujatDTO = $haettu_muuttujaDAO->hae_haetun_luvan_kohteen_haetut_muuttujat($haettu_luvan_kohdeDTO[$j]->ID);
														
					if(!empty($haetut_muuttujatDTO)){
						for($m=0; $m < sizeof($haetut_muuttujatDTO); $m++){
							if(in_array($haetut_muuttujatDTO[$m]->Muuttujan_koodi, $biopankit)){
								if(in_array($haetut_muuttujatDTO[$m]->Muuttujan_koodi, $viranomaiset)){
									continue;
								} else {
									array_push($viranomaiset, $haetut_muuttujatDTO[$m]->Muuttujan_koodi);
								}
							}
						}
					}
														
				}													
			}
		}
																							
		if($biopankkiaineistot_tunnistetaan_kohdejoukosta){ // Selvitetään kohdejoukon määrittelystä mille biopankeille lähetetään hakemus  
												
			$osiotDTO_biopankkivalinnat = $osioDAO->hae_luokan_osiot("checkbox-997");
											
			for($j=0; $j < sizeof($osiotDTO_biopankkivalinnat); $j++){
													
				$osio_sisaltoDTO_biopankki = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($haetut_aineistotDTO[$i]->ID, "FK_Haettu_aineisto", $osiotDTO_biopankkivalinnat[$j]->ID);
														
				if(isset($osio_sisaltoDTO_biopankki->Sisalto_boolean) && $osio_sisaltoDTO_biopankki->Sisalto_boolean==1){
					if (in_array($osiotDTO_biopankkivalinnat[$j]->Otsikko, $viranomaiset)) {
						continue;
					} else {
						array_push($viranomaiset, $osiotDTO_biopankkivalinnat[$j]->Otsikko);
					}											
				}
													
			}
											
		} 
																																																			
	}
										
	$luvan_kohteet = array_unique($aineiston_luvan_kohteet);

	for($i=0; $i < sizeof($luvan_kohteet); $i++){

		$luvan_kohdeDTO = $luvan_kohdeDAO->hae_luvan_kohde($luvan_kohteet[$i]);

		if(isset($luvan_kohdeDTO->ID)){
			if (in_array($luvan_kohdeDTO->Viranomaisen_koodi, $viranomaiset)) {
				continue;
			} else {
				if($luvan_kohdeDTO->Viranomaisen_koodi=="v_BIO") continue;
				array_push($viranomaiset,$luvan_kohdeDTO->Viranomaisen_koodi);
			}
		}
											
	}
																						
	return $viranomaiset;
	
}

function maarita_hakemuksen_viranomaiset_ja_lupaviranomainen($db, $luvan_kohteet){
	
	$luvan_kohdeDAO = new Luvan_kohdeDAO($db);
	
	$luvan_kohteetDTO = array();
	$viranomaiset = array();
	$viranomaiset_ryhma_1 = array();
	$viranomaiset_ryhma_2 = array();	
	$aineistoja_ryhmasta_1_kpl = 0;
	$aineistoja_ryhmasta_2_kpl = 0;
	
	foreach($luvan_kohteet as $indx => $fk_luvan_kohde){  
	
		$luvan_kohdeDTO = $luvan_kohdeDAO->hae_luvan_kohde($fk_luvan_kohde);
		
		if(isset($luvan_kohdeDTO->Lupaviranomaisen_toimivalta_ryhma)){												
			if($luvan_kohdeDTO->Lupaviranomaisen_toimivalta_ryhma==1){				
				if(!in_array($luvan_kohdeDTO->Viranomaisen_koodi, $viranomaiset_ryhma_1)){
					array_push($viranomaiset_ryhma_1, $luvan_kohdeDTO->Viranomaisen_koodi);
					$aineistoja_ryhmasta_1_kpl++;
				}								
			} 				
			if($luvan_kohdeDTO->Lupaviranomaisen_toimivalta_ryhma==2){
				if(!in_array($luvan_kohdeDTO->Viranomaisen_koodi, $viranomaiset_ryhma_2)){
					array_push($viranomaiset_ryhma_2, $luvan_kohdeDTO->Viranomaisen_koodi);
					$aineistoja_ryhmasta_2_kpl++;
				}
			} 
															
		}
		
		array_push($luvan_kohteetDTO, $luvan_kohdeDTO);
	
	}
	
	foreach($luvan_kohteetDTO as $indx => $luvan_kohdeDTO){   	
		if(isset($luvan_kohdeDTO->ID)){				
			if($luvan_kohdeDTO->Viranomaisen_koodi=="v_BIO"){
				continue;
			} else{
				
				if($luvan_kohdeDTO->Lupaviranomaisen_toimivalta_ryhma==0){ // Muu kuin ryhmiin 1 tai 2 kuuluva organisaatio päättää luvasta aina itse. Sama koskee Tilastokeskuksen muita aineistoja kuin kuolemansyyt ja kuolemansyytilasto.				
					if(!in_array($luvan_kohdeDTO->Viranomaisen_koodi, $viranomaiset)) array_push($viranomaiset, $luvan_kohdeDTO->Viranomaisen_koodi); 					
				}
				
				if($luvan_kohdeDTO->Lupaviranomaisen_toimivalta_ryhma==1){										
					if(($aineistoja_ryhmasta_1_kpl > 1) || ($aineistoja_ryhmasta_1_kpl >= 1 && $aineistoja_ryhmasta_2_kpl >= 1)){
						if(!in_array("v_KLV", $viranomaiset)) array_push($viranomaiset, "v_KLV"); 	
					} else {						
						if($aineistoja_ryhmasta_1_kpl==1){
							if(!in_array($luvan_kohdeDTO->Viranomaisen_koodi, $viranomaiset)) array_push($viranomaiset, $luvan_kohdeDTO->Viranomaisen_koodi); 
						}												
					}										
				}

				if($luvan_kohdeDTO->Lupaviranomaisen_toimivalta_ryhma==2){										
					if($aineistoja_ryhmasta_1_kpl >= 1 && $aineistoja_ryhmasta_2_kpl >= 1){
						if(!in_array("v_KLV", $viranomaiset)) array_push($viranomaiset, "v_KLV"); 	
					} else {
						if($aineistoja_ryhmasta_2_kpl >= 1){
							if(!in_array($luvan_kohdeDTO->Viranomaisen_koodi, $viranomaiset)) array_push($viranomaiset, $luvan_kohdeDTO->Viranomaisen_koodi); 
						}
					}					
				} 
				
			} 											
		}			
	}

	return $viranomaiset;
	
}

function verifyDate($date){
	return (DateTime::createFromFormat('Y-m-d H:i:s', $date) !== false);
}

?>