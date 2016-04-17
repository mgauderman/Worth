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
		$db->setEmail('test@usc.edu');
		$accounts = $db->getAccounts();
		$db->addAccount('test');
		$this->assertTrue(sizeof($db->getAccounts()) > sizeof($accounts));
		$db->getQueryResult('DELETE FROM accounts WHERE email="test@usc.edu" AND "accountName="test";');
	}

	function testdeleteAccount() {
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('test@usc.edu');
		$db->getQueryResult('INSERT INTO accounts (email, accountName) VALUES ("test@usc.edu", "test");');
		$accounts = $db->getAccounts();
		$db->deleteAccount('test');
		$this->assertTrue(sizeof($db->getAccounts()) < sizeof($accounts));
	}

	function testgetTransactions() {
		// create db connection
		$db = new WorthDB();
		$db->connect();
		$db->email = 'test@usc.edu';

		// insert some transactions
		$db->getQueryResult('INSERT INTO transactions (email, accountName, merchant, amount, date, category) VALUES ("test@usc.edu", "American Express Credit Card", "Spotify", -5, NOW(), "Entertainment");');
		$db->getQueryResult('INSERT INTO accounts (email, accountName) VALUES ("test@usc.edu", "American Express Credit Card");');

		// verify that we can get the transactions successfully
		$transactions = $db->getTransactions('American Express Credit Card');
		$this->assertTrue(sizeof($transactions) == 1);
		$this->assertTrue($transactions[0]['category']) = 'Entertainment';
		$this->assertTrue($transactions[0]['email']) = 'test@usc.edu';

		// verify that we don't get any transactions if we give a bad account name
		$transactions = $db->getTransactions('some random nonexistent account');
		$this->assertTrue(sizeof($transactions) == 0);

		// delete the transactions we inserted
		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu";');
		$db->getQueryResult('DELETE FROM accounts WHERE email="test@usc.edu"');
	}

	function testgetTotalAssets() {
		// create db connection
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('test@usc.edu');

		// delete any accounts or transactions from before (not really necessary but just in case)
		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu";');
		$db->getQueryResult('DELETE FROM accounts WHERE email="test@usc.edu";');

		// insert some transactions
		$db->getQueryResult('INSERT INTO transactions (email, accountName, merchant, amount, date, category) VALUES ("test@usc.edu", "US Bank Account", "University of Southern California", 2.99, NOW(), "Work Income");');
		$db->getQueryResult('INSERT INTO accounts (email, accountName) VALUES ("test@usc.edu", "US Bank Credit Card");');
		$db->getQueryResult('INSERT INTO transactions (email, accountName, merchant, amount, date, category) VALUES ("test@usc.edu", "First Republic Bank Account", "Stanford University", 0.47, NOW(), "Work Income");');
		$db->getQueryResult('INSERT INTO accounts (email, accountName) VALUES ("test@usc.edu", "First Republic Bank Account");');

		// specify start and end date for desired total assets info, and then get them
		$startDate = '2000-01-01 00:00:01';
		$endDate = '2100-01-01 00:00:01';
		$totalAssets = $db->getTotalAssets($startDate, $endDate); // map for date->total assets to date

		// assert that the first key of $totalAssets is in the desired date format
		$grabDateRegex = '(\\d+)-(\\d+)-(\\d+) (\\d+):(\\d+):(\\d+)';
		// Date format example: 2016-04-03 06:59:32
		$this->assertEquals(preg_match(key($totalAssets), $grabDateRegex), 1);

		// assert that the first value of $totalAssets is in the desired amount format
		$grabAmountRegex = '^(\\d+)\\.(\\d{2})';
		$this->assertEquals(preg_match(reset($totalAssets), $grabAmountRegex), 1);

		// delete the transactions we inserted
		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu";');
		$db->getQueryResult('DELETE FROM accounts WHERE email="test@usc.edu";');
	}

	function testgetTotalLiabilities() {
		// create connection to db
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('test@usc.edu');

		// clear all accounts and transactions affiliated with test@usc.edu
		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu";');
		$db->getQueryResult('DELETE FROM accounts WHERE email="test@usc.edu";');

		// add some transactions
		$db->getQueryResult('INSERT INTO transactions (email, accountName, date, amount, merchant, category) VALUES ("test@usc.edu", "Discover Card", NOW(), -12.93, "Chick-fil-a", "Dining");');

		// specify start and end date
		$startDate = '2000-01-01 00:00:01';
		$endDate = '2020-01-01 00:00:01';
		$totalLiabilities = $db->getTotalAssets($startDate, $endDate); // map for date -> total liabilities to date
		$grabDateRegex = '(\\d+)-(\\d+)-(\\d+) (\\d+):(\\d+):(\\d+)';
		// Date format example: 2016-04-03 06:59:32
		// assert that the first key of $totalLiabilities is in the desired date format
		$this->assertEquals(preg_match(key($totalLiabilities), $grabDateRegex), 1);
		$grabAmountRegex = '-(\\d+)\\.(\\d{2})';
		$this->assertEquals(preg_match(reset($totalLiabilities), $grabAmountRegex), 1);

		// delete the transactions just inserted
		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu";');
	}
	
	function testgetNetWorths() {
		// connect to the db
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('test@usc.edu');

		// delete any previous stuff (not necessary but just in case)
		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu"');

		// insert some transactions that are assets and some transactions that are liabilities
		$db->getQueryResult('INSERT INTO transactions (email, accountName, date, amount, merchant, category) VALUES ("test@usc.edu", "Discover Card", NOW(), -12.93, "Chick-fil-a", "Dining");');
		$db->getQueryResult('INSERT INTO transactions (email, accountName, date, amount, merchant, category) VALUES ("test@usc.edu", "Discover Card", NOW(), 12.93, "Chick-fil-a", "Dining");');

		// specify a start and end date and get the net worth
		$startDate = '2001-01-01 00:00:01';
		$endDate = '2020-01-01 00:00:01';
		$netWorths = $db->getNetWorths($startDate, $endDate); // map for date->net worth to date

		// assert that the date and amount are in the desired format
		$grabDateRegex = '(\\d+)-(\\d+)-(\\d+) (\\d+):(\\d+):(\\d+)';
		// Date format example: 2016-04-03 06:59:32
		$this->assertEquals(preg_match(key($netWorths), $grabDateRegex), 1);
		$grabAmountRegex = '(\\d+)\\.(\\d{2})';
		$this->assertEquals(preg_match(reset($netWorths), $grabAmountRegex), 1);

		foreach ($netWorths as $netWorth) {
			$this->assertTrue($netWorth == "0.00");
		}

		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu";');
	}

	function testaddTransactions() {
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('test@usc.edu');
		$accountNameOne = 'Visa Credit Card';
		$transactions = array();
		$transactions[0] = array();
		$transactions[0]['date'] = '2002-03-04 11:54:39';
		$transactions[0]['amount'] = '-12.66';
		$transactions[0]['merchant'] = 'Chipotle';
		$transactions[0]['category'] = 'Dining';
		$db->addTransactions($accountNameOne, $transactions);

		$result = $db->getTransactions($accountNameOne);
		$tranAdded = false;
		foreach ($result as $tran) {
			if ($tran == $transactions[0]) {
				$tranAdded = true;
			}
		}
		$this->assertTrue($tranAdded);

		$accountNameTwo = 'Visa Debit Card';
		$transactions[1] = array('date'=>'2005-05-27 09:04:00', 'amount'=>'-563.71', 'merchant'=>'Bloomingdale\'s', 'category'=>'Fashion');
		$db->addTransactions($accountNameTwo, $transactions);

		$result = $db->getTransactions($accountNameOne, $transactions);
		$transAdded = false;
		$tranOneAdded = false;
		$tranTwoAdded = false;
		foreach ($result as $tran) {
			if ($tran == $transactions[0]) {
				$tranOneAdded = true;
			} else if ($tran == $transactions[1]) {
				$tranTwoAdded = true;
			}
		}
		$transAdded = $tranOneAdded && $tranTwoAdded;
		$this->assertTrue($transAdded);

		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu";');
		$db->getQueryResult('DELETE FROM accounts WHERE email="test@usc.edu";');
	}

}


?>
