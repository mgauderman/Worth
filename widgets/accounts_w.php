<?php

$accounts = $db->getAccounts();

foreach ($accounts as $accountName) {
	print '<br />';
	print '<a href="index.php/?tas=' . urlencode($accountName) . '">' . $accountName . '</a>';	
}
print '<br />';


echo "<div class="container-fluid">
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
</div>";
?>