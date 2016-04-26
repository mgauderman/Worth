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
			body {
			    background-color: #333333;
			}

			h2 {
				text-align: center;
			}

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

			.haveAccount {
			    margin-top: 30px;
			    text-align: right;
			}     

			.checking {
			     width: 13px;
			    height: 13px;
			    padding: 0;
			    margin:0;
			    vertical-align: bottom;
			    position: relative;
			    top: -1px;
			    *overflow: hidden;
			}

			.pointer {
			    cursor: pointer;
			}

			.moduleDiv {
			    width:100%;
			    height:300px;
			/*    background-color: red;
			*/}

			.table-hover tbody tr:hover td{
			  background-color: #f2f2f2;
			}

			table.table.portfolioWidget th {
			    background-color: white
			}
			table.table.portfolioWidget td,
			table.table.portfolioWidget th {
			    border-radius: 0px !important;
			    height: 40px;
			    text-align: center;
			}

			.portfolioPage td {
			    background-color: lightgray;
			    border-radius: 10px !important;
			    padding: 20px;
			    vertical-align: top;
			}
			.pageHeader {
			    
			}

			.moduleTitle {
			    margin-top:0px !important;
			    text-align: center !important;
			}

			.regAndLogForm {
			    border-radius: 10px !important;
			    background-color: white;
			    width:500px;
			    height:600px;
			    margin:auto;
			    padding: 50px;
			    position:absolute;
			    top:0;
			    bottom:0;
			    right:0;
			    left:0;      
			}
			#regPage {

			}

			.formButton:active regForm {
			    display:none;
			}

			.formButton {
			    display: block;
			    margin: auto;
			    margin-top: 30px;
			}

			.error {
			    color:#FF0000;
			}
		</style>

	</head>

	<body>


		<table class ="portfolioPage" style=" border-collapse: separate; border-spacing: 15px; width:100% ">
			<thead>
			</thead>
				<tbody>
					<!-- Top -->
					<tr>
						<!-- Import csv -->
						<td style ="width: 400px; height: 80px; text-align:center; padding-top:0px">
							<h2> CSV Import </h2>
							<?php require_once('widgets/csv_w.php');?>
						</td>
						<!-- Search -->
						<td style="width:50%; text-align: -webkit-center;">
						
						</td>

						<!-- Date, User Manual, Logout -->
						<td style="vertical-align:middle;width:25%; text-align:center ">
							<a href="php/logout.php" class="btn btn-default" style="width:70%">Logout</a>
						</td>
					</tr>

					<!-- Middle -->
					<tr>
						<!-- Accounts -->
						<td class="accountsWidget" style="padding-top:0px;height:480px; width:25%; padding-top:0px;">
							<h2> Accounts </h2>
							<?php require_once('widgets/accounts_w.php'); ?>
						</td>

						<!-- Graph -->
						<td class="graphWidget" style="vertical-align: top; border-collapse: collapse;">
							<h2> Graph </h2>
							<?php require_once('widgets/graph_w.php');?>
						</td>

						<!--Budget -->
						<td class="budgetWidget" style="padding-top:0px">	
							<h2> Monthly Budget </h2>
						</td>
						
					</tr>

					<!-- Bottom -->
					<tr>
						<!-- Bottom Left -->
						<td class="buySellWidget" style="height: 255px;padding-top: 0px;  border-collapse: collapse;">
						
						</td>
						<!-- Transactions-->
						<td class="transactionsWidget" style="padding-top:0px;height:200px ">
							<h2>Transactions</h2>
							<?php require_once('widgets/transactions_w.php');?>
						</td>

						<!-- Bottom Right-->
						<td style="height:200px; ">

						</td>

					</tr>
					
				</tbody>

		</table>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="../jquery.min.js"><\/script>')</script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	</body>

</html>
