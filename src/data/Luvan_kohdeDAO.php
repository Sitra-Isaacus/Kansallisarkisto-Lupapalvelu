<?php
/*
 * FMAS Käyttölupapalvelu
 * Luvan_kohde Data access object
 *
 * Created: 13.3.2017
 */

class Luvan_kohdeDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_luvan_kohde($luvan_kohteen_nimi, $luv_kohde_tyyppi, $vir_koodi, $Lupaviranomaisen_toimivalta_ryhma, $linkki, $selite, $identifier, $viiteajankohta_alku, $viiteajankohta_loppu){
		$query = "INSERT INTO Luvan_kohde (Luvan_kohteen_nimi, Luvan_kohteen_tyyppi, Viranomaisen_koodi, Lupaviranomaisen_toimivalta_ryhma, Hyperlinkki, Selite, Identifier, Viiteajankohta_alku, Viiteajankohta_loppu) VALUES (:luvan_kohteen_nimi, :luv_kohde_tyyppi, :vir_koodi, :Lupaviranomaisen_toimivalta_ryhma, :linkki, :selite, :identifier, :viiteajankohta_alku, :viiteajankohta_loppu)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':luvan_kohteen_nimi' => $luvan_kohteen_nimi, ':luv_kohde_tyyppi' => $luv_kohde_tyyppi, ':vir_koodi' => $vir_koodi, ':Lupaviranomaisen_toimivalta_ryhma' => $Lupaviranomaisen_toimivalta_ryhma, ':linkki' => $linkki, ':selite' => $selite, ':identifier' => $identifier, ':viiteajankohta_alku' => $viiteajankohta_alku, ':viiteajankohta_loppu' => $viiteajankohta_loppu));
	}

	function paivita_luvan_kohde($id, $Luvan_kohteen_nimi, $Selite, $Viiteajankohta_alku, $Viiteajankohta_loppu){

		$query = "UPDATE Luvan_kohde SET Luvan_kohteen_nimi=:Luvan_kohteen_nimi, Selite=:Selite, Viiteajankohta_alku=:Viiteajankohta_alku, Viiteajankohta_loppu=:Viiteajankohta_loppu WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':Luvan_kohteen_nimi' => $Luvan_kohteen_nimi, ':Selite' => $Selite, ':Viiteajankohta_alku' => $Viiteajankohta_alku, ':Viiteajankohta_loppu' => $Viiteajankohta_loppu, ':id' => $id));

	}	
	
	function hae_luvan_kohde($id){

		$query = "SELECT * FROM Luvan_kohde WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$luvan_kohdeDTO = new Luvan_kohdeDTO();
		$luvan_kohdeDTO->ID = $result["ID"];
		$luvan_kohdeDTO->Luvan_kohteen_nimi = $result["Luvan_kohteen_nimi"];
		$luvan_kohdeDTO->Luvan_kohteen_tyyppi = $result["Luvan_kohteen_tyyppi"];
		$luvan_kohdeDTO->Viranomaisen_koodi = $result["Viranomaisen_koodi"];
		$luvan_kohdeDTO->Hyperlinkki = $result["Hyperlinkki"];
		$luvan_kohdeDTO->Identifier = $result["Identifier"];
		$luvan_kohdeDTO->Lupaviranomaisen_toimivalta_ryhma = $result["Lupaviranomaisen_toimivalta_ryhma"];
		
		return $luvan_kohdeDTO;

	}
	
	function hae_luvan_kohde_identifierilla($identifier){

		$query = "SELECT * FROM Luvan_kohde WHERE Identifier=:identifier ORDER BY Luvan_kohteen_nimi ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':identifier' => $identifier));
		$result = $sth->fetch();

		$luvan_kohdeDTO = new Luvan_kohdeDTO();
		$luvan_kohdeDTO->ID = $result["ID"];
		$luvan_kohdeDTO->Luvan_kohteen_nimi = $result["Luvan_kohteen_nimi"];
		$luvan_kohdeDTO->Luvan_kohteen_tyyppi = $result["Luvan_kohteen_tyyppi"];
		//$luvan_kohdeDTO->Selite = $result["Selite"];
		$luvan_kohdeDTO->Viranomaisen_koodi = $result["Viranomaisen_koodi"];
		$luvan_kohdeDTO->Hyperlinkki = $result["Hyperlinkki"];
		$luvan_kohdeDTO->Identifier = $result["Identifier"];

		return $luvan_kohdeDTO;

	}	

	function hae_kaikki_luvan_kohteet(){

		$query = "SELECT * FROM Luvan_kohde ORDER BY Luvan_kohteen_nimi ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$result = $sth->fetchAll();
		$luvan_kohteetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$luvan_kohdeDTO = new Luvan_kohdeDTO();
			$luvan_kohdeDTO->ID = $result[$i]["ID"];
			$luvan_kohdeDTO->Luvan_kohteen_nimi = $result[$i]["Luvan_kohteen_nimi"];
			$luvan_kohdeDTO->Luvan_kohteen_tyyppi = $result[$i]["Luvan_kohteen_tyyppi"];
			$luvan_kohdeDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$luvan_kohdeDTO->Hyperlinkki = $result[$i]["Hyperlinkki"];
			$luvan_kohdeDTO->Identifier = $result[$i]["Identifier"];
			//$luvan_kohdeDTO->Selite = $result[$i]["Selite"];
			
			$luvan_kohteetDTO[$i] = $luvan_kohdeDTO;

		}

		return $luvan_kohteetDTO;

	}
	
	function hae_tyypin_luvan_kohteet($tyyppi){

		$query = "SELECT * FROM Luvan_kohde WHERE Luvan_kohteen_tyyppi=:tyyppi ORDER BY Luvan_kohteen_nimi ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':tyyppi' => $tyyppi));
		$result = $sth->fetchAll();
		$luvan_kohteetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$luvan_kohdeDTO = new Luvan_kohdeDTO();
			$luvan_kohdeDTO->ID = $result[$i]["ID"];
			$luvan_kohdeDTO->Luvan_kohteen_nimi = $result[$i]["Luvan_kohteen_nimi"];
			$luvan_kohdeDTO->Luvan_kohteen_tyyppi = $result[$i]["Luvan_kohteen_tyyppi"];
			$luvan_kohdeDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$luvan_kohdeDTO->Hyperlinkki = $result[$i]["Hyperlinkki"];
			$luvan_kohdeDTO->Identifier = $result[$i]["Identifier"];
			$luvan_kohdeDTO->Selite = $result[$i]["Selite"];
			$luvan_kohdeDTO->Viiteajankohta_alku = $result[$i]["Viiteajankohta_alku"];
			$luvan_kohdeDTO->Viiteajankohta_loppu = $result[$i]["Viiteajankohta_loppu"];
			
			$luvan_kohteetDTO[$luvan_kohdeDTO->Identifier] = $luvan_kohdeDTO; // Avaimena identifier

		}

		return $luvan_kohteetDTO;

	}	

	function hae_viranomaisen_luvan_kohteet($vir_koodi){

		$query = "SELECT * FROM Luvan_kohde WHERE Viranomaisen_koodi=:vir_koodi ORDER BY Luvan_kohteen_nimi ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':vir_koodi' => $vir_koodi));
		$result = $sth->fetchAll();
		$luvan_kohteetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$luvan_kohdeDTO = new Luvan_kohdeDTO();
			$luvan_kohdeDTO->ID = $result[$i]["ID"];
			$luvan_kohdeDTO->Luvan_kohteen_nimi = $result[$i]["Luvan_kohteen_nimi"];
			$luvan_kohdeDTO->Luvan_kohteen_tyyppi = $result[$i]["Luvan_kohteen_tyyppi"];
			$luvan_kohdeDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
			$luvan_kohdeDTO->Hyperlinkki = $result[$i]["Hyperlinkki"];
			$luvan_kohdeDTO->Identifier = $result[$i]["Identifier"];
			//$luvan_kohdeDTO->Selite = $result[$i]["Selite"];
			
			$luvan_kohteetDTO[$result[$i]["ID"]] = $luvan_kohdeDTO;

		}

		return $luvan_kohteetDTO;

	}

	function hae_viranomaisten_luvan_kohteet(){

		$query = "SELECT DISTINCT Viranomaisen_koodi FROM Luvan_kohde";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$viranomaiset = $sth->fetchAll();
		$luvan_kohteetDTO = array();

		for($j=0; $j < sizeof($viranomaiset); $j++){

			$vir_koodi = $viranomaiset[$j]["Viranomaisen_koodi"];

			$query = "SELECT * FROM Luvan_kohde WHERE Viranomaisen_koodi=:vir_koodi ORDER BY Luvan_kohteen_nimi ASC";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':vir_koodi' => $vir_koodi));
			$result = $sth->fetchAll();

			for($i=0; $i < sizeof($result); $i++){

				$luvan_kohdeDTO = new Luvan_kohdeDTO();
				$luvan_kohdeDTO->ID = $result[$i]["ID"];
				$luvan_kohdeDTO->Luvan_kohteen_nimi = $result[$i]["Luvan_kohteen_nimi"];
				$luvan_kohdeDTO->Luvan_kohteen_tyyppi = $result[$i]["Luvan_kohteen_tyyppi"];
				$luvan_kohdeDTO->Viranomaisen_koodi = $result[$i]["Viranomaisen_koodi"];
				$luvan_kohdeDTO->Hyperlinkki = $result[$i]["Hyperlinkki"];
				$luvan_kohdeDTO->Identifier = $result[$i]["Identifier"];
				//$luvan_kohdeDTO->Selite = $result[$i]["Selite"];
				
				$luvan_kohteetDTO[$vir_koodi][$i] = $luvan_kohdeDTO;

			}

		}

		return $luvan_kohteetDTO;

	}
  
}

?>