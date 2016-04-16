<?php 
?>
<div style="font-color:red;" id="chart">
	This is where the graph will go.
</div>

<link href="../vendors/c3.min.css" rel="stylesheet" type="text/css">
<script src="../vendors/d3.min.js" charset="utf-8"></script>
<script src="../vendors/c3.min.js"></script>

<script type="text/javascript">
	var cols = [
		['data1', 30, 200, 100, 400, 150, 250],
		['data2', 50, 20, 10, 40, 15, 25],
		['x', 2016, 2017, 2018, 2019, 2020, 2021]
	]; 


	var chart = c3.generate({
		bindto: '#chart',
		data: {
			x: 'x',
			columns: cols
			// axes: {
			// 	data2: 'y2'
			// }
		},
		axis: {
			x: {
				type: 'timeseries',
				tick: {
				 	format: '%Y-%m-%d'
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

	/*
axis: {
			
		},

	 */
</script>

