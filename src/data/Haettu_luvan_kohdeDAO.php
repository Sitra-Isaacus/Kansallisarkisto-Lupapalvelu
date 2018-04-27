<?php
/*
 * FMAS Käyttölupapalvelu
 * Haettu_luvan_kohde Data access object
 *
 * Created: 9.3.2017
 */

class Haettu_luvan_kohdeDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function alusta_haettu_luvan_kohde($fk_haettu_aineisto, $kohde_tyyppi, $fk_kayttaja){

		$haettu_luvan_kohdeDTO = new Haettu_luvan_kohdeDTO($fk_haettu_aineisto, $fk_kayttaja);

		$query = "INSERT INTO Haettu_luvan_kohde (FK_Haettu_aineisto, Kohde_tyyppi, Lisaaja) VALUES (:fk_haettu_aineisto, :kohde_tyyppi, :fk_kayttaja)";
		$sth = $this->db->prepare($query);
		$sth->execute(array(':fk_haettu_aineisto' => $fk_haettu_aineisto, ':kohde_tyyppi' => $kohde_tyyppi, ':fk_kayttaja' => $fk_kayttaja));
		$haettu_luvan_kohdeDTO->ID = $this->db->lastInsertId();
		$haettu_luvan_kohdeDTO->Kohde_tyyppi = $kohde_tyyppi;

		return $haettu_luvan_kohdeDTO;

	}

	function lisaa_tieto_haetun_aineiston_haettuun_lupaan($fk_haettu_aineisto, $kohde_tyyppi, $kentan_nimi, $kentan_arvo, $fk_kayttaja){

		$haettu_luvan_kohdeDTO = new Haettu_luvan_kohdeDTO();

		if($kentan_nimi=="Toimintayksikoihin_on_oltu_yhteydessa" || $kentan_nimi=="Kohdejoukon_mukaanottokriteerit" || $kentan_nimi=="Toimintayksikot" || $kentan_nimi=="FK_Luvan_kohde" || $kentan_nimi=="Kuvaus_naytteista" || $kentan_nimi=="Muuttujat_lueteltuna" || $kentan_nimi=="Kohde_tyyppi" || $kentan_nimi=="Poiminta_ajankohdat"){

			if(is_numeric($kentan_arvo)){
				$q = "INSERT INTO Haettu_luvan_kohde (FK_Haettu_aineisto, $kentan_nimi, Kohde_tyyppi, Lisaaja) VALUES ($fk_haettu_aineisto, $kentan_arvo, '$kohde_tyyppi', $fk_kayttaja)";
			} else {
				$q = "INSERT INTO Haettu_luvan_kohde (FK_Haettu_aineisto, $kentan_nimi, Kohde_tyyppi, Lisaaja) VALUES ($fk_haettu_aineisto, '$kentan_arvo', '$kohde_tyyppi', $fk_kayttaja)";
			}

			$this->db->query($q);
			$haettu_luvan_kohdeDTO->ID = $this->db->lastInsertId();
			$haettu_luvan_kohdeDTO->Kohde_tyyppi = $kohde_tyyppi;

		}

		return $haettu_luvan_kohdeDTO;

	}
	
	function kopioi_haettu_luvan_kohde_ja_liita_haettuun_aineistoon($edellinen_haettu_luvan_kohdeDTO, $haettu_aineistoDTO, $lisaaja){

		$query = "INSERT INTO Haettu_luvan_kohde (FK_Haettu_aineisto, FK_Luvan_kohde, Muuttujat_lueteltuna, Kohde_tyyppi, Poiminta_ajankohdat, Kuvaus_naytteista, Toimintayksikot, Kohdejoukon_mukaanottokriteerit, Toimintayksikoihin_on_oltu_yhteydessa, Lisaaja) VALUES (:haettu_aineisto_id, :fk_luvan_kohde, :muuttujat, :kohde_tyyppi, :poim, :kuv_nayt, :thl1, :thl2, :thl3, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':haettu_aineisto_id' => $haettu_aineistoDTO->ID, ':fk_luvan_kohde' => $edellinen_haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID, ':muuttujat' => $edellinen_haettu_luvan_kohdeDTO->Muuttujat_lueteltuna, ':kohde_tyyppi' => $edellinen_haettu_luvan_kohdeDTO->Kohde_tyyppi, ':poim' => $edellinen_haettu_luvan_kohdeDTO->Poiminta_ajankohdat, ':kuv_nayt' => $edellinen_haettu_luvan_kohdeDTO->Kuvaus_naytteista, ':thl1' => $edellinen_haettu_luvan_kohdeDTO->Toimintayksikot, ':thl2' => $edellinen_haettu_luvan_kohdeDTO->Kohdejoukon_mukaanottokriteerit, ':thl3' => $edellinen_haettu_luvan_kohdeDTO->Toimintayksikoihin_on_oltu_yhteydessa,':lisaaja' => $lisaaja));

		$haettu_luvan_kohdeDTO = new Haettu_luvan_kohdeDTO();
		$haettu_luvan_kohdeDTO->ID = $this->db->lastInsertId();

		return $haettu_luvan_kohdeDTO;

	}	

	function paivita_tieto_haetun_aineiston_haettuun_lupaan($id, $kentan_nimi, $kentan_arvo){

		if($kentan_nimi=="Toimintayksikoihin_on_oltu_yhteydessa" || $kentan_nimi=="Kohdejoukon_mukaanottokriteerit" || $kentan_nimi=="Toimintayksikot" || $kentan_nimi=="FK_Luvan_kohde" || $kentan_nimi=="Kuvaus_naytteista" || $kentan_nimi=="Muuttujat_lueteltuna" || $kentan_nimi=="Kohde_tyyppi" || $kentan_nimi=="Poiminta_ajankohdat"){

			if(is_numeric($kentan_arvo)){
				$q = "UPDATE Haettu_luvan_kohde SET $kentan_nimi=$kentan_arvo WHERE ID=$id";
			} else {
				$q = "UPDATE Haettu_luvan_kohde SET $kentan_nimi='$kentan_arvo' WHERE ID=$id";
			}

			$this->db->query($q);

		}

	}
	
	function hae_haettu_luvan_kohde($id){

		$query = "SELECT * FROM Haettu_luvan_kohde WHERE ID=:id AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();
		
		$haettu_luvan_kohdeDTO = new Haettu_luvan_kohdeDTO();
		$haettu_luvan_kohdeDTO->ID = $result["ID"];
		$haettu_luvan_kohdeDTO->Luvan_kohdeDTO = new Luvan_kohdeDTO();
		$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID = $result["FK_Luvan_kohde"];
		$haettu_luvan_kohdeDTO->Muuttujat_lueteltuna = $result["Muuttujat_lueteltuna"];
		$haettu_luvan_kohdeDTO->Kohde_tyyppi = $result["Kohde_tyyppi"];
		$haettu_luvan_kohdeDTO->Poiminta_ajankohdat = $result["Poiminta_ajankohdat"];
		$haettu_luvan_kohdeDTO->Kuvaus_naytteista = $result["Kuvaus_naytteista"];
		$haettu_luvan_kohdeDTO->Toimintayksikot = $result["Toimintayksikot"];
		$haettu_luvan_kohdeDTO->Kohdejoukon_mukaanottokriteerit = $result["Kohdejoukon_mukaanottokriteerit"];
		$haettu_luvan_kohdeDTO->Toimintayksikoihin_on_oltu_yhteydessa = $result["Toimintayksikoihin_on_oltu_yhteydessa"];

		return $haettu_luvan_kohdeDTO;

	}	

	function hae_haetun_aineiston_haetut_luvan_kohteet($fk_haettu_aineisto){

		$query = "SELECT * FROM Haettu_luvan_kohde WHERE FK_Haettu_aineisto=:fk_haettu_aineisto AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_haettu_aineisto' => $fk_haettu_aineisto));
		$result = $sth->fetchAll();
		$haetut_luvan_kohteetDTO = array();

		$luvan_kohdeDAO = new Luvan_kohdeDAO($this->db);

		for($i=0; $i < sizeof($result); $i++){

			$haettu_luvan_kohdeDTO = new Haettu_luvan_kohdeDTO();
			$haettu_luvan_kohdeDTO->ID = $result[$i]["ID"];
			$haettu_luvan_kohdeDTO->Luvan_kohdeDTO = new Luvan_kohdeDTO();
			$haettu_luvan_kohdeDTO->Luvan_kohdeDTO = $luvan_kohdeDAO->hae_luvan_kohde($result[$i]["FK_Luvan_kohde"]);
			$haettu_luvan_kohdeDTO->Muuttujat_lueteltuna = $result[$i]["Muuttujat_lueteltuna"];
			$haettu_luvan_kohdeDTO->Kohde_tyyppi = $result[$i]["Kohde_tyyppi"];
			$haettu_luvan_kohdeDTO->Poiminta_ajankohdat = $result[$i]["Poiminta_ajankohdat"];
			$haettu_luvan_kohdeDTO->Kuvaus_naytteista = $result[$i]["Kuvaus_naytteista"];
			$haettu_luvan_kohdeDTO->Toimintayksikot = $result[$i]["Toimintayksikot"];
			$haettu_luvan_kohdeDTO->Kohdejoukon_mukaanottokriteerit = $result[$i]["Kohdejoukon_mukaanottokriteerit"];
			$haettu_luvan_kohdeDTO->Toimintayksikoihin_on_oltu_yhteydessa = $result[$i]["Toimintayksikoihin_on_oltu_yhteydessa"];			

			$haetut_luvan_kohteetDTO[$i] = $haettu_luvan_kohdeDTO;
			
		}

		return $haetut_luvan_kohteetDTO;

	}
	
	function hae_haetun_aineiston_haetun_luvan_kohteet($fk_haettu_aineisto){

		$query = "SELECT * FROM Haettu_luvan_kohde WHERE FK_Haettu_aineisto=:fk_haettu_aineisto AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_haettu_aineisto' => $fk_haettu_aineisto));
		$result = $sth->fetchAll();
		$haetut_luvan_kohteetDTO = array();

		$luvan_kohdeDAO = new Luvan_kohdeDAO($this->db);

		for($i=0; $i < sizeof($result); $i++){

			$haettu_luvan_kohdeDTO = new Haettu_luvan_kohdeDTO();
			$haettu_luvan_kohdeDTO->ID = $result[$i]["ID"];
			$haettu_luvan_kohdeDTO->Luvan_kohdeDTO = new Luvan_kohdeDTO();
			$haettu_luvan_kohdeDTO->Luvan_kohdeDTO = $luvan_kohdeDAO->hae_luvan_kohde($result[$i]["FK_Luvan_kohde"]);
			$haettu_luvan_kohdeDTO->Muuttujat_lueteltuna = $result[$i]["Muuttujat_lueteltuna"];
			$haettu_luvan_kohdeDTO->Kohde_tyyppi = $result[$i]["Kohde_tyyppi"];
			$haettu_luvan_kohdeDTO->Poiminta_ajankohdat = $result[$i]["Poiminta_ajankohdat"];
			$haettu_luvan_kohdeDTO->Kuvaus_naytteista = $result[$i]["Kuvaus_naytteista"];
			$haettu_luvan_kohdeDTO->Toimintayksikot = $result[$i]["Toimintayksikot"];
			$haettu_luvan_kohdeDTO->Kohdejoukon_mukaanottokriteerit = $result[$i]["Kohdejoukon_mukaanottokriteerit"];
			$haettu_luvan_kohdeDTO->Toimintayksikoihin_on_oltu_yhteydessa = $result[$i]["Toimintayksikoihin_on_oltu_yhteydessa"];				

			if(!isset($haetut_luvan_kohteetDTO[$result[$i]["Kohde_tyyppi"]])){
				$haetut_luvan_kohteetDTO[$result[$i]["Kohde_tyyppi"]] = array();
			}

			array_push($haetut_luvan_kohteetDTO[$result[$i]["Kohde_tyyppi"]], $haettu_luvan_kohdeDTO);

		}

		return $haetut_luvan_kohteetDTO;

	}

	function merkitse_haettu_luvan_kohde_poistetuksi($id, $kayt_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Haettu_luvan_kohde SET Poistaja=:kayt_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':kayt_id' => $kayt_id, ':nyt' => $nyt, ':id' => $id));

	}
  
}

?>