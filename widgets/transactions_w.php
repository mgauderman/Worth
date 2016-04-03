<?php

$accountName = 'Visa Credit Card';
$transactions = $db->getTransactions($accountName);

foreach($transactions as $transaction) {
	print '<br />';
	print $transaction['date'];
	print '<br />';
	print $transaction['accountName'];
	print '<br />';
	print $transaction['amount'];
	print '<br />';
	print $transaction['merchant'];
	print '<br />';
	print $transaction['category'];
	print '<br />';	
}

?>