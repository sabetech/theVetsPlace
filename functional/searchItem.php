<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	
	$itemName = $_REQUEST['itemName'];
	
	$searchItemRes = $vets_Instance->searchItem($itemName);
	
	echo $searchItemRes;
?>