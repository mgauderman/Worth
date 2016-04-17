<?php 
?>
<div style="font-color:red;" id="chart">
</div>

<link href="vendors/c3.min.css" rel="stylesheet" type="text/css">
<script src="vendors/d3.min.js" charset="utf-8"></script>
<script src="vendors/c3.min.js"></script>

<script type="text/javascript">
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

<!--var chartData = <?php echo json_encode($chartData)?>;-->