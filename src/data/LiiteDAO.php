<?php
/*
 * FMAS Käyttölupapalvelu
 * Liite Data access object
 *
 * Created: 7.10.2016
 */

class LiiteDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function hae_liite($id){

		$query = "SELECT * FROM Liite WHERE ID=:id AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$liiteDTO = new LiiteDTO();
		$liiteDTO->ID = $result["ID"];
		$liiteDTO->Asiakirjan_tila = $result["Asiakirjan_tila"];
		$liiteDTO->Liitetiedosto_nimi = $result["Liitetiedosto_nimi"];
		$liiteDTO->Liitteen_tyypin_koodi = $result["Liitteen_tyypin_koodi"];
		$liiteDTO->Liitetiedosto_blob = base64_encode($result["Liitetiedosto_blob"]);
		$liiteDTO->Tiedostomuoto = $result["Tiedostomuoto"];
		$liiteDTO->Versio = $result["Versio"];		
		$liiteDTO->Julkisuusluokka = $result["Julkisuusluokka"];
		$liiteDTO->Salassapitoaika = $result["Salassapitoaika"];
		$liiteDTO->Salassapitoperuste = $result["Salassapitoperuste"];
		$liiteDTO->Suojaustaso = $result["Suojaustaso"];
		$liiteDTO->Henkilotietoja = $result["Henkilotietoja"];
		$liiteDTO->Sailytysajan_pituus = $result["Sailytysajan_pituus"];
		$liiteDTO->Sailytysajan_peruste = $result["Sailytysajan_peruste"];				
		$liiteDTO->Lisaaja = $result["Lisaaja"];
		$liiteDTO->Lisayspvm = $result["Lisayspvm"];

		return $liiteDTO;

	}

	function lisaa_liitetiedosto($liite_nimi, $liitteen_koodi, $data, $tiedostomuoto, $asiakirjan_tila, $versio, $lisaaja){

		$query = "INSERT INTO Liite (Liitetiedosto_nimi, Liitteen_tyypin_koodi, Liitetiedosto_blob, Tiedostomuoto, Asiakirjan_tila, Versio, Lisaaja) VALUES (:liite_nimi, :liitteen_koodi, :data, :tiedostomuoto, :asiakirjan_tila, :versio, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':liite_nimi' => $liite_nimi, ':liitteen_koodi' => $liitteen_koodi, ':data' => $data, ':tiedostomuoto' => $tiedostomuoto, ':asiakirjan_tila' => $asiakirjan_tila, ':versio' => $versio, ':lisaaja' => $lisaaja));
		return $this->db->lastInsertId();

	}
	
	function paivita_liitteen_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Liite SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Liite SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}	

	function merkitse_liite_poistetuksi($liite_id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Liite SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:liite_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':liite_id' => $liite_id));

	}

	/*
	function lisaa_hakemusversioon_liitetiedosto($fk_hakemusversio, $liitteen_koodi, $liite, $data, $tiedostomuoto, $lisaaja){

		$query = "INSERT INTO Liitteet (FK_Hakemusversio, Liitteen_tyypin_koodi, Liitetiedosto, Liitetiedosto_blob, Tiedostomuoto, Lisaaja) VALUES (:fk_hakemusversio, :liitteen_koodi, :liite, :data, :tiedostomuoto, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':liitteen_koodi' => $liitteen_koodi, ':liite' => $liite, ':data' => $data, ':tiedostomuoto' => $tiedostomuoto, ':lisaaja' => $lisaaja));

	}

	function hae_hakemusversion_liitteet($fk_hakemusversio){

		$query = "SELECT * FROM Liitteet WHERE FK_Hakemusversio=:fk_hakemusversio";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));
		$liitteet = $sth->fetchAll();
		$liitteetDTO = array();

		for($i=0; $i < sizeof($liitteet); $i++){

			$liiteDTO = new LiitteetDTO();
			$liiteDTO->ID = $liitteet[$i]["ID"];
			$liiteDTO->HakemusversioDTO = new HakemusversioDTO();
			$liiteDTO->HakemusversioDTO->ID = $liitteet[$i]["FK_Hakemusversio"];
			$liiteDTO->Liitteen_tyypin_koodi = $liitteet[$i]["Liitteen_tyypin_koodi"];
			$liiteDTO->Liitetiedosto = $liitteet[$i]["Liitetiedosto"];
			$liiteDTO->Liitetiedosto_blob = base64_encode($liitteet[$i]["Liitetiedosto_blob"]);
			$liiteDTO->Tiedostomuoto = $liitteet[$i]["Tiedostomuoto"];
			$liiteDTO->Lisaaja = $liitteet[$i]["Lisaaja"];
			$liiteDTO->Lisayspvm = $liitteet[$i]["Lisayspvm"];
			$liitteetDTO[$i] = $liiteDTO;

		}

		return $liitteetDTO;

	}

	function poista_hakemusversion_liitteet($fk_hakemusversio){

		$query = "DELETE FROM Liitteet WHERE FK_Hakemusversio=:fk_hakemusversio";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));

	}

	function poista_hakemusversion_liite($id){

		$query = "DELETE FROM Liitteet WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':id' => $id));

	}
	*/
}