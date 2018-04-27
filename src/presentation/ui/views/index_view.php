<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the main page
 *
 * Created: 5.10.2015
 */
 
$page_title = ETUSIVU;

include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';
include './ui/template/info_notification.php';

?>

<div class="messagepop pop" id="muutospop">
	<form enctype="multipart/form-data" name="muutoshakemus_form" id="muutoshakemus_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
		
		<p><b><?php echo TEE_MUUTOSHAKEMUS; ?></b></p>
		
		<div style="display: <?php if(isset($vastaus["HakemusversiotDTO"]["Lahetetyt_uusimmat"]) && sizeof($vastaus["HakemusversiotDTO"]["Lahetetyt_uusimmat"])>0){ echo "block"; } else { echo "none"; } ?>;" >
			<label>
				<input id="input_olemassa_olevaan" type="radio" name="muutoshakemuksen_tyyppi" value="muutoshakemus_olemassa_olevaan" />      
				<?php echo MUUTOSHAKEMUS_OLEMASSA_OLEVAAN; ?><br><br>
			</label>
			<div id="muutoshakemus_olemassa_olevaan" style="display: none;">
				<select name="valittu_hakemus" class="laatikko_p2">
					<option selected disabled><?php echo VALITSE_HAKEMUS; ?>:</option>
						<?php for($i=0; $i < sizeof($vastaus["HakemusversiotDTO"]["Lahetetyt_uusimmat"]); $i++){ ?>
							<option value="<?php echo $vastaus["HakemusversiotDTO"]["Lahetetyt_uusimmat"][$i]->ID;?>"><?php echo htmlentities($vastaus["HakemusversiotDTO"]["Lahetetyt_uusimmat"][$i]->Tutkimuksen_nimi, ENT_COMPAT, "UTF-8"); ?></option>
						<?php } ?>
				</select>
				<br><br>
			</div>
		</div>
		<?php /* 
		<label>
			<input id="input_aiempaan" type="radio" name="muutoshakemuksen_tyyppi" value="muutoshakemus_aiempaan" />
			<?php echo MUUTOSHAKEMUS_AIEMPAAN_HAKEMUKSEEN; ?>
		</label>
		<br><br>
		<div id="muutoshakemus_aiempaan_hakemukseen" style="display: none;">
			<div class="laatikko_p2"><?php echo DIAARINUMERO; ?>:</div><br>
			<input class="laatikko_p2" name="aiempi_diaarinumero" type="text">
		</div>
		*/ ?>
        <br><input class="laatikko_p2" type="submit" value="<?php echo TEE_MUUTOSHAKEMUS; ?>" name="tee_muutoshakemus" id="message_submit"/><br>
		<input type="hidden" id="hakemus_id" name="hakemus_id" />
		<p class="close-muutos"><input class="laatikko_p2" type="button" value="<?php echo PERUUTA; ?>"/></p>
    </form>
</div>

<div class="messagepop pop" id="taydennyspop">
	<form enctype="multipart/form-data" name="taydennys_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
		<p><b><?php echo TAYDASK; ?></b></p>
		<p>Toimita lausuntopyyntöön tarvittavat korjaukset tai täydennykset: </p>
		<p><input type="file" name="taydennysasiakirjat[]" multiple ></p>
		<input name="laheta_tayd_ask" type="submit" class="laatikko_p2" value="<?php echo LAH_TAYD_ASKI; ?>" />
		<input type="hidden" id="taydennys_paatos_id" name="taydennys_paatos_id" />
		<p class="close-tayd"><input class="laatikko_p2" type="button" value="<?php echo PERUUTA; ?>"/></p>
	</form>
</div>

<!-- Aloitus sivun sisältö -->
<form enctype="multipart/form-data" name="luo_hakemus" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
	<div class="laatikko10">
		<h1><?php echo OMAT_HAKEMUKSET; ?></h1>
		<p><?php echo ETUSIVUN_OHJE; ?></p>
		<br>
		<p>
		
		<?php for($i=0; $i < sizeof($lomake_hakemuksetDTO); $i++){ ?>
			<input class="nappi" type="submit" value="<?php echo tulosta_teksti($lomake_hakemuksetDTO[$i]->Uusi_hakemus_painike_teksti); ?> &raquo; " name="luo_hakemus[<?php echo $lomake_hakemuksetDTO[$i]->LomakeDTO->ID; ?>]" >
		<?php } ?>
		
		<?php if(isset($vastaus["HakemusversiotDTO"]["Lahetetyt_uusimmat"]) && sizeof($vastaus["HakemusversiotDTO"]["Lahetetyt_uusimmat"])>0){ ?>
			<input type='button' class='nappi' id='nappi_muutos' value='<?php echo TEE_MUUTOSHAKEMUS; ?> &raquo;' />
		<?php } ?>	
		
		</p>
	</div>
</form>

<?php
if(isset($vastaus["HakemusversiotDTO"]["Keskeneraiset"]) && !empty($vastaus["HakemusversiotDTO"]["Keskeneraiset"])){ ?>
	<p>
	<div class="laatikko">
		<table class="taulu" id="table_keskeneraiset">
			<h3><?php echo KESKENERAISET_HAKEMUKSET ?></h3>
			<thead>
				<tr>
					<th><p><?php echo TUTKIMUKSEN_NIMI; ?> </p></th>
					<th></th>					
					<th><p><?php echo HAKEMUS; ?></p></th>
					<th><p><?php echo TILAN_PVM; ?></p></th>
					<th><?php echo POISTA_HAKEMUS; ?></th>
				</tr>
			</thead>
			<?php
			for($i=0; $i < sizeof($vastaus["HakemusversiotDTO"]["Keskeneraiset"]); $i++){ ?>
				<tbody>
					<tr>
						<td>
							<a href="hakemus.php?tutkimus_id=<?php echo $vastaus["HakemusversiotDTO"]["Keskeneraiset"][$i]->TutkimusDTO->ID; ?>&hakemusversio_id=<?php echo $vastaus["HakemusversiotDTO"]["Keskeneraiset"][$i]->ID; ?>" title="Muokkaa keskeneräistä hakemusta">
								<?php echo htmlentities($vastaus["HakemusversiotDTO"]["Keskeneraiset"][$i]->Tutkimuksen_nimi, ENT_COMPAT, "UTF-8"); ?>								
							</a>							
						</td>
						<td>
							<?php if($vastaus["HakemusversiotDTO"]["Keskeneraiset"][$i]->Hakemuksen_tyyppi=="tayd_hak"){ ?>
								<div class='tooltip'>
									<img src="static/images/info.png" style="width: 20px; height: 20px;">
									<span class='tooltiptext2'><?php echo PYYD_LISTIET; ?></span>
								</div>
							<?php } ?>												
						</td>
						<td>
							<?php echo htmlentities($vastaus["HakemusversiotDTO"]["Keskeneraiset"][$i]->LomakeDTO->Nimi, ENT_COMPAT, "UTF-8") . " " . htmlentities($vastaus["HakemusversiotDTO"]["Keskeneraiset"][$i]->Hakemusversion_tunnus, ENT_COMPAT, "UTF-8"); ?>
						</td>						
						<td><?php echo muotoilepvm($vastaus["HakemusversiotDTO"]["Keskeneraiset"][$i]->Lisayspvm,$_SESSION["kayttaja_kieli"]); ?></td>
						<td><?php if(isset($vastaus["HakemusversiotDTO"]["Keskeneraiset"][$i]->On_oikeus_poistaa) && $vastaus["HakemusversiotDTO"]["Keskeneraiset"][$i]->On_oikeus_poistaa==true){ ?><a onclick="return confirm('<?php echo POISTO_VARMISTUS; ?>');" href="index.php?poista_hakemus=<?php echo $vastaus["HakemusversiotDTO"]["Keskeneraiset"][$i]->ID; ?>" class="ei_alleviivausta"><img src="static/images/erase.png" alt="Poista hakemus" width="16" height="16"></a><?php } else { ?> <img src="static/images/erase_gray.png" width="16" height="16"> <?php } ?></td>
					</tr>
				</tbody>
			<?php } ?>
		</table>
	</div>
	</p>
<?php } ?>

<?php
if(isset($tutkimukset_lahetetyt) && !empty($tutkimukset_lahetetyt)){ ?>
	<p>
	<div class="laatikko">
		<table class="taulu">
			<h3 id="lah_hak"><?php echo LAHETETYT_HAKEMUKSET ?> </h3>
			<thead>
				<tr>
					<th><?php echo TUTKIMUKSEN_NIMI; ?></th>
					<th><?php echo HAKEMUS; ?></th>
					<th><?php echo DIAARINUMERO; ?></th>
					<th></th>
					<th><?php echo HAKEMUKSEN_TILA; ?></th>
					<th><?php echo TILAN_PVM; ?></th>
					<th></th>
				</tr>
			</thead>
			<?php for($i=0; $i < sizeof($tutkimukset_lahetetyt); $i++){ ?>
				<tbody class="tbody_keskeneraiset">
					<?php for($j=0; $j < sizeof($tutkimukset_lahetetyt[$i]->HakemusversiotDTO); $j++){ ?>
						<?php for($h=0; $h < sizeof($tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO); $h++){ ?>
						
							<?php 
								$uusin_hakemus = false;
								if(in_array($tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->ID, $uusimpien_hakemusten_idt)) $uusin_hakemus = true; 
							?>
							
							<tr>
								<td>
									<?php if($h==0 && $j==0) echo htmlentities($tutkimukset_lahetetyt[$i]->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?>
								</td>
								<td>
									<a <?php if(!$uusin_hakemus){ ?>style="color: gray; text-decoration: line-through;"<?php } ?> href="hakemus.php?tutkimus_id=<?php echo $tutkimukset_lahetetyt[$i]->ID; ?>&hakemusversio_id=<?php echo $tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->ID; ?>"><?php echo htmlentities($tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->Hakemuksen_tunnus,ENT_COMPAT, "UTF-8"); ?></a>										
								</td>
								<td>
									<p <?php if(!$uusin_hakemus){ ?>style="color: gray; text-decoration: line-through;"<?php } ?>><?php echo htmlentities($tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->AsiaDTO->Diaarinumero,ENT_COMPAT, "UTF-8"); ?></p>
								</td>
								<td>
									<?php if(!is_null($tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->Hakemus_sisaltaa_viesteja) && $tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->Hakemus_sisaltaa_viesteja==true){ ?>
										<a href="hakemus_viestit.php?hakemus_id=<?php echo $tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->ID; ?>"><img src="static/images/message.png" style="width: 23px; height: 13px;"></a>
									<?php } ?>
								</td>
								<td>
									<p <?php if(!$uusin_hakemus){ ?>style="color: gray; text-decoration: line-through;"<?php } ?>>
										<?php if($tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat"){ ?><a href="paatos_pdf.php?tutkimus_id=<?php echo $tutkimukset_lahetetyt[$i]->ID; ?>&hakemus_id=<?php echo $tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->ID; ?>"><?php } ?>					
											<?php echo koodin_selite(htmlentities($tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi,ENT_COMPAT, "UTF-8"),$_SESSION["kayttaja_kieli"]); ?>									
										<?php if($tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat"){ ?></a><?php } ?>
									</p>
								</td>
								<td>
									<p <?php if(!$uusin_hakemus){ ?>style="color: gray; text-decoration: line-through;"<?php } ?>>
										<?php echo muotoilepvm($tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->Hakemuksen_tilaDTO->Lisayspvm,$_SESSION["kayttaja_kieli"]); ?>
									</p>
								</td>
								<td class="vasen_border">
																									
									<?php if(isset($tutkimukset_lahetetyt[$i]->Taydennyshakemuksen_luominen_sallittu) && $tutkimukset_lahetetyt[$i]->Taydennyshakemuksen_luominen_sallittu){ ?>
										<p><a href="index.php?hakemusversio_id=<?php echo $tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->ID; ?>&tee_taydennyshakemus=1"><?php echo TAYDENNA_HAK; ?></a></p>
									<?php } ?>
									
									<a onclick="return confirm('<?php echo PERUUTA_VARMISTUS; ?>');" href="index.php?peruuta_hakemus=<?php echo $tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->ID; ?>" style="color:#F00;"><?php if($j==0 && $h==0){
										if(sizeof($tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO)==1){
											echo PERUUTA_HAKEMUS;
										}
										if(sizeof($tutkimukset_lahetetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO)>1){
											echo PERUUTA_HAKEMUKSET;
										}
									} ?></a>
									
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
			<?php } ?>
		</table>
	</div>
	</p>
<?php } ?>

<?php if(isset($tutkimukset_paatetyt) && !empty($tutkimukset_paatetyt)){ ?>
	<p>
	<div class="laatikko">
		<table class="taulu">
			<h3><?php echo PAAT_HAK; ?> </h3>
			<thead>
				<tr>
					<th><?php echo TUTKIMUKSEN_NIMI; ?></th>
					<th><?php echo HAKEMUS; ?></th>
					<th><?php echo DIAARINUMERO; ?></th>
					<th></th>
					<th><?php echo PAATOS; ?></th>
					<th></th>
					<th><?php echo TILAN_PVM; ?></th>
					<th> </th>
				</tr>
			</thead>
			<?php for($i=0; $i < sizeof($tutkimukset_paatetyt); $i++){ ?>
				<tbody class="tbody_keskeneraiset">
					<?php for($j=0; $j < sizeof($tutkimukset_paatetyt[$i]->HakemusversiotDTO); $j++){ ?>
						<?php for($h=0; $h < sizeof($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO); $h++){ ?>
						
							<?php 
								$uusin_hakemus = false;
								if(in_array($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->ID, $uusimpien_hakemusten_idt)) $uusin_hakemus = true; 
							?>						
						
							<tr>
								<td>
									<?php if($j==0 && $h==0) echo htmlentities($tutkimukset_paatetyt[$i]->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?>
								</td>
								<td>
									<a <?php if(!$uusin_hakemus){ ?>style="color: gray; text-decoration: line-through;"<?php } ?> href="hakemus.php?tutkimus_id=<?php echo $tutkimukset_paatetyt[$i]->ID; ?>&hakemusversio_id=<?php echo $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->ID; ?>"><?php echo htmlentities($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->Hakemuksen_tunnus,ENT_COMPAT, "UTF-8"); ?></a>									
								</td>
								<td>
									<p <?php if(!$uusin_hakemus){ ?>style="color: gray; text-decoration: line-through;"<?php } ?>>
										<?php echo htmlentities($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->AsiaDTO->Diaarinumero,ENT_COMPAT, "UTF-8"); ?>
									</p>
								</td>
								<td>
									<?php if(!is_null($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->Hakemus_sisaltaa_viesteja) && $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->Hakemus_sisaltaa_viesteja==true){ ?>
										<a href="hakemus_viestit.php?hakemus_id=<?php echo $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->ID; ?>"><img src="static/images/message.png" style="width: 23px; height: 13px;"></a>
									<?php } ?>
								</td>
								<td>								
									<?php if($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->LomakeDTO->ID==1){ ?><a <?php if(!$uusin_hakemus){ ?>style="color: gray; text-decoration: line-through;"<?php } ?> href="paatos_pdf.php?tutkimus_id=<?php echo $tutkimukset_paatetyt[$i]->ID; ?>&hakemus_id=<?php echo $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->ID; ?>"><?php } ?>										
										<?php if(isset($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi) && $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi!="paat_tila_kesken") echo koodin_selite(htmlentities($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi,ENT_COMPAT, "UTF-8"),$_SESSION["kayttaja_kieli"]); ?>
									<?php if($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->LomakeDTO->ID==1){ ?></a><?php } ?>
								</td>
								<td>
									<?php if(isset($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Paatoksen_liitteetDTO) && !empty($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Paatoksen_liitteetDTO)){ ?>										
										<div class="tooltip">
											
											<a style="cursor: default;" href="#"><img src="static/images/attachment.png" style="width: 18px; height: 18px;"></a>
											
											<span class='tooltiptext2'>
											
												<h5><?php echo PAAT_LIITTEET; ?></h5>
											
												<table class='taulu table_saapuneet_hakemukset'>
													<tbody>
														<tr>
															<th><?php echo NIMI; ?></th>
														</tr>
														<?php for($q=0; $q < sizeof($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Paatoksen_liitteetDTO); $q++){ ?>
															<tr>
																<td>
																	<a href="liitetiedosto.php?avaa=<?php echo $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Paatoksen_liitteetDTO[$q]->LiiteDTO->ID; ?>" target="_blank">
																		<?php echo $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Paatoksen_liitteetDTO[$q]->Liitteen_nimi; ?>
																	</a>
																</td>
															</tr>
														<?php } ?>	
													</tbody>
												</table>
												
											</span>
											
										</div>
									<?php } ?>
								</td>
								<td>
									<p <?php if(!$uusin_hakemus){ ?>style="color: gray; text-decoration: line-through;"<?php } ?>>
										<?php echo muotoilepvm($tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Paatoksen_tilaDTO->Lisayspvm,$_SESSION["kayttaja_kieli"]); ?>
									</p>
								</td>
								<td>
								
									<?php if($h==0 && $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_ehd_hyvaksytty" && $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Ehdollisen_paatoksen_tyyppi=="ehd_paat_hak" && isset($tutkimukset_paatetyt[$i]->Taydennyshakemuksen_luominen_sallittu) && $tutkimukset_paatetyt[$i]->Taydennyshakemuksen_luominen_sallittu){ ?>
										<p><a href="index.php?hakemusversio_id=<?php echo $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->ID; ?>&tee_taydennyshakemus=1"><?php echo TAYDENNA_HAK; ?></a></p>
									<?php } ?>								
								
									<?php if($j==0 && $h==0 && $tutkimukset_paatetyt[$i]->Aineistotilaus_sallittu){ ?> 
										<a href="aineistotilaus.php?tutkimus_id=<?php echo $tutkimukset_paatetyt[$i]->ID; ?>"><?php echo TILAA_AINEISTO; ?></a>
									<?php } ?>	
									
									<?php if($h==0 && $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Ehdollisen_paatoksen_tyyppi=="ehd_paat_ask" && $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_ehd_hyvaksytty"){ ?>
										<a id="toim_tayd_ask_<?php echo $tutkimukset_paatetyt[$i]->HakemusversiotDTO[$j]->HakemuksetDTO[$h]->PaatosDTO->ID; ?>" class="toim_tayd_ask" href="#"><?php echo TOIM_TAYD_ASK; ?></a>
									<?php } ?>
									
								</td>
							</tr>
						<?php } ?>
					<?php } ?>
				</tbody>
			<?php } ?>
		</table>
	</div>
	</p>
<?php } ?>

<?php if(isset($paatokset_aineistotilaukset) && !empty($paatokset_aineistotilaukset)){ ?>

	<p>
	<div class="laatikko">

		<table class="taulu">
		
			<h3><?php echo AINEISTOTILAUKSET; ?> </h3>
			<thead>
				<tr>
					<th><?php echo TILAAJA; ?></th>
					<th><?php echo HAKEMUS; ?></th>
					<th><?php echo TILAN_PVM; ?></th>
					<th><?php echo KASITTELY_TILA; ?></th>
					<th>Hinta</th>
					<th><?php echo AINEISTONMUODOSTAJA; ?></th>
					<th></th>
				</tr>
			</thead>
			
			<?php for($i=0; $i < sizeof($paatokset_aineistotilaukset); $i++){ ?>
				
				<tbody class="tbody_keskeneraiset">
					<tr>
					
						<td>
							<?php echo htmlentities($paatokset_aineistotilaukset[$i]->AineistotilausDTO->KayttajaDTO_Aineiston_tilaaja->Etunimi . " " . $paatokset_aineistotilaukset[$i]->AineistotilausDTO->KayttajaDTO_Aineiston_tilaaja->Sukunimi,ENT_COMPAT, "UTF-8"); ?>
						</td>
						
						<td>
							<?php if (isset($paatokset_aineistotilaukset[$i]->HakemusDTO->HakemusversioDTO->ID)) { ?>
								<a href="hakemus.php?tutkimus_id=<?php echo $paatokset_aineistotilaukset[$i]->HakemusDTO->HakemusversioDTO->TutkimusDTO->ID; ?>&hakemusversio_id=<?php echo $paatokset_aineistotilaukset[$i]->HakemusDTO->HakemusversioDTO->ID; ?>"><?php echo htmlentities($paatokset_aineistotilaukset[$i]->HakemusDTO->Hakemuksen_tunnus, ENT_COMPAT, "UTF-8"); ?></a>								
							<?php } ?>
						</td>
						
						<td>
							<?php echo muotoilepvm(htmlentities($paatokset_aineistotilaukset[$i]->AineistotilausDTO->Aineistotilauksen_tilaDTO->Lisayspvm, ENT_COMPAT, "UTF-8"), $_SESSION["kayttaja_kieli"]); ?>
						</td>

						<td>
							<?php aineistohistoria($paatokset_aineistotilaukset, $i, $_SESSION["kayttaja_kieli"]); ?>
						</td>

						<td>
							<?php if(!is_null($paatokset_aineistotilaukset[$i]->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja->ID)){
								echo htmlentities($paatokset_aineistotilaukset[$i]->AineistotilausDTO->Aineistonmuodostuksen_hinta, ENT_COMPAT, "UTF-8");
							} ?>
						</td>						
						
						<td>
							<?php if(!is_null($paatokset_aineistotilaukset[$i]->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja->ID)){
								echo htmlentities($paatokset_aineistotilaukset[$i]->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja->Etunimi . " " . $paatokset_aineistotilaukset[$i]->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja->Sukunimi,ENT_COMPAT, "UTF-8"); ?>
							<?php } else { echo EI_KASITTELIJAA; } ?>
							
						</td>
						
						<td>
						
							<?php if(($paatokset_aineistotilaukset[$i]->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_rekl" || $paatokset_aineistotilaukset[$i]->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_uusi")){ ?>
								<a onclick="return confirm('<?php echo PERUUTA_AIN_TILAUS_VARMISTUS; ?>');" style="color:#F00;" href="index.php?peru_aineistopyynto=<?php echo $paatokset_aineistotilaukset[$i]->AineistotilausDTO->ID; ?>"><?php echo PERUUTA_AINEISTOTILAUS; ?></a>
							<?php } ?>
							
							<?php if($paatokset_aineistotilaukset[$i]->AineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi=="aint_toimitettu"){ ?>
								<span class="kuittaus link" id="n<?php echo $paatokset_aineistotilaukset[$i]->AineistotilausDTO->ID; ?>"><?php echo KUITTAA_AINEISTO; ?></span><br>
								<a onclick="return confirm('<?php echo REKLAMAATIO_VARMISTUS; ?>');" style="color:#F00;" href="index.php?laheta_reklamaatiotilaus=<?php echo $paatokset_aineistotilaukset[$i]->AineistotilausDTO->ID; ?>"><?php echo TEE_REKLAMAATIO; ?></a>
							<?php } ?>
							
						</td>						
						
					</tr>
				</tbody>
			<?php } ?>
			
		</table>
	
	</div>
	</p>
	
<?php } ?>

<?php for($i=0; $i < sizeof($paatokset_aineistotilaukset); $i++) { ?>
	<div class="messagepop3 pop" id="kuittauspop-n<?php echo $paatokset_aineistotilaukset[$i]->AineistotilausDTO->ID; ?>">
		<form enctype="multipart/form-data" name="ain_kuittaus_form" id="ain_kuittaus_form-n<?php echo $paatokset_aineistotilaukset[$i]->AineistotilausDTO->ID; ?>" action="" method="POST">
			<p><b><?php echo KUITTAA_AINEISTO; ?></b></p>
			<p>
				<label> <input id="palautetuksi" type="radio" name="kuittauksen_tyyppi" value="aint_palautettu" /> <?php echo PALAUTETUKSI; ?> </label><br>
				<label> <input id="havitetyksi" type="radio" name="kuittauksen_tyyppi" value="aint_havitetty" /> <?php echo HAVITETYKSI; ?> </label><br>
				<label> <input id="arkistoiduksi" type="radio" name="kuittauksen_tyyppi" value="aint_arkistoitu" /> <?php echo ARKISTOIDUKSI; ?> </label>
			</p>
			<p>
				<input class="laatikko_p2" type="submit" name="kuittaa_aineisto" value="<?php echo KUITTAA; ?>" /><br>
				<input type="hidden" name="aineistotilaus_id" value="<?php echo $paatokset_aineistotilaukset[$i]->AineistotilausDTO->ID; ?>" />
			</p>
			<p class="close" id="n<?php echo $paatokset_aineistotilaukset[$i]->AineistotilausDTO->ID; ?>"><input class="laatikko_p2" type="button" value="<?php echo PERUUTA; ?>"/></p>
		</form>
	</div>
<?php } ?>

<?php
	include './ui/template/footer.php';
?>