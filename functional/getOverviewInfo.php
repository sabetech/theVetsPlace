<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	$overviewInfo = $vets_Instance->getOverviewInfo();
	echo $overviewInfo;
	
?>