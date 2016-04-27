<?php
	$categories = $db->getCategories();
?>
<div>
	<div class="budget-tab">
		<table class="table table-striped ">
			<thead>
			    <tr>
					<th> Category </th>
					<th> Current Expenditure </th>
					<th> Budgeted Expenditure </th>
			    </tr>
			</thead>
			<tbody>
				<?php
				foreach ( $categories as $category ) {
					$expenditureOfCategory = $db->getTotalExpenditureOfCategory( $category );
					if ($expenditureOfCategory['expenditure'] > $expenditureOfCategory['budget']) {
						print '<tr style="color:green;">';
					} else if ($expenditureOfCategory['expenditure'] < $expenditureOfCategory['budget']) {
						print '<tr style="color:red;">';
					} else {
						print '<tr style="color:yellow;">';
					}
					print '<td class="budget-cat" style="    width: 40%">';
					print ( $category );
					print '</td>';
					print '<td>';
					print $expenditureOfCategory['expenditure'];
					print '</td>';
					print '<td>';
					print $expenditureOfCategory['budget'];
					print '</td></tr>
					';
				}
				?>
			</tbody>
		</table>
	</div>

	Category: <input type="input" id="category"></input>
	Budget: <input type="input" id="new-budget"></input>
	<input type="submit" onclick="updateBudget()"></input>
</div>

<style>
.budget-tab table td{
background: transparent;
border-radius:0px !important;
}
.budget-table thead tr {
	display: inline-flex;
}
.budget-tab  {
	max-height:500px;
	overflow-y:scroll;
}
.budget-table tbody,
.budget-table thead { display: block; }


</style>

<script type="text/javascript">
	function updateBudget() {
		var newBudget = document.getElementById("new-budget").value;
		var category = document.getElementById("category").value;
		if ( newBudget !== "" && category !== "" ) {
			if (category.contains(" & ")) {
				category = category.replace(" & ", "AND");
			}
			window.location = './' + encodeURI('index.php/?cat=' + category + '&bud=' + newBudget);
			// update the budget value in the database
			// update the expenditure values and re-apply styling to the text
		}
	}
	// Change the selector if needed
	var $table = $('.budget-table'),
	    $bodyCells = $table.find('tbody tr:first').children(),
	    colWidth;
	colWidth = $bodyCells.map(function() {
        return $(this).width();
    }).get();
    
    // Set the width of thead columns
    $table.find('thead tr').children().each(function(i, v) {
        $(v).width(colWidth[i]);
    });    
    $(window).trigger('resize');
	// Adjust the width of thead cells when window resizes
	$(window).resize(function() {
	    // Get the tbody columns width array
	    colWidth = $bodyCells.map(function() {
	        return $(this).width();
	    }).get();
	    
	    // Set the width of thead columns
	    $table.find('thead tr').children().each(function(i, v) {
	        $(v).width(colWidth[i]);
	    });    
	}).resize(); // Trigger resize handler
</script>