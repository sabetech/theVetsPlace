<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	$date = $_REQUEST['date'];
	
	$date = date('Y-m-d',strtotime($date));
	$result = $vets_Instance->checkIfStockTaken($date);
	if ($result){
		echo "true";
	}else{
		echo "false";
	}
	
?>