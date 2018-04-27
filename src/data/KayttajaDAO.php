<?php
/*
 * FMAS Käyttölupapalvelu
 * Kayttaja Data access object
 *
 * Created: 30.9.2016
 */

class KayttajaDAO {

	public $db;

	function __construct($db) {
		$this->db = $db;
	}

	function luo_kayttaja($sukunimi, $etunimi, $sahkopostiosoite, $hash, $puh_nro, $kieli, $synt_aika, $sahkopostivarmenne, $kayttaja_varmennettu){

		$query = "INSERT INTO Kayttaja (
			Sukunimi,
			Etunimi,
			Syntymaaika,
			Sahkopostiosoite,
			Hash,
			Puhelinnumero,
			Kieli_koodi,
			Sahkopostivarmenne,
			Kayttaja_varmennettu
		) VALUES (
			:sukunimi,
			:etunimi,
			:synt_aika,
			:sahkopostiosoite,
			:hash,
			:puh_nro,
			:kieli,
			:sahkopostivarmenne,
			:kayttaja_varmennettu
		)";

		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

		$par = array(
			':sukunimi' => $sukunimi,
			':etunimi' => $etunimi,
			':synt_aika' => $synt_aika,
			':sahkopostiosoite' => $sahkopostiosoite,
			':hash' => $hash,
			':puh_nro' => $puh_nro,
			':kieli' => $kieli,
			':sahkopostivarmenne' => $sahkopostivarmenne,
			':kayttaja_varmennettu' => $kayttaja_varmennettu
		);

		$sth->execute($par);

		$kayttajaDTO = new KayttajaDTO();
		$kayttajaDTO->ID = $this->db->lastInsertId();

		$kayttajaDTO->Etunimi = $etunimi;
		$kayttajaDTO->Sukunimi = $sukunimi;
		$kayttajaDTO->Sahkopostiosoite = $sahkopostiosoite;
		$kayttajaDTO->Puh_nro = $puh_nro;
		$kayttajaDTO->Kieli = $kieli;

		$kayttajaDTO->Sahkopostivarmenne = $sahkopostivarmenne;
		$kayttajaDTO->Kayttaja_varmennettu = $kayttaja_varmennettu;

		return $kayttajaDTO;

	}

	function register_existing_kayttaja($kayttajaDTO){

		$query = "UPDATE Kayttaja SET
					Etunimi = :etunimi,
					Sukunimi = :sukunimi,
					Syntymaaika = :syntymaaika,
					Puhelinnumero = :puhelinnro,
					Kieli_koodi = :kieli,
					Hash = :hash,
					Sahkopostivarmenne = :sahkopostivarmenne
					WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(
			':id' => $kayttajaDTO->ID,
			':etunimi' => $kayttajaDTO->Etunimi,
			':sukunimi' => $kayttajaDTO->Sukunimi,
			':syntymaaika' => $kayttajaDTO->Syntymaaika,
			':puhelinnro' => $kayttajaDTO->Puhelinnumero,
			':kieli' => $kayttajaDTO->Kieli,
			':hash' => $kayttajaDTO->Salasana_hash,
			':sahkopostivarmenne' => $kayttajaDTO->Sahkopostivarmenne,
		));
	}

	function varmenna_kayttaja($id){
		$query = "UPDATE Kayttaja SET Kayttaja_varmennettu=1 WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':id' => $id));
	}

	function paivita_kayttajan_tiedot($id, $sukunimi, $etunimi, $synt_aika, $kieli, $puh_nro){
		$query = "UPDATE Kayttaja SET Sukunimi=:sukunimi, Etunimi=:etunimi, Syntymaaika=:synt_aika, Kieli_koodi=:kieli, Puhelinnumero=:puh_nro WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':id' => $id, ':sukunimi' => $sukunimi, ':etunimi' => $etunimi, ':synt_aika' => $synt_aika, ':kieli' => $kieli, ':puh_nro' => $puh_nro));
	}

	function hae_kayttajan_tiedot($id){

		$query = "SELECT * FROM Kayttaja WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$kayttajaDTO = new KayttajaDTO();
		$kayttajaDTO->ID = $result["ID"];
		$kayttajaDTO->Sukunimi = $result["Sukunimi"];
		$kayttajaDTO->Etunimi = $result["Etunimi"];
		$kayttajaDTO->Syntymaaika = $result["Syntymaaika"];
		$kayttajaDTO->Kieli_koodi = $result["Kieli_koodi"];
		$kayttajaDTO->Puhelinnumero = $result["Puhelinnumero"];
		$kayttajaDTO->Sahkopostiosoite = $result["Sahkopostiosoite"];
		$kayttajaDTO->Sahkopostivarmenne = $result["Sahkopostivarmenne"];
		$kayttajaDTO->Lisaaja = $result["Lisaaja"];
		$kayttajaDTO->Lisayspvm = $result["Lisayspvm"];
		$kayttajaDTO->Muokkaaja = $result["Muokkaaja"];
		$kayttajaDTO->Muokkauspvm = $result["Muokkauspvm"];

		return $kayttajaDTO;

	}

	function hae_kayttaja_kayttajatunnuksella($sahkopostiosoite){

		$query = "SELECT * FROM Kayttaja WHERE Sahkopostiosoite=:sahkopostiosoite";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':sahkopostiosoite' => $sahkopostiosoite));
		$result = $sth->fetch();

		$kayttajaDTO = new KayttajaDTO();
		$kayttajaDTO->ID = $result["ID"];
		$kayttajaDTO->Sukunimi = $result["Sukunimi"];
		$kayttajaDTO->Etunimi = $result["Etunimi"];
		$kayttajaDTO->Syntymaaika = $result["Syntymaaika"];
		$kayttajaDTO->Sahkopostivarmenne = $result["Sahkopostivarmenne"];
		$kayttajaDTO->Kieli_koodi = $result["Kieli_koodi"];
		$kayttajaDTO->Puhelinnumero = $result["Puhelinnumero"];
		$kayttajaDTO->Sahkopostiosoite = $result["Sahkopostiosoite"];
		$kayttajaDTO->Sahkopostivarmenne = $result["Sahkopostivarmenne"];
		$kayttajaDTO->Kayttaja_varmennettu = $result["Kayttaja_varmennettu"];
		$kayttajaDTO->Lisaaja = $result["Lisaaja"];
		$kayttajaDTO->Lisayspvm = $result["Lisayspvm"];
		$kayttajaDTO->Muokkaaja = $result["Muokkaaja"];
		$kayttajaDTO->Muokkauspvm = $result["Muokkauspvm"];

		return $kayttajaDTO;

	}

	function hae_varmennettu_kayttaja_kayttajatunnuksella($sahkopostiosoite){

		$query = "SELECT * FROM Kayttaja WHERE Sahkopostiosoite=:sahkopostiosoite AND Kayttaja_varmennettu=1";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':sahkopostiosoite' => $sahkopostiosoite));
		$result = $sth->fetch();

		$kayttajaDTO = new KayttajaDTO();
		$kayttajaDTO->ID = $result["ID"];
		$kayttajaDTO->Sukunimi = $result["Sukunimi"];
		$kayttajaDTO->Etunimi = $result["Etunimi"];
		$kayttajaDTO->Salasana_hash = $result["Hash"];
		$kayttajaDTO->Syntymaaika = $result["Syntymaaika"];
		$kayttajaDTO->Sahkopostivarmenne = $result["Sahkopostivarmenne"];
		$kayttajaDTO->Kieli_koodi = $result["Kieli_koodi"];
		$kayttajaDTO->Puhelinnumero = $result["Puhelinnumero"];
		$kayttajaDTO->Sahkopostiosoite = $result["Sahkopostiosoite"];
		$kayttajaDTO->Sahkopostivarmenne = $result["Sahkopostivarmenne"];
		$kayttajaDTO->Lisaaja = $result["Lisaaja"];
		$kayttajaDTO->Lisayspvm = $result["Lisayspvm"];
		$kayttajaDTO->Muokkaaja = $result["Muokkaaja"];
		$kayttajaDTO->Muokkauspvm = $result["Muokkauspvm"];

		return $kayttajaDTO;

	}

	function sahkopostiosoite_loytyy_tietokannasta($sahkopostiosoite){

		$query = "SELECT ID FROM Kayttaja WHERE Sahkopostiosoite=:sahkopostiosoite";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':sahkopostiosoite' => $sahkopostiosoite));

		if($sth->rowCount()==0){
			return false;
		}

		return true;

	}

}