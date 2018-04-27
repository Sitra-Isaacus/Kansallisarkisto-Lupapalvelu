<?php
/*
 * FMAS Käyttölupapalvelu
 * Käyttölupa Data access object
 *
 * Created: 21.9.2016
 */

class KayttolupaDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_paatokseen_kayttolupa($lupa_voimassa_pvm, $fk_kayttaja, $lisaaja_id, $fk_paatos){

		if(isset($lupa_voimassa_pvm) && !is_null($lupa_voimassa_pvm) && $lupa_voimassa_pvm!=""){

			$query = "INSERT INTO Kayttolupa (FK_Paatos, FK_Kayttaja, Lakkaamispvm, Lisaaja) VALUES (:fk_paatos, :fk_kayttaja, :lupa_voimassa_pvm, :lisaaja_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_paatos' => $fk_paatos, ':fk_kayttaja' => $fk_kayttaja, ':lupa_voimassa_pvm' => $lupa_voimassa_pvm, ':lisaaja_id' => $lisaaja_id));

		} else {

			$query = "INSERT INTO Kayttolupa (FK_Paatos, FK_Kayttaja, Lisaaja) VALUES (:fk_paatos, :fk_kayttaja, :lisaaja_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_paatos' => $fk_paatos, ':fk_kayttaja' => $fk_kayttaja, ':lisaaja_id' => $lisaaja_id));

		}

	}

	function hae_kayttajan_kayttoluvat($kayt_id){

		$query = "SELECT * FROM Kayttolupa WHERE FK_Kayttaja=:kayt_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':kayt_id' => $kayt_id));
		$kayttoluvat = $sth->fetchAll();

		$kayttoluvat_dto = array();

		for($i=0; $i < sizeof($kayttoluvat); $i++){

			$kayttoluvat_dto[$i] = new KayttolupaDTO();
            $kayttoluvat_dto[$i]->ID = $kayttoluvat[$i]["ID"];
			$kayttoluvat_dto[$i]->Lakkaamispvm = $kayttoluvat[$i]["Lakkaamispvm"];
			$kayttoluvat_dto[$i]->Lisaaja = $kayttoluvat[$i]["Lisaaja"];
			$kayttoluvat_dto[$i]->Lisayspvm = $kayttoluvat[$i]["Lisayspvm"];

			$kayttoluvat_dto[$i]->PaatosDTO = new PaatosDTO();
			$kayttoluvat_dto[$i]->PaatosDTO->ID = $kayttoluvat[$i]["FK_Paatos"];

			$kayttoluvat_dto[$i]->KayttajaDTO = new KayttajaDTO();
			$kayttoluvat_dto[$i]->KayttajaDTO->ID = $kayt_id;

		}

		return $kayttoluvat_dto;

	}

	function hae_kayttajan_ja_paatoksen_kayttolupa($fk_paatos, $fk_kayttaja){

		$query = "SELECT * FROM Kayttolupa WHERE FK_Paatos=:fk_paatos AND FK_Kayttaja=:fk_kayttaja AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos, ':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetch();

		$kayttolupa_dto = new KayttolupaDTO();
        $kayttolupa_dto->ID = $result["ID"];
		$kayttolupa_dto->Lakkaamispvm = $result["Lakkaamispvm"];
		// .. todo: tänne kaikki muutki attribuutit

		$kayttolupa_dto->PaatosDTO = new PaatosDTO();
		$kayttolupa_dto->PaatosDTO->ID = $result["FK_Paatos"];

		$kayttolupa_dto->KayttajaDTO = new KayttajaDTO();
		$kayttolupa_dto->KayttajaDTO->ID = $fk_kayttaja;

		return $kayttolupa_dto;

	}

	function hae_paatokseen_liittyvat_kayttoluvat($fk_paatos){

		$query = "SELECT * FROM Kayttolupa WHERE FK_Paatos=:fk_paatos AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos));
		$kayttoluvat = $sth->fetchAll();

		$kayttoluvat_dto = array();

		for($i=0; $i < sizeof($kayttoluvat); $i++){

			$kayttoluvat_dto[$i] = new KayttolupaDTO();
            $kayttoluvat_dto[$i]->ID = $kayttoluvat[$i]["ID"];
			$kayttoluvat_dto[$i]->Lakkaamispvm = $kayttoluvat[$i]["Lakkaamispvm"];
			$kayttoluvat_dto[$i]->Lisaaja = $kayttoluvat[$i]["Lisaaja"];
			$kayttoluvat_dto[$i]->Lisayspvm = $kayttoluvat[$i]["Lisayspvm"];

			$kayttoluvat_dto[$i]->PaatosDTO = new PaatosDTO();
			$kayttoluvat_dto[$i]->PaatosDTO->ID = $kayttoluvat[$i]["FK_Paatos"];

			$kayttoluvat_dto[$i]->KayttajaDTO = new KayttajaDTO();
			$kayttoluvat_dto[$i]->KayttajaDTO->ID = $kayttoluvat[$i]["FK_Kayttaja"];

		}

		return $kayttoluvat_dto;

	}

	function poista_kayttajan_paatos($fk_paatos, $fk_kayttaja, $poistaja_id){
		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Kayttolupa SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE FK_Paatos=:fk_paatos AND FK_Kayttaja=:fk_kayttaja";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':fk_paatos' => $fk_paatos, ':fk_kayttaja' => $fk_kayttaja, ':nyt' => $nyt));
	}

	function poista_paatoksen_kayttoluvat($fk_paatos){
		$query = "DELETE FROM Kayttolupa WHERE FK_Paatos=:fk_paatos";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_paatos' => $fk_paatos));
	}
   
}

?>