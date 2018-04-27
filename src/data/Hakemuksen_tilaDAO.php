<?php
/*
 * FMAS Käyttölupapalvelu
 * Hakija Data access object
 *
 * Created: 28.9.2016
 */

class Hakemuksen_tilaDAO {

	public $db;

	function __construct($db) {
		$this->db = $db;
	}

	function luo_hakemuksen_tila($FK_Hakemus, $lisaaja, $hak_tila_koodi){
		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "INSERT INTO Hakemuksen_tila (FK_Hakemus, Hakemuksen_tilan_koodi, Nyt, Lisaaja, Lisayspvm) VALUES ('$FK_Hakemus', '$hak_tila_koodi', 1, '$lisaaja', '$nyt')";
		return $this->db->query($query);
	}

	function maarita_hakemuksen_tiloista_tamanhetkiset_pois($fk_hakemus){
		$query = "UPDATE Hakemuksen_tila SET Nyt=0 WHERE FK_Hakemus=:fk_hakemus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemus' => $fk_hakemus));
	}

	function etsi_tilan_perusteella($hak_tila){

		$query = "SELECT * FROM Hakemuksen_tila WHERE Hakemuksen_tilan_koodi=:hak_tila AND Nyt=1 ORDER BY Lisayspvm DESC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$result = $sth->fetchAll();

		$hakemuksen_tilatDTO = array();
		
		for($i=0; $i < sizeof($result); $i++){

			$hakemuksen_tilaDTO = new Hakemuksen_tilaDTO();

			$hakemuksen_tilaDTO->ID = $result[$i]["ID"];
			$hakemuksen_tilaDTO->Hakemuksen_tilan_koodi = $result[$i]["Hakemuksen_tilan_koodi"];
			$hakemuksen_tilaDTO->Nyt = $result[$i]["Nyt"];
			$hakemuksen_tilaDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakemuksen_tilaDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakemuksen_tilaDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$hakemuksen_tilaDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];

			$hakemuksen_tilaDTO->HakemusDTO = new HakemusDTO();
			$hakemuksen_tilaDTO->HakemusDTO->ID = $result[$i]["FK_Hakemus"];

			$hakemuksen_tilatDTO[$i] = $hakemuksen_tilaDTO;

		}

		return $hakemuksen_tilatDTO;
		
	}
	
	function etsi_alkuvuoden_ja_loppuvuoden_perusteella($vuosi_alku, $vuosi_loppu){

		$query = "SELECT * FROM Hakemuksen_tila WHERE Nyt=1 AND YEAR(Hakemuksen_tila.Lisayspvm) BETWEEN :vuosi_alku AND :vuosi_loppu";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':vuosi_alku' => $vuosi_alku, ':vuosi_loppu' => $vuosi_loppu));
		$results = $sth->fetchAll();
	
		$hakemuksen_tilatDTO = array();

		foreach ($results as $indx => $result){	

			$hakemuksen_tilaDTO = new Hakemuksen_tilaDTO();

			$hakemuksen_tilaDTO->ID = $result["ID"];
			$hakemuksen_tilaDTO->HakemusDTO = new HakemusDTO();
			$hakemuksen_tilaDTO->HakemusDTO->ID = $result["FK_Hakemus"];
			
			array_push($hakemuksen_tilatDTO, $hakemuksen_tilaDTO);

		}

		return $hakemuksen_tilatDTO;		
		
	}
	
	function hae_hakemuksen_tilat(){

		$query = "SELECT * FROM Hakemuksen_tila WHERE Nyt=1 ORDER BY Lisayspvm DESC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$result = $sth->fetchAll();
		$hakemuksen_tilatDTO = array();
		
		for($i=0; $i < sizeof($result); $i++){

			$hakemuksen_tilaDTO = new Hakemuksen_tilaDTO();

			$hakemuksen_tilaDTO->ID = $result[$i]["ID"];
			$hakemuksen_tilaDTO->Hakemuksen_tilan_koodi = $result[$i]["Hakemuksen_tilan_koodi"];
			$hakemuksen_tilaDTO->Nyt = $result[$i]["Nyt"];
			$hakemuksen_tilaDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakemuksen_tilaDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakemuksen_tilaDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$hakemuksen_tilaDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];

			$hakemuksen_tilaDTO->HakemusDTO = new HakemusDTO();
			$hakemuksen_tilaDTO->HakemusDTO->ID = $result[$i]["FK_Hakemus"];

			$hakemuksen_tilatDTO[$i] = $hakemuksen_tilaDTO;

		}

		return $hakemuksen_tilatDTO;	
	
	}
	
	function hae_uudet_avatut_hakemuksen_tilat(){

		$query = "SELECT * FROM Hakemuksen_tila WHERE Hakemuksen_tilan_koodi <> :hak_lah AND Hakemuksen_tilan_koodi <> 'hak_korvattu' AND Hakemuksen_tilan_koodi <> 'hak_peruttu' AND Nyt=1 ORDER BY Lisayspvm DESC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':hak_lah' => "hak_lah"));
		$result = $sth->fetchAll();
		$hakemuksen_tilatDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemuksen_tilaDTO = new Hakemuksen_tilaDTO();

			$hakemuksen_tilaDTO->ID = $result[$i]["ID"];
			$hakemuksen_tilaDTO->Hakemuksen_tilan_koodi = $result[$i]["Hakemuksen_tilan_koodi"];
			$hakemuksen_tilaDTO->Nyt = $result[$i]["Nyt"];
			$hakemuksen_tilaDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakemuksen_tilaDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakemuksen_tilaDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$hakemuksen_tilaDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];

			$hakemuksen_tilaDTO->HakemusDTO = new HakemusDTO();
			$hakemuksen_tilaDTO->HakemusDTO->ID = $result[$i]["FK_Hakemus"];

			$hakemuksen_tilatDTO[$i] = $hakemuksen_tilaDTO;

		}

		return $hakemuksen_tilatDTO;

	}

	function hae_lahetetyt_ja_uudet_hakemuksen_tilat(){

		$query = "SELECT * FROM Hakemuksen_tila WHERE Hakemuksen_tilan_koodi=:hak_lah AND Nyt=1 ORDER BY Lisayspvm DESC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':hak_lah' => "hak_lah"));
		$result = $sth->fetchAll();
		$hakemuksen_tilatDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemuksen_tilaDTO = new Hakemuksen_tilaDTO();

			$hakemuksen_tilaDTO->ID = $result[$i]["ID"];
			$hakemuksen_tilaDTO->Hakemuksen_tilan_koodi = $result[$i]["Hakemuksen_tilan_koodi"];
			$hakemuksen_tilaDTO->Nyt = $result[$i]["Nyt"];
			$hakemuksen_tilaDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakemuksen_tilaDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakemuksen_tilaDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$hakemuksen_tilaDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];

			$hakemuksen_tilaDTO->HakemusDTO = new HakemusDTO();
			$hakemuksen_tilaDTO->HakemusDTO->ID = $result[$i]["FK_Hakemus"];

			$hakemuksen_tilatDTO[$i] = $hakemuksen_tilaDTO;

		}

		return $hakemuksen_tilatDTO;

	}

	function hae_hakemuksen_uusimman_tilan_tiedot($fk_hakemus){

		$query = "SELECT * FROM Hakemuksen_tila WHERE FK_Hakemus=:fk_hakemus AND Nyt=:nyt ORDER BY Lisayspvm DESC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemus' => $fk_hakemus, ':nyt' => 1));
		$result = $sth->fetch();

		$hakemuksen_tilaDTO = new Hakemuksen_tilaDTO();

		$hakemuksen_tilaDTO->ID = $result["ID"];
		$hakemuksen_tilaDTO->Hakemuksen_tilan_koodi = $result["Hakemuksen_tilan_koodi"];
		$hakemuksen_tilaDTO->Nyt = $result["Nyt"];
		$hakemuksen_tilaDTO->Lisaaja = $result["Lisaaja"];
		$hakemuksen_tilaDTO->Lisayspvm = $result["Lisayspvm"];
		$hakemuksen_tilaDTO->Muokkaaja = $result["Muokkaaja"];
		$hakemuksen_tilaDTO->Muokkauspvm = $result["Muokkauspvm"];

		$hakemuksen_tilaDTO->HakemusDTO = new HakemusDTO();
		$hakemuksen_tilaDTO->HakemusDTO->ID = $result["FK_Hakemus"];

		return $hakemuksen_tilaDTO;

	}

	function hae_hakemuksen_hakemuksen_tilat($fk_hakemus){

		$query = "SELECT * FROM Hakemuksen_tila WHERE FK_Hakemus=:fk_hakemus ORDER BY Lisayspvm ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemus' => $fk_hakemus));
		$result = $sth->fetchAll();

		$hakemuksen_tilatDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemuksen_tilaDTO = new Hakemuksen_tilaDTO();

			$hakemuksen_tilaDTO->ID = $result[$i]["ID"];
			$hakemuksen_tilaDTO->Hakemuksen_tilan_koodi = $result[$i]["Hakemuksen_tilan_koodi"];
			$hakemuksen_tilaDTO->Nyt = $result[$i]["Nyt"];
			$hakemuksen_tilaDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakemuksen_tilaDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakemuksen_tilaDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$hakemuksen_tilaDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];

			$hakemuksen_tilaDTO->HakemusDTO = new HakemusDTO();
			$hakemuksen_tilaDTO->HakemusDTO->ID = $result[$i]["FK_Hakemus"];

			$hakemuksen_tilatDTO[$i] = $hakemuksen_tilaDTO;

		}

		return $hakemuksen_tilatDTO;

	}

	function hae_hakemuksen_tila_koodin_perusteella($fk_hakemus, $hak_tila){

		$query = "SELECT * FROM Hakemuksen_tila WHERE FK_Hakemus=:fk_hakemus AND Hakemuksen_tilan_koodi=:hak_tila ORDER BY Lisayspvm DESC LIMIT 1";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemus' => $fk_hakemus, ':hak_tila' => $hak_tila));
		$result = $sth->fetch();

		$hakemuksen_tilaDTO = new Hakemuksen_tilaDTO();

		$hakemuksen_tilaDTO->ID = $result["ID"];
		$hakemuksen_tilaDTO->Hakemuksen_tilan_koodi = $result["Hakemuksen_tilan_koodi"];
		$hakemuksen_tilaDTO->Nyt = $result["Nyt"];
		$hakemuksen_tilaDTO->Lisaaja = $result["Lisaaja"];
		$hakemuksen_tilaDTO->Lisayspvm = $result["Lisayspvm"];
		$hakemuksen_tilaDTO->Muokkaaja = $result["Muokkaaja"];
		$hakemuksen_tilaDTO->Muokkauspvm = $result["Muokkauspvm"];

		$hakemuksen_tilaDTO->HakemusDTO = new HakemusDTO();
		$hakemuksen_tilaDTO->HakemusDTO->ID = $result["FK_Hakemus"];

		return $hakemuksen_tilaDTO;

	}

	function poista_hakemuksen_hakemuksen_tila($fk_hakemus){

		$query = "DELETE FROM Hakemuksen_tila WHERE FK_Hakemus=:fk_hakemus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':fk_hakemus' => $fk_hakemus));

	}

}