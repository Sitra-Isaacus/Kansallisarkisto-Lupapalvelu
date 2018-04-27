<?php
/*
 * FMAS Käyttölupapalvelu
 * Front end: Multilingual support
 *
 * Created: 5.10.2015
 */

define("DEFAULT_LANGUAGE", "fi");
define("LANGUAGE_FILES_BASE", "./ui/language/lang_%s.php");

class lang {
	
	private static $SUPPORTED_LANGUAGES = array('fi', 'en');
	private $current_language;

	function __construct() {
		$this->current_language = self::detectLanguage();
		include_once sprintf(LANGUAGE_FILES_BASE, $this->current_language);
	}
	
	function getCurrentLanguage() {
		return $this->current_language;
	}
	
	function setCurrentLanguage($lang) {
		$this->current_language = $lang;
	}
	
	private function detectLanguage() {
		
		if(isset($_GET['kieli'])){
			
			$nykyinenKieli = strtolower(tarkista($_GET['kieli']));
			if(isset($_SESSION["kayttaja_kieli"])) $_SESSION["kayttaja_kieli"] = $nykyinenKieli;
			
			return $nykyinenKieli;
			
		}
		
		if(isset($_SESSION["kayttaja_kieli"])){
			return $_SESSION["kayttaja_kieli"];
		}
						
		// try languages listed by user's browser
		$lang_code = self::checkBrowserLanguages();
		if ($lang_code) return $lang_code;
		
		// finally, return the default language
		return DEFAULT_LANGUAGE;
	}
	
	private function checkBrowserLanguages() {
		if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			foreach (explode(',', strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE'])) as $lang_code) {
				$lang_code = strtolower(substr($lang_code, 0, 2));
				if (in_array($lang_code, self::$SUPPORTED_LANGUAGES)) {
					return $lang_code;
				}
			}
		}
	}
}