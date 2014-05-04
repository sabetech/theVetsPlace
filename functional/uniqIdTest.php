<?php
	$str = "Kofi Mensah-Ansah";
	$uniqueIDTest = uniqid(substr($str,0,2));
	
	
	echo $uniqueIDTest;
?>