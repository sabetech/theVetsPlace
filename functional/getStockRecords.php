<?php
	$from = $_REQUEST['fromdate'];
	$to = $_REQUEST['todate'];
	
	
	$from_str = date('Y-m-d',strtotime($from));
	$to_str = date('Y-m-d',strtotime($to));
	//echo $from_str;
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	
	$stockRecords = $vets_Instance->getStockRecs($from_str,$to_str);
	echo $stockRecords;
?>