<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Viranomaisen saapuneet lausunnot view
 *
 * Created: 1.2.2016
 */
 
include './ui/template/header.php';

$oletus_sivu_parametri = "sivu=hakemus_perustiedot&";
if($_SESSION['kayttaja_viranomainen']=="v_VSSHP") $oletus_sivu_parametri = "";

?>

<div class="laatikko10">
	<?php if(!empty($vastaus["LausunnotDTO"]["Lukemattomat"]) || !empty($vastaus["LausunnotDTO"]["Luetut"])){ ?>
		<?php echo SAAPUNEET_LAUSUNNOT_INFO; ?>
	<?php } else { ?>
		<h1><?php echo SAAPUNEET_LAUSUNNOT; ?></h1>
		<p><?php echo EI_SAAPUNEITA_LAUSUNTOJA; ?></p>
	<?php } ?>
</div>

<?php if(!empty($vastaus["LausunnotDTO"]["Lukemattomat"]) || !empty($vastaus["LausunnotDTO"]["Luetut"])){ ?>
	<div class="laatikko">
		<?php
		if(!empty($lukemattomat_lausunnot)){ ?>
			<h3><?php echo LUKEMATTOMAT_LAUSUNNOT; ?></h3>
			<table class="taulu">
			<thead>
				<tr>
					<th><?php echo TUTKIMUKSEN_NIMI; ?></th>
					<th><?php echo LAUSUNNON_ANTAJA; ?></th>
					<th><?php echo LAUSUNTO_PYYDETTY; ?></th>
					<th><?php echo LAUSUNTO_ANNETTU; ?></th>
				</tr>
			</thead>
			<?php for($i=0; $i < sizeof($lukemattomat_lausunnot); $i++) { ?>
				<tbody>
					<tr>
						<td><a href="hakemus.php?<?php echo $oletus_sivu_parametri; ?>hakemusversio_id=<?php echo $lukemattomat_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $lukemattomat_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->ID; ?>&hakemus_id=<?php echo $lukemattomat_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->HakemusDTO->ID; ?>" title="Tarkastele lähetettyä hakemusta"><?php echo htmlentities($lukemattomat_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?></a></td>
						<td><?php echo htmlentities($lukemattomat_lausunnot[$i]->LausuntopyyntoDTO->KayttajaDTO_Antaja->Etunimi . " " . $lukemattomat_lausunnot[$i]->LausuntopyyntoDTO->KayttajaDTO_Antaja->Sukunimi . " / " . koodin_selite($lukemattomat_lausunnot[$i]->LausuntopyyntoDTO->KayttajaDTO_Antaja->Viranomaisen_rooliDTO->Viranomaisen_koodi, $_SESSION["kayttaja_kieli"]),ENT_COMPAT, "UTF-8"); ?></td>
						<td><a href="viranomainen_hakemus_lausunto.php?hakemus_id=<?php echo $lukemattomat_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->HakemusDTO->ID; ?>"><?php echo muotoilepvm($lukemattomat_lausunnot[$i]->LausuntopyyntoDTO->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></a></td>
						<td><a href="lausunto.php?lausunto_id=<?php echo $lukemattomat_lausunnot[$i]->ID; ?>&hakemus_id=<?php echo $lukemattomat_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->HakemusDTO->ID; ?>"><?php echo muotoilepvm($lukemattomat_lausunnot[$i]->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></a></td>
					</tr>
				</tbody>
			<?php }?>
		</table><br>
		<?php } ?>
		<?php
		if(!empty($luetut_lausunnot)){ ?>
			<table class="taulu">
			<h3><?php echo LUETUT_LAUSUNNOT; ?></h3>
			<thead>
				<tr>
					<th><?php echo TUTKIMUKSEN_NIMI; ?></th>
					<th><?php echo LAUSUNNON_ANTAJA; ?></th>
					<th><?php echo LAUSUNTO_PYYDETTY; ?></th>
					<th><?php echo LAUSUNTO_ANNETTU; ?></th>
				</tr>
			</thead>
			<?php for($i=0; $i < sizeof($luetut_lausunnot); $i++) { ?>
				<tbody>
					<tr>
						<td><a href="hakemus.php?<?php echo $oletus_sivu_parametri; ?>hakemusversio_id=<?php echo $luetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $luetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->ID; ?>&hakemus_id=<?php echo $luetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->HakemusDTO->ID; ?>" title="Tarkastele lähetettyä hakemusta"><?php echo htmlentities($luetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?></a></td>
						<td><?php echo htmlentities($luetut_lausunnot[$i]->LausuntopyyntoDTO->KayttajaDTO_Antaja->Etunimi . " " . $luetut_lausunnot[$i]->LausuntopyyntoDTO->KayttajaDTO_Antaja->Sukunimi . " / " . koodin_selite($luetut_lausunnot[$i]->LausuntopyyntoDTO->KayttajaDTO_Antaja->Viranomaisen_rooliDTO->Viranomaisen_koodi, $_SESSION["kayttaja_kieli"]),ENT_COMPAT, "UTF-8"); ?></td>
						<td><a href="viranomainen_hakemus_lausunto.php?hakemus_id=<?php echo $luetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->HakemusDTO->ID; ?>"><?php echo muotoilepvm($luetut_lausunnot[$i]->LausuntopyyntoDTO->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></a></td>
						<td><a href="lausunto.php?lausunto_id=<?php echo $luetut_lausunnot[$i]->ID; ?>&hakemus_id=<?php echo $luetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->HakemusDTO->ID; ?>"><?php echo muotoilepvm($luetut_lausunnot[$i]->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></a></td>
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