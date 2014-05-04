<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	
	$itemId = $_REQUEST['itemId'];
	$itemName = $_REQUEST['itemName'];
	$response = $vets_Instance->updateItem($itemId,$itemName);
	
	if ($response){
		echo "{\"reply\":\"SUCCESS\"}";
	}else{
		echo "{\"reply\":\"FAILED\"}";
	}
?>