<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Lomakkkeen kysymysten väliset suhteet view
 *
 * Created: 24.4.2017
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>
<p class="murupolku"><?php echo ETUSIVU; ?> > Hallinta > Lomakkeet > Uusi lomake > Liitetiedostot-sivu</p>

<?php include './ui/template/vasen_menu_lomake.php'; ?>

<div class="oikea_sisalto">

	<form enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
		<div style="display: none;" class="oikea_sisalto_laatikko uusi_riippuvuussaanto">
			<div class="paneeli_otsikko">
				<h3>Lisää uusi kysymysten välinen suhde</h3>
			</div>
			<div class="paneelin_tiedot">
				<div class="tieto_blue">
					<p>
						Kysymyksen vastaus, johon riippuvuus sidotaan<br />
						<select name="Uusi_Osio_lause[FK_Osio_muuttuja]">
							<option value=""></option>
								<?php foreach($lomakeDTO->Lomakkeen_sivutDTO as $tunniste => $lomake_sivuDTO) {
									foreach($lomake_sivuDTO->OsiotDTO_taulu as $fk_osio => $osioDTO) {
										if(!is_null($osioDTO->OsioDTO_parent->ID) && ($osioDTO->Osio_tyyppi=="checkbox" || $osioDTO->Osio_tyyppi=="radio" || $osioDTO->Osio_tyyppi=="textarea" || $osioDTO->Osio_tyyppi=="textarea_large")){ 
											$kysymystyyppi = null;
											if($osioDTO->Osio_tyyppi=="checkbox") $kysymystyyppi = "Valintaruutu:";
											if($osioDTO->Osio_tyyppi=="radio") $kysymystyyppi = "Valintapainike:";
											if($osioDTO->Osio_tyyppi=="textarea_large" || $osioDTO->Osio_tyyppi=="textarea") $kysymystyyppi = "Tekstivastaus";
											?> <option value="<?php echo $osioDTO->ID; ?>">Sivu: <?php echo kaanna_osion_kentta($lomake_sivuDTO, "Nimi", $_SESSION['kayttaja_kieli']); ?>, Kysymys: <?php echo $osioDTO->OsioDTO_parent->Otsikko_fi . " (ID: " . $osioDTO->OsioDTO_parent->ID ." )"; ?>, <?php echo $kysymystyyppi; ?> <?php echo $osioDTO->Otsikko_fi; ?></option>
										<?php }
									}
								} ?>
						</select>
					</p>
					<p>
					Vastauksen ehto<br />
						<select name="Uusi_Osio_lause[Predikaatti]">
							<option value=""></option>
							<option value="Valittu">Vastaus on valittu</option>
							<option value="Ei_valittu">Vastaus on tyhjä</option>
						</select>
					</p>
					<p>
						Kysymysten välisen suhteen toiminto<br />
						<select name="Uusi_Osio_saanto[Saanto]">
							<option value=""></option>
							<option value="nayta">Näytä</option>
							<option value="piilota">Piilota</option>
							<option value="tyhjenna">Tyhjennä</option>
						</select>
					</p>
					<p>
						Kohde, johon luotu suhde vaikuttaa<br />
						<select name="Uusi_Osio_saanto[FK_Osio_muuttuja]">
							<option value=""></option>
								<?php foreach($lomakeDTO->Lomakkeen_sivutDTO as $tunniste => $lomake_sivuDTO) {
									foreach($lomake_sivuDTO->OsiotDTO_taulu as $fk_osio => $osioDTO) {
										if(!is_null($osioDTO->OsioDTO_parent->ID) && ($osioDTO->Osio_tyyppi=="kysymys" || $osioDTO->Osio_tyyppi=="checkbox" || $osioDTO->Osio_tyyppi=="radio" || $osioDTO->Osio_tyyppi=="textarea" || $osioDTO->Osio_tyyppi=="textarea_large")){ 
											$kysymystyyppi = null;
											if($osioDTO->Osio_tyyppi=="checkbox") $kysymystyyppi = "Valintaruutu:";
											if($osioDTO->Osio_tyyppi=="radio") $kysymystyyppi = "Valintapainike:";
											if($osioDTO->Osio_tyyppi=="textarea_large" || $osioDTO->Osio_tyyppi=="textarea") $kysymystyyppi = "Tekstivastaus";
											if($osioDTO->Osio_tyyppi=="kysymys"){ ?> 
												<option value="<?php echo $osioDTO->ID; ?>">Sivu: <?php echo kaanna_osion_kentta($lomake_sivuDTO, "Nimi", $_SESSION['kayttaja_kieli']); ?>, Kysymys: <?php echo $osioDTO->Otsikko_fi . " (ID: " . $osioDTO->ID . ")"; ?></option>
											<?php } else { ?>
												<option value="<?php echo $osioDTO->ID; ?>">Sivu: <?php echo kaanna_osion_kentta($lomake_sivuDTO, "Nimi", $_SESSION['kayttaja_kieli']); ?>, Kysymys: <?php echo $osioDTO->OsioDTO_parent->Otsikko_fi . " (ID: " . $osioDTO->OsioDTO_parent->ID . ")"; ?>, <?php echo $kysymystyyppi; ?> <?php echo $osioDTO->Otsikko_fi; ?></option>
											<?php } ?>
										<?php }
									}
								} ?>
						</select>
					</p>
				</div>
			</div>
		</div>
		<div class="oikea_sisalto_laatikko lomake_riippuvuussaanto">
			<div class="paneeli_otsikko">
				<h3>Kysymysten väliset suhteet</h3>
			</div>
			<div class="paneelin_tiedot">
				<p></p>
				<div id="lomake_liite-lisaa">
					<input type="button" id="lisaa_uusi_riippuvuussaanto" class="nappi" value="Lisää uusi kysymysten välinen suhde">
				</div>
			</div>
		</div>
		<?php 
		$r_nro = 1;
		foreach($lomakeDTO->Lomakkeen_sivutDTO as $tunniste => $lomake_sivuDTO) {
			foreach($lomake_sivuDTO->OsiotDTO_taulu as $fk_osio => $osioDTO) { 
				if(empty($osioDTO->Osio_saannotDTO)){ 
					continue; 
				} else { ?>
					<?php for($s=0; $s < sizeof($osioDTO->Osio_saannotDTO); $s++){ ?>
						<div class="oikea_sisalto_laatikko lomake_riippuvuussaanto">
							<div class="paneeli_otsikko">
								<h3>Riippuvuussääntö #<?php echo $r_nro; ?></h3>
							</div>
							<div class="paneelin_tiedot">
								<div class="tieto_blue">
									<p>
										Kysymyksen vastaus, johon riippuvuus sidotaan<br />
										<select name="Osio_lause[<?php echo $osioDTO->Osio_saannotDTO[$s]->Osio_lauseetDTO[0][0]->ID; ?>][FK_Osio_muuttuja]">
											<option value=""></option>
												<?php foreach($lomakeDTO->Lomakkeen_sivutDTO as $tunniste_b => $lomake_sivuDTO_b) {
													foreach($lomake_sivuDTO_b->OsiotDTO_taulu as $fk_osio_b => $osioDTO_b) {
														if(!is_null($osioDTO_b->OsioDTO_parent->ID) && ($osioDTO_b->Osio_tyyppi=="checkbox" || $osioDTO_b->Osio_tyyppi=="radio" || $osioDTO_b->Osio_tyyppi=="textarea" || $osioDTO_b->Osio_tyyppi=="textarea_large")){ 
															$kysymystyyppi = null;
															if($osioDTO_b->Osio_tyyppi=="checkbox") $kysymystyyppi = "Valintaruutu:";
															if($osioDTO_b->Osio_tyyppi=="radio") $kysymystyyppi = "Valintapainike:";
															if($osioDTO_b->Osio_tyyppi=="textarea_large" || $osioDTO_b->Osio_tyyppi=="textarea") $kysymystyyppi = "Tekstivastaus";
															?> <option <?php if(isset($osioDTO->Osio_saannotDTO[$s]->Osio_lauseetDTO[0][0]->OsioDTO_Muuttuja->ID) && $osioDTO->Osio_saannotDTO[$s]->Osio_lauseetDTO[0][0]->OsioDTO_Muuttuja->ID==$osioDTO_b->ID) echo "selected"; ?> value="<?php echo $osioDTO_b->ID; ?>">Sivu: <?php echo kaanna_osion_kentta($lomake_sivuDTO_b, "Nimi", $_SESSION['kayttaja_kieli']); ?>, Kysymys: <?php echo $osioDTO_b->OsioDTO_parent->Otsikko_fi . " (ID: " . $osioDTO_b->OsioDTO_parent->ID . ")"; ?>, <?php echo $kysymystyyppi; ?> <?php echo $osioDTO_b->Otsikko_fi; ?></option>
														<?php }
													}
												} ?>
										</select>
									</p>
									<p>
									Vastauksen ehto<br />
										<select name="Osio_lause[<?php echo $osioDTO->Osio_saannotDTO[$s]->Osio_lauseetDTO[0][0]->ID; ?>][Predikaatti]">
											<option value=""></option>
											<option <?php if(isset($osioDTO->Osio_saannotDTO[$s]->Osio_lauseetDTO[0][0]->Predikaatti) && $osioDTO->Osio_saannotDTO[$s]->Osio_lauseetDTO[0][0]->Predikaatti=="Valittu") echo "selected"; ?> value="Valittu">Vastaus on valittu</option>
											<option <?php if(isset($osioDTO->Osio_saannotDTO[$s]->Osio_lauseetDTO[0][0]->Predikaatti) && $osioDTO->Osio_saannotDTO[$s]->Osio_lauseetDTO[0][0]->Predikaatti=="Ei_valittu") echo "selected"; ?> value="Ei_valittu">Vastaus on tyhjä</option>
										</select>
									</p>
									<p>
										Kysymysten välisen suhteen toiminto<br />
										<select name="Osio_saanto[<?php echo $osioDTO->Osio_saannotDTO[$s]->ID; ?>][Saanto]">
											<option value=""></option>
											<option <?php if(isset($osioDTO->Osio_saannotDTO[$s]->Saanto) && $osioDTO->Osio_saannotDTO[$s]->Saanto=="nayta") echo "selected"; ?> value="nayta">Näytä</option>
											<option <?php if(isset($osioDTO->Osio_saannotDTO[$s]->Saanto) && $osioDTO->Osio_saannotDTO[$s]->Saanto=="piilota") echo "selected"; ?> value="piilota">Piilota</option>
											<option <?php if(isset($osioDTO->Osio_saannotDTO[$s]->Saanto) && $osioDTO->Osio_saannotDTO[$s]->Saanto=="tyhjenna") echo "selected"; ?> value="tyhjenna">Tyhjennä</option>
										</select>
									</p>
									<p>
										Kohde, johon luotu suhde vaikuttaa<br />
										<select name="Osio_saanto[<?php echo $osioDTO->Osio_saannotDTO[$s]->ID; ?>][FK_Osio_muuttuja]">
											<option value=""></option>
												<?php foreach($lomakeDTO->Lomakkeen_sivutDTO as $tunniste_b => $lomake_sivuDTO_b) {
													foreach($lomake_sivuDTO_b->OsiotDTO_taulu as $fk_osio_b => $osioDTO_b) {
														if(!is_null($osioDTO_b->OsioDTO_parent->ID) && ($osioDTO_b->Osio_tyyppi=="kysymys" || $osioDTO_b->Osio_tyyppi=="checkbox" || $osioDTO_b->Osio_tyyppi=="radio" || $osioDTO_b->Osio_tyyppi=="textarea" || $osioDTO_b->Osio_tyyppi=="textarea_large")){ 
															$kysymystyyppi = null;
															if($osioDTO_b->Osio_tyyppi=="checkbox") $kysymystyyppi = "Valintaruutu:";
															if($osioDTO_b->Osio_tyyppi=="radio") $kysymystyyppi = "Valintapainike:";
															if($osioDTO_b->Osio_tyyppi=="textarea_large" || $osioDTO_b->Osio_tyyppi=="textarea") $kysymystyyppi = "Tekstivastaus";
															if($osioDTO_b->Osio_tyyppi=="kysymys"){ ?> 
																<option <?php if(isset($osioDTO->Osio_saannotDTO[$s]->OsioDTO_Muuttuja->ID) && $osioDTO->Osio_saannotDTO[$s]->OsioDTO_Muuttuja->ID==$osioDTO_b->ID) echo "selected"; ?> value="<?php echo $osioDTO_b->ID; ?>">Sivu: <?php echo kaanna_osion_kentta($lomake_sivuDTO_b, "Nimi", $_SESSION['kayttaja_kieli']); ?>, Kysymys: <?php echo $osioDTO_b->Otsikko_fi . " (ID: " . $osioDTO_b->ID . ")"; ?></option>
															<?php } else { ?>
																<option <?php if(isset($osioDTO->Osio_saannotDTO[$s]->OsioDTO_Muuttuja->ID) && $osioDTO->Osio_saannotDTO[$s]->OsioDTO_Muuttuja->ID==$osioDTO_b->ID) echo "selected"; ?> value="<?php echo $osioDTO_b->ID; ?>">Sivu: <?php echo kaanna_osion_kentta($lomake_sivuDTO_b, "Nimi", $_SESSION['kayttaja_kieli']); ?>, Kysymys: <?php echo $osioDTO_b->OsioDTO_parent->Otsikko_fi . " (ID: " . $osioDTO_b->OsioDTO_parent->ID . ")"; ?>, <?php echo $kysymystyyppi; ?> <?php echo $osioDTO_b->Otsikko_fi; ?></option>
															<?php } ?>
														<?php }
													}
												} ?>
										</select>
									</p>
								</div>
							</div>
							<div style="width: 635px; margin-bottom: 50px;">
								<p><input type="submit" name="poista_osio_saanto[Osio_saanto][<?php echo $osioDTO->Osio_saannotDTO[$s]->ID; ?>]" class="poista" value="Poista kysymysten välinen suhde"></p>
							</div>
						</div>
					<?php } ?>
					<?php $r_nro++; ?>
				<?php } ?>
			<?php } 
		} ?>
		<input name="lomake_id" type="hidden" value="<?php echo $lomake_id; ?>" />
		<input style="display: none;" class="nappi uusi_riippuvuussaanto" id="peruuta_saannon_lisays" type="button" value="<?php echo PERUUTA; ?>"/>
		<input style="display: none;" name="uusi_riippuvuussaanto" type="submit" class="nappi2 uusi_riippuvuussaanto" value="Tallenna uusi riippuvuussääntö" />
		<input id="lom_tall" name="tallenna_lomake" type="submit" class="nappi2 lomake_riippuvuussaanto" value="Tallenna lomake" />
	</form>
</div>
<?php
	include './ui/template/footer.php';
?>