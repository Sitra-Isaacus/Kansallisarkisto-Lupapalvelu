<?php

/**
 * filter requests:
 * 		no more than N requests in X seconds per session,
 * 		no more than N requests in X seconds per ip,
 * 		etc
 */

$filters = array(

	'sess' => array(
		'time_frame' => 10,  // time in seconds, should be less than 1min
		'hits_allowed' => 10, // max hits allowed in time frame
		'ip_white_list' => array('127.0.0.1'), // this IPs allowed unconditionally. You can use mask like '192.168.'
	),

	'ip' => array(
		'time_frame' => 10,  // time in seconds, should be less than 1min
		'hits_allowed' => 10, // max hits allowed in time frame
		'ip_white_list' => array('127.0.0.1'), // this IPs allowed unconditionally. You can use mask like '192.168.'
	),

);

$memcached = new MemoryCached();

if (isset($_COOKIE['PHPSESSID'])) {
	$sessId = $_COOKIE['PHPSESSID'];
}
$ip = $_SERVER['REMOTE_ADDR'];

$access_check = true;
if (isset($sessId)) {
	$access_check = check_access($filters, $memcached, $ip, $sessId);
}

if ($access_check !== true) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	//echo $access_check . " ";
	echo ODOTA_OLE_HYVA . "...<script>setTimeout('location.href=location.href', 2500);</script>";
	exit();
}

/**
* returns true is access granted, error message if not
*/
function check_access($filters, $memcached, $ip, $sessId) {

	foreach ($filters as $filter_key => $filter) {

		// allow whitelisted ips, no further checks needed
		if (isset($filter['ip_white_list']) && is_array($filter['ip_white_list'])) {
			foreach ($filter['ip_white_list'] as $white_ip) {
				if (strpos(" ".$ip, $white_ip)==1) return true;
			}
		}

		$now = time();
		$sec = date("s",$now);
		$timer = floor($sec/$filter['time_frame']);

		switch ($filter_key) {

			case "sess":
				$key = "h_{$timer}_{$sessId}";
				break;

			case "ip":
				$key = "h_{$timer}_{$ip}";
				break;

			default:
				die("Secutity: unexpected filter key");
				break;

		}

		$hits = $memcached->get($key);
		if (!$hits) $hits = 0;
		$hits++;

		if ($hits > $filter['hits_allowed']) {
			return "Max hits reached for filter '{$filter_key}', key '{$key}'";
		}

		$memcached->set($key, $hits, $filter['time_frame']);

	}

	return true;
}


/**
 * Class MemoryCached
 *
 * this class is a custom backwards-compartibility wrapper designed to make php5 to php7 upgrade less painful
 *
 * usage:
 *
 * $memcached = new MemoryCached();
 * $key = 'some key';
 * $time = 1; // can be from 1sec to 2592000sec (1 month)
 * $stored_value = $memcached->get($key);
 * memcached->set($key, $new_value, $time);
 *
 */

class MemoryCached
{
	public $memcached;
	public $mode;

	function __construct() {

		// using memcachE for php 5.3 - comment this block out when move to php 7
		$memcached = new Memcache();
		$memcached->addServer('localhost', 11211) or die ("Could not connect");
		$this->memcached = $memcached;
		$this->mode = "E";

		// upcomment this when move to php 7
		//$memcached = new Memcached();
		// $servers = array(array('localserv', 11212));
		// $memcached->addServers($servers) or die ("Could not connect to Memcached");
		// $memcached->setOption(Memcached::OPT_BINARY_PROTOCOL, true);
		// $this->mode = "D";

	}

	public function get($key) {
		$val = $this->memcached->get($key);
		if ($val) {
			$arr = @json_decode($val);
			if ($arr && (is_array($arr) || is_object($arr))) $val = $arr;
		}
		return $val;
	}

	public function set($key, $val, $time = 2592000) { // 1 month
		if (is_array($val) || is_object($val)) $val = json_encode($val);
		if ($this->mode == "D") {
			return $this->memcached->set($key, $val, $time);
		} else {
			return $this->memcached->set($key, $val, null, $time);
		}

	}
	
	public function delete($key, $time=0){
		return $this->memcached->delete($key,$time);
	}
	
	public function flush($delay=0){
		return $this->memcached->flush($delay);
	}

}

?>