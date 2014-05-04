<?php
require_once 'vetsplaceAPI.php';
	
	$start = $_REQUEST['start'];
	$end = $_REQUEST['end'];
	
	$vets_Instance = new vetsplaceAPI();
	$clients = $vets_Instance->getClients($start,$end);
	echo $clients;
?>