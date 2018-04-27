<?php

/*
 * FMAS Käyttölupapalvelu
 * Osio Data transfer Object
 *
 * Created: 10.2.2016
 */

class OsioDTO {

	public $ID;
	public $Osio_nimi;
	public $Sivun_tunniste;
	public $Viranomaiskohtainen_tunniste;
	public $Osio_tyyppi;
	public $Otsikko;
	public $Osio_luokka;
	public $Pakollinen_tieto;
	public $Infoteksti;
	public $Sarakkeiden_lkm;
	public $Jarjestys;
	public $Lisaaja;
	public $Lisayspvm;
	public $Poistaja;
	public $Poistopvm;

	public $Tila;
	public $OsioDTO_parent;
	public $OsioDTO_childs;
	public $Osio_sisaltoDTO;
	public $Osio_saannotDTO;
	public $Pakollinen_tieto_puuttuu;

	// Käännökset
	public $Otsikko_fi;
	public $Otsikko_en;
	public $Otsikko_sv;
	public $Infoteksti_fi;
	public $Infoteksti_en;
	public $Infoteksti_sv;

}
?>