<?php
/*
 * FMAS Käyttölupapalvelu
 * Hakija Data access object
 *
 * Created: 28.9.2016
 */

class HakijaDAO {

	public $db;

	function __construct($db) {
		$this->db = $db;
	}

	function luo_hakija_kayttajan_tiedoista($kayttajaDTO, $haetaan_kayttolupaa){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$kayt_id = $kayttajaDTO->ID;
		$sukunimi = $kayttajaDTO->Sukunimi;
		$etunimi = $kayttajaDTO->Etunimi;
		$sahkopostiosoite = $kayttajaDTO->Sahkopostiosoite;

		$query = "INSERT INTO Hakija (FK_Kayttaja, Sukunimi, Etunimi, Sahkopostiosoite, Haetaanko_kayttolupaa, Kutsuttu_jaseneksi, Lisaaja) VALUES (:kayt_id, :sukunimi, :etunimi, :sahkopostiosoite, :haetaan_kayttolupaa, :nyt, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':kayt_id' => $kayt_id, ':sukunimi' => $sukunimi, ':etunimi' => $etunimi, ':sahkopostiosoite' => $sahkopostiosoite, ':haetaan_kayttolupaa' => $haetaan_kayttolupaa, ':nyt' => $nyt, ':lisaaja' => $kayt_id));

		$hakijaDTO = new HakijaDTO();
		$hakijaDTO->ID = $this->db->lastInsertId();
		$hakijaDTO->KayttajaDTO = $kayttajaDTO;
		$hakijaDTO->Sukunimi = $sukunimi;
		$hakijaDTO->Etunimi = $etunimi;
		$hakijaDTO->Sahkopostiosoite = $sahkopostiosoite;
		$hakijaDTO->Haetaanko_kayttolupaa = 1;
		$hakijaDTO->Kutsuttu_jaseneksi = $nyt;
		$hakijaDTO->Lisaaja = $kayt_id;

		return $hakijaDTO;

	}

	function luo_hakija($fk_kayttaja, $sukunimi, $etunimi, $sahkopostiosoite, $organisaatio, $oppiarvo, $haetaanko_kayttolupaa, $nyt, $jasen, $varmenne, $kayt_id){

		$query = "INSERT INTO Hakija (FK_Kayttaja, Sukunimi, Etunimi, Sahkopostiosoite, Organisaatio, Oppiarvo, Haetaanko_kayttolupaa, Kutsuttu_jaseneksi, Jasen, Varmenne, Lisaaja) VALUES (:fk_kayttaja, :sukunimi, :etunimi, :sahkopostiosoite, :organisaatio, :oppiarvo, :haetaanko_kayttolupaa, :nyt, :jasen, :varmenne, :kayt_id)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja,':sukunimi' => $sukunimi,':etunimi' => $etunimi,':sahkopostiosoite' => $sahkopostiosoite,':organisaatio' => $organisaatio,':oppiarvo' => $oppiarvo, ':haetaanko_kayttolupaa' => $haetaanko_kayttolupaa, ':nyt' => $nyt, ':jasen' => $jasen, ':varmenne' => $varmenne, ':kayt_id' => $kayt_id));

		$hakijaDTO = new HakijaDTO();
		$hakijaDTO->ID = $this->db->lastInsertId();
		$hakijaDTO->KayttajaDTO = new KayttajaDTO();
		$hakijaDTO->KayttajaDTO->ID = $fk_kayttaja;
		$hakijaDTO->Sukunimi = $sukunimi;
		$hakijaDTO->Etunimi = $etunimi;
		$hakijaDTO->Sahkopostiosoite = $sahkopostiosoite;
		$hakijaDTO->Haetaanko_kayttolupaa = $haetaanko_kayttolupaa;
		$hakijaDTO->Kutsuttu_jaseneksi = $nyt;
		$hakijaDTO->Lisaaja = $kayt_id;

		return $hakijaDTO;

	}

	function paivita_hakijan_tieto($id, $kentan_nimi, $kentan_arvo){

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Hakija SET $kentan_nimi=$kentan_arvo WHERE ID=$id";
		} else {
			$q = "UPDATE Hakija SET $kentan_nimi='$kentan_arvo' WHERE ID=$id";
		}

		$this->db->query($q);

	}

	function vahvista_hakijan_jasenyys($hakija_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Hakija SET Jasen=:nyt WHERE ID=:hakija_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':nyt' => $nyt, ':hakija_id' => $hakija_id));

	}

	function paivita_hakijan_tiedot_ja_vahvista_jasenyys($jasen, $sukunimi, $etunimi, $sahkopostiosoite, $organisaatio, $oppiarvo, $haetaanko_kayttolupaa, $hakija_id){
		$query = "UPDATE Hakija SET Jasen=:jasen, Sukunimi=:sukunimi, Etunimi=:etunimi, Sahkopostiosoite=:sahkopostiosoite, Organisaatio=:organisaatio, Oppiarvo=:oppiarvo, Haetaanko_kayttolupaa=:haetaanko_kayttolupaa WHERE ID=:hakija_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':jasen' => $jasen, ':sukunimi' => $sukunimi, ':etunimi' => $etunimi, ':sahkopostiosoite' => $sahkopostiosoite, ':organisaatio' => $organisaatio, ':oppiarvo' => $oppiarvo, ':haetaanko_kayttolupaa' => $haetaanko_kayttolupaa, ':hakija_id' => $hakija_id));
	}

	function paivita_hakijan_tiedot($sukunimi, $etunimi, $sahkopostiosoite, $organisaatio, $oppiarvo, $haetaanko_kayttolupaa, $hakija_id){
		$query = "UPDATE Hakija SET Sukunimi=:sukunimi, Etunimi=:etunimi, Sahkopostiosoite=:sahkopostiosoite, Organisaatio=:organisaatio, Oppiarvo=:oppiarvo, Haetaanko_kayttolupaa=:haetaanko_kayttolupaa WHERE ID=:hakija_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':sukunimi' => $sukunimi, ':etunimi' => $etunimi, ':sahkopostiosoite' => $sahkopostiosoite, ':organisaatio' => $organisaatio, ':oppiarvo' => $oppiarvo, ':haetaanko_kayttolupaa' => $haetaanko_kayttolupaa, ':hakija_id' => $hakija_id));
	}

	function etsi_hakijan_nimella($nimi1, $nimi2){
		
		$query = "SELECT * FROM Hakija WHERE Etunimi LIKE :nimi1a OR Etunimi LIKE :nimi2a OR Sukunimi LIKE :nimi1b OR Sukunimi LIKE :nimi2b";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':nimi1a' => $nimi1, ':nimi2a' => $nimi2, ':nimi1b' => $nimi1, ':nimi2b' => $nimi2));
		$result = $sth->fetchAll();
		$hakijatDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$hakijaDTO = new HakijaDTO();
			$hakijaDTO->ID = $result[$i]["ID"];
			$hakijatDTO[$i] = $hakijaDTO;
		}

		return $hakijatDTO;		
		
	}
	
	function hae_hakijan_tiedot($id){

		$query = "SELECT * FROM Hakija WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$hakija = $sth->fetch();

		$hakijaDTO = new HakijaDTO();
		$hakijaDTO->ID = $hakija["ID"];
		$hakijaDTO->KayttajaDTO = new KayttajaDTO();
		$hakijaDTO->KayttajaDTO->ID = $hakija["FK_Kayttaja"];
		$hakijaDTO->Sahkopostiosoite = $hakija["Sahkopostiosoite"];
		$hakijaDTO->Etunimi = $hakija["Etunimi"];
		$hakijaDTO->Sukunimi = $hakija["Sukunimi"];
		$hakijaDTO->Organisaatio = $hakija["Organisaatio"];
		$hakijaDTO->Oppiarvo = $hakija["Oppiarvo"];
		$hakijaDTO->Osoite = $hakija["Osoite"];
		$hakijaDTO->Maa = $hakija["Maa"];
		$hakijaDTO->Puhelin = $hakija["Puhelin"];
		$hakijaDTO->Haetaanko_kayttolupaa = $hakija["Haetaanko_kayttolupaa"];
		$hakijaDTO->Kutsuttu_jaseneksi = $hakija["Kutsuttu_jaseneksi"];
		$hakijaDTO->Jasen = $hakija["Jasen"];
		$hakijaDTO->Lisaaja = $hakija["Lisaaja"];
		$hakijaDTO->Lisayspvm = $hakija["Lisayspvm"];

		return $hakijaDTO;

	}
	
	function hae_hakemusversion_rekisteritietojen_kasittelyoikeutta_hakevat_kayttajat($fk_hakemusversio){

		$query = "SELECT Hakija.*, Hakijan_rooli.Hakijan_roolin_koodi, Hakijan_rooli.Muun_roolin_selite FROM Hakija INNER JOIN Hakijan_rooli ON Hakija.ID = Hakijan_rooli.FK_Hakija WHERE ((Hakijan_rooli.FK_Hakemusversio=:fk_hakemusversio) AND (Hakija.Haetaanko_kayttolupaa=1)) ORDER BY Hakija.Sukunimi, Hakija.Etunimi, Hakija.ID, Hakijan_rooli.Hakijan_roolin_koodi";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));
		$result = $sth->fetchAll();
		$hakijatDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakijaDTO = new HakijaDTO();
			$hakijaDTO->ID = $result[$i]["ID"];
			$hakijaDTO->KayttajaDTO = new KayttajaDTO();
			$hakijaDTO->KayttajaDTO->ID = $result[$i]["FK_Kayttaja"];
			$hakijaDTO->Sukunimi = $result[$i]["Sukunimi"];
			$hakijaDTO->Etunimi = $result[$i]["Etunimi"];
			$hakijaDTO->Sahkopostiosoite = $result[$i]["Sahkopostiosoite"];
			$hakijaDTO->Organisaatio = $result[$i]["Organisaatio"];
			$hakijaDTO->Osoite = $result[$i]["Osoite"];
			$hakijaDTO->Maa = $result[$i]["Maa"];
			$hakijaDTO->Oppiarvo = $result[$i]["Oppiarvo"];
			$hakijaDTO->Haetaanko_kayttolupaa = $result[$i]["Haetaanko_kayttolupaa"];
			$hakijaDTO->Kutsuttu_jaseneksi = $result[$i]["Kutsuttu_jaseneksi"];
			$hakijaDTO->Jasen = $result[$i]["Jasen"];
			$hakijaDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakijaDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakijaDTO->Hakijan_rooliDTO = new Hakijan_rooliDTO();
			$hakijaDTO->Hakijan_rooliDTO->Hakijan_roolin_koodi = $result[$i]["Hakijan_roolin_koodi"];
			$hakijaDTO->Hakijan_rooliDTO->Muun_roolin_selite = $result[$i]["Muun_roolin_selite"];
			$hakijatDTO[$i] = $hakijaDTO;

		}

		return $hakijatDTO;

	}

	function hae_hakemusversion_kayttajat_jotka_eivat_hae_rekisteritietojen_kasittelyoikeutta($fk_hakemusversio){

		$query = "SELECT Hakija.*, Hakijan_rooli.Hakijan_roolin_koodi, Hakijan_rooli.Muun_roolin_selite FROM Hakija INNER JOIN Hakijan_rooli ON Hakija.ID = Hakijan_rooli.FK_Hakija WHERE ((Hakijan_rooli.FK_Hakemusversio=:fk_hakemusversio) AND (Hakija.Haetaanko_kayttolupaa=0)) ORDER BY Hakija.Sukunimi, Hakija.Etunimi, Hakija.ID, Hakijan_rooli.Hakijan_roolin_koodi";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));
		$result = $sth->fetchAll();
		$hakijatDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakijaDTO = new HakijaDTO();
			$hakijaDTO->ID = $result[$i]["ID"];
			$hakijaDTO->KayttajaDTO = new KayttajaDTO();
			$hakijaDTO->KayttajaDTO->ID = $result[$i]["FK_Kayttaja"];
			$hakijaDTO->Sukunimi = $result[$i]["Sukunimi"];
			$hakijaDTO->Etunimi = $result[$i]["Etunimi"];
			$hakijaDTO->Sahkopostiosoite = $result[$i]["Sahkopostiosoite"];
			$hakijaDTO->Organisaatio = $result[$i]["Organisaatio"];
			$hakijaDTO->Oppiarvo = $result[$i]["Oppiarvo"];
			$hakijaDTO->Haetaanko_kayttolupaa = $result[$i]["Haetaanko_kayttolupaa"];
			$hakijaDTO->Kutsuttu_jaseneksi = $result[$i]["Kutsuttu_jaseneksi"];
			$hakijaDTO->Jasen = $result[$i]["Jasen"];
			$hakijaDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakijaDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakijaDTO->Hakijan_rooliDTO = new Hakijan_rooliDTO();
			$hakijaDTO->Hakijan_rooliDTO->Hakijan_roolin_koodi = $result[$i]["Hakijan_roolin_koodi"];
			$hakijaDTO->Hakijan_rooliDTO->Muun_roolin_selite = $result[$i]["Muun_roolin_selite"];
			$hakijatDTO[$i] = $hakijaDTO;

		}

		return $hakijatDTO;

	}

	function hae_hakemusversion_hakija($fk_hakemusversio, $fk_kayttaja){

		$query = "SELECT Hakija.* FROM Hakija INNER JOIN Hakijan_rooli ON Hakija.ID = Hakijan_rooli.FK_Hakija WHERE ((Hakijan_rooli.FK_Hakemusversio=:fk_hakemusversio) AND (Hakija.FK_Kayttaja=:fk_kayttaja))";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetch();

		$hakijaDTO = new HakijaDTO();
		$hakijaDTO->ID = $result["ID"];
		$hakijaDTO->KayttajaDTO = new KayttajaDTO();
		$hakijaDTO->KayttajaDTO->ID = $result["FK_Kayttaja"];
		$hakijaDTO->Sukunimi = $result["Sukunimi"];
		$hakijaDTO->Etunimi = $result["Etunimi"];
		$hakijaDTO->Sahkopostiosoite = $result["Sahkopostiosoite"];
		$hakijaDTO->Organisaatio = $result["Organisaatio"];
		$hakijaDTO->Oppiarvo = $result["Oppiarvo"];
		$hakijaDTO->Haetaanko_kayttolupaa = $result["Haetaanko_kayttolupaa"];
		$hakijaDTO->Kutsuttu_jaseneksi = $result["Kutsuttu_jaseneksi"];
		$hakijaDTO->Jasen = $result["Jasen"];
		$hakijaDTO->Lisaaja = $result["Lisaaja"];
		$hakijaDTO->Lisayspvm = $result["Lisayspvm"];

		return $hakijaDTO;

	}

	function hae_kayttajan_hakijatiedot($fk_kayttaja, $hakija_id){

		$query = "SELECT * FROM Hakija WHERE FK_Kayttaja=:fk_kayttaja AND ID=:hakija_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja, ':hakija_id' => $hakija_id));
		$result = $sth->fetch();
		$hakijaDTO = new HakijaDTO();

		if(isset($result["ID"]) && !is_null($result["ID"])){

			$hakijaDTO->ID = $result["ID"];
			$hakijaDTO->KayttajaDTO = new KayttajaDTO();
			$hakijaDTO->KayttajaDTO->ID = $result["FK_Kayttaja"];
			$hakijaDTO->Sukunimi = $result["Sukunimi"];
			$hakijaDTO->Etunimi = $result["Etunimi"];
			$hakijaDTO->Sahkopostiosoite = $result["Sahkopostiosoite"];
			$hakijaDTO->Organisaatio = $result["Organisaatio"];
			$hakijaDTO->Oppiarvo = $result["Oppiarvo"];
			$hakijaDTO->Haetaanko_kayttolupaa = $result["Haetaanko_kayttolupaa"];
			$hakijaDTO->Kutsuttu_jaseneksi = $result["Kutsuttu_jaseneksi"];
			$hakijaDTO->Jasen = $result["Jasen"];
			$hakijaDTO->Lisaaja = $result["Lisaaja"];
			$hakijaDTO->Lisayspvm = $result["Lisayspvm"];

		}

		return $hakijaDTO;

	}

	function hae_kayttajaan_ja_tunnukseen_liitetyt_hakijat($sahkopostiosoite, $fk_kayttaja){

		$query = "SELECT * FROM Hakija WHERE Sahkopostiosoite=:sahkopostiosoite AND FK_Kayttaja=:fk_kayttaja";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':sahkopostiosoite' => $sahkopostiosoite, ':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetchAll();
		$hakijatDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$hakijaDTO = new HakijaDTO();
			$hakijaDTO->ID = $result[$i]["ID"];
			$hakijaDTO->KayttajaDTO = new KayttajaDTO();
			$hakijaDTO->KayttajaDTO->ID = $result[$i]["FK_Kayttaja"];
			$hakijaDTO->Sukunimi = $result[$i]["Sukunimi"];
			$hakijaDTO->Etunimi = $result[$i]["Etunimi"];
			$hakijaDTO->Sahkopostiosoite = $result[$i]["Sahkopostiosoite"];
			$hakijaDTO->Organisaatio = $result[$i]["Organisaatio"];
			$hakijaDTO->Oppiarvo = $result[$i]["Oppiarvo"];
			$hakijaDTO->Haetaanko_kayttolupaa = $result[$i]["Haetaanko_kayttolupaa"];
			$hakijaDTO->Kutsuttu_jaseneksi = $result[$i]["Kutsuttu_jaseneksi"];
			$hakijaDTO->Jasen = $result[$i]["Jasen"];
			$hakijaDTO->Varmenne = $result[$i]["Varmenne"];
			$hakijaDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakijaDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakijaDTO->Hakijan_rooliDTO = new Hakijan_rooliDTO();
			if (isset($result[$i]["Hakijan_roolin_koodi"]))
				$hakijaDTO->Hakijan_rooliDTO->Hakijan_roolin_koodi = $result[$i]["Hakijan_roolin_koodi"];
			$hakijatDTO[$i] = $hakijaDTO;

		}

		return $hakijatDTO;

	}

	function hakemusversiosta_loytyi_vastuullinen_johtaja($fk_hakemusversio){

		$query = "SELECT Hakija.* FROM Hakija INNER JOIN Hakijan_rooli ON Hakija.ID = Hakijan_rooli.FK_Hakija WHERE ((Hakijan_rooli.FK_Hakemusversio=:fk_hakemusversio) AND (Hakijan_rooli.Hakijan_roolin_koodi='rooli_vast'))";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio));
		$result = $sth->fetch();
		$hakijaDTO = new HakijaDTO();

		if(isset($result["ID"]) && !is_null($result["ID"])){

			$hakijaDTO->ID = $result["ID"];
			$hakijaDTO->KayttajaDTO = new KayttajaDTO();
			$hakijaDTO->KayttajaDTO->ID = $result["FK_Kayttaja"];
			$hakijaDTO->Sukunimi = $result["Sukunimi"];
			$hakijaDTO->Etunimi = $result["Etunimi"];
			$hakijaDTO->Sahkopostiosoite = $result["Sahkopostiosoite"];
			$hakijaDTO->Organisaatio = $result["Organisaatio"];
			$hakijaDTO->Oppiarvo = $result["Oppiarvo"];
			$hakijaDTO->Haetaanko_kayttolupaa = $result["Haetaanko_kayttolupaa"];
			$hakijaDTO->Kutsuttu_jaseneksi = $result["Kutsuttu_jaseneksi"];
			$hakijaDTO->Jasen = $result["Jasen"];
			$hakijaDTO->Lisaaja = $result["Lisaaja"];
			$hakijaDTO->Lisayspvm = $result["Lisayspvm"];

		}

		return $hakijaDTO;

	}

	function hae_kayttajan_hakijat($fk_kayttaja){

		$query = "SELECT * FROM Hakija WHERE FK_Kayttaja=:fk_kayttaja";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja));
		$result = $sth->fetchAll();
		$hakijatDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$hakijaDTO = new HakijaDTO();
			$hakijaDTO->ID = $result[$i]["ID"];
			$hakijaDTO->KayttajaDTO = new KayttajaDTO();
			$hakijaDTO->KayttajaDTO->ID = $result[$i]["FK_Kayttaja"];
			$hakijaDTO->Sukunimi = $result[$i]["Sukunimi"];
			$hakijaDTO->Etunimi = $result[$i]["Etunimi"];
			$hakijaDTO->Sahkopostiosoite = $result[$i]["Sahkopostiosoite"];
			$hakijaDTO->Organisaatio = $result[$i]["Organisaatio"];
			$hakijaDTO->Oppiarvo = $result[$i]["Oppiarvo"];
			$hakijaDTO->Haetaanko_kayttolupaa = $result[$i]["Haetaanko_kayttolupaa"];
			$hakijaDTO->Kutsuttu_jaseneksi = $result[$i]["Kutsuttu_jaseneksi"];
			$hakijaDTO->Jasen = $result[$i]["Jasen"];
			$hakijaDTO->Varmenne = $result[$i]["Varmenne"];
			$hakijaDTO->Lisaaja = $result[$i]["Lisaaja"];
			$hakijaDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$hakijatDTO[$i] = $hakijaDTO;
		}

		return $hakijatDTO;

	}

	function hakija_loytyy_nimien_perusteella($id, $nimi1="", $nimi2=""){

		$result = $this->db->query("SELECT COUNT(*) FROM (SELECT Etunimi, Sukunimi FROM Hakija WHERE ID=$id AND (Etunimi LIKE '$nimi1' OR Etunimi LIKE '$nimi2' OR Sukunimi LIKE '$nimi1' OR Sukunimi LIKE '$nimi2')) AS A")->fetch();

		if($result["COUNT(*)"] > 0){
			return true;
		}

		return false;

	}

	function poista_hakija($hakija_id){

		$query = "DELETE FROM Hakija WHERE ID=:hakija_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		return $sth->execute(array(':hakija_id' => $hakija_id));

	}

}