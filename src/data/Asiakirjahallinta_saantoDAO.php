<?php
/*
 * FMAS Käyttölupapalvelu
 * Asiakirjahallinta_saanto Data access object
 *
 * Created: 3.4.2017
 */

class Asiakirjahallinta_saantoDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}
	
	function luo_asiakirjalle_saanto($fk_asiakirjahallinta_liite, $saanto, $lisaaja){

		$query = "INSERT INTO Asiakirjahallinta_saanto (FK_Asiakirjahallinta_liite, Saanto, Lisaaja) VALUES (:fk_asiakirjahallinta_liite, :saanto, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_asiakirjahallinta_liite' => $fk_asiakirjahallinta_liite, ':saanto' => $saanto, ':lisaaja' => $lisaaja));

		$asiakirjahallinta_saantoDTO = new Asiakirjahallinta_saantoDTO();
		$asiakirjahallinta_saantoDTO->ID = $this->db->lastInsertId();

		return $asiakirjahallinta_saantoDTO;

	}	

	function hae_asiakirjan_saannot($fk_asiakirjahallinta_liite){

		$query = "SELECT * FROM Asiakirjahallinta_saanto WHERE FK_Asiakirjahallinta_liite=:fk_asiakirjahallinta_liite AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_asiakirjahallinta_liite' => $fk_asiakirjahallinta_liite));
		$result = $sth->fetchAll();
		$asiakirjahallinta_saannotDTO = array();
		//$osio_lauseDAO = new Osio_lauseDAO($this->db);

		for($i=0; $i < sizeof($result); $i++){

			$asiakirjahallinta_saantoDTO = new Asiakirjahallinta_saantoDTO();
			$asiakirjahallinta_saantoDTO->ID = $result[$i]["ID"];
			$asiakirjahallinta_saantoDTO->Saanto = $result[$i]["Saanto"];
			//$asiakirjahallinta_saantoDTO->Osio_lauseetDTO = $osio_lauseDAO->hae_saannon_lauseet($result[$i]["ID"]);
			$asiakirjahallinta_saannotDTO[$i] = $asiakirjahallinta_saantoDTO;

		}

		return $asiakirjahallinta_saannotDTO;

	}	
	
	function poista_asiakirjan_saanto($id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Asiakirjahallinta_saanto SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':id' => $id));

	}

}