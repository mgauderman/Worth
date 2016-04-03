<?php

$accounts = $db->getAccounts();

foreach ($accounts as $account) {
	print '<br />';
	print $account;
}
print '<br />';

?>