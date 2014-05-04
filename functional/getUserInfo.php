<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	
	$userInfo = $vets_Instance->getUserInfo();
	
	//echo "{\"username\":\"$userInfo->username\",\"access_level\":\"$userInfo->accessLevel\"}";
?>