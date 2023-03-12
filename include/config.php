<?php

define("BASE_PATH", "https://binance.com");
define("MASTER_COIN", "BTC");
define("LAST_TRACK_HOUR", 6);
define("LAST_DISPLAY_HOUR", 6);
define("LAST_TRACK_HOUR_BTC", 6);
define("BTC_BUMP_CHANGE", 0.03); // 3%
define("PER_PAGING", 20);
define("RANGE_PRICE_DISPLAY", 1.06); // max/min on chart

// API Get Prices
$method_get_prices = "GET";
$url_get_prices = BASE_PATH."/api/v3/ticker/price";


?>