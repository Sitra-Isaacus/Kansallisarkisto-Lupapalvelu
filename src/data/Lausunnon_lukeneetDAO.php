<?php
/*
 * FMAS Käyttölupapalvelu
 * Lausunnon_lukeneet Data access object
 *
 * Created: 20.10.2016
 */

class Lausunnon_lukeneetDAO {

	protected $db;

	function __construct($db) {
       $this->db = $db;
	}

	function merkkaa_lausunto_luetuksi($fk_lausunto, $fk_kayttaja){

		$query = "INSERT INTO Lausunnon_lukeneet (FK_Lausunto, FK_Kayttaja) VALUES (:fk_lausunto, :fk_kayttaja)";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		return $sth->execute(array(':fk_lausunto' => $fk_lausunto, ':fk_kayttaja' => $fk_kayttaja));

	}

	function lausunto_on_luettu($fk_kayttaja, $fk_lausunto){

		$query = "SELECT COUNT(*) FROM Lausunnon_lukeneet WHERE FK_Kayttaja=:fk_kayttaja AND FK_Lausunto=:fk_lausunto";
		$sth = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		$sth->execute(array(':fk_kayttaja' => $fk_kayttaja, ':fk_lausunto' => $fk_lausunto));
		$result = $sth->fetch();
		return $result["COUNT(*)"];

	}

}