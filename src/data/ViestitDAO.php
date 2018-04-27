<?php
/*
 * FMAS Käyttölupapalvelu
 * Viestit data access object
 *
 * Created: 21.9.2016
 */

class ViestitDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function laheta_viesti($fk_hakemus, $lahettaja, $vastaanottaja, $viesti, $taydennettavaa_hakemukseen){

		$query = "INSERT INTO Viestit (FK_Hakemus, FK_Kayttaja_Lahettaja, FK_Kayttaja_Vastaanottaja, Viesti, Taydennettavaa_hakemukseen) VALUES (:fk_hakemus, :lahettaja, :vastaanottaja, :viesti, :taydennettavaa_hakemukseen)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemus' => $fk_hakemus, ':lahettaja' => $lahettaja, ':vastaanottaja' => $vastaanottaja, ':viesti' => $viesti, ':taydennettavaa_hakemukseen' => $taydennettavaa_hakemukseen));
		
		return $this->db->lastInsertId();
		
	}

	function lisaa_viestiin_vastaus($fk_hakemus, $lahettaja, $vastaanottaja, $viesti, $parent_id){

		$query = "INSERT INTO Viestit (FK_Hakemus, FK_Kayttaja_Lahettaja, FK_Kayttaja_Vastaanottaja, Viesti, FK_Viestit_Parent) VALUES (:fk_hakemus, :lahettaja, :vastaanottaja, :viesti, :parent_id)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemus' => $fk_hakemus, ':lahettaja' => $lahettaja, ':vastaanottaja' => $vastaanottaja, ':viesti' => $viesti, ':parent_id' => $parent_id));
		$child_id = $this->db->lastInsertId();

		$query = "UPDATE Viestit SET FK_Viestit_Child=:child_id WHERE ID=:parent_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':child_id' => $child_id, ':parent_id' => $parent_id));

	}

	function merkitse_viesti_luetuksi($viesti_id){
		$query = "UPDATE Viestit SET Luettu=:luettu WHERE ID=:viesti_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':luettu' => 1,':viesti_id' => $viesti_id));
	}

	function hae_viesti($id){

		$query = "SELECT * FROM Viestit WHERE ID=$id";
		$viestit = $this->db->query($query)->fetch();
		$viestitDTO = new ViestitDTO();

		$viestitDTO->ID = $viestit["ID"];

		$viestitDTO->HakemusDTO = new HakemusDTO();
		$viestitDTO->HakemusDTO->ID = $viestit["FK_Hakemus"];

		$viestitDTO->KayttajaDTO_Lahettaja = new KayttajaDTO();
		$viestitDTO->KayttajaDTO_Lahettaja->ID = $viestit["FK_Kayttaja_Lahettaja"];

		$viestitDTO->KayttajaDTO_Vastaanottaja = new KayttajaDTO();
		$viestitDTO->KayttajaDTO_Vastaanottaja->ID = $viestit["FK_Kayttaja_Vastaanottaja"];

		$viestitDTO->Viesti = $viestit["Viesti"];
		$viestitDTO->Taydennettavaa_hakemukseen = $viestit["Taydennettavaa_hakemukseen"];
		$viestitDTO->Luettu = $viestit["Luettu"];
		$viestitDTO->Lisayspvm = $viestit["Lisayspvm"];

		$viestitDTO->ViestitDTO_Parent = new ViestitDTO();
		$viestitDTO->ViestitDTO_Parent->ID = $viestit["FK_Viestit_Parent"];

		$viestitDTO->ViestitDTO_Child = new ViestitDTO();
		$viestitDTO->ViestitDTO_Child->ID = $viestit["FK_Viestit_Child"];

		return $viestitDTO;

	}

	function hae_uusien_viestien_maara_vastaanottajalle($kayt_id){

		// Haetaan käyttäjän lukemattomien viestien lukumäärä
		$query = "SELECT COUNT(*) FROM Viestit WHERE FK_Kayttaja_Vastaanottaja=:kayt_id AND Luettu=:luettu";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':kayt_id' => $kayt_id, ':luettu' => 0));
		$result = $sth->fetch();

		return  $result["COUNT(*)"];

	}  

	function hae_lukemattomat_viestit_kayttajalle($kayt_id){

		$query = "SELECT * FROM Viestit WHERE FK_Kayttaja_Vastaanottaja=:kayt_id AND Luettu=0 ORDER BY Lisayspvm DESC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':kayt_id' => $kayt_id));
		$viestit = $sth->fetchAll();
		$viestitDTO = array();

		for($i=0; $i < sizeof($viestit); $i++){

			$viestiDTO = new ViestitDTO();
			$viestiDTO->ID = $viestit[$i]["ID"];

			$viestiDTO->HakemusDTO = new HakemusDTO();
			$viestiDTO->HakemusDTO->ID = $viestit[$i]["FK_Hakemus"];

			$viestiDTO->KayttajaDTO_Lahettaja = new KayttajaDTO();
			$viestiDTO->KayttajaDTO_Lahettaja->ID = $viestit[$i]["FK_Kayttaja_Lahettaja"];

			$viestiDTO->KayttajaDTO_Vastaanottaja = new KayttajaDTO();
			$viestiDTO->KayttajaDTO_Vastaanottaja->ID = $viestit[$i]["FK_Kayttaja_Vastaanottaja"];

			$viestiDTO->Viesti = $viestit[$i]["Viesti"];
			$viestiDTO->Taydennettavaa_hakemukseen = $viestit[$i]["Taydennettavaa_hakemukseen"];
			$viestiDTO->Luettu = $viestit[$i]["Luettu"];
			$viestiDTO->Lisayspvm = $viestit[$i]["Lisayspvm"];

			$viestiDTO->ViestitDTO_Parent = new ViestitDTO();
			$viestiDTO->ViestitDTO_Parent->ID = $viestit[$i]["FK_Viestit_Parent"];

			$viestiDTO->ViestitDTO_Child = new ViestitDTO();
			$viestiDTO->ViestitDTO_Child->ID = $viestit[$i]["FK_Viestit_Child"];

			$viestitDTO[$i] = $viestiDTO;

		}

		return $viestitDTO;

	}

	function hae_luetut_viestit_kayttajalle($kayt_id){

		$query = "SELECT * FROM Viestit WHERE FK_Kayttaja_Vastaanottaja=:kayt_id AND Luettu=1 ORDER BY Lisayspvm DESC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':kayt_id' => $kayt_id));
		$viestit = $sth->fetchAll();
		$viestitDTO = array();

		for($i=0; $i < sizeof($viestit); $i++){

			$viestiDTO = new ViestitDTO();
			$viestiDTO->ID = $viestit[$i]["ID"];

			$viestiDTO->HakemusDTO = new HakemusDTO();
			$viestiDTO->HakemusDTO->ID = $viestit[$i]["FK_Hakemus"];

			$viestiDTO->KayttajaDTO_Lahettaja = new KayttajaDTO();
			$viestiDTO->KayttajaDTO_Lahettaja->ID = $viestit[$i]["FK_Kayttaja_Lahettaja"];

			$viestiDTO->KayttajaDTO_Vastaanottaja = new KayttajaDTO();
			$viestiDTO->KayttajaDTO_Vastaanottaja->ID = $viestit[$i]["FK_Kayttaja_Vastaanottaja"];

			$viestiDTO->Viesti = $viestit[$i]["Viesti"];
			$viestiDTO->Taydennettavaa_hakemukseen = $viestit[$i]["Taydennettavaa_hakemukseen"];
			$viestiDTO->Luettu = $viestit[$i]["Luettu"];
			$viestiDTO->Lisayspvm = $viestit[$i]["Lisayspvm"];

			$viestiDTO->ViestitDTO_Parent = new ViestitDTO();
			$viestiDTO->ViestitDTO_Parent->ID = $viestit[$i]["FK_Viestit_Parent"];

			$viestiDTO->ViestitDTO_Child = new ViestitDTO();
			$viestiDTO->ViestitDTO_Child->ID = $viestit[$i]["FK_Viestit_Child"];

			$viestitDTO[$i] = $viestiDTO;

		}

		return $viestitDTO;

	}

	function hae_vastaanottajalle_hakemuksen_viestit_jotka_eivat_ole_vastauksia($vastaanottaja, $fk_hakemus){

		$query = "SELECT * FROM Viestit WHERE (FK_Kayttaja_Vastaanottaja=$vastaanottaja AND FK_Viestit_Parent IS NULL AND FK_Hakemus=$fk_hakemus) ORDER BY Lisayspvm DESC";
		$viestit = $this->db->query($query)->fetchAll();
		$viestitDTO = array();

		if(sizeof($viestit) > 0){

			for($i=0; $i < sizeof($viestit); $i++){

				$viestiDTO = new ViestitDTO();
				$viestiDTO->ID = $viestit[$i]["ID"];

				$viestiDTO->HakemusDTO = new HakemusDTO();
				$viestiDTO->HakemusDTO->ID = $viestit[$i]["FK_Hakemus"];

				$viestiDTO->KayttajaDTO_Lahettaja = new KayttajaDTO();
				$viestiDTO->KayttajaDTO_Lahettaja->ID = $viestit[$i]["FK_Kayttaja_Lahettaja"];

				$viestiDTO->KayttajaDTO_Vastaanottaja = new KayttajaDTO();
				$viestiDTO->KayttajaDTO_Vastaanottaja->ID = $viestit[$i]["FK_Kayttaja_Vastaanottaja"];

				$viestiDTO->Viesti = $viestit[$i]["Viesti"];
				$viestiDTO->Taydennettavaa_hakemukseen = $viestit[$i]["Taydennettavaa_hakemukseen"];
				$viestiDTO->Luettu = $viestit[$i]["Luettu"];
				$viestiDTO->Lisayspvm = $viestit[$i]["Lisayspvm"];

				$viestiDTO->ViestitDTO_Parent = new ViestitDTO();
				$viestiDTO->ViestitDTO_Parent->ID = $viestit[$i]["FK_Viestit_Parent"];

				$viestiDTO->ViestitDTO_Child = new ViestitDTO();
				$viestiDTO->ViestitDTO_Child->ID = $viestit[$i]["FK_Viestit_Child"];

				$viestitDTO[$i] = $viestiDTO;

			}

		}

		return $viestitDTO;

	}

	function hae_kayttajalle_hakemuksen_viestit_jotka_eivat_ole_vastauksia($vastaanottaja_tai_lahettaja, $fk_hakemus){

		$query = "SELECT * FROM Viestit WHERE (FK_Kayttaja_Vastaanottaja=$vastaanottaja_tai_lahettaja AND FK_Viestit_Parent IS NULL AND FK_Hakemus=$fk_hakemus) OR (FK_Kayttaja_Lahettaja=$vastaanottaja_tai_lahettaja AND FK_Viestit_Parent IS NULL AND FK_Hakemus=$fk_hakemus) ORDER BY Lisayspvm DESC";
		$viestit = $this->db->query($query)->fetchAll();
		$viestitDTO = array();

		if(sizeof($viestit) > 0){

			for($i=0; $i < sizeof($viestit); $i++){

				$viestiDTO = new ViestitDTO();
				$viestiDTO->ID = $viestit[$i]["ID"];

				$viestiDTO->HakemusDTO = new HakemusDTO();
				$viestiDTO->HakemusDTO->ID = $viestit[$i]["FK_Hakemus"];

				$viestiDTO->KayttajaDTO_Lahettaja = new KayttajaDTO();
				$viestiDTO->KayttajaDTO_Lahettaja->ID = $viestit[$i]["FK_Kayttaja_Lahettaja"];

				$viestiDTO->KayttajaDTO_Vastaanottaja = new KayttajaDTO();
				$viestiDTO->KayttajaDTO_Vastaanottaja->ID = $viestit[$i]["FK_Kayttaja_Vastaanottaja"];

				$viestiDTO->Viesti = $viestit[$i]["Viesti"];
				$viestiDTO->Taydennettavaa_hakemukseen = $viestit[$i]["Taydennettavaa_hakemukseen"];
				$viestiDTO->Luettu = $viestit[$i]["Luettu"];
				$viestiDTO->Lisayspvm = $viestit[$i]["Lisayspvm"];

				$viestiDTO->ViestitDTO_Parent = new ViestitDTO();
				$viestiDTO->ViestitDTO_Parent->ID = $viestit[$i]["FK_Viestit_Parent"];

				$viestiDTO->ViestitDTO_Child = new ViestitDTO();
				$viestiDTO->ViestitDTO_Child->ID = $viestit[$i]["FK_Viestit_Child"];

				$viestitDTO[$i] = $viestiDTO;

			}

		}

		return $viestitDTO;

	}

	function hae_hakemuksen_viestit($fk_hakemus){

		$query = "SELECT * FROM Viestit WHERE FK_Hakemus=:fk_hakemus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemus' => $fk_hakemus));
		$viestit = $sth->fetchAll();
		$viestitDTO = array();

		if(sizeof($viestit) > 0){

			for($i=0; $i < sizeof($viestit); $i++){

				$viestiDTO = new ViestitDTO();
				$viestiDTO->ID = $viestit[$i]["ID"];

				$viestiDTO->HakemusDTO = new HakemusDTO();
				$viestiDTO->HakemusDTO->ID = $viestit[$i]["FK_Hakemus"];

				$viestiDTO->KayttajaDTO_Lahettaja = new KayttajaDTO();
				$viestiDTO->KayttajaDTO_Lahettaja->ID = $viestit[$i]["FK_Kayttaja_Lahettaja"];

				$viestiDTO->KayttajaDTO_Vastaanottaja = new KayttajaDTO();
				$viestiDTO->KayttajaDTO_Vastaanottaja->ID = $viestit[$i]["FK_Kayttaja_Vastaanottaja"];

				$viestiDTO->Viesti = $viestit[$i]["Viesti"];
				$viestiDTO->Taydennettavaa_hakemukseen = $viestit[$i]["Taydennettavaa_hakemukseen"];
				$viestiDTO->Luettu = $viestit[$i]["Luettu"];
				$viestiDTO->Lisayspvm = $viestit[$i]["Lisayspvm"];

				$viestiDTO->ViestitDTO_Parent = new ViestitDTO();
				$viestiDTO->ViestitDTO_Parent->ID = $viestit[$i]["FK_Viestit_Parent"];

				$viestiDTO->ViestitDTO_Child = new ViestitDTO();
				$viestiDTO->ViestitDTO_Child->ID = $viestit[$i]["FK_Viestit_Child"];

				$viestitDTO[$i] = $viestiDTO;

			}

		}

		return $viestitDTO;

	}

}