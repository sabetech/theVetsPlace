<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	
	$dueDate = $_REQUEST['dueDate'];
	
	$response = $vets_Instance->getDueDeworming($dueDate);
	echo $response;
?>