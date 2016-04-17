<html>
<body>

<?php

	ini_set('display_errors', 1);

	require_once('php/db_query.php');
	$db = new WorthDB();
	$db->connect();
	$db->setEmail('test1@usc.edu');

	print var_dump($db->getTotalAssets('2012-04-01', '2016-04-16'));
	print '<br />';
	print var_dump($db->getTotalLiabilities('2012-04-01', '2016-04-16'));
	print '<br />';
	print var_dump($db->getNetWorths('2012-04-01', '2016-04-16'));
	print '<br />';
	$db->addTransactions('my account', array(array(
		'date'=>'2012-06-22',
		'category'=>'my category',
		'amount'=>4.20,
		'merchant'=>'blaze pizza',
		'asset'=>1
		)));

?>

</body>
</html>