<?php
require_once("include/common.php");
?>
<?php
	$pair = isset($_GET["pair-search"]) ? strtoupper($_GET["pair-search"]) : 'ETHBTC';

	$query = 'SELECT * FROM price_history INNER JOIN pairs ON price_history.pair = pairs.pair ';
	$query .= 'WHERE date_time >= SUBDATE(NOW(), INTERVAL '.LAST_DISPLAY_HOUR.' HOUR) ';
	$query .= 'AND pairs.pair = "'.$pair.'"';
	$query .= ' ORDER BY price_history.pair, price_history.date_time';

	$prices = mysqli_query($con, $query);
	$arr_price = array();
	$arr_price[1] = array();
	while ($row = $prices->fetch_array(MYSQLI_ASSOC))
	{
		$arr_price[0] = $row["mark"];
		array_push($arr_price[1], array("x" => strtotime($row["date_time"])*1000, "y" => floatval($row["price"])));
	}
	$prices->close();
	mysqli_close($con);
?>
<html>
	<head>
		<title>Lazy Coin</title>
		<link rel="stylesheet" type="text/css" href="include/main.css">
		<script src="include/jquery-3.4.1.min.js"></script>

<script>

window.onload = function () {

var min0 = <?php echo min(array_column($arr_price[1], 'y')); ?>;
var max0 = <?php echo max(array_column($arr_price[1], 'y')); ?>;

var chart0 = new CanvasJS.Chart("chartContainer0", {
	animationEnabled: true,
	title: {
		text: "<?php echo $pair; ?>"
	},
	axisY: {
		title: "Price",
		suffix: " BTC",
		minimum: min0*0.9999,
		maximum: max0,
		crosshair : {
			enabled: true,
			color: "orange",
			labelFontColor: "#F8F8F8"
		}
	},
	axisX: {
	    crosshair: {
	        enabled: true,
			color: "orange",
			labelFontColor: "#F8F8F8"
	    }
	 },
	data: [{
		type: "spline",
		markerSize: 5,
		click: onClick,
		xValueFormatString: "YYYY",
		yValueFormatString: "$#,##0.##",
		xValueType: "dateTime",
		dataPoints: <?php echo json_encode($arr_price[1]); ?>
	}]
});
chart0.render();

var chart0 = document.getElementById('chartContainer0');
chart0.dataset.pair = "<?php echo $pair; ?>";
var mark0 = "<?php echo $arr_price[0]; ?>";
chart0.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark0;
chart0.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark0+".png");

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
</script>

	</head>
	<body>

		<div id="sb-left" class="sb-all">
			<?php $module = 'search'; require_once("include/sb.php"); ?>
		</div>

		<div id="wrapper">
			<div class="indicator in-all"></div>
			<div id="main">
				<div class="chartWrapper">
					<img class="mark" onclick="onMark(this)"></img>
					<div class="chartContainer" id="chartContainer0"></div>
				</div>
			</div>

		</div>

		<div class="clear"></div>

		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	</body>
</html>


