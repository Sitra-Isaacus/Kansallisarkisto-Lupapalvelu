<?php
/*
 * FMAS Käyttölupapalvelu
 * Asiakirjahallinta_liite Data access object
 *
 * Created: 3.4.2017
 */

class Asiakirjahallinta_liiteDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}
	
	function luo_asiakirjahallinta_liite($asiakirjan_tarkenne, $fk_lomake, $liitten_nimi_fi, $liitteen_nimi_en, $liite_nimi_sv, $max_koko, $sallitut_tyypit, $lisatiedot_fi, $lisatiedot_en, $lisatiedot_sv, $lisaaja){

		$query = "INSERT INTO Asiakirjahallinta_liite (Asiakirjan_tarkenne, FK_Lomake, Liitteen_nimi_fi, Liitteen_nimi_en, Liitteen_nimi_sv, Maksimi_tiedoston_koko, Sallitut_tiedostotyypit, Lisatiedot_fi, Lisatiedot_en, Lisatiedot_sv, Lisaaja) VALUES (:asiakirjan_tarkenne, :fk_lomake, :liitten_nimi_fi, :liitteen_nimi_en, :liite_nimi_sv, :max_koko, :sallitut_tyypit, :lisatiedot_fi, :lisatiedot_en, :lisatiedot_sv, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':asiakirjan_tarkenne' => $asiakirjan_tarkenne, ':fk_lomake' => $fk_lomake, ':liitten_nimi_fi' => $liitten_nimi_fi, ':liitteen_nimi_en' => $liitteen_nimi_en, ':liite_nimi_sv' => $liite_nimi_sv, ':max_koko' => $max_koko, ':sallitut_tyypit' => $sallitut_tyypit, ':lisatiedot_fi' => $lisatiedot_fi, ':lisatiedot_en' => $lisatiedot_en, ':lisatiedot_sv' => $lisatiedot_sv, ':lisaaja' => $lisaaja));
		return $this->db->lastInsertId();

	}	

	function paivita_liite_asiakirjan_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Asiakirjahallinta_liite SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Asiakirjahallinta_liite SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}

	function hae_liite_asiakirjahallinnan_tiedot($id){

		$query = "SELECT * FROM Asiakirjahallinta_liite WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();
		$asiakirjahallinta_liiteDTO = array();

		$asiakirjahallinta_liiteDTO = new Asiakirjahallinta_liiteDTO();
		$asiakirjahallinta_liiteDTO->ID = $result["ID"];
		$asiakirjahallinta_liiteDTO->Asiakirjan_tarkenne = $result["Asiakirjan_tarkenne"];
		$asiakirjahallinta_liiteDTO->LomakeDTO = new LomakeDTO();
		$asiakirjahallinta_liiteDTO->LomakeDTO->ID = $result["FK_Lomake"];
		$asiakirjahallinta_liiteDTO->Viite_asiakirjaan = $result["Viite_asiakirjaan"];
		$asiakirjahallinta_liiteDTO->Liitteen_nimi_fi = $result["Liitteen_nimi_fi"];
		$asiakirjahallinta_liiteDTO->Liitteen_nimi_en = $result["Liitteen_nimi_en"];
		$asiakirjahallinta_liiteDTO->Liitteen_nimi_sv = $result["Liitteen_nimi_sv"];
		$asiakirjahallinta_liiteDTO->Maksimi_tiedoston_koko = $result["Maksimi_tiedoston_koko"];
		$asiakirjahallinta_liiteDTO->Sallitut_tiedostotyypit = $result["Sallitut_tiedostotyypit"];
		$asiakirjahallinta_liiteDTO->Lisatiedot_fi = $result["Lisatiedot_fi"];
		$asiakirjahallinta_liiteDTO->Lisatiedot_en = $result["Lisatiedot_en"];
		$asiakirjahallinta_liiteDTO->Lisatiedot_sv = $result["Lisatiedot_sv"];

		return $asiakirjahallinta_liiteDTO;

	}

	function hae_lomakkeen_liitetyypit($fk_lomake, $kieli){

		if($fk_lomake==27){
			$query = "SELECT * FROM Asiakirjahallinta_liite WHERE FK_Lomake=:fk_lomake AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Jarjestys ASC";
		} else { // Haetaan aakkosjärjestyksessä
			if($kieli=="en"){
				$query = "SELECT * FROM Asiakirjahallinta_liite WHERE FK_Lomake=:fk_lomake AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Liitteen_nimi_en ASC";
			} else { // Oletuskieli on suomi
				$query = "SELECT * FROM Asiakirjahallinta_liite WHERE FK_Lomake=:fk_lomake AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Liitteen_nimi_fi ASC";
			}			
		}
			
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lomake' => $fk_lomake));
		$result = $sth->fetchAll();
		$asiakirjahallinta_liitteetDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$asiakirjahallinta_liiteDTO = new Asiakirjahallinta_liiteDTO();
			$asiakirjahallinta_liiteDTO->ID = $result[$i]["ID"];
			$asiakirjahallinta_liiteDTO->Asiakirjan_tarkenne = $result[$i]["Asiakirjan_tarkenne"];
			$asiakirjahallinta_liiteDTO->LomakeDTO = new LomakeDTO();
			$asiakirjahallinta_liiteDTO->LomakeDTO->ID = $result[$i]["FK_Lomake"];			
			$asiakirjahallinta_liiteDTO->Viite_asiakirjaan = $result[$i]["Viite_asiakirjaan"];
			//$asiakirjahallinta_liiteDTO->Liitteen_nimi = $result[$i]["Liitteen_nimi"];
			$asiakirjahallinta_liiteDTO->Liitteen_nimi_fi = $result[$i]["Liitteen_nimi_fi"];
			$asiakirjahallinta_liiteDTO->Liitteen_nimi_en = $result[$i]["Liitteen_nimi_en"];
			$asiakirjahallinta_liiteDTO->Liitteen_nimi_sv = $result[$i]["Liitteen_nimi_sv"];
			$asiakirjahallinta_liiteDTO->Maksimi_tiedoston_koko = $result[$i]["Maksimi_tiedoston_koko"];
			$asiakirjahallinta_liiteDTO->Sallitut_tiedostotyypit = $result[$i]["Sallitut_tiedostotyypit"];
			//$asiakirjahallinta_liiteDTO->Lisatiedot = $result[$i]["Lisatiedot"];
			$asiakirjahallinta_liiteDTO->Lisatiedot_fi = $result[$i]["Lisatiedot_fi"];
			$asiakirjahallinta_liiteDTO->Lisatiedot_en = $result[$i]["Lisatiedot_en"];
			$asiakirjahallinta_liiteDTO->Lisatiedot_sv = $result[$i]["Lisatiedot_sv"];

			$asiakirjahallinta_liitteetDTO[$i] = $asiakirjahallinta_liiteDTO;

		}

		return $asiakirjahallinta_liitteetDTO;

	}	
	
	function merkitse_asiakirja_liite_poistetuksi($id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Asiakirjahallinta_liite SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':id' => $id));

	}

}