<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Päätös
 *
 * Created: 20.6.2017
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>
<p class="murupolku"><a style="color: #6EA9C2; text-decoration: none;" href="index.php"><?php echo ETUSIVU; ?></a> > <a style="color: #6EA9C2; text-decoration: none;" href="hakemus.php?hakemusversio_id=<?php echo $hakemusDTO->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID; ?>&sivu=hakemus_perustiedot"><?php echo HAKEMUS; ?></a> > <?php echo tulosta_teksti($hakemusDTO->HakemusversioDTO->Tutkimuksen_nimi); ?>  > <?php echo PAATOS; ?> </a> </p>
<?php include './ui/template/vasen_menu.php'; ?>

<div class="oikea_sisalto">

	<?php if((!isset($paatosDTO->LomakeDTO->ID) || $paatosDTO->LomakeDTO->ID==0) && $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_muuta"){ ?>
		<h1 style="margin-top: 45px; text-align: center;"><?php echo "Ei päätöksiä"; ?></h1>	
	<?php } else { ?>
	
		<form enctype="multipart/form-data" id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
		
			<?php if ( !$lomakkeen_muokkaus_sallittu ) { ?>
				<fieldset disabled="disabled">
			<?php } ?>
			
			<input name="hakemus_id" type="hidden" value="<?php echo $hakemus_id; ?>" />
			
			<?php $hakemusversioDTO = $paatosDTO->HakemusDTO->HakemusversioDTO; ?>
						
			<?php if(($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat") && $eett_paatos_valittu){ ?>
				<div class="oikea_sisalto_laatikko">

					<div class="paneeli_otsikko">
						<h2><?php echo "Eettisen toimikunnan lausunto"; ?></h2>
					</div>	

					<div class="paneelin_tiedot">
					
						<div class="tieto">
							
							<div class="kentta_otsikko">
								<?php echo PAATOS; ?>
							</div>

							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val" || $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>		
								<label><input class="form_input paatos <?php echo $paatosDTO->ID; ?> Alustava_paatos" type="radio" id="paat_tila_hyvaksytty" <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty") || (($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hyvaksytty")){ echo "checked"; } ?> name="paatos_eettinen" value="paat_tila_hyvaksytty"> <?php echo HYVAKSYTTY; ?><br></label>
								<?php if($hakemusversioDTO->Hakemuksen_tyyppi=="muutos_hak" || $hakemusversioDTO->Hakemuksen_tyyppi=="uus_hak"){ ?>
									<label><input class="form_input paatos <?php echo $paatosDTO->ID; ?> Alustava_paatos" type="radio" id="paat_tila_ehd_hyvaksytty" <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_ehd_hyvaksytty") || (($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_ehd_hyvaksytty")){ echo "checked"; } ?> name="paatos_eettinen" value="paat_tila_ehd_hyvaksytty"> <?php echo EHD_HYVAKSYTTY; ?><br></label>
								<?php } ?>
								<label><input class="form_input paatos <?php echo $paatosDTO->ID; ?> Alustava_paatos" type="radio" id="paat_tila_hylatty" <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hylatty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hylatty")){ echo "checked"; } ?> name="paatos_eettinen" value="paat_tila_hylatty"> <?php echo HYLATTY; ?></label>					
							<?php } ?>
							
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat"){ ?>
								<?php if(isset($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi) && $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty") echo HYVAKSYTTY; ?>
								<?php if(isset($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi) && $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_ehd_hyvaksytty") echo EHD_HYVAKSYTTY; ?>
								<?php if(isset($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi) && $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hylatty") echo HYLATTY; ?>
							<?php } ?>
														
						</div>	

						<?php if($hakemusversioDTO->Hakemuksen_tyyppi=="tayd_hak"){ ?>
							<div class="tieto">
								
								<div class="kentta_otsikko">
									<?php echo TAYD_HYVPYYNT; ?>
								</div>		

								<?php echo TAYD_VAAT_PJ_HYV; ?><br><br>
								
								<label><input id="pj_hyvaksynta_vaaditaan" <?php if(isset($paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan) && $paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan==1) echo "checked"; ?> name="pj_hyvaksynta_vaaditaan" value="1" class="form_input paatos <?php echo $paatosDTO->ID; ?> Puheenjohtajan_hyvaksynta_vaaditaan" type="radio"><?php echo KYLLA; ?></label><br>
								<label><input id="pj_hyvaksyntaa_ei_vaadita" <?php if(isset($paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan) && $paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan==0) echo "checked"; ?> name="pj_hyvaksynta_vaaditaan" value="0" class="form_input paatos <?php echo $paatosDTO->ID; ?> Puheenjohtajan_hyvaksynta_vaaditaan" type="radio"><?php echo EI; ?></label>
								
								<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
								<div id="pj_valinta" style="margin-top: 25px; display: <?php if(isset($paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan) && $paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan==1){ echo "block;"; } else { echo "none;"; } ?>">
									
									<?php echo VAL_PJ; ?><br><br>
									
									<?php for($i=0; $i < sizeof($viranomaisten_roolitDTO_Paattajat); $i++){ ?>

										<?php

										$paattaja_valittu = false;

										for($j=0; $j < sizeof($paatosDTO->PaattajatDTO); $j++){
											if($paatosDTO->PaattajatDTO[$j]->KayttajaDTO->ID==$viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->ID) $paattaja_valittu = true;
										}

										?>
										
										
											<label>
												<input name="pjt[]" <?php if($paattaja_valittu) echo "checked"; ?> class="form_input pj <?php echo $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->ID; ?>" type="checkbox" value="<?php echo $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->ID; ?>" > 
												<?php echo $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->Etunimi . " " . $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->Sukunimi; ?>
											</label><br>
																			
										
										<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_kas" && $paattaja_valittu){ ?>
											<?php echo $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->Etunimi . " " . $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->Sukunimi; ?><br>
										<?php } ?>

									<?php } ?>									
									
								</div>
								<?php } ?>
								
							</div>
						<?php } ?>
						
						<div class="tieto ehdollinen_paatos" style="display: <?php if(($hakemusversioDTO->Hakemuksen_tyyppi=="muutos_hak" || $hakemusversioDTO->Hakemuksen_tyyppi=="uus_hak") && isset($paatosDTO) && ( ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_ehd_hyvaksytty") || (($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_ehd_hyvaksytty") )){ echo "block;"; } else { echo "none;"; } ?>">
					
							<div class="kentta_otsikko">
								<?php echo TAYD_PYYNTO; ?>
							</div>		

							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>		
								<label><input class="form_input paatos <?php echo $paatosDTO->ID; ?> Ehdollisen_paatoksen_tyyppi" type="radio" id="ehd_paat_hak" <?php if(isset($paatosDTO->Ehdollisen_paatoksen_tyyppi) && $paatosDTO->Ehdollisen_paatoksen_tyyppi=="ehd_paat_hak") echo "checked"; ?> name="ehdollinen_paatos" value="ehd_paat_hak"> <?php echo TAYD_HAK; ?><br></label>
								<label><input class="form_input paatos <?php echo $paatosDTO->ID; ?> Ehdollisen_paatoksen_tyyppi" type="radio" id="ehd_paat_ask" <?php if(isset($paatosDTO->Ehdollisen_paatoksen_tyyppi) && $paatosDTO->Ehdollisen_paatoksen_tyyppi=="ehd_paat_ask") echo "checked"; ?> name="ehdollinen_paatos" value="ehd_paat_ask"> <?php echo TAYD_ASK; ?><br></label>				
							<?php } else { ?>
								<?php if(isset($paatosDTO->Ehdollisen_paatoksen_tyyppi) && $paatosDTO->Ehdollisen_paatoksen_tyyppi=="ehd_paat_hak") echo TAYD_HAK; ?>
								<?php if(isset($paatosDTO->Ehdollisen_paatoksen_tyyppi) && $paatosDTO->Ehdollisen_paatoksen_tyyppi=="ehd_paat_ask") echo TAYD_ASK; ?>
							<?php } ?>
						
						</div>
					
						<div class="tieto" style="display: <?php if(isset($paatosDTO->Paatoksen_liitteetDTO) && !empty($paatosDTO->Paatoksen_liitteetDTO)){ echo "block;"; } else { echo "none;"; } ?>;">
								
							<div class="kentta_otsikko">
								<?php echo POYTKIRJ_OTE; ?>
							</div>
								
							<table>
								<?php for($i=0; $i < sizeof($paatosDTO->Paatoksen_liitteetDTO); $i++){ ?>
									<tr>
										<td>	
											<a id="<?php echo $paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->ID; ?>" class="liitetiedosto_linkki" href="liitetiedosto.php?avaa=<?php echo $paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->ID; ?>" target="_blank">
												<img style="width: 50px; height=50px;" src="static/images/pdf_download.png">
											</a>
										</td>
										<td style="vertical-align: middle; width: 75%;">
											<a id="<?php echo $paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->ID; ?>" class="liitetiedosto_linkki" href="liitetiedosto.php?avaa=<?php echo $paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->ID; ?>" target="_blank">
												<?php echo htmlentities($paatosDTO->Paatoksen_liitteetDTO[$i]->Liitteen_nimi,ENT_COMPAT, "UTF-8"); ?>
											</a>
										</td>
										<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
											<td style="vertical-align: middle;">
												<?php echo POISTA; ?> <a href="paatos.php?liite_id=<?php echo $paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->ID; ?>&poista_liite=1&paatos_id=<?php echo $paatosDTO->ID; ?>&hakemus_id=<?php echo $hakemusDTO->ID; ?>" class="ei_alleviivausta"><img style="width: 20px; height: 20px;" src="static/images/erase.png" alt="Poista liitetiedosto"></a>
											</td>
										<?php } ?>	
									</tr>
								<?php } ?>
							</table>
							
							<input type="hidden" value="<?php if(isset($paatosDTO->Paatoksen_liitteetDTO)){ echo sizeof($paatosDTO->Paatoksen_liitteetDTO); } else { echo 0; } ; ?>" name="poytakirjaotteet_kpl">
																						
						</div>					
															
						<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
							<div class="tieto">

								<div class="kentta_otsikko">
									<?php echo LIITA_PKO; ?>
								</div>
											
								<input type="hidden" name="paatos_id" value="<?php echo $paatosDTO->ID; ?>">
								<br>	
								<table style="border-collapse: collapse; width: 60%;">
									<tr>
										<td>
											<p><?php echo LIITTEEN_NIMI; ?>:</p>
											
										</td>
										<td>
											<p><input type="textarea" name="liitteen_nimi"></p>
										</td>
									</tr>
									<tr>
										<td>
											<p><input type="file" name="lisaa_liite" id="lisaa_liite"></p>
										</td>
										<td>
											<p><input style="background: #9CE3E5; border-radius: 3px;" type="submit" name="lisaa_liite_asiakirja" value="<?php echo LIITA; ?>"></p>
										</td>									
									</tr>
								</table>
							
							</div>					
						<?php } ?>
						
						<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>	
							<div id="laheta_eettinen_lausunto" style="margin-top: 15px; display: <?php if(($hakemusversioDTO->Hakemuksen_tyyppi=="muutos_hak" || $hakemusversioDTO->Hakemuksen_tyyppi=="uus_hak") || (isset($paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan) && $paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan==0) ){ echo "block;"; } else { echo "none;"; } ?>;">
								<input onclick="return confirm('<?php echo LAUS_TIED_VARM; ?>');" class="nappi" type="submit" value="<?php echo LAHETA_LAUSUNTO2; ?> &raquo;" name="laheta_lausunto_tiedoksi">
							</div>
							<div id="laheta_hyv_pyynto" style="margin-top: 15px; display: <?php if(isset($paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan) && $paatosDTO->Puheenjohtajan_hyvaksynta_vaaditaan==1){ echo "block;"; } else { echo "none;"; } ?>;">
								<input onclick="return confirm('Haluatko varmasti lähettää hyväksymispyynnön puheenjohtajalle?');" class="nappi" type="submit" value="<?php echo "Lähetä hyväksymispyyntö"; ?> &raquo;" name="laheta_hyvaksymispyynto">
							</div>							
						<?php } ?>						
											
					</div>		
				
				</div>
			<?php } ?>			
			
			<div class="oikea_sisalto_laatikko" style="display: <?php if(!$eett_paatos_valittu && $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ echo "block;"; } else { echo "none;"; } ?>">

				<div class="paneeli_otsikko">
					<h2><?php echo PAATOSPOHJA; ?></h2>
				</div>

				<div class="paneelin_tiedot">				
					<div class="tieto">
						<select class="form_input paatos <?php echo $paatosDTO->ID; ?> FK_Lomake">
							<option><?php echo VAL_PAATPOHJ; ?></option>
							<?php for($i=0; $i < sizeof($lomakkeetDTO_Paatos); $i++){ ?>
								<option <?php if($lomakkeetDTO_Paatos[$i]->ID==$paatosDTO->LomakeDTO->ID) echo "selected"; ?> value="<?php echo $lomakkeetDTO_Paatos[$i]->ID; ?>"><?php echo $lomakkeetDTO_Paatos[$i]->Nimi; ?></option>
							<?php } ?>
						</select>
					</div>					
				</div>

			</div>
			
			<div class="oikea_sisalto_laatikko" style="display: <?php if($eett_paatos_valittu){ echo "none;"; } else { echo "block;"; } ?>">

				<div class="paneeli_otsikko">
					<h2><?php echo PAATTAJAT; ?></h2>
				</div>

				<div class="paneelin_tiedot">

					<div class="tieto">

						<?php for($i=0; $i < sizeof($viranomaisten_roolitDTO_Paattajat); $i++){ ?>

							<?php

							$paattaja_valittu = false;

							for($j=0; $j < sizeof($paatosDTO->PaattajatDTO); $j++){
								if($paatosDTO->PaattajatDTO[$j]->KayttajaDTO->ID==$viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->ID) $paattaja_valittu = true;
							}

							?>
							
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
								<input name="paattajat[]" <?php if($paattaja_valittu) echo "checked"; ?> id="paattaja_<?php echo $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->ID; ?>" class="form_input paattaja <?php echo $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->ID; ?>" type="checkbox" value="<?php echo $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->ID; ?>" > 
							<?php } ?>
							
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
								<label for="paattaja_<?php echo $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->ID; ?>">
									<?php echo $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->Etunimi . " " . $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->Sukunimi; ?><br>
								</label>
							<?php } ?>	
							
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_kas" && $paattaja_valittu){ ?>
								<?php echo $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->Etunimi . " " . $viranomaisten_roolitDTO_Paattajat[$i]->KayttajaDTO->Sukunimi; ?><br>
							<?php } ?>

						<?php } ?>

					</div>

				</div>

			</div>
			
			<div class="oikea_sisalto_laatikko" style="display: <?php if(!$eett_paatos_valittu && isset($paatosDTO->LomakeDTO->ID) && !is_null($paatosDTO->LomakeDTO->ID) && $paatosDTO->LomakeDTO->ID!=0){ echo "block;"; } else { echo "none;"; } ?>">
			
				<div class="paneeli_otsikko">
					<h2><?php echo PAATOS_OTSIKKO; ?></h2>
				</div>	

				<div class="paneelin_tiedot">
					
					<div class="tieto">
					
						<div class="kentta_otsikko">
							<?php echo PAATOS; ?>
						</div>
						
						<label><input class="form_input paatos <?php echo $paatosDTO->ID; ?> Alustava_paatos" type="radio" id="paat_tila_hyvaksytty" <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty") || (($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hyvaksytty")){ echo "checked"; } ?> name="paatos" value="paat_tila_hyvaksytty"> <?php echo HYVAKSYTTY; ?><br></label>
						<label><input class="form_input paatos <?php echo $paatosDTO->ID; ?> Alustava_paatos" type="radio" id="paat_tila_hylatty" <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hylatty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hylatty")){ echo "checked"; } ?> name="paatos" value="paat_tila_hylatty"> <?php echo HYLATTY; ?></label>					
						
					</div>
					
					<div class="tieto paatos_hyvaksytty" style="line-height: 20px; display: <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hyvaksytty")){ echo "block"; } else { echo "none"; } ?>;">
						<?php echo koodin_selite($paatosDTO->HakemusDTO->Viranomaisen_koodi, $_SESSION["kayttaja_kieli"]) . " " . PAATOS_LISATIEDOT; ?>
					</div>	

					<div class="tieto paatos_hyvaksytty" style="line-height: 20px; display: <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hyvaksytty")){ echo "block"; } else { echo "none"; } ?>;">
						
						<div class="kentta_otsikko">
							<?php echo LUV_VOIMAIKA; ?>
						</div>	
						
						<?php echo LUPA_ON_VOIMASSA; ?> 
						<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
							<input class="form_input paatos <?php echo $paatosDTO->ID; ?> Lakkaamispvm aika_laatikko" type="text" name="lupa_voimassa_pvm" value="<?php if(isset($paatosDTO->Lakkaamispvm)){ echo htmlentities(muotoilepvm2($paatosDTO->Lakkaamispvm, "fi"), ENT_COMPAT, "UTF-8"); } ?>" style="width: 125px;"> <?php echo ASTI; ?>.
						<?php } else { ?>
							<?php echo htmlentities(muotoilepvm2($paatosDTO->Lakkaamispvm, "fi"), ENT_COMPAT, "UTF-8") . " " . ASTI . "."; ?>
						<?php } ?>
						
					</div>	
					
					<div class="tieto">
					
						<div class="kentta_otsikko">
							<?php echo TUTKIMUKSEN_NIMI; ?>
						</div>
						
						<?php echo htmlentities($hakemusversioDTO->Tutkimuksen_nimi, ENT_COMPAT, "UTF-8"); ?>
						
					</div>
					
					<?php if($rak_kl_paatos_valittu){ ?>
					
						<div class="tieto paatos_hylatty" style="line-height: 20px; display: <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hylatty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hylatty")){ echo "block"; } else { echo "none"; } ?>;">
						
							<div class="kentta_otsikko">
								<?php echo HAK_HYL_PER; ?>
							</div>
								
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
								<textarea style="width: 100%; height: 480px;" class="form_input paatos <?php echo $paatosDTO->ID; ?> Hylkayksen_perustelut" ><?php if(isset($paatosDTO->Hylkayksen_perustelut)) echo htmlentities($paatosDTO->Hylkayksen_perustelut, ENT_COMPAT, "UTF-8"); ?></textarea><br><br>
							<?php } else { ?>
								<?php echo htmlentities($paatosDTO->Hylkayksen_perustelut, ENT_COMPAT, "UTF-8"); ?>
							<?php } ?>			
						
						</div>				
					
					<?php } ?>	
					
					<?php if($rak_kl_paatos_valittu){ ?>
					
						<div class="tieto">

							<div class="kentta_otsikko">
								<?php echo REKISTERINPITAJAT; ?>
							</div>
							
							<?php if(isset($paatosDTO->HakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO)){ ?>
								<table style="width: 100%;">
									<?php for($r=0; $r < sizeof($paatosDTO->HakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO); $r++){ ?>
										<tr>
											<td>
												<div style="font-weight: bold;"><?php echo NIMI; ?></div>
												<?php echo htmlentities($paatosDTO->HakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO[$r]->Nimi, ENT_COMPAT, "UTF-8"); ?>																		
												<br><br>
											</td>
											<td>
												<div style="font-weight: bold;"><?php echo OSOITE; ?></div>
												<?php echo htmlentities($paatosDTO->HakemusDTO->HakemusversioDTO->Osallistuvat_organisaatiotDTO[$r]->Osoite, ENT_COMPAT, "UTF-8"); ?>								
												<br><br>
											</td>
										</tr>								
									<?php } ?>								
								</table>					
							<?php } ?>
							
						</div>
						
					<?php } ?>	
									
					<div class="tieto paatos_hyvaksytty" style="line-height: 20px; display: <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hyvaksytty")){ echo "block"; } else { echo "none"; } ?>;">
					
						<div class="kentta_otsikko">
							<?php echo SALASSPIT_TUTK; ?>
						</div>
							
						<table style="width: 100%; border-collapse: collapse; ">
						
							<tr style="border-bottom: 1px solid #65C5BF;">
								<td><?php echo NIMI; ?></td>
								<td><?php echo OPPIARVO; ?></td>
								<td><?php echo ORGANISAATIO; ?></td>
								<td>Lupa myönnetään</td>
							</tr>
						
							<?php for($i=0; $i < sizeof($sitoumuksetDTO); $i++){ ?>
								
								<tr>								
									<td>
										<?php echo $sitoumuksetDTO[$i]->KayttajaDTO->Etunimi . " " . $sitoumuksetDTO[$i]->KayttajaDTO->Sukunimi; ?>
									</td>
									<td>
										<?php echo htmlentities($sitoumuksetDTO[$i]->KayttajaDTO->HakijaDTO->Oppiarvo,ENT_COMPAT,"UTF-8"); ?>
									</td>
									<td>
										<?php echo htmlentities($sitoumuksetDTO[$i]->KayttajaDTO->HakijaDTO->Organisaatio,ENT_COMPAT,"UTF-8"); ?>
									</td>
									<td>
										<input id="kl_<?php echo $sitoumuksetDTO[$i]->KayttajaDTO->ID; ?>" class="form_input kayttolupa <?php echo $sitoumuksetDTO[$i]->KayttajaDTO->ID; ?> FK_Kayttaja" type="checkbox" name="kayttolupa[]" <?php if(isset($sitoumuksetDTO[$i]->KayttajaDTO->KayttolupaDTO->ID)){ echo "checked"; } ?> value="<?php echo $sitoumuksetDTO[$i]->KayttajaDTO->ID; ?>">
									</td>		
								</tr>
															
							<?php } ?>
					
						</table>							
					
					</div>
					
					<?php if($vap_kl_paatos_valittu){ ?>
					
						<div class="tieto">
						
							<div class="kentta_otsikko">
								<?php echo VAPAAMUOTOINEN_PAATOS; ?>
							</div>
								
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>	
								<textarea style="width: 100%; height: 480px;" class="form_input paatos <?php echo $paatosDTO->ID; ?> Vapaamuotoinen_paatos" ><?php if(isset($paatosDTO->Vapaamuotoinen_paatos)) echo htmlentities($paatosDTO->Vapaamuotoinen_paatos, ENT_COMPAT, "UTF-8"); ?></textarea><br><br>
							<?php } else { ?>
								<?php echo htmlentities($paatosDTO->Vapaamuotoinen_paatos, ENT_COMPAT, "UTF-8"); ?><br><br>
							<?php } ?>										
						
						</div>					
					
					<?php } ?>
					
					<?php if($rak_kl_paatos_valittu){ ?>
					
						<div class="tieto paatos_hyvaksytty" style="line-height: 20px; display: <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hyvaksytty")){ echo "block"; } else { echo "none"; } ?>;">
						
							<div class="kentta_otsikko">
								<?php echo AINEISTO; ?>
							</div>	

							<p><?php echo AINEISTO_LUOVUTETAAN; ?></p>	
							
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
								<textarea class="form_input paatos <?php echo $paatosDTO->ID; ?> Luovutettavat_tiedot tieto_laatikko3"><?php echo htmlentities($paatosDTO->Luovutettavat_tiedot, ENT_COMPAT, "UTF-8"); ?></textarea>
							<?php } else { ?>
								<?php echo htmlentities($paatosDTO->Luovutettavat_tiedot, ENT_COMPAT, "UTF-8"); ?>
							<?php } ?>							
													
						</div>
						
					<?php } ?>	
					
					<?php if($rak_kl_paatos_valittu){ ?>
					
						<div class="tieto paatos_hyvaksytty" style="line-height: 20px; display: <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hyvaksytty")){ echo "block"; } else { echo "none"; } ?>;">
						
							<div class="kentta_otsikko">
								<?php echo LUOVUTUSTAPA; ?>
							</div>	
							
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
								<textarea class="form_input paatos <?php echo $paatosDTO->ID; ?> Luovutustapa tieto_laatikko3"><?php echo htmlentities($paatosDTO->Luovutustapa, ENT_COMPAT, "UTF-8"); ?></textarea>
							<?php } else { ?>
								<?php echo htmlentities($paatosDTO->Luovutustapa, ENT_COMPAT, "UTF-8"); ?>
							<?php } ?>						

						</div>					
					
					<?php } ?>	
					
					<?php if($rak_kl_paatos_valittu){ ?>
					
						<div class="tieto paatos_hyvaksytty" style="line-height: 20px; display: <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hyvaksytty")){ echo "block"; } else { echo "none"; } ?>;">
						
							<div class="kentta_otsikko">
								<?php echo HINTA_ARVIO; ?>
							</div>						
							
							<div style="line-height: 25px;">
							
								<?php echo HINTA_ARVIO_TIEDOT; ?>
								
								<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
									<input class="form_input paatos <?php echo $paatosDTO->ID; ?> Hinta_arvio" type="number" min="1" value="<?php echo htmlentities($paatosDTO->Hinta_arvio, ENT_COMPAT, "UTF-8"); ?>" style="width: 70px; height: 25px;">
								<?php } else { ?>
									<?php echo htmlentities($paatosDTO->Hinta_arvio, ENT_COMPAT, "UTF-8"); ?>
								<?php } ?>		
										
								<?php echo HINTA_ARVIO_TIEDOT_2; ?> 
								<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
									<input class="form_input paatos <?php echo $paatosDTO->ID; ?> Hinta_arvio_alkuvuosi" type="number" value="<?php if(isset($paatosDTO->Hinta_arvio_alkuvuosi)){ echo $paatosDTO->Hinta_arvio_alkuvuosi; } else { echo $paatosDTO->Hinta_arvio_alkuvuosi; } ?>" style="width: 70px; height: 25px;">
								<?php } else { ?>
									<?php echo htmlentities($paatosDTO->Hinta_arvio_alkuvuosi, ENT_COMPAT, "UTF-8"); ?>
								<?php } ?>							
								-
								<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
									<input class="form_input paatos <?php echo $paatosDTO->ID; ?> Hinta_arvio_loppuvuosi" type="number" value="<?php if(isset($paatosDTO->Hinta_arvio_loppuvuosi)){ echo $paatosDTO->Hinta_arvio_loppuvuosi; } else { echo $paatosDTO->Hinta_arvio_loppuvuosi; } ?>" style="width: 70px; height: 25px;">
								<?php } else { ?>
									<?php echo htmlentities($paatosDTO->Hinta_arvio_loppuvuosi, ENT_COMPAT, "UTF-8"); ?>
								<?php } ?>							
								
								. <?php echo HINTA_ARVIO_TIEDOT_3; ?>.
							</div>
						
						</div>
										
					<?php } ?>
					
					<?php if($rak_kl_paatos_valittu){ ?>
					
						<div class="tieto">
						
							<div class="kentta_otsikko">
								<?php echo LUVAN_LAUSUNNOT; ?>
							</div>
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
								<textarea class="form_input paatos <?php echo $paatosDTO->ID; ?> Luvan_lausunnot tieto_laatikko3"><?php echo htmlentities($paatosDTO->Luvan_lausunnot, ENT_COMPAT, "UTF-8"); ?></textarea>
							<?php } else { ?>
								<?php echo htmlentities($paatosDTO->Luvan_lausunnot, ENT_COMPAT, "UTF-8"); ?>
							<?php } ?>	
								
						</div>	

						<div class="tieto">
						
							<div class="kentta_otsikko">
								<?php echo SOV_OIKEUSOHJ; ?>
							</div>
							
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
								<textarea class="form_input paatos <?php echo $paatosDTO->ID; ?> Sovelletut_oikeusohjeet tieto_laatikko3"><?php echo htmlentities($paatosDTO->Sovelletut_oikeusohjeet, ENT_COMPAT, "UTF-8"); ?></textarea>
							<?php } else { ?>
								<?php echo htmlentities($paatosDTO->Sovelletut_oikeusohjeet, ENT_COMPAT, "UTF-8"); ?>
							<?php } ?>						
							
						</div>	

						<div class="tieto paatos_hyvaksytty" style="line-height: 20px; display: <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hyvaksytty")){ echo "block"; } else { echo "none"; } ?>;">

							<div class="kentta_otsikko">
								<?php echo LUVAN_EHDOT; ?>
							</div>					
						
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
								<textarea class="form_input paatos <?php echo $paatosDTO->ID; ?> Luvan_ehdot tieto_laatikko3"><?php echo htmlentities($paatosDTO->Luvan_ehdot, ENT_COMPAT, "UTF-8"); ?></textarea>
							<?php } else { ?>
								<?php echo htmlentities($paatosDTO->Luvan_ehdot, ENT_COMPAT, "UTF-8"); ?>
							<?php } ?>					
						
						</div>

						<div class="tieto">
						
							<div class="kentta_otsikko">
								<?php echo VALITUSOSOITUS; ?>
							</div>
							
							<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
								<textarea class="form_input paatos <?php echo $paatosDTO->ID; ?> Valitusosoitus tieto_laatikko4"><?php echo htmlentities($paatosDTO->Valitusosoitus, ENT_COMPAT, "UTF-8"); ?></textarea>
							<?php } else { ?>
								<?php echo htmlentities($paatosDTO->Valitusosoitus, ENT_COMPAT, "UTF-8"); ?>
							<?php } ?>					
							
						</div>	

						<div class="tieto paatos_hyvaksytty" style="line-height: 20px; display: <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hyvaksytty")){ echo "block"; } else { echo "none"; } ?>;">

							<div class="kentta_otsikko">
								<?php echo MAKSU; ?>
							</div>		

							<div style="line-height: 25px;">
								<?php echo TUTK_MAKSU . " " ; ?>
								
								<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
									<input class="form_input paatos <?php echo $paatosDTO->ID; ?> Maksu_euroina" type="number" value="<?php if(isset($paatosDTO->Maksu_euroina)){ echo $paatosDTO->Maksu_euroina; } else { echo $paatosDTO->Maksu_euroina; } ?>" style="width: 70px; height: 25px;">
								<?php } else { ?>
									<?php echo htmlentities($paatosDTO->Maksu_euroina, ENT_COMPAT, "UTF-8"); ?>
								<?php } ?>	
								
								<?php echo EUROA; ?>. <br><br>
								<?php echo MAKS_PER; ?><br>
								
								<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas"){ ?>
									<textarea class="form_input paatos <?php echo $paatosDTO->ID; ?> Maksu_peruste tieto_laatikko4"><?php if(isset($paatosDTO->Maksu_peruste)) echo htmlentities($paatosDTO->Maksu_peruste, ENT_COMPAT, "UTF-8"); ?></textarea>
								<?php } else { ?>
									<?php echo htmlentities($paatosDTO->Maksu_peruste, ENT_COMPAT, "UTF-8"); ?>
								<?php } ?>	
								
								<br><br>
							</div>
							
							<?php echo LUP_MAKS_VELV; ?><br><br>
							<?php echo htmlentities($paatosDTO->Laskutustieto_1, ENT_COMPAT, "UTF-8"); ?><br><br>
							<?php echo htmlentities($paatosDTO->Laskutustieto_2, ENT_COMPAT, "UTF-8"); ?>	
							<br><br>
							<?php echo MAKSU_INFO; ?>.
						
						</div>	
						
						<div class="tieto paatos_hyvaksytty" style="line-height: 20px; display: <?php if(isset($paatosDTO) && ( $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_hyvaksytty" ) || ( ($paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" || $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_peruttu") && $paatosDTO->Alustava_paatos=="paat_tila_hyvaksytty")){ echo "block"; } else { echo "none"; } ?>;">
						
							<div class="kentta_otsikko">
								<?php echo HAV_ARK_ILMO; ?>
							</div>			

							<?php echo HAV_ARK_ILMO_TIEDOT; ?>	
						
						</div>
				
					<?php } ?>
				
				</div>
			
			</div>	
					
			<?php if(isset($paatosDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu)){ ?>
			
				<?php $parametrit = array();
				$parametrit["osiotDTO_puu"] = $paatosDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu;
				$parametrit["sivun_tunniste"] = $sivu;
				$parametrit["hakija_kayttaja_id"] = null;
				$parametrit["hakemusversio"] = $paatosDTO->HakemusDTO->HakemusversioDTO;
				$parametrit["luo_hakija"] = null;
				$parametrit["lomake_muokkaus_sallittu"] = $lomakkeen_muokkaus_sallittu;
							
				nayta_sivun_osiot($parametrit); ?>			
			
			<?php } ?>
			
			<?php if($paatos_allekirjoitettu){ ?>
				<div class="oikea_sisalto_laatikko">
					<div class="paneeli_otsikko">
						<h2><?php echo PAAT_ALLEKIRJ; ?></h2>
					</div>
					<div class="paneelin_tiedot">
						<div class="tieto">
							<table>
								<tr>
									<?php for($p=0; $p < sizeof($paatosDTO->PaattajatDTO); $p++){ ?>
										<?php if($paatosDTO->PaattajatDTO[$p]->Paatos_allekirjoitettu==1){ ?>
											<td>
												<?php echo $paatosDTO->PaattajatDTO[$p]->KayttajaDTO->Etunimi . " " . $paatosDTO->PaattajatDTO[$p]->KayttajaDTO->Sukunimi; ?><br>
												<?php echo muotoilepvm($paatosDTO->PaattajatDTO[$p]->Muokkauspvm, $_SESSION["kayttaja_kieli"]); ?>
											</td>
										<?php } ?>
									<?php } ?>
								</tr>
							</table>
						</div>
					</div>
				</div>
			<?php } ?>
			
			<?php if(!$eett_paatos_valittu && isset($paatosDTO->LomakeDTO->ID) && !is_null($paatosDTO->LomakeDTO->ID) && $paatosDTO->LomakeDTO->ID!=0){ ?>
				
				<div class="oikea_sisalto_laatikko" style="display: <?php if(sizeof($paatosDTO->Paatoksen_liitteetDTO)==0 && $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_kas") { echo "none;"; } else { echo "block;"; } ?>">	
					
					<div class="paneeli_otsikko">
						<h2><?php echo LIITTEET; ?></h2>
					</div>
						
					<div class="paneelin_tiedot">
					
						<div class="tieto" style="display: <?php if(isset($paatosDTO->Paatoksen_liitteetDTO) && !empty($paatosDTO->Paatoksen_liitteetDTO)){ echo "block;"; } else { echo "none;"; } ?>;">
									
							<div class="kentta_otsikko">
								<?php echo LIS_LIITT; ?>
							</div>
									
							<table class="taulu">
								
								<thead>
									<tr>
										<th align="left"><?php echo PVM; ?></th>
										<th align="left"><?php echo NIMI; ?></th>
										<th align="left"><?php echo LIITE; ?></th>
										<?php if ($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas" && isset($_SESSION["kayttaja_rooli"]) && $_SESSION["kayttaja_rooli"]=="rooli_kasitteleva" && $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken") { ?>
											<th align="left"><?php echo METATIEDOT; ?></th>
										<?php } ?>									
										<?php if ($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas" && isset($_SESSION["kayttaja_rooli"]) && $_SESSION["kayttaja_rooli"]=="rooli_kasitteleva" && $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken") { ?>
											<th align="left"><?php echo POISTA_LIITE; ?></th>
										<?php } ?>
									</tr>
								</thead>

								<?php for($i=0; $i < sizeof($paatosDTO->Paatoksen_liitteetDTO); $i++){ ?>
									<tbody>
										<tr>
											<td><?php echo muotoilepvm($paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
											<td>
												<?php echo htmlentities($paatosDTO->Paatoksen_liitteetDTO[$i]->Liitteen_nimi,ENT_COMPAT, "UTF-8"); ?>
											</td>
											<td>
												<a id="<?php echo $paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->ID; ?>" class="liitetiedosto_linkki" href="liitetiedosto.php?avaa=<?php echo $paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->ID; ?>" target="_blank"><?php echo $paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->Liitetiedosto_nimi; ?></a>										
											</td>
											<?php if ($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas" && isset($_SESSION["kayttaja_rooli"]) && $_SESSION["kayttaja_rooli"]=="rooli_kasitteleva" && $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken") { ?>
												<td>
													<a href="metatiedot.php?&liite_id=<?php echo $paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->ID; ?>&metatiedot_kohde=Liite&hakemus_id=<?php echo $hakemusDTO->ID; ?>"><img src="static/images/meta-edit.png" style="width: 18px; height: 18px;"></a>
												</td>
											<?php } ?>		
											<?php if ($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas" && isset($_SESSION["kayttaja_rooli"]) && $_SESSION["kayttaja_rooli"]=="rooli_kasitteleva" && $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken") { ?>
												<td><a onclick="return confirm('<?php echo LIITE_POISTO_VARMISTUS; ?>');" href="paatos.php?liite_id=<?php echo $paatosDTO->Paatoksen_liitteetDTO[$i]->LiiteDTO->ID; ?>&poista_liite=1&paatos_id=<?php echo $paatosDTO->ID; ?>&hakemus_id=<?php echo $hakemusDTO->ID; ?>" class="ei_alleviivausta"><img src="static/images/erase.png" alt="Poista liitetiedosto" width="16" height="16"></a></td>
											<?php } ?>
										</tr>
									</tbody>
								<?php } ?>
									
							</table>						
							
						</div>
					
						<?php if ($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas" && isset($_SESSION["kayttaja_rooli"]) && $_SESSION["kayttaja_rooli"]=="rooli_kasitteleva" && $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken") { ?>	
							
							<div class="tieto">
										
								<div class="kentta_otsikko">
									<?php echo LISAA_LIITE; ?>
								</div>
										
								<input type="hidden" name="paatos_id" value="<?php echo $paatosDTO->ID; ?>">
								
								<table style="border-collapse: collapse; width: 100%;">
									<tr>
										<td>
											<p><?php echo LIITTEEN_NIMI; ?></p>
										</td>
										<td>
											<p><input type="textarea" name="liitteen_nimi"></p>
										</td>
										<td>
											<p><input type="file" name="lisaa_liite" id="lisaa_liite"></p>
										</td>
										<td>
											<p><input style="background: #9CE3E5; border-radius: 3px;" type="submit" name="lisaa_liite_asiakirja" value="<?php echo LIITA; ?>"></p>
										</td>									
									</tr>

								</table>							
																																						
							</div>					
							
						<?php } ?>	
																			
					</div>	
						
				</div>		

			<?php } ?>	
			
			<?php if(isset($paatosDTO->LomakeDTO->ID) && !is_null($paatosDTO->LomakeDTO->ID) && $paatosDTO->LomakeDTO->ID!=0){ ?>
				<?php if ($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_kas" && isset($_SESSION["kayttaja_rooli"]) && $_SESSION["kayttaja_rooli"]=="rooli_kasitteleva" && $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken") { ?>
					<input onclick="return confirm('<?php echo LAH_PAAT_HYV_VARM; ?>');" name="laheta_paatos_hyvaksyttavaksi" type="submit" class="nappi2" value='<?php echo LAHETA_HYVAKSYTTAVAKSI; ?>' />
				<?php }?>
			<?php } ?>	
			
			<?php if (!$lomakkeen_muokkaus_sallittu ) { ?>
				</fieldset>
			<?php } ?>
			
		</form>
		
		<form enctype="multipart/form-data" id="form_paatos_2" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			<input name="hakemus_id" type="hidden" value="<?php echo $hakemus_id; ?>" />
			<?php if(!$kayttaja_allekirjoittanut_paatoksen && $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val" && $_SESSION["kayttaja_rooli"]=="rooli_paattava" && $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken"){ ?>
				<input onclick="return confirm('<?php echo PAL_HAK_KAS_VARM; ?>');" name="palauta_paatos_kasiteltavaksi" type="submit" class="nappi" value='<?php echo PAL_KAS; ?>' />
			<?php } ?>
			<?php if(!$kayttaja_allekirjoittanut_paatoksen && $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_val" && $paatosDTO->Paatoksen_tilaDTO->Paatoksen_tilan_koodi=="paat_tila_kesken" && ($_SESSION["kayttaja_rooli"]=="rooli_paattava" || $_SESSION["kayttaja_rooli"]=="rooli_eettisen_puheenjohtaja")){ ?>
				<input onclick="return confirm('<?php echo PAAT_VAHV_VARM; ?>');" name="allekirjoita_paatos" type="submit" class="nappi2" value='<?php echo ALLEKIRJOITA_PAATOS; ?>' />
			<?php } ?>
		</form>
	
		<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat"){ ?>
			<a href="paatos_pdf.php?tutkimus_id=<?php echo $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID; ?>&hakemus_id=<?php echo $hakemusDTO->ID; ?>">
				<button class="nappi2" style="float: right;">Lataa päätös PDF-muodossa</button>
			</a>
		<?php } ?>
	
	<?php } ?>
	
</div>
<?php
	include './ui/template/footer.php';
?>