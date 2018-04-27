<?php
/*
 * FMAS Käyttölupapalvelu
 * Koodistot Data access object
 *
 * Created: 13.10.2016
 */

class KoodistotDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_koodisto($koodistoryhma, $koodi, $kieli, $selite1, $selite2, $selite3, $lisaaja){
		$query = "INSERT INTO Koodistot (Koodistoryhma, Koodi, Kieli, Selite1, Selite2, Selite3, Lisaaja) VALUES (:koodistoryhma, :koodi, :kieli, :selite1, :selite2, :selite3, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':koodistoryhma' => $koodistoryhma, ':koodi' => $koodi, ':kieli' => $kieli, ':selite1' => $selite1, ':selite2' => $selite2, ':selite3' => $selite3, ':lisaaja' => $lisaaja));
	}

    function hae_viranomaiset($kieli="fi"){

		$query = "SELECT * FROM Koodistot WHERE Koodistoryhma=:viranomainen AND Kieli=:kieli ORDER BY Koodi ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':viranomainen' => 'VIRANOMAINEN', ':kieli' => $kieli));
		$result = $sth->fetchAll();
		$koodistotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$koodistoDTO = new KoodistotDTO();
			$koodistoDTO->ID = $result[$i]["ID"];
			$koodistoDTO->Koodi = $result[$i]["Koodi"];
			$koodistoDTO->Selite1 = $result[$i]["Selite1"];
			$koodistoDTO->Selite2 = $result[$i]["Selite2"];
			$koodistoDTO->Selite3 = $result[$i]["Selite3"];
			$koodistotDTO[$i] = $koodistoDTO;

		}

		return $koodistotDTO;

	}

	function hae_koodin_tiedot($koodi,$kieli){

		$query = "SELECT * FROM Koodistot WHERE (Koodi=:koodi) AND (Kieli=:kieli)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':koodi' => $koodi, ':kieli' => $kieli));
		$result = $sth->fetch();
		$koodistotDTO = new KoodistotDTO();

		$koodistotDTO->ID = $result["ID"];
		$koodistotDTO->Koodi = $result["Koodi"];
		$koodistotDTO->Selite1 = $result["Selite1"];
		$koodistotDTO->Selite2 = $result["Selite2"];
		$koodistotDTO->Selite3 = $result["Selite3"];

		return $koodistotDTO;

	}

	function poista_koodin_koodistot($koodi){

		$query = "DELETE FROM Koodistot WHERE Koodi=:koodi";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':koodi' => $koodi));

	}

	function poista_koodistot($koodistoryhma, $koodi){

		$query = "DELETE FROM Koodistot WHERE Koodistoryhma=:koodistoryhma AND Koodi=:koodi";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':koodistoryhma' => $koodistoryhma, ':koodi' => $koodi));

	}

	function poista_kieli_koodistot($koodistoryhma, $koodi, $kieli){

		$query = "DELETE FROM Koodistot WHERE Koodistoryhma=:koodistoryhma AND Koodi=:koodi AND Kieli=:kieli";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':koodistoryhma' => $koodistoryhma, ':koodi' => $koodi, ':kieli' => $kieli));

	}

}