<?php 

function sisallyta_kielitiedosto($kieli){
	if(!is_null($kieli) && ($kieli=="en" || kieli=="fi")){
		include_once sprintf(LANGUAGE_FILES_BASE, $kieli);
	} else {
		include_once("lang_fi.php"); // Oletuskieli on suomi
	}	
}

function paivita_osioiden_tilat($dto_taulukko, $osiotDTO, $sivu, $yhteys){
	
	if(is_array($osiotDTO)){
		foreach($osiotDTO as $fk_osio => $osioDTO){
			$osiotDTO[$fk_osio] = paivita_osion_tila($osioDTO, $osiotDTO, $yhteys, $dto_taulukko);
		}
	}

	return $osiotDTO;
	
}

function paivita_osion_tila($osioDTO_paivitettava, $osiotDTO, $yhteys, $dto_taulukko){
	
	for($i=0; $i < sizeof($osioDTO_paivitettava->Osio_saannotDTO); $i++){
		
		$osio_saanto_on_tosi = true;

		if(!is_null($osioDTO_paivitettava->Osio_saannotDTO[$i]->Osio_lauseetDTO)){

			for($j=0; $j < sizeof($osioDTO_paivitettava->Osio_saannotDTO[$i]->Osio_lauseetDTO); $j++){
				if(!alkeisdisjunktio_on_tosi($osioDTO_paivitettava->Osio_saannotDTO[$i]->Osio_lauseetDTO[$j],$osiotDTO)){
					$osio_saanto_on_tosi = false;
					break;
				}
			}
		} else {
			$osio_saanto_on_tosi = true;
		}
		
		if($osio_saanto_on_tosi){

			if($osioDTO_paivitettava->Osio_saannotDTO[$i]->Saanto=="pakollinen") $osioDTO_paivitettava->Pakollinen_tieto = 1;
									
			if($osioDTO_paivitettava->Osio_saannotDTO[$i]->Saanto=="tyhjenna" || $osioDTO_paivitettava->Osio_saannotDTO[$i]->Saanto=="piilota"){

				if(isset($osioDTO_paivitettava->Osio_sisaltoDTO->ID) && !is_null($osioDTO_paivitettava->Osio_sisaltoDTO->ID)){ // Osio_sisalto merkitään poistetuksi databeissis
					$vastaus = suorita_datakerroksen_funktio($yhteys, "poista_osion_sisalto", muotoile_soap_parametrit(array("fk_osio_sisalto"=>$osioDTO_paivitettava->Osio_sisaltoDTO->ID, "token"=>$dto_taulukko["token"], "kayt_id"=>$dto_taulukko["kayt_id"])));
				}
				
				$osioDTO_paivitettava->Osio_sisaltoDTO = null;

			}
			
			$osioDTO_paivitettava->Tila[$i] = $osioDTO_paivitettava->Osio_saannotDTO[$i]->Saanto;

		}

		if(!$osio_saanto_on_tosi && $osioDTO_paivitettava->Osio_saannotDTO[$i]->Saanto=="nayta"){
			$osioDTO_paivitettava->Tila[$i] = "piilota";
		}
		
	}
	
	return $osioDTO_paivitettava;
	
}

function alkeisdisjunktio_on_tosi($alkeisdisjunktio,$osiotDTO){
	
	for($p=0; $p < sizeof($alkeisdisjunktio); $p++){
		if(lause_on_tosi($alkeisdisjunktio[$p],$osiotDTO)){
			return true;
		}
	}

	return false;
}

/*
function paivita_asiakirjan_ehdollinen_tila($asiakirjan_hallintaDTO){
	if(isset($asiakirjan_hallintaDTO->Asiakirjahallinta_saannotDTO) && !empty($asiakirjan_hallintaDTO->Asiakirjahallinta_saannotDTO)){
		for($i=0; $i < sizeof($asiakirjan_hallintaDTO->Asiakirjahallinta_saannotDTO); $i++){

			$saanto_on_tosi = true;

			if(!is_null($asiakirjan_hallintaDTO->Asiakirjahallinta_saannotDTO[$i]->Osio_lauseetDTO)){

				for($j=0; $j < sizeof($osioDTO_paivitettava->Osio_saannotDTO[$i]->Osio_lauseetDTO); $j++){
					if(!alkeisdisjunktio_on_tosi($osioDTO_paivitettava->Osio_saannotDTO[$i]->Osio_lauseetDTO[$j],$osiotDTO)){
						$saanto_on_tosi = false;
						break;
					}
				}
			} else {
				$saanto_on_tosi = true;
			}
		}
	}

	return $asiakirjan_hallintaDTO;
}
*/

function lause_on_tosi($osio_lauseDTO, $osiotDTO_taulu){

	if($osio_lauseDTO->Predikaatti=="Ei_valittu"){

		if(!isset($osiotDTO_taulu[$osio_lauseDTO->OsioDTO_Muuttuja->ID]->Osio_sisaltoDTO->ID) || ( is_null($osiotDTO_taulu[$osio_lauseDTO->OsioDTO_Muuttuja->ID]->Osio_sisaltoDTO->Sisalto_date) && is_null($osiotDTO_taulu[$osio_lauseDTO->OsioDTO_Muuttuja->ID]->Osio_sisaltoDTO->Sisalto_boolean) && is_null($osiotDTO_taulu[$osio_lauseDTO->OsioDTO_Muuttuja->ID]->Osio_sisaltoDTO->Sisalto_text))){
			return true;
		}
	}
	if($osio_lauseDTO->Predikaatti=="Valittu"){
		if(isset($osio_lauseDTO->OsioDTO_Muuttuja->ID) && isset($osiotDTO_taulu[$osio_lauseDTO->OsioDTO_Muuttuja->ID]->Osio_sisaltoDTO) && (!is_null($osiotDTO_taulu[$osio_lauseDTO->OsioDTO_Muuttuja->ID]->Osio_sisaltoDTO->Sisalto_date) || !is_null($osiotDTO_taulu[$osio_lauseDTO->OsioDTO_Muuttuja->ID]->Osio_sisaltoDTO->Sisalto_boolean) || !is_null($osiotDTO_taulu[$osio_lauseDTO->OsioDTO_Muuttuja->ID]->Osio_sisaltoDTO->Sisalto_text))){
			return true;
		}
	}
	
	return false;
	
}

// todo: optimointia
function segmentit_pdf_rakenteeseen($parametrit){
		
	if(isset($parametrit["lomakkeen_sivutDTO"])){
		$lomakkeen_sivutDTO = $parametrit["lomakkeen_sivutDTO"];
	} else {
		throw new SoapFault(ERR_MISSING_PARAMETER, "Parametri puuttuu: lomakkeen_sivutDTO");
	}
	
	$luvan_kohteetDTO_taika = null;
	
	if(isset($parametrit["luvan_kohteetDTO_taika"])) $luvan_kohteetDTO_taika = $parametrit["luvan_kohteetDTO_taika"];
	if(isset($parametrit["hakemusversioDTO"])) $hakemusversioDTO = $parametrit["hakemusversioDTO"];
	$kieli = (isset($parametrit["kieli"]) ? $parametrit["kieli"] : "fi"); // Oletuskieli on suomi
	
	$segments = array();
	$orders = array();
	$block_orders = array();
	
	if (is_array($lomakkeen_sivutDTO)) { 
		foreach ($lomakkeen_sivutDTO as $sivun_tunniste => $lomakkeen_sivuDTO) {
		
			if($sivun_tunniste=="hakemus_liitteet") continue;
		
			$segment = array();
			$osiotDTO_taulu = $lomakkeen_sivuDTO->OsiotDTO_taulu;
			$segment["heading"] = kaanna_osion_kentta($lomakkeen_sivuDTO, "Nimi", $kieli);
			$segment["blocks"] = array();
			$blocks = array();
			$blocks_sorted = array();
									
			if($sivun_tunniste=="hakemus_organisaatiotiedot" && isset($hakemusversioDTO->Osallistuvat_organisaatiotDTO)){
				
				$block = array();
				$block["block_title"] = koodin_selite("TUTK_OS_ORG", $kieli);
				$block["titles"] = array();
				
				for($i=0; $i < sizeof($hakemusversioDTO->Osallistuvat_organisaatiotDTO); $i++){
										
					// Org. nimi
					if(isset($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Nimi) && !empty($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Nimi)){
							
						$title = luo_pdf_otsikko("NIMI", true);												
						array_push($title["fields"], luo_pdf_kentta("textarea", $hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Nimi, null, null));
						array_push($block["titles"], $title);
				
					}
					
					// Org. osoite
					if(isset($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Osoite) && !empty($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Osoite)){
																	
						$title = luo_pdf_otsikko("OSOITE", true);												
						array_push($title["fields"], luo_pdf_kentta("textarea", $hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Osoite, null, null));
						array_push($block["titles"], $title);						
										
					}	

					// Org. osoite
					if(isset($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Y_tunnus) && !empty($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Y_tunnus)){
																			
						$title = luo_pdf_otsikko("Y_TUNNUS", true);												
						array_push($title["fields"], luo_pdf_kentta("textarea", $hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Y_tunnus, null, null));
						array_push($block["titles"], $title);							
				
					}	

					// Org. rooli
					if(isset($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Rooli) && !empty($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Rooli)){	
					
						$title = luo_pdf_otsikko("ROOLI_JA_VAST", true);												
						array_push($title["fields"], luo_pdf_kentta("textarea", $hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Rooli, null, null));
						array_push($block["titles"], $title);
						
					}	

					// Org. edustaja
					if(isset($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Edustaja) && !empty($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Edustaja)){
													
						$title = luo_pdf_otsikko("ORG_VIR_ED", true);												
						array_push($title["fields"], luo_pdf_kentta("textarea", $hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Edustaja, null, null));
						array_push($block["titles"], $title);						
				
					}	

					// Org. edustaja
					if(isset($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Edustajan_email) && !empty($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Edustajan_email)){
													
						$title = luo_pdf_otsikko("VIR_ED_SAHK", true);												
						array_push($title["fields"], luo_pdf_kentta("textarea", $hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Edustajan_email, null, null));
						array_push($block["titles"], $title);						
				
					}

					// Org. MTA allekirjoittaja
					if(isset($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->MTA_allekirjoittaja) && !is_null($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->MTA_allekirjoittaja)){
																																								
						$title = luo_pdf_otsikko("ONKO_MTA_AK", true);
						
						$selected = false;						
						if($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->MTA_allekirjoittaja==1) $selected = true;							
						array_push($title["fields"], luo_pdf_kentta("radio", null, "Kyllä", $selected));
																														
						$selected = false;						
						if($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->MTA_allekirjoittaja==0) $selected = true;		
						array_push($title["fields"], luo_pdf_kentta("radio", null, "Ei", $selected));
										
						array_push($block["titles"], $title);
				
					}

					// Org. MTA rek. pitaja
					if(isset($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Rekisterinpitaja) && !is_null($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Rekisterinpitaja)){
						
						$title = luo_pdf_otsikko("REKISTERINPITAJA", true);	
						$title["show_hr_bottom"] = true;
						
						$selected = false;						
						if($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Rekisterinpitaja==1) $selected = true;																																							
						array_push($title["fields"], luo_pdf_kentta("radio", null, "Kyllä", $selected));						
												
						$selected = false;						
						if($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Rekisterinpitaja==0) $selected = true;																																							
						array_push($title["fields"], luo_pdf_kentta("radio", null, "Ei", $selected));						
																		
						array_push($block["titles"], $title);
				
					}					
					
				}
				
				$segment["blocks"][0] = $block;
				
			} else if($sivun_tunniste=="hakemus_viranomaiskohtaiset" && is_array($osiotDTO_taulu)){ // Kesken
				
				$block = array();
				$block["block_title"] = koodin_selite("VIRANOMAISKOHTAISET", "fi");
				$block["titles"] = array();	

				foreach ($osiotDTO_taulu as $id => $osioDTO) { 	
					if($osioDTO->Osio_tyyppi=="kysymys_ja_textarea_large" && isset($osioDTO->Osio_sisaltoDTO->Sisalto_text) && !is_null($osioDTO->Osio_sisaltoDTO->Sisalto_text)){
						$title = luo_pdf_otsikko($osioDTO->Otsikko, true);												
						array_push($title["fields"], luo_pdf_kentta("textarea", $osioDTO->Osio_sisaltoDTO->Sisalto_text, null, null));
						array_push($block["titles"], $title);	
					}		
				}

				$segment["blocks"][0] = $block;	
				
			} else if($sivun_tunniste=="hakemus_tutkimusryhma" && (isset($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat) || isset($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat))) {				
				if(!empty($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat))	$segment["blocks"][0] = luo_pdf_tutkimusryhma_lohko($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat, "TUTKIMUSRYHMA_1OTSIKKO");														
				if(!empty($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat))	$segment["blocks"][1] = luo_pdf_tutkimusryhma_lohko($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat, "TUTKIMUSRYHMA_2OTSIKKO");	
			} else {						
				if(is_array($osiotDTO_taulu) && !empty($osiotDTO_taulu)){
						
					//if(($sivun_tunniste=="hakemus_perustiedot" || $sivun_tunniste=="hakemus_aineisto") && is_array($osiotDTO_taulu)) {
					
					// Kootaan taulu $block ja $orders
					foreach ($osiotDTO_taulu as $id => $osioDTO) {
						
						if($osioDTO->Osio_tyyppi=="laatikko_otsikko"){		
							
							$block = array();
							
							if(!is_null($osioDTO->Otsikko)){
								$block["block_title"] = kaanna_osion_kentta($osioDTO, "Otsikko", $kieli); 
							} else {
								$block["block_title"] = "";
							}
							
							$block["parent_id"] = $osioDTO->OsioDTO_parent->ID;	
							$block["titles"] = array();
							
							unset($osiotDTO_taulu[$id]);	
							array_push($blocks, $block);	
						
						} else if($osioDTO->Osio_tyyppi=="laatikko"){
							if(is_null($osioDTO->OsioDTO_parent->ID) || $osioDTO->OsioDTO_parent->ID==1034 || $osioDTO->OsioDTO_parent->ID==3084 || $osioDTO->OsioDTO_parent->ID==3085){
								$orders[$osioDTO->ID] = $osioDTO->Jarjestys;
								unset($osiotDTO_taulu[$id]);
							}
							
						} else if($osioDTO->Osio_tyyppi=="laatikko_sisalto" || $osioDTO->Osio_tyyppi=="taulukko"){
							continue;
						} else if($osioDTO->Osio_tyyppi=="haettu_kohde_viitehenkilot_muuttujat" || $osioDTO->Osio_tyyppi=="haettu_kohde_verrokit_muuttujat" || $osioDTO->Osio_tyyppi=="haettu_kohde_tapaukset_muuttujat" || $osioDTO->Osio_tyyppi=="haettu_kohde_kohdejoukko_muuttujat" || $osioDTO->Osio_tyyppi=="haettu_kohde_verrokit" || $osioDTO->Osio_tyyppi=="haettu_kohde_tapaukset" || $osioDTO->Osio_tyyppi=="haettu_kohde_viitehenkilot" || $osioDTO->Osio_tyyppi=="haettu_kohde_kohdejoukko"){						
							if(isset($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$osioDTO->Osio_tyyppi])){														
								$blocks_sorted[$osioDTO->Jarjestys] = luo_pdf_haetut_luvan_kohteet_lohko($osioDTO->Jarjestys, $osioDTO->Osio_tyyppi, $hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$osioDTO->Osio_tyyppi], $luvan_kohteetDTO_taika);							
								unset($osiotDTO_taulu[$id]);
							}						
						} else {
							unset($osiotDTO_taulu[$id]);
						}
						
					}
					
					$osiotDTO_laatikko_sisallot = $osiotDTO_taulu;
					
					foreach ($osiotDTO_laatikko_sisallot as $ls_id => $osioDTO_laatikko_sisalto) { 				
						for($i=0; $i < sizeof($blocks); $i++){
							if($blocks[$i]["parent_id"]==$osioDTO_laatikko_sisalto->OsioDTO_parent->ID){ // parent: Osio_tyyppi = laatikko
														
								$osiotDTO_kaikki = $lomakkeen_sivuDTO->OsiotDTO_taulu;
								$osiotDTO_ls = array();
								$taulu_ids = array();
								$title_ids = array();
								
								// Kootaan kaikki laatikko sisällön id:t ja osiot tauluihin $titles_id ja $osiotDTO_ls
								foreach ($osiotDTO_kaikki as $id => $osioDTO) {
									if($osioDTO->OsioDTO_parent->ID==$osioDTO_laatikko_sisalto->ID){
										if($osioDTO->Osio_tyyppi=="taulukko") array_push($taulu_ids, $osioDTO->ID);
										$osiotDTO_ls[$id] = $osioDTO;
										array_push($title_ids, $id);	
									} 								
								}	
								
								foreach ($osiotDTO_kaikki as $id => $osioDTO) { 
									if(in_array($osioDTO->OsioDTO_parent->ID,$title_ids) || in_array($osioDTO->OsioDTO_parent->ID,$taulu_ids)) $osiotDTO_ls[$id] = $osioDTO;
								}
								
								$blocks[$i]["order"] = $orders[$osioDTO_laatikko_sisalto->OsioDTO_parent->ID];
								$blocks_sorted[$orders[$osioDTO_laatikko_sisalto->OsioDTO_parent->ID]] = $blocks[$i];
								
								$pdf_osiot_param = array();
								$pdf_osiot_param["osiotDTO_taulu"] = $osiotDTO_ls;
								$pdf_osiot_param["hakemusversioDTO"] = $hakemusversioDTO;
								
								$blocks_sorted[$orders[$osioDTO_laatikko_sisalto->OsioDTO_parent->ID]]["titles"] = osiot_pdf_rakenteeseen($pdf_osiot_param);
									
							}
						}				
					}
																								
					// Järjestetään blockit		
					usort($blocks_sorted, jarjesta_taulu_nro_asc("order"));			
					$segment["blocks"] = $blocks_sorted;
				
				}				
			}
							
			array_push($segments, $segment);
		
		}	
	}
				
	return $segments;
	
}

function luo_pdf_haetut_luvan_kohteet_lohko($block_order, $kohde_tyyppi, $haetut_luvan_kohteetDTO, $luvan_kohteetDTO_taika){
	
	$block = array();
	$block["order"] = $block_order;
	$block["titles"] = array();	
	
	if($kohde_tyyppi=="haettu_kohde_kohdejoukko") $block["block_title"] = REK_MAAR1 . " " . KOHDEJOUKKO2;  
	if($kohde_tyyppi=="haettu_kohde_viitehenkilot") $block["block_title"] = REK_MAAR_VIITE;  
	if($kohde_tyyppi=="haettu_kohde_tapaukset") $block["block_title"] = REK_MAAR2 . " " . TAPAUKSET;	
	if($kohde_tyyppi=="haettu_kohde_verrokit") $block["block_title"] = REK_MAAR2 . " " . j_verrokki; 
	if($kohde_tyyppi=="haettu_kohde_kohdejoukko_muuttujat") $block["block_title"] = KOHD_POIM;  
	if($kohde_tyyppi=="haettu_kohde_tapaukset_muuttujat") $block["block_title"] = MAARITTELE_TAPAUKSEN_MUUTTUJAT;  
	if($kohde_tyyppi=="haettu_kohde_verrokit_muuttujat") $block["block_title"] = MAARITTELE_VERROKIT_MUUTTUJAT;  
	if($kohde_tyyppi=="haettu_kohde_viitehenkilot_muuttujat") $block["block_title"] = MAARITTELE_VIITEHENKILO_MUUTTUJAT; 
	
	for($i=0; $i < sizeof($haetut_luvan_kohteetDTO); $i++){		
		if(isset($haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->ID)){
		
			// Viranomainen
			$title = luo_pdf_otsikko("VIRANOMAINEN", true);
			array_push($title["fields"], luo_pdf_kentta("textarea", koodin_selite($haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->Viranomaisen_koodi,"fi"), null, null));	
			array_push($block["titles"], $title);
			
			// Rekisteri
			$title = luo_pdf_otsikko("REK_TILASTOAIN", true);
			array_push($title["fields"], luo_pdf_kentta("textarea", $haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->Luvan_kohteen_nimi, null, null));	
			array_push($block["titles"], $title);
			
			// THL kysymykset
			if($haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->ID==180 || $haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->ID==179){
				
				if(!is_null($haetut_luvan_kohteetDTO[$i]->Toimintayksikot )){					
					if($haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->ID==180) $title = luo_pdf_otsikko("TKPSST", true);
					if($haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->ID==179) $title = luo_pdf_otsikko("TKPSTT", true);						
					array_push($title["fields"], luo_pdf_kentta("textarea", $haetut_luvan_kohteetDTO[$i]->Toimintayksikot, null, null));	
					array_push($block["titles"], $title);				
				}

				if(!is_null($haetut_luvan_kohteetDTO[$i]->Kohdejoukon_mukaanottokriteerit )){					
					$title = luo_pdf_otsikko("KOHD_MK", true);					 
					array_push($title["fields"], luo_pdf_kentta("textarea", $haetut_luvan_kohteetDTO[$i]->Kohdejoukon_mukaanottokriteerit, null, null));	
					array_push($block["titles"], $title);				
				}	

				if(!is_null($haetut_luvan_kohteetDTO[$i]->Toimintayksikoihin_on_oltu_yhteydessa)){
					
					$title = luo_pdf_otsikko("TOIM_YHT", true);		
					$title["show_hr_bottom"] = true;	
					
					if($haetut_luvan_kohteetDTO[$i]->Toimintayksikoihin_on_oltu_yhteydessa==1){
						array_push($title["fields"], luo_pdf_kentta("radio", null, "Kyllä", true));	
						array_push($title["fields"], luo_pdf_kentta("radio", null, "Ei", false));							
					} else {
						array_push($title["fields"], luo_pdf_kentta("radio", null, "Kyllä", false));	
						array_push($title["fields"], luo_pdf_kentta("radio", null, "Ei", true));							
					}

					array_push($block["titles"], $title);	
					
				}				
			
			} else if ($haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Biopankki"){
				
				if(!is_null($haetut_luvan_kohteetDTO[$i]->Kuvaus_naytteista)){ 
					$title = luo_pdf_otsikko("TARK_KUV_NAYT", true);
					$title["show_hr_bottom"] = true;
					array_push($title["fields"], luo_pdf_kentta("textarea", $haetut_luvan_kohteetDTO[$i]->Kuvaus_naytteista, null, null));	
					array_push($block["titles"], $title);						
				}				
			
			} else {
				
				// Poimittavat muuttujat	
				if(isset($haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->Luvan_kohteen_tyyppi) && ($haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Aineistokatalogi" || $haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Taika_tilastoaineisto")){
					if(isset($luvan_kohteetDTO_taika[$haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->Identifier]->MuuttujatDTO) && isset($haetut_luvan_kohteetDTO[$i]->Haetut_muuttujatDTO) && !empty($haetut_luvan_kohteetDTO[$i]->Haetut_muuttujatDTO)){					
						
						$title = luo_pdf_otsikko("POIM_MUUT2", true);	
						$field = "";
						$m_lkm = 0;
					
						for($j=0; $j < sizeof($luvan_kohteetDTO_taika[$haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->Identifier]->MuuttujatDTO); $j++){						
							for($l=0; $l < sizeof($haetut_luvan_kohteetDTO[$i]->Haetut_muuttujatDTO); $l++){
								if($luvan_kohteetDTO_taika[$haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$j]->Tunnus==$haetut_luvan_kohteetDTO[$i]->Haetut_muuttujatDTO[$l]->Muuttujan_koodi){
									
									if($m_lkm==0){
										$field .= $luvan_kohteetDTO_taika[$haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$j]->Nimi;
									} else {
										$field .= ", " . $luvan_kohteetDTO_taika[$haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$j]->Nimi;
									}
									
									$m_lkm++;
																	
								}
							}						
						}

						array_push($title["fields"], luo_pdf_kentta("textarea", $field, null, null));
						array_push($block["titles"], $title);	
						
					}
				} else { 				
					if(!is_null($haetut_luvan_kohteetDTO[$i]->Muuttujat_lueteltuna)){ 
						$title = luo_pdf_otsikko("POIM_MUUT2", true);
						array_push($title["fields"], luo_pdf_kentta("textarea", $haetut_luvan_kohteetDTO[$i]->Muuttujat_lueteltuna, null, null));	
						array_push($block["titles"], $title);				
					}				
				}
				
				// Poiminta-ajankohdat
				if(!is_null($haetut_luvan_kohteetDTO[$i]->Poiminta_ajankohdat)){
					$title = luo_pdf_otsikko("POIMINTAAJANKOHDAT", true);
					$title["show_hr_bottom"] = true;
					array_push($title["fields"], luo_pdf_kentta("textarea", $haetut_luvan_kohteetDTO[$i]->Poiminta_ajankohdat, null, null));	
					array_push($block["titles"], $title);				
				}				
				
			}
									
		}		
	}
	
	return $block;
	
}

function luo_pdf_tutkimusryhma_lohko($hakijatDTO, $block_title){
	
	$block = array();
	$block["block_title"] = koodin_selite($block_title, "fi");
	$block["titles"] = array();					
					
	for($i=0; $i < sizeof($hakijatDTO); $i++){
						
		// Nimi
		$title = luo_pdf_otsikko("NIMI", true);												
		array_push($title["fields"], luo_pdf_kentta("textarea", $hakijatDTO[$i]->Etunimi . " " . $hakijatDTO[$i]->Sukunimi, null, null));
		array_push($block["titles"], $title);							
						
		// Sahkopostiosoite
		if(!is_null($hakijatDTO[$i]->Sahkopostiosoite)){							
			$title = luo_pdf_otsikko("SAHKOPOSTIOSOITE", true);												
			array_push($title["fields"], luo_pdf_kentta("textarea", $hakijatDTO[$i]->Sahkopostiosoite, null, null));
			array_push($block["titles"], $title);															
		}
						
		// Oppiarvo
		if(!is_null($hakijatDTO[$i]->Oppiarvo)){						
			$title = luo_pdf_otsikko("OPPIARVO", true);												
			array_push($title["fields"], luo_pdf_kentta("textarea", $hakijatDTO[$i]->Oppiarvo, null, null));
			array_push($block["titles"], $title);														
		}
						
		// Organisaatio
		if(!is_null($hakijatDTO[$i]->Organisaatio)){							
			$title = luo_pdf_otsikko("ORGANISAATIO", true);												
			array_push($title["fields"], luo_pdf_kentta("textarea", $hakijatDTO[$i]->Organisaatio, null, null));
			array_push($block["titles"], $title);															
		}

		// Osoite
		if(!is_null($hakijatDTO[$i]->Osoite)){							
			$title = luo_pdf_otsikko("OSOITE", true);												
			array_push($title["fields"], luo_pdf_kentta("textarea", $hakijatDTO[$i]->Osoite, null, null));
			array_push($block["titles"], $title);															
		}	

		// Puhelin
		if(!is_null($hakijatDTO[$i]->Puhelin)){							
			$title = luo_pdf_otsikko("PUHELIN", true);	
			$title["show_hr_bottom"] = true;	
			array_push($title["fields"], luo_pdf_kentta("textarea", $hakijatDTO[$i]->Puhelin, null, null));
			array_push($block["titles"], $title);															
		}							
						
	}	
	
	return $block;
	
}

function luo_pdf_kentta($type, $content, $field_text, $selected){
	
	$field = array();
	$field["type"] = $type;	
	
	if(!is_null($content)) $field["content"] = $content;	
	if(!is_null($field_text)) $field["field_text"] = $field_text;
	if(!is_null($selected)) $field["selected"] = $selected;
	
	return $field;
}

function luo_pdf_otsikko($title_text, $complete){
	
	$title = array();							
	$title["title_text"] = koodin_selite($title_text, "fi"); // todo : lisättävä multilang support
	$title["complete"] = $complete;
	$title["fields"] = array();	
	
	return $title;
	
}

function osiot_pdf_rakenteeseen($parametrit){
	
	$osiotDTO_taulu = (isset($parametrit["osiotDTO_taulu"]) ? $parametrit["osiotDTO_taulu"] : null); 
	$hakemusversioDTO = (isset($parametrit["hakemusversioDTO"]) ? $parametrit["hakemusversioDTO"] : null); 
	$kieli = (isset($parametrit["kieli"]) ? $parametrit["kieli"] : "fi"); // Oletuskieli on suomi
	
	$titles = array();
	$titles_id = array();
	
	// Kerätään otsikot
    if (is_array($osiotDTO_taulu)) {
        foreach ($osiotDTO_taulu as $id => $osioDTO) {
			
            if($osioDTO->Osio_tyyppi=="kysymys" || $osioDTO->Osio_tyyppi=="taulukko"){
						
                $title = array();
				
				if(!is_null($osioDTO->Otsikko)){
					$title["title_text"] = kaanna_osion_kentta($osioDTO, "Otsikko", $kieli);
				} else {
					$title["title_text"] = "";
				}
                
				$title["id"] = $osioDTO->ID;
                $title["fields"] = array();
				$title["complete"] = false;
                $titles_id[$osioDTO->ID] = $title;

            }
			
			if($osioDTO->Osio_tyyppi=="lomake_tutkimuksen_nimi"){
				$title = luo_pdf_otsikko("TUTKIMUKSEN_NIMI", true);
				array_push($title["fields"], luo_pdf_kentta("textarea", $hakemusversioDTO->Tutkimuksen_nimi, null, null));
				$titles_id[$osioDTO->ID] = $title;
			}
			
        }
	    	
		// Kerätään kentät
        foreach ($osiotDTO_taulu as $id => $osioDTO) {
            if ($osioDTO->Osio_tyyppi == "date_start" || $osioDTO->Osio_tyyppi == "date_end" || $osioDTO->Osio_tyyppi == "radio" || $osioDTO->Osio_tyyppi == "checkbox" || $osioDTO->Osio_tyyppi == "textarea" || $osioDTO->Osio_tyyppi == "textarea_large") {
				
                $field = array();
                $field["type"] = $osioDTO->Osio_tyyppi;
				$field_complete = false;

                if ($osioDTO->Osio_tyyppi == "textarea" || $osioDTO->Osio_tyyppi == "textarea_large") {
					$field["content"] = $osioDTO->Osio_sisaltoDTO->Sisalto_text;
					if ($field["content"]!='') $field_complete = true;
				}
				
				if($osioDTO->Osio_tyyppi == "date_start"){
					$field["content"] = "Alkaa: " . muotoilepvm($osioDTO->Osio_sisaltoDTO->Sisalto_date, $kieli);
					if ($field["content"]!='') $field_complete = true;
				}

				if($osioDTO->Osio_tyyppi == "date_end"){
					$field["content"] = "Loppuu: " . muotoilepvm($osioDTO->Osio_sisaltoDTO->Sisalto_date, $kieli);
					if ($field["content"]!='') $field_complete = true;
				}				
				
                if ($osioDTO->Osio_tyyppi == "radio" || $osioDTO->Osio_tyyppi == "checkbox") {

                    $field["field_text"] = kaanna_osion_kentta($osioDTO, "Otsikko", $kieli);

                    if (isset($osioDTO->Osio_sisaltoDTO->Sisalto_boolean) && $osioDTO->Osio_sisaltoDTO->Sisalto_boolean == 1) {
                        $field["selected"] = true;
						$field_complete = true;
                    } else {
                        $field["selected"] = false;
                    }

                }

                if (is_array($titles_id[$osioDTO->OsioDTO_parent->ID]["fields"])) array_push($titles_id[$osioDTO->OsioDTO_parent->ID]["fields"], $field);
									
				if ($field_complete === true) $titles_id[$osioDTO->OsioDTO_parent->ID]['complete'] = true;
								
            }
        }
    }

	foreach ($titles_id as $id => $title) {
		if (isset($title['fields']) && $title['complete'] === true) array_push($titles, $title);	
	}	
		
	return $titles;
	
}

function kaanna_osion_kentta($obj, $kentan_nimi, $kieli){
	if(is_null($kieli) || $kieli=="") $kieli = "fi"; // Oletuskieli on suomi

	// Tarkistetaan ensin löytyykö staattinen käännös
	$kaannos = koodin_selite($obj->$kentan_nimi,$kieli);

	if($kaannos){
		return koodin_selite($obj->$kentan_nimi,$kieli);
	} else {
		$kentan_nimi = $kentan_nimi . "_" . $kieli;

		if(isset($obj->$kentan_nimi) && !is_null($obj->$kentan_nimi) && !empty($obj->$kentan_nimi) && $obj->$kentan_nimi!=""){
			return $obj->$kentan_nimi;
		}
	}

	return null;
}

function koodin_selite($koodi, $lang) {
	if (defined($koodi)) {
		return constant($koodi);
	} else {
		return 0;
	}
}

function tarkista_hakemusversion_puuttuvat_tiedot($hakemusversioDTO, $tutkimuksen_sitoumuksetDTO, $jarjestelman_hakijan_roolitDTO){

	if(isset($hakemusversioDTO->Lomakkeen_sivutDTO) && !empty($hakemusversioDTO->Lomakkeen_sivutDTO)){
		foreach($hakemusversioDTO->Lomakkeen_sivutDTO as $sivun_tunniste => $lomakkeen_sivuDTO){

			$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = false;

			if($sivun_tunniste=="hakemus_organisaatiotiedot" && isset($hakemusversioDTO->Osallistuvat_organisaatiotDTO)){ // Pakollista on, että vähintään yhden organisaation kohdalla on merkintä, että se on rekisterinpitäjä
				
				$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = true;
				
				for($i=0; $i < sizeof($hakemusversioDTO->Osallistuvat_organisaatiotDTO); $i++){
					if(isset($hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Rekisterinpitaja) && $hakemusversioDTO->Osallistuvat_organisaatiotDTO[$i]->Rekisterinpitaja==1){
						$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = false;
						break;
					} 
				}
				
			}
			
			// Tarkitetaan löytyykö haetusta aineistosta haettu luvan kohde
			if($sivun_tunniste=="hakemus_aineisto"){
				if(isset($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO) && !empty($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO)){
					foreach($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO as $kohde_tyyppi => $luvan_kohteet_tyyppi){
						foreach($luvan_kohteet_tyyppi as $key => $haettu_luvan_kohdeDTO){
							if($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi=="v_0"){ // Ei viranomaista
								$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = true;
							}
						}
					}
				} else {
					$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = true;
				}
			}
			
			if($sivun_tunniste=="hakemus_liitteet"){
				for($i=0; $i < sizeof($hakemusversioDTO->Asiakirjahallinta_liitteetDTO); $i++){
					if(!empty($hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO)){
						for($j=0; $j < sizeof($hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO); $j++){
							// Liite on pakollinen (ilman ehtoja)
							if($hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO[$j]->Saanto=="liite_on_pakollinen"){

								$hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Liite_on_pakollinen = 1;
								$pakollinen_liite_loydetty = false;

								if(empty($hakemusversioDTO->LiitteetDTO)){
									$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = 1;
								} else {
									for($l=0; $l < sizeof($hakemusversioDTO->LiitteetDTO); $l++){
										if((int)$hakemusversioDTO->LiitteetDTO[$l]->Liitteen_tyypin_koodi==$hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->ID){
											$pakollinen_liite_loydetty = true;
											$hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Liite_puuttuu = 0;
											break;
										}
									}
								}
								if(!$pakollinen_liite_loydetty){
									$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = 1; $hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Liite_puuttuu = 1;
								}
							}
							// Liite on ehdollisesti pakollinen
							if($hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO[$j]->Saanto=="liite_on_ehdollisesti_pakollinen" && !is_null($hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO[$j]->Osio_lauseetDTO)){

								$hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Liite_on_pakollinen = 0;

								foreach($hakemusversioDTO->Lomakkeen_sivutDTO as $st => $lom_sivuDTO){ 
									if(isset($lom_sivuDTO->OsiotDTO_taulu) && !empty($lom_sivuDTO->OsiotDTO_taulu)){
										if(lause_on_tosi($hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Asiakirjahallinta_saannotDTO[$j]->Osio_lauseetDTO[0], $lom_sivuDTO->OsiotDTO_taulu)){
											$hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Liite_on_pakollinen = 1;
										}
									}
								}

								// Duplikaattikoodia, todo: tää tästä metodi
								if($hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Liite_on_pakollinen){
									$pakollinen_liite_loydetty = false;

									if(empty($hakemusversioDTO->LiitteetDTO)){
										$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = 1;
									} else {
										for($l=0; $l < sizeof($hakemusversioDTO->LiitteetDTO); $l++){
											if((int)$hakemusversioDTO->LiitteetDTO[$l]->Liitteen_tyypin_koodi==$hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->ID){
												$pakollinen_liite_loydetty = true;
												$hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Liite_puuttuu = 0;
												break;
											}
										}
									}
									if(!$pakollinen_liite_loydetty){
										$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = 1; $hakemusversioDTO->Asiakirjahallinta_liitteetDTO[$i]->Liite_puuttuu = 1;
									}
								}

							}
						}
					}
				}
			}
			
			if(isset($lomakkeen_sivuDTO->OsiotDTO_taulu) && !empty($lomakkeen_sivuDTO->OsiotDTO_taulu)){
				foreach($lomakkeen_sivuDTO->OsiotDTO_taulu as $fk_osio => $osioDTO){
					if($osioDTO->Pakollinen_tieto){
						// Osioiden pakolliset tiedot
						if($osioDTO->Osio_tyyppi=="kysymys_ja_textarea_large" || $osioDTO->Osio_tyyppi=="radio" || $osioDTO->Osio_tyyppi=="textarea" || $osioDTO->Osio_tyyppi=="textarea_large" || $osioDTO->Osio_tyyppi=="date_end" || $osioDTO->Osio_tyyppi=="date_start"){ // Pakolliset tiedot tarkistetaan vain tietyntyyppisistä osioista
							if(!isset($osioDTO->Osio_sisaltoDTO) || (empty($osioDTO->Osio_sisaltoDTO->Sisalto_date) && empty($osioDTO->Osio_sisaltoDTO->Sisalto_boolean) && empty($osioDTO->Osio_sisaltoDTO->Sisalto_text))){
								$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = true;
								break;
							}
						}

						// Tutkimuksen nimi
						if($osioDTO->Osio_tyyppi=="lomake_tutkimuksen_nimi"){
							if($hakemusversioDTO->Tutkimuksen_nimi=="" || is_null($hakemusversioDTO->Tutkimuksen_nimi)){
								$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = true;
								break;
							}
						}
						// Tutkimusryhmä
						if($osioDTO->Osio_tyyppi=="tutkimusryhma"){

							// Haetaan hakemuksen pakolliset roolit
							$pakollinen_rooli_loytyi = array();

							for($i=0; $i < sizeof($jarjestelman_hakijan_roolitDTO); $i++){
								if($jarjestelman_hakijan_roolitDTO[$i]->Pakollinen_rooli_hakemukselle){
									$pakollinen_rooli_loytyi[$jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_koodi] = false;
								}
							}
							// Tarkistetaan käsittelyoikeutta hakevien pakolliset tiedot
							if(isset($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat) && !empty($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat)){
								for($i=0; $i < sizeof($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat); $i++){
																		
									if(is_null($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Jasen) || is_null($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Oppiarvo) || $hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Oppiarvo=="" || is_null($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Organisaatio) || $hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Organisaatio=="" || is_null($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Sahkopostiosoite) || $hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Sahkopostiosoite=="" || is_null($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Sukunimi) || $hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Sukunimi=="" || is_null($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Etunimi) || $hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Etunimi==""){
										$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = true;
										break;
									}
									
									// Tarkistetaan onko sitoumus annettu
									$sitoumus_annettu = false;

									for($j=0; $j < sizeof($tutkimuksen_sitoumuksetDTO); $j++){
										if($tutkimuksen_sitoumuksetDTO[$j]->KayttajaDTO->ID==$hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID){
											$sitoumus_annettu = true;
										}
									}
									
									if(!$sitoumus_annettu){
										$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = true;
										break;
									}
									
									for($j=0; $j < sizeof($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO); $j++){
										if(isset($pakollinen_rooli_loytyi[$hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO[$j]->Hakijan_roolin_koodi])){
											$pakollinen_rooli_loytyi[$hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO[$j]->Hakijan_roolin_koodi] = true;
										}
									}
									
								}
							}

							// Tarkistetaan hakijoiden tiedot (jotka eivät hae käsittelyoikeutta)
							if(isset($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat) && !empty($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat)){
								for($i=0; $i < sizeof($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat); $i++){
									
									if(is_null($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Jasen) || is_null($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Oppiarvo) || $hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Oppiarvo=="" || is_null($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Organisaatio) || $hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Organisaatio=="" || is_null($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Sahkopostiosoite) || $hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Sahkopostiosoite=="" || is_null($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Sukunimi) || $hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Sukunimi=="" || is_null($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Etunimi) || $hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Etunimi==""){
										$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = true;
										break;
									}

									for($j=0; $j < sizeof($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO); $j++){
										if(isset($pakollinen_rooli_loytyi[$hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO[$j]->Hakijan_roolin_koodi])){
											$pakollinen_rooli_loytyi[$hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO[$j]->Hakijan_roolin_koodi] = true;
										}
									}
									
								}

							}
							foreach($pakollinen_rooli_loytyi as $roolin_koodi => $loytyi){
								if(!$loytyi){
									$lomakkeen_sivuDTO->Pakollisia_tietoja_puuttuu = true;
									break;
								}
							}
							
						}
					}
				}
			}
			
			$hakemusversioDTO->Lomakkeen_sivutDTO[$sivun_tunniste] = $lomakkeen_sivuDTO;

		}
	}
	
	return $hakemusversioDTO;
	
}

function maarita_hakemusversion_kayttooikeudet_kayttajalle($dto_taulukko){
	
	if(isset($dto_taulukko["tutkimus_id"]) && isset($dto_taulukko["hakemusversio_id"]) && isset($dto_taulukko["HakemusversioDTO"])){
		
		$hakemusversioDTO = $dto_taulukko["HakemusversioDTO"];
		$hakemusversioDTO->On_oikeus_muokata = 0; $hakemusversioDTO->On_oikeus_poistaa = 0; $hakemusversioDTO->On_oikeus_perua = 0; $hakemusversioDTO->On_oikeus_lahettaa = 0; $hakemusversioDTO->On_oikeus_kutsua_jasen = 0; $hakemusversioDTO->On_oikeus_poistaa_jasen = 0;

		if(isset($dto_taulukko["kayt_id"]) && isset($dto_taulukko["tutkimus_id"]) && isset($dto_taulukko["hakemusversio_id"]) && isset($dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"])){
			
			if(isset($dto_taulukko["HakemusversioDTO"])){
				for($i=0; $i < sizeof($dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"]); $i++){
					if(!empty($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat)){
						for($j=0; $j < sizeof($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat); $j++){

							if($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$j]->KayttajaDTO->ID==$dto_taulukko["kayt_id"]){
								for($l=0; $l < sizeof($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$j]->Hakijan_roolitDTO); $l++){									
									if($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$j]->Hakijan_roolitDTO[$l]->Hakijan_roolin_koodi==$dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakijan_roolin_koodi){
										
										if($hakemusversioDTO->On_oikeus_muokata==0) $hakemusversioDTO->On_oikeus_muokata = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_muokkaus_sallittu;
										if($hakemusversioDTO->On_oikeus_poistaa==0) $hakemusversioDTO->On_oikeus_poistaa = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_poisto_sallittu;
										if($hakemusversioDTO->On_oikeus_perua==0) $hakemusversioDTO->On_oikeus_perua = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_peruminen_sallittu;
										if($hakemusversioDTO->On_oikeus_lahettaa==0) $hakemusversioDTO->On_oikeus_lahettaa = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_lahetys_sallittu;
										if($hakemusversioDTO->On_oikeus_kutsua_jasen==0) $hakemusversioDTO->On_oikeus_kutsua_jasen = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_hakijan_kutsuminen_sallittu;
										if($hakemusversioDTO->On_oikeus_poistaa_jasen==0) $hakemusversioDTO->On_oikeus_poistaa_jasen = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_hakijan_poistaminen_sallittu;
									
									}									
								}
							}

						}
					}

					if(!empty($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat)){
						for($j=0; $j < sizeof($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat); $j++){
							if($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$j]->KayttajaDTO->ID==$dto_taulukko["kayt_id"]){
								for($l=0; $l < sizeof($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$j]->Hakijan_roolitDTO); $l++){
									
									if($hakemusversioDTO->On_oikeus_muokata==0) $hakemusversioDTO->On_oikeus_muokata = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_muokkaus_sallittu;
									if($hakemusversioDTO->On_oikeus_poistaa==0) $hakemusversioDTO->On_oikeus_poistaa = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_poisto_sallittu;
									if($hakemusversioDTO->On_oikeus_perua==0) $hakemusversioDTO->On_oikeus_perua = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_peruminen_sallittu;
									if($hakemusversioDTO->On_oikeus_lahettaa==0) $hakemusversioDTO->On_oikeus_lahettaa = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_lahetys_sallittu;
									if($hakemusversioDTO->On_oikeus_kutsua_jasen==0) $hakemusversioDTO->On_oikeus_kutsua_jasen = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_hakijan_kutsuminen_sallittu;
									if($hakemusversioDTO->On_oikeus_poistaa_jasen==0) $hakemusversioDTO->On_oikeus_poistaa_jasen = $dto_taulukko["Istunto"]["Asetukset"]["Jarjestelman_hakijan_roolitDTO"][$i]->Hakemuksen_hakijan_poistaminen_sallittu;
								
								}
							}
						}
					}

				}
			}
		}
		
		$dto_taulukko["HakemusversioDTO"] = $hakemusversioDTO;

	}
	
	return $dto_taulukko;
	
}

function pakollisia_parametreja_puuttuu($syoteparametrit, $pakolliset_parametrit){
	$tarkistettavat_parametrit = array();
	$syoteparametrit_taulukko = array();

	for($i=0; $i < sizeof($syoteparametrit); $i++){
		$objektin_muuttujat = get_object_vars($syoteparametrit[$i]);

		foreach($objektin_muuttujat as $kentta => $arvo) {
			array_push($tarkistettavat_parametrit,$kentta);
			$syoteparametrit_taulukko[$kentta] = $arvo;
		}
	}

	for($i=0; $i < sizeof($pakolliset_parametrit); $i++){
		if(!in_array($pakolliset_parametrit[$i], $tarkistettavat_parametrit)){
			throw new SoapFault(ERR_MISSING_PARAMETER, "Pakollisia parametreja [{$pakolliset_parametrit[$i]}] puuttuu. (tarkisettavat: ".implode(",",$tarkistettavat_parametrit).")");
		}
	}
	return $syoteparametrit_taulukko;
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

function laheta_sposti($postiOs, $otsikko, $viesti) {
	
	$headers = "From: " . EMAIL_LAHETTAJA ." \r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	$message = '<html><body style="font-family: Calibri, Arial;">';
	$message .= $viesti;
	$message .= '</body></html>';

	return mail($postiOs, $otsikko, $message, $headers);
					
}

function maarita_muutoshakemuksen_viranomaiset($uusimmat_hakemuksetDTO, $hakemusversioDTO){
	
	// Haetaan hakemuksen viranomaiset
	$hakemuksen_viranomaiset = array();
	$haetaan_biopankkiaineistoa = false;
	$biopankki_koodit = array("AURIA", "THL_BIO", "FHRB", "HKI_BIO", "BOREALIS", "TMPR_BIO", "ITAS_BIO", "KESK_BIO", "VERIP_BIO");	
		
	$luvan_kohteetDTO = array();
	$viranomaiset = array();
	$viranomaiset_ryhma_1 = array();
	$viranomaiset_ryhma_2 = array();	
	$aineistoja_ryhmasta_1_kpl = 0;
	$aineistoja_ryhmasta_2_kpl = 0;		
		
	foreach($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO as $kohde_tyyppi => $luvan_kohteet_tyyppi){	
		for($i=0; $i < sizeof($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$kohde_tyyppi]); $i++){
			if(isset($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$kohde_tyyppi][$i]->Luvan_kohdeDTO->Viranomaisen_koodi)){		

				if($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$kohde_tyyppi][$i]->Luvan_kohdeDTO->Viranomaisen_koodi=="v_BIO"){
					$haetaan_biopankkiaineistoa = true;
					continue;
				} 
								
				$muutoshakemus_lahetys_sallittu = true;
							
				if(isset($uusimmat_hakemuksetDTO[$hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$kohde_tyyppi][$i]->Luvan_kohdeDTO->Viranomaisen_koodi])){
								
					$uusin_hakemusDTO = $uusimmat_hakemuksetDTO[$hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$kohde_tyyppi][$i]->Luvan_kohdeDTO->Viranomaisen_koodi];								
					if($uusin_hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val" || $uusin_hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_lah" || $uusin_hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas") $muutoshakemus_lahetys_sallittu = false;
								
				}
							
				$hakemuksen_viranomaiset[$hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$kohde_tyyppi][$i]->Luvan_kohdeDTO->Viranomaisen_koodi] = $muutoshakemus_lahetys_sallittu;
					
				if(TOISIOLAKI_PAALLA){	
					
					$luvan_kohdeDTO = $hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$kohde_tyyppi][$i]->Luvan_kohdeDTO;
					
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
				
			}
		}
	}

	if(TOISIOLAKI_PAALLA){
	
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
		
		$hakemuksen_viranomaiset_temp = array();
		
		foreach ($viranomaiset as $indx => $org_koodi) { 
		
			$viranomainen_loydetty = false;
		
			foreach ($hakemuksen_viranomaiset as $vir_koodi => $lahetys_sallittu) { 			
				if($org_koodi==$vir_koodi) $hakemuksen_viranomaiset_temp[$org_koodi] = $lahetys_sallittu;				
			}
			
		}
		
		if(isset($uusimmat_hakemuksetDTO["v_KLV"])){
			
			$hakemuksen_viranomaiset_temp["v_KLV"] = true;
			$uusin_hakemusDTO_KLV = $uusimmat_hakemuksetDTO["v_KLV"];
			
			if($uusin_hakemusDTO_KLV->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val" || $uusin_hakemusDTO_KLV->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_lah" || $uusin_hakemusDTO_KLV->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas") $hakemuksen_viranomaiset_temp["v_KLV"] = false;
			
		}
		
		$hakemuksen_viranomaiset = $hakemuksen_viranomaiset_temp;
		
	}	
			
	if($haetaan_biopankkiaineistoa){				
		foreach($uusimmat_hakemuksetDTO as $vir_koodi_u => $hakemusDTO_uusi){ 
			if(in_array($vir_koodi_u, $biopankki_koodit)){
				if($hakemusDTO_uusi->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val" || $hakemusDTO_uusi->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_lah" || $hakemusDTO_uusi->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){
					$hakemuksen_viranomaiset[$vir_koodi_u] = false;
				} else {
					$hakemuksen_viranomaiset[$vir_koodi_u] = true;
				}
			}
		}				
	}
	
	return $hakemuksen_viranomaiset;
	
}

function muotoilepvm($p, $opt) {
	// muotoilee pvm:n $p uuteen muotoon opt-parametrin mukaan:
	//
	// opt		muunnos		esim.
	// --------------------------------------------------------------------
	// fi		ansi -> fi 	2011-01-01 -> 01.01.2011
	// fi2		ansi -> fi 	2011-01-01 -> 1.1.2011
	// ansi		fi -> ansi	01.01.2011 -> 2011-01-01
	// fi_aika	ansi -> fi+aika	2011-01-01 23:00:00 -> 01.01.2011 23:00
	// ansi_aika fi -> ansi+aika	01.01.2011 23:00:00 -> 2011-01-01 23:00:00

	if (strpos($p, " ") !== false) {
		$px = explode(" ", $p);
		$p = $px[0];
		$a = $px[1];
	}

	if (strlen($p) > 0) {
		if ($opt == "fi") {
			$pv = explode("-", $p);
			return "$pv[2].$pv[1].$pv[0]";
		}
		if ($opt == "fi2") {
			$pv = explode("-", $p);
			$p2 = ltrim($pv[2], "0");
			$p1 = ltrim($pv[1], "0");
			return "$p2.$p1.$pv[0]";
		}
		if ($opt == "fi_aika") {
			$pv = explode("-", $p);
			return "$pv[2].$pv[1].$pv[0]&nbsp;" . substr($a, 0, 5);
		}

		if ($opt == "ansi") {
			$pv = explode(".", $p);
			return "$pv[2]-$pv[1]-$pv[0]";
		}
		if ($opt == "ansi_aika") {
			$pv = explode(".", $p);
			return "$pv[2]-$pv[1]-$pv[0] $a";
		}
	} else
		return "";
}

function suorita_datakerroksen_funktio($yhteys, $funktio, $parametrit){
	if ($response = $yhteys->$funktio($parametrit)) {
		return $response;
	} else {
		throw new SoapFault(ERR_INVALID_REQUEST_FORMAT, "Invalid request format");
	}
}

function jarjesta_objekti_avaimena_string($key) {
    return function ($a, $b) use ($key) {
        return strnatcmp($a->$key, $b->$key);
    };
}

function jarjesta_objekti_avaimena_nro($key) {
    return function ($a, $b) use ($key) {		
		if($a->$key==$b->$key) return 0;		
        return ($a->$key > $b->$key) ? -1 : 1;
    };
}

function jarjesta_taulu_nro_asc($key) {
    return function ($a, $b) use ($key) {		
		if($a[$key]==$b[$key]) return 0;		
        return ($b[$key] > $a[$key]) ? -1 : 1;
    };
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

function muodosta_dto($muotoiltava_vastaus){
	$vastaus = array();

	foreach($muotoiltava_vastaus as $kentta => $arvo) { 
		$objekti = new stdClass();
		$objekti->$kentta = $arvo;
		array_push($vastaus, $objekti);
	}

	return $vastaus;
}

function generate_titles($titles) {

	$out = '';

	for ($i=0; $i<count($titles); $i++) {

		if (isset($titles[$i]['title_text'])) {

			$out .= "<div class=\"bl\">&nbsp;</div>\n";

			$out .= "<table cellpadding=\0\" cellspacing=\"0\" nobr=\"true\"><tbody><tr><td>";
			$out .= "<div class=\"ind header-3\">{$titles[$i]['title_text']}</div>\n";
			//$out .= "<div class=\"br\">&nbsp;</div>\n";

			$field_name = rand(1,100000);
			
			for ($j = 0; $j < count($titles[$i]["fields"]); $j++) {
							
				if (
					(isset($titles[$i]["fields"][$j]["content"]) && $titles[$i]["fields"][$j]["content"]!='') ||
					(isset($titles[$i]["fields"][$j]["field_text"]) && $titles[$i]["fields"][$j]["field_text"]!='')
				) {

					switch ($titles[$i]["fields"][$j]["type"]) {

						case "textarea":
						case "date_start":
						case "date_end":
						case "textarea_large":
							//$out .= "<p class=\"ind\">{$titles[$i]["fields"][$j]["content"]}</p>\n";
							$out .= "<p class='ind'>";
							$out .= htmlentities($titles[$i]["fields"][$j]["content"], ENT_COMPAT, "UTF-8");
							$out .= "</p>\n";
							break;

						case "checkbox":
						
							if($titles[$i]["fields"][$j]["selected"] == 1){
								//if ($titles[$i]["fields"][$j]["selected"] == 1) $checked = "checked=\"checked\"";
								//else $checked = "";
								//$rand = rand(1,100000);
								$out .= "<p class=\"ind\">\n";
								$out .= $titles[$i]["fields"][$j]["field_text"];
								//$out .= "\t<input type=\"checkbox\" {$checked} id=\"ch_{$i}_{$j}_{$rand}\" /> \n";
								//$out .= "\t<label for=\"ch_{$i}_{$j}_{$rand}\">{$titles[$i]["fields"][$j]["field_text"]}</label>\n";
								$out .= "</p>\n";								
							}
							
							break;
							
						case "radio":
						
							if($titles[$i]["fields"][$j]["selected"] == 1){
								//$checked = "";
								//if ($titles[$i]["fields"][$j]["selected"] == 1 || $titles[$i]["fields"][$j]["selected"] == true) $checked = "checked";							
								$out .= "<p class=\"ind\">\n";
								//$out .= "<label>";
								//$out .= "\t <input name=\"r_{$field_name}\" type='radio' checked='" . $checked . "' > \n";
								$out .= $titles[$i]["fields"][$j]["field_text"];
								//$out .= "</label>";
								$out .= "</p>\n";
							}
							
							break;

					}

					//$out .= "<div class=\"br\">&nbsp;</div>\n";

				}
			}
			
			if(isset($titles[$i]['show_hr_bottom']) && $titles[$i]['show_hr_bottom']) $out .= "<div class=\"br\"><div style='margin-top: 15px; width: 90%;'><hr style='margin-left:80px;'></div></div>";			
			$out .= "</td></tr></tbody></table>";			
			$out .= "<div class=\"bl\">&nbsp;</div>\n";
			
			
		}
						
	}

	return $out;

}

/**
 * Liiteet - attachments template helper
 */

function print_liiteet($liitteet) {

	if (!is_array($liitteet)) return '';
	if (count($liitteet)==0) return '';

	$out = <<<EOF
	<table class="wide-table" nobr="true">
		<tbody>
			<tr>
				<td style="width: 78px; text-align: left;">
					LIITTEET:
				</td>
				<td>
EOF;

    $cnt=0;

    foreach ($liitteet as $liitteen_nimi) {
		$cnt++;
		$out .= "<p>{$liitteen_nimi}</p>\n";
		$out .= "<div class=\"bl\">&nbsp;</div>\n";
	}

	$out .= <<<EOF
				</td>
			</tr>
		</tbody>
	</table>
EOF;

	return $out;

}


/**
 * templating helper: if no complete blocks inside we want to skip the whole segment
 *
 * @param $blocks
 * @return bool
 */

function segment_has_nonempty_blocks($blocks) {
    for ($j=0; $j<count($blocks); $j++) {
		if (count($blocks[$j]["titles"])>0) return true;
	}
	return false;
}


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
			file_put_contents("/tmp/debug_{$interface}.log", date("Ymd H:i:s") . " " . $_SERVER['REQUEST_URI'] . "\n" . $ob . "\n", FILE_APPEND);

		}

	}

}

?>