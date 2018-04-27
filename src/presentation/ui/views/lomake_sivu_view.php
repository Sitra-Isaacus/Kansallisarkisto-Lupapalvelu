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

<p class="murupolku"><?php echo ETUSIVU; ?> > Lomakkeet > <?php tulosta_teksti($lomakeDTO->Nimi); ?> > <?php tulosta_teksti($haettu_lomake_sivuDTO->Nimi_fi); ?></p>

<?php include './ui/template/vasen_menu_lomake.php'; ?>

<div class="oikea_sisalto">

	<?php if($haettu_lomake_sivuDTO->Sivun_tunniste=="hakemus_liitteet"){ // Liitteet -sivupohja ?>
		<form class="form_lomake_sivu_liitteet" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			<div style="display: none;" class="oikea_sisalto_laatikko uusi_liitetiedosto">
			
					<div class="paneeli_otsikko">
						<h3>Lisää uusi liitetiedosto</h3>
					</div>
					
					<div class="paneelin_tiedot">
						<div class="tieto_blue">
							<p class="miniotsikko">1. Liitetiedoston nimi* (esim. "Rekisteriseloste")</p>
							<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
							<input type="text" name="uusi_liitetiedosto[Liitteen_nimi_fi]"><br /><br>
							<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
							<input type="text" name="uusi_liitetiedosto[Liitteen_nimi_en]"><br /><br>
						</div>
						<div class="tieto_blue">
							<p class="miniotsikko">2. Liitetiedoston asiakirjan tarkenne*</p>
							<input type="text" name="uusi_liitetiedosto[Asiakirjan_tarkenne]"><br />
						</div>
						<div class="tieto_blue">
							<p class="miniotsikko">3. Liitetiedoston lisätiedot</p>
							<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
							<input type="text" name="uusi_liitetiedosto[Lisatiedot_fi]"><br /><br>
							<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
							<input type="text" name="uusi_liitetiedosto[Lisatiedot_en]"><br /><br>
						</div>
						<div class="tieto_blue">
							<p class="miniotsikko">4. Hyväksytyt tiedostotyypit</p>
							<input type="checkbox" name="uusi_liitetiedosto_tyypit_all" id="uusi_liitetiedosto_tyypit_all">
							<label for="uusi_liitetiedosto_tyypit_all">Kaikki</label>
							<p style="margin-bottom: 0.3em;">
								<input type="checkbox" name="uusi_liitetiedosto_tyypit_txt_all" id="uusi_liitetiedosto_tyypit_txt">
								<label for="uusi_liitetiedosto_tyypit_txt">Kaikki tekstitiedostot</label>
							</p>
								<div class="sisalto_sisennus">
									<input value="pdf" type="checkbox" class="uusi_liite_teksti" name="uusi_liitetiedosto_tyypit[]" id="uusi_liitetiedosto_tyypit[1]">
									<label for="uusi_liitetiedosto_tyypit[1]">PDF</label><br />
									<input value="docx" type="checkbox" class="uusi_liite_teksti" name="uusi_liitetiedosto_tyypit[]" id="uusi_liitetiedosto_tyypit[2]">
									<label for="uusi_liitetiedosto_tyypit[2]">DOC/DOCX</label><br />
									<input value="rtf" type="checkbox" class="uusi_liite_teksti" name="uusi_liitetiedosto_tyypit[]" id="uusi_liitetiedosto_tyypit[3]">
									<label for="uusi_liitetiedosto_tyypit[3]">RTF</label><br />
									<input value="txt" type="checkbox" class="uusi_liite_teksti" name="uusi_liitetiedosto_tyypit[]" id="uusi_liitetiedosto_tyypit[4]">
									<label for="uusi_liitetiedosto_tyypit[4]">TXT</label><br />
									<input value="xlsx" type="checkbox" class="uusi_liite_teksti" name="uusi_liitetiedosto_tyypit[]" id="uusi_liitetiedosto_tyypit[5]">
									<label for="uusi_liitetiedosto_tyypit[5]">XLS/XLSX</label>
								</div>
							<p style="margin-bottom: 0.3em;">
								<input type="checkbox" name="uusi_liitetiedosto_tyypit_img_all" id="uusi_liitetiedosto_tyypit_img">
								<label for="uusi_liitetiedosto_tyypit_img">Kaikki kuvatiedostot</label>
							</p>
							<div class="sisalto_sisennus">
								<input value="jpg" type="checkbox" class="liite_kuva" name="uusi_liitetiedosto_tyypit[]" id="uusi_liitetiedosto_tyypit[6]">
								<label for="uusi_liitetiedosto_tyypit[6]">JPG</label><br />
								<input value="png" type="checkbox" class="liite_kuva" name="uusi_liitetiedosto_tyypit[]" id="uusi_liitetiedosto_tyypit[7]">
								<label for="uusi_liitetiedosto_tyypit[7]">PNG</label>
							</div>
						</div>
						<div class="tieto_blue">
							<p class="miniotsikko">5. Liitetiedoston pakollisuus</p>
							<input type="radio" name="uusi_liitetiedosto[Pakollinen]" id="uusi_liitetiedosto_pakollinen_0" value="ei_pakollinen">
							<label for="uusi_liitetiedosto_pakollinen_0">Ei pakollinen liitetiedosto</label><br />
							<input type="radio" name="uusi_liitetiedosto[Pakollinen]" id="uusi_liitetiedosto_pakollinen_1" value="pakollinen">
							<label for="uusi_lomake_liitesivu_liite[1]_pakollinen_1">Pakollinen liitetiedosto</label><br />
							<input type="radio" name="uusi_liitetiedosto[Pakollinen]" id="uusi_liitetiedosto_pakollinen_ehd" value="ehdollisesti_pakollinen">
							<label for="uusi_liitetiedosto_pakollinen_ehd">Ehdollisesti pakollinen liitetiedosto</label>
							<div id="uusi_liitetiedosto_ehd_pakollisuus" style="display: none;">
								<br><br>
								Lomakkeen vastaus, johon liitetiedosto sidotaan<br />
								<select name="uusi_liitetiedosto[Osio_ehto]">
									<option value=""></option>
									<?php foreach($lomakeDTO->Lomakkeen_sivutDTO as $tunniste => $lomake_sivuDTO) {
										foreach($lomake_sivuDTO->OsiotDTO_taulu as $fk_osio => $osioDTO) {
											if(!is_null($osioDTO->OsioDTO_parent) && ($osioDTO->Osio_tyyppi=="checkbox" || $osioDTO->Osio_tyyppi=="radio" || $osioDTO->Osio_tyyppi=="textarea" || $osioDTO->Osio_tyyppi=="textarea_large")){ 
												$kysymystyyppi = null;
												if($osioDTO->Osio_tyyppi=="checkbox") $kysymystyyppi = "Valintaruutu:";
												if($osioDTO->Osio_tyyppi=="radio") $kysymystyyppi = "Valintapainike:";
												if($osioDTO->Osio_tyyppi=="textarea_large" || $osioDTO->Osio_tyyppi=="textarea") $kysymystyyppi = "Tekstivastaus";
												?> <option value="<?php echo $osioDTO->ID; ?>">Sivu: <?php echo koodin_selite($lomake_sivuDTO->Nimi, $_SESSION['kayttaja_kieli']); ?>, Kysymys: <?php echo kaanna_osion_kentta($osioDTO->OsioDTO_parent, "Otsikko", "fi"); ?>, <?php echo $kysymystyyppi; ?> <?php echo kaanna_osion_kentta($osioDTO, "Otsikko","fi"); ?></option>
											<?php }
										}
									} ?>
								</select>
								<br><br>
								Vastauksen ehto<br />
								<select name="uusi_liitetiedosto[Ehto]">
									<option value=""></option>
									<option value="Valittu">Vastaus on valittu / vastaus ei ole tyhjä </option>
									<option value="Ei_valittu">Vastaus ei ole valittu / vastaus on tyhjä</option>
								</select>
							</div>
						</div>
					</div>
			</div>
						
			<?php if(!is_null($lomakeDTO->Asiakirjahallinta_liitteetDTO) && !empty($lomakeDTO->Asiakirjahallinta_liitteetDTO)){ ?>
				<?php for($a=0; $a < sizeof($lomakeDTO->Asiakirjahallinta_liitteetDTO); $a++){ ?>
					<div class="oikea_sisalto_laatikko lomake_liitetiedosto">
						<div class="paneeli_otsikko">
							<h3><?php echo 1 + $a; ?>. liitetiedosto</h3>
						</div>
						<div class="paneelin_tiedot">
						
								<div class="tieto_blue">
									<p class="miniotsikko">Liitetiedoston nimi*</p>
									<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
									<input value="<?php if(isset($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Liitteen_nimi_fi)) tulosta_teksti($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Liitteen_nimi_fi); ?>" type="text" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Liitteen_nimi_fi]"><br /><br>
									<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
									<input value="<?php if(isset($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Liitteen_nimi_en)) tulosta_teksti($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Liitteen_nimi_en); ?>" type="text" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Liitteen_nimi_en]"><br /><br>
								</div>
								
								<div class="tieto_blue">
									<p class="miniotsikko">Liitetiedoston asiakirjan tarkenne*</p>
									<input value="<?php if(isset($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Asiakirjan_tarkenne)) tulosta_teksti($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Asiakirjan_tarkenne); ?>" type="text" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Asiakirjan_tarkenne]"><br />
								</div>
								
								<div class="tieto_blue">
									<p class="miniotsikko">Liitetiedoston lisätiedot</p>
									<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
									<input value="<?php if(isset($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Lisatiedot_fi)) tulosta_teksti($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Lisatiedot_fi); ?>" type="text" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Lisatiedot_fi]"><br /><br>
									<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
									<input value="<?php if(isset($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Lisatiedot_en)) tulosta_teksti($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Lisatiedot_en); ?>" type="text" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Lisatiedot_en]"><br /><br>
								</div>
								
								<div class="tieto_blue">
									<p class="miniotsikko">Hyväksytyt tiedostotyypit</p>
										<?php $sallitut_tyypit = explode( ',', $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Sallitut_tiedostotyypit ); ?>
										<input <?php if(in_array("pdf",$sallitut_tyypit)) echo "checked"; ?> id="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-1" value="pdf" type="checkbox" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Sallittu_tyyppi][1]">
										<label for="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-1">PDF</label><br />
										<input <?php if(in_array("doc",$sallitut_tyypit) || in_array("docx",$sallitut_tyypit)) echo "checked"; ?> value="docx" type="checkbox" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Sallittu_tyyppi][2]" id="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-2">
										<label for="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-2">DOC/DOCX</label><br />
										<input <?php if(in_array("rtf",$sallitut_tyypit)) echo "checked"; ?> value="rtf" type="checkbox" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Sallittu_tyyppi][3]" id="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-3">
										<label for="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-3">RTF</label><br />
										<input <?php if(in_array("txt",$sallitut_tyypit)) echo "checked"; ?> value="txt" type="checkbox" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Sallittu_tyyppi][4]" id="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-4">
										<label for="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-4">TXT</label><br />
										<input <?php if(in_array("xlsx",$sallitut_tyypit) || in_array("xls",$sallitut_tyypit)) echo "checked"; ?> value="xlsx" type="checkbox" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Sallittu_tyyppi][5]" id="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-5">
										<label for="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-5">XLS/XLSX</label><br />
										<input <?php if(in_array("jpg",$sallitut_tyypit)) echo "checked"; ?> value="jpg" type="checkbox" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Sallittu_tyyppi][6]" id="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-6">
										<label for="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-6">JPG</label><br />
										<input <?php if(in_array("png",$sallitut_tyypit)) echo "checked"; ?> value="png" type="checkbox" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Sallittu_tyyppi][7]" id="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-7">
										<label for="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-tyyppi-6">PNG</label>
								</div>
								<div class="tieto_blue">
									<p class="miniotsikko"> Liitetiedoston pakollisuus</p>
									<?php 
									$liite_on_pakollinen = false;
									$liite_on_ehd_pakollinen = false;
									$ehdollinen_osio_id = null;
									$predikaatti = null;
									
									if(isset($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Asiakirjahallinta_saannotDTO) && !empty($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Asiakirjahallinta_saannotDTO)){
										for($s=0; $s < sizeof($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Asiakirjahallinta_saannotDTO); $s++){
											
											if($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Asiakirjahallinta_saannotDTO[$s]->Saanto=="liite_on_pakollinen") $liite_on_pakollinen = true;
											
											if($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Asiakirjahallinta_saannotDTO[$s]->Saanto=="liite_on_ehdollisesti_pakollinen"){
												
												$liite_on_ehd_pakollinen = true;
												
												if(isset($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Asiakirjahallinta_saannotDTO[$s]->Osio_lauseetDTO[0]->OsioDTO_Muuttuja->ID)){
													$ehdollinen_osio_id = $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Asiakirjahallinta_saannotDTO[$s]->Osio_lauseetDTO[0]->OsioDTO_Muuttuja->ID;
												}
												
												if($lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Asiakirjahallinta_saannotDTO[$s]->Osio_lauseetDTO[0]->Predikaatti){
													$predikaatti = $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->Asiakirjahallinta_saannotDTO[$s]->Osio_lauseetDTO[0]->Predikaatti;
												}
												
											}
											
										}
									}
									
									?>
									<input class="liitetiedosto_ei_pakollisuus" <?php if(!$liite_on_pakollinen && !$liite_on_ehd_pakollinen) echo "checked"; ?> type="radio" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Pakollinen]" id="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-pakollinen_0" value="ei_pakollinen">
									<label for="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-pakollinen_0">Ei pakollinen liitetiedosto</label><br />
									<input class="liitetiedosto_pakollisuus" <?php if($liite_on_pakollinen) echo "checked"; ?> type="radio" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Pakollinen]" id="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-pakollinen_1" value="pakollinen">
									<label for="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-pakollinen_1">Pakollinen liitetiedosto</label><br />
									<input value="ehdollisesti_pakollinen" class="liitetiedosto_ehd_pakollisuus" <?php if($liite_on_ehd_pakollinen) echo "checked"; ?> type="radio" name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Pakollinen]" id="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-pakollinen_ehd">
									<label for="liite-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>-pakollinen_ehd">Ehdollisesti pakollinen liitetiedosto</label>
									<div id="liitetiedosto_ehd_pakollisuus-<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>" style="display: <?php if($liite_on_ehd_pakollinen){ echo "block"; } else { echo "none"; } ?>;">
										<br><br>
										Lomakkeen vastaus, johon liitetiedosto sidotaan<br />
										<select name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Osio_ehto]">
											<option value=""></option>
											<?php foreach($lomakeDTO->Lomakkeen_sivutDTO as $tunniste => $lomake_sivuDTO) {
												foreach($lomake_sivuDTO->OsiotDTO_taulu as $fk_osio => $osioDTO) {
													if(!is_null($osioDTO->OsioDTO_parent->ID) && ($osioDTO->Osio_tyyppi=="checkbox" || $osioDTO->Osio_tyyppi=="radio" || $osioDTO->Osio_tyyppi=="textarea" || $osioDTO->Osio_tyyppi=="textarea_large")){ 
														$kysymystyyppi = null;
														if($osioDTO->Osio_tyyppi=="checkbox") $kysymystyyppi = "Valintaruutu:";
														if($osioDTO->Osio_tyyppi=="radio") $kysymystyyppi = "Valintapainike:";
														if($osioDTO->Osio_tyyppi=="textarea_large" || $osioDTO->Osio_tyyppi=="textarea") $kysymystyyppi = "Tekstivastaus";
														?> <option <?php if($osioDTO->ID==$ehdollinen_osio_id) echo "selected"; ?> value="<?php echo $osioDTO->ID; ?>">Sivu: <?php echo kaanna_osion_kentta($lomake_sivuDTO, "Nimi", $_SESSION['kayttaja_kieli']); ?>, Kysymys: <?php echo kaanna_osion_kentta($osioDTO->OsioDTO_parent, "Otsikko", $_SESSION['kayttaja_kieli']); ?>, <?php echo $kysymystyyppi; ?> <?php echo $osioDTO->Otsikko; ?></option>
													<?php }
												}
											} ?>
										</select>
										<br><br>
										Vastauksen ehto<br />
										<select name="liitetiedosto[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>][Ehto]">
											<option value=""></option>
											<option <?php if($predikaatti=="Valittu") echo "selected"; ?> value="Valittu">Vastaus on valittu / vastaus ei ole tyhjä </option>
											<option <?php if($predikaatti=="Ei_valittu") echo "selected"; ?> value="Ei_valittu">Vastaus ei ole valittu / vastaus on tyhjä</option>
										</select>
									</div>
								</div>
								<div style="width: 600px;">
									<input name="poista_liitetyyppi[<?php echo $lomakeDTO->Asiakirjahallinta_liitteetDTO[$a]->ID; ?>]" type="submit" class="poista" value="Poista liitetiedosto">
									<br><br>
								</div>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
			
			<br>
			<input id="lisaa_uusi_liitetiedosto" type="button" class="nappi lomake_liitetiedosto" value="Lisää uusi liitetiedosto">
			<input name="lomake_id" type="hidden" value="<?php echo $lomake_id; ?>" />
			<input name="lomake_sivu_id" type="hidden" value="<?php echo $lomake_sivu_id; ?>" />
			<input style="display: none;" class="nappi uusi_liitetiedosto" id="peruuta_liitetiedoston_lisays" type="button" value="<?php echo PERUUTA; ?>"/>
			<input style="display: none;" name="lisaa_uusi_liitetiedosto" type="submit" class="nappi2 uusi_liitetiedosto" value="Tallenna uusi liitetiedosto" />
			<input id="liit_tall" name="tallenna_liitetyypit" type="submit" class="nappi2 lomake_liitetiedosto" value="Tallenna lomake" />
		</form>
		
	<?php } else { // Ei sivupohjaa ?>
	
		<form class="form_lomake_sivu" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
		
			<?php if($_SESSION['kayttaja_rooli'] == "rooli_viranomaisen_paak" && $haettu_lomake_sivuDTO->Sivun_tunniste!="hakemus_viranomaiskohtaiset" && $lomakeDTO->Lomakkeen_tyyppi=="Hakemus"){ ?>
				<fieldset disabled>
			<?php } ?>
			
			<div class="oikea_sisalto_laatikko uusi_kysymys" style="display: none;">
			
				<?php if(!empty($haettu_lomake_sivuDTO->OsiotDTO_puu)){ ?>
					<?php for($i=0; $i < sizeof($haettu_lomake_sivuDTO->OsiotDTO_puu); $i++){ ?>
						
						<div style="display: none;" id="parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>-uusi_kysymys" class="uuden_kysymyksen_lisays_kokonaisuuteen">
						
							<div class="paneeli_otsikko">
								<h2>Kysymyskokonaisuus: <?php tulosta_teksti($haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->Osio_nimi); ?></h2>
								<h3>Lisää uusi kysymys </h3>
							</div>
							
							<div class="paneelin_tiedot">
							
								<div class="tieto_blue">
									<p class="miniotsikko">Kysymyksen tyyppi*</p>
									<select id="parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>-uusi_kysymys_tyyppi" class="uusi_kysymys_tyyppi" name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Osio_tyyppi]">
										<option selected></option>
										<option value="textarea">Lyhyt tekstivastaus</option>
										<option value="textarea_large">Pitkä tekstivastaus</option>
										<option value="date_range">Päivämäärä (jakso)</option>
										<option value="checkbox">Valintaruutu (voi valita useita vaihtoehtoja)</option>
										<option value="radio">Valintapainike (voi valita yhden vaihtoehdon)</option>
										<?php if($lomakeDTO->Lomakkeen_tyyppi=="Hakemus" && $haettu_lomake_sivuDTO->Sivun_tunniste!="hakemus_viranomaiskohtaiset"){ ?> <option value="lomake_tutkimuksen_nimi">Tutkimuksen/hankkeen nimi</option> <?php } ?>
									</select>
								</div>
								
								<?php if($_SESSION['kayttaja_rooli'] == "rooli_lupapalvelun_paak" && $haettu_lomake_sivuDTO->Sivun_tunniste=="hakemus_viranomaiskohtaiset"){ ?>
									<div class="tieto_blue">
										<p class="miniotsikko"><?php echo VIRANOMAINEN; ?></p>
										<select name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Viranomainen]">
											<option selected></option>
											<?php for($o=0; $o < sizeof($koodistotDTO_organisaatiot); $o++){ ?>
												<option value="<?php echo $koodistotDTO_organisaatiot[$o]->Koodi; ?>"><?php echo $koodistotDTO_organisaatiot[$o]->Selite1; ?></option>
											<?php } ?>
										</select>
									</div>
								<?php } ?>
								
								<div class="tieto_blue">
									<p class="miniotsikko">Järjestys</p>
									<input type="number" style="width: 60px;" name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Jarjestys]">
								</div>
								
								<div id="parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>-uuden_kysymyksen_lisametatiedot" style="display: none;">
									
									<div class="tieto_blue">
										<p class="miniotsikko">Kysymyksen teksti*</p>
										<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
										<input type="text" name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Otsikko_fi]"><br><br>
										<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
										<input type="text" name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Otsikko_en]"><br><br>
									</div>
									
									<div class="tieto_blue">
										<input id="fk_osio_parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>-pakollinen" type="checkbox" name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Pakollinen_tieto]">
										<label for="fk_osio_parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>-pakollinen">Pakollinen kysymys</label>
									</div>
									
									<div class="tieto_blue">
										<p class="miniotsikko">Kysymyksen infoteksti</p>
										<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
										<textarea name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Infoteksti_fi]"></textarea><br><br>
										<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
										<textarea name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Infoteksti_en]"></textarea><br><br>
									</div>
									
									<div style="display: none;" id="parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>-uusi_kysymys_checkbox">
										
										<?php for($j=0; $j < 20; $j++){ ?>
											<div class="tieto_blue" style="display: <?php if($j==0){ echo "block;"; } else { echo "none;"; } ?>" id="parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>-checkbox-<?php echo $j; ?>">
												
												<p class="miniotsikko">Vastausvaihtoehto</p>
												
												<div class="sisalto_sisennus">
												
													<p>Vastausvaihtoehdon teksti*</p>
													<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
													<input type="text" name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Osio_checkbox][<?php echo $j; ?>][Otsikko_fi]"><br><br>
													<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
													<input type="text" name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Osio_checkbox][<?php echo $j; ?>][Otsikko_en]"><br><br><br>
													<p>Vaihtoehdon järjestys</p>
													<input type="number" style="width: 60px;" name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Osio_checkbox][<?php echo $j; ?>][Jarjestys]"><br><br>
													<?php if($j>0){ ?>
														<input id="poista_uusi_vaihtoehto-<?php echo $j; ?>-checkbox" type="button" class="poista2 poista_uusi_vaihtoehto" value="Poista vaihtoehto"><br><br>
													<?php } ?>
													
												</div>
												
											</div>
										<?php } ?>
										
										<input type="button" id="lisaa_checkbox_vaihtoehto-parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>" class="nappi2 lisaa_uusi_checkbox_vaihtoehto" value="Lisää uusi vaihtoehto">
									
									</div>
									
									<div style="display: none;" id="parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>-uusi_kysymys_radio">
										<?php for($j=0; $j < 20; $j++){ ?>
											
											<div class="tieto_blue" style="display: <?php if($j==0){ echo "block;"; } else { echo "none;"; } ?>" id="parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>-radio-<?php echo $j; ?>">
												
												<p class="miniotsikko">Vastausvaihtoehto</p>
												
												<div class="sisalto_sisennus">
													<p>Vastausvaihtoehdon teksti*</p>
													
													<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
													<input type="text" name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Osio_radio][<?php echo $j; ?>][Otsikko_fi]"><br><br>
													<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
													<input type="text" name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Osio_radio][<?php echo $j; ?>][Otsikko_en]"><br><br>

													<p>Vaihtoehdon järjestys</p>
													<input type="number" style="width: 60px;" name="Osio_parent[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Uusi_kysymys][Osio_radio][<?php echo $j; ?>][Jarjestys]"><br><br>
													<?php if($j>0){ ?>
														<input id="poista_uusi_vaihtoehto-<?php echo $j; ?>-radio" type="button" class="poista2 poista_uusi_vaihtoehto" value="Poista vaihtoehto"><br><br>
													<?php } ?>
													
												</div>
												
											</div>
											
										<?php } ?>
										
										<input type="button" id="lisaa_radio_vaihtoehto-parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>" class="nappi2 lisaa_uusi_radio_vaihtoehto" value="Lisää uusi vaihtoehto">
									
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
				
			</div>
			
			<div class="oikea_sisalto_laatikko uusi_kokonaisuus" id="lisaa_uusi_uusi_kokonaisuus_box" style="display: none;">
				<div class="paneeli_otsikko">
					<h3>Lisää uusi kysymyskokonaisuus</h3>
				</div>
				<div class="paneelin_tiedot">
					<div class="tieto_blue">
						<table class="lomake_sivu_jarj">
						<tr>
							<th>Järjestys</th>
							<th>Kysymyskokonaisuuden nimi</th>
							<?php if($_SESSION['kayttaja_rooli'] == "rooli_lupapalvelun_paak" && $haettu_lomake_sivuDTO->Sivun_tunniste=="hakemus_viranomaiskohtaiset"){ ?>
								<th>
									<?php echo VIRANOMAINEN; ?>
								</th>
							<?php } ?>
						</tr>
						<tr>
							<td><input type="number" name="uusi_kokonaisuus[Jarjestys]"></td>
							<td><input type="text" name="uusi_kokonaisuus[Nimi]"></td>
							<?php if($_SESSION['kayttaja_rooli'] == "rooli_lupapalvelun_paak" && $haettu_lomake_sivuDTO->Sivun_tunniste=="hakemus_viranomaiskohtaiset"){ ?>
								<td>
									<select name="uusi_kokonaisuus[Viranomainen]">
										<?php for($o=0; $o < sizeof($koodistotDTO_organisaatiot); $o++){ ?>
											<option value="<?php echo $koodistotDTO_organisaatiot[$o]->Koodi; ?>"><?php echo $koodistotDTO_organisaatiot[$o]->Selite1; ?></option>
										<?php } ?>
									</select>
								</td>
							<?php } ?>
						</tr>
						</table>
					</div>
				</div>
			</div>
			
			<div class="oikea_sisalto_laatikko lomake_sivu">
				
				<div class="paneeli_otsikko">
					<h2><?php tulosta_teksti($lomakeDTO->Nimi); ?> : <?php tulosta_teksti($haettu_lomake_sivuDTO->Nimi_fi); ?></h2>
					<h3>Sivun perustiedot</h3>
				</div>
				
				<div class="paneelin_tiedot">
					<div class="tieto_blue">
						Sivun nimi* <br>
						<img src="static/images/fi.png" class="lippu" /> Suomeksi*<br />
						<input <?php if($_SESSION['kayttaja_rooli'] == "rooli_viranomaisen_paak"){ ?> disabled <?php } ?> type="text" name="sivun_nimi_fi" value="<?php tulosta_teksti($haettu_lomake_sivuDTO->Nimi_fi); ?>"><br><br>
						<img src="static/images/uk.png" class="lippu" /> Englanniksi*<br />
						<input <?php if($_SESSION['kayttaja_rooli'] == "rooli_viranomaisen_paak"){ ?> disabled <?php } ?> type="text" name="sivun_nimi_en" value="<?php tulosta_teksti($haettu_lomake_sivuDTO->Nimi_en); ?>"><br>
					</div>
				</div>
				
			</div>
						
			<?php if(!empty($haettu_lomake_sivuDTO->OsiotDTO_puu)){ ?>			
				<?php for($i=0; $i < sizeof($haettu_lomake_sivuDTO->OsiotDTO_puu); $i++){ ?>
				
					<?php if((isset($_SESSION['kayttaja_viranomainen']) && $_SESSION['kayttaja_viranomainen']!=$haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->Viranomaiskohtainen_tunniste) && $_SESSION['kayttaja_rooli'] == "rooli_viranomaisen_paak" && $haettu_lomake_sivuDTO->Sivun_tunniste=="hakemus_viranomaiskohtaiset") continue; ?>
					<?php if($haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->Osio_tyyppi!="laatikko") continue; ?>
					
					<div class="oikea_sisalto_laatikko lomake_sivu">
						<div class="paneeli_otsikko">
							<h3> Kysymyskokonaisuus: <?php tulosta_teksti($haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->Osio_nimi); ?></h3>
						</div>
						<?php 
						$osioDTO_otsikko = null;
						$osioDTO_sisalto = null;
						for($j=0; $j < sizeof($haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->OsioDTO_childs); $j++){
							if(isset($haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->OsioDTO_childs[$j]->Osio_tyyppi) && $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->OsioDTO_childs[$j]->Osio_tyyppi=="laatikko_otsikko"){
								$osioDTO_otsikko = $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->OsioDTO_childs[$j];
							}
							if(isset($haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->OsioDTO_childs[$j]->Osio_tyyppi) && $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->OsioDTO_childs[$j]->Osio_tyyppi=="laatikko_sisalto"){
								$osioDTO_sisalto = $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->OsioDTO_childs[$j];
							}
						}
						?>
						<div class="paneelin_tiedot">
						
							<?php if($_SESSION['kayttaja_rooli'] == "rooli_lupapalvelun_paak" && $haettu_lomake_sivuDTO->Sivun_tunniste=="hakemus_viranomaiskohtaiset"){ ?>
								<div class="tieto_blue">
									<p class="miniotsikko"><?php echo VIRANOMAINEN; ?></p>
									<?php for($o=0; $o < sizeof($koodistotDTO_organisaatiot); $o++){ ?>
										<?php if($haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->Viranomaiskohtainen_tunniste==$koodistotDTO_organisaatiot[$o]->Koodi) echo $koodistotDTO_organisaatiot[$o]->Selite1; ?>
									<?php } ?>
								</div>
							<?php } ?>
							
							<div class="tieto_blue">
								<p class="miniotsikko">Kokonaisuuden otsikko</p>
								<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
								<input type="text" value="<?php tulosta_teksti($osioDTO_otsikko->Otsikko_fi); ?>" name="Osio[<?php echo $osioDTO_otsikko->ID; ?>][Otsikko_fi]"><br><br>
								<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
								<input type="text" value="<?php tulosta_teksti($osioDTO_otsikko->Otsikko_en); ?>" name="Osio[<?php echo $osioDTO_otsikko->ID; ?>][Otsikko_en]"><br><br>
							</div>
							
							<div class="tieto_blue">
								<p class="miniotsikko">Järjestys</p>
								<input style="width: 60px;" type="number" name="Osio[<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>][Jarjestys]" value="<?php tulosta_teksti($haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->Jarjestys); ?>">
							</div>
							
						</div>
						
						<div class="paneelin_tiedot" style="width: 600px;">
							<input type="button" id="lisaa_kysymys-parent-<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>" class="nappi lisaa_uusi_kysymys" value="Lisää uusi kysymys kokonaisuuteen">
							<input name="poista_osio[Osio][<?php echo $haettu_lomake_sivuDTO->OsiotDTO_puu[$i]->ID; ?>]" type="submit" class="poista" value="Poista kysymyskokonaisuus">
						</div>
						
						<div class="sisalto_sisennus">
							<hr />
							<?php if(!empty($osioDTO_sisalto->OsioDTO_childs)){ ?>
								<?php for($j=0; $j < sizeof($osioDTO_sisalto->OsioDTO_childs); $j++){ ?>
								
									<div id="kysymys_box-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>">
									
										<div class="paneeli_otsikko">
											<h3 style="cursor: pointer;" class="kysymys_otsikko_click" id="kysymys_otsikko_nayta-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>"> <?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->Jarjestys; ?>. Kysymys <img src="static/images/expand.png"> </h3>
											<h3 style="cursor: pointer; display: none;" class="kysymys_otsikko_click" id="kysymys_otsikko_piilota-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>"> <?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->Jarjestys; ?>. Kysymys <img src="static/images/collapse.png"> </h3>
										</div>
										
										<div id="kysymys_sisalto-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>" style="display: none;" class="paneelin_tiedot">
											
											<div class="tieto_blue">
												<p class="miniotsikko">Kysymyksen tunniste (ID)</p>
												<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>
											</div>
											
											<div class="tieto_blue">
												<p class="miniotsikko">Kysymyksen tyyppi*</p>
												<select class="select_kysymyksen_tyyppi" id="select_kysymyksen_tyyppi-parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>" name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->ID; ?>][Osio_tyyppi]">
													<option></option>
													<option <?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->Osio_tyyppi=="textarea") echo "selected"; ?> value="textarea">Lyhyt tekstivastaus</option>
													<option <?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->Osio_tyyppi=="textarea_large") echo "selected"; ?> value="textarea_large">Pitkä tekstivastaus</option>
													<option <?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->Osio_tyyppi=="date_start" && $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[1]->Osio_tyyppi=="date_end") echo "selected"; ?> value="date_range">Päivämäärä (jakso)</option>
													<option <?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->Osio_tyyppi=="checkbox") echo "selected"; ?> value="checkbox">Valintaruutu (voi valita useita vaihtoehtoja)</option>
													<option <?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->Osio_tyyppi=="radio") echo "selected"; ?> value="radio">Valintapainike (voi valita yhden vaihtoehdon)</option>
													<option <?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->Osio_tyyppi=="lomake_tutkimuksen_nimi") echo "selected"; ?> value="lomake_tutkimuksen_nimi">Tutkimuksen/hankkeen nimi</option>
												</select>
											</div>
											
											<div class="tieto_blue">
												<p class="miniotsikko">Järjestys</p>
												<input value="<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->Jarjestys; ?>" type="number" style="width: 60px;" name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Jarjestys]">
											</div>
											
											<div id="kysymyksen_lisametatiedot-parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>" style="display: <?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->Osio_tyyppi!="lomake_tutkimuksen_nimi"){ echo "block;"; } else { echo "none;"; } ?>">
												
												<div class="tieto_blue">
													<p class="miniotsikko">Kysymyksen teksti*</p>
													<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
													<input value="<?php tulosta_teksti($osioDTO_sisalto->OsioDTO_childs[$j]->Otsikko_fi); ?>" type="text" name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Otsikko_fi]"><br><br>
													<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
													<input value="<?php tulosta_teksti($osioDTO_sisalto->OsioDTO_childs[$j]->Otsikko_en); ?>" type="text" name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Otsikko_en]"><br><br>													
												</div>
												
												<?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->Osio_tyyppi!="checkbox" && $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->Osio_tyyppi!="radio"){ ?>
													<div class="tieto_blue">
														<input <?php if($osioDTO_sisalto->OsioDTO_childs[$j]->Pakollinen_tieto==1) echo "checked"; ?> id="osio-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>-pakollinen" type="checkbox" name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Pakollinen_tieto]">
														<label for="osio-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>-pakollinen">Pakollinen kysymys</label>
													</div>
												<?php } ?>
												
												<div class="tieto_blue">
													<p class="miniotsikko">Kysymyksen infoteksti</p>
													<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
													<textarea name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Infoteksti_fi]"><?php tulosta_teksti($osioDTO_sisalto->OsioDTO_childs[$j]->Infoteksti_fi); ?></textarea><br><br>
													<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
													<textarea name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Infoteksti_en]"><?php tulosta_teksti($osioDTO_sisalto->OsioDTO_childs[$j]->Infoteksti_en); ?></textarea><br><br>
												</div>
												
												<div style="display: <?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->Osio_tyyppi=="checkbox"){ echo "block;"; } else { echo "none;"; } ?>" id="parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>-kysymys_checkbox">
													<?php for($c=0; $c < sizeof($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs); $c++){ ?>
														<?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Osio_tyyppi=="checkbox"){ ?>
															<div class="tieto_blue" id="parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>-olemassaoleva_checkbox-<?php echo $c; ?>" style="display: <?php if(isset($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->ID) && $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Osio_tyyppi=="checkbox"){ echo "block;"; } else { echo "none;"; } ?>" >
																
																<p class="miniotsikko">Vastausvaihtoehto</p>
																
																<div class="sisalto_sisennus">
																	<p>Vastausvaihtoehdon teksti*</p>
																	<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
																	<input value="<?php if(isset($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Otsikko_fi)) echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Otsikko_fi; ?>" type="text" name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->ID; ?>][Otsikko_fi]"><br><br>
																	<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
																	<input value="<?php if(isset($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Otsikko_en)) echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Otsikko_en; ?>" type="text" name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->ID; ?>][Otsikko_en]"><br><br><br>
																	
																	<p>Vaihtoehdon järjestys</p>
																	<input value="<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Jarjestys; ?>" type="number" style="width: 60px;" name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->ID; ?>][Jarjestys]"><br><br>
																	<?php if($c>0){ ?>
																		<input name="poista_osio[Osio][<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->ID; ?>]" id="poista_vaihtoehto-<?php echo $c; ?>" type="submit" class="poista poista_vaihtoehto" value="Poista vaihtoehto"><br><br>
																	<?php } ?>
																	
																</div>
																
															</div>
														<?php } ?>
													<?php } ?>
													<?php for($l=0; $l < (20 - $c); $l++){ ?>
														<div class="tieto_blue" style="display: none;" id="parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>-vaihtoehto-<?php echo $l; ?>">
															
															<p class="miniotsikko">Vastausvaihtoehto</p>
															
															<div class="sisalto_sisennus">
															
																<p>Vastausvaihtoehdon teksti*</p>
																<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
																<input type="text" name="Osio_parent[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Osio_checkbox][<?php echo $l; ?>][Otsikko_fi]"><br><br>
																<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
																<input type="text" name="Osio_parent[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Osio_checkbox][<?php echo $l; ?>][Otsikko_en]"><br><br><br>

																<p>Vaihtoehdon järjestys</p>
																<input type="number" style="width: 60px;" name="Osio_parent[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Osio_checkbox][<?php echo $l; ?>][Jarjestys]"><br><br>
																<input id="parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>-poista_vaihtoehto-<?php echo $l; ?>" type="button" class="poista poista_dyn_vaihtoehto" value="Poista vaihtoehto"><br><br>
															
															</div>
															
														</div>
													<?php } ?>
													<div style="width: 100%;">
														<input type="button" id="lisaa_vaihtoehto-parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>" class="nappi2 lisaa_checkbox_vaihtoehto" value="Lisää uusi vaihtoehto">
														<br><br>
													</div>
												</div>
												
												<div style="display: <?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[0]->Osio_tyyppi=="radio"){ echo "block;"; } else { echo "none;"; } ?>" id="parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>-kysymys_radio">
													<?php for($c=0; $c < sizeof($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs); $c++){ ?>
														<?php if($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Osio_tyyppi=="radio"){ ?>
															<div class="tieto_blue" id="parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>-olemassaoleva_vaihtoehto-<?php echo $c; ?>" style="display: <?php if(isset($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->ID) && $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Osio_tyyppi=="radio"){ echo "block;"; } else { echo "none;"; } ?>" >
																
																<p class="miniotsikko">Vastausvaihtoehto</p>
																
																<div class="sisalto_sisennus">
																
																	<p>Vastausvaihtoehdon teksti*</p>
																	<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
																	<input value="<?php if(isset($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Otsikko_fi)) echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Otsikko_fi; ?>" type="text" name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->ID; ?>][Otsikko_fi]"><br><br>
																	<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
																	<input value="<?php if(isset($osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Otsikko_en)) echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Otsikko_en; ?>" type="text" name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->ID; ?>][Otsikko_en]"><br><br><br>

																	<p>Vaihtoehdon järjestys</p>
																	<input value="<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->Jarjestys; ?>" type="number" style="width: 60px;" name="Osio[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->ID; ?>][Jarjestys]"><br><br>
																	<?php if($c>0){ ?>
																		<input name="poista_osio[Osio][<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->OsioDTO_childs[$c]->ID; ?>]" id="parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>-poista_vaihtoehto-<?php echo $c; ?>-olemassaoleva_vaihtoehto" type="submit" class="poista poista_vaihtoehto" value="Poista vaihtoehto"><br><br>
																	<?php } ?>
																	
																</div>
															</div>
														<?php } ?>
													<?php } ?>
													
													<?php for($l=0; $l < (20 - $c); $l++){ ?>
														<div class="tieto_blue" style="display: none;" id="parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>-radiovaihtoehto-<?php echo $l; ?>">
															<p class="miniotsikko">Vastausvaihtoehto</p>
															<div class="sisalto_sisennus">
															
																<p>Vastausvaihtoehdon teksti*</p>
																<img src="static/images/fi.png" class="lippu" /> Suomeksi<br />
																<input type="text" name="Osio_parent[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Osio_radio][<?php echo $l; ?>][Otsikko_fi]"><br><br>
																<img src="static/images/uk.png" class="lippu" /> Englanniksi<br />
																<input type="text" name="Osio_parent[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Osio_radio][<?php echo $l; ?>][Otsikko_en]"><br><br><br>

																<p>Vaihtoehdon järjestys</p>
																<input type="number" style="width: 60px;" name="Osio_parent[<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>][Osio_radio][<?php echo $l; ?>][Jarjestys]"><br><br>
																<input id="parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>-poista_vaihtoehto-<?php echo $l; ?>" type="button" class="poista poista_dyn_radiovaihtoehto" value="Poista vaihtoehto"><br><br>
															</div>
														</div>
													<?php } ?>
													
													<div style="width: 100%;">
														<p><input type="button" id="lisaa_vaihtoehto-parent-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>" class="nappi2 lisaa_radio_vaihtoehto" value="Lisää uusi vaihtoehto"></p>
														<br>
													</div>
												</div>
											</div>
											<div style="width: 600px;">
												<br><input name="poista_osio[Osio][<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>]" id="poista_kysymys-<?php echo $osioDTO_sisalto->OsioDTO_childs[$j]->ID; ?>" type="submit" class="poista3 poista_kysymys" value="Poista kysymys">
												<br>
											</div>
										</div>
									</div>
									<hr />
								<?php } ?>
							<?php } ?>
						</div>
						
					</div>
				<?php } ?>
			<?php } ?>
			
			<br>
			<input name="lomake_id" type="hidden" value="<?php echo $lomake_id; ?>" />
			<input name="lomake_sivu_id" type="hidden" value="<?php echo $lomake_sivu_id; ?>" />
			<input style="display: none;" class="nappi uusi_kysymys" id="peruuta_kysymyksen_lisays" type="button" value="<?php echo PERUUTA; ?>"/>
			<input style="display: none;" name="lisaa_uusi_kysymys" type="submit" class="nappi2 uusi_kysymys" value="Tallenna uusi kysymys" />
			<input style="display: none;" class="nappi uusi_kokonaisuus" id="peruuta_kokonaisuuden_lisays" type="button" value="<?php echo PERUUTA; ?>"/>
			<input style="display: none;" id="lom_tall" name="lisaa_uusi_kokonaisuus" type="submit" class="nappi2 uusi_kokonaisuus" value="Tallenna uusi kysymyskokonaisuus" />
			<input id="lisaa_uusi_kokonaisuus" type="button" class="nappi lomake_sivu" value="Lisää uusi kysymyskokonaisuus">
			<input id="lom_tall" name="tallenna_lomake" type="submit" class="nappi2 lomake_sivu" value="Tallenna lomake" />
			
			<?php if($_SESSION['kayttaja_rooli'] == "rooli_viranomaisen_paak" && $haettu_lomake_sivuDTO->Sivun_tunniste!="hakemus_viranomaiskohtaiset" && $lomakeDTO->Lomakkeen_tyyppi=="Hakemus"){ ?>
				</fieldset>
			<?php } ?>
		</form>
	<?php } ?>
</div>
<?php
	include './ui/template/footer.php';
?>