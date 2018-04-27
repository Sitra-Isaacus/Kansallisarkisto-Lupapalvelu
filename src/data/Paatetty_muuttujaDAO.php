<?php
/*
 * FMAS Käyttölupapalvelu
 * Paatetty_kori Data access object
 *
 * Created: 8.12.2016
 */

class Paatetty_muuttujaDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_paatetty_muuttuja_paatettyyn_koriin($fk_paatetty_kori, $muuttujan_koodi, $lisatieto){

		$query = "INSERT INTO Paatetty_muuttuja (FK_Paatetty_kori, Muuttujan_koodi, Lisatieto) VALUES (:fk_paatetty_kori, :muuttujan_koodi, :lisatieto)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_paatetty_kori' => $fk_paatetty_kori, ':muuttujan_koodi' => $muuttujan_koodi, ':lisatieto' => $lisatieto));

	}
   
	function hae_paatetyn_korin_paatetyt_muuttujat($fk_paatetty_kori){

		$query = "SELECT * FROM Paatetty_muuttuja WHERE FK_Paatetty_kori=:fk_paatetty_kori";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatetty_kori' => $fk_paatetty_kori));
		$result = $sth->fetchAll();
		$paatetyt_muuttujatDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$paatetty_muuttujaDTO = new Paatetty_muuttujaDTO();
			$paatetty_muuttujaDTO->ID = $result[$i]["ID"];
			$paatetty_muuttujaDTO->Muuttujan_koodi = $result[$i]["Muuttujan_koodi"];
			$paatetty_muuttujaDTO->Lisatieto = $result[$i]["Lisatieto"];
			$paatetyt_muuttujatDTO[$i] = $paatetty_muuttujaDTO;

		}

		return $paatetyt_muuttujatDTO;

	}

	function poista_paatetyn_korin_paatetyt_muuttujat($fk_paatetty_kori){

		$query = "DELETE FROM Paatetty_muuttuja WHERE FK_Paatetty_kori=:fk_paatetty_kori";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_paatetty_kori' => $fk_paatetty_kori));

	}

}

?>