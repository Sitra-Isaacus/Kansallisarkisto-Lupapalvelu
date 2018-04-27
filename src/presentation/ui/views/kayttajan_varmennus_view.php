<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end
 *
 * Created: 15.11.2016
 */
?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="hp" >
    <link rel="icon" href="static/images/ka1.png">
    <title><?php echo KAYTTOLUPAPALVELU; ?><?php if (isset($page_title)) echo " &mdash; $page_title"?></title>
    <!-- CSS Tyylit ja JavaScript -->
    <script src="static/js/fmas_front_end.js" type="text/javascript"></script>
    <script src="static/js/yleiset.js" type="text/javascript"></script>
    <link href="static/css/jquery-ui.css" rel="stylesheet" type="text/css">
    <script src="static/js/jquery-1.10.2.js"></script>
    <script src="static/js/jquery-ui.js"></script>
    <link href="static/css/tyylit.css" rel="stylesheet" type="text/css">
</head>
<body>

	<br><br><br>
	<div class="tausta">
		<div class="sisalto">
			<div class="laatikko">
				<?php if($kayttaja_varmennettu){ ?>
					<h3><?php echo VARMENT_ONNISTUI; ?></h3>
					<div>
						<p class="liitteet"><?php echo $vastaus["KayttajaDTO"]["Varmennettu_kayttaja"]->Etunimi . " " . $vastaus["KayttajaDTO"]["Varmennettu_kayttaja"]->Sukunimi . " " . ON_REKISTEROITY; ?> </p>
						<p class="liitteet"><a href="kirjaudu.php"><?php echo KIRJ_LPAL; ?>.</a></p>
					</div>
				<?php } else { ?>
					<h3><?php echo VARMENT_EPAONNISTUI; ?></h3>
					<div>
						<p class="liitteet"><?php echo REK_UUDELL; ?></p>
					</div>
				<?php } ?>
			</div>
			<?php
				include './ui/template/footer.php';
			?>
 
