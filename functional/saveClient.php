<?php
	require_once 'vetsplaceAPI.php';

		$cliName = $_REQUEST['cliName'];
		$cliPhone = $_REQUEST['cliPhone'];
		$otherPhn = $_REQUEST['otherPhn'];
		$cliEmail = $_REQUEST['cliEmail'];
		$cliAddress = $_REQUEST['cliAddress'];
		$cliFolderNum = $_REQUEST['cliFolderNum'];
		$imgLink = $_REQUEST['cliImgLink'];
		
		$vets_Instance = new vetsplaceAPI();
		
		$cliInfo = $vets_Instance->addNewClient($cliName,$cliPhone,$otherPhn,$cliEmail,$cliAddress,$cliFolderNum,$imgLink);
	
?>