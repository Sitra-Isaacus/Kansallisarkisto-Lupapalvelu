<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Erääntyvät luvat view
 *
 * Created: 15.10.2015
 */
 
$page_title = ER_LUVAT;
include './ui/template/header.php';

?>
<div class="laatikko10">
		<?php if(isset($vastaus["KayttoluvatDTO"]) && !empty($vastaus["KayttoluvatDTO"])){ ?>
			<?php echo ERAANTYVAT_INFO; ?>
		<?php } else { ?>
			<h1><?php echo PAATTYVAT_KAYTTTOLUVAT; ?></h1>
			<p>
				<?php echo EI_ERAANTYVIA; ?><br>
			</p>
		<?php } ?>
</div>
<?php if(isset($vastaus["KayttoluvatDTO"]) && !empty($vastaus["KayttoluvatDTO"])){ ?>
	<div class="laatikko">
		<?php if(!empty($vastaus["KayttoluvatDTO"])){ ?>
			<table class="taulu">
				<h3><?php echo ERAANTYVAT_KAYTTOLUVAT; ?></h3>
				<thead>
					<tr>
						<th><?php echo HAKEMUS; ?></th>
						<th><?php echo ORGANISAATIO; ?></th>
						<th><?php echo KAYTTOLUPA_PAATTYY; ?></th>
					</tr>
				</thead>
				<?php for($i=0; $i < sizeof($vastaus["KayttoluvatDTO"]); $i++) { ?>
					<tbody>
						<tr>
							<td><?php echo htmlentities($vastaus["KayttoluvatDTO"][$i]->PaatosDTO->HakemusDTO->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?></td>
							<td><?php echo koodin_selite($vastaus["KayttoluvatDTO"][$i]->PaatosDTO->HakemusDTO->Viranomaisen_koodi, $_SESSION["kayttaja_kieli"]); ?></td>
							<td><?php echo muotoilepvm($vastaus["KayttoluvatDTO"][$i]->Lakkaamispvm, $_SESSION["kayttaja_kieli"]); ?></td>
						</tr>
					</tbody>
				<?php } ?>
			</table><br>
		<?php } ?>
	</div>
<?php } ?>
<?php
	include './ui/template/footer.php';
?>