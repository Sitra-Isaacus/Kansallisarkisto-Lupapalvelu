		<ul class="tabnav">
			<?php
				// Tutkijan ylävalikko
				if (isset($_SESSION["kayttaja_rooli"])) {
					$v_kpl = "";
					if(isset($_SESSION["kayttaja_uudet_viestit_kpl"]) && $_SESSION["kayttaja_uudet_viestit_kpl"]>0){
						$v_kpl = " (" . $_SESSION["kayttaja_uudet_viestit_kpl"] .")";
					} else {
						$v_kpl = "";
					}
					$e_kpl = "";
					if(isset($_SESSION["kayttaja_eraantyvat_kayttoluvat_kpl"]) && $_SESSION["kayttaja_eraantyvat_kayttoluvat_kpl"]>0){
						$e_kpl = " (" . $_SESSION["kayttaja_eraantyvat_kayttoluvat_kpl"] .")";
					} else {
						$e_kpl = "";
					}
					$sivut = array(// tähän kirjautumisen vaativat etusivun välilehdet
					"index.php" => OMAT_HAKEMUKSET,
					"viestit.php" => SAAPUNEET_VIESTIT . $v_kpl,
					"eraantyvat_luvat.php" => ERAANTYVAT_KAYTTOLUVAT . $e_kpl);
					foreach ($sivut as $os => $nimi) {
						$val = "";
						if ($self == $os ||  ($self=="hakemus_viestit.php" && $os=="viestit.php") || ($self=="aineistotilaus.php" && $os=="index.php") || ($self=="hakemus.php" && $os=="index.php") || ($self=="tutkimus.php" && $os=="index.php")){
							$val = " class=\"val\"";
						}
						echo "<li class=\"vasen\"><a href=\"$os\"$val>$nimi</a></li>";
					}
					if ($self == "kirjaudu.php" || $self == "ohje.php" || $self == "index.php" || $self == "viestit.php" || $self == "eraantyvat_luvat.php" || $self == "rekisteroidy.php") {
						$val = "";
						if ($self == "kirjaudu.php"){
							$val = " class=\"val\"";
						}
						if (!isset($_SESSION["kayttaja_id"])) {
							$tmp = KIRJAUDU;
							echo "<li class=\"vasen\"><a href=\"kirjaudu.php\" $val>$tmp</a></li>\n";
						}
					}
					$val = "";
					//if ($self == "ohje.php"){
					//	$val = " class=\"val\"";
					//}
					//echo "<li class=\"vasen\"><a href=\"ohje.php\"$val>" . OHJE . "</a></li>";
				} else {
					$sivut = array(// tähän kirjautumisen vaativat etusivun välilehdet
					"kirjaudu.php" => KIRJAUDU,
					"rekisteroidy.php" => REKISTEROIDY_MENU);
					//"ohje.php" => OHJE);
					foreach ($sivut as $os => $nimi) {
						$val = "";
						if ($self == $os) $val = " class=\"val\"";
						echo "<li class=\"vasen\"><a href=\"$os\"$val>$nimi</a></li>";
					}
				}
			?>
		</ul>