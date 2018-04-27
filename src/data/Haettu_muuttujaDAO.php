<?php
/*
 * FMAS Käyttölupapalvelu
 * Haettu_muuttuja Data access object
 *
 * Created: 6.10.2016
 */

class Haettu_muuttujaDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_haettu_muuttuja_haettuun_luvan_kohteeseen($fk_luvan_kohde, $haettu_muuttujan_koodi, $haettu_lisatieto){

		$query = "INSERT INTO Haettu_muuttuja (FK_Haettu_luvan_kohde, Muuttujan_koodi, Lisatieto) VALUES (:fk_luvan_kohde, :haettu_muuttujan_koodi, :haettu_lisatieto)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_luvan_kohde' => $fk_luvan_kohde, ':haettu_muuttujan_koodi' => $haettu_muuttujan_koodi, ':haettu_lisatieto' => $haettu_lisatieto));

	}

	function hae_haetun_luvan_kohteen_haetut_muuttujat($fk_haettu_luvan_kohde){

		$query = "SELECT * FROM Haettu_muuttuja WHERE FK_Haettu_luvan_kohde=:fk_haettu_luvan_kohde AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_haettu_luvan_kohde' => $fk_haettu_luvan_kohde));
		$muuttujat = $sth->fetchAll();
		$haetut_muuttujatDTO = array();

		for($i=0; $i < sizeof($muuttujat); $i++){

			$haettu_muuttujaDTO = new Haettu_muuttujaDTO();
			$haettu_muuttujaDTO->ID = $muuttujat[$i]["ID"];
			$haettu_muuttujaDTO->Muuttujan_koodi = $muuttujat[$i]["Muuttujan_koodi"];
			$haettu_muuttujaDTO->Lisatieto = $muuttujat[$i]["Lisatieto"];
			$haetut_muuttujatDTO[$i] = $haettu_muuttujaDTO;

		}

		return $haetut_muuttujatDTO;

	}

	function merkitse_haettu_muuttuja_poistetuksi($id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Haettu_muuttuja SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':id' => $id));

	} 

}