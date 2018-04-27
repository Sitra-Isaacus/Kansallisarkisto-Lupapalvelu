<?php
/*
 * FMAS Käyttölupapalvelu
 * Paatos Data access object
 *
 * Created: 21.9.2016
 */

class PaatosDAO {

	public $db;

   function __construct($db) {
       $this->db = $db;
   }

	function lisaa_paatos_hakemukseen($hakemus_id, $lomake_id, $kasittelija_id, $lisaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		$query = "INSERT INTO Paatos (FK_Hakemus, FK_Lomake, Kasittelija, Lisaaja, Lisayspvm) VALUES (:hakemus_id, :lomake_id, :kasittelija_id, :lisaaja, :nyt)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':hakemus_id' => $hakemus_id, ':lomake_id' => $lomake_id, ':kasittelija_id' => $kasittelija_id, ':lisaaja' => $lisaaja, ':nyt' => $nyt));
				
		$paatosDTO = new PaatosDTO();
		$paatosDTO->ID = $this->db->lastInsertId();
		$paatosDTO->HakemusDTO = new HakemusDTO();
		$paatosDTO->HakemusDTO->ID = $hakemus_id;
		$paatosDTO->LomakeDTO = new LomakeDTO();
		$paatosDTO->LomakeDTO->ID = $lomake_id;
		$paatosDTO->Kasittelija = $kasittelija_id;
		$paatosDTO->Lisaaja = $lisaaja;
		$paatosDTO->Lisayspvm = $nyt;

		return $paatosDTO;

	}

	function paivita_paatoksen_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Paatos SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Paatos SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}

	function tallenna_paatos($paatos, $lupa_voimassa_pvm, $kayttotarkoitus, $oikaisumahdollisuus, $luvan_ehdot, $paatos_id){
		$query = "UPDATE Paatos SET Alustava_paatos=:paatos, Lakkaamispvm=:lupa_voimassa_pvm, Kayttotarkoitus=:kayttotarkoitus, Oikaisumahdollisuus=:oikaisumahdollisuus, Luvan_ehdot=:luvan_ehdot WHERE ID=:paatos_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':paatos' => $paatos, ':lupa_voimassa_pvm' => $lupa_voimassa_pvm, ':kayttotarkoitus' => $kayttotarkoitus, ':oikaisumahdollisuus' => $oikaisumahdollisuus, ':paatos_id' => $paatos_id, ':luvan_ehdot' => $luvan_ehdot));
	}

	function laheta_paatos_hyvaksyttavaksi($kayttotarkoitus, $oikaisumahdollisuus, $luvan_ehdot, $paatos_id){
		$query = "UPDATE Paatos SET Kayttotarkoitus=:kayttotarkoitus, Oikaisumahdollisuus=:oikaisumahdollisuus, Luvan_ehdot=:luvan_ehdot WHERE ID=:paatos_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':kayttotarkoitus' => $kayttotarkoitus, ':oikaisumahdollisuus' => $oikaisumahdollisuus, ':paatos_id' => $paatos_id, ':luvan_ehdot' => $luvan_ehdot));
	}

	function laheta_paatos($kayttotarkoitus, $oikaisumahdollisuus, $luvan_ehdot, $kayt_id, $paatos_id){
		$query = "UPDATE Paatos SET Kayttotarkoitus=:kayttotarkoitus, Oikaisumahdollisuus=:oikaisumahdollisuus, Luvan_ehdot=:luvan_ehdot, Paattaja=:kayt_id WHERE ID=:paatos_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':kayttotarkoitus' => $kayttotarkoitus, ':oikaisumahdollisuus' => $oikaisumahdollisuus, ':luvan_ehdot' => $luvan_ehdot, ':kayt_id' => $kayt_id, ':paatos_id' => $paatos_id));
	}

	function peru_paatos($perusteet_perumiselle, $paatos_id){
		$query = "UPDATE Paatos SET Perusteet_perumiselle=:perusteet_perumiselle WHERE ID=:paatos_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':perusteet_perumiselle' => $perusteet_perumiselle, ':paatos_id' => $paatos_id));
	}
  
	function hae_paatoksen_tiedot($id){

		$paatosDTO = new PaatosDTO();

		$query = "SELECT * FROM Paatos WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$paatosDTO->ID = $result["ID"];
		$paatosDTO->HakemusDTO = new HakemusDTO();
		$paatosDTO->HakemusDTO->ID = $result["FK_Hakemus"];
		$paatosDTO->LomakeDTO = new LomakeDTO();
		$paatosDTO->LomakeDTO->ID = $result["FK_Lomake"];
		$paatosDTO->Paatoksen_tunnus = $result["Paatoksen_tunnus"];
		$paatosDTO->Hylkayksen_perustelut = $result["Hylkayksen_perustelut"];
		$paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan = $result["Puheenjohtajan_hyvaksynta_vaaditaan"];
		$paatosDTO->Vapaamuotoinen_paatos = $result["Vapaamuotoinen_paatos"];
		$paatosDTO->Asiakirjatyyppi = $result["Asiakirjatyyppi"];
		$paatosDTO->Palautettu_kasittelyyn = $result["Palautettu_kasittelyyn"];
		$paatosDTO->Alustava_paatos = $result["Alustava_paatos"];
		$paatosDTO->Ehdollisen_paatoksen_tyyppi = $result["Ehdollisen_paatoksen_tyyppi"];
		$paatosDTO->Lakkaamispvm = $result["Lakkaamispvm"];
		$paatosDTO->Maksu_euroina = $result["Maksu_euroina"];
		$paatosDTO->Maksu_peruste = $result["Maksu_peruste"];
		$paatosDTO->Luovutustapa = $result["Luovutustapa"];
		$paatosDTO->Luovutettavat_tiedot = $result["Luovutettavat_tiedot"];
		$paatosDTO->Luvan_ehdot = $result["Luvan_ehdot"];
		$paatosDTO->Perusteet_perumiselle = $result["Perusteet_perumiselle"];
		$paatosDTO->Paatospvm = $result["Paatospvm"];
		$paatosDTO->Lisaaja = $result["Lisaaja"];
		$paatosDTO->Lisayspvm = $result["Lisayspvm"];
		$paatosDTO->Kasittelija = $result["Kasittelija"];

		return $paatosDTO;

	}

	function hae_hakemuksen_paatos($fk_hakemus){

		$paatosDTO = new PaatosDTO();

		$query = "SELECT * FROM Paatos WHERE FK_Hakemus=:fk_hakemus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemus' => $fk_hakemus));
		$result = $sth->fetch();

		$paatosDTO->ID = $result["ID"];
		$paatosDTO->HakemusDTO = new HakemusDTO();
		$paatosDTO->HakemusDTO->ID = $result["FK_Hakemus"];
		$paatosDTO->LomakeDTO = new LomakeDTO();
		$paatosDTO->LomakeDTO->ID = $result["FK_Lomake"];		
		//$paatosDTO->Paatoksen_tunnus = $result["Paatoksen_tunnus"];
		$paatosDTO->Asiakirjatyyppi = $result["Asiakirjatyyppi"];
		$paatosDTO->Palautettu_kasittelyyn = $result["Palautettu_kasittelyyn"];
		$paatosDTO->Alustava_paatos = $result["Alustava_paatos"];
		$paatosDTO->Lakkaamispvm = $result["Lakkaamispvm"];
		$paatosDTO->Vapaamuotoinen_paatos = $result["Vapaamuotoinen_paatos"];
		$paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan = $result["Puheenjohtajan_hyvaksynta_vaaditaan"];
		$paatosDTO->Hylkayksen_perustelut = $result["Hylkayksen_perustelut"];
		$paatosDTO->Luovutettavat_tiedot = $result["Luovutettavat_tiedot"];
		$paatosDTO->Ehdollisen_paatoksen_tyyppi = $result["Ehdollisen_paatoksen_tyyppi"];
		$paatosDTO->Luvan_ehdot = $result["Luvan_ehdot"];
		$paatosDTO->Perusteet_perumiselle = $result["Perusteet_perumiselle"];
		$paatosDTO->Paatospvm = $result["Paatospvm"];
		$paatosDTO->Kasittelija = $result["Kasittelija"];
		$paatosDTO->Rekisterinpitaja_nimi = $result["Rekisterinpitaja_nimi"];
		$paatosDTO->Rekisterinpitaja_osoite = $result["Rekisterinpitaja_osoite"];
		$paatosDTO->Rekisterinpitaja_posti = $result["Rekisterinpitaja_posti"];
		$paatosDTO->Rekisterinpitaja_puhelin = $result["Rekisterinpitaja_puhelin"];
		$paatosDTO->Hinta_arvio = $result["Hinta_arvio"];
		$paatosDTO->Hinta_arvio_alkuvuosi = $result["Hinta_arvio_alkuvuosi"];
		$paatosDTO->Hinta_arvio_loppuvuosi = $result["Hinta_arvio_loppuvuosi"];
		$paatosDTO->Maksu_euroina = $result["Maksu_euroina"];
		$paatosDTO->Maksu_peruste = $result["Maksu_peruste"];		
		$paatosDTO->Luvan_lausunnot = $result["Luvan_lausunnot"];
		$paatosDTO->Sovelletut_oikeusohjeet = $result["Sovelletut_oikeusohjeet"];
		$paatosDTO->Luovutustapa = $result["Luovutustapa"];
		$paatosDTO->Valitusosoitus = $result["Valitusosoitus"];
		$paatosDTO->Maksu_organisaation_nimi = $result["Maksu_organisaation_nimi"];
		$paatosDTO->Maksu_organisaation_y_tunnus = $result["Maksu_organisaation_y_tunnus"];
		
		$paatosDTO->Julkisuusluokka = $result["Julkisuusluokka"];
		$paatosDTO->Salassapitoaika = $result["Salassapitoaika"];
		$paatosDTO->Salassapitoperuste = $result["Salassapitoperuste"];
		$paatosDTO->Suojaustaso = $result["Suojaustaso"];
		$paatosDTO->Henkilotietoja = $result["Henkilotietoja"];
		$paatosDTO->Sailytysajan_pituus = $result["Sailytysajan_pituus"];
		$paatosDTO->Sailytysajan_peruste = $result["Sailytysajan_peruste"];
		
		$paatosDTO->Lisaaja = $result["Lisaaja"];
		$paatosDTO->Lisayspvm = $result["Lisayspvm"];		

		return $paatosDTO;

	}

}