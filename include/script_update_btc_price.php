<?php

/**
* Tracking BTC
* Run every minute
* 1. Get price, insert db
* 2. Check wheter if: within LAST_TRACK_HOUR_BTC, BTC price change over BTC_BUMP_CHANGE ?
* 3. Send mail when BUMP
*/

require_once("common.php");


// -------------- ONLY BTC/USDT ----------------------- //

// 1a. Get price of all pairs from GET /api/v3/ticker/price
$bi = new Binance("", "");
$price_btc = $bi->priceBySymbol('BTCUSDT');

$price_with_datetime[] = '("'.$price_btc['symbol'].'", "'.CommonFunc::getCurrentTime().'", "'.$price_btc['price'].'")';
$price[] = '("'.$price_btc['symbol'].'", "'.$price_btc['price'].'")';

// 1b. Insert to [price_history]
// multiple rows in 1 sql query
$insert_query = 'INSERT INTO price_history (pair, date_time, price) VALUES '.implode(',', $price_with_datetime);
mysqli_query($con, $insert_query);

// 1c. Delete old [price_history] data (before LAST_TRACK_HOUR_BTC)
// NOTEEEE THAT datetime on MySQL may not same web server (CommonFunc::getCurrentTime()) ///////
$delete_query = 'DELETE FROM price_history WHERE pair = "BTCUSDT" AND date_time < SUBDATE(NOW(), INTERVAL '.LAST_TRACK_HOUR_BTC.' HOUR)';
mysqli_query($con, $delete_query);

// 1d. Update to [pairs]
// multiple rows in 1 sql query
$update_query = 'INSERT INTO pairs (pair, cur_price) VALUES '.implode(',', $price);
$update_query .= ' ON DUPLICATE KEY UPDATE cur_price = VALUES(cur_price)';

// 2. Check wheter if: within LAST_TRACK_HOUR_BTC, BTC price change over BTC_BUMP_CHANGE ?
$query = 'SELECT price FROM price_history WHERE pair = "BTCUSDT" ORDER BY date_time DESC LIMIT 0,1';
$last_price = mysqli_fetch_row(mysqli_query($con, $query))[0];
$query = 'SELECT price FROM price_history WHERE pair = "BTCUSDT" ORDER BY date_time ASC LIMIT 0,1';
$origin_price = mysqli_fetch_row(mysqli_query($con, $query))[0];

$is_bump = abs($last_price-$origin_price)/$origin_price >= BTC_BUMP_CHANGE;


// 3. Send mail if BTC bump
require '../smtpmail/PHPMailerAutoload.php';

if ($is_bump) {
	$from_mail = 'thanhzzzzznguyen@gmail.com';
	$from_name = 'Thanh Nguyen';
	$from_pass = 'azbc@@123';
	$reply_mail = 'thanhzzzzznguyen@gmail.com';
	$reply_name = 'Thanh Nguyen';
	$to_mail = 'haodcdng@gmail.com';
	$to_name = 'Hao';

	//Create a new PHPMailer instance
	$mail = new PHPMailer();

	//Tell PHPMailer to use SMTP
	$mail->isSMTP();

	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 2;

	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';

	//Set the hostname of the mail server
	$mail->Host = 'smtp.gmail.com';

	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;

	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';

	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;

	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = $from_mail;

	//Password to use for SMTP authentication
	$mail->Password = $from_pass;

	//Set who the message is to be sent from
	$mail->setFrom($from_mail, $from_name);

	//Set an alternative reply-to address
	$mail->addReplyTo($reply_mail, $reply_name);

	//Set who the message is to be sent to
	$mail->addAddress($to_mail, $to_name);

	//Set the subject line
	$mail->Subject = 'BTC BUMP';

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$mail->msgHTML('BTC BUMP');

	//Replace the plain text body with one created manually
	//$mail->AltBody = 'This is a plain-text message body';

	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	if (!$mail->send()) {
	    echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	    echo "Message sent!";
	}
}


?>