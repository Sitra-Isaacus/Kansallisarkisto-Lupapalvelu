<?php
/*
 * FMAS Käyttölupapalvelu
 * Viranomaisen rooli Data access object
 *
 * Created: 30.9.2016
 */

class Viranomaisen_rooliDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_viranomaisen_rooli($fk_kayttaja, $vir_koodi, $vir_rooli, $lisaaja){

		$query = "INSERT INTO Viranomaisen_rooli (FK_Kayttaja, Viranomaisen_koodi, Viranomaisroolin_koodi, Lisaaja) VALUES (:fk_kayttaja, :vir_koodi, :vir_rooli, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja, ':vir_koodi' => $vir_koodi, ':vir_rooli' => $vir_rooli, ':lisaaja' => $lisaaja));

		$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();
		$viranomaisen_rooliDTO->ID = $this->db->lastInsertId();
		$viranomaisen_rooliDTO->KayttajaDTO = new KayttajaDTO();
		$viranomaisen_rooliDTO->KayttajaDTO->ID = $fk_kayttaja;
		$viranomaisen_rooliDTO->Viranomaisen_koodi = $vir_koodi;
		$viranomaisen_rooliDTO->Viranomaisroolin_koodi = $vir_rooli;
		$viranomaisen_rooliDTO->Lisaaja = $lisaaja;

		return $viranomaisen_rooliDTO;

	}

	function hae_viranomaisen_tiedot($id){

		$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

		$query = "SELECT * FROM Viranomaisen_rooli WHERE ID=:id AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$viranomaisen_rooliDTO->ID = $id;
		$viranomaisen_rooliDTO->KayttajaDTO = new KayttajaDTO();
		$viranomaisen_rooliDTO->KayttajaDTO->ID = $result["FK_Kayttaja"];
		$viranomaisen_rooliDTO->Viranomaisen_koodi = $result["Viranomaisen_koodi"];
		$viranomaisen_rooliDTO->Viranomaisroolin_koodi = $result["Viranomaisroolin_koodi"];
		$viranomaisen_rooliDTO->Lisaaja = $result["Lisaaja"];
		$viranomaisen_rooliDTO->Lisayspvm = $result["Lisayspvm"];
		$viranomaisen_rooliDTO->Muokkaaja = $result["Muokkaaja"];
		$viranomaisen_rooliDTO->Muokkauspvm = $result["Muokkauspvm"];
		$viranomaisen_rooliDTO->Poistaja = $result["Poistaja"];
		$viranomaisen_rooliDTO->Poistopvm = $result["Poistopvm"];

		return $viranomaisen_rooliDTO;

	}

	function hae_viranomaisen_koodi($id){

		$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

		$query = "SELECT Viranomaisen_koodi FROM Viranomaisen_rooli WHERE ID=:id AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$viranomaisen_rooliDTO->ID = $id;
		$viranomaisen_rooliDTO->Viranomaisen_koodi = $result["Viranomaisen_koodi"];

		return $viranomaisen_rooliDTO;

	}

	function hae_kayttajan_viranomaisen_rooli($fk_kayttaja){

		$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

		$query = "SELECT * FROM Viranomaisen_rooli WHERE FK_Kayttaja=:fk_kayttaja AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetch();

		$viranomaisen_rooliDTO->ID = $result["ID"];
		$viranomaisen_rooliDTO->KayttajaDTO = new KayttajaDTO();
		$viranomaisen_rooliDTO->KayttajaDTO->ID = $result["FK_Kayttaja"];
		$viranomaisen_rooliDTO->Viranomaisen_koodi = $result["Viranomaisen_koodi"];
		$viranomaisen_rooliDTO->Viranomaisroolin_koodi = $result["Viranomaisroolin_koodi"];
		$viranomaisen_rooliDTO->Lisaaja = $result["Lisaaja"];
		$viranomaisen_rooliDTO->Lisayspvm = $result["Lisayspvm"];
		$viranomaisen_rooliDTO->Muokkaaja = $result["Muokkaaja"];
		$viranomaisen_rooliDTO->Muokkauspvm = $result["Muokkauspvm"];
		$viranomaisen_rooliDTO->Poistaja = $result["Poistaja"];
		$viranomaisen_rooliDTO->Poistopvm = $result["Poistopvm"];

		return $viranomaisen_rooliDTO;

	}

	function hae_kayttajalle_viranomaisen_roolit($fk_kayttaja){

		$query = "SELECT * FROM Viranomaisen_rooli WHERE FK_Kayttaja=:fk_kayttaja AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetchAll();
		$viranomaisen_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

			$viranomaisen_rooliDTO->ID = $result[$i]["ID"];
			$viranomaisen_rooliDTO->KayttajaDTO = new KayttajaDTO();
			$viranomaisen_rooliDTO->KayttajaDTO->ID = $result[$i]["FK_Kayttaja"];
			$viranomaisen_rooliDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$viranomaisen_rooliDTO->Viranomaisroolin_koodi = $result[$i]["Viranomaisroolin_koodi"];
			$viranomaisen_rooliDTO->Lisaaja = $result[$i]["Lisaaja"];
			$viranomaisen_rooliDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$viranomaisen_rooliDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$viranomaisen_rooliDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];

			$viranomaisen_roolitDTO[$i] = $viranomaisen_rooliDTO;

		}

		return $viranomaisen_roolitDTO;

	}

	function hae_organisaation_kasittelevat_viranomaiset($vir_koodi){

		$query = "SELECT * FROM Viranomaisen_rooli WHERE Viranomaisen_koodi=:vir_koodi AND Viranomaisroolin_koodi=:rooli_koodi AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':vir_koodi' => $vir_koodi, ':rooli_koodi' => 'rooli_kasitteleva'));
		$result = $sth->fetchAll();

		$viranomaisen_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

			$viranomaisen_rooliDTO->ID = $result[$i]["ID"];
			$viranomaisen_rooliDTO->KayttajaDTO = new KayttajaDTO();
			$viranomaisen_rooliDTO->KayttajaDTO->ID = $result[$i]["FK_Kayttaja"];
			$viranomaisen_rooliDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$viranomaisen_rooliDTO->Viranomaisroolin_koodi = $result[$i]["Viranomaisroolin_koodi"];
			$viranomaisen_rooliDTO->Lisaaja = $result[$i]["Lisaaja"];
			$viranomaisen_rooliDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$viranomaisen_rooliDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$viranomaisen_rooliDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];
			$viranomaisen_rooliDTO->Poistaja = $result[$i]["Poistaja"];
			$viranomaisen_rooliDTO->Poistopvm = $result[$i]["Poistopvm"];

			$viranomaisen_roolitDTO[$i] = $viranomaisen_rooliDTO;

		}

		return $viranomaisen_roolitDTO;

	}

	function hae_organisaation_viranomaiset_poislukien_haettu_kayttaja($vir_koodi, $fk_kayttaja){

		$query = "SELECT * FROM Viranomaisen_rooli WHERE Viranomaisen_koodi=:vir_koodi AND FK_Kayttaja<>:fk_kayttaja AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':vir_koodi' => $vir_koodi, ':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetchAll();

		$viranomaisen_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

			$viranomaisen_rooliDTO->ID = $result[$i]["ID"];
			$viranomaisen_rooliDTO->KayttajaDTO = new KayttajaDTO();
			$viranomaisen_rooliDTO->KayttajaDTO->ID = $result[$i]["FK_Kayttaja"];
			$viranomaisen_rooliDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$viranomaisen_rooliDTO->Viranomaisroolin_koodi = $result[$i]["Viranomaisroolin_koodi"];
			$viranomaisen_rooliDTO->Lisaaja = $result[$i]["Lisaaja"];
			$viranomaisen_rooliDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$viranomaisen_rooliDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$viranomaisen_rooliDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];
			$viranomaisen_rooliDTO->Poistaja = $result[$i]["Poistaja"];
			$viranomaisen_rooliDTO->Poistopvm = $result[$i]["Poistopvm"];

			$viranomaisen_roolitDTO[$i] = $viranomaisen_rooliDTO;

		}

		return $viranomaisen_roolitDTO;

	}

	function hae_viranomaisten_roolit_koodin_ja_roolin_perusteella($vir_koodi, $vir_rool_koodi){

		$query = "SELECT * FROM Viranomaisen_rooli WHERE Viranomaisen_koodi=:vir_koodi AND Viranomaisroolin_koodi=:vir_rool_koodi AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':vir_koodi' => $vir_koodi, ':vir_rool_koodi' => $vir_rool_koodi));
		$result = $sth->fetchAll();

		$viranomaisen_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

			$viranomaisen_rooliDTO->ID = $result[$i]["ID"];
			$viranomaisen_rooliDTO->KayttajaDTO = new KayttajaDTO();
			$viranomaisen_rooliDTO->KayttajaDTO->ID = $result[$i]["FK_Kayttaja"];
			$viranomaisen_rooliDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$viranomaisen_rooliDTO->Viranomaisroolin_koodi = $result[$i]["Viranomaisroolin_koodi"];
			$viranomaisen_rooliDTO->Lisaaja = $result[$i]["Lisaaja"];
			$viranomaisen_rooliDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$viranomaisen_rooliDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$viranomaisen_rooliDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];
			$viranomaisen_rooliDTO->Poistaja = $result[$i]["Poistaja"];
			$viranomaisen_rooliDTO->Poistopvm = $result[$i]["Poistopvm"];

			$viranomaisen_roolitDTO[$i] = $viranomaisen_rooliDTO;

		}

		return $viranomaisen_roolitDTO;

	}

	function hae_viranomaisten_koodit(){

		$query = "SELECT DISTINCT Viranomaisen_koodi FROM Viranomaisen_rooli WHERE Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$result = $sth->fetchAll();
		$viranomaisen_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();
			$viranomaisen_rooliDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$viranomaisen_roolitDTO[$i] = $viranomaisen_rooliDTO;
		}

		return $viranomaisen_roolitDTO;

	}

	function hae_organisaation_viranomaisen_roolit_ja_kayttajat($vir_koodi){

		$query = "SELECT DISTINCT Viranomaisen_rooli.FK_Kayttaja, Viranomaisen_rooli.Viranomaisen_koodi, Kayttaja.Etunimi, Kayttaja.Sukunimi, Kayttaja.Sahkopostiosoite FROM Viranomaisen_rooli INNER JOIN Kayttaja ON Viranomaisen_rooli.FK_Kayttaja=Kayttaja.ID AND Viranomaisen_rooli.Viranomaisen_koodi=:vir_koodi WHERE Viranomaisen_rooli.Poistaja IS NULL AND Viranomaisen_rooli.Poistopvm IS NULL;";
		//$query = "SELECT DISTINCT Viranomaisen_rooli.*, Kayttaja.Etunimi, Kayttaja.Sukunimi FROM Viranomaisen_rooli INNER JOIN Kayttaja ON Viranomaisen_rooli.FK_Kayttaja=Kayttaja.ID AND Viranomaisen_rooli.Viranomaisen_koodi=:vir_koodi WHERE Viranomaisen_rooli.Poistaja IS NULL AND Viranomaisen_rooli.Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':vir_koodi' => $vir_koodi));
		$result = $sth->fetchAll();
		$viranomaisen_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();
			//$viranomaisen_rooliDTO->ID = $result[$i]["ID"];
			$viranomaisen_rooliDTO->KayttajaDTO = new KayttajaDTO();
			$viranomaisen_rooliDTO->KayttajaDTO->ID = $result[$i]["FK_Kayttaja"];
			$viranomaisen_rooliDTO->KayttajaDTO->Etunimi = $result[$i]["Etunimi"];
			$viranomaisen_rooliDTO->KayttajaDTO->Sukunimi = $result[$i]["Sukunimi"];
			$viranomaisen_rooliDTO->KayttajaDTO->Sahkopostiosoite = $result[$i]["Sahkopostiosoite"];
			$viranomaisen_rooliDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			//$viranomaisen_rooliDTO->Viranomaisroolin_koodi = $result[$i]["Viranomaisroolin_koodi"];
			//$viranomaisen_rooliDTO->Lisaaja = $result[$i]["Lisaaja"];
			//$viranomaisen_rooliDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			//$viranomaisen_rooliDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			//$viranomaisen_rooliDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];
			$viranomaisen_roolitDTO[$i] = $viranomaisen_rooliDTO;
		}

		return $viranomaisen_roolitDTO;

	}

	function hae_kayttajan_viranomaisen_koodit($fk_kayttaja){

		$query = "SELECT DISTINCT Viranomaisen_koodi FROM Viranomaisen_rooli WHERE FK_Kayttaja=:fk_kayttaja AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetchAll();
		$viranomaisen_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();
			$viranomaisen_rooliDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$viranomaisen_roolitDTO[$i] = $viranomaisen_rooliDTO;
		}

		return $viranomaisen_roolitDTO;

	}

	function hae_kayttajan_viranomaisen_rooli_roolin_perusteella($fk_kayttaja, $viranomaisroolin_koodi){

		$viranomaisen_rooliDTO = new Viranomaisen_rooliDTO();

		$query = "SELECT * FROM Viranomaisen_rooli WHERE FK_Kayttaja=:fk_kayttaja AND Viranomaisroolin_koodi=:viranomaisroolin_koodi AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja, ':viranomaisroolin_koodi' => $viranomaisroolin_koodi));
		$result = $sth->fetch();

		$viranomaisen_rooliDTO->ID = $result["ID"];
		$viranomaisen_rooliDTO->KayttajaDTO = new KayttajaDTO();
		$viranomaisen_rooliDTO->KayttajaDTO->ID = $result["FK_Kayttaja"];
		$viranomaisen_rooliDTO->Viranomaisen_koodi = $result["Viranomaisen_koodi"];
		$viranomaisen_rooliDTO->Viranomaisroolin_koodi = $result["Viranomaisroolin_koodi"];
		$viranomaisen_rooliDTO->Lisaaja = $result["Lisaaja"];
		$viranomaisen_rooliDTO->Lisayspvm = $result["Lisayspvm"];
		$viranomaisen_rooliDTO->Muokkaaja = $result["Muokkaaja"];
		$viranomaisen_rooliDTO->Muokkauspvm = $result["Muokkauspvm"];
		$viranomaisen_rooliDTO->Poistaja = $result["Poistaja"];
		$viranomaisen_rooliDTO->Poistopvm = $result["Poistopvm"];

		return $viranomaisen_rooliDTO;

	}

	function viranomaiskayttajalla_on_haettu_rooli($fk_kayttaja, $vir_koodi, $vir_rooli){

		$query = "SELECT ID FROM Viranomaisen_rooli WHERE FK_Kayttaja=:fk_kayttaja AND Viranomaisen_koodi=:vir_koodi AND Viranomaisroolin_koodi=:vir_rooli AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja, ':vir_koodi' => $vir_koodi, ':vir_rooli' => $vir_rooli));

		if($sth->rowCount() > 0){
			return true;
		}

		return false;

	}

	function poista_kayttajan_viranomaisen_rooli($kayt_id, $id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Viranomaisen_rooli SET Poistaja=:kayt_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':kayt_id' => $kayt_id, ':nyt' => $nyt, ':id' => $id));

	}

}