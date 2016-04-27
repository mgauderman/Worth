<?php

// initiate a user session if we haven't already; we will be able to save the user's credentials this way
if (session_id() == '') { // for php >= 5.4.0: session_status() == PHP_SESSION_NONE
	session_start();
}

ini_set('display_errors', 1);

// include blackboxed methods for getting information from the database
require_once("php/db_query.php");
$db = new WorthDB();
$db->connect();

if (isset($_GET['cat']) && isset($_GET['bud']) && isset($_SESSION["user_email"])) {
	$cat = $_GET['cat'];
	$bud = $_GET['bud'];

	if (strpos($cat, "AND") !== false) {
		$cat = str_replace("AND", " & ", $cat);
	}
	require_once("php/db_query.php");
	$db->setEmail($_SESSION["user_email"]);
	$db->setBudget($cat, $bud);
	header("Location: http://localhost/worth/index.php");
} else {

	if (isset($_POST["inputEmail"]) && $_POST["inputEmail"] != "") {

		$email = $_POST["inputEmail"];
		$password = $_POST["inputPassword"];

		// encrypt the given password to compare against encrypted password in DB
		$encryptedPassword = encrypt($password);

		if ($db->credentialsValid($email, $encryptedPassword)) {
			$_SESSION["user_email"] = strtolower($email);
			$db->setEmail(strtolower($email));
		}

	}

	// if the user is not logged in, show the login page, else show the dashboard
	if (!isset($_SESSION["user_email"]) || $_SESSION['user_email'] == "") {
		require_once("views/login.php");
	} else {
		$db->setEmail($_SESSION["user_email"]);
		require_once("views/dashboard.php");
	}

}

function encrypt($pass_word) {
	return $pass_word;
}

?>
