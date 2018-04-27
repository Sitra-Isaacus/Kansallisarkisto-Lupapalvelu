<?php
/*
 * FMAS Käyttölupapalvelu
 * Paattaja Data access object
 *
 * Created: 12.7.2016
 */

class PaattajaDAO {

	public $db;

	function __construct($db) {
		$this->db = $db;
	}

	function lisaa_paattaja_paatokseen($fk_paatos, $fk_kayttaja, $lisaaja){

		$query = "INSERT INTO Paattaja (FK_Paatos, FK_Kayttaja, Lisaaja) VALUES (:fk_paatos, :fk_kayttaja, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_paatos' => $fk_paatos, ':fk_kayttaja' => $fk_kayttaja, ':lisaaja' => $lisaaja));

	}

	function paivita_paattajan_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Paattaja SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Paattaja SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}
 
	function hae_paatoksen_paattaja($fk_paatos, $fk_kayttaja){

		$query = "SELECT * FROM Paattaja WHERE FK_Paatos=:fk_paatos AND FK_Kayttaja=:fk_kayttaja AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos, ':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetch();

		$paattajaDTO = new PaattajaDTO();
		$paattajaDTO->ID = $result["ID"];
		$paattajaDTO->KayttajaDTO = new KayttajaDTO();
		$paattajaDTO->KayttajaDTO->ID = $result["FK_Kayttaja"];

		return $paattajaDTO;

	}

	function hae_paatoksen_paattajat($fk_paatos){

		$query = "SELECT * FROM Paattaja WHERE FK_Paatos=:fk_paatos AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos));
		$result = $sth->fetchAll();

		$paattajatDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$paattajatDTO[$i] = new PaattajaDTO();
			$paattajatDTO[$i]->ID = $result[$i]["ID"];
			$paattajatDTO[$i]->KayttajaDTO = new KayttajaDTO();
			$paattajatDTO[$i]->KayttajaDTO->ID = $result[$i]["FK_Kayttaja"];
			$paattajatDTO[$i]->Paatos_allekirjoitettu = $result[$i]["Paatos_allekirjoitettu"];
			$paattajatDTO[$i]->Muokkauspvm = $result[$i]["Muokkauspvm"];
		}

		return $paattajatDTO;

	}

	function merkitse_paattaja_poistetuksi($id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Paattaja SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':id' => $id));

	}

}