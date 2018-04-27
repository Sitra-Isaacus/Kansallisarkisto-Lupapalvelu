<?php
/*
 * FMAS Käyttölupapalvelu
 * Lausunnon_liite Data access object
 *
 * Created: 8.9.2017
 */

class Lausunnon_liiteDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_lausunnon_liitetiedosto($fk_lausunto, $fk_liite){

		$query = "INSERT INTO Lausunnon_liite (FK_Lausunto, FK_Liite) VALUES (:fk_lausunto, :fk_liite)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lausunto' => $fk_lausunto, ':fk_liite' => $fk_liite));

		return $this->db->lastInsertId();

	}

	function hae_lausunnon_liite($fk_lausunto){

		$query = "SELECT * FROM Lausunnon_liite WHERE FK_Lausunto=:fk_lausunto AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lausunto' => $fk_lausunto));
		$result = $sth->fetch();

		$lausunnon_liiteDTO = new Lausunnon_liiteDTO();
		$lausunnon_liiteDTO->ID = $result["ID"];
		$lausunnon_liiteDTO->LiiteDTO = new LiiteDTO();
		$lausunnon_liiteDTO->LiiteDTO->ID = $result["FK_Liite"];

		return $lausunnon_liiteDTO;

	}
	
	function hae_lausunnon_liitteet($fk_lausunto){

		$query = "SELECT * FROM Lausunnon_liite WHERE FK_Lausunto=:fk_lausunto AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lausunto' => $fk_lausunto));
		$result = $sth->fetchAll();
		$lausunnon_liitteetDTO = array();
		
		for($i=0; $i < sizeof($result); $i++){
			
			$lausunnon_liiteDTO = new Lausunnon_liiteDTO();
			$lausunnon_liiteDTO->ID = $result[$i]["ID"];
			$lausunnon_liiteDTO->LiiteDTO = new LiiteDTO();
			$lausunnon_liiteDTO->LiiteDTO->ID = $result[$i]["FK_Liite"];
			$lausunnon_liitteetDTO[$i] = $lausunnon_liiteDTO;
			
		}
		
		return $lausunnon_liitteetDTO;

	}

	function hae_liite($fk_liite){
	
		$query = "SELECT * FROM Lausunnon_liite WHERE FK_Liite=:fk_liite AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_liite' => $fk_liite));
		$result = $sth->fetch();
		
		$lausunnon_liiteDTO = new Lausunnon_liiteDTO();
		$lausunnon_liiteDTO->ID = $result["ID"];
		$lausunnon_liiteDTO->LiiteDTO = new LiiteDTO();
		$lausunnon_liiteDTO->LiiteDTO->ID = $result["FK_Liite"];	
		$lausunnon_liiteDTO->LausuntoDTO = new LausuntoDTO();
		$lausunnon_liiteDTO->LausuntoDTO->ID = $result["FK_Lausunto"];		
		
		return $lausunnon_liiteDTO;	
	
	}	

	function merkitse_lausunnon_liite_poistetuksi($liite_id, $fk_lausunto, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Lausunnon_liite SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE FK_Liite=:liite_id AND FK_Lausunto=:fk_lausunto";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':liite_id' => $liite_id, ':fk_lausunto' => $fk_lausunto));

	}

}