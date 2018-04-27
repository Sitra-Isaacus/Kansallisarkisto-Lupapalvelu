    <ul class="tabnav">
		<?php
			// Viranomaisen valikko
			if (isset($_SESSION["kayttaja_rooli"])) {
				$sivut = array(
				"kayttajaroolit.php" => KAYTTAJAROOLIT,
				"lomakkeet.php" => LOMAKKEET);
				//"muokkaa_viranomaiskohtaisia.php" => VIRANOMAISKOHTAISET,
				//"muokkaa_lakeja.php" => LAIT,
				//"lisaa_kayttaja.php" => LISAA_KAYTTAJA,
				//"poista_kayttaja.php" => POISTA_KAYTTAJA);
                foreach ($sivut as $os => $nimi) {
					$val ="";
					if($os == $self || ($self=="lomake_perustiedot.php" && $os=="lomakkeet.php") || ($self=="lomake_sivu.php" && $os=="lomakkeet.php") || ($self=="lomake_suhteet.php" && $os=="lomakkeet.php")) $val = " class=\"val\"";
					echo "<li class=\"vasen\"><a href=\"$os\"$val>$nimi</a></li>";
                }
            }
            if ($self == "kirjaudu.php" || $self == "rekisteroi.php") {
				$val = "";
				if ($self == "kirjaudu.php"){
					$val = " class=\"val\"";
				}
				if (!isset($_SESSION["kayttaja_id"])) {
					$tmp = KIRJAUDU;
					echo "<li class=\"vasen\"><a href=\"kirjaudu.php\" $val>$tmp</a></li>\n";
				}
			}
        ?>
    </ul>