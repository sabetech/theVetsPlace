<?php
	require_once 'vetsplaceAPI.php';
	$clientId = $_REQUEST['cliId'];
	$clientName = $_REQUEST['c_name'];
	$vets_Instance = new vetsplaceAPI();
	
	$response = $vets_Instance->deleteClient($clientId);
	if ($response){
		echo "$clientName has been deleted successfully";
	}
?>