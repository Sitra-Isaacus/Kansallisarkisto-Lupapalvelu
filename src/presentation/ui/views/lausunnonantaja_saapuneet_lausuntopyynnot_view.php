<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the main page (lausunnonantajan käyttöliittymä)
 *
 * Created: 27.1.2016
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

$oletus_sivu_parametri = "sivu=hakemus_perustiedot&";
if($_SESSION["kayttaja_viranomainen"]=="v_VSSHP") $oletus_sivu_parametri = "";

?>

<div class="laatikko10">
	<h1><?php echo LAUSUNNONANTAJAN_PALVELU; ?></h1>
	<p><?php echo TERVETULOA . " " . htmlentities($_SESSION["kayttaja_nimi"],ENT_COMPAT, "UTF-8") . " (" . koodin_selite($_SESSION["kayttaja_viranomainen"], $_SESSION["kayttaja_kieli"]) . ")." ; ?>  <?php echo LAUSUNNONANTAJAN_ETUSIVUN_OHJE; ?></p>
</div>

<?php if(isset($lausuntopyynnot) && !empty($lausuntopyynnot)){ ?>
	<div class="laatikko">
		<table class="taulu">
		<h3><?php echo LAUSUNTOPYYNNOT; ?></h3>
			<thead>
				<tr>
					<th><?php echo TUTKIMUKSEN_NIMI; ?></th>
					<th></th>
					<th><?php echo DIAARINUMERO; ?></th>
					<th><?php echo LAUSUNNON_PYYTAJA; ?></th>
					<th><?php echo MAARAPAIVA; ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(isset($lausuntopyynnot)){
			for($i=0; $i < sizeof($lausuntopyynnot); $i++) {  ?>
					<tr>
						<td>
							<a href="hakemus.php?<?php echo $oletus_sivu_parametri; ?>hakemusversio_id=<?php echo $lausuntopyynnot[$i]->TutkimusDTO->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $lausuntopyynnot[$i]->TutkimusDTO->HakemusversioDTO->TutkimusDTO->ID; ?>&hakemus_id=<?php echo $lausuntopyynnot[$i]->TutkimusDTO->HakemusversioDTO->HakemusDTO->ID; ?>" title="Avaa hakemus"><?php echo htmlentities($lausuntopyynnot[$i]->TutkimusDTO->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?></a>							
						</td>
						<td>
							<a href="hakemus_pdf.php?hakemusversio_id=<?php echo $lausuntopyynnot[$i]->TutkimusDTO->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $lausuntopyynnot[$i]->TutkimusDTO->HakemusversioDTO->TutkimusDTO->ID; ?>">
								<img src="static/images/pdf.png" class="lisatoim">
							</a>							
						</td>
						<td><?php echo htmlentities($lausuntopyynnot[$i]->TutkimusDTO->HakemusversioDTO->HakemusDTO->AsiaDTO->Diaarinumero,ENT_COMPAT, "UTF-8"); ?></td>
						<td><?php echo htmlentities( $lausuntopyynnot[$i]->KayttajaDTO_Pyytaja->Etunimi . " " . $lausuntopyynnot[$i]->KayttajaDTO_Pyytaja->Sukunimi . " / " . koodin_selite($lausuntopyynnot[$i]->KayttajaDTO_Pyytaja->Viranomaisen_rooliDTO->Viranomaisen_koodi, $_SESSION["kayttaja_kieli"]),ENT_COMPAT, "UTF-8"); ?></td>
						<td><?php echo muotoilepvm($lausuntopyynnot[$i]->Lausunnon_maarapaiva, $_SESSION["kayttaja_kieli"]); ?></td>
						<td style="text-align:right;"><a href="lausunto.php?hakemus_id=<?php echo $lausuntopyynnot[$i]->TutkimusDTO->HakemusversioDTO->HakemusDTO->ID; ?>&lausunto_id=<?php echo $lausuntopyynnot[$i]->LausuntoDTO->ID; ?>" ><?php echo ANNA_LAUSUNTO; ?></a></td>
					</tr>
			<?php } }?>
			</tbody>
		</table>
	</div>
<?php } ?>

<?php
	include './ui/template/footer.php';
?>