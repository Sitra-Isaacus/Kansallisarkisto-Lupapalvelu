<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: view of the login page
 *
 * Created: 9.10.2015
 */

$page_title = KIRJAUDU_SISAAN;
$self = basename($_SERVER['PHP_SELF']);

include './ui/template/header.php';
include './ui/template/error_notification.php';
include './ui/template/info_notification.php';

?>

<div class="laatikko10">

	<h1><?php echo KAYTTOLUPAPALVELU; ?></h1>
	<p><?php echo KIRJAUDU_INFO; ?></p>
	
	<form id="kirjaudu_form" method="POST">
		<div class="paneelin_tiedot">
		
			<br>
			<?php echo SAHKOPOSTIOSOITE; ?>
			
			<div class="sisennys">
				<input type="text" maxlength="50" ID="tun" name="tunnus" class="txt200" value="" autocomplete="off">
			</div>
			
			<?php echo SALASANA; ?>
			<div class="sisennys">
				<input type="password" id="pw" maxlength="20" name="salasana" class="txt200" value="" autocomplete="off">
			</div>
			
			<br>
			<div class="sisennys">
				<input type="submit" name="kirjaudu" class="nappi" value="<?php echo KIRJAUDU; ?>">
			</div>
			
		</div>
	</form>
	
</div>

<?php
	include './ui/template/footer.php';
?>
 
