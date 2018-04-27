<?php
/*
 * FMAS Käyttölupapalvelu
 * Osio_lause Data access object
 *
 * Created: 2.3.2017
 */

class Osio_lauseDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function luo_osio_lause($fk_osio_saanto, $fk_asiakirjahallinta_saanto, $predikaatti, $fk_osio_muuttuja, $alkeisdisjunktio, $lisaaja){

		$query = "INSERT INTO Osio_lause (FK_Osio_saanto, FK_Asiakirjahallinta_saanto, Predikaatti, FK_Osio_muuttuja, Alkeisdisjunktio, Lisaaja) VALUES (:fk_osio_saanto, :fk_asiakirjahallinta_saanto, :predikaatti, :fk_osio_muuttuja, :alkeisdisjunktio, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_osio_saanto' => $fk_osio_saanto, ':fk_asiakirjahallinta_saanto' => $fk_asiakirjahallinta_saanto, ':predikaatti' => $predikaatti, ':fk_osio_muuttuja' => $fk_osio_muuttuja, ':alkeisdisjunktio' => $alkeisdisjunktio, ':lisaaja' => $lisaaja));

	}

	function paivita_osio_lauseen_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Osio_lause SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Osio_lause SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}

	function hae_asiakirjan_saannon_lause($fk_asiakirjahallinta_saanto){

		$query = "SELECT * FROM Osio_lause WHERE FK_Asiakirjahallinta_saanto=:fk_asiakirjahallinta_saanto AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_asiakirjahallinta_saanto' => $fk_asiakirjahallinta_saanto));
		$result = $sth->fetch();

		$osio_lauseDTO = new Osio_lauseDTO();
		$osio_lauseDTO->ID = $result["ID"];
		$osio_lauseDTO->Predikaatti = $result["Predikaatti"];
		$osio_lauseDTO->Alkeisdisjunktio = $result["Alkeisdisjunktio"];
		$osio_lauseDTO->OsioDTO_Muuttuja = new OsioDTO();
		$osio_lauseDTO->OsioDTO_Muuttuja->ID = $result["FK_Osio_muuttuja"];

		return $osio_lauseDTO;

	}

	function hae_saannon_lauseet($fk_osio_saanto){

		$osio_lauseetDTO_kaikki = array();
		$etsitaan_lauseita = true;
		$ad = 1;

		while($etsitaan_lauseita){

			$query = "SELECT * FROM Osio_lause WHERE FK_Osio_saanto=:fk_osio_saanto AND Alkeisdisjunktio=:ad AND Poistaja IS NULL AND Poistopvm IS NULL";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':fk_osio_saanto' => $fk_osio_saanto, ':ad' => $ad));

			if($sth->rowCount() > 0){

				$result = $sth->fetchAll();
				$osio_lauseetDTO = array();

				for($i=0; $i < sizeof($result); $i++){

					$osio_lauseDTO = new Osio_lauseDTO();
					$osio_lauseDTO->ID = $result[$i]["ID"];
					$osio_lauseDTO->Predikaatti = $result[$i]["Predikaatti"];
					$osio_lauseDTO->Alkeisdisjunktio = $result[$i]["Alkeisdisjunktio"];
					$osio_lauseDTO->OsioDTO_Muuttuja = new OsioDTO();
					$osio_lauseDTO->OsioDTO_Muuttuja->ID = $result[$i]["FK_Osio_muuttuja"];
					$osio_lauseetDTO[$i] = $osio_lauseDTO;

				}

				$osio_lauseetDTO_kaikki[$ad-1] = $osio_lauseetDTO;
				$ad++;

			} else {
				$etsitaan_lauseita = false;
			}

		}

		return $osio_lauseetDTO_kaikki;

	}

	function merkitse_osio_lause_poistetuksi($id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Osio_lause SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':id' => $id));

	}

	function merkitse_osio_saannon_lause_poistetuksi($fk_osio_saanto, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Osio_lause SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE FK_Osio_saanto=:fk_osio_saanto";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':fk_osio_saanto' => $fk_osio_saanto));

	}

}