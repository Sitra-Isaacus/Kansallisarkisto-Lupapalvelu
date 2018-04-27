<?php
/*
 * FMAS Käyttölupapalvelu
 * Kohdejoukon tilaus Data access object
 *
 * Created: 27.10.2016
 */

class Kohdejoukon_tilauksen_tilaDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_kohdejoukon_tilaukseen_tila($fk_kohdejoukon_tilaus, $kt_koodi, $lisaaja){
		$query = "INSERT INTO Kohdejoukon_tilauksen_tila (FK_Kohdejoukon_tilaus, Kohdejoukon_tilauksen_tilan_koodi, Lisaaja) VALUES (:fk_kohdejoukon_tilaus, :kt_koodi, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_kohdejoukon_tilaus' => $fk_kohdejoukon_tilaus, ':kt_koodi' => $kt_koodi, ':lisaaja' => $lisaaja));
	}

	function hae_kohdejoukon_tilaukselle_uusin_tila($fk_kohdejoukon_tilaus){

		$query = "SELECT * FROM Kohdejoukon_tilauksen_tila WHERE FK_Kohdejoukon_tilaus=:fk_kohdejoukon_tilaus ORDER BY Lisayspvm DESC LIMIT 1";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kohdejoukon_tilaus' => $fk_kohdejoukon_tilaus));
		$result = $sth->fetch();

		$kohdejoukon_tilauksen_tilaDTO = new Kohdejoukon_tilauksen_tilaDTO();
		$kohdejoukon_tilauksen_tilaDTO->ID = $result["ID"];
		$kohdejoukon_tilauksen_tilaDTO->Kohdejoukon_tilausDTO = new Kohdejoukon_tilausDTO();
		$kohdejoukon_tilauksen_tilaDTO->Kohdejoukon_tilausDTO->ID = $result["FK_Kohdejoukon_tilaus"];
		$kohdejoukon_tilauksen_tilaDTO->Kohdejoukon_tilauksen_tilan_koodi = $result["Kohdejoukon_tilauksen_tilan_koodi"];
		$kohdejoukon_tilauksen_tilaDTO->Lisaaja = $result["Lisaaja"];
		$kohdejoukon_tilauksen_tilaDTO->Lisaaja = $result["Lisayspvm"];

		return $kohdejoukon_tilauksen_tilaDTO;

	}

}