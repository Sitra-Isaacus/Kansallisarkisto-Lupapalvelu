<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Metatiedot
 *
 * Created: 17.10.2017
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>

<p class="murupolku">
	<a href="index.php"><?php echo ETUSIVU; ?></a> > 
	<a href="hakemus.php?hakemusversio_id=<?php echo $hakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $hakemusversioDTO->TutkimusDTO->ID; ?>&sivu=hakemus_perustiedot"><?php echo HAKEMUS; ?></a> > 
	<?php echo tulosta_teksti($hakemusversioDTO->Tutkimuksen_nimi); ?> >  
	<a style="color: black;"> <?php echo METATIEDOT; ?> </a>
</p>

<?php include './ui/template/vasen_menu.php'; ?>

<div class="oikea_sisalto">
			
	<img id="loading_img" style="margin-right: auto; margin-left: auto; display: none; width: 250px; height: 250px;" src="static/images/loading.gif">
							
	<form enctype="multipart/form-data" id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
			
		<div class="oikea_sisalto_laatikko">
									
			<div class="paneeli_otsikko">
				<h3>
					<?php 
					if($metatiedot_kohde=="Hakemus") echo HAKEMUKSEN . " " . METATIEDOT_P;
					if($metatiedot_kohde=="Asia") echo ASIAN . " " . METATIEDOT_P;	
					if($metatiedot_kohde=="Paatos") echo PAATOKSEN . " " . METATIEDOT_P;	
					if($metatiedot_kohde=="Liite") echo LIITTEEN . " " . METATIEDOT_P;	
					if($metatiedot_kohde=="Lausunto") echo LAUSUNNON . " " . METATIEDOT_P;	
					?>
				</h3>
			</div>
			
			<div class="paneelin_tiedot">
			
				<?php if(isset($metatieto_kohdeDTO->Asiakirjatyyppi) && !is_null($metatieto_kohdeDTO->Asiakirjatyyppi)){ ?>
					<div class="tieto">
					
						<div class="kysymys">
							<?php echo ASIAKIRJATYYPPI; ?>
						</div>
						
						<?php echo htmlentities($metatieto_kohdeDTO->Asiakirjatyyppi,ENT_COMPAT, "UTF-8"); ?>
					
					</div>			
				<?php } ?>
			
				<div class="tieto">
				
					<div class="kysymys">
						<?php echo JULKISUUSLUOKKA; ?>
					</div>
					
					<textarea class="form_input <?php echo $metatiedot_kohde; ?> <?php if(isset($metatieto_kohdeDTO->ID)){ echo $metatieto_kohdeDTO->ID; } else { echo 0; } ?> Julkisuusluokka tieto_laatikko4"><?php echo htmlentities($metatieto_kohdeDTO->Julkisuusluokka,ENT_COMPAT, "UTF-8"); ?></textarea>
				
				</div>
				
				<div class="tieto">
				
					<div class="kysymys">
						<?php echo SALASSAPITOAIKA; ?>
					</div>
					
					<textarea class="form_input <?php echo $metatiedot_kohde; ?> <?php if(isset($metatieto_kohdeDTO->ID)){ echo $metatieto_kohdeDTO->ID; } else { echo 0; } ?> Salassapitoaika tieto_laatikko4"><?php echo htmlentities($metatieto_kohdeDTO->Salassapitoaika,ENT_COMPAT, "UTF-8"); ?></textarea>
				
				</div>		

				<div class="tieto">
				
					<div class="kysymys">
						<?php echo SALASSAPITOPERUSTE; ?>
					</div>
					
					<textarea class="form_input <?php echo $metatiedot_kohde; ?> <?php if(isset($metatieto_kohdeDTO->ID)){ echo $metatieto_kohdeDTO->ID; } else { echo 0; } ?> Salassapitoperuste tieto_laatikko4"><?php echo htmlentities($metatieto_kohdeDTO->Salassapitoperuste,ENT_COMPAT, "UTF-8"); ?></textarea>
				
				</div>	

				<div class="tieto">
				
					<div class="kysymys">
						<?php echo SUOJAUSTASO; ?>
					</div>
					
					<textarea class="form_input <?php echo $metatiedot_kohde; ?> <?php if(isset($metatieto_kohdeDTO->ID)){ echo $metatieto_kohdeDTO->ID; } else { echo 0; } ?> Suojaustaso tieto_laatikko4"><?php echo htmlentities($metatieto_kohdeDTO->Suojaustaso,ENT_COMPAT, "UTF-8"); ?></textarea>
				
				</div>

				<div class="tieto">
				
					<div class="kysymys">
						<?php echo HENKILOTIETOJA; ?>
					</div>
					
					<textarea class="form_input <?php echo $metatiedot_kohde; ?> <?php if(isset($metatieto_kohdeDTO->ID)){ echo $metatieto_kohdeDTO->ID; } else { echo 0; } ?> Henkilotietoja tieto_laatikko4"><?php echo htmlentities($metatieto_kohdeDTO->Henkilotietoja,ENT_COMPAT, "UTF-8"); ?></textarea>
				
				</div>

				<div class="tieto">
				
					<div class="kysymys">
						<?php echo SAILYTYSAJAN_PITUUS; ?>
					</div>
					
					<textarea class="form_input <?php echo $metatiedot_kohde; ?> <?php if(isset($metatieto_kohdeDTO->ID)){ echo $metatieto_kohdeDTO->ID; } else { echo 0; } ?> Sailytysajan_pituus tieto_laatikko4"><?php echo htmlentities($metatieto_kohdeDTO->Sailytysajan_pituus,ENT_COMPAT, "UTF-8"); ?></textarea>
				
				</div>	

				<div class="tieto">
				
					<div class="kysymys">
						<?php echo SAILYTYSAJAN_PERUSTE; ?>
					</div>
					
					<textarea class="form_input <?php echo $metatiedot_kohde; ?> <?php if(isset($metatieto_kohdeDTO->ID)){ echo $metatieto_kohdeDTO->ID; } else { echo 0; } ?> Sailytysajan_peruste tieto_laatikko4"><?php echo htmlentities($metatieto_kohdeDTO->Sailytysajan_peruste,ENT_COMPAT, "UTF-8"); ?></textarea>
				
				</div>					

			</div>
			
		</div>
						
	</form>
	
</div>

<?php
	include './ui/template/footer.php';
?>