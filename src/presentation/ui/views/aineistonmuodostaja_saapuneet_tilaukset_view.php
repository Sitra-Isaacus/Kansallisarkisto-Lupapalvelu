<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the main page (aineistonmuodostajan käyttöliittymä)
 *
 * Created: 28.4.2016
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>

<?php for($j=0; $j < sizeof($hakemukset); $j++){ ?>
	<div class="messagepop pop" id="kasittelypop-n<?php echo $hakemukset[$j]->PaatosDTO->AineistotilausDTO->ID; ?>">
		<form enctype="multipart/form-data" name="laheta_kasittely" id="laheta_kasittely-n<?php echo$hakemukset[$j]->PaatosDTO->AineistotilausDTO->ID; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			
			<p><b><?php echo OTA_AIN_VIR_KAS; ?></b></p>
			
			<label for="kasittelija"><?php echo KASITTELIJA; ?></label><br><br>
			<select name="kasittelija" class="laatikko_p2">
				<option selected disabled>Valitse käsittelijä</option>
				<?php for($i=0; $i < sizeof($aineistonmuodostajat); $i++){ ?>
					<option <?php if(isset($hakemukset[$j]->PaatosDTO->AineistotilausDTO->Aineistonmuodostaja) && $hakemukset[$j]->PaatosDTO->AineistotilausDTO->Aineistonmuodostaja==$aineistonmuodostajat[$i]->KayttajaDTO->ID) echo "selected"; ?> value="<?php echo $aineistonmuodostajat[$i]->KayttajaDTO->ID;?>"><?php echo $aineistonmuodostajat[$i]->KayttajaDTO->Etunimi . " " . $aineistonmuodostajat[$i]->KayttajaDTO->Sukunimi; ?></option>
				<?php } ?>
			</select>
			<br><br>
			<input class="laatikko_p2" type="submit" value="<?php echo TALLENNA; ?>" name="tallenna_kasittelija" id="message_submit"/><br>
			<input type="hidden" name="aineistotilaus_id" value="<?php echo $hakemukset[$j]->PaatosDTO->AineistotilausDTO->ID; ?>">
			<p id="close-n<?php echo $hakemukset[$j]->PaatosDTO->AineistotilausDTO->ID; ?>" class="close"><input class="laatikko_p2" type="button" value="<?php echo PERUUTA; ?>"/></p>
		</form>
	</div>
<?php } ?>

<div style="float: right; margin: 5px 35px 1em 3em;">
	<h5><?php echo KUVAKK; ?></h5>
	<p><img src='static/images/kasittelija.png' class='lisatoim' alt='' /> <?php echo VAIHDA_KASITTELIJAA; ?></p>
	<p><img class="lisatoim" src="static/images/pdf.png"> <?php echo LATAA_HAK_PDF; ?></p>
</div>

<div class="laatikko10">
	<h1><?php echo AINEISTONMUODOSTAJAN_PALVELU; ?></h1>
	<p><?php echo TERVETULOA . " " . $_SESSION["kayttaja_nimi"] . " (" . koodin_selite($_SESSION["kayttaja_viranomainen"], $_SESSION["kayttaja_kieli"]) . ")." ; ?>  <?php echo AINMUOD_ETUSIVUN_OHJE; ?> </p>
	<br>
</div>

<?php if(isset($hakemukset) && !empty($hakemukset)){ ?>
	<div class="laatikko">
		<table class="taulu">
		<h3><?php echo AINEISTOPYYNNOT; ?></h3>
			<thead>
				<tr>
					<th><?php echo TUTKIMUKSEN_NIMI; ?></th>
					<th></th>
					<th><?php echo DIAARINUMERO; ?></th>
					<th><?php echo AINEISTOTILAUKSEN_TILA; ?></th>
					<th><?php echo TILAUSPVM; ?></th>
					<th><?php echo TILAUKSEN_KASITTELIJA; ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(isset($hakemukset)){
			for($i=0; $i < sizeof($hakemukset); $i++) {  ?>
					<tr>
						<td>
							<a href="hakemus.php?hakemusversio_id=<?php echo $hakemukset[$i]->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $hakemukset[$i]->HakemusversioDTO->TutkimusDTO->ID; ?>&hakemus_id=<?php echo $hakemukset[$i]->ID; ?>" title="Avaa hakemus"><?php echo htmlentities($hakemukset[$i]->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?></a>								
						</td>
						<td>
							<a href="hakemus_pdf.php?hakemusversio_id=<?php echo $hakemukset[$i]->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $hakemukset[$i]->HakemusversioDTO->TutkimusDTO->ID; ?>">
								<img src="static/images/pdf.png" class="lisatoim">
							</a>							
						</td>
						<td><?php echo htmlentities($hakemukset[$i]->AsiaDTO->Diaarinumero,ENT_COMPAT, "UTF-8"); ?></td>
						<td><?php echo koodin_selite($hakemukset[$i]->PaatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi, $_SESSION["kayttaja_kieli"]); ?></td>
						<td><?php echo muotoilepvm($hakemukset[$i]->PaatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
						<td><?php if(isset($hakemukset[$i]->PaatosDTO->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja->Etunimi)){ 
								echo $hakemukset[$i]->PaatosDTO->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja->Etunimi . " " . $hakemukset[$i]->PaatosDTO->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja->Sukunimi; 
							} else { ?> 
								<a href="#" id="n<?php echo $hakemukset[$i]->PaatosDTO->AineistotilausDTO->ID; ?>" class="kasittely"><?php echo OTA_KASITTELYYN2; ?></a>
							<?php } ?>
						</td>
						<td>
							<?php if(!is_null($hakemukset[$i]->PaatosDTO->AineistotilausDTO->Aineistonmuodostaja) && $hakemukset[$i]->PaatosDTO->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_keskenerainen"){ ?>
								<div class="tooltip">
									<img src='static/images/kasittelija.png' class='lisatoim' alt='' />
									<span class='tooltiptext2'><a href="#" id="n<?php echo $hakemukset[$i]->PaatosDTO->AineistotilausDTO->ID; ?>" class="kasittely"><?php echo VAIHDA_KASITTELIJAA; ?></a></span>
								</div>
							<?php } ?>	
						</td>
					</tr>
			<?php } }?>
			</tbody>
		</table>
	</div>
<?php } ?>

<?php
	include './ui/template/footer.php';
?>