<?php
/*
 * FMAS Käyttölupapalvelu
 * Lomake Data access object
 *
 * Created: 28.4.2017
 */

class LomakeDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function luo_lomake($lisaaja){

		$nimi = "Uusi lomake";

		$query = "INSERT INTO Lomake (Nimi, Lisaaja) VALUES (:nimi, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':nimi' => $nimi, ':lisaaja' => $lisaaja));

		$lomakeDTO = new LomakeDTO();
		$lomakeDTO->ID = $this->db->lastInsertId();
		$lomakeDTO->Nimi = $nimi;

		return $lomakeDTO;

	}

	function paivita_lomake($id, $asiakirjatyyppi, $asiakirjan_tarkenne, $nimi, $lomakkeen_tyyppi){

		$query = "UPDATE Lomake SET Asiakirjatyyppi=:asiakirjatyyppi, Asiakirjan_tarkenne=:asiakirjan_tarkenne, Nimi=:nimi, Lomakkeen_tyyppi=:lomakkeen_tyyppi WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':asiakirjatyyppi' => $asiakirjatyyppi, ':asiakirjan_tarkenne' => $asiakirjan_tarkenne, ':nimi' => $nimi, ':lomakkeen_tyyppi' => $lomakkeen_tyyppi, ':id' => $id));

	}

	function hae_lomake($id){

		$query = "SELECT * FROM Lomake WHERE ID=:id AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$lomakeDTO = new LomakeDTO();
		$lomakeDTO->ID = $result["ID"];
		$lomakeDTO->Nimi = $result["Nimi"];
		$lomakeDTO->Lomakkeen_tyyppi = $result["Lomakkeen_tyyppi"];
		$lomakeDTO->Asiakirjatyyppi = $result["Asiakirjatyyppi"];
		$lomakeDTO->Asiakirjan_tarkenne = $result["Asiakirjan_tarkenne"];
		$lomakeDTO->Lisaaja = $result["Lisaaja"];
		
		return $lomakeDTO;

	}

	function hae_lomakkeet(){

		$query = "SELECT * FROM Lomake WHERE Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$result = $sth->fetchAll();
		$lomakkeetDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$lomakeDTO = new LomakeDTO();
			$lomakeDTO->ID = $result[$i]["ID"];
			$lomakeDTO->Nimi = $result[$i]["Nimi"];
			$lomakeDTO->Lomakkeen_tyyppi = $result[$i]["Lomakkeen_tyyppi"];
			$lomakeDTO->Asiakirjatyyppi = $result[$i]["Asiakirjatyyppi"];
			$lomakeDTO->Asiakirjan_tarkenne = $result[$i]["Asiakirjan_tarkenne"];
			$lomakeDTO->KayttajaDTO = new KayttajaDTO();
			$lomakeDTO->KayttajaDTO->ID = $result[$i]["Lisaaja"];
			$lomakeDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$lomakkeetDTO[$i] = $lomakeDTO;
		}

		return $lomakkeetDTO;

	}

	function hae_tyypin_lomakkeet($lomakkeen_tyyppi){

		$query = "SELECT * FROM Lomake WHERE Lomakkeen_tyyppi=:lomakkeen_tyyppi AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':lomakkeen_tyyppi' => $lomakkeen_tyyppi));
		$result = $sth->fetchAll();
		$lomakkeetDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$lomakeDTO = new LomakeDTO();
			$lomakeDTO->ID = $result[$i]["ID"];
			$lomakeDTO->Nimi = $result[$i]["Nimi"];
			$lomakeDTO->Lomakkeen_tyyppi = $result[$i]["Lomakkeen_tyyppi"];
			$lomakeDTO->Asiakirjatyyppi = $result[$i]["Asiakirjatyyppi"];
			$lomakeDTO->Asiakirjan_tarkenne = $result[$i]["Asiakirjan_tarkenne"];
			$lomakeDTO->KayttajaDTO = new KayttajaDTO();
			$lomakeDTO->KayttajaDTO->ID = $result[$i]["Lisaaja"];
			$lomakeDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$lomakkeetDTO[$i] = $lomakeDTO;
		}

		return $lomakkeetDTO;

	}

	function hae_asiakirjan_lomake($asiakirja, $asiakirjan_tarkenne){

		if(is_null($asiakirjan_tarkenne)){
			$query = "SELECT * FROM Lomake WHERE Asiakirjatyyppi=:asiakirja AND Poistaja IS NULL AND Poistopvm IS NULL";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':asiakirja' => $asiakirja));
		} else {
			$query = "SELECT * FROM Lomake WHERE Asiakirjatyyppi=:asiakirja AND Asiakirjan_tarkenne=:asiakirjan_tarkenne AND Poistaja IS NULL AND Poistopvm IS NULL";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':asiakirja' => $asiakirja, ':asiakirjan_tarkenne' => $asiakirjan_tarkenne));
		}

		$result = $sth->fetch();

		$lomakeDTO = new LomakeDTO();
		$lomakeDTO->ID = $result["ID"];
		$lomakeDTO->Nimi = $result["Nimi"];

		return $lomakeDTO;

	}

	function merkitse_lomake_poistetuksi($id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Lomake SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':id' => $id));

	}
					
}

?>