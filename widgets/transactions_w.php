<?php

$accountsToDisplay = $db->getAccounts();
$selectedAccounts = "";

if (isset($_GET['tas'])) {
	$listOfAccountsToShow = urldecode($_GET['tas']);
	$selectedAccounts = explode(",", $listOfAccountsToShow);

	for ($x = 0; $x < count($selectedAccounts); $x++) {
		$selectedAccounts[$x] = ucwords($selectedAccounts[$x]);
	}

}

?>
<table class="table table-striped">
	<thead>
	    <tr>
	    	<th>Account</th>
			<th>Date</th>
			<th>Amount</th>
			<th>Merchant</th>
			<th>Category</th>
	    </tr>
		<tbody>
		<?php
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
		?>
		</tbody>
	</thead>
</table>