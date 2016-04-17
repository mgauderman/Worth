<?php
 
?>

<div style="font-color: red" id="chart-container">
	<div id="chart"></div>
	<p> 
		Start Date: <input type="text" id="start-datepicker" />
	</p>
	<p>
		End Date: <input type="text" id="end-datepicker" />
	</p>
</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="vendors/c3.min.css" type="text/css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="vendors/d3.min.js" charset="utf-8"></script>
<script src="vendors/c3.min.js"></script>

<script type="text/javascript">

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

	var cols = [
		['data1', 30, 200, 100, 400, 150, 250],
		['data2', 50, 20, 10, 40],
		['x', 2016, 2017, 2018, 2019, 2021, 2022],
		['x2', 2015, 2017, 2018, 2023]
	]; 

	var chart = c3.generate({
		bindto: '#chart',
		data: {
			xs: {
				'data1': 'x',
				'data2': 'x2'
			},
			columns: cols
		},
		axis: {
			xs: {
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