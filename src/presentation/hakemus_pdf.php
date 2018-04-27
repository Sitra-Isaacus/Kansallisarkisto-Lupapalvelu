<?php
/*
 * FMAS Käyttölupapalvelu
 *
 * Created: 28.1.2016
 */

include_once '_fmas_ui.php';  
 
$kayt_id = $_SESSION["kayttaja_id"];

if(kayttaja_on_kirjautunut()){
	if(( isset($_GET['hakemusversio_id']) || isset($_POST['hakemusversio_id']) ) && ( isset($_GET['tutkimus_id']) || isset($_POST['tutkimus_id']) )){
		if(isset($_GET['hakemusversio_id'])) $hakemusversio_id = intval($_GET['hakemusversio_id']);
		if(isset($_POST['hakemuversios_id'])) $hakemusversio_id = intval($_POST['hakemusversio_id']);
		if(isset($_GET['tutkimus_id'])) $tutkimus_id = intval($_GET['tutkimus_id']);
		if(isset($_POST['tutkimus_id'])) $tutkimus_id = intval($_POST['tutkimus_id']);
	} else {
		header('Location: virhe.php?virhe=Missing params');
		die();
	}
} else {
	header("Location: kirjaudu.php?ei_kirjauduttu=1");
	die();		
}

try {
    if ($api = createSoapClient()) {

        if(isset($hakemusversio_id) && isset($tutkimus_id)) {

            $vastaus = suorita_logiikkakerroksen_funktio(
                $api,
                "hae_hakemusversio",
                array(
                    "format" => "pdf",
                    "sivu" => "hakemus_perustiedot",
                    "generoi_pdf" => 1,

                    "hakemusversio_id" => $hakemusversio_id,
                    "tutkimus_id" => $tutkimus_id,

                    "kayttajan_rooli" => $_SESSION["kayttaja_rooli"],
                    "token"=>$_SESSION["kayttaja_token"],
					"kayt_kieli"=>$_SESSION["kayttaja_kieli"],
                    "kayt_id"=>$_SESSION["kayttaja_id"],
                )
            );

            $data = $vastaus;
        }
    }
} catch (SoapFault $ex) {
	header('Location: virhe.php?virhe=' . $ex->getMessage());
	die();
}

if (isset($data) && !isset($data['error'])) {
    include './ui/views/pdf_view.php';
} elseif (isset($data)) {	
	header('Location: virhe.php?virhe=' . $data['error']);
	die();		
} else {	
	header('Location: virhe.php?virhe=PDF failure');
	die();		
}

?>