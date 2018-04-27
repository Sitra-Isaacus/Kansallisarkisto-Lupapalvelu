<?php

/*
 * FMAS Käyttölupapalvelu
 * Aineistotilaus Data transfer Object
 *
 * Created: 21.9.2016
 */

class AineistotilausDTO {

	public $ID;   
	public $PaatosDTO; 
	public $Aineistonmuodostusprosessi_teksti; 
	public $Aineistonmuodostuksen_hinta; 
	public $Aineisto_lahetetty; 
	public $Aineistotilauksen_tyypin_koodi; 
	public $Hyvaksyn_kayttoehdot; 
	public $Aineistonmuodostaja; 
	public $Aineiston_tilaaja; 
	public $Lisaaja; 
	public $Lisayspvm; 

	public $KayttajaDTO_Aineistonmuodostaja;
	public $KayttajaDTO_Aineiston_tilaaja;
	public $Aineistotilauksen_tilaDTO;
	public $Aineistotilauksen_tilatDTO;
	public $Kohdejoukon_tilausDTO;

	public $Aineistotilaus_sallittu;
    
}
?>