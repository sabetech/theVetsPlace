<?php
	require_once 'vetsplaceAPI.php';
	
	$date = $_REQUEST['newItemDate'];
	$vets_Instance = new vetsplaceAPI();
	
	$date = date('Y-m-d',strtotime($date));
	
	$result = $vets_Instance->getNewItems($date);
	echo $result;
?>