<?php

/*
 * FMAS Käyttölupapalvelu
 * Paatos Data transfer Object
 *
 * Created: 21.9.2016
 */

class PaatosDTO {

	public $ID;
	public $HakemusDTO;
	public $LomakeDTO;
	public $Asiakirjatyyppi;
	public $Alustava_paatos;
	public $Lakkaamispvm;
	public $Vapaamuotoinen_paatos;
	public $Hylkayksen_perustelut;
	public $Puheenjohtajan_hyvaksynta_vaaditaan;
	public $Ehdollisen_paatoksen_tyyppi;
	public $Luovutettavat_tiedot;
	public $Luvan_ehdot;
	public $Perusteet_perumiselle;
	public $Kasittelija;
	public $Paatospvm;
	public $Rekisterinpitaja_nimi;
	public $Rekisterinpitaja_osoite;
	public $Rekisterinpitaja_posti;
	public $Rekisterinpitaja_puhelin;
	public $Maksu_euroina;
	public $Maksu_peruste;
	public $Hinta_arvio;	
	public $Hinta_arvio_alkuvuosi;	
	public $Hinta_arvio_loppuvuosi;	
	public $Luovutustapa;
	public $Luvan_lausunnot;	
	public $Sovelletut_oikeusohjeet;		
	public $Valitusosoitus;	
	public $Maksu_organisaation_nimi;	
	public $Maksu_organisaation_y_tunnus;
	public $Palautettu_kasittelyyn;
	
	public $Julkisuusluokka;
	public $Salassapitoaika;
	public $Salassapitoperuste;
	public $Suojaustaso;
	public $Henkilotietoja;
	public $Sailytysajan_pituus;
	public $Sailytysajan_peruste;	
	public $Lisaaja;
	public $Lisayspvm;

	public $KayttajaDTO_Kasittelija;
	public $KayttajaDTO_Paattaja;
	public $Paatoksen_tilaDTO; 
	public $KayttoluvatDTO;
	public $AineistotilausDTO;
	public $Paatetyt_aineistotDTO;
	public $PaattajatDTO;
	public $Paatoksen_liitteetDTO;

	public $Lomakkeen_sivutDTO;
	
	public $Laskutustieto_1; // Organisaation tai henkilön nimi (riippuen laskutustavasta)
	public $Laskutustieto_2; // Organisaation y-tunnus tai henkilön osoite (riippuen laskutustavasta)

}

?>