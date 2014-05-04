<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	$allItems = $vets_Instance->getAllItems();
	
	echo $allItems;
	
?>