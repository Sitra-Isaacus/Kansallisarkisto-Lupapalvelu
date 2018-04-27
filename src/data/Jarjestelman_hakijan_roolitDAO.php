<?php
/*
 * FMAS Käyttölupapalvelu
 * Jarjestelman_hakijan_roolit Data access object
 *
 * Created: 10.4.2017
 */

class Jarjestelman_hakijan_roolitDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function hae_hakemustyypin_hakijan_roolit($fk_lomake){

		$query = "SELECT * FROM Jarjestelman_hakijan_roolit WHERE FK_Lomake=:fk_lomake ORDER BY Jarjestys ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lomake' => $fk_lomake));
		$result = $sth->fetchAll();
		$jarjestelman_hakijan_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$jarjestelman_hakijan_roolitDTO[$i] = new Jarjestelman_hakijan_roolitDTO();
			$jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_koodi = $result[$i]["Hakijan_roolin_koodi"];
			//$jarjestelman_hakijan_roolitDTO[$i]->Hakemustyyppi = $result[$i]["Hakemustyyppi"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_info = $result[$i]["Hakijan_roolin_info"];
			$jarjestelman_hakijan_roolitDTO[$i]->Pakollinen_rooli_hakemukselle = $result[$i]["Pakollinen_rooli_hakemukselle"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_muokkaus_sallittu = $result[$i]["Hakemuksen_muokkaus_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_lahetys_sallittu = $result[$i]["Hakemuksen_lahetys_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_poisto_sallittu = $result[$i]["Hakemuksen_poisto_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_peruminen_sallittu = $result[$i]["Hakemuksen_peruminen_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_hakijan_kutsuminen_sallittu = $result[$i]["Hakemuksen_hakijan_kutsuminen_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_hakijan_poistaminen_sallittu = $result[$i]["Hakemuksen_hakijan_poistaminen_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Jarjestys = $result[$i]["Jarjestys"];
		}

		return $jarjestelman_hakijan_roolitDTO;

	}
	
	function hae_lomakkeen_roolit_joilla_lahetys_sallittu($fk_lomake){

		$query = "SELECT * FROM Jarjestelman_hakijan_roolit WHERE FK_Lomake=:fk_lomake AND Hakemuksen_lahetys_sallittu=1 ORDER BY Jarjestys ASC";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_lomake' => $fk_lomake));
		$result = $sth->fetchAll();
		$jarjestelman_hakijan_roolitDTO = array();

		for($i=0; $i < sizeof($result); $i++){
			$jarjestelman_hakijan_roolitDTO[$i] = new Jarjestelman_hakijan_roolitDTO();
			$jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_koodi = $result[$i]["Hakijan_roolin_koodi"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_info = $result[$i]["Hakijan_roolin_info"];
			$jarjestelman_hakijan_roolitDTO[$i]->Pakollinen_rooli_hakemukselle = $result[$i]["Pakollinen_rooli_hakemukselle"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_muokkaus_sallittu = $result[$i]["Hakemuksen_muokkaus_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_lahetys_sallittu = $result[$i]["Hakemuksen_lahetys_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_poisto_sallittu = $result[$i]["Hakemuksen_poisto_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_peruminen_sallittu = $result[$i]["Hakemuksen_peruminen_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_hakijan_kutsuminen_sallittu = $result[$i]["Hakemuksen_hakijan_kutsuminen_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Hakemuksen_hakijan_poistaminen_sallittu = $result[$i]["Hakemuksen_hakijan_poistaminen_sallittu"];
			$jarjestelman_hakijan_roolitDTO[$i]->Jarjestys = $result[$i]["Jarjestys"];
		}

		return $jarjestelman_hakijan_roolitDTO;

	}	

}