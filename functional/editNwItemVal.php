<?php
	require_once 'vetsplaceAPI.php';
	$vets_Instance = new vetsplaceAPI();
	
	$id = $_REQUEST['id'];
	$val = $_REQUEST['val'];
	if ($vets_Instance->editStockVal($id,$val)){
		echo $val;
	}else{
		echo "FAILURE";
	}
?>