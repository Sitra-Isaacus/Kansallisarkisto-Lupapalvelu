<?php

ini_set("default_charset", "UTF-8"); // kommentoi tarvittaessa
//ini_set('display_errors', 'stdout');
ini_set('display_startup_errors', 'true');
error_reporting(-1); 

if (!isset($_SESSION) && basename($_SERVER['PHP_SELF'])!="kirjaudu.php"){
    session_start();
}

if (isset($_SESSION["kayttaja_id"]) && isset($_SESSION["kayttaja_viimeksi_kirjautunut"]) && (time() - $_SESSION["kayttaja_viimeksi_kirjautunut"] > 1800)) { // Käyttäjä nähty viimeksi 30 min sitten    

	session_unset();
	session_destroy();  
	header('Location: kirjaudu.php?ulos');
	die();	
	
}

if(isset($_SESSION["kayttaja_id"])){
	$_SESSION["kayttaja_viimeksi_kirjautunut"] = time(); // Päivitetään viimeisin kirjautuminen
}

?>