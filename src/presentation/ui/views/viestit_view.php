<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Viestit view
 *
 * Created: 15.10.2015
 */
 
include './ui/template/header.php';
?>

<div class="laatikko10">
		<?php if(!empty($uudet_saapuneet_viestit) || !empty($vanhat_saapuneet_viestit)){ ?>
			<?php echo VIESTIT_INFO; ?>
		<?php } else { ?>
			<h1><?php echo VIESTIT; ?></h1>
			<p>
				<?php echo EI_VIESTEJA; ?><br>
			</p>
		<?php } ?>
</div>

<?php if(!empty($vanhat_saapuneet_viestit) || !empty($uudet_saapuneet_viestit)){ ?>
	<div class="laatikko">
		<?php
		if(!is_null($uudet_saapuneet_viestit) && !empty($uudet_saapuneet_viestit)){ ?>
			<table class="taulu">
			<h3><?php echo UUDET_VIESTIT; ?></h3>
			<thead>
				<tr>
					<th><?php echo TUTKIMUKSEN_NIMI ?></th>
					<th><?php echo HAKEMUS ?></th>
					<th><?php echo PVM ?></th>
					<th><?php echo VIESTIN_LAHETTAJA ?></th>
				</tr>
			</thead>
			<?php for($i=0; $i < sizeof($uudet_saapuneet_viestit); $i++) { ?>
				<tbody>
					<tr>
						<?php 
						$linkki = null;
						if($_SESSION["kayttaja_rooli"] == "rooli_hakija"){
							$linkki = "hakemus_viestit.php?hakemus_id=" . $uudet_saapuneet_viestit[$i]->HakemusDTO->ID;
						} 
						if($_SESSION["kayttaja_rooli"] == "rooli_eettisen_puheenjohtaja" || $_SESSION["kayttaja_rooli"] == "rooli_eettisensihteeri" || $_SESSION["kayttaja_rooli"] == "rooli_kasitteleva" || $_SESSION["kayttaja_rooli"] == "rooli_paattava" || $_SESSION["kayttaja_rooli"] == "rooli_lausunnonantaja" || $_SESSION["kayttaja_rooli"] == "rooli_aineistonmuodostaja"){ 
							$linkki = "viranomainen_hakemus_viestit.php?hakemus_id=" . $uudet_saapuneet_viestit[$i]->HakemusDTO->ID;
						}
						?>
						<td><a href="<?php echo $linkki; ?>"><?php echo htmlentities($uudet_saapuneet_viestit[$i]->HakemusDTO->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?></a></td>
						<td><?php echo $uudet_saapuneet_viestit[$i]->HakemusDTO->Hakemuksen_tunnus; ?></td>
						<td><?php echo muotoilepvm($uudet_saapuneet_viestit[$i]->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
						<td><?php
							if($_SESSION["kayttaja_rooli"] == "rooli_hakija"){
								$output = $uudet_saapuneet_viestit[$i]->KayttajaDTO_Lahettaja->Etunimi . " " . $uudet_saapuneet_viestit[$i]->KayttajaDTO_Lahettaja->Sukunimi . " (" . koodin_selite($uudet_saapuneet_viestit[$i]->HakemusDTO->Viranomaisen_koodi,$lang->getCurrentLanguage()) . ")";
							} else {
								$output = $uudet_saapuneet_viestit[$i]->KayttajaDTO_Lahettaja->Etunimi . " " . $uudet_saapuneet_viestit[$i]->KayttajaDTO_Lahettaja->Sukunimi;
							}
							echo htmlentities($output,ENT_COMPAT, "UTF-8");
							?>
						</td>
					</tr>
				</tbody>
			<?php }?>
		</table><br>
		<?php } ?>
		<?php
		if(!is_null($vanhat_saapuneet_viestit) && !empty($vanhat_saapuneet_viestit)){ ?>
		<table class="taulu">
			<h3><?php echo LUETUT_VIESTIT; ?></h3>
			<thead>
				<tr>
					<th><?php echo TUTKIMUKSEN_NIMI ?></th>
					<th><?php echo HAKEMUS ?></th>
					<th><?php echo PVM ?></th>
					<th><?php echo VIESTIN_LAHETTAJA ?></th>
				</tr>
			</thead>
			<?php for($i=0; $i < sizeof($vanhat_saapuneet_viestit); $i++) { ?>
				<tbody>
					<tr>
						<td><a href="<?php if($_SESSION["kayttaja_rooli"] == "rooli_hakija"){ echo "hakemus_viestit.php?hakemus_id=" . $vanhat_saapuneet_viestit[$i]->HakemusDTO->ID; } if($_SESSION["kayttaja_rooli"] == "rooli_eettisen_puheenjohtaja" || $_SESSION["kayttaja_rooli"] == "rooli_eettisensihteeri" || $_SESSION["kayttaja_rooli"] == "rooli_kasitteleva" || $_SESSION["kayttaja_rooli"] == "rooli_paattava" || $_SESSION["kayttaja_rooli"] == "rooli_lausunnonantaja" || $_SESSION["kayttaja_rooli"] == "rooli_aineistonmuodostaja"){ echo "viranomainen_hakemus_viestit.php?hakemus_id=" . $vanhat_saapuneet_viestit[$i]->HakemusDTO->ID; } ?>"><?php echo htmlentities($vanhat_saapuneet_viestit[$i]->HakemusDTO->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?></a></td>
						<td><?php echo $vanhat_saapuneet_viestit[$i]->HakemusDTO->Hakemuksen_tunnus; ?></td>
						<td><?php echo muotoilepvm($vanhat_saapuneet_viestit[$i]->Lisayspvm,$_SESSION["kayttaja_kieli"]); ?></td>
						<td>
						<?php 
							if($_SESSION["kayttaja_rooli"] == "rooli_hakija"){
								$output = $vanhat_saapuneet_viestit[$i]->KayttajaDTO_Lahettaja->Etunimi . " " . $vanhat_saapuneet_viestit[$i]->KayttajaDTO_Lahettaja->Sukunimi . " (" . koodin_selite($vanhat_saapuneet_viestit[$i]->HakemusDTO->Viranomaisen_koodi,$lang->getCurrentLanguage()) . ")";
							} else {
								$output = $vanhat_saapuneet_viestit[$i]->KayttajaDTO_Lahettaja->Etunimi . " " . $vanhat_saapuneet_viestit[$i]->KayttajaDTO_Lahettaja->Sukunimi;
							}
							echo htmlentities($output,ENT_COMPAT, "UTF-8");
						?>
						</td>
					</tr>
				</tbody>
			<?php }?>
		</table><br>
		<?php } ?>
	</div>
<?php } ?>
<?php
	include './ui/template/footer.php';
?>