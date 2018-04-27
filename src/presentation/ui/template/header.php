<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: HTML header template
 *
 * Created: 2.10.2015
 */
 
$self = basename($_SERVER['PHP_SELF']); ?> 

<html>
<head>

	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="static/images/ka1.png">
    <title><?php echo KAYTTOLUPAPALVELU; ?><?php if (isset($page_title)) echo " &mdash; $page_title"?></title>
	
	<script type="text/javascript">
	
		var osiotDTO = null;
		var kayttaja_kieli = "fi"; // Oletuskieli on suomi
		
		<?php if($self=="hakemus.php"){ ?>
			osiotDTO = JSON.parse( '<?php echo json_encode($hakemusversioDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu); ?>' );
			<?php if ($sivu=="hakemus_aineisto"){ ?>
				var haetut_luvan_kohteetDTO = JSON.parse( '<?php echo json_encode($hakemusversioDTO->Haettu_aineistoDTO->Haetut_luvan_kohteetDTO); ?>' );
				var luvan_kohteetDTO_kaikki = JSON.parse( '<?php echo json_encode($kaikki_luvan_kohteet); ?>' );
				var luvan_kohteetDTO_viranomainen = JSON.parse( '<?php echo json_encode($viranomaisten_luvan_kohteet); ?>' );
				var viranomaisten_koodit = JSON.parse( '<?php echo json_encode($VIRANOMAISEN_KOODIT); ?>' );
			<?php } ?>
		<?php } ?>
		
		<?php if($self=="paatos.php"){ ?>
			osiotDTO = JSON.parse( '<?php echo json_encode($paatosDTO->Lomakkeen_sivutDTO[$sivu]->OsiotDTO_taulu); ?>' );
		<?php } ?>
		
		<?php if($self=="lausunto.php" && isset($lausuntoDTO->Lomakkeen_sivutDTO["lausunto_oletus"]->OsiotDTO_taulu)){ ?>
			osiotDTO = JSON.parse( '<?php echo json_encode($lausuntoDTO->Lomakkeen_sivutDTO["lausunto_oletus"]->OsiotDTO_taulu); ?>' );
		<?php } ?>
		
		<?php if(isset($_SESSION["kayttaja_kieli"])){ ?>
			kayttaja_kieli = JSON.parse( '<?php echo json_encode($_SESSION["kayttaja_kieli"]); ?>' );
		<?php } ?>
		
	</script>
	
    <!-- CSS Tyylit ja JavaScript -->
    <link href="static/css/tyylit.css" rel="stylesheet" type="text/css">
    <link href="static/css/jquery-ui.css" rel="stylesheet" type="text/css">
	<link href="static/css/w3.css" rel="stylesheet" type="text/css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

	<script src="static/js/jquery-1.10.2.js"></script>
    <script src="static/js/jquery-ui.js"></script>
    <script src="static/js/yleiset.js" type="text/javascript"></script>
	<script src="static/js/fmas_front_end.js" type="text/javascript"></script>
	
</head>

<body>

	<?php if(isset($_SESSION["kayttaja_rooli"])){
		
		if($_SESSION['kayttaja_rooli'] == "rooli_hakija") $etusivuOs = "index.php";
					 
		if($_SESSION['kayttaja_rooli'] == "rooli_eettisen_puheenjohtaja" || $_SESSION['kayttaja_rooli'] == "rooli_eettisensihteeri" || $_SESSION['kayttaja_rooli'] == "rooli_kasitteleva" || $_SESSION['kayttaja_rooli'] == "rooli_paattava") $etusivuOs = "viranomainen_saapuneet_hakemukset.php"; 
					
		if($_SESSION['kayttaja_rooli'] == "rooli_lausunnonantaja") $etusivuOs = "lausunnonantaja_saapuneet_lausuntopyynnot.php"; 
					
		if($_SESSION['kayttaja_rooli'] == "rooli_aineistonmuodostaja") $etusivuOs = "aineistonmuodostaja_saapuneet_tilaukset.php";  
					
		if($_SESSION['kayttaja_rooli'] == "rooli_lupapalvelun_paak" || $_SESSION['kayttaja_rooli'] == "rooli_viranomaisen_paak") $etusivuOs = "kayttajaroolit.php"; 
					
	} else {
		$etusivuOs = "index.php";
	} ?>
		
	<div id="yla_koko">
	
		<div id="yla_sisalto">
		
				<div id="yla_oikea">
				
					<div class="kielet">
					
						<?php
							$language = $lang->getCurrentLanguage();
							$kielet=array(
							"fi" => "Suomeksi",
							"en" => "in English");
							$q=htmlentities($_SERVER['REQUEST_URI'], ENT_COMPAT, "UTF-8");
							$x=strpos($q, "kieli");
							if($x!==false) $q=substr($q, 0, $x-1);
							if(strpos($q, "?")===false) 
								$q="?";
							else
								$q.="&";
								$q.="kieli";
							$i=0;
							foreach($kielet as $k => $txt){
								$c="";
								if($language==$k) $c="kielival"; 
								if($i>0) echo " &#124; ";
								echo "<a href=\"$q=$k\" alt=\"$txt\" class=\"$c\">$txt</a>";
								$i++;
							}
						?>
					</div>
					
					<?php if (isset($_SESSION['kayttaja_rooli'])) { ?>
						<p id="henkkoht">
							<a <?php if($self=="omat_tiedot.php") echo 'class="kielival"'; ?> href="omat_tiedot.php"><?php echo htmlentities($_SESSION['kayttaja_nimi'], ENT_COMPAT, "UTF-8"); ?></a>
							 | 
							<a href="kirjaudu.php?ulos"><?php echo KIRJAUDU_ULOS; ?></a>
						</p>
					<?php } ?>
					
					<?php if(isset($_SESSION["kayttaja_roolit"]) && sizeof($_SESSION["kayttaja_roolit"]) >= 1){ // viranomaisen rooleja on useita ?>
						<select id="viranomaisen_ryhmakoodit">
							<option <?php if($_SESSION["kayttaja_rooli"]==KAYTTAJARYHMA_HAKIJA){ echo "selected"; } ?> value="<?php echo KAYTTAJARYHMA_HAKIJA; ?>"> <?php echo rooli_hakija; ?> </option>
							<?php for($i=0; $i < sizeof($_SESSION["kayttaja_roolit"]); $i++){ ?>
								<?php 
								$selected = false;
								if($_SESSION["kayttaja_roolit"][$i]["Viranomaisroolin_koodi"]==$_SESSION["kayttaja_rooli"]) $selected = true;	 				 																	
								?>
								<option <?php if($selected){ echo "selected"; } ?> value="<?php echo $_SESSION["kayttaja_roolit"][$i]["Viranomaisroolin_koodi"]; ?>" > <?php echo koodin_selite($_SESSION["kayttaja_roolit"][$i]["Viranomaisroolin_koodi"], $lang->getCurrentLanguage()); ?> </option>
							<?php } ?>
						</select>
					<?php } ?>
				</div>
				
				<h1 id="header"><a href="<?php echo $etusivuOs; ?>"><?php echo KAYTTOLUPAPALVELU; ?></a></h1>
				
		</div> <!-- YLÄ sisältö loppuu -->
		
	</div> <!-- YLÄ koko loppuu -->
	
	<div id="navi">
		<div class="ylamenu">
			<?php 
			if(isset($_SESSION['kayttaja_rooli'])){
				if($_SESSION['kayttaja_rooli'] == "rooli_hakija"){
					include './ui/template/menu_hakija.php';
				} 
				if($_SESSION['kayttaja_rooli'] == "rooli_eettisen_puheenjohtaja" || $_SESSION['kayttaja_rooli'] == "rooli_eettisensihteeri" || $_SESSION['kayttaja_rooli'] == "rooli_kasitteleva" || $_SESSION['kayttaja_rooli'] == "rooli_paattava"){ 
					include './ui/template/menu_viranomainen.php';
				}
				if($_SESSION['kayttaja_rooli'] == "rooli_lausunnonantaja"){ 
					include './ui/template/menu_lausunnonantaja.php';
				}
				if($_SESSION['kayttaja_rooli'] == "rooli_aineistonmuodostaja"){ 
					include './ui/template/menu_aineistonmuodostaja.php';
				}
				if($_SESSION['kayttaja_rooli'] == "rooli_lupapalvelun_paak" || $_SESSION['kayttaja_rooli'] == "rooli_viranomaisen_paak"){ 
					include './ui/template/menu_paakayttaja.php';
				}
			} else {
				include './ui/template/menu_hakija.php';
			}
			?>
		</div>
	</div>

	<div class="tausta" id="klp_tausta">
		<div class="sisalto">