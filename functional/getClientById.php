<?php
	require_once 'vetsplaceAPI.php';
	
	$id = $_REQUEST['id'];
	
	$vets_Instance = new vetsplaceAPI();
	$client = $vets_Instance->getClientById($id);
	
	echo $client;
?>