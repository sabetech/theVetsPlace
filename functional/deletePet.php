<?php
	require_once 'vetsplaceAPI.php';
	
	$petId = $_REQUEST['petId'];
	$petname = $_REQUEST['petName'];
	$owner = $_REQUEST['clientName'];
	
	$vets_Instance = new vetsplaceAPI();
	$result = $vets_Instance->deletePet($petId);
	if ($result){
		echo "Pet $petname belonging to $owner has been deleted successfully.";
	}else{
		echo "For some reason, it could not delete!. mysql server might not be running";
	}
?>