<?php

require_once('../php/db_query.php');

class DB_QueryTest extends PHPUnit_Framework_Testcase {

	function testconnect() {
		$db = new WorthDB();
		$db->connect();
		// since the connection failing returns a "die"
		// the test will fail exactly when the connection fails
	}

	function testgetQueryResult() {
		$db = new WorthDB();
		$db->connect();
		$query = 'do something bad asdf;';
		$result = $db->getQueryResult($query);
		$this->assertTrue($result == null);
		$query = 'SELECT * FROM users;';
		$result = $db->getQueryResult($query);
		$this->assertTrue($result != null);
	}

	function testcredentialsValid() {
		$db = new WorthDB();
		$db->connect();
		$this->assertTrue($db->credentialsValid("udubey@usc.edu","temporary"));
		$this->assertFalse($db->credentialsValid("udubey@usc.edu","foo"));
		$this->assertFalse($db->credentialsValid("udubey@usc.edu","abc"));
	}

	function testsetEmail() {
		$db = new WorthDB();
		$db->connect();
		$this->assertTrue($db->email == "");
		$db->setEmail('test@usc.edu');
		$this->assertTrue($db->email != "");
		$this->assertTrue($db->email == 'test@usc.edu');
	}

	
	function testaddAccount() {
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('udubey@usc.edu');
		$accounts = $db->getAccounts();
		$db->addAccount('test');
		$this->assertTrue(sizeof($db->getAccounts()) > sizeof($accounts));
	}

	function testdeleteAccount() {
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('udubey@usc.edu');
		$accounts = $db->getAccounts();
		$db->deleteAccount('test');
		$this->assertTrue(sizeof($db->getAccounts()) < sizeof($accounts));
	}

	function testgetTransactions() {
		$db = new WorthDB();
		$db->connect();
		$db->email = 'test@usc.edu';
		$transactions = $db->getTransactions('American Express Credit Card');
		$this->assertTrue(sizeof($transactions) != 0);
		$transactions = $db->getTransactions('some random nonexistent account');
		$this->assertTrue(sizeof($transactions) == 0);
	}

	// TODO check whether regex is okay
	// TODO populate test@usc.edu with transactions
	function testgetTotalAssets() {
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('test@usc.edu');
		$startDate = '2000-01-01 00:00:01';
		$endDate = '2020-01-01 00:00:01';
		$totalAssets = $db->getTotalAssets($startDate, $endDate); // map for date to total asset to date
		$grabDateRegex = '(\\d+)-(\\d+)-(\\d+) (\\d+):(\\d+):(\\d+)';
		// Date format example: 2016-04-03 06:59:32
		// assert that the first key of $totalAssets is in the desired date format
		$this->assertEquals(preg_match(key($totalAssets), $grabDateRegex), 1);
		$grabAmountRegex = '^(\\d+)\\.(\\d{2})';
		$this->assertEquals(preg_match(reset($totalAssets), $grabAmountRegex), 1);
	}

	function testgetTotalLiabilities() {
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('test@usc.edu');
		$startDate = '2000-01-01 00:00:01';
		$endDate = '2020-01-01 00:00:01';
		$totalAssets = $db->getTotalAssets($startDate, $endDate); // map for date to total asset to date
		$grabDateRegex = '(\\d+)-(\\d+)-(\\d+) (\\d+):(\\d+):(\\d+)';
		// Date format example: 2016-04-03 06:59:32
		// assert that the first key of $totalAssets is in the desired date format
		$this->assertEquals(preg_match(key($totalAssets), $grabDateRegex), 1);
		$grabAmountRegex = '-(\\d+)\\.(\\d{2})';
		$this->assertEquals(preg_match(reset($totalAssets), $grabAmountRegex), 1);
	}

	function testgetNetWorth() {
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('udubey@usc.edu');
	}

	function testaddTransactions() {
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('udubey@usc.edu');
	}

	function testgetTotalAssets() {
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('udubey@usc.edu');
	}

}


?>
