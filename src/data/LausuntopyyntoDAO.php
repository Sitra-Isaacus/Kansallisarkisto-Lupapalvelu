<?php
/*
 * FMAS Käyttölupapalvelu
 * Lausuntopyynto Data access object
 *
 * Created: 20.10.2016
 */

class LausuntopyyntoDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_lausuntopyynto($fk_tutkimus, $fk_hakemus, $fk_kayttaja, $lausunnon_antaja, $dnro, $lausuntopyynto, $lausunnon_mpvm, $lisaaja){

		$query = "INSERT INTO Lausuntopyynto (FK_Tutkimus, FK_Hakemus, FK_Kayttaja_Pyytaja, FK_Kayttaja_Antaja, Diaarinumero, Pyynto, Lausunnon_maarapaiva, Lisaaja) VALUES (:fk_tutkimus, :fk_hakemus, :fk_kayttaja, :lausunnon_antaja, :dnro, :lausuntopyynto, :lausunnon_mpvm, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_tutkimus' => $fk_tutkimus, ':fk_hakemus' => $fk_hakemus, ':fk_kayttaja' => $fk_kayttaja, ':lausunnon_antaja' => $lausunnon_antaja, ':dnro' => $dnro, ':lausuntopyynto' => $lausuntopyynto, ':lausunnon_mpvm' => $lausunnon_mpvm, ':lisaaja' => $lisaaja));

		$lausuntopyyntoDTO = new LausuntopyyntoDTO();
		$lausuntopyyntoDTO->ID = $this->db->lastInsertId();

		return $lausuntopyyntoDTO;

	}

	function hae_lausuntopyynnon_tiedot($id){

		$query = "SELECT * FROM Lausuntopyynto WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$lausuntopyyntoDTO = new LausuntopyyntoDTO();
		$lausuntopyyntoDTO->ID = $result["ID"];
		$lausuntopyyntoDTO->TutkimusDTO = new TutkimusDTO();
		$lausuntopyyntoDTO->TutkimusDTO->ID = $result["FK_Tutkimus"];
		$lausuntopyyntoDTO->HakemusDTO = new HakemusDTO();
		$lausuntopyyntoDTO->HakemusDTO->ID = $result["FK_Hakemus"];		
		$lausuntopyyntoDTO->KayttajaDTO_Pyytaja = new KayttajaDTO();
		$lausuntopyyntoDTO->KayttajaDTO_Pyytaja->ID = $result["FK_Kayttaja_Pyytaja"];
		$lausuntopyyntoDTO->KayttajaDTO_Antaja = new KayttajaDTO();
		$lausuntopyyntoDTO->KayttajaDTO_Antaja->ID = $result["FK_Kayttaja_Antaja"];
		$lausuntopyyntoDTO->Diaarinumero = $result["Diaarinumero"];
		$lausuntopyyntoDTO->Pyynto = $result["Pyynto"];
		$lausuntopyyntoDTO->Lausunnon_maarapaiva = $result["Lausunnon_maarapaiva"];
		$lausuntopyyntoDTO->Lisaaja = $result["Lisaaja"];
		$lausuntopyyntoDTO->Lisayspvm = $result["Lisayspvm"];

		return $lausuntopyyntoDTO;

	}

	function hae_hakemuksen_lausuntopyynnot($fk_hakemus){

		$query = "SELECT * FROM Lausuntopyynto WHERE FK_Hakemus=:fk_hakemus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemus' => $fk_hakemus));
		$result = $sth->fetchAll();
		$lausuntopyynnotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$lausuntopyyntoDTO = new LausuntopyyntoDTO();
			$lausuntopyyntoDTO->ID = $result[$i]["ID"];
			$lausuntopyyntoDTO->TutkimusDTO = new TutkimusDTO();
			$lausuntopyyntoDTO->TutkimusDTO->ID = $result[$i]["FK_Tutkimus"];
			$lausuntopyyntoDTO->HakemusDTO = new HakemusDTO();
			$lausuntopyyntoDTO->HakemusDTO->ID = $result[$i]["FK_Hakemus"];				
			$lausuntopyyntoDTO->KayttajaDTO_Pyytaja = new KayttajaDTO();
			$lausuntopyyntoDTO->KayttajaDTO_Pyytaja->ID = $result[$i]["FK_Kayttaja_Pyytaja"];
			$lausuntopyyntoDTO->KayttajaDTO_Antaja = new KayttajaDTO();
			$lausuntopyyntoDTO->KayttajaDTO_Antaja->ID = $result[$i]["FK_Kayttaja_Antaja"];
			$lausuntopyyntoDTO->Diaarinumero = $result[$i]["Diaarinumero"];
			$lausuntopyyntoDTO->Pyynto = $result[$i]["Pyynto"];
			$lausuntopyyntoDTO->Lausunnon_maarapaiva = $result[$i]["Lausunnon_maarapaiva"];
			$lausuntopyyntoDTO->Lisaaja = $result[$i]["Lisaaja"];
			$lausuntopyyntoDTO->Lisayspvm = $result[$i]["Lisayspvm"];

			$lausuntopyynnotDTO[$i] = $lausuntopyyntoDTO;

		}

		return $lausuntopyynnotDTO;

	}	
	
	function hae_tutkimuksen_lausuntopyynnot($fk_tutkimus){

		$query = "SELECT * FROM Lausuntopyynto WHERE FK_Tutkimus=:fk_tutkimus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_tutkimus' => $fk_tutkimus));
		$result = $sth->fetchAll();
		$lausuntopyynnotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$lausuntopyyntoDTO = new LausuntopyyntoDTO();
			$lausuntopyyntoDTO->ID = $result[$i]["ID"];
			$lausuntopyyntoDTO->TutkimusDTO = new TutkimusDTO();
			$lausuntopyyntoDTO->TutkimusDTO->ID = $result[$i]["FK_Tutkimus"];
			$lausuntopyyntoDTO->HakemusDTO = new HakemusDTO();
			$lausuntopyyntoDTO->HakemusDTO->ID = $result[$i]["FK_Hakemus"];				
			$lausuntopyyntoDTO->KayttajaDTO_Pyytaja = new KayttajaDTO();
			$lausuntopyyntoDTO->KayttajaDTO_Pyytaja->ID = $result[$i]["FK_Kayttaja_Pyytaja"];
			$lausuntopyyntoDTO->KayttajaDTO_Antaja = new KayttajaDTO();
			$lausuntopyyntoDTO->KayttajaDTO_Antaja->ID = $result[$i]["FK_Kayttaja_Antaja"];
			$lausuntopyyntoDTO->Diaarinumero = $result[$i]["Diaarinumero"];
			$lausuntopyyntoDTO->Pyynto = $result[$i]["Pyynto"];
			$lausuntopyyntoDTO->Lausunnon_maarapaiva = $result[$i]["Lausunnon_maarapaiva"];
			$lausuntopyyntoDTO->Lisaaja = $result[$i]["Lisaaja"];
			$lausuntopyyntoDTO->Lisayspvm = $result[$i]["Lisayspvm"];

			$lausuntopyynnotDTO[$i] = $lausuntopyyntoDTO;

		}

		return $lausuntopyynnotDTO;

	}

	function hae_lausuntopyynnot_lausunnonmuodostajalle($fk_kayttaja){

		$query = "SELECT * FROM Lausuntopyynto WHERE FK_Kayttaja_Antaja=:fk_kayttaja";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetchAll();
		$lausuntopyynnotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$lausuntopyyntoDTO = new LausuntopyyntoDTO();
			$lausuntopyyntoDTO->ID = $result[$i]["ID"];
			$lausuntopyyntoDTO->TutkimusDTO = new TutkimusDTO();
			$lausuntopyyntoDTO->TutkimusDTO->ID = $result[$i]["FK_Tutkimus"];
			$lausuntopyyntoDTO->HakemusDTO = new HakemusDTO();
			$lausuntopyyntoDTO->HakemusDTO->ID = $result[$i]["FK_Hakemus"];				
			$lausuntopyyntoDTO->KayttajaDTO_Pyytaja = new KayttajaDTO();
			$lausuntopyyntoDTO->KayttajaDTO_Pyytaja->ID = $result[$i]["FK_Kayttaja_Pyytaja"];
			$lausuntopyyntoDTO->KayttajaDTO_Antaja = new KayttajaDTO();
			$lausuntopyyntoDTO->KayttajaDTO_Antaja->ID = $result[$i]["FK_Kayttaja_Antaja"];
			$lausuntopyyntoDTO->Diaarinumero = $result[$i]["Diaarinumero"];
			$lausuntopyyntoDTO->Pyynto = $result[$i]["Pyynto"];
			$lausuntopyyntoDTO->Lausunnon_maarapaiva = $result[$i]["Lausunnon_maarapaiva"];
			$lausuntopyyntoDTO->Lisaaja = $result[$i]["Lisaaja"];
			$lausuntopyyntoDTO->Lisayspvm = $result[$i]["Lisayspvm"];

			$lausuntopyynnotDTO[$i] = $lausuntopyyntoDTO;

		}

		return $lausuntopyynnotDTO;

	}
	
	function hae_lausuntopyynnot_lausunnonpyytajalle($fk_kayttaja){

		$query = "SELECT * FROM Lausuntopyynto WHERE FK_Kayttaja_Pyytaja=:fk_kayttaja";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetchAll();
		$lausuntopyynnotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$lausuntopyyntoDTO = new LausuntopyyntoDTO();
			$lausuntopyyntoDTO->ID = $result[$i]["ID"];
			$lausuntopyyntoDTO->TutkimusDTO = new TutkimusDTO();
			$lausuntopyyntoDTO->TutkimusDTO->ID = $result[$i]["FK_Tutkimus"];
			$lausuntopyyntoDTO->HakemusDTO = new HakemusDTO();
			$lausuntopyyntoDTO->HakemusDTO->ID = $result[$i]["FK_Hakemus"];				
			$lausuntopyyntoDTO->KayttajaDTO_Pyytaja = new KayttajaDTO();
			$lausuntopyyntoDTO->KayttajaDTO_Pyytaja->ID = $result[$i]["FK_Kayttaja_Pyytaja"];
			$lausuntopyyntoDTO->KayttajaDTO_Antaja = new KayttajaDTO();
			$lausuntopyyntoDTO->KayttajaDTO_Antaja->ID = $result[$i]["FK_Kayttaja_Antaja"];
			$lausuntopyyntoDTO->Diaarinumero = $result[$i]["Diaarinumero"];
			$lausuntopyyntoDTO->Pyynto = $result[$i]["Pyynto"];
			$lausuntopyyntoDTO->Lausunnon_maarapaiva = $result[$i]["Lausunnon_maarapaiva"];
			$lausuntopyyntoDTO->Lisaaja = $result[$i]["Lisaaja"];
			$lausuntopyyntoDTO->Lisayspvm = $result[$i]["Lisayspvm"];

			$lausuntopyynnotDTO[$i] = $lausuntopyyntoDTO;

		}

		return $lausuntopyynnotDTO;

	}	

	function hae_antajalle_tutkimuksen_lausuntopyynnot($fk_tutkimus, $fk_kayttaja){

		$query = "SELECT * FROM Lausuntopyynto WHERE FK_Tutkimus=:fk_tutkimus AND FK_Kayttaja_Antaja=:fk_kayttaja";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_tutkimus' => $fk_tutkimus, ':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetchAll();
		$lausuntopyynnotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$lausuntopyyntoDTO = new LausuntopyyntoDTO();
			$lausuntopyyntoDTO->ID = $result[$i]["ID"];
			$lausuntopyyntoDTO->TutkimusDTO = new TutkimusDTO();
			$lausuntopyyntoDTO->TutkimusDTO->ID = $result[$i]["FK_Tutkimus"];
			$lausuntopyyntoDTO->HakemusDTO = new HakemusDTO();
			$lausuntopyyntoDTO->HakemusDTO->ID = $result[$i]["FK_Hakemus"];				
			$lausuntopyyntoDTO->KayttajaDTO_Pyytaja = new KayttajaDTO();
			$lausuntopyyntoDTO->KayttajaDTO_Pyytaja->ID = $result[$i]["FK_Kayttaja_Pyytaja"];
			$lausuntopyyntoDTO->KayttajaDTO_Antaja = new KayttajaDTO();
			$lausuntopyyntoDTO->KayttajaDTO_Antaja->ID = $result[$i]["FK_Kayttaja_Antaja"];
			$lausuntopyyntoDTO->Diaarinumero = $result[$i]["Diaarinumero"];
			$lausuntopyyntoDTO->Pyynto = $result[$i]["Pyynto"];
			$lausuntopyyntoDTO->Lausunnon_maarapaiva = $result[$i]["Lausunnon_maarapaiva"];
			$lausuntopyyntoDTO->Lisaaja = $result[$i]["Lisaaja"];
			$lausuntopyyntoDTO->Lisayspvm = $result[$i]["Lisayspvm"];

			$lausuntopyynnotDTO[$i] = $lausuntopyyntoDTO;

		}

		return $lausuntopyynnotDTO;

	}

}