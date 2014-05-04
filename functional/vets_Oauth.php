<?php 
	//this is to authenticate the user login and give the user a session to work with
	require_once 'functional/vetsplaceAPI.php';
		$username = mysql_real_escape_string(stripslashes($_POST['username']));
		$password = mysql_real_escape_string(stripslashes($_POST['pwd']));
		$vets_Instance = new vetsplaceAPI();
		
		if ($vets_Instance->authenticate($username,$password)){
			session_start();
			$_SESSION['user'] = $username;
			header("location:index.php");
		}else{
			header("location:login.php");
		}
?>