<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Aineistotilaus
 *
 * Created: 24.10.2017
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>

<?php if(!empty($hakemuksetDTO_aineistopyynto)){ ?>

	<h2 class="independent"><?php echo AINEISTOTILAUS; ?></h2>

	<form enctype="multipart/form-data" id="form" action="" method="POST">

		<div class="laatikko">

				<div class="paneeli_otsikko">
					<h3><?php echo LAHETA_AINEISTOPYYNTO; ?></h3>
				</div>
				
				<div class="paneelin_tiedot">
				
					<p><?php echo LAHETA_AINEISTOPYYNTO_INFO1; ?></p>
					<textarea name="aineiston_muodostus_kuvaus"></textarea>
					<p><?php echo LAHETA_AINEISTOPYYNTO_INFO2; ?></p>
					
					<table>
					
						<tr>
							<th></th>
							<th><?php echo HAKEMUS; ?></th>
							<th><?php echo KAYTTOLUPA_PAATTYY; ?></th>
						</tr>
						
						<?php for($i=0; $i < sizeof($hakemuksetDTO_aineistopyynto); $i++){ ?>
							<tr>
								<td style="width: 50px;">
									<input checked type="checkbox" name="fk_aineistotilaus[]" value="<?php echo $hakemuksetDTO_aineistopyynto[$i]->PaatosDTO->AineistotilausDTO->ID; ?>">
								</td>
								<td style="width: 200px;">								
									<a href="hakemus.php?hakemusversio_id=<?php echo $hakemuksetDTO_aineistopyynto[$i]->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $hakemuksetDTO_aineistopyynto[$i]->HakemusversioDTO->TutkimusDTO->ID; ?>"><?php echo $hakemuksetDTO_aineistopyynto[$i]->Hakemuksen_tunnus; ?></a>								
								</td>
								<td>
									<?php echo muotoilepvm2($hakemuksetDTO_aineistopyynto[$i]->PaatosDTO->Lakkaamispvm, $_SESSION["kayttaja_kieli"]); ?>
								</td>
							</tr>
						<?php } ?>
					
					</table>
					
					<p><?php echo LAHETA_AINEISTOPYYNTO_INFO3; ?></p>
					
					<p>					
						<input onclick="return confirm('<?php echo LAH_AINP_VARM; ?>');" class="nappi" type="submit" value="<?php echo LAHETA_AINEISTOPYYNTO; ?>" name="laheta_aineistopyynto" />
					</p>				
					
				</div>
					
		</div>
	
	</form>
	
<?php } else { ?>

	<div class="laatikko10">

		<h1><?php echo AINEISTOTILAUS; ?></h1>
		<p>
			<?php echo EI_TIL_AIN; ?><br>
		</p>	
	
	</div>
	
<?php } ?>	
	
<?php
include './ui/template/footer.php';
?>