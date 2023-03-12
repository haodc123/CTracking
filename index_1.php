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
	$query .= 'WHERE date_time >= SUBDATE(NOW(), INTERVAL '.'750'.' HOUR) ';
	$query .= 'AND pairs.pair_order >= '.strval(($cur_page-1)*PER_PAGING).' AND pairs.pair_order < '.strval($cur_page*PER_PAGING);
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
	// var_dump($arr_price);
	
	mysqli_close($con);
?>
<html>
	<head>
		<title>Lazy Coin</title>
		<link rel="stylesheet" type="text/css" href="include/main.css">
		<script src="include/jquery-3.4.1.min.js"></script>

<script>

window.onload = function () {

var min0 = <?php isset($key_pair[0]) ? print(min(array_column($arr_price[$key_pair[0]][1], 'y'))) : print('0'); ?>;
var max0 = <?php isset($key_pair[0]) ? print(max(array_column($arr_price[$key_pair[0]][1], 'y'))) : print('0'); ?>;
var min1 = <?php isset($key_pair[1]) ? print(min(array_column($arr_price[$key_pair[1]][1], 'y'))) : print('0'); ?>;
var max1 = <?php isset($key_pair[1]) ? print(max(array_column($arr_price[$key_pair[1]][1], 'y'))) : print('0'); ?>;
var min2 = <?php isset($key_pair[2]) ? print(min(array_column($arr_price[$key_pair[2]][1], 'y'))) : print('0'); ?>;
var max2 = <?php isset($key_pair[2]) ? print(max(array_column($arr_price[$key_pair[2]][1], 'y'))) : print('0'); ?>;
var min3 = <?php isset($key_pair[3]) ? print(min(array_column($arr_price[$key_pair[3]][1], 'y'))) : print('0'); ?>;
var max3 = <?php isset($key_pair[3]) ? print(max(array_column($arr_price[$key_pair[3]][1], 'y'))) : print('0'); ?>;
var min4 = <?php isset($key_pair[4]) ? print(min(array_column($arr_price[$key_pair[4]][1], 'y'))) : print('0'); ?>;
var max4 = <?php isset($key_pair[4]) ? print(max(array_column($arr_price[$key_pair[4]][1], 'y'))) : print('0'); ?>;
var min5 = <?php isset($key_pair[5]) ? print(min(array_column($arr_price[$key_pair[5]][1], 'y'))) : print('0'); ?>;
var max5 = <?php isset($key_pair[5]) ? print(max(array_column($arr_price[$key_pair[5]][1], 'y'))) : print('0'); ?>;
var min6 = <?php isset($key_pair[6]) ? print(min(array_column($arr_price[$key_pair[6]][1], 'y'))) : print('0'); ?>;
var max6 = <?php isset($key_pair[6]) ? print(max(array_column($arr_price[$key_pair[6]][1], 'y'))) : print('0'); ?>;
var min7 = <?php isset($key_pair[7]) ? print(min(array_column($arr_price[$key_pair[7]][1], 'y'))) : print('0'); ?>;
var max7 = <?php isset($key_pair[7]) ? print(max(array_column($arr_price[$key_pair[7]][1], 'y'))) : print('0'); ?>;
var min8 = <?php isset($key_pair[8]) ? print(min(array_column($arr_price[$key_pair[8]][1], 'y'))) : print('0'); ?>;
var max8 = <?php isset($key_pair[8]) ? print(max(array_column($arr_price[$key_pair[8]][1], 'y'))) : print('0'); ?>;
var min9 = <?php isset($key_pair[9]) ? print(min(array_column($arr_price[$key_pair[9]][1], 'y'))) : print('0'); ?>;
var max9 = <?php isset($key_pair[9]) ? print(max(array_column($arr_price[$key_pair[9]][1], 'y'))) : print('0'); ?>;

var chart0 = new CanvasJS.Chart("chartContainer0", {
	animationEnabled: true,
	title: {
		text: "<?php echo (isset($key_pair[0])) ? $key_pair[0] : ''; ?>"
	},
	axisY: {
		title: "Price",
		suffix: " BTC",
		minimum: min0-min0*0.0001,
		maximum: max0+max0*0.0001,
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
		dataPoints: <?php echo (isset($key_pair[0])) ? json_encode($arr_price[$key_pair[0]][1]) : ''; ?>
	}]
});

var chart1 = new CanvasJS.Chart("chartContainer1", {
	animationEnabled: true,
	title:{
		text: "<?php echo (isset($key_pair[1])) ? $key_pair[1] : ''; ?>"
	},
	axisY: {
		title: "Price",
		suffix: " BTC",
		minimum: min1-min1*0.0001,
		maximum: max1+max1*0.0001,
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
		dataPoints: <?php echo (isset($key_pair[1])) ? json_encode($arr_price[$key_pair[1]][1]) : '[]'; ?>
	}]
});
var chart2 = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,
	title:{
		text: "<?php echo (isset($key_pair[2])) ? $key_pair[2] : ''; ?>"
	},
	axisY: {
		title: "Price",
		suffix: " BTC",
		minimum: min2-min2*0.0001,
		maximum: max2+max2*0.0001,
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
		dataPoints: <?php echo (isset($key_pair[2])) ? json_encode($arr_price[$key_pair[2]][1]) : '[]'; ?>
	}]
});
var chart3 = new CanvasJS.Chart("chartContainer3", {
	animationEnabled: true,
	title: {
		text: "<?php echo (isset($key_pair[3])) ? $key_pair[3] : ''; ?>"
	},
	axisY: {
		title: "Price",
		suffix: " BTC",
		minimum: min3-min3*0.0001,
		maximum: max3+max3*0.0001,
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
		dataPoints: <?php echo (isset($key_pair[3])) ? json_encode($arr_price[$key_pair[3]][1]) : '[]'; ?>
	}]
});

var chart4 = new CanvasJS.Chart("chartContainer4", {
	animationEnabled: true,
	title:{
		text: "<?php echo (isset($key_pair[4])) ? $key_pair[4] : ''; ?>"
	},
	axisY: {
		title: "Price",
		suffix: " BTC",
		minimum: min4-min4*0.0001,
		maximum: max4+max4*0.0001,
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
		dataPoints: <?php echo (isset($key_pair[4])) ? json_encode($arr_price[$key_pair[4]][1]) : '[]'; ?>
	}]
});
var chart5 = new CanvasJS.Chart("chartContainer5", {
	animationEnabled: true,
	title:{
		text: "<?php echo (isset($key_pair[5])) ? $key_pair[5] : ''; ?>"
	},
	axisY: {
		title: "Price",
		suffix: " BTC",
		minimum: min5-min5*0.0001,
		maximum: max5+max5*0.0001,
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
		dataPoints: <?php echo (isset($key_pair[5])) ? json_encode($arr_price[$key_pair[5]][1]) : '[]'; ?>
	}]
});
var chart6 = new CanvasJS.Chart("chartContainer6", {
	animationEnabled: true,
	title: {
		text: "<?php echo (isset($key_pair[6])) ? $key_pair[6] : ''; ?>"
	},
	axisY: {
		title: "Price",
		suffix: " BTC",
		minimum: min6-min6*0.0001,
		maximum: max6+max6*0.0001,
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
		dataPoints: <?php echo (isset($key_pair[6])) ? json_encode($arr_price[$key_pair[6]][1]) : '[]'; ?>
	}]
});

var chart7 = new CanvasJS.Chart("chartContainer7", {
	animationEnabled: true,
	title:{
		text: "<?php echo (isset($key_pair[7])) ? $key_pair[7] : ''; ?>"
	},
	axisY: {
		title: "Price",
		suffix: " BTC",
		minimum: min7-min7*0.0001,
		maximum: max7+max7*0.0001,
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
		dataPoints: <?php echo (isset($key_pair[7])) ? json_encode($arr_price[$key_pair[7]][1]) : '[]'; ?>
	}]
});
var chart8 = new CanvasJS.Chart("chartContainer8", {
	animationEnabled: true,
	title:{
		text: "<?php echo (isset($key_pair[8])) ? $key_pair[8] : ''; ?>"
	},
	axisY: {
		title: "Price",
		suffix: " BTC",
		minimum: min8-min8*0.0001,
		maximum: max8+max8*0.0001,
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
		dataPoints: <?php echo (isset($key_pair[8])) ? json_encode($arr_price[$key_pair[8]][1]) : '[]'; ?>
	}]
});
var chart9 = new CanvasJS.Chart("chartContainer9", {
	animationEnabled: true,
	title: {
		text: "<?php echo (isset($key_pair[9])) ? $key_pair[9] : ''; ?>"
	},
	axisY: {
		title: "Price",
		suffix: " BTC",
		minimum: min9-min9*0.0001,
		maximum: max9+max9*0.0001,
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
		dataPoints: <?php echo (isset($key_pair[9])) ? json_encode($arr_price[$key_pair[9]][1]) : '[]'; ?>
	}]
});

chart0.render();
chart1.render();
chart2.render();
chart3.render();
chart4.render();
chart5.render();
chart6.render();
chart7.render();
chart8.render();
chart9.render();

var chart0 = document.getElementById('chartContainer0');
var chart1 = document.getElementById('chartContainer1');
var chart2 = document.getElementById('chartContainer2');
var chart3 = document.getElementById('chartContainer3');
var chart4 = document.getElementById('chartContainer4');
var chart5 = document.getElementById('chartContainer5');
var chart6 = document.getElementById('chartContainer6');
var chart7 = document.getElementById('chartContainer7');
var chart8 = document.getElementById('chartContainer8');
var chart9 = document.getElementById('chartContainer9');

chart0.dataset.pair = "<?php echo (isset($key_pair[0])) ? $key_pair[0] : ''; ?>";
chart1.dataset.pair = "<?php echo (isset($key_pair[1])) ? $key_pair[1] : ''; ?>";
chart2.dataset.pair = "<?php echo (isset($key_pair[2])) ? $key_pair[2] : ''; ?>";
chart3.dataset.pair = "<?php echo (isset($key_pair[3])) ? $key_pair[3] : ''; ?>";
chart4.dataset.pair = "<?php echo (isset($key_pair[4])) ? $key_pair[4] : ''; ?>";
chart5.dataset.pair = "<?php echo (isset($key_pair[5])) ? $key_pair[5] : ''; ?>";
chart6.dataset.pair = "<?php echo (isset($key_pair[6])) ? $key_pair[6] : ''; ?>";
chart7.dataset.pair = "<?php echo (isset($key_pair[7])) ? $key_pair[7] : ''; ?>";
chart8.dataset.pair = "<?php echo (isset($key_pair[8])) ? $key_pair[8] : ''; ?>";
chart9.dataset.pair = "<?php echo (isset($key_pair[9])) ? $key_pair[9] : ''; ?>";

var mark0 = "<?php echo (isset($key_pair[0])) ? $arr_price[$key_pair[0]][0] : '0'; ?>";
var mark1 = "<?php echo (isset($key_pair[1])) ? $arr_price[$key_pair[1]][0] : '0'; ?>";
var mark2 = "<?php echo (isset($key_pair[2])) ? $arr_price[$key_pair[2]][0] : '0'; ?>";
var mark3 = "<?php echo (isset($key_pair[3])) ? $arr_price[$key_pair[3]][0] : '0'; ?>";
var mark4 = "<?php echo (isset($key_pair[4])) ? $arr_price[$key_pair[4]][0] : '0'; ?>";
var mark5 = "<?php echo (isset($key_pair[5])) ? $arr_price[$key_pair[5]][0] : '0'; ?>";
var mark6 = "<?php echo (isset($key_pair[6])) ? $arr_price[$key_pair[6]][0] : '0'; ?>";
var mark7 = "<?php echo (isset($key_pair[7])) ? $arr_price[$key_pair[7]][0] : '0'; ?>";
var mark8 = "<?php echo (isset($key_pair[8])) ? $arr_price[$key_pair[8]][0] : '0'; ?>";
var mark9 = "<?php echo (isset($key_pair[9])) ? $arr_price[$key_pair[9]][0] : '0'; ?>";
chart0.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark0;
chart0.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark0+".png");
chart1.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark1;
chart1.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark1+".png");
chart2.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark2;
chart2.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark2+".png");
chart3.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark3;
chart3.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark3+".png");
chart4.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark4;
chart4.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark4+".png");
chart5.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark5;
chart5.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark5+".png");
chart6.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark6;
chart6.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark6+".png");
chart7.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark7;
chart7.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark7+".png");
chart8.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark8;
chart8.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark8+".png");
chart9.parentNode.getElementsByTagName('img')[0].dataset.ismark = mark9;
chart9.parentNode.getElementsByTagName('img')[0].setAttribute("src", "img/star-"+mark9+".png");

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
			<div class="indicator in-all"></div>
			<div id="module-nav">
				<ul>
				  <li class="li-all"><a class="active" href="">All</a></li>
				  <li class="li-mark"><a href="">Mark</a></li>
				  <li class="li-auto"><a href="">Auto</a></li>
				</ul>
			</div>
			<div id="paging">
				<?php require_once("include/paging.php"); ?>
			</div>
			<div id="calculator">
				<div class="cal-unit-price" id="cal-fst">
					<label>1st price: </label>
					<input value="0" />
					<div class="clear"></div>
				</div>
				<div class="cal-unit-price" id="cal-sec">
					<label>2nd price: </label>
					<input value="0" />
					<div class="clear"></div>
				</div>
				<div class="cal-unit-price" id="cal-rate">
					<div id="cal-rate-line"></div>
					<label>Rate price: </label>
					<input value="0" />
					<div class="clear"></div>
				</div>
				<div class="cal-unit-price" id="cal-man">
					<div id="cal-man-rev">Revert</div>
					<div id="cal-man-cal">Cal</div>
				</div>
			</div>
		</div>

		<div id="wrapper">

			<div class="indicator in-all"></div>

			<div id="main">
				<div class="chartWrapper">
					<img class="mark" onclick="onMark(this)"></img>
					<div class="chartContainer" id="chartContainer0"></div>
				</div>
				<div class="chartWrapper">
					<img class="mark" onclick="onMark(this)"></img>
					<div class="chartContainer" id="chartContainer1"></div>
				</div>
				<div class="chartWrapper">
					<img class="mark" onclick="onMark(this)"></img>
					<div class="chartContainer" id="chartContainer2"></div>
				</div>
				<div class="chartWrapper">
					<img class="mark" onclick="onMark(this)"></img>
					<div class="chartContainer" id="chartContainer3"></div>
				</div>
				<div class="chartWrapper">
					<img class="mark" onclick="onMark(this)"></img>
					<div class="chartContainer" id="chartContainer4"></div>
				</div>
				<div class="chartWrapper">
					<img class="mark" onclick="onMark(this)"></img>
					<div class="chartContainer" id="chartContainer5"></div>
				</div>
				<div class="chartWrapper">
					<img class="mark" onclick="onMark(this)"></img>
					<div class="chartContainer" id="chartContainer6"></div>
				</div>
				<div class="chartWrapper">
					<img class="mark" onclick="onMark(this)"></img>
					<div class="chartContainer" id="chartContainer7"></div>
				</div>
				<div class="chartWrapper">
					<img class="mark" onclick="onMark(this)"></img>
					<div class="chartContainer" id="chartContainer8"></div>
				</div>
				<div class="chartWrapper">
					<img class="mark" onclick="onMark(this)"></img>
					<div class="chartContainer" id="chartContainer9"></div>
				</div>
			</div>

		</div>

		<div class="clear"></div>

		<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	</body>
</html>


