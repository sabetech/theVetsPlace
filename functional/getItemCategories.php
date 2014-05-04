<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();

	$response = $vets_Instance->getItemCategories();
	echo $response;
?>