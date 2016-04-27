<?php
	$categories = $db->getCategories();
?>
<div>
	<table class="table table-striped">
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
				print '<td>';
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

	Category: <input type="input" id="category"></input>
	Budget: <input type="input" id="new-budget"></input>
	<input type="submit" onclick="updateBudget()"></input>
</div>

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
</script>