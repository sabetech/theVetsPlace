<?php
	require_once 'vetsplaceAPI.php';
	
	$term = $_REQUEST['term'];
	
	$vets_Instance = new vetsplaceAPI();
	
	$result = $vets_Instance->searchAnimal($term);
	echo $result;
	
	
?>