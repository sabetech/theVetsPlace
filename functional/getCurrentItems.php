<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	
	$curntExstinItems = $vets_Instance->getCurExistnItems();
	echo $curntExstinItems;
?>