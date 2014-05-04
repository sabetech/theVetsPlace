$(document).ready(function(){
		
			$(window).resize(function(){
				$('.fitCenter').css({
					position:'absolute',
					left: ($(window).width() - $('.fitCenter').outerWidth())/2
					//top: ($(window).height() - $('.fitCenter').outerHeight())/2
				});
			});
			// To initially run the function:
			$(window).resize();	
		});
	
	function validate(username,pwd,confPwd){
		var loginStatus = document.getElementById("loginStatus");
		loginStatus.innerText = "";
		var loading = document.getElementById("loading");;
			
		loading.innerHTML = "<img src = \"images/preloader-w8-cycle-black.gif\" width=\"25\" height=\"25\"/>";
			
		var loginStatus = document.getElementById("loginStatus");
		loginStatus.className = "fg-color-red";
		if ((username == "") || (pwd == "")||(confPwd == "")){
			loginStatus.innerHTML = "<br /><br />Some Fields are empty!";
			loading.innerHTML = "";
			return false;
		}else if (pwd != confPwd){
			loginStatus.innerHTML = "<br /><br />The passwords are not same!";
			loading.innerHTML = "";
			return false;
		}else{	
			return true;
		}					
	}
