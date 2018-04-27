<?php
/*
 * FMAS Käyttölupapalvelu
 * Paatoksen_liite Data access object
 *
 * Created: 13.10.2017
 */

class Paatoksen_liiteDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_paatoksen_liitetiedosto($fk_paatos, $fk_liite, $nimi){

		$query = "INSERT INTO Paatoksen_liite (FK_Paatos, FK_Liite, Liitteen_nimi) VALUES (:fk_paatos, :fk_liite, :nimi)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos, ':fk_liite' => $fk_liite, ':nimi' => $nimi));

		return $this->db->lastInsertId();

	}

	function hae_paatoksen_liite($fk_paatos){

		$query = "SELECT * FROM Paatoksen_liite WHERE FK_Paatos=:fk_paatos AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos));
		$result = $sth->fetch();

		$paatoksen_liiteDTO = new Paatoksen_liiteDTO();
		$paatoksen_liiteDTO->ID = $result["ID"];
		$paatoksen_liiteDTO->LiiteDTO = new LiiteDTO();
		$paatoksen_liiteDTO->LiiteDTO->ID = $result["FK_Liite"];

		return $paatoksen_liiteDTO;

	}		
	
	function hae_paatoksen_liitteet($fk_paatos){

		$query = "SELECT * FROM Paatoksen_liite WHERE FK_Paatos=:fk_paatos AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos));
		$result = $sth->fetchAll();
		$paatoksen_liitteetDTO = array();
		
		for($i=0; $i < sizeof($result); $i++){
			
			$paatoksen_liiteDTO = new Paatoksen_liiteDTO();
			$paatoksen_liiteDTO->ID = $result[$i]["ID"];
			$paatoksen_liiteDTO->Liitteen_nimi = $result[$i]["Liitteen_nimi"];
			$paatoksen_liiteDTO->LiiteDTO = new LiiteDTO();
			$paatoksen_liiteDTO->LiiteDTO->ID = $result[$i]["FK_Liite"];
			$paatoksen_liitteetDTO[$i] = $paatoksen_liiteDTO;
			
		}
		
		return $paatoksen_liitteetDTO;

	}	
	
	function hae_liite($fk_liite){
	
		$query = "SELECT * FROM Paatoksen_liite WHERE FK_Liite=:fk_liite AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_liite' => $fk_liite));
		$result = $sth->fetch();
		
		$paatoksen_liiteDTO = new Paatoksen_liiteDTO();
		$paatoksen_liiteDTO->ID = $result["ID"];
		$paatoksen_liiteDTO->LiiteDTO = new LiiteDTO();
		$paatoksen_liiteDTO->LiiteDTO->ID = $result["FK_Liite"];	
		$paatoksen_liiteDTO->PaatosDTO = new PaatosDTO();
		$paatoksen_liiteDTO->PaatosDTO->ID = $result["FK_Paatos"];		
		
		return $paatoksen_liiteDTO;	
	
	}	

	function merkitse_paatoksen_liite_poistetuksi($liite_id, $fk_paatos, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Paatoksen_liite SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE FK_Liite=:liite_id AND FK_Paatos=:fk_paatos";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':liite_id' => $liite_id, ':fk_paatos' => $fk_paatos));

	}

}