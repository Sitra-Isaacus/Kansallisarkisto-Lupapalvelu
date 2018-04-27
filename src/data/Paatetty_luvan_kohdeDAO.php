<?php
/*
 * FMAS Käyttölupapalvelu
 * Paatetty_luvan_kohde Data access object
 *
 * Created: 8.12.2016
 */

class Paatetty_luvan_kohdeDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_paatetty_luvan_kohde_paatettyyn_aineistoon($fk_paatetty_aineisto, $fk_haettu_luvan_kohde, $joukon_tyyppi, $lisatiedot, $poim_aj, $lisaaja){

		$query = "INSERT INTO Paatetty_luvan_kohde (FK_Paatetty_aineisto, FK_Luvan_kohde, Kohde_tyyppi, Muuttujat_lueteltuna, Poiminta_ajankohdat, Lisaaja) VALUES (:fk_paatetty_aineisto, :fk_haettu_luvan_kohde, :joukon_tyyppi, :lisatiedot, :poim_aj, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatetty_aineisto' => $fk_paatetty_aineisto, ':fk_haettu_luvan_kohde' => $fk_haettu_luvan_kohde, ':joukon_tyyppi' => $joukon_tyyppi, ':lisatiedot' => $lisatiedot, ':poim_aj' => $poim_aj, ':lisaaja' => $lisaaja));

		$paatetty_luvan_kohdeDTO = new Paatetty_luvan_kohdeDTO();
		$paatetty_luvan_kohdeDTO->ID = $this->db->lastInsertId();
		$paatetty_luvan_kohdeDTO->Luvan_kohdeDTO = new Luvan_kohdeDTO();
		$paatetty_luvan_kohdeDTO->Luvan_kohdeDTO->ID = $fk_haettu_luvan_kohde;
		$paatetty_luvan_kohdeDTO->Kohde_tyyppi = $joukon_tyyppi;
		$paatetty_luvan_kohdeDTO->Muuttujat_lueteltuna = $lisatiedot;
		$paatetty_luvan_kohdeDTO->Poiminta_ajankohdat = $poim_aj;

		return $paatetty_luvan_kohdeDTO;

	}

	function paivita_paatetyn_luvan_kohteen_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Paatetty_luvan_kohde SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Paatetty_luvan_kohde SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}

	function hae_paatetyn_aineiston_paatetyt_luvan_kohteet($fk_paatetty_aineisto){

		$query = "SELECT * FROM Paatetty_luvan_kohde WHERE FK_Paatetty_aineisto=:fk_paatetty_aineisto";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatetty_aineisto' => $fk_paatetty_aineisto));
		$result = $sth->fetchAll();
		$paatetyt_koritDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$paatetty_luvan_kohdeDTO = new Paatetty_luvan_kohdeDTO();
			$paatetty_luvan_kohdeDTO->ID = $result[$i]["ID"];
			$paatetty_luvan_kohdeDTO->Luvan_kohdeDTO = new Luvan_kohdeDTO();
			$paatetty_luvan_kohdeDTO->Luvan_kohdeDTO->ID = $result[$i]["FK_Luvan_kohde"];
			$paatetty_luvan_kohdeDTO->Kohde_tyyppi = $result[$i]["Kohde_tyyppi"];
			$paatetty_luvan_kohdeDTO->Muuttujat_lueteltuna = $result[$i]["Muuttujat_lueteltuna"];
			$paatetty_luvan_kohdeDTO->Poiminta_ajankohdat = $result[$i]["Poiminta_ajankohdat"];
			$paatetyt_koritDTO[$i] = $paatetty_luvan_kohdeDTO;

		}

		return $paatetyt_koritDTO;

	}

	/*
	function paivita_paatetty_kori($lisatiedot, $poiminta_ajankohdat, $paatetty_kori_id){

		$query = "UPDATE Paatetty_kori SET Lisatiedot=:lisatiedot, Poiminta_ajankohdat=:poiminta_ajankohdat WHERE ID=:paatetty_kori_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':lisatiedot' => $lisatiedot, ':poiminta_ajankohdat' => $poiminta_ajankohdat, ':paatetty_kori_id' => $paatetty_kori_id));

	}

	function hae_paatetyn_aineiston_paatetyt_korit($fk_paatetty_aineisto, $joukon_lyhenne){

		$query = "SELECT * FROM Paatetty_kori WHERE FK_Paatetty_aineisto=:fk_paatetty_aineisto AND Joukon_tyypin_koodi=:joukon_lyhenne";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatetty_aineisto' => $fk_paatetty_aineisto, ':joukon_lyhenne' => $joukon_lyhenne));
		$result = $sth->fetchAll();
		$paatetyt_koritDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$paatetty_koriDTO = new Paatetty_koriDTO();
			$paatetty_koriDTO->ID = $result[$i]["ID"];
			$paatetty_koriDTO->RekisteriDTO = new RekisteriDTO();
			$paatetty_koriDTO->RekisteriDTO->ID = $result[$i]["FK_Rekisteri"];
			$paatetty_koriDTO->Joukon_tyypin_koodi = $result[$i]["Joukon_tyypin_koodi"];
			$paatetty_koriDTO->Lisatiedot = $result[$i]["Lisatiedot"];
			$paatetty_koriDTO->Poiminta_ajankohdat = $result[$i]["Poiminta_ajankohdat"];
			$paatetyt_koritDTO[$i] = $paatetty_koriDTO;

		}

		return $paatetyt_koritDTO;

	}
    */
}

?>