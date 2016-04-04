<?php

$accounts = $db->getAccounts();


foreach ($accounts as $accountName) {
	print '<br />';
	$link = './?tas=' . urlencode($accountName) . ',' . ($_GET['tas']);

	echo '<div class="checkbox">
		<label>
			<input type="checkbox" onclick="window.location.href=$link">' . $accountName . '</input>
		</label>
	</div>';

}
print '<br />';

echo '<div class="container-fluid">
	<div class="row">
		<div class="col-xs-6">
			<div class="input-group">
				<input type="text" class="form-control" placeholder="Account Name" aria-describeby="basic-addon2" name="accountName">
				<span class="input-group-btn">
					<button class="btn btn-primary" type="button" name="addAccount"> Add </button>
					<button class="btn btn-danger" type="button" name="removeAccount"> Remove </button>
				</span>
			</div>
		</div>
	</div>
</div>';
?>
