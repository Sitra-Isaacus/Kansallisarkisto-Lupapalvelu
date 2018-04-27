<?php
/*
 * FMAS Käyttölupapalvelu
 * Paatoksen tila Data access object
 *
 * Created: 19.10.2016
 */

class Paatoksen_tilaDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function luo_paatokselle_paatoksen_tila($fk_paatos, $p_tila, $lisaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "INSERT INTO Paatoksen_tila (FK_Paatos, Paatoksen_tilan_koodi, Lisaaja, Lisayspvm) VALUES (:fk_paatos, :p_tila, :lisaaja, :nyt)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':fk_paatos' => $fk_paatos, ':p_tila' => $p_tila, ':lisaaja' => $lisaaja, ':nyt' => $nyt));

	}

	function hae_paatoksen_uusin_paatoksen_tila($fk_paatos){

		$query = "SELECT * FROM Paatoksen_tila WHERE FK_Paatos=:fk_paatos ORDER BY Lisayspvm DESC LIMIT 1";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos));
		$result = $sth->fetch();

		$paatoksen_tilaDTO = new Paatoksen_tilaDTO();
		$paatoksen_tilaDTO->ID = $result["ID"];
		$paatoksen_tilaDTO->PaatosDTO = new PaatosDTO();
		$paatoksen_tilaDTO->PaatosDTO->ID = $result["FK_Paatos"];
		$paatoksen_tilaDTO->Paatoksen_tilan_koodi = $result["Paatoksen_tilan_koodi"];
		$paatoksen_tilaDTO->Lisaaja = $result["Lisaaja"];
		$paatoksen_tilaDTO->Lisayspvm = $result["Lisayspvm"];

		return $paatoksen_tilaDTO;

	}

}