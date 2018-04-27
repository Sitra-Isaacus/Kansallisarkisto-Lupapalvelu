<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the lausunto page (lausunnonantajan käyttöliittymä)
 *
 * Created: 28.1.2016
 */
 
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>

<p class="murupolku"><a style="color: #6EA9C2; text-decoration: none;" href="index.php"><?php echo ETUSIVU; ?></a> > <a style="color: #6EA9C2; text-decoration: none;" href="hakemus.php?hakemusversio_id=<?php echo $hakemusDTO->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $hakemusDTO->HakemusversioDTO->TutkimusDTO->ID; ?>&sivu=hakemus_perustiedot"><?php echo HAKEMUS; ?></a> > <?php echo tulosta_teksti($hakemusDTO->HakemusversioDTO->Tutkimuksen_nimi); ?>  > <?php echo LAUSUNNOT; ?> </a> </p>

<?php include './ui/template/vasen_menu.php'; ?>

<div class="oikea_sisalto">
	
	<form enctype="multipart/form-data" id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
	
		<?php if ($lausuntoDTO->Lausunto_julkaistu==1 ){ ?>
			<?php $lomake_muokkaus_sallittu = false; ?>
			<fieldset disabled="disabled">
		<?php } else { $lomake_muokkaus_sallittu = true; } ?>
		
		<?php if ($lausuntoDTO->Lausunto_julkaistu==0){ ?>
			<div class="oikea_sisalto_laatikko">
				<div class="paneeli_otsikko">
					<h2><?php echo VAL_LAUSUNTOPOHJA; ?></h2>
				</div>
				<div class="paneelin_tiedot">
					<div class="tieto">
						<select class="form_input lausunto <?php echo $lausuntoDTO->ID; ?> FK_Lomake">
							<option value="0"><?php echo TYHJA; ?></option>
							<?php for($i=0; $i < sizeof($lomakkeetDTO_lausunto); $i++){ ?>
								<option value="<?php echo $lomakkeetDTO_lausunto[$i]->ID; ?>" <?php if($lomakkeetDTO_lausunto[$i]->ID==$lausuntoDTO->LomakeDTO->ID) echo "selected"; ?> ><?php echo $lomakkeetDTO_lausunto[$i]->Nimi; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
		<?php } ?>
		
		<?php if(isset($lausuntoDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu)){ ?>
			<?php 			
				$parametrit = array();
				$parametrit["osiotDTO_puu"] = $lausuntoDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_puu;
				$parametrit["sivun_tunniste"] = $sivu;
				$parametrit["hakija_kayttaja_id"] = null;
				$parametrit["hakemusversio"] = null;
				$parametrit["luo_hakija"] = null;
				$parametrit["lomake_muokkaus_sallittu"] = $lomake_muokkaus_sallittu;			
			
				nayta_sivun_osiot($parametrit);
			?>
		<?php } ?>
		
		<div class="oikea_sisalto_laatikko">
		
			<div class="paneeli_otsikko">
				<h2><?php echo LIITTEET; ?></h2>
			</div>		
							
			<div class="paneelin_tiedot">
			
				<div class="tieto" style="display: <?php if(isset($lausuntoDTO->Lausunnon_liitteetDTO) && !empty($lausuntoDTO->Lausunnon_liitteetDTO)){ echo "block;"; } else { echo "none;"; } ?>;">
				
					<table class="taulu">
						
						<thead>
							<tr>
								<th align="left"><?php echo PVM; ?></th>
								<th align="left"><?php echo LIITE; ?></th>								
								<?php if ($_SESSION["kayttaja_rooli"] && $lausuntoDTO->Lausunto_julkaistu==0) { ?>
									<th align="left"><?php echo POISTA_LIITE; ?></th>
								<?php } ?>
							</tr>
						</thead>

						<?php for($i=0; $i < sizeof($lausuntoDTO->Lausunnon_liitteetDTO); $i++){ ?>
							<tbody>
								<tr>
									<td><?php echo muotoilepvm($lausuntoDTO->Lausunnon_liitteetDTO[$i]->LiiteDTO->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?></td>
									<td>
										<a id="<?php echo $lausuntoDTO->Lausunnon_liitteetDTO[$i]->LiiteDTO->ID; ?>" class="liitetiedosto_linkki" href="liitetiedosto.php?avaa=<?php echo $lausuntoDTO->Lausunnon_liitteetDTO[$i]->LiiteDTO->ID; ?>" target="_blank"><?php echo $lausuntoDTO->Lausunnon_liitteetDTO[$i]->LiiteDTO->Liitetiedosto_nimi; ?></a>										
									</td>		
									<?php if ($_SESSION["kayttaja_rooli"]=="rooli_lausunnonantaja" && $lausuntoDTO->Lausunto_julkaistu==0) { ?>
										<td><a onclick="return confirm('<?php echo LIITE_POISTO_VARMISTUS; ?>');" href="lausunto.php?liite_id=<?php echo $lausuntoDTO->Lausunnon_liitteetDTO[$i]->LiiteDTO->ID; ?>&poista_liite=1&lausunto_id=<?php echo $lausuntoDTO->ID; ?>&hakemus_id=<?php echo $hakemusDTO->ID; ?>" class="ei_alleviivausta"><img src="static/images/erase.png" alt="Poista liitetiedosto" width="16" height="16"></a></td>
									<?php } ?>
								</tr>
							</tbody>
						<?php } ?>
							
					</table>					
				
				</div>
					
				<?php if ($_SESSION["kayttaja_rooli"]=="rooli_lausunnonantaja" && $lausuntoDTO->Lausunto_julkaistu==0){ ?>	
					<div class="tieto">
						
						<div class="kentta_otsikko">	
							<?php echo LISAA_LIITE; ?>		
						</div>
														
						<input type="file" name="lisaa_liite" id="lisaa_liite">
						<input type="hidden" name="lausunto_id" value="<?php echo $lausuntoDTO->ID; ?>">
						<input type="submit" name="lisaa_liite_asiakirja" value="<?php echo LIITA; ?>">
														
					</div>
				<?php } ?>
			
			</div>
			
		</div>
				
		<div class="oikea_sisalto_laatikko">
		
			<div class="paneeli_otsikko">
				<h2><?php echo JOHTOPAATOS; ?></h2>
			</div>
			
			<div class="paneelin_tiedot">
			
				<div class="tieto">
					
					<?php if($lausuntoDTO->Lausunto_julkaistu==0){ ?>
					
					<input <?php if(isset($lausuntoDTO->Lausunto_koodi) && $lausuntoDTO->Lausunto_koodi=="laus_kylla") echo "checked"; ?> name="johtopaatos" id="laus_kylla" class="form_input lausunto <?php echo $lausuntoDTO->ID; ?> Lausunto_koodi" type="radio" value="laus_kylla" />
					<label for="laus_kylla"><?php echo laus_kylla; ?></label><br>
					
					<input <?php if(isset($lausuntoDTO->Lausunto_koodi) && $lausuntoDTO->Lausunto_koodi=="laus_ehto") echo "checked"; ?> name="johtopaatos" id="laus_ehto" class="form_input lausunto <?php echo $lausuntoDTO->ID; ?> Lausunto_koodi" type="radio" value="laus_ehto" />
					<label for="laus_ehto"><?php echo laus_ehto; ?></label><br>
					
					<div style="display: <?php if(isset($lausuntoDTO->Lausunto_koodi) && $lausuntoDTO->Lausunto_koodi=="laus_ehto"){ echo "block;"; } else { echo "none;"; } ?>" id="ehdollinen_puoltaminen">
						<br><textarea class="form_input lausunto <?php echo $lausuntoDTO->ID; ?> Ehdollinen_puoltaminen tieto_laatikko3"><?php if(isset($lausuntoDTO->Ehdollinen_puoltaminen)){ echo htmlentities($lausuntoDTO->Ehdollinen_puoltaminen,ENT_COMPAT, "UTF-8"); } ?></textarea><br><br>
					</div>
					
					<input <?php if(isset($lausuntoDTO->Lausunto_koodi) && $lausuntoDTO->Lausunto_koodi=="laus_ei") echo "checked"; ?> name="johtopaatos" id="laus_ei" class="form_input lausunto <?php echo $lausuntoDTO->ID; ?> Lausunto_koodi" type="radio" value="laus_ei" />
					<label for="laus_ei"><?php echo laus_ei; ?></label><br>
					
					<div style="display: <?php if(isset($lausuntoDTO->Lausunto_koodi) && $lausuntoDTO->Lausunto_koodi=="laus_ei"){ echo "block;"; } else { echo "none;"; } ?>" id="johtopaatoksen_perustelut">
						<br><?php echo PERUSTELUT; ?><br>
						<textarea class="form_input lausunto <?php echo $lausuntoDTO->ID; ?> Johtopaatoksen_perustelut tieto_laatikko3"><?php if(isset($lausuntoDTO->Johtopaatoksen_perustelut)){ echo htmlentities($lausuntoDTO->Johtopaatoksen_perustelut,ENT_COMPAT, "UTF-8"); } ?></textarea><br><br>
					</div>
					
					<?php } else { ?>
					
						<?php echo koodin_selite($lausuntoDTO->Lausunto_koodi, $_SESSION["kayttaja_kieli"]); ?>
						<br><br>
						<?php if($lausuntoDTO->Lausunto_koodi=="laus_ehto"){ echo htmlentities($lausuntoDTO->Ehdollinen_puoltaminen,ENT_COMPAT, "UTF-8"); } ?>
					
					<?php } ?>
					
				</div>
				
			</div>
			
		</div>
		
		<?php if($_SESSION["kayttaja_rooli"]=="rooli_lausunnonantaja" && $lausuntoDTO->Lausunto_julkaistu==0){ ?>
			<input onclick="return confirm('<?php echo LAH_LAUS_VARM; ?>');" name="laheta_lausunto" type="submit" class="nappi2" value='<?php echo LAHETA_LAUSUNTO2; ?>' />
		<?php } ?>
		<?php if($_SESSION["kayttaja_rooli"]=="rooli_lausunnonantaja"){ ?>
			<a style="color: #6EA9C2; text-decoration: none; font-size: large;" href="lausunnonantaja_hakemus_lausunto.php?hakemus_id=<?php echo $hakemus_id; ?>">&#8592; <?php echo SEL_LAUSPYYNT; ?></a>
		<?php } ?>
		<?php if($_SESSION["kayttaja_rooli"]=="rooli_paattava" || $_SESSION["kayttaja_rooli"]=="rooli_kasitteleva"){ ?>
			<a style="color: #6EA9C2; text-decoration: none; font-size: large;" href="viranomainen_hakemus_lausunto.php?hakemus_id=<?php echo $hakemus_id; ?>">&#8592; <?php echo SEL_LAUSPYYNT; ?></a>
		<?php } ?>
		
		<input name="hakemus_id" type="hidden" value="<?php echo $hakemus_id; ?>" />		
		<input name="lausunto_id" type="hidden" value="<?php echo $lausunto_id; ?>" />
		
		<?php if ($lausuntoDTO->Lausunto_julkaistu==1 ){ ?>
			</fieldset>
		<?php } ?>
		
	</form>
	
</div>
<?php
	include './ui/template/footer.php';
?>