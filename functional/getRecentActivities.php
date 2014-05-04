<?php
	
	require_once 'vetsplaceAPI.php';
	
	$searchTxt = $_REQUEST['searchActvs'];
	
	$vets_Instance = new vetsplaceAPI();
	$activities = $vets_Instance->getRecentActivites($searchTxt);
	echo $activities;
?>
