<?php
require_once 'vetsplaceAPI.php';
	$clientId = $_REQUEST['clientId'];
	$name = $_REQUEST['name'];
	$address = $_REQUEST['address'];
	$phone = $_REQUEST['phone'];
	$otherPhone = $_REQUEST['otherPhone'];
	$email = $_REQUEST['email'];
	$folderNo = $_REQUEST['folderNo'];
	
	$vets_Instance = new vetsplaceAPI();
	$updateStats = $vets_Instance->updateClient($clientId,$name,$address,$phone,$otherPhone,$email,$folderNo);
	
	if ($updateStats){
		echo "Client details were modified successfully";
	}
	
?>