<?php

	// getNetWorths
	// getTotalLiabilities
	// getTotalAssets
	// getTransactionsForGraph

//	$_GET[''];

	$startDate = '2016-01-17';
	$endDate = '2016-04-17';
	if (isset($_GET['start']) && isset($_GET['end'])) {
		$startDate = $_GET['start'];
		$endDate = $_GET['end'];
	}

	// TODO: loop through all in tas
	$count = 0;
	$allDatesBySpace = '';
	$allTlsBySpace = '';
	if (isset($_GET['tas']) ) {

		$tas = urldecode($_GET['tas']);
		$tas = explode(',', $tas);
		$transactions = $db->getTransactionsForGraph($startDate, $endDate, $tas[0]);

		foreach($transactions as $date => $tl) {
			$allDatesBySpace = $allDatesBySpace . ' ' . $date;
			$allTlsBySpace = $allTlsBySpace . ' ' . $tl;
		}

		print '<div id="x' . $count . '" style="visibility:hidden;">' . substr($allDatesBySpace, 1) . '</div>';
		print '<div id="data' . $count . '" style="visibility:hidden;">' . substr($allTlsBySpace, 1) . '</div>';
	}	

	/* Liabilities */
	$totalLiabilities = $db->getTotalLiabilities($startDate, $endDate);
	//print var_dump($totalLiabilities);
	$count++;
	$allDatesBySpace = '';
	$allTlsBySpace = '';
	foreach($totalLiabilities as $date => $tl) {
		$allDatesBySpace = $allDatesBySpace . ' ' . $date;
		$allTlsBySpace = $allTlsBySpace . ' ' . $tl;
	}

	print '<div id="x' . $count . '" style="visibility:hidden;">' . substr($allDatesBySpace, 1) . '</div>';
	print '<div id="data' . $count . '" style="visibility:hidden;">' . substr($allTlsBySpace, 1) . '</div>';

	/* Assets */
	$count++;
	$allDatesBySpace = '';
	$allTlsBySpace = '';
	$totalAssets = $db->getTotalAssets( $startDate, $endDate );
	foreach( $totalAssets as $date => $tl ) {
		$allDatesBySpace = $allDatesBySpace . ' ' . $date;
		$allTlsBySpace = $allTlsBySpace . ' ' . $tl;
	}

	print '<div id="x' . $count . '" style="visibility:hidden;">' . substr($allDatesBySpace, 1) . '</div>';
	print '<div id="data' . $count . '" style="visibility:hidden;">' . substr($allTlsBySpace, 1) . '</div>';

	/* Net Worth */
	$count++;
	$allDatesBySpace = '';
	$allTlsBySpace = '';
	$totalNetWorths = $db->getNetWorths( $startDate, $endDate );
	foreach( $totalNetWorths as $date => $tl ) {
		$allDatesBySpace = $allDatesBySpace . ' ' . $date;
		$allTlsBySpace = $allTlsBySpace . ' ' . $tl;
	}

	print '<div id="x' . $count . '" style="visibility:hidden;">' . substr($allDatesBySpace, 1) . '</div>';
	print '<div id="data' . $count . '" style="visibility:hidden;">' . substr($allTlsBySpace, 1) . '</div>';
 
?>

<div style="font-color: red" id="chart-container">
	<div id="chart"></div>
	<p> 
		Start Date: <input type="text" id="start-datepicker" />
	</p>
	<p>
		End Date: <input type="text" id="end-datepicker" />
	</p>

	<input type="submit" onclick="updateGraph()"></input>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="vendors/c3.min.css" type="text/css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="vendors/d3.min.js" charset="utf-8"></script>
<script src="vendors/c3.min.js"></script>

<script type="text/javascript">

	function updateGraph() {
		var startDate = document.getElementById("start-datepicker").value;
		var endDate = document.getElementById("end-datepicker").value;

		window.location = "http://localhost/worth/?start=" + startDate + "&end=" + endDate; // TODO don't remove transactions to display, which we do here

	}

	var startDatePicker = $( "#start-datepicker" ).datepicker({
		changeMonth: true,
		changeYear: true
	});
	startDatePicker.datepicker( "option", "dateFormat", "yy-mm-dd" );

	var endDatePicker = $( "#end-datepicker" ).datepicker({
		changeMonth: true,
		changeYear: true
	});
	endDatePicker.datepicker( "option", "dateFormat", "yy-mm-dd" );

	/* Transactions for first element in tas */
	var data0Arr = null, x0Arr = null;
	if ( document.getElementById('data0') != null) {
		var data0 = document.getElementById('data0').innerHTML;
		data0Arr = data0.split(' ');
		for ( var i = 0; i < data0Arr.length; i++) {
			data0Arr[i] = parseFloat(data0Arr[i]);
		}
		data0Arr.unshift('Account 1');

		var x0 = document.getElementById('x0').innerHTML;
		x0Arr = x0.split(' ');
		x0Arr.unshift('x0');
	}

	/* Liabilities */
	var data1 = document.getElementById('data1').innerHTML;
	var data1Arr = data1.split(' ');
	for ( var i = 0; i < data1Arr.length; i++) {
		data1Arr[i] = parseFloat(data1Arr[i]);
	}
	data1Arr.unshift('Total Liabilities');

	var x1 = document.getElementById('x1').innerHTML;
	var x1Arr = x1.split(' ');
	x1Arr.unshift('x1');

	/* Assets */
	var data2 = document.getElementById('data2').innerHTML;
	var data2Arr = data2.split(' ');
	for ( var i = 0; i < data2Arr.length; i++ ) {
		data2Arr[i] = parseFloat(data2Arr[i]);
	}
	data2Arr.unshift('Total Assets');

	var x2 = document.getElementById('x2').innerHTML;
	var x2Arr = x2.split(' ');
	x2Arr.unshift('x2');

	/* Net worths */
	var data3 = document.getElementById('data3').innerHTML;
	var data3Arr = data3.split(' ');
	for ( var i = 0; i < data3Arr.length; i++ ) {
		data3Arr[i] = parseFloat(data3Arr[i]);
	}
	data3Arr.unshift('Net Worth');

	var x3 = document.getElementById('x3').innerHTML;
	var x3Arr = x3.split(' ');
	x3Arr.unshift('x3');



	// var x = x1Arr;
	// var sample = data1Arr;

	/*var cols = [
		data1, x1
		//['data1', 30, 200, 100, 400, 150, 250],
		//['data2', 50, 20, 10, 40],
		//['x', 2016, 2017, 2018, 2019, 2021, 2022],
		//['x2', 2015, 2017, 2018, 2023]
	]; */

	var cols = [x1Arr, 
				x2Arr,
				x3Arr, 
				data1Arr, 
				data2Arr, 
				data3Arr];
	if (x0Arr != null) {
		cols.unshift(x0Arr);
		cols.unshift(data0Arr);
	}

	var chart = c3.generate({
		bindto: '#chart',
		data: {
			xs: {
				'Total Liabilities': 'x1',
				'Total Assets': 'x2',
				'Net Worth': 'x3',
				'Account 1': 'x0'
			},
			columns: cols
		},
		axis: {
			x: {
				type: 'timeseries',
				tick: {
				 	format: function(x) { return x.getFullYear(); }
				 }
			}
		},
		color: {
			pattern: [ '#990000', '#ffff00', '#000099', '#ff6600', '#660033' ]
		}, 
		legend: {
			position: 'right'
		}
	});
</script>



<script type="text/javascript">
	
</script>


<!--var chartData = <?php echo json_encode($chartData)?>;-->