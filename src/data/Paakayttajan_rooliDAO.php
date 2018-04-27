<?php
/*
 * FMAS Käyttölupapalvelu
 * Paakayttajan rooli Data access object
 *
 * Created: 30.9.2016
 */

class Paakayttajan_rooliDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function hae_kayttajalle_paakayttajan_rooli($fk_kayttaja){

		$query = "SELECT * FROM Paakayttajan_rooli WHERE FK_Kayttaja=:fk_kayttaja AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Lisayspvm DESC LIMIT 1";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja));
		$onPaakayttaja = $sth->rowCount();

		if($onPaakayttaja > 0){

			$result = $sth->fetch();

			$paakayttajan_rooliDTO = new Paakayttajan_rooliDTO();
			$paakayttajan_rooliDTO->ID = $result["ID"];
			$paakayttajan_rooliDTO->KayttajaDTO = new KayttajaDTO();
			$paakayttajan_rooliDTO->KayttajaDTO->ID = $result["FK_Kayttaja"];
			$paakayttajan_rooliDTO->Paakayttajaroolin_koodi = $result["Paakayttajaroolin_koodi"];
			$paakayttajan_rooliDTO->Lisaaja = $result["Lisaaja"];
			$paakayttajan_rooliDTO->Lisayspvm = $result["Lisayspvm"];
			$paakayttajan_rooliDTO->Muokkaaja = $result["Muokkaaja"];
			$paakayttajan_rooliDTO->Muokkauspvm = $result["Muokkauspvm"];
			$paakayttajan_rooliDTO->Poistaja = $result["Poistaja"];
			$paakayttajan_rooliDTO->Poistopvm = $result["Poistopvm"];

			return $paakayttajan_rooliDTO;

		} else {
			return false;
		}

	}

	function kayttaja_on_lupapalvelun_paakayttaja($fk_kayttaja){

		$query = "SELECT ID FROM Paakayttajan_rooli WHERE FK_Kayttaja=:fk_kayttaja AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja));

		if($sth->rowCount() > 0){
			return true;
		} 

		return false;

	}

}