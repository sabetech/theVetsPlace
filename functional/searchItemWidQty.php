<?php
	require_once 'vetsplaceAPI.php';
	
	if (isset($_REQUEST['itemName'])){
		$itemName = $_REQUEST['itemName'];
		$vets_Instance = new vetsplaceAPI();
		$searchResults = $vets_Instance->searchItemWidQty($itemName);
		echo $searchResults;
		
	}

?>