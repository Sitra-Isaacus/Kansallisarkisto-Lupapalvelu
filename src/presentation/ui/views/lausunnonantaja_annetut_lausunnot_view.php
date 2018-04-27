<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Annetut lausunnot (lausunnonantajan käyttöliittymä)
 *
 * Created: 4.2.2016
 */
 
include './ui/template/header.php';

$oletus_sivu_parametri = "sivu=hakemus_perustiedot&";
if($_SESSION["kayttaja_viranomainen"]=="v_VSSHP") $oletus_sivu_parametri = "";

?>

<div class="laatikko10">
	<?php if(isset($annetut_lausunnot) && !empty($annetut_lausunnot)){ ?>
		<?php echo ANNETUT_LAUSUNNOT_INFO; ?>
	<?php } else { ?>
		<h1><?php echo ANNETUT_LAUSUNNOT; ?></h1>
		<p>
			<?php echo EI_ANNETTUJA_LAUSUNTOJA; ?><br>
		</p>
	<?php } ?>
</div>

<?php if(isset($annetut_lausunnot) && !empty($annetut_lausunnot)){ ?>
	<div class="laatikko">
		<table class="taulu">
		<h3><?php echo ANNETUT_LAUSUNNOT; ?></h3>
			<thead>
				<tr>
					<th><?php echo TUTKIMUKSEN_NIMI; ?></th>
					<th></th>
					<th><?php echo DIAARINUMERO; ?></th>
					<th><?php echo LAUSUNNON_PYYTAJA; ?></th>
					<th><?php echo LAUSUNTO_ANNETTU; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if(isset($annetut_lausunnot)){
				for($i=0; $i < sizeof($annetut_lausunnot); $i++) {  ?>
						<tr>
							<td>
								<a href="hakemus.php?<?php echo $oletus_sivu_parametri; ?>hakemusversio_id=<?php echo $annetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $annetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->ID; ?>&hakemus_id=<?php echo $annetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->HakemusDTO->ID; ?>" title="Avaa lausunto"><?php echo htmlentities($annetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?></a>							
							</td>
							<td>
								<a href="hakemus_pdf.php?hakemusversio_id=<?php echo $annetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $annetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->ID; ?>">
									<img src="static/images/pdf.png" class="lisatoim">
								</a>							
							</td>
							<td><?php echo htmlentities($annetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->HakemusDTO->AsiaDTO->Diaarinumero,ENT_COMPAT, "UTF-8"); ?></td>
							<td><?php echo htmlentities($annetut_lausunnot[$i]->LausuntopyyntoDTO->KayttajaDTO_Pyytaja->Etunimi . " " . $annetut_lausunnot[$i]->LausuntopyyntoDTO->KayttajaDTO_Pyytaja->Sukunimi . " / " . koodin_selite($annetut_lausunnot[$i]->LausuntopyyntoDTO->KayttajaDTO_Pyytaja->Viranomaisen_rooliDTO->Viranomaisen_koodi, $_SESSION["kayttaja_kieli"]),ENT_COMPAT, "UTF-8"); ?></td>
							<td><a href="lausunto_pdf.php?lausunto_id=<?php echo $annetut_lausunnot[$i]->ID; ?>&hakemus_id=<?php echo $annetut_lausunnot[$i]->LausuntopyyntoDTO->TutkimusDTO->HakemusversioDTO->HakemusDTO->ID; ?>" title="Avaa lausunto"><?php echo muotoilepvm($annetut_lausunnot[$i]->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></a></td>
						</tr>
				<?php } }?>
			</tbody>
		</table>
	</div>
<?php } ?>

<?php
	include './ui/template/footer.php';
?>