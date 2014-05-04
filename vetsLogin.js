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
		/*
		document.captureEvents(Events.KEYPRESS);
		document.captureEvents(Events.KEYUP);
		
		document.onkeypress = enterPressed();
		function enterPressed(e){
			if (e.keycode == 13){
				alert("somphin");
				document.getElementById("enter").click();
			}
		}
		*/
		/*
		document.getElementById('password').onkeypress=function(e){
			if(e.keyCode==13){
				document.getElementById('enter').click();
			}
		}
		*/
	function validate(username,pwd){
		var loginStatus = document.getElementById("loginStatus");
		loginStatus.innerText = "";
		var loading = document.getElementById("loading");;
			
		loading.innerHTML = "<img src = \"images/preloader-w8-cycle-black.gif\" width=\"25\" height=\"25\"/>";
			
		var loginStatus = document.getElementById("loginStatus");
		loginStatus.className = "fg-color-red";
		if ((username == "") || (pwd == "")){
			loginStatus.innerText = "Some Fields are empty!";
			loading.innerHTML = "";
			return false;
		}else
			return true;
							
	}
			