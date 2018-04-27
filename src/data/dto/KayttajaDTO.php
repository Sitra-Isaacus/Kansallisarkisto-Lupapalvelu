<?php

/*
 * FMAS Käyttölupapalvelu
 * Kayttaja Data transfer Object
 *
 * Created: 28.9.2016
 */

class KayttajaDTO {

	public $ID;
	public $Sukunimi;
	public $Etunimi;
	public $Syntymaaika;
	public $Kieli_koodi;
	public $Salasana_hash;
	public $Puhelinnumero;
	public $Sahkopostiosoite;
	public $Lisaaja;
	public $Lisayspvm;
	public $Muokkaaja;
	public $Muokkauspvm;

	public $Paakayttajan_rooliDTO;
	public $Viranomaisen_rooliDTO;
	public $Viranomaisen_roolitDTO;
	public $SuojausDTO;
	public $SitoumusDTO;
	public $KayttolupaDTO;
	public $HakijaDTO;
	
	public $Lukemattomien_viestien_maara;
	public $Eraantyvien_kayttolupien_maara;
	public $Lukemattomien_lausuntojen_maara;

	public $Nimi; // Etunimi + Sukunimi
	public $Roolin_koodi;
	public $Viranomaisen_koodi;
	public $Viranomaisen_rooli_id;
	public $Paakayttajan_rooli_id;
   
}
?>