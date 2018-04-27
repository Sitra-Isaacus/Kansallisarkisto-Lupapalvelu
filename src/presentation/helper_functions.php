<?php function nayta_osio($parametrit){ ?>

	<?php
	
	$osiotDTO_puu = (isset($parametrit["osiotDTO_puu"]) ? $parametrit["osiotDTO_puu"] : null);
	$sivun_tunniste = (isset($parametrit["sivun_tunniste"]) ? $parametrit["sivun_tunniste"] : null);
	$indeksi = (isset($parametrit["osio_indeksi"]) ? $parametrit["osio_indeksi"] : null); 
	$hakija_kayttaja_id = (isset($parametrit["hakija_kayttaja_id"]) ? $parametrit["hakija_kayttaja_id"] : null);
	$hakemusversio = (isset($parametrit["hakemusversio"]) ? $parametrit["hakemusversio"] : null);
	$luo_hakija = (isset($parametrit["luo_hakija"]) ? $parametrit["luo_hakija"] : null);
	$lomake_muokkaus_sallittu = (isset($parametrit["lomake_muokkaus_sallittu"]) ? $parametrit["lomake_muokkaus_sallittu"] : null);
				
	?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="valiotsikko"){ ?>
		<div class="valiotsikko o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>">
			<br><h2><?php echo kaanna_osion_kentta($osiotDTO_puu[$indeksi],"Otsikko",$_SESSION['kayttaja_kieli']); ?><?php if(!empty($osiotDTO_puu[$indeksi]->Infoteksti)){ echo nayta_info(kaanna_osion_kentta($osiotDTO_puu[$indeksi],"Infoteksti",$_SESSION['kayttaja_kieli'])); } ?></h2>
		</div>
	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="taulukko"){ ?>

		<div class="osio o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>">
		
			<div style="display: <?php if(is_null(kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli'])) || kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli'])=="" || !preg_match("/[a-z]/i", kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli']))){ echo "none;"; } else { echo "block;"; } ?>" class="taulukko_kysymys">
				<?php echo kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli']); ?>
				<?php if(isset($osiotDTO_puu[$indeksi]->Pakollinen_tieto)){ if($osiotDTO_puu[$indeksi]->Pakollinen_tieto){ echo "*"; } } ?>
				<?php if(!is_null(kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Infoteksti", $_SESSION['kayttaja_kieli']))){ echo nayta_info(kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Infoteksti", $_SESSION['kayttaja_kieli'])); } ?>
			</div>

			<?php
				$lapsi_nro = 0;
				$rivien_lkm = ceil(sizeof($osiotDTO_puu[$indeksi]->OsioDTO_childs) / $osiotDTO_puu[$indeksi]->Sarakkeiden_lkm);
			?>

			<table class="osio_taulukko">
				<?php for($i=0; $i < $rivien_lkm; $i++){ ?>
					<tr>
						<?php for($j=0; $j < $osiotDTO_puu[$indeksi]->Sarakkeiden_lkm; $j++){ ?>

							<?php if(isset($osiotDTO_puu[$indeksi]->OsioDTO_childs[$lapsi_nro])){ ?>

								<td>
									<?php 
									
									$rekursio_parametrit = $parametrit;
									$rekursio_parametrit["osiotDTO_puu"] = $osiotDTO_puu[$indeksi]->OsioDTO_childs;
									$rekursio_parametrit["osio_indeksi"] = $lapsi_nro;
									
									nayta_osio($rekursio_parametrit);
									
									?>
								</td>

								<?php $lapsi_nro++; ?>

							<?php } else { break 2; } ?>

						<?php } ?>
					</tr>
				<?php } ?>
			</table>
			
		</div>

	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="laatikko"){ ?>

		<?php if(!is_null($osiotDTO_puu[$indeksi]->Otsikko)){ ?>
			<div class="valiotsikko o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>">
				<h2><?php echo kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli']); ?><?php if(!empty($osiotDTO_puu[$indeksi]->Infoteksti)){ echo nayta_info(kaanna_osion_kentta($osiotDTO_puu[$indeksi],"Infoteksti",$_SESSION['kayttaja_kieli'])); } ?></h2>
			</div>
		<?php } ?>

		<div class="oikea_sisalto_laatikko o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>">
		
			<?php $rekursio_parametrit = $parametrit;
			$rekursio_parametrit["osiotDTO_puu"] = $osiotDTO_puu[$indeksi]->OsioDTO_childs;					
			nayta_osiot($rekursio_parametrit); ?>
			
		</div>
		
	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="laatikko_otsikko"){ ?>
		<div class="paneeli_otsikko o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>"">
			<h2><?php echo kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli']); ?> <?php if(!is_null(kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Infoteksti", $_SESSION['kayttaja_kieli']))){ echo nayta_info(kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Infoteksti", $_SESSION['kayttaja_kieli'])); } ?> </h2>
		</div>
	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="laatikko_sisalto"){ ?>
		<div class="paneelin_tiedot">
		
			<?php $rekursio_parametrit = $parametrit;
			$rekursio_parametrit["osiotDTO_puu"] = $osiotDTO_puu[$indeksi]->OsioDTO_childs;					
			nayta_osiot($rekursio_parametrit); ?>
			
		</div>
	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="lohko"){ ?>
		<div class="lohko o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>">
		
			<?php $rekursio_parametrit = $parametrit;
			$rekursio_parametrit["osiotDTO_puu"] = $osiotDTO_puu[$indeksi]->OsioDTO_childs;					
			nayta_osiot($rekursio_parametrit); ?>
			
		</div>
	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="kysymys"){ ?>

		<div class="tieto o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" >

			<div style="display: <?php if(is_null(kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli'])) || kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli'])=="" || !preg_match("/[a-z]/i", kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli']))){ echo "none;"; } else { echo "block;"; } ?>" class="kysymys">
				<?php echo kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli']); ?>
				<?php if(isset($osiotDTO_puu[$indeksi]->Pakollinen_tieto)){ if($osiotDTO_puu[$indeksi]->Pakollinen_tieto){ echo "*"; } } ?>
				<?php if(!is_null(kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Infoteksti", $_SESSION['kayttaja_kieli']))){ echo nayta_info(kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Infoteksti", $_SESSION['kayttaja_kieli'])); } ?>
			</div>

			<?php $rekursio_parametrit = $parametrit;
			$rekursio_parametrit["osiotDTO_puu"] = $osiotDTO_puu[$indeksi]->OsioDTO_childs;					
			nayta_osiot($rekursio_parametrit); ?>

		</div>

	<?php } ?>
	
	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="teksti"){ ?>

		<div class="teksti o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" >

			<div style="display: <?php if(is_null(kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli'])) || kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli'])=="" || !preg_match("/[a-z]/i", kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli']))){ echo "none;"; } else { echo "block;"; } ?>">
				<?php echo kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli']); ?>
				<?php if(isset($osiotDTO_puu[$indeksi]->Pakollinen_tieto)){ if($osiotDTO_puu[$indeksi]->Pakollinen_tieto){ echo "*"; } } ?>
				<?php if(!is_null(kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Infoteksti", $_SESSION['kayttaja_kieli']))){ echo nayta_info(kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Infoteksti", $_SESSION['kayttaja_kieli'])); } ?>
			</div>

			<?php $rekursio_parametrit = $parametrit;
			$rekursio_parametrit["osiotDTO_puu"] = $osiotDTO_puu[$indeksi]->OsioDTO_childs;					
			nayta_osiot($rekursio_parametrit); ?>

		</div>

	<?php } ?>	

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="textarea" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="textarea_large"){ ?>

		<?php if(!is_null($osiotDTO_puu[$indeksi]->Otsikko)){ ?> 
			<?php echo kaanna_osion_kentta($osiotDTO_puu[$indeksi], "Otsikko", $_SESSION['kayttaja_kieli']); ?><br>			
		<?php } ?>
	
		<?php if($lomake_muokkaus_sallittu){ ?>

			<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="textarea"){ ?>
				<textarea class="form_input osio o<?php echo $osiotDTO_puu[$indeksi]->ID; ?> tieto_laatikko4" <?php if(!is_null($osiotDTO_puu[$indeksi]->Maksimi_merkit)){ ?> maxlength="<?php echo $osiotDTO_puu[$indeksi]->Maksimi_merkit; ?>" <?php } ?> ><?php if(isset($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_text)){ echo htmlentities($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_text,ENT_COMPAT, "UTF-8"); } ?></textarea>
				<div class="maksimi_merkit"></div>
			<?php } ?>
			<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="textarea_large"){ ?>
				<textarea class="form_input osio o<?php echo $osiotDTO_puu[$indeksi]->ID; ?> tieto_laatikko3" <?php if(!is_null($osiotDTO_puu[$indeksi]->Maksimi_merkit)){ ?> maxlength="<?php echo $osiotDTO_puu[$indeksi]->Maksimi_merkit; ?>" <?php } ?> ><?php if(isset($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_text)){ echo htmlentities($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_text,ENT_COMPAT, "UTF-8"); } ?></textarea>
				<div class="maksimi_merkit"></div>
			<?php } ?>

		<?php } else { ?>
			<div class="tieto_kentta">
				<?php echo htmlentities($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_text,ENT_COMPAT, "UTF-8"); ?>
			</div>
		<?php } ?>

	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="date_start"){ ?>
		<?php if($lomake_muokkaus_sallittu){ ?>
			<input value="<?php if(isset($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_date)){ echo muotoilepvm2($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_date, 'fi'); } ?>" class="form_input osio o<?php echo $osiotDTO_puu[$indeksi]->ID; ?> aika_laatikko alku txt200" id="<?php echo $osiotDTO_puu[$indeksi]->ID ?>-datepicker" type="text" />&nbsp;–&nbsp;
		<?php } else { ?>
			<div class="tieto_kentta" style="display: inline-block;">
				<?php echo muotoilepvm2($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_date,  'fi'); ?> &nbsp;–&nbsp;
			</div>
		<?php } ?>
	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="date_end"){ ?>
		<?php if($lomake_muokkaus_sallittu){ ?>
			<input value="<?php if(isset($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_date)){ echo muotoilepvm2($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_date,  'fi'); } ?>" class="form_input osio o<?php echo $osiotDTO_puu[$indeksi]->ID; ?> aika_laatikko loppu txt200" id="<?php echo $osiotDTO_puu[$indeksi]->ID ?>-datepicker" type="text" />
		<?php } else { ?>
			<div style="display: inline-block; margin-top: 15px;">
				<?php echo muotoilepvm2($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_date,  'fi'); ?>
			</div>
		<?php } ?>
	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="radio"){ ?>

		<?php if($lomake_muokkaus_sallittu){ ?>

			<div>
				<input id="radio_<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" class="form_input osio o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" type="radio" name="<?php echo $osiotDTO_puu[$indeksi]->Osio_luokka; ?>" <?php if (isset($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_boolean) && $osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_boolean==1) echo "checked"; ?> value="<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" />
				<label for="radio_<?php echo $osiotDTO_puu[$indeksi]->ID; ?>"><?php echo kaanna_osion_kentta($osiotDTO_puu[$indeksi],"Otsikko", $_SESSION['kayttaja_kieli']); ?></label>
				<?php if(!empty($osiotDTO_puu[$indeksi]->Infoteksti)){ echo nayta_info(kaanna_osion_kentta($osiotDTO_puu[$indeksi],"Infoteksti", $_SESSION['kayttaja_kieli'])); } ?>
			</div>

		<?php } else { ?>
			<div class="tieto_kentta">
				<?php if(isset($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_boolean) && $osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_boolean==1){ ?>
					<?php echo kaanna_osion_kentta($osiotDTO_puu[$indeksi],"Otsikko",$_SESSION['kayttaja_kieli']); ?>
				<?php } ?>
			</div>
		<?php } ?>

		<?php $rekursio_parametrit = $parametrit;
		$rekursio_parametrit["osiotDTO_puu"] = $osiotDTO_puu[$indeksi]->OsioDTO_childs;					
		nayta_osiot($rekursio_parametrit); ?>

	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="checkbox"){ ?>

		<?php if($lomake_muokkaus_sallittu){ ?>
			<div style="margin-top: 4px;">
				<input id="checkboxi_<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" class="form_input osio o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" type="checkbox" name="<?php echo $osiotDTO_puu[$indeksi]->Osio_luokka; ?>" value="<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" <?php if (isset($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_boolean) && $osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_boolean==1) echo "checked"; ?> >
				<label for="checkboxi_<?php echo $osiotDTO_puu[$indeksi]->ID; ?>">
					<?php echo kaanna_osion_kentta($osiotDTO_puu[$indeksi],"Otsikko", $_SESSION['kayttaja_kieli']); ?> <?php if(!empty($osiotDTO_puu[$indeksi]->Infoteksti)){ echo nayta_info(koodin_selite($osiotDTO_puu[$indeksi]->Infoteksti, $_SESSION['kayttaja_kieli'])); } ?>
				</label>
			</div>
		<?php } else { ?>
			<div class="tieto_kentta">
				<input id="checkboxi_<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" class="form_input osio o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" type="checkbox" name="<?php echo $osiotDTO_puu[$indeksi]->Osio_luokka; ?>" value="<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" <?php if (isset($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_boolean) && $osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_boolean==1) echo "checked"; ?> >
				<label for="checkboxi_<?php echo $osiotDTO_puu[$indeksi]->ID; ?>">
					<?php echo kaanna_osion_kentta($osiotDTO_puu[$indeksi],"Otsikko", $_SESSION['kayttaja_kieli']); ?> <?php if(!empty($osiotDTO_puu[$indeksi]->Infoteksti)){ echo nayta_info(koodin_selite($osiotDTO_puu[$indeksi]->Infoteksti, $_SESSION['kayttaja_kieli'])); } ?>
				</label>
			</div>
		<?php } ?>

		<?php $rekursio_parametrit = $parametrit;
		$rekursio_parametrit["osiotDTO_puu"] = $osiotDTO_puu[$indeksi]->OsioDTO_childs;					
		nayta_osiot($rekursio_parametrit); ?>
		
	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_tapaukset_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_kohdejoukko_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_tapaukset" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_kohdejoukko"){ ?>

		<?php $naytetaan_lohko = true;

		if(($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit_muuttujat" && (!isset($hakemusversio->Haettu_aineistoDTO->Poimitaanko_verrokeille_samat) || $hakemusversio->Haettu_aineistoDTO->Poimitaanko_verrokeille_samat==1)) || ($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot_muuttujat" && (!isset($hakemusversio->Haettu_aineistoDTO->Poimitaanko_viitehenkiloille_samat) || $hakemusversio->Haettu_aineistoDTO->Poimitaanko_viitehenkiloille_samat==1))){
			$naytetaan_lohko = false;
		} ?>

		<?php $biopankki_aineistoa_loydetty = false; ?>

		<div class="oikea_sisalto_laatikko o<?php echo $osiotDTO_puu[$indeksi]->ID; ?>">

			<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit_muuttujat"){ ?>
				<div class="paneeli_otsikko verrokki_poim_samat">
					<h2>
						<?php echo POIMITAANKO_SAMAT_VERROKKI; ?>
					</h2>
					<div class="paneelin_tiedot">
						<div class="tieto">
							<label><input name="Poimitaanko_verrokeille_samat" <?php if(isset($hakemusversio->Haettu_aineistoDTO->Poimitaanko_verrokeille_samat) && $hakemusversio->Haettu_aineistoDTO->Poimitaanko_verrokeille_samat==1) echo "checked"; ?> value="1" class="form_input haettu_aineisto <?php if(isset($hakemusversio->Haettu_aineistoDTO->ID)){ echo $hakemusversio->Haettu_aineistoDTO->ID; } else { echo 0; } ?> Poimitaanko_verrokeille_samat" type="radio"><?php echo KYLLA; ?></label><br>
							<label><input name="Poimitaanko_verrokeille_samat" <?php if(isset($hakemusversio->Haettu_aineistoDTO->Poimitaanko_verrokeille_samat) && $hakemusversio->Haettu_aineistoDTO->Poimitaanko_verrokeille_samat==0) echo "checked"; ?> value="0" class="form_input haettu_aineisto <?php if(isset($hakemusversio->Haettu_aineistoDTO->ID)){ echo $hakemusversio->Haettu_aineistoDTO->ID; } else { echo 0; } ?> Poimitaanko_verrokeille_samat" type="radio"><?php echo EI; ?></label>
						</div><br>
					</div>
				</div>
			<?php } ?>

			<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot_muuttujat"){ ?>
				<div class="paneeli_otsikko viitehenkilot_poim_samat">
					<h2>
						<?php echo POIMITAANKO_SAMAT_VIITE; ?>
					</h2>
					<div class="paneelin_tiedot">
						<div class="tieto">
							<label><input name="Poimitaanko_viitehenkiloille_samat" <?php if(isset($hakemusversio->Haettu_aineistoDTO->Poimitaanko_viitehenkiloille_samat) && $hakemusversio->Haettu_aineistoDTO->Poimitaanko_viitehenkiloille_samat==1) echo "checked"; ?> value="1" class="form_input haettu_aineisto <?php if(isset($hakemusversio->Haettu_aineistoDTO->ID)){ echo $hakemusversio->Haettu_aineistoDTO->ID; } else { echo 0; } ?> Poimitaanko_viitehenkiloille_samat" type="radio"><?php echo KYLLA; ?></label><br>
							<label><input name="Poimitaanko_viitehenkiloille_samat" <?php if(isset($hakemusversio->Haettu_aineistoDTO->Poimitaanko_viitehenkiloille_samat) && $hakemusversio->Haettu_aineistoDTO->Poimitaanko_viitehenkiloille_samat==0) echo "checked"; ?> value="0" class="form_input haettu_aineisto <?php if(isset($hakemusversio->Haettu_aineistoDTO->ID)){ echo $hakemusversio->Haettu_aineistoDTO->ID; } else { echo 0; } ?> Poimitaanko_viitehenkiloille_samat" type="radio"><?php echo EI; ?></label>
						</div><br>
					</div>
				</div>
			<?php } ?>

			<div id="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>-lohko1" class="paneeli_otsikko" style="display: <?php if($naytetaan_lohko){ echo "block;"; } else { echo "none;"; } ?>">
				<h2>
					<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_kohdejoukko"){ echo REK_MAAR1 . " " . KOHDEJOUKKO2; nayta_info(REK_MAAR_KUV); } ?>
					<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot"){ echo REK_MAAR_VIITE; } ?>
					<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_tapaukset"){ echo REK_MAAR2 . " " . TAPAUKSET; nayta_info(REK_MAAR_KUV); } ?>
					<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit"){ echo REK_MAAR2 . " " . j_verrokki; nayta_info(REK_MAAR_KUV); } ?>
					<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_kohdejoukko_muuttujat"){ echo KOHD_POIM; } ?>
					<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_tapaukset_muuttujat"){ echo MAARITTELE_TAPAUKSEN_MUUTTUJAT; } ?>
					<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit_muuttujat"){ echo MAARITTELE_VERROKIT_MUUTTUJAT; } ?>
					<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot_muuttujat"){ echo MAARITTELE_VIITEHENKILO_MUUTTUJAT; } ?>
				</h2>
			</div>

			<div id="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>-lohko2" class="paneelin_tiedot" style="display: <?php if($naytetaan_lohko){ echo "block;"; } else { echo "none;"; } ?>">

				<div class="tieto">

					<h5 style="font-weight:bold; margin-bottom: 0;">
						<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_kohdejoukko"){ echo TUTK_K1 . " " . POIMINTA_MUOD; } ?>
						<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot"){ echo TUTK_V5 . " " . POIMINTA_MUOD; } ?>
						<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_tapaukset"){ echo TUTK_T2 . " " . POIMINTA_MUOD; } ?>
						<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit"){ echo TUTK_VERR2 . " " . POIMINTA_MUOD; } ?>
						<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_tapaukset_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_kohdejoukko_muuttujat"){
							echo RTBA;
						} ?>
					</h5>

					<?php if(isset($hakemusversio->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$osiotDTO_puu[$indeksi]->Osio_tyyppi])){

						for($i=0; $i < sizeof($hakemusversio->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$osiotDTO_puu[$indeksi]->Osio_tyyppi]); $i++){
							
							if(isset($hakemusversio->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$osiotDTO_puu[$indeksi]->Osio_tyyppi][$i]->Luvan_kohdeDTO->Luvan_kohteen_tyyppi) && $hakemusversio->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$osiotDTO_puu[$indeksi]->Osio_tyyppi][$i]->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Biopankki"){
								$biopankki_aineistoa_loydetty = true;
							}
							if(($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") && $i < (sizeof($hakemusversio->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$osiotDTO_puu[$indeksi]->Osio_tyyppi]) - 1)){
								$nayta_lisays_painike = false;
								$nayta_poisto_painike = true;
							} else if($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi!="hv_kesken"){
								$nayta_lisays_painike = false;
								$nayta_poisto_painike = false;
							} else {
								$nayta_lisays_painike = true;
								$nayta_poisto_painike = true;
							}
							if($i==0 && sizeof($hakemusversio->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$osiotDTO_puu[$indeksi]->Osio_tyyppi])==1){
								$nayta_poisto_painike = false;
							}
							
							$rekursio_parametrit = $parametrit;
							$rekursio_parametrit["haettu_luvan_kohdeDTO"] = $hakemusversio->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO[$osiotDTO_puu[$indeksi]->Osio_tyyppi][$i];
							$rekursio_parametrit["nayta_lisays_painike"] = $nayta_lisays_painike;
							$rekursio_parametrit["nayta_poisto_painike"] = $nayta_poisto_painike;
							
							nayta_haettu_luvan_kohde($rekursio_parametrit);

						}

					} else {
						
						$rekursio_parametrit = $parametrit;
						$rekursio_parametrit["haettu_luvan_kohdeDTO"] = null;
						$rekursio_parametrit["nayta_lisays_painike"] = true;
						$rekursio_parametrit["nayta_poisto_painike"] = false;						
						
						nayta_haettu_luvan_kohde($rekursio_parametrit);
						
					} ?>

				</div>

			</div>
						 
			<div class="paneelin_tiedot">
				<div class="tieto">

					<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_tapaukset" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_kohdejoukko"){ ?>
						<div class="paneelin_tiedot">
				
							<?php $rekursio_parametrit = $parametrit;
							$rekursio_parametrit["osiotDTO_puu"] = $osiotDTO_puu[$indeksi]->OsioDTO_childs;					
							nayta_osiot($rekursio_parametrit); ?>
				
						</div>
					<?php } ?>

				</div>
			</div>
			 			
		</div>

	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="lomake_tutkimuksen_nimi"){ ?>

		<div class="tieto">

			<?php if(!is_null($osiotDTO_puu[$indeksi]->Otsikko)){ ?>
				<div class="kysymys">
					<?php echo koodin_selite($osiotDTO_puu[$indeksi]->Otsikko, $_SESSION["kayttaja_kieli"]); ?>
					<?php if(isset($osiotDTO_puu[$indeksi]->Pakollinen_tieto)){ if($osiotDTO_puu[$indeksi]->Pakollinen_tieto){ echo "*"; } } ?>
					<?php if(!empty($osiotDTO_puu[$indeksi]->Infoteksti)){ echo nayta_info(koodin_selite($osiotDTO_puu[$indeksi]->Infoteksti, $_SESSION["kayttaja_kieli"])); } ?>
				</div>
			<?php } ?>

			<?php if((isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") || !isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi)){ ?>
				<textarea name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input <?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?> <?php echo $hakemusversio->ID; ?> Tutkimuksen_nimi tieto_laatikko4"><?php if(isset($hakemusversio->Tutkimuksen_nimi)) echo htmlentities($hakemusversio->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?></textarea>
			<?php } else { ?>
				<div class="tieto_kentta">
					<?php echo htmlentities($hakemusversio->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?>
				</div>
			<?php } ?>

		</div>

	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="kysymys_ja_textarea_large"){ ?>

		<div class="tieto">

			<div class="kysymys">
				<?php echo koodin_selite($osiotDTO_puu[$indeksi]->Otsikko, $_SESSION["kayttaja_kieli"]); ?>
				<?php if(isset($osiotDTO_puu[$indeksi]->Pakollinen_tieto)){ if($osiotDTO_puu[$indeksi]->Pakollinen_tieto){ echo "*"; } } ?>
				<?php if(!empty($osiotDTO_puu[$indeksi]->Infoteksti)){ echo nayta_info(koodin_selite($osiotDTO_puu[$indeksi]->Infoteksti, $_SESSION["kayttaja_kieli"])); } ?>
			</div>

			<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="kysymys_ja_textarea_large"){ ?>
				<textarea class="form_input osio o<?php echo $osiotDTO_puu[$indeksi]->ID; ?> tieto_laatikko3"><?php if(isset($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_text)){ echo htmlentities($osiotDTO_puu[$indeksi]->Osio_sisaltoDTO->Sisalto_text,ENT_COMPAT, "UTF-8"); } ?></textarea>
			<?php } ?>

		</div>

	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="tutkimusryhma") nayta_tutkimusryhma($parametrit); ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="esikatsele_ja_laheta") nayta_esikatsele_ja_laheta($parametrit); ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="liitteet") nayta_kayttolupahakemuksen_liitelomake($parametrit); ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="biopankkinaytteiden_kasittely"){
		
		$rekursio_parametrit = $parametrit;
		$rekursio_parametrit["osiotDTO_puu"] = $osiotDTO_puu[$indeksi]->OsioDTO_childs;		
		nayta_biopankkinaytteiden_kasittely($rekursio_parametrit);
		
	} ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="sosiaalihuollon_jatkokysymykset"){

		$rekursio_parametrit = $parametrit;
		$rekursio_parametrit["osiotDTO_puu"] = $osiotDTO_puu[$indeksi]->OsioDTO_childs;			
		nayta_sosiaalihuollon_jatkokysymykset($rekursio_parametrit);
		
	} ?>
	
	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="organisaatiotiedot") nayta_organisaatiotiedot($parametrit); ?>

<?php } ?>

<?php function nayta_kayttolupahakemuksen_liitelomake($parametrit){ ?>

	<?php $hakemusversio = (isset($parametrit["hakemusversio"]) ? $parametrit["hakemusversio"] : null); 
	$asiakirjahallinta_liitteetDTO = (isset($parametrit["asiakirjahallinta_liitteetDTO"]) ? $parametrit["asiakirjahallinta_liitteetDTO"] : null);
	?>

	<?php if($hakemusversio->Lomakkeen_sivutDTO["hakemus_liitteet"]->Pakollisia_tietoja_puuttuu){ ?>
		<div class="oikea_sisalto_laatikko">

			<div class="paneeli_otsikko">
				<h2><?php echo PAKOLLISET_LIITTEET; ?></h2>
			</div>	
			
					
				<?php for($i=0; $i < sizeof($asiakirjahallinta_liitteetDTO); $i++){ ?>
					<?php if($asiakirjahallinta_liitteetDTO[$i]->Liite_on_pakollinen && $asiakirjahallinta_liitteetDTO[$i]->Liite_puuttuu){ ?>
						<p class="liitteet">
							<?php echo kaanna_osion_kentta($asiakirjahallinta_liitteetDTO[$i],"Liitteen_nimi", $_SESSION["kayttaja_kieli"]); ?>
						</p>
					<?php } ?>
				<?php } ?>				
			

		</div>
	<?php } ?>

	<?php if((isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") || !isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi)){ ?>

		<div class="oikea_sisalto_laatikko">

			<div class="paneeli_otsikko"><h2><?php echo LISAA_UUSI_LIITE; ?></h2></div>

			<div class="paneelin_tiedot">

				<div class="paneeli_otsikko" style="margin-top: 20px;">
					<h3><?php echo VALITSE_LIITTEEN_TYYPPI; ?></h3>
					<p class="liitteet">
						<select id="valittu_liite" name="liitteen_koodi">
							<option value="ei_valittu" selected="selected"><?php echo VALITSE_TYYPPI; ?></option>
							<?php for($i=0; $i < sizeof($asiakirjahallinta_liitteetDTO); $i++) { ?>
								<option title="<?php echo kaanna_osion_kentta($asiakirjahallinta_liitteetDTO[$i],"Liitteen_nimi", $_SESSION["kayttaja_kieli"]); ?>" value="<?php echo $asiakirjahallinta_liitteetDTO[$i]->ID;?>"><?php echo kaanna_osion_kentta($asiakirjahallinta_liitteetDTO[$i], "Liitteen_nimi", $_SESSION["kayttaja_kieli"]); ?></option>
							<?php } ?>
						</select>
					</p>
				</div>

				<div id="valittu_liitetyyppi" style="display: none;">

					<div class="paneeli_otsikko">

						<?php for($i=0; $i < sizeof($asiakirjahallinta_liitteetDTO); $i++){ ?>

							<div style="display: none;" class="liite_lista" id="<?php echo $asiakirjahallinta_liitteetDTO[$i]->ID; ?>">

								<?php if(!is_null($asiakirjahallinta_liitteetDTO[$i]->Lisatiedot) || !is_null($asiakirjahallinta_liitteetDTO[$i]->Lisatiedot_fi)){ ?>
									<h3><?php echo LIITTEEN_TIEDOT; ?></h3>

									<p class="liitteet">
										<?php echo kaanna_osion_kentta($asiakirjahallinta_liitteetDTO[$i], "Lisatiedot", $_SESSION["kayttaja_kieli"]); ?>
									</p>

									<br>

								<?php } ?>	
									
								<h3><?php echo SALLITUT_FORMAATIT; ?></h3>

								<p class="liitteet">
									<?php echo $asiakirjahallinta_liitteetDTO[$i]->Sallitut_tiedostotyypit; ?>
								</p>

								<br>

								<h3><?php echo PAKOLLINEN_LIITETIEDOSTO; ?></h3>

								<p class="liitteet">
									<?php if($asiakirjahallinta_liitteetDTO[$i]->Liite_on_pakollinen){ echo KYLLA; } else { echo EI; } ?>
								</p>

							</div>

						<?php } ?>

					</div>

				</div>

				<div id="liitteen_lisays" style="display: none;">

					<div class="paneeli_otsikko">
						<h3><?php echo LISAA_LIITE; ?> <?php nayta_info(LISAA_LIITE_INFO); ?> </h3>
						<p class="liitteet">
							<input type="file" name="lisaa_liite" id="lisaa_liite">
							<input type="hidden" name="tutkimus_id" value="<?php echo $hakemusversio->TutkimusDTO->ID; ?>">
							<input type="hidden" name="hakemusversio_id" value="<?php echo $hakemusversio->ID; ?>">
							<input type="hidden" name="sivu" value="hakemus_liitteet">
							<input type="submit" name="lisaa_liite_asiakirja" value="<?php echo LIITA; ?>">
						</p>
					</div>

				</div>

			</div>

		</div>

	<?php } ?>

	<?php if(isset($hakemusversio->LiitteetDTO) && !empty($hakemusversio->LiitteetDTO)){ ?>

		<div class="oikea_sisalto_laatikko">

			<div class="paneeli_otsikko">
				<h2><?php echo LISATYT_LIITTEET; ?></h2>
			</div>

				<table class="taulu">
					<thead>
						<tr>
							<th align="left"><?php echo PVM; ?></th>
							<th align="left"><?php echo TYYPPI; ?></th>
							<th align="left"><?php echo LIITE; ?></th>
							<th align="left"><?php echo VERSIO; ?></th>
							<th align="left"><?php echo LIITTEEN_LISAAJA; ?></th>
							<?php if ( (isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") && ( !isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) || (isset($hakemusversio->Lukittu_toiselle_kayttajalle) && !$hakemusversio->Lukittu_toiselle_kayttajalle) )) { ?>
								<th align="left"><?php echo POISTA_LIITE; ?></th>
							<?php } ?>
							<?php if(isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi!="hv_kesken" && ($_SESSION["kayttaja_rooli"]=="rooli_eettisensihteeri" || $_SESSION["kayttaja_rooli"]=="rooli_paattava" || $_SESSION["kayttaja_rooli"]=="rooli_kasitteleva")){ ?>
								<th align="left"><?php echo METATIEDOT; ?></th>
							<?php } ?>
						</tr>
					</thead>

				<?php for($i=0; $i < sizeof($hakemusversio->LiitteetDTO); $i++){ ?>
					<tbody>
						<tr>
							<td><?php echo muotoilepvm($hakemusversio->LiitteetDTO[$i]->Lisayspvm, $_SESSION['kayttaja_kieli']); ?></td>
							<td>
								<?php for($t=0; $t < sizeof($asiakirjahallinta_liitteetDTO); $t++){ 
									if($asiakirjahallinta_liitteetDTO[$t]->ID==$hakemusversio->LiitteetDTO[$i]->Liitteen_tyypin_koodi){
										echo kaanna_osion_kentta($asiakirjahallinta_liitteetDTO[$t],"Liitteen_nimi",$_SESSION['kayttaja_kieli']);
									} 
								} ?>
							</td>
							<td>
								<a id="<?php echo $hakemusversio->LiitteetDTO[$i]->ID; ?>" class="liitetiedosto_linkki" href="liitetiedosto.php?avaa=<?php echo $hakemusversio->LiitteetDTO[$i]->ID; ?>" target="_blank"><?php echo $hakemusversio->LiitteetDTO[$i]->Liitetiedosto_nimi; ?></a>
								<?php if(isset($hakemusversio->LiitteetDTO[$i]->Tiedot_muutettu) && $hakemusversio->LiitteetDTO[$i]->Tiedot_muutettu==true){ echo "( <b style='color: green;'> " . TIEDOSTO_PAIVITETTY . " </b> )"; } ?>
							</td>
							<td>
								<?php echo $hakemusversio->LiitteetDTO[$i]->Versio; ?>
							</td>
							<td><?php echo $hakemusversio->LiitteetDTO[$i]->KayttajaDTO->Etunimi . " " . $hakemusversio->LiitteetDTO[$i]->KayttajaDTO->Sukunimi; ?></td>
							<?php if ( (isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") && ( (isset($hakemusversio->Lukittu_toiselle_kayttajalle) && !$hakemusversio->Lukittu_toiselle_kayttajalle) )) { ?>
								<td>
									<a onclick="return confirm('<?php echo LIITE_POISTO_VARMISTUS; ?>');" href="hakemus.php?sivu=hakemus_liitteet&hakemusversio_id=<?php echo $hakemusversio->ID; ?>&tutkimus_id=<?php echo $hakemusversio->TutkimusDTO->ID; ?>&poista_liite=1&liite_id=<?php echo $hakemusversio->LiitteetDTO[$i]->ID; ?>" class="ei_alleviivausta">
										<img src="static/images/erase.png" alt="Poista liitetiedosto" width="16" height="16">
									</a>
								</td>
							<?php } ?>
							<?php if(isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi!="hv_kesken" && ($_SESSION["kayttaja_rooli"]=="rooli_eettisensihteeri" || $_SESSION["kayttaja_rooli"]=="rooli_paattava" || $_SESSION["kayttaja_rooli"]=="rooli_kasitteleva")){ ?>
								<td>
									<a href="metatiedot.php?liite_id=<?php echo $hakemusversio->LiitteetDTO[$i]->ID; ?>&metatiedot_kohde=Liite&hakemus_id=<?php echo $hakemusversio->HakemusDTO->ID; ?>"><img src="static/images/meta-edit.png" alt="<?php echo META_MUOK;?>" title="<?php echo META_MUOK;?>" style="width: 15px; height: 20px;"></a>
								</td>
							<?php } ?>
						</tr>
					</tbody>
				<?php } ?>
				</table>

		</div>

	<?php } ?>

<?php } ?>

<?php function nayta_esikatsele_ja_laheta($parametrit){ ?>

	<?php 	
	$hakemusversio = (isset($parametrit["hakemusversio"]) ? $parametrit["hakemusversio"] : null); 
	$hakija_kayttaja_id = (isset($parametrit["hakija_kayttaja_id"]) ? $parametrit["hakija_kayttaja_id"] : null);
	$luo_hakija = (isset($parametrit["luo_hakija"]) ? $parametrit["luo_hakija"] : null);
	$hakemuksen_viranomaiset = (isset($parametrit["hakemuksen_viranomaiset"]) ? $parametrit["hakemuksen_viranomaiset"] : null);
	?>
	
	<div class="oikea_sisalto_laatikko">

		<div class="paneeli_otsikko">
			<h2><?php echo HAK_ESIKATSELU; ?></h2>
		</div>

		<div class="paneelin_tiedot">

			<div class="tieto">
				<?php echo HAK_ESIK_INFO; ?>
				<br><br>
				<table>
					<tr>
						<td>	
							<a href="hakemus_pdf.php?hakemusversio_id=<?php echo $hakemusversio->ID; ?>&tutkimus_id=<?php echo $hakemusversio->TutkimusDTO->ID; ?>">
								<img width="70" height="70" src="static/images/pdf_download.png">
							</a>
						</td>
						<td style="vertical-align: middle;">
							<a href="hakemus_pdf.php?hakemusversio_id=<?php echo $hakemusversio->ID; ?>&tutkimus_id=<?php echo $hakemusversio->TutkimusDTO->ID; ?>"><?php echo "FMAS_" . HAKEMUS . "_" . str_ireplace("/", "_", $hakemusversio->Tutkimuksen_nimi) . ".pdf"; ?></a>
						</td>
					</tr>
				</table>
			</div>

		</div>

	</div>

	<div class="oikea_sisalto_laatikko">

		<div class="paneeli_otsikko">
			<h2><?php echo HAK_LAHETTAMINEN; ?></h2>
		</div>

		<div class="paneelin_tiedot">

			<div class="tieto">
				<?php echo HAK_LAHETTAMINEN_INFO; ?>
				<br><br>

				<?php if($hakemusversio->LomakeDTO->ID==1 && $hakemusversio->Hakemuksen_tyyppi=="muutos_hak"){ // Lomake id==1 on käyttölupahakemus ?>
					<?php echo VMVML; ?>:<br><br><br>

					<?php foreach ($hakemuksen_viranomaiset as $viranomaisen_koodi => $muutoshakemus_lahetys_sallittu) { ?>
						<label><input <?php if(!$muutoshakemus_lahetys_sallittu){ echo "disabled"; } ?> name="muutoshakemus_viranomaiset[]" value="<?php echo $viranomaisen_koodi; ?>" type="checkbox">
							<?php echo koodin_selite($viranomaisen_koodi, $_SESSION['kayttaja_kieli']); ?>
							<?php if(!$muutoshakemus_lahetys_sallittu){ echo "(" . MHLES . ")"; } ?>
						</label><br><br>
					<?php } ?>

				<?php } ?>

				<?php if($hakemusversio->Hakemuksen_tyyppi=="muutos_hak"){ ?>
					<button onclick="return confirm('<?php echo HAK_LAH_VARM; ?>');" class="nappi2" <?php if($hakemusversio->Lukittu_toiselle_kayttajalle==1){ echo "disabled"; }?> formaction="hakemus.php?laheta_hakemus=1&tutkimus_id=<?php echo $hakemusversio->TutkimusDTO->ID; ?>&sivu=<?php echo "hakemus_esikatsele_ja_laheta"; ?>&hakemusversio_id=<?php echo $hakemusversio->ID; ?>"><?php echo LAHETA_MUUTOSHAKEMUS; ?> &raquo;</button>
				<?php } else { ?>
					<button onclick="return confirm('<?php echo HAK_LAH_VARM; ?>');" class="nappi2" <?php if($hakemusversio->Lukittu_toiselle_kayttajalle==1){ echo "disabled"; }?> formaction="hakemus.php?laheta_hakemus=1&tutkimus_id=<?php echo $hakemusversio->TutkimusDTO->ID; ?>&sivu=<?php echo "hakemus_esikatsele_ja_laheta"; ?>&hakemusversio_id=<?php echo $hakemusversio->ID; ?>"><?php echo LAHETA_HAKEMUS; ?> &raquo;</button>
				<?php } ?>

			</div>

		</div>

	</div>

<?php } ?>

<?php function nayta_biopankkinaytteiden_kasittely($parametrit){ ?>

	<?php	
		$hakemusversio = (isset($parametrit["hakemusversio"]) ? $parametrit["hakemusversio"] : null); 
		$osiotDTO_puu = (isset($parametrit["osiotDTO_puu"]) ? $parametrit["osiotDTO_puu"] : null); 
		$hakija_kayttaja_id = (isset($parametrit["hakija_kayttaja_id"]) ? $parametrit["hakija_kayttaja_id"] : null); 
		$luo_hakija = (isset($parametrit["luo_hakija"]) ? $parametrit["luo_hakija"] : null); 
		$lomake_muokkaus_sallittu = (isset($parametrit["lomake_muokkaus_sallittu"]) ? $parametrit["lomake_muokkaus_sallittu"] : null);  		
		$nayta_biopankkinaytteiden_kasittely = false;

		if(isset($hakemusversio->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO)) $haetut_luvan_kohteetDTO = $hakemusversio->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO;
					
		foreach ($haetut_luvan_kohteetDTO as $joukko => $haettu_luvan_kohdeDTO) {
			for($i=0; $i < sizeof($haettu_luvan_kohdeDTO); $i++){
				if(isset($haettu_luvan_kohdeDTO[$i]->Luvan_kohdeDTO->Luvan_kohteen_tyyppi) && $haettu_luvan_kohdeDTO[$i]->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Biopankki"){
					if($haettu_luvan_kohdeDTO[$i]->Luvan_kohdeDTO->ID!=500){
						$nayta_biopankkinaytteiden_kasittely = true;
						break 2;						
					}
				} 
			}
		}
	?>

	<div class="biopankkinaytteiden_kasittely" style="display: <?php if($nayta_biopankkinaytteiden_kasittely){ echo "block;"; } else { echo "none;"; } ?>">
		<?php nayta_osiot($parametrit); ?>
	</div>

<?php } ?>

<?php function nayta_sosiaalihuollon_jatkokysymykset($parametrit){ ?>

	<?php

		$osiotDTO_puu = (isset($parametrit["osiotDTO_puu"]) ? $parametrit["osiotDTO_puu"] : null); 
		$hakija_kayttaja_id = (isset($parametrit["hakija_kayttaja_id"]) ? $parametrit["hakija_kayttaja_id"] : null); 
		$luo_hakija = (isset($parametrit["luo_hakija"]) ? $parametrit["luo_hakija"] : null); 
		$lomake_muokkaus_sallittu = (isset($parametrit["lomake_muokkaus_sallittu"]) ? $parametrit["lomake_muokkaus_sallittu"] : null); 
		$hakemusversio = (isset($parametrit["hakemusversio"]) ? $parametrit["hakemusversio"] : null); 
	
		$nayta_sosiaalihuollon_kysymykset = false;
		$haetut_luvan_kohteetDTO = array();
		
		if(isset($hakemusversio->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO)) $haetut_luvan_kohteetDTO = $hakemusversio->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO;
					
		foreach ($haetut_luvan_kohteetDTO as $joukko => $haetut_luvan_kohteetDTO) {
			for($i=0; $i < sizeof($haetut_luvan_kohteetDTO); $i++){
				if(isset($haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->ID) && $haetut_luvan_kohteetDTO[$i]->Luvan_kohdeDTO->ID==180){					
					$nayta_sosiaalihuollon_kysymykset = true;
					break 2;											
				} 
			}
		}
		
	?>

	<div id="sosiaalihuollon_jatkokysymykset" style="display: <?php if($nayta_sosiaalihuollon_kysymykset){ echo "block;"; } else { echo "none;"; } ?>">
		<?php //nayta_osiot($osiotDTO_puu, $hakija_kayttaja_id, $hakemusversio, $luo_hakija, $lomake_muokkaus_sallittu); ?>
	</div>

<?php } ?>

<?php function nayta_organisaatiotiedot($parametrit){ ?>

	<?php $hakemusversio = (isset($parametrit["hakemusversio"]) ? $parametrit["hakemusversio"] : null); 
	$lomake_muokkaus_sallittu = (isset($parametrit["lomake_muokkaus_sallittu"]) ? $parametrit["lomake_muokkaus_sallittu"] : null); ?> 

	<div class="oikea_sisalto_laatikko">

		<div class="paneeli_otsikko">
			<h2><?php echo TUTK_OS_ORG; ?><?php echo nayta_info(TUTK_OS_ORG_INFO); ?></h2>
		</div>

		<div class="paneelin_tiedot">

			<?php if(!is_null($hakemusversio->Osallistuvat_organisaatiotDTO) && !empty($hakemusversio->Osallistuvat_organisaatiotDTO)){ 

				$nayta_poisto_nappi = true;

				if(sizeof($hakemusversio->Osallistuvat_organisaatiotDTO)==1) $nayta_poisto_nappi = false;

				for($i=0; $i < sizeof($hakemusversio->Osallistuvat_organisaatiotDTO); $i++){
					if($i < (sizeof($hakemusversio->Osallistuvat_organisaatiotDTO)-1)){
						nayta_organisaatio($hakemusversio, $hakemusversio->Osallistuvat_organisaatiotDTO[$i], false, $nayta_poisto_nappi, $lomake_muokkaus_sallittu);
					} else {
						nayta_organisaatio($hakemusversio, $hakemusversio->Osallistuvat_organisaatiotDTO[$i], true, $nayta_poisto_nappi, $lomake_muokkaus_sallittu);
					}
				}

			} else { 
				nayta_organisaatio($hakemusversio, null, true, false, $lomake_muokkaus_sallittu);
			} ?>

		</div>

	</div>

<?php } ?>

<?php function nayta_organisaatio($hakemusversio, $osallistuva_organisaatioDTO, $nayta_lisays_nappi, $nayta_poisto_nappi, $lomake_muokkaus_sallittu){ ?>

	<table class="osio_taulukko">
	
		<tr>
			<td>
				<div class="kysymys">
					<?php echo NIMI; ?>
				</div>

				<div style="margin-bottom: 15px;">
					<?php if($lomake_muokkaus_sallittu){ ?>
						<textarea class="form_input organisaatio <?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?> Nimi tieto_laatikko4" ><?php if(isset($osallistuva_organisaatioDTO->Nimi)){ echo htmlentities($osallistuva_organisaatioDTO->Nimi,ENT_COMPAT, "UTF-8"); } ?></textarea>
					<?php } else { ?>
						<?php if(isset($osallistuva_organisaatioDTO->ID)) echo htmlentities($osallistuva_organisaatioDTO->Nimi,ENT_COMPAT, "UTF-8"); ?>
					<?php } ?>
				</div>
			</td>
			<td>
				<div class="kysymys">
					<?php echo OSOITE; ?>
				</div>

				<div style="margin-bottom: 15px;">
					<?php if($lomake_muokkaus_sallittu){ ?>
						<textarea class="form_input organisaatio <?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?> Osoite tieto_laatikko4" ><?php if(isset($osallistuva_organisaatioDTO->Osoite)){ echo htmlentities($osallistuva_organisaatioDTO->Osoite,ENT_COMPAT, "UTF-8"); } ?></textarea>
					<?php } else { 
						if(isset($osallistuva_organisaatioDTO->ID)) echo htmlentities($osallistuva_organisaatioDTO->Osoite,ENT_COMPAT, "UTF-8");
					} ?>
				</div>
			</td>
		</tr>
		
		<tr>
			<td>
				<div class="kysymys">
					<?php echo Y_TUNNUS; ?>
				</div>

				<div style="margin-bottom: 15px;">
					<?php if($lomake_muokkaus_sallittu){ ?>
						<textarea maxlength="9" class="form_input organisaatio <?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?> Y_tunnus tieto_laatikko4" ><?php if(isset($osallistuva_organisaatioDTO->Y_tunnus)){ echo htmlentities($osallistuva_organisaatioDTO->Y_tunnus,ENT_COMPAT, "UTF-8"); } ?></textarea>
						<div class="maksimi_merkit"></div>
					<?php } else { ?>
						<?php if(isset($osallistuva_organisaatioDTO->ID)) echo htmlentities($osallistuva_organisaatioDTO->Y_tunnus,ENT_COMPAT, "UTF-8"); ?>
					<?php } ?>
				</div>
			</td>
			<td>
				<div class="kysymys">
					<?php echo ROOLI_JA_VAST; nayta_info(ROOLI_JA_VAST_INFO); ?>
				</div>

				<div style="margin-bottom: 15px;">
					<?php if($lomake_muokkaus_sallittu){ ?>
						<textarea class="form_input organisaatio <?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?> Rooli tieto_laatikko4" ><?php if(isset($osallistuva_organisaatioDTO->Rooli)){ echo htmlentities($osallistuva_organisaatioDTO->Rooli,ENT_COMPAT, "UTF-8"); } ?></textarea>
					<?php } else { 
						if(isset($osallistuva_organisaatioDTO->ID)) echo htmlentities($osallistuva_organisaatioDTO->Rooli,ENT_COMPAT, "UTF-8");
					} ?>
				</div>
			</td>
		</tr>		
		
		<tr>
			<td>
				<div class="kysymys">
					<?php echo ORG_VIR_ED; ?>
				</div>

				<div style="margin-bottom: 15px;">
					<?php if($lomake_muokkaus_sallittu){ ?>
						<textarea class="form_input organisaatio <?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?> Edustaja tieto_laatikko4" ><?php if(isset($osallistuva_organisaatioDTO->Edustaja)){ echo htmlentities($osallistuva_organisaatioDTO->Edustaja,ENT_COMPAT, "UTF-8"); } ?></textarea>
					<?php } else { 
						if(isset($osallistuva_organisaatioDTO->ID)) echo htmlentities($osallistuva_organisaatioDTO->Edustaja,ENT_COMPAT, "UTF-8");
					} ?>
				</div>
			</td>
			<td>
				<div class="kysymys">
					<?php echo VIR_ED_SAHK; ?>
				</div>

				<div style="margin-bottom: 15px;">
					<?php if($lomake_muokkaus_sallittu){ ?>
						<textarea class="form_input organisaatio <?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?> Edustajan_email tieto_laatikko4" ><?php if(isset($osallistuva_organisaatioDTO->Edustajan_email)){ echo htmlentities($osallistuva_organisaatioDTO->Edustajan_email,ENT_COMPAT, "UTF-8"); } ?></textarea>
					<?php } else { 
						if(isset($osallistuva_organisaatioDTO->ID)) echo htmlentities($osallistuva_organisaatioDTO->Edustajan_email,ENT_COMPAT, "UTF-8");
					} ?>
				</div>
			</td>
		</tr>
		
		<tr>
			<td>
				<div class="kysymys">
					<?php echo ONKO_MTA_AK; ?><?php echo nayta_info(ONKO_MTA_AK_INFO); ?>
				</div>

				<div style="margin-bottom: 15px;">
					<label><input name="mta_allekirjoittaja-<?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?>" class="form_input organisaatio <?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?> MTA_allekirjoittaja" type="radio" <?php if (isset($osallistuva_organisaatioDTO->MTA_allekirjoittaja) && $osallistuva_organisaatioDTO->MTA_allekirjoittaja==1) echo "checked"; ?> value="1" /><?php echo KYLLA; ?></label><br>
					<label><input name="mta_allekirjoittaja-<?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?>" class="form_input organisaatio <?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?> MTA_allekirjoittaja" type="radio" <?php if (isset($osallistuva_organisaatioDTO->MTA_allekirjoittaja) && $osallistuva_organisaatioDTO->MTA_allekirjoittaja==0) echo "checked"; ?> value="0" /><?php echo EI; ?></label>
				</div>
			</td>
			<td>
				<div class="kysymys">
					<?php echo REKISTERINPITAJA;?> * <?php nayta_info(REKISTERINPITAJA_INFO); ?>
				</div>

				<label><input name="rekisterinpitaja-<?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?>" class="form_input organisaatio <?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?> Rekisterinpitaja" type="radio" <?php if (isset($osallistuva_organisaatioDTO->Rekisterinpitaja) && $osallistuva_organisaatioDTO->Rekisterinpitaja==1) echo "checked"; ?> value="1" /><?php echo KYLLA; ?></label><br>
				<label><input name="rekisterinpitaja-<?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?>" class="form_input organisaatio <?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?> Rekisterinpitaja" type="radio" <?php if (isset($osallistuva_organisaatioDTO->Rekisterinpitaja) && $osallistuva_organisaatioDTO->Rekisterinpitaja==0) echo "checked"; ?> value="0" /><?php echo EI; ?></label>

			</td>
		</tr>
		
	</table>

	<?php if($lomake_muokkaus_sallittu){ ?> <input style="display: <?php if($nayta_lisays_nappi){ echo "block;"; } else { echo "none;"; } ?>" class="form_nappi organisaatio 0 Uusi_organisaatio nappi2" value="<?php echo LISAA_ORGANISAATIO; ?> » " type="button"> <?php } ?>
	<?php if($lomake_muokkaus_sallittu){ ?> <input style="display: <?php if($nayta_poisto_nappi){ echo "block;"; } else { echo "none;"; } ?>;" class="form_nappi organisaatio <?php if(isset($osallistuva_organisaatioDTO->ID)){ echo $osallistuva_organisaatioDTO->ID; } else { echo 0; } ?> Poistettava_organisaatio poista2" value="<?php echo POISTA_ORGANISAATIO; ?> » " type="button"> <?php } ?>
	<br>
	<hr>

<?php } ?>

<?php function nayta_haettu_luvan_kohde($parametrit){ ?>

	<?php
	// Muuttujien alustus
	$hakemusversio = (isset($parametrit["hakemusversio"]) ? $parametrit["hakemusversio"] : null); 
	$haettu_luvan_kohdeDTO = (isset($parametrit["haettu_luvan_kohdeDTO"]) ? $parametrit["haettu_luvan_kohdeDTO"] : null);
	$osiotDTO_puu = (isset($parametrit["osiotDTO_puu"]) ? $parametrit["osiotDTO_puu"] : null);
	$indeksi = (isset($parametrit["osio_indeksi"]) ? $parametrit["osio_indeksi"] : null);
	$nayta_lisays_painike = (isset($parametrit["nayta_lisays_painike"]) ? $parametrit["nayta_lisays_painike"] : null);
	$nayta_poisto_painike = (isset($parametrit["nayta_poisto_painike"]) ? $parametrit["nayta_poisto_painike"] : null);
	$lomake_muokkaus_sallittu = (isset($parametrit["lomake_muokkaus_sallittu"]) ? $parametrit["lomake_muokkaus_sallittu"] : null);
	$kaikki_luvan_kohteet = (isset($parametrit["kaikki_luvan_kohteet"]) ? $parametrit["kaikki_luvan_kohteet"] : null);
	$viranomaisten_luvan_kohteet = (isset($parametrit["viranomaisten_luvan_kohteet"]) ? $parametrit["viranomaisten_luvan_kohteet"] : null);
	$taika_luvan_kohteet = (isset($parametrit["taika_luvan_kohteet"]) ? $parametrit["taika_luvan_kohteet"] : null);
	$nayta_poim_muuttujat_biopankit = (isset($parametrit["nayta_poim_muuttujat_biopankit"]) ? $parametrit["nayta_poim_muuttujat_biopankit"] : null);
	
	if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_kohdejoukko") $viranomainen = VALITSE_VIR_KOHD_MUUTT;			
	if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot") $viranomainen = VALITSE_VIRANOMAINEN_VIITE; 			 
	if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_tapaukset") $viranomainen = VALITSE_VIR_TAPAUS_MUUTT; 			 
	if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit") $viranomainen = VALITSE_VIRANOMAINEN_VERROKKI;  			
	if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_tapaukset_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_kohdejoukko_muuttujat") $viranomainen = VALITSE_LUPVIR;
	?>
	
	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_tapaukset" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_kohdejoukko" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot"){ ?>

		<div class="paneelin_tiedot">

			<h5 style="font-weight:bold; margin-bottom: 0;">
				<?php echo REK_TILASTOAIN; ?>
			</h5>

			<div class="tieto">

				<div style="margin-top: 24px; margin-bottom: 4px;"><?php echo $viranomainen; ?> <?php nayta_info(VALITSE_VIR_INFO); ?> </div>

				<a id="luv_kohde-a-<?php if (isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>"></a>				
				<select id="vir_koodi-<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Viranomaisen_koodi">
					<?php global $VIRANOMAISEN_KOODIT; foreach ($VIRANOMAISEN_KOODIT as $vir_koodi => $kaannos) { ?>
						<?php if($vir_koodi=="v_BIO") continue; // Skipataan bio-aineistot ?>
						<option value="<?php echo $vir_koodi; ?>" <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi) && $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi == $vir_koodi){ echo "selected"; } ?>>
							<?php echo $kaannos; ?>
						</option>
					<?php } ?>
				</select>

				<div style="margin-top: 24px; margin-bottom: 4px;"><?php echo VALITSE_REKISTERI ; ?></div>

				<select id="luv_kohde-<?php echo $osiotDTO_puu[$indeksi]->ID; ?>" name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> FK_Luvan_kohde">

					<?php if (!isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi)) {
						$luvan_kohteet = $kaikki_luvan_kohteet;
					}  else {
						$luvan_kohteet = $viranomaisten_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi];
					}

					for($i=0; $i < sizeof($luvan_kohteet); $i++){ ?>
						<?php if($luvan_kohteet[$i]->Luvan_kohteen_tyyppi=="Biopankki") continue; // Skipataan bio-aineisto ?>
						<option <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID) && $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID==$luvan_kohteet[$i]->ID) echo "selected"; ?> value="<?php echo $luvan_kohteet[$i]->ID; ?>"><?php echo $luvan_kohteet[$i]->Luvan_kohteen_nimi; ?></option>
					<?php } ?>

				</select>

				<div class="tk_muuttujat_aineisto" style="display: <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi) && ($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Aineistokatalogi" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Taika_tilastoaineisto")){ echo "block;"; } else { echo "none;"; } ?>">

					<div style="display: <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier) && isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Selite)){ echo "block;"; } else { echo "none;"; } ?> margin-top: 30px; margin-bottom: 30px;">

						<h3 id="aineisto_lisatiedot_expand-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" class="aineisto_lisatiedot_click" style="cursor: pointer;"><?php echo LISATIETOJA_AIN; ?><img src="static/images/expand.png"></h3>
						<h3 id="aineisto_lisatiedot_collapse-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" class="aineisto_lisatiedot_click" style="display: none; cursor: pointer;"><?php echo LISATIETOJA_AIN; ?><img src="static/images/collapse.png"></h3>
																		
						<div id="tk_muuttujat_aineisto_sisalto-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" style="display: none; line-height: 20px;">
							<?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier) && isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Selite)){ ?>
							<p><?php echo htmlentities($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Selite,ENT_COMPAT, "UTF-8"); ?></p>
							<?php } ?>
						</div>

					</div>

					<h3 id="poimi_muuttujat_expand-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" class="poimi_muuttujat_click" style="display: none; cursor: pointer;"><?php echo POIMI_MUUT; ?><img src="static/images/expand.png"></h3>
					<h3 id="poimi_muuttujat_collapse-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" class="poimi_muuttujat_click" style="cursor: pointer;"><?php echo POIMI_MUUT; ?><img src="static/images/collapse.png"></h3>

					<div id="taika_muuttuja_poiminta_sisalto-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" class="tieto">
						<table>
							<tr>
								<td>

									<?php $valitse_kaikki_koodi = "valitse_kaikki"; 

									if(isset($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO) && isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO)){
										if(sizeof($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO)==sizeof($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO)){
											$valitse_kaikki_koodi = "poista_kaikki";
										}
									}

									?>

									<label style="cursor: pointer;">
										<input <?php if($valitse_kaikki_koodi=="poista_kaikki"){ echo "checked"; } ?> value="<?php echo $valitse_kaikki_koodi; ?>" class="form_input haettu_muuttuja <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Kaikki_muuttujat" id="valitse_kaikki_m-<?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID; } else { echo 0; } ?>" type="checkbox"><?php echo VALITSE_KAIKKI; ?>
									</label>

									<br><br>

									<?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier) && isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO)) {
										for($i=0; $i < sizeof($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO); $i++){ ?>

											<?php $taika_muutt_checked = false; ?>

											<?php if(isset($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO)){ ?>
												<?php for($m=0; $m < sizeof($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO); $m++){ 
													if(isset($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO[$m]->Muuttujan_koodi) && $haettu_luvan_kohdeDTO->Haetut_muuttujatDTO[$m]->Muuttujan_koodi==$taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Tunnus) $taika_muutt_checked = true; 									 
												} ?>
											<?php } ?>

											<label style="cursor: pointer;">
												<input <?php if($taika_muutt_checked) echo "checked"; ?> class="form_input haettu_muuttuja <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Muuttujan_koodi"  value="<?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Tunnus; ?>" type="checkbox">
												<?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Nimi; ?><?php if(isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku) && !is_null($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku) && $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku!="0000-00-00") echo ", " . muotoilepvm($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku, "fi") . " -"; ?>												
												<?php if(isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu) && !is_null($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu) && $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu!="0000-00-00") echo muotoilepvm($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu, "fi"); ?>
											</label><br>

										<?php }
									} ?>

								</td>
								<td>
									<?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier) && isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO)) {
										for($i=0; $i < sizeof($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO); $i++){ ?>

											<div class="muuttuja_lisatieto-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?> muuttujan_lisatiedot" id="muuttuja_lisatieto-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>-<?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Tunnus; ?>" style="display: none;">

												<h3 style="text-align: center;">
													<?php echo MUUTT_LISATIED; ?>
												</h3>

												<?php if(!empty($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Nimi)){ ?>
													<p style="font-weight: bold;"><?php echo NIMI; ?></p>
													<p><?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Nimi; ?></p>
												<?php } ?>

												<?php if(!empty($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Kuvaus)){ ?>
													<p style="font-weight: bold;"><?php echo MUUTT_KUVAUS; ?></p>
													<p><?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Kuvaus; ?></p>
												<?php } ?>

												<?php if(!empty($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Mittayksikko)){ ?>
													<p style="font-weight: bold;"><?php echo MITTAYKSIKKO; ?></p>
													<p><?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Mittayksikko; ?></p>
												<?php } ?>
												
												<?php if(isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku) && !is_null($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku) && $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku!="0000-00-00"){ ?>
													<p style="font-weight: bold;"><?php echo "Viiteajankohta"; ?></p>
													<p>
														<?php echo muotoilepvm($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku, "fi") . " - "; ?>
														<?php if(isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu) && !is_null($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu) && $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu!="0000-00-00") echo muotoilepvm($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu, "fi"); ?>
													</p>
												<?php } ?>

											</div>

										<?php } ?>
									<?php } ?>
								</td>
							</tr>
						</table>
					</div>

				</div>

				<div class="muuttujat_lueteltuna" style="margin-top: 24px; display: <?php if(!isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID) || (isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi) && ($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Aineistokatalogi" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Taika_tilastoaineisto" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID==180 || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID==179))){ echo "none;"; } else { echo "block;"; } ?>">

					<?php echo LUETT_POIM_MUUTT; ?> <?php echo nayta_info(POIM_MUUTTUJA_INFO); ?><br>

					<?php if((isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") || !isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi)){ ?>
						<textarea name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Muuttujat_lueteltuna tieto_laatikko3"><?php if(isset($haettu_luvan_kohdeDTO->Muuttujat_lueteltuna)) echo htmlentities($haettu_luvan_kohdeDTO->Muuttujat_lueteltuna,ENT_COMPAT, "UTF-8"); ?></textarea>
					<?php } else { ?>
						<div class="tieto_kentta">
							<?php if (isset($haettu_luvan_kohdeDTO->Muuttujat_lueteltuna)) echo htmlentities($haettu_luvan_kohdeDTO->Muuttujat_lueteltuna,ENT_COMPAT, "UTF-8"); ?>
						</div>
					<?php } ?>

				</div>

				<div class="poimintaajankohdat" style="display: <?php if(!isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID) || ($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID==180 || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID==179)){ echo "none;"; } else { echo "block;"; } ?>">
					
					<div style="margin-top: 24px;"><?php echo MAARITTELE_POIMINTAAJANKOHDAT; ?></div>

					<?php if((isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") || !isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi)){ ?>
						<textarea name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Poiminta_ajankohdat tieto_laatikko3"><?php if(isset($haettu_luvan_kohdeDTO->Poiminta_ajankohdat)) echo htmlentities($haettu_luvan_kohdeDTO->Poiminta_ajankohdat,ENT_COMPAT, "UTF-8"); ?></textarea>
					<?php } else { ?>
						<div class="tieto_kentta">
							<?php if (isset($haettu_luvan_kohdeDTO->Poiminta_ajankohdat)) echo htmlentities($haettu_luvan_kohdeDTO->Poiminta_ajankohdat,ENT_COMPAT, "UTF-8"); ?>
						</div>
					<?php } ?>
					
				</div>
				
				<div class="potilas_ask_lisatiedot" style="display: <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID) && ($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID==180 || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID==179)){ echo "block;"; } else { echo "none;"; } ?>">
									
					<div style="margin-top: 24px;"><?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID) && $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID==180){ echo TKPSST; } else { echo TKPSTT; } ?></div>
					
					<?php if((isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") || !isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi)){ ?>
						<textarea name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Toimintayksikot tieto_laatikko3"><?php if(isset($haettu_luvan_kohdeDTO->Toimintayksikot)) echo htmlentities($haettu_luvan_kohdeDTO->Toimintayksikot,ENT_COMPAT, "UTF-8"); ?></textarea>
					<?php } else { ?>
						<div class="tieto_kentta">
							<?php if(isset($haettu_luvan_kohdeDTO->Toimintayksikot)) echo htmlentities($haettu_luvan_kohdeDTO->Toimintayksikot, ENT_COMPAT, "UTF-8"); ?>
						</div>
					<?php } ?>		

					<div style="margin-top: 24px;"><?php echo KOHD_MK; ?></div>
					
					<?php if((isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") || !isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi)){ ?>
						<textarea name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Kohdejoukon_mukaanottokriteerit tieto_laatikko3"><?php if(isset($haettu_luvan_kohdeDTO->Kohdejoukon_mukaanottokriteerit)) echo htmlentities($haettu_luvan_kohdeDTO->Kohdejoukon_mukaanottokriteerit,ENT_COMPAT, "UTF-8"); ?></textarea>
					<?php } else { ?>
						<div class="tieto_kentta">
							<?php if(isset($haettu_luvan_kohdeDTO->Kohdejoukon_mukaanottokriteerit)) echo htmlentities($haettu_luvan_kohdeDTO->Kohdejoukon_mukaanottokriteerit, ENT_COMPAT, "UTF-8"); ?>
						</div>
					<?php } ?>	

					<div style="margin-top: 24px;"><?php echo TOIM_YHT; ?></div><br>
										
					<label class="t_yht_true">
						<input class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Toimintayksikoihin_on_oltu_yhteydessa" type="radio" name="t_yht-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" <?php if (isset($haettu_luvan_kohdeDTO->Toimintayksikoihin_on_oltu_yhteydessa) && $haettu_luvan_kohdeDTO->Toimintayksikoihin_on_oltu_yhteydessa==1) echo "checked"; ?> value="1" />					
						<?php echo KYLLA; ?>
					</label>

					<label class="t_yht_false">
						<input class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Toimintayksikoihin_on_oltu_yhteydessa" type="radio" name="t_yht-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" <?php if (isset($haettu_luvan_kohdeDTO->Toimintayksikoihin_on_oltu_yhteydessa) && $haettu_luvan_kohdeDTO->Toimintayksikoihin_on_oltu_yhteydessa==0) echo "checked"; ?> value="0" />					
						<?php echo EI; ?>
					</label>					
													
				</div>
				
			</div>

			<br>

			<table style="width: 100%;">
				<tr>
					<td>
						<input style="display: <?php if($nayta_lisays_painike){ echo "block"; } else { echo "none;"; } ?>" name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" type="button" class="form_nappi haettu_luvan_kohde 0 Uusi_haettu_luvan_kohde nappi2" value="<?php echo LISAA_REKISTERI; ?> &raquo ">
						<input style="display: <?php if($nayta_poisto_painike){ echo "block"; } else { echo "none;"; } ?>" type="button" class="form_nappi haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Poistettava_haettu_luvan_kohde poista2" value="<?php echo POISTA_REKISTERI; ?> &raquo ">
					</td>
				</tr>
			</table>

		</div>

	<?php } ?>

	<?php if($osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_verrokit_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_tapaukset_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_viitehenkilot_muuttujat" || $osiotDTO_puu[$indeksi]->Osio_tyyppi=="haettu_kohde_kohdejoukko_muuttujat"){ ?>

		<div class="paneelin_tiedot">

			<div class="tieto">

				<div style="margin-top: 24px;"><?php echo $viranomainen; ?></div>

				<a id="luv_kohde-a-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>"></a>
				<select id="vir_koodi-<?php echo $osiotDTO_puu[$indeksi]->ID; ?>-<?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?>" name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Viranomaisen_koodi">
					<?php global $VIRANOMAISEN_KOODIT; foreach ($VIRANOMAISEN_KOODIT as $vir_koodi => $kaannos) { ?>
						<option value="<?php echo $vir_koodi; ?>" <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi) && $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi == $vir_koodi){ echo "selected"; } ?>>
							<?php echo $kaannos; ?>
						</option>
					<?php } ?>
				</select>

				<div style="margin-top: 24px;"><?php echo VAL_REK_TAI_MUU ; ?></div>

				<select id="luv_kohde-<?php echo $osiotDTO_puu[$indeksi]->ID; ?>-<?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?>" name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> FK_Luvan_kohde">

					<?php if (!isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi)) {
						$luvan_kohteet = $kaikki_luvan_kohteet;
					}  else {
						$luvan_kohteet = $viranomaisten_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Viranomaisen_koodi];
					}

					for($i=0; $i < sizeof($luvan_kohteet); $i++){ ?>
						<option <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID) && $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID==$luvan_kohteet[$i]->ID) echo "selected"; ?> value="<?php echo $luvan_kohteet[$i]->ID; ?>"><?php echo $luvan_kohteet[$i]->Luvan_kohteen_nimi; ?></option>
					<?php } ?>

				</select>

				<div class="tk_muuttujat_aineisto" style="display: <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi) && ($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Aineistokatalogi" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Taika_tilastoaineisto")){ echo "block;"; } else { echo "none;"; } ?>">

					<div style="display: <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier) && isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Selite)){ echo "block;"; } else { echo "none;"; } ?> margin-top: 30px; margin-bottom: 30px;">

						<h3 id="aineisto_lisatiedot_expand-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" class="aineisto_lisatiedot_click" style="cursor: pointer;"><?php echo LISATIETOJA_AIN; ?><img src="static/images/expand.png"></h3>
						<h3 id="aineisto_lisatiedot_collapse-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" class="aineisto_lisatiedot_click" style="display: none; cursor: pointer;"><?php echo LISATIETOJA_AIN; ?><img src="static/images/collapse.png"></h3>

						<div id="tk_muuttujat_aineisto_sisalto-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>"style="display: none; line-height: 20px;">
							<p><?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier) && isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Selite)) echo htmlentities($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Selite,ENT_COMPAT, "UTF-8"); ?></p>
						</div>

					</div>

					<h3 id="poimi_muuttujat_expand-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" class="poimi_muuttujat_click" style="display: none; cursor: pointer;"><?php echo POIMI_MUUT; ?><img src="static/images/expand.png"></h3>
					<h3 id="poimi_muuttujat_collapse-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" class="poimi_muuttujat_click" style="cursor: pointer;"><?php echo POIMI_MUUT; ?><img src="static/images/collapse.png"></h3>

					<div id="taika_muuttuja_poiminta_sisalto-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>" class="tieto">
						<table>
							<tr>
								<td>

									<?php $valitse_kaikki_koodi = "valitse_kaikki";

									if(isset($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO) && isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO)){
										if(sizeof($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO)==sizeof($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO)){
											$valitse_kaikki_koodi = "poista_kaikki";
										}
									}

									?>

									<label style="cursor: pointer;">
										<input <?php if($valitse_kaikki_koodi=="poista_kaikki"){ echo "checked"; } ?> value="<?php echo $valitse_kaikki_koodi; ?>" class="form_input haettu_muuttuja <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Kaikki_muuttujat" id="valitse_kaikki_m-<?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID; } else { echo 0; } ?>" type="checkbox"><?php echo VALITSE_KAIKKI; ?>
									</label>

									<br><br>
									<?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier) && isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO)) {
										for($i=0; $i < sizeof($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO); $i++){ ?>

											<?php $taika_muutt_checked = false; ?>

											<?php if(isset($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO)){ ?>
												<?php for($m=0; $m < sizeof($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO); $m++){ 
													if(isset($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO[$m]->Muuttujan_koodi) && $haettu_luvan_kohdeDTO->Haetut_muuttujatDTO[$m]->Muuttujan_koodi==$taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Tunnus) $taika_muutt_checked = true; 									 
												} ?>
											<?php } ?>

											<label style="cursor: pointer;"><input <?php if($taika_muutt_checked) echo "checked"; ?> class="form_input haettu_muuttuja <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Muuttujan_koodi"  value="<?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Tunnus; ?>" type="checkbox">
												<?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Nimi; ?> <?php if(isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku) && !is_null($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku) && $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku!="0000-00-00") echo ", " . muotoilepvm($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku, "fi") . " -"; ?>												
												<?php if(isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu) && !is_null($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu) && $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu!="0000-00-00") echo muotoilepvm($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu, "fi"); ?>												
											</label><br>

										<?php }
									} ?>

								</td>
								<td>
									<?php  if (isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier) && isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO)) {
										for($i=0; $i < sizeof($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO); $i++){ ?>
											<div class="muuttuja_lisatieto-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?> muuttujan_lisatiedot" id="muuttuja_lisatieto-<?php if(isset($haettu_luvan_kohdeDTO->ID)) echo $haettu_luvan_kohdeDTO->ID; ?>-<?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Tunnus; ?>" style="display: none;">

												<h3 style="text-align: center;">
													<?php echo MUUTT_LISATIED; ?>
												</h3>

												<?php if(!empty($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Nimi)){ ?>
													<p style="font-weight: bold;"><?php echo NIMI; ?></p>
													<p><?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Nimi; ?></p>
												<?php } ?>

												<?php if(!empty($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Kuvaus)){ ?>
													<p style="font-weight: bold;"><?php echo MUUTT_KUVAUS; ?></p>
													<p><?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Kuvaus; ?></p>
												<?php } ?>

												<?php if(!empty($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Mittayksikko)){ ?>
													<p style="font-weight: bold;"><?php echo MITTAYKSIKKO; ?></p>
													<p><?php echo $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->MuuttujatDTO[$i]->Mittayksikko; ?></p>
												<?php } ?>
												
												<?php if(isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku) && !is_null($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku) && $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku!="0000-00-00"){ ?>
													<p style="font-weight: bold;"><?php echo "Viiteajankohta"; ?></p>
													<p>
														<?php echo muotoilepvm($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_alku, "fi") . " - "; ?>
														<?php if(isset($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu) && !is_null($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu) && $taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu!="0000-00-00") echo muotoilepvm($taika_luvan_kohteet[$haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Identifier]->Viiteajankohta_loppu, "fi"); ?>
													</p>
												<?php } ?>												

											</div>
										<?php } ?>
									<?php } ?>
								</td>
							</tr>
						</table>
					</div>

				</div>

				<?php /*
				<div id="rekisteri_kuvaus-<?php echo $osiotDTO_puu[$indeksi]->ID; ?>-<?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?>" class="rekisteri_kuvaus <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?>" style="display: <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID) && ( $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Aineistokatalogi" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Taika_tilastoaineisto" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Rekisteri" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Asiakirja" )){ echo "block;"; } else { echo "none;"; } ?>">
				*/ ?>
				
					<div class="muuttujat_lueteltuna" style="margin-top: 24px; display: <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID) && ( $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Aineistokatalogi" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Taika_tilastoaineisto" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Rekisteri" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Asiakirja" )){ echo "block;"; } else { echo "none;"; } ?>">
						<div style="margin-top: 24px;"><?php echo LUETT_POIM_MUUTT; ?> <?php echo nayta_info(POIM_MUUTTUJA_INFO); ?></div>

						<?php if((isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") || !isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi)){ ?>
							<textarea name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Muuttujat_lueteltuna tieto_laatikko3"><?php if(isset($haettu_luvan_kohdeDTO->Muuttujat_lueteltuna)) echo htmlentities($haettu_luvan_kohdeDTO->Muuttujat_lueteltuna,ENT_COMPAT, "UTF-8"); ?></textarea>
						<?php } else { ?>
							<div class="tieto_kentta">
								<?php if(isset($haettu_luvan_kohdeDTO->Muuttujat_lueteltuna)) echo htmlentities($haettu_luvan_kohdeDTO->Muuttujat_lueteltuna,ENT_COMPAT, "UTF-8"); ?>
							</div>
						<?php } ?>
					</div>
					
				<?php /*
				</div>
				*/ ?>
				
				<div class="poimintaajankohdat" style="display: <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID) && ( $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Aineistokatalogi" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Taika_tilastoaineisto" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Rekisteri" || $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Asiakirja" )){ echo "block;"; } else { echo "none;"; } ?>">
				
					<div style="margin-top: 24px;"><?php echo MAARITTELE_POIMINTAAJANKOHDAT; ?></div>

					<?php if((isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") || !isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi)){ ?>
						<textarea name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Poiminta_ajankohdat tieto_laatikko3"><?php if(isset($haettu_luvan_kohdeDTO->Poiminta_ajankohdat)) echo htmlentities($haettu_luvan_kohdeDTO->Poiminta_ajankohdat,ENT_COMPAT, "UTF-8"); ?></textarea>
					<?php } else { ?>
						<div class="tieto_kentta">
							<?php if (isset($haettu_luvan_kohdeDTO->Poiminta_ajankohdat)) echo htmlentities($haettu_luvan_kohdeDTO->Poiminta_ajankohdat,ENT_COMPAT, "UTF-8"); ?>
						</div>
					<?php } ?>	
					
				</div>			
					
				<div class="biopankki_kuvaus" id="biopankki_kuvaus-<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>-<?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?>" style="margin-top: 15px; display: <?php if(isset($haettu_luvan_kohdeDTO->Luvan_kohdeDTO->ID) && ( $haettu_luvan_kohdeDTO->Luvan_kohdeDTO->Luvan_kohteen_tyyppi=="Biopankki")){ echo "block;"; } else { echo "none;"; } ?>">

					<div class="poim_muuttujat_biopankit" style="display: <?php if($nayta_poim_muuttujat_biopankit){ echo "block;"; } else { echo "none;"; } ?>">
				
						<div class="kysymys">
							<?php echo TIED_KER_BIO; ?>
						</div>

						
						<div class="tieto">

							<?php global $BIOPANKIT; foreach ($BIOPANKIT as $bio_koodi => $bio_kaannos) { ?>

								<?php $bio_checked = false; ?>

								<?php if(isset($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO)){ ?>
									<?php for($m=0; $m < sizeof($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO); $m++){ 
										if(isset($haettu_luvan_kohdeDTO->Haetut_muuttujatDTO[$m]->Muuttujan_koodi) && $haettu_luvan_kohdeDTO->Haetut_muuttujatDTO[$m]->Muuttujan_koodi==$bio_koodi) $bio_checked = true; 									 
									} ?>
								<?php } ?>

								<label>
									<input value="<?php echo $bio_koodi; ?>" type="checkbox" <?php if($bio_checked) echo "checked"; ?> class="form_input haettu_muuttuja <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Muuttujan_koodi"  >
									<?php echo $bio_kaannos; ?>
								</label>

								<br>

							<?php } ?>

						</div>					
				
					</div>
				
					<div style="margin-top: 24px;">
						<?php echo TARK_KUV_NAYT; ?> <?php echo nayta_info(TARK_KUV_NAYT_INFO); ?>
					</div>

					<?php if((isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") || !isset($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi)){ ?>
						<textarea name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" class="form_input haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Kuvaus_naytteista tieto_laatikko3"><?php if(isset($haettu_luvan_kohdeDTO->Kuvaus_naytteista)) echo htmlentities($haettu_luvan_kohdeDTO->Kuvaus_naytteista,ENT_COMPAT, "UTF-8"); ?></textarea>
					<?php } else { ?>
						<div class="tieto_kentta">
							<?php if (isset($haettu_luvan_kohdeDTO->Kuvaus_naytteista)) echo htmlentities($haettu_luvan_kohdeDTO->Kuvaus_naytteista,ENT_COMPAT, "UTF-8"); ?>
						</div>
					<?php } ?>

				</div>
								
			</div>

			<br>

			<table style="width: 100%;">
				<tr>
					<td>
						<input style="display: <?php if($nayta_lisays_painike){ echo "block"; } else { echo "none;"; } ?>" name="<?php echo $osiotDTO_puu[$indeksi]->Osio_tyyppi; ?>" type="button" class="form_nappi haettu_luvan_kohde 0 Uusi_haettu_luvan_kohde nappi2" value="<?php echo LISAA_REKISTERI; ?> &raquo ">
						<input style="display: <?php if($nayta_poisto_painike){ echo "block"; } else { echo "none;"; } ?>" type="button" class="form_nappi haettu_luvan_kohde <?php if(isset($haettu_luvan_kohdeDTO->ID)){ echo $haettu_luvan_kohdeDTO->ID; } else { echo 0; } ?> Poistettava_haettu_luvan_kohde poista2" value="<?php echo POISTA_REKISTERI; ?> &raquo ">
					</td>
				</tr>
			</table>

		</div>

	<?php } ?>

<?php } ?>

<?php function nayta_info($infoteksti){ ?>
	<div class="tooltip"> <img src="static/images/info.png">
		<span class="tooltiptext"><?php echo $infoteksti; ?>&nbsp;&nbsp; </span>
	</div>
<?php } ?>

<?php function nayta_tutkimusryhma($parametrit){ ?>

	<?php 
	
	$hakemusversio = (isset($parametrit["hakemusversio"]) ? $parametrit["hakemusversio"] : null); 
	$hakija_kayttaja_id = (isset($parametrit["hakija_kayttaja_id"]) ? $parametrit["hakija_kayttaja_id"] : null); 
	$luo_hakija = (isset($parametrit["luo_hakija"]) ? $parametrit["luo_hakija"] : null); 
	$sivun_tunniste = (isset($parametrit["sivun_tunniste"]) ? $parametrit["sivun_tunniste"] : null); 
	$sitoumuksetDTO = (isset($parametrit["sitoumuksetDTO"]) ? $parametrit["sitoumuksetDTO"] : null); 
	$jarjestelman_hakijan_roolitDTO = (isset($parametrit["jarjestelman_hakijan_roolitDTO"]) ? $parametrit["jarjestelman_hakijan_roolitDTO"] : null);
	
	// Tarkistetaan onko käyttäjä tutkimuksen vast. johtaja
	$kayttaja_on_johtaja = false;

	if(isset($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat)){
		for($i=0; $i < sizeof($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat); $i++){
			if($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID==$_SESSION["kayttaja_id"]){
				if(isset($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO)){
					for($j=0; $j < sizeof($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO); $j++){
						if($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO[$j]->Hakijan_roolin_koodi=="rooli_vast"){
							$kayttaja_on_johtaja = true; 
							break 2;
						} 
					}
				}
			}
		}
	}

	if(!$kayttaja_on_johtaja && isset($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat)){
		for($i=0; $i < sizeof($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat); $i++){
			if($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID==$_SESSION["kayttaja_id"]){
				if(isset($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO)){
					for($j=0; $j < sizeof($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO); $j++){
						if($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Hakijan_roolitDTO[$j]->Hakijan_roolin_koodi=="rooli_vast"){
							$kayttaja_on_johtaja = true; 
							break 2;
						} 
					}
				}
			}
		}
	}

	?> 

	<?php if(!is_null($hakija_kayttaja_id) || !is_null($luo_hakija)){ ?>

		<?php // Haetaan hakijan tiedot

			$hakijan_muokkaus_sallittu = false;
			$hakijaDTO = null;
			$etunimi = "";
			$sukunimi = "";
			$sahkopostiosoite = "";
			$organisaatio = "";
			$oppiarvo = "";
			$osoite = "";
			$puhelin = "";
			$maa = "";
	 		$annettu = "0"; // käyttäjä antanut sitoumuksen

			if($hakija_kayttaja_id==$_SESSION["kayttaja_id"]){
				for($i=0; $i < sizeof($sitoumuksetDTO); $i++){
					if(isset($sitoumuksetDTO[$i]) && $sitoumuksetDTO[$i]->KayttajaDTO->ID==$_SESSION["kayttaja_id"] && !is_null($sitoumuksetDTO[$i]->Lisayspvm)){
						$annettu = $sitoumuksetDTO[$i]->Lisayspvm;
					}
				}
			}

			if(!is_null($hakija_kayttaja_id) && (is_null($luo_hakija) || $luo_hakija==0)){
				for($i=0; $i < sizeof($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat); $i++){
					if($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID==$hakija_kayttaja_id){
						$hakijaDTO = $hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i];
					}
				}

				if(is_null($hakijaDTO)){
					for($i=0; $i < sizeof($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat); $i++){
						if($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID==$hakija_kayttaja_id){
							$hakijaDTO = $hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i];
						}
					}
				}

				if($kayttaja_on_johtaja || ( isset($hakijaDTO->KayttajaDTO->ID) && $_SESSION["kayttaja_id"]==$hakijaDTO->KayttajaDTO->ID)) $hakijan_muokkaus_sallittu = true;

				$etunimi = $hakijaDTO->Etunimi;
				$sukunimi = $hakijaDTO->Sukunimi;
				$sahkopostiosoite = $hakijaDTO->Sahkopostiosoite;
				$organisaatio = $hakijaDTO->Organisaatio;
				$oppiarvo = $hakijaDTO->Oppiarvo;
				$puhelin = $hakijaDTO->Puhelin;
				$osoite = $hakijaDTO->Osoite;
				$maa = $hakijaDTO->Maa;

			}

			if($luo_hakija && $kayttaja_on_johtaja) $hakijan_muokkaus_sallittu = true;

		?>

		<?php if(!$hakijan_muokkaus_sallittu){ ?>
			<fieldset disabled="disabled">
		<?php } ?>

		<div class="oikea_sisalto_laatikko">

			<div class="paneeli_otsikko">
				<h2>1.
				<?php if (!is_null($hakijaDTO)) {
					echo INFO_TARK_TAYD_JA_TALL;
				} else {
					echo INFO_LISAA_JASENET;
				} ?>
				</h2>
			</div>

			<div class="paneelin_tiedot">
				<td style="vertical-align;top;">
				<table id="data">
				<tr>
					<td><label for="etunimi"><?php echo ETUNIMI;?>*&nbsp;&nbsp;</label></td>
					<td><input name="etunimi" id="etunimi" class="<?php if(isset($hakijaDTO->ID)){ ?>form_input<?php } ?> hakijan_tiedot <?php if(isset($hakijaDTO->ID)){ echo $hakijaDTO->ID; } else { echo 0; } ?> Etunimi tieto_laatikko" type="textarea" value="<?php echo htmlentities($etunimi, ENT_COMPAT, "UTF-8"); ?>" maxlength="50" autocomplete="off" size="40" autofocus></td>
				</tr>
				<tr>
					<td><label for="sukunimi"><?php echo SUKUNIMI;?>*&nbsp;&nbsp;</label></td>
					<td><input name="sukunimi" id="sukunimi" class="<?php if(isset($hakijaDTO->ID)){ ?>form_input<?php } ?> hakijan_tiedot <?php if(isset($hakijaDTO->ID)){ echo $hakijaDTO->ID; } else { echo 0; } ?> Sukunimi tieto_laatikko" type="textarea" value="<?php echo htmlentities($sukunimi, ENT_COMPAT, "UTF-8"); ?>" maxlength="50" autocomplete="off" size="40" autofocus></td>
				</tr>
				<tr>
					<td><label for="sahkoposti"><?php echo SAHKOPOSTIOSOITE;?>*&nbsp;&nbsp;</label></td>
					<td><input id="sahkoposti" name="sahkoposti" class="<?php if(isset($hakijaDTO->ID)){ ?>form_input<?php } ?> hakijan_tiedot <?php if(isset($hakijaDTO->ID)){ echo $hakijaDTO->ID; } else { echo 0; } ?> Sahkopostiosoite tieto_laatikko" type="textarea" value="<?php echo htmlentities($sahkopostiosoite, ENT_COMPAT, "UTF-8"); ?>" maxlength="50" autocomplete="off"></td>
				</tr>
				<tr>
					<td><label for="organisaatio"><?php echo ORGANISAATIO;?>*&nbsp;&nbsp;</label></td>
					<td><input id="organisaatio" name="organisaatio" class="<?php if(isset($hakijaDTO->ID)){ ?>form_input<?php } ?> hakijan_tiedot <?php if(isset($hakijaDTO->ID)){ echo $hakijaDTO->ID; } else { echo 0; } ?> Organisaatio tieto_laatikko" type="textarea" value="<?php echo htmlentities($organisaatio, ENT_COMPAT, "UTF-8"); ?>" maxlength="100" autocomplete="off"></td>
				</tr>
				<tr>
					<td><label for="oppiarvo"><?php echo OPPIARVO;?>*&nbsp;&nbsp;</label></td>
					<td><input id="oppiarvo" name="oppiarvo" class="<?php if(isset($hakijaDTO->ID)){ ?>form_input<?php } ?> hakijan_tiedot <?php if(isset($hakijaDTO->ID)){ echo $hakijaDTO->ID; } else { echo 0; } ?> Oppiarvo tieto_laatikko" type="textarea" value="<?php echo htmlentities($oppiarvo, ENT_COMPAT, "UTF-8"); ?>" maxlength="50" autocomplete="off"></td>
				</tr>
				<tr>
					<td><label for="osoite"><?php echo OSOITE;?></label></td>
					<td><input id="osoite" name="osoite" class="<?php if(isset($hakijaDTO->ID)){ ?>form_input<?php } ?> hakijan_tiedot <?php if(isset($hakijaDTO->ID)){ echo $hakijaDTO->ID; } else { echo 0; } ?> Osoite tieto_laatikko" type="textarea" value="<?php echo htmlentities($osoite, ENT_COMPAT, "UTF-8"); ?>" maxlength="50" autocomplete="off"></td>
				</tr>
				<tr>
					<td><label for="maa"><?php echo MAA;?></label></td>
					<td><input id="maa" name="maa" class="<?php if(isset($hakijaDTO->ID)){ ?>form_input<?php } ?> hakijan_tiedot <?php if(isset($hakijaDTO->ID)){ echo $hakijaDTO->ID; } else { echo 0; } ?> Maa tieto_laatikko" type="textarea" value="<?php echo htmlentities($maa, ENT_COMPAT, "UTF-8"); ?>" maxlength="50" autocomplete="off"></td>
				</tr>				
				<tr>
					<td><label for="puhelin"><?php echo PUHELIN;?></label></td>
					<td><input id="puhelin" name="puhelin" class="<?php if(isset($hakijaDTO->ID)){ ?>form_input<?php } ?> hakijan_tiedot <?php if(isset($hakijaDTO->ID)){ echo $hakijaDTO->ID; } else { echo 0; } ?> Puhelin tieto_laatikko" type="textarea" value="<?php echo htmlentities($puhelin, ENT_COMPAT, "UTF-8"); ?>" maxlength="50" autocomplete="off"></td>
				</tr>
				</table>
				</td>
			</div>

		</div>

		<div class="oikea_sisalto_laatikko">

			<div class="paneeli_otsikko">
				<h2>2. <?php echo VALITSE_ROOLI;?>*
					<div class="tooltip"> <img src="static/images/info.png">
						<span class="tooltiptext"><?php echo INFO_JOKAISELLA; ?></span>
					</div>
				</h2>
			</div>

			<div class="paneelin_tiedot">

				<?php for($i=0; $i < sizeof($jarjestelman_hakijan_roolitDTO); $i++){ 

					$valittu_hakijan_rooli = null;

					if(!is_null($hakijaDTO)){
						for($j=0; $j < sizeof($hakijaDTO->Hakijan_roolitDTO); $j++){
							if($hakijaDTO->Hakijan_roolitDTO[$j]->Hakijan_roolin_koodi==$jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_koodi) $valittu_hakijan_rooli = $hakijaDTO->Hakijan_roolitDTO[$j];
						}
					}
					?>

					<input <?php if($jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_koodi=="rooli_vast" && !$kayttaja_on_johtaja){ echo "disabled"; } ?> name="roolit[]" value="<?php echo $jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_koodi; ?>" type="checkbox" <?php if(!is_null($valittu_hakijan_rooli)) echo "checked"; ?> class="<?php if(isset($hakijaDTO->ID)){ ?>form_input<?php } ?> hakijan_rooli <?php if(isset($hakijaDTO->ID)){ echo $hakijaDTO->ID; } else { echo 0; } ?> Hakijan_roolin_koodi" id="<?php echo $jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_koodi; ?>" >
					<label for="<?php echo $jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_koodi; ?>"><?php echo koodin_selite($jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_koodi, $_SESSION['kayttaja_kieli']); ?> </label>
					<?php if(!is_null($jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_info)) nayta_info(koodin_selite($jarjestelman_hakijan_roolitDTO[$i]->Hakijan_roolin_info, $_SESSION['kayttaja_kieli'])); ?>
					<br><br>

				<?php } ?>

			</div>

		</div>

		<div style="display: <?php if($hakemusversio->LomakeDTO->ID!=1){ echo "none;"; } else { "block;"; } ?>" class="oikea_sisalto_laatikko">

			<div class="paneeli_otsikko">
				<h2>3. <?php echo SALASSAPITOSITOUMUS;?>
					<div class="tooltip"> <img src="static/images/info.png">
						<span class="tooltiptext"><?php echo INFO_SALASSAPITOSITOUMUS; ?></span>
					</div>
				</h2>
			</div>

			<div class="paneelin_tiedot">
				<label for="haetaanko_kayttolupaa"><p><input name="haetaanko_kayttolupaa" class="<?php if(isset($hakijaDTO->ID)){ ?>form_input<?php } ?> haetaanko_kayttolupaa <?php if(isset($hakijaDTO->ID)){ echo $hakijaDTO->ID; } else { echo 0; } ?> Haetaanko_kayttolupaa" type="checkbox" id="haetaanko_kayttolupaa" <?php if(isset($hakijaDTO->Haetaanko_kayttolupaa) && $hakijaDTO->Haetaanko_kayttolupaa==1) echo "checked"; ?> >
				<?php echo HAETAAN_LUPAA; ?></label></p>
			</div>

			<?php if(isset($hakijaDTO->KayttajaDTO->ID) && $hakijaDTO->KayttajaDTO->ID==$_SESSION["kayttaja_id"]){ ?>
				<div id="div_salassapitositoumus" style="display: <?php if(isset($hakijaDTO->Haetaanko_kayttolupaa) && $hakijaDTO->Haetaanko_kayttolupaa==1 && $hakijaDTO->KayttajaDTO->ID==$_SESSION["kayttaja_id"]){ echo "block;"; } else { echo "none;"; } ?>" >

					<div class="paneelin_tiedot">

						<p><b><?php echo SITOUDUN; ?></b></p>

						<ul>
							<li><?php echo SITOUTUMINEN1; ?></li>
							<li><?php echo SITOUTUMINEN2; ?></li>
							<li><?php echo SITOUTUMINEN3; ?></li>
							<li><?php echo SITOUTUMINEN4; ?></li>
						</ul>

						<div id="sitoutuminen_checked" <?php maaritaAsetusDynaamisesti("sitoutuminen_checked", array($annettu)); ?> >
							<input class="<?php if(isset($hakijaDTO->ID)){ ?>form_input<?php } ?> sitoumus <?php echo $_SESSION["kayttaja_id"]; ?>" type="checkbox" id="ehtojen_hyvaksyminen"> <label for="ehtojen_hyvaksyminen"><?php echo HYVAKSYN_EHDOT; ?></label>
						</div>

						<div id="sitoutuminen_hyvaksytty" style="display: <?php if ($annettu != "0") { echo "block"; } else { echo "none"; } ?>;">
							<div class="paneeli_otsikko">
								<b><?php echo SITOUMUS_HYVAKSYTTY; ?></b>
							</div>
							<div id="dynaaminen_sitoutumispvm"></div>
							<p style="display: <?php if ($annettu != "0") { echo "block"; } else { echo "none"; } ?>;"><?php echo muotoilepvm($annettu, 'fi'); ?></p>
						</div>

					</div>

				</div>
			<?php } ?>
		</div>

		<?php if($luo_hakija){ ?>
			<input type="hidden" name="tutkimus_id" value="<?php echo $hakemusversio->TutkimusDTO->ID; ?>">
			<input type="hidden" name="hakemusversio_id" value="<?php echo $hakemusversio->ID; ?>">
			<input type="hidden" name="sivu" value="hakemus_tutkimusryhma">
			<input onclick="return confirm('<?php echo LAH_KUTS_VARM; ?>');" name="laheta_sahkopostikutsu" type="submit" class="nappi2" value="<?php echo LAHETA_KUTSU;?> &raquo;">
		<?php } ?>

		<?php if($kayttaja_on_johtaja && isset($hakijaDTO->KayttajaDTO->ID) && $_SESSION["kayttaja_id"]!=$hakijaDTO->KayttajaDTO->ID){ ?>
			<input type="hidden" name="hakija_kayttaja_id" value="<?php echo $hakija_kayttaja_id; ?>">
			<input type="hidden" name="tutkimus_id" value="<?php echo $hakemusversio->TutkimusDTO->ID; ?>">
			<input type="hidden" name="hakemusversio_id" value="<?php echo $hakemusversio->ID; ?>">
			<input type="hidden" name="sivu" value="hakemus_tutkimusryhma">
			<input onclick="return confirm('<?php echo POIST_HAKIJ_VARM; ?>');" name="poista_hakija" type="submit" class="poista2" value="<?php echo POISTA_HENKILO;?> &raquo;">
		<?php } ?>
		
		<?php if($kayttaja_on_johtaja && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken"){ ?>			
			<button class="nappi" style="cursor: pointer;" formaction="hakemus.php?sivu=hakemus_tutkimusryhma&tutkimus_id=<?php echo $hakemusversio->TutkimusDTO->ID; ?>&hakemusversio_id=<?php echo $hakemusversio->ID; ?>&luo_hakija=1"><?php echo UUSI_HENKILO;?> &raquo;</button>				
		<?php } ?>		

		<?php if(!$hakijan_muokkaus_sallittu){ ?>
			</fieldset>
		<?php } ?>

	<?php } else { ?>

		<?php if(!empty($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat)){ ?>
			<div class="oikea_sisalto_laatikko" style="min-height: 320px;">

				<div class="paneeli_otsikko">
					<h2 style="max-width: 85%;">					
						<?php echo TUTKIMUSRYHMA_1OTSIKKO; ?><?php nayta_info(TUTKIMUSRYHMA_1OTSIKKO_INFO); ?>						
					</h2>
				</div>
				
				<table class="taulu_tutkimusryhma">
				
					<thead>
						<tr>
							<th align="left"><?php echo NIMI; ?>
								<?php if ($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken"){ ?>
									<div class="tooltip"> <img src="static/images/info.png">
										<span class="tooltiptext"><?php echo INFO_NIMI; ?></span>
									</div>
								<?php } ?>
							</th>
							<th align="left"><?php echo SAHKOPOSTIOSOITE; ?></th>
							<th align="left"><?php echo OPPIARVO; ?></th>
							<th align="left"><?php echo ORGANISAATIO; ?></th>
							<?php if ($_SESSION["kayttaja_rooli"] == "rooli_hakija") { ?>
								<th align="left"><?php echo JASEN; ?>
									<div class="tooltip"> <img src="static/images/info.png">
										<span class="tooltiptext"><?php echo INFO_JASEN; ?></span>
									</div>
								</th>
							<?php } ?>
						</tr>
					</thead>

					<?php

					$edellinen_fk = '';

					for ($i=0; $i < sizeof($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat); $i++) {
						
						$fk_kayttaja = $hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID;
						$fk = $fk_kayttaja;

						if ($fk == $edellinen_fk) {
							$fk = '';
						} else {
							$edellinen_fk = $fk;
						}

						if ((strlen($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Etunimi) == 0) || (strlen($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Sukunimi) == 0)){
							$nimi = '<span style="color:red">' . PUUTTUU . '</span>';
						} else {
							$nimi = htmlentities($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Etunimi, ENT_COMPAT, "UTF-8") . " " . htmlentities($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Sukunimi, ENT_COMPAT, "UTF-8");
						}
						if ($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") $nimi = '<a href="hakemus.php?tutkimus_id=' . $hakemusversio->TutkimusDTO->ID . '&hakemusversio_id=' . $hakemusversio->ID . '&sivu=hakemus_tutkimusryhma&hakija_kayttaja_id=' . $fk_kayttaja . '" title="Tarkista ja täydennä tiedot.">' . $nimi . '</a>';

						if (!isset($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Jasen) || $hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Jasen == "0000-00-00 00:00:00" || !isset($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->SitoumusDTO->ID) || !isset($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Etunimi) || !isset($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Oppiarvo) || $hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Oppiarvo=="" || !isset($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Organisaatio) || $hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Organisaatio=="" || !isset($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Sukunimi) || (strlen($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Etunimi) == 0) || (strlen($hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Sukunimi) == 0)){
							$jasen = '<span style="color:red">&#x2718;</span>';
						}
						else {
							$jasen = '<span style="color:green">&#x2714;</span>';
						}
						
						$oppiarvo = $hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Oppiarvo ."";
						$organisaatio = $hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Organisaatio ."";
						$sahkopostiosoite = $hakemusversio->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Sahkopostiosoite ."";
					
					?>						
						<tbody>
							<tr <?php maaritaAsetusDynaamisesti("tutkimusryhma_taulu_yla", array($fk)); ?> >
								<td>
									<?php if ($fk !== '') {echo $nimi;} ?>
									<?php //if($_SESSION['hakemusversio']['Versio'] > 1){ ?>
										<?php //if (isset($aiempien_versioiden_kayttajatiedot["Sitoutuneet_kayttajat_aiemmista_hakemusversioista"]) && !in_array($kasittelyoikeutta_hakevat_hakijat[$i]["FK_Kayttaja"], $aiempien_versioiden_kayttajatiedot["Sitoutuneet_kayttajat_aiemmista_hakemusversioista"])) {
											//if ($fk !== ""){
												//echo "( <b style='color: green;'> " . UUSI_HAKIJA . " </b> )";
											//}
										//} ?>
									<?php //} ?>
								</td>
								<td><?php if ($fk !== '') if($sahkopostiosoite == "") { ?> <span style="color:red"><?php echo PUUTTUU; ?></span> <?php } else { echo htmlentities($sahkopostiosoite, ENT_COMPAT, "UTF-8"); } ?></td>
								<td><?php if ($fk !== '') if($oppiarvo == "") { ?> <span style="color:red"><?php echo PUUTTUU; ?></span> <?php } else { echo htmlentities($oppiarvo, ENT_COMPAT, "UTF-8"); } ?></td>
								<td><?php if ($fk !== '') if($organisaatio==""){ ?> <span style="color:red"> <?php echo PUUTTUU; ?> <span> <?php } else { echo htmlentities($organisaatio,ENT_COMPAT, "UTF-8"); } ?></td>
								<?php if ($_SESSION["kayttaja_rooli"] == "rooli_hakija") { ?><td><?php if ($fk !== '') echo $jasen; ?></td><?php } ?>
							</tr>
						</tbody>
						
					<?php } ?>
				
				</table>
				
			</div>

		<?php } ?>

		<?php if(!empty($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat)){ ?>
		
			<div class="oikea_sisalto_laatikko" style="min-height: 320px;">

				<div class="paneeli_otsikko">
					<h2>
						<?php if($hakemusversio->LomakeDTO->ID==1){ // Käyttölupahakemus ?>
							<?php echo TUTKIMUSRYHMA_2OTSIKKO; ?>
						<?php } else { // Muu lomake ?>
							<?php echo TUTKRYHMA_JASENET; ?>
						<?php } ?>
					</h2>
				</div>
				
				<table class="taulu_tutkimusryhma">
				
					<thead>
						<tr>
							<th align="left"><?php echo NIMI; ?>
								<?php if ($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken"){ ?>
									<div class="tooltip"> <img src="static/images/info.png">
										<span class="tooltiptext"><?php echo INFO_NIMI; ?></span>
									</div>
								<?php } ?>
							</th>
							<th align="left"><?php echo SAHKOPOSTIOSOITE; ?></th>
							<th align="left"><?php echo OPPIARVO; ?></th>
							<th align="left"><?php echo ORGANISAATIO; ?></th>
							<?php if ($_SESSION["kayttaja_rooli"] == "rooli_hakija") { ?>
								<th align="left"><?php echo JASEN; ?>
									<div class="tooltip"> <img src="static/images/info.png">
										<span class="tooltiptext"><?php echo INFO_JASEN; ?></span>
									</div>
								</th>
							<?php } ?>
						</tr>
					</thead>

					<?php

					$edellinen_fk = '';

					for ($i=0; $i < sizeof($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat); $i++) {
						
						$fk_kayttaja = $hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID;
						$fk = $fk_kayttaja;

						if ($fk == $edellinen_fk) {
							$fk = '';
						} else {
							$edellinen_fk = $fk;
						}

						if ((strlen($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Etunimi) == 0) || (strlen($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Sukunimi) == 0)){
							$nimi = '<span style="color:red">' . PUUTTUU . '</span>';
						} else {
							$nimi = htmlentities($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Etunimi, ENT_COMPAT, "UTF-8") . " " . htmlentities($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Sukunimi, ENT_COMPAT, "UTF-8");
						}
						
						if ($hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") $nimi = '<a href="hakemus.php?tutkimus_id=' . $hakemusversio->TutkimusDTO->ID . '&hakemusversio_id=' . $hakemusversio->ID . '&sivu=' . $sivun_tunniste . '&hakija_kayttaja_id=' . $fk_kayttaja . '" title="Tarkista ja täydennä tiedot.">' . $nimi . '</a>';

						if (!isset($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Jasen) || $hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Jasen == "0000-00-00 00:00:00" || !isset($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Etunimi) || !isset($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Oppiarvo) || $hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Oppiarvo=="" || !isset($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Organisaatio) || $hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Organisaatio=="" || !isset($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Sukunimi) || (strlen($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Etunimi) == 0) || (strlen($hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Sukunimi) == 0)){
							$jasen = '<span style="color:red">&#x2718;</span>';
						} else {
							$jasen = '<span style="color:green">&#x2714;</span>';
						}
						
						$oppiarvo = $hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Oppiarvo ."";
						$organisaatio = $hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Organisaatio ."";
						$sahkopostiosoite = $hakemusversio->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Sahkopostiosoite ."";
						
						?>
						<tbody>
							<tr <?php maaritaAsetusDynaamisesti("tutkimusryhma_taulu_yla", array($fk)); ?> >
								<td>
									<?php if ($fk !== '') {echo $nimi;} ?>
								</td>
								<td><?php if ($fk !== '') if($sahkopostiosoite == "") { ?> <span style="color:red"><?php echo PUUTTUU; ?></span> <?php } else { echo htmlentities($sahkopostiosoite, ENT_COMPAT, "UTF-8"); } ?></td>
								<td><?php if ($fk !== '') if($oppiarvo == "") { ?> <span style="color:red"><?php echo PUUTTUU; ?></span> <?php } else { echo htmlentities($oppiarvo, ENT_COMPAT, "UTF-8"); } ?></td>
								<td><?php if ($fk !== '') if($organisaatio==""){ ?> <span style="color:red"> <?php echo PUUTTUU; ?> <span> <?php } else { echo htmlentities($organisaatio,ENT_COMPAT, "UTF-8"); } ?></td>
								<?php if ($_SESSION["kayttaja_rooli"] == "rooli_hakija") { ?><td><?php if ($fk !== '') echo $jasen; ?></td><?php } ?>
							</tr>
						</tbody>
					<?php } ?>
				</table>
			</div>

		<?php } ?>

		<?php if($kayttaja_on_johtaja && $hakemusversio->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken"){ ?>
			<button class="nappi" style="cursor: pointer;" formaction="hakemus.php?sivu=hakemus_tutkimusryhma&tutkimus_id=<?php echo $hakemusversio->TutkimusDTO->ID; ?>&hakemusversio_id=<?php echo $hakemusversio->ID; ?>&luo_hakija=1"><?php echo UUSI_HENKILO;?> &raquo;</button>			
		<?php } ?>

	<?php } ?>

<?php } ?>