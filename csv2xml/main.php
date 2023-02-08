<?php
require('./function.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '-1');


if(getDataFromCsvAndGenerateSmallerCsv(1000, 'data.csv')) {
	
	# Convert data to xml from csv's

	getDataFromCsvAndSave(1000);
}

?>