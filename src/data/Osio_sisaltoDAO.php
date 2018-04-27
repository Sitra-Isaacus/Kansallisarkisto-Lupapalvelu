<?php
/*
 * FMAS Käyttölupapalvelu
 * Osio_sisalto Data access object
 *
 * Created: 7.2.2017
 */

class Osio_sisaltoDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function lisaa_hakemusversion_osio_sisalto($fk_hakemusversio, $fk_osio, $osio_tyyppi, $sisalto, $kayt_id){

		if($osio_tyyppi=="textarea" || $osio_tyyppi=="textarea_large" || $osio_tyyppi=="kysymys_ja_textarea_large"){
			$query = "INSERT INTO Osio_sisalto (FK_Hakemusversio, FK_Osio, Sisalto_text, Lisaaja) VALUES (:fk_hakemusversio, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':fk_osio' => $fk_osio, ':sisalto' => $sisalto, ':kayt_id' => $kayt_id));
		}

		if($osio_tyyppi=="date_start" || $osio_tyyppi=="date_end"){

			$sisalto = strtotime($sisalto);
			$sisalto = date('Y-m-d', $sisalto);

			$query = "INSERT INTO Osio_sisalto (FK_Hakemusversio, FK_Osio, Sisalto_date, Lisaaja) VALUES (:fk_hakemusversio, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':fk_osio' => $fk_osio, ':sisalto' => $sisalto, ':kayt_id' => $kayt_id));

		}

		if($osio_tyyppi=="radio" || $osio_tyyppi=="checkbox"){
			$query = "INSERT INTO Osio_sisalto (FK_Hakemusversio, FK_Osio, Sisalto_boolean, Lisaaja) VALUES (:fk_hakemusversio, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':fk_osio' => $fk_osio, ':sisalto' => 1, ':kayt_id' => $kayt_id));
		}

		return false;

	}

	function lisaa_paatoksen_osio_sisalto($fk_paatos, $fk_osio, $osio_tyyppi, $sisalto, $kayt_id){

		if($osio_tyyppi=="textarea" || $osio_tyyppi=="textarea_large" || $osio_tyyppi=="kysymys_ja_textarea_large"){
			$query = "INSERT INTO Osio_sisalto (FK_Paatos, FK_Osio, Sisalto_text, Lisaaja) VALUES (:fk_paatos, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_paatos' => $fk_paatos, ':fk_osio' => $fk_osio, ':sisalto' => $sisalto, ':kayt_id' => $kayt_id));
		}

		if($osio_tyyppi=="date_start" || $osio_tyyppi=="date_end"){

			$sisalto = strtotime($sisalto);
			$sisalto = date('Y-m-d', $sisalto);

			$query = "INSERT INTO Osio_sisalto (FK_Paatos, FK_Osio, Sisalto_date, Lisaaja) VALUES (:fk_paatos, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_paatos' => $fk_paatos, ':fk_osio' => $fk_osio, ':sisalto' => $sisalto, ':kayt_id' => $kayt_id));

		}

		if($osio_tyyppi=="radio" || $osio_tyyppi=="checkbox"){
			$query = "INSERT INTO Osio_sisalto (FK_Paatos, FK_Osio, Sisalto_boolean, Lisaaja) VALUES (:fk_paatos, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_paatos' => $fk_paatos, ':fk_osio' => $fk_osio, ':sisalto' => 1, ':kayt_id' => $kayt_id));
		}

		return false;

	}

	function lisaa_lausunnon_osio_sisalto($fk_lausunto, $fk_osio, $osio_tyyppi, $sisalto, $kayt_id){

		if($osio_tyyppi=="textarea" || $osio_tyyppi=="textarea_large" || $osio_tyyppi=="kysymys_ja_textarea_large"){
			$query = "INSERT INTO Osio_sisalto (FK_Lausunto, FK_Osio, Sisalto_text, Lisaaja) VALUES (:fk_lausunto, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_lausunto' => $fk_lausunto, ':fk_osio' => $fk_osio, ':sisalto' => $sisalto, ':kayt_id' => $kayt_id));
		}

		if($osio_tyyppi=="date_start" || $osio_tyyppi=="date_end"){

			$sisalto = strtotime($sisalto);
			$sisalto = date('Y-m-d', $sisalto);

			$query = "INSERT INTO Osio_sisalto (FK_Lausunto, FK_Osio, Sisalto_date, Lisaaja) VALUES (:fk_lausunto, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_lausunto' => $fk_lausunto, ':fk_osio' => $fk_osio, ':sisalto' => $sisalto, ':kayt_id' => $kayt_id));

		}

		if($osio_tyyppi=="radio" || $osio_tyyppi=="checkbox"){
			$query = "INSERT INTO Osio_sisalto (FK_Lausunto, FK_Osio, Sisalto_boolean, Lisaaja) VALUES (:fk_lausunto, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_lausunto' => $fk_lausunto, ':fk_osio' => $fk_osio, ':sisalto' => 1, ':kayt_id' => $kayt_id));
		}

		return false;

	}

	function lisaa_haettuun_aineistoon_osio_sisalto($fk_haettu_aineisto, $fk_osio, $osio_tyyppi, $sisalto, $kayt_id){

		if($osio_tyyppi=="textarea" || $osio_tyyppi=="textarea_large"){
			$query = "INSERT INTO Osio_sisalto (FK_Haettu_aineisto, FK_Osio, Sisalto_text, Lisaaja) VALUES (:fk_haettu_aineisto, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_haettu_aineisto' => $fk_haettu_aineisto, ':fk_osio' => $fk_osio, ':sisalto' => $sisalto, ':kayt_id' => $kayt_id));
		}

		if($osio_tyyppi=="date_start" || $osio_tyyppi=="date_end"){

			$sisalto = strtotime($sisalto);
			$sisalto = date('Y-m-d', $sisalto);

			$query = "INSERT INTO Osio_sisalto (FK_Haettu_aineisto, FK_Osio, Sisalto_date, Lisaaja) VALUES (:fk_haettu_aineisto, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_haettu_aineisto' => $fk_haettu_aineisto, ':fk_osio' => $fk_osio, ':sisalto' => $sisalto, ':kayt_id' => $kayt_id));

		}

		if($osio_tyyppi=="radio" || $osio_tyyppi=="checkbox"){
			$query = "INSERT INTO Osio_sisalto (FK_Haettu_aineisto, FK_Osio, Sisalto_boolean, Lisaaja) VALUES (:fk_haettu_aineisto, :fk_osio, :sisalto, :kayt_id)";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			return $sth->execute(array(':fk_haettu_aineisto' => $fk_haettu_aineisto, ':fk_osio' => $fk_osio, ':sisalto' => 1, ':kayt_id' => $kayt_id));
		}

		return false;

	}
	
	function lisaa_osion_sisalto_kopiosta($sisalto_viite, $fk_id, $osio_sisaltoDTO_kopio, $lisaaja){
		
		if($sisalto_viite=="FK_Hakemusversio") $query = "INSERT INTO Osio_sisalto (FK_Hakemusversio, FK_Osio, Sisalto_text, Sisalto_date, Sisalto_boolean, Lisaaja) VALUES (:fk_id, :fk_osio, :sisalto_text, :sisalto_date, :sisalto_boolean, :lisaaja)";		
		if($sisalto_viite=="FK_Haettu_aineisto") $query = "INSERT INTO Osio_sisalto (FK_Haettu_aineisto, FK_Osio, Sisalto_text, Sisalto_date, Sisalto_boolean, Lisaaja) VALUES (:fk_id, :fk_osio, :sisalto_text, :sisalto_date, :sisalto_boolean, :lisaaja)";			
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_id' => $fk_id, ':fk_osio' => $osio_sisaltoDTO_kopio->OsioDTO->ID, ':sisalto_text' => $osio_sisaltoDTO_kopio->Sisalto_text, ':sisalto_date' => $osio_sisaltoDTO_kopio->Sisalto_date, ':sisalto_boolean' => $osio_sisaltoDTO_kopio->Sisalto_boolean, ':lisaaja' => $lisaaja));		
		
	}

	function hae_osio_sisalto($id){

		$query = "SELECT * FROM Osio_sisalto WHERE ID=:id AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		$osio_sisaltoDTO = new Osio_sisaltoDTO();
		$osio_sisaltoDTO->HakemusversioDTO = new HakemusversioDTO();
		$osio_sisaltoDTO->HakemusversioDTO->ID = $result["FK_Hakemusversio"];
		$osio_sisaltoDTO->Haettu_aineistoDTO = new Haettu_aineistoDTO();
		$osio_sisaltoDTO->Haettu_aineistoDTO->ID = $result["FK_Haettu_aineisto"];
		$osio_sisaltoDTO->PaatosDTO = new PaatosDTO();
		$osio_sisaltoDTO->PaatosDTO->ID = $result["FK_Paatos"];
		$osio_sisaltoDTO->LausuntoDTO = new LausuntoDTO();
		$osio_sisaltoDTO->LausuntoDTO->ID = $result["FK_Lausunto"];

		return $osio_sisaltoDTO;

	}
	
	function hae_sisalto_tyypin_ja_osion_sisalto($fk_sisalto_viite, $sisalto_tyyppi, $fk_osio){
		
		if($sisalto_tyyppi=="FK_Paatos") $query = "SELECT * FROM Osio_sisalto WHERE FK_Paatos=:fk_sisalto_viite AND FK_Osio=:fk_osio AND Poistaja IS NULL AND Poistopvm IS NULL";
		if($sisalto_tyyppi=="FK_Hakemusversio") $query = "SELECT * FROM Osio_sisalto WHERE FK_Hakemusversio=:fk_sisalto_viite AND FK_Osio=:fk_osio AND Poistaja IS NULL AND Poistopvm IS NULL";
		if($sisalto_tyyppi=="FK_Haettu_aineisto") $query = "SELECT * FROM Osio_sisalto WHERE FK_Haettu_aineisto=:fk_sisalto_viite AND FK_Osio=:fk_osio AND Poistaja IS NULL AND Poistopvm IS NULL";
		if($sisalto_tyyppi=="FK_Lausunto") $query = "SELECT * FROM Osio_sisalto WHERE FK_Lausunto=:fk_sisalto_viite AND FK_Osio=:fk_osio AND Poistaja IS NULL AND Poistopvm IS NULL";
		
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_sisalto_viite' => $fk_sisalto_viite, ':fk_osio' => $fk_osio));
		$result = $sth->fetch();

		$osio_sisaltoDTO = new Osio_sisaltoDTO();
		$osio_sisaltoDTO->ID = $result["ID"];
		$osio_sisaltoDTO->Sisalto_text = $result["Sisalto_text"];
		$osio_sisaltoDTO->Sisalto_boolean = $result["Sisalto_boolean"];
		$osio_sisaltoDTO->Sisalto_date = $result["Sisalto_date"];

		return $osio_sisaltoDTO;		
		
	}

	function hae_haetun_aineiston_ja_osion_sisalto($fk_haettu_aineisto, $fk_osio){

		$query = "SELECT * FROM Osio_sisalto WHERE FK_Haettu_aineisto=:fk_haettu_aineisto AND FK_Osio=:fk_osio AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_haettu_aineisto' => $fk_haettu_aineisto, ':fk_osio' => $fk_osio));
		$result = $sth->fetch();

		$osio_sisaltoDTO = new Osio_sisaltoDTO();
		$osio_sisaltoDTO->ID = $result["ID"];
		$osio_sisaltoDTO->Sisalto_text = $result["Sisalto_text"];
		$osio_sisaltoDTO->Sisalto_boolean = $result["Sisalto_boolean"];
		$osio_sisaltoDTO->Sisalto_date = $result["Sisalto_date"];

		return $osio_sisaltoDTO;

	}	
	
	function hae_osion_sisallot($sisalto_viite, $fk_id){
		
		if($sisalto_viite=="FK_Hakemusversio") $query = "SELECT * FROM Osio_sisalto WHERE FK_Hakemusversio=:fk_id AND Poistaja IS NULL AND Poistopvm IS NULL";
		if($sisalto_viite=="FK_Haettu_aineisto") $query = "SELECT * FROM Osio_sisalto WHERE FK_Haettu_aineisto=:fk_id AND Poistaja IS NULL AND Poistopvm IS NULL";	
		if($sisalto_viite=="FK_Paatos") $query = "SELECT * FROM Osio_sisalto WHERE FK_Paatos=:fk_id AND Poistaja IS NULL AND Poistopvm IS NULL";	
		
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_id' => $fk_id));
		$result = $sth->fetchAll();
		$osioiden_sisallotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$osio_sisaltoDTO = new Osio_sisaltoDTO();
			$osio_sisaltoDTO->ID = $result[$i]["ID"];
			$osio_sisaltoDTO->OsioDTO = new OsioDTO();
			$osio_sisaltoDTO->OsioDTO->ID = $result[$i]["FK_Osio"];
			$osio_sisaltoDTO->Sisalto_text = $result[$i]["Sisalto_text"];
			$osio_sisaltoDTO->Sisalto_boolean = $result[$i]["Sisalto_boolean"];
			$osio_sisaltoDTO->Sisalto_date = $result[$i]["Sisalto_date"];			
			$osioiden_sisallotDTO[$i] = $osio_sisaltoDTO;
			
		}

		return $osioiden_sisallotDTO;
		
	}
	
	function hae_osion_ja_viitetyypin_sisalto($fk_osio, $sisalto_viite, $fk_id){
		
		if($sisalto_viite=="FK_Hakemusversio") $query = "SELECT * FROM Osio_sisalto WHERE FK_Osio=:fk_osio AND FK_Hakemusversio=:fk_id AND Poistaja IS NULL AND Poistopvm IS NULL";
		if($sisalto_viite=="FK_Haettu_aineisto") $query = "SELECT * FROM Osio_sisalto WHERE FK_Osio=:fk_osio AND FK_Haettu_aineisto=:fk_id AND Poistaja IS NULL AND Poistopvm IS NULL";	
		if($sisalto_viite=="FK_Paatos") $query = "SELECT * FROM Osio_sisalto WHERE FK_Osio=:fk_osio AND FK_Paatos=:fk_id AND Poistaja IS NULL AND Poistopvm IS NULL";
		if($sisalto_viite=="FK_Lausunto") $query = "SELECT * FROM Osio_sisalto WHERE FK_Osio=:fk_osio AND FK_Lausunto=:fk_id AND Poistaja IS NULL AND Poistopvm IS NULL";		
		
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_id' => $fk_id, ':fk_osio' => $fk_osio));
		$result = $sth->fetch();
			
		$osio_sisaltoDTO = new Osio_sisaltoDTO();
		$osio_sisaltoDTO->ID = $result["ID"];
		$osio_sisaltoDTO->OsioDTO = new OsioDTO();
		$osio_sisaltoDTO->OsioDTO->ID = $result["FK_Osio"];
		$osio_sisaltoDTO->Sisalto_text = $result["Sisalto_text"];
		$osio_sisaltoDTO->Sisalto_boolean = $result["Sisalto_boolean"];
		$osio_sisaltoDTO->Sisalto_date = $result["Sisalto_date"];			
				
		return $osio_sisaltoDTO;
		
	}	
	
	function hae_hakemusversion_ja_osion_sisalto($fk_hakemusversio, $fk_osio){

		$query = "SELECT * FROM Osio_sisalto WHERE FK_Hakemusversio=:fk_hakemusversio AND FK_Osio=:fk_osio AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_hakemusversio' => $fk_hakemusversio, ':fk_osio' => $fk_osio));
		$result = $sth->fetch();

		$osio_sisaltoDTO = new Osio_sisaltoDTO();
		$osio_sisaltoDTO->ID = $result["ID"];
		$osio_sisaltoDTO->Sisalto_text = $result["Sisalto_text"];
		$osio_sisaltoDTO->Sisalto_boolean = $result["Sisalto_boolean"];
		$osio_sisaltoDTO->Sisalto_date = $result["Sisalto_date"];

		return $osio_sisaltoDTO;

	}

	function hae_paatoksen_ja_osion_sisalto($fk_paatos, $fk_osio){

		$query = "SELECT * FROM Osio_sisalto WHERE FK_Paatos=:fk_paatos AND FK_Osio=:fk_osio AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_paatos' => $fk_paatos, ':fk_osio' => $fk_osio));
		$result = $sth->fetch();

		$osio_sisaltoDTO = new Osio_sisaltoDTO();
		$osio_sisaltoDTO->ID = $result["ID"];
		$osio_sisaltoDTO->Sisalto_text = $result["Sisalto_text"];
		$osio_sisaltoDTO->Sisalto_boolean = $result["Sisalto_boolean"];
		$osio_sisaltoDTO->Sisalto_date = $result["Sisalto_date"];

		return $osio_sisaltoDTO;

	}
	
	function hae_lausunnon_ja_osion_sisalto($fk_lausunto, $fk_osio){

		$query = "SELECT * FROM Osio_sisalto WHERE FK_Lausunto=:fk_lausunto AND FK_Osio=:fk_osio AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lausunto' => $fk_lausunto, ':fk_osio' => $fk_osio));
		$result = $sth->fetch();

		$osio_sisaltoDTO = new Osio_sisaltoDTO();
		$osio_sisaltoDTO->ID = $result["ID"];
		$osio_sisaltoDTO->Sisalto_text = $result["Sisalto_text"];
		$osio_sisaltoDTO->Sisalto_boolean = $result["Sisalto_boolean"];
		$osio_sisaltoDTO->Sisalto_date = $result["Sisalto_date"];

		return $osio_sisaltoDTO;

	}

	function merkitse_osio_sisalto_poistetuksi($id,$kayt_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Osio_sisalto SET Poistaja=:kayt_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':kayt_id' => $kayt_id, ':nyt' => $nyt, ':id' => $id));

	}


}