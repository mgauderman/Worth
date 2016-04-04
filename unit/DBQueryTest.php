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

	function setEmail() {
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
		$db->email = 'udubey@usc.edu';
		$transactions = $db->getTransactions('Visa Credit Card');
		$this->assertTrue(sizeof($transactions) != 0);
		$transactions = $db->getTransactions('some random nonexistent account');
		$this->assertTrue(sizeof($transactions) == 0);
	}

}


?>
