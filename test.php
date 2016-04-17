<html>
<body>

<?php

	ini_set('display_errors', 1);

	require_once('php/db_query.php');
	$db = new WorthDB();
	$db->connect();
	$db->setEmail('test1@usc.edu');

	print var_dump($db->getTotalAssets('2012-04-01', '2020-01-01'));
	print '<br />';
	print var_dump($db->getTotalLiabilities('2012-04-01', '2020-01-01'));
	print '<br />';
	print var_dump($db->getNetWorths('2012-04-01', '2020-01-01'));
	print '<br />';
	print var_dump($db->getTransactionsForGraph('2012-04-01', '2020-01-01', 'test1'));
	

?>

</body>
</html>