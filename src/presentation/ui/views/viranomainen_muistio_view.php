<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the search page (viranomaisen käyttöliittymä)
 *
 * Created: 3.12.2015
 */
include './ui/template/header.php';
?>
<?php
	$yhdista = mysqli_connect("localhost","root","fmashanke","suvi");
	$tallennusaika = date('Y-m-d H:i:s');
	function uusiMuistio ($syoteparametrit) {
		//Tallenna jossain arvot muuttujille $FK_Hakemus, $FK_Kayttaja, jne.
		$uusiMuistio = "INSERT INTO `Muistio` VALUES ('', '$FK_Hakemus', '$FK_Kayttaja', '$FK_Viranomaisen_rooli', '$muistionTeksti', '$tallennusaika', '', '$nakyvyys')";
	}
	function muokkaaMuistio ($syoteparametrit) {
		//Tallenna jossain arvot muuttujille $FK_Hakemus, $FK_Kayttaja, jne.
		// $vanhanID = Muistio-taulussa olevan rivin ID, jolta tallennetaan tiedot talteen Muistio_alkuper-tauluun ja jonne tallennetaan uusi teksti ja muokkauksen aikaleima
		$vMuistioHae = "SELECT * FROM `Muistio` WHERE ID = $vanhanID;";
		$vMuistio = mysqli_fetch_array($yhdista, $vMuistioHae) or die(mysqli_error($yhdista));
		$vMuistioData = mysqli_query($vMuistio, MYSQLI_BOTH);
		//$vMuistioVie = "INSERT INTO `Muistio_alkuper` VALUES ('', $vMuistioData['ID'], $vMuistioData['FK_Hakemus'], $vMuistioData['FK_Kayttaja'], $vMuistioData['FK_Viranomaisen_rooli'], $vMuistioData['Muistion_teksti'], $vMuistioData['Luontiaika'], $tallennusaika, $vMuistioData['Nakyvyys']);";
		$muokkaaMuistio = "UPDATE `Muistio` SET Muistion_teksti = $uusiTeksti, Viim_tallennus = $tallennusaika WHERE ID = $vanhanID;";
	}
?>
<div class="laatikko">
	<form enctype="multipart/form-data" name="muistioUusi" id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
		<div class="paneeli_otsikko"><h3>Kirjoita uusi muistiinpano</h3></div>
		<div class="paneelin_tiedot">
			<div class="tieto" style="padding-bottom: 1em;">
				<textarea type="text" rows="8" class="muistio" name="muistio" placeholder="Kirjoita uusi muistiinpano"></textarea>
				<p>
					<input type="submit" class="nappi2 tall_muistio" name="tall_muistio" value="Tallenna muistiinpano" />
				</p>
			</div>
		</div>
	</form>
	<div class="paneeli_otsikko"><h3>Aiemmat muistiinpanot</h3></div>
	<div class='paneelin_tiedot'>
		<div class='tieto'>
		<?php
			$hakemusID = 22; //Esimerkki-ID
			$muistioHakemukselle = "SELECT `ID` FROM `Muistio` WHERE `FK_Hakemus` = $hakemusID ORDER BY `Luontiaika` DESC;";
			$dataMuistioHakemukselle = mysqli_query($yhdista, $muistioHakemukselle) or die(mysqli_error($yhdista));
			$muistiot = mysqli_fetch_array($dataMuistioHakemukselle, MYSQLI_BOTH);
			$x = 0;
			if(mysqli_num_rows($dataMuistioHakemukselle) > 0) {
				$muistiinpano = "SELECT `ID`, `FK_Kayttaja`, `FK_Viranomaisen_rooli`, `Muistion_teksti`, `Luontiaika`, `Viim_tallennus`, `Nakyvyys` FROM `Muistio`;";
				$dataMuistiinpano = mysqli_query($yhdista, $muistiinpano) or die(mysqli_error($yhdista));
				while ($muistio = mysqli_fetch_array($dataMuistiinpano, MYSQLI_BOTH)) {
					if ($x != 0) {echo "<hr />";}
					echo "<div class='muistio_aiempi'><p>";
					echo "<strong>Käyttäjän ID: ". $muistio['FK_Kayttaja'] ." (roolin ID: ". $muistio['FK_Viranomaisen_rooli'] .") ". date_format(date_create($muistio['Luontiaika']), 'd.m.Y, H.i') ."</strong><br />";
					if ($muistio['Viim_tallennus'] != NULL) {
						echo "Muokattu viimeksi ". date_format(date_create($muistio['Viim_tallennus']), 'd.m.Y, H.i') ."<br />";
					}
					echo "<a href='?muokkaa=". $muistio['ID'] ."' class='muistio_muokkaa ei_alleviivausta'>Muokkaa muistiinpanoa</a></p>";
					echo "<div class='muistio_txt'>";
					echo $muistio['Muistion_teksti'];
					echo "</div></div>";
					$x++;
				}
			}
		?>
		</div>
	</div>
</div>
<?php
	include './ui/template/footer.php';
?>