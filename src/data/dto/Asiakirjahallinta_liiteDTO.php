<?php

/*
 * FMAS Käyttölupapalvelu
 * Asiakirjahallinta_liite Data transfer Object
 *
 * Created: 3.4.2017
 */

class Asiakirjahallinta_liiteDTO {

	public $ID;   
	public $LomakeDTO;
	public $Asiakirjan_tarkenne;
	public $Viite_asiakirjaan;
	public $Liitteen_nimi;
	public $Liitteen_nimi_fi;
	public $Liitteen_nimi_en;
	public $Liitteen_nimi_sv;
	public $Maksimi_tiedoston_koko;
	public $Sallitut_tiedostotyypit;
	public $Lisatiedot;
	public $Lisatiedot_fi;
	public $Lisatiedot_en;
	public $Lisatiedot_sv;

	public $Asiakirjahallinta_saannotDTO;
	public $Liite_on_pakollinen; // boolean
	public $Liite_puuttuu; // boolean

}
?>