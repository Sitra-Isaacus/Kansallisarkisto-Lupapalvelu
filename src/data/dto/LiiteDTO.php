<?php

/*
 * FMAS Käyttölupapalvelu
 * Liite Data transfer Object
 *
 * Created: 7.10.2016
 */

class LiiteDTO {

	public $ID;
	public $Asiakirjatyyppi;
	public $Asiakirjatyypin_tarkenne;
	public $Liitteen_tyypin_koodi;
	public $Liitetiedosto_nimi;
 	public $Liitetiedosto_blob;
	public $Tiedostomuoto;
	public $Asiakirjan_tila;
	public $Versio;
	public $Julkisuusluokka;
	public $Salassapitoaika;
	public $Salassapitoperuste;
	public $Suojaustaso;
	public $Henkilotietoja;
	public $Sailytysajan_pituus;
	public $Sailytysajan_peruste;		
	public $Lisaaja;   
	public $Lisayspvm;     
	  
	public $KayttajaDTO; // Lisääjän tiedot
}
?>