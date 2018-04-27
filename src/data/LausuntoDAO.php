<?php
/*
 * FMAS Käyttölupapalvelu
 * Lausunto Data access object
 *
 * Created: 20.10.2016
 */

class LausuntoDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function alusta_lausunto($fk_lausuntopyynto, $julkaistu, $lisaaja){

		$query = "INSERT INTO Lausunto (FK_Lausuntopyynto, Lausunto_julkaistu, Lisaaja) VALUES (:fk_lausuntopyynto, :julkaistu, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lausuntopyynto' => $fk_lausuntopyynto, ':julkaistu' => $julkaistu, ':lisaaja' => $lisaaja));

		return $this->db->lastInsertId();
		
	}

	function lisaa_lausunto_sanallisena($fk_lausuntopyynto, $julkaistu, $lausunnon_muoto_koodi, $sanallinen_kuvaus, $teksti3, $laus_koodi, $lisaaja){

		$query = "INSERT INTO Lausunto (FK_Lausuntopyynto, Lausunto_julkaistu, Lausunnon_muoto_koodi, Sanallinen_kuvaus, Teksti3, Lausunto_koodi, Lisaaja) VALUES (:fk_lausuntopyynto, :julkaistu, :lausunnon_muoto_koodi, :sanallinen_kuvaus, :teksti3, :laus_koodi, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_lausuntopyynto' => $fk_lausuntopyynto, ':julkaistu' => $julkaistu, ':lausunnon_muoto_koodi' => $lausunnon_muoto_koodi, ':sanallinen_kuvaus' => $sanallinen_kuvaus, ':teksti3' => $teksti3, ':laus_koodi' => $laus_koodi, ':lisaaja' => $lisaaja));

	}

	function lisaa_lausunto_pohjana($fk_lausuntopyynto, $julkaistu, $lausunnon_muoto_koodi, $kysymys1, $perustelu1, $kysymys2, $perustelu2, $kysymys3, $perustelu3, $kysymys4, $perustelu4, $kysymys5, $perustelu5, $kysymys6, $perustelu6, $kysymys7, $perustelu7, $kysymys8, $perustelu8, $perustelu9, $perustelu10, $teksti1, $teksti2, $teksti3, $laus_koodi, $kayt_id){

		$query = "INSERT INTO Lausunto (FK_Lausuntopyynto, Lausunto_julkaistu, Lausunnon_muoto_koodi, Kysymys1, Perustelu1, Kysymys2, Perustelu2, Kysymys3, Perustelu3, Kysymys4, Perustelu4, Kysymys5, Perustelu5, Kysymys6, Perustelu6, Kysymys7, Perustelu7, Kysymys8, Perustelu8, Perustelu9, Perustelu10, Teksti1, Teksti2, Teksti3, Lausunto_koodi, Lisaaja) VALUES (:fk_lausuntopyynto, :julkaistu, :lausunnon_muoto_koodi, :kysymys1, :perustelu1, :kysymys2, :perustelu2, :kysymys3, :perustelu3, :kysymys4, :perustelu4, :kysymys5, :perustelu5, :kysymys6, :perustelu6, :kysymys7, :perustelu7, :kysymys8, :perustelu8, :perustelu9, :perustelu10, :teksti1, :teksti2, :teksti3, :laus_koodi, :kayt_id)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_lausuntopyynto' => $fk_lausuntopyynto, ':julkaistu' => $julkaistu, ':lausunnon_muoto_koodi' => $lausunnon_muoto_koodi, ':kysymys1' => $kysymys1, ':perustelu1' => $perustelu1, ':kysymys2' => $kysymys2, ':perustelu2' => $perustelu2, ':kysymys3' => $kysymys3, ':perustelu3' => $perustelu3, ':kysymys4' => $kysymys4, ':perustelu4' => $perustelu4, ':kysymys5' => $kysymys5, ':perustelu5' => $perustelu5, ':kysymys6' => $kysymys6, ':perustelu6' => $perustelu6, ':kysymys7' => $kysymys7, ':perustelu7' => $perustelu7, ':kysymys8' => $kysymys8, ':perustelu8' => $perustelu8, ':perustelu9' => $perustelu9, ':perustelu10' => $perustelu10, ':teksti1' => $teksti1, ':teksti2' => $teksti2, ':teksti3' => $teksti3, ':laus_koodi' => $laus_koodi, ':kayt_id' => $kayt_id));

	}

	function paivita_lausunnon_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Lausunto SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Lausunto SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}

	function paivita_sanallinen_lausunto($lausunto_id, $fk_lausuntopyynto, $lausunto_julkaistu, $lausunnon_muoto_koodi, $sanallinen_kuvaus, $teksti3, $laus_koodi, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Lausunto SET FK_Lausuntopyynto=:fk_lausuntopyynto, Lausunto_julkaistu=:lausunto_julkaistu, Lausunnon_muoto_koodi=:lausunnon_muoto_koodi, Sanallinen_kuvaus=:sanallinen_kuvaus, Teksti3=:teksti3, Lausunto_koodi=:laus_koodi, Muokkaaja=:muokkaaja_id, Muokkauspvm=:nyt WHERE ID=:lausunto_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_lausuntopyynto' => $fk_lausuntopyynto, ':lausunto_julkaistu' => $lausunto_julkaistu, ':lausunnon_muoto_koodi' => $lausunnon_muoto_koodi, ':sanallinen_kuvaus' => $sanallinen_kuvaus, ':teksti3' => $teksti3, ':laus_koodi' => $laus_koodi, ':muokkaaja_id' => $muokkaaja, ':nyt' => $nyt, ':lausunto_id' => $lausunto_id));

	}

	function paivita_lausuntopohja($lausunto_id, $fk_lausuntopyynto, $lausunto_julkaistu, $lausunnon_muoto_koodi, $kysymys1, $perustelu1, $kysymys2, $perustelu2, $kysymys3, $perustelu3, $kysymys4, $perustelu4, $kysymys5, $perustelu5, $kysymys6, $perustelu6, $kysymys7, $perustelu7, $kysymys8, $perustelu8, $perustelu9, $perustelu10, $teksti1, $teksti2, $teksti3, $laus_koodi, $muokkaaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Lausunto SET FK_Lausuntopyynto=:fk_lausuntopyynto, Lausunto_julkaistu=:lausunto_julkaistu, Lausunnon_muoto_koodi=:lausunnon_muoto_koodi, Kysymys1=:kysymys1, Perustelu1=:perustelu1, Kysymys2=:kysymys2, Perustelu2=:perustelu2, Kysymys3=:kysymys3, Perustelu3=:perustelu3, Kysymys4=:kysymys4, Perustelu4=:perustelu4, Kysymys5=:kysymys5, Perustelu5=:perustelu5, Kysymys6=:kysymys6, Perustelu6=:perustelu6, Kysymys7=:kysymys7, Perustelu7=:perustelu7, Kysymys8=:kysymys8, Perustelu8=:perustelu8, Perustelu9=:perustelu9, Perustelu10=:perustelu10, Teksti1=:teksti1, Teksti2=:teksti2, Teksti3=:teksti3, Lausunto_koodi=:laus_koodi, Muokkaaja=:muokkaaja_id, Muokkauspvm=:nyt WHERE ID=:lausunto_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_lausuntopyynto' => $fk_lausuntopyynto, ':lausunto_julkaistu' => $lausunto_julkaistu, ':lausunnon_muoto_koodi' => $lausunnon_muoto_koodi, ':kysymys1' => $kysymys1, ':perustelu1' => $perustelu1, ':kysymys2' => $kysymys2, ':perustelu2' => $perustelu2, ':kysymys3' => $kysymys3, ':perustelu3' => $perustelu3, ':kysymys4' => $kysymys4, ':perustelu4' => $perustelu4, ':kysymys5' => $kysymys5, ':perustelu5' => $perustelu5, ':kysymys6' => $kysymys6, ':perustelu6' => $perustelu6, ':kysymys7' => $kysymys7, ':perustelu7' => $perustelu7, ':kysymys8' => $kysymys8, ':perustelu8' => $perustelu8, ':perustelu9' => $perustelu9, ':perustelu10' => $perustelu10, ':teksti1' => $teksti1, ':teksti2' => $teksti2, ':teksti3' => $teksti3, ':laus_koodi' => $laus_koodi, ':muokkaaja_id' => $muokkaaja_id, ':nyt' => $nyt, ':lausunto_id' => $lausunto_id));

	}

	function muuta_lausunnon_nakyvyys($lausunto_id, $naytetaankoLausuntoHakijoille){

		$query = "UPDATE Lausunto SET Naytetaanko_hakijoille=:naytetaankoLausuntoHakijoille WHERE ID=:lausunto_id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':naytetaankoLausuntoHakijoille' => $naytetaankoLausuntoHakijoille, ':lausunto_id' => $lausunto_id));

	}

	function hae_lausunto($id){

		$query = "SELECT * FROM Lausunto WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$lausuntoDTO = new LausuntoDTO();
		$lausuntoDTO->ID = $result["ID"];
		$lausuntoDTO->LausuntopyyntoDTO = new LausuntopyyntoDTO();
		$lausuntoDTO->LausuntopyyntoDTO->ID = $result["FK_Lausuntopyynto"];
		$lausuntoDTO->LomakeDTO = new LomakeDTO();
		$lausuntoDTO->LomakeDTO->ID = $result["FK_Lomake"];
		$lausuntoDTO->Lausunto_julkaistu = $result["Lausunto_julkaistu"];
		$lausuntoDTO->Sanallinen_kuvaus = $result["Sanallinen_kuvaus"];
		$lausuntoDTO->Lausunto_koodi = $result["Lausunto_koodi"];
		$lausuntoDTO->Johtopaatoksen_perustelut = $result["Johtopaatoksen_perustelut"];
		$lausuntoDTO->Naytetaanko_hakijoille = $result["Naytetaanko_hakijoille"];
		$lausuntoDTO->Ehdollinen_puoltaminen = $result["Ehdollinen_puoltaminen"];		
		$lausuntoDTO->Julkisuusluokka = $result["Julkisuusluokka"];
		$lausuntoDTO->Salassapitoaika = $result["Salassapitoaika"];
		$lausuntoDTO->Salassapitoperuste = $result["Salassapitoperuste"];
		$lausuntoDTO->Suojaustaso = $result["Suojaustaso"];
		$lausuntoDTO->Henkilotietoja = $result["Henkilotietoja"];
		$lausuntoDTO->Sailytysajan_pituus = $result["Sailytysajan_pituus"];
		$lausuntoDTO->Sailytysajan_peruste = $result["Sailytysajan_peruste"];				
		$lausuntoDTO->Lisaaja = $result["Lisaaja"];
		$lausuntoDTO->Lisayspvm = $result["Lisayspvm"];
		$lausuntoDTO->Muokkaaja = $result["Muokkaaja"];
		$lausuntoDTO->Muokkauspvm = $result["Muokkauspvm"];		

		return $lausuntoDTO;

	}

	function hae_lausuntopyynnolle_lausunto($fk_lausuntopyynto){

		$query = "SELECT * FROM Lausunto WHERE FK_Lausuntopyynto=:fk_lausuntopyynto";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lausuntopyynto' => $fk_lausuntopyynto));
		$result = $sth->fetch();

		$lausuntoDTO = new LausuntoDTO();
		$lausuntoDTO->ID = $result["ID"];
		$lausuntoDTO->LausuntopyyntoDTO = new LausuntopyyntoDTO();
		$lausuntoDTO->LausuntopyyntoDTO->ID = $result["FK_Lausuntopyynto"];
		$lausuntoDTO->LomakeDTO = new LomakeDTO();
		$lausuntoDTO->LomakeDTO->ID = $result["FK_Lomake"];
		$lausuntoDTO->Lausunto_julkaistu = $result["Lausunto_julkaistu"];
		$lausuntoDTO->Sanallinen_kuvaus = $result["Sanallinen_kuvaus"];
		$lausuntoDTO->Johtopaatoksen_perustelut = $result["Johtopaatoksen_perustelut"];
		$lausuntoDTO->Lausunto_koodi = $result["Lausunto_koodi"];
		$lausuntoDTO->Naytetaanko_hakijoille = $result["Naytetaanko_hakijoille"];
		$lausuntoDTO->Lisaaja = $result["Lisaaja"];
		$lausuntoDTO->Lisayspvm = $result["Lisayspvm"];

		return $lausuntoDTO;

	}

	function hae_lausuntopyynnolle_julkaistu_lausunto($fk_lausuntopyynto){

		$query = "SELECT * FROM Lausunto WHERE FK_Lausuntopyynto=:fk_lausuntopyynto AND Lausunto_julkaistu=1";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lausuntopyynto' => $fk_lausuntopyynto));
		$result = $sth->fetch();

		if(isset($result["ID"])){

			$lausuntoDTO = new LausuntoDTO();
			$lausuntoDTO->ID = $result["ID"];
			$lausuntoDTO->LausuntopyyntoDTO = new LausuntopyyntoDTO();
			$lausuntoDTO->LausuntopyyntoDTO->ID = $result["FK_Lausuntopyynto"];
			$lausuntoDTO->Lausunto_julkaistu = $result["Lausunto_julkaistu"];
			$lausuntoDTO->Lausunnon_muoto_koodi = $result["Lausunnon_muoto_koodi"];
			$lausuntoDTO->Sanallinen_kuvaus = $result["Sanallinen_kuvaus"];
			$lausuntoDTO->Johtopaatoksen_perustelut = $result["Johtopaatoksen_perustelut"];
			$lausuntoDTO->Lausunto_koodi = $result["Lausunto_koodi"];
			$lausuntoDTO->Naytetaanko_hakijoille = $result["Naytetaanko_hakijoille"];
			$lausuntoDTO->Ehdollinen_puoltaminen = $result["Ehdollinen_puoltaminen"];
			$lausuntoDTO->Lisaaja = $result["Lisaaja"];
			$lausuntoDTO->Lisayspvm = $result["Lisayspvm"];

			return $lausuntoDTO;

		}

		return false;

	}

	function hae_lausuntopyynnolle_keskenerainen_lausunto($fk_lausuntopyynto){

		$query = "SELECT * FROM Lausunto WHERE FK_Lausuntopyynto=:fk_lausuntopyynto AND Lausunto_julkaistu=0";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lausuntopyynto' => $fk_lausuntopyynto));
		$result = $sth->fetch();

		$lausuntoDTO = new LausuntoDTO();
		$lausuntoDTO->ID = $result["ID"];
		$lausuntoDTO->LausuntopyyntoDTO = new LausuntopyyntoDTO();
		$lausuntoDTO->LausuntopyyntoDTO->ID = $result["FK_Lausuntopyynto"];
		$lausuntoDTO->Lausunto_julkaistu = $result["Lausunto_julkaistu"];
		$lausuntoDTO->Lausunnon_muoto_koodi = $result["Lausunnon_muoto_koodi"];
		$lausuntoDTO->Sanallinen_kuvaus = $result["Sanallinen_kuvaus"];
		$lausuntoDTO->Kysymys1 = $result["Kysymys1"];
		$lausuntoDTO->Perustelu1 = $result["Perustelu1"];
		$lausuntoDTO->Kysymys2 = $result["Kysymys2"];
		$lausuntoDTO->Perustelu2 = $result["Perustelu2"];
		$lausuntoDTO->Kysymys3 = $result["Kysymys3"];
		$lausuntoDTO->Perustelu3 = $result["Perustelu3"];
		$lausuntoDTO->Kysymys4 = $result["Kysymys4"];
		$lausuntoDTO->Perustelu4 = $result["Perustelu4"];
		$lausuntoDTO->Kysymys5 = $result["Kysymys5"];
		$lausuntoDTO->Perustelu5 = $result["Perustelu5"];
		$lausuntoDTO->Kysymys6 = $result["Kysymys6"];
		$lausuntoDTO->Perustelu6 = $result["Perustelu6"];
		$lausuntoDTO->Kysymys7 = $result["Kysymys7"];
		$lausuntoDTO->Perustelu7 = $result["Perustelu7"];
		$lausuntoDTO->Kysymys8 = $result["Kysymys8"];
		$lausuntoDTO->Perustelu8 = $result["Perustelu8"];
		$lausuntoDTO->Perustelu9 = $result["Perustelu9"];
		$lausuntoDTO->Perustelu10 = $result["Perustelu10"];
		$lausuntoDTO->Teksti1 = $result["Teksti1"];
		$lausuntoDTO->Teksti2 = $result["Teksti2"];
		$lausuntoDTO->Teksti3 = $result["Teksti3"];
		$lausuntoDTO->Lausunto_koodi = $result["Lausunto_koodi"];
		$lausuntoDTO->Naytetaanko_hakijoille = $result["Naytetaanko_hakijoille"];
		$lausuntoDTO->Lisaaja = $result["Lisaaja"];
		$lausuntoDTO->Lisayspvm = $result["Lisayspvm"];

		return $lausuntoDTO;

	}

	function hae_lausuntopyynto_tutkijalle($fk_lausuntopyynto){

		$query = "SELECT * FROM Lausunto WHERE FK_Lausuntopyynto=:fk_lausuntopyynto AND Naytetaanko_hakijoille=:kylla";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lausuntopyynto' => $fk_lausuntopyynto, ':kylla' => 1));
		$result = $sth->fetch();

		$lausuntoDTO = new LausuntoDTO();
		$lausuntoDTO->ID = $result["ID"];
		$lausuntoDTO->LausuntopyyntoDTO = new LausuntopyyntoDTO();
		$lausuntoDTO->LausuntopyyntoDTO->ID = $result["FK_Lausuntopyynto"];
		$lausuntoDTO->Lausunto_julkaistu = $result["Lausunto_julkaistu"];
		$lausuntoDTO->Lausunnon_muoto_koodi = $result["Lausunnon_muoto_koodi"];
		$lausuntoDTO->Sanallinen_kuvaus = $result["Sanallinen_kuvaus"];
		$lausuntoDTO->Kysymys1 = $result["Kysymys1"];
		$lausuntoDTO->Perustelu1 = $result["Perustelu1"];
		$lausuntoDTO->Kysymys2 = $result["Kysymys2"];
		$lausuntoDTO->Perustelu2 = $result["Perustelu2"];
		$lausuntoDTO->Kysymys3 = $result["Kysymys3"];
		$lausuntoDTO->Perustelu3 = $result["Perustelu3"];
		$lausuntoDTO->Kysymys4 = $result["Kysymys4"];
		$lausuntoDTO->Perustelu4 = $result["Perustelu4"];
		$lausuntoDTO->Kysymys5 = $result["Kysymys5"];
		$lausuntoDTO->Perustelu5 = $result["Perustelu5"];
		$lausuntoDTO->Kysymys6 = $result["Kysymys6"];
		$lausuntoDTO->Perustelu6 = $result["Perustelu6"];
		$lausuntoDTO->Kysymys7 = $result["Kysymys7"];
		$lausuntoDTO->Perustelu7 = $result["Perustelu7"];
		$lausuntoDTO->Kysymys8 = $result["Kysymys8"];
		$lausuntoDTO->Perustelu8 = $result["Perustelu8"];
		$lausuntoDTO->Perustelu9 = $result["Perustelu9"];
		$lausuntoDTO->Perustelu10 = $result["Perustelu10"];
		$lausuntoDTO->Teksti1 = $result["Teksti1"];
		$lausuntoDTO->Teksti2 = $result["Teksti2"];
		$lausuntoDTO->Teksti3 = $result["Teksti3"];
		$lausuntoDTO->Lausunto_koodi = $result["Lausunto_koodi"];
		$lausuntoDTO->Naytetaanko_hakijoille = $result["Naytetaanko_hakijoille"];
		$lausuntoDTO->Lisaaja = $result["Lisaaja"];
		$lausuntoDTO->Lisayspvm = $result["Lisayspvm"];

		return $lausuntoDTO;

	}

}