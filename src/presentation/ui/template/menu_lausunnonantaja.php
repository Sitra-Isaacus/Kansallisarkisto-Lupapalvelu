    <ul class="tabnav">
		<?php
			// Viranomaisen valikko
			if (isset($_SESSION["kayttaja_rooli"])) {
				
				// Määritetään saapuneiden viestien määrä
				$v_kpl = "";
				if(isset($_SESSION["kayttaja_uudet_viestit_kpl"]) && $_SESSION["kayttaja_uudet_viestit_kpl"] > 0){
					$v_kpl = " (" . $_SESSION["kayttaja_uudet_viestit_kpl"] .")";
				} else {
					$v_kpl = "";
				}
				
				$sivut = array(
				"lausunnonantaja_saapuneet_lausuntopyynnot.php" => SAAPUNEET_LAUSUNTOPYYNNOT,
				"viranomainen_saapuneet_viestit.php" => SAAPUNEET_VIESTIT . $v_kpl,
				"lausunnonantaja_annetut_lausunnot.php" => ANNETUT_LAUSUNNOT);
				
                foreach ($sivut as $os => $nimi) {
					$val ="";
					if($os == $self || ($self=="lausunto.php" && $os=="lausunnonantaja_saapuneet_lausuntopyynnot.php") || ($self=="lausunnonantaja_hakemus_lausunto.php" && $os=="lausunnonantaja_saapuneet_lausuntopyynnot.php") || ($self=="viranomainen_hakemus_viestit.php" && $os=="lausunnonantaja_saapuneet_lausuntopyynnot.php") || ($self=="hakemus.php" && $os=="lausunnonantaja_saapuneet_lausuntopyynnot.php")) $val = " class=\"val\"";
					echo "<li class=\"vasen\"><a href=\"$os\"$val>$nimi</a></li>";
                }
				
				$val = "";
				if ($self == "ohje.php"){
					$val = " class=\"val\"";
				}
            }
            if ($self == "kirjaudu.php" || $self == "index.php" || $self == "viestit.php" || $self == "eraantyvat_luvat.php") {
				if (!isset($_SESSION["kayttaja_id"])) {
					$tmp = KIRJAUDU;
					echo "<li class=\"vasen\"><a href=\"kirjaudu.php\" $val>$tmp</a></li>\n";
				}
			}
        ?>
    </ul>