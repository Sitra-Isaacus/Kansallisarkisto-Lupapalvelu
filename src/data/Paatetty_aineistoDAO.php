<?php
/*
 * FMAS Käyttölupapalvelu
 * Paatetty_aineisto Data access object
 *
 * Created: 2.12.2016
 */

class Paatetty_aineistoDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_paatetty_aineisto_paatokseen($fk_paatos, $aineiston_indeksi, $lisaaja){

		$query = "INSERT INTO Paatetty_aineisto (FK_Paatos, Aineiston_indeksi, Lisaaja) VALUES (:fk_paatos, :aineiston_indeksi, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos, ':aineiston_indeksi' => $aineiston_indeksi, ':lisaaja' => $lisaaja));

		$paatetty_aineistoDTO = new Paatetty_aineistoDTO();
		$paatetty_aineistoDTO->ID = $this->db->lastInsertId();
		$paatetty_aineistoDTO->PaatosDTO = new PaatosDTO();
		$paatetty_aineistoDTO->PaatosDTO->ID = $fk_paatos;
		$paatetty_aineistoDTO->Aineiston_indeksi = $aineiston_indeksi;
		$paatetty_aineistoDTO->Lisaaja = $lisaaja;

		return $paatetty_aineistoDTO;

	}
   
	function hae_paatoksen_paatetyt_aineistot($fk_paatos){

		$query = "SELECT * FROM Paatetty_aineisto WHERE FK_Paatos=:fk_paatos";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos));
		$result = $sth->fetchAll();

		$paatetyt_aineistotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$paatetty_aineistoDTO = new Paatetty_aineistoDTO();
			$paatetty_aineistoDTO->ID = $result[$i]["ID"];
			$paatetty_aineistoDTO->Aineiston_indeksi = $result[$i]["Aineiston_indeksi"];
			// ..
			$paatetyt_aineistotDTO[$i] = $paatetty_aineistoDTO;

		}

		return $paatetyt_aineistotDTO;

	}

}

?>