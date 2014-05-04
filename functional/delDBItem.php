<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	
	$itemId = $_REQUEST['itemId'];
	
	$response = $vets_Instance->delDBItem($itemId);
	if ($response){
		echo "{\"reply\":\"SUCCESS\"}";
	}else
		echo "{\"reply\":\"FAILED\"}";
?>