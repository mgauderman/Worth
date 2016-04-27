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
			<tbody>
				<?php
				/* 
				foreach ( $categories as $category ) {
					$expenditurePerCategory = $db->getTotalExpenditurePerCategory( $category );
					foreach ( $expenditurePerCategory as $perCategory ) {
						print '
						<tr><td>';
						print ( $category );
						print '</td>';
						print '<td>'
						pring $perCategory['expenditure'];
						print '</td>'
						print '<td>'
						print $perCategory['budget']
						print '</td></tr>
						';
					}
				}
				*/
				?>
			</tbody>
		</thead>
	</table>

	Category: <input type="input" id="category"></input>
	Budget: <input type="input" id="new-budget"></input>
	<input type="submit" onclick="updateBudget()"></input>
</div>

<script type="text/javascript">
	function updateBudget() {
		var newBudget = document.getElementById("new-budget").innerHTML;
		var category = document.getElementById("category").innerHTML;
		if ( newBudget !== null && category !== null ) {
			// update the budget value in the database
			// update the expenditure values and re-apply styling to the text
		}
	}
</script>