<?php
	require_once 'functional/vetsplaceAPI.php';
	$username = $_POST['username'];
	$password = $_POST['pwd'];
	
	$vets_Instance = new vetsplaceAPI();
	
	if ($vets_Instance->signUp($username,$password)){
		session_start();
		$_SESSION['USER'] = $username;
		header("location:index.php?username=$username&access_level=regular");
	}else{
		header("location:signUp.php?error=An error Occured<br />while Signing up");
	}

?>