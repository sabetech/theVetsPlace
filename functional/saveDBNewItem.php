<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	
	$itemName = $_REQUEST['itemName'];
	$itemType = $_REQUEST['itemType'];
	
	$response = $vets_Instance->saveDBNewItem($itemName,$itemType);
	if ($response){
		echo "{\"reply\":\"SUCCESS\"}";
	}else{
		echo "{\"reply\":\"FAILED\"}";
	}
	
	
?>