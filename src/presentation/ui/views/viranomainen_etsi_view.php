<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the search page (viranomaisen käyttöliittymä)
 *
 * Created: 3.12.2015
 */
 
include './ui/template/header.php';
?>
<form enctype="multipart/form-data" name="etsi" id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
	<div class="laatikko">
		<div class="paneeli_otsikko">
			<h3><?php echo TUTK_HAKU; ?></h3>
		</div>
		<div class="paneelin_tiedot">
			<div class="tieto">
			<table>
				<tr>
					<td>
					<p class="hakuotsikko"><?php echo TUTKIMUKSEN_NIMI . ":"; ?></p>
					</td>
					<td>
					<input type="text" name="tutk_nimi" class="hakuboksi">
					</td>
				</tr>
				
				<?php if($_SESSION['kayttaja_rooli']!="rooli_eettisen_puheenjohtaja" && $_SESSION['kayttaja_rooli']!="rooli_eettisensihteeri"){ ?>
					<tr>
						<td>
						<p class="hakuotsikko"><?php echo HAKIJAN_NIMI . ":"; ?></p>
						</td>
						<td>
						<input type="text" name="hak_nimi" class="hakuboksi">
						</td>
					</tr>				
					<tr>
						<td>
						<p class="hakuotsikko"><?php echo HAKIJAN_ROOLI . ":"; ?></p>
						</td>
						<td>
						<select name="hak_rooli" class="hakuboksi">
						<option selected disabled><?php echo VALITSE_HAKIJAN_ROOLI; ?></option>
						<?php foreach ($HAKIJAN_ROOLIT as $key => $value){ ?>
							<option value="<?php echo $key;?>"><?php echo $value; ?></option>
						<?php } ?>
						</p>
						</select>
						</td>
					</tr>
				<?php } ?>
				
				<tr>
					<td>
					<p class="hakuotsikko"><?php echo HAKEMUKSEN_TUNNUS . ":"; ?></p>
					</td>
					<td>
					<input type="text" name="tutk_nro" class="hakuboksi">
					</td>
				</tr>
				<tr>
					<td>
					<p class="hakuotsikko"><?php echo HAKEMUKSEN_TILA . ":"; ?></p>
					</td>
					<td>
					<select name="hak_tila" class="hakuboksi">
					<option selected disabled><?php echo VALITSE_HAK_TILA; ?></option>
					<?php foreach ($HAKEMUKSEN_TILA_VIRANOMAISEN_KAYTTOLIITTYMA as $key => $value){ ?>
						<option value="<?php echo $key;?>"><?php echo $value; ?></option>
					<?php } ?>
					</select>
					</td>
				</tr>
				<tr>
					<td>
					<p class="hakuotsikko"><?php echo HAKEMUKSEN_KASITTELYPVM . ":"; ?></p>
					</td>
					<td>
					<select class="vuosi_alku" name ="vuosi_alku">
					<option selected disabled><?php echo ALKUVUOSI; ?></option>
					<?php for($i=2018; $i <= date("Y"); $i++){ ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php } ?>
					</select>
					-
					<select class="vuosi_loppu" name ="vuosi_loppu">
					<option selected disabled><?php echo LOPPUVUOSI; ?></option>
					<?php for($i=2018; $i <= date("Y"); $i++){ ?>
						<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php } ?>
					</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
					<button type="submit" class="hakuboksi" name="etsi"><?php echo ETSI_HAKEMUKSIA; ?></button>
					</td>
				</tr>
			<br>
			</table>
			</div>
		</div>
	</div>
</form>

<?php if(isset($etsityt) && !empty($etsityt["HakemuksetDTO"]["Loydetyt"])){ ?>
	<p>
	<div class="laatikko">
		<table class="taulu">
			<h3><?php echo HAKUTULOKSET; ?></h3>
			<thead>
				<tr>
					<th><?php echo TUTKIMUKSEN_NIMI; ?></th>
					<th><?php echo HAKEMUS; ?></th>
					<th><?php echo TYYPPI; ?></th>
					<th><?php echo HAKEMUKSEN_TILA; ?></th>
					<th><?php echo TILAN_PVM; ?></th>
				</tr>
			</thead>
			<?php
			for($i=0; $i < sizeof($etsityt["HakemuksetDTO"]["Loydetyt"]); $i++){
			?>
				<tbody>
					<tr>
						<td><a href="hakemus.php?hakemusversio_id=<?php echo $etsityt["HakemuksetDTO"]["Loydetyt"][$i]->HakemusversioDTO->ID; ?>&tutkimus_id=<?php echo $etsityt["HakemuksetDTO"]["Loydetyt"][$i]->HakemusversioDTO->TutkimusDTO->ID; ?>&hakemus_id=<?php echo $etsityt["HakemuksetDTO"]["Loydetyt"][$i]->ID; ?>"><?php echo htmlentities($etsityt["HakemuksetDTO"]["Loydetyt"][$i]->HakemusversioDTO->Tutkimuksen_nimi,ENT_COMPAT, "UTF-8"); ?></a></td>
						<td><?php echo htmlentities($etsityt["HakemuksetDTO"]["Loydetyt"][$i]->Hakemuksen_tunnus,ENT_COMPAT, "UTF-8"); ?></td>
						<td><?php echo koodin_selite($etsityt["HakemuksetDTO"]["Loydetyt"][$i]->HakemusversioDTO->Hakemuksen_tyyppi, $_SESSION['kayttaja_kieli']); ?></td>
						<td><?php echo koodin_selite($etsityt["HakemuksetDTO"]["Loydetyt"][$i]->Hakemuksen_tilaDTO->Hakemuksen_tilan_koodi, $_SESSION['kayttaja_kieli']); ?></td>
						<td><?php echo muotoilepvm($etsityt["HakemuksetDTO"]["Loydetyt"][$i]->Hakemuksen_tilaDTO->Lisayspvm, $_SESSION['kayttaja_kieli']); ?></td>
					</tr>
				</tbody>
			<?php }?>
		</table>
	</div>
<?php } else { ?>
	<?php if(isset($_POST['etsi'])){ ?>
	<h3 style="text-align: center;"><?php echo EI_TULOKSIA; ?>.</h3>
	<?php } ?>
<?php } ?>

<?php
	include './ui/template/footer.php';
?>