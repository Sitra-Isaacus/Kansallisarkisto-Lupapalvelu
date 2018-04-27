    <ul class="tabnav">
		<?php
			// Viranomaisen valikko
			if (isset($_SESSION['kayttaja_rooli'])) {
				
				// Määritetään saapuneiden viestien määrä
				$v_kpl = "";
				if(isset($_SESSION['kayttaja_uudet_viestit_kpl']) && $_SESSION['kayttaja_uudet_viestit_kpl'] > 0){
					$v_kpl = " (" . $_SESSION['kayttaja_uudet_viestit_kpl'] .")";
				} else {
					$v_kpl = "";
				}
				
				// Määritetään saapuneiden lausuntojen määrä
				$l_kpl = "";
				if(isset($_SESSION['kayttaja_lukemattomat_lausunnot_kpl']) && $_SESSION['kayttaja_lukemattomat_lausunnot_kpl'] > 0){
					$l_kpl = " (" . $_SESSION['kayttaja_lukemattomat_lausunnot_kpl'] .")";
				} else {
					$l_kpl = "";
				}
				
				$sivut = array(
				"viranomainen_saapuneet_hakemukset.php" => HAKEMUKSET,
				"viranomainen_saapuneet_viestit.php" => SAAPUNEET_VIESTIT . $v_kpl,
				"viranomainen_saapuneet_lausunnot.php" => SAAPUNEET_LAUSUNNOT . $l_kpl,
				"viranomainen_etsi.php" => ETSI_HAKEMUSTA);
                foreach ($sivut as $os => $nimi) {
					$val ="";
					if($os == $self || ($self=="metatiedot.php" && $os=="viranomainen_saapuneet_hakemukset.php") || ($self=="lausunto.php" && $os=="viranomainen_saapuneet_hakemukset.php") || ($self=="viranomainen_hakemus_lausunto.php" && $os=="viranomainen_saapuneet_hakemukset.php") || ($self=="paatos.php" && $os=="viranomainen_saapuneet_hakemukset.php") || ($self=="viranomainen_hakemus_viestit.php" && $os=="viranomainen_saapuneet_hakemukset.php") || ($self=="hakemus.php" && $os=="viranomainen_saapuneet_hakemukset.php")) $val = " class=\"val\"";
					echo "<li class=\"vasen\"><a href=\"$os\"$val>$nimi</a></li>";
                }
            }
            if ($self == "kirjaudu.php" || $self == "index.php" || $self == "viestit.php" || $self == "eraantyvat_luvat.php") {
				if (!isset($_SESSION['kayttaja_id'])) {
					$tmp = KIRJAUDU;
					echo "<li class=\"vasen\"><a href=\"kirjaudu.php\" $val>$tmp</a></li>\n";
				}
			}
        ?>
    </ul>