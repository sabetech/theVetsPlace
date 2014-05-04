<?php
	require_once 'vetsplaceAPI.php';
	//sleep(2);
	$petId = $_REQUEST['petId'];
	$vets_Instance = new vetsplaceAPI();
	$petDetail = $vets_Instance->getPetDetail($petId);
	echo $petDetail;
?>