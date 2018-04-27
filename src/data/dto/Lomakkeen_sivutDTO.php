<?php

/*
 * FMAS Käyttölupapalvelu
 * Lomakkeen_sivut Data transfer Object
 *
 * Created: 28.4.2017
 */

class Lomakkeen_sivutDTO {

	public $ID;
	public $LomakeDTO;
	public $Sivun_tunniste;
	public $Nimi;
	public $Sivun_kohde;
	public $Jarjestys;

	public $Pakollisia_tietoja_puuttuu;	// boolean
	public $OsiotDTO_puu;
	public $OsiotDTO_taulu;

	// Dynaamiset käännökset
	public $Nimi_fi;
	public $Nimi_sv;
	public $Nimi_en;

}
?>