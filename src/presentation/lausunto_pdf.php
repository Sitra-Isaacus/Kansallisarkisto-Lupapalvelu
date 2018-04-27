<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Lausunnonantajan käyttöliittymä (lausunto)
 *
 * Created: 28.1.2016
 */

include_once '_fmas_ui.php';  
 
$kayt_id = $_SESSION["kayttaja_id"];
$sivu = "lausunto_oletus";

if(( isset($_GET['hakemus_id']) || isset($_POST['hakemus_id']) ) && ( isset($_GET['lausunto_id']) || isset($_POST['lausunto_id']) )) {
	if(isset($_GET['hakemus_id'])) $hakemus_id = intval($_GET['hakemus_id']);
	if(isset($_POST['hakemus_id'])) $hakemus_id = intval($_POST['hakemus_id']);
	if(isset($_GET['lausunto_id'])) $lausunto_id = intval($_GET['lausunto_id']);
	if(isset($_POST['lausunto_id'])) $lausunto_id = intval($_POST['lausunto_id']);
} else {
	header('Location: virhe.php?virhe="Missing params');
	die();
}

try {
    if ($api = createSoapClient()) {

        if(isset($hakemus_id) && isset($lausunto_id)) {

            $vastaus = suorita_logiikkakerroksen_funktio(
                $api,
                "hae_lausunto",
                array(
                    "format" => "pdf",
                    "hakemus_id" => $hakemus_id,
                    "lausunto_id" => $lausunto_id,

                    "token"=>$_SESSION["kayttaja_token"],
					"kayt_kieli"=>$_SESSION["kayttaja_kieli"],
                    "kayt_id"=>$_SESSION["kayttaja_id"])
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