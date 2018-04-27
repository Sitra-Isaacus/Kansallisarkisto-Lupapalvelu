<?php
/*
 * FMAS Käyttölupapalvelu
 * Lomake_hakemus Data access object
 *
 * Created: 10.5.2017
 */

class Lomake_hakemusDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function luo_lomake_hakemus($fk_lomake, $uus_hak_txt, $lisaaja){

		$query = "INSERT INTO Lomake_hakemus (FK_Lomake, Uusi_hakemus_painike_teksti, Lisaaja) VALUES (:fk_lomake, :uus_hak_txt, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_lomake' => $fk_lomake, ':uus_hak_txt' => $uus_hak_txt, ':lisaaja' => $lisaaja));

	}
	
	function paivita_lomake_hakemuksen_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Lomake_hakemus SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Lomake_hakemus SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}	

	function hae_lomakkeen_lomake_hakemus($fk_lomake){

		$query = "SELECT * FROM Lomake_hakemus WHERE FK_Lomake=:fk_lomake AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lomake' => $fk_lomake));
		$result = $sth->fetch();

		$lomake_hakemusDTO = new Lomake_hakemusDTO();
		$lomake_hakemusDTO->ID = $result["ID"];
		$lomake_hakemusDTO->Uusi_hakemus_painike_teksti = $result["Uusi_hakemus_painike_teksti"];
		$lomake_hakemusDTO->Uusi_hakemus_painike_teksti_fi = $result["Uusi_hakemus_painike_teksti_fi"];
		$lomake_hakemusDTO->Uusi_hakemus_painike_teksti_en = $result["Uusi_hakemus_painike_teksti_en"];

		return $lomake_hakemusDTO;

	}

	function hae_hakemus_lomakkeet(){

		$query = "SELECT * FROM Lomake_hakemus WHERE Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute();
		$result = $sth->fetchAll();
		$lomake_hakemuksetDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$lomake_hakemusDTO = new Lomake_hakemusDTO();
			$lomake_hakemusDTO->ID = $result[$i]["ID"];
			$lomake_hakemusDTO->LomakeDTO = new LomakeDTO();
			$lomake_hakemusDTO->LomakeDTO->ID = $result[$i]["FK_Lomake"];
			$lomake_hakemusDTO->Uusi_hakemus_painike_teksti = $result[$i]["Uusi_hakemus_painike_teksti"];
			$lomake_hakemusDTO->Uusi_hakemus_painike_teksti_fi = $result[$i]["Uusi_hakemus_painike_teksti_fi"];
			$lomake_hakemusDTO->Uusi_hakemus_painike_teksti_en = $result[$i]["Uusi_hakemus_painike_teksti_en"];
			$lomake_hakemuksetDTO[$i] = $lomake_hakemusDTO;
		}

		return $lomake_hakemuksetDTO;

	}

	function merkitse_lomake_hakemus_poistetuksi($id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Lomake_hakemus SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':id' => $id));

	}
									
}

?>