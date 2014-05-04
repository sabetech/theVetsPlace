<?php
	require_once 'vetsplaceAPI.php';
	//sleep(2);
	$clientId = $_REQUEST['cliId'];
	$vets_Instance = new vetsplaceAPI();
	$clientDetail = $vets_Instance->getClientDetail($clientId);
	echo $clientDetail;
?>