<?php
require_once 'vetsplaceAPI.php';
	$petId = $_REQUEST['petId'];
	$pet_Name = $_REQUEST['petName'];
	$animal = $_REQUEST['pet_animal'];
	$breed = $_REQUEST['breed'];
	$origin = $_REQUEST['origin'];
	$petSex = $_REQUEST['petSex'];
	$breeder = $_REQUEST['breeder'];
	$microNo = $_REQUEST['microchp'];
	$ownerId = $_REQUEST['ownerId'];
	
	
	$vets_Instance = new vetsplaceAPI();
	$updateStats = $vets_Instance->updatePet($petId,$pet_Name,$animal,$breed,$origin,$petSex,$breeder,$microNo,$ownerId);
	
	if ($updateStats){
		echo "{\"SUCCESS\":\"Pet details were modified successfully\"}";
	}else{
		echo "{\"ERROR\":\"Error, There was a problem updating Pet Details\"}";
	}
	
?>