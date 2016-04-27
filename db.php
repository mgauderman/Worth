<html><body>

<?php
	ini_set('display_errors', 1);
	$serverName = 'localhost';
	$username = 'root';
	$password = 'mystockportfolio123';
	$link = mysql_connect($serverName, $username, $password);
	/*if ($link) {
		print '[SUCCESS] Connected <br />'.
	} else {
		die('[FAILURE] Could not connect. ' . mysql_error());
	}*/
	$db_selected = mysql_select_db('worth')
	or die('[FAILURE] You need to create the "worth" database manually:<br /><strong>sudo mysql -u root -p<br /></strong>[enter password(s)]<strong><br />create database worth;<br />exit<br /></strong> and then revisit this page.
		');

	print 'If you havent already, do these steps manually or the stuff wont REALLY work<br />';
	print '<strong>sudo mysql -u root -p<br />';
	print 'set @@session.block_encryption_mode="aes-256-cbc";<br />';
	print 'set @iv = random_bytes(16);<br /></strong>';

	/*if (!$db_selected) {
		$query = 'CREATE DATABASE worth;';
		if (mysql_query($query, $link)) {
			print '[SUCCESS] Database \'worth\' created. <br />';
			$db_selected = mysql_select_db('worth', $link);
			if (!$db_selected) {
				die('[FAILURE] Selecting created db \'worth\' failed. ' . mysql_error());
			} else {
				print '[SUCCESS] Selected created db \'worth\'. <br />');
			}
		} else {
			die('Error creating database. ' . mysql_error());
		}
	}*/
	getQueryResult($link, 'drop table if exists users;');
	getQueryResult($link, 'drop table if exists transactions;');
	getQueryResult($link, 'drop table if exists accounts;');
	getQueryResult($link, 'drop table if exists budgets;');

	getQueryResult($link, 'create table users (email varchar(32) primary key, password varchar(256) not null);');
	getQueryResult($link, 'create table transactions (id int(100) unsigned auto_increment primary key, email varchar(32) not null, accountName varchar(32) not null, merchant varchar(32) not null, amount float(32) not null, date datetime(0) not null, category varchar(32) not null, asset int(2) not null);');
	getQueryResult($link, 'create table accounts (id int(100) unsigned auto_increment primary key, email varchar(32) not null, accountName varchar(32) not null);');

	getQueryResult($link, 'create trigger encr1 before insert on users for each row
		begin
			set new.email = AES_ENCRYPT(new.email, "thisispassword", @iv);
			set new.password = AES_ENCRYPT(new.password, "thisispassword", @iv);
		end;
 		');

	getQueryResult($link, 'create trigger encr2 before insert on transactions for each row
		begin
			set new.email = AES_ENCRYPT(new.email, "thisispassword", @iv);
			set new.accountName = AES_ENCRYPT(new.accountName, "thisispassword", @iv);
			set new.merchant = AES_ENCRYPT(new.merchant, "thisispassword", @iv);
			set new.category = AES_ENCRYPT(new.category, "thisispassword", @iv);
		end;
 		');

	getQueryResult($link, 'create trigger encr3 before insert on accounts for each row
		begin
			set new.email = AES_ENCRYPT(new.email, "thisispassword", @iv);
			set new.accountName = AES_ENCRYPT(new.accountName, "thisispassword", @iv);
		end;
 		');

	getQueryResult($link, 'insert into users values ("udubey@usc.edu", "temporary");');
	getQueryResult($link, 'insert into accounts values (1, "udubey@usc.edu", "Visa Credit Card");');
	getQueryResult($link, 'insert into accounts values (2, "udubey@usc.edu", "Debit Card");');
	getQueryResult($link, 'insert into accounts values (3, "udubey@usc.edu", "Charles Schwab Savings Account");');
	getQueryResult($link, 'insert into transactions values (1, "udubey@usc.edu", "Visa Credit Card", "Costco", -370.19, "2016-03-30", "Food & Groceries", 0);');
	getQueryResult($link, 'insert into transactions values (2, "udubey@usc.edu", "Debit Card", "Taco Bell", -4.22, "2015-09-09", "Food & Groceries", 0);');
	getQueryResult($link, 'insert into transactions values (3, "udubey@usc.edu", "Charles Schwab Savings Account", "VMware Inc.", 1000, "2012-04-02", "Savings", 1);');
	getQueryResult($link, 'insert into transactions values (6, "udubey@usc.edu", "Charles Schwab Savings Account", "Google Inc.", 400, "2014-11-28", "Income", 1);');
	getQueryResult($link, 'insert into transactions values (4, "udubey@usc.edu", "Debit Card", "Chick-fil-a", -75.78, "2015-05-27", "Food & Groceries", 0);');
	getQueryResult($link, 'insert into transactions values (5, "udubey@usc.edu", "Debit Card", "Century 16", 800, "2016-02-07", "Leisure & Entertainment", 0);');
	getQueryResult($link, 'insert into transactions values (7, "udubey@usc.edu", "Charles Schwab Savings Account", "Parasailing Co.", -1000, NOW(), "Leisure & Entertainment", 0);');
	getQueryResult($link, 'insert into transactions values (8, "udubey@usc.edu", "Visa Credit Card", "Rock Climbing Co.", -500, "2016-04-01", "Leisure & Entertainment", 0);');

	getQueryResult($link, 'create table budgets (id int(100) unsigned auto_increment primary key, email varchar(32) not null, category varchar(32) not null, budget float(32) not null);');

	getQueryResult($link, 'create trigger encr4 before insert on budgets for each row
		begin
			set new.email = AES_ENCRYPT(new.email, "thisispassword", @iv);
			set new.category = AES_ENCRYPT(new.category, "thisispassword", @iv);
		end;
 		');

	getQueryResult($link, 'insert into budgets values (1, "udubey@usc.edu", "Food & Groceries", -350);');
	getQueryResult($link, 'insert into budgets values (2, "udubey@usc.edu", "Leisure & Entertainment", -100);');

	function getQueryResult($link, $query) {
		if (mysql_query($query, $link)) {
			print '[SUCCESS] ' . $query . '<br />';
		} else {
			die('<h3><strong>[FAILURE] ' . mysql_error() . ' </strong></h3>');
		}
	}
	mysql_close($link);
	print '<h1><strong>Setup successful.</strong></h1>';
	// header("Location: index.php");
?>

</body></html>