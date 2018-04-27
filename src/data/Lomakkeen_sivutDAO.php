<?php
/*
 * FMAS Käyttölupapalvelu
 * Lomakkeen_sivut Data access object
 *
 * Created: 28.4.2017
 */

class Lomakkeen_sivutDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_lomakkeen_sivu($fk_lomake, $sivun_tunniste, $nimi, $jarjestys, $lisaaja){

		$query = "INSERT INTO Lomakkeen_sivut (FK_Lomake, Sivun_tunniste, Nimi, Jarjestys, Lisaaja) VALUES (:fk_lomake, :sivun_tunniste, :nimi, :jarjestys, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lomake' => $fk_lomake, ':sivun_tunniste' => $sivun_tunniste, ':nimi' => $nimi, ':jarjestys' => $jarjestys, ':lisaaja' => $lisaaja));

		$lomakkeen_sivutDTO = new Lomakkeen_sivutDTO();
		$lomakkeen_sivutDTO->ID = $this->db->lastInsertId();

		return $lomakkeen_sivutDTO;

	}
	
	function paivita_lomakkeen_sivun_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Lomakkeen_sivut SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Lomakkeen_sivut SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}	

	function paivita_lomakkeen_sivu($id, $sivun_tunniste, $nimi, $jarjestys){
		$query = "UPDATE Lomakkeen_sivut SET Sivun_tunniste=:sivun_tunniste, Nimi=:nimi, Jarjestys=:jarjestys WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':id' => $id, ':sivun_tunniste' => $sivun_tunniste, ':nimi' => $nimi, ':jarjestys' => $jarjestys));
	}

	function hae_lomakkeen_sivu($id){

		$query = "SELECT * FROM Lomakkeen_sivut WHERE ID=:id AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$lomakkeen_sivuDTO = new Lomakkeen_sivutDTO();
		$lomakkeen_sivuDTO->ID = $result["ID"];
		$lomakkeen_sivuDTO->Nimi = $result["Nimi"];
		$lomakkeen_sivuDTO->Nimi_fi = $result["Nimi_fi"];
		$lomakkeen_sivuDTO->Nimi_en = $result["Nimi_en"];
		$lomakkeen_sivuDTO->Sivun_tunniste = $result["Sivun_tunniste"];
		$lomakkeen_sivuDTO->Jarjestys = $result["Jarjestys"];

		return $lomakkeen_sivuDTO;

	}

	function hae_lomakkeen_sivut($fk_lomake){

		$query = "SELECT * FROM Lomakkeen_sivut WHERE FK_Lomake=:fk_lomake AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Jarjestys ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lomake' => $fk_lomake));
		$result = $sth->fetchAll();
		$lomakkeen_sivutDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]] = new Lomakkeen_sivutDTO();
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->ID = $result[$i]["ID"];
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->Nimi = $result[$i]["Nimi"];
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->Nimi_fi = $result[$i]["Nimi_fi"];
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->Nimi_en = $result[$i]["Nimi_en"];
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->Sivun_tunniste = $result[$i]["Sivun_tunniste"];
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->Jarjestys = $result[$i]["Jarjestys"];
		}

		return $lomakkeen_sivutDTO;

	}
	
	function hae_lomakkeen_sivu_tunnisteella($fk_lomake, $sivun_tunniste){

		$query = "SELECT * FROM Lomakkeen_sivut WHERE FK_Lomake=:fk_lomake AND Sivun_tunniste=:sivun_tunniste AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Jarjestys ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lomake' => $fk_lomake, ':sivun_tunniste' => $sivun_tunniste));
		$result = $sth->fetchAll();
		$lomakkeen_sivutDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]] = new Lomakkeen_sivutDTO();
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->ID = $result[$i]["ID"];
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->Nimi = $result[$i]["Nimi"];
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->Nimi_fi = $result[$i]["Nimi_fi"];
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->Nimi_en = $result[$i]["Nimi_en"];
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->Sivun_tunniste = $result[$i]["Sivun_tunniste"];
			$lomakkeen_sivutDTO[$result[$i]["Sivun_tunniste"]]->Jarjestys = $result[$i]["Jarjestys"];
		}

		return $lomakkeen_sivutDTO;

	}	

	function merkitse_lomakkeen_sivu_poistetuksi($id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Lomakkeen_sivut SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':id' => $id));

	}
					
}

?>