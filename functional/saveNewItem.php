<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	
	$itemId = $_REQUEST['itemId'];
	$itemDate = $_REQUEST['itemDate'];
	$itemQty = $_REQUEST['itemQty'];
	$itemName = $_REQUEST['itemName'];
	
	$itemDate = date('Y-m-d',strtotime($itemDate));
	
	$saveItemDetails = $vets_Instance->saveNewItem($itemId,$itemDate,$itemQty,$itemName);
	
?>