<?php
/*
 * FMAS Käyttölupapalvelu
 * Sitoumus Data access object
 *
 * Created: 7.10.2016
 */

class SitoumusDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function tallenna_sitoumus($fk_tutkimus, $fk_kayttaja){

		$sth = $this->db->prepare("INSERT INTO Sitoumus (FK_Tutkimus, FK_Kayttaja, Lisaaja) VALUES(?,?,?)");
		$sth->bindParam(1, $fk_tutkimus, PDO::PARAM_INT);
		$sth->bindParam(2, $fk_kayttaja, PDO::PARAM_INT);
		$sth->bindParam(3, $fk_kayttaja, PDO::PARAM_INT);
		return $sth->execute();

	}

	function hae_tutkimuksen_sitoumukset($fk_tutkimus){

		$query = "SELECT * FROM Sitoumus WHERE FK_Tutkimus=:fk_tutkimus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_tutkimus' => $fk_tutkimus));
		$result = $sth->fetchAll();
		$sitoumuksetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$sitoumusDTO = new SitoumusDTO();
			$sitoumusDTO->ID = $result[$i]["ID"];
			$sitoumusDTO->TutkimusDTO = new TutkimusDTO();
			$sitoumusDTO->TutkimusDTO->ID = $result[$i]["FK_Tutkimus"];
			$sitoumusDTO->KayttajaDTO = new KayttajaDTO();
			$sitoumusDTO->KayttajaDTO->ID = $result[$i]["FK_Kayttaja"];
			$sitoumusDTO->Lakkaamispvm = $result[$i]["Lakkaamispvm"];
			$sitoumusDTO->Lisaaja = $result[$i]["Lisaaja"];
			$sitoumusDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$sitoumuksetDTO[$i] = $sitoumusDTO;

		}

		return $sitoumuksetDTO;

	}

	function tutkimuksen_kayttaja_on_sitoutunut($fk_tutkimus, $fk_kayttaja){

		$query = "SELECT ID FROM Sitoumus WHERE FK_Tutkimus=:fk_tutkimus AND FK_Kayttaja=:fk_kayttaja";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_tutkimus' => $fk_tutkimus, ':fk_kayttaja' => $fk_kayttaja));
		return $sth->rowCount();

	}

	function hae_tutkimuksen_kayttajan_sitoumus($fk_tutkimus, $fk_kayttaja){

		$query = "SELECT * FROM Sitoumus WHERE ((FK_Tutkimus=:fk_tutkimus) AND (FK_Kayttaja=:fk_kayttaja))";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_tutkimus' => $fk_tutkimus, ':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetch();

		$sitoumusDTO = new SitoumusDTO();
		$sitoumusDTO->ID = $result["ID"];
		$sitoumusDTO->TutkimusDTO = new TutkimusDTO();
		$sitoumusDTO->TutkimusDTO->ID = $result["FK_Tutkimus"];
		$sitoumusDTO->KayttajaDTO = new KayttajaDTO();
		$sitoumusDTO->KayttajaDTO->ID = $result["FK_Kayttaja"];
		$sitoumusDTO->Lakkaamispvm = $result["Lakkaamispvm"];
		$sitoumusDTO->Lisaaja = $result["Lisaaja"];
		$sitoumusDTO->Lisayspvm = $result["Lisayspvm"];

		return $sitoumusDTO;

	}

	function poista_tutkimuksen_sitoumukset($fk_tutkimus){
		$query = "DELETE FROM Sitoumus WHERE FK_Tutkimus=:fk_tutkimus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_tutkimus' => $fk_tutkimus));
	}

}