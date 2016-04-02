<?php

$db_hostname = "localhost";
$db_database = "worth";
$db_username = "root";
$db_password = "mystockportfolio123";

$db_server = mysql_connect($db_hostname, $db_username, $db_password) or die('MySQL connection failed.');
mysql_select_db($db_database, $db_server);

function getQueryResult($query) {
	return mysql_query($query);
}

function credentialsValid($email, $password) {
	// return true if the supplied credentials match credentials from the database, false otherwise

	$query = "SELECT * FROM users WHERE email = '" . $email . "' AND password = '" . $password . "'";
	$result = getQueryResult($query);

	// if there is no user with the given email address and password, the credentials are invalid
	// else the credentials are valid

	if (mysql_num_rows($result) == 0) {
		return false;
	} else {
		return true;
	}
}


?>
