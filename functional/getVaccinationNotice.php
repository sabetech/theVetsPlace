<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	
	$dueDate = $_REQUEST['dueDate'];
	//echo $dueDate;
	$response = $vets_Instance->getDueVaccinations($dueDate);
	echo $response;
	
?>