<?php
/*
 * FMAS Käyttölupapalvelu
 * Web scraping (Taika ja THL aineistoeditori) 
 *
 * Created: 13.9.2017
 */

(PHP_SAPI !== 'cli' || isset($_SERVER['HTTP_USER_AGENT'])) && die('no access'); // Skriptin saa suorittaa vain cli interfacella
include_once '_fmas_ui.php';  

function on_yritysaineisto($luvan_kohteen_id){
	
	$id_loppuosa = mb_substr($luvan_kohteen_id, 2, 3);
	
	// YA-aineistoista otetaan mukaan ne, joiden tunnus on YA222 tai YA244 (Toimipaikat ja FLEED-aineistot)
	if(mb_substr($luvan_kohteen_id, 0, 2)=="YA" && is_numeric($id_loppuosa) && $id_loppuosa!=222 && $id_loppuosa!=244) return true; // Yritysaineisto on muotoa YA<nro>	
	return false;	
	
}

$luvan_kohteet = array();
$luvan_kohteita_lisatty = 0;
$luvan_kohteita_paivitetty = 0;
$muuttujia_lisatty = 0;
$muuttujia_paivitetty = 0;

// Haetaan aineistot ja muuttujat THL:n aineistokatalogista
$studies = web_scraping_curlilla("https://aineistoeditori.fi/api/v1/public/studies");

if(is_array($studies) && !empty($studies)){
	for($i=0; $i < sizeof($studies); $i++){	
		for($j=0; $j < sizeof($studies[$i]["datasets"]); $j++){
			
			$luvan_kohde = array();
			$luvan_kohde["Identifier"] = $studies[$i]["datasets"][$j]["id"];
			$luvan_kohde["Luvan_kohteen_nimi"] = $studies[$i]["datasets"][$j]["prefLabel"]["fi"];
			$luvan_kohde["Selite"] = (isset($studies[$i]["datasets"][$j]["description"]["fi"]) ? $studies[$i]["datasets"][$j]["description"]["fi"] : null);
			$luvan_kohde["Viiteajankohta_alku"] = (isset($studies[$i]["datasets"][$j]["referencePeriodStart"]) ? $studies[$i]["datasets"][$j]["referencePeriodStart"] : null);
			$luvan_kohde["Viiteajankohta_loppu"] = (isset($studies[$i]["datasets"][$j]["referencePeriodEnd"]) ? $studies[$i]["datasets"][$j]["referencePeriodEnd"] : null);
			$luvan_kohde["Luvan_kohteen_tyyppi"] = "Aineistokatalogi";
			$luvan_kohde["Muuttujat"] = array();
		
			if($studies[$i]["ownerOrganization"]["abbreviation"]["fi"]=="THL"){
				$luvan_kohde["Viranomaisen_koodi"] = "v_THL";
			} else if($studies[$i]["ownerOrganization"]["abbreviation"]["fi"]=="TK"){
				$luvan_kohde["Viranomaisen_koodi"] = "v_TK";
			} else if($studies[$i]["ownerOrganization"]["abbreviation"]["fi"]=="Kela"){
				$luvan_kohde["Viranomaisen_koodi"] = "v_Kela";
			} else if($studies[$i]["ownerOrganization"]["abbreviation"]["fi"]=="VSSHP"){
				$luvan_kohde["Viranomaisen_koodi"] = "v_VSSHP";
			} else { // Skipataan aineisto, koska organisaatiota ei ole, tai sitä ei ole mäpätty lupajärjestelmään
				continue;
			}
									
			// Haetaan muuttujat		
			$dataset = web_scraping_curlilla("https://aineistoeditori.fi/api/v1/public/studies/" . $studies[$i]["id"] . "/datasets/" . $luvan_kohde["Identifier"] . "");		
			
			if(sizeof($dataset["instanceVariables"]) > 0){
				
				for($m=0; $m < sizeof($dataset["instanceVariables"]); $m++){
					
					$muuttuja = array();
					$muuttuja["Tunnus"] = $dataset["instanceVariables"][$m]["id"];
					$muuttuja["Nimi"] = $dataset["instanceVariables"][$m]["prefLabel"]["fi"];
					$muuttuja["Kuvaus"] = (isset($dataset["instanceVariables"][$m]["description"]["fi"]) ? $dataset["instanceVariables"][$m]["description"]["fi"] : null);
								
					array_push($luvan_kohde["Muuttujat"], $muuttuja);
					
				}
				
				array_push($luvan_kohteet,$luvan_kohde);
				echo "Haettu viranomaisen " . koodin_selite($luvan_kohde["Viranomaisen_koodi"], "fi") . " aineistosta " . $luvan_kohde["Luvan_kohteen_nimi"] . " " . sizeof($luvan_kohde["Muuttujat"]) . " kpl muuttujia." . PHP_EOL;
				
			}
			
		}		
	}
}

// Haetaan TK:n rekisterit TAIKA-metatietokatalogista
$data = web_scraping_curlilla("https://taika.stat.fi/api/fi/datasets");

// Poimitaan datasta rekisterit
if(is_array($data) && !empty($data)){
	for($i=0; $i < sizeof($data["dataset"]); $i++){
		
		$luvan_kohteen_id = $data["dataset"][$i]["docmeta"]["identifier"];
				
		if(!on_yritysaineisto($luvan_kohteen_id)){ // Skipataan yritysaineistot
		
			$luvan_kohde = array();				
			$dataset_lisatiedot = web_scraping_curlilla("https://taika.stat.fi/api/fi/datasets/" . $data["dataset"][$i]["docmeta"]["identifier"] . "");
		
			if(isset($dataset_lisatiedot["dataset"]["docmeta"]["orgname"])){				
				if($dataset_lisatiedot["dataset"]["docmeta"]["orgname"]=="Tilastokeskus"){
					$luvan_kohde["Viranomaisen_koodi"] = "v_TK";
				} else if($dataset_lisatiedot["dataset"]["docmeta"]["orgname"]=="Työ- ja elinkeinoministeriö"){
					$luvan_kohde["Viranomaisen_koodi"] = "v_TEM";
				} else { // Skipataan aineisto, koska organisaatiota ei ole, tai sitä ei ole mäpätty lupajärjestelmään
					continue;
				}													
			}
		
			$luvan_kohde["Luvan_kohteen_nimi"] = $data["dataset"][$i]["docmeta"]["subject"];
			$luvan_kohde["Identifier"] = $data["dataset"][$i]["docmeta"]["identifier"];
			$luvan_kohde["Selite"] = $data["dataset"][$i]["docmeta"]["contentdescription"];	
			$luvan_kohde["Luvan_kohteen_tyyppi"] = "Taika_tilastoaineisto";
			$luvan_kohde["Viiteajankohta_alku"] = null;
			$luvan_kohde["Viiteajankohta_loppu"] = null;
			
			// Haetaan muuttujat
			$luvan_kohde["Muuttujat"] = array();	
			$data_muuttujat = web_scraping_curlilla("https://taika.stat.fi/api/fi/variables/" . $data["dataset"][$i]["docmeta"]["identifier"]);
			
			for($j=0; $j < sizeof($data_muuttujat["variables"]); $j++){
				$luvan_kohde["Muuttujat"][$j]["Tunnus"] = $data_muuttujat["variables"][$j]["identifier"];
				$luvan_kohde["Muuttujat"][$j]["Nimi"] = $data_muuttujat["variables"][$j]["variablename"];
				$luvan_kohde["Muuttujat"][$j]["Kuvaus"] = $data_muuttujat["variables"][$j]["conceptdef"];
				$luvan_kohde["Muuttujat"][$j]["Mittayksikko"] = $data_muuttujat["variables"][$j]["measunit"];
				$luvan_kohde["Muuttujat"][$j]["Luokitus"] = $data_muuttujat["variables"][$j]["classification"];			
			}
					
			array_push($luvan_kohteet,$luvan_kohde);	
			echo "Haettu viranomaisen " . koodin_selite($luvan_kohde["Viranomaisen_koodi"], "fi") . " aineistosta " . $luvan_kohde["Luvan_kohteen_nimi"] . " " . sizeof($luvan_kohde["Muuttujat"]) . " kpl muuttujia." . PHP_EOL;
			
		}	
		
	}
}

// Tallennetaan data tietokantaan
try {
	if ($api = createSoapClient()) {

		$vastaus = suorita_logiikkakerroksen_funktio($api, "paivita_aineistot", array("luvan_kohteet"=>$luvan_kohteet));
		
		if(isset($vastaus["luvan_kohteita_lisatty"])) $luvan_kohteita_lisatty = $vastaus["luvan_kohteita_lisatty"];
		if(isset($vastaus["luvan_kohteita_paivitetty"])) $luvan_kohteita_paivitetty = $vastaus["luvan_kohteita_paivitetty"];
		if(isset($vastaus["muuttujia_lisatty"])) $muuttujia_lisatty = $vastaus["muuttujia_lisatty"];
		if(isset($vastaus["muuttujia_paivitetty"])) $muuttujia_paivitetty = $vastaus["muuttujia_paivitetty"];
				
	} 
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

echo PHP_EOL . "Metatietojen päivitys" . PHP_EOL . PHP_EOL;
echo "Luvan kohteita lisätty: " . $luvan_kohteita_lisatty . PHP_EOL;
echo "Luvan kohteita päivitetty: " . $luvan_kohteita_paivitetty . PHP_EOL;
echo "Muuttujia lisätty: " . $muuttujia_lisatty . PHP_EOL;
echo "Muuttujia päivitetty: " . $muuttujia_paivitetty . PHP_EOL;

?>