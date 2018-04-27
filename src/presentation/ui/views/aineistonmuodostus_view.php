<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the aineistonmuodostus page (viranomaisen käyttöliittymä)
 *
 * Created: 26.10.2017
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>

<p class="murupolku"><a style="color: #6EA9C2; text-decoration: none;" href="index.php"><?php echo ETUSIVU; ?></a> > <a style="color: #6EA9C2; text-decoration: none;" href="hakemus.php?hakemusversio_id=<?php echo $hakemusDTO->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID; ?>&sivu=hakemus_perustiedot"><?php echo HAKEMUS; ?></a> > <?php echo tulosta_teksti($hakemusDTO->HakemusversioDTO->Tutkimuksen_nimi); ?>  > <?php echo AINEISTON_MUODOSTUS; ?> </a> </p>

<?php include './ui/template/vasen_menu.php'; ?>

<div class="oikea_sisalto">
	
	<form enctype="multipart/form-data" class="form_ainmuod" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
	
		<?php if ($aineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi != "aint_kas") { ?>
			<fieldset disabled="disabled">
		<?php } ?>
		
		<div class="oikea_sisalto_laatikko">

			<div class="paneeli_otsikko">
				<h2><?php echo AINEISTOTILAUS; ?></h2>
			</div>
			
			<div class="paneelin_tiedot">
			
				<div class="tieto">
				
					<div class="kysymys">
						<?php echo TILAAJA; ?>
					</div>
									
					<?php echo $paatosDTO->AineistotilausDTO->KayttajaDTO_Aineiston_tilaaja->Etunimi . " " . $paatosDTO->AineistotilausDTO->KayttajaDTO_Aineiston_tilaaja->Sukunimi; ?><br>
					<?php echo $paatosDTO->AineistotilausDTO->KayttajaDTO_Aineiston_tilaaja->Sahkopostiosoite; ?><br>
					<?php echo $paatosDTO->AineistotilausDTO->KayttajaDTO_Aineiston_tilaaja->Puhelinnumero; ?>
								
				</div>
				
				<?php if(!is_null($paatosDTO->AineistotilausDTO->Aineistonmuodostusprosessi_teksti)){ ?>
					<div class="tieto">
					
						<div class="kysymys">
							<?php echo KUVAUS_AINMUOD; ?>
						</div>
											
						<?php echo htmlentities($paatosDTO->AineistotilausDTO->Aineistonmuodostusprosessi_teksti,ENT_COMPAT, "UTF-8"); ?>
										
					</div>
				<?php } ?>
				
			</div>		
		
		</div>
		
		<div class="oikea_sisalto_laatikko">
		
			<div class="paneeli_otsikko">
				<h2><?php echo AIN_MUOD_ETEN; ?></h2>
			</div>		

			<div class="paneelin_tiedot">
			
				<div class="tieto">
				
					<div class="kysymys">
						<?php echo AIN_MUOD_INFO; ?>
					</div>
					
					<table class="aineistonmuodostus_table">
						<tr>
							<td>
								<?php echo KASITTELIJA; ?>
							</td>
							<td>
								<?php echo $paatosDTO->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja->Etunimi . " " . $paatosDTO->AineistotilausDTO->KayttajaDTO_Aineistonmuodostaja->Sukunimi; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo AINEISTO_LAHETETTY; ?>
							</td>
							<td>
								<?php if($aineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi == "aint_kas"){ ?>
									<input name="aineisto_lahetetty" value="<?php if(isset($paatosDTO->AineistotilausDTO->Aineisto_lahetetty)){ echo muotoilepvm2($paatosDTO->AineistotilausDTO->Aineisto_lahetetty,  'fi'); } ?>" class="aika_laatikko" type="text" />
								<?php } else { ?>
									<?php if(isset($paatosDTO->AineistotilausDTO->Aineisto_lahetetty)){ echo muotoilepvm2($paatosDTO->AineistotilausDTO->Aineisto_lahetetty,  'fi'); } ?>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo AINEISTONMUODOSTUKSEN_HINTA; ?>
							</td>
							<td>
								<?php if($aineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi == "aint_kas"){ ?>
									<input name="aineistonmuodostuksen_hinta" style="height:25px; width: 135px;" value="<?php if(isset($paatosDTO->AineistotilausDTO->Aineistonmuodostuksen_hinta)){ echo htmlentities($paatosDTO->AineistotilausDTO->Aineistonmuodostuksen_hinta,ENT_COMPAT, "UTF-8"); } ?>" type="number" />
								<?php } else { ?>
									<?php if(isset($paatosDTO->AineistotilausDTO->Aineistonmuodostuksen_hinta)){ echo htmlentities($paatosDTO->AineistotilausDTO->Aineistonmuodostuksen_hinta,ENT_COMPAT, "UTF-8"); } ?>
								<?php } ?>								
							</td>
						</tr>						
					</table>
				
				</div>
			
				<input type="hidden" name="hakemus_id" value="<?php echo $hakemus_id; ?>" >
				
				<?php if($aineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi == "aint_kas"){ ?>
					<input onclick="return confirm('<?php echo AIN_KUITT_VARM; ?>');" name="tallenna_aineistonmuodostus" type="submit" class="nappi" value="<?php echo KUITTAA_AIN_LAH; ?>" >
				<?php } ?>
				
			</div>
								
		</div>
		
		<?php if ($aineistotilausDTO->Aineistotilauksen_tilaDTO->Aineistotilauksen_tilan_koodi != "aint_kas") { ?>
			</fieldset>
		<?php } ?>
		
	</form>
	
</div>

<?php
	include './ui/template/footer.php';
?>