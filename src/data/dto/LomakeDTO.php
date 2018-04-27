<?php

/*
 * FMAS Käyttölupapalvelu
 * Lomake Data transfer Object
 *
 * Created: 28.4.2017
 */

class LomakeDTO {

	public $ID;
	public $Asiakirjatyyppi;
	public $Asiakirjan_tarkenne;
	public $Nimi;
	public $Lomakkeen_tyyppi;
	public $Lisayspvm;
	public $Lisaaja;

	public $KayttajaDTO;
	public $Lomake_hakemusDTO;
	public $Lomake_paatosDTO;
	public $Lomakkeen_sivutDTO;
	public $Asiakirjahallinta_liitteetDTO;

}
?>