<?php
	require_once 'vetsplaceAPI.php';
	
	$id = $_REQUEST['id'];
	
	$vets_Instance = new vetsplaceAPI();
	$pets = $vets_Instance->getPetById($id);
	
	echo $pets;
?>