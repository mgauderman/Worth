<?php

?>
<div id="budget-container">
	<table class="table table-striped">
		<thead>
		    <tr>
				<th> Category </th>
				<th> Current Expenditure </th>
				<th> Budgeted Expenditure </th>
		    </tr>
			<tbody>
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
			// do some computation
		}
	}
</script>

		<!--
		<?php
			/*
			if ($selectedAccounts) {
				foreach ($selectedAccounts as $account) {
					$accountName = $account;
					$transactions = $db->getTransactions($accountName);
					foreach ($transactions as $transaction) {
						if (sizeof($transaction) != 0) {
							print '
							<tr><td>';
							print($accountName);
							print '</td>';
							print '<td>';
							print $transaction['date'];
							print '</td><td>';
							print $transaction['amount'];
							print '</td><td>';
							print $transaction['merchant'];
							print '</td><td>';
							print $transaction['category'];
							print '</td></tr>
							'; // on a separate line so the html is easier to read in "view source"
						}
					}
				}
			}
			*/
		?>
		-->
