<?php
/*
 * FMAS Käyttölupapalvelu
 * Kohdejoukon tilaus Data access object
 *
 * Created: 24.10.2016
 */

class Kohdejoukon_tilausDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_kohdejoukon_tilaus($fk_aineistotilaus, $lisaaja){

		$query = "INSERT INTO Kohdejoukon_tilaus (FK_Aineistotilaus, Lisaaja) VALUES (:fk_aineistotilaus, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_aineistotilaus' => $fk_aineistotilaus, ':lisaaja' => $lisaaja));

		$kohdejoukon_tilausDTO = new Kohdejoukon_tilausDTO();
		$kohdejoukon_tilausDTO->ID = $this->db->lastInsertId();
		$kohdejoukon_tilausDTO->AineistotilausDTO = new AineistotilausDTO();
		$kohdejoukon_tilausDTO->AineistotilausDTO->ID = $fk_aineistotilaus;
		$kohdejoukon_tilausDTO->Lisaaja = $lisaaja;

		return $kohdejoukon_tilausDTO;

	}

	function maarita_paivamaara_kohdejoukon_muodostamiselle($kohdejoukko_muodostettu, $id){
		$query = "UPDATE Kohdejoukon_tilaus SET Kohdejoukko_muodostettu=:kohdejoukko_muodostettu WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':kohdejoukko_muodostettu' => $kohdejoukko_muodostettu, ':id' => $id));
	}

	function hae_kohdejoukon_tilaus_aineistotilaukselle($fk_aineistotilaus){

		$query = "SELECT * FROM Kohdejoukon_tilaus WHERE FK_Aineistotilaus=:fk_aineistotilaus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_aineistotilaus' => $fk_aineistotilaus));
		$result = $sth->fetch();

		$kohdejoukon_tilausDTO = new Kohdejoukon_tilausDTO();
		$kohdejoukon_tilausDTO->ID = $result["ID"];
		$kohdejoukon_tilausDTO->AineistotilausDTO = new AineistotilausDTO();
		$kohdejoukon_tilausDTO->AineistotilausDTO->ID = $result["FK_Aineistotilaus"];
		$kohdejoukon_tilausDTO->Kohdejoukko_muodostettu = $result["Kohdejoukko_muodostettu"];
		$kohdejoukon_tilausDTO->Kohdejoukon_muodostaja = $result["Kohdejoukon_muodostaja"];
		$kohdejoukon_tilausDTO->Lisaaja = $result["Lisaaja"];
		$kohdejoukon_tilausDTO->Lisayspvm = $result["Lisayspvm"];

		return $kohdejoukon_tilausDTO;

	}

}