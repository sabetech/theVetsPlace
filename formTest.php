<?php
	
?>

<html>
	<head><title>Form Test Page</title>
	<script type="text/javascript" src="js/assets/jquery-1.8.2.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#runJs").click(function(){
				var petSex = $("input:radio[name='sex']:checked").val();
				alert(petSex+" is the sex");
			});
		});
	</script>
	</head>
	
		<body>
		<form action = "<?php echo $_SERVER["PHP_SELF"];?>"  method="GET">
			<label>Sex:</label>
			<label class="input-control radio " onclick="">
			<input type="radio" name="sex" value="male" checked="">
			<span class="helper">male</span>
			</label>
			<label class="input-control radio" onclick="">
			<input type="radio" name="sex" value="female" checked="">
			<span class="helper">female</span>
			</label>
			
			<button id ="runJs">run js</button>
		</form>
		</body>
</html>