<?php include("view/header.php")?>
	<?php
		$failedAuth = $_GET['error'];
	?>
	<title>Login &middot; The Vets Place Management System</title>
	<style type="text/css">
	</style>
		 
	<script type="text/javascript" src="vetsLogin.js">
	</script>
	</head>
	
	<body class = "metrouicss" style="background-color:#ccccff" onLoad="$(window).resize()">
			<div class="fitCenter">
				<div class="page-header">
					<div class="page-header-content">
						<div align= "center"><img src="images/logo.png" width = "111" height="114"></div>
						<h1 class="fg-color-green">The Vets Place</h1>
						<div class="fg-color-green" align="right">Management System</div>
					</div>
				</div>
				
				<div style="padding:25px"></div>
				
				<form action='vets_Oauth.php' onsubmit="return validate(username.value,pwd.value)" method='POST'>			<!--Change to POST-->
					<h2 class="fg-color-blue">Sign In</h2>
					
					<div class="bg-color-blueLight" style="padding:20px;">
				
					<label class="fg-color-blue">Username</label>
					<!--Username-->
					<div style="padding:2px"></div>
					<div class="input-control text">
						<input name="username" type="text" placeholder="Type your Username here"/>
						<button class="btn-clear"></button>
					</div>
					
					<label class="fg-color-blue">Password</label>
					<!--Password-->
					<div style="padding:2px"></div>
					<div class="input-control text">
						<input name="pwd" type="password" placeholder="Type your Password here" />
						<button class="btn-reveal"></button>
					</div>
					<label id="loginStatus" class="fg-color-red"><?php echo $failedAuth;?></label>
					<div style="padding:15px"></div>
					
					<div align="right">
						<span id="loading"></span>
						<input id="enter" class="bg-color-green fg-color-white" name="submit" type="submit" value="Sign In">
					</div>
					
					</form>
					
				</div>			
			
			</div>			
			
		
	</body>
</html>