<?php

/*
 * FMAS Käyttölupapalvelu
 * Lausunto Data transfer Object
 *
 * Created: 20.10.2016
 */

class LausuntoDTO {

	public $ID;   
	public $LausuntopyyntoDTO;
	public $LomakeDTO;
	public $HakemusDTO;
	public $Lausunto_julkaistu;
	public $Lausunnon_muoto_koodi;
	public $Sanallinen_kuvaus; 
 	public $Lausunto_koodi; 
	public $Ehdollinen_puoltaminen;
	public $Johtopaatoksen_perustelut;
 	public $Naytetaanko_hakijoille; 
	public $Julkisuusluokka;
	public $Salassapitoaika;
	public $Salassapitoperuste;
	public $Suojaustaso;
	public $Henkilotietoja;
	public $Sailytysajan_pituus;
	public $Sailytysajan_peruste;		
 	public $Lisaaja; 
 	public $Lisayspvm; 

	public $Lomakkeen_sivutDTO;
	public $Lausunnon_liitteetDTO;

}
?>