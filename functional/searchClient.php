<?php
	require_once 'vetsplaceAPI.php';
	
	if (isset($_REQUEST['cliName'])){
		$cliName = $_REQUEST['cliName'];
		$vets_Instance = new vetsplaceAPI();
		$searchResults = $vets_Instance->searchClient($cliName);
		echo $searchResults;
		
	}
	
	
?>