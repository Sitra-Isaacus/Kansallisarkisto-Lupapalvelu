<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Lomakkkeen perustiedot view
 *
 * Created: 24.4.2017
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>
<p class="murupolku"><?php echo ETUSIVU; ?> > <?php echo LOMAKKEET; ?> > <?php tulosta_teksti($lomakeDTO->Nimi); ?> > <?php echo PERUSTIEDOT; ?></p>

<?php include './ui/template/vasen_menu_lomake.php'; ?>

<form class="form_lomake_perustiedot" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

	<div class="oikea_sisalto">
	
		<?php if($_SESSION["kayttaja_rooli"] == "rooli_viranomaisen_paak" && $lomakeDTO->Lomakkeen_tyyppi=="Hakemus"){ ?>
			<fieldset disabled>
		<?php } ?>
		
		<div class="oikea_sisalto_laatikko uusi_sivu_lisays" id="lisaa_uusi_sivu_box" style="display: none;">
			<div class="paneeli_otsikko">
				<h3><?php echo LOM_UUSI_SIVU; ?></h3>
			</div>
			<br>
			<div class="paneelin_tiedot">
				
				<br><br>
				<table class="lomake_sivu_jarj">
					<tr>
						<th><?php echo JARJESTYS; ?> *</th>
						<th><?php echo SIVUPOHJA; ?> *</th>
						<th>Nimi * <img src="static/images/fi.png" class="lippu" /></th>
						<th>Name * <img src="static/images/uk.png" class="lippu" /></th>
					</tr>
					<tr>
						<td><input type="number" name="uusi_sivu[Jarjestys]"></td>
						<td>
							<select name="uusi_sivu[Sivun_tunniste]">
								<option value="uusi_sivu"><?php echo UUSI_SIVU; ?></option>
								<?php if($lomakeDTO->Lomakkeen_tyyppi=="Hakemus" && isset($lomakeDTO->Lomake_hakemusDTO)){ ?><option value="hakemus_liitteet"><?php echo LIITTEET; ?></option><?php } ?>
								<?php if($lomakeDTO->Lomakkeen_tyyppi=="Hakemus" && isset($lomakeDTO->Lomake_hakemusDTO)){ ?><option value="hakemus_viranomaiskohtaiset"><?php echo VIRANOMAISKOHTAISET; ?></option><?php } ?>
								</select>
							</td>
						<td><input type="text" name="uusi_sivu[Sivun_nimi_fi]"></td>
						<td><input type="text" name="uusi_sivu[Sivun_nimi_en]"></td>
					</tr>
				</table>
			</div>
			<br>
		</div>
		
		<div id="lomakkeen_tiedot_box" class="oikea_sisalto_laatikko lom_perustiedot">
			<div class="paneeli_otsikko">
				<h3>Lomakkeen perustiedot</h3>
			</div>
			<div class="paneelin_tiedot">
				<p>
					<label for="lomakkeen_nimi">Lomakkeen nimi *</label><br />
					<input type="text" name="lomakkeen_nimi" value="<?php tulosta_teksti($lomakeDTO->Nimi); ?>"><br />
				</p>
				<p>
					<label for="lomakkeen_tyyppi">Lomakkeen tyyppi *</label><br />
					<select id="lomakkeen_tyyppi" name="lomakkeen_tyyppi">
						<option value=""></option>
						<option <?php if($_SESSION["kayttaja_rooli"] == "rooli_viranomaisen_paak") echo "disabled"; ?> value="Hakemus" <?php if($lomakeDTO->Lomakkeen_tyyppi=="Hakemus"){ echo "selected"; } ?> >Hakemus</option>
						<option <?php if($lomakeDTO->Lomakkeen_tyyppi=="Päätös"){ echo "selected"; } ?> value="Päätös">Päätös</option>
						<option <?php if($lomakeDTO->Lomakkeen_tyyppi=="Lausunto"){ echo "selected"; } ?> value="Lausunto"><?php echo LAUSUNTO; ?></option>
					</select>
				</p>

			</div>
		</div>
		<div id="hakemuslom_lisatiedot" class="oikea_sisalto_laatikko lom_perustiedot" style="display: <?php if($lomakeDTO->Lomakkeen_tyyppi=="Hakemus" && isset($lomakeDTO->Lomake_hakemusDTO)){ echo "block"; } else { echo "none"; } ?>">
			<div class="paneeli_otsikko">
				<h3>Hakemuslomakkeen lisätiedot</h3>
			</div>
			<div class="paneelin_tiedot">
				<p>
					Painikkeen teksti (uusi hakemus)*<br>
					<img style="margin-top: 15px;" src="static/images/fi.png" class="lippu" /> Suomeksi *  <br> <input type="text" name="uusi_hakemus_teksti_fi" value="<?php if(isset($lomakeDTO->Lomake_hakemusDTO)) tulosta_teksti($lomakeDTO->Lomake_hakemusDTO->Uusi_hakemus_painike_teksti_fi); ?>"> <br><br>
					<img src="static/images/uk.png" class="lippu" /> Englanniksi * <br> <input type="text" name="uusi_hakemus_teksti_en" value="<?php if(isset($lomakeDTO->Lomake_hakemusDTO)) tulosta_teksti($lomakeDTO->Lomake_hakemusDTO->Uusi_hakemus_painike_teksti_en); ?>"><br>
				</p>
			</div>
		</div>
		<div id="paatoslom_lisatiedot" class="oikea_sisalto_laatikko lom_perustiedot" style="display: <?php if($lomakeDTO->Lomakkeen_tyyppi=="Päätös" && isset($lomakeDTO->Lomake_paatosDTO)){ echo "block"; } else { echo "none"; } ?>">
			<div class="paneeli_otsikko">
				<h3>Päätöslomakkeen lisätiedot</h3>
			</div>
			<div class="paneelin_tiedot">
				<p>
					Päätöksen tyyppi<br>
					<select id="paatoksen_tyyppi" name="Paatostyyppi">
						<option>Valitse päätöksen tyyppi</option>
						<option <?php if(isset($lomakeDTO->Lomake_paatosDTO->Paatostyyppi) && $lomakeDTO->Lomake_paatosDTO->Paatostyyppi=="paatos") echo "selected"; ?> value="paatos" >Päätös</option>
						<option <?php if(isset($lomakeDTO->Lomake_paatosDTO->Paatostyyppi) && $lomakeDTO->Lomake_paatosDTO->Paatostyyppi=="muutospaatos") echo "selected"; ?> value="muutospaatos" >Päätös muutoshakemukseen</option>
					</select>
				</p>
			</div>
		</div>
		<div id="lomakkeen_sivut_box" class="oikea_sisalto_laatikko lom_perustiedot" style="display: <?php if($lomakeDTO->Lomakkeen_tyyppi=="Hakemus" && isset($lomakeDTO->Lomake_hakemusDTO)){ echo "block"; } else { echo "none"; } ?>">
			<div class="paneeli_otsikko">
				<h3>Lomakkeen sivut</h3>
			</div>
			<div class="paneelin_tiedot">
				<p>
					<?php if(!empty($lomakeDTO->Lomakkeen_sivutDTO)){ ?>
						<table class="lomake_sivu_jarj">
						<tr>
							<th>Järjestys</th>
							<th>Nimi <img src="static/images/fi.png" class="lippu" /></th>
							<th>Name <img src="static/images/uk.png" class="lippu" /></th>
							<th></th>
						</tr>
							<?php foreach($lomakeDTO->Lomakkeen_sivutDTO as $tunniste => $lomake_sivutDTO) {  ?>
								<tr>
									<td><input value="<?php tulosta_teksti($lomakeDTO->Lomakkeen_sivutDTO[$tunniste]->Jarjestys); ?>" type="number" name="sivu[<?php echo $lomake_sivutDTO->ID; ?>][Jarjestys]"></td>
									<td><input value="<?php tulosta_teksti($lomakeDTO->Lomakkeen_sivutDTO[$tunniste]->Nimi_fi); ?>" type="text" name="sivu[<?php echo $lomake_sivutDTO->ID; ?>][Sivun_nimi_fi]"></td>
									<td><input value="<?php tulosta_teksti($lomakeDTO->Lomakkeen_sivutDTO[$tunniste]->Nimi_en); ?>" type="text" name="sivu[<?php echo $lomake_sivutDTO->ID; ?>][Sivun_nimi_en]"></td>									
									<td><input name="poista_lomake_sivu[<?php echo $lomakeDTO->Lomakkeen_sivutDTO[$tunniste]->ID; ?>]" type="submit" value="Poista sivu" class="poista"></td>
								</tr>
							<?php } ?>
						</table>
					<?php } ?>
				</p>
				<div class="onclick_funktio" id="lomake_sivu-lisaa">
					<input type="button" class="nappi" value="Lisää uusi sivu">
				</div>
			</div>
		</div>
		<input style="display: none;" class="nappi uusi_sivu_lisays" id="peruuta_sivun_lisays" type="button" value="<?php echo PERUUTA; ?>"/>
		<input name="lisaa_uusi_sivu" style="display: none;" class="nappi2 uusi_sivu_lisays" id="lisaa_uusi_sivu" type="submit" value="Tallenna uusi lomakkeen sivu"/>
		<input name="lomake_id" type="hidden" value="<?php echo $lomake_id; ?>" />
		<input id="lom_tall" name="tallenna_lomake" type="submit" class="nappi2 lom_perustiedot" value="Tallenna lomake" />
		<?php if($_SESSION["kayttaja_rooli"] == "rooli_viranomaisen_paak" && $lomakeDTO->Lomakkeen_tyyppi=="Hakemus"){ ?>
			</fieldset>
		<?php } ?>
	</div>
</form>
<?php
	include './ui/template/footer.php';
?>