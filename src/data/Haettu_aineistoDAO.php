<?php
/*
 * FMAS Käyttölupapalvelu
 * Haettu_aineisto Data access object
 *
 * Created: 5.10.2016
 */

class Haettu_aineistoDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function alusta_haettu_aineisto_hakemusversioon($hakemusversioDTO, $aineiston_indx, $kayt_id){

		$fk_hakemusversio = $hakemusversioDTO->ID;
		$haettu_aineistoDTO = new Haettu_aineistoDTO();

		$query = "INSERT INTO Haettu_aineisto (FK_Hakemusversio, Aineiston_indeksi, Lisaaja) VALUES (:fk_hakemusversio, :aineiston_indx, :kayt_id)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':aineiston_indx' => $aineiston_indx, ':kayt_id' => $kayt_id));

		$haettu_aineistoDTO->ID = $this->db->lastInsertId();
		$haettu_aineistoDTO->HakemusversioDTO = $hakemusversioDTO;
		$haettu_aineistoDTO->Aineiston_indeksi = 0;
		$haettu_aineistoDTO->Lisaaja = $kayt_id;

		return $haettu_aineistoDTO;

	}

	function paivita_haetun_aineiston_kentta($id, $kentta, $arvo){

		if(is_numeric($arvo)){
			$query = "UPDATE Haettu_aineisto SET $kentta=$arvo WHERE ID=$id";
		} else {
			$query = "UPDATE Haettu_aineisto SET $kentta='$arvo' WHERE ID=$id";
		}

		$this->db->query($query);

	}

	function kopioi_edellisen_haetun_aineiston_tiedot_uuteen_haettuun_aineistoon($edellinen_haettu_aineistoDTO, $uusi_haettu_aineistoDTO){

		$uusi_haettu_aineisto_id = $uusi_haettu_aineistoDTO->ID;

		foreach ($edellinen_haettu_aineistoDTO as $key => $value) {

			if(!is_object($value) && ($key=="Aineiston_indeksi" || $key=="Poimitaanko_verrokeille_samat" || $key=="Poimitaanko_viitehenkiloille_samat")){
							
				if(is_numeric($value)){
					$q = "UPDATE Haettu_aineisto SET $key=$value WHERE ID=$uusi_haettu_aineisto_id";
				} else {
					$q = "UPDATE Haettu_aineisto SET $key='$value' WHERE ID=$uusi_haettu_aineisto_id";
				}

				$this->db->query($q);
				
			}	

		}

	}
	
	function hae_haettu_aineisto($id){
		
		$query = "SELECT * FROM Haettu_aineisto WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$haettu_aineistoDTO = new Haettu_aineistoDTO();
		$haettu_aineistoDTO->ID = $result["ID"];
		$haettu_aineistoDTO->HakemusversioDTO = new HakemusversioDTO();
		$haettu_aineistoDTO->HakemusversioDTO->ID = $result["FK_Hakemusversio"];
		$haettu_aineistoDTO->Aineiston_indeksi = $result["Aineiston_indeksi"];
		
		return $haettu_aineistoDTO;		
		
	}

	function hae_hakemusversion_haetut_aineistot($fk_hakemusversio){

		$query = "SELECT * FROM Haettu_aineisto WHERE FK_Hakemusversio=:fk_hakemusversio";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		$haetut_aineistotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$haettu_aineistoDTO = new Haettu_aineistoDTO();
			$haettu_aineistoDTO->ID = $result[$i]["ID"];
			$haettu_aineistoDTO->HakemusversioDTO = new HakemusversioDTO();
			$haettu_aineistoDTO->HakemusversioDTO->ID = $result[$i]["FK_Hakemusversio"];
			$haettu_aineistoDTO->Aineiston_indeksi = $result[$i]["Aineiston_indeksi"];
			$haettu_aineistoDTO->Poimitaanko_verrokeille_samat = $result[$i]["Poimitaanko_verrokeille_samat"];
			$haettu_aineistoDTO->Poimitaanko_viitehenkiloille_samat = $result[$i]["Poimitaanko_viitehenkiloille_samat"];
			
			$haetut_aineistotDTO[$i] = $haettu_aineistoDTO;

		}

		return $haetut_aineistotDTO;

	}

	function hae_hakemusversion_haetun_aineiston_indeksin_aineisto($fk_hakemusversio, $haettu_aineisto_aineiston_indeksi){

		$query = "SELECT * FROM Haettu_aineisto WHERE FK_Hakemusversio=:fk_hakemusversio AND Aineiston_indeksi=:haettu_aineisto_aineiston_indeksi";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':haettu_aineisto_aineiston_indeksi' => $haettu_aineisto_aineiston_indeksi));
		$result = $sth->fetch();

		$haettu_aineistoDTO = new Haettu_aineistoDTO();
		$haettu_aineistoDTO->ID = $result["ID"];
		$haettu_aineistoDTO->HakemusversioDTO = new HakemusversioDTO();
		$haettu_aineistoDTO->HakemusversioDTO->ID = $result["FK_Hakemusversio"];
		$haettu_aineistoDTO->Aineiston_indeksi = $result["Aineiston_indeksi"];
		$haettu_aineistoDTO->Poimitaanko_verrokeille_samat = $result["Poimitaanko_verrokeille_samat"];
		$haettu_aineistoDTO->Poimitaanko_viitehenkiloille_samat = $result["Poimitaanko_viitehenkiloille_samat"];
		
		return $haettu_aineistoDTO;

	}

	function hae_hakemusversion_aineistojen_maara($fk_hakemusversio){

		$query = "SELECT ID FROM Haettu_aineisto WHERE FK_hakemusversio=:fk_hakemusversio";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));
		return $sth->rowCount();

	}

	function poista_hakemusversion_haetut_aineistot($fk_hakemusversio){

		$query = "DELETE FROM Haettu_aineisto WHERE FK_Hakemusversio=:fk_hakemusversio";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));

	}

	function poista_haetun_aineiston_haetut_korit($fk_haettu_aineisto){
		$query = "DELETE FROM Haettu_kori WHERE FK_Haettu_aineisto=:fk_haettu_aineisto";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_haettu_aineisto' => $fk_haettu_aineisto));
	}
   
}

?>