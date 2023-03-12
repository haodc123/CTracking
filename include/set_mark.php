<?php

require_once("common.php");

$pair = $_GET['pair'];
$is_mark = 0;
if (isset($_GET['is_mark']) && $_GET['is_mark'] == 1) 
	$is_mark = 1;

if ($pair == 'all')
	$update_query = 'UPDATE pairs SET mark = "'.$is_mark.'"';
else
	$update_query = 'UPDATE pairs SET mark = "'.$is_mark.'" WHERE pair="'.$pair.'"';

$runcheck = mysqli_query($con, $update_query);

if ($runcheck) {
	echo 'Update mark success !';
} else {
	echo 'Update mark error !';
}

?>