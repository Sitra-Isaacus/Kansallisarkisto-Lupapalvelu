<?php

/*
 * FMAS Käyttölupapalvelu
 * Tutkimus Data transfer Object
 *
 * Created: 28.9.2016
 */

class TutkimusDTO {

	public $ID;   
	public $Tutkimuksen_tunnus;
	public $Lisaaja;

	public $Nakymat_tutkimusDTO;

	public $Tutkimuksen_nimi; // Tutkimuksen nimi on uusimman hakemusversion nimi
	public $HakemusversioDTO;
	public $HakemusversiotDTO;
	
	public $Aineistotilaus_sallittu;
	public $Taydennyshakemuksen_luominen_sallittu;
   
}
?>