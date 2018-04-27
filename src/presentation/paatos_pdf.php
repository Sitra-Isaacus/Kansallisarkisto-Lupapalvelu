<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Hakemus
 *
 * Created: 29.3.2017
 */

include_once '_fmas_ui.php';

if(kayttaja_on_kirjautunut() &&
    (isset($_GET['tutkimus_id']) && isset($_GET['hakemus_id']))
) {

	// Muuttujien alustus
	$hakemus_id = null;
	//if(isset($_GET['hakemusversio_id'])) $hakemusversio_id = htmlspecialchars($_GET['hakemusversio_id']);

	if(isset($_GET['tutkimus_id'])) $tutkimus_id = htmlspecialchars($_GET['tutkimus_id']);
	if(isset($_GET["hakemus_id"])) $hakemus_id = htmlspecialchars($_GET['hakemus_id']);

} else {
	header('Location: virhe.php?virhe=Missing params');
	die();
}

try {
	if ($api = createSoapClient()) {
		
		if(isset($hakemus_id) && isset($tutkimus_id)) {

            $vastaus = suorita_logiikkakerroksen_funktio(
                $api,
                "hae_paatos",
                array(
                    "format" => "pdf",
                    "hakemus_id" => $hakemus_id,
                    //"hakemusversio_id"=>$hakemusversio_id,
                    "tutkimus_id" => $tutkimus_id,

                    "kayttajan_rooli"=>$_SESSION["kayttaja_rooli"],
                    "token"=>$_SESSION["kayttaja_token"],
					"generoi_pdf"=>true,
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