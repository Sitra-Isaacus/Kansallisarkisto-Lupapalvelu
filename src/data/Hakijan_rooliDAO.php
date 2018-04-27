<?php
/*
 * FMAS Käyttölupapalvelu
 * Hakijan rooli Data access object
 *
 * Created: 28.9.2016
 */

class Hakijan_rooliDAO {

	public $db;

	function __construct($db) {
		$this->db = $db;
	}

	function luo_hakijan_rooli($hakemusversioDTO, $hakijaDTO, $rooli){

		$fk_hakemusversio = $hakemusversioDTO->ID;
		$fk_hakija = $hakijaDTO->ID;
		$kayt_id = $hakijaDTO->KayttajaDTO->ID;

		$query = "INSERT INTO Hakijan_rooli (FK_Hakemusversio, FK_Hakija, Hakijan_roolin_koodi, Lisaaja) VALUES (:fk_hakemusversio, :FK_Hakija, :rooli, :kayt_id)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':FK_Hakija' => $fk_hakija, ':kayt_id' => $kayt_id, ':rooli' => $rooli));

		$hakijan_rooliDTO = new Hakijan_rooliDTO();
		$hakijan_rooliDTO->ID = $this->db->lastInsertId();
		$hakijan_rooliDTO->HakemusversioDTO = $hakemusversioDTO;
		$hakijan_rooliDTO->HakijaDTO = $hakijaDTO;
		$hakijan_rooliDTO->Hakijan_roolin_koodi = $rooli;
		$hakijan_rooliDTO->Lisaaja = $kayt_id;

		return $hakijan_rooliDTO;

	}

	function luo_hakijan_muu_rooli($hakemusversioDTO, $hakijaDTO, $rooli, $muun_roolin_selite){

		$fk_hakemusversio = $hakemusversioDTO->ID;
		$fk_hakija = $hakijaDTO->ID;
		$kayt_id = $hakijaDTO->KayttajaDTO->ID;

		$query = "INSERT INTO Hakijan_rooli (FK_Hakemusversio, FK_Hakija, Hakijan_roolin_koodi, Muun_roolin_selite, Lisaaja) VALUES (:fk_hakemusversio, :FK_Hakija, :rooli, :muun_roolin_selite, :kayt_id)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':FK_Hakija' => $fk_hakija, ':kayt_id' => $kayt_id, ':rooli' => $rooli, ':muun_roolin_selite' => $muun_roolin_selite));

		$hakijan_rooliDTO = new Hakijan_rooliDTO();
		$hakijan_rooliDTO->ID = $this->db->lastInsertId();
		$hakijan_rooliDTO->HakemusversioDTO = $hakemusversioDTO;
		$hakijan_rooliDTO->HakijaDTO = $hakijaDTO;
		$hakijan_rooliDTO->Hakijan_roolin_koodi = $rooli;
		$hakijan_rooliDTO->Muun_roolin_selite = $muun_roolin_selite;
		$hakijan_rooliDTO->Lisaaja = $kayt_id;

		return $hakijan_rooliDTO;

	}

	function hae_hakijan_roolilla($hakijan_rooli){
		
		$query = "SELECT ID, FK_Hakemusversio FROM Hakijan_rooli WHERE Hakijan_roolin_koodi=:hakijan_rooli";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':hakijan_rooli' => $hakijan_rooli));
		$result = $sth->fetchAll();
		$hakijan_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$hakijan_rooliDTO = new Hakijan_rooliDTO();
			$hakijan_rooliDTO->ID = $result[$i]["ID"];
			$hakijan_rooliDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakijan_rooliDTO->HakemusversioDTO->ID = $result[$i]["FK_Hakemusversio"];
			$hakijan_roolitDTO[$i] = $hakijan_rooliDTO;
		}

		return $hakijan_roolitDTO;		
		
	}	
	
	function hae_hakijan_roolit_jotka_saavat_poistaa_hakemusversion($fk_hakemusversio, $kayt_id){

		$query = "SELECT ID, FK_Hakija FROM Hakijan_rooli WHERE FK_Hakemusversio=:fk_hakemusversio AND (Hakijan_roolin_koodi='rooli_vast' OR Hakijan_roolin_koodi='rooli_hak_yht' OR Lisaaja=:kayt_id)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':kayt_id' => $kayt_id));
		$sallitut_idt = $sth->fetchAll(PDO::FETCH_ASSOC);
		$hakijan_roolitDTO = array();

		for($i=0; $i < sizeof($sallitut_idt); $i++){
			$hakijan_rooliDTO = new Hakijan_rooliDTO();
			$hakijan_rooliDTO->ID = $sallitut_idt[$i]["ID"];
			$hakijan_rooliDTO->HakijaDTO = new HakijaDTO();
			$hakijan_rooliDTO->HakijaDTO->ID = $sallitut_idt[$i]["FK_Hakija"];
			$hakijan_roolitDTO[$i] = $hakijan_rooliDTO;
		}

		return $hakijan_roolitDTO;

	}

	function hae_hakijan_roolit_joilla_fk_hakija_saa_poistaa_fk_hakemusversion($fk_hakemusversio, $fk_hakija ,$rooli_vast, $rooli_hak_yht){

		$query = "SELECT ID FROM Hakijan_rooli WHERE FK_Hakemusversio=:fk_hakemusversio AND FK_Hakija=:fk_hakija AND (Hakijan_roolin_koodi=:rooli_vast OR Hakijan_roolin_koodi=:rooli_hak_yht)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':fk_hakija' => $fk_hakija, ':rooli_vast' => $rooli_vast, ':rooli_hak_yht' => $rooli_hak_yht));

		return $sth->fetchAll();

	}

	function hae_hakijan_roolin_tiedot_hakijan_avaimella($hakija_id){

		$query = "SELECT * FROM Hakijan_rooli WHERE FK_Hakija=:hakija_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':hakija_id' => $hakija_id));
		$result = $sth->fetchAll();
		$hakijan_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakijan_rooliDTO = new Hakijan_rooliDTO();
			$hakijan_rooliDTO->ID = $result[$i]["ID"];
			//
			$hakijan_rooliDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakijan_rooliDTO->HakemusversioDTO->ID = $result[$i]["FK_Hakemusversio"];
			$hakijan_rooliDTO->HakijaDTO = new HakijaDTO();
			$hakijan_rooliDTO->HakijaDTO->ID = $result[$i]["FK_Hakija"];
			$hakijan_rooliDTO->Hakijan_roolin_koodi = $result[$i]["Hakijan_roolin_koodi"];
			$hakijan_roolitDTO[$i] = $hakijan_rooliDTO;

		}

		return $hakijan_roolitDTO;

	}

	function hae_hakemusversion_hakijan_roolit($fk_hakemusversio){

		$query = "SELECT * FROM Hakijan_rooli WHERE FK_Hakemusversio=:fk_hakemusversio";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$hakijan_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakijan_rooliDTO = new Hakijan_rooliDTO();
			$hakijan_rooliDTO->ID = $result[$i]["ID"];
			//
			$hakijan_rooliDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakijan_rooliDTO->HakemusversioDTO->ID = $result[$i]["FK_Hakemusversio"];
			$hakijan_rooliDTO->HakijaDTO = new HakijaDTO();
			$hakijan_rooliDTO->HakijaDTO->ID = $result[$i]["FK_Hakija"];
			$hakijan_rooliDTO->Hakijan_roolin_koodi = $result[$i]["Hakijan_roolin_koodi"];
			$hakijan_roolitDTO[$i] = $hakijan_rooliDTO;

		}

		return $hakijan_roolitDTO;

	}
	
	function hae_hakemusversion_hakijan_rooli($fk_hakemusversio, $rooli){

		$query = "SELECT * FROM Hakijan_rooli WHERE FK_Hakemusversio=:fk_hakemusversio AND Hakijan_roolin_koodi=:rooli";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':rooli' => $rooli));
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$hakijan_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakijan_rooliDTO = new Hakijan_rooliDTO();
			$hakijan_rooliDTO->ID = $result[$i]["ID"];
			//
			$hakijan_rooliDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakijan_rooliDTO->HakemusversioDTO->ID = $result[$i]["FK_Hakemusversio"];
			$hakijan_rooliDTO->HakijaDTO = new HakijaDTO();
			$hakijan_rooliDTO->HakijaDTO->ID = $result[$i]["FK_Hakija"];
			$hakijan_rooliDTO->Hakijan_roolin_koodi = $result[$i]["Hakijan_roolin_koodi"];
			$hakijan_roolitDTO[$i] = $hakijan_rooliDTO;

		}

		return $hakijan_roolitDTO;

	}	

	function hae_hakemusversion_fk_hakijan_roolit($fk_hakemusversio, $fk_hakija){

		$query = "SELECT * FROM Hakijan_rooli WHERE (FK_Hakemusversio=:fk_hakemusversio) AND (FK_Hakija=:fk_hakija)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':fk_hakija' => $fk_hakija));
		$result = $sth->fetchAll();
		$hakijan_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakijan_rooliDTO = new Hakijan_rooliDTO();
			$hakijan_rooliDTO->ID = $result[$i]["ID"];
			// ...
			//$hakijan_rooliDTO->HakemusversioDTO = new HakemusversioDTO();
			//$hakijan_rooliDTO->HakemusversioDTO->ID = $result[$i]["FK_Hakemusversio"];
			//$hakijan_rooliDTO->HakijaDTO = new HakijaDTO();
			//$hakijan_rooliDTO->HakijaDTO->ID = $result[$i]["FK_Hakija"];
			$hakijan_rooliDTO->Hakijan_roolin_koodi = $result[$i]["Hakijan_roolin_koodi"];
			$hakijan_rooliDTO->Muun_roolin_selite = $result[$i]["Muun_roolin_selite"];
			$hakijan_roolitDTO[$i] = $hakijan_rooliDTO;

		}

		return $hakijan_roolitDTO;

	}

	function hae_hakemusversion_hakijat($fk_hakemusversio){

		$query = "SELECT FK_Hakija FROM Hakijan_rooli GROUP BY FK_Hakemusversio, FK_Hakija HAVING (FK_Hakemusversio=:fk_hakemusversio)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));
		$result = $sth->fetchAll();
		$hakijan_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakijan_rooliDTO = new Hakijan_rooliDTO();
			$hakijan_rooliDTO->HakijaDTO = new HakijaDTO();
			$hakijan_rooliDTO->HakijaDTO->ID = $result[$i]["FK_Hakija"];

			$hakijan_roolitDTO[$i] = $hakijan_rooliDTO;

		}

		return $hakijan_roolitDTO;

	}

	function poista_hakijan_hakijan_rooli($fk_hakija){

		$query = "DELETE FROM Hakijan_rooli WHERE FK_Hakija=:fk_hakija";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':fk_hakija' => $fk_hakija));

	}

	function poista_hakijan_rooli($id){
		$query = "DELETE FROM Hakijan_rooli WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':id' => $id));
	}

}