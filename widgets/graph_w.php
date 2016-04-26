<?php
	$startDate = date('Y-m-d', strtotime('-3 months')); // 3 months ago
	$endDate = date('Y-m-d'); // today's date
	if (isset($_GET['start'])) {
		$startDate = urldecode($_GET['start']);
	}
	if (isset($_GET['end'])) {
		$endDate = urldecode($_GET['end']);
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
				$allDatesBySpace = $allDatesBySpace . ' ' . $date;
				$allTlsBySpace = $allTlsBySpace . ' ' . $tl;
			}
			
			print '<div id="account-name-' . $accountCount . '" style=visibility:hidden;">' . $accountToDisplay . '</div>';
			print '<div id="account-dates-' . $accountCount . '" style="visibility:hidden;">' . substr($allDatesBySpace, 1) . '</div>';
			print '<div id="account-data-' . $accountCount . '" style="visibility:hidden;">' . substr($allTlsBySpace, 1) . '</div>';	

			$allDatesBySpace = '';
			$allTlsBySpace = '';
			$accountCount++;
		}
	}
	print '<div id="numAccounts" style="visibility: hidden;">' . $accountCount . '</div>'; 

	/* Liabilities */
	$totalLiabilities = $db->getTotalLiabilities($startDate, $endDate);
	$allDatesBySpace = '';
	$allTlsBySpace = '';
	foreach($totalLiabilities as $date => $tl) {
		$allDatesBySpace = $allDatesBySpace . ' ' . $date;
		$allTlsBySpace = $allTlsBySpace . ' ' . $tl;
	}

	print '<div id="liabilities-dates" style="visibility:hidden;">' . substr($allDatesBySpace, 1) . '</div>';
	print '<div id="liabilities-data" style="visibility:hidden;">' . substr($allTlsBySpace, 1) . '</div>';

	/* Assets */
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

		if (location.search == "" || !location.search.contains("tas=")) {
			window.location = "http://localhost/worth/?start=" + startDate + "&end=" + endDate;
		} else if (location.search.contains("&tas=") && !location.search.contains("start=")) {
			var tasParams = location.search.substring(location.search.indexOf("&tas="));
			window.location = "http://localhost/worth/?start=" + startDate + "&end=" + endDate + tasParams;
		} else if (location.search.contains("?tas=") && !location.search.contains("start=")) {
			var tasParams = location.search.substring(location.search.indexOf("?tas="));
			window.location = "http://localhost/worth" + tasParams + "&start=" + startDate + "&end=" + endDate;
		} else if (location.search.contains("&tas=") && location.search.contains("?start=")) {
			window.location = "http://localhost/worth" + "?start=" + startDate + "&end=" + endDate + location.search.substring(location.search.indexOf("&tas"));
		} else if (location.search.contains("?tas=") && location.search.contains("&start=")) {
			window.location = "http://localhost/worth" + location.search.substring(location.search.indexOf("?tas="), location.search.indexOf("&start=")) + "&start=" + startDate + "&end=" + endDate;
		} else {
			console.log('problem in graph_w.php');
			console.log(startDate);
			console.log(endDate);
			console.log(location.search);
		}

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

	/* transaction-x, transaction-data-
	 * Transactions for each account in tas */
	var count = document.getElementById('numAccounts').innerHTML;
	var cols = [];
	var accounts = [];
	for ( var i = 0; i < count; i++ ) {
		var account = document.getElementById( 'account-name-' + i ).innerHTML;
		if ( account !== null ) {
			accounts.push(account);
			var data = document.getElementById( 'account-data-' + i ).innerHTML;
			data = data.split( ' ' );
			for ( var j = 0; j < data.length; j++ ) {
				data[j] = parseFloat(data[j]);;
			}
			data.unshift( account );		

			var dates = document.getElementById( 'account-dates-' + i ).innerHTML;	
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

	for ( var i = 0; i < count; i++ ) {
		xs[accounts[i]] = 'Dates-' + i;
	}

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
			pattern: [ '#E91E63', '#2196F3', '#4CAF50', '#FFF176', '#FF5722', '#f44336', '#3F51B5', '#009688', '#FF9800', '#212121', '#D500F9' ]
		}, 
		legend: {
			position: 'right'
		}
	});

	// make set color for liabilities, net worth and assets
	// change legend to have account names
</script>
