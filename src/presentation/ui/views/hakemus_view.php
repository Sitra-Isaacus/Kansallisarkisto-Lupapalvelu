<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Hakemus
 *
 * Created: 31.3.2017
 */
 
include './ui/template/header.php';

?>
<p class="murupolku">
	<a href="index.php"><?php echo ETUSIVU; ?></a> > 
	<a href="hakemus.php?hakemusversio_id=<?php echo $hakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $hakemusversioDTO->TutkimusDTO->ID; ?>&sivu=hakemus_perustiedot"><?php echo HAKEMUS; ?></a> > 
	<?php echo tulosta_teksti($hakemusversioDTO->Tutkimuksen_nimi); ?> >  
	<a style="color: black;"> <?php if(isset($hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->Nimi_fi)) echo tulosta_teksti(kaanna_osion_kentta($hakemusversioDTO->Lomakkeen_sivutDTO[$sivu], "Nimi", $_SESSION["kayttaja_kieli"])); ?></a>
</p>

<?php include './ui/template/vasen_menu.php'; ?>

	<div class="viesti_hakemus">
	
		<?php if($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi!="hv_kesken" && isset($hakemusDTO)){ ?>
					
			<div style="display: inline; font-weight: bold;">
				<?php echo HAKEMUS; ?>:
			</div> 
			
			<div style="display: inline;">
				<?php echo $hakemusDTO->Hakemuksen_tunnus; ?> 
			</div><br>
			
			<div style="display: inline; font-weight: bold;">
				<?php echo TYYPPI; ?>: 
			</div>
			<div style="display: inline;">
				<?php echo koodin_selite($hakemusversioDTO->Hakemuksen_tyyppi, $_SESSION["kayttaja_kieli"]); ?>
			</div><br>			
									
			<div style="display: inline; font-weight: bold;">			
				<?php echo TILA; ?>: 
			</div>
			<div style="display: inline;">
				<?php echo koodin_selite($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi, $_SESSION["kayttaja_kieli"]); ?>
			</div><br>
			
			<?php if(isset($hakemusDTO->Kasittelijan_nimi) && !is_null($hakemusDTO->Kasittelijan_nimi)){ ?>	
				<div style="font-weight: bold;">	
					<?php echo KASITTELIJA; ?>: 
				</div> 
				<div style="display: inline;">
					<?php echo $hakemusDTO->Kasittelijan_nimi; ?>
				</div><br>			
			<?php } ?>	
			
			<?php if(isset($hakemusDTO->AsiaDTO->Diaarinumero) && !is_null($hakemusDTO->AsiaDTO->Diaarinumero)){ ?>
				<div style="display: inline; font-weight: bold;">
					<?php echo ASIANRO; ?>:
				</div> 
				<div style="display: inline;">
					<?php echo $hakemusDTO->AsiaDTO->Diaarinumero; ?> 
				</div><br>		
			<?php } ?>				
					
		<?php } else { ?>	

			<div style="display: inline; font-weight: bold;">
				<?php echo LOMAKE; ?>:
			</div> 
			
			<div style="display: inline;">
				<?php echo htmlentities($hakemusversioDTO->LomakeDTO->Nimi, ENT_COMPAT, "UTF-8"); ?> 
			</div><br>	

			<div style="display: inline; font-weight: bold;">
				<?php echo TYYPPI; ?>: 
			</div>
			<div style="display: inline;">
				<?php echo koodin_selite($hakemusversioDTO->Hakemuksen_tyyppi, $_SESSION["kayttaja_kieli"]); ?>
			</div><br>		
			
			<div style="display: inline; font-weight: bold;">
				<?php echo TILA; ?>: 
			</div>
			<div style="display: inline;">
				<?php echo koodin_selite($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi, $_SESSION["kayttaja_kieli"]); ?>
			</div><br>				

			<div style="display: inline; font-weight: bold;">
				<?php echo VERSIO; ?>: 
			</div>
			<div style="display: inline;">
				<?php echo $hakemusversioDTO->Versio; ?>
			</div><br>							
		
		<?php } ?>
		
	</div>

<div class="oikea_sisalto">

	<?php if($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken" && isset($hakemusversioDTO->Lukittu_toiselle_kayttajalle) && $hakemusversioDTO->Lukittu_toiselle_kayttajalle){ ?>
		<div class="oikea_sisalto_laatikko">
			<div style="text-align: center">
				<h3> <?php echo TARKASTELET_VAIN_LUKU; ?> </h3>
				<h3> <?php echo VOIT_MUOK; ?> <?php echo $hakemusversioDTO->KayttajaDTO_Muokkaaja->Etunimi . " " . $hakemusversioDTO->KayttajaDTO_Muokkaaja->Sukunimi; ?> <?php echo ON_LOPETTANUT; ?> </h3>
			</div>
		</div>
	<?php } ?>
	
	<?php include './ui/template/success_notification.php'; ?>	
	<?php include './ui/template/error_notification.php'; ?>
	
	<img id="loading_img" style="margin-right: auto; margin-left: auto; display: none; width: 200px; height: 200px;" src="static/images/loading.gif">
				
	<form enctype="multipart/form-data" id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
	
		<?php if ( ($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi!="hv_kesken") || (isset($hakemusversioDTO->Lukittu_toiselle_kayttajalle) && $hakemusversioDTO->Lukittu_toiselle_kayttajalle)) { ?>
			<?php $lomake_muokkaus_sallittu = false; ?>
			<fieldset disabled="disabled">
		<?php } else { $lomake_muokkaus_sallittu = true; } ?>
		
		<div class="oikea_sisalto_laatikko" style="display:<?php if(isset($hakemusversioDTO->Hakemuksen_tyyppi) && ($hakemusversioDTO->Hakemuksen_tyyppi=="tayd_hak" || $hakemusversioDTO->Hakemuksen_tyyppi=="muutos_hak") && $sivu=="hakemus_perustiedot"){ echo "block;"; } else { echo "none;"; } ?>;">
									
			<div class="paneeli_otsikko">
				<h2><?php echo HAK_TIEDOT; ?></h2>
			</div>
			
			<div class="paneelin_tiedot">
				<div class="tieto">
					<div class="kysymys">
						<?php echo HAKEMUS_TYYPPI; ?>
					</div>
					<?php echo koodin_selite($hakemusversioDTO->Hakemuksen_tyyppi, $_SESSION["kayttaja_kieli"]) . " (" . VERSIO . " " . $hakemusversioDTO->Versio . ")"; ?>
				</div>
				<div class="tieto" style="display:<?php if(isset($hakemusversioDTO->Hakemuksen_tyyppi) && $hakemusversioDTO->Hakemuksen_tyyppi=="muutos_hak"){ echo "block;"; } else { echo "none;"; } ?>;">
					<div class="kysymys">
						<?php echo MUUTOS_TYYPPI; ?>
					</div>
					<?php global $MUUTOSHAKEMUS_TYYPIT; foreach ($MUUTOSHAKEMUS_TYYPIT as $muut_hak_koodi => $muut_hak_kaannos) { ?>
						<label><input value="1" <?php if(isset($hakemusversioDTO->$muut_hak_koodi) && $hakemusversioDTO->$muut_hak_koodi==1) echo "checked"; ?> class="form_input muutoshakemus_tyyppi <?php if(isset($hakemusversioDTO->ID)){ echo $hakemusversioDTO->ID; } else { echo 0; } ?> <?php echo $muut_hak_koodi; ?>" type="checkbox"><?php echo $muut_hak_kaannos; ?></label><br>
					<?php } ?>
					<div id="Muun_muutoshakemuksen_tyypin_selite" style="margin-top: 10px; display: <?php if(isset($hakemusversioDTO->Muu_muutoshakemuksen_tyyppi) && $hakemusversioDTO->Muu_muutoshakemuksen_tyyppi==1){ echo "block;"; } else { echo "none;"; }?>" >
						<textarea class="form_input muutoshakemus_tyyppi <?php echo $hakemusversioDTO->ID; ?> Muun_muutoshakemuksen_tyypin_selite tieto_laatikko4"><?php if(isset($hakemusversioDTO->Muun_muutoshakemuksen_tyypin_selite)) echo htmlentities($hakemusversioDTO->Muun_muutoshakemuksen_tyypin_selite,ENT_COMPAT, "UTF-8"); ?></textarea>
					</div>
				</div>
			</div>
			
		</div>
		
		<?php if(isset($hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu) && !empty($hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu)){ ?>
		
			<?php if($sivu=="hakemus_viranomaiskohtaiset"){ // Osiot on indeksoitu organisaation tunnuksen perusteella
			
				foreach($hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu as $viranomaisen_koodi => $viranomaiskohtaiset_osiotDTO){ ?>				
																																
					<?php $parametrit = array();
					$parametrit["osiotDTO_puu"] = $viranomaiskohtaiset_osiotDTO;
					$parametrit["sivun_tunniste"] = $sivu;
					$parametrit["hakija_kayttaja_id"] = $hakija_kayttaja_id;
					$parametrit["hakemusversio"] = $hakemusversioDTO;
					$parametrit["luo_hakija"] = $luo_hakija;
					$parametrit["lomake_muokkaus_sallittu"] = $lomake_muokkaus_sallittu;
					$parametrit["asiakirjahallinta_liitteetDTO"] = $asiakirjahallinta_liitteetDTO;
							
					nayta_sivun_osiot($parametrit); ?>
														
				<?php } ?>
				
			<?php } else { ?>
			
				<?php 
				$parametrit = array();
				$parametrit["osiotDTO_puu"] = $hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu;
				$parametrit["sivun_tunniste"] = $sivu;
				$parametrit["hakija_kayttaja_id"] = $hakija_kayttaja_id;
				$parametrit["hakemusversio"] = $hakemusversioDTO;
				$parametrit["luo_hakija"] = $luo_hakija;
				$parametrit["lomake_muokkaus_sallittu"] = $lomake_muokkaus_sallittu;
				$parametrit["asiakirjahallinta_liitteetDTO"] = $asiakirjahallinta_liitteetDTO;
				$parametrit["sitoumuksetDTO"] = $sitoumuksetDTO;
				$parametrit["jarjestelman_hakijan_roolitDTO"] = $jarjestelman_hakijan_roolitDTO;
				$parametrit["kaikki_luvan_kohteet"] = $kaikki_luvan_kohteet;
				$parametrit["viranomaisten_luvan_kohteet"] = $viranomaisten_luvan_kohteet;
				$parametrit["taika_luvan_kohteet"] = $taika_luvan_kohteet;
				$parametrit["hakemuksen_viranomaiset"] = $hakemuksen_viranomaiset;
				$parametrit["nayta_poim_muuttujat_biopankit"] = $nayta_poim_muuttujat_biopankit;
				
				nayta_sivun_osiot($parametrit); 
				?>
				
			<?php } ?>
			
		<?php } else { ?>
			<h1 style="margin-top: 45px; text-align: center;"><?php echo "Sivu on tyhjä."; ?></h1>
		<?php } ?>
		
		<?php if ( ($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi!="hv_kesken") || (isset($hakemusversioDTO->Lukittu_toiselle_kayttajalle) && $hakemusversioDTO->Lukittu_toiselle_kayttajalle) ) { ?>
			</fieldset>
		<?php } ?>
		
	</form>
	
</div>

<?php
	include './ui/template/footer.php';
?>