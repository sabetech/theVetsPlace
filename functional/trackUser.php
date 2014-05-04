<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	echo date('Y-m-d');
	$action = $_REQUEST['action'];
	$response = $vets_Instance->trackUser($action);
?>