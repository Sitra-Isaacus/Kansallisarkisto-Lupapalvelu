<?php
/*
 * FMAS Käyttölupapalvelu
 * Hakemus Data access object
 *
 * Created: 21.9.2016
 */

class HakemusDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function luo_hakemus($fk_hakemusversio, $vir_koodi, $Hakemuksen_tunnus, $lisaaja){

		$hakemusDTO = new HakemusDTO();
				
		$query = "INSERT INTO Hakemus (FK_Hakemusversio, Viranomaisen_koodi, Hakemuksen_tunnus, Lisaaja) VALUES (:fk_hakemusversio, :vir_koodi, :Hakemuksen_tunnus, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':vir_koodi' => $vir_koodi, ':Hakemuksen_tunnus' => $Hakemuksen_tunnus, ':lisaaja' => $lisaaja));

		$hakemusDTO->ID = $this->db->lastInsertId();
		$hakemusDTO->Viranomaisen_koodi = $vir_koodi;
		$hakemusDTO->Hakemuksen_tunnus = $Hakemuksen_tunnus;
		$hakemusDTO->Lisaaja = $lisaaja;
		$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
		$hakemusDTO->HakemusversioDTO->ID = $fk_hakemusversio;

		return $hakemusDTO;

	}

	function paivita_hakemuksen_tieto($id, $kentan_nimi, $kentan_arvo){

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Hakemus SET $kentan_nimi=$kentan_arvo WHERE ID=$id";
		} else {
			$q = "UPDATE Hakemus SET $kentan_nimi='$kentan_arvo' WHERE ID=$id";
		}

		return $this->db->query($q);
		
	}

	function hae_hakemuksen_tiedot($id){

		$query = "SELECT * FROM Hakemus WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$hakemusDTO = new HakemusDTO();
		$hakemusDTO->ID = $result["ID"];
		$hakemusDTO->Viranomaisen_koodi = $result["Viranomaisen_koodi"];
		$hakemusDTO->Hakemuksen_tunnus = $result["Hakemuksen_tunnus"];
		$hakemusDTO->Asiakirjatyyppi = $result["Asiakirjatyyppi"];
		$hakemusDTO->Julkisuusluokka = $result["Julkisuusluokka"];
		$hakemusDTO->Salassapitoaika = $result["Salassapitoaika"];
		$hakemusDTO->Salassapitoperuste = $result["Salassapitoperuste"];
		$hakemusDTO->Suojaustaso = $result["Suojaustaso"];
		$hakemusDTO->Henkilotietoja = $result["Henkilotietoja"];
		$hakemusDTO->Sailytysajan_pituus = $result["Sailytysajan_pituus"];
		$hakemusDTO->Sailytysajan_peruste = $result["Sailytysajan_peruste"];
		$hakemusDTO->Lisaaja = $result["Lisaaja"];
		$hakemusDTO->Lisayspvm = $result["Lisayspvm"];
		$hakemusDTO->Muokkaaja = $result["Muokkaaja"];
		$hakemusDTO->Muokkauspvm = $result["Muokkauspvm"];

		$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
		$hakemusDTO->HakemusversioDTO->ID = $result["FK_Hakemusversio"];
		$hakemusDTO->AsiaDTO = new AsiaDTO();
		$hakemusDTO->AsiaDTO->ID = $result["FK_Asia"];
		
		return $hakemusDTO;

	}
	
	function hae_hakemus_tunnuksella($hakemuksen_tunnus){

		$query = "SELECT ID, FK_Hakemusversio FROM Hakemus WHERE Hakemuksen_tunnus=:hakemuksen_tunnus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':hakemuksen_tunnus' => $hakemuksen_tunnus));
		$result = $sth->fetch();

		$hakemusDTO = new HakemusDTO();
		$hakemusDTO->ID = $result["ID"];
		$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
		$hakemusDTO->HakemusversioDTO->ID = $result["FK_Hakemusversio"];

		
		return $hakemusDTO;

	}	

	function hae_hakemusversion_hakemukset($fk_hakemusversio){

		$query = "SELECT * FROM Hakemus WHERE FK_Hakemusversio=:fk_hakemusversio ORDER BY Lisayspvm DESC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));
		$result = $sth->fetchAll();

		$hakemuksetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemusDTO = new HakemusDTO();
			$hakemusDTO->ID = $result[$i]["ID"];
			$hakemusDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$hakemusDTO->Hakemuksen_tunnus = $result[$i]["Hakemuksen_tunnus"];
			$hakemusDTO->Diaarinumero = $result[$i]["Diaarinumero"];
			$hakemusDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakemusDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakemusDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$hakemusDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];

			$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakemusDTO->HakemusversioDTO->ID = $fk_hakemusversio;
			$hakemusDTO->AsiaDTO = new AsiaDTO();
			$hakemusDTO->AsiaDTO->ID = $result[$i]["FK_Asia"];				

			$hakemuksetDTO[$i] = $hakemusDTO;

		}

		return $hakemuksetDTO;

	}
	
	function hae_kaikki_hakemukset(){

		$query = "SELECT * FROM Hakemus ORDER BY Lisayspvm DESC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$result = $sth->fetchAll();

		$hakemuksetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemusDTO = new HakemusDTO();
			$hakemusDTO->ID = $result[$i]["ID"];
			$hakemusDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$hakemusDTO->Hakemuksen_tunnus = $result[$i]["Hakemuksen_tunnus"];
			$hakemusDTO->Diaarinumero = $result[$i]["Diaarinumero"];
			$hakemusDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakemusDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakemusDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$hakemusDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];

			$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakemusDTO->HakemusversioDTO->ID = $result[$i]["FK_Hakemusversio"];
			$hakemusDTO->AsiaDTO = new AsiaDTO();
			$hakemusDTO->AsiaDTO->ID = $result[$i]["FK_Asia"];				

			$hakemuksetDTO[$i] = $hakemusDTO;

		}

		return $hakemuksetDTO;

	}	

	function hae_hakemusversion_uusin_hakemus_viranomaiselle($fk_hakemusversio, $viranomaisen_koodi){

		$query = "SELECT * FROM Hakemus WHERE FK_Hakemusversio=:fk_hakemusversio AND Viranomaisen_koodi=:viranomaisen_koodi ORDER BY Lisayspvm DESC LIMIT 1";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':viranomaisen_koodi' => $viranomaisen_koodi));
		$result = $sth->fetch();

		$hakemusDTO = new HakemusDTO();
		$hakemusDTO->ID = $result["ID"];
		$hakemusDTO->Viranomaisen_koodi = $result["Viranomaisen_koodi"];
		$hakemusDTO->Hakemuksen_tunnus = $result["Hakemuksen_tunnus"];
		$hakemusDTO->Diaarinumero = $result["Diaarinumero"];
		$hakemusDTO->Lisaaja = $result["Lisaaja"];
		$hakemusDTO->Lisayspvm = $result["Lisayspvm"];
		$hakemusDTO->Muokkaaja = $result["Muokkaaja"];
		$hakemusDTO->Muokkauspvm = $result["Muokkauspvm"];

		$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
		$hakemusDTO->HakemusversioDTO->ID = $result["FK_Hakemusversio"];

		return $hakemusDTO;

	}

	function hae_hakemusversion_hakemukset_viranomaiselle($fk_hakemusversio, $vir_koodi){

		$query = "SELECT * FROM Hakemus WHERE FK_Hakemusversio=:fk_hakemusversio AND Viranomaisen_koodi=:vir_koodi";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':vir_koodi' => $vir_koodi));
		$result = $sth->fetchAll();

		$hakemuksetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemusDTO = new HakemusDTO();
			$hakemusDTO->ID = $result[$i]["ID"];
			$hakemusDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$hakemusDTO->Hakemuksen_tunnus = $result[$i]["Hakemuksen_tunnus"];
			$hakemusDTO->Diaarinumero = $result[$i]["Diaarinumero"];
			$hakemusDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakemusDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakemusDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$hakemusDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];

			$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakemusDTO->HakemusversioDTO->ID = $fk_hakemusversio;
			$hakemusDTO->AsiaDTO = new AsiaDTO();
			$hakemusDTO->AsiaDTO->ID = $result[$i]["FK_Asia"];			

			$hakemuksetDTO[$i] = $hakemusDTO;

		}

		return $hakemuksetDTO;


	}

	function hae_hakemuksen_hakemusversiot_hakutermeilla($vir_koodi="", $tutk_nro="", $hak_tila="", $tutk_nimi="", $vuosi_alku="", $vuosi_loppu=""){

		$query = "SELECT Hakemus.ID, Hakemus.FK_Hakemusversio FROM Hakemus JOIN Hakemusversio ON Hakemus.FK_Hakemusversio=Hakemusversio.ID JOIN Hakemuksen_tila ON Hakemuksen_tila.FK_Hakemus=Hakemus.ID WHERE Hakemuksen_tila.Nyt=1 AND Hakemus.Viranomaisen_koodi='$vir_koodi' AND (Hakemus.Hakemuksen_tunnus='$tutk_nro' OR Hakemuksen_tila.Hakemuksen_tilan_koodi='$hak_tila' OR MATCH (Hakemusversio.Tutkimuksen_nimi) AGAINST ('$tutk_nimi') OR YEAR(Hakemuksen_tila.Lisayspvm) BETWEEN '$vuosi_alku' AND '$vuosi_loppu')";
		$result = $this->db->query($query)->fetchAll();
		$hakemuksetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemusDTO = new HakemusDTO();
			$hakemusDTO->ID = $result[$i]["ID"];
			$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakemusDTO->HakemusversioDTO->ID = $result[$i]["FK_Hakemusversio"];

			$hakemuksetDTO[$i] = $hakemusDTO;

		}

		return $hakemuksetDTO;

	}

	function hae_hakemuksen_hakemusversiot_viranomaiskohtaisesti($vir_koodi){

		$query = "SELECT Hakemus.ID, Hakemus.FK_Hakemusversio FROM Hakemus JOIN Hakemusversio ON Hakemus.FK_Hakemusversio=Hakemusversio.ID JOIN Hakemuksen_tila ON Hakemuksen_tila.FK_Hakemus=Hakemus.ID WHERE Hakemuksen_tila.Nyt=1 AND Hakemus.Viranomaisen_koodi='$vir_koodi'";
		$result = $this->db->query($query)->fetchAll();
		$hakemuksetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemusDTO = new HakemusDTO();
			$hakemusDTO->ID = $result[$i]["ID"];
			$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakemusDTO->HakemusversioDTO->ID = $result[$i]["FK_Hakemusversio"];

			$hakemuksetDTO[$i] = $hakemusDTO;

		}

		return $hakemuksetDTO;

	}

	function hae_hakemusversion_hakemukset_ja_hakemuksen_tilat($fk_hakemusversio){

		$query = "SELECT Hakemusversio.Hakemuksen_tyyppi, Hakemusversio.FK_Tutkimus, Hakemus.ID, Hakemus.Hakemuksen_tunnus, Hakemusversio.Versio, Hakemusversio.Tutkimuksen_nimi, Hakemuksen_tila.Hakemuksen_tilan_koodi, Hakemuksen_tila.Lisayspvm FROM Hakemus JOIN Hakemusversio ON Hakemus.FK_Hakemusversio=Hakemusversio.ID JOIN Hakemuksen_tila ON Hakemuksen_tila.FK_Hakemus=Hakemus.ID WHERE Hakemuksen_tila.Nyt=1 AND Hakemus.FK_Hakemusversio=$fk_hakemusversio";
		$result = $this->db->query($query)->fetchAll();
		$hakemuksetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemusDTO = new HakemusDTO();
			$hakemusDTO->ID = $result[$i]["ID"];
			$hakemusDTO->Hakemuksen_tunnus = $result[$i]["Hakemuksen_tunnus"];
			$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakemusDTO->HakemusversioDTO->ID = $fk_hakemusversio;
			$hakemusDTO->HakemusversioDTO->Hakemuksen_tyyppi = $result[$i]["Hakemuksen_tyyppi"];
			$hakemusDTO->HakemusversioDTO->Versio = $result[$i]["Versio"];
			$hakemusDTO->HakemusversioDTO->Tutkimuksen_nimi = $result[$i]["Tutkimuksen_nimi"];
			$hakemusDTO->HakemusversioDTO->TutkimusDTO = new TutkimusDTO();
			$hakemusDTO->HakemusversioDTO->TutkimusDTO->ID = $result[$i]["FK_Tutkimus"];
			$hakemusDTO->Hakemuksen_tilaDTO = new Hakemuksen_tilaDTO();
			$hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi = $result[$i]["Hakemuksen_tilan_koodi"];
			$hakemusDTO->Hakemuksen_tilaDTO->Lisayspvm = $result[$i]["Lisayspvm"];

			$hakemuksetDTO[$i] = $hakemusDTO;

		}

		return $hakemuksetDTO;

	}

	function hae_viranomaisorganisaation_hakemukset($vir_koodi){

		$query = "SELECT * FROM Hakemus WHERE Viranomaisen_koodi='$vir_koodi'";
		$result = $this->db->query($query)->fetchAll();

		$hakemuksetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemusDTO = new HakemusDTO();
			$hakemusDTO->ID = $result[$i]["ID"];
			$hakemusDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$hakemusDTO->Hakemuksen_tunnus = $result[$i]["Hakemuksen_tunnus"];
			$hakemusDTO->AsiaDTO = new AsiaDTO();
			$hakemusDTO->AsiaDTO->ID = $result[$i]["FK_Asia"];
			$hakemusDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakemusDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakemusDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$hakemusDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];

			$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakemusDTO->HakemusversioDTO->ID = $result[$i]["FK_Hakemusversio"];

			$hakemuksetDTO[$i] = $hakemusDTO;

		}

		return $hakemuksetDTO;

	}

	function hae_muiden_organisaatioiden_hakemusversion_hakemukset($fk_hakemusversio, $vir_koodi){

		$query = "SELECT * FROM Hakemus WHERE FK_Hakemusversio=:fk_hakemusversio AND Viranomaisen_koodi<>:vir_koodi";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':vir_koodi' => $vir_koodi));
		$result = $sth->fetchAll();

		$hakemuksetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemusDTO = new HakemusDTO();
			$hakemusDTO->ID = $result[$i]["ID"];
			$hakemusDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$hakemusDTO->Hakemuksen_tunnus = $result[$i]["Hakemuksen_tunnus"];
			$hakemusDTO->Diaarinumero = $result[$i]["Diaarinumero"];
			$hakemusDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakemusDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakemusDTO->Muokkaaja = $result[$i]["Muokkaaja"];
			$hakemusDTO->Muokkauspvm = $result[$i]["Muokkauspvm"];

			$hakemusDTO->HakemusversioDTO = new HakemusversioDTO();
			$hakemusDTO->HakemusversioDTO->ID = $fk_hakemusversio;

			$hakemuksetDTO[$i] = $hakemusDTO;

		}

		return $hakemuksetDTO;

	}

	function poista_hakemusversion_hakemus($fk_hakemusversio){

		$query = "DELETE FROM Hakemus WHERE FK_Hakemusversio=:fk_hakemusversio";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));

	}

}