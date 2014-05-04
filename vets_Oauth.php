<?php 
	//this is to authenticate the user login and give the user a session to work with
	require_once 'functional/vetsplaceAPI.php';
		$username = $_POST['username'];
		$password = $_POST['pwd'];
		$vets_Instance = new vetsplaceAPI();
		
		if ($vets_Instance->authenticate($username,$password)){
			if ($username == "user"){
				header("location:signUp.php");
			}else{
				session_start();
				
				$userInfo = $vets_Instance->getUserInfo();
				
				$dispUserName = $userInfo->username;
				$dispAccessLevel = $userInfo->accessLevel;
				$userId = $userInfo->userid;
				
				$_SESSION['USER'] = $username;
				//$_SESSION['USERID'] = $userId;
				//$_SESSION['ACCESSLEVEL'] = $dispAccessLevel;
				
				
				
				header("location:index.php?username=$dispUserName&access_level=$dispAccessLevel");
			}
		}else{
			header("location:login.php?error=username and password <br />combination Incorrect!");
		}
?>