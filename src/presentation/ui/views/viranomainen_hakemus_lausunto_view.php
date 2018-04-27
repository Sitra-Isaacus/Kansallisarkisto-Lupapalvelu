<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the lausunnot page (viranomaisen käyttöliittymä)
 *
 * Created: 18.1.2016
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>
<p class="murupolku"><a style="color: #6EA9C2; text-decoration: none;" href="index.php"><?php echo ETUSIVU; ?></a> > <a style="color: #6EA9C2; text-decoration: none;" href="hakemus.php?hakemusversio_id=<?php echo $hakemusDTO->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID; ?>&sivu=hakemus_perustiedot"><?php echo HAKEMUS; ?></a> > <?php echo tulosta_teksti($hakemusDTO->HakemusversioDTO->Tutkimuksen_nimi); ?>  > <?php echo LAUSUNNOT; ?> </a> </p>
<?php include './ui/template/vasen_menu.php'; ?>

<div class="oikea_sisalto">
	
	<?php if($lausuntopyynto_sallittu){ ?>	
	
		<form enctype="multipart/form-data" name="laheta_lausunto" id="form_lausunto" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			
			<?php if (!$lausuntopyynto_sallittu || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_peruttu" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_lah" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_korvattu") { ?>
				<fieldset disabled="disabled">
			<?php } ?>
			
			<div class="oikea_sisalto_laatikko">
			
				<div class="paneeli_otsikko"><h2><?php echo PYYDA_LAUSUNTOA; ?></h2><br><br></div>
				
				<div class="table_ala_space" style="clear: right; height: 4em;">
					<div class="inlineblock">
					<b><?php echo LAUSUNNONANTAJAT; ?></b>
					</div>
					<div class="inlineblock" style="width: 60%; float: right; margin: 0 2em;">
					<select name="laus_antaja">
					<option selected disabled><?php echo VALITSE_LAUSUNNONANTAJA; ?></option>
					<?php for($i=0; $i < sizeof($lausunnonantajat); $i++){
						if (isset($_SESSION["kayttaja_id"])) {
							if($lausunnonantajat[$i]->KayttajaDTO->ID!=$_SESSION["kayttaja_id"]){ ?>
								<option value="<?php echo $lausunnonantajat[$i]->KayttajaDTO->ID;?>"><?php echo $lausunnonantajat[$i]->KayttajaDTO->Etunimi . " " . $lausunnonantajat[$i]->KayttajaDTO->Sukunimi; ?></option>
							<?php } ?>
						<?php } ?>
					<?php } ?>
					</select>
					</div>
				</div>
				
				<div class="table_ala_space" style="clear: right; height: 4em;">
					<div class="inlineblock">
					<b><?php echo LAUSUNNON_MAARAPAIVA; ?></b>
					</div>
					<div class="inlineblock" style="width: 60%; float: right; margin: 0 2em;">
					<input type="text" class="aika_laatikko" name="laus_pvm">
					</div>
				</div>
				
				<div class="table_ala_space" style="clear: right; height: 4em;">
					<div class="inlineblock">
					<b><?php echo LAUSUNTOPYYNTO; ?></b>
					</div>
					<div class="inlineblock" style="width: 60%; float: right; margin: 0 2em;">
					<textarea name="lausuntopyynto" form="form_lausunto" style="resize:none; height:200;"></textarea>
					</div>
				</div>
				
				<div class="table_ala_space" style="clear: right; height: 4em;">
				
					<div class="inlineblock"></div>
					<div class="inlineblock" style="width: 60%; float: right; margin: 0 2em;">
						<button type="submit" name="laheta_lausunto"><?php echo PYYDA_LAUSUNTO; ?></button>
					</div>
				
				</div>
				
			</div>
			
			<input name="hakemus_id" type="hidden" value="<?php echo $hakemus_id; ?>" />
			
			<?php if (!$lausuntopyynto_sallittu || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_peruttu" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_lah" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_korvattu") { ?>
				</fieldset>
			<?php } ?>
			
		</form>	
		
	<?php } ?>	
		
	<?php if(isset($lausuntopyynnot) && !empty($lausuntopyynnot)) { ?>
	
		<div class="oikea_sisalto_laatikko">
		
			<h2><?php echo LAUSUNNOT_JA_PYYNNOT; ?></h2><br>
			
			<div class="paneelin_tiedot">
			
				<?php for($i=0; $i < sizeof($lausuntopyynnot); $i++){ ?>
				
					<table style="table-layout: fixed; margin-top: 15px; width: 100%;">
						<tr>
							<td style="width: 35%;"><b><?php echo LAUS_PYYTAJA; ?>: </b></td>
							<td>
								<?php echo $lausuntopyynnot[$i]->KayttajaDTO_Pyytaja->Etunimi . " " . $lausuntopyynnot[$i]->KayttajaDTO_Pyytaja->Sukunimi; ?>
							</td>
						</tr>
						<tr>
							<td><b><?php echo PVM; ?>: </b></td>
							<td><?php echo muotoilepvm($lausuntopyynnot[$i]->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
						</tr>
						<tr>
							<td><b><?php echo KENELTA_PYYD_LAUS; ?>: </b></td>
							<td><?php echo $lausuntopyynnot[$i]->KayttajaDTO_Antaja->Etunimi . " " . $lausuntopyynnot[$i]->KayttajaDTO_Antaja->Sukunimi; ?></td>
						</tr>
						<tr class="table_ala_space">
							<td><b><?php echo PYYNTO; ?>: </b></td>
							<td><?php echo nl2br(htmlentities($lausuntopyynnot[$i]->Pyynto, ENT_COMPAT, "UTF-8")); ?></td>
						</tr>
						<!-- Tulostetaan lausunto jos sellainen löytyy -->
						<?php if(isset($lausuntopyynnot[$i]->LausuntoDTO->ID) && !is_null($lausuntopyynnot[$i]->LausuntoDTO->ID)){ ?>
						
							<tr>
								<td><p><b><?php echo SAAPUNUT_LAUSUNTO; ?>: </b></p></td>
								<td>
								
								<table>
									<tr>
										<td>
											<a href="lausunto_pdf.php?lausunto_id=<?php echo $lausuntopyynnot[$i]->LausuntoDTO->ID; ?>&hakemus_id=<?php echo $hakemus_id; ?>">
												<img width="70" height="70" src="static/images/pdf_download.png">
											</a>
										</td>
										<td style="vertical-align: middle;">
											<a href="lausunto_pdf.php?lausunto_id=<?php echo $lausuntopyynnot[$i]->LausuntoDTO->ID; ?>&hakemus_id=<?php echo $hakemus_id; ?>"><?php echo LAUSUNTO . ".pdf"; ?></a>
										</td>
									</tr>
								</table>							
								
								</td>
							</tr>
							
							<?php if(isset($lausuntopyynnot[$i]->LausuntoDTO->Lausunnon_liitteetDTO) && !empty($lausuntopyynnot[$i]->LausuntoDTO->Lausunnon_liitteetDTO)){ ?>
								<tr>
									<td>
										<b><?php echo LIITTEET; ?>:</b>
									</td>
									<td>
									<?php for($j=0; $j < sizeof($lausuntopyynnot[$i]->LausuntoDTO->Lausunnon_liitteetDTO); $j++){ ?>										
										<a href="liitetiedosto.php?avaa=<?php echo $lausuntopyynnot[$i]->LausuntoDTO->Lausunnon_liitteetDTO[$j]->LiiteDTO->ID; ?>" target="_blank">
											<?php echo htmlentities($lausuntopyynnot[$i]->LausuntoDTO->Lausunnon_liitteetDTO[$j]->LiiteDTO->Liitetiedosto_nimi, ENT_COMPAT, "UTF-8"); ?>
										</a><br>										
									<?php } ?>
									</td>
								</tr>
							<?php } ?>
							
							<tr>
								<td><p><b><?php echo LAUSUNTO_PAATOS; ?>: </b></p></td>
								<td><p><?php echo koodin_selite($lausuntopyynnot[$i]->LausuntoDTO->Lausunto_koodi, $_SESSION["kayttaja_kieli"]); ?></p></td>
							</tr>
							
							<?php /* if($_SESSION['pohja_viranomaisen_koodi']==$lausuntopyynnot[$i]->KayttajaDTO_Pyytaja->Viranomaisen_rooliDTO->Viranomaisen_koodi){ ?>
								<!--
								<tr>
									<td><b><?php echo LAUSUNNON_NAYTTAMINEN; ?>:</b></td>
									<form enctype="multipart/form-data" name="laheta_nayta_sisalto" id="form_lausunto_<?php echo $i; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
										<?php if ($_SESSION["hakemus"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_peruttu" || $_SESSION["hakemus"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_lah" || $_SESSION["hakemus"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_korvattu") { ?>
											<fieldset disabled="disabled">
										<?php } ?>
										<td>
										<div class="space_oikea">
											<input type="hidden" name="lausunto_id" value="<?php echo $lausuntopyynnot[$i]->LausuntoDTO->ID; ?>">
											<input type="hidden" name="lausunto_naytetaan_hakijoille" value="">
											<input class="naytetaankoLausunto <?php echo $lausuntopyynnot[$i]->LausuntoDTO->ID; ?>" <?php if($_SESSION["hakemus"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == 'hak_peruttu' || $_SESSION["hakemus"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == 'hak_lah' || $_SESSION["hakemus"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == 'hak_korvattu'){ echo 'disabled="disabled"'; } ?> type="checkbox" name="lausunto_naytetaan_hakijoille" style="margin-left: -1px;" <?php if(isset($lausuntopyynnot[$i]->LausuntoDTO->Naytetaanko_hakijoille) && $lausuntopyynnot[$i]->LausuntoDTO->Naytetaanko_hakijoille==1){ echo "checked"; } ?> >
										</div>
										</td>
										<?php if ($_SESSION["hakemus"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_peruttu" || $_SESSION["hakemus"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_lah" || $_SESSION["hakemus"]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi == "hak_korvattu") { ?>
											</fieldset>
										<?php } ?>
										<input name="hakemus_id" type="hidden" value="<?php echo $hakemus_id; ?>" />
									</form>
								</tr>
								!-->
							<?php  } */ ?>
							
						<?php }  ?>
					</table>
					
					<?php if(isset($lausuntopyynnot[$i+1])){ ?><div class="borderi"></div> <?php } ?>
				
				<?php } ?>
				
			</div>
		</div>
		
	<?php } else { ?>
		<?php if(!$lausuntopyynto_sallittu){ ?>		
			<h1 style="margin-top: 45px; text-align: center;"><?php echo "Ei lausuntoja"; ?></h1>		
		<?php } ?>
	<?php } ?>
</div>

<?php
	include './ui/template/footer.php';
?>