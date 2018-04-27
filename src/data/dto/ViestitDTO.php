<?php

/*
 * FMAS Käyttölupapalvelu
 * Viestit Data transfer Object
 *
 * Created: 26.9.2016
 */

class ViestitDTO {

	public $ID;

	public $HakemusDTO;

	public $KayttajaDTO_Lahettaja;
	public $KayttajaDTO_Vastaanottaja;
	public $Viesti;
	public $Taydennettavaa_hakemukseen;
	public $Luettu;
	public $Lisayspvm;
	public $ViestitDTO_Parent;
	public $ViestitDTO_Child;

	public $ViestitDTO_Vastaukset;
	public $lukemattomien_viestien_maara_vastaanottajalle;   
	   
}
?>