<?php

// if the user manually goes to "http://.../dashboard.php" without being logged in, we direct them to login
if ($_SESSION["user_email"] == "") {
	header("Location: ..");
}

if (isset($_POST['addAccount'])) {
	$db->addAccount($_POST['accountName']);
}

if (isset($_POST['removeAccount'])) {
	$db->removeAccount($_POST['accountName']);
}


$email = $_SESSION["user_email"];

?>

<!DOCTYPE html>
<html lang="en">

	<head>

		<title>Worth</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Worth for CSCI 310, Group I">
		<meta name="author" content="Utkash Dubey">
		<link rel="icon" href="favicon.ico">
		<!-- Latest compiled and minified CSS -->
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<!-- Custom styles -->
		<link href="views/dashboard.css" rel="stylesheet">

		<style>
			#header {
			    text-align:center;
			    padding:5px;
			}
			#nav {
			    line-height:30px;
			    height:300px;
			    width:300px;
			    float:left;
			    padding:5px; 
			}
			#section {
			    width:350px;
			    float:left;
			    padding:10px; 
			}
			#footer {
			    background-color:black;
			    color:white;
			    clear:both;
			    text-align:center;
			    padding:5px; 
			}
			#aside {
				line-height:30px;
				height:300px;
				width:260px;
				float:right;
				padding:5px;
			}
		</style>

	</head>

	<body onload="startTime()">

		<div class="container-fluid">
			<?php
				print '<a href="php/logout.php">Logout</a><br />';
				print '<br /><h2>Accounts:</h2></br />';
				require_once('widgets/accounts_w.php');
			
				print '<br /><h2>Transactions:</h2></br />';
				require_once('widgets/transactions_w.php');
			?>

		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="../jquery.min.js"><\/script>')</script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	</body>

</html>
