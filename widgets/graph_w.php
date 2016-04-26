<?php

	$startDate = '2016-01-17';
	$endDate = '2016-04-17';
	if (isset($_GET['start']) && isset($_GET['end'])) {
		$startDate = $_GET['start'];
		$endDate = $_GET['end'];
	}

	$allDatesBySpace = '';
	$allTlsBySpace = '';
	$accountCount = 0;
	if (isset($_GET['tas']) ) {
		$tas = urldecode($_GET['tas']);
		$tas = explode(',', $tas);

		foreach( $tas as $accountToDisplay ) {
			$transactions = $db->getTransactionsForGraph($startDate, $endDate, $accountToDisplay);

			foreach($transactions as $date => $tl) {
				print '<p>here I am inside the loops</p>';
				$allDatesBySpace = $allDatesBySpace . ' ' . $date;
				$allTlsBySpace = $allTlsBySpace . ' ' . $tl;
			}
			
			print '<div id="transaction-dates-' . $accountCount . '" style="visibility:hidden;">' . substr($allDatesBySpace, 1) . '</div>';
			print '<div id="transaction-data-' . $accountCount . '" style="visibility:hidden;">' . substr($allTlsBySpace, 1) . '</div>';

			$allDatesBySpace = '';
			$allTlsBySpace = '';
			$accountCount++;
		}
	}
	print '<div id="numAccounts" style="visibility: hidden;">' . $accountCount . '</div>'; 

	/* Liabilities */
	$totalLiabilities = $db->getTotalLiabilities($startDate, $endDate);
//	$count = 0;
	$allDatesBySpace = '';
	$allTlsBySpace = '';
	foreach($totalLiabilities as $date => $tl) {
		$allDatesBySpace = $allDatesBySpace . ' ' . $date;
		$allTlsBySpace = $allTlsBySpace . ' ' . $tl;
	}

	print '<div id="liabilities-dates" style="visibility:hidden;">' . substr($allDatesBySpace, 1) . '</div>';
	print '<div id="liabilities-data" style="visibility:hidden;">' . substr($allTlsBySpace, 1) . '</div>';

	/* Assets */
//	$count++;
	$allDatesBySpace = '';
	$allTlsBySpace = '';
	$totalAssets = $db->getTotalAssets( $startDate, $endDate );
	foreach( $totalAssets as $date => $tl ) {
		$allDatesBySpace = $allDatesBySpace . ' ' . $date;
		$allTlsBySpace = $allTlsBySpace . ' ' . $tl;
	}

	print '<div id="assets-dates" style="visibility:hidden;">' . substr($allDatesBySpace, 1) . '</div>';
	print '<div id="assets-data" style="visibility:hidden;">' . substr($allTlsBySpace, 1) . '</div>';

	/* Net Worth */
//	$count++;
	$allDatesBySpace = '';
	$allTlsBySpace = '';
	$totalNetWorths = $db->getNetWorths( $startDate, $endDate );
	foreach( $totalNetWorths as $date => $tl ) {
		$allDatesBySpace = $allDatesBySpace . ' ' . $date;
		$allTlsBySpace = $allTlsBySpace . ' ' . $tl;
	}

	print '<div id="net-worth-dates" style="visibility:hidden;">' . substr($allDatesBySpace, 1) . '</div>';
	print '<div id="net-worth-data" style="visibility:hidden;">' . substr($allTlsBySpace, 1) . '</div>';
 
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

	/* Transactions for first element in tas 
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
	*/

	/* transaction-x, transaction-data-
	 * Transactions for each account in tas */
	var count = document.getElementById('numAccounts').innerHTML;
	var cols = [];
	for ( var i = 0; i < count; i++ ) {
		var data = document.getElementById( 'transaction-data-' + i ).innerHTML;
		if ( data !== null ) {
			data = data.split( ' ' );
			for ( var j = 0; j < data.length; j++ ) {
				data[j] = parseFloat(data[j]);;
			}
			data.unshift( 'Data-' + i );		

			var dates = document.getElementById( 'transaction-dates-' + i ).innerHTML;	
			dates = dates.split( ' ' );
			dates.unshift( 'Dates-' + i );
			cols.push( data );
			cols.push( dates );
		}
	}


	/* Liabilities */
	var liabilitiesData = document.getElementById('liabilities-data').innerHTML;
	liabilitiesData = liabilitiesData.split(' ');
	for ( var i = 0; i < liabilitiesData.length; i++) {
		liabilitiesData[i] = parseFloat(liabilitiesData[i]);
	}
	liabilitiesData.unshift('Total Liabilities');

	var liabilitiesDates = document.getElementById('liabilities-dates').innerHTML;
	liabilitiesDates = liabilitiesDates.split(' ');
	liabilitiesDates.unshift('Liabilities Dates');

	/* Assets */
	var assetsData = document.getElementById('assets-data').innerHTML;
	assetsData = assetsData.split(' ');
	for ( var i = 0; i < assetsData.length; i++ ) {
		assetsData[i] = parseFloat(assetsData[i]);
	}
	assetsData.unshift('Total Assets');

	var assetsDates = document.getElementById('assets-dates').innerHTML;
	assetsDates = assetsDates.split(' ');
	assetsDates.unshift('Assets Dates');

	/* Net worths */
	var netWorthData = document.getElementById('net-worth-data').innerHTML;
	netWorthData = netWorthData.split(' ');
	for ( var i = 0; i < netWorthData.length; i++ ) {
		netWorthData[i] = parseFloat(netWorthData[i]);
	}
	netWorthData.unshift('Net Worth');

	var netWorthDates = document.getElementById('net-worth-dates').innerHTML;
	netWorthDates = netWorthDates.split(' ');
	netWorthDates.unshift('Net Worth Dates');

	cols.push( liabilitiesData );
	cols.push( assetsData );
	cols.push( netWorthData );
	cols.push( liabilitiesDates );
	cols.push( assetsDates );
	cols.push( netWorthDates );

	var xs = {
		'Total Liabilities': 'Liabilities Dates',
		'Total Assets': 'Assets Dates',
		'Net Worth': 'Net Worth Dates'
	};

	console.log ( count );

	for ( var i = 0; i < count; i++ ) {
		xs['Data-' + i] = 'Dates-' + i;
	}

	console.log( xs );

	var chart = c3.generate({
		bindto: '#chart',
		data: {
			xs: xs,
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