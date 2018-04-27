<?php
/*
 * FMAS Käyttölupapalvelu
 * Asia Data access object
 *
 * Created: 3.10.2017
 */

class AsiaDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function luo_asia($Organisaatio, $Tehtavaluokka, $Julkisuusluokka, $Henkilotietoja, $Sailytysajan_pituus, $Sailytysajan_peruste, $Lisaaja){
	
		$query = "INSERT INTO Asia (Organisaatio, Tehtavaluokka, Julkisuusluokka, Henkilotietoja, Sailytysajan_pituus, Sailytysajan_peruste, Lisaaja) VALUES (:Organisaatio, :Tehtavaluokka, :Julkisuusluokka, :Henkilotietoja, :Sailytysajan_pituus, :Sailytysajan_peruste, :Lisaaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':Organisaatio' => $Organisaatio, ':Tehtavaluokka' => $Tehtavaluokka, ':Julkisuusluokka' => $Julkisuusluokka, ':Henkilotietoja' => $Henkilotietoja, ':Sailytysajan_pituus' => $Sailytysajan_pituus, ':Sailytysajan_peruste' => $Sailytysajan_peruste, ':Sailytysajan_peruste' => $Sailytysajan_peruste, ':Lisaaja' => $Lisaaja));
	
		$asiaDTO = new AsiaDTO();	
		$asiaDTO->ID = $this->db->lastInsertId();

		return $asiaDTO;	
	
	}
	
	function paivita_asian_tieto($id, $kentan_nimi, $kentan_arvo){

		if(is_numeric($kentan_arvo)){
			$q = "UPDATE Asia SET $kentan_nimi=$kentan_arvo WHERE ID=$id";
		} else {
			$q = "UPDATE Asia SET $kentan_nimi='$kentan_arvo' WHERE ID=$id";
		}

		return $this->db->query($q);
		
	}	
	
	function hae_asia($fk_asia){
		
		$query = "SELECT * FROM Asia WHERE ID=:fk_asia";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_asia' => $fk_asia));
		$result = $sth->fetch();

		$asiaDTO = new AsiaDTO();
		$asiaDTO->ID = $result["ID"];
		$asiaDTO->Diaarinumero = $result["Diaarinumero"];
		$asiaDTO->Organisaatio = $result["Organisaatio"];
		$asiaDTO->Tehtavaluokka = $result["Tehtavaluokka"];
		$asiaDTO->Julkisuusluokka = $result["Julkisuusluokka"];
		$asiaDTO->Salassapitoaika = $result["Salassapitoaika"];
		$asiaDTO->Salassapitoperuste = $result["Salassapitoperuste"];
		$asiaDTO->Suojaustaso = $result["Suojaustaso"];
		$asiaDTO->Henkilotietoja = $result["Henkilotietoja"];
		$asiaDTO->Sailytysajan_pituus = $result["Sailytysajan_pituus"];
		$asiaDTO->Sailytysajan_peruste = $result["Sailytysajan_peruste"];
		
		return $asiaDTO;		
		
	}
	
	function hae_vuoden_juokseva_nro($vuosi){
		
		$query = "SELECT COUNT(*) FROM Asia WHERE YEAR(Lisayspvm)=:vuosi";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':vuosi' => $vuosi));		
		$result = $sth->fetch();

		return $result["COUNT(*)"];		
		
	}

}