<?php
/*
 * FMAS Käyttölupapalvelu
 * Käyttöloki Data access object
 *
 * Created: 5.10.2016
 */

class KayttolokiDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	public function kirjaa_lokiin($parametrit){

		$kayt_id = null; $toiminto = null; $hakemusversio_id = null; $liite_id = null; $lausunto_id = null; $pohja_rooli = "";

		if(isset($parametrit["kayt_id"])) $kayt_id = $parametrit["kayt_id"];
		if(isset($parametrit["pohja_rooli"])) $pohja_rooli = $parametrit["pohja_rooli"];
		if(isset($parametrit["toiminto"])) $toiminto = $parametrit["toiminto"];
		if(isset($parametrit["hakemusversio_id"])) $hakemusversio_id = $parametrit["hakemusversio_id"];
		if(isset($parametrit["liite_id"])) $liite_id = $parametrit["liite_id"];
		if(isset($parametrit["lausunto_id"])) $lausunto_id = $parametrit["lausunto_id"];

		$query = "INSERT INTO Kayttoloki (FK_Kayttaja, FK_Hakemusversio, FK_Liitteet, FK_Lausunto, Toiminto, Rooli, Lisaaja) VALUES (:kayt_id, :hakemusversio_id, :liite_id, :lausunto_id, :toiminto, :pohja_rooli, :lisaaja_id)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':kayt_id' => $kayt_id, ':hakemusversio_id' => $hakemusversio_id, ':liite_id' => $liite_id, ':lausunto_id' => $lausunto_id, ':toiminto' => $toiminto, ':pohja_rooli' => $pohja_rooli, ':lisaaja_id' => $kayt_id));
		$kayttoloki_id = $this->db->lastInsertId();

		$kayttolokiDTO = new KayttolokiDTO();
		$kayttolokiDTO->ID = $kayttoloki_id;

		return $kayttolokiDTO;

	}
   
}

?>