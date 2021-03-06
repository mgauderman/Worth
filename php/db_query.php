<?php

class WorthDB {

	protected $db_hostname = "localhost";
	protected $db_database = "worth";
	protected $db_username = "root";
	protected $db_password = "mystockportfolio123";

	public $email = "";
	private $link;

	public function connect() {
		$this->link = mysqli_connect($this->db_hostname, $this->db_username, $this->db_password, $this->db_database);
		if (mysqli_connect_errno()) {
			print mysqli_connect_error();
			exit();
		}
	}

	public function getQueryResult($query) {
		$tmp = $this->link;
		$result = mysqli_query($tmp, $query);
		if (!$result) {
			// print 'BAD MYSQL QUERY: ' . $query;
			return null;
		} else {
			return $result;
		}
	}

	public function credentialsValid($email, $password) {
		// return true if the supplied credentials match credentials from the database, false otherwise

		$query = "SELECT * FROM users WHERE email = AES_ENCRYPT('" . mysqli_real_escape_string($this->link, $email) . "', 'thisispassword', @iv) AND password = AES_ENCRYPT('" . mysqli_real_escape_string($this->link, $password) . "', 'thisispassword', @iv);";
		$result = $this->getQueryResult($query);

		// if there is no user with the given email address and password, the credentials are invalid
		// else the credentials are valid

		if (mysqli_num_rows($result) == 0) {
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
		$query = "SELECT AES_DECRYPT(accountName, 'thisispassword', @iv) AS accountName, AES_DECRYPT(merchant, 'thisispassword', @iv) AS merchant, amount AS amount, `date`, AES_DECRYPT(category, 'thisispassword', @iv) AS category FROM transactions WHERE email=AES_ENCRYPT('" . mysqli_real_escape_string($this->link, $this->email) . "', 'thisispassword', @iv) AND accountName = AES_ENCRYPT('" . mysqli_real_escape_string($this->link, $accountName) . "', 'thisispassword', @iv) ORDER BY `date`;";
		$result = $this->getQueryResult($query);
		$transactions = array();
		$i = 0;
		while ($row = $result->fetch_assoc()) {
			$row['amount'] = floatval(str_replace(',', '', number_format($row['amount'], 2)));
			$transactions[$i] = $row;
			$i++;
		}
		return $transactions;
	}

	public function getAccounts() {
		// returns a list of the user's accounts in alphabetical order
		// $email should be defined by now, since credentialsValid() should have been called and returned true
		$query = "SELECT AES_DECRYPT(accountName, 'thisispassword', @iv) AS accountName FROM accounts WHERE email = AES_ENCRYPT('" . mysqli_real_escape_string($this->link, $this->email) . "', 'thisispassword', @iv) ORDER BY AES_DECRYPT(accountName, 'thisispassword', @iv);";
		$result = $this->getQueryResult($query);
		$accounts = array();
		$i = 0;
		if ($result == null) {
			return $accounts;
		}
		while ($row = $result->fetch_assoc()) {
			$accounts[$i] = $row['accountName'];
			$i++;
		}
		return $accounts;
	}

	public function addAccount($accountName) {
		$this->deleteAccount($accountName);
		$query = "INSERT INTO accounts (email, accountName) VALUES ('" . mysqli_real_escape_string($this->link, $this->email) . "', '" . mysqli_real_escape_string($this->link, $accountName) . "');";
		$result = $this->getQueryResult($query);
		return $result;
	}

	public function deleteAccount($accountName) {
		$query = "DELETE FROM accounts WHERE email = AES_ENCRYPT('" . mysqli_real_escape_string($this->link, $this->email) . "', 'thisispassword', @iv) AND accountName = AES_ENCRYPT('" . mysqli_real_escape_string($this->link, $accountName) . "', 'thisispassword', @iv);";
		$result = $this->getQueryResult($query);
		$query = "DELETE FROM transactions WHERE email = AES_ENCRYPT('" . mysqli_real_escape_string($this->link, $this->email) . "', 'thisispassword', @iv) AND accountName = AES_ENCRYPT('" . mysqli_real_escape_string($this->link, $accountName) . "', 'thisispassword', @iv);";
		$result = $this->getQueryResult($query);
		return $result;
	}

	public function getTransactionsForGraph($startDate, $endDate, $accountName) {
		$query = 'SELECT `date`, amount FROM transactions WHERE email = AES_ENCRYPT("' . mysqli_real_escape_string($this->link, $this->email) . '", "thisispassword", @iv) AND accountName = AES_ENCRYPT("' . mysqli_real_escape_string($this->link, $accountName) . '", "thisispassword", @iv) AND `date` >= "' . mysqli_real_escape_string($this->link, $startDate) . '" AND `date` <= "' . mysqli_real_escape_string($this->link, $endDate) . ' 23:59:59" ORDER BY `date` ASC;';
		$result = $this->getQueryResult($query);
		if ($result == null) {
			return array();
		}
		$transactionsForGraph = array();
		$sum = 0;
		while ($row = $result->fetch_assoc()) {
			$sum = $sum + floatval(str_replace(',', '', number_format($row['amount'], 2)));
			$transactionsForGraph[split(' ', $row['date'])[0]] = $sum;
		}
		return $transactionsForGraph;
	}

	public function getTotalAssets($startDate, $endDate) {
		$query = 'SELECT `date`, amount FROM transactions WHERE email = AES_ENCRYPT("' . mysqli_real_escape_string($this->link, $this->email) . '", "thisispassword", @iv) AND asset = 1 AND `date` >= "' . mysqli_real_escape_string($this->link, $startDate) . '" AND `date` <= "' . mysqli_real_escape_string($this->link, $endDate) . ' 23:59:59" ORDER BY `date` ASC;';
		$result = $this->getQueryResult($query);
		$totalAssets = array(); // map from `date` (as string) to number
		$sum = 0;
		if ($result == null) {
			return $totalAssets;
		}
		while ($row = $result->fetch_assoc()) {
			$sum = $sum + floatval(str_replace(',', '', number_format($row['amount'], 2)));
			$totalAssets[split(' ', $row['date'])[0]] = $sum;
		}
		return $totalAssets;
	}

	public function getTotalLiabilities($startDate, $endDate) {
		$query = 'SELECT `date`, amount FROM transactions WHERE email = AES_ENCRYPT("' . mysqli_real_escape_string($this->link, $this->email) . '", "thisispassword", @iv) AND asset = 0 AND `date` >= "' . mysqli_real_escape_string($this->link, $startDate) . '" AND `date` <= "' . mysqli_real_escape_string($this->link, $endDate) . ' 23:59:59" ORDER BY `date` ASC;';
		$result = $this->getQueryResult($query);
		$totalAssets = array(); // map from `date` (as string) to number
		$sum = 0;
		if ($result == null) {
			return $totalAssets;
		}
		while ($row = $result->fetch_assoc()) {
			$sum = $sum + floatval(str_replace(',', '', number_format($row['amount'], 2)));
			$totalAssets[split(' ', $row['date'])[0]] = (-1) * $sum;
		}
		return $totalAssets;
	}

	public function getNetWorths($startDate, $endDate) {
		$query = 'SELECT `date`, amount FROM transactions WHERE email = AES_ENCRYPT("' . mysqli_real_escape_string($this->link, $this->email) . '", "thisispassword", @iv) AND `date` >= "' . mysqli_real_escape_string($this->link, $startDate) . '" AND `date` <= "' . mysqli_real_escape_string($this->link, $endDate) . ' 23:59:59" ORDER BY `date` ASC;';
		$result = $this->getQueryResult($query);
		$netWorths = array(); // map from `date` (as string) to number
		$sum = 0;
		if ($result == null) {
			return $netWorths;
		}
		while ($row = $result->fetch_assoc()) {
			$sum = $sum + floatval(str_replace(',', '', number_format($row['amount'], 2)));
			$netWorths[split(' ', $row['date'])[0]] = $sum;
		}
		return $netWorths;
	}

	public function addTransactions($accountName, $transactions) {
		$this->deleteAccount($accountName);
		if (in_array($accountName, $this->getAccounts())) {
			foreach($transactions as $transaction) {
				$isAssetAccount = $transactions[0]['asset'];
				if ($isAssetAccount == 1) {
					$query = 'INSERT INTO transactions (email, accountName, `date`, amount, merchant, category, asset) VALUES ("' . mysqli_real_escape_string($this->link, $this->email) . '", "' . mysqli_real_escape_string($this->link, $accountName) . '", "' . mysqli_real_escape_string($this->link, $transaction['date']) . '", ' . mysqli_real_escape_string($this->link, $transaction['amount']) . ', "' . mysqli_real_escape_string($this->link, $transaction['merchant']) . '", "' . mysqli_real_escape_string($this->link, $transaction['category']) . '", 1);';
				} else {
					$query = 'INSERT INTO transactions (email, accountName, `date`, amount, merchant, category, asset) VALUES ("' . mysqli_real_escape_string($this->link, $this->email) . '", "' . mysqli_real_escape_string($this->link, $accountName) . '", "' . mysqli_real_escape_string($this->link, $transaction['date']) . '", ' . mysqli_real_escape_string($this->link, $transaction['amount']) . ', "' . mysqli_real_escape_string($this->link, $transaction['merchant']) . '", "' . mysqli_real_escape_string($this->link, $transaction['category']) . '", 0);';
				}
				$this->getQueryResult($query);
			}
		} else {
			$this->addAccount($accountName);
			$isAssetAccount = $transactions[0]['asset'];
			foreach($transactions as $transaction) {
				if ($isAssetAccount == 1) {
					$query = 'INSERT INTO transactions (email, accountName, `date`, amount, merchant, category, asset) VALUES ("' . mysqli_real_escape_string($this->link, $this->email) . '", "' . mysqli_real_escape_string($this->link, $accountName) . '", "' . mysqli_real_escape_string($this->link, $transaction['date']) . '", ' . mysqli_real_escape_string($this->link, $transaction['amount']) . ', "' . mysqli_real_escape_string($this->link, $transaction['merchant']) . '", "' . mysqli_real_escape_string($this->link, $transaction['category']) . '", 1);';
				} else {
					$query = 'INSERT INTO transactions (email, accountName, `date`, amount, merchant, category, asset) VALUES ("' . mysqli_real_escape_string($this->link, $this->email) . '", "' . mysqli_real_escape_string($this->link, $accountName) . '", "' . mysqli_real_escape_string($this->link, $transaction['date']) . '", ' . mysqli_real_escape_string($this->link, $transaction['amount']) . ', "' . mysqli_real_escape_string($this->link, $transaction['merchant']) . '", "' . mysqli_real_escape_string($this->link, $transaction['category']) . '", 0);';
				}
				$this->getQueryResult($query);
			}
		}
	}

	public function createUser($email, $password) {
		// returns false if the user is already in the database, otherwise true
		$query = 'INSERT INTO users VALUES ("' . mysqli_real_escape_string($this->link, $email) . '", "' . mysqli_real_escape_string($this->link, $password) . '");';
		if ($this->getQueryResult($query)) {
			return true;
		} else {
			return false;
		}

		/*$query = 'SELECT * FROM users WHERE email="' . mysqli_real_escape_string($this->link, $email) . '";';
		if ($this->getQueryResult($query)) {
			return false; // user already exists
		} else {
			$query = 'INSERT INTO users VALUES ("' . mysqli_real_escape_string($this->link, $email) . '", "' . mysqli_real_escape_string($this->link, $password) . '");';
			$return $this->getQueryResult($query);
		}*/
	}

	public function getCategories() { // no need to have a database for this, hard coded accomplishes what this should do
		return array(
			'Leisure & Entertainment',
			'Food & Groceries',
			'Home & Household Maintenance',
			'Utilities',
			'Income',
			'Savings',
			'Health Care',
			'Consumer Debt',
			'Personal Care',
			'Hobbies',
			'Automobile & Maintenance',
			'Investments',
			'Childcare',
			'Clothing & Accessories',
			'Education',
			'Events',
			'Gifts',
			'Vacation',
			'Taxes & Fines'
			);
	}

	public function getTotalExpenditureOfCategory($category) {
		$query = 'SELECT budget FROM budgets WHERE email=AES_ENCRYPT("' . mysqli_real_escape_string($this->link, $this->email) . '", "thisispassword", @iv) AND category = AES_ENCRYPT("' . mysqli_real_escape_string($this->link, $category) . '", "thisispassword", @iv);';
		$result = $this->getQueryResult($query);
		$expenditureOfCategory = array();
		foreach ($this->getCategories() as $cat) {
			$expenditureOfCategory['name'] = $cat;
			$expenditureOfCategory['budget'] = number_format(0, 2);
			$expenditureOfCategory['expenditure'] = number_format(0, 2);
		}
		while ($row = $result->fetch_assoc()) {
			$expenditureOfCategory['categoryName'] = $category;
			$expenditureOfCategory['budget'] = number_format(floatval(str_replace(',', '', number_format($row['budget'], 2))), 2);
			$query = 'SELECT amount FROM transactions WHERE email = AES_ENCRYPT("' . mysqli_real_escape_string($this->link, $this->email) . '", "thisispassword", @iv) AND category = AES_ENCRYPT("' . mysqli_real_escape_string($this->link, $category) . '", "thisispassword", @iv) AND date >= DATE_FORMAT(NOW(), "%Y-%m-01") AND date <= NOW();';
			$resultTwo = $this->getQueryResult($query);
			$expenditure = 0.;
			while ($rowTwo = $resultTwo->fetch_assoc()) {
				$expenditure = $expenditure + floatval(str_replace(',', '', number_format($rowTwo['amount'], 2)));
			}
			$expenditure = number_format($expenditure, 2);
			$expenditureOfCategory['expenditure'] = $expenditure;
		}
		return $expenditureOfCategory;
	}

	public function setBudget($category, $amount) {
		if (!in_array($category, $this->getCategories())) {
			return false;
		}
		$query = 'DELETE FROM budgets WHERE email=AES_ENCRYPT("' . mysqli_real_escape_string($this->link, $this->email) . '", "thisispassword", @iv) AND category = AES_ENCRYPT("' . mysqli_real_escape_string($this->link, $category) . '", "thisispassword", @iv);';
		$result = $this->getQueryResult($query);
		$query = 'INSERT INTO budgets (email, category, budget) VALUES ("' . mysqli_real_escape_string($this->link, $this->email) . '", "' . mysqli_real_escape_string($this->link, $category) . '", ' . mysqli_real_escape_string($this->link, $amount) . ');';
		$result = $this->getQueryResult($query);
		return true;
	}
}


?>
