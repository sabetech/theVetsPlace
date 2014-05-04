<?php
	require_once 'vetsplaceAPI.php';
	
	if (isset($_REQUEST['petName'])){
		$petName = $_REQUEST['petName'];
		$vets_Instance = new vetsplaceAPI();
		$searchResults = $vets_Instance->searchPets($petName);
		echo $searchResults;	
	}	
?>
