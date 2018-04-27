<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the views page (pääkäyttäjän käyttöliittymä)
 *
 * Created: 4.7.2016
 */
 
include './ui/template/header.php';

?>

<form enctype="multipart/form-data" name="luo_lomake" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">

	<div class="laatikko10">
		<h1><?php echo PAAKAYT_LUPAPALVELU; ?> (beta)</h1>
		<?php if($_SESSION["kayttaja_rooli"] == "rooli_viranomaisen_paak"){ ?>
			<p><?php echo TERVETULOA . " " .$_SESSION["kayttaja_nimi"] . " (" . koodin_selite($_SESSION["kayttaja_viranomainen"], $_SESSION["kayttaja_kieli"]) . ")." ; ?> </p>
		<?php } ?>
		<?php if($_SESSION["kayttaja_rooli"] == "rooli_lupapalvelun_paak"){ ?>
			<p><?php echo TERVETULOA . " " . $_SESSION["kayttaja_nimi"]; ; ?> </p>
		<?php } ?>
		<?php //if($_SESSION['Istunto']->Kayttaja->Valittu_rooli->roolin_koodi == "rooli_lupapalvelun_paak"){ ?>
			<br>
			<p>
				<input class="nappi" type="submit" value="Luo uusi lomake &raquo; " name="luo_lomake" >
			</p>
		<?php //} ?>
		<div id="block_container">
			<div id="block2">
				<div id="tallennettu_info" class="tallennettu_info" style="display: none;">
					<?php echo TIEDOT_TALLENNETTU; ?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="viesti_alue" style="display: none;">
		<div class="viesti"></div>
	</div>
	
	<?php if(!empty($lomakkeetDTO)){ ?>
		<div class="laatikko">
			<table class="taulu left">
				<h3>Lomakkeiden hallinta</h3>
				<thead>
					<tr>
						<th class="left">Lomakkeen nimi</th>
						<th class="left">Lomakkeen tyyppi</th>
						<th class="left">Tekijä</th>
						<th class="left">Lisäyspvm</th>
						<th class="center">Poista lomake</th>
					</tr>
				</thead>
				<tbody>
					<?php for($i=0; $i < sizeof($lomakkeetDTO); $i++){ ?>
					
						<?php if($lomakkeetDTO[$i]->ID==27 && $_SESSION["kayttaja_rooli"] == "rooli_viranomaisen_paak") continue; // Hardkoodattu sääntö (viranomaisen pääkäyttäjän ei tarvitse nähdä eettistä hakemusta) ?>
						
						<tr>
							<td>
								<?php if($lomakkeetDTO[$i]->ID==1 && $_SESSION["kayttaja_rooli"] == "rooli_viranomaisen_paak"){ ?>
									<a href="lomake_sivu.php?lomake_sivu_id=4&lomake_id=<?php echo $lomakkeetDTO[$i]->ID; ?>" title="Muokkaa lomaketta">
										<?php tulosta_teksti($lomakkeetDTO[$i]->Nimi); ?>
									</a>									
								<?php } else { ?>							
									<a href="lomake_perustiedot.php?lomake_id=<?php echo $lomakkeetDTO[$i]->ID; ?>" title="Muokkaa lomaketta">
										<?php tulosta_teksti($lomakkeetDTO[$i]->Nimi); ?>
									</a>								
								<?php } ?>
								
							</td>
							<td>
								<?php tulosta_teksti($lomakkeetDTO[$i]->Lomakkeen_tyyppi); ?>
							</td>
							<td>
								<?php tulosta_teksti($lomakkeetDTO[$i]->KayttajaDTO->Etunimi . " " . $lomakkeetDTO[$i]->KayttajaDTO->Sukunimi); ?>
							</td>
							<td>
								<?php echo tulosta_teksti(muotoilepvm($lomakkeetDTO[$i]->Lisayspvm, $_SESSION["kayttaja_kieli"])); ?>
							</td>
							<td class="center">
								<?php if($_SESSION["kayttaja_rooli"] == "rooli_lupapalvelun_paak" || ($_SESSION["kayttaja_id"] == $lomakkeetDTO[$i]->KayttajaDTO->ID)){ ?>
									<a class="ei_alleviivausta" onclick="return confirm('Haluatko varmasti poistaa lomakkeen?');" href="lomakkeet.php?poista_lomake=<?php echo $lomakkeetDTO[$i]->ID; ?>">
										<img width="16" height="16" alt="Poista lomake" src="static/images/erase.png">
									</a>
								<?php } else { ?>
									<img src="static/images/erase_gray.png" width="16" height="16">
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<br>
		</div>
	<?php } ?>
</form>

<?php
	include './ui/template/footer.php';
?>