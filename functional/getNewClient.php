<?php
	require_once 'vetsplaceAPI.php';
	
	$vets_Instance = new vetsplaceAPI();
	
	echo $vets_Instance->getNewClient();
	
?>