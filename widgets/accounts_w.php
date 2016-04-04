<!-- <?php

$accounts = $db->getAccounts();


// foreach ($accounts as $accountName) {
// 	print '<br />';
// 	$link = './?tas=' . urlencode($accountName) . ',' . ($_GET['tas']);

// 	echo '<div class="checkbox">
// 		<label>
// 			<input type="checkbox" onclick="window.location.href=$link">' . $accountName . '</input>
// 		</label>
// 	</div>';

// }
// print '<br />';

// echo '<div class="container-fluid">
// 	<div class="row">
// 		<div class="col-xs-6">
// 			<div class="input-group">
// 				<input type="text" class="form-control" placeholder="Account Name" aria-describeby="basic-addon2" name="accountName">
// 				<span class="input-group-btn">
// 					<button class="btn btn-primary" type="button" name="addAccount"> Add </button>
// 					<button class="btn btn-danger" type="button" name="removeAccount"> Remove </button>
// 				</span>
// 			</div>
// 		</div>
// 	</div>
// </div>';
?> -->


<?php

foreach ($accounts as $accountName) {
	print '<br />';
	$link = './?tas=' . urlencode($accountName);
	if (isset($_GET['tas'])) {
		$link = './?tas=' . urlencode($accountName) . urlencode(',') . ($_GET['tas']);
	}

	echo '<div class="checkbox">
		<label>
			<input type="checkbox" onclick="window.location.href=\'' . $link . '\'">' . $accountName . '</input>
		</label>
	</div>';

}
print '<br />';

echo '<form action="php/add_delete_handle.php?email=' . urlencode($email) . '" id="addDelForm" method="post">';

?>

<div class="form-inline">
		<div class="form-group">
			<label for="account">Account Name</label>
			<input type="text" class="form-control" id="account" name="account" placeholder="Visa Credit Card">
		</div>
	</div>
	<button type="submit" class="btn btn-default" name="add_button">Add</button>
	<button type="submit" class="btn btn-default" name="delete_button">Delete</button>
</form>
