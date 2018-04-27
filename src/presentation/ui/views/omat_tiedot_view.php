<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the omat tiedot page
 *
 * Created: 8.4.2016
 */
 
$self = basename($_SERVER['PHP_SELF']);
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>
<form enctype="multipart/form-data" id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
	<div class="laatikko10">
		<h1>
			<?php echo OMAT_TIEDOT; ?>
		</h1>
	</div>
	<div class="paneelin_tiedot">
		<?php echo ETUNIMI;?>*
		<div class="sisennys"><input class="txt200" id="etunimi" name="etunimi" class="tieto_laatikko" type="textarea" value="<?php if(isset($kayttaja->Etunimi)){ echo htmlentities($kayttaja->Etunimi, ENT_COMPAT, "UTF-8"); } ?>" maxlength="100" autocomplete="off" required autofocus></div>
		<?php echo SUKUNIMI;?>*
		<div class="sisennys"><input class="txt200" id="sukunimi" name="sukunimi" class="tieto_laatikko" type="textarea" value="<?php if(isset($kayttaja->Sukunimi)){ echo htmlentities($kayttaja->Sukunimi, ENT_COMPAT, "UTF-8"); } ?>" maxlength="100" autocomplete="off" required></div>
		<?php echo ASIOINTIKIELI;?>
		<div class="sisennys">
			<select class="txt200" id="asiointikieli" name="asiointikieli">
				<option <?php if(isset($kayttaja->Kieli_koodi) && $kayttaja->Kieli_koodi=="fi"){ echo "selected"; } ?> value='fi'> <?php echo fi; ?></option>
				<option <?php if(isset($kayttaja->Kieli_koodi) && $kayttaja->Kieli_koodi=="en"){ echo "selected"; } ?> value='en'> <?php echo en; ?></option>
			</select>
		</div>
		<?php echo PUHELIN;?>&nbsp;&nbsp;
		<div class="sisennys"><input class="txt200" id="puhelin" name="puhelin" class="tieto_laatikko" type="textarea" value="<?php if(isset($kayttaja->Puhelinnumero)){ echo htmlentities($kayttaja->Puhelinnumero, ENT_COMPAT, "UTF-8"); } ?>" maxlength="20" autocomplete="off"/></div>
		<?php echo SYNTYMAAIKA;?>&nbsp;&nbsp;
		<div class="sisennys">
			<input value="<?php if(isset($kayttaja->Syntymaaika)){ echo muotoilepvm2($kayttaja->Syntymaaika, 'fi'); } ?>" type="text" id="kalenteri" name="syntymaaika" class="txt200" />
		</div>
		<div class="sisennys">
			<br><br>
			<input type="submit" name="tallenna_omat_tiedot" value="<?php echo TALLENNA; ?>" class="nappi">
		</div>
	</div>
</form>
<?php
	include './ui/template/footer.php';
?> 