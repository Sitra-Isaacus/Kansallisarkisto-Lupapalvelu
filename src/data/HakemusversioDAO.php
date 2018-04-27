<?php
/*
 * FMAS Käyttölupapalvelu
 * Hakemusversio Data access object
 *
 * Created: 21.9.2016
 */

class HakemusversioDAO {

	public $db;

	function __construct($db) {
       $this->db = $db;
	}

	function luo_hakemusversio($fk_tutkimus, $fk_lomake, $vt, $versio, $asiakirjatyyppi, $hakemuksen_tyyppi, $tila, $kayt_id){

		$hakemusversioDTO = new HakemusversioDTO();

		$tutk_nimi = "Tutkimuksen nimi";
		
		$query = "INSERT INTO Hakemusversio (Tutkimuksen_nimi, FK_Tutkimus, FK_Lomake, Hakemusversion_tunnus, Versio, Asiakirjatyyppi, Hakemuksen_tyyppi, Asiakirjan_tila, Lisaaja) VALUES (:tutk_nimi, :fk_tutkimus, :fk_lomake, :vt, :versio, :asiakirjatyyppi, :hakemuksen_tyyppi, :tila, :kayt_id)";
		$sth = $this->db->prepare($query);
		$sth->execute(array(':tutk_nimi' => $tutk_nimi, ':fk_tutkimus' => $fk_tutkimus, ':fk_lomake' => $fk_lomake, ':vt' => $vt, ':versio' => $versio, ':asiakirjatyyppi' => $asiakirjatyyppi, ':hakemuksen_tyyppi' => $hakemuksen_tyyppi, ':tila' => $tila, ':kayt_id' => $kayt_id));

		$hakemusversioDTO->ID = $this->db->lastInsertId();
		$hakemusversioDTO->Asiakirjatyyppi = $asiakirjatyyppi;
		$hakemusversioDTO->Hakemuksen_tyyppi = $hakemuksen_tyyppi;
		$hakemusversioDTO->Tutkimuksen_nimi = $tutk_nimi;
		$hakemusversioDTO->TutkimusDTO = new TutkimusDTO();
		$hakemusversioDTO->TutkimusDTO->ID = $fk_tutkimus;
		$hakemusversioDTO->LomakeDTO = new LomakeDTO();
		$hakemusversioDTO->LomakeDTO->ID = $fk_lomake;
		$hakemusversioDTO->Hakemusversion_tunnus = $vt;
		$hakemusversioDTO->Versio = $versio;
		$hakemusversioDTO->Lisaaja = $kayt_id;

		return $hakemusversioDTO;

	}

	function paivita_hakemusversion_muokkaaja($id, $muokkaaja, $muokkauspvm){

		$query = "UPDATE Hakemusversio SET Muokkaaja=:muokkaaja, Muokkauspvm=:muokkauspvm WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':muokkaaja' => $muokkaaja, ':muokkauspvm' => $muokkauspvm, ':id' => $id));

	}

	function kopioi_edellisen_hakemusversion_tiedot_muutoshakemusversioon($edellinen_hakemusversioDTO, $uusi_hakemusversioDTO){

		$uusi_hakemusversio_id = $uusi_hakemusversioDTO->ID;

		foreach ($edellinen_hakemusversioDTO as $key => $value) {

			if(!is_object($value) && ( $key=="Tutkimuksen_nimi" || $key=="Asiakirjatyyppi" )){

				if(is_numeric($value)){
					$q = "UPDATE Hakemusversio SET $key=$value WHERE ID=$uusi_hakemusversio_id";
				} else {
					$q = "UPDATE Hakemusversio SET $key='$value' WHERE ID=$uusi_hakemusversio_id";
				}

				$this->db->query($q);

			}

		}

	}

	function paivita_hakemusversion_tieto($id, $kentan_nimi, $kentan_arvo){

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Hakemusversio SET $kentan_nimi=$kentan_arvo WHERE ID=$id";
		} else {
			$q = "UPDATE Hakemusversio SET $kentan_nimi='$kentan_arvo' WHERE ID=$id";
		}

		$this->db->query($q);
		
	}

	function etsi_tutkimuksen_nimella($hakutermi){
				
		$query = "SELECT * FROM Hakemusversio WHERE MATCH Tutkimuksen_nimi AGAINST (:hakutermi)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':hakutermi' => $hakutermi));
		$result = $sth->fetchAll();
		$hakemusversiotDTO = array();
		
		for($i=0; $i < sizeof($result); $i++){

			$hakemusversioDTO = new HakemusversioDTO();
			$hakemusversioDTO->ID = $result[$i]["ID"];
			$hakemusversioDTO->Tutkimuksen_nimi = $result[$i]["Tutkimuksen_nimi"];
			$hakemusversioDTO->Hakemusversion_tunnus = $result[$i]["Hakemusversion_tunnus"];
			$hakemusversioDTO->Versio = $result[$i]["Versio"];
			$hakemusversioDTO->Lisayspvm = $result[$i]["Lisayspvm"];

			$hakemusversiotDTO[$i] = $hakemusversioDTO;
		}
		
		return $hakemusversiotDTO;
		
	}
	
	function hae_hakemusversion_tiedot($id){

		$hakemusversioDTO = new HakemusversioDTO();
		$query = "SELECT * FROM Hakemusversio WHERE ID=:id AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$hakemusversioDTO->ID = $result["ID"];
		$hakemusversioDTO->TutkimusDTO = new TutkimusDTO();
		$hakemusversioDTO->TutkimusDTO->ID = $result["FK_Tutkimus"];
		$hakemusversioDTO->LomakeDTO = new LomakeDTO();
		$hakemusversioDTO->LomakeDTO->ID = $result["FK_Lomake"];
		$hakemusversioDTO->Tutkimuksen_nimi = $result["Tutkimuksen_nimi"];
		$hakemusversioDTO->Hakemusversion_tunnus = $result["Hakemusversion_tunnus"];
		$hakemusversioDTO->Asiakirjatyyppi = $result["Asiakirjatyyppi"];
		$hakemusversioDTO->Hakemuksen_tyyppi = $result["Hakemuksen_tyyppi"];
		$hakemusversioDTO->Tutkijaryhmaa_taydennetaan = $result["Tutkijaryhmaa_taydennetaan"];
		$hakemusversioDTO->Luvan_kestoa_jatketaan = $result["Luvan_kestoa_jatketaan"];
		$hakemusversioDTO->Aineistoa_laajennetaan = $result["Aineistoa_laajennetaan"];
		$hakemusversioDTO->Aineiston_seurantaa_jatketaan = $result["Aineiston_seurantaa_jatketaan"];
		$hakemusversioDTO->Muu_muutoshakemuksen_tyyppi = $result["Muu_muutoshakemuksen_tyyppi"];
		$hakemusversioDTO->Muun_muutoshakemuksen_tyypin_selite = $result["Muun_muutoshakemuksen_tyypin_selite"];				
		$hakemusversioDTO->Versio = $result["Versio"];
		$hakemusversioDTO->Lisaaja = $result["Lisaaja"];
		$hakemusversioDTO->Lisayspvm = $result["Lisayspvm"];
		$hakemusversioDTO->Muokkaaja = $result["Muokkaaja"];
		$hakemusversioDTO->Muokkauspvm = $result["Muokkauspvm"];

		return $hakemusversioDTO;

	}

	function hae_tutkimuksen_uusin_hakemusversio($fk_tutkimus){

		$query = "SELECT MAX(Versio) FROM Hakemusversio WHERE FK_Tutkimus=:fk_tutkimus AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_tutkimus' => $fk_tutkimus));
		$result = $sth->fetch();
		$uusin_versio = $result["MAX(Versio)"];

		$query = "SELECT * FROM Hakemusversio WHERE (FK_Tutkimus=:fk_tutkimus) AND (Versio=:uusin_versio)";
		$sth = $this->db->prepare($query);
		$sth->execute(array(':fk_tutkimus' => $fk_tutkimus, ':uusin_versio' => $uusin_versio));
		$result = $sth->fetch();

		$uusin_hakemusversioDTO = new HakemusversioDTO();
		$uusin_hakemusversioDTO->ID = $result['ID'];
		$uusin_hakemusversioDTO->Tutkimuksen_nimi = $result["Tutkimuksen_nimi"];
		$uusin_hakemusversioDTO->Versio = $result['Versio'];
		$uusin_hakemusversioDTO->TutkimusDTO = new TutkimusDTO();
		$uusin_hakemusversioDTO->TutkimusDTO->ID = $result["FK_Tutkimus"];
		$uusin_hakemusversioDTO->LomakeDTO = new LomakeDTO();
		$uusin_hakemusversioDTO->LomakeDTO->ID = $result['FK_Lomake'];

		return $uusin_hakemusversioDTO;

	}

	function hae_muutoshakemuksen_aiemmat_hakemusversiot($fk_tutkimus, $versio){

		$query = "SELECT * FROM Hakemusversio WHERE FK_Tutkimus=:fk_tutkimus AND Versio < :versio AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_tutkimus' => $fk_tutkimus, ':versio' => $versio));
		$result = $sth->fetchAll();
		$aiemmat_hakemusversiotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakemusversioDTO = new HakemusversioDTO();
			$hakemusversioDTO->ID = $result[$i]["ID"];
			$hakemusversioDTO->Tutkimuksen_nimi = $result[$i]["Tutkimuksen_nimi"];
			$hakemusversioDTO->Hakemusversion_tunnus = $result[$i]["Hakemusversion_tunnus"];
			$hakemusversioDTO->Versio = $result[$i]["Versio"];
			$hakemusversioDTO->Lisayspvm = $result[$i]["Lisayspvm"];

			$aiemmat_hakemusversiotDTO[$i] = $hakemusversioDTO;
		}

		return $aiemmat_hakemusversiotDTO;

	}

	function hae_tutkimuksen_kaikki_hakemusversiot($fk_tutkimus){

		$query = "SELECT * FROM Hakemusversio WHERE FK_Tutkimus=:fk_tutkimus AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Versio ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_tutkimus' => $fk_tutkimus));
		$result = $sth->fetchAll();
		$hakemusversiotDTO = array();
		
		for($i=0; $i < sizeof($result); $i++){

			$hakemusversioDTO = new HakemusversioDTO();
			$hakemusversioDTO->ID = $result[$i]["ID"];
			$hakemusversioDTO->LomakeDTO = new LomakeDTO();
			$hakemusversioDTO->LomakeDTO->ID = $result[$i]["FK_Lomake"];
			$hakemusversioDTO->TutkimusDTO = new TutkimusDTO();
			$hakemusversioDTO->TutkimusDTO->ID = $fk_tutkimus;
			$hakemusversioDTO->Tutkimuksen_nimi = $result[$i]["Tutkimuksen_nimi"];
			$hakemusversioDTO->Hakemusversion_tunnus = $result[$i]["Hakemusversion_tunnus"];
			$hakemusversioDTO->Hakemuksen_tyyppi = $result[$i]["Hakemuksen_tyyppi"];
			$hakemusversioDTO->Versio = $result[$i]["Versio"];
			$hakemusversioDTO->Lisayspvm = $result[$i]["Lisayspvm"];

			$hakemusversiotDTO[$i] = $hakemusversioDTO;

		}

		return $hakemusversiotDTO;

	}

	function hae_tutkimuksen_poistamattomat_hakemusversiot($fk_tutkimus){

		$query = "SELECT * FROM Hakemusversio WHERE FK_Tutkimus=:fk_tutkimus AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_tutkimus' => $fk_tutkimus));
		$result = $sth->fetchAll();

		for($i=0; $i < sizeof($result); $i++){

			$hakemusversioDTO = new HakemusversioDTO();
			$hakemusversioDTO->ID = $result[$i]["ID"];
			$hakemusversioDTO->Tutkimuksen_nimi = $result[$i]["Tutkimuksen_nimi"];
			$hakemusversioDTO->Hakemusversion_tunnus = $result[$i]["Hakemusversion_tunnus"];
			$hakemusversioDTO->Versio = $result[$i]["Versio"];
			$hakemusversioDTO->Lisayspvm = $result[$i]["Lisayspvm"];

			$hakemusversiotDTO[$result[$i]["ID"]] = $hakemusversioDTO;

		}

		return $hakemusversiotDTO;

	}

	function merkitse_hakemusversio_poistetuksi($hakemusversio_id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Hakemusversio SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:hakemusversio_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':hakemusversio_id' => $hakemusversio_id));

	}	
	
	function poista_hakemusversio($hakemusversio_id){

		$query = "DELETE FROM Hakemusversio WHERE ID=:hakemusversio_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':hakemusversio_id' => $hakemusversio_id));

	}

}