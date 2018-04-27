<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the viestit page (tutkijan käyttöliittymä)
 *
 * Created: 12.1.2016
 */

include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>

<div class="laatikko10">
	<h1><?php echo VIESTIT; ?> (<?php echo $vastaus["HakemusDTO"]->HakemusversioDTO->Tutkimuksen_nimi; ?>)</h1>
</div>

<?php if(!empty($viestit)){ ?>

	<div class="laatikko">
		
		<div class="paneeli_otsikko">
			<h3><?php echo HAKEMUKSEN_VIESTIT; ?></h3>
		</div>
		
		<div class="paneelin_tiedot">
		
			<?php for($i=0; $i < sizeof($viestit); $i++){ ?>
				<form enctype="multipart/form-data" class="form_vastaus" name="laheta_vastaus" id="form_vastaus_<?php echo $viestit[$i]->ID; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
					<table class="viestit">
						<tr>
							<td><div class="viesti_vasen"><b><?php echo HAKEMUS; ?>: </b></div></td>
							<td><?php echo $viestit[$i]->HakemusDTO->Hakemuksen_tunnus; ?></td>
						</tr>
						<tr>
							<td><div class="viesti_vasen"><b><?php echo VIESTIN_LAHETTAJA; ?>: </b></div></td>
							<td><?php echo $viestit[$i]->KayttajaDTO_Lahettaja->Etunimi . " "; ?> <?php echo $viestit[$i]->KayttajaDTO_Lahettaja->Sukunimi; ?> ( <?php echo koodin_selite($viestit[$i]->KayttajaDTO_Lahettaja->Viranomaisen_rooliDTO->Viranomaisen_koodi, $_SESSION["kayttaja_kieli"]); ?> )</td>
						</tr>
						<tr>
							<td><div class="viesti_vasen"><b><?php echo VIESTIN_VASTAANOTTAJA; ?>: </b></div></td>
							<td><?php echo $viestit[$i]->KayttajaDTO_Vastaanottaja->Etunimi . " "; ?> <?php echo $viestit[$i]->KayttajaDTO_Vastaanottaja->Sukunimi; ?> </td>
						</tr>
						<tr>
							<td><div class="viesti_vasen"><b><?php echo PVM; ?>: </b></div></td>
							<td><?php echo muotoilepvm($viestit[$i]->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
						</tr>
						<tr>
							<td><div class="viesti_vasen"><b><?php echo VIESTI; ?>: </b></div></td>
							<td><?php echo nl2br(htmlentities($viestit[$i]->Viesti, ENT_COMPAT, "UTF-8")); ?></td>
						</tr>
						<?php if($kayt_id==$viestit[$i]->KayttajaDTO_Vastaanottaja->ID && is_null($viestit[$i]->ViestitDTO_Child->ID)){ ?>
							<tr>
								<td><div class="viesti_vasen"></div></td>
								<td>
								
									<div class="onclick_input" id="viesti-<?php echo $viestit[$i]->ID; ?>"><input id='nappi_vastaa_<?php echo $viestit[$i]->ID; ?>' type='button' class='nappi_vastaa' value='<?php echo VASTAA; ?>' /></div>
									<textarea class="vastaus_fieldi" id="vastaus_kentta_<?php echo $viestit[$i]->ID; ?>" name="vastaus" form="form_vastaus_<?php echo $viestit[$i]->ID; ?>"></textarea>
									<textarea id="viestin_parent_<?php echo $i; ?>" name="parent_id" style="display:none" form="form_vastaus_<?php echo $viestit[$i]->ID; ?>"><?php echo htmlentities($viestit[$i]->ID, ENT_COMPAT, "UTF-8"); ?></textarea>
									<textarea id="vastaanottaja_<?php echo $i; ?>" name="vastaanottaja" style="display:none" form="form_vastaus_<?php echo $viestit[$i]->ID; ?>"><?php echo htmlentities($viestit[$i]->KayttajaDTO_Lahettaja->ID, ENT_COMPAT, "UTF-8"); ?></textarea>
									
									<?php if(isset($viestit[$i]->Taydennettavaa_hakemukseen) && $viestit[$i]->Taydennettavaa_hakemukseen==1){ ?>
										<div id="liite_kentta_<?php echo $viestit[$i]->ID; ?>" style="display: none; margin-top: 25px; margin-bottom: 25px;">
											<p style="font-weight: bold;"><?php echo TAYDASK; ?></p>										
											<p><input type="file" name="taydennysasiakirjat[]" multiple ></p>												
										</div>
									<?php } ?>
																																	
									<input name="laheta_vastaus" id='nappi_laheta_vastaus_<?php echo $viestit[$i]->ID; ?>' type='submit' class='nappi_vastaa' style="display:none" value='<?php echo LAHETA_VASTAUS; ?>' />
									<textarea id="hakemus_<?php echo $i; ?>" name="hakemus_id" style="display:none" form="form_vastaus_<?php echo $viestit[$i]->ID; ?>"><?php echo htmlentities($viestit[$i]->HakemusDTO->ID, ENT_COMPAT, "UTF-8"); ?></textarea>
								
								</td>
							</tr>
						<?php } ?>
					</table>
				</form>
				
				<?php if(!is_null($viestit[$i]->ViestitDTO_Child->ID)){ ?>
					<?php for($c=0; $c < sizeof($viestit[$i]->ViestitDTO_Vastaukset); $c++){ ?>
						<form enctype="multipart/form-data" name="laheta_vastaus" id="form_vastaus_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
							<table>
								<tr>
									<td><div class="reply"><?php if($c==sizeof($viestit[$i]->ViestitDTO_Vastaukset)-1){ ?><div class="viesti_reply"></div><br><img src="static/images/arrow.png" width="40" height="40"><?php } ?></div></td>
									<td>
										<table class="vastaukset">
											<tr>
												<td><div class="viesti_vasen"><b><?php echo VASTAUKSEN_LAHETTAJA; ?>: </b></div></td>
												<td><?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->KayttajaDTO_Lahettaja->Etunimi . " " . $viestit[$i]->ViestitDTO_Vastaukset[$c]->KayttajaDTO_Lahettaja->Sukunimi; ?></td>
											</tr>
											<tr>
												<td><div class="viesti_vasen"><b><?php echo PVM; ?>: </b></div></td>
												<td><?php echo muotoilepvm($viestit[$i]->ViestitDTO_Vastaukset[$c]->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
											</tr>
											<tr>
												<td><div class="viesti_vasen"><b><?php echo VASTAUS; ?>: </b></div></td>
												<td><?php echo nl2br(htmlentities($viestit[$i]->ViestitDTO_Vastaukset[$c]->Viesti, ENT_COMPAT, "UTF-8")); ?></td>
											</tr>
											<?php if($kayt_id==$viestit[$i]->ViestitDTO_Vastaukset[$c]->KayttajaDTO_Vastaanottaja->ID && is_null($viestit[$i]->ViestitDTO_Vastaukset[$c]->ViestitDTO_Child->ID)){ ?>
												<tr>
													<td><div class="viesti_vasen"></div></td>
													<td>
														<div class="onclick_input" id="viesti-<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>"><input id='nappi_vastaa_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>' type='button' class='nappi_vastaa' value='<?php echo VASTAA; ?>' /></div>
															<textarea class="vastaus_fieldi" id="vastaus_kentta_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>" name="vastaus" form="form_vastaus_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>"></textarea>
															<textarea id="viestin_parent_<?php echo $i; ?>_<?php echo $c; ?>" name="parent_id" style="display:none" form="form_vastaus_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>"><?php echo htmlentities($viestit[$i]->ViestitDTO_Vastaukset[$c]->ID, ENT_COMPAT, "UTF-8"); ?></textarea>
															<textarea id="vastaanottaja_<?php echo $i; ?>_<?php echo $c; ?>" name="vastaanottaja" style="display:none" form="form_vastaus_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>"><?php echo htmlentities($viestit[$i]->ViestitDTO_Vastaukset[$c]->KayttajaDTO_Lahettaja->ID, ENT_COMPAT, "UTF-8"); ?></textarea>
															<textarea id="hakemus_<?php echo $i; ?>_<?php echo $c; ?>" name="hakemus_id" style="display:none" form="form_vastaus_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>"><?php echo htmlentities($viestit[$i]->ViestitDTO_Vastaukset[$c]->HakemusDTO->ID, ENT_COMPAT, "UTF-8"); ?></textarea>
														</td>
														<tr>
															<td><div class="viesti_vasen"></div></td>
															<td><input name="laheta_vastaus" id='nappi_laheta_vastaus_<?php echo $viestit[$i]->ViestitDTO_Vastaukset[$c]->ID; ?>' type='submit' class='nappi_vastaa' style="display:none" value='<?php echo LAHETA_VASTAUS; ?>' />	</td>
														</tr>
													</tr>
												<?php } ?>
											</table>
											</td>
											</tr>
										</table>
									</form>
								<?php } ?>
							<?php } ?>
							<br>
							<?php if(isset($viestit[$i+1])){ ?><div class="borderi"></div> <?php } ?>
							<br>
			<?php } ?>
			
		</div>
	</div>
<?php } ?>
	<?php if(empty($viestit)){ ?>
		<div class="laatikko9">
			<div class="paneeli_otsikko">
				<?php echo EI_VIESTEJA_HAKEMUS; ?>
			</div>
		</div>
	<?php } ?>
	<br><br><br><br>
<?php
	include './ui/template/footer.php';
?>