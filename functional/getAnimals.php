<?php
	require_once 'vetsplaceAPI.php';
	
	$vets_Instance = new vetsplaceAPI();
	$animals = $vets_Instance->getAnimals();
	echo $animals;
?>