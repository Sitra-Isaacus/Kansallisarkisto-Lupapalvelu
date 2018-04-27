<?php
/*
 * FMAS Käyttölupapalvelu
 * Muuttuja Data access object
 *
 * Created: 13.9.2017
 */

class MuuttujaDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_muuttuja($Tunnus, $Luvan_kohde_identifier, $Nimi, $Kuvaus, $Mittayksikko, $Luokitus){

		$query = "INSERT INTO Muuttuja (Tunnus, Luvan_kohde_identifier, Nimi, Kuvaus, Mittayksikko, Luokitus) VALUES (:Tunnus, :Luvan_kohde_identifier, :Nimi, :Kuvaus, :Mittayksikko, :Luokitus)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':Tunnus' => $Tunnus, ':Luvan_kohde_identifier' => $Luvan_kohde_identifier, ':Nimi' => $Nimi, ':Kuvaus' => $Kuvaus, ':Mittayksikko' => $Mittayksikko, ':Luokitus' => $Luokitus));

	}
	
	function paivita_muuttuja($id, $Nimi, $Kuvaus, $Mittayksikko, $Luokitus){

		$query = "UPDATE Muuttuja SET Nimi=:Nimi, Kuvaus=:Kuvaus, Mittayksikko=:Mittayksikko, Luokitus=:Luokitus WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':Nimi' => $Nimi, ':Kuvaus' => $Kuvaus, ':Mittayksikko' => $Mittayksikko, ':Luokitus' => $Luokitus, ':id' => $id));

	}	
	
	function hae_muuttuja_tunnisteilla($luvan_kohde_identifier, $tunnus){

		$query = "SELECT * FROM Muuttuja WHERE Luvan_kohde_identifier=:luvan_kohde_identifier AND Tunnus=:tunnus";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':luvan_kohde_identifier' => $luvan_kohde_identifier, ':tunnus' => $tunnus));
		$result = $sth->fetch();

		$muuttujaDTO = new MuuttujaDTO();
		$muuttujaDTO->ID = $result["ID"];
		$muuttujaDTO->Tunnus = $result["Tunnus"];
		$muuttujaDTO->Luvan_kohde_identifier = $result["Luvan_kohde_identifier"];
		$muuttujaDTO->Nimi = $result["Nimi"];
		$muuttujaDTO->Kuvaus = $result["Kuvaus"];
		$muuttujaDTO->Mittayksikko = $result["Mittayksikko"];
		$muuttujaDTO->Luokitus = $result["Luokitus"];
		
		return $muuttujaDTO;

	}

	function hae_luvan_kohteen_muuttujat($luvan_kohde_identifier){
		
		$query = "SELECT * FROM Muuttuja WHERE Luvan_kohde_identifier=:luvan_kohde_identifier";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':luvan_kohde_identifier' => $luvan_kohde_identifier));
		$result = $sth->fetchAll();	
		$muuttujatDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$muuttujaDTO = new MuuttujaDTO();
			$muuttujaDTO->ID = $result[$i]["ID"];
			$muuttujaDTO->Tunnus = $result[$i]["Tunnus"];
			$muuttujaDTO->Luvan_kohde_identifier = $result[$i]["Luvan_kohde_identifier"];
			$muuttujaDTO->Nimi = $result[$i]["Nimi"];
			$muuttujaDTO->Kuvaus = $result[$i]["Kuvaus"];
			$muuttujaDTO->Mittayksikko = $result[$i]["Mittayksikko"];
			$muuttujaDTO->Luokitus = $result[$i]["Luokitus"];
			
			$muuttujatDTO[$i] = $muuttujaDTO;

		}
		
		return $muuttujatDTO;
		
	}


}