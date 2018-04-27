<?php
/*
 * FMAS Käyttölupapalvelu
 * Hakemusversion_liite Data access object
 *
 * Created: 3.4.2017
 */

class Hakemusversion_liiteDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_hakemusversion_liitetiedosto($fk_hakemusversio, $fk_liite){

		$query = "INSERT INTO Hakemusversion_liite (FK_Hakemusversio, FK_Liite) VALUES (:fk_hakemusversio, :fk_liite)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':fk_liite' => $fk_liite));

		return $this->db->lastInsertId();

	}
	
	function hae_liite($fk_liite){
	
		$query = "SELECT * FROM Hakemusversion_liite WHERE FK_Liite=:fk_liite AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_liite' => $fk_liite));
		$result = $sth->fetch();
		
		$hakemusversion_liiteDTO = new Hakemusversion_liiteDTO();
		$hakemusversion_liiteDTO->ID = $result["ID"];
		$hakemusversion_liiteDTO->LiiteDTO = new LiiteDTO();
		$hakemusversion_liiteDTO->LiiteDTO->ID = $result["FK_Liite"];	
		$hakemusversion_liiteDTO->HakemusversioDTO = new HakemusversioDTO();
		$hakemusversion_liiteDTO->HakemusversioDTO->ID = $result["FK_Hakemusversio"];		
		
		return $hakemusversion_liiteDTO;	
	
	}

	function hae_hakemusversion_liitteet($hakemusversio_id){

		$query = "SELECT * FROM Hakemusversion_liite WHERE FK_Hakemusversio=:hakemusversio_id AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':hakemusversio_id' => $hakemusversio_id));
		$result = $sth->fetchAll();
		$hakemusversion_liitteetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemusversion_liiteDTO = new Hakemusversion_liiteDTO();
			$hakemusversion_liiteDTO->ID = $result[$i]["ID"];
			$hakemusversion_liiteDTO->LiiteDTO = new LiiteDTO();
			$hakemusversion_liiteDTO->LiiteDTO->ID = $result[$i]["FK_Liite"];

			$hakemusversion_liitteetDTO[$i] = $hakemusversion_liiteDTO;

		}

		return $hakemusversion_liitteetDTO;

	}

	function merkitse_hakemusversion_liite_poistetuksi($liite_id, $hakemusversio_id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Hakemusversion_liite SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE FK_Liite=:liite_id AND FK_Hakemusversio=:hakemusversio_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':liite_id' => $liite_id, ':hakemusversio_id' => $hakemusversio_id));

	}

}