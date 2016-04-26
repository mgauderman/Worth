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
		$query = 'INSERT INTO users (email, password) VALUES ("test", "blah");';
	}

	function testsetEmail() {
		$db = new WorthDB();
		$db->connect();
		$this->assertTrue($db->email == "");
		$db->setEmail('test@usc.edu');
		$this->assertTrue($db->email != "");
		$this->assertTrue($db->email == 'test@usc.edu');
	}

	function testgetAccounts() {
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('test@usc.edu');
		$db->getQueryResult('DELETE FROM accounts WHERE email="test@usc.edu";');
		$this->assertEquals(count($db->getAccounts()), 0);
		$db->getQueryResult('INSERT INTO accounts (email, accountName) VALUES ("test@usc.edu", "test account");');
		$this->assertEquals(count($db->getAccounts()), 1);
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

		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu";');
		$db->getQueryResult('DELETE FROM accounts WHERE email="test@usc.edu"');

		// insert some transactions
		$db->getQueryResult('INSERT INTO transactions (email, accountName, merchant, amount, date, category) VALUES ("test@usc.edu", "American Express Credit Card", "Spotify", -5, NOW(), "Entertainment");');
		$db->getQueryResult('INSERT INTO accounts (email, accountName) VALUES ("test@usc.edu", "American Express Credit Card");');

		// verify that we can get the transactions successfully
		$transactions = $db->getTransactions('American Express Credit Card');
		$this->assertTrue(sizeof($transactions) == 1);
		$this->assertTrue($transactions[0]['category'] == 'Entertainment');

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
		$db->getQueryResult('INSERT INTO transactions (email, accountName, merchant, amount, date, category, asset) VALUES ("test@usc.edu", "US Bank Account", "University of Southern California", 2.99, NOW(), "Work Income", 1);');
		$db->getQueryResult('INSERT INTO accounts (email, accountName) VALUES ("test@usc.edu", "US Bank Account");');
		$db->getQueryResult('INSERT INTO transactions (email, accountName, merchant, amount, date, category, asset) VALUES ("test@usc.edu", "First Republic Bank Account", "Stanford University", 1.47, NOW(), "Work Income", 1);');
		$db->getQueryResult('INSERT INTO accounts (email, accountName) VALUES ("test@usc.edu", "First Republic Bank Account");');

		// specify start and end date for desired total assets info, and then get them
		$startDate = '2000-01-01';
		$endDate = '2100-01-01';
		$totalAssets = $db->getTotalAssets($startDate, $endDate); // map for date->total assets to date

		// assert that the first key of $totalAssets is in the desired date format
		$this->assertEquals(strlen(split('-', key($totalAssets))[0]), 4);
		$this->assertEquals(strlen(split('-', key($totalAssets))[1]), 2);
		$this->assertEquals(strlen(split('-', key($totalAssets))[2]), 2);
		// Date format example: 2016-04-03

		// assert that the first value of $totalAssets is in the desired amount format
		$this->assertEquals(reset($totalAssets), 2.99+1.47);

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
		$startDate = '2000-01-01';
		$endDate = '2020-01-01';
		$totalLiabilities = $db->getTotalLiabilities($startDate, $endDate); // map for date -> total liabilities to date
		
		// assert that the first key of $totalAssets is in the desired date format
		$this->assertEquals(strlen(split('-', key($totalLiabilities))[0]), 4);
		$this->assertEquals(strlen(split('-', key($totalLiabilities))[1]), 2);
		$this->assertEquals(strlen(split('-', key($totalLiabilities))[2]), 2);
		// Date format example: 2016-04-03

		// assert that the first value of $totalAssets is in the desired amount format
		$this->assertTrue(reset($totalLiabilities) == 12.93);

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
		$startDate = '2001-01-01';
		$endDate = '2020-01-01';
		$netWorths = $db->getNetWorths($startDate, $endDate); // map for date->net worth to date

		// assert that the first key of $totalAssets is in the desired date format
		$this->assertTrue(strlen(split('-', key($netWorths))[0]) == 4);
		$this->assertTrue(strlen(split('-', key($netWorths))[1]) == 2);
		$this->assertTrue(strlen(split('-', key($netWorths))[2]) == 2);
		// Date format example: 2016-04-03

		// assert that the first value of $totalAssets is in the desired amount format
		$this->assertTrue(reset($netWorths) == 0);

		foreach ($netWorths as $netWorth) {
			$this->assertTrue($netWorth == 0);
		}

		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu";');
	}

	function testcreateUser() {
		$db = new WorthDB();
		$db->connect();

		$db->getQueryResult('INSERT INTO users VALUES ("asdf@asdf.edu", "pass");');
		$this->assertFalse($db->createUser("asdf@asdf.edu", 'blahblah'));
		$db->getQueryResult('DELETE FROM users WHERE email="asdf@asdf.edu";');

		$this->assertTrue($db->createUser('tempor@blah.com', 'password'));
		$result = $db->getQueryResult('SELECT * FROM users WHERE email="tempor@blah.com";');
		while ($row = mysql_fetch_array($result)) {
			$this->assertEquals($row['email'], 'tempor@blah.com');
			$this->assertEquals($row['password'], 'password');
		}
	}

	function testaddTransactions() {
		$db = new WorthDB();
		$db->connect();
		$db->setEmail('test@usc.edu');

		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu";');
		$db->getQueryResult('DELETE FROM accounts WHERE email="test@usc.edu";');

		$accountNameOne = 'Visa Credit Card';
		$transactions = array();
		$transactions[0] = array();
		$transactions[0]['date'] = '2002-03-04';
		$transactions[0]['amount'] = '-12.66';
		$transactions[0]['merchant'] = 'Chipotle';
		$transactions[0]['category'] = 'Dining';
		$transactions[0]['asset'] = 0;
		$db->addTransactions($accountNameOne, $transactions);

		$result = $db->getTransactions($accountNameOne);
		$tranAdded = false;
		foreach ($result as $tran) {
			if ($tran['category'] == 'Dining' && $tran['amount'] == -12.66) {
				$tranAdded = true;
			}
		}
		$this->assertTrue($tranAdded);
		$transactions[1] = array('date'=>'2005-05-27', 'amount'=>'-563.71', 'merchant'=>'Bloomingdale\'s', 'category'=>'Fashion', 'asset'=>0);
		$db->addTransactions($accountNameOne, $transactions);

		$result = $db->getTransactions($accountNameOne, $transactions);
		$transAdded = false;
		$tranOneAdded = false;
		$tranTwoAdded = false;
		foreach ($result as $tran) {
			if ($tran['category'] == 'Fashion' && $tran['amount'] == -563.71) {
				$tranTwoAdded = true;
			} else if ($tran['category'] == 'Dining' && $tran['amount'] == -12.66) {
				$tranOneAdded = true;
			}
		}
		$transAdded = $tranOneAdded && $tranTwoAdded;
		$this->assertTrue($transAdded);

		$db->getQueryResult('DELETE FROM transactions WHERE email="test@usc.edu";');
		$db->getQueryResult('DELETE FROM accounts WHERE email="test@usc.edu";');
	}

}


?>
