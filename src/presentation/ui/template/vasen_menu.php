<div class="vasen_menu" id="menu_fix">

	<div class="vasen_sisa">
	
		<?php if($_SESSION["kayttaja_rooli"]=="rooli_hakija"){ ?>
		
			<h3 class="vasen_h">
				<?php if($self=="hakemus.php"){ echo HAKEMUS; } ?>
				<?php if($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_lah"){ ?>
					<a href="hakemus_pdf.php?hakemusversio_id=<?php echo $hakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $hakemusversioDTO->TutkimusDTO->ID; ?>">
						<img src="static/images/pdf.png" class="lisatoim">
					</a>	
				<?php } ?>	
			</h3>
			
			<ol class="vasen_menu_ol">
			
				<?php if($self=="hakemus.php" && isset($hakemusversioDTO->Lomakkeen_sivutDTO)){ ?>
				
					<?php foreach($hakemusversioDTO->Lomakkeen_sivutDTO as $sivun_tunniste => $nakyma_hakemusversio){ ?>
					
						<?php if($sivun_tunniste=="hakemus_aineisto"){ ?>
							<li class="vasen_li"><a <?php if($sivu==$sivun_tunniste){ ?> class="valittu" <?php } ?> href="hakemus.php?sivu=<?php echo $sivun_tunniste; ?>&tutkimus_id=<?php echo $tutkimus_id; ?>&hakemusversio_id=<?php echo $hakemusversio_id; ?>&aineiston_indeksi=0"><?php echo tulosta_teksti(kaanna_osion_kentta($nakyma_hakemusversio,"Nimi",$_SESSION["kayttaja_kieli"])); ?></a> <span id="pukkimerkki_<?php echo $sivun_tunniste; ?>" class="green" style="display: <?php if($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken" && $nakyma_hakemusversio->Pakollisia_tietoja_puuttuu==false){ echo "inline"; } else { echo "none"; } ?>;">&#x2714;</span></li>
						<?php } else if($sivun_tunniste=="hakemus_esikatsele_ja_laheta") { ?>
							
							<?php if((isset($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi) && $hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken") || !isset($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi)){ ?>
								<li class="vasen_li"><a <?php if($sivu==$sivun_tunniste){ ?> class="valittu" <?php } ?> href="hakemus.php?sivu=<?php echo $sivun_tunniste; ?>&tutkimus_id=<?php echo $tutkimus_id; ?>&hakemusversio_id=<?php echo $hakemusversio_id; ?>&aineiston_indeksi=0"><?php echo tulosta_teksti(kaanna_osion_kentta($nakyma_hakemusversio,"Nimi",$_SESSION["kayttaja_kieli"])); ?></a></li>
							<?php } ?>
							
						<?php } else { ?>
						
							<li class="vasen_li" style="display: <?php if(!empty($nakyma_hakemusversio->OsiotDTO_taulu)){ echo "block"; } else { echo "none"; } ?>;">
								<a <?php if($sivu==$sivun_tunniste){ ?> class="valittu" <?php } ?> href="hakemus.php?sivu=<?php echo $sivun_tunniste; ?>&tutkimus_id=<?php echo $tutkimus_id; ?>&hakemusversio_id=<?php echo $hakemusversio_id; ?>"><?php echo tulosta_teksti(kaanna_osion_kentta($nakyma_hakemusversio,"Nimi",$_SESSION["kayttaja_kieli"])); ?></a> <span id="pukkimerkki_<?php echo $sivun_tunniste; ?>" class="green" style="display: <?php if($hakemusversioDTO->Hakemusversion_tilaDTO->Hakemusversion_tilan_koodi=="hv_kesken" && $nakyma_hakemusversio->Pakollisia_tietoja_puuttuu==false && $sivun_tunniste!="hakemus_esikatsele_ja_laheta"){ echo "inline"; } else { echo "none"; } ?>;">&#x2714;</span>
							</li> 
							
							<?php if(isset($hakija_kayttaja_id) && $sivun_tunniste=="hakemus_tutkimusryhma"){ ?>
							
								<?php $listatut_kayttajat = array(); ?>
							
								<ul style="list-style-type: none; margin-bottom: 20px;">
								
									<?php if(!empty($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat)){ ?>
										<?php for($i=0; $i < sizeof($hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat); $i++){ ?>										
											<li>
												<a <?php if($hakija_kayttaja_id==$hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID){ ?> style="text-decoration: underline;" <?php } ?> href="hakemus.php?tutkimus_id=<?php echo $hakemusversioDTO->TutkimusDTO->ID; ?>&hakemusversio_id=<?php echo $hakemusversioDTO->ID; ?>&sivu=hakemus_tutkimusryhma&hakija_kayttaja_id=<?php echo $hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID; ?>">
													<?php echo $hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Etunimi . " " . $hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->Sukunimi; ?>
												</a>
											</li>	
											<?php array_push($listatut_kayttajat, $hakemusversioDTO->HakijatDTO_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID); ?>	
										<?php } ?>
									<?php } ?>
									
									<?php if(!empty($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat)){ ?>
										<?php for($i=0; $i < sizeof($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat); $i++){ ?>
											<?php if(!in_array($hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID, $listatut_kayttajat)){ ?>
												<li>
													<a <?php if($hakija_kayttaja_id==$hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID){ ?> style="text-decoration: underline;" <?php } ?> href="hakemus.php?tutkimus_id=<?php echo $hakemusversioDTO->TutkimusDTO->ID; ?>&hakemusversio_id=<?php echo $hakemusversioDTO->ID; ?>&sivu=hakemus_tutkimusryhma&hakija_kayttaja_id=<?php echo $hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID; ?>">
														<?php echo $hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Etunimi . " " . $hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->Sukunimi; ?>
													</a>													
												</li>
												<?php array_push($listatut_kayttajat, $hakemusversioDTO->HakijatDTO_ei_kasittelyoikeutta_hakevat[$i]->KayttajaDTO->ID); ?>
											<?php } ?>	
										<?php } ?>
									<?php } ?>
									
								</ul>
																
							<?php } ?>
							
						<?php } ?>
						
					<?php } ?>
					
				<?php } ?>
				
			</ol>
			
		<?php } ?>
		
		<?php if($_SESSION["kayttaja_rooli"]!="rooli_hakija"){ ?>
		
			<div class="w3-sidebar w3-bar-block w3-card-2">
			
				<h3 class="vasen_h">
					
					<?php if($_SESSION['kayttaja_rooli']=="rooli_eettisen_puheenjohtaja" || $_SESSION['kayttaja_rooli']=="rooli_eettisensihteeri"){ ?>
						<?php echo "Eettinen toimikunnan lausunto"; ?>
					<?php } else { ?>
						<?php echo KAYTTOLUPA; ?> 
					<?php } ?>	
					
					<?php if(($_SESSION['kayttaja_rooli']=="rooli_kasitteleva" || $_SESSION['kayttaja_rooli']=="rooli_eettisensihteeri") && $self!="metatiedot.php" || ($self=="metatiedot.php" && isset($metatiedot_kohde) && $metatiedot_kohde!="Asia")){ ?>
						<a href="metatiedot.php?metatiedot_kohde=Asia&hakemus_id=<?php echo $hakemus_id; ?>"><img src="static/images/meta-edit.png" alt="<?php echo META_MUOK;?>" title="<?php echo META_MUOK;?>" style="width: 12px; height: 17px;"></a>
					<?php } ?>
					
					<?php if(($_SESSION['kayttaja_rooli']=="rooli_kasitteleva" || $_SESSION['kayttaja_rooli']=="rooli_eettisensihteeri") && $self=="metatiedot.php" && $metatiedot_kohde=="Asia"){ ?>
						<a href="hakemus.php?&sivun_tunniste=hakemus_perustiedot&tutkimus_id=<?php echo $tutkimus_id; ?>&hakemusversio_id=<?php echo $hakemusversio_id; ?>&hakemus_id=<?php echo $hakemus_id; ?>">						
							<img src="static/images/meta-save.png" alt="<?php echo META_TALL;?>" title="<?php echo META_TALL;?>" style="width: 15px; height: 15px;">							
						</a> 
					<?php } ?>
					
				</h3>
				<br>
				<a <?php if($self=="hakemus.php" || ($self=="metatiedot.php" && isset($metatiedot_kohde) && $metatiedot_kohde=="Hakemus")){ ?> style="font-weight: bold;" <?php } ?> class="w3-button w3-bar-item" id="hakemus_button">															
					
					<?php echo HAKEMUS; ?> <?php echo $hakemusversioDTO->Versio; ?>/<?php echo $tutkimus_id; ?>
					
					<?php if(($_SESSION['kayttaja_rooli']=="rooli_kasitteleva" || $_SESSION['kayttaja_rooli']=="rooli_eettisensihteeri") && ($self!="metatiedot.php" || ($self=="metatiedot.php" && isset($metatiedot_kohde) && $metatiedot_kohde!="Hakemus"))){ ?>
						<a href="metatiedot.php?metatiedot_kohde=Hakemus&hakemus_id=<?php echo $hakemus_id; ?>"><img src="static/images/meta-edit.png" alt="<?php echo META_MUOK;?>" title="<?php echo META_MUOK;?>" style="width: 12px; height: 17px;"></a>
					<?php } ?>
					<?php if($self=="metatiedot.php" && $metatiedot_kohde=="Hakemus"){ ?>
						<a href="hakemus.php?&sivun_tunniste=hakemus_perustiedot&tutkimus_id=<?php echo $tutkimus_id; ?>&hakemusversio_id=<?php echo $hakemusversio_id; ?>&hakemus_id=<?php echo $hakemus_id; ?>"><img src="static/images/meta-save.png" alt="<?php echo META_TALL;?>" title="<?php echo META_TALL;?>" style="width: 15px; height: 15px;"></a> <!-- TÄMÄ PITÄÄ KATSOA VIELÄ /SL -->
					<?php } ?>
					
				</a><br> 
				
				<div id="hakemus_menu_div" class="w3-hide w3-white w3-card-2 <?php if($self=="hakemus.php" || ($self=="metatiedot.php" && isset($metatiedot_kohde) && $metatiedot_kohde=="Liite")){ ?>w3-show<?php } ?>">
					<?php foreach($hakemusversioDTO->Lomakkeen_sivutDTO as $sivun_tunniste => $nakyma_hakemusversio){ if($sivun_tunniste=="hakemus_esikatsele_ja_laheta") continue; //if(empty($nakyma_hakemusversio->OsiotDTO_taulu)) continue; ?>
						<a class="small" style="<?php if(isset($sivu) && $sivu==$sivun_tunniste){ ?> font-weight: bold; <?php } ?>" href="hakemus.php?sivu=<?php echo $sivun_tunniste; ?>&tutkimus_id=<?php echo $tutkimus_id; ?>&hakemusversio_id=<?php echo $hakemusversio_id; ?>&hakemus_id=<?php echo $hakemus_id; ?>" class="w3-bar-item w3-button"> <?php echo tulosta_teksti(kaanna_osion_kentta($nakyma_hakemusversio,"Nimi",$_SESSION["kayttaja_kieli"])); ?></a><br>
					<?php }?>
				</div>
				
				<?php if(isset($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi) && $hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_lah"){ ?>
					
					<?php if($hakemusDTO->Viranomaisen_koodi==$_SESSION['kayttaja_viranomainen']){ ?>
										
						<a <?php if($self=="viranomainen_hakemus_viestit.php"){ ?> style="font-weight: bold;" <?php } ?> href="viranomainen_hakemus_viestit.php?hakemus_id=<?php echo $hakemus_id; ?>" class="w3-bar-item w3-button"><?php echo VIESTIT; ?></a>
						<br>
					<?php } ?>
					
					
					<?php if($hakemusDTO->Viranomaisen_koodi==$_SESSION['kayttaja_viranomainen'] && ($_SESSION['kayttaja_rooli']=="rooli_eettisen_puheenjohtaja" || $_SESSION['kayttaja_rooli']=="rooli_eettisensihteeri" || $_SESSION['kayttaja_rooli']=="rooli_paattava" || $_SESSION['kayttaja_rooli']=="rooli_kasitteleva")){ ?>
						
						<a <?php if($self=="lausunto.php" || $self=="viranomainen_hakemus_lausunto.php" || ($self=="metatiedot.php" && isset($metatiedot_kohde) && $metatiedot_kohde=="Lausunto")){ ?> style="font-weight: bold;" <?php } ?> href="viranomainen_hakemus_lausunto.php?hakemus_id=<?php echo $hakemus_id; ?>" class="w3-bar-item w3-button"><?php echo LAUSUNNOT; ?></a>
						<br>
					<?php } ?>
					
					<?php if($_SESSION['kayttaja_rooli']=="rooli_lausunnonantaja"){ ?>
						<a <?php if($self=="lausunto.php" || $self=="lausunnonantaja_hakemus_lausunto.php"){ ?> style="font-weight: bold;" <?php } ?> href="lausunnonantaja_hakemus_lausunto.php?hakemus_id=<?php echo $hakemus_id; ?>" class="w3-bar-item w3-button"><?php echo LAUSUNNOT; ?></a>
						<br>
					<?php } ?>
					
					<?php if($hakemusDTO->Viranomaisen_koodi==$_SESSION['kayttaja_viranomainen'] && ($_SESSION['kayttaja_rooli']=="rooli_eettisen_puheenjohtaja" || $_SESSION['kayttaja_rooli']=="rooli_eettisensihteeri" || $_SESSION['kayttaja_rooli']=="rooli_aineistonmuodostaja" || $_SESSION['kayttaja_rooli']=="rooli_paattava" || $_SESSION['kayttaja_rooli']=="rooli_kasitteleva")){ ?>
						
						<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi!="hak_korvattu"){ ?>
							<a <?php if($self=="paatos.php" || ($self=="metatiedot.php" && isset($metatiedot_kohde) && $metatiedot_kohde=="Paatos")){ ?> style="font-weight: bold;" <?php } ?> href="paatos.php?hakemus_id=<?php echo $hakemus_id; ?>" class="w3-bar-item w3-button"><?php echo PAATOS; ?></a>
						<?php } ?>
							
						<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat"){
							if($self!="metatiedot.php" || ($self=="metatiedot.php" && isset($metatiedot_kohde) && $metatiedot_kohde!="Paatos")){ ?>
								<a href="metatiedot.php?metatiedot_kohde=Paatos&hakemus_id=<?php echo $hakemus_id; ?>">
								<?php if($_SESSION['kayttaja_rooli']=="rooli_eettisensihteeri" ||$_SESSION['kayttaja_rooli']=="rooli_kasitteleva"){ ?>
									<img src="static/images/meta-edit.png" alt="<?php echo META_MUOK;?>" title="<?php echo META_MUOK;?>" style="width: 12px; height: 17px;">
								<?php } ?>
								</a>							
						<?php } } ?>
						
						<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat" && $self=="metatiedot.php" && $metatiedot_kohde=="Paatos"){ ?>
							<a href="paatos.php?hakemus_id=<?php echo $hakemus_id; ?>"><img src="static/images/meta-save.png" alt="<?php echo META_TALL;?>" title="<?php echo META_TALL;?>" style="width: 15px; height: 15px;"></a>
						<?php } ?>
						<br>
						
					<?php } ?> 
					
					<?php if($hakemusDTO->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi=="hak_paat" && $_SESSION['kayttaja_rooli']=="rooli_aineistonmuodostaja" && $hakemusDTO->Viranomaisen_koodi==$_SESSION['kayttaja_viranomainen']){ ?>
						<a <?php if($self=="aineistonmuodostus.php"){ ?> style="font-weight: bold;" <?php } ?> class="w3-bar-item w3-button" href="aineistonmuodostus.php?hakemus_id=<?php echo $hakemus_id; ?>"><?php echo AINEISTON_MUODOSTUS; ?></a>
					<?php } ?>
					
				<?php } ?>
				
			</div>
			
			<?php if(isset($Tutkimuksen_viranomaisen_hakemuksetDTO) && !empty($Tutkimuksen_viranomaisen_hakemuksetDTO)){ ?>
			
				<div class="w3-sidebar w3-bar-block w3-card-2" style="margin-top: 15px;">
				
					<div class="vasen_h">
						<?php echo MUUT_HAK;?>
					</div>
												
					<?php					
					for($j=0; $j < sizeof($Tutkimuksen_viranomaisen_hakemuksetDTO); $j++) {						
						if ($Tutkimuksen_viranomaisen_hakemuksetDTO[$j]->ID != $hakemus_id) { ?>													
							<a class="w3-bar-item w3-button" href="hakemus.php?tutkimus_id=<?php echo $Tutkimuksen_viranomaisen_hakemuksetDTO[$j]->HakemusversioDTO->TutkimusDTO->ID; ?>&hakemusversio_id=<?php echo $Tutkimuksen_viranomaisen_hakemuksetDTO[$j]->HakemusversioDTO->ID; ?>&hakemus_id=<?php echo $Tutkimuksen_viranomaisen_hakemuksetDTO[$j]->ID; ?>"><?php echo HAKEMUS ." " . $Tutkimuksen_viranomaisen_hakemuksetDTO[$j]->HakemusversioDTO->Versio . "/".$Tutkimuksen_viranomaisen_hakemuksetDTO[$j]->HakemusversioDTO->TutkimusDTO->ID; ?></a>
						<?php } ?>						
					<?php } ?>
								
				</div>
			
			<?php } ?>

		<?php } ?>
		
	</div>

</div>