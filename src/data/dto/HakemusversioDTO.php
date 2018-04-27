<?php

/*
 * FMAS Käyttölupapalvelu
 * Hakemusversio Data transfer Object
 *
 * Created: 21.9.2016
 */

class HakemusversioDTO {

	public $ID;
	public $Tutkimuksen_nimi; 
	public $Asiakirjatyyppi;
	public $Hakemuksen_tyyppi;
	public $Tutkijaryhmaa_taydennetaan;
	public $Luvan_kestoa_jatketaan;
	public $Aineistoa_laajennetaan;
	public $Aineiston_seurantaa_jatketaan;
	public $Muu_muutoshakemuksen_tyyppi;
	public $Muun_muutoshakemuksen_tyypin_selite;	
	public $Hakemusversion_tunnus;
	public $Versio;

	// Asiakirjan metatiedot
	public $Salassapitoperuste;
	public $Salassapitoaika;
	public $Asiakirjan_tila;

	public $Lisaaja;
	public $Lisayspvm;
	public $Muokkaaja;
	public $Muokkauspvm;

	public $Lomakkeen_sivutDTO;
	public $HakijatDTO_kasittelyoikeutta_hakevat;
	public $HakijatDTO_ei_kasittelyoikeutta_hakevat;
	public $Hakemusversion_tilaDTO;
	public $TutkimusDTO;
	public $LomakeDTO;
	public $HakemuksetDTO;
	public $Haettu_aineistoDTO; // Valittu aineisto (object)
	public $Haetut_aineistotDTO; // Kaikki aineistot (array)
	public $LiitteetDTO;
	public $KayttajaDTO_Muokkaaja;
	public $Asiakirjahallinta_liitteetDTO; // Hakemus asiakirjaan viitatut liite asiakirjat
	public $Osallistuvat_organisaatiotDTO;

	public $On_oikeus_poistaa; // boolean
	public $On_oikeus_perua; // boolean
	public $On_oikeus_muokata; // boolean
	public $On_oikeus_lahettaa; // boolean 
	public $On_oikeus_kutsua_jasen; // boolean 
	public $On_oikeus_poistaa_jasen; // boolean 
	public $Lukittu_toiselle_kayttajalle; // boolean
   
}
?>