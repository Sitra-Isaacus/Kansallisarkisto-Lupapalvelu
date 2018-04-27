<?php
/**
 * usage:
 *
    ssh root@192.168.104.250
    cd /var/www/html/lupapalvelu_demo_v6/logic/tests/
    php tests/phpunit.phar tests/test_suite.php
 *
 * see tests/README.md for details
 */

use PHPUnit\Framework\TestCase;

define('FMAS', true);

include("_test_config.php");
include("func_lib.php");

// Lupapalvelun integraatiotesti
// ------------------------------
// Testitapauksen suorituspolku:
// 1. Hakija 1 & 2 sekä Viranomainen 1 & 2 kirjautuvat sisään
// 2. Hakija 1 luo hakemukset A ja B
// 3. Hakemus B poistetaan
// 4. Haetaan hakemukset hakijoille
// 5. Haetaan uusi hakemus tutkijalle
// 6. Tallennetaan hakemuksen pakolliset tiedot
// 7. Lisätään tutkimusryhmään hakija 2
// 8. Poistetaan hakija 2 tutkimusryhmästä
// 9. Lisätään hakemukseen liitetiedostot
// 10. Poistetaan liitetiedosto
// 11. Hakija esikatselee hakemusta pdf-muodossa
// 12. Lähetetään hakemus lupaviranomaiselle
// 13. Haetaan saapuneet hakemukset viranomaiselle
// 14. Hakemus otetaan viranomaiskäsittelyyn
// 15. Viranomainen 1 lähettää viestin hakijalle ja hakija vastaa siihen
// 16. Viranomainen 1 lähettää lausuntopyynnön viranomainen 2:lle
// 17. Lausuntopyynnöt haetaan lausunnonantajalle
// 18. Avataan uusi lausunto
// 19. Lausuntoon tallennetaan liitetiedosto
// 20. Lausunnon liitetiedosto poistetaan
// 21. Tallennetaan ja julkaistaan lausunto lomake
// 22. Viranomainen 1 tallentaa päätös-lomakkeen


class TestSuite extends TestCase
{

    public $token; // test user 1 able to see pdf docs
    public $user_id;
	public $hakija_id;
	
    public $new_token; // test user 2 with no access to the hakemus
    public $new_user_id;

    public $new_tutkimus_id; // user 1 luoma tutkimus
    public $new_hakemusversio_id; // user 1 luoma hakemus
	
    public function testAuth() {

		// Hakija 1 kirjautuu
        $res = cliRun("test_auth", '{"user_email": "'.USER_EMAIL.'", "user_pass": "'.USER_PASS.'", "format":"json"}');
        $auth_data = fromObjArray($res);
        $this->token = findValRecursive('Suojaustunnus', $auth_data); // this token is used by all the other tests

        $this->assertTrue(is_string($this->token), "Unable to get auth token for test user1, run 'php tests/test_auth.php' for details");
        $this->assertTrue(strlen($this->token)>30, "Invalid token, 'php tests/test_auth.php' for details");

        $this->user_id = $auth_data['KayttajaDTO']['ID'];

		// Hakija 2 kirjautuu
        $res = cliRun("test_auth", '{"user_email": "'.NEW_USER_EMAIL.'", "user_pass": "'.NEW_USER_PASS.'", "format":"json"}');
        $auth_data = fromObjArray($res);
        $this->new_token = findValRecursive('Suojaustunnus', $auth_data); // this token is used by all the other tests

        $this->assertTrue(is_string($this->new_token), "Unable to get auth token for test user2, run 'php tests/test_auth.php' for details");
        $this->assertTrue(strlen($this->new_token)>30, "Invalid token, 'php tests/test_auth.php' for details");

        $this->new_user_id = $auth_data['KayttajaDTO']['ID'];

        // phpunit4 that comes with php5 calls tests statically, so we need this workaround. Cause @depends testAuth doesn't do the trick too
        define('TOKEN', $this->token);
        define('USER_ID', $this->user_id);
        define('NEW_TOKEN', $this->new_token);
        define('NEW_USER_ID', $this->new_user_id);	
		
		// Viranomainen kirjautuu
        $res = cliRun("test_auth", '{"user_email": "'.VIRANOMAINEN_EMAIL.'", "user_pass": "'.VIRANOMAINEN_PASS.'", "format":"json"}');
        $auth_data = fromObjArray($res);	
		$Suojaustunnus = findValRecursive('Suojaustunnus', $auth_data);	

        $this->assertTrue(is_string($Suojaustunnus), "Unable to get auth token for test viranomainen, run 'php tests/test_auth.php' for details");
        $this->assertTrue(strlen($Suojaustunnus)>30, "Invalid token, 'php tests/test_auth.php' for details");	

        define('VIRANOMAINEN_TOKEN', $Suojaustunnus);
        define('VIRANOMAINEN_ID', $auth_data['KayttajaDTO']['ID']);			
		
		// Viranomainen 2 kirjautuu
        $res = cliRun("test_auth", '{"user_email": "'.VIRANOMAINEN2_EMAIL.'", "user_pass": "'.VIRANOMAINEN2_PASS.'", "format":"json"}');
        $auth_data = fromObjArray($res);	
		$Suojaustunnus = findValRecursive('Suojaustunnus', $auth_data);			

        $this->assertTrue(is_string($Suojaustunnus), "Unable to get auth token for test viranomainen, run 'php tests/test_auth.php' for details");
        $this->assertTrue(strlen($Suojaustunnus)>30, "Invalid token, 'php tests/test_auth.php' for details");	
		
        define('VIRANOMAINEN2_TOKEN', $Suojaustunnus);
        define('VIRANOMAINEN2_ID', $auth_data['KayttajaDTO']['ID']);		
		
    }
	
    /**
     * route: /index.php
     */
    public function test_luo_hakemus() {

		// User 1 luo uuden hakemuksen
		$res = cliRun(__FUNCTION__, '{"user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");

        $this->new_tutkimus_id = $res[0]['HakemusversioDTO']['TutkimusDTO']['ID'];
        $this->new_hakemusversio_id = $res[0]['HakemusversioDTO']['ID'];
		$this->hakija_id = $res[0]['HakemusversioDTO']['HakijatDTO_kasittelyoikeutta_hakevat'][0]['ID'];

        $this->assertGreaterThan(0, intval($this->new_tutkimus_id), "'tutkimus_id' not found");
        $this->assertGreaterThan(0, intval($this->new_hakemusversio_id), "'hakemusversio_id' not found");
		$this->assertGreaterThan(0, intval($this->hakija_id), "'hakija_id' not found");

        // phpunit4 that comes with php5 calls tests statically, so we need this workaround. Cause @depends testAuth doesn't do the trick too
        define('NEW_HAKEMUSVERSIO_ID', $this->new_hakemusversio_id);
        define('NEW_TUTKIMUS_ID', $this->new_tutkimus_id);
		define('HAKIJA_ID', $this->hakija_id);

		// User 1 luo myöhemmin poistettavan hakemuksen
		$res = cliRun(__FUNCTION__, '{"user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		
		
        $this->assertGreaterThan(0, intval($res[0]['HakemusversioDTO']['TutkimusDTO']['ID']), "'tutkimus_id' not found");
        $this->assertGreaterThan(0, intval($res[0]['HakemusversioDTO']['ID']), "'hakemusversio_id' not found");
		$this->assertGreaterThan(0, intval($res[0]['HakemusversioDTO']['HakijatDTO_kasittelyoikeutta_hakevat'][0]['ID']), "'hakija_id' not found");	

        define('POISTETTAVA_HAKEMUSVERSIO_ID', $res[0]['HakemusversioDTO']['ID']);
        define('POISTETTAVA_TUTKIMUS_ID', $res[0]['HakemusversioDTO']['TutkimusDTO']['ID']);			
		
    }
	
    /**
	 @depends test_luo_hakemus
     * route: /index.php
     */	
	public function test_poista_hakemus(){
		
		// User 2 yrittää poistaa user ykkösen hakemuksen
        $res = cliRun(__FUNCTION__, '{"hakemusversio_id": "'.POISTETTAVA_HAKEMUSVERSIO_ID.'", "user_id": "'.NEW_USER_ID.'", "token": "'.NEW_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php $par' for details");		      				
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 2 ei saanut auth. erroria");		
		
		// User 1 poistaa oman hakemuksen
        $res = cliRun(__FUNCTION__, '{"hakemusversio_id": "'.POISTETTAVA_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php $par' for details");

        $Hakemus_poistettu = searchArrayValueByKey($res, "Hakemus_poistettu");
        $this->assertTrue($Hakemus_poistettu, "'Hakemus_poistettu' should br TRUE after we have deleted hakemus version");		
		
	}

    /**
	 @depends test_luo_hakemus
     * route: /index.php
     */
    public function test_hae_hakemukset_tutkijalle() {

		// Uuden hakemuksen täytyy näkyä user ykköselle
        $res = cliRun(__FUNCTION__, '{"user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");

        $Keskeneraiset = $res[0]['HakemusversiotDTO']['Keskeneraiset'];
        $new_hakemusversio_found = false;
		
        foreach ($Keskeneraiset as $hakemusversio) {
            if ($hakemusversio['ID'] == NEW_HAKEMUSVERSIO_ID) {

                if (is_object($hakemusversio['TutkimusDTO'])) $hakemusversio['TutkimusDTO'] = std2ArrayRecursive($hakemusversio['TutkimusDTO']);
                if ($hakemusversio['TutkimusDTO']['ID'] == NEW_TUTKIMUS_ID) $new_hakemusversio_found = true;

            }
        }

        $this->assertTrue($new_hakemusversio_found, "new hakemusversio not found in the list");

		// User ykkösen luoma hakemus ei saa näkyä user kakkoselle
        $res = cliRun(__FUNCTION__, '{"user_id": "'.NEW_USER_ID.'", "token": "'.NEW_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		

        $Keskeneraiset = $res[0]['HakemusversiotDTO']['Keskeneraiset'];
        $new_hakemusversio_found = false;
		
        foreach ($Keskeneraiset as $hakemusversio) {
            if ($hakemusversio['ID'] == NEW_HAKEMUSVERSIO_ID) {

                if (is_object($hakemusversio['TutkimusDTO'])) $hakemusversio['TutkimusDTO'] = std2ArrayRecursive($hakemusversio['TutkimusDTO']);
                if ($hakemusversio['TutkimusDTO']['ID'] == NEW_TUTKIMUS_ID) $new_hakemusversio_found = true;

            }
        }

        $this->assertFalse($new_hakemusversio_found, "new hakemusversio found in the list");
		
    }	
	
    /**
	 @depends test_luo_hakemus
     * route: /hakemus.php?tutkimus_id=737&hakemusversio_id=x
     */
    public function test_hae_hakemusversio() {

		// Hakija ykkösellä on käyttöoikeus luomaansa hakemukseen
        $res = cliRun(__FUNCTION__, '{"tutkimus_id": "'.NEW_TUTKIMUS_ID.'","hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'","user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");

        //debug_log($res); // This goes to /tmp/debug_test.log. The object is about 11M

        $this->assertEquals(NEW_HAKEMUSVERSIO_ID, intval($res[3]['HakemusversioDTO']['ID']), "'hakemusversio id' not found");
		$this->assertEquals(NEW_TUTKIMUS_ID, intval($res[3]['HakemusversioDTO']['TutkimusDTO']['ID']), "'hakemusversio id' not found");
		$this->assertGreaterThan(0, intval($res[3]['HakemusversioDTO']['LomakeDTO']['ID']), "'fk_lomake' not found");

		// Käyttöoikeuden tarkistus: hakija kakkosella ei saa olla käyttöoikeutta hakija ykkösen luomaan hakemukseen
        $res = cliRun(__FUNCTION__, '{"tutkimus_id": "'.NEW_TUTKIMUS_ID.'","hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'","user_id": "'.NEW_USER_ID.'", "token": "'.NEW_TOKEN.'", "format":"json"}');
	    $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");				
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 2 ei saanut auth. erroria");
		
    }	

    /**
	 @depends test_luo_hakemus	
     * route: POST /hakemus.php?sivu=hakemus_perustiedot&tutkimus_id=y&hakemusversio_id=x
     */
    public function test_tallenna_hakemus() {

		// Hakija 2 yrittää tallentaa hakemusta A
        $res = cliRun(__FUNCTION__, '{"tallennuskohde_kentta": "Tutkimuksen_nimi", "tallennuskohde": "lomake_tutkimuksen_nimi", "tallennuskohde_arvo": "Tutkimus '.generateRandomString(5).'", "tallennuskohde_id": "'.NEW_HAKEMUSVERSIO_ID.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.NEW_USER_ID.'", "token": "'.NEW_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 2 ei saanut auth. erroria");
		
		// Hakija ykkösen tallennus alkaa:
		// PERUSTIEDOT
	
		// Tutk. nimi
        $res = cliRun(__FUNCTION__, '{"tallennuskohde_kentta": "Tutkimuksen_nimi", "tallennuskohde": "lomake_tutkimuksen_nimi", "tallennuskohde_arvo": "Tutkimus '.generateRandomString(5).'", "tallennuskohde_id": "'.NEW_HAKEMUSVERSIO_ID.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");			
	
		// Tutk. nimi englanniksi
        $res = cliRun(__FUNCTION__, '{"hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");

		// Tutk. arvioitu kok. kesto alku
        $res = cliRun(__FUNCTION__, '{"tallennuskohde_arvo": "8.3.2018", "tallennuskohde_id": "7", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");		

		// Tutk. arvioitu kok. kesto loppu
        $res = cliRun(__FUNCTION__, '{"tallennuskohde_arvo": "8.3.2020", "tallennuskohde_id": "8", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");			
		
		// Lyhyt kuvaus tutkimuksesta
        $res = cliRun(__FUNCTION__, '{"tallennuskohde_id": "10", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");			

		// Julkinen kuvaus tutkimuksesta suomeksi
        $res = cliRun(__FUNCTION__, '{"tallennuskohde_id": "842", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");

		// Kuinka tulokset aiotaan julkaista?
        $res = cliRun(__FUNCTION__, '{"tallennuskohde_id": "26", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");
		
		// ORGANISAATIOTIEDOT
		
		// Rekisterinpitäjä 
        $res = cliRun(__FUNCTION__, '{"tallennettavat_tiedot": "hakemus_organisaatiotiedot", "tallennuskohde_kentta": "Rekisterinpitaja", "tallennuskohde_arvo": "1", "tallennuskohde": "organisaatio", "tallennuskohde_id": "0", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");

		// TUTKIMUSRYHMA
		// HAKIJA
		
		// Organisaatio 
        $res = cliRun(__FUNCTION__, '{"tallennettavat_tiedot": "hakemus_tutkimusryhma", "tallennuskohde_kentta": "Organisaatio", "tallennuskohde": "hakijan_tiedot", "tallennuskohde_id": "'.HAKIJA_ID.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");			

		// Oppiarvo 
        $res = cliRun(__FUNCTION__, '{"tallennettavat_tiedot": "hakemus_tutkimusryhma", "tallennuskohde_kentta": "Oppiarvo", "tallennuskohde": "hakijan_tiedot", "tallennuskohde_id": "'.HAKIJA_ID.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");	

		// Rooli 
        $res = cliRun(__FUNCTION__, '{"tallennuskohde_arvo": "rooli_hak_yht", "tallennettavat_tiedot": "hakemus_tutkimusryhma", "tallennuskohde_kentta": "Hakijan_roolin_koodi", "tallennuskohde": "hakijan_rooli", "tallennuskohde_id": "'.HAKIJA_ID.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");

		// Salassapitositoumus 
        $res = cliRun(__FUNCTION__, '{"tallennuskohde_arvo": "1", "tallennettavat_tiedot": "hakemus_tutkimusryhma", "tallennuskohde": "sitoumus", "tallennuskohde_id": "'.USER_ID.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");
					
		// TUTKIMUSAINEISTO #1
		
		// Kohorttitutkimus
        $res = cliRun(__FUNCTION__, '{"haetun_aineiston_indeksi": "0", "tallennettavat_tiedot": "hakemus_aineisto", "tallennuskohde_nimi": "tutkimusasetelma", "tallennuskohde_arvo": "85", "tallennuskohde_id": "85", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");		

		// Ei käytetä viitehenkilöitä
        $res = cliRun(__FUNCTION__, '{"haetun_aineiston_indeksi": "0", "tallennettavat_tiedot": "hakemus_aineisto", "tallennuskohde_nimi": "viitehenkiloiden_kaytto", "tallennuskohde_arvo": "92", "tallennuskohde_id": "92", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");

		// Ei tunnisteta aik. aineistosta
        $res = cliRun(__FUNCTION__, '{"haetun_aineiston_indeksi": "0", "tallennettavat_tiedot": "hakemus_aineisto", "tallennuskohde_nimi": "kohdejoukon_tunnistus", "tallennuskohde_arvo": "105", "tallennuskohde_id": "105", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");

		// Tietolähde tunnistetaan rekistereistä
        $res = cliRun(__FUNCTION__, '{"haetun_aineiston_indeksi": "0", "tallennettavat_tiedot": "hakemus_aineisto", "tallennuskohde_nimi": "checkbox-980", "tallennuskohde_arvo": "981", "tallennuskohde_id": "981", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");
		
		// Rekistereistä määriteltävä kohdejoukko
        $res = cliRun(__FUNCTION__, '{"tallennuskohde": "haettu_luvan_kohde", "tallennuskohde_kentta": "FK_Luvan_kohde", "haetun_aineiston_indeksi": "0", "tallennettavat_tiedot": "hakemus_aineisto", "tallennuskohde_nimi": "haettu_kohde_kohdejoukko", "tallennuskohde_arvo": "42", "tallennuskohde_id": "0", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");

		// Poimittavat muuttujat
        $res = cliRun(__FUNCTION__, '{"tallennuskohde": "haettu_luvan_kohde", "tallennuskohde_kentta": "FK_Luvan_kohde", "haetun_aineiston_indeksi": "0", "tallennettavat_tiedot": "hakemus_aineisto", "tallennuskohde_nimi": "haettu_kohde_kohdejoukko_muuttujat", "tallennuskohde_arvo": "44", "tallennuskohde_id": "0", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Hakemusversio_tallennettu = searchArrayValueByKey($res, "Hakemusversio_tallennettu");
        $this->assertTrue($Hakemusversio_tallennettu, "'Hakemusversio_tallennettu' should br TRUE after we update hakemus");		
		
    }

    /**
	 @depends test_tallenna_hakemus
     * route: /presentation/hakemus.php?sivu=hakemus_tutkimusryhma&tutkimus_id=y&hakemusversio_id=x
     */
    public function test_luo_hakija() {

        $res = cliRun(__FUNCTION__, '{"sahkopostiosoite": "'.NEW_USER_EMAIL.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");

        $this->assertEquals(1, intval($res[0]["sahkopostikutsu_lahetetty"]), "'res[0][\"sahkopostikutsu_lahetetty\"]' should equal to 1");

        $KayttajaDTO_Uusi_hakija = $res[2]["KayttajaDTO_Uusi_hakija"];
		
        $this->assertTrue(is_array($KayttajaDTO_Uusi_hakija), "'KayttajaDTO_Uusi_hakija' not found");
        $this->assertGreaterThan(0, intval($KayttajaDTO_Uusi_hakija['ID']), "'KayttajaDTO_Uusi_hakija' is invalid");
		
		if(isset($res[3]["HakijaDTO_Uusi"])){
			
			$HakijaDTO_Uusi = $res[3]["HakijaDTO_Uusi"];

			$this->assertGreaterThan(0, intval($HakijaDTO_Uusi['ID']), "'HakijaDTO_Uusi' is invalid");
			$this->assertRegExp('/^.+\@\S+\.\S+$/', $HakijaDTO_Uusi['Sahkopostiosoite'], "'Sahkopostiosoite' is not valid email address");
					
			if(intval($HakijaDTO_Uusi['ID']) > 0) define('NEW_HAKIJA_ID', $HakijaDTO_Uusi['ID']);
			define('NEW_HAKIJA_EMAIL', $HakijaDTO_Uusi['Sahkopostiosoite']);			
			
		}
		
    }	
	
    /**
	 @depends test_luo_hakija
     * route: /presentation/hakemus.php?sivu=hakemus_tutkimusryhma&tutkimus_id=y&hakemusversio_id=x
     */
    public function test_poista_hakija_tutkimusryhmasta() {

		// Hakija 2 poistetaan tutkimusryhmästä	
        $res = cliRun(__FUNCTION__, '{"poistettavan_kayttaja_id": "'.NEW_USER_ID.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");

        $Hakija_poistettu = searchArrayValueByKey($res, "Hakija_poistettu");
        $this->assertTrue($Hakija_poistettu, "'Hakija_poistettu' should br TRUE after we update hakemus");
		
    }	
	
    /**
	 @depends test_tallenna_hakemus
     * route: /hakemus.php?tutkimus_id=737&hakemusversio_id=x
     */
    public function test_tallenna_hakemusversioon_liitetiedosto() {

		// Hakija 2 yrittää lisätä liitteen		
		$file = 'tests/tutkimussuunnitelma.txt';
		$data = 'Unit testing';
		file_put_contents($file, $data);

		$tiedosto = file_get_contents($file);
		$tiedosto_encoded = base64_encode($tiedosto);
		
        $res = cliRun(__FUNCTION__, '{"liitteen_koodi": "1", "tiedosto": "'.$tiedosto_encoded.'", "name": "'.$file.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'","user_id": "'.NEW_USER_ID.'", "token": "'.NEW_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 2 ei saanut auth. erroria");
		
		// Hakija 1 lisää liitteet	
		// Tutkimussuunnitelma	
        $res = cliRun(__FUNCTION__, '{"liitteen_koodi": "1", "tiedosto": "'.$tiedosto_encoded.'", "name": "'.$file.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'","user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Liitetiedosto_tallennettu = searchArrayValueByKey($res, "Liitetiedosto_tallennettu");
        $this->assertTrue($Liitetiedosto_tallennettu, "'Liitetiedosto_tallennettu' should br TRUE after we update hakemus");
		$Uusi_liite_ID = searchArrayValueByKey($res, "Uusi_liite_ID");	
		$this->assertGreaterThan(0, intval($Uusi_liite_ID), "'Uusi_liite_ID' is invalid");
		
		// Rekisteriseloste	
		$file = 'tests/rekisteriseloste.txt';
		$data = 'Unit testing';
		file_put_contents($file, $data);

		$tiedosto = file_get_contents($file);
		$tiedosto_encoded = base64_encode($tiedosto);
		
        $res = cliRun(__FUNCTION__, '{"tiedosto": "'.$tiedosto_encoded.'", "name": "'.$file.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'","user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "liitteen_koodi": "39", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Liitetiedosto_tallennettu = searchArrayValueByKey($res, "Liitetiedosto_tallennettu");
        $this->assertTrue($Liitetiedosto_tallennettu, "'Liitetiedosto_tallennettu' should br TRUE after we update hakemus");
		$Uusi_liite_ID = searchArrayValueByKey($res, "Uusi_liite_ID");	
		$this->assertGreaterThan(0, intval($Uusi_liite_ID), "'Uusi_liite_ID' is invalid");		
		
		// Julkaisusuunnitelma	
		$file = 'tests/julkaisusuunnitelma.txt';
		$data = 'Unit testing';
		file_put_contents($file, $data);

		$tiedosto = file_get_contents($file);
		$tiedosto_encoded = base64_encode($tiedosto);
		
        $res = cliRun(__FUNCTION__, '{"tiedosto": "'.$tiedosto_encoded.'", "name": "'.$file.'", "hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'","user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "liitteen_koodi": "2", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $Liitetiedosto_tallennettu = searchArrayValueByKey($res, "Liitetiedosto_tallennettu");
        $this->assertTrue($Liitetiedosto_tallennettu, "'Liitetiedosto_tallennettu' should br TRUE after we update hakemus");
		$Uusi_liite_ID = searchArrayValueByKey($res, "Uusi_liite_ID");	
		$this->assertGreaterThan(0, intval($Uusi_liite_ID), "'Uusi_liite_ID' is invalid");

		define('POISTETTAVA_LIITE_ID', $Uusi_liite_ID);	
		
    }
	
    /**
	 @depends test_tallenna_hakemusversioon_liitetiedosto
     * route: /hakemus.php?tutkimus_id=737&hakemusversio_id=x
     */
    public function test_poista_hakemusversion_liitetiedosto() {

		// Hakija 2 yrittää poistaa liitteen
        $res = cliRun(__FUNCTION__, '{"liite_id": "'.POISTETTAVA_LIITE_ID.'","hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'","user_id": "'.NEW_USER_ID.'", "token": "'.NEW_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");			
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 2 ei saanut auth. erroria");
	
		// Hakija 1 poistaa liitteen
        $res = cliRun(__FUNCTION__, '{"liite_id": "'.POISTETTAVA_LIITE_ID.'","hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'","user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		

        $Liitetiedosto_poistettu = searchArrayValueByKey($res, "Liitetiedosto_poistettu");
        $this->assertTrue($Liitetiedosto_poistettu, "'Liitetiedosto_poistettu' should br TRUE after we update hakemus");		
		
    }	
	
    /**
	 @depends test_tallenna_hakemus
     * route: /hakemus.php?tutkimus_id=737&hakemusversio_id=x
     */
    public function test_pdf_hakemus() {

		// Hakija 2 yrittää muodostaa pdf:n
        $res = cliRun(__FUNCTION__, '{"tutkimus_id": "'.NEW_TUTKIMUS_ID.'","hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'","user_id": "'.NEW_USER_ID.'", "token": "'.NEW_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 2 ei saanut auth. erroria");
	
		// Hakija 1 muodostaa pdf:n
        $res = cliRun(__FUNCTION__, '{"tutkimus_id": "'.NEW_TUTKIMUS_ID.'","hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'","user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");

        $pdf_content = searchArrayValueByKey($res, 'pdf_content');
        $size = strlen($pdf_content);

        $this->assertGreaterThan(10000, $size, "Generated pdf document supposed to be bigger than 10kb. Size is {$size}.\nrun 'php tests/test_pdf_paatos.php' for details");

    }	

    /**
	 @depends test_tallenna_hakemus
	 @depends test_poista_hakemusversion_liitetiedosto	
	 @depends test_poista_hakija_tutkimusryhmasta	 
     * route: /hakemus.php?tutkimus_id=737&hakemusversio_id=x
     */
    public function test_laheta_hakemus() {

		// Hakija 2 yrittää lähettää hakemuksen
        $res = cliRun(__FUNCTION__, '{"tutkimus_id": "'.NEW_TUTKIMUS_ID.'","hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.NEW_USER_ID.'", "token": "'.NEW_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 2 ei saanut auth. erroria");
		
		// Hakija 1 lähettää hakemuksen
        $res = cliRun(__FUNCTION__, '{"tutkimus_id": "'.NEW_TUTKIMUS_ID.'","hakemusversio_id": "'.NEW_HAKEMUSVERSIO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");

        $Hakemus_lahetetty = searchArrayValueByKey($res, "Hakemus_lahetetty");
        $this->assertTrue($Hakemus_lahetetty, "'Hakemus_lahetetty' should br TRUE after we update hakemus");

        $Email_lahetetty_yhteyshenkilolle = searchArrayValueByKey($res, "Email_lahetetty_yhteyshenkilolle");
        $this->assertTrue($Email_lahetetty_yhteyshenkilolle, "'Email_lahetetty_yhteyshenkilolle' should br TRUE after we update hakemus");		
		
    }

    /**
	 @depends test_laheta_hakemus 
     */	
	public function test_hae_saapuneet_hakemukset_viranomaiselle(){
		
		// Viranomainen hakee hakemukset
        $res = cliRun(__FUNCTION__, '{"user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		

		$HakemuksetDTO_Uudet = $res[1]["HakemuksetDTO"]["Uudet"];
        $new_hakemus_found = false;
	
        foreach ($HakemuksetDTO_Uudet as $key => $hakemusDTO) {
			if (isset($hakemusDTO["HakemusversioDTO"]->ID) && isset($hakemusDTO["HakemusversioDTO"]->TutkimusDTO->ID) && $hakemusDTO["HakemusversioDTO"]->ID==NEW_HAKEMUSVERSIO_ID && $hakemusDTO["HakemusversioDTO"]->TutkimusDTO->ID==NEW_TUTKIMUS_ID){
				$new_hakemus_found = true;	
				define('NEW_HAKEMUS_ID', $hakemusDTO["ID"]);
			} 	
        }

        $this->assertTrue($new_hakemus_found, "new hakemusversio not found in the list");
		
		// Hakija yrittää hakea hakemukset
        $res = cliRun(__FUNCTION__, '{"user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 1 ei saanut auth. erroria");			
		
	}

    /**
	 @depends test_hae_saapuneet_hakemukset_viranomaiselle 
     */		
	public function test_ota_hakemus_viranomaiskasittelyyn(){

		// Hakija yrittää ottaa käsittelyyn
        $res = cliRun(__FUNCTION__, '{"kasittelija": "'.USER_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 1 ei saanut auth. erroria");	
		
		// Viranomainen ottaa hakemuksen käsittelyyn
        $res = cliRun(__FUNCTION__, '{"kasittelija": "'.VIRANOMAINEN_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
		
        $Hakemus_kasittelyssa = searchArrayValueByKey($res, "Hakemus_kasittelyssa");
        $this->assertTrue($Hakemus_kasittelyssa, "'Hakemus_kasittelyssa' should be TRUE");		
		
	}

    /**
	 @depends test_ota_hakemus_viranomaiskasittelyyn 
     */		
	public function test_laheta_viesti(){
		
		// Viranomainen lähettää viestin
        $res = cliRun(__FUNCTION__, '{"vastaanottaja": "'.USER_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		

        $Viesti_lahetetty = searchArrayValueByKey($res, "Viesti_lahetetty");
        $this->assertTrue($Viesti_lahetetty, "'Viesti_lahetetty' should be TRUE");	

        $Lahetetty_viesti_ID = searchArrayValueByKey($res, "Lahetetty_viesti_ID");
		$this->assertGreaterThan(0, intval($Lahetetty_viesti_ID), "'Lahetetty_viesti_ID' not found");
		
		// Hakija vastaa viestiin
        $res = cliRun(__FUNCTION__, '{"parent_id": "'. $Lahetetty_viesti_ID .'", "kayttajan_rooli": "rooli_hakija", "on_vastaus": "'. true .'", "vastaanottaja": "'.VIRANOMAINEN_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");			

        $Viesti_lahetetty = searchArrayValueByKey($res, "Viesti_lahetetty");
        $this->assertTrue($Viesti_lahetetty, "'Viesti_lahetetty' should be TRUE");	
		
	}
	
    /**
	 @depends test_ota_hakemus_viranomaiskasittelyyn 
     */		
	public function test_laheta_lausuntopyynto(){
		
		// Viranomainen 1 lähettää lausuntopyynnön 
        $res = cliRun(__FUNCTION__, '{"laus_antaja": "'.VIRANOMAINEN2_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		

        $Lausuntopyynto_lahetetty = searchArrayValueByKey($res, "Lausuntopyynto_lahetetty");
        $this->assertTrue($Lausuntopyynto_lahetetty, "'Lausuntopyynto_lahetetty' should be TRUE");
		
		$this->assertGreaterThan(0, intval($res[2]["LausuntopyyntoDTO"]["LausuntoDTO"]["ID"]), "'Lausunto ID' is invalid");
		define('NEW_LAUSUNTO_ID', $res[2]["LausuntopyyntoDTO"]["LausuntoDTO"]["ID"]);
	
        $Email_lahetetty_lausunnonantajalle = searchArrayValueByKey($res, "Email_lahetetty_lausunnonantajalle");
        $this->assertTrue($Email_lahetetty_lausunnonantajalle, "'Email_lahetetty_lausunnonantajalle' should br TRUE after we update hakemus");	

		// Hakija yrittää lähettää lausuntopyynnön
        $res = cliRun(__FUNCTION__, '{"laus_antaja": "'.VIRANOMAINEN2_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 1 ei saanut auth. erroria");	
		
	}
	
    /**
	 @depends test_laheta_lausuntopyynto 
     */		
	public function test_hae_saapuneet_lausuntopyynnot(){
		
		// Viranomainen 2 hakee lausuntopyynnöt
        $res = cliRun(__FUNCTION__, '{"user_id": "'.VIRANOMAINEN2_ID.'", "token": "'.VIRANOMAINEN2_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");			
		
		$LausuntopyynnotDTO = $res[1]["LausuntopyynnotDTO"];
        $new_lausunto_found = false;

		foreach ($LausuntopyynnotDTO as $key => $lausuntopyyntoDTO) {		
			if($lausuntopyyntoDTO["LausuntoDTO"]["ID"]==NEW_LAUSUNTO_ID) $new_lausunto_found = true;		
		}	
		
		$this->assertTrue($new_lausunto_found, "new lausunto not found in the list");
		
		// Hakija yrittää hakea lausuntopyynnöt
        $res = cliRun(__FUNCTION__, '{"user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 1 ei saanut auth. erroria");
		
	}

    /**
	 @depends test_hae_saapuneet_lausuntopyynnot 
     */	
    public function test_lausunto() {

		// Viranomainen 2 hakee lausunnon
        $res = cliRun(__FUNCTION__, '{"lausunto_id": "'.NEW_LAUSUNTO_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN2_ID.'", "token": "'.VIRANOMAINEN2_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		
		
		$res = std2ArrayRecursive($res);
        $this->assertGreaterThan(0, intval($res[1]["LausuntoDTO"]["ID"]), "'Lausunto ID' is invalid");	
		
		// Hakija 2 yrittää hakea lausunnon
		$res = cliRun(__FUNCTION__, '{"kayttajan_rooli": "rooli_kasitteleva", "lausunto_id": "'.NEW_LAUSUNTO_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.NEW_USER_ID.'", "token": "'.NEW_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");	
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 2 ei saanut auth. erroria");	
		
    }	

    /**
	 @depends test_lausunto 
     */		
	public function test_tallenna_lausunnon_liitetiedosto(){
		
		// Viranomainen 2 tallentaa liitteen
        $res = cliRun(__FUNCTION__, '{"lausunto_id": "'.NEW_LAUSUNTO_ID.'", "user_id": "'.VIRANOMAINEN2_ID.'", "token": "'.VIRANOMAINEN2_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");					
		$Liitetiedosto_tallennettu = searchArrayValueByKey($res, "Liitetiedosto_tallennettu");
		$this->assertTrue($Liitetiedosto_tallennettu, "'Liitetiedosto_tallennettu' should br TRUE");		
		$Liitetiedosto_ID = searchArrayValueByKey($res, "Liitetiedosto_ID");
		$this->assertGreaterThan(0, intval($Liitetiedosto_ID), "'Lausunto ID' is invalid");

		// Viranomainen 2 tallentaa poistettavan liitteen
        $res = cliRun(__FUNCTION__, '{"lausunto_id": "'.NEW_LAUSUNTO_ID.'", "user_id": "'.VIRANOMAINEN2_ID.'", "token": "'.VIRANOMAINEN2_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");			
		$Liitetiedosto_tallennettu = searchArrayValueByKey($res, "Liitetiedosto_tallennettu");
		$this->assertTrue($Liitetiedosto_tallennettu, "'Liitetiedosto_tallennettu' should br TRUE");
		$Liitetiedosto_ID = searchArrayValueByKey($res, "Liitetiedosto_ID");
		$this->assertGreaterThan(0, intval($Liitetiedosto_ID), "'Lausunto ID' is invalid");
		
		define('POISTETTAVA_LAUSUNTO_LIITE_ID', $Liitetiedosto_ID);
		
		// Hakija 1 yrittää tallentaa liitteen
        $res = cliRun(__FUNCTION__, '{"lausunto_id": "'.NEW_LAUSUNTO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");			
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 1 ei saanut auth. erroria");	
		
	}
	
    /**
	 @depends test_tallenna_lausunnon_liitetiedosto 
     */		
	public function test_poista_lausunnon_liitetiedosto(){
		
		// Hakija 1 yrittää poistaa liitteen
        $res = cliRun(__FUNCTION__, '{"liite_id": "'.POISTETTAVA_LAUSUNTO_LIITE_ID.'", "lausunto_id": "'.NEW_LAUSUNTO_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");					
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 1 ei saanut auth. erroria");	
		
		// Viranomainen 2 poistaa liitteen
        $res = cliRun(__FUNCTION__, '{"liite_id": "'.POISTETTAVA_LAUSUNTO_LIITE_ID.'", "lausunto_id": "'.NEW_LAUSUNTO_ID.'", "user_id": "'.VIRANOMAINEN2_ID.'", "token": "'.VIRANOMAINEN2_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");			
		$Liitetiedosto_poistettu = searchArrayValueByKey($res, "Liitetiedosto_poistettu");
		$this->assertTrue($Liitetiedosto_poistettu, "'Liitetiedosto_poistettu' should br TRUE");
		
	}

    /**
	 @depends test_lausunto 
     */		
	public function test_tallenna_lausunto_lomake(){
		
		// Hakija 1 yrittää tallentaa 
        $res = cliRun(__FUNCTION__, '{"tallennuskohde": "lausunto", "tallennuskohde_kentta": "Lausunto_koodi", "tallennuskohde_nimi": "johtopaatos", "tallennuskohde_arvo": "laus_kylla", "tallennuskohde_id": "114", "lausunto_id": "'.NEW_LAUSUNTO_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");
        $this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 1 ei saanut auth. erroria");
		
		// Viranomainen 2 tallentaa johtopäätöksen ja julkaisee lausunnon
        $res = cliRun(__FUNCTION__, '{"tallennuskohde": "lausunto", "tallennuskohde_kentta": "Lausunto_koodi", "tallennuskohde_nimi": "johtopaatos", "tallennuskohde_arvo": "laus_kylla", "tallennuskohde_id": "'.NEW_LAUSUNTO_ID.'", "lausunto_id": "'.NEW_LAUSUNTO_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN2_ID.'", "token": "'.VIRANOMAINEN2_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");				
		$Lausunto_tallennettu = searchArrayValueByKey($res, "Lausunto_tallennettu");		
        $this->assertTrue($Lausunto_tallennettu, "'Lausunto_tallennettu' should br TRUE");

        $res = cliRun(__FUNCTION__, '{"tallennuskohde": "lausunto", "tallennuskohde_kentta": "Lausunto_julkaistu", "tallennuskohde_nimi": "", "tallennuskohde_arvo": "1", "tallennuskohde_id": "'.NEW_LAUSUNTO_ID.'", "lausunto_id": "'.NEW_LAUSUNTO_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN2_ID.'", "token": "'.VIRANOMAINEN2_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");				
		$Lausunto_tallennettu = searchArrayValueByKey($res, "Lausunto_tallennettu");		
        $this->assertTrue($Lausunto_tallennettu, "'Lausunto_tallennettu' should br TRUE");
		
	}

    /**
	 @depends test_tallenna_lausunto_lomake 
     */		
    public function test_pdf_lausunto() {

		// Viranomainen 1 hakee lausunnon
        $res = cliRun(__FUNCTION__, '{"hakemus_id": "'.NEW_HAKEMUS_ID.'", "lausunto_id": "'.NEW_LAUSUNTO_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");

        $pdf_content = searchArrayValueByKey($res, 'pdf_content');
        $size = strlen($pdf_content);

        $this->assertGreaterThan(5000, $size, "Generated pdf document supposed to be bigger than 5kb. Size is {$size}.\nrun 'php tests/test_pdf_lausunto.php' for details");

		// Hakija 2 yrittää hakea lausunnon
        $res = cliRun(__FUNCTION__, '{"hakemus_id": "'.NEW_HAKEMUS_ID.'", "lausunto_id": "'.NEW_LAUSUNTO_ID.'", "user_id": "'.NEW_USER_ID.'", "token": "'.NEW_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");		
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 2 ei saanut auth. erroria");
		
    }	
	
    /**
	 @depends test_tallenna_lausunto_lomake 
     */		
	public function test_paatos(){
		
        $res = cliRun(__FUNCTION__, '{"hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");

		$res = std2ArrayRecursive($res);
		$this->assertGreaterThan(0, intval($res[8]["PaatosDTO"]["ID"]), "'Paatos ID' is invalid");
		
		define('NEW_PAATOS_ID', $res[8]["PaatosDTO"]["ID"]);
		
	}
	
    /**
	 @depends test_paatos 
     */		
	public function test_tallenna_paatos_lomake(){
		
		// Viranomainen 1 tallentaa päätöslomakkeen pakolliset tiedot
		// Päätöspohja
		$res = cliRun(__FUNCTION__, '{"tallennuskohde": "paatos", "tallennuskohde_kentta": "FK_Lomake", "tallennuskohde_arvo": "44", "tallennuskohde_id": "'.NEW_PAATOS_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");				
		$Paatos_tallennettu = searchArrayValueByKey($res, "Paatos_tallennettu");		
        $this->assertTrue($Paatos_tallennettu, "'Paatos_tallennettu' should be TRUE");
		
		// Päättäjät
		$res = cliRun(__FUNCTION__, '{"tallennuskohde": "paattaja", "tallennuskohde_kentta": "FK_Lomake", "tallennuskohde_arvo": "'.VIRANOMAINEN_ID.'", "tallennuskohde_id": "'.VIRANOMAINEN_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");				
		$Paatos_tallennettu = searchArrayValueByKey($res, "Paatos_tallennettu");		
        $this->assertTrue($Paatos_tallennettu, "'Paatos_tallennettu' should be TRUE");		
		
		// Päätös 
		$res = cliRun(__FUNCTION__, '{"tallennuskohde_nimi": "paatos", "tallennuskohde": "paatos", "tallennuskohde_kentta": "Alustava_paatos", "tallennuskohde_arvo": "paat_tila_hyvaksytty", "tallennuskohde_id": "'.NEW_PAATOS_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");				
		$Paatos_tallennettu = searchArrayValueByKey($res, "Paatos_tallennettu");		
        $this->assertTrue($Paatos_tallennettu, "'Paatos_tallennettu' should be TRUE");	
		
		// Luvan voim. olo
		$res = cliRun(__FUNCTION__, '{"tallennuskohde_nimi": "lupa_voimassa_pvm", "tallennuskohde": "paatos", "tallennuskohde_kentta": "Lakkaamispvm", "tallennuskohde_arvo": "30.4.2038", "tallennuskohde_id": "'.NEW_PAATOS_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");				
		$Paatos_tallennettu = searchArrayValueByKey($res, "Paatos_tallennettu");		
        $this->assertTrue($Paatos_tallennettu, "'Paatos_tallennettu' should be TRUE");			
		
		// Lupa myönnetään tutkijalle
		$res = cliRun(__FUNCTION__, '{"tallennuskohde": "kayttolupa", "tallennuskohde_kentta": "FK_Kayttaja", "tallennuskohde_arvo": "'.USER_ID.'", "tallennuskohde_id": "'.USER_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");				
		$Paatos_tallennettu = searchArrayValueByKey($res, "Paatos_tallennettu");		
        $this->assertTrue($Paatos_tallennettu, "'Paatos_tallennettu' should be TRUE");			
		
		// Vapaamuot. päätös
		$res = cliRun(__FUNCTION__, '{"tallennuskohde": "paatos", "tallennuskohde_kentta": "Vapaamuotoinen_paatos", "tallennuskohde_arvo": "Lorem ipsum", "tallennuskohde_id": "'.NEW_PAATOS_ID.'", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");				
		$Paatos_tallennettu = searchArrayValueByKey($res, "Paatos_tallennettu");		
        $this->assertTrue($Paatos_tallennettu, "'Paatos_tallennettu' should be TRUE");			
		
	}	

    /**
	 @depends test_tallenna_paatos_lomake 
     */		
	public function test_tallenna_paatoksen_liitetiedosto(){
		
		// Viranomainen 2 tallentaa liitteen
        $res = cliRun(__FUNCTION__, '{"paatos_id": "'.NEW_PAATOS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");					
		$Liitetiedosto_tallennettu = searchArrayValueByKey($res, "Liitetiedosto_tallennettu");
		$this->assertTrue($Liitetiedosto_tallennettu, "'Liitetiedosto_tallennettu' should be TRUE");		
		//$Liitetiedosto_ID = searchArrayValueByKey($res, "Liitetiedosto_ID");
		//$this->assertGreaterThan(0, intval($Liitetiedosto_ID), "'Lausunto ID' is invalid");
		
		// Hakija 1 yrittää tallentaa liitteen
        $res = cliRun(__FUNCTION__, '{"paatos_id": "'.NEW_PAATOS_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");			
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "user 1 ei saanut auth. erroria");	
		
		// Viranomainen 2 yrittää tallentaa liitteen
        $res = cliRun(__FUNCTION__, '{"paatos_id": "'.NEW_PAATOS_ID.'", "user_id": "'.VIRANOMAINEN2_ID.'", "token": "'.VIRANOMAINEN2_TOKEN.'", "format":"json"}');
        $this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");					
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "viranomainen 2 ei saanut auth. erroria");	
		
	}	

    /**
	 @depends test_tallenna_paatoksen_liitetiedosto 
     */		
	public function test_tallenna_paatos(){ 

		// Hakija yrittää lähettää päätöksen hyväksyttäväksi
        $res = cliRun(__FUNCTION__, '{"laheta_paatos_hyvaksyttavaksi": "1", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.USER_ID.'", "token": "'.TOKEN.'", "format":"json"}');
		$this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");					
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "viranomainen 2 ei saanut auth. erroria");	
		
		// Viranomainen 2 (ei käsittelijä) yrittää lähettää päätöksen hyväksyttäväksi
        $res = cliRun(__FUNCTION__, '{"laheta_paatos_hyvaksyttavaksi": "1", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN2_ID.'", "token": "'.VIRANOMAINEN2_TOKEN.'", "format":"json"}');
		$this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");					
		$this->assertEquals(ERR_AUTH_FAIL, intval($res[0]), "viranomainen 2 ei saanut auth. erroria");	
	
		// Viranomainen 1 lähettää päätöksen hyväksyttäväksi (itselleen)
        $res = cliRun(__FUNCTION__, '{"laheta_paatos_hyvaksyttavaksi": "1", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
		$this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");					
		$Paatos_tallennettu = searchArrayValueByKey($res, "Paatos_tallennettu");
		$this->assertTrue($Paatos_tallennettu, "'Paatos_tallennettu' should be TRUE");

		// Viranomainen 1 allekirjoittaa päätöksen
        $res = cliRun(__FUNCTION__, '{"kayttajan_rooli": "rooli_paattava", "allekirjoita_paatos": "1", "hakemus_id": "'.NEW_HAKEMUS_ID.'", "user_id": "'.VIRANOMAINEN_ID.'", "token": "'.VIRANOMAINEN_TOKEN.'", "format":"json"}');
		$this->assertTrue(is_array($res), "Invalid ".__FUNCTION__." output.\nrun 'php tests/".__FUNCTION__.".php' for details");					
		$Paatos_tallennettu = searchArrayValueByKey($res, "Paatos_tallennettu");
		$this->assertTrue($Paatos_tallennettu, "'Paatos_tallennettu' should be TRUE");
		
	}
	
}