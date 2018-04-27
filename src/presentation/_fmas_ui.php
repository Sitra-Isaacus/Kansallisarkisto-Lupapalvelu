<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: User Interface Methods
 *
 * Created: 7.10.2015
 */

define('FMAS', true); // security GP

include_once '_config.php';
include_once '_session.php';
include_once '_soapclient.php';
include_once '_language.php';

if(HTTPS_PAALLA && $_SERVER['SERVER_PORT'] !== 443 && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')) {
  header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
  exit;
}

// initialize multilanguage support
$lang = new lang();
if($lang->getCurrentLanguage() == 'fi') include_once 'ui/language/lang_fi_arrays.php';	
if($lang->getCurrentLanguage() == 'en') include_once 'ui/language/lang_en_arrays.php';
	
if(isset($_SERVER['REMOTE_ADDR'])) include_once '_security.php'; // security messages are in lang files, so we need to prepare langs first

// desc: putsataan GET- ja POST-muuttujia hax0r-yritysten varalta
// input: string $s
// output: string $s
function tarkista($s) {
	
	$etsi = array('#', '´', '%', '|', '--', '\t');
	$korv = array('&#35;', '&#39;', '&#37;', '&#124;', '&#150;', '&nbsp;');

	$s = htmlspecialchars($s);
	$s = trim(str_replace($etsi, $korv, $s));
	$s = stripslashes($s);
	$enc = mb_detect_encoding($s, 'UTF-8', true);

	if ($enc == 'UTF-8'){
		return $s;
	} else {
		return utf8_encode($s);
	}
	
}

function web_scraping_curlilla($url){
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:57.0) Gecko/20100101 Firefox/57.0');
	$xmlstr = curl_exec($ch);
	curl_close($ch);
	
	return json_decode($xmlstr, true);	
	
}

function poista_erikoismerkit($data){
	if(!is_null($data) && is_array($data)){
		foreach ($data as $key => $value) {
			if(is_array($value)){
				$data[$key] = poista_erikoismerkit($value);
			} else {
				$data[$key] = htmlentities($value, ENT_COMPAT, "UTF-8");
			}
		}
	} else {
		if(!is_null($data)){
			$data = htmlentities($data, ENT_COMPAT, "UTF-8");
		}
	}
	return $data;
}

function poista_erikoismerkit_2($data){
	foreach ($data as $key => $value) {
		if(is_array($value)){
			$data[$key] = poista_erikoismerkit_2($value);
		} else {
			$data[$key] = htmlspecialchars($value, ENT_QUOTES,"UTF-8");
		}
	}

	return $data;
}

function koodin_selite($koodi, $lang) {
	if (defined($koodi)) {
		return constant ($koodi);
	} else {
		return 0;
	}
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
		if ($opt == "fi2" && (isset($pv[2]) || isset($pv[1]))) {
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
		if($opt=="en"){
			return date_format(date_create($p), 'd/m/Y');
		}
	} else{
		return date_format(date_create($p), 'd/m/Y');
	}		
}

function muotoilepvm2($pvm, $kieli) {
	
	// fi, sv	01.01.2011
	// else		2011-01-01	
	
	if ($kieli == "fi" || $kieli == "fi2" || $kieli == "sv") {
		return date_format(date_create($pvm), 'd.m.Y');
	} else {
		return date_format(date_create($pvm), 'Y-m-d');
	}
	
}

function kayttaja_on_kirjautunut(){
	if (!isset($_SESSION["kayttaja_nimi"]) || !isset($_SESSION["kayttaja_id"]) || !isset($_SESSION["kayttaja_token"])){
		session_unset();	
		header("Location: kirjaudu.php");
		die();
	}
	return true;	
}

function jarjesta_pvm_mukaan($a, $b){
    if ($a["Lisayspvm"] == $b["Lisayspvm"]) {
        return 0;
    }
    return ($a["Lisayspvm"] > $b["Lisayspvm"]) ? -1 : 1;
}

function jarjesta_hakemuksen_tilan_mukaan($a, $b){
    return strcmp($a["Hakemuksen_tila"], $b["Hakemuksen_tila"]);
}

function jarjesta_viranomaisen_mukaan($a, $b){
    return strcmp($a["Viranomainen"], $b["Viranomainen"]);
}

function jarjesta_kasittelijan_mukaan($a, $b){
    return strcmp($a["Kasittelijan_nimi"], $b["Kasittelijan_nimi"]);
}

function jarjesta_toiminnon_mukaan($a, $b){
    return strcmp($a["Toiminto"], $b["Toiminto"]);
}

function jarjesta_tunnuksen_mukaan($a, $b){
    return strcmp($a["Hakemus"]["Hakemuksen_tunnus"], $b["Hakemus"]["Hakemuksen_tunnus"]);
}

function rinnakkaishakemukset($vastaus, $tyyppi, $i, $lang) {
	if(!empty($vastaus["HakemuksetDTO"][$tyyppi][$i]->muiden_viranomaisten_HakemuksetDTO)) {
		echo "<div class='tooltip'>
		<img src='static/images/rinnakkaishakemuksia_on.png' class='lisatoim' alt='' />
			<span class='tooltiptext2'>
			<h5>".MUID_VO_H."</h5>
			<table class='taulu'>
				<tr class='vo_taulu'>
					<th>".HAKEMUS."</th>
					<th>".TILA."</th>
					<th>".KASITTELIJA."</th>
				</tr>";
			for($j=0; $j < sizeof($vastaus["HakemuksetDTO"][$tyyppi][$i]->muiden_viranomaisten_HakemuksetDTO); $j++) {
				echo "
				<tr>
					<td>
						<a href=\"hakemus.php?sivu=hakemus_perustiedot&tutkimus_id=".$vastaus["HakemuksetDTO"][$tyyppi][$i]->HakemusversioDTO->TutkimusDTO->ID."&hakemusversio_id=".$vastaus["HakemuksetDTO"][$tyyppi][$i]->muiden_viranomaisten_HakemuksetDTO[$j]->HakemusversioDTO->ID."&hakemus_id=".$vastaus["HakemuksetDTO"][$tyyppi][$i]->muiden_viranomaisten_HakemuksetDTO[$j]->ID."\" title=\"Avaa hakemus\">
							". $vastaus["HakemuksetDTO"][$tyyppi][$i]->muiden_viranomaisten_HakemuksetDTO[$j]->Hakemuksen_tunnus ."
						</a>
					</td>
					<td>". koodin_selite($vastaus["HakemuksetDTO"][$tyyppi][$i]->muiden_viranomaisten_HakemuksetDTO[$j]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi,$lang) ."</td>
					<td>";
					if (isset($vastaus["HakemuksetDTO"][$tyyppi][$i]->muiden_viranomaisten_HakemuksetDTO[$j]->PaatosDTO->KayttajaDTO_Kasittelija->Etunimi)) {
						echo $vastaus["HakemuksetDTO"][$tyyppi][$i]->muiden_viranomaisten_HakemuksetDTO[$j]->PaatosDTO->KayttajaDTO_Kasittelija->Etunimi ." ". $vastaus["HakemuksetDTO"][$tyyppi][$i]->muiden_viranomaisten_HakemuksetDTO[$j]->PaatosDTO->KayttajaDTO_Kasittelija->Sukunimi;
					} else {echo "<i>".EI_KASITTELIJAA."</i>";}
					echo "</td>
				</tr>";
			}
			echo "
			</table>
			</span>
	</div>";
	} else {
		echo "<div class='tooltip'><img src='static/images/rinnakkaishakemuksia_off.png' class='lisatoim' alt='' /><span class='tooltiptext2'>".EI_RINN."</span></div>";
	}
}

function hakemushistoria($vastaus, $tyyppi, $i, $lang) {
	if(sizeof($vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO) > 1){
		echo "<div class='tooltip'>
		<img src='static/images/hakemushistoria_on.png' class='lisatoim' alt='' />
			<span class='tooltiptext2'>
			<h5>".HIST."</h5>
			<table class='taulu'>
				<tr class='vo_taulu'>
					<th>".HAKEMUS."</th>
					<th></th>
					<th>".DIAARINUMERO."</th>
					<th>".TILA."</th>
					<th>".TILAN_PVM."</th>
					<th>".KASITTELIJA."</th>
				</tr>";
			for($j=0; $j < sizeof($vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO); $j++) {
				echo "
				<tr>
					<td>
						<a href=\"hakemus.php?sivu=hakemus_perustiedot&tutkimus_id=". $vastaus["HakemuksetDTO"][$tyyppi][$i]->HakemusversioDTO->TutkimusDTO->ID."&hakemusversio_id=". $vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->HakemusversioDTO->ID ."&hakemus_id=". $vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->ID ."\" title=\"Avaa hakemus\">
							". $vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->Hakemuksen_tunnus ."
						</a>
					</td>
					<td>
						<a href=\"hakemus_pdf.php?tutkimus_id=". $vastaus["HakemuksetDTO"][$tyyppi][$i]->HakemusversioDTO->TutkimusDTO->ID."&hakemusversio_id=". $vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->HakemusversioDTO->ID."\" >
							<img src=\"static/images/pdf.png\" class=\"lisatoim\">
						</a>	
					</td>
					<td>". htmlentities($vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->AsiaDTO->Diaarinumero,ENT_COMPAT, "UTF-8") ."</td>
					<td>"; 
						if($vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat") { echo "<a href='paatos.php?hakemus_id=".$vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->ID."'>"; }
						echo koodin_selite($vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi,$lang);
						if($vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat") { echo "</a>"; }
					echo "</td>
					<td>". muotoilepvm($vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->Hakemuksen_tilaDTO->Lisayspvm,$lang) ."</td>
					<td>";
					if (isset($vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->PaatosDTO->KayttajaDTO_Kasittelija->Etunimi)) {
						echo $vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->PaatosDTO->KayttajaDTO_Kasittelija->Etunimi ." ". $vastaus["HakemuksetDTO"][$tyyppi][$i]->hakemushistoria_HakemuksetDTO[$j]->PaatosDTO->KayttajaDTO_Kasittelija->Sukunimi;
					} else {echo "<i>".EI_KASITTELIJAA."</i>";}
					echo "</td>
				</tr>";
			}
			echo "
			</table>
			</span>
		</div>";
	} else echo "<div class='tooltip'><img src='static/images/hakemushistoria_off.png' class='lisatoim' alt='' /><span class='tooltiptext2'>".EI_HIST."</span></div>";
}

function aineistohistoria($paatokset_aineistotilaukset, $i, $lang) {
	if(sizeof($paatokset_aineistotilaukset[$i]->AineistotilausDTO->Aineistotilauksen_tilatDTO) > 2){
		echo "<div class='tooltip'>
			<a href=\"#\">
				".koodin_selite(htmlentities($paatokset_aineistotilaukset[$i]->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi, ENT_COMPAT, "UTF-8"), $lang)."
			</a>
			<span class='tooltiptext2'>
			<h5>".AINEISTOTILAUKSET."</h5>
			<table class='taulu table_saapuneet_hakemukset'>
				<tbody>
					<tr>
						<th></th>
						<th>".TILA."</th>
						<th>".TILAN_PVM."</th>
					</tr>";
			for($j=0; $j < sizeof($paatokset_aineistotilaukset[$i]->AineistotilausDTO->Aineistotilauksen_tilatDTO); $j++) {
				$nro = $j + 1;
				echo "
					<tr>
						<td>
							". $nro .".
						</td>
						<td>
							".koodin_selite(htmlentities($paatokset_aineistotilaukset[$i]->AineistotilausDTO->Aineistotilauksen_tilatDTO[$j]->Aineistotilauksen_tilan_koodi, ENT_COMPAT, "UTF-8"), $lang)."
						</td>
						<td>
							". muotoilepvm(htmlentities($paatokset_aineistotilaukset[$i]->AineistotilausDTO->Aineistotilauksen_tilatDTO[$j]->Lisayspvm, ENT_COMPAT, "UTF-8"), $lang) ."
						</td>
					</tr>";
			}
			echo "
			</tbody>
			</table>
			</span>
		</div>";
	} else echo koodin_selite(htmlentities($paatokset_aineistotilaukset[$i]->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi, ENT_COMPAT, "UTF-8"), $lang);
}

function tulosta_teksti($teksti){
	if(isset($teksti) && !is_null($teksti)) echo htmlentities($teksti,ENT_COMPAT, "UTF-8");
}

function maaritaAsetusDynaamisesti($id, $parametrit){
	if(isset($id) && !empty($parametrit)){
		if($id=="tutkimusryhma_taulu_yla"){
			if($parametrit[0]!== ""){
				echo 'style="border-top: 1px solid rgb(118, 180, 207);"';
			} 
		}
		if($id=="div_salassapitositoumus"){
			echo 'class="' . $parametrit[1] . '-' . $parametrit[2] . '" ';

			if($parametrit[0]=="checked" && $parametrit[1]==$parametrit[2]){
				echo 'style="display: block;"';
			} else {
				echo 'style="display: none;"';
			}
		}
		if($id=="sitoutuminen_checked"){
			if($parametrit[0]!= "0"){
				echo 'style="display: none;"';
			} 
		}

	}

}

function hakemusversio_lukittu_toiselle_kayttajalle($hakemusversioDTO, $kayttaja_id){	
	if(is_null($hakemusversioDTO->Muokkaaja) || $hakemusversioDTO->Muokkaaja==$kayttaja_id || time() - strtotime($hakemusversioDTO->Muokkauspvm) > 1800 ){ // todo : ajasta globaali variable
		return false;
	}
	return true;	
}

function suorita_logiikkakerroksen_funktio($yhteys, $funktio, $parametrit){
	
	if ($dto = $yhteys->$funktio(muotoile_soap_parametrit($parametrit))) return objektit_taulukkomuotoon($dto);	
	return false;
	
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

function objektit_taulukkomuotoon($syoteparametrit){
	
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

function nayta_sivun_osiot($parametrit){	
	if(isset($parametrit["osiotDTO_puu"]) && isset($parametrit["sivun_tunniste"])){
		
		$osiotDTO_puu = $parametrit["osiotDTO_puu"];
		$sivun_tunniste = $parametrit["sivun_tunniste"];
		
		for($i=0; $i < sizeof($osiotDTO_puu); $i++){
			if($osiotDTO_puu[$i]->Sivun_tunniste==$sivun_tunniste && is_null($osiotDTO_puu[$i]->OsioDTO_parent->ID)){
				
				$parametrit["osio_indeksi"] = $i;
				nayta_osio($parametrit);
				
			}
		}
	} else {
		return false;
	}
}

function nayta_osiot($parametrit){
	
	$osiotDTO_puu = $parametrit["osiotDTO_puu"];
	
	for($i=0; $i < sizeof($osiotDTO_puu); $i++){
		$parametrit["osio_indeksi"] = $i;
		nayta_osio($parametrit);
	}
	
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
?>