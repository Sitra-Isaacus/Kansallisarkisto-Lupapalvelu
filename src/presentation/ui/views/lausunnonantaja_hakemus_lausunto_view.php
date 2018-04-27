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
	
	<?php if(isset($lausuntopyynto) && !empty($lausuntopyynto)) { ?>
	
		<div class="oikea_sisalto_laatikko">
		
			<div class="paneeli_otsikko">
				<h2><?php echo LAUSUNNOT; ?></h2>
			</div>
		
			<table class="taulu" style="table-layout: fixed; margin-top: 25px; width: 100%;">
				<thead>
					<tr style="padding-bottom: 5px;">
						<th style="width:10%;">
							<b><?php echo PVM; ?></b>
						</th>
						<th style="width:20%;">
							<b><?php echo LAUSUNNON_PYYTAJA; ?></b>
						</th>
						<th style="width:25%;">
							<b><?php echo LAUSUNTOPYYNTO; ?></b>
						</th>
						<th style="width:10%;">
							<b><?php echo MAARAPAIVA; ?></b>
						</th>
						<th style="width:10%;">
							<b><?php echo LAUSUNTO; ?></b>
						</th>	
						<th style="width:10%;">
							<b><?php echo LIITTEET; ?></b>
						</th>
					</tr>
				</thead>
								
				<?php for($i=0; $i < sizeof($lausuntopyynto); $i++){  ?>
					<tbody class="tbody_keskeneraiset">
						<tr style="padding-bottom: 1em;">
							<td style="width:10%;">
								<?php echo muotoilepvm($lausuntopyynto[$i]->Lisayspvm, $_SESSION["kayttaja_kieli"]); ?>
							</td>
							<td style="width:20%;">
								<?php echo $lausuntopyynto[$i]->KayttajaDTO_Pyytaja->Etunimi . " " . $lausuntopyynto[$i]->KayttajaDTO_Pyytaja->Sukunimi . " / " . koodin_selite($lausuntopyynto[$i]->KayttajaDTO_Pyytaja->Viranomaisen_rooliDTO->Viranomaisen_koodi, $_SESSION["kayttaja_kieli"]); ?>
							</td>
							<td style="width:25%;">
								<?php echo nl2br(htmlentities($lausuntopyynto[$i]->Pyynto, ENT_COMPAT, "UTF-8")); ?>
							</td>
							<td style="width:10%;">
								<?php echo muotoilepvm($lausuntopyynto[$i]->Lausunnon_maarapaiva, $_SESSION["kayttaja_kieli"]); ?>
							</td>
							<td style="width:10%;">
								<?php if(isset($lausuntopyynto[$i]->LausuntoDTO->Lausunto_julkaistu) && $lausuntopyynto[$i]->LausuntoDTO->Lausunto_julkaistu==1){ ?>
									<a href="lausunto_pdf.php?lausunto_id=<?php echo $lausuntopyynto[$i]->LausuntoDTO->ID; ?>&hakemus_id=<?php echo $hakemus_id; ?>">
										<img width="60" height="60" src="static/images/pdf_download.png">
									</a>						
								<?php } else { ?>
									<a href="lausunto.php?hakemus_id=<?php echo $hakemus_id; ?>&lausunto_id=<?php echo $lausuntopyynto[$i]->LausuntoDTO->ID; ?>"><?php echo ANNA_LAUSUNTO; ?></a>
								<?php } ?>						
							</td>
							<td style="width:10%;">
								<?php if(isset($lausuntopyynto[$i]->LausuntoDTO->Lausunnon_liitteetDTO) && !empty($lausuntopyynto[$i]->LausuntoDTO->Lausunnon_liitteetDTO)){ ?>
									<?php for($j=0; $j < sizeof($lausuntopyynto[$i]->LausuntoDTO->Lausunnon_liitteetDTO); $j++){ ?>
										<p><a href="liitetiedosto.php?avaa=<?php echo $lausuntopyynto[$i]->LausuntoDTO->Lausunnon_liitteetDTO[$j]->LiiteDTO->ID; ?>" target="_blank">											
											<?php echo LIITE . " " . ($j+1); ?>
										</a></p>
									<?php } ?>
								<?php } ?>
							</td>							
						</tr>
					</tbody>
				<?php } ?>
				
				
			</table>
	
		</div>
		
	<?php } ?>
	
	<?php if(empty($annetut_lausunnot) && empty($lausuntopyynto)){ ?>
		<div class="laatikko9">
			<div class="paneeli_otsikko">
				<h3><?php echo EI_LAUS_PYYNTOJA; ?></h3>
			</div>
		</div>
	<?php } ?>
	
</div>
<?php
	include './ui/template/footer.php';
?>