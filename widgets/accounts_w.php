<!-- <?php


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

$accounts = $db->getAccounts();

$count = 0;
foreach ($accounts as $accountName) {
	print '<br />';
	
	// if (isset($_GET['tas'])) {
	// 	$link = './?tas=' . urlencode($accountName) . ',' . ($_GET['tas']);
	// }
	$ischecked = "";

	$link = './?tas=' . urlencode($accountName);
	if (isset($_GET['tas'])) {
		$link = './?tas=' . urlencode($accountName . ',') . urlencode($_GET['tas']);

		if (strpos(urldecode($_GET['tas']), $accountName) !== false) {
			$ischecked = 'checked';

			if (strpos(urldecode($_GET['tas']), $accountName . ',') !== false) {
				
				// if the accountName is not the last one in the url, remove the accountName and the comma
				$link = './?tas=' . urlencode(str_replace($accountName . ',', '', urldecode($_GET['tas'])));
			
			} else {

				// the accountName is the last one in the url and we won't find a comma after the accountName, but we'll find one before it
				$link = './?tas=' . urlencode(str_replace(',' . $accountName, '', urldecode($_GET['tas'])));
				
				if (urlencode(str_replace($accountName, '', urldecode($_GET['tas']))) == '') {
					$link = '.';
				}
			}
		}
	}

	$name = 'check' . $count;

	echo '<div class="checkbox">
		<label>
			<input type="checkbox" ' . $ischecked . ' name="' . $name . '" onclick="window.location.href=\'' . $link . '\'">' . $accountName . '</input>

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
	<button type="submit" class="btn btn-primary" name="add_button">Add</button>
	<button type="submit" class="btn btn-danger" name="delete_button">Delete</button>
</form>
