<?php

/*
 * FMAS Käyttölupapalvelu
 * Jarjestelman_hakijan_roolit Data transfer Object
 *
 * Created: 10.4.2017
 */

class Jarjestelman_hakijan_roolitDTO {

	public $ID; 
	public $Hakemustyyppi;
	public $Hakijan_roolin_koodi;
	public $Hakijan_roolin_info;
	public $Pakollinen_rooli_hakemukselle;
	public $Hakemuksen_muokkaus_sallittu;
	public $Hakemuksen_lahetys_sallittu;
	public $Hakemuksen_poisto_sallittu;
	public $Hakemuksen_peruminen_sallittu;
	public $Hakemuksen_hakijan_kutsuminen_sallittu;
	public $Hakemuksen_hakijan_poistaminen_sallittu;
	public $Jarjestys; 

}
?>