<?php

/*
 * FMAS Käyttölupapalvelu
 * Hakemus Data transfer Object
 *
 * Created: 21.9.2016
 */

class HakemusDTO {

	public $ID;
	public $Viranomaisen_koodi;  
	public $Hakemuksen_tunnus;  
	public $Diaarinumero; 
	public $Kasittelija;
	public $Asiakirjatyyppi;
	public $Julkisuusluokka;
	public $Salassapitoaika;
	public $Salassapitoperuste;
	public $Suojaustaso;
	public $Henkilotietoja;
	public $Sailytysajan_pituus;
	public $Sailytysajan_peruste;
	public $Lisaaja;  
	public $Lisayspvm;  
	public $Muokkaaja;
	public $Muokkauspvm;   
	   
	public $HakemusversioDTO;
	public $AsiaDTO;

	public $Hakemuksen_tilaDTO;
	public $PaatosDTO;
	public $Hakemus_sisaltaa_viesteja;
	
	// Hakemukseen linkitettyjä hakemuksia
	public $muiden_viranomaisten_HakemuksetDTO;
	public $hakemushistoria_HakemuksetDTO;
	
	// Attribuutteja muista tauluista
	public $Tutkimuksen_nimi;
	public $Hakemuksen_tila;
	public $Tilan_pvm;
	public $Kasittelijan_nimi;
	public $Hakemuksen_yhteyshenkilo;

}
?>