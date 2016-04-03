<?php


$accountsToDisplay = $db->getAccounts();


if (isset($_GET['tas'])) {
	$listOfAccountsToShow = urldecode($_GET['tas']);
	$selectedAccounts = explode(",", $listOfAccountsToShow);

	for ($x = 0; $x < count($selectedAccounts); $x++) {
		$selectedAccounts[$x] = ucwords($selectedAccounts[$x]);
	}

}
?>
 <html>
	<body>
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
					foreach($selectedAccounts as $account) {
						$accountName = $account;
						print '<tr><td>';
						print($accountName);
						print '</td>';
						$transactions = $db->getTransactions($accountName);
						foreach($transactions as $transaction) {
							if($transaction['accountName'] == $accountName) {
									print '<td>';
									print $transaction['date'];
									print '</td><td>';
									print $transaction['amount'];
									print '</td><td>';
									print $transaction['merchant'];
									print '</td><td>';
									print $transaction['category'];
									print '</td>';	
							}

					}

					}

				?>
				</tr>
				</tbody>
			</thead>
		</table>
	</body>
</html>
 
