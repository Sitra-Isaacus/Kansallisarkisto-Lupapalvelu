<?php

/*
 * FMAS Käyttölupapalvelu
 * Luvan_kohde Data transfer Object
 *
 * Created: 13.3.2017
 */

class Luvan_kohdeDTO {

	public $ID;
	public $Luvan_kohteen_nimi;
	public $Luvan_kohteen_tyyppi;
	public $Viranomaisen_koodi;
	public $Hyperlinkki;
	public $Identifier;
	public $Selite;	
	public $Viiteajankohta_alku;	
	public $Viiteajankohta_loppu;		
	public $Lupaviranomaisen_toimivalta_ryhma;
	
	public $MuuttujatDTO;

}
?>