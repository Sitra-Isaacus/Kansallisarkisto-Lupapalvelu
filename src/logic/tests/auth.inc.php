<?php

// authorization for tests

if (!defined('FMAS')) die('Direct access not allowed');

if (!isset($cnt)) $cnt = 0;

$syoteparametrit = array("sahkopostiosoite" => $user_email, "salasana" => $user_pass, "kayttajan_rooli"=>$kayttajan_rooli);
$syoteparametrit = toObjArray($syoteparametrit);

$respObj = $logic->kirjaudu_lupapalveluun($syoteparametrit);

$auth_data = fromObjArray($respObj);
$token = findValRecursive('Suojaustunnus', $auth_data); // this token is used by all the other tests
if (!$token) {
    print_r($respObj);
    die("ERROR: no token (Suojaustunnu) in response object\n");
}
$user_id = $auth_data['KayttajaDTO']['ID'];


/*

kirjaudu_lupapalveluun reponse obj dump:

Array
(
    [0] => stdClass Object
        (
            [Kayttaja_kirjautunut] =>
        )

    [1] => stdClass Object
        (
            [KayttajaDTO] => stdClass Object
                (
                    [ID] => 87
                    [Sukunimi] => Tenhunen
                    [Etunimi] => Henri
                    [Syntymaaika] => 2006-11-15
                    [Kieli_koodi] => fi
                    [Salasana_hash] =>
                    [Puhelinnumero] => 31425
                    [Sahkopostiosoite] => henri.tenhunen@narc.fi
                    [Lisaaja] =>
                    [Lisayspvm] => 2016-11-16 11:15:52
                    [Muokkaaja] =>
                    [Muokkauspvm] =>
                    [Paakayttajan_rooliDTO] =>
                    [Viranomaisen_rooliDTO] =>
                    [Viranomaisen_roolitDTO] =>
                    [SuojausDTO] => stdClass Object
                        (
                            [ID] => 2905
                            [KayttajaDTO] => stdClass Object
                                (
                                    [ID] => 87
                                    [Sukunimi] =>
                                    [Etunimi] =>
                                    [Syntymaaika] =>
                                    [Kieli_koodi] =>
                                    [Salasana_hash] =>
                                    [Puhelinnumero] =>
                                    [Sahkopostiosoite] =>
                                    [Lisaaja] =>
                                    [Lisayspvm] =>
                                    [Muokkaaja] =>
                                    [Muokkauspvm] =>
                                    [Paakayttajan_rooliDTO] =>
                                    [Viranomaisen_rooliDTO] =>
                                    [Viranomaisen_roolitDTO] =>
                                    [SuojausDTO] =>
                                    [SitoumusDTO] =>
                                    [KayttolupaDTO] =>
                                    [HakijaDTO] =>
                                    [Lukemattomien_viestien_maara] =>
                                    [Eraantyvien_kayttolupien_maara] =>
                                    [Lukemattomien_lausuntojen_maara] =>
                                )

                            [Suojaustunnus] => a74e8c3069d33949a045d54e3bce5f9a83d9f4298c34d48b7e55787e9911d57932ff0be57c0a5a6143d39e83285ada523de989b0ba064bc2515aecd8466b108987627ae1efe12a129f11846c0d53
                            [Lisayspvm] =>
                        )

                    [SitoumusDTO] =>
                    [KayttolupaDTO] =>
                    [HakijaDTO] =>
                    [Lukemattomien_viestien_maara] =>
                    [Eraantyvien_kayttolupien_maara] =>
                    [Lukemattomien_lausuntojen_maara] =>
                    [Sahkopostivarmenne] => 42494e653b3e2b5b0ba207fc5898eb69aa2cf7b7ee39f40dc3cdc3e14d47ac98ef366b22e9bffb728bb0f26d65f97277cc4adbef022d0c803de06a4200031a935ee5a1f31599846d7896c37c4d04
                )

        )

)

 */