    <ul class="tabnav">
		<?php
		
			if (isset($_SESSION["kayttaja_rooli"])) {
				
				// Määritetään saapuneiden viestien määrä
				$v_kpl = "";
				if(isset($_SESSION["kayttaja_uudet_viestit_kpl"]) && $_SESSION["kayttaja_uudet_viestit_kpl"] > 0){
					$v_kpl = " (" . $_SESSION["kayttaja_uudet_viestit_kpl"] .")";
				} else {
					$v_kpl = "";
				}
								
				$sivut = array(
				"aineistonmuodostaja_saapuneet_tilaukset.php" => SAAPUNEET_TILAUKSET,
				"viranomainen_saapuneet_viestit.php" => SAAPUNEET_VIESTIT . $v_kpl);
				
                foreach ($sivut as $os => $nimi) {
					$val ="";
					if($os == $self || ($self=="paatos.php" && $os=="aineistonmuodostaja_saapuneet_tilaukset.php") || ($self=="viranomainen_hakemus_viestit.php" && $os=="aineistonmuodostaja_saapuneet_tilaukset.php") || ($self=="hakemus.php" && $os=="aineistonmuodostaja_saapuneet_tilaukset.php") || ($self=="aineistonmuodostus.php" && $os=="aineistonmuodostaja_saapuneet_tilaukset.php")) $val = " class=\"val\"";
					echo "<li class=\"vasen\"><a href=\"$os\"$val>$nimi</a></li>";
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