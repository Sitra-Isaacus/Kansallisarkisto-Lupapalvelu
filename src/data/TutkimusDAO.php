<?php
/*
 * FMAS Käyttölupapalvelu
 * Tutkimus Data access object
 *
 * Created: 28.9.2016
 */

class TutkimusDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_tutkimus($tutkimuksen_tunnus, $kayt_id){

		$tutkimusDTO = new TutkimusDTO();

		$query = "INSERT INTO Tutkimus (Tutkimuksen_tunnus, Lisaaja) VALUES (:tutkimuksen_tunnus, :kayt_id)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':tutkimuksen_tunnus' => $tutkimuksen_tunnus, ':kayt_id' => $kayt_id));

		$tutkimusDTO->ID = $this->db->lastInsertId();
		$tutkimusDTO->Tutkimuksen_tunnus = $tutkimuksen_tunnus;
		$tutkimusDTO->Lisaaja = $kayt_id;

		return $tutkimusDTO;

	}

	function hae_tutkimus($id){

		$query = "SELECT Tutkimuksen_tunnus FROM Tutkimus WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$tutkimusDTO = new TutkimusDTO();
		$tutkimusDTO->ID = $id;
		$tutkimusDTO->Tutkimuksen_tunnus = $result["Tutkimuksen_tunnus"];

		return $tutkimusDTO;

	}
	
	function hae_tutkimukset(){

		$query = "SELECT * FROM Tutkimus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$result = $sth->fetchAll();
		$tutkimuksetDTO = array();
	
		for($i=0; $i < sizeof($result); $i++){
			
			$tutkimusDTO = new TutkimusDTO();
			$tutkimusDTO->ID = $result[$i]["ID"];
			$tutkimusDTO->Tutkimuksen_tunnus = $result[$i]["Tutkimuksen_tunnus"];
			
			$tutkimuksetDTO[$i] = $tutkimusDTO;
			
		}
		
		return $tutkimuksetDTO;
	
	}

	function hae_seuraava_vapaa_tutkimuksen_tunnus(){

		$query = "SELECT MAX(Tutkimuksen_tunnus) FROM Tutkimus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$result = $sth->fetch();

		return $result["MAX(Tutkimuksen_tunnus)"];

	}

	function tutkimus_loytyi_tunnuksella($tutkimuksen_tunnus){

		$query = "SELECT Tutkimuksen_tunnus FROM Tutkimus WHERE Tutkimuksen_tunnus=:tutkimuksen_tunnus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':tutkimuksen_tunnus' => $tutkimuksen_tunnus));

		if($sth->rowCount() > 0){
			return true;
		}

		return false;

	}

	function merkitse_tutkimus_poistetuksi($tutkimus_id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Tutkimus SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:tutkimus_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':tutkimus_id' => $tutkimus_id));

	}		
	
	function poista_tutkimus($id){

		$query = "DELETE FROM Tutkimus WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':id' => $id));

	}

}