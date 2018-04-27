<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end
 *
 * Created: 29.4.2016
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
				<?php if($kayttajaLiitettyHakemukseen){ ?>
					<h2><?php echo LIITTAMINEN_ONNISTUI; ?></h2>
					<div>
						<p class="liitteet"><?php echo $kayttaja->Etunimi . " " . $kayttaja->Sukunimi . " " . ON_LISATTY . " " . $hakemusversioDTO->Tutkimuksen_nimi; ?> </p>
						<?php if($registrationNeeded == 1){ ?>
						<p class="liitteet"><?=REKISTEROI_OLE_HYVA?> <a href="rekisteroidy.php"><?=REKISTEROIDY_MENU?></a></p>
						<?php } ?>
					</div>
				<?php } else if($kayttajaOnLiitettyJoAiemmin) { ?>
					<h3 class="ohje"><?php echo KAYT_JO_LIITETTY; ?></h3>
					<div>
						<p class="liitteet"><?php echo $kayttaja->Etunimi . " " . $kayttaja->Sukunimi . " " . ON_LIITETTY . " " . $hakemusversioDTO->Tutkimuksen_nimi . " " . JO_AIEMMIN; ?>. </p>
					</div>
				<?php } else { ?>
					<h3 class="ohje"><?php echo LIITTAMINEN_EPAONNISTUI; ?></h3>
					<div>
						<p class="liitteet"> </p>
					</div>
				<?php } ?>
			</div>
			<?php
				include './ui/template/footer.php';
			?>
 
