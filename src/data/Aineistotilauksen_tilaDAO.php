<?php
/*
 * FMAS Käyttölupapalvelu
 * Aineistotilaus Data access object
 *
 * Created: 21.9.2016
 */

class Aineistotilauksen_tilaDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_aineistotilaukseen_tila($fk_aineistotilaus, $at_koodi, $lisaaja){
		$query = "INSERT INTO Aineistotilauksen_tila (FK_Aineistotilaus, Aineistotilauksen_tilan_koodi, Lisaaja) VALUES (:fk_aineistotilaus, :at_koodi, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_aineistotilaus' => $fk_aineistotilaus, ':at_koodi' => $at_koodi, ':lisaaja' => $lisaaja));
	}

	function hae_tilan_koodi_aineistotilauksen_avaimella($fk_aineistotilaus){

		$aineistotilauksen_tilaDTO = new Aineistotilauksen_tilaDTO();

		$query = "SELECT * FROM Aineistotilauksen_tila WHERE FK_Aineistotilaus=:fk_aineistotilaus ORDER BY Lisayspvm DESC LIMIT 1";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_aineistotilaus' => $fk_aineistotilaus));
		$result = $sth->fetch();

		$aineistotilauksen_tilaDTO->Lisayspvm = $result["Lisayspvm"];
		$aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi = $result["Aineistotilauksen_tilan_koodi"];

		return $aineistotilauksen_tilaDTO;

	} 

	function hae_tilojen_koodi_aineistotilauksen_avaimella($fk_aineistotilaus){

		$query = "SELECT * FROM Aineistotilauksen_tila WHERE FK_Aineistotilaus=:fk_aineistotilaus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_aineistotilaus' => $fk_aineistotilaus));
		$result = $sth->fetchAll();
		$aineistotilauksen_tilatDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$aineistotilauksen_tilaDTO = new Aineistotilauksen_tilaDTO();
			$aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi = $result[$i]["Aineistotilauksen_tilan_koodi"];
			$aineistotilauksen_tilaDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$aineistotilauksen_tilatDTO[$i] = $aineistotilauksen_tilaDTO;
		}

		return $aineistotilauksen_tilatDTO;

	}

	function hae_tilan_koodi_aineistotilauksen_avaimella_ja_tilan_koodilla($fk_aineistotilaus, $at_koodi){

		$query = "SELECT * FROM Aineistotilauksen_tila WHERE FK_Aineistotilaus=:fk_aineistotilaus AND Aineistotilauksen_tilan_koodi=:at_koodi ORDER BY Lisayspvm DESC LIMIT 1";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_aineistotilaus' => $fk_aineistotilaus, ':at_koodi' => $at_koodi));
		$result = $sth->fetch();

		$aineistotilauksen_tilaDTO->Lisayspvm = $result["Lisayspvm"];
		$aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi = $result["Aineistotilauksen_tilan_koodi"];

		return $aineistotilauksen_tilaDTO;

	}

}