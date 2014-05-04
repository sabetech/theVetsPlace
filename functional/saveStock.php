<?php
	require_once 'vetsplaceAPI.php';
	$itemId = $_REQUEST['itemId'];
	
	$date = $_REQUEST['date'];
	$date = date('Y-m-d',strtotime($date));
	
	$qty = $_REQUEST['quantity'];
	$vets_Instance = new vetsplaceAPI();
	
	$response = $vets_Instance->takeStock($itemId,$qty,$date);
	
	if ($response){
		echo "Stock for the date '$date' has been taken successfully";
	}else{
		echo "Oops sorry, something went wrong";
	}
?>