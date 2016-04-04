<?php

$accounts = $db->getAccounts();

foreach ($accounts as $account) {
	print '<br />';
	print $account;
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