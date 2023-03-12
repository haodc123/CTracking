<?php
require_once("include/common.php");
?>
<?php
	$module = isset($_GET["module"]) ? $_GET["module"] : 'all';
	$cur_page = isset($_GET["page"]) ? $_GET["page"] : 1;

	$query = 'SELECT count(pair) AS c FROM pairs';
	$num_pairs = mysqli_query($con, $query)->fetch_assoc()['c'];
	$num_page = FLOOR($num_pairs/PER_PAGING)+1;
	
	$query = 'SELECT * FROM price_history INNER JOIN pairs ON price_history.pair = pairs.pair ';
	$query .= 'WHERE date_time >= SUBDATE(NOW(), INTERVAL '.'9999900'.' HOUR) ';
	if ($module == 'all') {
		$query .= 'AND pairs.pair_order >= '.strval(($cur_page-1)*PER_PAGING).' AND pairs.pair_order < '.strval($cur_page*PER_PAGING);
	} else if ($module == 'mark') {
		$query .= 'AND pairs.mark = 1';
	} else if ($module == 'auto') {
		$query .= 'AND pairs.auto = 1';
	}
	$query .= ' ORDER BY price_history.pair, price_history.date_time';

	$prices = mysqli_query($con, $query);
	$cur_pair = "";
	$arr_price = array();
	while ($row = $prices->fetch_array(MYSQLI_ASSOC))
	{
		if ($cur_pair != $row["pair"]) {
			$cur_pair = $row["pair"];
			$arr_price[$cur_pair] = array();
			$arr_price[$cur_pair][0] = $row["mark"];
			$arr_price[$cur_pair][1] = array();
		}
		array_push($arr_price[$cur_pair][1], array("x" => strtotime($row["date_time"])*1000, "y" => floatval($row["price"])));
	}
	$prices->close();

	$key_pair = array_keys($arr_price);
	$num_pair_page = sizeof($key_pair);
	
	mysqli_close($con);
?>
<html>
	<head>
		<title>Lazy Coin</title>
		<link rel="stylesheet" type="text/css" href="include/main.css">
		<script src="include/jquery-3.4.1.min.js"></script>

<script>

window.onload = function () {

<?php
echo 'var min = new Array();'."\r\n";
echo 'var max = new Array();'."\r\n";
for ($i=0; $i < $num_pair_page; $i++) {
	echo 'min['.$i.'] = ';
		echo isset($key_pair[$i]) ? min(array_column($arr_price[$key_pair[$i]][1], 'y')).";\r\n" : "0;\r\n";
	echo 'max['.$i.'] = ';
		echo isset($key_pair[$i]) ? max(array_column($arr_price[$key_pair[$i]][1], 'y')).";\r\n" : "0;\r\n";
}	
?>

<?php
echo 'var chart = new Array();'."\r\n";
for ($i=0; $i < $num_pair_page; $i++) {
	echo 'chart['.$i.'] = new CanvasJS.Chart("chartContainer['.$i.']", {'."\r\n";
	echo '  animationEnabled: true,'."\r\n";
	echo '  title: {'."\r\n";
	echo '    text: "';echo (isset($key_pair[$i])) ? $key_pair[$i] : ''; echo '"'."\r\n";
	echo '  },'."\r\n";
	echo '  axisY: {'."\r\n";
	echo '    title: "Price",'."\r\n";
	echo '    suffix: " BTC",'."\r\n";
	if ($module == 'all') {
		echo '    minimum: min['.$i.'],'."\r\n";
		echo '    maximum: min['.$i.']*'.RANGE_PRICE_DISPLAY.','."\r\n";
	} else {
		echo '    minimum: min['.$i.']*0.9999,'."\r\n";
		echo '    maximum: max['.$i.'],'."\r\n";
	}
	echo '    crosshair : {'."\r\n";
	echo '      enabled: true,'."\r\n";
	echo '      color: "orange",'."\r\n";
	echo '      labelFontColor: "#F8F8F8"'."\r\n";
	echo '    }'."\r\n";
	echo '  },'."\r\n";
	echo '  axisX: {'."\r\n";
	echo '    crosshair : {'."\r\n";
	echo '      enabled: true,'."\r\n";
	echo '      color: "orange",'."\r\n";
	echo '      labelFontColor: "#F8F8F8"'."\r\n";
	echo '    }'."\r\n";
	echo '  },'."\r\n";
	echo '  data: [{'."\r\n";
	echo '    type: "spline",'."\r\n";
	echo '    markerSize: 5,'."\r\n";
	echo '    click: onClick,'."\r\n";
	echo '    xValueFormatString: "YYYY",'."\r\n";
	echo '    yValueFormatString: "$#,##0.##",'."\r\n";
	echo '    xValueType: "dateTime",'."\r\n";
	echo '    dataPoints: ';echo (isset($key_pair[$i])) ? json_encode($arr_price[$key_pair[$i]][1])."\r\n" : ''."\r\n";
	echo '  }]'."\r\n";
	echo '});'."\r\n";
	echo 'chart['.$i.'].render();'."\r\n";
}
?>

<?php
echo "var chartCon = new Array();\r\n";
echo "var mark = new Array();\r\n";
for ($i=0; $i<$num_pair_page; $i++) {
	echo 'chartCon['.$i.'] = document.getElementById("chartContainer['.$i.']");'."\r\n";
	//var chartCon0 = document.getElementById('chartContainer0');
	echo 'chartCon['.$i.'].dataset.pair = ';
		echo isset($key_pair[$i]) ? '"'.$key_pair[$i].'";'."\r\n" : '"";'."\r\n";
	echo 'mark['.$i.'] = ';
		echo isset($key_pair[$i]) ? '"'.$arr_price[$key_pair[$i]][0].'";'."\r\n" : "0;\r\n";
	echo 'chartCon['.$i.'].parentNode.getElementsByTagName("img")[0].dataset.ismark = mark['.$i.'];'."\r\n";
	//chart0.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark0;
	echo 'chartCon['.$i.'].parentNode.getElementsByTagName("img")[0].setAttribute("src", "img/star-"+mark['.$i.']+".png");'."\r\n";
	//chart0.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark0+".png");
}
?>

var cal_fst = document.querySelectorAll("#cal-fst input")[0];
var cal_sec = document.querySelectorAll("#cal-sec input")[0];
var cal_rate = document.querySelectorAll("#cal-rate input")[0];
var cal_fst_price = 0;
var cal_sec_price = 0;
var cal_rate_price = 0;
var cal_pointer = 1;

	function onClick(e) {
		if (cal_pointer == 1) {
			cal_fst.value = e.dataPoint.y;
			cal_sec.value = 0;
			cal_rate.value = 0;
			cal_pointer = 2;
		} else if (cal_pointer == 2) {
			cal_sec.value = e.dataPoint.y;
			cal_fst_price = cal_fst.value;
			cal_sec_price = cal_sec.value;
			cal_rate_price = cal_sec_price/cal_fst_price;
			cal_rate.value = cal_rate_price;
			cal_pointer = 1;
		}
	}

	// manual calculator: 2nd/1st
	function onCalMan() {
		onCalManual(0);	
	}
	// revert: 1st/2nd
	function onCalRev() {
		onCalManual(1);
	}
	function onCalManual(revert) {
		cal_fst_price = cal_fst.value;
		cal_sec_price = cal_sec.value;
		if (revert == 1) {
			cal_fst.value = cal_sec_price;
			cal_sec.value = cal_fst_price;
			cal_rate_price = cal_fst_price/cal_sec_price;
		} else {
			cal_rate_price = cal_sec_price/cal_fst_price;
		}
		cal_rate.value = cal_rate_price;
		cal_pointer = 1;
	}
	document.getElementById("cal-man-rev").onclick = onCalRev;
	document.getElementById("cal-man-cal").onclick = onCalMan;

}
function onMark(elm) {
	var pair = elm.parentNode.getElementsByTagName('div')[0].dataset.pair;
	if (elm.dataset.ismark == 0) {
		elm.setAttribute("src", "img/star-1.png");
		$.ajax({
		    url: "include/set_mark.php?pair="+pair+"&is_mark=1",
		    success:function(data) {}
		});
		elm.dataset.ismark = 1;
	} else {
		elm.setAttribute("src", "img/star-0.png");
		$.ajax({
		    url: "include/set_mark.php?pair="+pair+"&is_mark=0",
		    success:function(data) {}
		});
		elm.dataset.ismark = 0;
	}
}

function onResetMark() {
	$.ajax({
		    url: "include/set_mark.php?pair=all&is_mark=0",
		    success:function(data) {}
		});
}
</script>

	</head>
	<body>
		<div id="sb-left" class="<?php echo CommonFunc::getSbModule($module); ?>">

			<?php require_once("include/sb.php"); ?>

		</div>

		<div id="wrapper">

			<div class="indicator <?php echo CommonFunc::getIndicatorModule($module); ?>"></div>

			<div id="main">
				<?php
				for ($i=0; $i<PER_PAGING; $i++) {
				?>
					<div class="chartWrapper">
						<img class="mark" onclick="onMark(this)"></img>
						<div class="chartContainer" id="chartContainer[<?php echo $i; ?>]"></div>
					</div>
				<?php
				}
				?>
			</div>

		</div>

		<div class="clear"></div>

		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	</body>
</html>