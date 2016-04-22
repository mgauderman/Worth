<?php
// $accountsToDisplay = $db->getAccounts();
// var_dump($accountsToDisplay) ;
	// This block creates a label and a button for csv file importing and
	// allows the user to select a file from their computer
	// echo "<div class=\"form-group\">
	// 	<form enctype=\"multipart/form-data\" method=\"POST\" action=\"php/csv_process.php\">
	// 	    <label for=\"csvInputFile\" style=\"margin: 3px\">.csv Import</label>
	// 	    <input type=\"file\" class=\"btn btn-default\" name=\"csvInputFile\" id=\"csvInputFile\" style=\"margin: 3px\">
	// 	    <input type=\"submit\" value=\"Upload File\" style=\"margin: 3px\">
	//     </form>
 //    </div>";

    // <p class=\"help-block\">Choose a .csv file to import</p>
    // add ^that line to make a helper tip below input file button to tell user what to do
?>



<form action="" method="post" enctype="multipart/form-data">
    <input style="display: inline;" type="file" name="csv" value="csv" />
    <input type="submit" name="submit" value="Save" />
</form>
<!-- <input type="file" accept=".csv" />
 -->
<?php
$csv = array();

// check there are no errors
if($_FILES) {
    if($_FILES['csv']['error'] == 0){
    $name = $_FILES['csv']['name'];
    $ext = strtolower(end(explode('.', $_FILES['csv']['name'])));
    $type = $_FILES['csv']['type'];
    $tmpName = $_FILES['csv']['tmp_name'];

    // check the file is a csv
    if($ext === 'csv'){
        if(($handle = fopen($tmpName, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);
            $nicename = substr($name, 0, -4);
            // echo $nicename;
            $row = 0;
            $account = array();
            $account['name'] = str_replace('.csv', '', $name);
            $count = 0;
            while(($data = fgetcsv($handle, 1000, ' ')) !== FALSE) {
                // echo $data[0];
                $transaction = (explode(",",$data[0]));
                // echo $transaction[0];
                if ($count == 0 && $transaction[0] == "Asset"){
                    $account['transactions'][0]['asset'] = 1;
                    $count++;
                }
                else if ($count == 0) {
                    $account['transactions'][0]['asset'] = 0;
                    $count++;
                } else {
                    $account['transactions'][$count-1]['date'] = $transaction[0] . ' ' . $transaction[1];
                    $account['transactions'][$count-1]['time'] = $transaction[1];
                    $account['transactions'][$count-1]['amount'] = $transaction[2];
                    $account['transactions'][$count-1]['merchant'] = $transaction[3];
                    $account['transactions'][$count-1]['category'] = $transaction[4];
                    $count++;
                }
            }
            $db->addTransactions($account['name'], $account['transactions']);
            fclose($handle);
        }
    }

}
}

?>