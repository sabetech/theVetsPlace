<?php
	require_once 'vetsplaceAPI.php';
	
	$newAnimal = $_REQUEST['animal'];
	$vets_Instance = new vetsplaceAPI();
	$result = $vets_Instance->addAnimalToDB($newAnimal);
	if ($result){
		echo "New Animal has been added to the database";
	}else{
	
	}
?>