<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: julkiset tiedot
 *
 * Created: 14.2.2018
 */
 
include_once '_fmas_ui.php'; 
	
try {
	if ($api = createSoapClient()) {	
	
		$vastaus = suorita_logiikkakerroksen_funktio($api, "hae_lupapalvelun_tilastotiedot", array());
		
		$tilastohavainnot = $vastaus["tilastohavainnot"];
		$tutkimusten_julkiset_kuvaukset = $vastaus["tutkimusten_julkiset_kuvaukset"];
		
	} 
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

echo "<pre>";
var_dump($tilastohavainnot);
echo "</pre>";

echo "<pre>";
var_dump($tutkimusten_julkiset_kuvaukset);
echo "</pre>";

//include './ui/views/tilastot_view.php';
	
?>