<?php

class WorthDB {

	protected $db_hostname = "localhost";
	protected $db_database = "worth";
	protected $db_username = "root";
	protected $db_password = "mystockportfolio123";

	public $email = "";

	public function connect() {
		$db_server = mysql_connect($this->db_hostname, $this->db_username, $this->db_password) or die(mysql_error());
		mysql_select_db($this->db_database, $db_server);
	}

	private static function getQueryResult($query) {
		$result = mysql_query($query);
		if (!$result) {
			print 'BAD MYSQL QUERY: ' . $query;
			return null;
		} else {
			return $result;
		}
	}

	public function credentialsValid($email, $password) {
		// return true if the supplied credentials match credentials from the database, false otherwise

		$query = "SELECT * FROM users WHERE email = '" . $email . "' AND password = '" . $password . "'";
		$result = $this->getQueryResult($query);

		// if there is no user with the given email address and password, the credentials are invalid
		// else the credentials are valid

		if (mysql_num_rows($result) == 0) {
			return false;
		} else {
			$this->email = $email;
			return true;
		}
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getTransactions($accountName) {
		$query = "SELECT accountName, merchant, amount, date, category FROM transactions WHERE email='" . $this->email . "' ORDER BY date";
		$result = $this->getQueryResult($query);
		$transactions = array();
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			$transactions[$i] = $row;
			$i++;
		}
		return $transactions;
	}

	public function getAccounts() {
		// returns a list of the user's accounts in alphabetical order
		// $email should be defined by now, since credentialsValid() should have been called and returned true
		$query = "SELECT accountName FROM accounts WHERE email = '" . $this->email . "' ORDER BY accountName";
		$result = $this->getQueryResult($query);
		$accounts = array();
		$i = 0;
		while ($row = mysql_fetch_array($result)) {
			$accounts[$i] = $row['accountName'];
			$i++;
		}
		return $accounts;
	}

}


?>
