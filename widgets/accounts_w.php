<?php

$accounts = $db->getAccounts();

foreach ($accounts as $accountName) {
	print '<br />';
	print '<a href="index.php/?tas=' . urlencode($accountName) . '">' . $accountName . '</a>';	
}
print '<br />';

?>