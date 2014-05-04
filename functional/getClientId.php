<?php
	require_once 'vetsplaceAPI.php';
	
	$clientName = $_REQUEST['clientName'];
	
	$vets_Instance = new vetsplaceAPI();
	
	$clientId = $vets_Instance->getClientId($clientName);
	echo $clientId;
	
?>