<?php
/*
 * FMAS Käyttölupapalvelu
 * Hakemusversion tila Data access object
 *
 * Created: 29.3.2017
 */

class Hakemusversion_tilaDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function luo_hakemusversion_tila($fk_hakemusversio, $tila, $lisaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "INSERT INTO Hakemusversion_tila (FK_Hakemusversio, Hakemusversion_tilan_koodi, Lisaaja, Lisayspvm) VALUES (:fk_hakemusversio, :tila, :lisaaja, :nyt)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':tila' => $tila, ':lisaaja' => $lisaaja, ':nyt' => $nyt));

	}

	function hae_hakemusversion_uusin_tila($fk_hakemusversio){

		$query = "SELECT * FROM Hakemusversion_tila WHERE FK_Hakemusversio=:fk_hakemusversio ORDER BY Lisayspvm DESC LIMIT 1";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));
		$result = $sth->fetch();

		$hakemusversion_tilaDTO = new Hakemusversion_tilaDTO();
		$hakemusversion_tilaDTO->ID = $result["ID"];
		$hakemusversion_tilaDTO->Hakemusversion_tilan_koodi = $result["Hakemusversion_tilan_koodi"];
		$hakemusversion_tilaDTO->Lisaaja = $result["Lisaaja"];
		$hakemusversion_tilaDTO->Lisayspvm = $result["Lisayspvm"];

		return $hakemusversion_tilaDTO;

	}

}