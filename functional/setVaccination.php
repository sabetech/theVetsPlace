<?php
	require_once 'vetsplaceAPI.php';
	
	$petId = $_REQUEST['petId'];
    $occurence = $_REQUEST['occurence'];
	$day = $_REQUEST['day'];
	$month = $_REQUEST['month'];
	$year = $_REQUEST['year'];
	$description = $_REQUEST['description'];
	
	$test_date = "$month/$day/$year";
	//echo $test_date;
	$test_arr  = explode('/', $test_date);
	if (count($test_arr) == 3) {
		if (checkdate($test_arr[0], $test_arr[1], $test_arr[2])) {
			// valid date ...
			$date = "$year-$month-$day";
			$date = date('Y-m-d',strtotime($date));
			$vets_Instance = new vetsplaceAPI();
			$setResult = $vets_Instance->setVaccination($petId,$occurence,$date,$description);
	
			if ($setResult){
				echo "{\"status\":\"success\"}";
			}else{
				echo "{\"status\":\"fail\"}";
			}
			
		} else {
			// problem with dates ...
			echo "{\"status\":\"fail\"}";
		}
	} else {
		// problem with input ...
		echo "{\"status\":\"fail\"}";
	}
	
?>