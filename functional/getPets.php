<?php
	require_once 'vetsplaceAPI.php';
	
	$start = $_REQUEST['start'];
	$end = $_REQUEST['end'];
	
	$vets_Instance = new vetsplaceAPI();
	$pets = $vets_Instance->getPets($start,$end);
	echo $pets;
?>