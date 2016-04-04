<html>
<?php

require_once("db_query.php");

$db = new WorthDB();
$db->connect();
$db->setEmail(urldecode($_GET['email']));
// print $_POST['add_button'];
// print urldecode($_POST['account']);
// print urldecode($_GET['email']);
if (isset($_POST['add_button'])) {
	$db->addAccount(urldecode($_POST['account']));
} else if (isset($_POST['delete_button'])) {
	$db->deleteAccount(urldecode($_POST['account']));
}

print '<script type="text/javascript">window.location.href = "..";</script>';

?>
</body>
</html>