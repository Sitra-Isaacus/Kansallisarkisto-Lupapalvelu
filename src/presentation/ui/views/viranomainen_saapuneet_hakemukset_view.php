<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the main page (viranomaisen käyttöliittymä)
 *
 * Created: 30.11.2015
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';
include './ui/template/info_notification.php';

$oletus_sivu_parametri = "sivu=hakemus_perustiedot&";
if($_SESSION["kayttaja_viranomainen"]=="v_VSSHP") $oletus_sivu_parametri = "";

?>

<?php for($j=0; $j < sizeof($hakemuksetDTO_kasiteltavat); $j++){ ?>
	<div class="messagepop pop" id="kasittelypop-n<?php echo $hakemuksetDTO_kasiteltavat[$j]->ID; ?>">
		<form enctype="multipart/form-data" name="laheta_kasittely" id="laheta_kasittely-n<?php echo $hakemuksetDTO_kasiteltavat[$j]->ID; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			
			<p><b><?php echo OTA_HAK_VIR_KAS; ?></b></p>
			
			<label for="kasittelija"><?php echo KASITTELIJA; ?></label><br><br>
			<select name="kasittelija" class="laatikko_p2">
				<option selected disabled><?php echo VAL_KAS; ?></option>
				<?php for($i=0; $i < sizeof($vastaus["Viranomaisten_roolitDTO"]["Kasittelijat"]); $i++){ ?>
					<option <?php if(isset($hakemuksetDTO_kasiteltavat[$j]->PaatosDTO->Kasittelija) && $hakemuksetDTO_kasiteltavat[$j]->PaatosDTO->Kasittelija==$vastaus["Viranomaisten_roolitDTO"]["Kasittelijat"][$i]->KayttajaDTO->ID) echo "selected"; ?> value="<?php echo $vastaus["Viranomaisten_roolitDTO"]["Kasittelijat"][$i]->KayttajaDTO->ID;?>"><?php echo $vastaus["Viranomaisten_roolitDTO"]["Kasittelijat"][$i]->KayttajaDTO->Etunimi . " " . $vastaus["Viranomaisten_roolitDTO"]["Kasittelijat"][$i]->KayttajaDTO->Sukunimi; ?></option>
				<?php } ?>
			</select>
			<br><br>
			<input class="laatikko_p2" type="submit" value="<?php echo TALLENNA; ?>" name="tallenna_kasittelija" id="message_submit"/><br>
			<input type="hidden" id="hakemus_id" name="hakemus_id" value="<?php echo $hakemuksetDTO_kasiteltavat[$j]->ID; ?>">
			<p id="close-n<?php echo $hakemuksetDTO_kasiteltavat[$j]->ID; ?>" class="close"><input class="laatikko_p2" type="button" value="<?php echo PERUUTA; ?>"/></p>
		
		</form>
	</div>
<?php } ?>

<div style="float: right; margin: 0 0 1em 3em;">
	<h5><?php echo KUVAKK; ?></h5>
	<p><img src='static/images/kasittelija.png' class='lisatoim' alt='' /> <?php echo VAIHDA_KASITTELIJAA; ?></p>
	<p><img src='static/images/hakemushistoria_off.png' class='lisatoim' alt='' /> <?php echo NAY_HIST; ?></p>
	<p><img src='static/images/rinnakkaishakemuksia_off.png' class='lisatoim' alt='' /> <?php echo NAY_RINN; ?></p>
	<p><img class="lisatoim" src="static/images/pdf.png"> <?php echo LATAA_HAK_PDF; ?></p>
</div>

<div class="laatikko10">

	<h1><?php echo VIRANOMAISEN_KAYTTOLUPAPALVELU; ?></h1>
	<p><?php echo TERVETULOA . " " . $_SESSION["kayttaja_nimi"] . " (" . koodin_selite($_SESSION["kayttaja_viranomainen"], $_SESSION["kayttaja_kieli"]) . ")." ; ?>
		<?php echo VIR_ETUSIVU_INFO; ?>
	</p><br>
	
	<a class="nappi" href="#omat"><?php echo OMAT_HAKEMUKSET; ?></a>
	<a class="nappi" href="#uudet"><?php echo UUDET_HAKEMUKSET; ?></a>
	<a class="nappi" href="#avatut"><?php echo AVATUT_HAKEMUKSET; ?></a>
	<a class="nappi" href="#paatetyt"><?php echo PAATETYT_HAKEMUKSET; ?></a>	
	
</div>

<?php if(!empty($vastaus["HakemuksetDTO"]["Omat"])){ ?>
	<p>
	<div class="laatikko">
		<table class="taulu table_saapuneet_hakemukset">
			<a name="omat"></a>
			<h3><?php echo OMAT_HAKEMUKSET; ?></h3>
			<thead>
				<tr class="vo_taulu">
					<th><p class="a_sort"><a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Tutkimuksen_nimi&jarjestys_kohde=Omat"><?php echo TUTKIMUKSEN_NIMI; ?>
						<img <?php if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> ></a></p>
					</th>
					<th></th>
					<th><p class="a_sort"><a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_yhteyshenkilo&jarjestys_kohde=Omat"><?php echo rooli_hakija; ?>
						<img <?php if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> ></a></p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_tunnus&jarjestys_kohde=Omat"><?php echo HAKEMUS; ?>
								<img <?php if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_tila&jarjestys_kohde=Omat">
								<?php echo TILA; ?>
								<img <?php if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Tilan_pvm&jarjestys_kohde=Omat">
								<?php echo TILAN_PVM; ?>
								<img <?php if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Kasittelijan_nimi" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Kasittelijan_nimi&jarjestys_kohde=Omat">
								<?php echo KASITTELIJA; ?>
								<img <?php if($jarjestys_kentta=="Kasittelijan_nimi" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Kasittelijan_nimi" && $jarjestys_kohde=="Omat" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th class="right"><?php echo LISATOIM; ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($vastaus["HakemuksetDTO"]["Omat"])){
				for($i=0; $i < sizeof($vastaus["HakemuksetDTO"]["Omat"]); $i++) {  ?>
						<tr <?php if($vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat"){ echo "style='display: none;'"; } ?>>
							<td class="tutnimi"><?php if(isset($vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi) && $vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ ?> 
								<a href="hakemus.php?<?php echo $oletus_sivu_parametri; ?>tutkimus_id=<?php echo $vastaus["HakemuksetDTO"]["Omat"][$i]->HakemusversioDTO->TutkimusDTO->ID; ?>&hakemusversio_id=<?php echo $vastaus["HakemuksetDTO"]["Omat"][$i]->HakemusversioDTO->ID; ?>&hakemus_id=<?php echo $vastaus["HakemuksetDTO"]["Omat"][$i]->ID; ?>" title="Avaa hakemus"> 							
								<?php } ?> 
								<?php echo htmlentities($vastaus["HakemuksetDTO"]["Omat"][$i]->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?><?php if(isset($vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi) && $vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ ?></a><?php } ?>																
							</td>							
							<td>
								<?php if(isset($vastaus["HakemuksetDTO"]["Omat"][$i]->PaatosDTO->Palautettu_kasittelyyn) && $vastaus["HakemuksetDTO"]["Omat"][$i]->PaatosDTO->Palautettu_kasittelyyn==1){ ?>
									<div class='tooltip'>
										<img src="static/images/info.png" style="width: 20px; height: 20px;">
										<span class='tooltiptext2'><?php echo HAK_PAL_KAS_INFO; ?></span>
									</div>
								<?php } else { ?>														
									<?php if($vastaus["HakemuksetDTO"]["Omat"][$i]->HakemusversioDTO->Hakemuksen_tyyppi=="tayd_hak"){ ?>
										<div class='tooltip'>
											<img src="static/images/info.png" style="width: 20px; height: 20px;">
											<span class='tooltiptext2'><?php echo HAKEMUSTA_TAYD; ?></span>
										</div>
									<?php } ?>
								<?php } ?>	
							</td>	
							<td><?php echo $vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_yhteyshenkilo; ?></td>
							<td>
								<?php echo $vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tunnus; ?>										
							</td>
							<td>
								<?php echo koodin_selite($vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi, $_SESSION["kayttaja_kieli"]); ?>								
							</td>
							<td><?php echo muotoilepvm($vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tilaDTO->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
							<td><?php if($vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ echo $vastaus["HakemuksetDTO"]["Omat"][$i]->PaatosDTO->KayttajaDTO_Kasittelija->Etunimi . " " . $vastaus["HakemuksetDTO"]["Omat"][$i]->PaatosDTO->KayttajaDTO_Kasittelija->Sukunimi; } ?></td>
							<td class="right">
															
								<?php if($vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu" && $vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_val" && $vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_paat"){ ?>
									<div class='tooltip'>
										<img src='static/images/kasittelija.png' class='lisatoim' alt='' />
										<span class='tooltiptext2'><a href="#" id="n<?php echo $vastaus["HakemuksetDTO"]["Omat"][$i]->ID; ?>" class="kasittely"><?php echo VAIHDA_KASITTELIJAA; ?></a></span>
									</div>
								<?php } ?>
								<?php rinnakkaishakemukset($vastaus, "Omat", $i, $_SESSION["kayttaja_kieli"]); ?>
								<?php hakemushistoria($vastaus, "Omat", $i, $_SESSION["kayttaja_kieli"], $vastaus["HakemuksetDTO"]["Omat"][$i]->Hakemuksen_tunnus); ?>	
								
								<?php //if(sizeof($vastaus["HakemuksetDTO"]["Omat"][$i]->hakemushistoria_HakemuksetDTO) <= 1){ ?>
									<a href="hakemus_pdf.php?hakemusversio_id=<?php echo $vastaus["HakemuksetDTO"]["Omat"][$i]->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $vastaus["HakemuksetDTO"]["Omat"][$i]->HakemusversioDTO->TutkimusDTO->ID; ?>">
										<img class="lisatoim" src="static/images/pdf.png">
									</a>
								<?php //} ?>
								
							</td>
						</tr>
				<?php }
			} ?>
			</tbody>
		</table>
	</div>
	</p>
<?php } ?>

<?php if(!empty($vastaus["HakemuksetDTO"]["Uudet"])){ ?>
	<p>
	<div class="laatikko">
		<table class="taulu table_saapuneet_hakemukset">
			<a name="uudet"></a>
			<h3><?php echo UUDET_HAKEMUKSET; ?></h3>
			<thead>
				<tr class="vo_taulu">
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Tutkimuksen_nimi&jarjestys_kohde=Uudet">
								<?php echo TUTKIMUKSEN_NIMI; ?>
								<img <?php if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th><p class="a_sort"><a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_yhteyshenkilo&jarjestys_kohde=Uudet"><?php echo rooli_hakija; ?>
						<img <?php if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> ></a></p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_tunnus&jarjestys_kohde=Uudet">
								<?php echo HAKEMUS; ?>
								<img <?php if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_tila&jarjestys_kohde=Uudet">
								<?php echo TILA; ?>
								<img <?php if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Tilan_pvm&jarjestys_kohde=Uudet">
								<?php echo TILAN_PVM; ?>
								<img <?php if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Uudet" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th><p class="a_sort"><?php echo KASITTELIJA; ?></p></th>
					<th class="right"><?php echo LISATOIM; ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($vastaus["HakemuksetDTO"]["Uudet"])){
				for($i=0; $i < sizeof($vastaus["HakemuksetDTO"]["Uudet"]); $i++) {  ?>
						<tr>
							<td class="tutnimi"><?php if(isset($vastaus["HakemuksetDTO"]["Uudet"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi) && $vastaus["HakemuksetDTO"]["Uudet"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ ?> 								
								<a href="hakemus.php?<?php echo $oletus_sivu_parametri; ?>tutkimus_id=<?php echo $vastaus["HakemuksetDTO"]["Uudet"][$i]->HakemusversioDTO->TutkimusDTO->ID; ?>&hakemusversio_id=<?php echo $vastaus["HakemuksetDTO"]["Uudet"][$i]->HakemusversioDTO->ID; ?>&hakemus_id=<?php echo $vastaus["HakemuksetDTO"]["Uudet"][$i]->ID; ?>" title="Avaa hakemus"> <?php } ?> <?php echo htmlentities($vastaus["HakemuksetDTO"]["Uudet"][$i]->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?><?php if(isset($vastaus["HakemuksetDTO"]["Uudet"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi) && $vastaus["HakemuksetDTO"]["Uudet"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ ?></a><?php } ?>								
							</td>
							<td><?php echo $vastaus["HakemuksetDTO"]["Uudet"][$i]->Hakemuksen_yhteyshenkilo; ?></td>
							<td>
								<?php echo $vastaus["HakemuksetDTO"]["Uudet"][$i]->Hakemuksen_tunnus; ?>								
							</td>
							<td>
								<?php echo koodin_selite($vastaus["HakemuksetDTO"]["Uudet"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi, $_SESSION["kayttaja_kieli"]); ?>								
							</td>
							<td><?php echo muotoilepvm($vastaus["HakemuksetDTO"]["Uudet"][$i]->Hakemuksen_tilaDTO->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
							<td><?php if($vastaus["HakemuksetDTO"]["Uudet"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ ?>
								<?php echo $vastaus["HakemuksetDTO"]["Uudet"][$i]->Kasittelija; ?>
									<p>
										<a href="#" id="n<?php echo $vastaus["HakemuksetDTO"]["Uudet"][$i]->ID; ?>" class="kasittely">Ota käsittelyyn</a>
									</p>
								<?php } ?>
							</td>
							<td class="right">
							
								<?php rinnakkaishakemukset($vastaus, "Uudet", $i, $_SESSION["kayttaja_kieli"]); ?>
								<?php hakemushistoria($vastaus, "Uudet", $i, $_SESSION["kayttaja_kieli"]); ?>

								<?php //if(sizeof($vastaus["HakemuksetDTO"]["Uudet"][$i]->hakemushistoria_HakemuksetDTO) <= 1){ ?>
									<a href="hakemus_pdf.php?hakemusversio_id=<?php echo $vastaus["HakemuksetDTO"]["Uudet"][$i]->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $vastaus["HakemuksetDTO"]["Uudet"][$i]->HakemusversioDTO->TutkimusDTO->ID; ?>">
										<img class="lisatoim" src="static/images/pdf.png">
									</a>
								<?php //} ?>
								
							</td>
						</tr>
				<?php }
			} ?>
			</tbody>
		</table>
	</div>
	</p>
<?php } ?>

<?php if(!empty($vastaus["HakemuksetDTO"]["Avatut"])){ ?>
	<p>
	<div class="laatikko">
		<table class="taulu table_saapuneet_hakemukset">
			<a name="avatut"></a>
			<h3><?php echo AVATUT_HAKEMUKSET; ?></h3>
			<thead>
				<tr class="vo_taulu">
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Tutkimuksen_nimi&jarjestys_kohde=Avatut">
								<?php echo TUTKIMUKSEN_NIMI; ?>
								<img <?php if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th><p class="a_sort"><a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_yhteyshenkilo&jarjestys_kohde=Avatut"><?php echo rooli_hakija; ?>
						<img <?php if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> ></a></p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_tunnus&jarjestys_kohde=Avatut">
								<?php echo HAKEMUS; ?>
								<img <?php if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_tila&jarjestys_kohde=Avatut">
								<?php echo TILA; ?>
								<img <?php if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Tilan_pvm&jarjestys_kohde=Avatut">
								<?php echo TILAN_PVM; ?>
								<img <?php if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Kasittelijan_nimi" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Kasittelijan_nimi&jarjestys_kohde=Avatut">
								<?php echo KASITTELIJA; ?>
								<img <?php if($jarjestys_kentta=="Kasittelijan_nimi" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Kasittelijan_nimi" && $jarjestys_kohde=="Avatut" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th class="right"><?php echo LISATOIM; ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($vastaus["HakemuksetDTO"]["Avatut"])){
				for($i=0; $i < sizeof($vastaus["HakemuksetDTO"]["Avatut"]); $i++) {  ?>
						<tr>
							<td class="tutnimi">
								<?php if(isset($vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi) && $vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ ?> 
									<a href="hakemus.php?<?php echo $oletus_sivu_parametri; ?>tutkimus_id=<?php echo $vastaus["HakemuksetDTO"]["Avatut"][$i]->HakemusversioDTO->TutkimusDTO->ID; ?>&hakemusversio_id=<?php echo $vastaus["HakemuksetDTO"]["Avatut"][$i]->HakemusversioDTO->ID; ?>&hakemus_id=<?php echo $vastaus["HakemuksetDTO"]["Avatut"][$i]->ID; ?>" title="Avaa hakemus"> 
								<?php } ?> 
								<?php echo htmlentities($vastaus["HakemuksetDTO"]["Avatut"][$i]->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?><?php if(isset($vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi) && $vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ ?></a><?php } ?>								
							</td>
							<td><?php echo $vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_yhteyshenkilo; ?></td>
							<td>
								<?php echo $vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_tunnus; ?>								
							</td>
							<td>
								<?php echo koodin_selite($vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi, $_SESSION["kayttaja_kieli"]); ?>
							</td>
							<td><?php echo muotoilepvm($vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_tilaDTO->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
							<td><?php if($vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ echo $vastaus["HakemuksetDTO"]["Avatut"][$i]->PaatosDTO->KayttajaDTO_Kasittelija->Etunimi . " " . $vastaus["HakemuksetDTO"]["Avatut"][$i]->PaatosDTO->KayttajaDTO_Kasittelija->Sukunimi; } ?></td>
							<td class="right">
								<?php if($vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu" && $vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_val" && $vastaus["HakemuksetDTO"]["Avatut"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_paat"){ ?>
									<div class='tooltip'>
										<img src='static/images/kasittelija.png' class='lisatoim' alt='' />
										<span class='tooltiptext2'><a href="#" id="n<?php echo $vastaus["HakemuksetDTO"]["Avatut"][$i]->ID; ?>" class="kasittely"><?php echo VAIHDA_KASITTELIJAA; ?></a></span>
									</div>
								<?php } ?>
								
								<?php rinnakkaishakemukset($vastaus, "Avatut", $i, $_SESSION["kayttaja_kieli"]); ?>
								<?php hakemushistoria($vastaus, "Avatut", $i, $_SESSION["kayttaja_kieli"]); ?>

								<?php //if(sizeof($vastaus["HakemuksetDTO"]["Avatut"][$i]->hakemushistoria_HakemuksetDTO) <= 1){ ?>
									<a href="hakemus_pdf.php?hakemusversio_id=<?php echo $vastaus["HakemuksetDTO"]["Avatut"][$i]->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $vastaus["HakemuksetDTO"]["Avatut"][$i]->HakemusversioDTO->TutkimusDTO->ID; ?>">
										<img class="lisatoim" src="static/images/pdf.png">
									</a>
								<?php //} ?>
								
							</td>
						</tr>
				<?php }
			} ?>
			</tbody>
		</table>
	</div>
	</p>
<?php } ?>

<?php if(!empty($vastaus["HakemuksetDTO"]["Paatetyt"])){ ?>
	<p>
	<div class="laatikko">
		<table class="taulu table_saapuneet_hakemukset">
			<a name="paatetyt"></a>
			<h3><?php echo PAATETYT_HAKEMUKSET; ?></h3>
			<thead>
				<tr class="vo_taulu">
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Tutkimuksen_nimi&jarjestys_kohde=Paatetyt">
								<?php echo TUTKIMUKSEN_NIMI; ?>
								<img <?php if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Tutkimuksen_nimi" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th><p class="a_sort"><a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_yhteyshenkilo&jarjestys_kohde=Paatetyt"><?php echo rooli_hakija; ?>
						<img <?php if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_yhteyshenkilo" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> ></a></p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_tunnus&jarjestys_kohde=Paatetyt">
								<?php echo HAKEMUS; ?>
								<img <?php if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_tunnus" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Hakemuksen_tila&jarjestys_kohde=Paatetyt">
								<?php echo PAATOS; ?>
								<img <?php if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Hakemuksen_tila" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Tilan_pvm&jarjestys_kohde=Paatetyt">
								<?php echo TILAN_PVM; ?>
								<img <?php if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Tilan_pvm" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th>
						<p class="a_sort">
							<a href="viranomainen_saapuneet_hakemukset.php?jarjestys_tyyppi=<?php if($jarjestys_kentta=="Kasittelijan_nimi" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ echo "asc"; } else { echo "desc"; } ; ?>&jarjestys_kentta=Kasittelijan_nimi&jarjestys_kohde=Paatetyt">
								<?php echo KASITTELIJA; ?>
								<img <?php if($jarjestys_kentta=="Kasittelijan_nimi" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="desc"){ ?> src="static/images/sort_up.gif" <?php } else if($jarjestys_kentta=="Kasittelijan_nimi" && $jarjestys_kohde=="Paatetyt" && $jarjestys_tyyppi=="asc"){ ?> src="static/images/sort_down.gif" <?php } else { ?> src="static/images/sort_updown.gif" <?php } ?> >
							</a>
						</p>
					</th>
					<th class="right"><?php echo LISATOIM; ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			if(!empty($vastaus["HakemuksetDTO"]["Paatetyt"])){
				for($i=0; $i < sizeof($vastaus["HakemuksetDTO"]["Paatetyt"]); $i++) {  ?>
						<tr>
							<td class="tutnimi">
							
								<?php if(isset($vastaus["HakemuksetDTO"]["Paatetyt"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi) && $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ ?> 
									<a href="hakemus.php?<?php echo $oletus_sivu_parametri; ?>tutkimus_id=<?php echo $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->HakemusversioDTO->TutkimusDTO->ID; ?>&hakemusversio_id=<?php echo $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->HakemusversioDTO->ID; ?>&hakemus_id=<?php echo $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->ID; ?>" title="Avaa hakemus"> 
								<?php } ?><?php echo htmlentities($vastaus["HakemuksetDTO"]["Paatetyt"][$i]->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?>								
								<?php if(isset($vastaus["HakemuksetDTO"]["Paatetyt"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi) && $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ ?></a><?php } ?>
																
							</td>
							<td><?php echo $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->Hakemuksen_yhteyshenkilo; ?></td>
							<td>
								<?php echo $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->Hakemuksen_tunnus; ?>								
							</td>
							<td><?php echo koodin_selite($vastaus["HakemuksetDTO"]["Paatetyt"][$i]->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi, $_SESSION["kayttaja_kieli"]); ?></td>
							<td><?php echo muotoilepvm($vastaus["HakemuksetDTO"]["Paatetyt"][$i]->Hakemuksen_tilaDTO->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
							<td><?php if($vastaus["HakemuksetDTO"]["Paatetyt"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu"){ echo $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->PaatosDTO->KayttajaDTO_Kasittelija->Etunimi . " " . $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->PaatosDTO->KayttajaDTO_Kasittelija->Sukunimi; } ?></td>
							<td class="right">
								<?php if($vastaus["HakemuksetDTO"]["Paatetyt"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_peruttu" && $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_val" && $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_paat"){ ?>
									<div class='tooltip'>
										<img src='static/images/kasittelija.png' class='lisatoim' alt='' />
										<span class='tooltiptext2'><a href="#" id="n<?php echo $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->ID; ?>" class="kasittely"><?php echo VAIHDA_KASITTELIJAA; ?></a></span>
									</div>
								<?php } ?>
								<?php rinnakkaishakemukset($vastaus, "Paatetyt", $i, $_SESSION["kayttaja_kieli"]); ?>
								<?php hakemushistoria($vastaus, "Paatetyt", $i, $_SESSION["kayttaja_kieli"]); ?>	

								<?php //if(sizeof($vastaus["HakemuksetDTO"]["Paatetyt"][$i]->hakemushistoria_HakemuksetDTO) <= 1){ ?>
									<a href="hakemus_pdf.php?hakemusversio_id=<?php echo $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $vastaus["HakemuksetDTO"]["Paatetyt"][$i]->HakemusversioDTO->TutkimusDTO->ID; ?>">
										<img class="lisatoim" src="static/images/pdf.png">
									</a>
								<?php //} ?>
								
							</td>
						</tr>
				<?php }
			} ?>
			</tbody>
		</table>
	</div>
	</p>
<?php } ?>

<?php
	include './ui/template/footer.php';
?>