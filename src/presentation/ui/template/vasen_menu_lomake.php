<div class="vasen_menu" id="menu_fix">
	<div class="vasen_sisa">

		<p style="margin-left: 9px; font-weight: bold; color: #6EA9C2; font-size: 125%">Lomake</p>	

		<ol class="vasen_menu_ol">
		
			<li style="display: <?php if($_SESSION["kayttaja_rooli"] == "rooli_viranomaisen_paak" && $lomakeDTO->ID==1){ echo "none;"; } else { "block;"; } ?>"><a href="lomake_perustiedot.php?lomake_id=<?php echo $lomake_id; ?>" <?php if($self=="lomake_perustiedot.php"){ ?> class="valittu" <?php } ?> >Lomakkeen perustiedot</a></li>
		
			<?php if(!empty($lomakeDTO->Lomakkeen_sivutDTO)){ ?>
				<?php foreach($lomakeDTO->Lomakkeen_sivutDTO as $tunniste => $lomake_sivuDTO) { ?>
				
					<?php if($_SESSION["kayttaja_rooli"] == "rooli_viranomaisen_paak" && $lomakeDTO->ID==1 && $tunniste=="hakemus_liitteet") continue; ?>
					<?php if($lomakeDTO->ID==1 && ($tunniste=="hakemus_organisaatiotiedot" || $tunniste=="hakemus_esikatsele_ja_laheta" || $tunniste=="hakemus_aineisto" || $tunniste=="hakemus_tutkimusryhma" || $tunniste=="hakemus_perustiedot")) continue; ?>
					
					<li>					
						<a href="lomake_sivu.php?lomake_sivu_id=<?php echo $lomake_sivuDTO->ID; ?>&lomake_id=<?php echo $lomake_id; ?>" <?php if(isset($lomake_sivu_id) && $lomake_sivu_id==$lomake_sivuDTO->ID){ ?> class="valittu" <?php } ?> > <?php echo kaanna_osion_kentta($lomake_sivuDTO, "Nimi", $_SESSION["kayttaja_kieli"]); ?> </a>					
					</li>	
					
				<?php } ?>
			<?php } ?>
			
			<?php if($lomakeDTO->ID!=1){ ?>
				<li><a href="lomake_suhteet.php?lomake_id=<?php echo $lomake_id; ?>" <?php if($self=="lomake_suhteet.php"){ ?> class="valittu" <?php } ?> >Riippuvuussäännöt</a></li>
			<?php } ?>
			
		</ol>
		
	</div>
</div>