<?php
/*
 * FMAS Käyttölupapalvelu
 * Osio Data access object
 *
 * Created: 10.2.2017
 */

class OsioDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function luo_osio($fk_osio_parent, $nimi, $sivun_tunniste, $fk_lomake, $vir_om_tunn, $osio_tyyppi, $osio_luokka, $otsikko, $pakollinen_tieto, $infoteksti, $max_merkit, $jarjestys, $lisaaja){

		$query = "INSERT INTO Osio (FK_Osio_parent, Osio_nimi, Sivun_tunniste, FK_Lomake, Viranomaiskohtainen_tunniste, Osio_tyyppi, Osio_luokka, Otsikko, Pakollinen_tieto, Infoteksti, Maksimi_merkit, Jarjestys, Lisaaja) VALUES (:fk_osio_parent, :nimi, :sivun_tunniste, :fk_lomake, :vir_om_tunn, :osio_tyyppi, :osio_luokka, :otsikko, :pakollinen_tieto, :infoteksti, :max_merkit, :jarjestys, :lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_osio_parent' => $fk_osio_parent, ':nimi' => $nimi, ':sivun_tunniste' => $sivun_tunniste, ':fk_lomake' => $fk_lomake, ':vir_om_tunn' => $vir_om_tunn, ':osio_tyyppi' => $osio_tyyppi, ':osio_luokka' => $osio_luokka, ':otsikko' => $otsikko, ':pakollinen_tieto' => $pakollinen_tieto, ':infoteksti' => $infoteksti, ':max_merkit' => $max_merkit, ':jarjestys' => $jarjestys, ':lisaaja' => $lisaaja));

		$osioDTO = new OsioDTO();
		$osioDTO->ID = $this->db->lastInsertId();

		return $osioDTO;

	}

	function paivita_osion_tieto($id, $kentan_nimi, $kentan_arvo, $muokkaaja){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Osio SET $kentan_nimi=$kentan_arvo, Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		} else {
			$q = "UPDATE Osio SET $kentan_nimi='$kentan_arvo', Muokkaaja=$muokkaaja, Muokkauspvm='$nyt' WHERE ID=$id";
		}

		return $this->db->query($q);

	}

	function hae_osio($id){

		$query = "SELECT * FROM Osio WHERE ID=:id AND Poistaja IS NULL AND Poistopvm IS NULL";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id));
		$result = $sth->fetch();

		//$koodistotDAO = new KoodistotDAO($this->db);

		$osioDTO = new OsioDTO();
		$osioDTO->ID = $result["ID"];
		$osioDTO->Otsikko = $result["Otsikko"];
		$osioDTO->Otsikko_fi = $result["Otsikko_fi"];
		$osioDTO->Otsikko_en = $result["Otsikko_en"];
		//$osioDTO->Otsikko_fi = $koodistotDAO->hae_koodin_tiedot($result["Otsikko"],"fi")->Selite1;
		//$osioDTO->Otsikko_en = $koodistotDAO->hae_koodin_tiedot($result["Otsikko"],"en")->Selite1;
		//$osioDTO->Otsikko_sv = $koodistotDAO->hae_koodin_tiedot($result["Otsikko"],"sv")->Selite1;
		$osioDTO->Infoteksti = $result["Infoteksti"];
		$osioDTO->OsioDTO_parent = new OsioDTO();
		$osioDTO->OsioDTO_parent->ID = $result["FK_Osio_parent"];
		$osioDTO->Osio_tyyppi = $result["Osio_tyyppi"];
		$osioDTO->Osio_luokka = $result["Osio_luokka"];

		return $osioDTO;

	}

	function hae_sisarosiot($id, $fk_osio_parent){

		$query = "SELECT * FROM Osio WHERE ID<>:id AND FK_Osio_parent=:fk_osio_parent AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Jarjestys ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':id' => $id, ':fk_osio_parent' => $fk_osio_parent));
		$result = $sth->fetchAll();

		$osiotDTO = array();

		if(!empty($result)){

			for($i=0; $i < sizeof($result); $i++){

				$osioDTO = new OsioDTO();
				$osioDTO->ID = $result[$i]["ID"];
				$osioDTO->Osio_nimi = $result[$i]["Osio_nimi"];
				$osioDTO->Sivun_tunniste = $result[$i]["Sivun_tunniste"];
				$osioDTO->Osio_tyyppi = $result[$i]["Osio_tyyppi"];
				$osioDTO->Osio_luokka = $result[$i]["Osio_luokka"];

				$osioDTO->OsioDTO_parent = new OsioDTO();
				$osioDTO->OsioDTO_parent->ID = $result[$i]["FK_Osio_parent"];

				$osiotDTO[$i] = $osioDTO;

			}

			return $osiotDTO;

		}

		return null;

	}

	function hae_lapsiosiot($fk_osio_parent, $kayt_kieli){

		$query = "SELECT * FROM Osio WHERE FK_Osio_parent=:fk_osio_parent AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Jarjestys ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_osio_parent' => $fk_osio_parent));
		$result = $sth->fetchAll();

		$osiotDTO = array();
		//$koodistotDAO = new KoodistotDAO($this->db);

		if(!empty($result)){

			for($i=0; $i < sizeof($result); $i++){

				$osioDTO = new OsioDTO();
				$osioDTO->ID = $result[$i]["ID"];
				$osioDTO->Osio_nimi = $result[$i]["Osio_nimi"];
				$osioDTO->Sivun_tunniste = $result[$i]["Sivun_tunniste"];
				$osioDTO->Viranomaiskohtainen_tunniste = $result[$i]["Viranomaiskohtainen_tunniste"];
				$osioDTO->Osio_tyyppi = $result[$i]["Osio_tyyppi"];
				$osioDTO->Osio_luokka = $result[$i]["Osio_luokka"];
				$osioDTO->Otsikko = $result[$i]["Otsikko"];
				$osioDTO->Otsikko_fi = $result[$i]["Otsikko_fi"];
				$osioDTO->Otsikko_en = $result[$i]["Otsikko_en"];
				//$osioDTO->Otsikko = $koodistotDAO->hae_koodin_tiedot($result[$i]["Otsikko"],$kayt_kieli)->Selite1;	// Otsikko käyttäjän kielellä
				//$osioDTO->Otsikko_fi = $koodistotDAO->hae_koodin_tiedot($result[$i]["Otsikko"],"fi")->Selite1;
				//$osioDTO->Otsikko_en = $koodistotDAO->hae_koodin_tiedot($result[$i]["Otsikko"],"en")->Selite1;
				//$osioDTO->Otsikko_sv = $koodistotDAO->hae_koodin_tiedot($result[$i]["Otsikko"],"sv")->Selite1;
				$osioDTO->Pakollinen_tieto = $result[$i]["Pakollinen_tieto"];
				$osioDTO->Infoteksti = $result[$i]["Infoteksti"];
				$osioDTO->Infoteksti_fi = $result[$i]["Infoteksti_fi"];
				$osioDTO->Infoteksti_en = $result[$i]["Infoteksti_en"];
				//$osioDTO->Infoteksti_fi = $koodistotDAO->hae_koodin_tiedot($result[$i]["Infoteksti"],"fi")->Selite1;
				//$osioDTO->Infoteksti_en = $koodistotDAO->hae_koodin_tiedot($result[$i]["Infoteksti"],"en")->Selite1;
				//$osioDTO->Infoteksti_sv = $koodistotDAO->hae_koodin_tiedot($result[$i]["Infoteksti"],"sv")->Selite1;
				$osioDTO->Jarjestys = $result[$i]["Jarjestys"];
				$osioDTO->Maksimi_merkit = $result[$i]["Maksimi_merkit"];
				$osioDTO->Sarakkeiden_lkm = $result[$i]["Sarakkeiden_lkm"];
				$osioDTO->Lisaaja = $result[$i]["Lisaaja"];
				$osioDTO->Lisayspvm = $result[$i]["Lisayspvm"];
				$osioDTO->Poistaja = $result[$i]["Poistaja"];
				$osioDTO->Poistopvm = $result[$i]["Poistopvm"];

				$osioDTO->OsioDTO_parent = new OsioDTO();
				$osioDTO->OsioDTO_parent->ID = $result[$i]["FK_Osio_parent"];
				$osioDTO->OsioDTO_childs = $this->hae_lapsiosiot($result[$i]["ID"], $kayt_kieli);

				$osiotDTO[$i] = $osioDTO;

			}

			return $osiotDTO;

		}

		return null;

	}

	function hae_lomakkeen_sivun_osiot_ja_sisallot_taulukko($sivun_tunniste, $fk_sisalto_viite, $sisalto_tyyppi, $fk_lomake, $hae_sisalto, $lukituspvm){

		if(!is_null($lukituspvm)){ 
			$query = "SELECT * FROM Osio WHERE Sivun_tunniste=:sivun_tunniste AND FK_Lomake=:fk_lomake AND (Lisayspvm IS NULL OR DATE(Lisayspvm) <= :lukituspvm) AND ( Poistopvm IS NULL OR ( Poistopvm IS NOT NULL AND DATE(Poistopvm) > :lukituspvm2 ) ) ORDER BY Jarjestys ASC";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));				
			$sth->execute(array(':sivun_tunniste' => $sivun_tunniste, ':fk_lomake' => $fk_lomake, ':lukituspvm' => $lukituspvm, ':lukituspvm2' => $lukituspvm));					
		} else {
			$query = "SELECT * FROM Osio WHERE Sivun_tunniste=:sivun_tunniste AND FK_Lomake=:fk_lomake AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Jarjestys ASC";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':sivun_tunniste' => $sivun_tunniste, ':fk_lomake' => $fk_lomake));			
		}
			
		$result = $sth->fetchAll();
		$osiotDTO = array();

		$osio_sisaltoDAO = new Osio_sisaltoDAO($this->db);
		$osio_saantoDAO = new Osio_saantoDAO($this->db);
		//$koodistotDAO = new KoodistotDAO($this->db);

		for($i=0; $i < sizeof($result); $i++){

			$osioDTO = new OsioDTO();
			$osioDTO->ID = $result[$i]["ID"];
			$osioDTO->Sivun_tunniste = $result[$i]["Sivun_tunniste"];
			$osioDTO->Osio_tyyppi = $result[$i]["Osio_tyyppi"];
			$osioDTO->Otsikko = $result[$i]["Otsikko"];
			$osioDTO->Otsikko_fi = $result[$i]["Otsikko_fi"];
			$osioDTO->Otsikko_en = $result[$i]["Otsikko_en"];
			$osioDTO->Otsikko_sv = $result[$i]["Otsikko_sv"];	
			//$osioDTO->Otsikko_fi = $koodistotDAO->hae_koodin_tiedot($result[$i]["Otsikko"],"fi")->Selite1;
			$osioDTO->Osio_luokka = $result[$i]["Osio_luokka"];
			$osioDTO->Pakollinen_tieto = $result[$i]["Pakollinen_tieto"];
			$osioDTO->Infoteksti = $result[$i]["Infoteksti"];
			$osioDTO->Jarjestys = $result[$i]["Jarjestys"];
			$osioDTO->Maksimi_merkit = $result[$i]["Maksimi_merkit"];
			$osioDTO->Sarakkeiden_lkm = $result[$i]["Sarakkeiden_lkm"];
			$osioDTO->Lisaaja = $result[$i]["Lisaaja"];
			$osioDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$osioDTO->Poistaja = $result[$i]["Poistaja"];
			$osioDTO->Poistopvm = $result[$i]["Poistopvm"];

			$osioDTO->OsioDTO_parent = new OsioDTO();
			$osioDTO->OsioDTO_parent->ID = $result[$i]["FK_Osio_parent"];
			
			if($hae_sisalto){
				if($osioDTO->Osio_tyyppi!="valiotsikko" && $osioDTO->Osio_tyyppi!="lohko" && $osioDTO->Osio_tyyppi!="kysymys" && $osioDTO->Osio_tyyppi!="laatikko_sisalto" && $osioDTO->Osio_tyyppi!="laatikko_otsikko" && $osioDTO->Osio_tyyppi!="laatikko"){
					//$osioDTO->Osio_sisaltoDTO = $osio_sisaltoDAO->hae_osion_ja_viitetyypin_sisalto($result[$i]["ID"], $sisalto_tyyppi, $fk_sisalto_viite);
					$osioDTO->Osio_sisaltoDTO = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($fk_sisalto_viite, $sisalto_tyyppi, $result[$i]["ID"]);
				} 
			}
			
			$osioDTO->Osio_saannotDTO = $osio_saantoDAO->hae_osion_saannot($result[$i]["ID"]);

			$osiotDTO[$result[$i]["ID"]] = $osioDTO;

		}

		return $osiotDTO;

	}	
	
	function hae_lomakkeen_sivun_osiot_ja_sisallot_puu($fk_lomake, $sivun_tunniste, $fk_sisalto_viite, $sisalto_tyyppi, $hae_sisallot, $lukituspvm){

		if(!is_null($lukituspvm)){
			$query = "SELECT * FROM Osio WHERE Sivun_tunniste=:sivun_tunniste AND FK_Lomake=:fk_lomake AND FK_Osio_parent IS NULL AND (Lisayspvm IS NULL OR DATE(Lisayspvm) <= :lukituspvm ) AND (Poistopvm IS NULL OR (Poistopvm IS NOT NULL AND DATE(Poistopvm) > :lukituspvm2)) ORDER BY Jarjestys ASC";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':sivun_tunniste' => $sivun_tunniste, ':fk_lomake' => $fk_lomake, ':lukituspvm' => $lukituspvm, ':lukituspvm2' => $lukituspvm));		
		} else {
			$query = "SELECT * FROM Osio WHERE Sivun_tunniste=:sivun_tunniste AND FK_Lomake=:fk_lomake AND FK_Osio_parent IS NULL AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Jarjestys ASC";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':sivun_tunniste' => $sivun_tunniste, ':fk_lomake' => $fk_lomake));			
		}
			
		$result = $sth->fetchAll();

		$osiotDTO = array();
		//$koodistotDAO = new KoodistotDAO($this->db);

		for($i=0; $i < sizeof($result); $i++){

			$osioDTO = new OsioDTO();
			$osioDTO->ID = $result[$i]["ID"];
			$osioDTO->Sivun_tunniste = $result[$i]["Sivun_tunniste"];
			$osioDTO->Osio_tyyppi = $result[$i]["Osio_tyyppi"];
			$osioDTO->Osio_nimi = $result[$i]["Osio_nimi"];	
			$osioDTO->Otsikko = $result[$i]["Otsikko"];	// Kielikoodi						
			$osioDTO->Otsikko_fi = $result[$i]["Otsikko_fi"];									
			$osioDTO->Otsikko_en = $result[$i]["Otsikko_en"];									
			$osioDTO->Otsikko_sv = $result[$i]["Otsikko_sv"];									
			$osioDTO->Infoteksti_fi = $result[$i]["Infoteksti_fi"];						
			$osioDTO->Infoteksti_en = $result[$i]["Infoteksti_en"];									
			$osioDTO->Infoteksti_sv = $result[$i]["Infoteksti_sv"];						
			$osioDTO->Osio_luokka = $result[$i]["Osio_luokka"];
			$osioDTO->Pakollinen_tieto = $result[$i]["Pakollinen_tieto"];
			$osioDTO->Viranomaiskohtainen_tunniste = $result[$i]["Viranomaiskohtainen_tunniste"];
			$osioDTO->Infoteksti = $result[$i]["Infoteksti"];			
			$osioDTO->Jarjestys = $result[$i]["Jarjestys"];
			$osioDTO->Maksimi_merkit = $result[$i]["Maksimi_merkit"];
			$osioDTO->Sarakkeiden_lkm = $result[$i]["Sarakkeiden_lkm"];
			$osioDTO->Lisaaja = $result[$i]["Lisaaja"];
			$osioDTO->Lisayspvm = $result[$i]["Lisayspvm"];
			$osioDTO->Poistaja = $result[$i]["Poistaja"];
			$osioDTO->Poistopvm = $result[$i]["Poistopvm"];

			$osioDTO->OsioDTO_parent = new OsioDTO();
			$osioDTO->OsioDTO_parent->ID = $result[$i]["FK_Osio_parent"];
			$osioDTO->OsioDTO_childs = $this->hae_osion_lapsiosiot_rekursiivisesti($result[$i]["ID"],$fk_sisalto_viite, $sisalto_tyyppi, $hae_sisallot, $lukituspvm);

			$osiotDTO[$i] = $osioDTO;

		}

		return $osiotDTO;
		
	}
	
	function hae_osion_lapsiosiot_rekursiivisesti($fk_osio_parent, $fk_sisalto_viite, $sisalto_tyyppi, $hae_sisallot, $lukituspvm){

		if(!is_null($lukituspvm)){ 
			$query = "SELECT * FROM Osio WHERE FK_Osio_parent=:fk_osio_parent AND (Lisayspvm IS NULL OR DATE(Lisayspvm) <= :lukituspvm) AND (Poistopvm IS NULL OR (Poistopvm IS NOT NULL AND DATE(Poistopvm) > :lukituspvm2) ) ORDER BY Jarjestys ASC";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':fk_osio_parent' => $fk_osio_parent, ':lukituspvm' => $lukituspvm, ':lukituspvm2' => $lukituspvm));		
		} else {
			$query = "SELECT * FROM Osio WHERE FK_Osio_parent=:fk_osio_parent AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Jarjestys ASC";
			$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$sth->execute(array(':fk_osio_parent' => $fk_osio_parent));		
		}
			
		$result = $sth->fetchAll();

		$osiotDTO = array();
		//$koodistotDAO = new KoodistotDAO($this->db);

		if(!empty($result)){

			$osio_sisaltoDAO = new Osio_sisaltoDAO($this->db);

			for($i=0; $i < sizeof($result); $i++){

				$osioDTO = new OsioDTO();
				$osioDTO->ID = $result[$i]["ID"];
				$osioDTO->Sivun_tunniste = $result[$i]["Sivun_tunniste"];
				$osioDTO->Viranomaiskohtainen_tunniste = $result[$i]["Viranomaiskohtainen_tunniste"];
				$osioDTO->Osio_tyyppi = $result[$i]["Osio_tyyppi"];
				$osioDTO->Osio_luokka = $result[$i]["Osio_luokka"];
				$osioDTO->Osio_nimi = $result[$i]["Osio_nimi"];
				$osioDTO->Otsikko = $result[$i]["Otsikko"];
				$osioDTO->Otsikko_fi = $result[$i]["Otsikko_fi"];									
				$osioDTO->Otsikko_en = $result[$i]["Otsikko_en"];									
				$osioDTO->Otsikko_sv = $result[$i]["Otsikko_sv"];									
				$osioDTO->Infoteksti_fi = $result[$i]["Infoteksti_fi"];						
				$osioDTO->Infoteksti_en = $result[$i]["Infoteksti_en"];									
				$osioDTO->Infoteksti_sv = $result[$i]["Infoteksti_sv"];						
				$osioDTO->Pakollinen_tieto = $result[$i]["Pakollinen_tieto"];
				$osioDTO->Infoteksti = $result[$i]["Infoteksti"];
				$osioDTO->Jarjestys = $result[$i]["Jarjestys"];
				$osioDTO->Maksimi_merkit = $result[$i]["Maksimi_merkit"];
				$osioDTO->Sarakkeiden_lkm = $result[$i]["Sarakkeiden_lkm"];
				$osioDTO->Lisaaja = $result[$i]["Lisaaja"];
				$osioDTO->Lisayspvm = $result[$i]["Lisayspvm"];
				$osioDTO->Poistaja = $result[$i]["Poistaja"];
				$osioDTO->Poistopvm = $result[$i]["Poistopvm"];

				$osioDTO->OsioDTO_parent = new OsioDTO();
				$osioDTO->OsioDTO_parent->ID = $result[$i]["FK_Osio_parent"];
				$osioDTO->OsioDTO_childs = $this->hae_osion_lapsiosiot_rekursiivisesti($result[$i]["ID"], $fk_sisalto_viite, $sisalto_tyyppi, $hae_sisallot, $lukituspvm);

				if($hae_sisallot){
					if($osioDTO->Osio_tyyppi!="valiotsikko" && $osioDTO->Osio_tyyppi!="lohko" &&  $osioDTO->Osio_tyyppi!="kysymys" && $osioDTO->Osio_tyyppi!="laatikko_sisalto" && $osioDTO->Osio_tyyppi!="laatikko_otsikko" && $osioDTO->Osio_tyyppi!="laatikko"){	
						$osioDTO->Osio_sisaltoDTO = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($fk_sisalto_viite, $sisalto_tyyppi, $result[$i]["ID"]);
					}
				}	
				
				$osiotDTO[$i] = $osioDTO;

			}

			return $osiotDTO;

		}

		return null;

	}	
		
	function hae_lomakkeen_sivun_ja_viranomaisten_osiot_puu($fk_lomake, $sivun_tunniste, $fk_sisalto_viite, $sisalto_tyyppi, $hae_sisallot, $viranomaiset, $lukituspvm){

		$viranomaiskohtaiset_osiotDTO = array();

		if(isset($viranomaiset)){
			foreach($viranomaiset as $key => $viranomaisen_koodi){
			
				if(!is_null($lukituspvm)){ 
					$query = "SELECT * FROM Osio WHERE Sivun_tunniste=:sivun_tunniste AND FK_Lomake=:fk_lomake AND Viranomaiskohtainen_tunniste=:viranomaisen_koodi AND (Lisayspvm IS NULL OR DATE(Lisayspvm) <= :lukituspvm) AND (Poistopvm IS NULL OR (Poistopvm IS NOT NULL AND DATE(Poistopvm) > :lukituspvm2) ) ORDER BY Jarjestys ASC";
					$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					$sth->execute(array(':lukituspvm' => $lukituspvm, ':lukituspvm2' => $lukituspvm, ':sivun_tunniste' => $sivun_tunniste, ':fk_lomake' => $fk_lomake, ':viranomaisen_koodi' => $viranomaisen_koodi));				
				} else {
					$query = "SELECT * FROM Osio WHERE Sivun_tunniste=:sivun_tunniste AND FK_Lomake=:fk_lomake AND Viranomaiskohtainen_tunniste=:viranomaisen_koodi AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Jarjestys ASC";
					$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					$sth->execute(array(':sivun_tunniste' => $sivun_tunniste, ':fk_lomake' => $fk_lomake, ':viranomaisen_koodi' => $viranomaisen_koodi));					
				}
							
				$result = $sth->fetchAll();
				$osiotDTO = array();

				$osio_sisaltoDAO = new Osio_sisaltoDAO($this->db);

				for($i=0; $i < sizeof($result); $i++){

					$osioDTO = new OsioDTO();
					$osioDTO->ID = $result[$i]["ID"];
					$osioDTO->Sivun_tunniste = $result[$i]["Sivun_tunniste"];
					$osioDTO->Viranomaiskohtainen_tunniste = $result[$i]["Viranomaiskohtainen_tunniste"];
					$osioDTO->Osio_tyyppi = $result[$i]["Osio_tyyppi"];
					$osioDTO->Otsikko = $result[$i]["Otsikko"];
					$osioDTO->Osio_luokka = $result[$i]["Osio_luokka"];
					$osioDTO->Pakollinen_tieto = $result[$i]["Pakollinen_tieto"];
					$osioDTO->Infoteksti = $result[$i]["Infoteksti"];
					$osioDTO->Jarjestys = $result[$i]["Jarjestys"];
					$osioDTO->Maksimi_merkit = $result[$i]["Maksimi_merkit"];
					$osioDTO->Sarakkeiden_lkm = $result[$i]["Sarakkeiden_lkm"];
					$osioDTO->Lisaaja = $result[$i]["Lisaaja"];
					$osioDTO->Lisayspvm = $result[$i]["Lisayspvm"];
					$osioDTO->Poistaja = $result[$i]["Poistaja"];
					$osioDTO->Poistopvm = $result[$i]["Poistopvm"];

					$osioDTO->OsioDTO_parent = new OsioDTO();
					$osioDTO->OsioDTO_parent->ID = $result[$i]["FK_Osio_parent"];
					$osioDTO->OsioDTO_childs = $this->hae_osion_lapsiosiot_rekursiivisesti($result[$i]["ID"], $fk_sisalto_viite, $sisalto_tyyppi, $hae_sisallot, $lukituspvm);
					
					if($hae_sisallot){
						if($osioDTO->Osio_tyyppi!="valiotsikko" && $osioDTO->Osio_tyyppi!="lohko" &&  $osioDTO->Osio_tyyppi!="kysymys" && $osioDTO->Osio_tyyppi!="laatikko_sisalto" && $osioDTO->Osio_tyyppi!="laatikko_otsikko" && $osioDTO->Osio_tyyppi!="laatikko"){	
							$osioDTO->Osio_sisaltoDTO = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($fk_sisalto_viite, $sisalto_tyyppi, $result[$i]["ID"]);
						}
					}	
									
					$osiotDTO[$i] = $osioDTO;
					//array_push($viranomaiskohtaiset_osiotDTO, $osioDTO);

				}

				if(!empty($osiotDTO)){
					$viranomaiskohtaiset_osiotDTO[$viranomaisen_koodi] = $osiotDTO;
				}

			}
		}

		return $viranomaiskohtaiset_osiotDTO;

	}

	function hae_lomakkeen_sivun_ja_viranomaisten_osiot_taulukko($fk_lomake, $sivun_tunniste, $fk_sisalto_viite, $sisalto_tyyppi, $hae_sisallot, $viranomaiset, $lukituspvm){

		$osiotDTO = array();

		if(isset($viranomaiset)){
			foreach($viranomaiset as $key => $viranomaisen_koodi){

				if(!is_null($lukituspvm)){ 
					$query = "SELECT * FROM Osio WHERE Sivun_tunniste=:sivun_tunniste AND FK_Lomake=:fk_lomake AND Viranomaiskohtainen_tunniste=:viranomaisen_koodi AND (Lisayspvm IS NULL OR DATE(Lisayspvm) <= :lukituspvm) AND (Poistopvm IS NULL OR (Poistopvm IS NOT NULL AND DATE(Poistopvm) > :lukituspvm2) ) ORDER BY Jarjestys ASC";
					$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					$sth->execute(array(':lukituspvm' => $lukituspvm, ':lukituspvm2' => $lukituspvm, ':sivun_tunniste' => $sivun_tunniste, ':fk_lomake' => $fk_lomake, ':viranomaisen_koodi' => $viranomaisen_koodi));				
				} else {
					$query = "SELECT * FROM Osio WHERE Sivun_tunniste=:sivun_tunniste AND FK_Lomake=:fk_lomake AND Viranomaiskohtainen_tunniste=:viranomaisen_koodi AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Jarjestys ASC";
					$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
					$sth->execute(array(':sivun_tunniste' => $sivun_tunniste, ':fk_lomake' => $fk_lomake, ':viranomaisen_koodi' => $viranomaisen_koodi));					
				}
			
				$result = $sth->fetchAll();

				$osio_sisaltoDAO = new Osio_sisaltoDAO($this->db);
				$osio_saantoDAO = new Osio_saantoDAO($this->db);

				for($i=0; $i < sizeof($result); $i++){

					$osioDTO = new OsioDTO();
					$osioDTO->ID = $result[$i]["ID"];
					$osioDTO->Sivun_tunniste = $result[$i]["Sivun_tunniste"];
					$osioDTO->Viranomaiskohtainen_tunniste = $result[$i]["Viranomaiskohtainen_tunniste"];
					$osioDTO->Osio_tyyppi = $result[$i]["Osio_tyyppi"];
					$osioDTO->Otsikko = $result[$i]["Otsikko"];
					$osioDTO->Osio_luokka = $result[$i]["Osio_luokka"];
					$osioDTO->Pakollinen_tieto = $result[$i]["Pakollinen_tieto"];
					$osioDTO->Infoteksti = $result[$i]["Infoteksti"];
					$osioDTO->Jarjestys = $result[$i]["Jarjestys"];
					$osioDTO->Maksimi_merkit = $result[$i]["Maksimi_merkit"];
					$osioDTO->Sarakkeiden_lkm = $result[$i]["Sarakkeiden_lkm"];
					$osioDTO->Lisaaja = $result[$i]["Lisaaja"];
					$osioDTO->Lisayspvm = $result[$i]["Lisayspvm"];
					$osioDTO->Poistaja = $result[$i]["Poistaja"];
					$osioDTO->Poistopvm = $result[$i]["Poistopvm"];

					$osioDTO->OsioDTO_parent = new OsioDTO();
					$osioDTO->OsioDTO_parent->ID = $result[$i]["FK_Osio_parent"];
					
					if($hae_sisallot){
						if($osioDTO->Osio_tyyppi!="valiotsikko" && $osioDTO->Osio_tyyppi!="lohko" &&  $osioDTO->Osio_tyyppi!="kysymys" && $osioDTO->Osio_tyyppi!="laatikko_sisalto" && $osioDTO->Osio_tyyppi!="laatikko_otsikko" && $osioDTO->Osio_tyyppi!="laatikko"){	
							$osioDTO->Osio_sisaltoDTO = $osio_sisaltoDAO->hae_sisalto_tyypin_ja_osion_sisalto($fk_sisalto_viite, $sisalto_tyyppi, $result[$i]["ID"]);
						}
					}
					
					$osioDTO->Osio_saannotDTO = $osio_saantoDAO->hae_osion_saannot($result[$i]["ID"]);

					$osiotDTO[$result[$i]["ID"]] = $osioDTO;

				}

			}
		}

		return $osiotDTO;

	}

	function hae_luokan_osiot($osio_luokka){

		$query = "SELECT * FROM Osio WHERE Osio_luokka=:osio_luokka AND Poistaja IS NULL AND Poistopvm IS NULL ORDER BY Jarjestys ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':osio_luokka' => $osio_luokka));
		$result = $sth->fetchAll();
		$osiotDTO = array();

		for($i=0; $i < sizeof($result); $i++){

			$osioDTO = new OsioDTO();
			$osioDTO->ID = $result[$i]["ID"];
			$osioDTO->Otsikko = $result[$i]["Otsikko"];
			$osiotDTO[$i] = $osioDTO;

		}

		return $osiotDTO;

	}

	function merkitse_osio_poistetuksi($id, $poistaja_id){

		$nyt = date_format(date_create(), 'Y-m-d H:i:s');
		$query = "UPDATE Osio SET Poistaja=:poistaja_id, Poistopvm=:nyt WHERE ID=:id";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':poistaja_id' => $poistaja_id, ':nyt' => $nyt, ':id' => $id));

	}

}