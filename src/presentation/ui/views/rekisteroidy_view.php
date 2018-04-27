<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the register page
 *
 * Created: 5.4.2016
 */
 
$self = basename($_SERVER['PHP_SELF']);
include './ui/template/header.php';
include './ui/template/success_notification.php';
include './ui/template/error_notification.php';

?>

<form enctype="multipart/form-data" id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
	<div class="laatikko10">
		<h1>
			<?php echo REKISTEROIDY; ?>
		</h1>
		<br>
		<div class="paneelin_tiedot">
			<p><?php echo TAHD_PAKOLLISIA; ?></p>
			<?php echo ETUNIMI;?>*
			<div class="sisennys"><input class="txt200" id="etunimi" name="etunimi" class="tieto_laatikko" type="textarea" value="" maxlength="100" autocomplete="off" required autofocus></div>
			<?php echo SUKUNIMI;?>*
			<div class="sisennys"><input class="txt200" id="sukunimi" name="sukunimi" class="tieto_laatikko" type="textarea" value="" maxlength="100" autocomplete="off" required></div>
			<?php echo SAHKOPOSTIOSOITE;?>*
			<div class="sisennys"><input class="txt200" id="sahkopostiosoite" name="sahkopostiosoite" class="tieto_laatikko" type="textarea" value="" maxlength="100" autocomplete="off" required></div>
			<?php echo LUO_SALASANA;?>*
			<div class="sisennys"><input class="txt200" id="salasana" name="salasana" class="tieto_laatikko" type="password" value="" maxlength="500" autocomplete="off" required /></div>
			<?php echo VAHVISTA_SALASANA;?>*
			<div class="sisennys"><input class="txt200" id="salasana_vahvistus" name="salasana_vahvistus" class="tieto_laatikko" type="password" value="" maxlength="50" autocomplete="off" required /></div>
			<?php echo ASIOINTIKIELI;?>
			<div class="sisennys">
				<select class="txt200" id="asiointikieli" name="asiointikieli">
						<option value='fi'> <?php echo fi; ?></option>
						<option value='en'> <?php echo en; ?></option>
				</select>
			</div>
			<?php echo SYNTYMAAIKA;?>&nbsp;&nbsp;
			<div class="sisennys">
				<input type="text" id="kalenteri" name="syntymaaika" class="txt200" />
			</div>
			<?php echo PUHELIN;?>&nbsp;&nbsp;
			<div class="sisennys"><input class="txt200" id="puhelin" name="puhelin" class="tieto_laatikko" type="textarea" value="" maxlength="20" autocomplete="off"/></div>
			<div class="sisennys">
			<br><br>
			<input type="submit" name="rekisteroidy" value="<?php echo REKISTEROIDY_MENU; ?>" class="nappi">
			</div>
			
			<p><?php echo SALASANA_INFO; ?></p>
		</div>
	</div>
</form>
<?php
	include './ui/template/footer.php';
?>
 
