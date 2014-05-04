<?php
	require_once 'vetsplaceAPI.php';
	
	$petId = $_REQUEST['petid'];
	$vets_Instance = new vetsplaceAPI();
	
	$result = $vets_Instance->getDewormDate($petId);
	echo $result;
?>