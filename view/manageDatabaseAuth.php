<div>
				<form >	
					<div class="bg-color-blueLight span4" style="padding:20px;">
					<label><p class="fg-color-red" style='text-align:center;'>Please Note that any action taken here will affect the database the application is running on. Be sure you are fully aware of what you are doing before continuing!<p></label>
					<br />
					<label>Click the proceed button to proceed</label>
					<br />
					<br />
				<!--Change to POST-->
					<!--h2 class="fg-color-blue">Admin Sign In</h2>
					
					
					<label class="fg-color-blue">Admin Username</label>
					<!--Username>
					<div style="padding:2px"></div>
					<div class="input-control text">
						<input name="admin_username" type="text"/>
						<button class="btn-clear"></button>
					</div>
					
					<label class="fg-color-blue">Admin Password</label>
					<!--Password>
					<div style="padding:2px"></div>
					<div class="input-control text">
						<input name="admin_pwd" type="password" />
						<button class="btn-reveal"></button>
					</div>
					<label id="loginStatus" class="fg-color-red"></label>
					<div style="padding:15px"></div-->
					
					<div align="right">
						<span id="loading"></span>
						<input id="enter" class="bg-color-green fg-color-white" name="submit" type="button" value="Proceed>>" onclick="adminSignInAjax()"/>
					</div>
					</div>
					</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#manageDB").click(function(){
			$(currentDiv).fadeOut(200,function(){
				$("#divManageDBAuth").fadeIn(500);
				currentDiv = "#divManageDBAuth";
			});
		});	
	});
	
	function adminSignInAjax(){
		//console.log("yep!");
		/*$.post("functional/signIn",
			  {admin_username:admin_username,
			  admin_pwd:admin_pwd},
			   
			  function(responseText){*/
				if (true){
					$(currentDiv).fadeOut(200,function(){
						$("#divManageDB").fadeIn(500);
						currentDiv = "#divManageDB";
					});
				}
			 /* }
	  )*/
	}
</script>