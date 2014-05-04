<?php
	require_once 'vetsplaceAPI.php';
	
	$petName = $_REQUEST['petName'];
	$petAnimal = $_REQUEST['petAnimal'];
	$petBreed = $_REQUEST['petBreed'];
	$petOrigin = $_REQUEST['petOrigin'];
	$petBreeder = $_REQUEST['petBreeder'];
	$sex = $_REQUEST['sex'];
	$microNo = $_REQUEST['microchpNo'];
	$owner = $_REQUEST['owner'];
	
	$vets_Instance = new vetsplaceAPI();
	$reply = $vets_Instance->savePet($petName,$petAnimal,$petBreed,$petOrigin,$petBreeder,$sex,$microNo,$owner);
	
	echo $reply;
?>