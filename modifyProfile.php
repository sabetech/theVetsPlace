<?php include("header.php")?>

	<title>Login &middot; The Vets Place Management System</title>
	<style type="text/css">
	</style>
		 
	<script type="text/javascript" src="js/vetsLogin.js">
	</script>
	</head>
	
	<body class = "metrouicss" style="background-color:#ccccff" onLoad="$(window).resize()">
			<div class="fitCenter">
				<div class="page-header">
					<div class="page-header-content">
						<div align= "center"><img src="images/logo.png" width = "111" height="114"></div>
						<h1 class="fg-color-green">The Vets Place</h1>
						<div class="fg-color-green" align="right">Client Management System</div>
					</div>
				</div>
				
				<div style="padding:25px"></div>
				
				<!--form -->			<!--Change to POST-->
					<h2 class="fg-color-blue">Modify Login Info</h2>
					
					<div class="bg-color-blueLight" style="padding:20px;">
				
					<label class="fg-color-blue">Current Username</label>
					<!--Username-->
					<div style="padding:2px"></div>
					<div class="input-control text">
						<input id="username" type="text" placeholder="Type your Username here" value='<?php session_start();
																											echo $_SESSION['USER']?>'/>
						<button class="btn-clear"></button>
					</div>
					
					<label class="fg-color-blue">Current Password</label>
					<!--Password-->
					<div style="padding:2px"></div>
					<div class="input-control text">
						<input id="pwd" type="password" placeholder="Type your Password here" />
						<button class="btn-reveal"></button>
					</div>
					<label id="loginStatus" class="fg-color-red"></label>
					<div style="padding:15px"></div>
					
					<div align="right">
						<span id="loading"></span>
						<input id="enter" class="bg-color-green fg-color-white" name="submit" type="button" value="Update" onclick="ajaxFunction(username.value,pwd.value)">
					</div>
					
					<!--/form-->
					
				</div>			
			
			</div>			
			
		
	</body>
</html>