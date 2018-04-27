<?php

if (!defined('FMAS')) die('Direct access not allowed');

// helper functions

function _echo($msg) {

    global $format;

    if (isset($format) && $format=='json') return;

    echo $msg;

}


/*

convert normal array to the array of objects:

from:
Array
(
    [sahkopostiosoite] => esko.esimerkkinen@narc.fi
    [salasana] => Salasana
)

to:
Array
(
    [0] => stdClass Object( [salasana] => Salasana )
    [1] => stdClass Object( [sahkopostiosoite] => esko.esimerkkinen@narc.fi )
)
*/

function toObjArray($array) {

    $out = array();

    foreach ($array as $k=>$v) {

        $obj = new StdClass();
        $obj->$k = $v;
        $out[] = $obj;

    }

    return $out;
}

function fromObjArray($arrayOfObjects) {

    $out = array();

    if (is_object($arrayOfObjects)) $arrayOfObjects = std2ArrayRecursive($arrayOfObjects);

    if (is_array($arrayOfObjects)) {

        foreach ($arrayOfObjects as $obj) {

            $arr = std2ArrayRecursive($obj);
            if (is_array($arr)) {
                foreach ($arr as $k => $v) {
                    $out[$k] = $v;
                }
            }

        }
    } else {
        die("Function 'fromObjArray': array of objects required");
    }

    return $out;
}

function findValRecursive($key, $data) {

    $out = null;

    if (is_object($data)) $arr = std2ArrayRecursive($data);
    else $arr = $data;

   return  searchArrayValueByKey($data, $key);

}

function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
            return $current_key;
        }
    }
    return false;
}

if (!function_exists('searchArrayValueByKey')) {
    function searchArrayValueByKey(array $array, $search) {
        foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) as $key => $value) {
            if ($search === $key)
                return $value;
        }
        return false;
    }
}

/**
 * transform Obj to Array recursively
 *
 * @param $std
 * @return array|null
 */
if (!function_exists('std2ArrayRecursive')) {
    function std2ArrayRecursive($std, $cnt=0)
    {
        $cnt++;
        $out = null;
        if (is_object($std) || is_array($std)) {
            $out = array();
            foreach ($std as $k => $v) {
                if ($cnt<5) $out[$k] = std2ArrayRecursive($v,$cnt);
                else $out[$k] = $v;
            }
        } else {
            return $std;
        }
        return $out;
    }
}

/**
 * @param $arg
 * @return array|null
 */
function cliParamConvert($arg) {

    $json = json_decode($arg);
    if ($json) return std2ArrayRecursive($json);

    return $arg;

}

/**
 * print out json encoded data and exit
 *
 * @param $data
 */
function jsonOut($data) {

    error_reporting(0);
    $json = json_encode($data, 512);
    error_reporting(1025);

    echo $json."\n";
    exit;

}

/**
 * run cli command and return the output
 *
 * @param $cmd
 */
function cliRun($cmd, $params = array()) {

    if (strpos(" ".$cmd, "test_")) {
        if (is_string($params)) {
            $params = json_decode($params);
            $params = std2ArrayRecursive($params);
        }
        $params['format'] = 'json';
        $json_arg = json_encode($params);
        $cmd = "php tests/{$cmd}.php '{$json_arg}'";
    }

    exec($cmd, $out);

    $data = false;
    if ($out && is_array($out)) {
        $data = implode("\n", $out);
    }

    if ($params['format'] == 'json') {
        $out = json_decode($data);
        if (!$out) return $data;
        return std2ArrayRecursive($out);
    }

    return $data;

}



function handle_arguments($argv) {

    global $token;
    global $user_id;
    global $format;
    global $user_email;
    global $user_pass;
    global $new_hakemusversio;
    global $hakemusversio_id;
    global $tutkimus_id;
	
    if (!is_array($argv)) return false;
    if (!isset($argv[1])) return false;

    $params = json_decode($argv[1]);
    $params = std2ArrayRecursive($params);

    if ($params['token']) $token = $params['token'];
    if ($params['user_id']) $user_id = $params['user_id'];
    if ($params['format']) $format = $params['format'];
    if ($params['user_email']) $user_email = $params['user_email'];
    if ($params['user_pass']) $user_pass = $params['user_pass'];
    if ($params['new_hakemusversio']) $new_hakemusversio = $params['new_hakemusversio'];
    if ($params['hakemusversio_id']) $hakemusversio_id = $params['hakemusversio_id'];
    if ($params['tutkimus_id']) $tutkimus_id = $params['tutkimus_id'];
	
    return $params;
	
}

function generateRandomString($length = 10) {
	
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
	
    for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
    }
	
    return $randomString;
	
}

if (!function_exists('debug_log')) {

    function debug_log($obj) {

        $interface = "test";

        if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == '192.168.104.250') {

            ob_start();
            print_r($obj);
            $ob = ob_get_contents();
            ob_end_clean();
            file_put_contents("/tmp/debug_{$interface}.log", date("Ymd H:i:s")." ".$_SERVER['REQUEST_URI']."\n".$ob."\n", FILE_APPEND);

        }

    }

}