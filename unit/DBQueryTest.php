<?php

require_once('../php/db_query.php');

class DB_QueryTest extends PHPUnit_Framework_Testcase {

	function testconnect() {
		$db = new WorthDB();
		$db->connect();
		//$this-> DO SOME ASSERTIONS HERE
	}

	// test $db->credentialsValid($email, $encryptedPassword)
	function testcredentialsValid() {
		$db = new WorthDB();
		$db->connect();
		$this->assertTrue($db->credentialsValid("udubey@usc.edu","temporary"));
		$this->assertFalse($db->credentialsValid("udubey@usc.edu","foo"));
		$this->assertFalse($db->credentialsValid("udubey@usc.edu","abc"));
	}

}


?>
