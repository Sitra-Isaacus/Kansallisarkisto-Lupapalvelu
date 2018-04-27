<?php
/*
 * FMAS Käyttölupapalvelu
 * Aineistotilaus Data access object
 *
 * Created: 21.9.2016
 */

class AineistotilausDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_aineistotilaus($fk_paatos, $lisaaja){

		$query = "INSERT INTO Aineistotilaus (FK_Paatos, Lisaaja) VALUES (:fk_paatos, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos, ':lisaaja' => $lisaaja));

		$aineistotilausDTO = new AineistotilausDTO();
		$aineistotilausDTO->ID = $this->db->lastInsertId();
		$aineistotilausDTO->PaatosDTO = new PaatosDTO();
		$aineistotilausDTO->PaatosDTO->ID = $fk_paatos;
		$aineistotilausDTO->Lisaaja = $lisaaja;

		return $aineistotilausDTO;

	}

	function paivita_aineistotilauksen_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Aineistotilaus SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else if(is_null($kentan_arvo)){
			$q = "UPDATE Aineistotilaus SET $kentan_nimi=NULL, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Aineistotilaus SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}	
	
	function maarita_aineistotilaukselle_aineistonmuodostaja($id, $aineistonmuodostaja){
		$query = "UPDATE Aineistotilaus SET Aineistonmuodostaja=:aineistonmuodostaja WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':aineistonmuodostaja' => $aineistonmuodostaja, ':id' => $id));
	}

	function maarita_aineistotilauksen_lahetyspvm($id, $aineisto_lahetetty){
		$query = "UPDATE Aineistotilaus SET Aineisto_lahetetty=:aineisto_lahetetty WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':aineisto_lahetetty' => $aineisto_lahetetty, ':id' => $id));
	}

	function maarita_aineistotilaukselle_hinta($id, $hinta){
		$query = "UPDATE Aineistotilaus SET Aineistonmuodostuksen_hinta=:hinta WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':hinta' => $hinta, ':id' => $id));
	}

	function hae_aineistotilauksen_tiedot($id){

		$query = "SELECT * FROM Aineistotilaus WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$aineistotilausDTO = new AineistotilausDTO();
		$aineistotilausDTO->ID = $result["ID"];
		$aineistotilausDTO->PaatosDTO = new PaatosDTO();
		$aineistotilausDTO->PaatosDTO->ID = $result["FK_Paatos"];
		$aineistotilausDTO->Aineistonmuodostusprosessi_teksti = $result["Aineistonmuodostusprosessi_teksti"];
		$aineistotilausDTO->Aineistonmuodostuksen_hinta = $result["Aineistonmuodostuksen_hinta"];
		$aineistotilausDTO->Aineisto_lahetetty = $result["Aineisto_lahetetty"];
		$aineistotilausDTO->Aineistotilauksen_tyypin_koodi = $result["Aineistotilauksen_tyypin_koodi"];
		$aineistotilausDTO->Hyvaksyn_kayttoehdot = $result["Hyvaksyn_kayttoehdot"];
		$aineistotilausDTO->Aineistonmuodostaja = $result["Aineistonmuodostaja"];
		$aineistotilausDTO->Aineiston_tilaaja = $result["Aineiston_tilaaja"];
		$aineistotilausDTO->Lisaaja = $result["Lisaaja"];
		$aineistotilausDTO->Lisayspvm = $result["Lisayspvm"];

		return $aineistotilausDTO;

	}

	function hae_aineistotilaus_paatokselle($fk_paatos){

		$query = "SELECT * FROM Aineistotilaus WHERE FK_Paatos=:fk_paatos";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos));
		$result = $sth->fetch();

		$aineistotilausDTO = new AineistotilausDTO();
		$aineistotilausDTO->ID = $result["ID"];
		$aineistotilausDTO->PaatosDTO = new PaatosDTO();
		$aineistotilausDTO->PaatosDTO->ID = $fk_paatos;
		$aineistotilausDTO->Aineistonmuodostusprosessi_teksti = $result["Aineistonmuodostusprosessi_teksti"];
		$aineistotilausDTO->Aineistonmuodostuksen_hinta = $result["Aineistonmuodostuksen_hinta"];
		$aineistotilausDTO->Aineisto_lahetetty = $result["Aineisto_lahetetty"];
		$aineistotilausDTO->Aineistotilauksen_tyypin_koodi = $result["Aineistotilauksen_tyypin_koodi"];
		$aineistotilausDTO->Hyvaksyn_kayttoehdot = $result["Hyvaksyn_kayttoehdot"];
		$aineistotilausDTO->Aineistonmuodostaja = $result["Aineistonmuodostaja"];
		$aineistotilausDTO->Aineiston_tilaaja = $result["Aineiston_tilaaja"];
		$aineistotilausDTO->Lisaaja = $result["Lisaaja"];
		$aineistotilausDTO->Lisayspvm = $result["Lisayspvm"];

		return $aineistotilausDTO;

	}

	function hae_id_paatoksen_avaimella($fk_paatos){

		$aineistotilausDTO = new AineistotilausDTO();
		$query = "SELECT ID FROM Aineistotilaus WHERE FK_Paatos=:fk_paatos";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos));
		$result = $sth->fetch();
        $aineistotilausDTO->ID = $result["ID"];

		return $aineistotilausDTO;

	}  

}