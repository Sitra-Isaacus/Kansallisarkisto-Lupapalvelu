<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the main page (pääkäyttäjän käyttöliittymä)
 *
 * Created: 4.7.2016
 */
  
include './ui/template/header.php';

?>
<div class="laatikko10">
	<h1><?php echo PAAKAYT_LUPAPALVELU; ?> (beta)</h1>
	<?php if($_SESSION["kayttaja_rooli"] == "rooli_viranomaisen_paak"){ ?>
		<p><?php echo TERVETULOA . " " . $_SESSION["kayttaja_nimi"] . " (" . koodin_selite($_SESSION["kayttaja_viranomainen"], $_SESSION["kayttaja_kieli"]) . ")." ; ?> </p>
	<?php } ?>
	<?php if($_SESSION["kayttaja_rooli"] == "rooli_lupapalvelun_paak"){ ?>
		<p><?php echo TERVETULOA . " " . $_SESSION["kayttaja_nimi"]; ; ?> </p>
	<?php } ?>
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

<form enctype="multipart/form-data" name="roolit_form" id="roolit_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
	<?php if($_SESSION["kayttaja_rooli"] == "rooli_viranomaisen_paak"){ ?>
		<div class="laatikko">
			<table class="taulu">
				<h3><?php echo ROOLIEN_HALLINTA; ?></h3>
				<thead>
					<tr>
						<th><?php echo NIMI; ?></th>
						<?php foreach($VIRANOMAISEN_ROOLIT as $rooli_lyhenne => $rooli_selite){ ?>
							<th class="center"><?php echo koodin_selite($rooli_lyhenne, $_SESSION["kayttaja_kieli"]); ?></th>
						<?php } ?>
					</tr>
				</thead>
				<?php for($i=0; $i < sizeof($kayttajat); $i++){ ?>
					<tbody>
						<tr>
							<td> <?php echo $kayttajat[$i]->KayttajaDTO->Etunimi . " " . $kayttajat[$i]->KayttajaDTO->Sukunimi . "<br>" . $kayttajat[$i]->KayttajaDTO->Sahkopostiosoite; ?> </td>
							<?php foreach($VIRANOMAISEN_ROOLIT as $rooli_lyhenne => $rooli_selite){ 
								$rooliValittu = false;
								$valitunRoolinID = null;
								for($j=0; $j < sizeof($kayttajat[$i]->KayttajaDTO->Viranomaisen_roolitDTO); $j++){
									if($kayttajat[$i]->KayttajaDTO->Viranomaisen_roolitDTO[$j]->Viranomaisroolin_koodi==$rooli_lyhenne){
										$rooliValittu = true;
										$valitunRoolinID = $kayttajat[$i]->KayttajaDTO->Viranomaisen_roolitDTO[$j]->ID;
									}
								} ?>
								<td class="center">
									<?php if($rooliValittu){
										$kayttajaRoolitID = $rooli_lyhenne . "-" . $kayttajat[$i]->KayttajaDTO->ID . "-" . $valitunRoolinID;
									} else {
										$kayttajaRoolitID = $rooli_lyhenne . "-" . $kayttajat[$i]->KayttajaDTO->ID . "-0";
									} ?>
									<input class="kayttajaroolit" id="<?php echo $kayttajaRoolitID; ?>" type="checkbox" <?php if($rooliValittu){ echo "checked"; } ?> value="<?php echo $rooli_lyhenne; ?>">
								</td>
							<?php } ?>
						</tr>
					</tbody>
				<?php } ?>
			</table>
		</div>
	<?php } ?>
	
	<?php if($_SESSION["kayttaja_rooli"] == "rooli_lupapalvelun_paak"){ ?>
		<?php foreach ($viranomaiset as $organisaatio => $kayttajat) { ?>
			<div class="laatikko">
				<table class="taulu">
					<h3><?php echo ROOLIEN_HALLINTA . ": " . koodin_selite($organisaatio, $_SESSION["kayttaja_kieli"]); ?></h3>
					<thead>
						<tr>
							<th><?php echo NIMI; ?></th>
							<?php foreach($VIRANOMAISEN_ROOLIT as $rooli_lyhenne => $rooli_selite){ ?>
								<th class="center"><?php echo koodin_selite($rooli_lyhenne, $_SESSION["kayttaja_kieli"]); ?></th>
							<?php } ?>
						</tr>
					</thead>
					<?php for($i=0; $i < sizeof($kayttajat); $i++){ ?>
						<tbody>
							<tr>
								<td> <?php echo $kayttajat[$i]->KayttajaDTO->Etunimi . " " . $kayttajat[$i]->KayttajaDTO->Sukunimi; ?> </td>
								<?php foreach($VIRANOMAISEN_ROOLIT as $rooli_lyhenne => $rooli_selite){ 
									$rooliValittu = false;
									$valitunRoolinID = null;
									for($j=0; $j < sizeof($kayttajat[$i]->KayttajaDTO->Viranomaisen_roolitDTO); $j++){
										if($kayttajat[$i]->KayttajaDTO->Viranomaisen_roolitDTO[$j]->Viranomaisroolin_koodi==$rooli_lyhenne){
											$rooliValittu = true;
											$valitunRoolinID = $kayttajat[$i]->KayttajaDTO->Viranomaisen_roolitDTO[$j]->ID;
										}
									} ?>
									<td class="center">
										<?php if($rooliValittu){
											$kayttajaRoolitID = $rooli_lyhenne . "-" . $kayttajat[$i]->KayttajaDTO->ID . "-" . $valitunRoolinID . "-" . $kayttajat[$i]->Viranomaisen_koodi;
										} else {
											$kayttajaRoolitID = $rooli_lyhenne . "-" . $kayttajat[$i]->KayttajaDTO->ID . "-0-" . $kayttajat[$i]->Viranomaisen_koodi;
										} ?>
										<input class="kayttajaroolit" id="<?php echo $kayttajaRoolitID; ?>" type="checkbox" <?php if($rooliValittu){ echo "checked"; } ?> value="<?php echo $rooli_lyhenne; ?>">
									</td>
								<?php } ?>
							</tr>
						</tbody>
					<?php } ?>
				</table>
			</div>
		<?php } ?>
	<?php } ?>
</form>
<?php
	include './ui/template/footer.php';
?>