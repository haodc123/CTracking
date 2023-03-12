<?php

/**
* Run every minute
* 1. Get price of all pairs from GET /api/v3/ticker/price
* 2. Insert to [price_history]
* 3. Delete old [price_history] data (before LAST_TRACK_HOUR)
* 4. Update to [pairs]
*/

require_once("common.php");

// 1. Get price of all pairs from GET /api/v3/ticker/price
$bi = new Binance("", "");
$prices_api = $bi->prices();

foreach ($prices_api as $key => $value) {
    if (CommonFunc::isPairByMaster($key)) {
        $prices_with_datetime[] = '("'.$key.'", "'.CommonFunc::getCurrentTime().'", "'.$value.'")';
        $prices[] = '("'.$key.'", "'.$value.'")';
    }
}

// 2. Insert to [price_history]
// multiple rows in 1 sql query
$insert_query = 'INSERT INTO price_history (pair, date_time, price) VALUES '.implode(',', $prices_with_datetime);
mysqli_query($con, $insert_query);

// 3. Delete old [price_history] data (before LAST_TRACK_HOUR)
$delete_query = 'DELETE FROM price_history WHERE date_time < SUBDATE(NOW(), INTERVAL '.LAST_TRACK_HOUR.' HOUR)';
mysqli_query($con, $delete_query);

// 4. Update to [pairs]
// multiple rows in 1 sql query
$update_query = 'INSERT INTO pairs (pair, cur_price) VALUES '.implode(',', $prices);
$update_query .= ' ON DUPLICATE KEY UPDATE cur_price = VALUES(cur_price)';

mysqli_query($con, $update_query);

?>