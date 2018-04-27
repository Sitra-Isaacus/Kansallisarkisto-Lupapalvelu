<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the viestit page (viranomaisen käyttöliittymä)
 *
 * Created: 22.12.2015
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>

<p class="murupolku"><a style="color: #6EA9C2; text-decoration: none;" href="index.php"><?php echo ETUSIVU; ?></a> > <a style="color: #6EA9C2; text-decoration: none;" href="hakemus.php?hakemusversio_id=<?php echo $vastaus["HakemusDTO"]->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $vastaus["HakemusDTO"]->HakemusversioDTO->TutkimusDTO->ID; ?>&sivu=hakemus_perustiedot"><?php echo HAKEMUS; ?></a> > <?php echo tulosta_teksti($vastaus["HakemusDTO"]->HakemusversioDTO->Tutkimuksen_nimi); ?>  > <?php echo VIESTIT; ?> </a> </p>

<?php include './ui/template/vasen_menu.php'; ?>

<div class="oikea_sisalto">
		
	<form enctype="multipart/form-data" class="form_viesti" name="laheta_viesti" id="form_viesti" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
		
		<?php if ($vastaus["HakemusDTO"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_peruttu") { ?>
			<fieldset disabled="disabled">
		<?php } ?>
		
		<div class="oikea_sisalto_laatikko">
		
			<div class="paneeli_otsikko"><h2><?php echo LAHETA_VIESTI; ?></h2></div>

			<div class="paneeli_otsikko" style="margin-top:25px;">
				<h3><?php echo VASTAANOTTAJA; ?>:</h3>
			</div>
			
			<div class="paneelin_tiedot">
			
				<select id="valitse_viestin_vastaanottaja" name="vastaanottaja" style="width:100%">
					<option selected disabled><?php echo VAL_VAST; ?></option>
					<?php $vastaanottajat = array(); ?>
					<?php for($i=0; $i < sizeof($vastaus["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"]); $i++){ ?>
						<?php if($vastaus["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"][$i]->HakijaDTO->KayttajaDTO->ID==$_SESSION["kayttaja_id"]){ continue; } ?>
						<?php if(!in_array($vastaus["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"][$i]->HakijaDTO->KayttajaDTO->ID, $vastaanottajat)){ ?>
							<option class="viestin_vastaanottaja <?php if( ($vastaus["HakemusDTO"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val" || $vastaus["HakemusDTO"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas") && ($_SESSION["kayttaja_rooli"]=="rooli_kasitteleva" || $_SESSION["kayttaja_rooli"]=="rooli_eettisensihteeri") && ($vastaus["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"][$i]->Hakijan_roolin_koodi=="rooli_hak_yht" || $vastaus["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"][$i]->Hakijan_roolin_koodi=="rooli_vast" || $vastaus["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"][$i]->Hakijan_roolin_koodi=="rooli_tutk_yht")){ echo "lisatietopyynto_vastaanottaja"; } ?>" value="<?php echo $vastaus["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"][$i]->HakijaDTO->KayttajaDTO->ID;?>"><?php echo $vastaus["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"][$i]->HakijaDTO->KayttajaDTO->Etunimi . " " . $vastaus["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"][$i]->HakijaDTO->KayttajaDTO->Sukunimi . " (" . koodin_selite($vastaus["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"][$i]->Hakijan_roolin_koodi, $_SESSION["kayttaja_kieli"]) . ")"; ?></option>
							<?php array_push($vastaanottajat, $vastaus["Hakijan_roolitDTO"]["Vastaanottajat_tutkimusryhma"][$i]->HakijaDTO->KayttajaDTO->ID); ?>
						<?php } ?>
					<?php } ?>
					<?php $vastaanottajat = array(); ?>
					<?php for($i=0; $i < sizeof($vastaus["KayttajatDTO"]["Vastaanottajat_viranomaiset"]); $i++){ ?>
						<?php if($vastaus["KayttajatDTO"]["Vastaanottajat_viranomaiset"][$i]->ID==$_SESSION["kayttaja_id"]){ continue; } ?>
						<?php if(!in_array($vastaus["KayttajatDTO"]["Vastaanottajat_viranomaiset"][$i]->ID, $vastaanottajat)){ ?>
							<option class="viestin_vastaanottaja" value="<?php echo $vastaus["KayttajatDTO"]["Vastaanottajat_viranomaiset"][$i]->ID; ?>"><?php echo $vastaus["KayttajatDTO"]["Vastaanottajat_viranomaiset"][$i]->Etunimi . " " . $vastaus["KayttajatDTO"]["Vastaanottajat_viranomaiset"][$i]->Sukunimi . " (" . koodin_selite($vastaus["KayttajatDTO"]["Vastaanottajat_viranomaiset"][$i]->Viranomaisen_rooliDTO->Viranomaisen_koodi, $_SESSION["kayttaja_kieli"]) . ")"; ?></option>
							<?php array_push($vastaanottajat, $vastaus["KayttajatDTO"]["Vastaanottajat_viranomaiset"][$i]->ID); ?>
						<?php } ?>
					<?php } ?>
				</select>
				
				<div id="lisatietopyynto" style="display: none; margin-top: 20px;">
				
					<?php if($_SESSION["kayttaja_rooli"]=="rooli_eettisensihteeri"){ ?>	
						<?php /*
						<label>
							<input name="laheta_taydennyspyynto" value="hakemus" type="radio">						
							<?php echo LAH_TAYD_HAK; ?>						
						</label>
						<br>
						*/ ?>
						<label>
							<input name="laheta_taydennyspyynto" value="taydennettavaa_hakemukseen" type="checkbox">
							<?php echo LAH_TAYD_ASK; ?>	
						</label>										
					<?php } else { ?>					
						<label>
							<input name="laheta_lisatietopyynto" value="1" type="checkbox">
							<?php echo LAH_LTP; ?>			
						</label>											
					<?php } ?>
												
				</div>
				
			</div>
			
			<div class="paneeli_otsikko">
				<h3 style="margin-top: 1.5em;"><?php echo VIESTI; ?>:</h3>
			</div>
			
			<div class="paneelin_tiedot">
				<textarea name="viesti" class="input_type2" form="form_viesti" style="resize:none;"></textarea>
				<input name="hakemus_id" type="hidden" value="<?php echo $hakemus_id; ?>" /><br />
				<button style="margin-top: 20px;" type="submit" name="laheta_viesti" class="nappi"><?php echo LAHETA_VIESTI; ?></button>
			</div>
			
		</div>
		
		<?php if ($vastaus["HakemusDTO"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_peruttu") { ?>
			</fieldset>
		<?php } ?>
		
	</form>
	
	<?php if(!empty($viestit)){ ?>
			
			<?php if ($vastaus["HakemusDTO"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_peruttu") { ?>
				<fieldset disabled="disabled">
			<?php } ?>
			
			<div class="oikea_sisalto_laatikko">
			
				<div class="paneeli_otsikko">
					<h2><?php echo HAKEMUKSEN_VIESTIT; ?></h2>
				</div>
				
				<div class="paneelin_tiedot">
					<?php for($i=0; $i < sizeof($viestit); $i++){ ?>
						<form enctype="multipart/form-data" id="form_vastaus_<?php echo $viestit[$i]->ID; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
							<table class="viestit">
								<tr>
									<td><div class="viesti_vasen"><b><?php echo VIESTIN_LAHETTAJA; ?>: </b></div></td>
									<td><?php echo $viestit[$i]->KayttajaDTO_Lahettaja->Etunimi . " "; ?> <?php echo $viestit[$i]->KayttajaDTO_Lahettaja->Sukunimi; ?></td>
								</tr>
								<tr>
									<td><div class="viesti_vasen"><b><?php echo VIESTIN_VASTAANOTTAJA; ?>: </b></div></td>
									<td><?php echo $viestit[$i]->KayttajaDTO_Vastaanottaja->Etunimi . " "; ?> <?php echo $viestit[$i]->KayttajaDTO_Vastaanottaja->Sukunimi; ?> </td>
								</tr>
								<tr>
									<td><div class="viesti_vasen"><b><?php echo PVM; ?>: </b></div></td>
									<td><?php echo muotoilepvm($viestit[$i]->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
								</tr>
								<tr>
									<td><div class="viesti_vasen"><b><?php echo VIESTI; ?>: </b></div></td>
									<td><?php echo nl2br(htmlentities($viestit[$i]->Viesti, ENT_COMPAT, "UTF-8")); ?></td>
								</tr>
								<?php if($kayt_id==$viestit[$i]->KayttajaDTO_Vastaanottaja->ID && is_null($viestit[$i]->ViestitDTO_Child->ID)){ ?>
									<tr>
										<td><div class="viesti_vasen"></div></td>
										<td>
										<div class="onclick_input" id="viesti-<?php echo $viestit[$i]->ID; ?>"><input id='nappi_vastaa_<?php echo $viestit[$i]->ID; ?>' type='button' class='nappi_vastaa' value='<?php echo VASTAA; ?>' /></div>
										<textarea class="vastaus_fieldi" id="vastaus_kentta_<?php echo $viestit[$i]->ID; ?>" name="vastaus" form="form_vastaus_<?php echo $viestit[$i]->ID; ?>"></textarea>
										<textarea id="viestin_parent_<?php echo $i; ?>" name="parent_id" style="display:none" form="form_vastaus_<?php echo $viestit[$i]->ID; ?>"><?php echo $viestit[$i]->ID; ?></textarea>
										<textarea id="vastaanottaja_<?php echo $i; ?>" name="vastaanottaja" style="display:none" form="form_vastaus_<?php echo $viestit[$i]->ID; ?>"><?php echo $viestit[$i]->KayttajaDTO_Lahettaja->ID; ?></textarea>
										<input name="laheta_vastaus" id='nappi_laheta_vastaus_<?php echo $viestit[$i]->ID; ?>' type='submit' class='nappi_vastaa' style="display:none" value='<?php echo LAHETA_VASTAUS; ?>' />
										</td>
									</tr>
								<?php } ?>
							</table>
							<input name="hakemus_id" type="hidden" value="<?php echo $hakemus_id; ?>" />
						</form>
						<?php if(!is_null($viestit[$i]->ViestitDTO_Child->ID)){ ?>
							<?php for($c=0; $c < sizeof($viestit[$i]->ViestitDTO_Vastaukset); $c++){ ?>
							<form enctype="multipart/form-data" class="form_vastaus" id="form_vastaus_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
								<table>
									<tr>
										<td><div class="reply"><?php if($c==sizeof($viestit[$i]->ViestitDTO_Vastaukset)-1){ ?><div class="viesti_reply"></div><br><img src="static/images/arrow.png" width="40" height="40"><?php } ?></div></td>
										<td>
											<table class="vastaukset">
											<tr>
												<td><div class="viesti_vasen"><b><?php echo VASTAUKSEN_LAHETTAJA; ?>: </b></div></td>
												<td><?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->KayttajaDTO_Lahettaja->Etunimi . " " . $viestit[$i]->ViestitDTO_Vastaukset[$c]->KayttajaDTO_Lahettaja->Sukunimi; ?></td>
											</tr>
											<tr>
												<td><div class="viesti_vasen"><b><?php echo PVM; ?>: </b></div></td>
												<td><?php echo muotoilepvm($viestit[$i]->ViestitDTO_Vastaukset[$c]->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
											</tr>
											<tr>
												<td><div class="viesti_vasen"><b><?php echo VASTAUS; ?>: </b></div></td>
												<td><?php echo nl2br(htmlentities($viestit[$i]->ViestitDTO_Vastaukset[$c]->Viesti, ENT_COMPAT, "UTF-8")); ?></td>
											</tr>
											<?php if($kayt_id==$viestit[$i]->ViestitDTO_Vastaukset[$c]->KayttajaDTO_Vastaanottaja->ID && is_null($viestit[$i]->ViestitDTO_Vastaukset[$c]->ViestitDTO_Child->ID)){ ?>
												<tr>
													<td><div class="viesti_vasen"></div></td>
													<td>
													<div class="onclick_input" id="viesti-<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>"> <input id='nappi_vastaa_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>' type='button' class='nappi_vastaa' value='<?php echo VASTAA; ?>' /> </div>
													<textarea class="vastaus_fieldi" id="vastaus_kentta_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>" name="vastaus" form="form_vastaus_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>"></textarea>
													<textarea id="viestin_parent_<?php echo $i; ?>_<?php echo $c; ?>" name="parent_id" style="display:none" form="form_vastaus_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>"><?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?></textarea>
													<textarea id="vastaanottaja_<?php echo $i; ?>_<?php echo $c; ?>" name="vastaanottaja" style="display:none" form="form_vastaus_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>"><?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->KayttajaDTO_Lahettaja->ID; ?></textarea>
													</td>
													<tr>
													<td><div class="viesti_vasen"></div></td>
													<td><input name="laheta_vastaus" id='nappi_laheta_vastaus_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>' type='submit' class='nappi_vastaa' style="display:none" value='<?php echo LAHETA_VASTAUS; ?>' />	</td>
													</tr>
												</tr>
											<?php } ?>
											</table>
										</td>
									</tr>
								</table>
								<input name="hakemus_id" type="hidden" value="<?php echo $hakemus_id; ?>" />
							</form>
							<?php } ?>
						<?php } ?>
						<br>
						<?php if(isset($viestit[$i+1])){ ?><div class="borderi"></div> <?php } ?>
						<br>
					<?php } ?>
				</div>
				
			</div>
			
			<?php if ($vastaus["HakemusDTO"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_peruttu") { ?>
				</fieldset>
			<?php } ?>
			
	<?php } ?>
	
	<br>
</div>

<?php
	include './ui/template/footer.php';
?>