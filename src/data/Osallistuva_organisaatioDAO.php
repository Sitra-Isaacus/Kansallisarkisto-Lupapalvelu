<?php
/*
 * FMAS Käyttölupapalvelu
 * Osallistuva_organisaatio Data access object
 *
 * Created: 4.9.2017
 */

class Osallistuva_organisaatioDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function luo_organisaatio($fk_hakemusversio, $lisaaja){
	
		$query = "INSERT INTO Osallistuva_organisaatio (FK_Hakemusversio, Lisaaja) VALUES (:fk_hakemusversio, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':lisaaja' => $lisaaja));

		return $this->db->lastInsertId();

	}

	function paivita_organisaation_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Osallistuva_organisaatio SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Osallistuva_organisaatio SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}	
	
	function hae_hakemusversion_organisaatiot($fk_hakemusversio){

		$query = "SELECT * FROM Osallistuva_organisaatio WHERE FK_Hakemusversio=:fk_hakemusversio AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Lisayspvm ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));
		$result = $sth->fetchAll();
		$organisaatiotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$osallistuva_organisaatioDTO = new Osallistuva_organisaatioDTO();
			$osallistuva_organisaatioDTO->ID = $result[$i]["ID"];
			$osallistuva_organisaatioDTO->Nimi = $result[$i]["Nimi"];
			$osallistuva_organisaatioDTO->Osoite = $result[$i]["Osoite"];
			$osallistuva_organisaatioDTO->Rekisterinpitaja = $result[$i]["Rekisterinpitaja"];
			$osallistuva_organisaatioDTO->Rooli = $result[$i]["Rooli"];
			$osallistuva_organisaatioDTO->Edustaja = $result[$i]["Edustaja"];
			$osallistuva_organisaatioDTO->Edustajan_email = $result[$i]["Edustajan_email"];
			$osallistuva_organisaatioDTO->Y_tunnus = $result[$i]["Y_tunnus"];
			$osallistuva_organisaatioDTO->MTA_allekirjoittaja = $result[$i]["MTA_allekirjoittaja"];
			
			$organisaatiotDTO[$i] = $osallistuva_organisaatioDTO;

		}

		return $organisaatiotDTO;

	}
	
	function merkitse_organisaatio_poistetuksi($id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Osallistuva_organisaatio SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':id' => $id));

	}	
   
}

?>