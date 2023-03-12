<?php

require_once("config.php");
require_once("db_conn.php");
require_once("binance.php");

class CommonFunc
{
	public static function getCurrentTime() {
		return date('Y-m-d H:i:s', time());
	}

	public static function isPairByMaster($pair) {
		return substr($pair, -3) == MASTER_COIN;
	}

	public static function rebuildUrl($cur_url, $param, $new_val) {
		if (strpos($cur_url, '?') !== false) {
			list($file, $parameters) = explode('?', $cur_url);
			parse_str($parameters, $output);
			unset($output[$param]); // remove the parameter

			return $file . '?'.http_build_query($output).'&'.$param.'='.strval($new_val); // Rebuild the url
		}
		return $cur_url . '?' . $param.'='.strval($new_val);
	}

	public static function getSbModule($module) {
		if ($module == 'mark') {
			return 'sb-mark';
		} else if ($module == 'auto') {
			return 'sb-auto';
		} else {
			return 'sb-all';
		}
	}

	public static function getIndicatorModule($module) {
		if ($module == 'mark') {
			return 'in-mark';
		} else if ($module == 'auto') {
			return 'in-auto';
		} else {
			return 'in-all';
		}
	}
}

?>