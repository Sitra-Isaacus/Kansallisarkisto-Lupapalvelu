<?php
/*
 * FMAS Käyttölupapalvelu
 * Osio_saanto Data access object
 *
 * Created: 2.3.2017
 */

class Osio_saantoDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function luo_osio_saanto($saanto, $fk_osio_muuttuja, $lisaaja){

		$query = "INSERT INTO Osio_saanto (Saanto, FK_Osio_muuttuja, Lisaaja) VALUES (:saanto, :fk_osio_muuttuja, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':saanto' => $saanto, ':fk_osio_muuttuja' => $fk_osio_muuttuja, ':lisaaja' => $lisaaja));

		$osio_saantoDTO = new Osio_saantoDTO();
		$osio_saantoDTO->ID = $this->db->lastInsertId();

		return $osio_saantoDTO;

	}

	function paivita_osion_saannon_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Osio_saanto SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Osio_saanto SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}

	function hae_osio_saanto($id){

		$query = "SELECT * FROM Osio_saanto WHERE ID=:id AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$osio_saantoDTO = new Osio_saantoDTO();
		$osio_saantoDTO->ID = $result["ID"];
		$osio_saantoDTO->Saanto = $result["Saanto"];
		$osio_saantoDTO->OsioDTO_Muuttuja = new OsioDTO();
		$osio_saantoDTO->OsioDTO_Muuttuja->ID = $result["FK_Osio_muuttuja"];

		return $osio_saantoDTO;

	}

	function hae_osion_saannot($fk_osio){

		$osio_lauseDAO = new Osio_lauseDAO($this->db);

		$query = "SELECT * FROM Osio_saanto WHERE FK_Osio_muuttuja=:fk_osio AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_osio' => $fk_osio));
		$result = $sth->fetchAll();
		$osio_saannotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$osio_saantoDTO = new Osio_saantoDTO();
			$osio_saantoDTO->ID = $result[$i]["ID"];
			$osio_saantoDTO->Saanto = $result[$i]["Saanto"];
			$osio_saantoDTO->OsioDTO_Muuttuja = new OsioDTO();
			$osio_saantoDTO->OsioDTO_Muuttuja->ID = $result[$i]["FK_Osio_muuttuja"];
			$osio_saantoDTO->Osio_lauseetDTO = $osio_lauseDAO->hae_saannon_lauseet($result[$i]["ID"]);

			$osio_saannotDTO[$i] = $osio_saantoDTO;

		}

		return $osio_saannotDTO;

	}

	function merkitse_osio_saanto_poistetuksi($id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Osio_saanto SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':id' => $id));

	}

}