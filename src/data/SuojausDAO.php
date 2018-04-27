<?php
/*
 * FMAS Käyttölupapalvelu
 * Suojaus Data access object
 *
 * Created: 30.9.2016
 */

class SuojausDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function hae_kayttajan_suojaustunnus($kayt_id){

		$suojausDTO = new SuojausDTO();

		$query = "SELECT * FROM Suojaus WHERE FK_Kayttaja=:kayt_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':kayt_id' => $kayt_id));
		$result = $sth->fetch();

		$suojausDTO->ID = $result["ID"];
		$suojausDTO->KayttajaDTO = new KayttajaDTO();
		$suojausDTO->KayttajaDTO->ID = $result["FK_Kayttaja"];
		$suojausDTO->Suojaustunnus = $result["Suojaustunnus"];
		$suojausDTO->Lisayspvm = $result["Lisayspvm"];

		return $suojausDTO;

	}
	
	function hae_kayttajan_suojaustunnukset($kayt_id){
		
		$query = "SELECT * FROM Suojaus WHERE FK_Kayttaja=:kayt_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':kayt_id' => $kayt_id));
		$results = $sth->fetchAll();		
		
		$suojauksetDTO = array();
		
		foreach ($results as $indx => $result) {
			
			$suojausDTO = new SuojausDTO();
			$suojausDTO->ID = $result["ID"];
			$suojausDTO->KayttajaDTO = new KayttajaDTO();
			$suojausDTO->KayttajaDTO->ID = $result["FK_Kayttaja"];
			$suojausDTO->Suojaustunnus = $result["Suojaustunnus"];
			$suojausDTO->Lisayspvm = $result["Lisayspvm"];
			
			array_push($suojauksetDTO, $suojausDTO);
		
		}
		
		return $suojauksetDTO;
		
	}

	function poista_kayttajan_tokenit($fk_kayttaja){
		$query = "DELETE FROM Suojaus WHERE FK_Kayttaja=:fk_kayttaja";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja));
	}
	
	function poista_kayttajan_vanhentuneet_tokenit($fk_kayttaja){ 
		$query = "DELETE FROM Suojaus WHERE FK_Kayttaja=:fk_kayttaja AND Lisayspvm < (NOW() - INTERVAL 10080 MINUTE)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja));		
	}

	function lisaa_kayttajan_token($fk_kayttaja, $token){

		$suojausDTO = new SuojausDTO();

		$query = "INSERT INTO Suojaus (FK_Kayttaja, Suojaustunnus) VALUES (:fk_kayttaja, :token)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja, ':token' => $token));

		$suojausDTO->ID = $this->db->lastInsertId();
		$suojausDTO->KayttajaDTO = new KayttajaDTO;
		$suojausDTO->KayttajaDTO->ID = $fk_kayttaja;
		$suojausDTO->Suojaustunnus = $token;

		return $suojausDTO;

	}

}